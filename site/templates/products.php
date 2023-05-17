<?php

use auaust\products\WK1;

$products = WK1::products();
$quantity = WK1::productsQuantity();

foreach ($products as $product) {
  echo $product['name'] . '<br>';
}
