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
        $products = WK1::products(20);

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


          $returnString .= 'featured_image: ' . json_encode($product['featured_image']) . '<br>';
          $returnString .= 'gallery_image: ' . json_encode($product['gallery_image']) . '<br>';
          $returnString .= 'featured: ' . $product['featured'] . '<br>';
          $returnString .= 'price: ' . $product['price'] . '<br>';
          $returnString .= 'price_welt: ' . $product['price_welt'] . '<br>';
          $returnString .= 'in_stock: ' . $product['in_stock'] . '<br>';
          $returnString .= 'weight: ' . $product['weight'] . '<br>';
          $returnString .= 'length: ' . $product['length'] . '<br>';
          $returnString .= 'width: ' . $product['width'] . '<br>';
          $returnString .= 'height: ' . $product['height'] . '<br>';
          $returnString .= 'downloadable: ' . $product['downloadable'] . '<br>';
          $returnString .= 'name: ' . json_encode($product['categories']) . '<br>';
          $returnString .= 'name: ' . json_encode($product['tags']) . '<br>';
          $returnString .= 'name: ' . json_encode($product['brands']) . '<br>';
          $returnString .= 'average_rating: ' . $product['average_rating'] . '<br>';
          $returnString .= 'review_count: ' . $product['review_count'] . '<br>';
          $returnString .= 'quantity: ' . $product['quantity'] . '<br>';
          $returnString .= 'quantite: ' . $product['quantite'] . '<br>';
          $returnString .= 'back_order_qty: ' . $product['back_order_qty'] . '<br>';
          $returnString .= 'only_welt_point: ' . $product['only_welt_point'] . '<br>';
          $returnString .= 'multiplier: ' . $product['multiplier'] . '<br>';
          $returnString .= 'new: ' . $product['new'] . '<br>';
          $returnString .= 'rare: ' . $product['rare'] . '<br>';
          $returnString .= 'made_by_weltanschauung: ' . $product['made_by_weltanschauung'] . '<br>';
          $returnString .= 'staff_pick: ' . $product['staff_pick'] . '<br>';
          $returnString .= 'weltclub_exclu: ' . $product['weltclub_exclu'] . '<br>';
          $returnString .= 'name: ' . json_encode($product['download']) . '<br>';
          $returnString .= 'name: ' . json_encode($product['in_use']) . '<br>';
          $returnString .= 'choice_product: ' . $product['choice_product'] . '<br>';
          $returnString .= 'name: ' . json_encode($product['header']) . '<br>';
          $returnString .= 'name: ' . json_encode($product['licences']) . '<br>';
          $returnString .= 'name: ' . json_encode($product['font_feature']) . '<br>';
          $returnString .= 'name: ' . json_encode($product['poids']) . '<br>';
          $returnString .= 'estimation_de_livraison: ' . json_encode($product['estimation_de_livraison']) . '<br>';
          $returnString .= 'estimation_back_order: ' . json_encode($product['estimation_back_order']) . '<br>';
          $returnString .= 'gift_wrap: ' . $product['gift_wrap'] . '<br>';
          $returnString .= 'frais_livraisons: ' . json_encode($product['frais_livraisons']) . '<br>';
          $returnString .= 'header_color: ' . $product['header_color'] . '<br>';
          $returnString .= 'welt_price: ' . $product['welt_price'] . '<br>';
          $returnString .= 'options: ' . json_encode($product['options']) . '<br>';
          $returnString .= 'estimation_date_backorder: ' . $product['estimation_date_backorder'] . '<br>';
          $returnString .= 'content_story: ' . json_encode($product['content_story']) . '<br>';
          $returnString .= 'display_story: ' . $product['display_story'] . '<br>';
          $returnString .= 'variant: ' . $product['variant'] . '<br>';
          $returnString .= 'backorder_check: ' . $product['backorder_check'] . '<br>';
          $returnString .= 'categorie_multiplier: ' . $product['categorie_multiplier'] . '<br>';
          $returnString .= 'font_face: ' . json_encode($product['font_face']) . '<br>';
          $returnString .= 'points: ' . json_encode($product['points']) . '<br>';
          $returnString .= 'currency: ' . $product['currency'] . '<br>';
          $returnString .= 'colors: ' . json_encode($product['colors']) . '<br>';
          $returnString .= 'name: ' . '<br>';
          $returnString .= 'name: ' . '<br>';
          $returnString .= 'name: ' . '<br>';
          $returnString .= 'name: ' . '<br>';


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
