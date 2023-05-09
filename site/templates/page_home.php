<?php

use Kirby\Cms\Response;
use Kirby\Data\Json;

// Product's data
$data = [
  "title" => $site->title()->toString(),
  "titles" => [
    "short" => $site->titleShort()->toString(),
    "simple" => $site->titleSimple()->toString(),
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
