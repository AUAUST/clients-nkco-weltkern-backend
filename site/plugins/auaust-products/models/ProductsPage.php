<?php

use Kirby\Cms\Page;

use AUAUST\products\WK1;

class ProductsPage extends Page
{
  public function sum()
  {
    return WK1::productsQuantity();
  }
}
