<?php

use Kirby\Cms\Field;
use Kirby\Cms\Page as Page;
use Kirby\Data\Data;

class DesignerPage extends Page
{
  public static function create(array $props): Page
  {

    // Try to guess the designer's full name from the page's title at creation
    // "Anthony Eric Monnier" => ["Anthony", "Eric", "Monnier"]
    $nameparts = explode(" ", $props['content']['title']);

    // "Monnier"
    $lastname = array_pop($nameparts);

    // "Anthony Eric"
    $firstname = implode("\u{00a0}", $nameparts);

    // Update the page's "name" field default value
    $props['content']['name'] = [
      "firstname" => $firstname,
      "lastname" => $lastname
    ];

    // Create the page with updated props
    return parent::create($props);
  }

  public function title()
  {
    // Get the designer's name from the "name" field
    $name = $this->content()->get("name")->toObject();

    return new Field(
      $this,
      "title",
      // Join the first and last name with a non-breaking space
      $name->firstname() . "\u{00a0}" . $name->lastname()
    );
  }
}
