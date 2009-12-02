<?php
/***************************************************
*Date: 01/10/2009      File:admin.php 	 		   *
*Author: Eoxia							           *
*Comment: Add Admin Interface in Wordpress         *
***************************************************/
	/*	INCLUDE LIBRAIRIES	*/
	require_once(dirname(__FILE__) . '/../includes/lib/sfform/require_once.php');
	require_once(dirname(__FILE__) . '/../includes/lib/admin.class.php');
	require_once(dirname(__FILE__) . '/../includes/lib/admin_passerelle.class.php');
	require_once(dirname(__FILE__) . '/../includes/lib/admin_annonces.class.php');
	require_once(dirname(__FILE__) . '/../includes/lib/admin_attributs.class.php');
	require_once(dirname(__FILE__) . '/../includes/lib/admin_attributs_group.class.php');
	require_once(dirname(__FILE__) . '/../includes/lib/admin_filters.class.php');
	require_once dirname(__FILE__) . '/../includes/lib/admin_export.class.php';
	require_once dirname(__FILE__) . '/../includes/lib/Zip/Zip.class.php';
	require_once dirname(__FILE__) . '/../includes/lib/Ftp/Ftp.class.php';
	require_once dirname(__FILE__) . '/../includes/lib/eav.class.php';
	DEFINE('annonces_options_page_url',dirname(__FILE__) . '/options.php');


	/*	CREATE A ADMIN INSTANCE	*/
	$AdminPanel = new admin();
	/*	ADD THE MENU INTO ADMIN PANEL	*/
	add_action('admin_menu', array( $AdminPanel, "adds_menu" ));
	add_action('admin_head', array( $AdminPanel, "add_admin_header" ));
	add_action('admin_head', array( $AdminPanel, "add_admin_js" ));
		
	add_action('admin_notice', array($tools, 'admin_message'));

	/*	ADD THE MENU OPTIONS INTO ADMIN PANEL	*/
	function annonces_options_page()  
	{  
		//add_options_page(Title,Menu title,Access Level,File,Function)  
		add_options_page("Annonces Options","Annonces",10,"annonces/options.php","annonces_options_admin");  
	}  
 
	add_action("admin_menu","annonces_options_page");  
  
	function annonces_options_admin()  
	{  
		require_once annonces_options_page_url;
	}
	
	/*	DYNAMICALLY CREATE GROUP POSSIBILITIES COMBOBOX	*/
	$attribute_group_for_poss = new attribut_group();
	$attribute_group_possibilities = $attribute_group_for_poss->set_attribut_group_in_array($attribute_group_for_poss->get_attribut_group('',"'valid'"));
	$attribute_group_possibilities_for_filters[DEFAULT_FILTERS_EMPTY_VALUE_AOS] = __('Tous','annonces');
	foreach($attribute_group_possibilities as $key => $value)
	{
		$attribute_group_possibilities_for_filters[$key] = $value;
	}
