<?php

use Kirby\Cms\App as Kirby;
use Kirby\Filesystem\F;

// Load the ProductsPage model class
load([
  'ProductsPage' => 'models/ProductsPage.php',
  'ProductPage' => 'models/ProductPage.php',
  'BookPage' => 'models/BookPage.php',
], __DIR__);

F::loadClasses([
  'AUAUST\\products\\WK1' => 'classes/WK1.php',
], __DIR__);

Kirby::plugin("auaust/products", [
  'pageModels' => [
    'products' => 'ProductsPage',
    'product_book' => 'BookPage'
  ],
  'options' => [
    'cache' => [
      // Stores the raw responses from the WK1 API to limit the number of requests and gain speed
      'wk1-rawresponses' => true,
      // Stores the processed data from the WK1 API for use in the code
      'wk1' => true
    ]
  ],
  'pageMethods' => [
    /**
     * Returns the UUID of a page, with the page:// prefix removed
     */
    'simpleUuid' => function () {
      return $this->uuid()->id();
    }
  ],
  'collectionMethods' => [
    'toData' => function () {
      return $this->pluck('toData');
    },
    'toAlgoliaData' => function () {
      return $this->pluck('toAlgoliaData');
    }
  ]
]);
