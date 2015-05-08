<?php
session_start();
unset($_SESSION["calculation"]);
if(!isset($_SESSION['user_id'])){
	header("Location: login.php");
	exit;
}


// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";
require_once('config.php');
require_once('function.php');
connect_to_base();
require_once('template/header.html');
//Запрос по умолчанию при пустых полях фильтра
if(isset($_POST['date_released_start']) || isset($_POST['date_released_end'])){
	if(!empty($_POST['date_released_start'])){
		$date_start = strtotime($_POST['date_released_start']);
	}
	if(!empty($_POST['date_released_end'])){
		$date_end = strtotime($_POST['date_released_end']);
	}
	if($date_start && $date_end && $date_start > $date_end){
	echo "<span class=\"text-danger\"><center>Дата окончания выходов не может быть меньше даты начала выходов.</center></span>";
	exit();			
	}
	if(isset($date_start) || isset($date_end)){
		$date_released = 1;
	}
}
$query = "SELECT advert.*, client.*, item.name item_name FROM `advert`,`client`,`item` WHERE advert.id_client = client.id_client AND item.id = advert.item AND advert.date_create>DATE_ADD(now(), INTERVAL -31 DAY)";
//$query = "SELECT advert.*,client.*,item.name item_name,"
if(isset($_POST['channel']) && !empty($_POST['channel'])){
	$query = "SELECT advert.*, client.*, item.name item_name,channel_advert.* FROM `advert`,`client`,`item`,channel_advert WHERE advert.id_client = client.id_client AND item.id = advert.item AND advert.id = channel_advert.id_advert AND channel_advert.id_channel = $_POST[channel]";
}
if(isset($date_released) && empty($_POST['channel'])){
	$query = "SELECT distinct advert.id, advert.calc_id, advert.comment, advert.md5_id, advert.date_create, advert.paid, advert.text_advert, advert.words, advert.price, advert.who_add, client.name, client.phone, item.name item_name FROM `advert`,`client`,`item`, `released_advert` WHERE advert.id_client = client.id_client  AND item.id = advert.item AND released_advert.id_advert = advert.id ".(isset($date_start) ? " AND released_advert.date_unix >= $date_start" : '')." ".(isset($date_end) ? " AND released_advert.date_unix <= $date_end" : '')." ";
	//echo $query;
}
if(isset($date_released) && !empty($_POST['channel'])){
	$query = "SELECT distinct advert.id, advert.calc_id, advert.comment, advert.md5_id, advert.date_create, advert.paid, advert.text_advert, advert.words, advert.price, advert.who_add, client.name, client.phone, item.name item_name FROM `advert`,`client`,`item`, `released_advert`, `channel_advert` WHERE advert.id_client = client.id_client  AND item.id = advert.item AND released_advert.id_advert = advert.id ".(isset($date_start) ? " AND released_advert.date_unix >= $date_start" : '')." ".(isset($date_end) ? " AND released_advert.date_unix <= $date_end" : '')." AND channel_advert.id_channel = $_POST[channel] AND advert.id = channel_advert.id_advert";
	//echo $query;
}

