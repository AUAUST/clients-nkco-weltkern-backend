<?php

use AUAUST\products\WK1;

class ProductBookPage extends ProductPage
{
  public function sum()
  {
    return WK1::productsQuantity();
  }

  public function dataArray()
  {
    $default = parent::dataArray();

    $extension = [
      'isbn' => $this->isbn()->value(),
    ];

    return array_merge(
      $default,
      $extension
    );
  }
}
