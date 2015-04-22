<?php
require_once('../config.php');
require_once('../function.php');
connect_to_base();
//require_once('template/header.html');

?>
<h2 class="sub-header"><em>Список пользователей</em></h2>
<div class="table-responsive">
		    			<table class='table table-hover table-responsive table-condensed table-bordered' id='contract_table'>
		    				<thead>
		    					<tr>
				    				<th style = 'cursor: pointer;'><center>ID</center></th>
				    				<th style = 'cursor: pointer;'><center>Имя</center></th>
				    				<th style = 'cursor: pointer;'><center>Дата добавления</center></th>
									<th style = 'cursor: pointer;'><center>Кто добавил</center></th>
				    			</tr>
			    			</thead>
			    			<tbody>
<?php
$query = mysql_query("SELECT * FROM user ORDER BY date_register");
while($row = mysql_fetch_assoc($query)){
	if($row['active'] == '1'){
		echo '<tr class="success">';	
	} else {
		echo '<tr class="danger">';	
	}
	echo "<td><center>".$row['user_id']."</center></td>";
	echo "<td><center>".$row['first_name']."</center></td>";	
	echo "<td><center>".date("d.m.Y H:i:s", strtotime($row['date_register']))."</center></td>";
	echo "<td><center>".$row['who_added']."</center></td>";
	echo "</td>";	
	echo "</tr>";
}
?>			    			
	</tbody>
</table>
</div> 
