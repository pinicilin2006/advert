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
$id_advert = mysql_real_escape_string($_GET['id']);
$query = "SELECT old_advert.*,user.first_name,user.second_name,user.third_name FROM `old_advert`,`user`  WHERE old_advert.id_advert = $id_advert AND old_advert.who_edit = user.user_id  ORDER BY id";
//echo $query;
$query = mysql_query($query);
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
	    			<h3 class="panel-title">Список версий объявления</h3>
	  			</div>
	  			<div class="panel-body" id="user_data">
	  			<div class="row">
	  			<hr class="hr_red2">
					<div class="table-responsive">
		    			<table class='table table-hover table-responsive table-condensed table-bordered' id='contract_table'>
		    				<thead>
		    					<tr>
				    				<th style = 'cursor: pointer;'>Дата изменения<span class="glyphicon glyphicon-sort pull-right"></span></th>
				    				<th style = 'cursor: pointer;'>Текст<span class="glyphicon glyphicon-sort pull-right"></span></th>
				    				<th style = 'cursor: pointer;'>Кто менял текст<span class="glyphicon glyphicon-sort pull-right"></span></th>
				    			</tr>
			    			</thead>
			    			<tbody>
<?php
while($row = mysql_fetch_assoc($query)){
	echo '<tr>';
	echo "<td>".date("d.m.Y H:i:s", strtotime($row['date_edit']))."</td>";
	echo "<td>".$row['text_advert']."</td>";
	echo "<td>".$row['second_name']." ".$row['first_name']." ".$row['third_name']."</td>";	
	echo "</tr>";
}
?>			    			
			    			</tbody>
			    		</table> 
			    	</div>				
	  			</div>
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
<script src="/js/ad.js"></script>
<script type="text/javascript">
$("#date_released").datepicker();
</script>