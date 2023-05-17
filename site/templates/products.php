<?php

use auaust\products\WK1;
use Kirby\Cms\Url;

// $products = WK1::products();
// $quantity = WK1::productsQuantity();

// foreach ($products as $product) {
//   echo $product['name'] . '<br>';
// }


dump([
  "url" => $url = "https://www.auaust.com/products",
  "path" => Url::path($url, false),
  "reUrl" => $url = "/products",
  "rePath" => Url::path($url, false),
]);
