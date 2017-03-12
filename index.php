<?php header('X-Powered-By: QixStudio'); ?>
<?php

/**

Welcome to Standard Framework
The only framework you need 
to build simple web app
Set your route in app/route.php
Controllers are kept at app/Controllers
and Views are kept at app/Views

REST :
web::post('name') / web::get('name')

------------------------------------------------------------------------------------------------

Filters :
web::clean('type', $variable) // type => string, integer, or email, default

------------------------------------------------------------------------------------------------

@ Route :
'name' => ['uses' => 'nameController@method', 'with' => [1 => 'namePart1', 2 => 'namePart2']]
this "...[1 = 'namePart1']; will throw $_GET[1] to Controller Level with name $namePart. You can of course just like :
'name' => ['uses' => 'nameController@method']

------------------------------------------------------------------------------------------------

@ Controller :
Catch Variable from route with define args in method
ex : function index ($args) {} 
all variable named in route then can be catched as it exists on url

web::view('name') to view page in folders app/views
web::view('name', ['title' => 'This is Title']) // $var = array, let you throw variable to view.

Session can be set with :
Session::set('name','value'); // to create single session
Session::set($array); // to create session with array 

------------------------------------------------------------------------------------------------
@ View :
web::part('header'); // to call the file named header.php in views folder

web::css('name'); // to create <link> with href to name. For inline purpose, you can write :
web::css('begin'); // <stylle>
web::css('end'); // </style>

web::js('name'); // to create <script></script> with src to name. For inline purpose, you can write :
web::js('begin'); // <script>
web::js('end'); // </script>


------------------------------------------------------------------------------------------------

@ Model
Select :
$model = new Model;
$query = $model->query("SELECT * FROM TABLE WHERE CONDITION=1 ORDER BY sorts LIMIT 1"); // complex query
$query = $model->select('`table1`, `table2`', '*', ' `table1`.field = `table2`.field', '10'); // select
$query = $model->insert('table', ['id' => null, 'name' => 'Adams', 'last_name' => 'Bryan']); // insert
$query = $model->multiinsert('table', $array); // insert multiple values
$query = $model->set('table', ['name' => 'Bryan', 'last_name' => 'Adams'], ['last_name' => 'Bryan']; // update
$query = $model->delete('table', ['id'=>1]); // delete

**/

if (file_exists(__DIR__.'/app/config.php')) require_once __DIR__.'/app/config.php'; else die('404');
if ( Config::get('environment') == 'development' ) {
  error_reporting(E_ALL);
}else{
  error_reporting(0);}
if (file_exists(__DIR__.'/app/app.php')) require_once __DIR__.'/app/app.php'; else die('404');
if (file_exists(__DIR__.'/app/web.php')) require_once __DIR__.'/app/web.php'; else die('404');
if (file_exists(__DIR__.'/app/model.php')) require_once __DIR__.'/app/model.php'; else die('404');


$web = new web;
$route = $web->routing();
$paths = explode('/', web::path());
if (!class_exists($route['controller']))
{
  web::view('error');

}else{

  $controller = new $route['controller'];

  if(isset($route['with'])){
    if($route['with']) // just to prevent error
    {
      foreach ($route['with'] as $key => $val) $vars[$key] = $val;
        foreach ($vars as $var => $val) $attribute[$val] = isset($paths[$var]) == true ? $paths[$var] : null ;
    }
  }
  $controller->{$route['action']}(isset($attribute) == true ? $attribute : false);
}
