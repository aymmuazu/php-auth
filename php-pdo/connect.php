<?php

$dsn = 'mysql:dbname=pdo;host=localhost';
$user = 'root';
$password = '';
 
try
{
	$con = new PDO($dsn,$user,$password);
	$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
	echo "PDO error".$e->getMessage();
	die();
}
?>