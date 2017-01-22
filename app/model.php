<?php

class Model extends mysqli{

  protected $mysqli;
  private  $db_host;
  private  $db_name;
  private  $db_username;
  private  $db_password;
  private  $db_database;
  private  $update = false;
  private  $set;
  private  $where;
  private  $with = false;

  public function __construct()
  {
    $this->db_host = Config::get('db_host');
    $this->db_name = Config::get('db_name');
    $this->db_username = Config::get('db_username');
    $this->db_password = Config::get('db_password');
    $this->mysqli=new mysqli($this->db_host,$this->db_username,
    $this->db_password,$this->db_name) or die($this->mysqli->error);

    return $this->mysqli;
  }

  function query($query){
    $this->mysqli->query($query);
    return $this->mysqli->query($query);
  }

  public function ok(){
    return 'ok';
  }
  public function real_escape_string($str)
  {
    return $this->mysqli->real_escape_string($str);
  }

  function __destruct(){$this->mysqli->close();}

  function select($table = false, $field = false, $condition = false, $limit = false)
  {
    /** Easy way #field = "id, name" **/
    $field = isset($field) ? $field : '*';
    if($limit !== false) { $limit = 'LIMIT '.$limit;}
    $condition = isset($condition)? ' where '.$condition : '1=1';
    if($table === false){
      // throw new Exception(['status' => 'false', 'text' => 'Error : 100']);
    }else{

      $query = 'SELECT ' .$field . ' FROM ' . $table. $condition. ' '.$limit;
//      echo $query;
//         echo $query;
        $result = $this->mysqli->query("$query");
        if($result){
          if($result->num_rows < 1) {return ['status' => false, 'text' => 'No Result', 'data' => $result];}else{
          return ['status' => true, 'text' => $result->num_rows.' found' , 'data' => $result->fetch_all(MYSQL_ASSOC)];}
        }
    }
      // foreach(func_get_args() as $key => $val) $$key = $val; // later
      // return $this->mysqli->query($query);
  }

  function find($table, $condition)
  {
    if(!isset($table)) return false;
    if(!isset($condition)) return false;
    echo "SELECT * FROM $table WHERE $condition";
    $result = $this->mysqli->query("SELECT * FROM $table WHERE $condition");
    if($result) return $result->fetch_all(MYSQL_ASSOC);

  }
  
  function multiinsert($table, $var, $duplicate = false)
  {
    
    $reference = [];
    if(Session::get('orderCreated') === false){
    foreach ($var as $elem) 
    {
        $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($elem));
        $i = 0;
        $target = '';
        $values = '';
        foreach($it as $v => $a) {
          $target .= '`'.$v.'`';
          $values .=  "'$a'";
          $i++;
          $target .= $i == sizeOf($elem) ? '' : ',';
          $values .= $i == sizeOf($elem) ? '' : ',';
        }
        $query = 'INSERT INTO ' . '`' . $table. '`' . ' ('.$target.')' . ' VALUES ' . '('.$values.')';
        if($this->mysqli->query($query)){
          array_push($reference,$this->mysqli->insert_id);
        }else{
          array_push($reference,'false');
        } 
    }
    Session::set(['orderCreated'=>$reference]);
  }else{
    $reference = Session::get('orderCreated');
  }
  return ['status' => true, 'reference' => $reference];
    
}
  
  function insert($table, $var)
  {

    $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($var));
    $i = 0;

    $target = '';
    $values = '';
    foreach($it as $v => $a) {
      $target .= '`'.$v.'`';
      $values .=  "'$a'";
      $i++;
      $target .= $i == sizeOf($var) ? '' : ',';
      $values .= $i == sizeOf($var) ? '' : ',';
    }

    $query = 'INSERT INTO ' . '`' . $table. '`' . ' ('.$target.')' . ' VALUES ' . '('.$values.')';
    
    if($this->mysqli->query($query)){
      return ['status' => true, 'message' => 'Succeed','reference'=>$this->mysqli->insert_id ];
    }else{
      return ['status' => false, 'message' => 'Failed'];
    }
  }

  function where($condition){
    $this->where = $condition;
    return $condition;
  }

  // function update($table, $set = false, $condition = false){
  //   $result = $this->mysqli->query("UPDATE $table SET $set WHERE $condition");
  //   return $result;
  // }
  function delete($table, $conditions){
    $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($conditions));
    $i = 0;
    $condition = '';
    foreach($it as $v => $a) {
      $condition .=  '`'.$v.'`' ."='$a'";
      $i++;
      $condition .= $i == sizeOf($conditions) ? '' : ',';
    }
    $query = 'DELETE FROM `' . $table . '` WHERE '. $condition;
    $result = $this->query($query);

    if($result){
      return ['status' => true, 'message' => 'Succeed'];
    }else{
      return ['status' => false, 'message' => 'Failed'];
    }

  }
  function set($table, $set, $condition){
    $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($set));
    $i = 0;
    $sets = '';
    foreach($it as $v => $a) {
      $sets .=  '`'.$v.'`' ."='$a'";
      $i++;
      $sets .= $i == sizeOf($set) ? '' : ',';
    }

    $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($condition));
    $i = 0;
    $condition = '';
    foreach($it as $v => $a) {
      $condition .=  '`'.$v.'`' ."='$a'";
      $i++;
      $condition .= $i == sizeOf($condition) ? '' : 'AND';
    }
    $query = 'UPDATE `'.$table.'` SET '.$sets.' WHERE '.$condition;
//    echo "$query";
    $result = $this->query($query);
    return ['status'=> true, 'message'=>'Succeed', 'data'=>$set];

  }

  function MainMenu(){
    $menu = new Model;
    return $menu->select('menu', '*', '1=1');
  }

}

