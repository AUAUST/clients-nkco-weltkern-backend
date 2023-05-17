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
        // echo simple props map (only keys, not values)
        // max 5 props per line
        $props = array_keys($product);
        $props = array_chunk($props, 5);
        foreach ($props as $prop) {
          echo implode(', ', $prop) . '<br>';
        }
      }
      ?></pre>
