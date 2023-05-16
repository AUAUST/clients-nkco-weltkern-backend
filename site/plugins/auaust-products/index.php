<?php

use Kirby\Cms\App as Kirby;

// Load the ProductsPage model class
load([
  'ProductsPage' => 'models/ProductsPage.php',
], __DIR__);

Kirby::plugin("auaust/products", [
  'pageModels' => [
    'products' => 'ProductsPage'
  ],
]);
