<?php

use Kirby\Cms\Response;

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

// Response object
echo Response::json(
  [
    'status' => 'ok',
    'data' => $data
  ],
);

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
