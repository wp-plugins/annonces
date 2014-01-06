<?php
/*********************************************************
*Date: 01/10/2009   File:admin_attributs_group.class.php *
*Author: Eoxia							                       			 *
*Comment:                                          			 *
*	GENERATE UTILITIES FOR ATTRIBUTES                			 *
*********************************************************/

/*															*/
/*	ATTRIBUTE GROUP FORM CLASS	*/
/*															*/
class attribut_group_form extends sfForm
{

	public function configure()
	{
		global $flag_possibilities;
		$this->setWidgets(array(
		  'flagvalidgroupeattribut' 	=> new sfWidgetFormSelect(array(
				'choices' => $flag_possibilities,
				'label' => __('Validit&eacute;','annonces'),
		  )),
		  'nomgroupeattribut'      		=> new sfWidgetFormInput(array(
				'label' => __('Nom de la cat&eacute;gorie','annonces'),
		  )),
		  'descriptiongroupeattribut' => new sfWidgetFormInput(array(
				'label' => __('Description','annonces'),
		  )),
		  'idgroupeattribut' 					=> new sfWidgetFormInputHidden(),
		));
		$this->widgetSchema->setNameFormat('attribut_group[%s]');

		$this->setValidators(array(
		  'flagvalidgroupeattribut' 	=> new sfValidatorChoice(array('choices' => array_keys($flag_possibilities))),
		  'nomgroupeattribut'       	=> new sfValidatorString(array('max_length' => 50,'required' => true)),
		  'descriptiongroupeattribut' => new sfValidatorString(array('max_length' => 255,'required' => false)),
		  'idgroupeattribut'     			=> new sfValidatorString(array('required' => false)),
		));
	}

}

/*															*/
/*	ATTRIBUTE GROUP CLASS				*/
/*															*/
class attribut_group
{

	private static $table = 'petiteannonce__groupeattribut';
	public $error_message = '';
	public $class_admin_notice = '';

	function create_attribut_group($values)
	{
		global $wpdb;
		global $tools;
		$fields = $content = "  ";

		foreach($values as $field_name => $field_value)
		{
			if($field_value != '')
			{
				$fields .= " ".$field_name.", ";
				$content .= " '".mysql_real_escape_string($tools->IsValid_Variable($field_value))."', ";
			}
		}

		$fields = substr($fields,0,-2);
		$content = substr($content,0,-2);
		if(($fields != "") && ($content != ""))
		{
			$sql = "INSERT INTO " . $wpdb->prefix . small_ad_table_prefix_AOS . self::$table . " (".$fields.")
				VALUES
				(".$content.")";
			if($wpdb->query( ($sql) ))
			{
				$this->error_message = __('Cr&eacute;ation effectu&eacute; avec succ&eacute;s','annonces');
				$this->class_admin_notice = 'admin_notices_class_ok';
			}
			else
			{
				$this->error_message = __('Erreur lors de l\'insertion','annonces');
				if(is_admin())$this->error_message .= '<hr/>'.$sql.'<br/>'.mysql_error().'<hr/>';
				$this->class_admin_notice = 'admin_notices_class_notok';
			}
		}
	}

	function update_attribut_group($values)
	{
		global $wpdb;
		global $tools;
		$sql = "  ";
		$idtoupdate = 0;

		foreach($values as $field_name => $field_value)
		{
			if(($field_value != '') && ($field_name!='idgroupeattribut'))
			{
				$sql .= " ".$field_name." = '".mysql_real_escape_string($tools->IsValid_Variable($field_value))."', ";
			}
			elseif($field_name=='idgroupeattribut')$idtoupdate = mysql_real_escape_string($field_value);
		}

		$sql = substr($sql,0,-2);
		if(($sql != "") && ($idtoupdate!=0))
		{
			$sql = "UPDATE " . $wpdb->prefix . small_ad_table_prefix_AOS . self::$table . "
				SET ".$sql." WHERE idgroupeattribut = '" . $idtoupdate . "' ";
			if( $wpdb->query( ($sql) ))
			{
				$this->error_message = __('Modification effectu&eacute;e avec succ&eacute;s','annonces');
				$this->class_admin_notice = 'admin_notices_class_ok';
			}
			elseif(mysql_error() != '')
			{
				$this->error_message = __('Erreur lors de la modification','annonces');
				if(is_admin())$this->error_message .= '<hr/>'.$sql.'<br/>'.mysql_error().'<hr/>';
				$this->class_admin_notice = 'admin_notices_class_notok';
			}
		}
	}

	function delete_attribut_group($id_to_delete)
	{
		global $wpdb;
		$sql = "UPDATE " . $wpdb->prefix . small_ad_table_prefix_AOS . self::$table . " SET flagvalidgroupeattribut = 'deleted' WHERE idgroupeattribut = '".mysql_real_escape_string($id_to_delete)."' ";
		if( $wpdb->query( ($sql) ))
		{
			$this->error_message = __('Cet &eacute;l&eacute;ment a bien &eacute;t&eacute; supprim&eacute;','annonces');
			$this->class_admin_notice = 'admin_notices_class_ok';
		}
		elseif(mysql_error() != '')
		{
			$this->error_message = __('Erreur lors de la suppression','annonces');
			if(is_admin())$this->error_message .= '<hr/>'.$sql.'<br/>'.mysql_error().'<hr/>';
			$this->class_admin_notice = 'admin_notices_class_notok';
		}
	}

