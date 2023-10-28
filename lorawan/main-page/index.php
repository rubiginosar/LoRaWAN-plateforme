<?php
// Connect to the database

$servername 	= "localhost:3306";	
$username 	= "root";			
$password 	= "";				
$dbname 		= "loraserver";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the coordinates from the database
$sql = "SELECT humidity, latitude, longitude, temperature, pressure, time FROM loratable ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

// Store the coordinates in a PHP array
$coordinates = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        array_push($coordinates, $row['latitude'], $row['longitude']);
    }
}
$result2 = $conn->query($sql);
$payload = array();
if ($result2->num_rows > 0) {
    while($row = $result2->fetch_assoc()) {
        $data = array(
            'temperature' => $row['temperature'],
            'pressure' => $row['pressure'],
            'humidity' => $row['humidity']
        );
        array_push($payload, $data);
    }
}/*
$payload = array();
if ($result2->num_rows > 0) {
    while($row = $result2->fetch_assoc()) {
        array_push($payload, $row['temperature'], $row['pressure'],$row['humidity'] );
    }
}*/
// Encode the PHP array in JSON format
$coordinates_json = json_encode($coordinates);
$payload_json = json_encode($payload);
$payload_obj = json_decode($payload_json);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>

    <!-- leaflet css  -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
     integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
     crossorigin=""/>
	 <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
     integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM="
     crossorigin=""></script>
	 <style>
		#map { height: 760px;
			width: 100%; 
			margin: 0 auto;
			
			
		}
	 </style>


</head>

<body>


	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="#" class="brand">
			<img src="../img/Group17.png" width="50px" hight="50px">
			 
			<span class="text">&nbsp;&nbsp;&nbsp;&nbsp;LoRaWAN</span>
		</a>
		<ul class="side-menu top">
			<li class="active">
				<a href="#" id="dashboard-link">
					<i class='bx bxs-dashboard'  ></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li>
				<a href="#" id="map-link">
					<i class='bx bxs-map'  ></i>
					<span class="text">Map</span>
				</a>
			</li>
			<!--
			<li>
				<a href="#">
					<i class='bx bxs-doughnut-chart' ></i>
					<span class="text">Analytics</span>
				</a>
			</li>
			-->
			<li>
				<a href="#" id="contact-us">
					<i class='bx bxs-message-dots' ></i>
					<span class="text">Contact us</span>
				</a>
			</li>
			<li>
				<a href="#" id="about-us">
					<i class='bx bxs-group' ></i>
					<span class="text">about us</span>
				</a>
			</li>
		</ul>
		<ul class="side-menu">
			<!--<li>
				<a href="#">
					<i class='bx bxs-cog' ></i>
					<span class="text">Settings</span>
				</a>
			</li>-->
			<li>
				<a href="../first-page/firstpage.php" class="logout">
					<i class='bx bxs-log-out-circle' ></i>
					<span class="text">Logout</span>
				</a>
			</li>
		</ul>
	</section>
	<!-- SIDEBAR -->



	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu' ></i>
			<a href="#" class="nav-link">Categories</a>
			<!-- <form action="#">
				<div class="form-input">
					<input type="search" placeholder="Search...">
					<button type="submit" class="search-btn"><i class='bx bx-search' ></i></button>
				</div> 
			</form>
			<input type="checkbox" id="switch-mode" hidden>
			<label for="switch-mode" class="switch-mode"></label>-->
			<!--<a href="#" class="notification">
				<i class='bx bxs-bell' ></i>
				<span class="num">8</span>
			</a>
	-->
		</nav>
		<!-- NAVBAR -->
		<div id="menu-content">
        <!-- Content for the icons will be dynamically loaded here -->
    </div>
	

	<script src="script.js"></script> 
	<script>
		// Get the icon links and the menu content container
	const dashboardLink = document.getElementById('dashboard-link');
    const mapLink = document.getElementById('map-link');
    const menuContent = document.getElementById('menu-content');
	const contactus = document.getElementById('contact-us');
	const aboutus = document.getElementById('about-us');

    // Add event listeners to the icon links
	window.addEventListener('load', function () {
  dashboardLink.click();
});

