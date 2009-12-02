<?php
/***************************************************
*Date: 19/10/2009      File:options.php 		   *
*Author:Eoxia							           *
*Comment: Options du Plugin Annonces               *
***************************************************/

	/**
	* Options du plugin Annonces
	*
	* @author Eoxia <contact@eoxia.com>
	* @version 1.0.0
	*/
	$location = annonces_options_page_url;
	if(isset($_POST['options'])){
		if(isset($_FILES['options']['tmp_name']))
		{
			if (is_uploaded_file($_FILES['options']['tmp_name']['change_marqueur']))
			{
				$marker = $_FILES['options']['name']['change_marqueur'];
				$extension = strtolower(strrchr($marker,'.'));
				if($extension == '.png'){
					if(move_uploaded_file($_FILES['options']['tmp_name']['change_marqueur'],WP_PLUGIN_DIR.'/'.Basename_Dirname_AOS.'/medias/images/'.$marker))
					{
						update_option('url_marqueur_courant',$marker);
						update_option('url_marqueur_perso',$marker);
						update_option('annonces_marqueur_activation','0');
					}
				}
			}
		}
		if(isset($_POST['options']['api_key'])){
			update_option('annonces_api_key',$_POST['options']['api_key']);
		}
		if(isset($_POST['options']['maps_activation'])){
			if($_POST['options']['maps_activation'] == 1){
				update_option('annonces_maps_activation',$_POST['options']['maps_activation']);
			}
		}else{
			update_option('annonces_maps_activation','0');
		}
		if(isset($_POST['options']['photos_activation'])){
			if($_POST['options']['photos_activation'] == 1){
				update_option('annonces_photos_activation',$_POST['options']['photos_activation']);
			}
		}else{
			update_option('annonces_photos_activation','0');
		}
		if(isset($_POST['options']['date_activation'])){
			if($_POST['options']['date_activation'] == 1){
				update_option('annonces_date_activation',$_POST['options']['date_activation']);
			}
		}else{
			update_option('annonces_date_activation','0');
		}
		if(isset($_POST['options']['marqueur_activation'])){
			if($_POST['options']['marqueur_activation'] == 1){
				update_option('annonces_marqueur_activation',$_POST['options']['marqueur_activation']);
				update_option('url_marqueur_courant','red-dot_default.png');
			}
		}else{
			update_option('annonces_marqueur_activation','0');
			update_option('url_marqueur_courant',get_option('url_marqueur_perso'));
		}
		if(isset($_POST['options']['theme_activation'])){
			if($_POST['options']['theme_activation'] == 1){
				update_option('theme_activation',$_POST['options']['theme_activation']);
				update_option('url_radio_toutes_theme_courant','toutes_default.png');
				update_option('url_radio_terrains_theme_courant','terrains_default.png');
				update_option('url_radio_maisons_theme_courant','maisons_default.png');
				update_option('url_budget_theme_courant','budget_default.png');
				update_option('url_superficie_theme_courant','surface_default.png');
				update_option('url_recherche_theme_courant','recherche_default.png');
			}
		}else{
			update_option('theme_activation','0');
			update_option('url_radio_toutes_theme_courant',get_option('url_radio_toutes_theme_perso'));
			update_option('url_radio_terrains_theme_courant',get_option('url_radio_terrains_theme_perso'));
			update_option('url_radio_maisons_theme_courant',get_option('url_radio_maisons_theme_perso'));
			update_option('url_budget_theme_courant',get_option('url_budget_theme_perso'));
			update_option('url_superficie_theme_courant',get_option('url_superficie_theme_perso'));
			update_option('url_recherche_theme_courant',get_option('url_recherche_theme_perso'));
		}
	}

