<?php
session_start();
if(!isset($_GET["item_id"]) || !isset($_SESSION['access'][1])){
	exit();
}
require_once('../config.php');
require_once('../function.php');
connect_to_base();
$item_id = htmlspecialchars($_GET["item_id"]); 
$query = mysql_query("SELECT * FROM `item` WHERE `id` = $item_id ");
if(mysql_num_rows($query) == 0){
	exit;   
}
$item_data = mysql_fetch_assoc($query);
echo $item_data['active'];

?>
