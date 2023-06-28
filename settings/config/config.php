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
        'algoliaApiKey' => secret('ALGOLIA_API_KEY'),

        'indices' => [
          'dev_wk_products' => [
            'settings' => [
              // Attributes
              'attributeForDistinct' => 'uuid',
              // 'attributesForFaceting',

              // 'attributesToRetrieve',
              // 'unretrievableAttributes',

              'searchableAttributes' => [
                'title',
                'slug',
                'description'
              ],

              // Ranking
              // 'ranking',
              // 'customRanking' => [
              //   'desc(popularity)',
              //   'asc(price)'
              // ],

              // Faceting
              // 'maxValuesPerFacet',
              // 'sortFacetValuesBy',

              // Typos
              // 'minWordSizefor1Typo',
              // 'minWordSizefor2Typos',
              // 'typoTolerance',

              // Query strategy
              'advancedSyntax' => true,
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
