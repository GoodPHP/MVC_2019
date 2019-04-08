<?php
if(!defined("LOAD")){ header('HTTP/1.0 404 Not Found');  die('Not Found'); }

class cmsUsers{

  private static $instance;

  // ============================================================================ //
  // ============================================================================ //

  public $user;

  /**
  * Load function from class self
  * @return bool
  */
  public static function getInstance() {
      if (self::$instance === null) {
        self::$instance = new self;
      }
      return self::$instance;
  }

  /**
  * Set cookie for user
  * @param string $name
  * @param string $id
  * @param string $time
  * @param string $page
  * @return boot
  */
  public function setCookieUser($name,$id,$time,$page = '/'){
    return setcookie($name, $id, $time, $page);
  }

  /**
  * If user is auth or not
  * @return boot
  */
  public function isAuth(){
    if(isset($_SESSION['btc_uid'])){
      return true;
    }else{
      return false;
    }
  }

  /**
  * Get id of user
  * @return int
  */
  public function getId(){
    if($_SESSION['btc_uid']){
      return $_SESSION['btc_uid'];
    }
  }

  /**
  * Get information about user
  * @return array
  */
  public function getUser(){
    $id = $this->getId();

    $db = cmsDatabase::getInstance();
    if($user = $db->get_fields('btc_users','id='.$id,'*')){
      return $user;
    }else{
      return false;
    }
  }

  /**
  * Give data from btc_users
  * @param string $id
  * @param string $field
  * @return array
  */
  public function getDataByID($id,$field){
    $get = $this->db->get_field('btc_users','id='.$id,'id');
    return $get;
  }

}
