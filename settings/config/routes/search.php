<?php

use Kirby\Cms\Response;
use Algolia\AlgoliaSearch\SearchClient as Algolia;

return [
  'pattern' => ['search', 'search/(:any)', 'search/(:any)/(:any)'],
  'language' => '*',
  'action' => function ($lang = null, string $type = 'all', string $count = 'all') {

    // Connect and authenticate with your Algolia app
    $client = Algolia::create(
      secret('ALGOLIA_APP_ID'),
      secret('ALGOLIA_ADMIN_KEY')
    );

    // Create a new index and add a record
    $index = $client->initIndex('dev_products');

    $record = ["objectID" => 1, "name" => "test_record"];
    $index->saveObject($record)->wait();

    // Search the index and print the results
    $results = $index->search("test_record");

    return dump($results, false);

    $kirby   = kirby();
    $request = $kirby->request();
    $query   = $request->query()->toArray();

    // This gets all parameters from the request URL, regardless of whether they are set as query or URL parameters
    // exemple.com/search/foo:bar?baz=qux -> ['foo' => 'bar', 'baz' => 'qux']
    $params = array_merge(
      $query,
      params(),
    );

    if ($count === 'all' && preg_match('/^[0-9]+$/', $type)) {
      $count = $type;
      $type = 'all';
    }

    if ($count === 'all') {
      $count = null;
    } else {
      $count = intval($count);

      // If $count is 0 or less, set it to -1
      // Invalid values will fallback to 0 in intval() so it sanitizes automatically
      if ($count < 1) {
        $count = null;
      }
    }

    // If the params are empty, return the latest $count articles
    if (empty($params)) {
      // TODO: drafts() -> children()
      $products = page('products')->drafts();

      // if ($count) {
      //   $products = $products->limit($count);
      // }

      // return print_r($products, true);
      // return $products->pluck('toData');

      return Response::json([
        'status' => 'ok',
        'data' => [
          'count' => $products->count(),
          'products' => $products->pluck('toData'),
        ]
      ], 200);
    }

    return Response::json([
      'params' => $params,
      'type' => $type,
      'count' => $count,
    ], 200);
  }
];
