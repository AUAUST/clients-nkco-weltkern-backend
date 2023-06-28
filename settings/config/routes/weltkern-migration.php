<?php

use Kirby\Cms\Response;
use AUAUST\products\WK1;

return [
  // Get the latest, unimported products from WK1
  [
    'pattern' => 'sync-weltkern',
    'language' => '*',
    'action' => function ($lang = null) {
    }
  ],
  // Parses the imported products drafts and fills the product pages with the data
  [
    'pattern' => 'parse-weltkern',
    'language' => '*',
    'action' => function ($lang = null) {
    }
  ]
];
