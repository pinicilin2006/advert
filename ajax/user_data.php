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
$result=array();
while($row = mysql_fetch_assoc($user_rights)){
	$result['rights'][$row['rights']] = 1;
}
$result['first_name'] = $user_data['first_name'];
$result['second_name'] = $user_data['second_name'];
$result['third_name'] = $user_data['third_name'];
$result['date_birth'] = $user_data['date_birth'];
$result['login'] = $user_data['login'];
$result['active'] = $user_data['active'];
echo json_encode($result);
?>
