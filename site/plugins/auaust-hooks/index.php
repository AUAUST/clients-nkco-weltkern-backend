<?php

use Kirby\Cms\App as Kirby;

Kirby::plugin("auaust/hooks", [
  'hooks' => [
    'page.create:after' => function ($page) {

      // Switch by intended template rather than actual template
      switch ($page->intendedTemplate()->name()) {

          /*
        After a new designer page is created
          */
        case "designer":
          // Get the designer's name
          $fullname = $page->title();

          // Split the name into first and last name.
          // "Anthony Eric Monnier" => ["Anthony", "Eric", "Monnier"]
          $nameparts = explode(" ", $fullname);
          // "Monnier"
          $lastname = array_pop($nameparts);
          // "Anthony Eric"
          $firstname = implode(" ", $nameparts);

          // Update the page's "name" field (object with firstname and lastname)
          $page->update([
            "name" => [
              "firstname" => $firstname,
              "lastname" => $lastname
            ]
          ]);
      }
    }
  ]
]);
