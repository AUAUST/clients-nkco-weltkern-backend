<?php

use AUAUST\Headless\Response;

/**
 * Set all the API routes
 * Routes are defined in their own files. Some files includes multiple routes, some only one.
 * This is the reason why some are spreaded with `...(require_once)`, and some are not.
 *
 * @see https://getkirby.com/docs/guide/routing#routes
 */
return [
  [
    'pattern' => 'ping',
    'language' => '*',
    'action' => function () {
      return Response::success(
        'Pong',
        [
          'time' => microtime(true),
        ]
      );
    }
  ],
  [
    'pattern' => ['', 'home'],
    'language' => '*',
    'action' => function () {
      return kirby()->site()->callbackTemplate();
      return Response::success(
        'Welcome to the Weltkern API',
        [
          'version' => '1.0.0',
        ]
      );
    }
  ],
  require_once __DIR__ . '/search.php',
  require_once __DIR__ . '/product.php',
  require_once __DIR__ . '/products.php',
  require_once __DIR__ . '/publishers.php',
  require_once __DIR__ . '/option.php',
  ...(require_once __DIR__ . '/weltkern-migration.php'),
  [
    'pattern' => '(:all)',
    'language' => '*',
    'action' => function () {
      return Response::notFound(
        'This endpoint does not exist'
      );
    }
  ],
  [
    'pattern' => 'isbn',
    'language' => '*',
    'action' => function () {
      $productsPage = page('products');
      $products = $productsPage->drafts();

      $isbns = '';
      $noisbn = 0;

      foreach ($products as $product) {

        $isbn = $product->oldWeltkern()->toObject()->isbn()->toString();

        if ($isbn === 'NO ISBN') {
          $noisbn++;
          continue;
        }

        $isbns .= $isbn . '<br>';
      }

      return 'Missing ISBN: ' . $noisbn . '<br><br>' . $isbns;
    }
  ],
];
