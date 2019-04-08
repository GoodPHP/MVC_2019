<?php
if(!defined("LOAD")){ header('HTTP/1.0 404 Not Found');  die('Not Found'); }

$this->routes = array
(

  array(
	// pattern in a Perl-compatible regular expression format
	'pattern' => '~^/register~',
	// handler do name
	'do' => 'register'
	)

);
