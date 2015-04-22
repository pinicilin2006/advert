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
	if($key == 'rights' || $key == 'channel' || $key == 'item' || empty($val)){
		continue;
	}
	$$key = mysql_escape_string($val);
	//echo $key."<br>";
}
$err_text = '';
if(!$first_name && !$second_name && !$third_name){
	$err_text .= "<li class=\"text-danger\">Необходимо заполнить хотя бы одно поле из полей отвечающих за ФИО</li>";
}

if(!$login){
	$err_text .= "<li class=\"text-danger\">Не указан логин</li>";
}
if(!$password){
	$err_text .= "<li class=\"text-danger\">Не указан пароль</li>";
}
// if(isset($password) && (strlen($password) < 6 || !preg_match("/([0-9]+)/", $password) || !preg_match("/([a-zA-Z]+)/", $password))){
// 	$err_text .= "<li class=\"text-danger\">Пароль должен содержать минимум 6 символов, включающих в себя букву на английском языке и одну цифру<br>";
// }
// if(!$filial){
// 	$err_text .= "<li class=\"text-danger\">Не указан филиал</li>";
// }
if(!$_POST["rights"]){
	$err_text .= "<li class=\"text-danger\">Не указаны права пользователя</li>";	
}
if(!empty($err_text)){
	echo "<br><p><ol>$err_text</ol></p><br><button type=\"button\" class=\"btn btn-danger\" id=\"button_return\" onclick=\"button_return();\">Назад</button>";
	exit();
}
if(mysql_num_rows(mysql_query("SELECT * FROM `user` WHERE `login` = '".$login."'"))>0){
	echo "<br><p class=\"text-danger\">Логин занят!</p><br><button type=\"button\" class=\"btn btn-danger\" id=\"button_return\" onclick=\"button_return();\">Назад</button>";
	exit();	
}

if(mysql_num_rows(mysql_query("SELECT * FROM `user` WHERE `first_name` = '".$first_name."' AND `second_name` = '".$second_name."' AND `third_name` = '".$third_name."'"))>0){
	echo "<br><p class=\"text-danger\">Пользователь с такими данными уже имеется в базе данных пользователей.</p><br><button type=\"button\" class=\"btn btn-danger\" id=\"button_return\" onclick=\"button_return();\">Назад</button>";
	exit();	
}
if(!$active){
	$active = 0;
}
$password_hash = password_hash($password, PASSWORD_DEFAULT);
if(mysql_query("INSERT INTO `user` (login,password,first_name,second_name,third_name,active,max_time,who_added) VALUES('".$login."','".$password_hash."','".$first_name."','".$second_name."','".$third_name."','".$active."','".$max_time."','".$_SESSION["user_id"]."')")){
	$user_data = mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `login` = '".$login."'"));
	$user_id = $user_data["user_id"];
	foreach ($_POST["rights"] as $key => $value) {
		mysql_query("INSERT INTO `user_rights` (user_id,rights) VALUES('".$user_id."','".mysql_real_escape_string($value)."')");
	}
	foreach ($_POST["channel"] as $key => $value) {
		mysql_query("INSERT INTO `user_channels` (user_id,channel) VALUES('".$user_id."','".mysql_real_escape_string($value)."')");
	}
	foreach ($_POST["item"] as $key => $value) {
		mysql_query("INSERT INTO `user_items` (user_id,item) VALUES('".$user_id."','".mysql_real_escape_string($value)."')");
	}		
	echo "<br><p class=\"text-success\">Пользователь <strong>$second_name $first_name $third_name</strong> успешно добавлен. <br> Логин <strong>$login</strong><br>Пароль <strong>$password</strong></p>";
} else {
	echo "<p class=\"text-danger\">Произошла ошибка при добавление пользователя в базу данных!</p>";
}
?>
