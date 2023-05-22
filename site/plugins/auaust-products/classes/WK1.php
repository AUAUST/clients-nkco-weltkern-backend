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
  private static function remoteGet(string|array $endpoint, array $parameters = null, bool $parseJson = false)
  {
    // Try to get the data from the cache to not wait WK-time
    $cache = kirby()->cache('auaust.products.wk1');

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

    $url = "https://api.weltkern.com/$endpoint?$parameters";

    // https://api.weltkern.com/my/endpoint?amount=10 -> https-api-weltkern-com-my-endpoint-amount-10
    $cacheKey = Str::slug($url);

    // Return cached data if available
    if (($cachedData = $cache->get($cacheKey)) !== null) {
      return $cachedData;
    }

    // Get the data from the API
    $response = Remote::get($url, [
      // Disables the timeout to prevent WK-time from causing errors
      'timeout' => 0,
    ]);

    // Return null before caching if the request failed
    if ($response->code() !== 200) {
      return null;
    }

    // Parse the response as JSON if requested
    $data = $parseJson ?
      json_decode($response->content(), true) :
      $response->content();

    // Cache the data for next time
    $cache->set($cacheKey, $data, 60);

    return $data;
  }

  private static function getCustomRoute(string $endpoint, array $parameters)
  {
    return self::remoteGet(
      ["/wp-json/custom-routes/v1/", $endpoint],
      $parameters,
      true
    );
  }

  public static function productsQuantity()
  {
    $data = self::getCustomRoute('products/total', []);

    if ($data === null) {
      return 0;
    }

    return intval($data);
  }

  public static function products(int $quantity = null)
  {
    $quantity ??= self::productsQuantity();

    return self::getCustomRoute('products', ['amount' => $quantity]);
  }

  }
}
