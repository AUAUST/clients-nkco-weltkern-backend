<?php

use Kirby\Cms\Response;
use Kirby\Toolkit\Str;
use auaust\products\WK1;

return [
  [
    'pattern' => ['product', 'product/(:any)'],
    'language' => '*',
    'action'  => function ($lang = null, $id = null) {

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

      // Render the product if it exists
      if ($product) {
        return site()->visit($product, $lang);
      }

      return Response::json([
        "message" => "Not found",
        "searchId" => $id,
      ], 404);
    }
  ],
  [
    'pattern' => 'publishers',
    'language' => '*',
    'action' => function () {
      return json_encode(WK1::publishers(), JSON_PRETTY_PRINT);
    }
  ],
  [
    'pattern' => 'query-weltkern',
    'language' => '*',
    'action' => function () {
      $products = WK1::products();
      $productsPage = page('products');

      $newProducts = [];
      $updatedProducts = [];

      foreach ($products as $index => $product) {

        // if ($index > 5) {
        //   break;
        // }

        // Only extract books, not typefaces nor stationery
        if ($product['categories'][0]['slug'] !== 'books') {
          continue;
        }

        // Get name
        $title = $product['name'];
        // Replace inline <br> with line breaks
        $title = preg_replace('/<br\s*\/?>/', '|', $title);
        // Remove all other HTML tags
        $title = Str::unhtml($title);
        // Deduplicate spaces, and remove spaces next to a pipe
        $title = preg_replace('/\s+/', ' ', $title);
        $title = preg_replace('/\s*\|\s*/', '|', $title);

        $slug = Str::slug($title);
        $content = [
          'title' => $title,

          'oldWeltkern' => [
            'title' => $product['name'],

            'slug' => $product['slug'],

            'id' => $product['id'],

            'isbn' => (function () use ($product) {
              foreach ($product['header'][0]['header']['block_option'] as $option) {
                if ($option['option'] === 'ISBN') {
                  return $option['value'];
                }
              }
              return 'NO ISBN';
            })(),

            'price' => $product['price'],

            'weight' => $product['weight'],

            'author' => (function () use ($product) {
              $author = $product['header'][0]['header']['author_information']['author'];
              return [
                'name' => $author['name'],
                'id' => $author['term_id'],
              ];
            })(),

            'description' => $product['short_description'],

            'details' => (function () use ($product) {
              $details = '';

              foreach ($product['header'][0]['header']['block_option'] as $option) {
                $details .= '- ' . $option['option'] . ': ' . $option['value'] . PHP_EOL;
              }

              return $details;
            })(),

            'gallery' => (array_map(
              function ($image) {
                return [
                  'url' => $image['url'],
                  'id' => $image['id'],
                ];
              },
              $product['gallery_image']
            )),

            'tags' => (array_map(
              function ($tag) {
                return [
                  'name' => $tag['name'],
                  'id' => $tag['id'],
                ];
              },
              $product['tags']
            )
            )
          ]
        ];

        if ($productPage = $productsPage->draft($slug)) {
          $productPage = $productPage->update($content);
          $updatedProducts[] = $productPage;
        } else {
          $productPage = $productsPage->createChild([
            'slug' => $slug,

            'template' => 'product_book',
            'content' => $content
          ]);
          $newProducts[] = $productPage;
        }
      }

      $returnString = '';

      foreach ($newProducts as $product) {
        $returnString .= '<pre style="color:green">ADD: ' . $product->title() . ' (' . $product->slug() . ')</pre>';
      }

      foreach ($updatedProducts as $product) {
        $returnString .= '<pre style="color:orange">UPDATE: ' . $product->title() . ' (' . $product->slug() . ')</pre>';
      }

      return $returnString;
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
  [
    'pattern' => 'migration',
    'language' => '*',
    'action' => function () {
      $productsPage = page('products');
      $products = $productsPage->drafts();

      $contents = [];

      function getIsbn($isbn)
      {
        $isbn = trim($isbn);

        // Ignore missing ISBNs
        if ($isbn === 'NO ISBN') {
          return false;
        }


        // If there's both the ISBN 10 and 13, we split the slash and keep the 13
        // Necessary because lot of the stored ISBNs are in the "2955701072 / 978-2-955-70107-2" format
        if (
          $isbn13 = explode('/', $isbn)[1] ?? false
        ) {
          $isbn = $isbn13;
        }

        // Remove all non-digit characters from the ISBN
        // Trims and removes dashes at the same time
        $isbn = preg_replace('/\D/', '', $isbn);


        // If the ISBN has 10 digits, convert it to 13
        if (strlen($isbn) === 10) {
          $isbn = '978' . $isbn;
        }

        // If the ISBN yet doesn't have 13 digits, we ignore it
        if (strlen($isbn) !== 13) {
          return false;
        }

        return $isbn;
      }

      foreach ($products as $product) {
        $oldWeltkern = $product->oldWeltkern()->toObject();
        $content = [];

        $isbn = getIsbn($oldWeltkern->isbn()->toString());

        // Get and parse ISBN
        $rawIsbn = $oldWeltkern->isbn()->toString();
      }

      $contents[] = $content;


      return dump($contents, false);
    }
  ],
];
