<!DOCTYPE html>
<html>
<head>
	<title>Saved Stops</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="./styles2.css?v=1.1">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Roboto+Mono&v=1.1">

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="./assets/icons/humanpictos.ico">

	    <!-- hosted jQuery lib -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</head>
<body>
	<div id="total-wrapper">
		<div id="top-nav-bar">
	        <a href="./index.php">
		        <div id="title">
		          <img id="logo-companion" src="./assets/png/station.png">
		          <img id ="logo-name" src="./assets/icons/logo.svg" width="200px" height="30px">
		         <!--  <h1>BBTransit</h1> -->
		        </div>
	    	</a>

	        <div id="real-nav-bar">
	          <nav>
	            <?php
	              session_start();
	              if(isset($_SESSION['user'])){
	                echo "<p class='username'>" . strtoupper($_SESSION['user']) . "</p>
	                      <p><a class='web-link' id='savestops' href='./savedStops.php'>SAVED STOPS</a></p>
	                      <p><a class='web-link' id='logout' href='./sign_out.php'>LOG OUT</a></p>";
	              }else{
	                echo "<p><a class='web-link' id='login' href='./sign_in.php'>LOG IN</a></p>";
	              }
	            ?>
	          </nav>
	        </div>
      	</div>
		<div id="stops-wrap">
			<?php
				include("connect.php");

				if(isset($_SESSION['user'])){
					$user = $_SESSION['user'];

					// $statement = $conn->prepare("SELECT * FROM bbtransit.savedStops WHERE Username = ?;"); 				 
					// $statement->bind_param("s", $user);
					// $statement->execute();
			       
			  //   	$result = $statement->get_result();
			  //   	$statement->close();
					// $structured_result = mysqli_fetch_assoc($result);

					$res = $conn->query("SELECT * FROM savedBuses WHERE Username LIKE '" . $user . "';");
					if($res->num_rows == 0){
						echo "<div id='message'><h2>You currently don't have any saved stops</h2></div>";
					}
					foreach($res as $savedStop){
						//create html element with link which will redirect to map and plot the selected saved stop
						$stopName = $savedStop['busName'];
						$stopNum = $savedStop['routeNum'];
						
						echo "<div class='saved-route-info' onClick=\"retrieveStopData(" . $stopNum . "," . "'" .  $stopName . "'" .  ")\">" .
							 	"<div id='icon-holder'><img src='./assets/png/location-pin.png'></div>" .
							 	"<div id='content-holder'>" .
							 		"<p><b>Stop Name: </b>" . $stopName . "</p>" .
							 		"<p><b>Route Number:</b> " . $stopNum . "</p>" .
							 	"</div>" .
							 "</div>";
					}
				}
			?>
		</div>
	</div>

	<script type="text/javascript">
		function retrieveStopData(routeNum, routeName){
			routeName = handleAmpersand(routeName);
			window.location.replace("./index.php?routeNum=" + routeNum + "&routeName='" + routeName + "'");
		}

		function handleAmpersand(element){
			let temp = element;
			for(let i = 0; i < temp.length; i++){
				if(temp.charAt(i) == '&'){
					console.log(i);
					temp = temp.substring(0, i) + "%26" + temp.substring(i+1, temp.length);
					return temp;
				}
			}
			return temp;
		}
	</script>
</body>
</html>
