<?php
/***************************************************
*Date: 01/10/2009      file:frontend.class.php     *
*Author: Eoxia                                     *
*Comment:                                          *
***************************************************/

class annonces_options
{
	/**
	*	Declare the different options for the plugin
	*/
	function add_options()
	{
		register_setting('annonces_options', 'annonces_options', array('annonces_options', 'options_validator'));
		// register_setting('annonces_email_options', 'annonces_email_options', array('annonces_options', 'options_validator'));
		// register_setting('annonces_db_option', 'annonces_db_option');

		{	/*	Declare the different option for geolocalisation	*/
			add_settings_section('annonces_geolocalisation_options', __('Options pour la g&eacute;olocalisation', 'annonces'), array('annonces_options', 'options_output'), 'annonces_options_settings');
			/*	Add the different field for geolocalisation	*/
			add_settings_field('annonce_activate_map', __('Activer la g&eacute;olicalisation', 'annonces'), array('annonces_options', 'annonce_activate_map'), 'annonces_options_settings', 'annonces_geolocalisation_options');
		//	add_settings_field('annonce_gmap_api_key', __('Cl&eacute; api pour Google maps', 'annonces'), array('annonces_options', 'annonce_gmap_api_key'), 'annonces_options_settings', 'annonces_geolocalisation_options');
			add_settings_field('annonce_map_marker_size', __('Taille du marqueur sur la carte', 'annonces'), array('annonces_options', 'annonce_map_marker_size'), 'annonces_options_settings', 'annonces_geolocalisation_options');
			add_settings_field('annonce_map_marker', __('Marqueur a afficher sur la carte', 'annonces'), array('annonces_options', 'annonce_map_marker'), 'annonces_options_settings', 'annonces_geolocalisation_options');
		}

		{	/*	Declare the different option for output	*/
			add_settings_section('annonces_display_options_settings', __('Options pour l\'affichage', 'annonces'), array('annonces_options', 'options_output'), 'annonces_options_settings');
			/*	Add the different field for display	*/
			add_settings_field('annonce_show_picture', __('Afficher les photos', 'annonces'), array('annonces_options', 'annonce_show_picture'), 'annonces_options_settings', 'annonces_display_options_settings');
			add_settings_field('annonce_show_date', __('Afficher la date', 'annonces'), array('annonces_options', 'annonce_show_date'), 'annonces_options_settings', 'annonces_display_options_settings');
			add_settings_field('annonce_frontend_listing_order', __('Trier la liste des annonces par', 'annonces'), array('annonces_options', 'annonce_frontend_listing_order'), 'annonces_options_settings', 'annonces_display_options_settings');
			add_settings_field('annonce_frontend_listing_order_side', __('Sens du triage des annonces', 'annonces'), array('annonces_options', 'annonce_frontend_listing_order_side'), 'annonces_options_settings', 'annonces_display_options_settings');
			add_settings_field('annonce_theme_search_form', __('Bouttons pour la recherche', 'annonces'), array('annonces_options', 'annonce_theme_search_form'), 'annonces_options_settings', 'annonces_display_options_settings');
		}

		{	/*	Declare the general options	*/
			add_settings_section('annonces_general_options_settings', __('Options g&eacute;n&eacute;rales', 'annonces'), array('annonces_options', 'options_output'), 'annonces_options_settings');
			/*	Add the different field for display	*/
			add_settings_field('annonce_currency', __('Devise &agrave; utiliser dans le plugin', 'annonces'), array('annonces_options', 'annonce_currency'), 'annonces_options_settings', 'annonces_general_options_settings');
			add_settings_field('annonce_export_picture', __('Type d\'export pour les photos', 'annonces'), array('annonces_options', 'annonce_export_picture'), 'annonces_options_settings', 'annonces_general_options_settings');
		}

		{	/*	Declare options for url rewriting	*/
			add_settings_section('annonces_rewrite_options_settings', __('Options pour la r&eacute;&eacute;criture d\'url', 'annonces'), array('annonces_options', 'options_output'), 'annonces_options_settings');
			/*	Add the different field for display	*/
			add_settings_field('annonce_activate_url_rewrite', __('Activer la r&eacute;&eacute;criture d\'url', 'annonces'), array('annonces_options', 'annonce_activate_url_rewrite'), 'annonces_options_settings', 'annonces_rewrite_options_settings');
			add_settings_field('annonce_url_rewrite_template', __('Format de l\'url', 'annonces'), array('annonces_options', 'annonce_url_rewrite_template'), 'annonces_options_settings', 'annonces_rewrite_options_settings');
		}

		{	/*	Declare options for contacting adds writer	*/
			add_settings_section('annonces_emailcontact_options_settings', __('Options pour contacter les annonceurs', 'annonces'), array('annonces_options', 'options_output'), 'annonces_options_settings_contact');
			/*	Add the different field for display	*/
			add_settings_field('annonces_email_reception', __('Adresse e-mail de r&eacute;ception', 'annonces'), array('annonces_options', 'annonces_email_reception'), 'annonces_options_settings', 'annonces_options_settings_contact');
			add_settings_field('annonces_sujet_reception', __('Sujet de l\'e-mail envoy&eacute;', 'annonces'), array('annonces_options', 'annonces_sujet_reception'), 'annonces_options_settings', 'annonces_options_settings_contact');
			add_settings_field('annonces_txt_reception', __('Contenu de l\'e-mail envoy&eacute; au format texte', 'annonces'), array('annonces_options', 'annonces_txt_reception'), 'annonces_options_settings', 'annonces_options_settings_contact');
			add_settings_field('annonces_html_reception', __('Contenu de l\'e-mail envoy&eacute; au format html', 'annonces'), array('annonces_options', 'annonces_html_reception'), 'annonces_options_settings', 'annonces_options_settings_contact');
			add_settings_field('annonces_email_activation', __('Contenu de l\'e-mail envoy&eacute; au format html', 'annonces'), array('annonces_options', 'annonces_email_activation'), 'annonces_options_settings', 'annonces_options_settings_contact');
		}
	}
	/**
	*	Validate the different data sent for the option
	*
	*	@param array $input An array which will receive the values sent by the user with the form
	*
	*	@return array $newinput An array with the send values cleaned for more secure usage
	*/
	function options_validator($input)
	{
		$newinput['gmap_api_key'] = trim($input['gmap_api_key']);
		$newinput['annonce_activate_map'] = trim($input['annonce_activate_map']);
		$newinput['annonce_map_marker'] = trim($input['annonce_map_marker']);
		$newinput['annonce_map_marker_size'] = trim($input['annonce_map_marker_size']);

		$newinput['annonce_show_picture'] = trim($input['annonce_show_picture']);
		$newinput['annonce_show_date'] = trim($input['annonce_show_date']);
		$newinput['annonce_frontend_listing_order'] = trim($input['annonce_frontend_listing_order']);
		$newinput['annonce_frontend_listing_order_side'] = trim($input['annonce_frontend_listing_order_side']);

		$newinput['annonce_currency'] = trim($input['annonce_currency']);
		$newinput['annonce_export_picture'] = trim($input['annonce_export_picture']);

		$newinput['url_radio_maisons'] = trim($input['url_radio_maisons']);
		$newinput['url_radio_terrains'] = trim($input['url_radio_terrains']);
		$newinput['url_radio_toutes'] = trim($input['url_radio_toutes']);
		$newinput['url_budget'] = trim($input['url_budget']);
		$newinput['url_superficie'] = trim($input['url_superficie']);
		$newinput['url_recherche'] = trim($input['url_recherche']);

		$newinput['annonce_activate_url_rewrite'] = trim($input['annonce_activate_url_rewrite']);
		$urlRewritingFormat = trim($input['annonce_url_rewrite_template']);
		if(!preg_match('%idpetiteannonce%', $urlRewritingFormat))
		{
			$urlRewritingFormat .= '%idpetiteannonce%';
		}
		$newinput['annonce_url_rewrite_template'] = $urlRewritingFormat;
		$newinput['annonce_url_rewrite_template_suffix'] = trim($input['annonce_url_rewrite_template_suffix']);

		// $newinput['annonces_email_activation'] = trim($input['annonces_email_activation']);
		// $newinput['annonces_html_reception'] = trim($input['annonces_html_reception']);
		// $newinput['annonces_txt_reception'] = trim($input['annonces_txt_reception']);
		// $newinput['annonces_sujet_reception'] = trim($input['annonces_sujet_reception']);
		// $newinput['annonces_email_reception'] = trim($input['annonces_email_reception']);

		return $newinput;
	}