?>
<div class="wrap">
	<script type="text/javascript" >
		var id = 0;
		function AddSubElement_frame(wheretoadd, input_file_name, form)
		{	
			id++;

			var d=document.createElement("div");
			d.id="dynamic_file";
			var i=document.createElement("input");	// ajout input file
			i.type="file";
			i.id= input_file_name+id;
			i.name= input_file_name;
			i.size=34;
			i.onchange=function(){
				form.submit();
				document.getElementById("submit_option").style.display = 'none';
			}

			d.appendChild(i);
			
			var b=document.createElement("input");	// ajout du bouton pour supprimer
			b.type="button";
			b.value="<?php _e('Annuler','annonces') ?>";
			b.onclick=function(){
				this.parentNode.style.display="none";
				document.getElementById('preview_marker').style.display = 'block';
			}
			d.appendChild(b);

			document.getElementById(wheretoadd).appendChild(d);
		}
	</script>
	<link rel="stylesheet" type="text/css" href="<?php echo WP_PLUGIN_URL.'/'.Basename_Dirname_AOS ?>/includes/css/gmlightbox.css" />
	<script type="text/javascript" src="<?php echo WP_PLUGIN_URL.'/'.Basename_Dirname_AOS ?>/includes/js/gmlightbox.js"></script>
	<h2><?php _e('Annonces Options', 'annonces') ?></h2>
	<form name="annonces_options_form" method="post" action="" enctype="multipart/form-data" >
		<table width="100%" cellspacing="2" cellpadding="5" class="form-table">
			<tr valign="baseline">
				<th scope="row"><?php _e('Cl&eacute; Google Maps', 'annonces') ?></th> 
				<td>
					<input type="text" id="api_key" name="options[api_key]" value="<?php echo (get_option('annonces_api_key')? get_option('annonces_api_key'): '') ?>" />
					<p><small><?php _e('Cette cl&eacute; autorise Google Maps &agrave; afficher une carte pour g&eacute;olocaliser vos annocnes.', 'annonces') ?></small></p>
				</td>
			</tr>
			<tr valign="baseline">
				<?php
					/**
					* Ajoute la cle Google Maps dans les options de Wordpress (BD)
					*/
				?>
				<th scope="row"><?php _e('Activer G&eacute;olocalisation', 'annonces') ?></th> 
				<td>
					<input type="checkbox" id="maps_activation" name="options[maps_activation]" value="1" <?php echo (get_option('annonces_maps_activation')? 'checked': '') ?> />
					<p><small><?php _e('Cette option lorsqu&#146;elle est activ&eacute;e, utilise Google Maps pour g&eacute;olocaliser vos annonces sur une carte.', 'annonces') ?></small></p>
				</td>
			</tr>
			<tr valign="baseline">
				<?php
					/**
					* Active l'affichage des photos dans les options de Wordpress (BD)
					*/
				?>
				<th scope="row"><?php _e('Activer Photos', 'annonces') ?></th> 
				<td>
					<input type="checkbox" id="photos_activation" name="options[photos_activation]" value="1" <?php echo (get_option('annonces_photos_activation')? 'checked': '') ?> />
					<p><small><?php _e('Cette option lorsqu&#146;elle est activ&eacute;e, affiche les photos de vos annonces si celles-ci sont d&eacute;finies.', 'annonces') ?></small></p>
				</td>
			</tr>
			<tr valign="baseline">
				<?php
					/**
					* Active l'affichage de la date dans les options de Wordpress (BD)
					*/
				?>
				<th scope="row"><?php _e('Activer Date', 'annonces') ?></th> 
				<td>
					<input type="checkbox" id="date_activation" name="options[date_activation]" value="1" <?php echo (get_option('annonces_date_activation')? 'checked': '') ?> />
					<p><small><?php _e('Cette option lorsqu&#146;elle est activ&eacute;e, affiche la date de derni&egrave;re modification de vos annonces.', 'annonces') ?></small></p>
				</td>
			</tr>
			<tr valign="baseline">
				<?php
					/**
					* Marqueur qui sera afficher dans la carte Google Maps
					*/
				?>
				<th scope="row"><?php _e('Marqueur', 'annonces') ?></th> 
				<td>
					<img onclick="AddSubElement_frame('btn_marker', 'options[change_marqueur]' , document.forms.annonces_options_form);this.style.display='none'" style="cursor:pointer;" id="preview_marker" src="<?php echo WP_PLUGIN_URL.'/'.Basename_Dirname_AOS.'/medias/images/'.get_option('url_marqueur_courant') ?>" alt="marqueur actif"/>
					<div id="btn_marker" style="clear:both;"></div>
					<br/>
					<input type="checkbox" id="marqueur_activation" name="options[marqueur_activation]" value="1" <?php echo (get_option('annonces_marqueur_activation')? 'checked': '') ?> /><label for="marqueur_activation"><?php _e('Utiliser le marqueur par d&eacute;faut.', 'annonces') ?></label>
					<p><small><?php _e('Vous pouvez d&eacute;finir un marqueur diff&eacute;rent que celui de Google Maps pour r&eacute;f&eacute;rencer vos annnonces sur la carte.', 'annonces') ?></small></p>
				</td>
			</tr>
			<tr valign="baseline">
				<?php
					/**
					* Changer le thème du plugin: cette option change les boutons du listing des annonces
					*/
				?>
				<th scope="row"><?php _e('Th&egrave;me', 'annonces') ?></th> 
				<td>
					<a rel="theme" rev="<?php echo WP_PLUGIN_URL.'/'.Basename_Dirname_AOS ?>/admin/theme.php" id="option_theme" title="<?php _e('Changer le th&egrave;me des boutons', 'annonces') ?>"><?php _e('Changer...','annonces') ?></a>
					<br/>
					<input type="checkbox" id="theme_activation" name="options[theme_activation]" value="1" <?php echo (get_option('theme_activation')? 'checked': '') ?> /><label for="theme_activation"><?php _e('Utiliser le th&egrave;me par d&eacute;faut.','annonces') ?></label>
					<p><small><?php _e('Cette option permet de changer l&#146;apparence des boutons du bloc recherche.', 'annonces') ?></small></p>
				</td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" id="submit_option" name="Submit" value="<?php _e('Envoyer', 'annonces') ?>" />
		</p>
	</form>
</div>