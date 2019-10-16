let map;
let directionDisplay;
let markers = [];
let prevOpenWindow = false;

// jquery obj holding our form
let formObj = $("#route-search-form");
let formObj2 = $("#route-search-form2");
let formObj3 = $("#saveStopForm");

let userPosition = {lat: 0, lon: 0};

ip();

// this api key is ded, so it don't rlly matter if u remove it or keep it...
function ip() {
    $.getJSON("https://jsonip.com?callback=?", function(data) {
        fetch("https://geo.ipify.org/api/v1?apiKey=at_XXb8IeW0xwKRCEHsFcPk1a2117qub&ipAddress=" + data.ip)
        .then((resp) => resp.json())
        .then((data) => {
          let userLoc = {
            lon: data.location.lng,
            lat: data.location.lat
          };
          userPosition = userLoc;
          console.log(userLoc);
        })
        .catch((error)=>console.log(error));
    });
  };


function initMap(){

  //Map settings
  let mapOpt = {
    zoom: 11,
    center: {lat: 33.7490, lng: -84.3880}
  }

  //Map Constructor
  map = new google.maps.Map(document.getElementById('map'), mapOpt);
  directionDisplay = new google.maps.DirectionsRenderer({suppressMarkers: true});


  // new functionality to close an info window if the map is clicked
  map.addListener("click", ()=>{
    if(prevOpenWindow){
      prevOpenWindow.close();
    }
  });
  /*

  //Info Window Content
  let infoCon = {
    content: '<h1>Piedmont Park</h1>'
  }

  //Info Window Constructor
  let info = new google.maps.InfoWindow(infoCon);

  //Click-to-Open
  marker.addListener('click', function(){
    info.open(map, marker);
  });
  */
}

//Add Marker function
function addMarker(element, currETA){
  let lat = parseFloat(element["LATITUDE"]);
  let lng = parseFloat(element["LONGITUDE"]);

  // console.log(lat + " " + lng);
  let dest_image = {
          url: './assets/png/bus-stop.svg',
          // This marker is 20 pixels wide by 32 pixels high.
          size: new google.maps.Size(50, 50),
          // The origin for this image is (0, 0).
          origin: new google.maps.Point(0, 0),
          // The anchor for this image is the base of the flagpole at (0, 32).
          anchor: new google.maps.Point(0, 25)
  };

  let marker = new google.maps.Marker({
    position: {lat, lng},
    map: map,
    icon: dest_image,
    optimized: false,
    draggable: false,
    animation: google.maps.Animation.DROP
  });

  let timeUser = element['MSGTIME'].split("  ")
  let googleETAMins = parseInt(currETA.charAt(0));

  // console.log("TIME: " + timeUser);

  let respectToAdherence = getRealTimeDiff(timeUser, googleETAMins);
  console.log(respectToAdherence);
  let etaResp = "";
  if(respectToAdherence < 0){
    etaResp = "Should have arrived " + Math.abs(respectToAdherence) + " minutes ago";
  }else{
    etaResp = "Will be arriving in " + + Math.abs(respectToAdherence) + " minutes";
  }

  let infoCon = {
    content: '<h6>Heading ' + element["DIRECTION"] + '</h6>' +
              '<h6>Towards ' + element["TIMEPOINT"] + '</h6>' +
              '<h6><i>Expected</i> to Arrive in ' + currETA + '</h6>' +
              '<h6>Last Update: ' + element["MSGTIME"] + '</h6>' +
              '<h6>' + etaResp + ' </h6>' +
              '<div class="center-button"><h5><form id="saveStopForm"><input type="hidden" name="route" value=' +  element['ROUTE'] +'><input type="hidden" name="routeName" value=\'' +  element['TIMEPOINT'] +'\'><input type="submit" value="want to save it?" class="save-stop"></form></h5></div>' +
              '<div class=success></div>'
  };



  //Info Window Constructor
  let info = new google.maps.InfoWindow(infoCon);

  let busLoc = {
      lat: element['LATITUDE'], 
      lon: element['LONGITUDE']
  };

  //Click-to-Open
  marker.addListener('click', function(){
    calcRoute(busLoc, userPosition);
    // added functionality to close any previously open
    // info window when a new one is open
    if(prevOpenWindow){
      prevOpenWindow.close();
    }

    info.open(map, marker);
    prevOpenWindow = info;
  });

  markers.push(marker);
}

