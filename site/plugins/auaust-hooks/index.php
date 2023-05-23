<?php

use Kirby\Cms\App as Kirby;

Kirby::plugin("auaust/hooks", [
  'hooks' => [
    'page.create:after' => function ($event) {
      $data = dump($event, false);

      $file = kirby()->root('content') . '/hooks.txt';

      file_put_contents($file, $data);
    }
  ]
]);
