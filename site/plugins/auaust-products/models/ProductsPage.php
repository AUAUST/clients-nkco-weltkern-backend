<?php

use Kirby\Cms\Page;

use auaust\products\WK1;

class ProductsPage extends Page
{
  public function cover()
  {
    // $quantity = WK1::productsQuantity();
    return WK1::products();
  }
  public function sum()
  {
    return WK1::productsQuantity();
  }
}
