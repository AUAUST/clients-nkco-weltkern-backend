<?php

namespace AUAUST;

use Kirby\Http\Remote;

// Weltkern 1.0
class WK1
{
  public static function productsQuantity()
  {
    $kirby = kirby();

    // Try to get the quantity from the cache to not wait WK-time
    $cache = $kirby->cache('auaust.products.wk1');

    if (($cachedQuantity = $cache->get('quantity')) !== null) {
      return $cachedQuantity;
    }

    // Get the quantity from the API
    $response = Remote::get("https://api.weltkern.com/wp-json/custom-routes/v1/products/total");

    if ($response->code() !== 200) {
      return 0;
    }

    // API response is a string of a number
    $quantity = intval($response->content());

    // Make sure the quantity is not negative, we never know
    $quantity = $quantity > 0 ? $quantity : 0;

    // Cache the quantity
    $cache->set('quantity', "$quantity");

    return $quantity;
  }
}
