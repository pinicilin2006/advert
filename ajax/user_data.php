<?php
session_start();
if(!isset($_GET["user_id"]) || !isset($_SESSION['access'][1])){
	exit();
}
require_once('../config.php');
require_once('../function.php');
connect_to_base();
$user_id = htmlspecialchars($_GET["user_id"]); 
$query = mysql_query("SELECT * FROM `user` WHERE `user_id` = $user_id ");
if(mysql_num_rows($query) == 0){
	exit;   
}
$user_data = mysql_fetch_assoc($query);

$user_rights = mysql_query("SELECT * FROM `user_rights` WHERE `user_id` = $user_id");
while ($row = mysql_fetch_assoc($user_rights)) {
	$user_data['rights'][$row['rights']] = 1;
}

$user_channels = mysql_query("SELECT * FROM `user_channels` WHERE `user_id` = $user_id");
while ($row = mysql_fetch_assoc($user_channels)) {
	$user_data['channels'][$row['channel']] = 1;
}

$user_items = mysql_query("SELECT * FROM `user_items` WHERE `user_id` = $user_id");
while ($row = mysql_fetch_assoc($user_items)) {
	$user_data['items'][$row['item']] = 1;
}
echo json_encode($user_data);
?>
