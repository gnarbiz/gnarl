<?php

class db{

  public $db = null;
  
  function __construct(){
    $this->db = new mysqli(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DATABASE);
  }

  function q($query){
    $res = $this->db->query($query);
    if($this->db->error){
      die($this->db->error);
    }
    return $res;  
  }
   
}

?>