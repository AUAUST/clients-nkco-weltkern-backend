<?php

use AUAUST\Products\WK1;

return [
  'pattern' => 'publishers',
  'language' => '*',
  'action' => function () {
    return json_encode(WK1::publishers(), JSON_PRETTY_PRINT);
  }
];
