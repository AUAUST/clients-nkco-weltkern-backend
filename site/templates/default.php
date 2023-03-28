<small>

  <?php dump($page->title()) ?>
</small>
<p>
  <?php
  $string =
    "Il l'avait: dit; à moi! \"C'est moi\", qu'il avait dit. - `simple` -- au --- `double` (C'est moi.) [C'est moi.] {C'est moi.} Pourquoi? demanda-t-il.";
  echo ($string);
  echo ("<br><br><br>");
  echo ($kirby->smartypants($string))
  ?>
</p>
