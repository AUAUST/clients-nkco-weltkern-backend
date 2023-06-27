<?php

return [
  'debug' => false,

  'languages' => true,
  'languages.detect' => true,

  'date.handler' => 'intl',

  'smartypants' => true,

  'routes' => require_once __DIR__ . '/routes/index.php',

  // Using 'ready' otherwise 'secret' isn't yet available
  'ready' => function () {
    return [
      'auaust.algolia' => [
        'algoliaAppId'  => secret('ALGOLIA_APP_ID'),
        'algoliaApiKey' => secret('ALGOLIA_ADMIN_KEY'),

        'indices' => [
          'dev_wk_products' => [
            'settings' => [
              'attributeForDistinct' => 'uuid',
              'searchableAttributes' => [
                'title',
                'slug',
                'description'
              ],
            ],

            'records' => function () {
              return page('products')->children()->toAlgoliaData();
            }
          ]
        ]
      ]
    ];
  }
];
