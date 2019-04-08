<?php
if(!defined("LOAD")){ header('HTTP/1.0 404 Not Found');  die('Not Found'); }

ob_start();
session_start();
//error_reporting(0);
if(file_exists($_SERVER['DOCUMENT_ROOT']."/install.php") && !file_exists($_SERVER['DOCUMENT_ROOT']."/config/config.php") && $_SERVER['REQUEST_URI'] != '/install.php') {
	header("Location: ./install.php");
}

ini_set("display_errors",1);
error_reporting(E_ALL);

$core = cmsCore::getInstance();
class cmsCore{

  private static $function;

	public $config = array();

  // ============================================================================ //
  // ============================================================================ //

  private function __construct(){
    self::loadClass('database');
		self::loadClass('crypt');
		self::loadClass('mvc');
		self::loadClass('theme');
		self::loadClass('phpmailer');
		self::loadClass('users');

		$this->config = array(
			'config' => $_SERVER['DOCUMENT_ROOT'].'/config/config.php',
			'block_io_config' => $_SERVER['DOCUMENT_ROOT'].'/config/block_io_config.php',
			'table' => $_SERVER['DOCUMENT_ROOT'].'/config/table.php',
			'theme' => $_SERVER['DOCUMENT_ROOT'].'/config/theme.php',
		);

  }

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
  * Load class from file /includes/classes/XXX.class.php, where is XXX = $class
  * @param string $class
  * @return bool
  */
  public static function loadClass($class){
      return include('classes/'.$class.'.class.php');
  }

	/**
  * Receives a $var variable from $ _REQUEST according to the specified type
  * @param string $var variable name
  * @param string $type type int | str | html | email | array | array_int | array_str | permissible array
  * @param string $default default
  * @param string $r Where to get the value get | post | request
  * @return mixed
  */
	public static function request($var, $type='str', $default=false, $r = 'request'){
		switch ($r) {
			case 'post':
			$request = $_POST;
			break;
			case 'get':
			$request = $_GET;
			break;
			default:
			$request = $_REQUEST;
			break;
		}
		if (isset($request[$var])){
			return self::cleanVar($request[$var], $type, $default);
		} else {
			return $default;
		}
	}

	/**
  * Clearing a variable from unnecessary characters
  * @param string $var variable name
  * @param string $type type int | str | html | email | array | array_int | array_str | permissible array
  * @param string $default default
  * @return mixed
  */
	public static function cleanVar($var, $type='str', $default=false) {
		if(is_array($type)){
			if(in_array($var, $type)){
				return self::strClear((string)$var);
			} else {
				return $default;
			}
		}
		switch($type){
			case 'int':   if ($var!=='') { return (int)$var;  } else { return (int)$default; } break;
			case 'str':   if ($var) { return self::strClear((string)$var); } else { return (string)$default; } break;
			case 'email': if(preg_match("/^(?:[a-z0-9\._\-]+)@(?:[a-z0-9](?:[a-z0-9\-]*[a-z0-9])?\.)+(?:[a-z]{2,6})$/ui", (string)$var)){ return $var; } else { return (string)$default; } break;
			case 'html':  if ($var) { return self::strClear((string)$var, false); } else { return (string)$default; } break;
			case 'array': if (is_array($var)) { foreach($var as $k=>$s){ $arr[$k] = self::strClear($s, false); } return $arr; } else { return $default; } break;
			case 'array_int': if (is_array($var)) { foreach($var as $k=>$i){ $arr[$k] = (int)$i; } return $arr; } else { return $default; } break;
			case 'array_str': if (is_array($var)) { foreach($var as $k=>$s){ $arr[$k] = self::strClear($s); } return $arr; } else { return $default; } break;
		}
	}

	/**
  * Cheaking on sending this variable
  * @param string $var variable name
  * @return boot
  */
	public static function inRequest($var){
		return isset($_REQUEST[$var]);
	}

	/**
  * For cleaning string variable
  * @param string $var variable name
  * @return boot
  */
	public static function strClear($input, $strip_tags=true){
		if(is_array($input)){
			foreach ($input as $key=>$string) {
				$value[$key] = self::strClear($string, $strip_tags);
			}
			return $value;
		}
		$string = trim((string)$input);
		//If magic_quotes_gpc = On, first remove the screening
		$string = (@get_magic_quotes_gpc()) ? stripslashes($string) : $string;
		$string = rtrim($string, ' \\');
		if ($strip_tags) {
			$string = strip_tags($string);
		}
		return $string;
	}

