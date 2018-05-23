<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="common.css">
</head>
<body>
<header>
	<!-- Icon message -->
	<div class="nav-block">
		<img src="msg.png" class="icon" height="50" width="50" />
	</div>

	<!-- Nav Bar -->
	<div class="nav-block">
		<input type="text" ndame="search" id="search_bar" placeholder="Chercher un sport, un terrain ..." />
	</div>

	<!-- Settings -->
	<div class="nav-block">
		<img src="settings.png" id="openNav" class="icon" height="50" width="50" />
	</div>

</header>

<div id="page-container">
	
	<input type="button" id="show-sports" value="Afficher les sports" />
	<input type="button" id="hide-sports" value="Cacher les sports" />

	<div id="sidenav">
		<div id="closeNav">&#10006;</div>
		<div id="sd-filtrer_button">Filtrer</div>
		<div>Home</div>
		<div>About</div>
		<div>Contacts</div>
	</div>

	<div id="sd-filtrer">
		<div id="sub_closeNav"><p>&#10006;fermer</p></div>
		<div class="filtre-container">
			<span class="sports-span">Football</span>
		</div>
		<div class="filtre-container">
			<span class="sports-span">Basketball</span>
		</div>
		<div class="filtre-container">
			<span class="sports-span">Volleyball</span>
		</div>
		<div class="filtre-container">
			<span class="sports-span">Natation</span>
		</div>
		<div class="filtre-container">
			<span class="sports-span">Tennis</span>
		</div>
		<div class="filtre-container">
			<span class="sports-span">Vélo</span>
		</div>
		<div class="filtre-container">
			<span class="sports-span">Course</span>
		</div>
	</div>
	<div id="map"></div>

</div>


