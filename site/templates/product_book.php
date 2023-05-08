<?php

use Kirby\Cms\Response;
use Kirby\Data\Json;

// $page is the product page

// Product's data
$data = [
  "name" => $page->title()->toString(),
  "author" => $page->author()->toString(),
  "publisher" => $page->publisher()->toString(),

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
