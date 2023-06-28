<?php

namespace AUAUST\Algolia;

use Algolia\AlgoliaSearch\Config\SearchConfig;
use Algolia\AlgoliaSearch\SearchClient;

class Algolia
{
  private static SearchClient|null $client;
  private static $indices = [];

  public static function initIndex(string $indexName)
  {
    if (
      !($indexConfig = option("auaust.algolia.indices.{$indexName}"))
    ) {
      throw new \Exception("The index '{$indexName}' was not found in the plugin config");
    }

    $index = self::client()->initIndex($indexName);

    if (is_array($indexConfig['settings'])) {
      $config['settings'] = $indexConfig['settings'];
    }

    if (!empty($config)) {
      $index->setSettings($config['settings']);
    }

    return $index;
  }

  public static function client()
  {
    if (isset(self::$client)) {
      return self::$client;
    }

    $config = [
      'appId'  => option('auaust.algolia.algoliaAppId'),
      'apiKey' => option('auaust.algolia.algoliaApiKey'),
    ];

    self::$client = SearchClient::createWithConfig(
      new SearchConfig($config)
    );

    return self::$client;
  }
}