if(isset($_POST['paid'])){
	$query .= " AND paid = 1";
}
if(isset($_POST['item']) && !empty($_POST['item'])){
	$query .= " AND advert.item = $_POST[item]";
}
if(isset($_POST['user']) && !empty($_POST['user'])){
	$query .= " AND advert.who_add = $_POST[user]";
}
if(!isset($_SESSION['access'][9])){
	$query .= " AND advert.who_add = $_SESSION[user_id]";
}
$query .= " ORDER BY id";
$query_text = $query;
//echo $query;
$query = mysql_query($query);
//$query .= "ORDER BY id";
if(mysql_num_rows($query) == 0){
	//echo $query_text;
	echo "<span class=\"text-danger\"><center>Отсутствуют объявления в базе данных.</center></span>";
	exit();	
}
//echo $query_text;
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
	  			<div class="panel-heading">
	    			<h3 class="panel-title">Список объявлений</h3>
	  			</div>
	  			<div class="panel-body" id="user_data">
	  			<div class="row">
	  			<hr class="hr_red">
	  				<form role="form" id="main_form" class="form-inline pull-right" method="post">
							<div class="form-group">					    					    
							      <input type="text" class="form-control date_released" id="date_released_start" name="date_released_start" value="<?php echo $_POST['date_released_start']?>" placeholder="Дата выхода, с">					    
							</div>
							<div class="form-group">					    					    
							      <input type="text" class="form-control date_released" id="date_released_end" name="date_released_end" value="<?php echo $_POST['date_released_end']?>" placeholder="Дата выхода, по">					    
							</div>							
							<div class="form-group">
								<select class="form-control" name="item" id="item">
											<option value="" <?php echo (!$_POST['item'] || empty($_POST['item']) ? ' selected' : '') ?>>Пункт приёма</option>
											<?php
											$query_item = mysql_query("SELECT * FROM item where active = 1");
											while($row = mysql_fetch_assoc($query_item)){
											?>
									    	<option value="<?php echo $row['id']?>" <?php echo ($_POST['item'] == $row['id'] ? ' selected' : '') ?> ><?php echo $row['name']?></option>											
											<?php
											}
											?>
								</select>
							</div>

							<div class="form-group">
								<select class="form-control" name="user" id="user" <?php echo (!isset($_SESSION['access'][9]) ? ' disabled' : '')?>>
											<option value="" <?php echo (!$_POST['user'] || empty($_POST['user']) ? ' selected' : '') ?>>Кто добавил</option>
											<?php
											$query_user = mysql_query("SELECT * FROM user");
											while($row = mysql_fetch_assoc($query_user)){
											?>
									    	<option value="<?php echo $row['user_id']?>" <?php echo ($_POST['user'] == $row['user_id'] ? ' selected' : '') ?> ><?php echo $row['first_name']?></option>											
											<?php
											}
											?>
								</select>
							</div>

							<div class="form-group">
								<select class="form-control" name="channel" id="channel">
											<option value="" <?php echo (!$_POST['channel'] || empty($_POST['channel']) ? ' selected' : '') ?>>Канал выхода</option>
											<?php
											$query_channel = mysql_query("SELECT * FROM channel where active = 1");
											while($row = mysql_fetch_assoc($query_channel)){
											?>
									    	<option value="<?php echo $row['id']?>" <?php echo ($_POST['channel'] == $row['id'] ? ' selected' : '') ?> ><?php echo $row['name']?></option>											
											<?php
											}
											?>
								</select>
							</div>							
							<div class="form-group ">
								<div class="checkbox-inline" style="padding-top:2%">	
									&nbsp;&nbsp;&nbsp;<label><input type="checkbox" name="paid" value="1" <?php echo ('1' == $_POST['paid'] ? ' checked' : '')?>>В эфир</label>&nbsp;&nbsp;&nbsp;
								</div>
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-block btn-danger">Выбрать</button>
							</div>																		
	  				</form>
	  			</div>
	  			<div class="row">
	  			<hr class="hr_red2">
					<div class="table-responsive">
		    			<table class='table table-hover table-responsive table-condensed table-bordered' id='contract_table'>
		    				<thead>
		    					<tr>
				    				<th style = 'cursor: pointer;'>№ </th>
				    				<th style = 'cursor: pointer;'>Дата приёма </th>
									<th style = 'cursor: pointer;'>Пункт приёма</th>
				    				<th style = 'cursor: pointer;'>Клиент</th>									
				    				<th style = 'cursor: pointer;'>Текст</th>
				    				<th style = 'cursor: pointer;'>Слов</th>
				    				<th style = 'cursor: pointer;'>Дней </th>
				    				<th style = 'cursor: pointer;'>Сумма</th>
				    				<th style = 'cursor: pointer;'>Действия</th>
				    			</tr>
			    			</thead>
			    			<tbody>
