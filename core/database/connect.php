<?php 
	$conection_error = 'Sorry, We\'are experiencing connection problems';
	mysql_connect('localhost','root','')  or     die($conection_error);
	mysql_select_db('rl db')              or     die($conection_error);

?>