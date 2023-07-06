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
        'value' => function () {
          return $this->toPages([site()->hero()]);
        },
        'disabled' => true,
        'translate' => false,
      ],
      'save' => function () {
        // Return null so that the field is not saved.
        return null;
      },
    ]
  ],
  'siteMethods' => [
    'hero' => function (string $mode = null) {
      $homepage = page('home');
      $mode ??= $homepage->heroMode()->toString();

      return page('home')->hero()->toPages();
    },
    'selectedHero' => function () {
      return page('home')->content()->hero()->toPage();
    },
    'automaticHero' => function () {
      $heros = page('home/heroes')->children();
      return $heros;
    },
  ]
]);
