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
			add_menu_page('Gestion des annonces', 'Annonces', 'level_2', Basename_Dirname_AOS.'/admin/annonce_listing.php', '', WP_PLUGIN_URL.'/'.Basename_Dirname_AOS.'/medias/images/icon_menu.gif' );
			add_submenu_page(Basename_Dirname_AOS.'/admin/annonce_listing.php', __('Gestion des annonces','annonces'), __('Annonces','annonces'), 'level_2', Basename_Dirname_AOS.'/admin/annonce_listing.php' );
			add_submenu_page(Basename_Dirname_AOS.'/admin/annonce_listing.php', __('Gestion des cat&eacute;gories','annonces'), __('Cat&eacute;gories','annonces'), 'level_10', Basename_Dirname_AOS.'/admin/attribut_grp_listing.php' );
			add_submenu_page(Basename_Dirname_AOS.'/admin/annonce_listing.php', __('Gestion des attributs','annonces'), __('Attributs','annonces'), 'level_10', Basename_Dirname_AOS.'/admin/attribut_listing.php' );
			add_submenu_page(Basename_Dirname_AOS.'/admin/annonce_listing.php', __('Gestion des passerelles','annonces'), __('Passerelle','annonces'), 'level_10', Basename_Dirname_AOS.'/admin/export_admin.php' );
			add_submenu_page(Basename_Dirname_AOS.'/admin/annonce_listing.php', __('Exporter les annonces','annonces'), __('Exporter','annonces'), 'level_2', Basename_Dirname_AOS.'/admin/export.php' );
			add_submenu_page(Basename_Dirname_AOS.'/admin/annonce_listing.php', __('Importer les annonces','annonces'), __('Importer','annonces'), 'level_2', Basename_Dirname_AOS.'/admin/import_admin.php' );
		}
	}

	function add_admin_header() {
		echo '<link rel="stylesheet" type="text/css" href="'. WP_PLUGIN_URL . '/' . Basename_Dirname_AOS. '/includes/css/admin.css" />';
	}

	function add_admin_js()
	{
		echo '<script type="text/javascript" src="'.WP_PLUGIN_URL . '/' . Basename_Dirname_AOS. '/includes/js/admin.js?v=1.0"></script>';
		echo '<script type="text/javascript" src="'.WP_PLUGIN_URL . '/' . Basename_Dirname_AOS. '/includes/js/geolocalisation.js"></script>';
	}
}