function getRealTimeDiff(timeUser, googleETA){

    let lastUpdateTime = new Date(timeUser);
    let currentTime = new Date();

    lastUpdateTime.setMinutes(lastUpdateTime.getMinutes() + googleETA);

    let diffTime = lastUpdateTime.getTime() - currentTime.getTime();
    let diffMins = (diffTime / 1000.0)  / 60.0
    diffMins = diffMins.toFixed(1);
    
    let BBTransitETA = diffMins;

    return BBTransitETA;
}

function calcRoute(busLoc, userLoc){
  let directionsService = new google.maps.DirectionsService();
  // console.log("BUS details: lat(" + busLoc.lat + ") and lon (" + busLoc.lon + ")");
  // console.log("user details: lat(" + userLoc.lat + ") and lon (" + userLoc.lon + ")");
  directionDisplay.setMap(null);
  directionDisplay.setMap(map);

  let user_image = {
          url: './assets/png/user.svg',
          // This marker is 20 pixels wide by 32 pixels high.
          size: new google.maps.Size(40, 40),
          // The origin for this image is (0, 0).
          origin: new google.maps.Point(0, 0),
          // The anchor for this image is the base of the flagpole at (0, 32).
          anchor: new google.maps.Point(0, 20)
  };

  let userMarker = new google.maps.Marker({
    position: new google.maps.LatLng(userLoc.lat, userLoc.lon),
    map: map,
    icon: user_image
  });

  let userInfo = new google.maps.InfoWindow({content: "Your approximate location."});
  userInfo.open(map, userMarker);

  let request = {
    origin: new google.maps.LatLng(userLoc.lat, userLoc.lon),
    destination: new google.maps.LatLng(busLoc.lat, busLoc.lon),
    travelMode: google.maps.DirectionsTravelMode.DRIVING
  };

  directionsService.route(request, function(response, status){
          if (status == google.maps.DirectionsStatus.OK) {
            directionDisplay.setDirections(response);
          }
  });


}

function getLastUpdate(lastUpdate, currTime){

}

function geocodeCurrLoc(element){
      let geocoder = new google.maps.Geocoder();
      let address = element['TIMEPOINT'] + " Atlanta, GA";
      let userGivenDest = [];

      geocoder.geocode({'address': address}, function(results, status){
          if(status === "OK"){
            let lng = results[0].geometry.location.lng();
            let lat = results[0].geometry.location.lat();

            userGivenDest.push(lat);
            userGivenDest.push(lng);

            estimateRoute(element, userGivenDest);
          }else{
            return "There was a geocoding error!";
          }
      });
}


function estimateRoute(element, dest){
        let origin = new google.maps.LatLng(element['LATITUDE'], element['LONGITUDE']); 
        let destination = new google.maps.LatLng(dest[0], dest[1]); 

        // must create a new directions sesrvice object in order to obtain route from origin to dest
        let directionsService = new google.maps.DirectionsService();

        // fill request var with our origin and dest objs, and then travelmode
        let request = {
            origin: origin, // LatLng|string
            destination: destination, // LatLng|string
            travelMode: google.maps.DirectionsTravelMode.DRIVING
        };


        // send those request details to the direction services  route method, and have callback fnction intercept the response - being necessary details like duration n distance - as well as status
        //log error if status isnt returned as OK; otherwise, print out the first routes legs duration and distance .text!!! look at JSON returns to see how google returns route!
        directionsService.route(request, function( response, status ) {
            if ( status === 'OK' ) {
                  let point = response.routes[0].legs[0];

                  let currETA = (point.duration.text + " (" + point.distance.text + ")");
                  addMarker(element, currETA);
                  // return point.duration.text + " (" + point.distance.text + ")";
            }else{
                console.log("FAIL");
                window.alert('Directions request failed due to ' + status);
                // return "route est. failed";
            }
        });

}

