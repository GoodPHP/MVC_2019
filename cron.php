<?php
define('LOAD','ACTIVE');
set_time_limit(0);

include($_SERVER['DOCUMENT_ROOT']."/includes/core.php");
include($_SERVER['DOCUMENT_ROOT']."/includes/classes/BlockIO/block_io.php");

if(file_exists(cmsCore::getInstance()->config['block_io_config'])) {
	include(cmsCore::getInstance()->config['block_io_config']);
}


$db = cmsDatabase::getInstance();
$query = $db->query("SELECT * FROM btc_users ORDER BY id");
if($query->num_rows>0) {
	while($row = $query->fetch_assoc()) {
		$core->btc_update_balance($row['id']);
		sleep($license["sleep_php"]);
		$core->btc_update_transactions($row['id']);
		sleep($license["sleep_php"]);
		$core->btc_delete_fee_transactions($row['id']);
		sleep($license["sleep_php"]);
	}
}
sleep($license["sleep_php"]);
$core->btc_get_bitcoin_prices();

?>
