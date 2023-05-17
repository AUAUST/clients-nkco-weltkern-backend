<?php

use Kirby\Cms\App as Kirby;
use Kirby\Filesystem\F;

// Load the ProductsPage model class
load([
  'ProductsPage' => 'models/ProductsPage.php',
], __DIR__);

F::loadClasses([
  'auaust\\products\\WK1' => 'classes/WK1.php'
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
