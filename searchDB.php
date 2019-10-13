<?php
	include("connect.php");

	$userResp = $_GET['route'];
	$pattern = "'%" . $userResp . "%'";
	$resultsArray = array();

	
	// echo "connection success <br/> ";
	// query to find data on specific dest the user enters
	$results = $conn->query('SELECT stop_name FROM stops WHERE stop_name LIKE' . $pattern);
	foreach($results as $row){
		$resultsArray[] = $row;
	}

	$resultsArray = json_encode($resultsArray, true);
	echo $resultsArray;
?>