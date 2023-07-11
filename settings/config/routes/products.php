<?php

use AUAUST\Headless\Response;

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
        return Response::invalidRequest(
          'Count must be a positive integer or "all"'
        );
      }
    }

    // TODO: drafts() -> children()
    $products = page('products')->drafts()->limit($max);

    return Response::success(
      null,
      [
        'count' => $products->count(),
        'products' => $products->toData(),
      ],
    );
  }
];
