<?php

use Kirby\Cms\Response;

/**
 * Return an option from the config, for debugging purposes
 */
return [
  'pattern' => 'option/(:all)',
  'language' => '*',
  'action' => function ($lang, $option) {
    return dump(
      kirby()->option($option, 'Not found'),
      false
    );
  }
];
