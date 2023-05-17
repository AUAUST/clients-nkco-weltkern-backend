<pre><?php

      use auaust\products\WK1;

      $products = WK1::products();
      $quantity = WK1::productsQuantity();

      foreach ($products as $index => $product) {
        // pad left index (1 -> 0001)
        $index = str_pad($index + 1, 3, '0', STR_PAD_LEFT);
        // {{ index }} : {{ product.name }}
        echo "============================== " . $index . " ==============================<br>";
        echo $product['name'] . '<br>';
        echo "-----------------------------------------------------------------<br>";
      }
      ?></pre>
