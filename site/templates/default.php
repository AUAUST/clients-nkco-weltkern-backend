<?php

// use Kirby\Data\Json;
// use Kirby\Toolkit\Str;
// use Kirby\Cms\Page;
// use Kirby\Cms\Blueprint;

// // parse JSON string
// $json = Json::decode($jsonString);
// // dump($json);

// $parent = page('products');
// $products = $parent->children();

// $blueprint = Blueprint::load('pages/product_book');

// foreach ($json as $key => $value) {
//   // $value = $json[array_keys($json)[0]];
//   $name = $value["name"];
//   $slug = Str::slug($name);

//   $page = Page::create([
//     "blueprint" => $blueprint,
//     "slug" => $slug,
//     "template" => "product_book",
//     "parent" => $parent,
//     "content" => [
//       "title" => $name,
//       "price" => $value["price"]["amount"],
//       "kerns" => $value["price"]["kerns"],
//       "author" => $value["author"] ?? null,
//       "publisher" => $value["publisher"],
//       "image" => $value["src"],
//       "stock" => rand(1, 3) < 3 ? rand(1, 4) : rand(5, 20),
//     ]
//   ]);

//   $products->add($page->changeStatus('listed'));
// }


// // $slug

// // $newProduct = Page::create([

// // ])




// // dump(kirby()->user()->permissions());
// // dump(kirby()->user()->role());

// // kirby()->user()->logout(); -->


?>

<?= $page->intendedTemplate() ?><br>
<?= $page->uuid() ?><br>
<?=
""
// $page->content()->get("files")->toFile()->blurhash()
?><br>
