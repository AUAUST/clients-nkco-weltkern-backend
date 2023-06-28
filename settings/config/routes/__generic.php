<?php

use Kirby\Cms\Response;

return [
  'pattern' => ['xxx', 'xxx/(:any)'],
  'language' => '*',
  'action' => function ($lang = null, $id = null) {
    return Response::json([], 200);
  }
];
