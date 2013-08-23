<?php

	DEFINE('small_ad_table_prefix_AOS','ctlg_');
	DEFINE('DEFAULT_FILTERS_EMPTY_VALUE_AOS',__("Tous","annonces"));

	DEFINE('WAY_TO_PICTURES_AOS',"/uploads/small_ad/");
	DEFINE('WAY_TO_PICTURES_THUMBNAIL_AOS',WAY_TO_PICTURES_AOS . "thumbnail/");
	DEFINE('NB_PICTURES_ALLOWED_AOS',10);
	DEFINE('MAX_PICTURE_HEIGHT_AOS',140);
	DEFINE('MAX_PICTURE_WIDTH_AOS',140);
	DEFINE('DEFAULT_PICTURE_TOKEN_AOS','-9999999');
	$TABALLOWEDEXT['.jpg']=1;
	$TABALLOWEDEXT['.jepg']=1;
	$TABALLOWEDEXT['.gif']=1;
	$TABALLOWEDEXT['.bmp']=1;
	$TABALLOWEDEXT['.png']=1;

	DEFINE('NUMBER_OF_ITEM_PAR_PAGE_ADMIN_AOS',10000);
	DEFINE('NUMBER_OF_ITEM_PAR_PAGE_ADMIN_AOS_LISTING',15);
	DEFINE('PAGINATION_OFFSET_ADMIN_AOS',4);
	DEFINE('DEFAULT_FLAG_ADMIN_AOS',"'valid','moderated'");
	DEFINE('WAY_TO_EXPORT_AOS',"/uploads/small_ad/export/");

	DEFINE('NUMBER_OF_ITEM_PAR_PAGE_FRONTEND_AOS',6);
	DEFINE('PAGINATION_OFFSET_FRONTEND_AOS',4);
	DEFINE('DEFAULT_FLAG_AOS',"'valid'");

	DEFINE('ANN_WPSHOP_PLUGIN_MAINFILE', 'wpshop/wpshop.php');

