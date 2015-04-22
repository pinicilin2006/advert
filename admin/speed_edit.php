<?php
require_once('../config.php');
require_once('../function.php');
connect_to_base();
//require_once('template/header.html');
$speed = mysql_fetch_assoc(mysql_query("SELECT * FROM speed"));
?>
<h2 class="sub-header"><em>Редактировать коэффициент за срочность</em></h2>
<div class="table-responsive"> 	
	  			<div class="panel-body" id="user_data">
					<form class="form-horizontal col-sm-4 col-sm-offset-1" role="form" id="main_form">									
					  <div class="form-group has-feedback">					    					    
					      <input type="text" class="form-control" id="speed" name="speed" value="<?php echo $speed['koef']?>" placeholder="Коэффициент">					    
					  </div>
					  
					  <hr align="center" size="2" />
					  <div class="form-group">
					      <button type="submit" class="btn btn-default">Редактировать коэффициент за срочность</button>
					  </div>
					</form>
	  			</div>
	  			<div id="message_result"></div>
</div>
<script type="text/javascript">
//проверка данных формы
    $('#main_form').submit(function( event ) {
    	edit_speed();
    	return false;
    });	
</script>