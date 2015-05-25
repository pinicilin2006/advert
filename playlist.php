<?php
session_start();
if(!isset($_SESSION['user_id']) || !isset($_POST["query_text"])){
	header("Location: /index.php");
	exit;
}
require_once('config.php');
require_once('function.php');
connect_to_base();
// if(isset($_POST['date_released']) && empty($_POST['date_released'])){
// 	echo 'Отсутствует дата';
// 	exit;
// }
$query = mysql_query($_POST['query_text']);
if(mysql_num_rows($query) < 1){
	echo "Отсутствуют объявления для этого дня";
	exit();
}
if(isset($_POST['date_released']) && !empty($_POST['date_released'])){
	$file = $_POST['date_released'].".txt";
} else {
	$file = date("d.m.Y").".txt";
}

$text = '';

while($row = mysql_fetch_assoc($query)){
	$text .= trim($row['text_advert'])."\r\n\r\n";
}
$text = iconv('utf-8', 'windows-1251', $text);
header ("Content-Type: application/txt");
header ("Accept-Ranges: bytes");
header ("Content-Disposition: attachment; filename=".$file);  
echo $text;
?>