// 	DEFINE('WPSHOP_NEWTYPE_IDENTIFIER_PRODUCT', 'wpshop_product');
	$table_name_by_type = array();
	$id_attribute_by_type = array();
	$field_name_value_by_type = array();
	global $wpdb;
	$table_name_by_type['char'] = $wpdb->prefix . small_ad_table_prefix_AOS . 'petiteannonce__attributchar';
		$field_name_value_by_type['char'] = 'valueattributchar';
			$id_attribute_by_type['char'] = 'idattributchar';
	$table_name_by_type['date'] = $wpdb->prefix . small_ad_table_prefix_AOS . 'petiteannonce__attributdate';
		$field_name_value_by_type['date'] = 'valueattributdate';
			$id_attribute_by_type['date'] = 'idattributdate';
	$table_name_by_type['dec'] = $wpdb->prefix . small_ad_table_prefix_AOS . 'petiteannonce__attributdec';
		$field_name_value_by_type['dec'] = 'valueattributdec';
			$id_attribute_by_type['dec'] = 'idattributdec';
	$table_name_by_type['int'] = $wpdb->prefix . small_ad_table_prefix_AOS . 'petiteannonce__attributint';
		$field_name_value_by_type['int'] = 'valueattributint';
			$id_attribute_by_type['int'] = 'idattributint';
	$table_name_by_type['text'] = $wpdb->prefix . small_ad_table_prefix_AOS . 'petiteannonce__attributtext';
		$field_name_value_by_type['text'] = 'valueattributtextcourt';
			$id_attribute_by_type['text'] = 'idattributtext';


	$type_export = array('csv'=>__('Csv','annonces'),'xml'=>__('Xml','annonces'));
	$flag_possibilities = array('valid'=>__('Valide','annonces'),'moderated'=>__('Exclure','annonces'),'deleted'=>__('Supprim&eacute;','annonces'));
	$flag_possibilities_for_filters = array(DEFAULT_FILTERS_EMPTY_VALUE_AOS=>__('Tous','annonces'),'valid'=>__('Valide','annonces'),'moderated'=>__('Exclure','annonces'));

	$attribute_type_possibilities = array('CHAR'=>__('Caract&egrave;re','annonces'),'DATE'=>__('Date','annonces'),'DEC'=>__('D&eacute;cimal','annonces'),'INT'=>__('Entier','annonces'),'TEXT'=>__('Texte','annonces'));
	$attribute_type_possibilities_for_filters = array(DEFAULT_FILTERS_EMPTY_VALUE_AOS=>__('Tous','annonces'),'char'=>__('Caract&egrave;re','annonces'),'date'=>__('Date','annonces'),'dec'=>__('D&eacute;cimal','annonces'),'int'=>__('Entier','annonces'),'text'=>__('Texte','annonces'));

	$flag_visible_attribut_possibilities = array('oui'=>__('Oui','annonces'),'non'=>__('Non','annonces'));
	$flag_visible_attribut_possibilities_for_filters = array(DEFAULT_FILTERS_EMPTY_VALUE_AOS=>__('Tous','annonces'),'oui'=>__('Oui','annonces'),'non'=>__('Non','annonces'));
	$flag_a_exporter_possibilities = array('oui'=>__('Oui','annonces'),'non'=>__('Non','annonces'));
	$flag_a_exporter_possibilities_for_filters = array(DEFAULT_FILTERS_EMPTY_VALUE_AOS=>__('Tous','annonces'),'oui'=>__('Oui','annonces'),'non'=>__('Non','annonces'));
	$select_yes_no = array('oui'=>__('Oui','annonces'),'non'=>__('Non','annonces'));
	$select_yes_no_for_filters = array(DEFAULT_FILTERS_EMPTY_VALUE_AOS=>__('Tous','annonces'),'oui'=>__('Oui','annonces'),'non'=>__('Non','annonces'));

	$select_special_attribut = array();
	$select_special_attribut['BilanConsommationEnergie'] = array(
				'A' => 'A',
				'B' => 'B',
				'C' => 'C',
				'D' => 'D',
				'E' => 'E',
				'F' => 'F',
				'G' => 'G'
	);
	$select_special_attribut['BilanEmissionGES'] = array(
				'A' => 'A',
				'B' => 'B',
				'C' => 'C',
				'D' => 'D',
				'E' => 'E',
				'F' => 'F',
				'G' => 'G'
	);
	$select_special_attribut['TypeAnnonce'] = array(
				//'cession de bail' => 'Cession de bail',
				//'location' => 'Location',
				//'location vacances' => 'Location vacances',
				//'produit d&#39;investissement' => 'Produit d&#39;investissement',
				'vente' => 'Vente',
				'vente de prestige' => 'Vente de prestige',
				'vente fond de commerce' => 'Vente fond de commerce',
				//'viager' => 'Viager'
	);
	$select_special_attribut['TypeBien'] = array(
				//'appartement' => 'Appartement',
				//'batiment' => 'B&acirc;timent',
				//'boutique' => 'Boutique',
				//'bureau' => 'Bureau',
				//'chateau' => 'Ch&acirc;teau',
				'inconnu' => 'Inconnu',
				//'hotel particulier' => 'H&ocirc;tel particulier',
				//'immeuble' => 'Immeuble',
				//'local' => 'Local',
				//'loft/atelier/surface' => 'Loft/atelier/surface',
				'maison/villa' => 'Maison/villa',
				//'parking/box' => 'Parking/box',
				'terrain' => 'Terrain'
	);
	$select_special_attribut['TypeDeChauffage'] = array(
				'128' => 'Radiateur',
				'256' => 'Sol',
				'384' => 'Mixte',
				'512' => 'Gaz',
				'640' => 'Gaz radiateur',
				'768' => 'Gaz sol',
				'896' => 'Gaz mixte',
				'1024' => 'Fuel',
				'1152' => 'Fuel radiateur',
				'1280' => 'Fuel sol',
				'1408' => 'Fuel mixte',
				'2048' => '&Eacute;lectrique',
				'2176'=> '&Eacute;lectrique radiateur',
				'2304' => '&Eacute;lectrique sol',
				'2432' => '&Eacute;lectrique mixte',
				'4096' => 'Collectif',
				'4224' => 'Collectif radiateur',
				'4352' => 'Collectif sol',
				'4480' => 'Collectif mixte',
				'4608' => 'Collectif gaz',
				'4736' => 'Collectif gaz radiateur',
				'4864' => 'Collectif gaz sol',
				'4992' => 'Collectif gaz mixte',
				'5120' => 'Collectif fuel',
				'5248' => 'Collectif fuel radiateur',
				'5376' => 'Collectif fuel sol',
				'5504' => 'Collectif fuel mixte',
				'6144' => 'Collectif &eacute;lectrique',
				'6272' => 'Collectif &eacute;lectrique radiateur',
				'6400' => 'Collectif &eacute;lectrique sol',
				'6528' => 'Collectif &eacute;lectrique mixte',
				'8192' => 'Individuel',
				'8320' => 'Individuel radiateur',
				'8448' => 'Individuel sol',
				'8576' => 'Individuel mixte',
				'8704' => 'Individuel gaz',
				'8832' => 'Individuel gaz radiateur',
				'8960' => 'Individuel gaz sol',
				'9088' => 'Individuel gaz mixte',
				'9216' => 'Individuel fuel',
				'9344' => 'Individuel fuel radiateur',
				'9472' => 'Individuel fuel sol',
				'9600' => 'Individuel fuel mixte',
				'10240' => 'Individuel &eacute;lectrique',
				'10368' => 'Individuel &eacute;lectrique radiateur',
				'10496' => 'Individuel &eacute;lectrique sol',
				'10624' => 'Individuel &eacute;lectrique mixte'
	);
	$select_special_attribut['TypeDeCuisine'] = array(
			'1' => 'Aucune',
			'2' => 'Am&eacute;ricaine',
			'3' => 'S&eacute;par&eacute;e',
			'4' => 'Industrielle',
			'5' => 'Coin cuisine',
			'6' => 'Am&eacute;ricaine &eacute;quip&eacute;e',
			'7' => 'S&eacute;par&eacute;e &eacute;quip&eacute;e',
			'8' => 'Coin cuisine &eacute;quip&eacute;'
	);
	$select_special_attribut['TypeDeResidence'] = array(
				'GIT' => 'G&icirc;t',
				'HOT' => 'Chambre d&#39;h&ocirc;te'
	);
	$select_special_attribut['Situation'] = array(
				'montagne' => 'Montagne',
				'mer' => 'Mer',
				'campagne' => 'Campagne',
				'ville' => 'Ville'
	);
	$select_special_attribut['Publications'] = array(
				'SL' => 'Internet www.seloger.com (et services partenaires)',
				'IS' => 'www.immostreet.com (et ses services partenaires)',
				'WA' => 'Site web de l&#39;agence si celui-ci est g&eacute;r&eacute; par Seloger.com',
				'SN' => 'SNPI www.snpi.com',
				'BD' => 'Belles demeures www.bellesdemeures.com',
				'PR' => 'support presse'
	);
	$select_special_attribut['CodeLangue1'] = array(
				'EN' => 'EN',
				'FR' => 'FR',
				'ES' => 'ES',
				'DE' => 'DE',
				'IT' => 'IT',
				'NL' => 'NL',
				'PT' => 'PT'
	);
	$select_special_attribut['CodeLangue2'] = array(
				'EN' => 'EN',
				'FR' => 'FR',
				'ES' => 'ES',
				'DE' => 'DE',
				'IT' => 'IT',
				'NL' => 'NL',
				'PT' => 'PT'
	);
	$select_special_attribut['CodeLangue3'] = array('EN' => 'EN',
				'FR' => 'FR',
				'ES' => 'ES',
				'DE' => 'DE',
				'IT' => 'IT',
				'NL' => 'NL',
				'PT' => 'PT'
	);
	$select_special_attribut['SousTypeDeBien'] = array(
				'bastide' => 'Bastide',
				'bastidon' => 'Bastidon',
				'bergerie' => 'Bergerie',
				'cabanon' => 'Cabanon',
				'chalet' => 'Chalet',
				'chambre de service' => 'Chambre de service',
				'corps de ferme' => 'Corps de ferme',
				'demeure' => 'demeure',
				'domaine' => 'domaine',
				'duplex' => 'Duplex',
				'echoppe' => '&eacute;choppe',
				'exploitation agricole' => 'Exploitation agricole',
				'exploitation viticole' => 'Exploitation viticole',
				'ferme' => 'Ferme',
				'grange' => 'Grange',
				'loft' => 'Loft',
				'maison ancienne' => 'Maison ancienne',
				'maison basque' => 'Maison basque',
				'maison charentaise' => 'Maison charentaise',
				'maison contemporaine' => 'Maison contemporaine',
				'maison d&#39;architecte' => 'Maison d&#39;architecte',
				'maison d&#39;hote' => 'Maison d&#39;h&ocirc;te',
				'maison de loisirs' => 'Maison de loisirs',
				'maison de maitre' => 'Maison de ma&icirc;tre',
				'maison de village' => 'Maison de village',
				'maison de ville' => 'Maison de ville',
				'maison en pierre' => 'Maison en pierre',
				'maison jumelee' => 'maison jumel&eacute;e',
				'maison landaise' => 'Maison landaise',
				'maison longere' => 'Maison long&egrave;re',
				'maison provencale' => 'Maison proven&ccedil;ale',
				'maison traditionnelle' => 'Maison traditionnelle',
				'manoir' => 'Manoir',
				'mas' => 'Mas',
				'mazet' => 'Mazet',
				'moulin' => 'Moulin',
				'pavillon' => 'Pavillon',
				'propriete' => 'Propri&eacute;t&eacute;',
				'propriete equestre' => 'Propri&eacute;t&eacute; &eacute;questre',
				'riad' => 'Riad',
				'studette' => 'Studette',
				'terrain agricole' => 'Terrain agricole',
				'terrain commercial' => 'Terrain commercial',
				'terrain de loisirs' => 'Terrain de loisirs',
				'terrain industriel' => 'Terrain industriel',
				'terrain viticole' => 'Terrain viticole',
				'toulousaine' => 'Toulousaine',
				'triplex' => 'Triplex',
				'villa' => 'Villa',
	);

	$field_select_yes_no = array('LoyerCC','LoyerHT','Meuble','WCSepares','OrientationSud','OrientationEst','OrientationOuest','OrientationNord','Ascenseur','Cave','Digicode','Interphone','Gardien','Terrasse','Alarme','CableTV','Calme','Climatisation','Piscine','AmenagementPourHandicapes','AnimauxAcceptes','Cheminee','Congelateur','Four','LaveVaisselle','MicroOndes','Placards','Telephone','ProcheLac','ProcheTennis','ProchePistesDeSki','VueDegagee','Duplex','MandatEnExclusivite','CoupDeCoeur','Intercabinet','IntercabinetPrive','Recent','TravauxAPrevoir','Entree','Residence','Parquet','VisAVis','MonteCharge','Quai','PrixMasque','ChargesMensuellesHT','LoyerAnnuelCC','LoyerAnnuelHT','ChargesAnnuellesHT','LoyerAnnuelAuM2CC','LoyerAnnuelAuM2HT','ChargesAnnuellesAuM2HT','Divisible');

	$fields_validator_string_1 = array('BilanConsommationEnergie','BilanEmissionGES');
	$fields_validator_string_5 = array('CP','CPReelDuBien','CPMandataire','TransportLigne');
	$fields_validator_string_6 = array('Pays');
	$fields_validator_string_10 = array('TelephoneAAfficher','TelephoneMandataire');
	$fields_validator_string_15 = array('NDeMandat');
	$fields_validator_string_30 = array('IdentifiantTechnique');
	$fields_validator_string_32 = array('TransportStation');
	$fields_validator_string_50 = array('Ville','VilleReelleDuBien','VilleMandataire','CodeNegociateur');
	$fields_validator_string_64 = array('QuartierProximite','Libelle','ContactAAfficher','EmailAAfficher','NomMandataire','PrenomMandataire','RaisonSocialeMandataire','ProximiteLangue1','LibelleLangue1','ProximiteLangue2','LibelleLangue2','ProximiteLangue3','LibelleLangue3');
	$fields_validator_string_128 = array('Adresse','Photo1','Photo2','Photo3','Photo4','Photo5','Photo6','Photo7','Photo8','Photo9','Photo10','Photo11','Photo12','Photo13','Photo14','Photo15','Photo16','Photo17','Photo18','Photo19','Photo20','PhotoPanoramique','AdresseMandataire');
	$fields_validator_string_256 = array('TitrePhoto1','TitrePhoto2','TitrePhoto3','TitrePhoto4','TitrePhoto5','TitrePhoto6','TitrePhoto7','TitrePhoto8','TitrePhoto9','URLVisiteVirtuelle');
	$fields_validator_string_4000 = array('Descriptif','CommentairesMandataire','CommentairesPrives','DescriptifLangue1','DescriptifLangue2','DescriptifLangue3');

	$geolocalisation_field = array('adresse','ville','cp','pays','departement','region','latitude','longitude','token','autolocalisation','iddest','idsrc','flagvalidgeolocalisation');

	$annonce_general_action = array(__('Supprimer','annonces') => 'selection_delete', __('Exclure','annonces') => 'selection_moderated', __('Valider','annonces') => 'selection_valid', __('Exportable','annonces') => 'selection_exportable', __('Non exportable','annonces') => 'selection_not_exportable');
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if(is_plugin_active(ANN_WPSHOP_PLUGIN_MAINFILE)){
		$annonce_general_action[__('Exporter vers WP-Shop','annonces')] = 'export_to_wpshop';
	}
	$attribut_general_action = array(__('Supprimer','annonces') => 'selection_delete', __('Exclure','annonces') => 'selection_moderated', __('Valider','annonces') => 'selection_valid', __('Visible','annonces') => 'selection_visible', __('Non visible','annonces') => 'selection_not_visible');
