<?php

use Kirby\Cms\Page;

use auaust\products\WK1;

class ProductsPage extends Page
{
  public function sum()
  {
    return WK1::productsQuantity();
  }
}