	/**
  * Function to launch the apps
  * @return boot
  */
	public function initCore(){
		$mvc = cmsMVC::getInstance();
		$theme = cmsTheme::getInstance();
		$theme->getTheme($mvc);
	}

	/**
  * Get site url
  * @return boot
  */
	public function siteUrl(){
		$db = cmsDatabase::getInstance();
		$url = $db->get_field('btc_settings','id=id','url');
		return $url;
	}

	/**
  * Redirect to url
  * @param string $url
  * @param string $code
  * @return boot
  */
	public static function redirect($url, $code='303'){
		if ($code == '301'){
			header('HTTP/1.1 301 Moved Permanently');
		} else {
			header('HTTP/1.1 303 See Other');
		}
		header('Location:'.$url);
		self::halt();
	}

	/**
  * Kill site
  * @param string $message
  * @return boot
  */
	public static function halt($message=''){
		die((string)$message);
	}

	/**
  * Redirect to last url
  * @return boot
  */
	public static function redirectBack(){
		self::redirect(self::getBackURL(false));
	}

	/**
  * Get last url
  * @param string $is_request
  * @return string
  */
	public static function getBackURL($is_request = true){
		$back = '/';
		if(self::inRequest('back') && $is_request){
			$back = self::request('back', 'str', '/');
		} elseif(!empty($_SERVER['HTTP_REFERER'])) {
			$refer_host = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
			if($refer_host == $_SERVER['HTTP_HOST']){
				$back = strip_tags($_SERVER['HTTP_REFERER']);
			}
		}
		return $back;
	}

	/**
  * Error404 show
  * @return error404
  */
	public function error404(){
		header("HTTP/1.0 404 Not Found");
    header("HTTP/1.1 404 Not Found");
    header("Status: 404 Not Found");
		die('<h1>Error 404</h1>');
	}


	////////////////////////////////////////////////////////////////////////////
	/**
	 * Adds a message to the session
	 * @param string $message
	 * @param string $class
	 */
	public static function addSessionMessage($message, $class='danger'){
			$_SESSION['core_message'][] = '<div class="alert alert-'.$class.' alert-dismissible fade show" role="alert">'.$message.' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div> ';
	}

	/*
	 * Returns an array of messages stored in the session.
	 */
	public static function getSessionMessages(){

			if (isset($_SESSION['core_message'])){
					$messages = $_SESSION['core_message'];
			} else {
					$messages = false;
			}

			self::clearSessionMessages();
			return $messages;

	}

	/*
	 * Clears the session message queue
	 */
	public static function clearSessionMessages(){
			unset($_SESSION['core_message']);
	}

	// ============================================================================ //
	// ================================Function.php================================ //

	/**
  * Cheking username for valid
  * @param string $str
  * @return boot
  */
	public function isValidUsername($str) {
	    return preg_match('/^[a-zA-Z0-9-_]+$/',$str);
	}

	/**
  * Cheking emain for valid
  * @param string $str
  * @return boot
  */
	public function isValidEmail($str) {
		return filter_var($str, FILTER_VALIDATE_EMAIL);
	}

	/**
  * Generation user adress for wallet
  * @param string $username
  * @param string $label
  * @return mixed
  */
	public function btc_generate_address($username,$label) {
		include_once($_SERVER['DOCUMENT_ROOT']."/includes/classes/BlockIO/block_io.php");
		$db = cmsDatabase::getInstance();
		if(file_exists(cmsCore::getInstance()->config['block_io_config'])) {
			include(cmsCore::getInstance()->config['block_io_config']);
			$user_query = $db->query("SELECT * FROM btc_users WHERE username='$username'");
			$user = $user_query->fetch_assoc();
			$rand = $this->getRand(6);
			$label = 'usr_'.$username.'_'.$label.'_'.$rand;
			$apiKey = $license['license'];
			$pin = $license['secret_pin'];
			$version = 2; // the API version
			$block_io = new BlockIo($apiKey, $pin, $version);
			$new_address = $block_io->get_new_address(array('label' => $label));
			if($new_address->status == "success") {
				$addr = $new_address->data->address;
				$time = time();
				$insert = $db->query("INSERT INTO btc_users_addresses (uid,label,address,lid,available_balance,pending_received_balance,status,created) VALUES ('$user[id]','$label','$addr','$license[id]','0.00000000','0.00000000','1','$time')");
				$update = $db->query("UPDATE btc_blockio_licenses UPDATE addresses=addresses+1 WHERE id='$license[id]'");
				return $addr;
			} else {
				return false;
			}
		}
	}

