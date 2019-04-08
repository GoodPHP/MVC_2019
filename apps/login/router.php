<?php
if(!defined("LOAD")){ header('HTTP/1.0 404 Not Found');  die('Not Found'); }
$this->routes = array
(

  array(
	// pattern in a Perl-compatible regular expression format
	'pattern' => '~^/login/change/(.*)$~',
	// handler do name
	'do' => 'change',
	'aliases' => array('hash')
  ),

  array(
	// pattern in a Perl-compatible regular expression format
	'pattern' => '~^/login/forgot-password$~',
	// handler do name
	'do' => 'forgot'
  ),

  array(
	// pattern in a Perl-compatible regular expression format
	'pattern' => '~^/login$~',
	// handler do name
	'do' => 'login'
	)

);
