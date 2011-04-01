<?php
/*
	Plugin Name: Annonces
	Plugin URI: http://www.eoxia.com/site-web/pluginannonces/
	Description: Annonces est un plugin permettant d'ajouter facilement des annonces immobil&egrave;re sur son blog. Il suffit d'ajouter cette balise <code>&lt;div rel="annonces" id="annonces" &gt;&lt;/div&gt;</code> dans le code html de votre page.
	Author: Eoxia
	Author URI: http://www.eoxia.com/
	Version: 1.1.2.5
*/
/*  Copyright 2011  EOXIA  (email : contact@eoxia.com)
 
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
	* @version 1.1.2.5
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
	*	Recherche de la version
	*/
	
	
	
	/**
	* INCLUDE LIBRAIRIES
	*/
	require_once(Basename_Dirname_AOS. '/../admin/admin.php');
	require_once(Basename_Dirname_AOS. '/../includes/lib/options.class.php');
	require_once(Basename_Dirname_AOS. '/../includes/lib/admin_annonces.class.php');
	require_once(Basename_Dirname_AOS. '/../includes/lib/admin_attributs.class.php');
	require_once(Basename_Dirname_AOS. '/../includes/lib/eav.class.php');
	require_once(Basename_Dirname_AOS. '/../includes/lib/frontend.class.php');
	require_once(Basename_Dirname_AOS. '/../includes/lib/installation.class.php');
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
	
	/**
	*	Ajoute la biblothèque JQuery dans le Header (même admin) de WordPress
	*/
	add_action('admin_head', array( $view, "add_js" ));
	add_action('wp_head', array( $view, "add_js" ));
		
	
	
	DEFINE('ANNONCES_PLUGIN_DIR', basename(dirname(__FILE__)));
	DEFINE('ANNONCES_HOME_DIR', WP_PLUGIN_DIR . '/' . ANNONCES_PLUGIN_DIR . '/');
	DEFINE('ANNONCES_INC_PLUGIN_DIR', ANNONCES_HOME_DIR . 'includes/');
	DEFINE('ANNONCES_CONFIG', ANNONCES_INC_PLUGIN_DIR . 'config/config.php');
	require_once(ANNONCES_CONFIG);
	
	/**
	*	Création des tables
	**/
	require_once(ANNONCES_MODULES_PLUGIN_DIR . 'installation/creationTables.php');
	annonces_creationTables();
	
	/**
	* Defini chaque option
	*/
	define('annonces_api_key', annonces_options::recupinfo('annonces_api_key'));
	define('url_budget_theme_courant', annonces_options::recupinfo('url_budget_theme_courant'));
	define('url_superficie_theme_courant', annonces_options::recupinfo('url_superficie_theme_courant'));
	define('url_radio_maisons_theme_courant', annonces_options::recupinfo('url_radio_maisons_theme_courant'));
	define('url_recherche_theme_courant', annonces_options::recupinfo('url_recherche_theme_courant'));
	define('theme_activation', annonces_options::recupinfo('theme_activation'));
	define('annonces_marqueur_activation', annonces_options::recupinfo('annonces_marqueur_activation'));
	define('url_radio_terrains_theme_courant', annonces_options::recupinfo('url_radio_terrains_theme_courant'));
	define('url_radio_toutes_theme_courant', annonces_options::recupinfo('url_radio_toutes_theme_courant'));
	define('url_marqueur_courant', annonces_options::recupinfo('url_marqueur_courant'));
	define('annonces_maps_activation', annonces_options::recupinfo('annonces_maps_activation'));
	define('annonces_photos_activation', annonces_options::recupinfo('annonces_photos_activation'));
	define('annonces_date_activation', annonces_options::recupinfo('annonces_date_activation'));
	define('annonces_email_reception', annonces_options::recupinfo('annonces_email_reception'));
	define('annonces_nom_reception', annonces_options::recupinfo('annonces_nom_reception'));
	define('annonces_sujet_reception', annonces_options::recupinfo('annonces_sujet_reception'));
	define('annonces_txt_reception', annonces_options::recupinfo('annonces_txt_reception'));
	define('annonces_html_reception', annonces_options::recupinfo('annonces_html_reception'));
	define('annonces_email_activation', annonces_options::recupinfo('annonces_email_activation'));
	define('annonces_expression_url', annonces_options::recupinfo('annonces_expression_url'));
	define('annonces_page_install', annonces_options::recupinfo('annonces_page_install'));
	define('annonces_url_activation', annonces_options::recupinfo('annonces_url_activation'));
	
	/**
	*	Réécriture des URLs
	**/
	
		
		add_filter('rewrite_rules_array','wp_insertMyRewriteRules');
		add_filter('query_vars','wp_insertMyRewriteQueryVars');
		add_filter('wp_loaded','flushRules');

		// Remember to flush_rules() when adding rules
		function flushRules(){
			global $wp_rewrite;
			$wp_rewrite->flush_rules();
		}

		// Adding a new rule
		function wp_insertMyRewriteRules($rules)
		{
			$page_annonce = Eav::recupPageAnnonce();
		
			$newrules = array();
			$newrules[$page_annonce.'(.+).html?'] = 'index.php?&pagename='.$page_annonce;
			$newrules[$page_annonce.'(.+)'] = 'index.php?&pagename='.$page_annonce;
			return $newrules + $rules;
		}

		// Adding the show_annonce var so that WP recognizes it
		function wp_insertMyRewriteQueryVars($vars)
		{
			array_push($vars, 'show_annonce');
			return $vars;
		}