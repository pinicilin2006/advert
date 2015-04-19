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
$query = "SELECT advert.*, client.*, views_ads.name views_name, item.name item_name FROM `advert`,`client`,`views_ads`,`item` WHERE advert.id_client = client.id_client AND views_ads.id = advert.view_ads AND item.id = advert.item";
if(isset($_POST['date_released']) && !empty($_POST['date_released'])){
	$query = "SELECT advert.*, client.*, views_ads.name views_name, item.name item_name, released_advert.* FROM `advert`,`client`,`views_ads`,`item`, `released_advert` WHERE advert.id_client = client.id_client AND views_ads.id = advert.view_ads AND item.id = advert.item AND released_advert.id_advert = advert.id AND released_advert.date_released = '".$_POST['date_released']."'";
}
if(isset($_POST['view_ads'])){
	$query .= " AND view_ads = $_POST[view_ads]";
}
if(isset($_POST['paid'])){
	$query .= " AND paid = 1";
}
$query .= " ORDER BY id";
$query_text = $query;
$query = mysql_query($query);
//$query .= "ORDER BY id";
if(mysql_num_rows($query) == 0){
	echo "<span class=\"text-danger\"><center>Отсутствуют объявления в базе данных.</center></span>";
	exit();	
}
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
											<?php
											$query_views = mysql_query("SELECT * FROM views_ads where active = 1");
											while($row = mysql_fetch_assoc($query_views)){
											?>
												<div class="radio-inline" style="padding-top:2%">
												  	<span><label style="font-weight:normal"><input type="radio" name="view_ads" value="<?php echo $row['id']?>" <?php echo ($row['id'] == $_POST['view_ads'] ? ' checked' : '')?>><b><?php echo $row['name']?></b></label></span>
												</div>
											<?php
											}
											?>
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
				    				<th style = 'cursor: pointer;'>№ <span class="glyphicon glyphicon-sort pull-right"></span></th>
				    				<th style = 'cursor: pointer;'>Дата создания <span class="glyphicon glyphicon-sort pull-right"></span></th>
				    				<th style = 'cursor: pointer;'>Нименование клиента<span class="glyphicon glyphicon-sort pull-right"></span></th>
									<th style = 'cursor: pointer;'>Пункт приёма<span class="glyphicon glyphicon-sort pull-right"></span></th>
				    				<th style = 'cursor: pointer;'>Вид объявления<span class="glyphicon glyphicon-sort pull-right"></span></th>
				    				<th style = 'cursor: pointer;'>Текст<span class="glyphicon glyphicon-sort pull-right"></span></th>
				    				<th style = 'cursor: pointer;'>Кол-во слов<span class="glyphicon glyphicon-sort pull-right"></span></th>
				    				<th style = 'cursor: pointer;'>кол-во дней <span class="glyphicon glyphicon-sort pull-right"></span></th>
				    				<th style = 'cursor: pointer;'>Стоимость<span class="glyphicon glyphicon-sort pull-right"></span></th>
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
	echo "<td>".$row['views_name']."</td>";
	echo "<td>".$row['text_advert']."</td>";
	echo "<td>".$row['words']."</td>";
	echo "<td>".mysql_num_rows(mysql_query("SELECT * FROM `released_advert` WHERE `id_advert` = $row[id]"))."</td>";
	echo "<td>".$row['price']."</td>";
	echo "</td>";
	echo '<td>
<div class="btn-group">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Действие <span class="caret"></span></button>
  <ul class="dropdown-menu" role="menu">';
echo '<li><a href="/advert_edit.php?id='.$row['md5_id'].'"><small>Редактировать</small></a></li><li class="divider" style="margin:0 0"></li>';
echo '<li><a href="/advert_history.php?id='.$row['id'].'"><small>Список изменений</small></a></li><li class="divider" style="margin:0 0"></li>';
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
	  				<form role="form" id="main_form" class="form-inline pull-right" method="post" action="/print/print.php" target="_blank">
							<div class="form-group">					    					    
							      <input type="hidden" name="query_text" value="<?php echo $query_text ?>">					    
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-block">Сформировать файл для выгрузки в программу</button>
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