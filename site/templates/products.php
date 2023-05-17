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
        // print all product properties
        foreach ($product as $key => $value) {
          // nest if array
          if (is_array($value)) {
            echo $key . ':<br>';
            foreach ($value as $key => $value) {
              // nest if array
              if (is_array($value)) {
                echo '&nbsp;&nbsp;&nbsp;&nbsp;' . $key . ':<br>';
                foreach ($value as $key => $value) {
                  // nest if array
                  if (is_array($value)) {
                    echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $key . ':<br>';
                    foreach ($value as $key => $value) {
                      // nest if array
                      if (is_array($value)) {
                        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $key . ':<br>';
                        foreach ($value as $key => $value) {
                          // nest if array
                          if (is_array($value)) {
                            echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $key . ':<br>';
                            foreach ($value as $key => $value) {
                              // nest if array
                              if (is_array($value)) {
                                echo "too deep<br>";
                                continue;
                              }
                              echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $key . ': ' . $value . '<br>';
                            }
                            continue;
                          }

                          echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $key . ': ' . $value . '<br>';
                        }
                        continue;
                      }

                      echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $key . ': ' . $value . '<br>';
                    }
                    continue;
                  }

                  echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $key . ': ' . $value . '<br>';
                }
                continue;
              }

              echo '&nbsp;&nbsp;&nbsp;&nbsp;' . $key . ': ' . $value . '<br>';
            }
            continue;
          }
          echo $key . ': ' . $value . '<br>';
        }
      }
      ?></pre>
