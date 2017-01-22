<?php
// Routes
// all param can be defined as in with
// with => [1 => X] define $X = first of $_GET

$route =
  [
      '/' => ['uses' => 'HomeController@index'],
      'defined' => ['uses' => 'HomeController@defined', 'with' => [1 => 'category', 2 => 'id']],
  ];

return $route;
