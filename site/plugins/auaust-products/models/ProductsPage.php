<?php

use Kirby\Cms\Page;

use AUAUST\Products\WK1;

class ProductsPage extends Page
{
  public function sum()
  {
    return WK1::productsQuantity();
  }
}
