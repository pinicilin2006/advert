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
$month = (empty($_POST['month']) ? date("m") : mysql_real_escape_string($_POST['month']));
$year = (empty($_POST['year']) ? date("Y") : mysql_real_escape_string($_POST['year']));
$day_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
	  			<div class="panel-heading">
	    			<h3 class="panel-title">График выхода объявлений на месяц</h3>
	  			</div>
	  			<div class="panel-body">
				<div class="row">
	  				<hr class="hr_red">
	  				<form role="form" id="main_form" class="form-inline pull-right" method="post">					    					    
							<div class="form-group">
									<select class="form-control" name="month" id="month">
										<option value="" disabled selected>Месяц</option>
										<option value="01">Январь</option>
										<option value="02">Февраль</option>
										<option value="03">Март</option>
										<option value="04">Апрель</option>
										<option value="05">Май</option>
										<option value="06">Июнь</option>
										<option value="07">Июль</option>
										<option value="08">Август</option>
										<option value="09">Сентябрь</option>
										<option value="10">Октябрь</option>
										<option value="11">Ноябрь</option>
										<option value="12">Декабрь</option>
									</select>
		    
							</div>
							<div class="form-group">
									<select class="form-control" name="year" id="year">
										<option value="" disabled selected>Год</option>
										<?php
										for($x=date("Y");$x>=2015;$x--){
											echo "<option value=$x>$x</option>";
										}
										?>
									</select>
		    
							</div>															
							<div class="form-group">
								<button type="submit" class="btn btn-block">Фильтр</button>
							</div>																		
	  				</form>
	  			</div>
	  			<div class="row">
	  			<hr class="hr_red2">
					<div class="table-responsive">
		    			<table class='table table-hover table-responsive table-condensed table-bordered' id='contract_table'>
		    				<thead>
		    					<tr>
		    					<th>Номер объявления</th>
		    					<?php
		    					for($x=1;$x<=$day_in_month;$x++){
		    						echo '<th>'.$x.'</th>';
		    					}
		    					?>
				    			</tr>
			    			</thead>
			    			<tbody>
<?php
$query_advert = mysql_query("SELECT * FROM released_advert WHERE date_released LIKE '%.".$month.".".$year."' GROUP BY id_advert");
	while($advert = mysql_fetch_assoc($query_advert)){
		echo "<tr><td>$advert[id_advert]</td>";
		$x = 0;
		for($x=1;$x<=$day_in_month;$x++){
			if($x < 10){
				$x = "0".$x;
			}
			if(mysql_num_rows(mysql_query("SELECT * FROM released_advert WHERE date_released = '".$x.".".$month.".".$year."' AND id_advert = $advert[id_advert]")) > 0){
				echo '<td class="success"></td>';
			} else {
				echo '<td></td>';
			}
		}
		echo '</tr>';
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