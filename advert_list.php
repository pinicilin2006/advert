<?php
session_start();
unset($_SESSION["calculation"]);
if(!isset($_SESSION['user_id'])){
	header("Location: login.php");
	exit;
}


// echo "<pre>";
// print_r($_POST);
// echo "</pre>";
require_once('config.php');
require_once('function.php');
connect_to_base();
require_once('template/header.html');
//Запрос по умолчанию при пустых полях фильтра
$query = "SELECT advert.*, client.*, item.name item_name FROM `advert`,`client`,`item` WHERE advert.id_client = client.id_client AND item.id = advert.item";
//$query = "SELECT advert.*,client.*,item.name item_name,"
if(isset($_POST['channel']) && !empty($_POST['channel'])){
	$query = "SELECT advert.*, client.*, item.name item_name,channel_advert.* FROM `advert`,`client`,`item`,channel_advert WHERE advert.id_client = client.id_client AND item.id = advert.item AND advert.id = channel_advert.id_advert AND channel_advert.id_channel = $_POST[channel]";
}
if(isset($_POST['date_released']) && !empty($_POST['date_released']) && empty($_POST['channel'])){
	$query = "SELECT advert.*, client.*, item.name item_name, released_advert.* FROM `advert`,`client`,`item`, `released_advert` WHERE advert.id_client = client.id_client  AND item.id = advert.item AND released_advert.id_advert = advert.id AND released_advert.date_released = '".$_POST['date_released']."'";
}
if(isset($_POST['date_released']) && !empty($_POST['date_released']) && !empty($_POST['channel'])){
	$query = "SELECT advert.*, client.*, item.name item_name, released_advert.*, channel_advert.* FROM `advert`,`client`,`item`, `released_advert`, `channel_advert` WHERE advert.id_client = client.id_client  AND item.id = advert.item AND released_advert.id_advert = advert.id AND released_advert.date_released = '".$_POST['date_released']."' AND channel_advert.id_channel = $_POST[channel] AND advert.id = channel_advert.id_advert";
}

if(isset($_POST['paid'])){
	$query .= " AND paid = 1";
}
if(!isset($_SESSION['access'][9])){
	$query .= " AND advert.who_add = $_SESSION[user_id]";
}
$query .= " ORDER BY id";
$query_text = $query;
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
							      <input type="text" class="form-control" id="date_released" name="date_released" value="<?php echo $_POST['date_released']?>" placeholder="Дата выхода">					    
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
									&nbsp;&nbsp;&nbsp;<label><input type="checkbox" name="paid" value="1" <?php echo ('1' == $_POST['paid'] ? ' checked' : '')?>>Оплачено</label>&nbsp;&nbsp;&nbsp;
								</div>
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-block">Отфильтровать объявления</button>
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
				    				<th style = 'cursor: pointer;'>Дата создания </th>
				    				<th style = 'cursor: pointer;'>Нименование клиента</th>
									<th style = 'cursor: pointer;'>Пункт приёма</th>
				    				<th style = 'cursor: pointer;'>Текст</th>
				    				<th style = 'cursor: pointer;'>Кол-во слов</th>
				    				<th style = 'cursor: pointer;'>кол-во дней </th>
				    				<th style = 'cursor: pointer;'>Стоимость</th>
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
	echo "<td>".$row['name']."</td>";
	echo "<td>".$row['item_name']."</td>";
	echo "<td>".$row['text_advert']."</td>";
	echo "<td>".$row['words']."</td>";
	echo "<td>".mysql_num_rows(mysql_query("SELECT * FROM `released_advert` WHERE `id_advert` = $row[id]"))."</td>";
	echo "<td>".$row['price']."</td>";
	echo "</td>";
	echo '<td>
<div class="btn-group">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Действие <span class="caret"></span></button>
  <ul class="dropdown-menu" role="menu">';
  echo '<li><a href="/advert_show.php?id='.$row['md5_id'].'" target="_blank"><small>Просмотр</small></a></li><li class="divider" style="margin:0 0"></li>';
if(isset($_SESSION['access'][12])){
	echo '<li><a href="/advert_copy.php?id='.$row['md5_id'].'"><small>Дублировать</small></a></li><li class="divider" style="margin:0 0"></li>';
}
if(isset($_SESSION['access'][8])){
	echo '<li><a href="/advert_edit2.php?id='.$row['md5_id'].'"><small>Редактировать текст</small></a></li><li class="divider" style="margin:0 0"></li>';
}
if(isset($_SESSION['access'][6])){
	echo '<li><a href="/advert_edit.php?id='.$row['md5_id'].'"><small>Полное редактирование</small></a></li><li class="divider" style="margin:0 0"></li>';
}
if(mysql_num_rows(mysql_query("SELECT * FROM `old_advert` WHERE id_advert = $row[id]")) > 0){
	echo '<li><a href="/advert_history.php?id='.$row['id'].'" target="_blank"><small>Список изменений</small></a></li><li class="divider" style="margin:0 0"></li>';
}
if(isset($_SESSION['access'][7])){
	echo '<li><a href="/advert_delete.php?id='.$row['md5_id'].'"><small>Удалить</small></a></li><li class="divider" style="margin:0 0"></li>';
}
echo '</ul>
</div>
	</td>';
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
							      <input type="hidden" name="date_released" value="<?php echo $_POST['date_released'] ?>">					    
							</div>
							<div class="form-group">					    					    
							      <input type="hidden" name="query_text" value="<?php echo $query_text ?>">					    
							</div>
							<?php
							if(isset($_POST['date_released']) && !empty($_POST['date_released']) && !empty($_POST['channel'])){
							?>							
							<div class="form-group">
								<button type="submit" class="btn btn-block">Сформировать файл для выгрузки в программу</button>
							</div>
							<?php
							}
							?>																							
	  				</form>
	  				<form role="form" id="main_form" class="form-inline pull-right" method="post" action="/excel/excel.php" target="_blank">
							<div class="form-group">					    					    
							      <input type="hidden" name="query_text" value="<?php echo $query_text ?>">					    
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-block">ЭКСПОРТ в Exel</button>
							</div>																									
	  				</form>	  				
	  			</div>	  			
			</div>
		</div>
	</div>
</div>
<div class="footer navbar-fixed-bottom text-center">
  <small>©<?php echo date("Y") ?>. <a class="sia_red" href="<?php echo $link_organization ?>" target="_blank"><b><?php echo $name_organization ?></b>.</a> Все права защищены.</small>
</div>
</body>
</html>
<!-- вставляем скрипты общие для формы добавления и редактирования -->
<script src="/js/ad.js"></script>
<script type="text/javascript">
$("#date_released").datepicker();
</script>