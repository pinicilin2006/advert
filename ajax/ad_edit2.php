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
$err_text = '';
if(!$md5_id){
	$err_text .= "<li class=\"text-danger\">Не указан md5_id</li>";
}
if(!$text_advert){
	$err_text .= "<li class=\"text-danger\">Не указан текст объявления</li>";
}

$query_advert = mysql_query("SELECT * FROM `advert` WHERE `md5_id` = '".$md5_id."'");
if(mysql_num_rows($query_advert)<1){
	$err_text .= "<li class=\"text-danger\">Редактируемое объявление не найдено в базе данных.</li>";
}
if(!empty($err_text)){
	echo "<br><p><ol>$err_text</ol></p><button type=\"button\" class=\"btn btn-danger\" id=\"button_return\" onclick=\"button_return();\">Назад</button>";
	exit();
}
$advert_data_old = mysql_fetch_assoc($query_advert);
if(!$paid){
	$paid = 0;
}
$id_advert = $advert_data_old['id'];
$query = "UPDATE `advert` SET text_advert = '".$text_advert."', paid  = '".$paid."', edit  = 1 WHERE `id` = '".$id_advert."'";
//Обновляем объявление в базе
if(mysql_query($query)){
	//если успешно то записываем старый текст в лог
	mysql_query("INSERT INTO `old_advert` (id_advert,text_advert,paid,who_edit) VALUES('".$id_advert."','".$advert_data_old['text_advert']."','".$advert_data_old['paid']."','".$_SESSION['user_id']."')");
} else {
		echo "<p class=\"text-danger\">Произошла ошибка при добавление объявления в базу данных.</p>";
		exit();	
}
echo '<div class="alert alert-success text-center">Объявление успешно отредактировано!</div>';
?>
