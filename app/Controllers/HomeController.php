<?php

class HomeController {

  function index(){

    web::view('home', ['text' => ['Hello World!']]);

  }

  function defined ($attr = false) {
    
    $category = $attr['category'];
    // catch query string with web::get('');
    $another = web::get('another') ? web::get('another') : 'default';
    
    web::view('defined', ['category' => $category, 'id' => $attr['id'], 'another' => $another]);
  
  }

}
