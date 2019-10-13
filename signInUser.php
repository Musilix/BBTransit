<?php
	session_start();

	include("connect.php");

	$currentUser = $_POST['username'];

	$statement = $conn->prepare("SELECT * FROM Users WHERE username = ?");
	$statement->bind_param("s", $currentUser);
	$statement->execute();
       
    $result = $statement->get_result();
    
    $statement->close();

	$givenPassword = $_POST['password'];
	if($result->num_rows > 0){
		$user = $result->fetch_assoc();

		if($givenPassword == $user['password']){
			$_SESSION['user'] = $currentUser;
			header("Location: http://ec2-18-218-1-243.us-east-2.compute.amazonaws.com/BBTransit/index.php");
			exit();
		}else{
			header("Location: http://ec2-18-218-1-243.us-east-2.compute.amazonaws.com/BBTransit/sign_in.php?error=invalid-password");
			exit();
		}
	}else{
		header("Location: http://ec2-18-218-1-243.us-east-2.compute.amazonaws.com/BBTransit/sign_in.php?error=invalid-username");
			exit();
	}
?>