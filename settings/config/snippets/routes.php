<?php

use Kirby\Cms\Response;
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

      // ----

      // Oldweltkern:

      // slug: digital-descending
      // id: 1111
      // gallery:
      //   -
      //     url: >
      //       https://api.weltkern.com/wp-content/uploads/2023/01/LuYang_COVER_.jpg
      //     id: 12312
      // tags:

      //   -
      //     name: Book
      //     id: 12312
      // cover: ""

      try {
        $newProducts = [];
        $products = WK1::products(50);

        $productsPage = page('products');

        $returnString = '';


        foreach ($products as $index => $product) {

          // if ($index > 1) {
          //   break;
          // }

          // // Only extract books, not typefaces nor stationery
          // if ($product['categories'][0]['slug'] !== 'books') {
          //   continue;
          // }

          $returnString .= 'name: ' . $product['name'] . '<br>';
          $returnString .= 'slug: ' . $product['slug'] . '<br>';
          $returnString .= 'id: ' . $product['id'] . '<br>';
          $returnString .= 'short_description: ' . $product['short_description'] . '<br>';
          $returnString .= 'gallery_image: ' . json_encode($product['gallery_image']) . '<br>';
          $returnString .= 'price: ' . $product['price'] . '<br>';
          $returnString .= 'price_welt: ' . $product['price_welt'] . '<br>';
          $returnString .= 'in_stock: ' . $product['in_stock'] . '<br>';
          $returnString .= 'weight: ' . $product['weight'] . '<br>';
          $returnString .= 'categories: ' . json_encode($product['categories']) . '<br>';
          $returnString .= 'tags: ' . json_encode($product['tags']) . '<br>';
          $returnString .= 'header: ' . json_encode($product['header']) . '<br>';
          $returnString .= 'poids: ' . json_encode($product['poids']) . '<br>';
          $returnString .= 'frais_livraisons: ' . json_encode($product['frais_livraisons']) . '<br>';
          $returnString .= 'header_color: ' . $product['header_color'] . '<br>';
          $returnString .= 'welt_price: ' . $product['welt_price'] . '<br>';
          $returnString .= 'options: ' . json_encode($product['options']) . '<br>';
          $returnString .= '<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';


          // id
          // name
          // slug
          // short_description

          // featured_image
          // gallery_image
          // featured

          // price
          // price_welt
          // in_stock
          // weight
          // length
          // width
          // height
          // downloadable
          // categories
          // tags
          // brands
          // average_rating
          // review_count
          // quantity
          // quantite
          // back_order_qty
          // only_welt_point
          // multiplier
          // new
          // rare
          // made_by_weltanschauung
          // staff_pick
          // weltclub_exclu
          // download
          // in_use
          // choice_product
          // block_text
          // header
          // licences
          // font_feature
          // poids
          // estimation_de_livraison
          // estimation_back_order
          // gift_wrap
          // frais_livraisons
          // header_color
          // welt_price
          // options
          // estimation_date_backorder
          // content_story
          // display_story
          // variant
          // backorder_check
          // categorie_multiplier
          // font_face
          // points
          // currency
          // colors

          // $productsPage->createChild([
          //   'num' => $index + 1,
          //   'slug' => $product['name'],
          //   'template' => 'product_book',
          //   'content' => [
          //     'oldWeltkern' => [
          //       'slug' => $product['slug'],
          //       'id' => $product['id'],
          //       'description' => $product['short_description'],
          //       'gallery' => (array_map(
          //         function ($image) {
          //           return [
          //             'url' => $image['url'],
          //             'id' => $image['id'],
          //           ];
          //         },
          //         $product['gallery_image']
          //       )),
          //       'tags' => (array_map(
          //         function ($tag) {
          //           return [
          //             'name' => $tag['name'],
          //             'id' => $tag['id'],
          //           ];
          //         },
          //         $product['tags']
          //       )
          //       )
          //     ]
          //   ]
          // ]);


          // $newProducts[] = [
          //   'id' => $product['id'],
          //   'name' => $product['name'],
          // ];
        }

        // return $productsPage;
        return $returnString;
      } catch (\Throwable $th) {
        return $th->getMessage() . '<br>' . $th->getLine() . '<br>' . $th->getFile() . '<br>' . $th->getTraceAsString();
      }
    }
  ]
];
