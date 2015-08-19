<?php

class gnarl{
  
  public $character_set = array();
  public $hash = null;
  public $url = null;
  public $redir = null;
  public $gnarURL = null;
  
  function __construct($url = null, $lookup = null){
    $this->character_set = str_split(CHAR_SET);
    if($url){
      $this->url = urlencode(trim($url));
      $this->gnarify($this->url);
    }
    if($lookup){
      $this->lookup($lookup);
    }
  }
  
  public function lookup($lookup){
    $db = new db();
    $res = $db->q("select url from urls where hash = '$lookup' limit 1");
    if($res->num_rows){
      $this->redir = urldecode($res->fetch_object()->url);
      return $this->redir;
    }else{
      header("HTTP/1.0 404 Not Found");
      include('../gnar.biz/404.html');
    }
  }
  
  function redir(){
    header("Location: $this->redir");
  }

  public function generateHashString($length = HASH_LENGTH){
    $hash = array();
    while(count($hash) < $length){
      $hash[] = $this->character_set[rand(0,count($this->character_set)-1)];
    }
    $this->hash = implode('',$hash);
    return $this->hash; 
  }
  
  public function hashExists($hash){
    if($hash){
      $db = new db();
      $res = $db->q("select id from urls where hash = '$hash'");
      return ($res->num_rows) ? true : false;
    }
  }
  
  public function urlExists($url){
    if($url){
      $db = new db();
      $res = $db->q("select hash from urls where url = '$url'");
      if($res->num_rows){
        return $res->fetch_object()->hash;
      }
    }  
  }
  
  public function createHash($new = true){
    while($new || $this->hashExists($hash)){
      unset($new);
      $hash = $this->generateHashString();
    }
    $this->hash = $hash;
    $this->storeHash();
    return $this->hash;
  }
  
  public function storeHash(){
    $db = new db();
    $res = $db->q("insert into urls (`hash`,`url`,`ip`,`datetime`) values('$this->hash','$this->url','$_SERVER[REMOTE_ADDR]','NOW()')");
  }
  
  public function validate($url){
    return filter_var(urldecode($url), FILTER_VALIDATE_URL);
  }
  
  public function gnarify($url = null){
    if(!$url || !$this->validate($url)){
      return false;
    }
    if($hash = $this->urlExists($url)){
      $this->gnarurl = SITE_PREFIX . $hash;
    }else{
      $this->gnarurl = SITE_PREFIX . $this->createHash();    
    }
    return $this->gnarurl; 
  }
  
}
?>
