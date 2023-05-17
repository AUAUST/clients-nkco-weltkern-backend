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
  private static function askWeltkern(string $endpoint, array $parameters)
  {
    $kirby = kirby();

    // Try to get the data from the cache to not wait WK-time
    $cache = $kirby->cache('auaust.products.wk1');

    $parameters = (new Query($parameters))->toString();

    $url = "https://api.weltkern.com/wp-json/custom-routes/v1/$endpoint?$parameters";

    $cacheKey = Str::slug($url);

    if (($cachedData = $cache->get($cacheKey)) !== null) {
      return $cachedData;
    }

    // Get the data from the API
    $response = Remote::get($url, [
      'timeout' => 0,
    ]);

    if ($response->code() !== 200) {
      return null;
    }

    // API response is a JSON string
    $data = json_decode($response->content(), true);

    // Cache the data
    $cache->set($cacheKey, $data);

    return $data;
  }
  public static function getResponseContent(string $url)
  {
    // Urls can either be (https://)api.weltkern.com/end/of/the/url or end/of/the/url
    $url = Url::path($url);
    $url = "https://api.weltkern.com/$url";

    $response = Remote::get($url);

    if ($response->code() !== 200) {
      return null;
    }

    return $response->content();
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
