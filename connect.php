<?php
	// establish connection to DB
	$host = "bbtransit2.ctxzlyyuaidg.us-east-2.rds.amazonaws.com";
	$user = "bbtransit";
	$pass = "bbtransit";
	$db_name = "bbtransit";

	$conn = new mysqli($host, $user, $pass, $db_name);

	if($conn->connect_error){
		die("connection failed: " . $conn->connect_error);
	}
?>