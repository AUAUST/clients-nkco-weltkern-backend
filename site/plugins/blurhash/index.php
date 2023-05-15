<?php

use Kirby\Cms\App as Kirby;

Kirby::plugin('auaust/blurhash', [
  "options" => [
    "blurWidth" => 4,
    "blurHeight" => 4,
  ]
]);
