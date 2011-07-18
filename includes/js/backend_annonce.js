var annoncejquery = jQuery.noConflict();
var mapPreview;
maxDescriptionLength = 200;
geoCodeMessage = "Vous devez entrer votre adresse avant de d\xe9placer le marqueur de carte.";
var id = 0;

function loadJs() {
	var msgs = new Object();
	msgs['LBC_MSG_geoCodeMessage'] = "Vous devez entrer votre adresse avant de d\xe9placer le marqueur de carte.";
	msgs['LBC_MSG_updatingMessage'] = "Mise \xe0 jour...";
	msgs['LBC_MSG_geocodeError'] = "Impossible de localiser votre adresse.";
	msgs['LBC_MSG_horizonMessage'] = "Vous devez placer le marqueur de carte sur l\x27emplacement de votre entreprise. Cliquez sur le lien Corriger l\x27emplacement du marqueur."; 

	mapPreview = new MapPreview(msgs);

	mapPreview.setDisplayOptions(true,
								 false,
								 false,
								 true,
								 true);
	mapPreview.init();
	lbc.setRtl(false);
	lbc.setUpBidiHandlers(new Array("address_org", "address_street1", "address_street2", "address_town", "description"));

}

function _AF_ToggleChildren(node) {
	var list = node.parentNode.childNodes;
	for (var i=0; i < list.length; i++) {
		var item = list.item(i);
		if (item.nodeType == 1) {
			item.style.display = (node == item) ? "none" : "";
		}
	}
}

function geocode(){
	var address = document.getElementById('address_street1').value+' '+document.getElementById('address_street2').value+' '+document.getElementById('address_town').value+' France';
	
	var geocoder = new GClientGeocoder();
	geocoder.getLatLng(
		address,
		function(point) {
			if (!point) {
				map.setZoom(2);
			} else {
				document.getElementById('ctlg_petiteannonce_externe_latitude').value = point.y;
				document.getElementById('ctlg_petiteannonce_externe_longitude').value = point.x;
				map.setCenter(point, 13);
				map.clearOverlays(); 
				
				var icon = new GIcon();
				icon.image = "/images/red-dot_default.png";
				icon.iconSize = new GSize(32, 32);
				icon.iconAnchor = new GPoint(0, 0);
				icon.infoWindowAnchor = new GPoint(9, 25);
				
				var marker = new GMarker(point,{icon:icon});
				map.addOverlay(marker);
				address = document.getElementById('address_street1').value+'<br/>'+document.getElementById('address_street2').value+'<br/>'+document.getElementById('address_town').value+'<br/>France';
				marker.openInfoWindowHtml(address);
				auto_completion(marker.getLatLng());
			}
		}
	);
	
	adresse_completion();
}

function auto_completion(point){
	geocoder = new GClientGeocoder();
	geocoder.getLocations(point,showAddress);
	function showAddress(response) {
		if (!response || response.Status.code != 200) {
			//If no response do nothing
		} else {
			//in autocompletion mode, we need to clean the form for put a new adress because the reverse geocode would'nt give us all field if it haven't them
			//document.getElementById('ctlg_petiteannonce_externe_adresse').value = '';
			document.getElementById('ctlg_petiteannonce_externe_cp').value = '0';
			document.getElementById('ctlg_petiteannonce_externe_ville').value = '';
			document.getElementById('ctlg_petiteannonce_externe_departement').value = '';
			document.getElementById('ctlg_petiteannonce_externe_region').value = '';
			document.getElementById('ctlg_petiteannonce_externe_pays').value = '';

			place = response.Placemark[0];

			if(place != undefined){
				if(place.AddressDetails != undefined){
					if(place.AddressDetails.Country != undefined){
						if(place.AddressDetails.Country.CountryName != undefined){
							document.getElementById('ctlg_petiteannonce_externe_pays').value = place.AddressDetails.Country.CountryName;
						}
						if(place.AddressDetails.Country.AdministrativeArea != undefined){
							if(place.AddressDetails.Country.AdministrativeArea.AdministrativeAreaName != undefined){
								document.getElementById('ctlg_petiteannonce_externe_region').value = place.AddressDetails.Country.AdministrativeArea.AdministrativeAreaName;
							}
							if(place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea != undefined){
								if(place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.Locality != undefined){
									if(place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.Locality.Thoroughfare != undefined){
										if(place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.Locality.Thoroughfare.ThoroughfareName != undefined){
											//document.getElementById('ctlg_petiteannonce_externe_adresse').value = place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.Locality.Thoroughfare.ThoroughfareName;
										}
									}
									if(place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.Locality.PostalCode != undefined){
										if(place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.Locality.PostalCode.PostalCodeNumber != undefined){
											document.getElementById('ctlg_petiteannonce_externe_cp').value = place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.Locality.PostalCode.PostalCodeNumber;
											document.getElementById('address_zip').value = place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.Locality.PostalCode.PostalCodeNumber;
										}
									}
									if(place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.Locality.LocalityName != undefined){
										document.getElementById('ctlg_petiteannonce_externe_ville').value = place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.Locality.LocalityName;
									}
								}
								if(place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.SubAdministrativeAreaName != undefined){
									document.getElementById('ctlg_petiteannonce_externe_departement').value = place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.SubAdministrativeAreaName;
								}
							}
						}
					}
				}
			}
		}
	}
}

