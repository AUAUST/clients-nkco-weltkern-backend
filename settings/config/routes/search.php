<?php

use Kirby\Cms\Response;

return [
  'pattern' => ['search', 'search/(:any)'],
  'language' => '*',
  'action' => function ($lang = null, string $type = 'all') {

    $kirby   = kirby();
    $request = $kirby->request();
    $query   = $request->query()->toArray();

    // This gets all set parameters from the request URL, regardless of whether they are set as query or URL parameters
    // exemple.com/search/foo:bar?baz=qux -> ['foo' => 'bar', 'baz' => 'qux']
    $params = array_merge(
      $query,
      params(),
    );

    return Response::json(['params' => $params, 'type' => $type], 200);
  }
];
