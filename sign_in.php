<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>

    <!-- styles -->
    <link rel="stylesheet" type="text/css" href="./register_styles.css?v=1.1">

    <!-- fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>

<body>
    <div id="total">
        <div id="signin-prompt">
            <div id="placeholder-img">
                <img src="./assets/sign_in.jpg" width="250px" height="285px">
                <a href="./sign_up.php" class="signup-image-link">I'm not a member</a>
            </div>

            <div id="form-stuff">
                <h2 class="form-title">Sign In</h2>

                <form method="POST" id="login-form" action="./signInUser.php">
                    <div class="form-group">
                        <input type="text" name="username" id="name" placeholder="Username" required/>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" id="pass" placeholder="Password" required/>
                    </div>
                    <?php
                        if(isset($_GET['error'])){
                            if($_GET['error'] == "invalid-password"){
                                echo "<div class='error'>The password you entered wasn't correct</div>";
                            }else if($_GET['error'] == "invalid-username"){
                                echo "<div class='error'>The username you entered wasn't correct</div>";
                            }
                        }
                    ?>
                    <div class="form-group form-button">
                        <input type="submit" name="signin" id="signin" class="form-submit" value="Log in"/>
                    </div>
                </form>

                <div id="hidden-signin">
                    <a href="./sign_up.php" class="signup-image-link">I'm not a member</a>
                </div>
            </div>
        </div>
    </div>
</body>

<script type="text/javascript">
    $(window).on("load", function(){
        $("#signin-prompt").animate({
            marginTop: 0,
            opacity: 1
        });

        $(".signup-image-link").on("click", function(){
            $("#signin-prompt").animate({
                marginBottom: "350px",
                opacity: 0
            }, 200);
        });
    });
</script>
</html>