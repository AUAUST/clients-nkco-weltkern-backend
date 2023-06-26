<?php

use AUAUST\products\WK1;

class BookPage extends ProductPage
{
  public function sum()
  {
    return WK1::productsQuantity();
  }

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
}
