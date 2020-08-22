<?php
include("connection.php");
	$sql=mysqli_query($conn,"select * from chartjs where name like 'Monika'");
	var_dump($sql);
	exit;
    while($row=mysqli_fetch_array($sql))

?>