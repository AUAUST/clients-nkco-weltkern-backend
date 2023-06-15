<?php

use Kirby\Cms\Response;

$getProducts = function (int|null $count = null) {
  if ($count === null) {
    $max = -1;
  } else if ($count <= 0) {
    return Response::json([
      'status' => 'error',
      'message' => 'Count must be a positive integer or \'all\'',
    ], 400);
  } else {
    $max = $count;
  }

  // TODO: drafts() -> children()
  $products = page('products')->drafts()->limit($max);

  return Response::json(
    [
      'status' => 'ok',
      'data' => [
        'count' => $products->count(),
        'products' => $products->pluck('slug'),
      ]
    ],
    200
  );
};

return [
  [
    'pattern' => ['products', 'products/all'],
    'language' => '*',
    'action' => function () use ($getProducts) {
      return $getProducts();
    }
  ], [
    'pattern' => 'products/(:num)',
    'language' => '*',
    'action' => function ($lang = null, int $count = -1) use ($getProducts) {
      return $getProducts($count);
    }
  ]
];
