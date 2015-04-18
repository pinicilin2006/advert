<?php
if(!isset($_GET["term"])){
	exit;
}
require_once('../config.php');
require_once('../function.php');
connect_to_base();
$name = htmlspecialchars($_GET["term"]); 
$query = mysql_query("SELECT * FROM client WHERE name like '%".$name."%' ORDER BY name");
if(mysql_num_rows($query) == 0){
	exit;   
}
$result=array();
while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
		$names["label"] = $row["name"];
		$names["phone"] = $row["phone"];
		array_push($result, $names);
    }
echo json_encode($result);
?>