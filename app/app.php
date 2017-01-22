<?php

session_start();

function __autoload($class){

  if(file_exists(__DIR__.'/Controllers/'.$class.".php")){
    require_once(__DIR__.'/Controllers/'.$class.".php");
  }else{
    return false;
  }

}
