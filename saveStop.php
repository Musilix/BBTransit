<?php
	session_start();
	include("connect.php");

	$user = $_SESSION['user'];
	$route = $_POST['route'];
	$routeName = $_POST['routeName'];

	$req = "INSERT INTO savedBuses (Username, routeNum, busName) VALUES (?,?,?)";
	$statement = $conn->prepare($req);
	$statement->bind_param("sss", $user, $route, $routeName);


	if($statement->execute()){
		echo "true";
	}else{
		echo "false";
	}


?>