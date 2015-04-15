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

foreach ($_POST as $key => $value) {
	if(!${$key}){
		$err_text .= "<li class=\"text-danger\">Отсутствует цена за слово</li>";
	}
}
if(!empty($err_text)){
	echo "<br><p><ol>$err_text</ol></p><button type=\"button\" class=\"btn btn-danger\" id=\"button_return\" onclick=\"button_return();\">Назад</button>";
	exit();
}
//Обновляем в базе данных
foreach ($_POST as $key => $value) {
	if(mysql_query("UPDATE `price_word` SET `price` = '".number_format($value, 2, '.', '')."' WHERE `id` = '".$key."'")){
		echo "<p class=\"text-success\">Стоимость слова была успешно изменена.</p>";
	}else{
		echo "<p class=\"text-danger\">Произошла ошибка при изменение цены слова.</p>";
	}
}
//echo "<br><p><ol>$err_text</ol></p><p class=\"text-center\"><button type=\"button\" class=\"btn btn-danger\" id=\"button_return\" onclick=\"button_return();\">Назад</button></p>";		
exit();

?>