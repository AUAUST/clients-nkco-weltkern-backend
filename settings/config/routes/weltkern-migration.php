<?php

use Kirby\Cms\Response;
use AUAUST\products\WK1;

return [
  'pattern' => 'sync-weltkern',
  'language' => '*',
  'action' => function ($lang = null) {
    return kirby()->site()->pageProducts()->toPage()->drafts()->toAlgoliaData();
  }
];
