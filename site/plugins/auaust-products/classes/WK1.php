<?php

namespace AUAUST;

use Kirby\Http\Remote;

// Weltkern 1.0
class WK1
{
  public static function productsQuantity()
  {
    $kirby = kirby();

    $cache = $kirby->cache('auaust.products.wk1');

    $response = Remote::get("https://api.weltkern.com/wp-json/custom-routes/v1/products/total");

    if ($response->code() !== 200) {
      return 0;
    }

    $quantity = intval($response->content());

    $quantity = $quantity > 0 ? $quantity : 0;

    $cache->set('quantity', $quantity);

    return $quantity;
  }
}
