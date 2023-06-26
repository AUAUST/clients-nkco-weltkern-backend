<?php

use Kirby\Cms\Response;

return [
  'pattern' => [
    'products',
    'products/(:alphanum)'
  ],
  'language' => '*',
  'action' => function ($lang = null, int|string $count = 'all') {

    if ($count === 'all') {

      $max = -1;
    } else {

      $max = intval($count);

      if ($max < 1) {
        return Response::json([
          'status' => 'error',
          'message' => 'Count must be a positive integer or "all"',
        ], 400);
      }
    }

    // TODO: drafts() -> children()
    $products = page('products')->drafts()->limit($max);

    return Response::json(
      [
        'status' => 'ok',
        'data' => [
          'count' => $products->count(),
          'products' => $products->pluck('toData'),
        ]
      ],
      200
    );
  }
];
