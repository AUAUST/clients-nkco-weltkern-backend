<?php

use auaust\products\WK1;

$products = WK1::products(100);
$quantity = WK1::productsQuantity();

// foreach ($products as $product) {
//   echo $product['name'] . '<br>';
// }


echo html(json_encode($products));
