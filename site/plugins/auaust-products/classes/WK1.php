<?php

namespace auaust\products;

use Kirby\Http\Params;
use Kirby\Http\Remote;
use Kirby\Http\Request\Query;
use Kirby\Http\Url;
use Kirby\Toolkit\Str;

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
   * @return array|null The products, or null if the request failed.
   */
  public static function products(int $quantity = null)
  {
    $quantity ??= self::productsQuantity();

    return self::getCustomRoute('products', ['amount' => $quantity]);
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
}