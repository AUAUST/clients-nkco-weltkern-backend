<?php

use Kirby\Cms\Response;

return [
  [
    'pattern' => ['product', 'product/(:any)'],
    'action'  => function ($id = null) {

      // Get the products
      $products = page('products')->children();

      // Try to find the product by id if it matches the UUID format
      if (preg_match('/^[a-zA-Z0-9]{16}$/', $id)) {
        $product = $products->find("page://" . $id);
      }

      // Try to find the product by slug
      else {
        $product = $products->find($id);
      }

      // Define the result as the product if it exists
      if ($product) {
        $result = [
          "success" => true,
          "product" => [
            "id" => array_reverse(explode("/", $product->id())),
            "uuid" => $product->uuid()->model()->content()->uuid()->toString(),
            "name" => $product->title()->toString(),
            "author" => $product->author()->toString(),
          ]
        ];
      }

      // Define the result as not found if the product doesn't exist
      else {
        $result = [
          "success" => false,
        ];
      }

      // Return the result as JSON
      return Response::json($result, null, true);
    }
  ],
];
