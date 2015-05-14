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
	if(empty($val) && $val !='0'){
		continue;
	}	
	$$key = mysql_escape_string($val);
}
$err_text = '';
if(!$discount_id){
	$err_text .= "<li class=\"text-danger\">Отсутствует id скидки</li>";
}
if(!isset($name)){
	$err_text .= "<li class=\"text-danger\">Отсутствует название скидки</li>";
}
if(!isset($percent)){
	$err_text .= "<li class=\"text-danger\">Отсутствует размер скидки</li>";
}
if(mysql_num_rows(mysql_query("SELECT * FROM `discount` WHERE `name` = '".$discount."' AND `id` <> $discount_id"))>0){
	$err_text .= "<li class=\"text-danger\">Скидка с таким именем была добавлена ранее.</li>";
}
if(!empty($err_text)){
	echo "<br><p><ol>$err_text</ol></p><button type=\"button\" class=\"btn btn-danger\" id=\"button_return\" onclick=\"button_return();\">Назад</button>";
	exit();
}
if(!$active){
	$active = 0;
}
//Обновляем в базе данных
if(mysql_query("UPDATE `discount` SET `name` = '".$name."',`percent` = '".$percent."', `active` = '".$active."', who_edit = '".$_SESSION['user_id']."' WHERE `id` = '".$discount_id."'")){
	echo "<p class=\"text-success\">Скидка успешно изменёна.</p>";
}else{
	echo "<p class=\"text-danger\">Произошла ошибка при изменение скидки.</p>";
}
//echo "<br><p><ol>$err_text</ol></p><p class=\"text-center\"><button type=\"button\" class=\"btn btn-danger\" id=\"button_return\" onclick=\"button_return();\">Назад</button></p>";		
exit();

?>