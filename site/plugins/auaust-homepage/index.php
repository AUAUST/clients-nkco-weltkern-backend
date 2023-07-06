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
          return $this->toPages([site()->automaticHero()]);
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

      if ($mode === 'selected') {
        return site()->selectedHero();
      }

      // By default, return the automatic hero.
      return site()->automaticHero();
    },
    'selectedHero' => function () {
      return page('home')->content()->hero()->toPage();
    },
    'automaticHero' => function () {
      $heroes = page('home/heroes')->children();
      $time = time();

      // Filter out all heroes that are not visible yet.
      $heroes = $heroes->filter(function ($hero) use ($time) {
        $visibleSince = $hero->visibleSince()->toTimestamp();
        return $visibleSince <= $time;
      });

      // Find the most recent hero.
      $hero = $heroes->sortBy('visibleSince', 'desc')->first();

      return $hero;
    },
  ]
]);
