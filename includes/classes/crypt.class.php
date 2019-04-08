<?php
if(!defined("LOAD")){ header('HTTP/1.0 404 Not Found');  die('Not Found'); }

class cmsCrypt{

  private static $function;
  private $memory_cost = 2048;
  private $time_cost = 4;
  private $threads = 3;

  /**
  * Load function from self
  * @return bool
  */
  public static function getInstance() {
		if (self::$function === null) {
			self::$function = new self;
		}
		return self::$function;
	}

  /**
  * Add encrypt to password.
  * @param string $password
  * @return string
  */
  public function setEncrypt($password){

    if($this->isMD5($password) == false){ return false; }

    $stored_hash = password_hash($password, PASSWORD_ARGON2I, ['memory_cost' => $this->memory_cost, 'time_cost' => $this->time_cost, 'threads' => $this->threads]);

    preg_match('#p=(.*?)#siU',$stored_hash,$hash,PREG_OFFSET_CAPTURE);

    return $hash[1][0];
  }

  /**
  * Generation right hash for checking
  * @param string $hash
  * @return string
  */
  public function genericHash($hash){
    $new_hash = '$argon2i$v=19';
    $new_hash .= '$m='.$this->memory_cost;
    $new_hash .= ',t='.$this->time_cost;
    $new_hash .= ',p='.$hash;
    return $new_hash;
  }

  /**
  * Cheaking is it MD5?
  * @param string $md5
  * @return boot
  */
  public function isMD5($md5=""){
    return preg_match("/^[a-f0-9]{32}$/", $md5);
  }

  /**
  * Vefir password between two
  * @param string $password
  * @param string $hash
  * @return int
  */
  public function checkPassword($password,$hash){
    if (password_verify($password, $hash)) {
        return 1;
    } else {
        return 0;
    }
  }

}
