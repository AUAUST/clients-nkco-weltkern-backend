<?php

use Kirby\Cms\Response;
use Kirby\Toolkit\Str;
use Kirby\Cms\Page;
use Kirby\Data\Yaml;

use AUAUST\products\WK1;

return [
  // Get the latest, unimported products from WK1
  [
    'pattern' => 'sync-weltkern',
    'language' => '*',
    'action' => function () {
      // Local shortcut function to fix encoding issues because Wordpress is a mess
      function fix(string $string)
      {
        $string = preg_replace('/\s+/', ' ', $string);
        $string = mb_convert_encoding($string, 'UTF-8', 'UTF-8');
        $string = Normalizer::normalize($string, Normalizer::FORM_KC);
        return $string;
        // return Str::unhtml(Html::encode($string));
        // return $string;
      }

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

      try {
        foreach ($remoteProducts as $product) {

          // if ($product['slug'] === '30-ans-a-paris') {
          //   return ;
          //   // return mb_detect_encoding($product['name']);
          // }

          // Only extract books, not typefaces nor stationery
          if ($product['categories'][0]['slug'] !== 'books') {
            continue;
          }

          // Skip if product already exists
          if (in_array($product['id'], $existingProductsIds)) {
            continue;
          }

          // Get name
          $title = fix($product['name']);
          // Replace inline <br> with line breaks
          $title = preg_replace('/<br\s*\/?>/', '|', $title);
          // Remove all other HTML tags
          $title = Str::unhtml($title);
          // Deduplicate spaces, and remove spaces next to a pipe
          $title = preg_replace('/\s+/', ' ', $title);
          $title = preg_replace('/\s*\|\s*/', '|', $title);

          $slug = fix(Str::slug($title));
          $baseSlug = $slug;
          $nth  = 1;

          while ($existingProducts->find($slug)) {
            $slug = $baseSlug . '-' . ++$nth;
          }

          $content = [
            'title' => $title,

            'oldWeltkern' => [
              'title'  => fix($product['name']),
              'slug'   => fix($product['slug']),
              'id'     => $product['id'],

              'price'  => $product['price'],

              'isbn'   => (function ($product) {
                foreach ($product['header'][0]['header']['block_option'] as $option) {
                  if ($option['option'] === 'ISBN') {
                    return fix($option['value']);
                  }
                }
                return 'NO ISBN';
              })($product),
              'weight' => $product['weight'],

              'author' => (function ($product) {
                $author = $product['header'][0]['header']['author_information']['author'];
                return [
                  'name' => fix($author['name']),
                  'id'   => $author['term_id'],
                ];
              })($product),

              'description' => fix($product['short_description']),
              'details' => (function ($product) {
                $details = '';

                foreach ($product['header'][0]['header']['block_option'] as $option) {
                  $details .= $option['option'] . ': ' . '"' . Str::replace(fix($option['value']), '"', '\"') . '"' . PHP_EOL;
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
      } catch (Exception $e) {
        return Response::json([
          'status' => 'error',
          'message' => $e->getMessage(),
        ], 500);
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
        'data' => array_map(function ($product) {
          return [
            'title' => $product->title()->toString(),
            'slug' => $product->slug(),
            'id' => $product->simpleUuid(),
          ];
        }, $newProducts),
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

      $publishers = [];


      foreach ($products as $product) {

        $oldWeltkern = $product->oldWeltkern()->toObject();

        $details =
          Yaml::decode(
            $oldWeltkern->details()->toString()
          );
        $details = array_change_key_case($details, CASE_LOWER);

        try {
          $updatedProducts[] = [
            'wk1-slug' => $oldWeltkern->slug()->toString(),
            'title' => $oldWeltkern->title()->toString(),
            'isbn' => WK1::extractIsbn($oldWeltkern->isbn()),
            'dimensions' => WK1::extractDimensions($details['size']),
            'price' => $oldWeltkern->price()->toFloat(),
            'weight' => $oldWeltkern->weight()->toString(),
            'description' => WK1::stripHtml($oldWeltkern->description()),
            'language' => WK1::extractLanguage($details),
            'pages' => WK1::extractPages($details),
            // 'author' => @$oldWeltkern->author()->toArray()['id'],
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

      // return dump(
      //   (function ($updatedProducts) {
      //     usort(
      //       $updatedProducts,
      //       fn ($a, $b) => $a['weight'] <=> $b['weight']
      //     );
      //     return $updatedProducts;
      //   }
      //   )($updatedProducts),
      //   false
      // );

      return dump([
        'success' => $updatedProducts,
        'error' => $erroredProducts,
      ], false);
    }

  ],
];
