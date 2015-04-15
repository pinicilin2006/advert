<?php
require_once('../config.php');
require_once('../function.php');
connect_to_base();
//require_once('template/header.html');

?>
<h2 class="sub-header"><em>Редактировать пункт приёма</em></h2>
<div class="table-responsive"> 	
	  			<div class="panel-body" id="user_data">
					<form class="form-horizontal col-sm-4 col-sm-offset-1" role="form" id="main_form">									
	  				<div class="form-group" id="item_select">
					  		<select class="form-control" name="item_id" id="item_id" required>
					  		<option value="" disabled selected>Выберите пункт приёма</option>
					  		<?php
					  		$query=mysql_query("SELECT * FROM `item` ORDER BY `name`");
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
					      <input type="text" class="form-control" id="item" name="item" placeholder="Название пункта приёма">					    
					  </div>
					  <div class="form-group">
							<label class="checkbox-inline"><input type="checkbox" name="active" id="active" value="1" checked>Пункт приёма активен</label>	    
					  </div>					  
					  <hr align="center" size="2" />
					  <div class="form-group">
					      <button type="submit" class="btn btn-default">Редактировать пункт приёма</button>
					  </div>
					</form>
	  			</div>
	  			<div id="message_result"></div>
</div>
<script type="text/javascript">
//Получаем данные по пункту приёма
$('#item_id').change(function(){
	item_data();
	return false;
});
//проверка данных формы
    $('#main_form').submit(function( event ) {
    	edit_item();
    	return false;
    });	
</script>