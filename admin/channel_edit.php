<?php
require_once('../config.php');
require_once('../function.php');
connect_to_base();
//require_once('template/header.html');

?>
<h2 class="sub-header"><em>Редактировать канал выхода</em></h2>
<div class="table-responsive"> 	
	  			<div class="panel-body" id="user_data">
					<form class="form-horizontal col-sm-4 col-sm-offset-1" role="form" id="main_form">									
	  				<div class="form-group" id="channel_select">
					  		<select class="form-control" name="channel_id" id="channel_id" required>
					  		<option value="" disabled selected>Выберите канал выхода</option>
					  		<?php
					  		$query=mysql_query("SELECT * FROM `channel` ORDER BY `name`");
					  		while($row = mysql_fetch_assoc($query)){
								echo "<option value=\"$row[id]\" ";
								echo ">$row[name]";
								echo "</option>";
							}
							?>    
							</select>
							<hr>
					</div>
					  <div class="form-group has-feedback">					    					    
					      <input type="text" class="form-control" id="channel" name="channel" placeholder="Название канала продаж">					    
					  </div>
					  <div class="form-group has-feedback">					    					    
					      <input type="text" class="form-control" id="price_word" name="price" placeholder="Цена за слово">					    
					  </div>					  
					  <div class="form-group">
							<label class="checkbox-inline"><input type="checkbox" name="active" id="active" value="1" checked>Канал продаж активен</label>	    
					  </div>					  
					  <hr align="center" size="2" />
					  <div class="form-group">
					      <button type="submit" class="btn btn-default">Редактировать канал продаж</button>
					  </div>
					</form>
	  			</div>
	  			<div id="message_result"></div>
</div>
<script type="text/javascript">
//Получаем данные по пункту приёма
$('#channel_id').change(function(){
	channel_data();
	return false;
});
//проверка данных формы
    $('#main_form').submit(function( event ) {
    	edit_channel();
    	return false;
    });	
</script>