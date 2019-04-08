<?php
if(!defined("LOAD")){ header('HTTP/1.0 404 Not Found');  die('Not Found'); }

	$license = array();
	$license["id"] = 1;
	$license["account"] = "";
	$license["license"] = "";
	$license["secret_pin"] = "";
	$license["address"] = "";
  $license["addresses"] = 0;
  $license["default_license"] = 1;

	$license["limit"] = 3;
	$license["sleep"] = 0.6;
	$license["sleep_php"] = 0.4;

	?>
