<?php

use Kirby\Cms\Page;
use Kirby\Http\Remote;

class ProductsPage extends Page
{
  public function cover()
  {
    return "The page model is working and Weltkern has {$this->getWeltkernProductsQuantity()} products.";
  }

  private function getWeltkernProductsQuantity()
  {
    $response = Remote::get("https://api.weltkern.com/wp-json/custom-routes/v1/products/total");

    if ($response->code() !== 200) {
      return 0;
    }

    $quantity = intval($response->content());

    if ($quantity < 0) {
      return 0;
    }

    return $quantity;
  }
}
