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
// if(!isset($_SESSION['access']['6'])){
// 	header("Location: advert_list.php");
// 	exit;
// }
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
$user_data = mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `user_id` = '".$advert_data['who_add']."'"));
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
	    			<h3 class="panel-title"><b><span class="text-danger">ПРОСМОТР ОБЪЯВЛЕНИЯ</span></b></h3>
	  			</div>
	  			<div class="panel-body" id="user_data">
					<form role="form" id="main_form">
					
						<div class="row">
								<div class="col-xs-4 col-sm-4 col-md-4">
									<div class="form-group">
										<span class="text-danger"><b>№ <?php echo $advert_data['id']?> от </b></span><span class="text-danger"><b><?php echo date("d.m.Y", strtotime($advert_data['date_create']))?>.<br>Принял: <?php echo $user_data['first_name'] ?></b></span>
									</div>
								</div>						
								<div class="col-xs-3 col-sm-3 col-md-3 col-xs-offset-5 col-sm-offset-5 col-md-offset-5">
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
												$query = mysql_query("SELECT * FROM `channel` WHERE`active` = 1 ORDER BY name");
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
													  	<label style="font-weight:normal"><input type="checkbox" name="channel[]" class="calc" id="channel_<?php echo $row['id']?>" value="<?php echo $row['id']?>"><b><span class="text-danger"><?php echo $row['name']?></span></b></label>
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
											<input type="text" class="form-control calc" id="price_day" value="<?php echo $calc_data['price_day']?>" name="price_day" required>
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
											    <select class="form-control calc" name="discount" id="discount" required>											   
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
											<label><input type="checkbox" id="paid" name="paid" value="1" <?php echo ( $advert_data['paid'] == 1 ? ' checked' : '')?>><b><span class="text-danger">ПРИНЯТО</span></b></label>
										</div>
									</div>								
							</div>
						</div>						
						<hr class="hr_red">
					</form>	
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-12" >
								<div class="pull-right">
								<?php if(isset($_SESSION['access'][13]) || (isset($_SESSION['access'][8]) && $advert_data['who_add'] == $_SESSION['user_id'])){ ?>													
										<button class="btn btn-danger" value="1">РЕДАКТИРОВАТЬ</button>
								<?php
									}
								?>
								<button class="btn btn-danger" value="3">ДУБЛИРОВАТЬ</button>   
								<?php if(isset($_SESSION['access'][6])){ ?>										
									<button class="btn btn-danger" value="2">ИСПРАВИТЬ</button>
								<?php
									}
								?>
								<?php if(isset($_SESSION['access'][7])){ ?>										
									<button class="btn btn-danger" value="4">УДАЛИТЬ</button>
								<?php
									}
								?>																
									
								</div>
							</div>
						</div>				
	  			</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-8 col-md-offset-2" >
			<div class="table-responsive">			
				<table class='table table-hover table-responsive table-condensed table-bordered' id='contract_table'>
					<thead>
						<tr>
		    				<th style = 'cursor: pointer;'>Дата изменения</th>
		    				<th style = 'cursor: pointer;'>Текст</th>
		    				<th style = 'cursor: pointer;'>Комментарий</th>
		    				<th style = 'cursor: pointer;'>Оплачено</th>
		    				<th style = 'cursor: pointer;'>Кто менял текст</th>
		    			</tr>
	    			</thead>
	    			<tbody>
	    				<?php		
						$query_history = "SELECT old_advert.*,user.first_name,user.second_name,user.third_name FROM `old_advert`,`user`  WHERE old_advert.id_advert = $advert_data[id] AND old_advert.who_edit = user.user_id  ORDER BY id";
						//echo $query;
						$query_history = mysql_query($query_history);
						while($row_history = mysql_fetch_assoc($query_history)){
							echo '<tr>';
							echo "<td>".date("d.m.Y H:i:s", strtotime($row_history['date_edit']))."</td>";
							echo "<td>".$row_history['text_advert']."</td>";
							echo "<td>".$row_history['comment']."</td>";
							echo "<td>".($row_history['paid'] == 1 ? 'Да' : 'Нет')."</td>";
							echo "<td>".$row_history['second_name']." ".$row_history['first_name']." ".$row_history['third_name']."</td>";	
							echo "</tr>";
						}							    				
	    				?>
	    			</tbody>									
				</table>
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
?>
$("#main_form :input").attr("disabled", true);
$(".btn").bind("change click", function () {
	var a = $(this).val();
	var id = "<?php echo $advert_data['md5_id']?>";
	if(a == '1'){
		window.location.replace("/advert_edit2.php?id="+id);
	}
	if(a == '2'){
		window.location.replace("/advert_edit.php?id="+id);
	}
	if(a == '3'){
		window.location.replace("/advert_copy.php?id="+id);
	}
	if(a == '4'){
		window.location.replace("/advert_delete.php?id="+id);
	}			
});
</script>