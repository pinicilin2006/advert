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
if(!isset($_SESSION['access']['6'])){
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
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
	  			<div class="panel-heading">
	    			<h3 class="panel-title"><b><span class="text-danger">ДОБАВИТЬ ОБЪЯВЛЕНИЕ</span></b></h3>
	  			</div>
	  			<div class="panel-body" id="user_data">
					<form role="form" id="main_form">
					<input type="hidden" name="md5_id" value="<?php echo md5(date("F j, Y, g:i:s "))?>">
						<div class="row">					
								<div class="col-xs-4 col-sm-4 col-md-4 col-xs-offset-8 col-sm-offset-8 col-md-offset-8">
									<div class="form-group">
									    <select class="form-control" name="item" id="item" required>
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
						</div>
						<div class="row">
							<!-- левая половинка -->
							<div class="col-xs-6 col-sm-6 col-md-6">
								<div class="row">
									<div class="col-xs-8 col-sm-8 col-md-8">
										<div class="form-group has-feedback">					    					    
										      <input type="text" class="form-control" id="client_name" name="client_name" value="<?php echo $client_data['name']?>" placeholder="Заказчик (Фамилия / Организация)" required>					    
										</div>
							  		</div>
									<div class="col-xs-4 col-sm-4 col-md-4" style="padding-right:0px">
										<div class="form-group has-feedback">					    					    
										      <input type="text" class="form-control" id="client_phone" name="client_phone" value="<?php echo $client_data['phone']?>" placeholder="Телефон заказчика">					    
										</div>
							  		</div>									
								</div>
								<div class="row">
									<div class="col-xs-12 col-sm-12 col-md-12" style="padding-right:0px">
										<div class="form-group has-feedback">					    					    
										      <textarea style="resize: none;" class="form-control calc text_advert" rows="3" id="ad" name="text_advert" placeholder="Текст объявления" required><?php echo $advert_data['text_advert']?></textarea>					    
										</div>
									</div>									
								</div>
							</div>
							<!-- конец левой половины -->
							<!-- правая половинка -->
							<div class="col-xs-6 col-sm-6 col-md-6">
								<div class="row">
									<div class="col-xs-12 col-sm-12 col-md-12" >
												<?php
												$query = mysql_query("SELECT * FROM `channel`,`user_channels` WHERE user_channels.channel = channel.id AND user_channels.user_id = $_SESSION[user_id] AND `active` = 1 ORDER BY id");
												$all_channel = mysql_num_rows($query);
												$i = 0;
												while($row = mysql_fetch_assoc($query)){
													$i++;
													if($i == 1 || $i == 4 || $i == 7){
														echo '<div class="col-xs-4 col-sm-4 col-md-4" >';
														echo '<div class="form-group has-feedback">';
													}
												?>										
													<div class="checkbox" style="margin-bottom:6px;margin-top:0px">
													  	<label style="font-weight:normal"><input type="checkbox" name="channel[]" class="calc channel" id="channel_<?php echo $i ?>" value="<?php echo $row['id']?>"><b><span class="text-danger"><?php echo $row['name']?></span></b></label>
													</div>									
												<?php
													if($i == 3 || $i == 6 || $i == 9){
														echo '</div></div>';
													}
												}
												if($all_channel < 9){
													$i++;
													for($i;$i<=9;$i++){
														if($i == 1 || $i == 4 || $i == 7){
															echo '<div class="col-xs-4 col-sm-4 col-md-4" >';
															echo '<div class="form-group has-feedback">';
														}
														?>
														<div class="checkbox" style="margin-bottom:6px;margin-top:0px">
														  	<label style="font-weight:normal"></label>
														</div>
														<?php
														if($i == 3 || $i == 6 || $i == 9){
															echo '</div></div>';
														}																												
													}
												}												
												?>								
									</div>									
								</div>
								<div class="row">
									<div class="col-xs-12 col-sm-12 col-md-12" >
										<div class="form-group has-feedback">				    	
											<input type="text" class="form-control calc" id="released" name="released" placeholder="Даты выходов объявления" readonly="readonly" required>
										</div>
									</div>									
								</div>
							</div>
							<!-- конец правой половины -->														
						</div>
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-12" >
								<div class="col-xs-3 col-sm-3 col-md-3" style="padding-left:0px">
									<div class="form-group has-feedback">
										<div class="input-group">
											<span class="input-group-addon"><span class="text-danger"><b>Всего слов:</b></span></span>	
											<input type="text" class="form-control calc" id="words" value="<?php echo $advert_data['words']?>" name="words" required>
										</div>
									</div>										
								</div>
								<div class="col-xs-3 col-sm-3 col-md-3" style="padding-right:0px">
									<div class="form-group has-feedback">
										<div class="input-group">
											<span class="input-group-addon"><span class="text-danger"><b>Цена за день:</b></span></span>	
											<input type="text" class="form-control calc" id="price_day" value="<?php echo $calc_data['price_day']?>" name="price_day" readonly="readonly" required>
										</div>
									</div>										
								</div>
								<div class="col-xs-3 col-sm-3 col-md-3" >
									<div class="form-group has-feedback">	
										<div class="input-group">
											<span class="input-group-addon"><span class="text-danger"><b>Всего дней:</b></span></span>	
											<input type="text" class="form-control calc" id="days" name="days" required>
										</div>
									</div>
								</div>
								<div class="col-xs-3 col-sm-3 col-md-3" style="padding-right:0px">
									<div class="form-group has-feedback">
										<div class="input-group">
											<span class="input-group-addon"><span class="text-danger"><b>Скидка:</b></span></span>	
											    <select class="form-control calc" name="discount" id="discount" <?php echo (isset($_SESSION['access'][11]) ? '' : ' disabled="disabled"') ?> required>											   
											    <?php					  		
										  		$query = mysql_query("SELECT * FROM discount ORDER BY name");
										  		while($row = mysql_fetch_assoc($query)){
													echo "<option value=\"$row[id]\" ";
													echo ($row['id'] == $calc_data['discount_id'] ? ' selected' : '');
													echo ">$row[name]";
													echo "</option>";
												}
												?> 						    
											    </select>											
										</div>
									</div>										
								</div>																																	
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-12" >
								<div class="col-xs-6 col-sm-6 col-md-6" style="padding-left:0px;padding-right:0px">
									<div class="form-group has-feedback">					    					    
									      <textarea style="resize: none;" class="form-control" rows="1" id="comment" name="comment" placeholder="Комментарий"><?php echo $advert_data['comment']?></textarea>					    
									</div>
								</div>
								<div class="col-xs-3 col-sm-3 col-md-3">
									<div class="form-group has-feedback" style="padding-top:4%">
										<div class="checkbox-inline">	
											<label><input type="checkbox" class="calc" id="speed" name="speed" value="1" <?php echo ( $advert_data['speed'] == 1 ? ' checked' : '')?>><b><span class="text-danger">СРОЧНОЕ!</span></b></label>
										</div>											
									</div>										
								</div>
								<div class="col-xs-3 col-sm-3 col-md-3" style="padding-right:0px">
									<div class="form-group has-feedback">
										<div class="input-group">
										<span class="input-group-addon"><span class="text-danger"><b>СУММА К ОПЛАТЕ:</b></span></span>	
											<input type="text" class="form-control" id="price" name="price" value="<?php echo $calc_data['summa']?>" readonly="readonly">
										</div>
									</div>
								</div>																							
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-12" >
								<hr class="hr_red">
									<div class="form-group has-feedback pull-right">
										<div class="checkbox-inline" >	
											<label><input type="checkbox" id="paid" name="paid" value="1" ><b><span class="text-danger">ПРИНЯТО</span></b></label>
										</div>
										&nbsp;&nbsp;&nbsp;&nbsp;<button type="submit" class="btn btn-danger" disabled="disabled" id="save_button">Сохранить объявление</button>
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
//календарик
$('#released').multiDatesPicker({
		minDate: <?php echo (strtotime(date("d.m.Y")." ".$_SESSION['max_time']) >= strtotime(date("d.m.Y H:i")) ? 1 : 2) ?>,
	  	onSelect: function() {
	    	num_days();
	    	calc();
  		}
});
<?php
//ставим чекбоксы на каналах
while($row = mysql_fetch_assoc($channel_data)){
	echo '$("#channel_'.$row['id_channel'].'").prop("checked", true);'."\n";
}
?>
calc();

//Включаем/отключаем кнопку сохранения
$("#speed").bind("change click", function () {
    if($(this).prop("checked")){
    	$(".channel").prop("checked", false);
    	for(x=2;x<10;x++){
    		$("#channel_"+x).prop("disabled", true);
    	}
    	$("#channel_1").prop("checked", true);
    	$("#released").val('');
    	$("#released").multiDatesPicker("destroy");
		$('#released').multiDatesPicker({
				minDate: <?php echo (strtotime(date("d.m.Y")." ".$_SESSION['max_time']) >= strtotime(date("d.m.Y H:i")) ? 0 : 1) ?>,
			  	onSelect: function() {
			    	num_days();
			    	calc();
		  		}
		});    	
    } else {
    	$(".channel").prop("checked", false);
    	$(".channel").prop("disabled", false);
    	$("#released").val('');
		$("#released").multiDatesPicker("destroy");
		$('#released').multiDatesPicker({
				minDate: <?php echo (strtotime(date("d.m.Y")." ".$_SESSION['max_time']) >= strtotime(date("d.m.Y H:i")) ? 1 : 2) ?>,
			  	onSelect: function() {
			    	num_days();
			    	calc();
		  		}
		});		    	
    }

});
//проверка данных формы
    $('#main_form').submit(function( event ) {
    	add_advert();
    	return false;
    });		
</script>