<?php
if(!defined("LOAD")){ header('HTTP/1.0 404 Not Found');  die('Not Found'); }

/**
*
* For set it important table or not
*
*/
$mysql = array(
  'btc_faq' => 'non',
  'btc_pages' => 'non',
  'btc_prices' => 'non',
  'btc_settings' => 'import',
  'btc_users' => 'import',
  'btc_users_addresses' => 'import',
  'btc_users_transactions' => 'import'
);

?>
