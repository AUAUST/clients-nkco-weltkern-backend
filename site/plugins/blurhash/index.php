<?php

use Kirby\Cms\App as Kirby;
use kornrunner\Blurhash\Blurhash;



Kirby::plugin('auaust/blurhash', [
  "options" => [
    "blurWidth" => 4,
    "blurHeight" => 4,
  ],
  "fieldMethods" => [
    "blurhash" => function ($field, $blurWidth = null, $blurHeight = null) {

      if (!($file = $field->toFile())) {
        throw new Exception("Field is not a file");
      }

      if (!($file->type() === "image")) {
        throw new Exception("File is not an image");
      }

      $image = imagecreatefromstring($file->read());
      $imageWidth = imagesx($image);
      $imageHeight = imagesy($image);

      $pixels = [];

      for ($y = 0; $y < $imageHeight; $y++) {
        $row = [];

        for ($x = 0; $x < $imageWidth; $x++) {
          // $index = imagecolorat($image, $x, $y);
          // $colors = imagecolorsforindex($image, $index);

          // $row[] = [$colors['red'], $colors['green'], $colors['blue']];
          $row[] = [0, 0, 0];
        }

        $pixels[] = $row;
      }

      imagedestroy($image);

      $blurWidth = $blurWidth ?? option("auaust.blurhash.blurWidth");
      $blurHeight = $blurHeight ?? option("auaust.blurhash.blurHeight");

      return Blurhash::encode($pixels, $blurWidth, $blurHeight);
    }
  ]
]);
