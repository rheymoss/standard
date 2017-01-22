<?php

class Config {
  
  static function get($var = false) {
    
    $conf = [
      'environment' => 'development', // development, test, production
      'debug' => '',
      'domain' => $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'],
      
      /** MYSQL **/
      
      'db_host' => "127.0.0.1",
      'db_username' => '',
      'db_password' => '',
      'db_name' => '',

    ];
    
    if (isset($conf[$var])) return $conf[$var];
      
  }

  
  
}