	function get_attribut_group($morequery = '' , $flag = DEFAULT_FLAG_ADMIN_AOS , $actual_page = 0, $option = '')
	{
		global $wpdb;
		$real_page = $actual_page;if($actual_page!=0)$real_page = $actual_page-1;
		$debut = $real_page * NUMBER_OF_ITEM_PAR_PAGE_ADMIN_AOS;

		$moreflag = "";
		if($flag != "")$moreflag = " AND flagvalidgroupeattribut IN (".$flag.") ";

		$TheSelect = "*";
		if($option == 'count')$TheSelect = "COUNT( idgroupeattribut ) ";

		$sql =
			"SELECT ".$TheSelect."
			FROM " . $wpdb->prefix . small_ad_table_prefix_AOS . self::$table . " AS ATT_GRP
			WHERE 1 "
			. $moreflag
			. $morequery ;
		if(($option != 'nolimit') && ($option != 'count'))$sql .= "LIMIT " . $debut . "," . NUMBER_OF_ITEM_PAR_PAGE_ADMIN_AOS;

		if($option == 'count')
		{
			return $wpdb->get_var($sql);
		}
		else
		{
			return $wpdb->get_results( $sql );
		}
	}

	function set_attribut_group_in_array($list_of_attribut)
	{
		$Array_of_attribute = array();

		foreach($list_of_attribut as $key => $attribut_content)
		{
			$Array_of_attribute[$attribut_content->idgroupeattribut] = $attribut_content->nomgroupeattribut;
		}

		return $Array_of_attribute;
	}

	function show_attribut_group($attribut_group_to_show)
	{
		global $flag_possibilities;
		$entete =	'<script type="text/javascript" charset="utf-8">
						annoncejquery(document).ready(function() {
							annoncejquery(\'#example\').dataTable({
								"oLanguage": {
									"sUrl": "' .WP_PLUGIN_URL . '/' . ANNONCES_PLUGIN_DIR.'/includes/js/dataTables.french.txt"
								}
							});
						} );
					</script>';
		$entete .='<div id="container">
					<div id="demo">
					<div id="example_wrapper" class="dataTables_wrapper">';
		$output =
			'<table class="display" id="example" border="0" cellpadding="0" cellspacing="0">
				<thead><tr class="titre_listing">';
		if(is_admin())$output .= '
					<th class="sorting" >ID</th>';
		$output .= '
					<th class="sorting" >'.__('Validit&eacute; de la cat&eacute;gorie','annonces').'</th>
					<th class="sorting" >'.__('Nom de la cat&eacute;gorie','annonces').'</th>
					<th class="sorting" >'.__('Description','annonces').'</th>
					<th class="sorting" colspan="5" >'.__('Op&eacute;ration','annonces').'</th>
				</tr></thead>';

		if( count($attribut_group_to_show) > 0 )
		{
			$output .= '<tbody>';
			foreach($attribut_group_to_show as $key => $attribut_group_content)
			{
				$output .=
				'<tr>';
		if(is_admin())$output .= '
					<td class="listing_header" >'.$attribut_group_content->idgroupeattribut.'</td>';
		$output .= '
					<td>'.$flag_possibilities[$attribut_group_content->flagvalidgroupeattribut].'</td>
					<td>'.$attribut_group_content->nomgroupeattribut.'</td>
					<td>'.$attribut_group_content->descriptiongroupeattribut.'</td>
					<td><img src="'.WP_PLUGIN_URL.'/'.ANNONCES_PLUGIN_DIR.'/medias/images/b_edit.png" alt="edit_cat" class="button_img"  onclick="javascript:document.getElementById(\'act\').value=\'edit\';document.getElementById(\'id_to_treat\').value=\''.$attribut_group_content->idgroupeattribut.'\';document.forms.treat_group_att.submit();"/></td>
					<td><img src="'.WP_PLUGIN_URL.'/'.ANNONCES_PLUGIN_DIR.'/medias/images/b_drop.png" alt="drop_cat" class="button_img" onclick="javascript:document.getElementById(\'act\').value=\'delete\';document.getElementById(\'id_to_treat\').value=\''.$attribut_group_content->idgroupeattribut.'\';var check = confirm(\'&Ecirc;tes vous s&ucirc;r de vouloir supprimer cet &eacute;l&eacute;ment ? \');if(check == true){document.forms.treat_group_att.submit();}" /></td>
				</tr>';
			}
		}
		else
		{
			$output .= '</tbody>';
			$output .= '<tr><td colspan="20" class="no_result" >'.__('Aucun r&eacute;sultat','annonces').'</td></tr>';
		}

		$output .= '</tbody></table></div></div></div>';

		return $entete.$output;
	}

}