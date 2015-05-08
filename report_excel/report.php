<?php
session_start();
unset($_SESSION["calculation"]);
if(!isset($_SESSION['user_id'])){
	header("Location: ../login.php");
	exit;
}
// if(!isset($_SESSION['access'][10])){
// 	header("Location: /advert_list.php");
// 	exit;
// }
// echo "<pre>";
// print_r($_POST);
// echo "</pre>";
// exit;
require_once('../config.php');
require_once('../function.php');
connect_to_base();
set_include_path(get_include_path() . PATH_SEPARATOR .'PhpExcel/Classes/');
$query = mysql_query($_POST['query_text']);
include_once 'Classes/PHPExcel/IOFactory.php';
$objPHPExcel = PHPExcel_IOFactory::load("report.xls");
$objPHPExcel->setActiveSheetIndex(0);
$aSheet = $objPHPExcel->getActiveSheet();
//$objPHPExcel->getActiveSheet()->setAutoFilter('A2:AL2');	// Always include the complete filter range!

///////////////////////////////////////////////////////////////////////////////////////////////

$font1 =  array(
'font' => array(
'name' => 'Arial',
'size' => '12'
),
);

$font2 =  array(
'font' => array(
'name' => 'Arial Cyr',
'size' => '10'
),
'alignment' => array(
'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER
),
'borders' => array(
'allborders'     => array(
'style' => PHPExcel_Style_Border::BORDER_THIN,
'color' => array( 'rgb' => '000000' )
)
)
);
///////////////////////////////////////////////////////////////////////////////////////////////
//Заполняем таблицу
$n = 0;
$i = 16; //счетчик с какой ячейки надо печатать
$summa_all = 0;
while($row = mysql_fetch_array($query)) {
	$n++;
	// 	//рамки для ячеек
	$aSheet->getStyle('B'.($i).':O'.($i).'')->applyFromArray($font2);
	$aSheet->getStyle('B'.($i).':O'.($i).'')->getAlignment()->setWrapText(true);
	 	//$aSheet->getStyle('C'.($i))->getAlignment()->setWrapText(true); //переносить по стракам
	// 	$aSheet->getStyle('G'.($i))->getAlignment()->setWrapText(true); //переносить по стракам
	$aSheet->setCellValue('A'.$i, $n);
	$aSheet->setCellValue('B'.$i, $row['id']);
	$date_create = date('d.m.Y', strtotime($row["date_create"]));
	$aSheet->setCellValue('C'.$i, " ".$date_create);
	$aSheet->setCellValue('D'.$i, $row['name']);
	$aSheet->setCellValue('E'.$i, " ".$row['phone']);
	$aSheet->setCellValue('F'.$i, " ".$row['text_advert']);
	$aSheet->setCellValue('G'.$i, " ".$row['words']);
	//Получаем канал выхода
	$channel = mysql_query("SELECT channel.name FROM `channel`,`channel_advert` where channel_advert.id_channel = channel.id AND channel_advert.id_advert = $row[id]");
	$channel_name = '';
	while ($row1 = mysql_fetch_array($channel)) {
		$channel_name .= $row1['name'].", ";
	}
	$channel_name = substr($channel_name, 0, -2);
	$aSheet->setCellValue('H'.$i, " ".$channel_name);
	//получаем данные расчёта
	$calculation = mysql_fetch_assoc(mysql_query("SELECT * FROM calculation WHERE id = $row[calc_id]"));
	$aSheet->setCellValue('I'.$i, " ".$calculation['price_day']);
	//Получаем количество дней
	$day = mysql_num_rows(mysql_query("SELECT * FROM released_advert WHERE id_advert = $row[id]"));
	$aSheet->setCellValue('J'.$i, " ".$day);
	//Получаем скидку
	$discount = ($calculation['discount'] == '' ? 0 : $calculation['discount']);
	$aSheet->setCellValue('K'.$i, " ".$discount);
	//Сумма
	$aSheet->setCellValue('L'.$i, " ".$calculation['summa']);
	$summa_all = $summa_all + $calculation['summa'];
	//Кто принял
	$user = mysql_fetch_array(mysql_query("SELECT * FROM user WHERE user_id = $row[who_add]"));
	$aSheet->setCellValue('M'.$i, " ".$user['first_name']);
	$aSheet->setCellValue('N'.$i, " ".$row['item_name']);
	$aSheet->setCellValue('O'.$i, " ".$row['comment']);

$i++;
}
//Заполняем шапку
$date_now = date('d.m.Y');
$aSheet->setCellValue('B7', $date_now);
$aSheet->setCellValue('D7', $_SESSION['first_name']);
$aSheet->setCellValue('I4', $_POST['excel_date_released_start']);
$aSheet->setCellValue('I5', $_POST['excel_date_released_end']);
//Пункт приёма
$item_name = 'Любой';
if(!empty($_POST['excel_item'])){
	$item_data = mysql_fetch_assoc(mysql_query("SELECT * FROM item WHERE id = $_POST[excel_item]"));
	$item_name = $item_data['name'];
}
$aSheet->setCellValue('I6', " ".$item_name);
//Принял
$user_name = 'Любой';
if(!empty($_POST['excel_user'])){
	$user_data = mysql_fetch_assoc(mysql_query("SELECT * FROM user WHERE user_id = $_POST[excel_user]"));
	$user_name = $user_data['first_name'];
}
$aSheet->setCellValue('I7', " ".$user_name);
//Канал
$channel_name = 'Любой';
if(!empty($_POST['excel_channel'])){
	$channel_data = mysql_fetch_assoc(mysql_query("SELECT * FROM channel WHERE id = $_POST[excel_channel]"));
	$channel_name = $channel_data['name'];
}
$aSheet->setCellValue('I8', " ".$channel_name);
//В эфире или нет
$paid = ($_POST['excel_paid'] == '0' ? 'Нет' : 'Да');
$aSheet->setCellValue('I9', " ".$paid);
///////////////////////////////
$aSheet->setCellValue('I10', $n);
$aSheet->setCellValue('I12', $summa_all);






//создаем объект класса-писателя
include("Classes/PHPExcel/Writer/Excel5.php");
$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);

//выводим заголовки
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="report.xls"');
header('Cache-Control: max-age=0');
//выводим в браузер таблицу с бланком
$objWriter->save('php://output');


?>
