<?php

use Kirby\Cms\Response;
use Kirby\Data\Json;

// Product's data
$data = [
  "title" => $title = $site->title()->toString(),
  "titles" => [
    "default" => $title,
    "short" => $site->titleShort()->toString() ?: $title,
    "simple" => $site->titleSimple()->toString() ?: $title,
  ],
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
