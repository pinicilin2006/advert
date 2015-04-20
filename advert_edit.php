<?php
session_start();
unset($_SESSION["calculation"]);
if(!isset($_SESSION['user_id'])){
	header("Location: login.php");
	exit;
}
if(!isset($_GET['id'])){
	header("Location: list.php");
	exit;
}
if(!isset($_SESSION['access']['1']) || !isset($_SESSION['access']['3'])){
	header("Location: advert_list.php");
	exit;
}
// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";
require_once('config.php');
require_once('function.php');
connect_to_base();
require_once('template/header.html');
$md5_id = mysql_real_escape_string($_GET['id']);
$query = mysql_query("SELECT * FROM `advert` WHERE `md5_id` = '".$md5_id."'");
if(mysql_num_rows($query) < 1){
	header("Location: list.php");
	exit;	
}
$advert_data = mysql_fetch_assoc($query);
$client_data = mysql_fetch_assoc(mysql_query("SELECT * FROM `client` WHERE `id_client` = '".$advert_data['id_client']."'"));
$channel_data = mysql_query("SELECT * FROM `channel_advert` WHERE `id_advert` = '".$advert_data['id']."'");
$released_data = mysql_query("SELECT * FROM `released_advert` WHERE `id_advert` = '".$advert_data['id']."'");
$calc_data = mysql_fetch_assoc(mysql_query("SELECT * FROM `calculation` WHERE `id` = '".$advert_data['calc_id']."'"));
$_SESSION['calculation'] = $advert_data['calc_id'];
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
	  			<div class="panel-heading">
	    			<h3 class="panel-title">Форма редактирования объявления №: <span class="text-danger"><b><?php echo $advert_data['id']?></b></span> от <span class="text-danger"><b><?php echo date("d.m.Y", strtotime($advert_data['date_create']))?></b></span></h3>
	  			</div>
	  			<div class="panel-body" id="user_data">
					<form role="form" id="main_form">
					<input type="hidden" name="md5_id" value="<?php echo $advert_data['md5_id']?>">
						<div class="row">
								<div class="col-xs-5 col-sm-5 col-md-5">
									<div class="form-group">
									    <select class="form-control" name="item" id="item" required>
									    <option value="" disabled>Пункт приёма</option>
									    <?php					  		
								  		$query=mysql_query("SELECT * FROM `item` WHERE `active` = 1 ORDER BY name");
								  		while($row = mysql_fetch_assoc($query)){
											echo "<option value=\"$row[id]\" ";
											echo ($row['id'] == $advert_data['item'] ? ' selected' : '');
											echo ">$row[name]";
											echo "</option>";
										}
										?> 						    
									    </select>
									</div>
								</div>
								<div class="col-xs-7 col-sm-7 col-md-7" >
											<div class="form-group has-feedback">
											<?php
											$query = mysql_query("SELECT * FROM `channel` WHERE `active` = 1");
											while($row = mysql_fetch_assoc($query)){
											?>
												<div class="checkbox-inline">
												  	<label style="font-weight:normal"><input type="checkbox" name="channel[]" id="channel_<?php echo $row['id']?>" value="<?php echo $row['id']?>"><?php echo $row['name']?></label>
												</div>									
											<?php	
											}
											?>
											</div>
										</div>								

						</div>
						<div class="row">
								<div class="col-xs-8 col-sm-8 col-md-8">
									<div class="form-group has-feedback">					    					    
									      <input type="text" class="form-control" id="client_name" name="client_name" value="<?php echo $client_data['name']?>" placeholder="Заказчик (Фамилия / Организация)" required>					    
									</div>
						  		</div>
								<div class="col-xs-4 col-sm-4 col-md-4">
									<div class="form-group has-feedback">					    					    
									      <input type="text" class="form-control" id="client_phone" name="client_phone" value="<?php echo $client_data['phone']?>" placeholder="Телефон заказчика">					    
									</div>
						  		</div>						  		
						</div>
						<div class="row">
								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group has-feedback">					    					    
									      <textarea style="resize: none;" class="form-control calc text_advert" rows="11" id="ad" name="text_advert" placeholder="Текст объявления" required><?php echo $advert_data['text_advert']?></textarea>					    
									</div>
						  		</div>
								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group has-feedback">				    	
										<input type="text" class="form-control calc" id="released" name="released" placeholder="Даты выходов объявления" readonly="readonly" required>
									</div>
									<div class="row" style="padding-left:0px" style="padding-right:0px">
										<div class="col-xs-6 col-sm-6 col-md-6">
											<div class="form-group has-feedback">
												<div class="input-group">
													<span class="input-group-addon"><span class="text-danger"><b>Всего слов:</b></span></span>	
													<input type="text" class="form-control calc" id="words" name="words" value="<?php echo $advert_data['words']?>" required>
												</div>
											</div>										
										</div>									
									
										<div class="col-xs-6 col-sm-6 col-md-6" >
											<div class="form-group has-feedback">	
												<div class="input-group">
													<span class="input-group-addon"><span class="text-danger"><b>Всего дней:</b></span></span>	
													<input type="text" class="form-control calc" id="days" name="days" required>
												</div>
											</div>
										</div>										
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-12 col-md-12">
											<div class="form-group ">
											<?php
											$query = mysql_query("SELECT * FROM views_ads where active = 1");
											
											while($row = mysql_fetch_assoc($query)){
												$i++;
											?>
												<div class="radio-inline" style="padding-top:2%">
												  	<span class="text-danger"><label style="font-weight:normal"><input type="radio" class="calc" name="view_ads" value="<?php echo $row['id']?>" <?php echo ($row["id"] == $advert_data['view_ads'] ? ' checked' : '')?>><b><?php echo $row['name']?></b></label></span>
												</div>
											<?php
											}
											?>
											</div>
										</div>
									</div>
									<div class="row" style="padding-left:0px" style="padding-right:0px">
										<div class="col-xs-6 col-sm-6 col-md-6" >
											<div class="form-group has-feedback">
												<div class="input-group">
												<span class="input-group-addon"><span class="text-danger"><b>Цена:</b></span></span>	
													<input type="text" class="form-control" id="price" name="price" value="<?php echo $calc_data["summa"]?>" readonly="readonly">
												</div>
											</div>
										</div>										
									</div>
									<div class="row">
									<hr class="hr_red">
										<div class="col-xs-6 col-sm-6 col-md-6">
											<div class="form-group has-feedback" style="padding-top:2%">
												<div class="checkbox-inline">	
													<label><input type="checkbox" name="paid" value="1" <?php echo ($advert_data['paid'] == 1 ? ' checked' : '')?>>Оплачено</label>
												</div>
