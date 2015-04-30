<?php
require_once('../config.php');
require_once('../function.php');
connect_to_base();
//require_once('template/header.html');

?>
<h2 class="sub-header"><em>Редактировать пользователя</em></h2>
<div class="table-responsive">
 	
	  			<div class="panel-body" id="user_data">
					<form class="form-horizontal col-sm-4 col-sm-offset-1" role="form" id="main_form">
	  				<div class="form-group" id="user_select">
					  		<select class="form-control" name="user" id="user" required>
					  		<option value="" disabled selected>Выберите пользователя</option>
					  		<?php
					  		$query=mysql_query("SELECT * FROM `user` ORDER BY user_id");
					  		while($row = mysql_fetch_assoc($query)){
								echo "<option value=\"$row[user_id]\" ";
								echo ">$row[second_name] $row[first_name] $row[third_name]";
								echo "</option>";
							}
							?>    
							</select>
							<hr>
					</div>										

<!-- 					  <div class="form-group has-feedback">					    					    
					      <input type="text" class="form-control input-sm fio" id="second_name" name="second_name" placeholder="Имя пользователя" required>					    
					  </div> -->
					  <div class="form-group has-feedback">					    					    
					      <input type="text" class="form-control input-sm fio" id="first_name" name="first_name" placeholder="Имя">					    
					  </div>

<!-- 					  <div class="form-group has-feedback">					    					    
					      <input type="text" class="form-control input-sm fio" id="third_name" name="third_name" placeholder="Отчество">					    
					  </div>					  					  

					  <div class="form-group has-feedback">					    					    
					      <input type="text" class="form-control input-sm" id="date_birth" name="date_birth" placeholder="Дата рождения">					    
					  </div> -->

					  <div class="form-group has-feedback">					    					    
					      <input type="text" class="form-control input-sm" id="login" name="login" placeholder="Логин" required>					    
					  </div>

					  <div class="form-group has-feedback">					    					    
					      <input type="text" class="form-control input-sm" id="password" name="password" placeholder="Пароль">				    
					  </div>

					  <div class="form-group has-feedback">					    					    
					      <input type="time" class="form-control input-sm" id="max_time" name="max_time" placeholder="Максимальное время приёма объявления" required>					    
					  </div>					  

					  <div class="form-group">					  
					  <dl>
					  		<dt>Права пользователя:</dt>
					  		<hr class="hr_red3">
					  		<?php
					  		$query=mysql_query("SELECT * FROM `rights` WHERE active = 1 ORDER BY name");
					  		while($row = mysql_fetch_assoc($query)){
								echo "<dd><label class=\"checkbox-inline\"><input type=\"checkbox\" class=\"data_clear\" id=right_$row[id] name=\"rights[]\" value=\"$row[id]\" >$row[name]</label></dd>";
							}
							?>
							<hr class="hr_red3"> 
					  </dl>							  
					  </div>

					  <div class="form-group">					  
					  <dl>
					  		<dt>Каналы выхода:</dt>
					  		<hr class="hr_red3">
					  		<?php
					  		$query=mysql_query("SELECT * FROM `channel` WHERE active = 1 ORDER BY id");
					  		while($row = mysql_fetch_assoc($query)){
								echo "<dd><label class=\"checkbox-inline\"><input type=\"checkbox\" class=\"data_clear\" id=channel_$row[id] name=\"channel[]\" value=\"$row[id]\" >$row[name]</label></dd>";
							}
							?>
							<hr class="hr_red3"> 
					  </dl>							  
					  </div>

					  <div class="form-group">					  
					  <dl>
					  		<dt>Пункты приёма:</dt>
					  		<hr class="hr_red3">
					  		<?php
					  		$query=mysql_query("SELECT * FROM `item` ORDER BY id");
					  		while($row = mysql_fetch_assoc($query)){
								echo "<dd><label class=\"checkbox-inline\"><input type=\"checkbox\" class=\"data_clear\" id=item_$row[id] name=\"item[]\" value=\"$row[id]\" >$row[name]</label></dd>";
							}
							?>
							<hr class="hr_red3"> 
					  </dl>							  
					  </div>

					  <hr align="center" size="2" />

					  <div class="form-group">
							<label class="checkbox-inline"><input type="checkbox" id="active" name="active" value="1" checked>Учётная запись активна</label>	    
					  </div>

					  <div class="form-group">
					      <button type="submit" class="btn btn-default">Редактировать пользователя</button>
					  </div>
					</form>
	  			</div>
	  			<div id="message_result"></div>
</div>
<script type="text/javascript">
//Маски ввода
	$('#max_time').mask('00:00');
//Ввод только английского и цифра в пароле
	$('#password').bind('keyup blur',function(){ 
    	$(this).val( $(this).val().replace(/[А-Яа-я]/g,'') );
    	 }
	);

//проверка данных формы
    $('#main_form').submit(function( event ) {
    	edit_user();
    	return false;
    });	
	$('#user').change(function(){
		var a = $("#user").val();
		user_data(a);
		return false;
	});
</script>