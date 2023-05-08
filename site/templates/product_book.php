<?php

use Kirby\Cms\Response;
use Kirby\Data\Json;

// $page is the product page

// Product's data
$data = [
  "name" => $page->title()->toString(),
  "author" => $page->author()->toString(),
  "publisher" => $page->publisher()->toString(),
  "href" => $page->url(),
  "remaining" => (function ($page) {
    $remaining = $page->stock()->toInt();

    if ($remaining <= 0) {
      return "none";
    } else if ($remaining === 1) {
      return "one";
    } else if ($remaining <= 3) {
      return "few";
    } else if ($remaining <= 5) {
      return "many";
    } else {
      return "all";
    }
  })($page),
  "price" => (function ($page) {

    $price = $page->price()->toFloat();
    $currency = "CHF";

    if ($page->auto_kerns()->toBool() === false) {
      $kerns = $page->kerns()->toFloat();
    } else {
      $kerns = (int)($price * 100);
    }

    return [
      "amount" => $price,
      "currency" => $currency,
      "kerns" => $kerns
    ];
  })($page)
];

// JSON string
$body = Json::encode($data);

// Response object
$response = new Response(
  $body,
  "application/json",
  404
);

?>

<?=
// Send the response
$response->send();
?>
