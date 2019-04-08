<?php
if(!defined("LOAD")){ header('HTTP/1.0 404 Not Found');  die('Not Found'); }
class loginModel {

  protected $db;

  function __construct($db){
    $this->db = $db;
  }

  public function getUser($email){
    return $this->db->get_fields('btc_users',"email='$email'",'*');
  }

  public function getCountUser($email){
    $sql = $this->db->query("SELECT id FROM btc_users WHERE email='$email'");
    $num = $this->db->num_rows($sql);
    return $num;
  }

  public function updateUser($time,$id){
    return $this->db->query("UPDATE btc_users SET time_signin='$time' WHERE id=".$id);
  }

  public function updateUserForgot($set){
    if($set){
      return $this->db->query("UPDATE btc_users SET ".$set);
    }
  }

  public function getDataByHash($hash){
    return $this->db->get_fields('btc_users','email_hash="'.$hash.'"','*');
  }

  public function setPassword($pass,$id){
    return $this->db->query("UPDATE btc_users SET password='".$pass."',email_hash='' WHERE id=".$id);
  }

}
