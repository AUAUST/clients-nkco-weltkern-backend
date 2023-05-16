<?php

namespace AUAUST;

use Kirby\Http\Remote;

// Weltkern 1.0
class WK1
{
  // WK1::productsQuantity() return 0, always.
  public static function productsQuantity()
  {
    $response = Remote::get("https://api.weltkern.com/wp-json/custom-routes/v1/products/total");

    if ($response->code() !== 200) {
      return 0;
    }

    $quantity = intval($response->content());

    if ($quantity < 0) {
      return 0;
    }

    return $quantity;
  }
}
