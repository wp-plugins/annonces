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

	if (isset($_POST["razLesUrl"]))
	{
		annonces_options::majUrlAnnonces();
	}

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
					if(move_uploaded_file($_FILES['options']['tmp_name']['change_marqueur'],WP_PLUGIN_DIR.'/'.ANNONCES_PLUGIN_DIR.'/medias/images/'.$img))
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

		if(isset($_POST['options']['page']))
		{
			if (preg_match_all('(\?page_id=)', $_POST['options']['page'], $out) == 0)
			{
				$wpdb->query('UPDATE `'.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__option` SET nomoption ="'.$_POST['options']['page'].'" WHERE labeloption="annonces_page_install"');
			}
			else
			{
				$wpdb->query('UPDATE `'.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__option` SET nomoption ="annonces" WHERE labeloption="annonces_page_install"');
			}
		}

		// if (trim($_POST['options']['url_type']) != '')
			// {
				// $url = trim($_POST['options']['url_type']);
				// $url = str_replace('.', '-', $url);
				// $eav_value = new Eav();
				// $wpdb->query('UPDATE `'.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__option` SET nomoption ="' . $url . '%idpetiteannonce%" WHERE labeloption="annonces_expression_url"');
			// }

		if (trim($_POST['options']['url_type']) != '')
			{
				$url = trim($_POST['options']['url_type']);
				$url = str_replace('.', '-', $url);
				$eav_value = new Eav();
				$wpdb->query('UPDATE `'.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__option` SET nomoption ="' . $url . "%idpetiteannonce%" . '" WHERE labeloption="annonces_expression_url"');
			}

		if(isset($_POST['options']['suffix']) != '')
		{
			$suffixx = trim($_POST['options']['suffix']);
			$eav_value = new Eav();
			$wpdb->query('UPDATE `'.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__option` SET nomoption ="'.$suffixx.'" WHERE labeloption="annonces_suffix"');
		}
		if(isset($_POST['options']['email_reception']))
		{
			$wpdb->query('UPDATE `'.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__option` SET nomoption ="'.$_POST['options']['email_reception'].'" WHERE labeloption="annonces_email_reception"');
		}
		if(isset($_POST['options']['sujet_reception']))
		{
			$wpdb->query('UPDATE `'.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__option` SET nomoption ="'.$_POST['options']['sujet_reception'].'" WHERE labeloption="annonces_sujet_reception"');
		}
		if(isset($_POST['options']['txt_reception']))
		{
			$wpdb->query('UPDATE `'.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__option` SET nomoption ="'.$_POST['options']['txt_reception'].'" WHERE labeloption="annonces_txt_reception"');
		}
		if(isset($_POST['options']['html_reception']))
		{
			$wpdb->query('UPDATE `'.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__option` SET nomoption ="'.$_POST['options']['html_reception'].'" WHERE labeloption="annonces_html_reception"');
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

		if(isset($_POST['options']['url_activation']))
		{
			if($_POST['options']['url_activation'] == 1)
			{
				$wpdb->query('UPDATE `'.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__option` SET nomoption ="'.$_POST['options']['url_activation'].'" WHERE labeloption="annonces_url_activation"');
			}
		}
		else
		{
			$wpdb->query('UPDATE `'.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__option` SET nomoption =0 WHERE labeloption="annonces_url_activation"');
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

		if(isset($_POST['options']['email_activation']))
		{
			if($_POST['options']['email_activation'] == 1)
			{
				$wpdb->query('UPDATE `'.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__option` SET nomoption ="'.$_POST['options']['email_activation'].'" WHERE labeloption="annonces_email_activation"');
			}
		}
		else
		{
			$wpdb->query('UPDATE `'.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__option` SET nomoption =0 WHERE labeloption="annonces_email_activation"');
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
		window.onload = init;
		var id = 0;
		function init()
		{
			if (document.getElementById('url_activation') && document.getElementById('url_activation').checked == true)
			{
				annoncejquery('#url_part1').show();
				annoncejquery('#url_part2').show();
				annoncejquery('#url_part3').show();
			}
			else
			{
				annoncejquery('#url_part1').hide();
				annoncejquery('#url_part2').hide();
				annoncejquery('#url_part3').hide();
			}
			reglages_url();

			if (document.getElementById('email_activation') && document.getElementById('email_activation').checked == true)
			{
				annoncejquery('#email_act').show();
				annoncejquery('#sujet_act').show();
				annoncejquery('#txt_act').show();
				annoncejquery('#html_act').show();
				annoncejquery('#email_part1').show();
			}
			else
			{
				annoncejquery('#email_act').hide();
				annoncejquery('#sujet_act').hide();
				annoncejquery('#txt_act').hide();
				annoncejquery('#html_act').hide();
				annoncejquery('#email_part1').hide();
			}
			reglages_email();
		}

		function reglages_url()
		{
			if (document.getElementById('url_activation') && document.getElementById('url_activation').checked == true)
			{
				annoncejquery('#url_part1').show();
				annoncejquery('#url_part2').show();
				annoncejquery('#url_part3').show();
			}
			else
			{
				annoncejquery('#url_part1').hide();
				annoncejquery('#url_part2').hide();
				annoncejquery('#url_part3').hide();
			}
		}

		function reglages_email()
		{
			if (document.getElementById('email_activation') && document.getElementById('email_activation').checked == true)
			{
				annoncejquery('#email_act').show();
				annoncejquery('#sujet_act').show();
				annoncejquery('#txt_act').show();
				annoncejquery('#html_act').show();
				annoncejquery('#email_part1').show();
			}
			else
			{
				annoncejquery('#email_act').hide();
				annoncejquery('#sujet_act').hide();
				annoncejquery('#txt_act').hide();
				annoncejquery('#html_act').hide();
				annoncejquery('#email_part1').hide();
			}
		}

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

		function urlID(id)
		{
			document.getElementById('url_type').value += id;
			document.getElementById('url_type').focus();
		}
	</script>
	<h2><?php _e('Options g&eacute;n&eacute;rales du Plugin d\'Annonces', 'annonces') ?></h2>
	Mon tetstststs
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
					<p><small><?php _e('Cette option lorsqu\'elle est activ&eacute;e, utilise Google Maps pour g&eacute;olocaliser vos annonces sur une carte.', 'annonces') ?></small></p>
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
					<p><small><?php _e('Cette option lorsqu\'elle est activ&eacute;e, affiche les photos de vos annonces si celles-ci sont d&eacute;finies.', 'annonces') ?></small></p>
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
					<p><small><?php _e('Cette option lorsqu\'elle est activ&eacute;e, affiche la date de derni&egrave;re modification de vos annonces.', 'annonces') ?></small></p>
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
					<img onclick="AddSubElement_frame('btn_marker', 'options[change_marqueur]' , document.forms.annonces_options_form);this.style.display='none'" id="preview_marker" src="<?php echo WP_PLUGIN_URL.'/'.ANNONCES_PLUGIN_DIR.'/medias/images/'.annonces_options::recupinfo('url_marqueur_courant'); ?>" alt="marqueur actif"/>
					<div id="btn_marker"></div>
					<br/>
					<input type="checkbox" id="marqueur_activation" name="options[marqueur_activation]" value="1" <?php echo (annonces_options::recupinfo('annonces_marqueur_activation')? 'checked': '') ?> /><label for="marqueur_activation"><?php _e('Utiliser le marqueur par d&eacute;faut.', 'annonces') ?></label>
					<p><small><?php _e('Vous pouvez d&eacute;finir un marqueur diff&eacute;rent que celui de Google Maps pour r&eacute;f&eacute;rencer vos annnonces sur la carte.', 'annonces') ?></small></p>
				</td>
			</tr>
			<tr class="v_align">
				<?php
					/**
					* Changer le th�me du plugin: cette option change les boutons du listing des annonces
					*/
				?>
				<th><?php echo __('Th&egrave;me', 'annonces') ?></th>
				<td>
					<a rel="theme" rev="<?php echo WP_PLUGIN_URL.'/'.ANNONCES_PLUGIN_DIR ?>/admin/theme.php" id="option_theme" title="<?php _e('Changer le th&egrave;me des boutons', 'annonces') ?>"><?php _e('Changer...','annonces') ?></a>
					<br/>
					<input type="checkbox" id="theme_activation" name="options[theme_activation]" value="1" <?php echo (annonces_options::recupinfo('theme_activation')? 'checked': '') ?> /><label for="theme_activation"><?php _e('Utiliser le th&egrave;me par d&eacute;faut.','annonces') ?></label>
					<p><small><?php _e('Cette option permet de changer l\'apparence des boutons du bloc recherche.', 'annonces') ?></small></p>
				</td>
			</tr>
			<tr class="v_align">
				<td class="entete_variable" colspan="2">
					<hr/>
					<h3><?php echo __('R&eacute;&eacute;criture des URLs','annonces') ?></h3>
				</td>
			</tr>
			<tr class="v_align">
				<th><?php _e('Activer la r&eacute;&eacute;criture', 'annonces') ?></th>
				<td>
					<input type="checkbox" id="url_activation" onclick="reglages_url()" name="options[url_activation]" value="1" <?php echo (annonces_options::recupinfo('annonces_url_activation')? 'checked': '') ?> />
					<p><small><?php echo __('Cette option, lorsqu\'elle est activ&eacute;e, permet de personnaliser les URLs pour visionner les annonces. N\'oublier pas d\'activer des permaliens autre que "Valeur par d&eacute;faut" si vous souhaitez que vos liens soit r&eacute;&eacute;crits. De plus, veuillez enlever le dernier slash de votre structure personnalis&eacute;e dans les permaliens pour ne pas qu\'il se r&eacute;percute sur vos URLs.', 'annonces') ?></small></p>
				</td>
			</tr>
			<tr class="v_align" id="url_part1">
				<td class="entete_variable" colspan="2">
					<p class="p-obligatoire"><?php echo __('La pr&eacute;sence de %idpetiteannonce% est obligatoire sous peine d\'avoir des conflits d\'URLs !','annonces') ?></p>
					<h4><?php echo __('Param&egrave;tres de r&eacute;&eacute;criture d\'url', 'annonces') ?></h4>
					<table id="table_motcles">
						<tr id="<?php echo __('%idpetiteannonce%','annonces') ?>" onclick="urlID(this.id)">
							<td><?php echo __('L\'id de l\'annonce :','annonces') ?></td>
							<td><?php echo __('%idpetiteannonce%','annonces') ?></td>
						</tr>
						<tr id="<?php echo __('%titre_annonce%','annonces') ?>" onclick="urlID(this.id)">
							<td><?php echo __('Titre de l\'annonce :','annonces') ?></td>
							<td><?php echo __('%titre_annonce%','annonces') ?></td></tr>
						<tr id="<?php echo __('%referenceagencedubien%','annonces') ?>" onclick="urlID(this.id)">
							<td><?php echo __('R&eacute;f&eacute;rence agence du bien:','annonces') ?></td>
							<td><?php echo __('%referenceagencedubien%','annonces') ?></td></tr>
						<tr id="<?php echo __('%nomgroupeattribut%','annonces') ?>" onclick="urlID(this.id)">
							<td><?php echo __('Nom du groupe d\'attribut :','annonces') ?></td>
							<td><?php echo __('%nomgroupeattribut%','annonces') ?></td></tr>
						<tr id="<?php echo __('%descriptiongroupeattribut%','annonces') ?>" onclick="urlID(this.id)">
							<td><?php echo __('Description du groupe d\'attribut :','annonces') ?></td>
							<td><?php echo __('%descriptiongroupeattribut%','annonces') ?></td></tr>
						<tr id="<?php echo __('%ville%','annonces') ?>" onclick="urlID(this.id)">
							<td><?php echo __('Ville :','annonces') ?></td>
							<td><?php echo __('%ville%','annonces') ?></td></tr>
						<tr id="<?php echo __('%departement%','annonces') ?>" onclick="urlID(this.id)">
							<td><?php echo __('D&eacute;partement :','annonces') ?></td>
							<td><?php echo __('%departement%','annonces') ?></td></tr>
						<tr id="<?php echo __('%region%','annonces') ?>" onclick="urlID(this.id)">
							<td><?php echo __('R&eacute;gion :','annonces') ?></td>
							<td><?php echo __('%region%','annonces') ?></td></tr>
						<tr id="<?php echo __('%cp%','annonces') ?>" onclick="urlID(this.id)">
							<td><?php echo __('Code Postal :','annonces') ?></td>
							<td><?php echo __('%cp%','annonces') ?></td></tr>
						<tr id="<?php echo __('%pays%','annonces') ?>" onclick="urlID(this.id)">
							<td><?php echo __('Pays :','annonces') ?></td>
							<td><?php echo __('%pays%','annonces') ?></td></tr>
						<tr id="<?php echo __('%date_publication%','annonces') ?>" onclick="urlID(this.id)">
							<td><?php echo __('Date de publication :','annonces') ?></td>
							<td><?php echo __('%date_publication%','annonces') ?></td></tr>
						<tr id="<?php echo __('%type_bien%','annonces') ?>" onclick="urlID(this.id)">
							<td><?php echo __('Type de bien :','annonces') ?></td>
							<td><?php echo __('%type_bien%','annonces') ?></td></tr>
					</table>
				</td>
			</tr>
			<tr class="v_align" id="url_part2">
				<td colspan="2" class="sous_titre_perso">
				<h3><?php echo __('Personnalisation des liens','annonces') ?></h3></td>
			</tr>
			<tr class="v_align" id="url_part3">
				<th><?php echo __('Url Type<br/>', 'annonces') ?></th>
				<td>
					<input type="text" size="50" id="url_type" name="options[url_type]" value="<?php echo substr(annonces_options::recupinfo('annonces_expression_url'), 0, -17); ?>" />
					<label>%idpetiteannonce%</label>
					<input type="text" size="10" id="suffix" name="options[suffix]" value="<?php echo annonces_options::recupinfo('annonces_suffix'); ?>" />
					<p><small><?php echo __('Cette Url Type sera le mod&egrave;le des URLs de chaque annonce dans la liste des annonces visibles par les visiteurs. Dans le premier champ, uniquement le nom de la page dans lequel les annonces sont visibles est requis. Le deuxi&egrave;me champ, lui, sert &agrave; d&eacute;finir les URLs de chaque annonce.', 'annonces') ?></small></p>
				</td>
			</tr>
		</table>
		<br/><br/>
		<p class="submit">
			<input type="submit" id="submit_option" name="submit" value="<?php _e('Enregistrer les r&eacute;glages', 'annonces') ?>" />
		</p>
	</form>
	<form method="post" name="raz_url">
		<table class="form-table">
				<tr>
					<th><?php echo __('R&eacute;initalisation des URLs', 'annonces') ?></th>
					<td>
						<input type="hidden" name="razLesUrl" id="razLesUrl" value="raz">
						<input  name="razurl" type="button" value="<?php echo __('R&eacute;initaliser les URLs', 'annonces') ?>" onclick="var check = confirm('&Ecirc;tes vous s&ucirc;r de vouloir remettre par d&eacute;faut selon l\'url type toutes les URLs y compris celles personnalis&eacute;es ?'); if (check ==true) document.forms.raz_url.submit();"/>
					</td>
				</tr>
		</table>
	</form>
			<!--
			<tr class="v_align">
				<td class="entete_variable" colspan="2">
					<hr/>
					<h3><?php echo __('Formulaire de contact personnalisable','annonces') ?></h3>
				</td>
			</tr>
			<tr class="v_align">
				<?php
					/**
					*	Activation ou non du formulaire de contact par email
					*/
				?>
				<th><?php _e('Activer Contact', 'annonces') ?></th>
				<td>
					<input type="checkbox" id="email_activation" name="options[email_activation]" onclick="reglages_email()" value="1" <?php echo (annonces_options::recupinfo('annonces_email_activation')? 'checked': '') ?> />
					<p><small><?php echo __('Cette option lorsqu\'elle est activ&eacute;e, affiche un lien "Contacter le vendeur par email" sur chaque annonce et affiche un formulaire de contact comprenant Nom, T&eacute;l&eacute;phone, Email et Message sur la page de l\'annonce s&eacute;lectionn&eacute;e. V&eacute;rifiez auparavant que votre h&eacute;bergement permet l\'utilisation de la fonction "mail()" obligatoire pour le formulaire de contact, sans cela, n\'activez pas cette fonctionnalit&eacute;.', 'annonces') ?></small></p>
				</td>
			</tr>
			<tr class="v_align" id="email_part1">
				<td class="entete_variable" colspan="2">
					<?php echo __('Si vous souhaitez r&eacute;cup&eacute;rer les champs des demandes d\'informations dans le sujet du mail ou le nom en ent&ecirc;te du mail ou encore, dans le corps des messages (TXT ou HTML), il vous suffit d\'indiquer les champs comme tels dans les r&eacute;glages ci-dessous :','annonces') ?>
					<br/><br/>
					<?php echo __('Nom : %nom%','annonces') ?><br/>
					<?php echo __('T&eacute;l&eacute;phone : %tel%','annonces') ?><br/>
					<?php echo __('Email : %mail%','annonces') ?><br/>
					<?php echo __('Message : %message%','annonces') ?><br/>
					<?php echo __('Id de l\'annonce : %id_annonce%','annonces') ?><br/>
					<?php echo __('Titre de l\'annonce : %titre%','annonces') ?><br/>
					<?php echo __('URL de l\'annonce : %url_annonce%','annonces') ?><br/><br/>
					<?php echo __('Par exemple :','annonces') ?><br/>
					<?php echo __('%nom% : %tel% donnera par exemple Dupont : 0606060606 si le nom et le t&eacute;l&eacute;phone dans le formulaire sont respectivement Dupont et 0606060606.','annonces') ?><br/><br/><br/>
				</td>
			</tr>
			<tr class="v_align" id="email_act">
				<?php
					/**
					*	Adresse email charg�e de recevoir les demandes d'informations
					*/
				?>
				<th><?php echo __('Email', 'annonces') ?></th>
				<td>
					<input size="40" type="text" id="email_reception" name="options[email_reception]" value="<?php echo annonces_options::recupinfo('annonces_email_reception'); ?>" />
					<p><small><?php echo __('Adresse email charg&eacute;e de recevoir les demandes d\'informations.', 'annonces') ?></small></p>
				</td>
			</tr>
			<tr class="v_align" id="sujet_act">
				<?php
					/**
					*	Sujet du mail
					*/
				?>
				<th><?php echo __('Sujet du mail', 'annonces') ?></th>
				<td>
					<input type="text" size="76" id="sujet_reception" name="options[sujet_reception]" value="<?php echo str_replace('"','\'', annonces_options::recupinfo('annonces_sujet_reception')); ?>" />
					<p><small><?php echo __('Sujet du mail visible lors de la r&eacute;ception des demandes d\'informations.', 'annonces') ?></small></p>
				</td>
			</tr>
			<tr class="v_align" id="txt_act">
				<?php
					/**
					*	Mod�le de mail TXT qui sera envoy� pour la r�ception des demandes d'informations
					*/
				?>
				<th><?php echo __('Mod&egrave;le mail TXT', 'annonces') ?></th>
				<td>
					<textarea cols="63" rows="10" id="txt_reception" name="options[txt_reception]"><?php echo str_replace('"','\'', annonces_options::recupinfo('annonces_txt_reception')); ?></textarea>
					<p><small><?php echo __('C\'est la version texte (TXT) du mod&egrave;le du formulaire qui sera envoy&eacute; &agrave; l\'adresse mail de r&eacute;ception des demandes d\'informations si votre client mail ne lit pas les formes HTML.', 'annonces') ?></small></p>
				</td>
			</tr>
			<tr class="v_align" id="html_act">
				<?php
					/**
					*	Mod�le de mail HTML qui sera envoy� pour la r�ception des demandes d'informations
					*/
				?>
				<th><?php echo __('Mod&egrave;le mail HTML', 'annonces') ?></th>
				<td>
					<textarea cols="63" rows="10" id="html_reception" name="options[html_reception]"><?php echo str_replace('"','\'', annonces_options::recupinfo('annonces_html_reception')); ?></textarea>
					<p><small><?php echo __('C\'est la version HTML du mod&egrave;le du formulaire qui sera envoy&eacute; &agrave; l\'adresse mail de r&eacute;ception des demandes d\'informations. Vous pouvez y ajouter images ou figures de style.', 'annonces') ?></small></p>
				</td>
			</tr>
		</table>
		<br/><br/>
		<p class="submit">
			<input type="submit" id="submit_option" name="submit" value="<?php _e('Enregistrer les r&eacute;glages', 'annonces') ?>" />
		</p>
	</form>-->
</div>
<center>
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_s-xclick" />
		<input type="hidden" name="hosted_button_id" value="10265740" />
		<input type="image" src="https://www.paypal.com/fr_FR/FR/i/btn/btn_donateCC_LG.gif" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus s�curis�e !" />
		<img alt="" border="0" src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" width="1" height="1" />
	</form>
</center>