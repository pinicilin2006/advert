<?php
session_start();
unset($_SESSION["calculation"]);
if(!isset($_SESSION['user_id'])){
	header("Location: login.php");
	exit;
}
if(!isset($_SESSION['access']['7'])){
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
	// header("Location: list.php");
	// exit;	
}
$advert_data = mysql_fetch_assoc($query);
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
	  			<div class="panel-heading">
	    			<h3 class="panel-title"><b><span class="text-danger">УДАЛИТЬ ОБЪЯВЛЕНИЕ</span></b></h3>
	  			</div>
	  			<div class="panel-body" id="user_data">
					<form role="form" id="main_form">
					<input type="hidden" name="md5_id" value="<?php echo $_GET['id']?>">
					<input type="hidden" name="action" id="action">
						<div class="row">
								<div class="col-xs-6 col-sm-6 col-md-6 col-xs-offset-3 col-sm-offset-3 col-md-offset-3">
								<h4><span class="text-danger"><b>УДАЛИТЬ ОБЪЯВЛЕНИЕ № <?php echo $advert_data['id']?> от </b></span><span class="text-danger"><b><?php echo date("d.m.Y", strtotime($advert_data['date_create']))?></b></span></h4>
								<hr class="hr_red">
								<button type="button submit" class="btn btn-success btn-block" value="return">ОТМЕНИТЬ УДАЛЕНИЕ ОБЪЯВЛЕНИЯ</button>
								<button type="button submit" class="btn btn-danger btn-block" value="delete">ПОДТВЕРЖДАЮ УДАЛЕНИЕ ОБЪЯВЛЕНИЯ</button>
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
//проверка данных формы
	$(".btn").bind("change click", function () {
		$("#action").val($(this).val());
		if($(this).val() == 'return'){
			window.location.replace("/advert_list.php");
		}
	});	
    $('#main_form').submit(function( event ) {
    	delete_advert();
    	return false;
    });	
</script>