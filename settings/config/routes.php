<?php

return [
  [
    'pattern' => 'my/awesome/url',
    'action'  => function () {
      return [
        'template' => 'my/awesome/template',
        'data'     => [
          'title' => 'My awesome page',
          'text'  => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'
        ]
      ];
    }
  ],
  [
    'pattern' => 'my/second/url',
    'action'  => function () {
      // ...
    }
  ]
];
