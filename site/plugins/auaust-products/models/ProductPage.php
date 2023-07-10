<?php

use Kirby\Cms\Page;
use Kirby\Cms\Field;

use AUAUST\Products\WK1;

class ProductPage extends Page
{
  protected string $type = 'product';

  /**
   * Split the page's name into a multiline title using pipes
   * `Un livre:|Une histoire` => `Un livre: \nUne histoire`.
   * Handles `\|` to escape pipes.
   */
  private static function splitName(string $name)
  {
    $nameparts = preg_split('/(?<!\\\)\|/', $name);

    $title = implode(PHP_EOL, $nameparts);

    return $title;
  }

  // Overrides the create method to parse multiline titles
  public static function create(array $props): Page
  {
    // Update the page's "name" field default value
    $props['content']['multilineTitle'] = self::splitName($props['content']['title']);
    // Exclude the page's "title" field
    $props['content']['title'] = null;

    // Create the page with updated props
    return parent::create($props);
  }

  public function title()
  {
    return $this->titleWithSeparator();
  }

  /**
   * Return the page's title, with line breaks replaced by a choosen separator
   */
  public function titleWithSeparator(string|null $separator = null)
  {
    // Use the parameter separator if provided, otherwise use the page's multilineTitleSeparator field
    $separator =
      $separator
      ?? $this->content()->get("multilineTitleSeparator")->toString()
      ?? 'space';

    // Get the title from the multilineTitle field
    $title = $this->content()->get("multilineTitle")->toString();

    // Map the separator type to a separator string
    $separators = [
      'space' => ' ',
      'dash' => ' – ',
      'colon' => ': ',
    ];

    // Fall back to space by default in case an invalid separator type is provided somehow
    if (array_key_exists($separator, $separators)) {
      $separator = $separators[$separator];
    } else {
      $separator = $separators['space'];
    }

    // Replace line breaks by em spaces
    $title = preg_replace('/\R/u', $separator, $title);

    return new Field(
      $this,
      "title",
      $title
    );
  }

  /**
   * Return the page's multiline title as a string
   */
  public function multilineTitle(): string
  {
    return $this->content()->get('multilinetitle')->toString();
  }

  /**
   * Return the product's type
   */
  public function type(): string
  {
    return $this->type;
  }

  /**
   * Return the product's data, structured for the API
   */
  public function toData()
  {
    return [
      'titles' => [
        'inline' => $this->title()->toString(),
        'multiline' => $this->multilineTitle()
      ],
      'uuid' => $this->simpleUuid(),
      'slug' => $this->slug(),
      'type' => $this->type(),
    ];
  }
  /**
   * Return the product's data, structured for Algolia
   */
  public function toAlgoliaData()
  {
    return [
      // Required by Algolia, otherwise it'll be generated automatically
      'objectID' => $this->simpleUuid(),

      'title' => $this->multilineTitle(),
      'slug' => $this->slug(),
    ];
  }
}
