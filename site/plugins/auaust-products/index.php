<?php

use Kirby\Cms\App as Kirby;
use Kirby\Filesystem\F;

// Load the ProductsPage model class
load([
  'ProductsPage' => 'models/ProductsPage.php',
  'ProductBookPage' => 'models/ProductBookPage.php'
], __DIR__);

F::loadClasses([
  'auaust\\products\\WK1' => 'classes/WK1.php'
], __DIR__);

Kirby::plugin("auaust/products", [
  'pageModels' => [
    'products' => 'ProductsPage',
    'product_book' => 'ProductBookPage'
  ],
  'options' => [
    'cache' => [
      // Stores the raw responses from the WK1 API to limit the number of requests and gain speed
      'wk1-rawresponses' => true,
      // Stores the processed data from the WK1 API for use in the code
      'wk1' => true
    ]
  ]
]);
