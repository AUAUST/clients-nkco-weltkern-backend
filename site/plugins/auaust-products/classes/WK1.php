<?php

namespace auaust\products;

use Kirby\Http\Remote;
use Kirby\Http\Request\Query;
use Kirby\Http\Url;
use Kirby\Toolkit\Str;
use Kirby\Data\Yaml;

// Weltkern 1.0
class WK1
{
  /**
   * Returns the response content from any given WK1 endpoint.
   *
   * @param string|array $endpoint The endpoint to fetch from. Should not include the base URL.
   * @param array $parameters The parameters to pass to the endpoint.
   * @param bool $parseJson Whether to parse the response as JSON or not.
   * @param int $minutes The amount of minutes to cache the response for.
   * @return array|string|null The response, as parsed JSON or not, or null if the request failed or the JSON was invalid.
   */
  private static function remoteGet(string|array $endpoint, array $parameters = null, bool $parseJson = false, int $minutes = 30)
  {
    // Try to get the data from the cache to not wait WK-time
    $cache = kirby()->cache('auaust.products.wk1-rawresponses');

    // If endpoint is an array, join it with slashes
    if (is_array($endpoint)) {

      // sanitize each endpoint part
      $endpoint = array_map(
        function ($part) {
          // remove slashes from the beginning and end
          return Url::path($part, false, false);
        },
        $endpoint
      );

      $endpoint = implode('/', $endpoint);
    }

    $endpoint = Url::path($endpoint, false);

    // ["amount" => 10] -> "amount=10"
    $parameters = (new Query($parameters))->toString();

    $url = 'https://api.weltkern.com/'
      . $endpoint
      . ($parameters
        ? "?$parameters"
        : '');

    // https://api.weltkern.com/my/endpoint?amount=10 -> api.weltkern.com/my/endpoint?amount=10
    $cacheKey = Str::after($url, '://');

    // Return cached data if available
    if (($cachedData = $cache->get($cacheKey)) !== null) {
      return $cachedData;
    }

    try { // Get the data from the API
      $response = Remote::get($url, [
        // Disables the timeout to prevent WK-time from causing errors
        'timeout' => 0,
      ]);
    } catch (\Throwable $th) {
      return null;
    }

    // Return null before caching if the request failed
    if ($response->code() !== 200) {
      return null;
    }

    // Parse the response as JSON if requested
    $data = $parseJson ?
      json_decode($response->content(), true) :
      $response->content();

    // Cache the data for next time
    $cache->set($cacheKey, $data, $minutes);

    return $data;
  }

  /**
   * Returns the parsed JSON response from a given custom endpoint.
   *
   * @param string $endpoint The endpoint to fetch from. Should not include the base URL, nor "/wp-json/custom-routes/v1/".
   * @param array $parameters The parameters to pass to the endpoint.
   * @return array|null The parsed JSON response, or null if the request failed.
   */
  private static function getCustomRoute(string $endpoint, array $parameters = null)
  {
    return self::remoteGet(
      ["/wp-json/custom-routes/v1/", $endpoint],
      $parameters,
      true
    );
  }

  /**
   * Returns the total amount of products from WordPress.
   *
   * @return int The total amount of products. 0 means the request failed.
   */
  public static function productsQuantity()
  {
    $data = self::getCustomRoute('products/total');

    if ($data === null) {
      return 0;
    }

    return intval($data);
  }

  /**
   * Returns the products from WordPress.
   *
   * @param int|null $quantity The amount of products to fetch. If null, all products will be fetched.
   * @param string|null $category The category to filter the products by. If null, all products will be fetched.
   * @return array|null The products, or null if the request failed.
   */
  public static function products(int $quantity = null, string $category = null)
  {
    // $quantity as 0 or less means all products
    if ($quantity < 1) {
      $quantity = null;
    }

    $quantity ??= self::productsQuantity();

    $products = self::getCustomRoute('products', ['amount' => $quantity]);

    if ($category) {
      $products = array_filter(
        $products,
        function ($product) use ($category) {
          return $product['categories'][0]['slug'] === $category;
        }
      );
    }

    return $products;
  }

  /**
   * Returns the media from WordPress.
   *
   * @param int|string|null $id The ID of the media to fetch.
   * @return array|null The media data, or null if the request failed.
   */
  public static function getMediaById(int|string $id = null)
  {
    if ($id === null) {
      return null;
    }

    try {
      $data = self::remoteGet(
        ["/wp-json/wp/v2/media/", $id],
        null,
        true,
        10080
      );
    } catch (\Throwable $th) {
      return null;
    }

    return $data;
  }

  /**
   * Returns the image from WordPress, as file contents or URLs.
   *
   * @param int|string|null $id The ID of the image to fetch.
   * @param bool $fetch Whether to fetch the image or not. If false, the image URL will be returned.
   * @return mixed|null The image URL or file contents depending on $fetch, or null if the request failed.
   */
  public static function getImageById(int|string $id = null, bool $fetch = false)
  {
    $cache = kirby()->cache('auaust.products.wk1');

    $cachedImages = $cache->get('medias', []);

    if (array_key_exists($id, $cachedImages)) {
      return $fetch ? self::remoteGet($cachedImages[$id]) : $cachedImages[$id];
    }

    $data = self::getMediaById($id);

    if ($data === null) {
      return null;
    }

    $url = $data['media_details']['sizes']['full']['source_url'];

    $cachedImages[$id] = $url;

    $cache->set('medias', $cachedImages, 0);

    if ($fetch) {
      return self::remoteGet($url);
    }

    return $url;
  }

