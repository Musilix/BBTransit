<?php

	include("connect.php");
	if(isset($_POST['signup'])){
		$name = $_POST['name'];
		$username = $_POST['username'];
		$password = $_POST['password'];
		$email = $_POST['email'];

		$req = "SELECT * FROM Users WHERE username = ?";
		$statement = $conn->prepare($req);
		$statement->bind_param("s", $username);
		$statement->execute();

		$result = $statement->get_result();

		if($result->num_rows != 0){
			header("Location: http://ec2-18-218-1-243.us-east-2.compute.amazonaws.com/BBTransit/sign_up.php?error=username-taken");
			exit();
		}else{
			$req = "INSERT INTO Users (username, password, name, email) VALUES (?,?,?,?)";
			$statement = $conn->prepare($req);
			$statement->bind_param("ssss", $username, $password, $name, $email);
			$statement->execute();
			$statement->close();

			header("Location: http://ec2-18-218-1-243.us-east-2.compute.amazonaws.com/BBTransit/sign_in.php");
			exit();
		}
	}
?>