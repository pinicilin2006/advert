<?php
session_start();
if(!isset($_SESSION['user_id'])){
	header("Location: login.php");
	exit;
}
header("Location: advert_list.php");
exit;
// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";
require_once('config.php');
require_once('function.php');
connect_to_base();
require_once('template/header.html');
?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="col-md-6 col-md-offset-3">
			<div class="panel panel-default">
	  			<div class="panel-heading">
	    			<h3 class="panel-title">Список изменений внесённых в веб-сервис</h3>
	  			</div>
	  			<div class="panel-body" id="user_data">
	  			<ul style="padding:0px">
				<?php
		  			$query = mysql_query("SELECT * FROM `news` ORDER BY `id` DESC");
		  			while ($row = mysql_fetch_assoc($query)) {
		  				echo '<li class="thumbnail"><small><u>'.date('d.m.Y', strtotime($row["date_create"])).'.</u></small><br><p class="">'.$row['text'].'</p></li>';
		  			}
		  		?>
		  		</ul>
	  			</div>
			</div>
			<div id="message"></div>
		</div>
	</div>
</div>
<div class="footer navbar-fixed-bottom text-center">
  <small>©<?php echo date("Y") ?>. <a class="sia_red" href="<?php echo $link_organization ?>" target="_blank"><b><?php echo $name_organization ?></b>.</a></small>
</div>
</body>
</html>