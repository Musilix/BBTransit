<!DOCTYPE html>
<html>
  <head lang = "en" ng-app>
    <title>BBTransit</title>
    <meta charset="utf-8">

    <meta name="description" content="A fast and reliable place to get all the details on your bus route!">
    <meta name="keywords" content="transit, buses, trains, MARTA, atlanta, ATL, GSU, routes, commuting, travel">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./assets/icons/humanpictos.ico">

    <!-- css -->
    <link rel="stylesheet" type="text/css" href="./styles.css?v=1.1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Roboto+Mono&v=1.1">

    <!-- BootStrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <!-- geolocation on http ec2 serve -->
    <script language="JavaScript" src="http://www.geoplugin.net/javascript.gp" type="text/javascript"></script>

    <!-- hosted jQuery lib -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

  </head>

  <style type="text/css">
    html, body{
      font-family: "Roboto-Mono";
    }
  </style>

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

      <div id = "sides-wrap" style="font-family: Roboto">
        <div id="left-side-routes">
          <div id="search-form">
            <div class="form-group">
              <form id="route-search-form">
                <div class="form-group">
                  <input id="query" type="text" name="route" placeholder="Enter a Route Number" autocomplete="off">
                </div>
                <!-- <input id="send-query" type="submit" name="Submit"></button> -->
              </form>
              <p><a href="https://www.itsmarta.com/bus-routes.aspx">Don't know your route number?</a></p>
            </div>
<!-- 
            <form id="route-search-form2">
              <input id="query" type="text" name="route" placeholder="Enter a Stop Name" autocomplete="off">
              <input id="send-query" type="submit" name="Submit"></button>
            </form> -->
          </div>
          <div id="search-results"></div>
        </div>

        <div id="right-side-map">
          <div id="map"></div>
        </div>
      </div>
    </div>

    <audio id="succ-sound" src="./assets/sounds/success.mp3"></audio>
    <audio id = "fail-sound" src="./assets/sounds/fail.mp3"></audio>
    <script src="mapBus.js"></script>
    <script src="organizeElements.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAoojb_00eo6M9kuc0FO6SzSdNdpXW9mjQ&callback=initMap"
    async defer></script>


    <script>
      <?php 
        if(isset($_GET['routeNum']) && isset($_GET['routeName'])){
          $num = $_GET['routeNum'];
          $name = $_GET['routeName'];
      ?>
          displaySingleRoute(<?php echo $num ?>, <?php echo $name ?>);
      <?php
        }
      ?>

        function handleButton(){
          //check user login state to give options... very bad method, but all i got rn
          <?php
            if(isset($_SESSION['user'])){
          ?>
              let style = $('<style>.center-button { display: block; }</style>');
              $('html > head').append(style);
            
          <?php
            }else{
          ?>
              let style = $('<style>.center-button { display: none; }</style>');
              $('html > head').append(style);
          <?php
            }
          ?>
        }
    </script>
  </body>
</html>
