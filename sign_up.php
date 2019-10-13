<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>

    <!-- styles -->
    <link rel="stylesheet" type="text/css" href="./register_styles.css?v=1.1">

    <!-- fonts -->
	<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>

<body>
	<div id="total">
		<div id="signup-prompt">
			<div id="form-stuff">
				<h2 class="form-title">Sign up</h2>
            	<form method="POST" id="register-form" action="./registerUser.php">
                	<div class="form-group">
                		<input type="text" name="name" id="name" placeholder="Full Name" required/>
                	</div>
                    <div class="form-group">
                        <input type="email" name="email" id="email" placeholder="Email" required/>
                    </div>
                	<div class="form-group">
                		<input type="username" name="username" id="username" placeholder="Username" required/>
                	</div>
                	<div class="form-group">
                		<input type="password" name="password" id="pass" placeholder="Password" required/>
                	</div>
                	<div class="form-group form-button">
                		<input type="submit" name="signup" id="signup" class="form-submit" value="Register"/>
                	</div>
                </form>
                <div id="error-hold">
                    <div class="error">
                        <?php 
                            if(isset($_GET['error'])){
                                if($_GET['error'] == "username-taken"){
                                   echo "<p style=\"color:red, text-align=center\">Username is already taken; please enter another</p>"; 
                                }
                            }
                        ?>
                    </div>
                </div>

                <div id="hidden-signin">
                	<a href="./sign_in.php" class="signup-image-link">I'm already member</a>
                </div>
            </div>

            <div id="placeholder-img">
                <img src="./assets/sign_out.jpg" width="250px" height="285px">
                <a href="./sign_in.php" class="signup-image-link">I'm already member</a>
            </div>
		</div>
	</div>
    <script type="text/javascript" src="./dynamicBehavior/moveSignUpScreen.js"></script>
</body>
</html>