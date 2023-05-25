<?php

use Kirby\Cms\Field;
use Kirby\Cms\Page as Page;
use Kirby\Toolkit\Str;

class DesignerPage extends Page
{

  private static function splitName(string $name): array
  {
    // Try to guess the designer's full name from the page's title at creation
    // "Anthony Eric Monnier" => ["Anthony", "Eric", "Monnier"]
    $nameparts = explode(" ", $name);

    // "Monnier"
    $lastname = array_pop($nameparts);

    // "Anthony Eric"
    $firstname = implode("\u{00a0}", $nameparts);

    return [
      "firstname" => $firstname,
      "lastname" => $lastname
    ];
  }

  private static function makeName(string $firstname, string $lastname): string
  {
    // Join the first and last name with a non-breaking space and trim the result
    return trim($firstname . "\u{00a0}" . $lastname, "\u{00a0} ");
  }

  public static function create(array $props): Page
  {
    // Update the page's "name" field default value
    $props['content']['names'] = self::splitName($props['content']['title']);
    // Exclude the page's "title" field
    $props['content']['title'] = null;

    // Create the page with updated props
    return parent::create($props);
  }

  public function title()
  {
    // Get the designer's name from the "name" field
    $name = $this->content()->get("names")->toObject();


    $name = self::makeName(
      $name->firstname(),
      $name->lastname()
    );

    return new Field(
      $this,
      "title",
      $name
    );
  }
}