  /**
   * Returns the images from WordPress, as file contents or URLs, for each ID in the array.
   *
   * @param array $ids The IDs of the images to fetch.
   * @param bool $fetch Whether to fetch the images or not. If false, the images URLs will be returned.
   * @return array|null The images URLs or file contents depending on $fetch, or null if the request failed.
   */
  public static function getImagesByIds(array $ids, bool $fetch = false)
  {
    $cache = kirby()->cache('auaust.products.wk1');

    $cachedImages = $cache->get('wk-all-images', []);

    $images = [];

    foreach ($ids as $id) {

      // If the image is already cached, use it
      if (array_key_exists($id, $cachedImages)) {
        $images[$id] =
          $fetch
          ? self::remoteGet($cachedImages[$id])
          : $cachedImages[$id];

        continue;
      }

      // Otherwise, fetch it and cache it

      try {
        $data = self::getMediaById($id);
      } catch (\Throwable $th) {
        continue;
      }

      if ($data === null) {
        $cachedImages[$id] = false;
        continue;
      }

      $url = $data['media_details']['sizes']['full']['source_url'];

      $cachedImages[$id] = $url;

      $cache->set('wk-all-images', $cachedImages, 10080);

      $images[$id] = $fetch ? self::remoteGet($url) : $url;
    }


    return $images;
  }

  public static function publishers()
  {
    $products = self::products();

    if ($products === null) {
      return null;
    }

    $publishers = [];

    foreach ($products as $product) {

      // Only consider books
      if ($product['categories'][0]['slug'] !== 'books') {
        continue;
      }

      // Messy because WK1's API is messy
      $publisher = $product["header"][0]["header"]["author_information"]["author"]["name"] ?? "N/A";

      if (!array_key_exists($publisher, $publishers)) {
        $publishers[$publisher] = 1;
      } else {
        $publishers[$publisher]++;
      }
    }

    return $publishers;
  }

  /**
   * Takes a raw ISBN, accepting a few formats, and returns a 13-digit ISBN when possible.
   * False if no valid ISBN was found.
   *
   * @param string $isbn The ISBN to parse.
   * @return string|false The parsed ISBN, or false if no valid ISBN was found.
   */
  public static function fixIsbn(string $isbn)
  {
    $isbn = trim($isbn);

    // Ignore missing ISBNs
    if ($isbn === 'NO ISBN') {
      return false;
    }

    // If there's both the ISBN 10 and 13, we split the slash and keep the 13
    // Necessary because lot of the stored ISBNs are in the "2955701072 / 978-2-955-70107-2" format
    if (
      $isbn13 = explode('/', $isbn)[1] ?? false
    ) {
      $isbn = $isbn13;
    }

    // Remove all non-digit characters from the ISBN
    // Trims and removes dashes at the same time
    $isbn = preg_replace('/\D/', '', $isbn);


    // If the ISBN has 10 digits, convert it to 13
    if (strlen($isbn) === 10) {
      $isbn = '978' . $isbn;
    }

    // If the ISBN yet doesn't have 13 digits, we ignore it
    if (strlen($isbn) !== 13) {
      return false;
    }

    // If the ISBN doesn't start with 978 or 979, we ignore it
    if (
      !Str::startsWith($isbn, '978') &&
      !Str::startsWith($isbn, '979')
    ) {
      return false;
    }

    // Checksum calculation
    $sum = 0;
    for ($i = 0; $i < 12; $i++) {
      $sum += $isbn[$i] * (($i % 2) ? 3 : 1);
    }
    $check = (10 - ($sum % 10)) % 10;

    // If the checksum is wrong, we ignore it
    if ($isbn[12] != $check) {
      return false;
    }

    return $isbn;
  }

  /**
   * Takes WK1's "details" field formatted as YAML, and tries to extract the dimensions from it.
   *
   * @param string $yaml The YAML to parse.
   * @return array|null The dimensions, or null if none were found.
   */
  public static function fixDimensions(string $yaml)
  {
    $data = Yaml::decode($yaml);

    $dimensionsString = null;

    // $data is an array of arrays, each being a single key-value pair
    foreach ($data as $pair) {
      if (array_keys($pair)[0] === 'Size') {
        $dimensionsString = $pair['Size'];
        break;
      }
    }

    // The product has no dimensions field
    if ($dimensionsString === null) {
      return null;
    }

    // Some exceptions are present in the data that aren't worth handling
    // We just ignore them, and return -1 for each dimension instead
    try {
      $splitDimensions = explode('×', $dimensionsString);

      return [
        // as decimal
        'x' => floatval($splitDimensions[0]),
        'y' => floatval($splitDimensions[1]),
        'z' => floatval($splitDimensions[2] ?? -1),
      ];
    } catch (\Throwable $th) {
      return [
        'x' => -1,
        'y' => -1,
        'z' => -1,
      ];
    }
  }
}
