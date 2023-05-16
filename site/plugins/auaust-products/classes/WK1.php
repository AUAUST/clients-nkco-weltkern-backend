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

  public static function products(int $quantity = null)
  {
    $kirby = kirby();

    // Try to get the products from the cache to not wait WK-time
    $cache = $kirby->cache('auaust.products.wk1');

    $quantity ??= self::productsQuantity();

    if (($cachedProducts = $cache->get('products')) !== null) {
      return $cachedProducts;
    }

    // Get the products from the API
    $response = Remote::get("https://api.weltkern.com/wp-json/custom-routes/v1/products?amount=$quantity");

    if ($response->code() !== 200) {
      return [];
    }

    // API response is a JSON string
    $products = json_decode($response->content(), true);

    // Cache the products
    $cache->set('products', $products);

    return $products;
  }
}