<script>
	var map;
	var markers = [];
	var basket_markers = [];
	var foot_markers = [];


	function initMap() {
		// Constructor creates a new map - only center and zoom are required
		map = new google.maps.Map(document.getElementById('map'), {
			center: {lat: 48.82994999, lng: 2.3499992},
			zoom: 15
		});

		var largeinfowindow = new google.maps.InfoWindow();

		//  Focus la map sur la localisation du mec
		if (navigator.geolocation) {
		  navigator.geolocation.getCurrentPosition(function(position) {
			var pos = {
			  lat: position.coords.latitude,
			  lng: position.coords.longitude
			};
			console.log(pos);

			largeinfowindow.setPosition(pos);
			largeinfowindow.setContent('Location found.');
			map.setCenter(pos);
		  }, function() {
			handleLocationError(true, largeinfowindow, map.getCenter());
		  });
		} else {
		  // Browser doesn't support Geolocation
		  handleLocationError(false, largeinfowindow, map.getCenter());
		}

		function getLocation() {
			navigator.geolocation.getCurrentPosition(function(position) {
				var pos = {
					lat: position.coords.latitude,
					lng: position.coords.longitude
				}
				return pos;
			});
		}

		var cityCircle = new google.maps.Circle({
			strokeColor: '#FF0000',
			strokeOpacity: 0.8,
			strokeWeight: 2,
			fillColor: '#FF0000',
			fillOpacity: 0.35,
			map: map,
			center: {lat: 48.833209, lng: 2.2359801999999998},
			radius: 800
		  });


		var locations = [
			{title: 'Casa Mario', location: {lat: 48.84123123, lng: 2.34123132}},
			{title: 'Terrain de basket chaud', location: {lat: 48.82123132, lng: 2.30123132}},
			{title: 'Lycee', location: {lat: 48.80123132, lng: 2.38123132}}
		];

		// function showSportsMarkers(sport) {
			// var locations = [
				<?php
				if (isset($_POST["sport"])):
					$bdd = new PDO("mysql:host=127.0.0.1;db_name=sporters;charset=utf8", "root", "");
					$pre_sports = mysqli_real_escape_string($_POST["sport"]);
					$sports = explode(',', $pre_sports);
					// if (count($sports) > ) {}
					foreach ($sports as $sport) {

					} 
					$query = $bdd->prepare("SELECT nom, lat, lng, adresse, description FROM terrains WHERE sport = ?");
					$query->execute([$_POST["sport"]]);
					while ($data = $query->fetch()):
				?>
				{title: ""+<?php echo $data["nom"]; ?>, location: {lat: <?php echo $data["lat"]; ?>, lng: <?php echo $data["lng"]; ?>}},


				<?php
					endwhile;
				endif;	?>
				// faire un code qui check si ya encore une data après: si n+1 existe, alors ajouter un , sinon non
			// ]
		// }
		
		for (var i = 0; i < locations.length; i++) {
			var position = locations[i].location;
			var title = locations[i].title;
			
			var icon = {
				url: "basketicon.png", // url
				scaledSize: new google.maps.Size(50, 50), // scaled size
				origin: new google.maps.Point(0,0), // origin
				anchor: new google.maps.Point(0, 0) // anchor
			};


			var marker = new google.maps.Marker({
				position: position,
				title: title,
				animation: google.maps.Animation.DROP,
				id: i,
				icon: icon 
			});

			markers.push(marker);

			marker.addListener('click', function() {
				populateInfoWindow(this, largeinfowindow);
			});

		}


		document.getElementById('show-sports').addEventListener('click', showSports);
		document.getElementById('hide-sports').addEventListener('click', hideSports);










	function initAutocomplete() {
		var map = new google.maps.Map(document.getElementById('map'), {
		center: {lat: 48.8245306, lng: 2.2743418999999676},
		zoom: 15,
		mapTypeId: 'roadmap'
		});

		// Create the search box and link it to the UI element.
		var input = document.getElementById('search_bar');
		var searchBox = new google.maps.places.SearchBox(input);

		// Bias the SearchBox results towards current map's viewport.
		map.addListener('bounds_changed', function() {
		searchBox.setBounds(map.getBounds());
		});

		var markers = [];
		// Listen for the event fired when the user selects a prediction and retrieve
		// more details for that place.
		searchBox.addListener('places_changed', function() {
		var places = searchBox.getPlaces();

		if (places.length == 0) {
			return;
		}

		// Clear out the old markers.
		markers.forEach(function(marker) {
			marker.setMap(null);
		});

		markers = [];

		// For each place, get the icon, name and location.
		var bounds = new google.maps.LatLngBounds();
		places.forEach(function(place) {
			$.a(place.geometry.location);

			if (!place.geometry) {
				console.log("Returned place contains no geometry");
				return;
			}
			
			var icon = {
				url: place.icon,
				size: new google.maps.Size(71, 71),
				origin: new google.maps.Point(0, 0),
				anchor: new google.maps.Point(17, 34),
				scaledSize: new google.maps.Size(25, 25)
			};

			// Create a marker for each place.
			markers.push(new google.maps.Marker({
				map: map,
				icon: icon,
				title: place.name,
				position: place.geometry.location
			}));

			if (place.geometry.viewport) {
			// Only geocodes have viewport.
				bounds.union(place.geometry.viewport);
			} else {
				bounds.extend(place.geometry.location);
			}
		});
		
		map.fitBounds(bounds);

		});

	}
	} // end initMap




	function initAll() {
		initMap();
		initAutocomplete();
	}








	function populateInfoWindow(marker, infowindow) {
		if (infowindow.marker != marker) {
			infowindow.marker = marker;
			// pour display les coords
			// var a = marker.position;
			// infowindow.setContent(""+a);
			infowindow.setContent(marker.title);
			infowindow.open(map, marker);

			infowindow.addListener('closeclick', function() {
				infowindow.setMarker(null);
				// ou infowindow.marker = null;
			});
		}
	}

	function showSports() {
		var bounds = new google.maps.LatLngBounds();
		for (var i = 0; i < markers.length; i++) {
			markers[i].setMap(map);
			bounds.extend(markers[i].position);
		}
		map.fitBounds(bounds);
	}

	function hideSports() {
		for (var i = 0; i < markers.length; i++) {
			markers[i].setMap(null);
		}
	}


	// // Recherche de lieux aux alentours
	// function addNearByPlaces(LatLng) {
	// 	var nearByService = new google.maps.places.PlacesService(map);

	// 	var request = {
	// 		location: LatLng,
	// 		radius: 10000,
	// 		types: ['park']
	// 	};
		
	// 	nearByService.nearbySearch(request, searchNearBy);
	// }

	// // Ajoute les lieux à la 
	// function searchNearBy(results, status) {
	// 	if (status === google.maps.places.PlacesServiceStatus.OK) {
	// 		for (var i = 0; i < results.length; i++) {
	// 			var place = results[i];
	// 			apiMarkerCreate(place.geometry.location, place);
	// 		}
	// 	}
	// }

	// function apiMarkerCreate(LatLng, placeResult) {
	// 	var markerOptions = {
	// 		position: LatLng,
	// 		map: map,
	// 		animation: google.maps.Animation.DROP,
	// 		clickable: true
	// 	}
	// 	var marker = new google.maps.Marker(markerOptions);

	// 	// if (placeResult) {
	// 	// 	var content = placeResult.name+
	// 	// }
	// }



	// SideNav
	function openNav() {
		document.getElementById('sidenav').style.width = "100%";
	}

	function closeNav() {
		document.getElementById('sidenav').style.width = "0%";
	}

	document.getElementById("openNav").addEventListener('click', openNav);
	document.getElementById("closeNav").addEventListener('click', closeNav);

	// document.getElementById("sd-filtrer_button").addEventListener('click', sub_openNav("cube"));

	// function sub_openNav(id) {
	// 	document.getElementById(id).style.width = "90%";	
	// }

</script>

<!-- Load the API -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAi_7BtnxTfKIjgBRVNXtRsyvH-o_X185U&v=3&callback=initMap&libraries=places"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
	$("#sd-filtrer_button").click(function() {
		$("#sd-filtrer").css("width", "90%");
	});

	$("#sub_closeNav").click(function() {
		$(this).parent().css("width", "0");
	});

	$(".sports-span").click(function() {
		$(this).css("background-color", "green");
		// Sanitizer ! SECURISER LE SPORT !
		var sport = $(this).text().toLowerCase();
		$.ajax({
			type: "POST",
			url: "/index.php",
			data: {"sport": sport},
			success: function() {
				console.log("Sport bien choisi");
			},
			error: function(request, status, error) {
				console.log("ERREUR: "+request.responseText);
			}
		})
	});
</script>

</body>
</html>
