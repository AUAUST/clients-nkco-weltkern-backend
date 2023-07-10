<?php

use AUAUST\Products\WK1;

class BookPage extends ProductPage
{
  protected string $type = 'book';

  public function toData()
  {
    $default = parent::toData();

    $extension = [
      'isbn' => $this->isbn()->value(),
    ];

    return array_merge(
      $default,
      $extension
    );
  }

  public function toAlgoliaData()
  {
    $default = parent::toAlgoliaData();

    $extension = [
      'isbn' => $this->isbn()->value(),
    ];

    return array_merge(
      $default,
      $extension
    );
  }
}
