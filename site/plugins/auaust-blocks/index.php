<?php

use Kirby\Cms\App as Kirby;

Kirby::plugin("auaust/blocks", [
  'blueprints' => [
    'blocks/spacer' => __DIR__ . '/blueprints/blocks/spacer.yml',
    'blocks/articles' => __DIR__ . '/blueprints/blocks/articles.yml',
  ],
  'snippets' => [
    'blocks/spacer' => __DIR__ . '/snippets/blocks/spacer.php',
    'blocks/articles' => __DIR__ . '/snippets/blocks/articles.php',
  ],
]);
