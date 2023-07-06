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
      return page('home')->content()->hero()->toPage() ?? site()->automaticHero();
    },
    'automaticHero' => function () {
      $heros = page('home/heroes')->children();

      $time = time();
      // Filter out all heros that are not visible yet.
      $heros = $heros->filter(function ($hero) use ($time) {
        $visibleSince = $hero->visibleSince()->toTimestamp();

        return $visibleSince <= $time;
      });
      // Sort by visibleSince.
      $heros = $heros->sortBy('visibleSince', 'asc');

      return $heros->first()->visibleSince()->toTimestamp() ?? null;
    },
  ]
]);
