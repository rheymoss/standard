<?php


class Session {
  public $var;
  private $state;

  static function check(){
    if ( session_status() == PHP_SESSION_NONE ) {
      session_start();
      return true;
    }
    return true;
  }

  static function set($sess, $altVal = false){
    Session::check();
    if (is_array($sess) || is_object($sess)) {
      foreach ($sess as $key => $val) $_SESSION[$key] = $val;
    }else{
      $_SESSION[$sess] = isset($altVal) ? $altVal : null;
    }
  }
  static function clear(){
    session_destroy();
  }
  static function get($sess){
    if(Session::check())
      if (is_array($sess) || is_object($sess)) {
        foreach ($_SESSION as $key => $val) {$result[$key] = $val;}
          return $result;
      }else{
        return isset($_SESSION[$sess]) ? $_SESSION[$sess] : false;
      }
  }
  static function remove($sess){
    if(isset($_SESSION[$sess])) unset ($_SESSION[$sess]);
  }

}//class sess

class web
{
  private $request;
  private $with;
  static function domain() {return $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'];}
  function asset() {return $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'];}

  static function path(){
    return urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
  }
  
  static function css($url){
    if($url !== 'begin' && $url !== 'end') {
      echo '<link rel="stylesheet" type="text/css" href="'.$url.'" />';
    }else{
      echo $url=='end' ? '</style>' : '<style>';
    }
  }

  static function js($url){
    if($url !== 'begin' && $url !== 'end') {
      echo '<script src="'.$url.'"></script>';
    }else{
      echo $url=='end' ? '</script>' : '<script>';
    }

  }
  static function clean($type, $variable) {
    $type = $type!==false ? $type : 'default';
    $variable = $variable ? $variable : $type;
    switch ($type) {
      case "html" :
        return htmlspecialchars($variable);
      break;  
      case "url" : 
        $result = preg_replace("/[^A-Za-z0-9:=\/&\-\_]/", '-', $variable);
        return $result;
      break;    
      case "string" :
      case "str":  
        $result = preg_replace("/[^A-Za-z]/", ' ', $variable);
        return $result;
      break;
      case "integer" :
      case "int":
        $result = preg_replace("/[^0-9]/", '', $variable);
        return filter_var($result, FILTER_SANITIZE_NUMBER_INT);
      break;  
      case "email":
        return filter_var($variable, FILTER_SANITIZE_EMAIL);
      break;  
      default :
        $result = preg_replace("/[^A-Za-z0-9]/", ' ', $variable);
        return $result;
    }
  }
  static function get($var = false, $filter = false){
    $url = str_replace('_url=/', '',$_SERVER['QUERY_STRING']);
    $url = explode('&', $url);
    if(is_array($var) || is_object($var)){
      foreach($url as $key)
        if( sizeOf(explode('=', $key)>1) )
        {
          $vars[explode('=', $key)[0]] = isset(explode('=', $key)[1])?explode('=', $key)[1]:null;
        }else{
          $vars[$key];
        }
      return $vars;
    }else{
      if($filter !== false) {
        return isset($_GET[$var]) ? web::clean($filter, $_GET[$var]) : false;  
      }else{
        return isset($_GET[$var]) ? $_GET[$var] : false;
      }
      
    }
  }

  static function post($var = false){
    if($var == 'all'){
      return $_POST;
    }elseif($var !== false){
      if(is_array($var) || is_object($var)) {
        foreach ($var as $key) return isset($_POST[$key])? $_POST[$key] : false;
      }else{
        return isset($_POST[$var]) ? $_POST[$var] : false;
      }
    }else{
      return isset($_POST[$var]) ? $_POST[$var] : false;
    }
  }

  function route(){
    return require_once('route.php');
  }

  function routing(){
    $path = $this->path();
    $route = $this->route();
    $paths = explode('/', $path);
    if(isset($route[$path])){
      $controller = explode('@', $route[$path]['uses'])[0];
      $action     = explode('@', $route[$path]['uses'])[1];
      if(isset($route[$path]['with'])){$vars = $route[$path]['with'];}
      return ['controller' => $controller, 'action' => $action, 'with' => false];
    }else if( isset($route[$paths[1]]) ){
      $controller = explode('@', $route[$paths[1]]['uses'])[0];
      $action     = explode('@', $route[$paths[1]]['uses'])[1];
      if(isset($route[$paths[1]]['with'])){$vars = $route[$paths[1]]['with'];}
      return ['controller' => $controller, 'action' => $action, 'with' => isset($vars) ? $vars : null];
    }else{
      if(isset($route[explode('/',$path)[0]])) {return ['controller' => $route[explode('/',$path)[0]], 'action' => 'index'];}
    }
  }

  function setRequest($vars){
    foreach($vars as $key => $val) $this->$key = $val;
  }

  function request($var){
    return $this->$val;
  }
  static function console($err){
    $err = addslashes($err);echo "<script>console.warn('$err');</script>";
  }
  static function error($err){echo json_encode('Error : '.$err); exit;}
  static function getView($view, $var = false){

    if ($var !== false) foreach($var as $varName => $val) $$varName = $val;
    if(file_exists(__DIR__.'/Views/'.$view.".php")){
      require_once(__DIR__.'/Views/'.$view.".php");
    }else{
      throw new Exception ('Views not found');
    }
  }

  static function view($view, $var = false){
    $view = str_replace('.php', '', $view);
    try{
      web::getView($view, $var);
    }catch(Exception $error){
        echo $error->getMessage();
    }
  }

  static function part($file){
    if(file_exists(__DIR__.'/Views/'.$file.".php")){
      require_once(__DIR__.'/Views/'.$file.'.php');
    }else{
      return false;
    }

  }



}
