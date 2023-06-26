<?php

use Kirby\Cms\App as Kirby;
use Kirby\Filesystem\F;

F::loadClasses([
  'AUAUST\\Algolia\\Algolia' => 'classes/Algolia.php',
], __DIR__);

Kirby::plugin("auaust/algolia", [
  'options' => [
    'algoliaAppId'    => null,
    'algoliaAdminKey' => null,
  ],
  'collectionMethods' => [
    'toAlgoliaData' => function () {
      return $this->pluck('toAlgoliaData');
    }
  ]
]);
