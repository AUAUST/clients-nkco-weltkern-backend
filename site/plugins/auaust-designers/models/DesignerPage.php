<?php

use Kirby\Cms\Field;
use Kirby\Cms\Page as Page;
use Kirby\Data\Data;

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

    return new Field(
      $this,
      "title",
      // Join the first and last name with a non-breaking space
      $name->firstname() . "\u{00a0}" . $name->lastname()
    );
  }

  public function changeTitle(string $title, ?string $languageCode = null)
  {
    // The title is determined by the "name" field, so we need to update it rather than the title field
    // The title field is removed to prevent confusion (mismatch between the title field and the actual title)
    $page = $this->update([
      "title" => null,
      "names" => self::splitName($title)
    ]);

    return $page;
  }
}
