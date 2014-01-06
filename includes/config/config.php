<?php

	require_once('configNomTables.php');


	DEFINE('ANNONCES_HOME_DIR', WP_PLUGIN_DIR . '/' . ANNONCES_PLUGIN_DIR . '/');
	DEFINE('ANNONCES_HOME_URL', WP_PLUGIN_URL . '/' . ANNONCES_PLUGIN_DIR . '/');

	DEFINE('ANNONCES_INC_PLUGIN_URL', ANNONCES_HOME_URL . 'includes/');
	DEFINE('ANNONCES_INC_PLUGIN_DIR', ANNONCES_HOME_DIR . 'includes/');

	DEFINE('ANNONCES_LIB_PLUGIN_DIR', ANNONCES_INC_PLUGIN_DIR . 'lib/');
	DEFINE('ANNONCES_LIB_PLUGIN_URL', ANNONCES_INC_PLUGIN_URL . 'lib/');
	DEFINE('ANNONCES_MODULES_PLUGIN_DIR', ANNONCES_INC_PLUGIN_DIR . 'modules/');

	DEFINE('ANNONCES_CSS_URL', ANNONCES_INC_PLUGIN_URL . 'css/');
	DEFINE('ANNONCES_JS_URL', ANNONCES_INC_PLUGIN_URL . 'js/');

	DEFINE('ANNONCES_IMG_PLUGIN_DIR', ANNONCES_HOME_DIR . 'medias/images/');
	DEFINE('ANNONCES_IMG_PLUGIN_URL', ANNONCES_HOME_URL . 'medias/images/');



	/**
	*	Define the option possible value for listing order
	*/
	$optionOrderList = array();
	$optionOrderList['titre'] = __('Intitul&eacute;', 'evarisk');
	$optionOrderList['autoinsert'] = __('Date', 'evarisk');
	$optionOrderList['valueattributdec'] = __('Prix', 'evarisk');

	/**
	*	Define the option possible value for listing order
	*/
	$optionOrderSideList = array();
	$optionOrderSideList['DESC'] = __('Descendant', 'evarisk');
	$optionOrderSideList['ASC'] = __('Ascendant', 'evarisk');

	/**
	*	Define the option possible value
	*/
	$optionCurrencyList = array();
	$optionCurrencyList['$'] = __('Dollar US', 'evarisk');
	$optionCurrencyList['&euro;'] = __('Euro', 'evarisk');
	$optionCurrencyList['&pound;'] = __('Livre Sterling', 'evarisk');
	$optionCurrencyList['&yen;'] = __('Yen', 'evarisk');

	/**
	*	Define the option possible value
	*/
	$optionYesNoList = array();
	$optionYesNoList['oui'] = __('Oui', 'evarisk');
	$optionYesNoList['non'] = __('Non', 'evarisk');

	/**
	*	Define the option possible value
	*/
	$optionPictureExportTypeList = array();
	$optionPictureExportTypeList['file'] = __('Envoyer les photos', 'evarisk');
	$optionPictureExportTypeList['link'] = __('Ne faire qu\'un lien vers les photos', 'evarisk');

	/**
	*	Define the different keyword available for url rewriting
	*/
	$urlKeyword = array();
	$urlKeyword['idpetiteannonce'] = __('Identifiant de l\'annonce', 'annonces');
	$urlKeyword['titre_annonce'] = __('Titre de l\'annonce', 'annonces');
	$urlKeyword['referenceagencedubien'] = __('R&eacute;f&eacute;rence de l\'annonce', 'annonces');
	// $urlKeyword['nomgroupeattribut'] = __('Nom du groupe d\'attribut', 'annonces');
	// $urlKeyword['descriptiongroupeattribut'] = __('Description du groupe d\'attribut', 'annonces');
	$urlKeyword['ville'] = __('Ville de l\'annonce', 'annonces');
	$urlKeyword['departement'] = __('D&eacute;partement de l\'annonce', 'annonces');
	$urlKeyword['region'] = __('R&eacute;gion de l\'annonce', 'annonces');
	$urlKeyword['cp'] = __('Code postal de l\'annonce', 'annonces');
	$urlKeyword['pays'] = __('Pays de l\'annonce', 'annonces');
	$urlKeyword['date_publication'] = __('Date de publication', 'annonces');
	$urlKeyword['type_bien'] = __('Type de bien', 'annonces');

?>