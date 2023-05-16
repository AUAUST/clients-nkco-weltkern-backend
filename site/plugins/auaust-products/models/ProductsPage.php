<?php

use Kirby\Cms\Page;

require_once __DIR__ . "/../classes/WK1.php";

use AUAUST\WK1 as WK1;

class ProductsPage extends Page
{
  public function cover()
  {
    // $quantity = WK1::productsQuantity();
    return WK1::products();
  }
}