dashboardLink.addEventListener('click', function () {
  var xhr = new XMLHttpRequest();
  xhr.open('GET', 'dashboard/dashboardConten.html', true); // Replace 'dashboardContent.html' with the URL of your dashboard content

  xhr.onload = function () {
    if (xhr.status === 200) {
      menuContent.innerHTML = xhr.responseText;
    }
  };

  xhr.send();
});


/*
window.addEventListener('load', function () {
  loadDashboardContent(); // Initial loading

  // Refresh content every 60 seconds (adjust as needed)
  setInterval(function () {
    loadDashboardContent();
  }, 60000); 
});
*/

	contactus.addEventListener('click', function () {

	  // Perform AJAX request
	  $.ajax({
    url: 'contactus.php', // Path to your content file
    dataType: 'html',
    success: function(response) {
      // Append the loaded content to the menuContent element
      menuContent.innerHTML = response;
      
      // If you have JavaScript code in the loaded content, you can execute it here
      // ...
    },
    error: function() {
      // Handle any error that occurs during the AJAX request
      console.log('Error loading content.');
    }
  });
});

    mapLink.addEventListener('click', function () {
        // Load the map content
        menuContent.innerHTML = '<div id="map"></div> ';
		


	var map = L.map('map').setView(<?php echo $coordinates_json; ?>, 13);
	var osm=L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

var Stadia_AlidadeSmoothDark = L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_smooth_dark/{z}/{x}/{y}{r}.png', {
	maxZoom: 20,
	attribution: '&copy; <a href="https://stadiamaps.com/">Stadia Maps</a>, &copy; <a href="https://openmaptiles.org/">OpenMapTiles</a> &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors'
});
var CartoDB_Positron = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
	attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
	subdomains: 'abcd',
	maxZoom: 20
});
googleTerrain = L.tileLayer('http://{s}.google.com/vt?lyrs=p&x={x}&y={y}&z={z}',{
    maxZoom: 20,
    subdomains:['mt0','mt1','mt2','mt3']
});
googleSat = L.tileLayer('http://{s}.google.com/vt?lyrs=s&x={x}&y={y}&z={z}',{
    maxZoom: 20,
    subdomains:['mt0','mt1','mt2','mt3']
});
googleHybrid = L.tileLayer('http://{s}.google.com/vt?lyrs=s,h&x={x}&y={y}&z={z}',{
    maxZoom: 20,
    subdomains:['mt0','mt1','mt2','mt3']
});
googleStreets = L.tileLayer('http://{s}.google.com/vt?lyrs=m&x={x}&y={y}&z={z}',{
    maxZoom: 20,
    subdomains:['mt0','mt1','mt2','mt3']
});

var marker = L.marker(<?php echo $coordinates_json; ?>).addTo(map);

marker.bindPopup("<b>Current device:</b><br>Temperature: " + <?php echo $payload_obj[0]->temperature;; ?>+ "<br>Pressure: " +<?php echo $payload_obj[0]->pressure; ?> + "<br>Humidity: " +<?php echo $payload_obj[0]->humidity; ?>).openPopup();
var circle = L.circle(<?php echo $coordinates_json; ?>, {
    color: 'red',
    fillColor: '#f03',
    fillOpacity: 0.5,
    radius: 500
});
circle.bindPopup("current device location.").openPopup();
//layer control
var baseMaps = {
	"osm": osm,
    "googleStreets": googleStreets,
    "googleHybrid": googleHybrid,
	"googleSat": googleSat,
	"googleTerrain": googleTerrain,
	"Stadia_AlidadeSmoothDark": Stadia_AlidadeSmoothDark,
	"CartoDB_Positron": CartoDB_Positron

};

var overlayMaps = {
    "marker": marker,
	"circle": circle,
};
var layerControl = L.control.layers(baseMaps, overlayMaps).addTo(map);

		
    });

	aboutus.addEventListener('click', function () {

		/*menuContent.innerHTML =' <img src="../img/Desktopoo.png" width="1400px" height="1714px">';*/





  // Perform AJAX request
  $.ajax({
    url: 'aboutu.html', // Path to your content file
    dataType: 'html',
    success: function(response) {
      // Append the loaded content to the menuContent element
      menuContent.innerHTML = response;
      
      // If you have JavaScript code in the loaded content, you can execute it here
      // ...
    },
    error: function() {
      // Handle any error that occurs during the AJAX request
      console.log('Error loading content.');
    }
  });
});

		
	</script>
	</body>
</html>