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
$price_day = 0;
if($speed){
	$speed_data = mysql_fetch_assoc(mysql_query("SELECT * FROM speed WHERE id = 4"));
	$days = $days -1;
}
$n = 0;
foreach ($channel as $key => $value) {
	$n++;
	$channel_data = '';
	$channel_data = mysql_fetch_assoc(mysql_query("SELECT * FROM channel WHERE id = $value"));
	// //Считаем для первого дня при установленной галочке за срочность
	// if($speed && $n == 1){
	// 	$summa = $summa * $speed_data['koef'];
	// }	
	$summa = $summa + ($days * $words * $channel_data['price']);
	if($speed){
		$summa = $summa + ($words * $speed_data['koef'] * $channel_data['price']);
	}
	//Определяем стоимость в день
	$price_day = $price_day + $channel_data['price'] * $words;
}
	$discount_data = mysql_fetch_assoc(mysql_query("SELECT * FROM discount WHERE id = $discount"));
	if($discount_data['percent'] > 0){
		$summa = round($summa - ($summa/100)*$discount_data['percent'], 0);
	} 
//Округляем, убираем копейки
	$summa = round($summa, 0);
	$price_day = round($price_day, 0);
//Закидываем в таблицу с расчётами
if(mysql_query("INSERT INTO `calculation` (summa,price_day,discount,discount_id) VALUES ('".$summa."','".$price_day."','".$discount_data['percent']."','".$discount_data['id']."')")){
	$_SESSION['calculation'] = mysql_insert_id();
	$calc_result = array();
	$calc_result['price_day'] = $price_day;
	$calc_result['summa'] = $summa;
	echo json_encode($calc_result);
}
?>