<!-- 												<div class="checkbox-inline">	
													<label><input type="checkbox" name="offsetting" value="1" <?php echo ($advert_data['offsetting'] == 1 ? ' checked' : '')?>>Взаимозачёт</label>
												</div> -->													
											</div>
										</div>
										<div class="col-xs-6 col-sm-6 col-md-6" >
											<button type="submit" class="btn btn-danger btn-block">Редактировать объявление</button>
										</div>
									</div>																			
						  		</div>						  		
						</div>
					</form>	  				
	  			</div>
			</div>
			<div id="message_result"></div>
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
<?php
//ставим чекбоксы на каналах
while($row = mysql_fetch_assoc($channel_data)){
	echo '$("#channel_'.$row['id_channel'].'").prop("checked", true);'."\n";
}
//забиваем поле с датами выходов
$i = 0;
$released = '';
while($row = mysql_fetch_assoc($released_data)){
	$i++;
	if($i>1){
		$released .= ', ';
	}	
	$released .= $row['date_released'];
	//Отмечаем даты в календарике
	$datepicker_date = date("m/d/Y", strtotime($row['date_released']));
	echo "$('#released').multiDatesPicker('value', '".$datepicker_date."');\n";
}
echo '$("#released").val("'.$released.'");'."\n";
//Поле с количество дней
echo '$("#days").val("'.$i.'");'."\n";
//Отмечаем даты в календарике

?>
//проверка данных формы
    $('#main_form').submit(function( event ) {
    	edit_advert();
    	return false;
    });	
</script>