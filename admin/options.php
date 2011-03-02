<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<link rel="stylesheet" type="text/css" href="../includes/css/admin.css" />
</head>
<body>
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

	require_once dirname(__FILE__).'./../includes/lib/options.class.php';
	
	if(isset($_POST["submit"]))
	{
		global $wpdb;		
	
		if(isset($_FILES['options']['tmp_name']))
		{
			$extensions = array(".png", ".jpg", ".bmp");
			if (is_uploaded_file($_FILES['options']['tmp_name']['change_marqueur']))
			{
				$img = $_FILES['options']['name']['change_marqueur'];
				$extension = strtolower(strrchr($img,'.'));
				if(in_array($extension,$extensions)){
					if(move_uploaded_file($_FILES['options']['tmp_name']['change_marqueur'],WP_PLUGIN_DIR.'/'.Basename_Dirname_AOS.'/medias/images/'.$img))
					{
						$wpdb->query('UPDATE `'.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__option` SET nomoption ="'.$img.'" WHERE labeloption="url_marqueur_courant"');
					}
				}
			}
		}
		
		if(isset($_POST['options']['api_key']))
		{
			$wpdb->query('UPDATE `'.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__option` SET nomoption ="'.$_POST['options']['api_key'].'" WHERE labeloption="annonces_api_key"');
		}
		if(isset($_POST['options']['monnaie']))
		{
			switch($_POST['options']['monnaie'])
			{
				case 'euro':
					$maMonnaie = '&euro;';
				break;
				case 'yen':
					$maMonnaie = '&yen;';
				break;
				case 'pound':
					$maMonnaie = '&pound;';
				break;
				case 'dollar':
					$maMonnaie = '$';
				break;
			}
			$query = $wpdb->prepare(
				"UPDATE ".$wpdb->prefix.small_ad_table_prefix_AOS."petiteannonce__attribut
				SET measureunit = %s 
				WHERE labelattribut = 'PrixLoyerPrixDeCession' ", 
				$maMonnaie
			);
			$wpdb->query($query);
		}
		
		if(isset($_POST['options']['maps_activation']))
		{
			if($_POST['options']['maps_activation'] == 1)
			{
				$wpdb->query('UPDATE `'.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__option` SET nomoption ="'.$_POST['options']['maps_activation'].'" WHERE labeloption="annonces_maps_activation"');
			}
		}
		else
		{
			$wpdb->query('UPDATE `'.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__option` SET nomoption =0 WHERE labeloption="annonces_maps_activation"');
		}
		
		if(isset($_POST['options']['photos_activation']))
		{
			if($_POST['options']['photos_activation'] == 1)
			{
				$wpdb->query('UPDATE `'.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__option` SET nomoption ="'.$_POST['options']['photos_activation'].'" WHERE labeloption="annonces_photos_activation"');
			}
		}
		else
		{
			$wpdb->query('UPDATE `'.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__option` SET nomoption =0 WHERE labeloption="annonces_photos_activation"');
		}
		
		if(isset($_POST['options']['date_activation']))
		{
			if($_POST['options']['date_activation'] == 1)
			{
				$wpdb->query('UPDATE `'.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__option` SET nomoption ="'.$_POST['options']['date_activation'].'" WHERE labeloption="annonces_date_activation"');
			}
		}
		else
		{
			$wpdb->query('UPDATE `'.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__option` SET nomoption =0 WHERE labeloption="annonces_date_activation"');
		}
		if(isset($_POST['options']['theme_activation']))
		{
			if($_POST['options']['theme_activation'] == 1)
			{
				$wpdb->query('UPDATE `'.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__option` SET nomoption ="'.$_POST['options']['theme_activation'].'" WHERE labeloption="theme_activation"');
				
					annonces_options::updateoption('url_radio_toutes_theme_defaut','url_radio_toutes_theme_courant');
					annonces_options::updateoption('url_radio_terrains_theme_defaut','url_radio_terrains_theme_courant');
					annonces_options::updateoption('url_radio_maisons_theme_defaut','url_radio_maisons_theme_courant');
					annonces_options::updateoption('url_budget_theme_defaut','url_budget_theme_courant');
					annonces_options::updateoption('url_superficie_theme_defaut','url_superficie_theme_courant');
					annonces_options::updateoption('url_recherche_theme_defaut','url_recherche_theme_courant');
			}
		}
		else
		{
			$wpdb->query('UPDATE `'.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__option` SET nomoption =0 WHERE labeloption="theme_activation"');
		}
		
		if(isset($_POST['options']['marqueur_activation']))
		{
			if($_POST['options']['theme_activation'] == 1)
			{
				$wpdb->query('UPDATE `'.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__option` SET nomoption ="'.$_POST['options']['marqueur_activation'].'" WHERE labeloption="annonces_marqueur_activation"');
			
				annonces_options::updateoption('url_marqueur_defaut','url_marqueur_courant');
			}
		}
		else
		{
			$wpdb->query('UPDATE `'.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__option` SET nomoption =0 WHERE labeloption="annonces_marqueur_activation"');
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
		<table class="form-table">
			<tr class="v_align">
				<th><?php _e('Cl&eacute; Google Maps', 'annonces') ?></th> 
				<td>
					<input type="text" id="api_key" name="options[api_key]" value="<?php echo annonces_options::recupinfo('annonces_api_key'); ?>" />
					<p><small><?php _e('Cette cl&eacute; autorise Google Maps &agrave; afficher une carte pour g&eacute;olocaliser vos annonces.', 'annonces') ?></small></p>
				</td>
			</tr>
			<tr class="v_align">
				<?php
					/**
					* Monnaie
					*/
				?>
				<th><?php echo __('Monnaie', 'annonces') ?></th>
				<td>
					<select name="options[monnaie]">
						<option <?php if (annonces_options::monnaie() == '$') echo 'selected'?> value="dollar"><?php echo __('Dollar US','annonces') ?></option>
						<option <?php if (annonces_options::monnaie() == '&euro;') echo 'selected'?> value="euro"><?php echo __('Euro','annonces') ?></option>
						<option <?php if (annonces_options::monnaie() == '&pound;') echo 'selected'?> value="pound"><?php echo __('Livre Sterling','annonces') ?></option>
						<option <?php if (annonces_options::monnaie() == '&yen;') echo 'selected'?> value="yen"><?php echo __('Yen','annonces') ?></option>
					</select>
					<p><small><?php _e('Cette option permet de configurer la monnaie qui sera effective sur le site.', 'annonces') ?></small></p>
				</td>
			</tr>
			<tr class="v_align">
				<?php
					/**
					* Ajoute la cle Google Maps dans les options de Wordpress (BD)
					*/
				?>
				<th><?php _e('Activer G&eacute;olocalisation', 'annonces') ?></th> 
				<td>
					<input type="checkbox" id="maps_activation" name="options[maps_activation]" value="1" <?php echo (annonces_options::recupinfo('annonces_maps_activation')? 'checked': '') ?> />
					<p><small><?php _e('Cette option lorsqu&#146;elle est activ&eacute;e, utilise Google Maps pour g&eacute;olocaliser vos annonces sur une carte.', 'annonces') ?></small></p>
				</td>
			</tr>
			<tr class="v_align">
				<?php
					/**
					* Active l'affichage des photos dans les options de Wordpress (BD)
					*/
				?>
				<th><?php _e('Activer Photos', 'annonces') ?></th> 
				<td>
					<input type="checkbox" id="photos_activation" name="options[photos_activation]" value="1" <?php echo (annonces_options::recupinfo('annonces_photos_activation')? 'checked': '') ?> />
					<p><small><?php _e('Cette option lorsqu&#146;elle est activ&eacute;e, affiche les photos de vos annonces si celles-ci sont d&eacute;finies.', 'annonces') ?></small></p>
				</td>
			</tr>
			<tr class="v_align">
				<?php
					/**
					* Active l'affichage de la date dans les options de Wordpress (BD)
					*/
				?>
				<th><?php _e('Activer Date', 'annonces') ?></th> 
				<td>
					<input type="checkbox" id="date_activation" name="options[date_activation]" value="1" <?php echo (annonces_options::recupinfo('annonces_date_activation')? 'checked': '') ?> />
					<p><small><?php _e('Cette option lorsqu&#146;elle est activ&eacute;e, affiche la date de derni&egrave;re modification de vos annonces.', 'annonces') ?></small></p>
				</td>
			</tr>
			<tr class="v_align">
				<?php
					/**
					* Marqueur qui sera afficher dans la carte Google Maps
					*/
				?>
				<th><?php _e('Marqueur', 'annonces') ?></th> 
				<td class="preview_marker">
					<img onclick="AddSubElement_frame('btn_marker', 'options[change_marqueur]' , document.forms.annonces_options_form);this.style.display='none'" id="preview_marker" src="<?php echo WP_PLUGIN_URL.'/'.Basename_Dirname_AOS.'/medias/images/'.annonces_options::recupinfo('url_marqueur_courant'); ?>" alt="marqueur actif"/>
					<div id="btn_marker"></div>
					<br/>
					<input type="checkbox" id="marqueur_activation" name="options[marqueur_activation]" value="1" <?php echo (annonces_options::recupinfo('annonces_marqueur_activation')? 'checked': '') ?> /><label for="marqueur_activation"><?php _e('Utiliser le marqueur par d&eacute;faut.', 'annonces') ?></label>
					<p><small><?php _e('Vous pouvez d&eacute;finir un marqueur diff&eacute;rent que celui de Google Maps pour r&eacute;f&eacute;rencer vos annnonces sur la carte.', 'annonces') ?></small></p>
				</td>
			</tr>
			<tr class="v_align">
				<?php
					/**
					* Changer le thème du plugin: cette option change les boutons du listing des annonces
					*/
				?>
				<th><?php _e('Th&egrave;me', 'annonces') ?></th> 
				<td>
					<a rel="theme" rev="<?php echo WP_PLUGIN_URL.'/'.Basename_Dirname_AOS ?>/admin/theme.php" id="option_theme" title="<?php _e('Changer le th&egrave;me des boutons', 'annonces') ?>"><?php _e('Changer...','annonces') ?></a>
					<br/>
					<input type="checkbox" id="theme_activation" name="options[theme_activation]" value="1" <?php echo (annonces_options::recupinfo('theme_activation')? 'checked': '') ?> /><label for="theme_activation"><?php _e('Utiliser le th&egrave;me par d&eacute;faut.','annonces') ?></label>
					<p><small><?php _e('Cette option permet de changer l&#146;apparence des boutons du bloc recherche.', 'annonces') ?></small></p>
				</td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" id="submit_option" name="submit" value="<?php _e('Envoyer', 'annonces') ?>" />
		</p>
	</form>
</div>
<center>
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="10265740">
		<input type="image" src="https://www.paypal.com/fr_FR/FR/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !">
		<img alt="" border="0" src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
	</form>
</center>
</body>
</html>