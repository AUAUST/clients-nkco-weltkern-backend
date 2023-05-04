<?php

use Kirby\Cms\Response;

return [
  [
    'pattern' => ['product', 'product/(:any)'],
    'action'  => function ($id = null) {

      $productsPage = page('products');
      $product = $productsPage->children()->find($id);

      if ($product) {
        $result = [
          "success" => true,
          "product" => [
            "name" => $product->title()->toString(),
          ]
        ];
      } else {
        $result = [
          "success" => false,
        ];
      }

      return Response::json($result, null, true);
    }
  ],
];
