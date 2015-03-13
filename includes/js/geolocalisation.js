	var map;
	var geocoder = new google.maps.Geocoder();
	var infowindow = new google.maps.InfoWindow();
	var coord;
	var marker;
	var markers = [];
	var the_new_coord;

	var input_country = 'annonce_form_pays';
	var input_dept = 'annonce_form_departement';
	var input_region = 'annonce_form_region';
	var adress = 'annonce_form_adresse';
	var town = 'annonce_form_ville';
	var postal_code = 'annonce_form_cp';
	var latitude_input = 'annonce_form_latitude';
	var longitude_input = 'annonce_form_longitude';


	function getCoordonnees() {
		var address = document.getElementById(adress).value + " " + document.getElementById(town).value + " " + document.getElementById(postal_code).value;
		geocoder.geocode( { 'address': address}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				map.setCenter(results[0].geometry.location);

				if ( markers ) {
					deleteMarkers();
				}
				generateMarker(results[0].geometry.location, map);
				if ( (results[0].geometry.location.e != "undefined") && (results[0].geometry.location.d != "undefined") ) {
					document.getElementById(latitude_input).value = results[0].geometry.location.lat();
					document.getElementById(longitude_input).value = results[0].geometry.location.lng();
				}
				else {
					document.getElementById(latitude_input).value = 0;
					document.getElementById(longitude_input).value = 0;
				}

				FillExtraFields( results[0].address_components );
			}
			else {
				document.getElementById(latitude_input).value = 0;
				document.getElementById(longitude_input).value = 0;
			//	alert("Geocode was not successful for the following reason: " + status);
			}
		});
	}

	function getDraggedCoordonees(response)
	{
		if (!response || (response.Status.code != 200))
		{
      alert("Status Code:" + response.Status.code);
    }
		else
		{
      place = response.Placemark[0];
			document.getElementById(latitude_input).value = place.Point.coordinates[1];
			document.getElementById(longitude_input).value = place.Point.coordinates[0];
    }
	}

	function FillExtraFields( address_components ) {
		if(address_components != undefined){
			if ( ( address_components[4] != undefined ) && ( address_components[4].long_name != undefined ) ) {
				document.getElementById(input_country).value = address_components[4].long_name;
			}
			if ( ( address_components[3] != undefined ) && ( address_components[3].long_name != undefined ) ) {
				document.getElementById(input_region).value = address_components[3].long_name;
			}
			if ( ( address_components[2] != undefined ) && ( address_components[2].long_name != undefined ) ) {
				document.getElementById(input_dept).value = address_components[2].long_name;
			}
		}
	}

	function generateMarker(coordinates, themap) {
		marker = new google.maps.Marker({
            position: coordinates,
            map: themap,
            icon: image_icon,
	    });

		markers.push( marker );

	    google.maps.event.addListener(marker, "dragend", function getAddress(){
			geocoder.getLocations(marker.getLatLng(), getDraggedCoordonees);
		});

		themap.setCenter(coordinates, 17);
	}

	function clearMarkers() {
	  setAllMap(null);
	}

	function setAllMap(map) {
	  for (var i = 0; i < markers.length; i++) {
	    markers[i].setMap(map);
	  }
	}

	// Deletes all markers in the array by removing references to them.
	function deleteMarkers() {
	  clearMarkers();
	  markers = [];
	}
