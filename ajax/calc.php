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
	if(empty($val)){
		continue;
	}
	$$key = mysql_escape_string($val);
}
$channel = $_POST['channel'];
if(!$days || !$channel || !$words){
	echo '';
	exit;
}
$summa = 0;
foreach ($channel as $key => $value) {
	$channel_data = mysql_fetch_assoc(mysql_query("SELECT * FROM channel WHERE id = $value"));
	$summa = $summa + ($days * $words * $channel_data['price']);
}
if($discount){
	$summa = round(($summa /100)*$discount, 2);
}
if($speed){
	$speed_data = mysql_fetch_assoc(mysql_query("SELECT * FROM speed WHERE id = 4"));
	$summa = round($summa * $speed_data['koef'], 2);
}
//Закидываем в таблицу с расчётами
if(mysql_query("INSERT INTO `calculation` (summa) VALUES ('".$summa."')")){
	$_SESSION['calculation'] = mysql_insert_id();
	echo $summa;
}

?>