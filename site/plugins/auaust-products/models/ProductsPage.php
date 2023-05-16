<?php

use Kirby\Cms\Page;

class ProductsPage extends Page
{
  public function cover()
  {
    return $this->images()->first();
  }
}
