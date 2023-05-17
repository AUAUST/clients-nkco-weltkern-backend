<?php

namespace auaust\products;

use Kirby\Http\Remote;

// Weltkern 1.0
class WK1
{
  private static function askWeltkern(string $endpoint, array $parameters)
  {
    $kirby = kirby();

    // Try to get the data from the cache to not wait WK-time
    $cache = $kirby->cache('auaust.products.wk1');

    if (($cachedData = $cache->get($endpoint)) !== null) {
      return $cachedData;
    }

    // Get the data from the API
    $response = Remote::get("https://api.weltkern.com/wp-json/custom-routes/v1/$endpoint", [
      'query' => $parameters,
      'timeout' => 0,
    ]);

    if ($response->code() !== 200) {
      return null;
    }

    // API response is a JSON string
    $data = json_decode($response->content(), true);

    // Cache the data
    $cache->set($endpoint, $data);

    return $data;
  }

  public static function productsQuantity()
  {
    $data = self::askWeltkern('products/total', []);

    if ($data === null) {
      return 0;
    }

    return intval($data);
  }

  public static function products(int $quantity = null)
  {
    $quantity ??= self::productsQuantity();

    return self::askWeltkern('products', ['amount' => $quantity]);
  }
}
