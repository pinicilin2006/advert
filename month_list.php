<?php
session_start();
unset($_SESSION["calculation"]);
if(!isset($_SESSION['user_id'])){
	header("Location: login.php");
	exit;
}
if(!isset($_SESSION['access'][10])){
	header("Location: /advert_list.php");
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
$month_name = array(
	'01' => 'Январь',
	'02' => 'Февраль',
	'03' => 'Март',
	'04' => 'Апрель',
	'05' => 'Май',
	'06' => 'Июнь',
	'07' => 'Июль',
	'08' => 'Август',
	'09' => 'Сентябрь',
	'10' => 'Октябрь',
	'11' => 'Ноябрь',
	'12' => 'Декабрь',
	);
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
	  			<div class="panel-heading">
	    			<h3 class="panel-title"><b><span class="text-danger">График выхода объявлений на месяц: <?php echo $month_name["$month"] ?></span></b></h3>
	  			</div>
	  			<div class="panel-body">
				<div class="row">
	  				<hr class="hr_red">
	  				<form role="form" id="main_form" class="form-inline pull-right" method="post">				    					    
							<div class="form-group">
								<select class="form-control" name="channel" id="channel">
											<option value="" <?php echo (!$_POST['channel'] || empty($_POST['channel']) ? ' selected' : '') ?>>Все каналы выхода</option>
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
							<div class="form-group">
									<select class="form-control" name="month" id="month">
										<option value="" disabled selected>Месяц</option>
										<option value="01" <?php echo ($month == '01' ? ' selected' : '') ?>>Январь</option>
										<option value="02" <?php echo ($month == '02' ? ' selected' : '') ?>>Февраль</option>
										<option value="03" <?php echo ($month == '03' ? ' selected' : '') ?>>Март</option>
										<option value="04" <?php echo ($month == '04' ? ' selected' : '') ?>>Апрель</option>
										<option value="05" <?php echo ($month == '05' ? ' selected' : '') ?>>Май</option>
										<option value="06" <?php echo ($month == '06' ? ' selected' : '') ?>>Июнь</option>
										<option value="07" <?php echo ($month == '07' ? ' selected' : '') ?>>Июль</option>
										<option value="08" <?php echo ($month == '08' ? ' selected' : '') ?>>Август</option>
										<option value="09" <?php echo ($month == '09' ? ' selected' : '') ?>>Сентябрь</option>
										<option value="10" <?php echo ($month == '10' ? ' selected' : '') ?>>Октябрь</option>
										<option value="11" <?php echo ($month == '11' ? ' selected' : '') ?>>Ноябрь</option>
										<option value="12" <?php echo ($month == '12' ? ' selected' : '') ?>>Декабрь</option>
									</select>
		    
							</div>
							<div class="form-group">
									<select class="form-control" name="year" id="year">
										<option value="" disabled selected>Год</option>
										<?php
										for($x=date("Y");$x>=2015;$x--){
											echo "<option value=$x";
											echo ($x == $year ? ' selected' : '');
											echo ">$x</option>";
										}
										?>
									</select>
		    
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
		    					<th>№</th>
		    					<?php
		    					for($x=1;$x<=$day_in_month;$x++){
		    						echo '<th>'.$x.'</th>';
		    					}
		    					?>
		    					<th><center>Всего</center></th>
		    					<th><center>Действие</center></th>
				    			</tr>
			    			</thead>
			    			<tbody>
<?php
$query_advert = mysql_query("SELECT * FROM released_advert r, channel_advert c, advert a WHERE r.date_released LIKE '%.".$month.".".$year."' AND r.id_advert = c.id_advert AND r.id_advert = a.id AND c.id_channel ".(empty($_POST['channel']) ? '>0' : "= $_POST[channel]")." ".(!isset($_SESSION['access'][9]) ? " AND a.who_add = $_SESSION[user_id] " : '')." GROUP BY r.id_advert");
$num_in_day = array();
$num_in_day[$x] = 0;
$num_all = 0;
$advert_all = mysql_num_rows($query_advert);	
	while($advert = mysql_fetch_assoc($query_advert)){
		echo "<tr><td>$advert[id_advert]</td>";
		$x = 0;
		$k = 0;
		for($x=1;$x<=$day_in_month;$x++){
			if($x < 10){
				$x = "0".$x;
			}
			if(mysql_num_rows(mysql_query("SELECT * FROM released_advert WHERE date_released = '".$x.".".$month.".".$year."' AND id_advert = $advert[id_advert]")) > 0){
				echo '<td class="success"></td>';
				$num_in_day[$x] = $num_in_day[$x] + 1;
				$num_all = $num_all + 1;
				$k++;
			} else {
				echo '<td></td>';
			}
			
		}
		echo "<td><center><b>$k</b></center></td>";
		echo '<td><center><a href="/advert_show.php?id='.$advert['md5_id'].'">Просмотр</a></center></td>';
		echo '</tr>';
	}
echo "<tr><td><b>Всего</b>:</td>";
	for($z=1;$z<=$day_in_month;$z++){
		echo '<td>';
		if(isset($num_in_day[$z])){
			echo $num_in_day[$z];
		} else {
			echo '0';
		}
		echo '</td>';
	}
echo '<td><center><b>'.$num_all.'</b></center></td><tr>';
?>	    			
			    			</tbody>
			    		</table>
			    		<b><span class="text-danger">Всего объявлений в этом месяце: <?php echo $advert_all?></span></b><br>
			    		<b><span class="text-danger">Всего выходов объявлений в этом месяце: <?php echo $num_all?></span></b>
			    	</div>				
	  			</div>	  			
			</div>
		</div>
	</div>
</div>
<?php
// echo '<pre>';
// print_r($num_in_day);
// echo '</pre>';
?>
<div class="footer text-center">
  <small>©<?php echo date("Y") ?>. <a class="sia_red" href="<?php echo $link_organization ?>" target="_blank"><b><?php echo $name_organization ?></b>.</a></small>
</div>
</body>
</html>
<!-- вставляем скрипты общие для формы добавления и редактирования -->
<script src="/js/ad.js"></script>
<script type="text/javascript">
$("#date_released").datepicker();
setInterval(check_login, 30000);
</script>