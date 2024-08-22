<?php

date_default_timezone_set('America/Toronto');

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(!isset($_POST['location'])) {
        $error = "No location specified";
    } else {
        $loc = htmlspecialchars($_POST['location'], ENT_QUOTES);
        $date = date('Y/m/d H:i:s');
        $orig = file_get_contents("michael.txt");
        $spl = explode("<br>", $orig, 5);
        $out = "";
        foreach($spl as $s){
            if(str_contains($s, "<br>")){
                break;
            }
            $out .= "<br>" . $s;
        }
        $file = fopen("michael.txt", "w");
        $txt = "\"". $loc . "\" at " . $date; 
        fwrite($file, $txt . $out);
        fclose($file);
        $file = fopen("history.txt", "a");
        fwrite($file, '\n' . $txt);
        fclose($file);
    }
}

?>

<!DOCTYPE HTML>
<html>
<head>
<title>MichaelSpotted.ca</title>
<script async
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC7Y0hb0SgnwzC6wSdjlYN4uLSAvPsRets&loading=async&callback=initMap">
</script>
<style>
body {
    position: absolute;
    top: 0;
    left: 0;
    background-image: url('Michael.jpg');
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-size: 100% 100%;
    width: 99%;
    height: 99%;
}
h1 {
    color: white;
    font-size: 40px;
}
h2 {
    color: white;
    font-size: 30px;
}
p {
    color: white;
}
.middle {
    top: 10%;
    position: absolute;
    width: 100%;
    text-align: center;
}

.bottom {
    top: 70%;
    position: absolute;
    width: 100%;
    text-align: center;
}
#map {
    position: absolute;
    bottom: 0;
    margin-bottom: 20px;
    height: 400px;
    width: 100%;
}
</style>
</head>
<body>
<br>
<div class="middle">
<h1>Michael Has Been Spotted!</h1>
<div>
<p>Map Refreshed At:</p>
<p id="timestamp" /></div>
<form method="POST" id="form">
    <input type="text" name="location">
    <button type="button" onclick="buttonclick()">Submit</button>
</form>
<h2>
<?php
    include "michael.txt";
?>
</h2>
</div>
<div id="map"/>

<script>
if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
}

function buttonclick(){
    document.getElementById('form').submit();

}
let map;

async function initMap() {
  const { Map } = await google.maps.importLibrary("maps");
  const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");
  const xhttp = new XMLHttpRequest();
  xhttp.onload = function() {
    let values = this.responseText.split(",");
    let lat = Number(values[0]);
    let lon = Number(values[1]);
    let timestamp = values[2];
    map = new Map(document.getElementById("map"), {
      center: { lat: lat, lng: lon },
      zoom: 15,
      mapId: "MICHAEL_MAP",
    });
    const marker = new AdvancedMarkerElement({
      map: map,
      position: { lat: lat, lng: lon },
      title: "Michael",
    });
    var date = new Date(timestamp * 1000);
    document.getElementById("timestamp").innerHTML = date.toString();
  }
  xhttp.open("GET", "info.txt", true);
  xhttp.send();
}
</script>
</body>
</html>
