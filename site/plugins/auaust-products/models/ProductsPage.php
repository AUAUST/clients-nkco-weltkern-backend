<?php

use Kirby\Cms\Page;
use Kirby\Http\Remote;

class ProductsPage extends Page
{
  public function cover()
  {
    return "The page model is working and Weltkern has {$this->getWeltkernProductsQuantity()} products.";
  }
}
