<?php

use Kirby\Cms\Response;
use Kirby\Toolkit\Str;
use AUAUST\Products\WK1;
use Kirby\Data\Yaml;


use AUAUST\Users\Database;
use AUAUST\Users\Dbuser;
use Kirby\Cms\User;

/**
 * Set all the API routes
 * Routes are defined in their own files. Some files includes multiple routes, some only one.
 * This is the reason why some are spreaded with `...(require_once)`, and some are not.
 *
 * @see https://getkirby.com/docs/guide/routing#routes
 */
return [
  [
    'pattern' => ['', 'home'],
    'language' => '*',
    'action' => function () {

      try {

        Database::singleton()->execRaw('DELETE FROM users WHERE id = "via-sql";');


        $result = Database::singleton()->exec('insert-user', [
          ['id', 'via-sql', SQLITE3_TEXT],
          ['password', User::hashPassword('testtest'), SQLITE3_TEXT],
          ['email', 'via-sql@accounts.test', SQLITE3_TEXT],
          ['name', 'Test User via SQL', SQLITE3_TEXT],
          ['language', 'en', SQLITE3_TEXT],
          ['role', Dbuser::USER_ROLE, SQLITE3_TEXT],
          ['example', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', SQLITE3_TEXT],
        ]);

        return dump($result, false);
      } catch (\Throwable $th) {
        return dump([
          'message' => $th->getMessage(),
          'code' => $th->getCode(),
          'line' => $th->getLine(),
          'file' => $th->getFile(),
          'trace' => $th->getTrace(),

        ], false);
      }




      // return Response::json([
      //   'status' => 'ok',
      //   'data' => [
      //     'message' => 'Welcome to the Weltkern API',
      //     'version' => '1.0.0',
      //   ]
      // ], 200);
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
      return Response::json([
        'status' => 'error',
        'message' => 'This endpoint does not exist',
      ], 404);
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
