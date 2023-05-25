<?php

use Kirby\Cms\App as Kirby;




// Load the DesignerPage model class
load([
  'DesignerPage' => 'models/DesignerPage.php',
], __DIR__);

Kirby::plugin("auaust/designers", [

  'pageModels' => [
    'designer' => 'DesignerPage'
  ],
  // 'hooks' => [
  //   'page.create:after' => function ($page) {

  //     // Switch by intended template rather than actual template
  //     switch ($page->intendedTemplate()->name()) {

  //         /*
  //       After a new designer page is created
  //         */
  //       case "designer":
  //         // Get the designer's name
  //         $fullname = $page->title();

  //         // Split the name into first and last name.
  //         // "Anthony Eric Monnier" => ["Anthony", "Eric", "Monnier"]
  //         $nameparts = explode(" ", $fullname);
  //         // "Monnier"
  //         $lastname = array_pop($nameparts);
  //         // "Anthony Eric"
  //         $firstname = implode(" ", $nameparts);

  //         // Update the page's "name" field (object with firstname and lastname)
  //         $page->update([
  //           "name" => [
  //             "firstname" => $firstname,
  //             "lastname" => $lastname
  //           ]
  //         ]);
  //     }
  //   },

  //   'page.update:before' => function ($page, $values, $strings) {

  //     switch ($page->intendedTemplate()->name()) {
  //         /*
  //       When a designer page is updated
  //         */
  //       case "designer":

  //         // $file = kirby()->root('content') . '/hooks.txt';

  //         $oldName = $page->title();
  //         $newName = $values["name"]["firstname"] . " " . $values["name"]["lastname"];

  //         if ($oldName !== $newName) {
  //           $page->changeTitle($newName);
  //         }
  //         // file_put_contents($file, dump(, false));


  //         // $name = ;

  //         // $newName = $name->firstname() . " " . $name->lastname();

  //     }
  //   }
  // ]
]);
