<small>

  <?php dump($page->title()) ?>
</small>
<p>
  <?php
  $string =
    "Il l'avait dit! \"C'est moi\", qu'il avait dit. (C'est moi.) [C'est moi.] {C'est moi.} <C'est moi.> Pourquoi? demanda-t-il.";
  echo ($string);
  echo ("<br><br><br>");
  echo (smartypants($string))
  ?>
</p>
