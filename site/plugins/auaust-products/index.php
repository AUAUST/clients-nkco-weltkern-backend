<?php

use Kirby\Cms\App as Kirby;
use AUAUST\WK1;

// Load the ProductsPage model class
load([
  'ProductsPage' => 'models/ProductsPage.php',
], __DIR__);

Kirby::plugin("auaust/products", [
  'pageModels' => [
    'products' => 'ProductsPage'
  ],
  'options' => [
    'cache' => [
      'wk1' => true
    ]
  ]
]);