function displayResults(results){

  results.forEach((element)=>{
    geocodeCurrLoc(element);
  });
  // showMarkers();
}

function displayResult(result){
  geocodeCurrLoc(result);
}

function clearMarkers(){
  setMapOnAll(null);
}

function setMapOnAll(map){
  for (let i = 0; i < markers.length; i++) {
    markers[i].setMap(map);
  }
  handleButton();
}

function showMarkers(){
  setMapOnAll(map);
  // handleButton();
}

function deleteMarkers(){
  clearMarkers();
  markers=[];
}

function createResultBoxes(results){
  clearPreviousResults();
  for(let i = 0; i < results.length; i++){
    let content = $(["<div class=\"idv-res\">",
                      "<h2>" + results[i].stop_name + "</h2>",
                      "<p>Here we can put specific details about a route, such as buses going to this destination, their ETAs, and other various details that could be helpful</p>",
                    "</div>"].join("\n"));

    content.appendTo("#search-results");
  }
}

function clearPreviousResults(){
  let resultBox = $("#search-results");

  resultBox.empty();
}

 function displaySingleRoute(routeNum, routeName){
    console.log(routeNum + "and" + routeName);
    $.ajax({
      url: "./extractMARTA.php?route=" + routeNum,
      type: "GET",
      dataType: 'json',
      success: function(data){
        let singlePoint = null;
        results = JSON.parse(data);
        if(results.length === 0){
          alert("No routes by that number.")
        }else{
          for(let i = 0; i < results.length; i++){
            if(results[i]['TIMEPOINT'] == routeName){
              // console.log(results[i]);
              displayResult(results[i]);
            }
          }
        }
      },
      error: function(e){
        console.log("ERROR" + e);
        alert("ERROR: No Data Was Able to be Obtained")
      }
    });
}


//when form submission button is clicked, we will prevent the default - the reloading of the page onto .php - and send a ajax GET call, sending the route data entered in the form. If success, we overwrite our results with whatever data the .php returns, and print out; we can do whatever we like with this

// if error, we log there was an error!
$(formObj).on("submit", function(e){
  //when form submitted, resize search dialog, and search MARTA API for details
  deleteMarkers();

  let results = [];
  e.preventDefault();

  // display markers/buses on route specified
  $.ajax({
    url: "./extractMARTA.php",
    type: "GET",
    data: formObj.serialize(),
    dataType: 'json',
    success: function(data){
      results = JSON.parse(data);
      if(results.length === 0){
        alert("No routes by that number.")
      }else{
        displayResults(results);
      }
    },
    error: function(e){
      console.log("ERROR" + e);
      alert("ERROR: No Data Was Able to be Obtained")
    }
  });
});

$(formObj2).on("submit", function(e){
  e.preventDefault();

  // plots
  $.ajax({
    url: "./searchDB.php",
    type: "GET",
    data: formObj2.serialize(),
    success: function(data){
      results = JSON.parse(data);
      createResultBoxes(results);
    },
    error:function(error){
      console.log("ERROR: " + error);
    }
  });
});

$('body').on('submit', '#saveStopForm', function (e) {
  $(".success").empty();
  $.ajax({
      url: "./saveStop.php",
      type: "POST",
      data: $("#saveStopForm").serialize(),
      success: function(data){
                  if(data == "true"){
                    $("#succ-sound").get(0).play();
                    $message = $([
                                  "<div class='success-message' style='display:none'>Stop was saved successfully!</div>"
                                 ].join("\n"));
                  }else{
                    $("#fail-sound").get(0).play();
                    $message = $([
                                 "<div class='error-message' style='display:none'>You already saved this stop.</div>"
                                 ].join("\n"));
                  }
               $message.appendTo(".success");
               $message.show("fast");
      },
      error:function(error){
        window.alert("ERROR: " + error);
        $errorMessage = $([
                  "<div class='error-message'>There was a problem saving the stop.</div>"
                ].join("\n"));

        $errorMessage.appendTo(".success");
      }
  });
   e.preventDefault();
});
