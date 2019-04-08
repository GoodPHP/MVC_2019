<?php
if(!defined("LOAD")){ header('HTTP/1.0 404 Not Found');  die('Not Found'); }

class registerModel {

  protected $db;

  function __construct($db){
    $this->db = $db;
  }

  public function getCountUser($where){
    $sql = $this->db->query("SELECT id FROM btc_users WHERE ".$where);
    $num = $this->db->num_rows($sql);
    return $num;
  }

  public function insert($sql){
    return $this->db->query("INSERT INTO btc_users (username,email,password,status,email_hash,time_signup,ip) VALUES".$sql);
  }

}
