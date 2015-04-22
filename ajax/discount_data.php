<?php
session_start();
if(!isset($_GET["discount_id"]) || !isset($_SESSION['access'][1])){
	exit();
}
require_once('../config.php');
require_once('../function.php');
connect_to_base();
$discount_id = htmlspecialchars($_GET["discount_id"]); 
$query = mysql_query("SELECT * FROM `discount` WHERE `id` = $discount_id ");
if(mysql_num_rows($query) == 0){
	exit;   
}
$discount_data = mysql_fetch_assoc($query);
echo json_encode($discount_data);
?>
