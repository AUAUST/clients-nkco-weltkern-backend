<?php

use Kirby\Cms\Response;
use Kirby\Data\Json;

use FontLib\Font;

// Product's data
$data = [
  "title" => $title = $site->title()->toString(),
  "titles" => [
    "default" => $title,
    "short" => $site->titleShort()->toString() ?: $title,
    "simple" => $site->titleSimple()->toString() ?: $title,
  ],
  "slogan" => $site->slogan()->toString(),
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

<?php

// $font = Font::load(__DIR__ . '/TWKLausanne-500.otf');
// $font->parse(); // for getFontWeight() to work this call must be done first!
// echo $font->getFontName() . '<br>';
// echo $font->getFontSubfamily() . '<br>';
// echo $font->getFontSubfamilyID() . '<br>';
// echo $font->getFontFullName() . '<br>';
// echo $font->getFontVersion() . '<br>';
// echo $font->getFontWeight() . '<br>';
// echo $font->getFontPostscriptName() . '<br>';
// dump($font);
// $font->close();

?>
