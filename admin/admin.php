<?php
/***************************************************
*Date: 01/10/2009      File:admin.php 	 		   *
*Author: Eoxia							           *
*Comment: Add Admin Interface in Wordpress         *
***************************************************/

	/*	ADD THE MENU INTO ADMIN PANEL	*/
	add_action('admin_menu', array( 'admin', "adds_menu" ));
	add_action('admin_init', array( 'admin', "add_admin_header" ));
	add_action('admin_init', array( 'admin', "add_admin_js" ));

	add_action('admin_notice', array($tools, 'admin_message'));
	
	/*	DYNAMICALLY CREATE GROUP POSSIBILITIES COMBOBOX	*/
	$attribute_group_for_poss = new attribut_group();
	$attribute_group_possibilities = $attribute_group_for_poss->set_attribut_group_in_array($attribute_group_for_poss->get_attribut_group('',"'valid'"));
	$attribute_group_possibilities_for_filters[DEFAULT_FILTERS_EMPTY_VALUE_AOS] = __('Tous','annonces');
	foreach($attribute_group_possibilities as $key => $value){
		$attribute_group_possibilities_for_filters[$key] = $value;
	}