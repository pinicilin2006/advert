<?php
session_start();
if(!isset($_SESSION['user_id'])|| !isset($_POST["query_text"])){
	header("Location: /index.php");
	exit;
}
require_once('../config.php');
require_once('../function.php');
connect_to_base();
exit;
echo "<pre>";
print_r($_POST);
echo "</pre>"
?>