function delete_photo(id){
	alert(id);
	return "<?php echo Provibat::delete_photo("+id+") ?>";
}

function ville_completion(){
	document.getElementById('ctlg_petiteannonce_externe_ville').value = document.getElementById('address_town').value;
	geocode();
}

function cp_completion(){
	document.getElementById('ctlg_petiteannonce_externe_cp').value = document.getElementById('address_zip').value;
}

function adresse_completion(){
	document.getElementById('ctlg_petiteannonce_externe_adresse').value = document.getElementById('address_street1').value+' '+document.getElementById('address_street2').value;
}

function preview_photo(){
	return "$ctlg_petiteannonce_photos_list = Doctrine::getTable('CtlgPetiteannonce_Photos')->getGalerie_v2('document.getElementById(\"ctlg_petiteannonce_externe_token_galerie\").value');";
}

/*		FILL A INPUT WITH ANOTHER FIELDS 		*/
function add_column_to_export_strucure(idtofill , stringtoadd, text_separator){
	var input_to_fill = document.getElementById(idtofill);
	var checkbox = document.getElementById(stringtoadd);
	var box_is_checked = checkbox.checked;
	if(box_is_checked)
	{
		input_to_fill.value += text_separator + checkbox.value + text_separator + ",";
	}
	else
	{
		input_to_fill.value = input_to_fill.value.replace(text_separator + checkbox.value + text_separator + ",",'');
	}
}

/*		EMPTY A INPUT												*/
function empty_input_field(idtofill){
	var input_to_fill = document.getElementById(idtofill);
	input_to_fill.value = '';
}

function AddSubElement_frame(the_src, form, wheretoadd, actualnumber, maxnumber, the_height){	
	id++;
	form.enctype="multipart/form-data";

	var frame = document.createElement("iframe");
	frame.id = id;
	frame.src = the_src;
	//frame.height = (the_height + 50) + 'px';
	frame.style.overflow = 'noscroll';

	document.getElementById(wheretoadd).appendChild(frame);
}
 
function check_selection(form,action){
	for (i=0, n=form.elements.length; i<n; i++)
	{
    if (action == 'check_all') form.elements[i].checked = true;
		else if (action == 'uncheck_all') form.elements[i].checked = false;
	}
}

/*	Define the different actions into annonce management interface	*/
annoncejquery(document).ready(function(){
	/*	Hide the 	*/
	setTimeout(function(){
		annoncejquery('#ajout_ok').hide();
		annoncejquery('#error_message').hide();
	},5000);

	/*		*/
	annoncejquery('#annonce_form_titre').bind('keyup', function(){
		annoncejquery('#annonce_form_urlannonce').val(annoncejquery('#annonce_form_titre').val());

		var txt = annoncejquery('#annonce_form_urlannonce').val();

		txt = txt.replace(new RegExp("[ ]", "g"),"-");
		txt = txt.replace(new RegExp("[<?php echo utf8_encode('èéêë'); ?>]", "g"),"e");
		txt = txt.replace(new RegExp("[<?php echo utf8_encode('àáâãäå'); ?>]", "g"),"a");
		txt = txt.replace(new RegExp("[<?php echo utf8_encode('æ'); ?>]", "g"),"ae");
		txt = txt.replace(new RegExp("[<?php echo utf8_encode('ç'); ?>]", "g"),"c");
		txt = txt.replace(new RegExp("[<?php echo utf8_encode('ìíîï'); ?>]", "g"),"i");
		txt = txt.replace(new RegExp("[<?php echo utf8_encode('ñ'); ?>]", "g"),"n");
		txt = txt.replace(new RegExp("[<?php echo utf8_encode('òóôõö'); ?>]", "g"),"o");
		txt = txt.replace(new RegExp("[<?php echo utf8_encode('œ'); ?>]", "g"),"oe");
		txt = txt.replace(new RegExp("[<?php echo utf8_encode('ùúûü'); ?>]", "g"),"u");
		txt = txt.replace(new RegExp("[<?php echo utf8_encode('ýÿ'); ?>]", "g"),"y");

		var reg = new RegExp("[^0-9a-zA-Z-_]", "g");
		txt = txt.replace(reg,"");

		annoncejquery('#annonce_form_urlannonce').val(txt.toLowerCase());
	});
});