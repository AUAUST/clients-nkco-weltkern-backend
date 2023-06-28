<?php

use Kirby\Cms\Response;
use Kirby\Toolkit\Str;
use Kirby\Cms\Page;

use AUAUST\products\WK1;

return [
  // Get the latest, unimported products from WK1
  [
    'pattern' => 'sync-weltkern',
    'language' => '*',
    'action' => function () {
      $kirby = kirby();
      $site  = $kirby->site();


      /**
       * @var Page $productsPage
       */
      $productsPage = $site->pageProducts()->toPage();

      $remoteProducts = WK1::products();

      $existingProducts    = $productsPage->childrenAndDrafts();
      $existingProductsIds = $existingProducts->values(
        function ($product) {
          return $product->oldWeltkern()->toObject()->id()->toString();
        }
      );

      $newProducts = [];
      $updatedProducts = [];

      foreach ($remoteProducts as $product) {

        // Only extract books, not typefaces nor stationery
        if ($product['categories'][0]['slug'] !== 'books') {
          continue;
        }

        // Skip if product already exists
        if (in_array($product['id'], $existingProductsIds)) {
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
            'title'  => $product['name'],
            'slug'   => $product['slug'],
            'id'     => $product['id'],

            'price'  => $product['price'],

            'isbn'   => (function ($product) {
              foreach ($product['header'][0]['header']['block_option'] as $option) {
                if ($option['option'] === 'ISBN') {
                  return $option['value'];
                }
              }
              return 'NO ISBN';
            })(),
            'weight' => $product['weight'],

            'author' => (function ($product) {
              $author = $product['header'][0]['header']['author_information']['author'];
              return [
                'name' => $author['name'],
                'id'   => $author['term_id'],
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
  // Parses the imported products drafts and fills the product pages with the data
  [
    'pattern' => 'parse-weltkern',
    'language' => '*',
    'action' => function ($lang = null) {
    }
  ],
];
