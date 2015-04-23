<?php
session_start();
require_once('../config.php');
require_once('../function.php');
if($_POST['action'] != 'delete' || !isset($_SESSION['access']['7'])){
	exit;	
}
// echo "<pre>";
// print_r($_POST);
// echo "</pre>";
// exit;
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
$query_advert = mysql_query("SELECT * FROM `advert` WHERE `md5_id` = '".$md5_id."'");
if(mysql_num_rows($query_advert)<1){
	$err_text .= "<li class=\"text-danger\">Редактируемое объявление не найдено в базе данных.</li>";
}
if(!empty($err_text)){
	echo "<br><p><ol>$err_text</ol></p><button type=\"button\" class=\"btn btn-danger\" id=\"button_return\" onclick=\"button_return();\">Назад</button>";
	exit();
}
$advert_data = mysql_fetch_assoc($query_advert);
$id_advert = $advert_data['id'];
$query_delete_advert = "DELETE FROM advert WHERE id = $id_advert";
$query_delete_channels = "DELETE FROM channel_advert WHERE id_advert = $id_advert";
$query_delete_releaseds = "DELETE FROM released_advert WHERE id_advert = $id_advert";
//Обновляем объявление в базе
if(mysql_query($query_delete_advert) && mysql_query($query_delete_channels) && mysql_query($query_delete_releaseds)){

	
} else {
		echo "<p class=\"text-danger\">Произошла ошибка при удаление объявления.</p>";
		exit();	
}
echo '<div class="alert alert-success text-center">Объявление успешно удалено!</div>';
?>
