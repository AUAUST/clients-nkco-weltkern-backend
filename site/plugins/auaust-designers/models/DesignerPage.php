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

  public static function create(array $props): Page
  {
    // Update the page's "name" field default value
    $props['content']['names'] = self::splitName($props['content']['title']);
    // Exclude the page's "title" field
    $props['content']['title'] = null;

    // Create the page with updated props
    return parent::create($props);
  }

  private static function makeName(string $firstname, string $lastname): string
  {
    // Join the first and last name with a non-breaking space and trim the result
    return trim($firstname . "\u{00a0}" . $lastname, "\u{00a0} ");
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

  public function changeTitle(string $title, ?string $languageCode = null)
  {
    // Replace all kinds of spaces with a single space
    $title = preg_replace("/\s+/", ' ', $title);

    // Trim the title
    $title = trim($title);

    // The title is determined by the "name" field, so we need to update it rather than the title field
    // The title field is removed to prevent confusion (mismatch between the title field and the actual title)
    $page = $this->update([
      "title" => null,
      "names" => self::splitName($title)
    ]);

    return $page->checkSlug();
  }

  public function update(array $input = null, string $languageCode = null, bool $validate = false)
  {

    // If the names are being updated, check if the slug needs to be updated too
    if (isset($input["names"])) {
    }

    return parent::update($input, $languageCode, $validate);
  }

  private function checkSlug(): static
  {
    // If the new title implies a new slug, show a warning in the panel
    $expectedSlug = Str::slug($this->title());

    // If the slug is the same as the expected slug, there's no mismatch
    if ($this->slug() === $expectedSlug) {
      return $this->update([
        "hasMismatchedSlug" => "false",
        "ignoreMismatchedSlug" => "false"
      ]);
    }

    // Otherwise, show a warning in the panel
    return $this->update([
      "hasMismatchedSlug" => "true",
      "ignoreMismatchedSlug" => "false"
    ]);
  }
}