	/**
  * Function for update balance in users
  * @param string $uid
  * @return null
  */
	public function btc_update_balance($uid) {
		include_once($_SERVER['DOCUMENT_ROOT']."/includes/classes/BlockIO/block_io.php");
		$db = cmsDatabase::getInstance();

		$get_address = $db->query("SELECT * FROM btc_users_addresses WHERE uid='$uid' AND archived = 0");
		if($get_address->num_rows>0) {
			while($get = $get_address->fetch_assoc()) {
				if(file_exists(cmsCore::getInstance()->config['block_io_config'])) {
					include(cmsCore::getInstance()->config['block_io_config']);
				}
				$user_address = $get['address'];
				$apiKey = $license['license'];
				$pin = $license['secret_pin'];
				$version = 2; // the API version
				$block_io = new BlockIo($apiKey, $pin, $version);
				$balance = $block_io->get_address_balance(array('addresses' => $user_address));
				if($balance->status == "success") {
					$time = time();
					$available_balance = $balance->data->available_balance;
					$pending_received_balance = $balance->data->pending_received_balance;
					$update = $db->query("UPDATE btc_users_addresses SET available_balance='$available_balance',pending_received_balance='$pending_received_balance' WHERE id='$get[id]' and uid='$uid'");
				}
			}
		}
	}

	/**
  * Function for update transactions in users
  * @param string $uid
  * @return null
  */
	public function btc_update_transactions($uid) {
		include_once($_SERVER['DOCUMENT_ROOT']."/includes/classes/BlockIO/block_io.php");
		$db = cmsDatabase::getInstance();

		$get_address = $db->query("SELECT * FROM btc_users_addresses WHERE uid='$uid' AND archived = 0");
		if($get_address->num_rows>0) {
			while($get = $get_address->fetch_assoc()) {
				if(file_exists(cmsCore::getInstance()->config['block_io_config'])) {
					include(cmsCore::getInstance()->config['block_io_config']);
				}
			$apiKey = $license['license'];
			$pin = $license['secret_pin'];
			$version = 2; // the API version
			$block_io = new BlockIo($apiKey, $pin, $version);
			$received = $block_io->get_transactions(array('type' => 'received', 'addresses' => $get['address']));
			if($received->status == "success") {
				$data = $received->data->txs;
				$dt = $this->StdClass2array($data);
				foreach($dt as $k=>$v) {
					$txid = $v['txid'];
					$time = $v['time'];
					$amounts = $v['amounts_received'];
					$amounts = $this->StdClass2array($amounts);
					foreach($amounts as $a => $b) {
						$recipient = $b['recipient'];
						$amount = $b['amount'];
					}
					$senders = $v['senders'];
					$senders = $this->StdClass2array($senders);
					foreach($senders as $c => $d) {
						 $sender = $d;
					}
					$confirmations = $v['confirmations'];
						$check = $db->query("SELECT * FROM btc_users_transactions WHERE uid='$uid' and txid='$txid'");
						if($check->num_rows>0) {
							$update = $db->query("UPDATE btc_users_transactions SET confirmations='$confirmations' WHERE uid='$uid' and txid='$txid'");
						} else {
							$insert = $db->query("INSERT INTO btc_users_transactions (uid,type,recipient,sender,amount,time,confirmations,txid) VALUES ('$uid','received','$recipient','$sender','$amount','$time','$confirmations','$txid')");
						}
				}
			}
			$sent = $block_io->get_transactions(array('type' => 'sent', 'addresses' => $get['address']));
			if($sent->status == "success") {
				$data = $sent->data->txs;
				$dt = $this->StdClass2array($data);
				foreach($dt as $k=>$v) {
					$txid = $v['txid'];
					$time = $v['time'];
					$amounts = $v['amounts_sent'];
					$amounts = $this->StdClass2array($amounts);
					foreach($amounts as $a => $b) {
						$recipient = $b['recipient'];
						$amount = $b['amount'];
					}
					$senders = $v['senders'];
					$senders = $this->StdClass2array($senders);
					foreach($senders as $c => $d) {
						 $sender = $d;
					}
					$confirmations = $v['confirmations'];
						$check = $db->query("SELECT * FROM btc_users_transactions WHERE uid='$uid' and txid='$txid'");
						if($check->num_rows>0) {
							$update = $db->query("UPDATE btc_users_transactions SET confirmations='$confirmations' WHERE uid='$uid' and txid='$txid'");
						} else {
							$insert = $db->query("INSERT INTO btc_users_transactions (uid,type,recipient,sender,amount,time,confirmations,txid) VALUES ('$uid','sent','$recipient','$sender','$amount','$time','$confirmations','$txid')");
						}
				}
			}
			}
		}
	}

