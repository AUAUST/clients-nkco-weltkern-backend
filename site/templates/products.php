<?php

use Kirby\Toolkit\Str;
use auaust\products\WK1;

// echo Str::unhtml(json_encode(WK1::getMediaById(WK1::products()[0]["gallery_image"][0]["id"])));


$image = WK1::getImageById(WK1::products()[0]["gallery_image"][0]["id"]);

$image = base64_encode($image);

echo "<img src='data:image/jpeg;base64," . $image . "' alt=''>";

return;

// dump(WK1::products()[0]);
// echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
// dump(WK1::products()[9]);
// echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
?>
<pre><?php

      return;
      $products = WK1::products();
      $quantity = WK1::productsQuantity();

      foreach ($products as $index => $product) {
        // pad left index (1 -> 0001)
        $index = str_pad($index + 1, 3, '0', STR_PAD_LEFT);
        // {{ index }} : {{ product.name }}
        echo "============================== " . $index . " ==============================<br>";
        echo $product['name'] . '<br>';
        echo "-----------------------------------------------------------------<br>";
        // ALL PROPS
        // id, name, slug, description, short_description
        // featured_image, gallery_image, featured, typeface, typefaces
        // price, price_welt, in_stock, weight, length
        // width, height, downloadable, categories, tags
        // brands, average_rating, review_count, quantity, quantite
        // back_order_qty, only_welt_point, multiplier, new, rare
        // made_by_weltanschauung, staff_pick, weltclub_exclu, download, in_use
        // choice_product, block_text, header, licences, font_feature
        // poids, estimation_de_livraison, estimation_back_order, gift_wrap, frais_livraisons
        // header_color, welt_price, options, estimation_date_backorder, content_story
        // display_story, variant, backorder_check, categorie_multiplier, 0
        // 1, 2, 3, 4, font_face
        // points, currency, colors

        // USEFUL PROPS
        // name, slug, short_description
        // featured_image: {
        //  url: false|url
        //  id: number
        // }

        // , gallery_image, typefaces
        // price, price_welt, in_stock, weight, length
        // width, height, downloadable, categories, tags
        // brands, average_rating, review_count, quantity, quantite
        // back_order_qty, only_welt_point, multiplier, new, rare
        // made_by_weltanschauung, staff_pick, weltclub_exclu, download, in_use
        // choice_product, block_text, header, licences, font_feature
        // poids, estimation_de_livraison, estimation_back_order, gift_wrap, frais_livraisons
        // header_color, welt_price, options, estimation_date_backorder, content_story
        // display_story, backorder_check, categorie_multiplier, 0
        // 1, 2, 3, 4
        // points, currency, colors
        echo "slug<br>(WP) " . $product['slug'] . '<br>';
        echo "(K3) " . Str::slug(Str::unhtml($product['name'])) . '<br>';
        echo "-----------------------------------------------------------------<br>";
        echo "type<br>  " . $product['categories'][0]['slug'] . '<br>';
        echo "-----------------------------------------------------------------<br>";
        echo "description<br>  " . str_replace(PHP_EOL, "<br>  ", Str::unhtml($product['short_description'])) . '<br>';
        echo "-----------------------------------------------------------------<br>";
        echo "kkkkk<br>  " . json_encode($product['featured_image']) . '<br>';
        echo "<br><br><br><br><br>";
      }
      ?></pre>
