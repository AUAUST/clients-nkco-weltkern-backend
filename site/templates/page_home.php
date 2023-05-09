<?php

use Kirby\Cms\Response;
use Kirby\Data\Json;

// Product's data
$data = [
  "title" => $page->title()->toString(),
  "shortTitle" => $page->short_title()->toString(),
];

// JSON string
$body = Json::encode($data);

// Response object
$response = Response::json(
  $data,
  null,
  true
);

?>

<?=

// Send the response
$response->send();

?>
