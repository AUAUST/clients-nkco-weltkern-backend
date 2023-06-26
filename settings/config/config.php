<?php

return [
  "debug" => false,

  "languages" => true,
  "languages.detect" => true,

  "date.handler" => "intl",

  "smartypants" => true,

  "routes" => require_once __DIR__ . "/routes/index.php",

  "auaust.algolia" => [
    "algoliaAppId"    => secret('ALGOLIA_APP_ID'),
    "algoliaAdminKey" => secret('ALGOLIA_ADMIN_KEY')
  ]
];
