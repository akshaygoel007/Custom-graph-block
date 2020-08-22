<?php
$server_name="v7-pgsql-dev.cgx5rogykyr8.us-east-1.rds.amazonaws.com";
$username="moodle";
$password="kiyJD9sDeOHv";
$db="tbh-moodle";
$conn=mysqli_connect($server_name,$username,$password,$db);
if(!$conn)
{
	echo "connection failed";
}
else
{
	//echo "connection estabished";
}
?>
