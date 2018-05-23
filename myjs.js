
	var map;
	var markers = [];

	function initMap() {
		// Constructor creates a new map - only center and zoom are required
		map = new google.maps.Map(document.getElementById('map'), {
			center: {lat: 48.82994999, lng: 2.3499992},
			zoom: 15
		});

		var locations = [
			{title: 'Casa Mario', location: {lat: 48.84123123, lng: 2.34123132}},
			{title: 'Terrain de basket chaud', location: {lat: 48.82123132, lng: 2.30123132}},
			{title: 'Lycee', location: {lat: 48.80123132, lng: 2.38123132}}
		];


		var largeinfowindow = new google.maps.InfoWindow();

		
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
	} // end initMap

	function initAutocomplete() {
		var map = new google.maps.Map(document.getElementById('map'), {
		center: {lat: -33.8688, lng: 151.2195},
		zoom: 13,
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


	// Recherche de lieux aux alentours
	function addNearByPlaces(LatLng) {
		var nearByService = new google.maps.places.PlacesService(map);

		var request = {
			location: LatLng,
			radius: 10000,
			types: ['park']
		};
		
		nearByService.nearbySearch(request, searchNearBy);
	}

	// Ajoute les lieux à la 
	function searchNearBy(results, status) {
		if (status === google.maps.places.PlacesServiceStatus.OK) {
			for (var i = 0; i < results.length; i++) {
				var place = results[i];
				apiMarkerCreate(place.geometry.location, place);
			}
		}
	}

	function apiMarkerCreate(LatLng, placeResult) {
		var markerOptions = {
			position: LatLng,
			map: map,
			animation: google.maps.Animation.DROP,
			clickable: true
		}
		var marker = new google.maps.Marker(markerOptions);

		// if (placeResult) {
		// 	var content = placeResult.name+
		// }
	}



	// SideNav
	function openNav(sd) {
		sd.style.width = "100%";
	}

	function sub_openNav(sd) {
		sd.style.width = "90%";
	}

	function closeNav(sd) {
		sd.style.width = "0%";
	}


	// Sidenav ROOT
	document.getElementById("openNav").addEventListener('click', openNav(document.getElementById('sidenav')));
	document.getElementById("closeNav").addEventListener('click', closeNav(document.getElementById('sidenav')));
// 
