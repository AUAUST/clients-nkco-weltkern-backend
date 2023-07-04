<?php

use Kirby\Cms\App as Kirby;
use Kirby\Cms\Pages;
use Kirby\Content\Field;

Kirby::plugin("auaust/homepage", [
  'fields' => [
    // Tag's tagsets field.
    'homepage-hero' => [
      'extends' => 'pages',
      'props' => [
        'value' => function ($value = null) {
          // Pretty hacky way to generate the list of tagset pages to be displayed in the field.
          // It works by generating a YAML string of the tagset pages' UUIDs.
          // return '- ' . kirby()->site()->find('home')->uuid()->id();
          return kirby()->site()->find(false, false, ...[page('home')->simpleUuid()]);
        },
      ],
      'save' => function () {
        // Return null so that the field is not saved.
        return null;
      },
    ]
  ],

]);
