<?php
session_start();
// if(!isset($_SESSION['user_id']) || (!isset($_SESSION["access"][1]) || !isset($_SESSION["access"][3]))){
// 	header("Location: login.php");
// 	exit;
// }
// echo "<pre>";
// print_r($_POST);
// echo "</pre>";
// echo "<br><p class=\"text-center\"><button type=\"button\" class=\"btn btn-danger\" id=\"button_return\" onclick=\"button_return();\">Назад</button></p>";
//exit();
require_once('../config.php');
require_once('../function.php');
//require_once('../template/header.html');
connect_to_base();
$err_text='';
foreach($_POST as $key => $val){
	if(empty($val) || $key == 'channel'){
		continue;
	}	
	$$key = mysql_escape_string($val);
}
if(isset($_POST['channel'])){
	$channel = $_POST['channel'];
}
$err_text = '';
if(!$md5_id){
	$err_text .= "<li class=\"text-danger\">Не указан md5_id</li>";
}
if(!$item){
	$err_text .= "<li class=\"text-danger\">Не указан пункт приёма</li>";
}
if(!$channel){
	$err_text .= "<li class=\"text-danger\">Не указан канал выхода</li>";
}
if(!$client_name){
	$err_text .= "<li class=\"text-danger\">Не указано имя клиента</li>";
}
if(!$text_advert){
	$err_text .= "<li class=\"text-danger\">Не указан текст объявления</li>";
}
if(!$words){
	$err_text .= "<li class=\"text-danger\">Не указан текст объявления</li>";
}
if(!$released){
	$err_text .= "<li class=\"text-danger\">Не указана дата выхода</li>";
}
if(!$days){
	$err_text .= "<li class=\"text-danger\">Не указано количество дней</li>";
}
if(!$_SESSION['calculation']){
	$err_text .= "<li class=\"text-danger\">Отсутствует данные расчёта</li>";
}
if(isset($days) && isset($released)){
	$released = explode(" ", $released);
	$num_released = count($released);
	if($num_released != $days){
		$err_text .= "<li class=\"text-danger\">Не совпадают даты выходов с количеством дней</li>";
	}
}
if(mysql_num_rows(mysql_query("SELECT * FROM `advert` WHERE `md5_id` = '".$md5_id."'"))>0){
	$err_text .= "<li class=\"text-danger\">Данное объявление уже добавленно ранее в базу данных.</li>";
}
if(!empty($err_text)){
	echo "<br><p><ol>$err_text</ol></p><button type=\"button\" class=\"btn btn-danger\" id=\"button_return\" onclick=\"button_return();\">Назад</button>";
	exit();
}
if(!$paid){
	$paid = 0;
}
$price = mysql_fetch_assoc(mysql_query("SELECT * FROM `calculation` WHERE `id` = '".$_SESSION['calculation']."'"));
if(!$price){
	echo "<p class=\"text-danger\">Произошла ошибка при получение данных рассчёта.</p>";
	exit();	
}
if(!$client_phone){
	$client_phone = '';
}
if(!$comment){
	$comment = '';
}
if(!$speed){
	$speed = '';
}
//Проверяем есть клиент уже в базе и если нет то добавляем его в базу данных
$client_data = mysql_fetch_assoc(mysql_query("SELECT * FROM `client` WHERE `name` = '".$client_name."' AND `phone` = '".$client_phone."'"));
if($client_data){
	$id_client= $client_data['id_client'];
} else {
	if(mysql_query("INSERT INTO `client` (name,phone,who_add) VALUES('".$client_name."','".$client_phone."','".$_SESSION['user_id']."')")){
		$id_client = mysql_insert_id();
	} else {
		echo "<p class=\"text-danger\">Произошла ошибка при добавление клиента в базу данных.</p>";
		exit();
	}
}
$query = "INSERT INTO `advert` (id_client,text_advert,comment,item,words,price,calc_id,speed,paid,who_add,md5_id) VALUES('".$id_client."','".$text_advert."','".$comment."','".$item."','".$words."','".$price['summa']."','".$_SESSION['calculation']."','".$speed."','".$paid."','".$_SESSION['user_id']."','".$md5_id."')";
//Добавляем объявление в базу
if(mysql_query($query)){
	$id_advert = mysql_insert_id();
} else {
		echo "<p class=\"text-danger\">Произошла ошибка при добавление объявления в базу данных.</p>";
		exit();	
}
//Добавляем каналы выхода для этого объявления
foreach ($channel as $key => $value) {
	if(mysql_query("INSERT INTO `channel_advert` (id_advert,id_channel) VALUES('".$id_advert."','".$value."')")){
		/////
	} else {
		echo "<p class=\"text-danger\">Произошла ошибка при добавление канала выхода объявления.</p>";
		mysql_query("DELETE FROM `channel_advert` WHERE id_advert = '".$id_advert."'");
		exit();			
	}
}
//Добавляем даты выхода объявления
foreach ($released as $key => $value) {
	if(mysql_query("INSERT INTO `released_advert` (id_advert,date_released) VALUES('".$id_advert."','".rtrim($value, ",")."')")){
		/////
	} else {
		echo "<p class=\"text-danger\">Произошла ошибка при добавление даты выхода объявления.</p>";
		mysql_query("DELETE FROM `released_advert` WHERE id_advert = '".$id_advert."'");
		exit();			
	}
}
echo '<div class="alert alert-success text-center">Объявление успешно добавлено!</div>';
?>
