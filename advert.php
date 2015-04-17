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
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
	  			<div class="panel-heading">
	    			<h3 class="panel-title">Форма добавления объявления</h3>
	  			</div>
	  			<div class="panel-body" id="user_data">
					<form role="form" id="main_form">
						<div class="row">
								<div class="col-xs-5 col-sm-5 col-md-5">
									<div class="form-group">
									    <select class="form-control" name="item" required>
									    <option value="" disabled selected>Пункт приёма</option>
									    <?php					  		
								  		$query=mysql_query("SELECT * FROM `item` WHERE `active` = 1 ORDER BY name");
								  		while($row = mysql_fetch_assoc($query)){
											echo "<option value=\"$row[id]\" ";
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
												  	<label style="font-weight:normal"><input type="checkbox" name="channel[]" value="<?php echo $row['id']?>"><?php echo $row['name']?></label>
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
									      <input type="text" class="form-control" id="client_name" name="client_name" placeholder="Заказчик (Фамилия / Организация)" required>					    
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
									      <textarea style="resize: none;" class="form-control calc text_advert" rows="11" name="text_advert" placeholder="Текст объявления" required></textarea>					    
									</div>
						  		</div>
								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group has-feedback">				    	
										<input type="text" class="form-control calc" id="released" name="released" placeholder="Даты выходов объявления" required>
									</div>
									<div class="row" style="padding-left:0px" style="padding-right:0px">
										<div class="col-xs-6 col-sm-6 col-md-6">
											<div class="form-group has-feedback">
												<div class="input-group">
													<span class="input-group-addon"><span class="text-danger"><b>Всего слов:</b></span></span>	
													<input type="text" class="form-control calc" id="words" name="words" required>
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
											$i = 0;
											while($row = mysql_fetch_assoc($query)){
												$i++;
											?>
												<div class="radio-inline" style="padding-top:2%">
												  	<span class="text-danger"><label style="font-weight:normal"><input type="radio" class="calc" name="view_ads" value="<?php echo $row['id']?>" <?php echo ($i == 1 ? ' checked' : '')?>><b><?php echo $row['name']?></b></label></span>
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
													<input type="text" class="form-control" id="price" name="price" readonly="readonly">
												</div>
											</div>
										</div>										
									</div>
									<div class="row">
									<hr class="hr_red">
										<div class="col-xs-6 col-sm-6 col-md-6">
											<div class="form-group has-feedback" style="padding-top:2%">
												<div class="checkbox-inline">	
													<label><input type="checkbox" name="paid" value="1">Оплачено</label>
												</div>
											</div>
										</div>
										<div class="col-xs-6 col-sm-6 col-md-6" >
											<button type="submit" class="btn btn-danger btn-block">Сохранить объявление</button>
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
<script type="text/javascript">
//календарик
$('#released').multiDatesPicker({
		minDate: 0,
	  	onSelect: function() {
	    	num_days();
	    	calc();
  		}
});
//Подсчёт слов
$(document).on("keyup", ".text_advert", function(){
	num_words();
	calc();
});
//Подсчёт стоимости
$(document).on("change keyup", ".calc", function(){
	calc();
});
//проверка данных формы
    $('#main_form').submit(function( event ) {
    	add_advert();
    	return false;
    });	
</script>