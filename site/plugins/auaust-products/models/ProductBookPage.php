<?php

use AUAUST\products\WK1;

class ProductBookPage extends ProductPage
{
  public function sum()
  {
    return WK1::productsQuantity();
  }
}
