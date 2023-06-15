<?php

use Kirby\Cms\Response;

return [
  'pattern' => ['products', 'products/(:any)'],
  'language' => '*',
  'action' => function ($lang = null, string|int $count = 'all') {

    if ($count === 'all') {
      $max = -1;
    } else if (is_int($count) && $count > 0) {
      $max = intval($count);
    } else {
      return Response::json([
        "message" => "Count must be a positive integer or 'all'",
      ], 400);
    }

    $products = page('products')->children()->limit($max);

    return Response::json(
      $products->pluck('slug'),
      200
    );
  }
];
