<?php
/*
	Plugin Name: Annonces
	Plugin URI: http://www.eoxia.com/open-source/extension-annonces-pour-wordpress
	Description: Annonces est un plugin permettant d'ajouter facilement des annonces immobil&egrave;re sur son blog. Il suffit d'ajouter cette balise <code>&lt;div rel="annonces" id="annonces" &gt;&lt;/div&gt;</code> dans le code html de votre page.
	Author: Eoxia
	Author URI: http://www.eoxia.com/
	Version: 1.2.0.7
*/


DEFINE('ANNONCE_PLUGIN_VERSION', '1.2.0.7');

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
	* @version 1.2.0.0
	*/

	{/* VARIABLES DE CONFIGURATIONS */
		/* ANNONCES_PLUGIN_DIR permet de d�finirle chemin de base pour inclure des classes ou fonctions du plugin */
		DEFINE('ANNONCES_PLUGIN_DIR', basename(dirname(__FILE__)));
	}

	/* Ajout des options	*/
	add_action('admin_init', array('annonces_options', 'add_options'));

	/* TRADUCTION : Wordpress propose un systeme de traduction I18N : Cette fonction recupere le nom de domaine annonces et le dossier ou se trouve toute les traductions */
	load_plugin_textdomain( 'annonces', false, ANNONCES_PLUGIN_DIR.'\includes\languages');

	/**
	* Fichier de configurations
	*/
	require_once(WP_PLUGIN_DIR . '/' . ANNONCES_PLUGIN_DIR . '/includes/configs.php' );
	require_once(WP_PLUGIN_DIR . '/' . ANNONCES_PLUGIN_DIR . '/includes/config/config.php' );

	/*	INCLUDES TOOLS	*/
		/* Tools est une classe contenant toute les methodes utiles du plugin	*/
		require_once('includes/lib/tools.class.php');
		/* CREATE A TOOL INSTANCE	*/
		$tools = new tools();

	/* INCLUDE LIBRAIRIES */
	require_once(ANNONCES_INC_PLUGIN_DIR . 'includes.php');

	require_once(WP_PLUGIN_DIR . '/' . ANNONCES_PLUGIN_DIR . '/admin/admin.php');

	/**
	* CREATE A FRONTEND INSTANCE
	*/
	$view = new annonce_frontend();
	/**
	* Appel la methode show dans le contenu de la page Wordpress
	* Cette methode gen�re du code html avec toutes les annonces a afficher
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
	add_action('init', array( $view, "add_css" ));

	/**
	*	Ajoute la bibloth�que JQuery dans le Header (m�me admin) de WordPress
	*/
	add_action('admin_init', array( $view, "add_js" ));
	add_action('init', array( $view, "add_js" ));

	/**
	*	Cr�ation des tables
	**/
	require_once(ANNONCES_MODULES_PLUGIN_DIR . 'installation/creationTables.php');
	annonces_creationTables();

	{/* Definition des options */
		$annonces_options = get_option('annonces_options');

// 		define('annonces_api_key', annonces_options::valeurOption('gmap_api_key'));
		define('annonces_maps_activation', annonces_options::valeurOption('annonce_activate_map'));
		define('url_marqueur_courant', annonces_options::valeurOption('annonce_map_marker'));
		define('annonce_map_marker_size', annonces_options::valeurOption('annonce_map_marker_size'));

		define('annonces_photos_activation', annonces_options::valeurOption('annonce_show_picture'));
		define('annonces_date_activation', annonces_options::valeurOption('annonce_show_date'));
		define('annonce_frontend_listing_order', annonces_options::valeurOption('annonce_frontend_listing_order'));
		define('annonce_frontend_listing_order_side', annonces_options::valeurOption('annonce_frontend_listing_order_side'));

		define('annonce_currency', annonces_options::valeurOption('annonce_currency'));
		define('annonce_export_picture', annonces_options::valeurOption('annonce_export_picture'));

		define('annonces_url_activation', annonces_options::valeurOption('annonce_activate_url_rewrite'));
		define('annonces_expression_url', annonces_options::valeurOption('annonce_url_rewrite_template'));
		define('annonce_url_rewrite_template_suffix', annonces_options::valeurOption('annonce_url_rewrite_template_suffix'));

		define('url_budget_theme_courant', annonces_options::valeurOption('url_budget'));
		define('url_superficie_theme_courant', annonces_options::valeurOption('url_superficie'));
		define('url_radio_maisons_theme_courant', annonces_options::valeurOption('url_radio_maisons'));
		define('url_recherche_theme_courant', annonces_options::valeurOption('url_recherche'));
		define('url_radio_terrains_theme_courant', annonces_options::valeurOption('url_radio_terrains'));
		define('url_radio_toutes_theme_courant', annonces_options::valeurOption('url_radio_toutes'));

		define('annonces_email_reception', annonces_options::valeurOption('annonces_email_reception'));
		define('annonces_nom_reception', annonces_options::valeurOption('annonces_nom_reception'));
		define('annonces_sujet_reception', annonces_options::valeurOption('annonces_sujet_reception'));
		define('annonces_txt_reception', annonces_options::valeurOption('annonces_txt_reception'));
		define('annonces_html_reception', annonces_options::valeurOption('annonces_html_reception'));
		define('annonces_email_activation', annonces_options::valeurOption('annonces_email_activation'));
	}

	{/* R��criture des URLs */
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
			$newrules[$page_annonce.'(.+).html?'] = 'index.php?&pagename='.$page_annonce.'&show_annonce=$matches[1]';
			$newrules[$page_annonce.'(.+)'] = 'index.php?&pagename='.$page_annonce.'&show_annonce=$matches[1]';
			$finalrules = $newrules + $rules;

			return $finalrules;
		}

		// Adding the show_annonce var so that WP recognizes it
		function wp_insertMyRewriteQueryVars($vars)
		{
			array_push($vars, 'show_annonce');

			return $vars;
		}
	}
