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



      foreach ($products as $product) {
        $oldWeltkern = $product->oldWeltkern()->toObject();

        try {
          $contents[] = [
            'isbn' => WK1::fixIsbn($oldWeltkern->isbn()),
            'dimensions' => WK1::fixDimensions($oldWeltkern->details()),
          ];
        } catch (Exception $e) {
          $contents[] = 'Errored: ' . $e->getMessage() . ' (' . $e->getFile() . ', ' . $e->getLine() . ')';
        }
      }



      return dump($contents, false);
    }
  ],
];
