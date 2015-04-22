<?php
session_start();
if(!isset($_SESSION['user_id'])){
	header("Location: login.php");
	exit;
}
// echo "<pre>";
// print_r($_POST);
// echo "</pre>";
// exit();
require_once('../config.php');
require_once('../function.php');
//require_once('../template/header.html');
connect_to_base();
$err_text='';
foreach($_POST as $key => $val){
	if($key == 'rights' || $key == 'channels' || $key == 'items' || empty($val)){
		continue;
	}
	$$key = mysql_escape_string($val);
	//echo $key."<br>";
}
$err_text = '';
if(!$user){
	$err_text .= "<li class=\"text-danger\">Не указан id пользователя</li>";
}
if(!$first_name && !$second_name && !$third_name){
	$err_text .= "<li class=\"text-danger\">Необходимо заполнить хотя бы одно поле из полей ФИО</li>";
}

if(!$login){
	$err_text .= "<li class=\"text-danger\">Не указан логин</li>";
}

if(!$_POST["rights"]){
	$err_text .= "<li class=\"text-danger\">Не указаны права пользователя</li>";	
}

if(!empty($err_text)){
	echo "<br><p><ol>$err_text</ol></p><button type=\"button\" class=\"btn btn-danger\" id=\"button_return\" onclick=\"button_return();\">Назад</button>";
	exit();
}
if(mysql_num_rows(mysql_query("SELECT * FROM `user` WHERE `login` = '".$login."' AND `user_id` <> '".$user."'"))>0){
	echo "<br><p class=\"text-danger text-center\">Логин занят!</p><button type=\"button\" class=\"btn btn-danger\" id=\"button_return\" onclick=\"button_return();\">Назад</button>";
	exit();	
}
if(mysql_num_rows(mysql_query("SELECT * FROM `user` WHERE `first_name` = '".(isset($first_name) ? $first_name : '')."' AND `second_name` = '".(isset($second_name) ? $second_name : '')."' AND `third_name` = '".(isset($third_name) ? $third_name : '')."' AND `date_birth` = '".(isset($date_birth) ? $date_birth : '')."' AND `user_id` <> '".$user."'"))>0){
	echo "<br><p class=\"text-danger\">Пользователь с такими данными уже имеется в базе данных пользователей.</p><button type=\"button\" class=\"btn btn-danger\" id=\"button_return\" onclick=\"button_return();\">Назад</button>";
	exit();	
}
if(!$active){
	$active = 0;
}
if($password){
	$password_hash = password_hash($password, PASSWORD_DEFAULT);
}
if(mysql_query("UPDATE `user` SET `login` = '".$login."',`first_name` = '".(isset($first_name) ? $first_name : '')."',`second_name` = '".(isset($second_name) ? $second_name : '')."',`third_name` = '".(isset($third_name) ? $third_name : '')."',`date_birth` = '".$date_birth."',`max_time` = '".$max_time."',`active` = '".$active."'".(isset($password) ? ",`password` = '".$password_hash."'" : '')." WHERE `user_id` = '".$user."'")){
	$user_id = $user_data["user_id"];
	mysql_query("DELETE FROM `user_rights` WHERE `user_id` = '".$user."'");
	mysql_query("DELETE FROM `user_channels` WHERE `user_id` = '".$user."'");
	mysql_query("DELETE FROM `user_items` WHERE `user_id` = '".$user."'");
	foreach ($_POST["rights"] as $key => $value) {
		mysql_query("INSERT INTO `user_rights` (user_id,rights) VALUES('".$user."','".mysql_real_escape_string($value)."')");
	}
	foreach ($_POST["channel"] as $key => $value) {
		mysql_query("INSERT INTO `user_channels` (user_id,channel) VALUES('".$user."','".mysql_real_escape_string($value)."')");
	}
	foreach ($_POST["item"] as $key => $value) {
		mysql_query("INSERT INTO `user_items` (user_id,item) VALUES('".$user."','".mysql_real_escape_string($value)."')");
	}		
	echo "<br><p class=\"text-success\">Пользователь успешно изменён. <br> Логин: <strong>$login</strong>";
	echo (isset($password) ? "<br>Пароль: <strong>$password</strong></p>" : '');
} else {
	echo "<p class=\"text-danger\">Произошла ошибка при изменение пользователя в базе данных!</p>";
}
?>

