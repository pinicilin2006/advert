<?php
session_start();
if(!isset($_SESSION['user_id']) || !isset($_SESSION["access"][1])){
	header("Location: login.php");
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
$err_text='';
foreach($_POST as $key => $val){
	$$key = mysql_escape_string($val);
}
$err_text = '';
if(!$item_id){
	$err_text .= "<li class=\"text-danger\">Отсутствует id пункта приёма</li>";
}
if(!$item){
	$err_text .= "<li class=\"text-danger\">Отсутствует название пункта приёма</li>";
}
if(mysql_num_rows(mysql_query("SELECT * FROM `item` WHERE `name` = '".$item."' AND `id` <> $item_id"))>0){
	$err_text .= "<li class=\"text-danger\">Пункт приёма с таким именем был добавлен ранее.</li>";
}
if(!empty($err_text)){
	echo "<br><p><ol>$err_text</ol></p><button type=\"button\" class=\"btn btn-danger\" id=\"button_return\" onclick=\"button_return();\">Назад</button>";
	exit();
}
if(!$active){
	$active = 0;
}
//Обновляем в базе данных
if(mysql_query("UPDATE `item` SET `name` = '".$item."', `active` = '".$active."', who_edit = '".$_SESSION['user_id']."' WHERE `id` = '".$item_id."'")){
	echo "<p class=\"text-success\">Пункт продаж успешно изменён.</p>";
}else{
	echo "<p class=\"text-danger\">Произошла ошибка при изменение пункта продаж.</p>";
}
//echo "<br><p><ol>$err_text</ol></p><p class=\"text-center\"><button type=\"button\" class=\"btn btn-danger\" id=\"button_return\" onclick=\"button_return();\">Назад</button></p>";		
exit();

?>