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
if(!isset($_SESSION['access']['8']) && !isset($_SESSION['access']['13'])){
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
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
	  			<div class="panel-heading">
	    			<h3 class="panel-title"><b><span class="text-danger">РЕДАКТИРОВАНИЕ ОБЪЯВЛЕНИЯ</span></b></h3>
	  			</div>
	  			<div class="panel-body" id="user_data">
					<form role="form" id="main_form">
					<input type="hidden" name="md5_id" value="<?php echo $advert_data['md5_id']?>">
						<div class="row">
								<div class="col-xs-6 col-sm-6 col-md-6 col-xs-offset-3 col-sm-offset-3 col-md-offset-3">
									<div class="form-group">
										<span class="text-danger"><b>ОБЪЯВЛЕНИЕ № <?php echo $advert_data['id']?> от </b></span><span class="text-danger"><b><?php echo date("d.m.Y", strtotime($advert_data['date_create']))?></b></span>
									</div>
								</div>
						</div>														
						<div class="row">
							<!-- левая половинка -->
							<div class="col-xs-6 col-sm-6 col-md-6 col-xs-offset-3 col-sm-offset-3 col-md-offset-3">
									<div class="form-group has-feedback">					    					    
									      <textarea style="resize: none;" class="form-control calc text_advert" rows="3" id="ad" name="text_advert" placeholder="Текст объявления" required><?php echo $advert_data['text_advert']?></textarea>					    
									</div>
									<div class="form-group has-feedback">					    					    
									      <input type="text" class="form-control" name="comment" placeholder="Комментарий" value="<?php echo $advert_data['comment']?>">				    
									</div>									
									<hr class="hr_red2">
									<div class="form-group has-feedback pull-right">
										<div class="checkbox-inline" >	
											<label><input type="checkbox" id="paid" name="paid" value="1" <?php echo ( $advert_data['paid'] == 1 ? ' checked' : '')?>><b><span class="text-danger">ПРИНЯТО</span></b></label>
										</div>
										&nbsp;&nbsp;&nbsp;&nbsp;<button type="submit" class="btn btn-danger">Сохранить объявление</button>
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
<div class="footer text-center">
  <small>©<?php echo date("Y") ?>. <a class="sia_red" href="<?php echo $link_organization ?>" target="_blank"><b><?php echo $name_organization ?></b>.</a></small>
</div>
</body>
</html>
<!-- вставляем скрипты общие для формы добавления и редактирования -->
<script src="/js/ad.js"></script>
<script type="text/javascript">
setInterval(check_login, 30000);
//проверка данных формы
    $('#main_form').submit(function( event ) {
    	edit_advert2();
    	return false;
    });	
</script>