<?php
while($row = mysql_fetch_assoc($query)){
	if($row['paid'] == '1'){
		echo '<tr class="success">';	
	} else {
		echo '<tr class="danger">';	
	}
	echo "<td>".$row['id']."</td>";
	echo "<td>".date("d.m.Y", strtotime($row['date_create']))."</td>";
	echo "<td>".$row['item_name']."</td>";
	echo "<td>".$row['name']."</td>";
	echo "<td>".$row['text_advert']."</td>";
	echo "<td>".$row['words']."</td>";
	echo "<td>".mysql_num_rows(mysql_query("SELECT * FROM `released_advert` WHERE `id_advert` = $row[id]"))."</td>";
	echo "<td>".$row['price']."</td>";
	echo "</td>";
	echo '<td>';
	echo '<a href="/advert_show.php?id='.$row['md5_id'].'">Просмотр</a>';
// echo '<div class="btn-group">
//   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Действие <span class="caret"></span></button>
//   <ul class="dropdown-menu" role="menu">';
//   echo '<li><a href="/advert_show.php?id='.$row['md5_id'].'"><small>Просмотр</small></a></li><li class="divider" style="margin:0 0"></li>';
// if(isset($_SESSION['access'][12])){
// 	echo '<li><a href="/advert_copy.php?id='.$row['md5_id'].'"><small>Дублировать</small></a></li><li class="divider" style="margin:0 0"></li>';
// }
// if(isset($_SESSION['access'][13]) || (isset($_SESSION['access'][8]) && $row['who_add'] == $_SESSION['user_id'])){
// 	echo '<li><a href="/advert_edit2.php?id='.$row['md5_id'].'"><small>Редактировать текст</small></a></li><li class="divider" style="margin:0 0"></li>';
// }
// if(isset($_SESSION['access'][6])){
// 	echo '<li><a href="/advert_edit.php?id='.$row['md5_id'].'"><small>Полное редактирование</small></a></li><li class="divider" style="margin:0 0"></li>';
// }
// if(mysql_num_rows(mysql_query("SELECT * FROM `old_advert` WHERE id_advert = $row[id]")) > 0){
// 	//echo '<li><a href="/advert_history.php?id='.$row['id'].'" target="_blank"><small>Список изменений</small></a></li><li class="divider" style="margin:0 0"></li>';
// }
// if(isset($_SESSION['access'][7])){
// 	echo '<li><a href="/advert_delete.php?id='.$row['md5_id'].'"><small>Удалить</small></a></li><li class="divider" style="margin:0 0"></li>';
// }
// echo '</ul>
// </div>';
echo '</td>';
	echo "</tr>";	
	echo "</tr>";
}
?>			    			
			    			</tbody>
			    		</table> 
			    	</div>				
	  			</div>
	  			<hr class="hr_red">
	  				<form role="form" id="main_form" class="form-inline pull-right" method="post" action="/playlist.php" target="_blank">
							<div class="form-group">					    					    
							      <input type="hidden" name="date_released" value="<?php echo $_POST['date_released_start'] ?>">					    
							</div>
							<div class="form-group">					    					    
							      <input type="hidden" name="query_text" value="<?php echo $query_text ?>">					    
							</div>
							<?php
							//if(isset($_POST['date_released_start']) && !empty($_POST['date_released_start']) && isset($_POST['date_released_end']) && !empty($_POST['date_released_end']) && $_POST['date_released_start'] == $_POST['date_released_end'] && !empty($_POST['channel'])){
							?>							
							<div class="form-group">
								<button type="submit" class="btn btn-block btn-danger">Сформировать файл для выгрузки в программу</button>
							</div>
							<?php
							//}
							?>																							
	  				</form>
	  				<form role="form" id="main_form" class="form-inline pull-right" method="post" action="/report_excel/report.php">				    					    
							    <input type="hidden" name="query_text" value="<?php echo $query_text ?>">
							    <input type="hidden" name="excel_date_released_start" value="<?php echo ($_POST['date_released_start'] ? $_POST['date_released_start'] : '') ?>">		
								<input type="hidden" name="excel_date_released_end" value="<?php echo ($_POST['date_released_end'] ? $_POST['date_released_end'] : '') ?>">
								<input type="hidden" name="excel_item" value="<?php echo ($_POST['item'] ? $_POST['item'] : '') ?>">
								<input type="hidden" name="excel_user" value="<?php echo ($_POST['user'] ? $_POST['user'] : '') ?>">
								<input type="hidden" name="excel_channel" value="<?php echo ($_POST['channel'] ? $_POST['channel'] : '') ?>">		
								<input type="hidden" name="excel_paid" value="<?php echo ($_POST['paid'] ? '1' : '0') ?>">	
							<div class="form-group">
								<button type="submit" class="btn btn-block btn-danger">ЭКСПОРТ в Exel</button>
							</div>																									
	  				</form>	  				
	  			</div>	  			
			</div>
		</div>
	</div>
</div>
<div class="footer navbar-fixed-bottom text-center">
  <small>©<?php echo date("Y") ?>. <a class="sia_red" href="<?php echo $link_organization ?>" target="_blank"><b><?php echo $name_organization ?></b>.</a></small>
</div>
</body>
</html>
<!-- вставляем скрипты общие для формы добавления и редактирования -->

<script type="text/javascript">
$(".date_released").datepicker();
$("#contract_table").tablesorter();
</script>