<?php
$db['db_host'] = "mysql.cs.virginia.edu";
$db['db_user'] = "nn3un";
$db['db_pass'] = "Moose2030";
$db['db_name'] = "nn3un_Databaseball_3";

foreach($db as $key => $value){
	define(strtoupper($key), $value);
}
?>