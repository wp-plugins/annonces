<?php
/***************************************************
*Date: 01/10/2009      File:admin.class.php    	   *
*Author: Eoxia							           *
*Comment:                                          *
*	GENERATE MENU FOR ADMIN PANEL                  *
***************************************************/

class admin
{

	/*	ADD AN ADMIN MENU IN ADMIN PANEL 	*/
	function adds_menu()
	{
		if (function_exists('add_menu_page'))
		{
			add_menu_page('Gestion des annonces', 'Annonces', 'level_2', ANNONCES_PLUGIN_DIR.'/admin/annonce_listing.php', '', WP_PLUGIN_URL.'/'.ANNONCES_PLUGIN_DIR.'/medias/images/icon_menu.png' );
			add_submenu_page(ANNONCES_PLUGIN_DIR.'/admin/annonce_listing.php', __('Gestion des annonces','annonces'), __('Annonces','annonces'), 'level_2', ANNONCES_PLUGIN_DIR.'/admin/annonce_listing.php' );
			add_submenu_page(ANNONCES_PLUGIN_DIR.'/admin/annonce_listing.php', __('Ajouter une annonce','annonces'), __('Ajouter','annonces'), 'level_1', ANNONCES_PLUGIN_DIR.'/admin/add_annonce.php' );
			add_submenu_page(ANNONCES_PLUGIN_DIR.'/admin/annonce_listing.php', __('Gestion des cat&eacute;gories','annonces'), __('Cat&eacute;gories','annonces'), 'level_5', ANNONCES_PLUGIN_DIR.'/admin/attribut_grp_listing.php' );
			add_submenu_page(ANNONCES_PLUGIN_DIR.'/admin/annonce_listing.php', __('Gestion des attributs','annonces'), __('Attributs','annonces'), 'level_5', ANNONCES_PLUGIN_DIR.'/admin/attribut_listing.php' );
			add_submenu_page(ANNONCES_PLUGIN_DIR.'/admin/annonce_listing.php', __('Gestion des passerelles','annonces'), __('Passerelle','annonces'), 'level_10', ANNONCES_PLUGIN_DIR.'/admin/export_admin.php' );
			add_submenu_page(ANNONCES_PLUGIN_DIR.'/admin/annonce_listing.php', __('Exporter les annonces','annonces'), __('Exporter','annonces'), 'level_10', ANNONCES_PLUGIN_DIR.'/admin/export.php' );
			add_submenu_page(ANNONCES_PLUGIN_DIR.'/admin/annonce_listing.php', __('Importer les annonces','annonces'), __('Importer','annonces'), 'level_10', ANNONCES_PLUGIN_DIR.'/admin/import_admin.php' );

			/*	Add the options menu in the options section	*/
			add_options_page(__('Options pour les petites annonces', 'annonces'), __('Annonces', 'annonces'), 'level_10', 'annonces_options', array('annonces_options', 'optionMainPage'));
		}
	}

	function add_admin_header()
	{
		wp_register_style('annonces_css_main', ANNONCES_CSS_URL . 'annonce.css', '', ANNONCE_PLUGIN_VERSION);
		wp_enqueue_style('annonces_css_main');
		wp_register_style('annonces_css_admin', ANNONCES_CSS_URL . 'admin.css', '', ANNONCE_PLUGIN_VERSION);
		wp_enqueue_style('annonces_css_admin');
		wp_register_style('annonces_css_fileuploader', ANNONCES_CSS_URL . 'fileuploader.css', '', ANNONCE_PLUGIN_VERSION);
		wp_enqueue_style('annonces_css_fileuploader');
		wp_register_style('annonces_jquery_custom', ANNONCES_CSS_URL . 'jquery/jquery-ui-1.7.2.custom.css', '', ANNONCE_PLUGIN_VERSION);
		wp_enqueue_style('annonces_jquery_custom');
	}

	function add_admin_js() {
		if ( !wp_script_is('jquery-ui-dialog', 'queue') ) {
			wp_enqueue_script('jquery-ui-dialog');
		}

		wp_enqueue_script('jquery-form');
		wp_enqueue_script('annonces_js_main_admin', ANNONCES_JS_URL . 'admin.js', '', ANNONCE_PLUGIN_VERSION);
		wp_enqueue_script('annonces_js_main', ANNONCES_JS_URL . 'backend_annonce.js', '', ANNONCE_PLUGIN_VERSION);
		wp_enqueue_script('annonces_js_jq_fileuploader', ANNONCES_JS_URL . 'fileuploader.js', '', ANNONCE_PLUGIN_VERSION);
		wp_enqueue_script('annonces_js_geolocalisation', ANNONCES_JS_URL . 'geolocalisation.js', '', ANNONCE_PLUGIN_VERSION);
		wp_enqueue_script('annonces_js_jq_datatable', ANNONCES_JS_URL . 'jquery.dataTables.js', '', ANNONCE_PLUGIN_VERSION);
	}

}