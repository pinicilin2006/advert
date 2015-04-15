<?php
require_once('../config.php');
require_once('../function.php');
connect_to_base();
//require_once('template/header.html');

?>
<h2 class="sub-header"><em>Цена за слово</em></h2>
<div class="table-responsive"> 	
	  			<div class="panel-body" id="user_data">
					<form class="form-horizontal col-sm-4 col-sm-offset-1" role="form" id="main_form">					
					  	<?php
					  	$query = mysql_query("select price_word.*,views_ads.name from price_word, views_ads where price_word.views_ads = views_ads.id");
					  	while($row = mysql_fetch_assoc($query)){
					  	?>
						<div class="form-group">
						    <label for="price_<?php echo $row['id']?>" class="col-sm-2 control-label"><?php echo $row['name']?>:</label>
						    <div class="col-sm-9 col-sm-offset-1">
						      <input type="text" class="form-control" id="price_<?php echo $row['id']?>" name="<?php echo $row['id']?>" value="<?php echo $row['price']?>" placeholder="Email" required>
						    </div>
						  </div>					  					    					    
					  	<?php	
					  	}
					  	?>
					  <div class="form-group">
					      <button type="submit" class="btn btn-default">Сохранить</button>
					  </div>
					</form>
	  			</div>
	  			<div id="message_result"></div>
</div>
<script type="text/javascript">
//проверка данных формы
    $('#main_form').submit(function( event ) {
    	price();
    	return false;
    });	
</script>


