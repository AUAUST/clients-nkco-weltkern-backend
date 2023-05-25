<?php

use Kirby\Toolkit\Str;
use auaust\products\WK1;

$products = WK1::products(
  (int) (params()['amount'] ?? null),
  (string) (params()['category'] ?? null)
);
// $quantity = WK1::productsQuantity();

// WK1::getImageById($product['featured_image']['id']);
//   getImagesByIds

// Each product's cover image and gallery images, as a two-dimensional array
$imagesIds = array_map(
  function ($product) {
    return array_merge(
      // The cover image
      [$product['featured_image']['id'] . ""],
      // The gallery images
      array_map(
        function ($image) {
          return $image['id'] . "";
        },
        $product['gallery_image']
      )
    );
  },
  $products
);

// Flatten the two-dimensional array
$imagesIds = array_merge(...$imagesIds);

$imagesUrls = WK1::getImagesByIds($imagesIds);

$columnWidth = 100;

function titleLine(string $title = "", int $columnWidth = 101)
{
  $title = " " . $title . " ";
  $titleLength = strlen($title);
  return '---' . $title . str_repeat('-', $columnWidth - $titleLength - 3);
}

?>

<div>
  <style>
    :root {
      --width: <?= $columnWidth ?>ch;
    }

    section {
      margin-bottom: 100px;
      width: var(--width);
    }

    pre {
      margin: 7px 0;

      white-space: normal;
    }

    .gallery {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 10px;
    }

    .gallery img {
      width: 100%;
    }
  </style>
  <?php foreach ($products as $index => $product) : ?>
    <?php $index = str_pad($index + 1, 3, '0', STR_PAD_LEFT); ?>
    <section>
      <pre><?= str_repeat('=', ($columnWidth - 4) / 2) ?> <?= $index ?> <?= str_repeat('=', ($columnWidth - 4) / 2) ?></pre>
      <pre><?= $product['name'] ?></pre>
      <pre><?= titleLine("description") ?></pre>
      <pre><?= Str::unhtml($product['short_description']) ?></pre>
      <pre><?= titleLine("slug") ?></pre>
      <pre>(WP) <?= $product['slug'] ?><br>(K3) <?= Str::slug(Str::unhtml($product['name'])) ?></pre>
      <pre><?= titleLine("category") ?></pre>
      <pre><?= $product['categories'][0]['slug'] ?></pre>
      <pre><?= titleLine("cover") ?></pre>
      <div>
        <img src="<?= $imagesUrls[$product['featured_image']['id']] ?>" alt="<?= $product['name'] ?>" height="300" loading="lazy">
      </div>
      <pre><?= titleLine("gallery") ?></pre>
      <div class="gallery">
        <?php foreach ($product['gallery_image'] as $index => $image) : ?>
          <img src="<?= $imagesUrls[$image['id'] ?? '0'] ?? '' ?>" alt="<?= $product['name'] ?>" loading="lazy">
        <?php endforeach; ?>
      </div>
      <?php if (is_array($product['typefaces'])) : ?>
        <pre><?= titleLine("typeface") ?></pre>
        <pre><?= $product['typefaces']['font_family_name'] ?></pre>
        <?php
        // foreach ($product['typefaces'] as $key => $typeface) :
        ?>
        <!-- <pre>< ?= $key ?>: < ?= json_encode($typeface) ?></pre> -->
        <?php
        //  endforeach;
        ?>
      <?php endif; ?>
      <pre><?= titleLine("price") ?></pre>
      <pre><?= $product['price'] ?> <?= $product['currency'] ?></pre>
      <pre><?= titleLine("stock") ?></pre>
      <pre><?= $product['quantity'] ?></pre>
      <pre><?= titleLine("publisher") ?></pre>
      <pre><?= $product["header"][0]["header"]["author_information"]["author"]["name"] ?></pre>
      <?php if ($description = $product["header"][0]["header"]["author_information"]["author"]["description"]) : ?>
        <pre><?= titleLine("publisher description") ?></pre>
        <pre><?= $description ?></pre>
      <?php endif; ?>
    </section>
  <?php endforeach; ?>
</div>
<?php
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
// name: string
// slug: string
// short_description: string
// featured_image: {
//  url: false|url
//  id: number
// }
// gallery_image: { // this[0] === featured_image
//  url: false|url
//  id: number
// }[]

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
// echo "-----------------------------------------------------------------<br>";
// echo "cover<div style='display:flex;gap:10px'>";
// echo "<img src='" . $imagesUrls[$product['featured_image']['id']] . "' alt='" . $product['name'] . "' height='300' loading='lazy'><div>";
// foreach ($product['gallery_image'] as $index => $image) {
//   if ($index > 0)
//     echo "<img src='" . $imagesUrls[$image['id']] . "' alt='" . $product['name'] . "' height='200' loading='lazy'>";
// }
// echo "</div></div>";
// echo "<br><br><br><br><br>";
?>
