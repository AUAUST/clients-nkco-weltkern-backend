<?php

use Kirby\Cms\Response;

return [
  'pattern' => ['product', 'product/(:any)'],
  'language' => '*',
  'action'  => function ($lang = null, $id = null) {
    // Get the products
    $products = page('products')->drafts();

    if (
      // If the string doesn't match the UUID format
      !(preg_match('/^[a-zA-Z0-9]{16}$/', $id))
      // or the string doesn't match an existing product's UUID
      || !($product = $products->find("page://" . $id))
    ) {
      // we try to find the product by its slug as the input string is most likely a slug
      $product = $products->find($id);
    }

    // Render the product if it exists
    if ($product) {
      return dump($product, false);
      return site()->visit($product, $lang);
    }

    return Response::json([
      "message" => "Not found",
      "searchId" => $id,
    ], 404);
  }
];
