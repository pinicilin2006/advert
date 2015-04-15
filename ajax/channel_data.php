<?php
session_start();
if(!isset($_GET["channel_id"]) || !isset($_SESSION['access'][1])){
	exit();
}
require_once('../config.php');
require_once('../function.php');
connect_to_base();
$channel_id = htmlspecialchars($_GET["channel_id"]); 
$query = mysql_query("SELECT * FROM `channel` WHERE `id` = $channel_id ");
if(mysql_num_rows($query) == 0){
	exit;   
}
$channel_data = mysql_fetch_assoc($query);
echo $channel_data['active'];

?>
