<?php
session_start();
if(!isset($_SESSION['access'][1])){
	header("Location: login.php");
	exit;
}
// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";
// exit();
require_once('config.php');
require_once('function.php');
connect_to_base();
require_once('template/header.html');
?>
<link href="/css/dashboard.css" rel="stylesheet">
<div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
            <li class="item_menu active"><a class="admin_menu" id="user_add" href="#">Добавить пользователя</a></li>
            <li class="item_menu"><a class="admin_menu" id="user_edit" href="#">Редактировать пользователя</a></li>
            <li class="item_menu"><a class="admin_menu" id="item_add"href="#">Добавить пункт приёма</a></li>
            <li class="item_menu"><a class="admin_menu" id="item_edit" href="#">Редактировать пункт приёма</a></li>
            <li class="item_menu"><a class="admin_menu" id="channel_add" href="#">Добавить канал выхода</a></li>
            <li class="item_menu"><a class="admin_menu" id="channel_edit" href="#">Редактировать канал выхода</a></li>
            <li class="item_menu"><a class="admin_menu" id="price" href="#">Цена за слово</a></li>
          </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">Администрирование системы</h1>

          <div id="message"></div>
        </div>
      </div>
    </div>
  </body>
</html>
<script type="text/javascript">
$(document).ready(function(){
//Начальная загрузка страницы
$("#message").load("/admin/user_add.php");
//Загрузка содержимого в зависимости от выбранного пункта меню
$(document).on("click", ".admin_menu", function(){
	var a = $(this).attr('id');
	$(".item_menu").removeClass("active");
	$("#"+a).parents("li").addClass("active");
  $("#message").load("/admin/"+a+".php");
  return false;
});
//проверка данных формы
    // $('#main_form').submit(function( event ) {
    // 	add_news();
    // 	return false;
    // });
});

</script>