<?php
/*
	Plugin Name: Annonces
	Plugin URI: http://www.eoxia.com/site-web/pluginannonces/
	Description: Annonces est un plugin permettant d'ajouter facilement des annonces immobil&egrave;re sur son blog. Il suffit d'ajouter cette balise <code>&lt;div rel="annonces" id="annonces" &gt;&lt;/div&gt;</code> dans le code html de votre page.
	Author: Eoxia
	Author URI: http://www.eoxia.com/
	Version: 1.1.2
*/
/*  Copyright 2009  EOXIA  (email : contact@eoxia.com)
 
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.
 
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
 
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
	/**
	* Annonces, Plugin Wordpress
	*
	* Annonces est un plugin OpenSource permettant d'ajouter facilement des annonces sur son blog (ex: annonce immobilere, automobile...).
	* Il suffit d'ajouter cette balise <b><div rel="annonces" id="annonces" ></div></b> dans le code html de votre page.
	* @author Eoxia <contact@eoxia.com>
	* @version 1.1.2
	*/

	/**
	* VARIABLES DE CONFIGURATIONS
	*/
	/**
	* Basename_Dirname_AOS
	* Sert de chemin de base pour inclure des classes ou fonctions du plugin
	*/
	DEFINE('Basename_Dirname_AOS',basename(dirname(__FILE__)));

	/**
	* I18N
	*
	* Wordpress propose un systeme de traduction I18N
	* Cette fonction recupere le nom de domaine annonces et le dossier ou se trouve toute les traductions
	*/
	load_plugin_textdomain( 'annonces', false, Basename_Dirname_AOS.'\includes\languages');
	
	/**
	* Search_index_AOS
	* Chemin du dossier ou Lucene cree son index
	*/
	DEFINE('Search_index_AOS',ABSPATH.'wp-content/plugins/'.Basename_Dirname_AOS.'/includes/data/eav-index');
	
	/**
	* Fichier de configurations
	*/
	require_once('includes/configs.php');

	/**
	* INCLUDES TOOLS
	*/

	/**
	* Tools est une classe contenant toute les methodes utiles du plugin
	*/
	require_once('includes/lib/tools.class.php');
	/**
	* CREATE A TOOL INSTANCE
	*/
	$tools = new tools();
	/**
	* small_ad_install s'execute lorsqu'on active le plugin, Initialise la base de donnee si inexistante...
	* @global array $wpdb variable permettant l'accès a la bd de Wordpress
	*/
	function small_ad_install()
	{
		global $wpdb;
		require_once(Basename_Dirname_AOS. '/../includes/database_structure.php');
		require_once(Basename_Dirname_AOS. '/../includes/database_data.php');
		foreach($create_small_ad_table as $table_name => $sql){
			if($wpdb->get_var("show tables like '$table_name'") != $table_name) 
			{
				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);
				if(isset($create_small_ad_data_table[$table_name])){
					dbDelta($create_small_ad_data_table[$table_name]);
				}
			}
		}

		add_option('annonces_api_key','');
		add_option('annonces_maps_activation','1');
		add_option('annonces_photos_activation','1');
		add_option('annonces_date_activation','1');
		add_option('url_marqueur_courant','red-dot_default.png');
		add_option('url_marqueur_perso','red-dot_default.png');
		add_option('annonces_marqueur_activation','1');
		add_option('theme_activation','1');
		add_option('url_radio_toutes_theme_courant','toutes_default.png');
		add_option('url_radio_terrains_theme_courant','terrains_default.png');
		add_option('url_radio_maisons_theme_courant','maisons_default.png');
		add_option('url_budget_theme_courant','budget_default.png');
		add_option('url_superficie_theme_courant','surface_default.png');
		add_option('url_recherche_theme_courant','recherche_default.png');
	}
	/**
	* Fonction de Wordpress qui appel la methode a activer
	* @param string chemin du fichier contenant la fonction a inclure
	* @param string nom de la methode a inclure
	*/
	register_activation_hook(__FILE__,'small_ad_install');

	/**
	* INCLUDE LIBRAIRIES
	*/
	require_once(Basename_Dirname_AOS. '/../admin/admin.php');
	require_once(Basename_Dirname_AOS. '/../includes/lib/eav.class.php');
	require_once(Basename_Dirname_AOS. '/../includes/lib/frontend.class.php');
	require_once(Basename_Dirname_AOS. '/../includes/lib/Zend/Search/Lucene.php');

	/**
	* CREATE A FRONTEND INSTANCE
	*/
	$view = new Frontend();
	/**
	* Appel la methode show dans le contenu de la page Wordpress
	* Cette methode genère du code html avec toutes les annonces a afficher
	*/
	add_filter('the_content', array( $view, "show" ), 99);
	/**
	* Appel la methode filter_plugin_actions_links dans le fitre de l interface admin de Wordpress
	* Cette methode ajoute au plugin dans la liste des Plugins un lien vers les reglages de ce plugin
	*/
	add_filter('plugin_action_links', array( $view, 'filter_plugin_actions_links'), 10, 2);
	/**
	* Ajoute le CSS dans le Header de Wordpress
	*/
	add_action('wp_head', array( $view, "add_css" ));
	/**
	* Ajoute le Script Javascript de la cle Google Maps dans le Header de Wordpress
	*/
	add_action('wp_head', array( $view, "add_gmap" ));
	/**
	* Ajoute le Script Javascript de la cle Google Maps dans le Header Admin de Wordpress
	*/
	add_action('admin_head', array( $view, "add_gmap" ));
