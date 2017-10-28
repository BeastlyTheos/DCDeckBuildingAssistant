<?php
require "local_variables.php";

$sql = new mysqli($mysql_host, $mysql_user, $mysql_pw, $db);
if($sql->connect_error)
	die ('Connect Error ('.$sql->connect_errno.') '.
	$sql->connect_error.'.  '.$sql->sqlstate);
mysqli_query($sql, "set Names 'utf8'");
?>
