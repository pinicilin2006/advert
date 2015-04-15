<?php
session_start();
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
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
	  			<div class="panel-heading">
	    			<h3 class="panel-title">Форма добавления объявления</h3>
	  			</div>
	  			<div class="panel-body" id="user_data">
					<form role="form">
						<div class="row">
								<div class="col-xs-5 col-sm-5 col-md-5">
									<div class="form-group">
									    <select class="form-control" name="item">
									    <option value="" disabled selected>Пункт приёма</option>
									    <?php					  		
								  		$query=mysql_query("SELECT * FROM `item` ORDER BY name");
								  		while($row = mysql_fetch_assoc($query)){
											echo "<option value=\"$row[id]\" ";
											echo ">$row[name]";
											echo "</option>";
										}
										?> 						    
									    </select>
									</div>
								</div>
								<div class="col-xs-7 col-sm-7 col-md-7">
									<div class="form-group ">
									<?php
									$query = mysql_query("SELECT * FROM views_ads where active = 1");
									while($row = mysql_fetch_assoc($query)){
									?>
										<div class="radio-inline">
										  	<label style="font-weight:normal"><input type="radio" name="view_ads" value="<?php echo $row['id']?>" checked><?php echo $row['name']?></label>
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
									      <input type="text" class="form-control" id="client_name" name="client_name" placeholder="Заказчик (Фамилия / Организация)">					    
									</div>
						  		</div>
								<div class="col-xs-4 col-sm-4 col-md-4">
									<div class="form-group has-feedback">					    					    
									      <input type="text" class="form-control" id="client_phone" name="client_phone" placeholder="Телефон заказчика">					    
									</div>
						  		</div>						  		
						</div>
						<div class="row">
								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group has-feedback">					    					    
									      <textarea class="form-control" rows="11" name="text_advert" placeholder="Текст объявления"></textarea>					    
									</div>
						  		</div>
								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group has-feedback">	
										<input type="text" class="form-control" id="simpliest-usage" placeholder="Дата выхода объявления">
									</div>
									<div class="col-xs-6 col-sm-6 col-md-6" style="padding-left:0px">
										<div class="form-group has-feedback">	
											<input type="text" class="form-control" id="days" name="days" placeholder="Количество дней">
										</div>
									</div>
									<div class="col-xs-6 col-sm-6 col-md-6" style="padding-right:0px">
										<div class="form-group has-feedback">		
											<input type="text" class="form-control" id="words" name="words" placeholder="Количество слов">
										</div>										
									</div>
									<div class="form-group has-feedback">
									<?php
									$query = mysql_query("SELECT * FROM `channel` WHERE `active` = 1");
									while($row = mysql_fetch_assoc($query)){
									?>
										<div class="checkbox-inline">
										  	<label style="font-weight:normal"><input type="checkbox" name="channel[]" value="<?php echo $row['id']?>"><?php echo $row['name']?></label>
										</div>									
									<?php	
									}
									?>
									</div>
									<div class="form-group has-feedback">	
										<input type="text" class="form-control" id="price" name="price" placeholder="Стоимость объявления">
									</div>
									<div class="form-group has-feedback">
										<div class="checkbox-inline">	
											<label><input type="checkbox" name="paid" value="1">Оплаченно</label>
										</div>
									</div>																			
						  		</div>						  		
						</div>
						<div class="row">						
						  <button type="submit" class="btn btn-default">Войти</button>
						</div>
					</form>	  				
	  			</div>
			</div>
			<div id="message"></div>
		</div>
	</div>
</div>
<div class="footer navbar-fixed-bottom text-center">
  <small>©<?php echo date("Y") ?>. <a class="sia_red" href="<?php echo $link_organization ?>" target="_blank"><b><?php echo $name_organization ?></b>.</a> Все права защищены.</small>
</div>
</body>
</html>
<script type="text/javascript">
	$('#simpliest-usage').multiDatesPicker();
</script>