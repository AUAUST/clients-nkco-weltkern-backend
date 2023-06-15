<?php

use Kirby\Cms\Page;
use Kirby\Cms\Field;

use AUAUST\products\WK1;

class ProductBookPage extends Page
{
  public function sum()
  {
    return WK1::productsQuantity();
  }

  private static function splitName(string $name)
  {
    // See if there are pipes to split in the page's title
    // "Un livre:|Une histoire" => Un livre:\nUne histoire
    // Handles \| to escape pipes
    $nameparts = preg_split('/(?<!\\\)\|/', $name);

    $title = implode(PHP_EOL, $nameparts);

    return $title;
  }

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
   * Return the book's data, structured for the API
   */
  public function dataArray()
  {
    return [
      'title' => $this->title()->toString(),
      'multilineTitle' => $this->content()->get("multilineTitle")->toString(),
      'uuid' => $this->uuidValue(),
      'slug' => $this->slug(),
      'isbn' => $this->isbn()->toString() || null,
    ];
  }
}