	/**
  * Function for update transactions in users
  * @param string $uid
  * @return null
  */
	public function btc_delete_fee_transactions($uid) {
		include_once($_SERVER['DOCUMENT_ROOT']."/includes/classes/BlockIO/block_io.php");
		$db = cmsDatabase::getInstance();

		$get_address = $db->query("SELECT * FROM btc_users_addresses WHERE uid='$uid' AND archived = 0");
		if($get_address->num_rows>0) {
			while($get = $get_address->fetch_assoc()) {
				if(file_exists(cmsCore::getInstance()->config['block_io_config'])) {
					include(cmsCore::getInstance()->config['block_io_config']);
				}
			$addr = $license['address'];
			$query = $db->query("SELECT * FROM btc_users_transactions WHERE uid='$uid' and type='sent'");
			if($query->num_rows>0) {
				while($row = $query->fetch_assoc()) {
					if($license['address'] >= $row['recipient']) {
						//$delete = $db->query("DELETE FROM btc_users_transactions WHERE id='$row[id]' and uid='$uid'");
					}
				}
			}
			}
		}
	}

	/**
  * Function for update prices for bitcoin
  * @param string $uid
  * @return null
  */
	function btc_get_bitcoin_prices() {
		include_once($_SERVER['DOCUMENT_ROOT']."/includes/classes/BlockIO/block_io.php");
		$db = cmsDatabase::getInstance();

		if(file_exists(cmsCore::getInstance()->config['block_io_config'])) {
			include(cmsCore::getInstance()->config['block_io_config']);
			$apiKey = $license['license'];
			$pin = $license['secret_pin'];
			$version = 2; // the API version
			$block_io = new BlockIo($apiKey, $pin, $version);
			$price = $block_io->get_current_price();
			$prices = $price->data->prices;
			$prices = $this->StdClass2array($prices);
			foreach($prices as $k => $v) {
				foreach($v as $a => $b) {
					$rows[$a] = $b;
				}
				$query = $db->query("SELECT * FROM btc_prices WHERE source='$rows[exchange]' and currency='$rows[price_base]'");
				if($query->num_rows>0) {
					$update = $db->query("UPDATE btc_prices SET price='$rows[price]' WHERE source='$rows[exchange]' and currency='$rows[price_base]'");
				} else {
					$insert = $db->query("INSERT INTO btc_prices (source,price,currency) VALUES ('$rows[exchange]','$rows[price]','$rows[price_base]')");
				}
			}
		}
	}


	public function StdClass2array($class)
	{
	    $array = array();

	    foreach ($class as $key => $item)
	    {
	            if ($item instanceof StdClass) {
	                    $array[$key] = $this->StdClass2array($item);
	            } else {
	                    $array[$key] = $item;
	            }
	    }

	    return $array;
	}

	/**
  * Generate random symbols
  * @param string $len
  * @return string
  */
	public function getRand($len) {
    $str = '';
    $a = "abcdefghijklmnopqrstuvwxyz0123456789";
    $b = str_split($a);
    for ($i=1; $i <= $len ; $i++) {
        $str .= $b[rand(0,strlen($a)-1)];
    }
    return $str;
	}

	/**
  * Get a random hash number
  * @param string $lenght
  * @return array
  */
	public function randomHash($lenght = 7) {
		$random = substr(md5(rand()),0,$lenght);
		return $random;
	}

	public function protect($string) {
		$protection = htmlspecialchars(trim($string), ENT_QUOTES);
		return $protection;
	}

	public function success($text) {
		return '<div class="alert alert-success alert-dismissible fade show" role="alert"><i class="fa fa-check"></i> '.$text.' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
	}

	public function errors($text) {
		return '<div class="alert alert-danger alert-dismissible fade show" role="alert"><i class="fa fa-times"></i> '.$text.' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
	}

	public function info($text) {
		return '<div class="alert alert-info alert-dismissible fade show" role="alert"><i class="fa fa-info-circle"></i> '.$text.' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
	}

	// ============================================================================ //
  // ============================================================================ //

	/**
  * Check is it ajax request or not
  * @return boot
  */
	public function isAjax(){
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {
			return true;
		}else{
			return false;
		}
	}

}

?>
