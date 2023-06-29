<?php

use Kirby\Cms\Response;
use Kirby\Toolkit\Str;
use Kirby\Cms\Page;
use Kirby\Data\Yaml;
use Kirby\Toolkit\Html;

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
        $baseSlug = $slug;
        $nth  = 1;

        while ($existingProducts->find($slug)) {
          $slug = $baseSlug . '-' . ++$nth;
        }

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
            })($product),
            'weight' => $product['weight'],

            'author' => (function ($product) {
              $author = $product['header'][0]['header']['author_information']['author'];
              return [
                'name' => $author['name'],
                'id'   => $author['term_id'],
              ];
            })($product),

            'description' => $product['short_description'],
            'details' => (function ($product) {
              $details = '';

              foreach ($product['header'][0]['header']['block_option'] as $option) {
                $details .= $option['option'] . ': ' . '"' . Str::replace($option['value'], '"', '\"') . '"' . PHP_EOL;
              }

              return $details;
            })($product),

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

        $newProducts[] = $productsPage->createChild([
          'slug' => $slug,
          'template' => 'product_book',
          'content' => $content
        ]);
      }

      if (empty($newProducts)) {
        return Response::json([
          'status' => 'success',
          'message' => 'No new products found',
        ], 200);
      }

      return Response::json([
        'status' => 'success',
        'message' => 'New products found',
        'data' => $newProducts,
      ], 200);
    }
  ],
  // Parses the imported products drafts and fills the product pages with the data
  [
    'pattern' => 'parse-weltkern',
    'language' => '*',
    'action' => function ($lang = null) {

      $kirby = kirby();
      $site  = $kirby->site();

      /**
       * @var Page $productsPage
       */
      $productsPage = $site->pageProducts()->toPage();

      $products = $productsPage->drafts();

      $updatedProducts = [];
      $erroredProducts = [];

      foreach ($products as $product) {

        $oldWeltkern = $product->oldWeltkern()->toObject();

        $details =
          Yaml::decode(
            $oldWeltkern->details()->toString()
          );

        try {
          $updatedProducts[] = [
            'wk1-slug' => $oldWeltkern->slug()->toString(),
            'isbn' => WK1::extractIsbn($oldWeltkern->isbn()),
            'dimensions' => WK1::extractDimensions($details['Size']),
            'price' => $oldWeltkern->price()->toFloat(),
            'weight' => $oldWeltkern->weight()->toString(),
            'description' => WK1::stripHtml($oldWeltkern->description()),
          ];
        } catch (Exception $e) {
          $erroredProducts[] =
            'Error in product '
            . $product->id() . ': '
            . $e->getMessage() . ' ('
            . $e->getFile() . ', '
            . $e->getLine() . ')';
        }
      }

      return dump(
        (function ($updatedProducts) {
          usort(
            $updatedProducts,
            fn ($a, $b) => $a['weight'] <=> $b['weight']
          );
          return $updatedProducts;
        }
        )($updatedProducts),
        false
      );
    }

  ],
];
