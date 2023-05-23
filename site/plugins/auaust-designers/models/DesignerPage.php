<?php

use Kirby\Cms\Field;
use Kirby\Cms\Page as Page;
use Kirby\Data\Data;

class DesignerPage extends Page
{
  public static function create(array $props): Page
  {
    // Split the name into first and last name.
    // "Anthony Eric Monnier" => ["Anthony", "Eric", "Monnier"]
    $nameparts = explode(" ", $props['content']['title']);
    // "Monnier"
    $lastname = array_pop($nameparts);
    // "Anthony Eric"
    $firstname = implode(" ", $nameparts);

    // Update the page's "name" field (object with firstname and lastname)
    $props['content']['name'] = [
      "firstname" => $firstname,
      "lastname" => $lastname
    ];

    return parent::create($props);
  }

  public function title()
  {
    $name = $this->content()->get("name")->toObject();

    return new Field(
      $this,
      "title",
      $name->firstname() . " " . $name->lastname()
    );
  }
}
