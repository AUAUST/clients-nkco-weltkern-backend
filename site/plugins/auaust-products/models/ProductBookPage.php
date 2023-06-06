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
    // Get the title from the multilineTitle field
    $title = $this->content()->get("multilineTitle")->toString();

    // Replace line breaks by em spaces
    $title = preg_replace('/\R/u', ' ', $title);

    return new Field(
      $this,
      "title",
      $title
    );
  }
}
