<?php
session_start();
unset($_SESSION["calculation"]);
if(!isset($_SESSION['user_id'])){
	exit;
}
// echo "<pre>";
// print_r($_POST);
// echo "</pre>";
// echo "<br><p class=\"text-danger text-center\">Логин занят!</p><p class=\"text-center\"><button type=\"button\" class=\"btn btn-danger\" id=\"button_return\" onclick=\"button_return();\">Назад</button></p>";
// exit();
require_once('../config.php');
require_once('../function.php');
//require_once('../template/header.html');
connect_to_base();
foreach($_POST as $key => $val){
	if(empty($val) || $key == 'channel'){
		continue;
	}
	$$key = mysql_escape_string($val);
}

if(!$days || !$view_ads || !$words){
	echo '';
	exit;
}
$summa = 0;
$price_word = mysql_fetch_assoc(mysql_query("SELECT * FROM `price_word` WHERE `views_ads` = '".$view_ads."'"));
$summa = $days * $words * $price_word['price'];
if(mysql_query("INSERT INTO `calculation` (summa) VALUES ('".$summa."')")){
	$_SESSION['calculation'] = mysql_insert_id();
	echo $summa;
}

?>