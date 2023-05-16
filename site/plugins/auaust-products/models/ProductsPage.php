<?php

use Kirby\Cms\Page;

use AUAUST\WK1;

class ProductsPage extends Page
{
  public function cover()
  {
    $quantity = WK1::productsQuantity();
    return "The page model is working and Weltkern has {$quantity} products.";
  }
}
