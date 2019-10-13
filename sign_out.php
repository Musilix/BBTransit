<?php

	session_start();

	session_destroy();

	header("Location: http://ec2-18-218-1-243.us-east-2.compute.amazonaws.com/BBTransit/index.php");
	// header("Location: http://localhost/New%20Folder/BBTransit/BBTransit/index.php");
	exit();
?>