	/**
	*	Function allowing to set a explication area for the settings section
	*/
	function options_output()
	{

	}
	/**
	*	Define the output for the field. Get the option value to put the good value by default
	*/
	function annonce_gmap_api_key()
	{
		$options = get_option('annonces_options');
		echo '<input type="text" id="gmap_api_key" name="annonces_options[gmap_api_key]" value="' . $options['gmap_api_key'] . '" />
		<p class="optionFieldHelper" >' . __('Cette cl&eacute; autorise Google Maps &agrave; afficher une carte pour g&eacute;olocaliser vos annonces.', 'annonces') . '</p>';
	}
	function annonce_activate_map()
	{
		global $optionYesNoList;
		$options = get_option('annonces_options');
		echo annonces_display::createComboBox('annonce_activate_map', 'annonces_options[annonce_activate_map]', $optionYesNoList, $options['annonce_activate_map']) . '
		<p class="optionFieldHelper" >' . __('Cette option lorsqu\'elle est activ&eacute;e, utilise Google Maps pour g&eacute;olocaliser vos annonces sur une carte.', 'annonces') . '</p>';
	}
	function annonce_map_marker_size()
	{
		$options = get_option('annonces_options');
		echo '<input type="text" name="annonces_options[annonce_map_marker_size]" id="annonce_map_marker_size" value="' . $options['annonce_map_marker_size']. '" />
		<p class="optionFieldHelper" >' . __('Cette option permet de d&eacute;finir la taille de l\'ic&ocirc;ne qui sera affich&eacute;e sur la carte dans la page d\'une annonce.', 'annonces') . '</p>';
	}
	function annonce_map_marker()
	{
		$options = get_option('annonces_options');
		echo '
<div class="alignleft annonceSearchPicto" id="change_annonce_map_marker" >
	<img class="searchPictoManager" style="max-height:43px;" id="preview_annonce_map_marker" src="' . WP_CONTENT_URL . WAY_TO_PICTURES_AOS . $options['annonce_map_marker'] . '" alt="' . __('Marqueur actuel', 'annonces') . '" />
	<input type="hidden" class="pictoField" value="' . $options['annonce_map_marker'] . '" name="annonces_options[annonce_map_marker]" id="annonce_map_marker" />
	<div class="searchPictoManager" >' . __('Changer', 'annonces') . '</div>
	<p class="optionFieldHelper" >' . __('Vous pouvez d&eacute;finir un marqueur diff&eacute;rent que celui de Google Maps pour r&eacute;f&eacute;rencer vos annnonces sur la carte.', 'annonces') . '</p>
</div>';
	}

	function annonce_show_picture()
	{
		global $optionYesNoList;
		$options = get_option('annonces_options');
		echo annonces_display::createComboBox('annonce_show_picture', 'annonces_options[annonce_show_picture]', $optionYesNoList, $options['annonce_show_picture']) . '
		<p class="optionFieldHelper" >' . __('Cette option lorsqu\'elle est activ&eacute;e, affiche les photos de vos annonces si celles-ci sont d&eacute;finies.', 'annonces') . '</p>';
	}
	function annonce_show_date()
	{
		global $optionYesNoList;
		$options = get_option('annonces_options');
		echo annonces_display::createComboBox('annonce_show_date', 'annonces_options[annonce_show_date]', $optionYesNoList, $options['annonce_show_date']) . '
		<p class="optionFieldHelper" >' . __('Cette option lorsqu\'elle est activ&eacute;e, affiche la date de derni&egrave;re modification de vos annonces.', 'annonces') . '</p>';
	}
	function annonce_frontend_listing_order()
	{
		global $optionOrderList;
		$options = get_option('annonces_options');
		echo annonces_display::createComboBox('annonce_frontend_listing_order', 'annonces_options[annonce_frontend_listing_order]', $optionOrderList, $options['annonce_frontend_listing_order']);
	}
	function annonce_frontend_listing_order_side()
	{
		global $optionOrderSideList;
		$options = get_option('annonces_options');
		echo annonces_display::createComboBox('annonce_frontend_listing_order_side', 'annonces_options[annonce_frontend_listing_order_side]', $optionOrderSideList, (!empty($options) && !empty($options['annonce_frontend_listing_order_side']) ? $options['annonce_frontend_listing_order_side'] : ''));
	}

	function annonce_currency()
	{
		global $optionCurrencyList;
		$options = get_option('annonces_options');
		echo annonces_display::createComboBox('annonce_currency', 'annonces_options[annonce_currency]', $optionCurrencyList, htmlentities( $options['annonce_currency'], ENT_NOQUOTES, 'UTF-8' )) . '
		<p class="optionFieldHelper" >' . __('Cette option permet de configurer la devise qui sera effective sur le site.', 'annonces') . '</p>';
	}
	function annonce_export_picture()
	{
		global $optionPictureExportTypeList;
		$options = get_option('annonces_options');
		echo annonces_display::createComboBox('annonce_export_picture', 'annonces_options[annonce_export_picture]', $optionPictureExportTypeList, (!empty($options) && !empty($options['annonce_export_picture']) ? $options['annonce_export_picture'] : '')) . '
		<p class="optionFieldHelper" >' . __('Cette option permet de d&eacute;finir comment les photos des annonces seront export&eacute;es. Soit les fichiers sont envoy&eacute;s, soit l\'export contiendra uniquement les liens vers les photos', 'annonces') . '</p>';
	}

	function annonce_activate_url_rewrite()
	{
		global $optionYesNoList;
		$options = get_option('annonces_options');
		echo annonces_display::createComboBox('annonce_activate_url_rewrite', 'annonces_options[annonce_activate_url_rewrite]', $optionYesNoList, $options['annonce_activate_url_rewrite']) . '
		<p class="optionFieldHelper" >' . __('Cette option, lorsqu\'elle est activ&eacute;e, permet de personnaliser les URLs pour visionner les annonces. N\'oublier pas d\'activer des permaliens autre que "Valeur par d&eacute;faut" si vous souhaitez que vos liens soit r&eacute;&eacute;crits. De plus, veuillez enlever le dernier slash de votre structure personnalis&eacute;e dans les permaliens pour ne pas qu\'il se r&eacute;percute sur vos URLs.', 'annonces') . '</p>';
	}
	function annonce_url_rewrite_template()
	{
		$options = get_option('annonces_options');
		$urlRewriteFormat = $options['annonce_url_rewrite_template'];
		$urlIDPAContainerClass = '';
		if(preg_match('%idpetiteannonce%', $urlRewriteFormat))
		{
			$urlIDPAContainerClass = ' class="annonce_hide" ';
		}
		echo '<input type="text" readonly="readonly" id="annonce_url_rewrite_template" name="annonces_options[annonce_url_rewrite_template]" value="' . $urlRewriteFormat . '" /><span id="IDPAContainer" ' . $urlIDPAContainerClass . ' >%idpetiteannonce%</span>' . annonces_display::createComboBox('annonce_url_rewrite_template_suffix', 'annonces_options[annonce_url_rewrite_template_suffix]', array('' => '&nbsp;', '.html' => __('.html', 'annonces')), $options['annonce_url_rewrite_template_suffix']) . '
		<div id="urlFormatterContainer" class="annonce_hide" title="' . __('Configuration pour les url', 'annonces') . '" ><div id="urlFormatter" ><img src="' . ANNONCES_IMG_PLUGIN_URL . 'loading.gif" alt="loading" /></div></div>
		<div id="urlConfigurator" >' . __('Configurer le format', 'annonces') . '</div>
		<p class="optionFieldHelper" >' . __('Cette Url Type sera le mod&egrave;le des URLs de chaque annonce dans la liste des annonces visibles par les visiteurs. Dans le premier champ, uniquement le nom de la page dans lequel les annonces sont visibles est requis. Le deuxi&egrave;me champ, lui, sert &agrave; d&eacute;finir les URLs de chaque annonce.', 'annonces') . '</p>';
	}

	function annonce_theme_search_form()
	{
		$options = get_option('annonces_options');
		echo '
			<div class="alignleft annonceSearchPicto" id="change_url_radio_maisons" >
				<img class="searchPictoManager" style="max-height:43px;" id="preview_url_radio_maisons" src="' . WP_CONTENT_URL . WAY_TO_PICTURES_AOS . $options['url_radio_maisons'] . '" alt="' . __('Marqueur actuel', 'annonces') . '" />
				<input type="hidden" class="pictoField" name="annonces_options[url_radio_maisons]" id="url_radio_maisons" value="' . $options['url_radio_maisons']. '" />
				<div class="searchPictoManager" >' . __('Changer', 'annonces') . '</div>
			</div>
			<div class="alignleft annonceSearchPicto" id="change_url_radio_terrains" >
				<img class="searchPictoManager" style="max-height:43px;" id="preview_url_radio_terrains" src="' . WP_CONTENT_URL . WAY_TO_PICTURES_AOS . $options['url_radio_terrains'] . '" alt="' . __('Marqueur actuel', 'annonces') . '" />
				<input type="hidden" class="pictoField" name="annonces_options[url_radio_terrains]" id="url_radio_terrains" value="' . $options['url_radio_terrains']. '" />
				<div class="searchPictoManager" >' . __('Changer', 'annonces') . '</div>
			</div>
			<div class="alignleft annonceSearchPicto" id="change_url_radio_toutes" >
				<img class="searchPictoManager" style="max-height:43px;" id="preview_url_radio_toutes" src="' . WP_CONTENT_URL . WAY_TO_PICTURES_AOS . $options['url_radio_toutes'] . '" alt="' . __('Marqueur actuel', 'annonces') . '" />
				<input type="hidden" class="pictoField" name="annonces_options[url_radio_toutes]" id="url_radio_toutes" value="' . $options['url_radio_toutes']. '" />
				<div class="searchPictoManager" >' . __('Changer', 'annonces') . '</div>
			</div>
			<div class="clear" >&nbsp;</div>
			<div class="alignleft annonceSearchPicto" id="change_url_budget" >
				<img class="searchPictoManager" style="max-height:43px;" id="preview_url_budget" src="' . WP_CONTENT_URL . WAY_TO_PICTURES_AOS . $options['url_budget'] . '" alt="' . __('Marqueur actuel', 'annonces') . '" />
				<input type="hidden" class="pictoField" name="annonces_options[url_budget]" id="url_budget" value="' . $options['url_budget']. '" />
				<div class="searchPictoManager" >' . __('Changer', 'annonces') . '</div>
			</div>
			<div class="alignleft annonceSearchPicto" id="change_url_superficie" >
				<img class="searchPictoManager" style="max-height:43px;" id="preview_url_superficie" src="' . WP_CONTENT_URL . WAY_TO_PICTURES_AOS . $options['url_superficie'] . '" alt="' . __('Marqueur actuel', 'annonces') . '" />
				<input type="hidden" class="pictoField" name="annonces_options[url_superficie]" id="url_superficie" value="' . $options['url_superficie']. '" />
				<div class="searchPictoManager" >' . __('Changer', 'annonces') . '</div>
			</div>
			<div class="clear" >&nbsp;</div>
			<div class="alignleft annonceSearchPicto" id="change_url_recherche" >
				<img class="searchPictoManager" style="max-height:43px;" id="preview_url_recherche" src="' . WP_CONTENT_URL . WAY_TO_PICTURES_AOS . $options['url_recherche'] . '" alt="' . __('Marqueur actuel', 'annonces') . '" />
				<input type="hidden" class="pictoField" name="annonces_options[url_recherche]" id="url_recherche" value="' . $options['url_recherche']. '" />
				<div class="searchPictoManager" >' . __('Changer', 'annonces') . '</div>
			</div>';
	}
	function url_radio_maisons()
	{
		$options = get_option('annonces_options');
		echo '<input type="hidden" name="annonces_options[url_radio_maisons]" id="url_radio_maisons" value="' . $options['url_radio_maisons']. '" />';
	}
	function url_radio_terrains()
	{
		$options = get_option('annonces_options');
		echo '<input type="hidden" name="annonces_options[url_radio_terrains]" id="url_radio_terrains" value="' . $options['url_radio_terrains']. '" />';
	}
	function url_radio_toutes()
	{
		$options = get_option('annonces_options');
		echo '<input type="hidden" name="annonces_options[url_radio_toutes]" id="url_radio_toutes" value="' . $options['url_radio_toutes']. '" />';
	}
	function url_budget()
	{
		$options = get_option('annonces_options');
		echo '<input type="hidden" name="annonces_options[url_budget]" id="url_budget" value="' . $options['url_budget']. '" />';
	}
	function url_superficie()
	{
		$options = get_option('annonces_options');
		echo '<input type="hidden" name="annonces_options[url_superficie]" id="url_superficie" value="' . $options['url_superficie']. '" />';
	}
	function url_recherche()
	{
		$options = get_option('annonces_options');
		echo '<input type="hidden" name="annonces_options[url_recherche]" id="url_recherche" value="' . $options['url_recherche']. '" />';
	}

	function annonces_email_activation()
	{
		global $optionYesNoList;
		$options = get_option('annonces_options');
		echo annonces_display::createComboBox('annonces_email_activation', 'annonces_options[annonces_email_activation]', $optionYesNoList, $options['annonces_email_activation']);
	}
	function annonces_email_reception()
	{
		$options = get_option('annonces_options');
		echo '<input type="hidden" name="annonces_options[annonces_email_reception]" id="annonces_email_reception" value="' . $options['annonces_email_reception']. '" />';
	}
	function annonces_sujet_reception()
	{
		$options = get_option('annonces_options');
		echo '<input type="hidden" name="annonces_options[annonces_sujet_reception]" id="annonces_sujet_reception" value="' . $options['annonces_sujet_reception']. '" />';
	}
	function annonces_txt_reception()
	{
		$options = get_option('annonces_options');
		echo '<input type="hidden" name="annonces_options[annonces_txt_reception]" id="annonces_txt_reception" value="' . $options['annonces_txt_reception']. '" />';
	}
	function annonces_html_reception()
	{
		$options = get_option('annonces_options');
		echo '<input type="hidden" name="annonces_options[annonces_html_reception]" id="annonces_html_reception" value="' . $options['annonces_html_reception']. '" />';
	}

	/**
	*	Create the html ouput code for the options page
	*
	*	@return The html code to output for option page
	*/
	function optionMainPage()
	{
		echo annonces_display::afficherDebutPage(__('Options pour les petites annonces', 'annonces'), ANNONCES_IMG_PLUGIN_URL . 'options_s.png', __('options', 'annonces'), __('options', 'annonces'), ANNONCES_TABLE_OPTION, false, '&nbsp;');
?>
<div id="annonces_options_container" >
	<div id="annoncePictoChangerContainer" class="annonce_hide" title="<?php _e('Choix d\'une ic&ocirc;ne', 'annonces'); ?>" >
		<p><?php _e('Ajouter une nouvelle image', 'annonces') ?></p>
		<div id="annoncePictoChanger" ><img src="<?php echo ANNONCES_IMG_PLUGIN_URL; ?>loading.gif" alt="loading" /></div>
		<p><?php _e('Ic&ocirc;nes existantes', 'annonces'); ?></p>
		<div id="annoncePictoChangerContent" ><img src="<?php echo ANNONCES_IMG_PLUGIN_URL; ?>loading.gif" alt="loading" /></div>
	</div>
	<script type="text/javascript" >
		annoncejquery(document).ready(function(){
			jQuery("#annoncePictoChangerContainer").dialog({
				autoOpen: false,
				height: 350,
				width: 350,
				modal: true,
				buttons:{
					"<?php _e('Utiliser', 'annonces'); ?>": function(){
						var choosenPicto;
						jQuery(".pictoToChoose").each(function(){
							if(jQuery(this).is(":checked")){
								choosenPicto = jQuery(this).val();
							}
						});
						jQuery("#" + jQuery("#fieldToUpdate").val()).val(choosenPicto);
						jQuery("#" + jQuery("#previewToUpdate").val()).attr("src", "<?php echo WP_CONTENT_URL . WAY_TO_PICTURES_AOS; ?>" + choosenPicto);
						jQuery(this).dialog("close");
					},
					"<?php _e('Annuler', 'annonces'); ?>": function(){
						jQuery(this).dialog("close");
					}
				},
				close:function(){
					jQuery("#annoncePictoChanger").html(jQuery("#loadingImg").html());
					jQuery("#annoncePictoChangerContent").html(jQuery("#loadingImg").html());
				}
			});

			jQuery(".searchPictoManager").click(function(){
				jQuery("#annoncePictoChangerContainer").dialog("open");
				jQuery("#annoncePictoChanger").load("<?php echo ANNONCES_INC_PLUGIN_URL; ?>ajax.php", {
					"post": "true", "elementCode": jQuery(this).parent("div").attr("id").replace("change_", ""), "action": "loadPictureUploadForm"
				});
				jQuery("#annoncePictoChangerContent").load("<?php echo ANNONCES_INC_PLUGIN_URL; ?>ajax.php", {
					"post": "true", "elementCode": jQuery(this).parent("div").attr("id").replace("change_", ""), "action": "loadPictureDirContent"
				});
			});

			jQuery("#urlFormatterContainer").dialog({
				autoOpen: false, height: 400, width: 800, modal:  true,
				buttons: {
					"<?php _e('Utiliser', 'annonces'); ?>": function(){
						jQuery("#annonce_url_rewrite_template").val(jQuery("#urlRewriteFormat").val());
						if(jQuery("#urlRewriteFormat").val().match("%idpetiteannonce%")){
							jQuery("#IDPAContainer").hide();
						}
						else{
							jQuery("#IDPAContainer").show();
						}
						jQuery(this).dialog("close");
					},
					"<?php _e('Annuler', 'annonces'); ?>": function(){
						jQuery(this).dialog("close");
					}
				}
			});

			jQuery("#urlConfigurator").click(function(){
				jQuery("#urlFormatterContainer").dialog("open");
				jQuery("#urlFormatter").load("<?php echo ANNONCES_INC_PLUGIN_URL; ?>ajax.php", { "post": "true", "elementCode": "urlRewriteFormat", "action": "loadUrlPossibleParams" });
			});
		});
	</script>
	<div class="annonce_hide" id="loadingImg" ><img src="<?php echo ANNONCES_IMG_PLUGIN_URL; ?>loading.gif" alt="loading" /></div>
	<form action="options.php" method="post">

	<?php settings_fields('annonces_options'); ?>
	<br/><br/>
	<?php do_settings_sections('annonces_options_settings'); ?>

	<br/><br/>
	<input class="button-primary" name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />

	</form>
</div>
<?php
		echo annonces_display::afficherFinPage();
	}


	function slugify_noaccent($text)
	{
		$pattern  = Array("/&eacute;/", "/&egrave;/", "/&ecirc;/", "/&ccedil;/", "/&agrave;/", "/&acirc;/", "/&icirc;/", "/&iuml;/", "/&ucirc;/", "/&ocirc;/", "/&Egrave;/", "/&Eacute;/", "/&Ecirc;/", "/&Euml;/", "/&Igrave;/", "/&Iacute;/", "/&Icirc;/", "/&Iuml;/", "/&Ouml;/", "/&Ugrave;/", "/&Ucirc;/", "/&Uuml;/","/�/", "/�/", "/�/", "/�/", "/�/", "/�/", "/�/", "/�/", "/�/", "/�/", "/�/", "/�/", "/�/", "/�/", "/�/", "/�/", "/�/", "/�/", "/�/", "/�/", "/�/", "/�/");
		$rep_pat = Array("e", "e", "e", "c", "a", "a", "i", "i", "u", "o", "E", "E", "E", "E", "I", "I", "I", "I", "O", "U", "U", "U","e", "e", "e", "c", "a", "a", "i", "i", "u", "o", "E", "E", "E", "E", "I", "I", "I", "I", "O", "U", "U", "U");
		if ($text == '')
		{
			return '';
		}
		else
		{
			$text = preg_replace($pattern, $rep_pat, utf8_decode($text));
		}
		return $text;
	}

	function slugify_noaccent_no_utf8decode($text)
	{
		$pattern  = Array("/&eacute;/", "/&egrave;/", "/&ecirc;/", "/&ccedil;/", "/&agrave;/", "/&acirc;/", "/&icirc;/", "/&iuml;/", "/&ucirc;/", "/&ocirc;/", "/&Egrave;/", "/&Eacute;/", "/&Ecirc;/", "/&Euml;/", "/&Igrave;/", "/&Iacute;/", "/&Icirc;/", "/&Iuml;/", "/&Ouml;/", "/&Ugrave;/", "/&Ucirc;/", "/&Uuml;/","/�/", "/�/", "/�/", "/�/", "/�/", "/�/", "/�/", "/�/", "/�/", "/�/", "/�/", "/�/", "/�/", "/�/", "/�/", "/�/", "/�/", "/�/", "/�/", "/�/", "/�/", "/�/");
		$rep_pat = Array("e", "e", "e", "c", "a", "a", "i", "i", "u", "o", "E", "E", "E", "E", "I", "I", "I", "I", "O", "U", "U", "U","e", "e", "e", "c", "a", "a", "i", "i", "u", "o", "E", "E", "E", "E", "I", "I", "I", "I", "O", "U", "U", "U");
		if ($text == '')
		{
			return '';
		}
		else
		{
			$text = preg_replace($pattern, $rep_pat, $text);
		}

		return $text;
	}

	function majUrlAnnonces()
	{
		$eav_value = new Eav();
		global $wpdb;

		$annonces = $eav_value->getLesAnnonces();
		$sizei = count($annonces);

		for($i = 0; $i < $sizei; $i++)
		{
			$eav_mode = new Eav();

			$recup_link = annonces_expression_url;

			$recup_link = str_replace('%idpetiteannonce%', $annonces[$i]->idpetiteannonce, $recup_link);
			$recup_link = str_replace('%titre_annonce%', $annonces[$i]->titre, $recup_link);
			$recup_link = str_replace('%referenceagencedubien%', $annonces[$i]->referenceagencedubien, $recup_link);
			$recup_link = str_replace('%nomgroupeattribut%', $annonces[$i]->nomgroupeattribut, $recup_link);
			$recup_link = str_replace('%descriptiongroupeattribut%', $annonces[$i]->descriptiongroupeattribut, $recup_link);
			$recup_link = str_replace('%ville%', $annonces[$i]->ville, $recup_link);
			$recup_link = str_replace('%departement%', $annonces[$i]->departement, $recup_link);
			$recup_link = str_replace('%region%', $annonces[$i]->region, $recup_link);
			$recup_link = str_replace('%cp%', $annonces[$i]->cp, $recup_link);
			$recup_link = str_replace('%pays%', str_replace("'", '-', $annonces[$i]->pays), $recup_link);
			$recup_link = str_replace('%date_publication%', date("d/m/Y",strtotime($annonces[$i]->autoinsert)), $recup_link);
			$recup_link = str_replace('%type_bien%', str_replace('/','-',$eav_mode->getBien($annonces[$i]->idpetiteannonce)), $recup_link);

			$recup_link = annonces_options::slugify_noaccent($recup_link);
			$recup_link = trim($recup_link);
			$recup_link = str_replace(' ', '-', $recup_link);
			$recup_link = mb_strtolower($recup_link);

			$recup_link = $recup_link . annonce_url_rewrite_template_suffix;

			$maj_annonce = $wpdb->prepare('UPDATE `'.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce`
								SET urlannonce ="'. $recup_link .'"
								WHERE idpetiteannonce="' . $annonces[$i]->idpetiteannonce . '"', array());
			$wpdb->query($maj_annonce);
		}
	}

	function recupNumImage()
	{
		global $wpdb;

			$sqlbudget = "SELECT numphoto FROM " . ANNONCES_TABLE_TEMPPHOTO . "";
			$reqbudget = mysql_query($sqlbudget) or die(mysql_error());
			while($data = mysql_fetch_array($reqbudget))
			{
				$budget_theme = $data["numphoto"];
			}
			return $budget_theme;
	}

	function valeurOption($nomOption) {
		global $annonces_options;

		$valeur = '';
		if ( !empty($annonces_options) && !empty($annonces_options[$nomOption]) ) {
			$valeur = $annonces_options[$nomOption];
		}

		return $valeur;
	}



	function recupinfo($lbloption)
	{
		global $wpdb;

		$sqlbudget = "SELECT nomoption FROM `".$wpdb->prefix.small_ad_table_prefix_AOS."petiteannonce__option` WHERE labeloption='".$lbloption."'";
		$reqbudget = mysql_query($sqlbudget) or die(mysql_error());
		while($data = mysql_fetch_array($reqbudget))
		{
			$budget_theme = $data["nomoption"];
		}
		return $budget_theme;
	}
	function updateoption($lbldefaut,$lblcourant)
	{
		global $wpdb;

		$query = $wpdb->prepare(
			'UPDATE `'.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__option`
			SET nomoption ="%s"
			WHERE labeloption="%s"',
				annonces_options::recupinfo($lbldefaut), $lblcourant);
		$wpdb->query($query);
	}

	function monnaie()
	{
		global $wpdb;

		$query = $wpdb->prepare('select measureunit from '.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__attribut where labelattribut = "PrixLoyerPrixDeCession"', array() );
		$reqmonnaie = $wpdb->get_row($query);
		$monnaie = $reqmonnaie->measureunit;

		return $monnaie;
	}
}

?>