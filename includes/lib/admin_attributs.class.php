<?php
/***************************************************
*Date: 01/10/2009   File:admin_attributs.class.php *
*Author: Eoxia							                       *
*Comment:                                          *
*	GENERATE UTILITIES FOR ATTRIBUTES                *
***************************************************/

/*															*/
/*		ATTRIBUTE FORM CLASS			*/
/*															*/
require_once(dirname(__FILE__) . '/sfform/require_once.php');

class attribut_annonce_form extends sfForm
{

	public function configure()
	{
		global $flag_possibilities;
		global $attribute_type_possibilities;
		global $flag_visible_attribut_possibilities;
		global $attribute_group_possibilities;

		$this->setWidgets(array(
		  'flagvalidattribut' 	=> new sfWidgetFormSelect(array(
				'choices' => $flag_possibilities,
				'label' => __('Validit&eacute;','annonces'),
		  )),
			'flagvisibleattribut' 	=> new sfWidgetFormSelect(array(
				'choices' => $flag_visible_attribut_possibilities,
				'label' => __('Visibilit&eacute;','annonces'),
		  )),
		  'group_attribut'      	=> new sfWidgetFormSelect(array(
				'choices' => $attribute_group_possibilities,
				'label' => __('Groupe de l\'attribut','annonces'),
		  )),
		  'typeattribut'      	=> new sfWidgetFormSelect(array(
				'choices' => $attribute_type_possibilities,
				'label' => __('Type de l\'attribut','annonces'),
		  )),
		  'nomattribut'				 	=> new sfWidgetFormInput(array(
				'label' => __('Nom de l\'attribut','annonces'),
		  )),
			'measureunit' 				=> new sfWidgetFormInput(array(
				'label' => __('Unit&eacute; de mesure','annonces'),
		  )),
		  'idattribut' 					=> new sfWidgetFormInputHidden(),
		));
		$this->widgetSchema->setNameFormat('attribut_annonce[%s]');

		$this->setValidators(array(
		  'flagvalidattribut' 	=> new sfValidatorChoice(array('choices' => array_keys($flag_possibilities))),
		  'flagvisibleattribut' => new sfValidatorChoice(array('choices' => array_keys($flag_visible_attribut_possibilities))),
		  'typeattribut'       	=> new sfValidatorString(array('required' => true)),
		  'group_attribut' 			=> new sfValidatorString(array('required' => false)),
		  'nomattribut' 				=> new sfValidatorString(array('max_length' => 70,'required' => true)),
		  'measureunit'					=> new sfValidatorString(array('max_length' => 10,'required' => false)),
		  'idattribut'     			=> new sfValidatorString(array('required' => false)),
		));
	}

}

class attribut_annonce
{

	private static $table = 'petiteannonce__attribut';
	public $error_message = '';
	public $class_admin_notice = '';

	function slugify_label($text)
	{

	  $pattern = Array("�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�");
	  $rep_pat = Array("e", "e", "e", "c", "a", "a", "i", "i", "u", "o", "E", "E", "E", "E", "I", "I", "I", "I", "O", "U", "U", "U");

	  if (empty($text))
	  {
			return '';
	  }
		else
		{
			$text = str_replace($pattern, $rep_pat, utf8_decode($text));
			$text = preg_replace('/\s/', '', $text);
			$text = trim($text);
	  }

	  return $text;
	}

	function create_attribut_annonce($values)
	{
		global $wpdb;
		global $tools;
		$fields = $content = "  ";
		$attribute_group_id = $attribute_id = 0;

		foreach($values as $field_name => $field_value)
		{
			if(($field_value != '') && ($field_name != 'group_attribut'))
			{
				$fields .= " ".$field_name.", ";
				$content .= " '".mysql_real_escape_string($tools->IsValid_Variable($field_value))."', ";
				if($field_name == 'nomattribut')
				{
					$fields .= " labelattribut, ";
					$content .= " '".mysql_real_escape_string($tools->IsValid_Variable($this->slugify_label($field_value)))."', ";
				}
			}
			elseif($field_name == 'group_attribut')
			{
				$attribute_group_id = mysql_real_escape_string($tools->IsValid_Variable($field_value));
			}
		}

		$fields = substr($fields,0,-2);
		$content = substr($content,0,-2);
		if(($fields != "") && ($content != ""))
		{
			$sql = "INSERT INTO " . $wpdb->prefix . small_ad_table_prefix_AOS . self::$table . " (".$fields.")
				VALUES
				(".$content.")";
			$wpdb->query( $wpdb->prepare( $sql, array() ) );
			$attribute_id = mysql_insert_id($wpdb->dbh);

			if(($attribute_id > 0) && ($attribute_group_id >0)){
				$sql = "INSERT INTO " . $wpdb->prefix . small_ad_table_prefix_AOS . "petiteannonce__groupeattribut_attribut (idattribut,idgroupeattribut,flagvalidgroupeattribut_attribut)
				VALUES
					('".$attribute_id."','".$attribute_group_id."','valid')";
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
	}

	function update_attribut_annonce($values)
	{
		global $wpdb;
		global $tools;
		$sql = "  ";
		$idtoupdate = $attribute_group_id = 0;

		foreach($values as $field_name => $field_value)
		{
			if(($field_value != '') && ($field_name!='idattribut') && ($field_name!='group_attribut'))
			{
				$sql .= " ".$field_name." = '".mysql_real_escape_string($tools->IsValid_Variable($field_value))."', ";
			}
			elseif($field_name == 'idattribut')
			{
				$idtoupdate = mysql_real_escape_string($field_value);
			}
			elseif($field_name == 'group_attribut')
			{
				$attribute_group_id = $tools->IsValid_Variable($field_value);
			}
		}

		$sql = substr($sql,0,-2);
		if(($sql != "") && ($idtoupdate!=0))
		{
			$sql = "UPDATE " . $wpdb->prefix . small_ad_table_prefix_AOS . self::$table . "
				SET ".$sql." WHERE idattribut = '" . $idtoupdate . "' ";
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

			$sql = "UPDATE " . $wpdb->prefix . small_ad_table_prefix_AOS . "petiteannonce__groupeattribut_attribut
				SET idgroupeattribut = '".$attribute_group_id."'
				WHERE idattribut = '".$idtoupdate."' ";
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

	function update_attribut_annonce_status($idtoupdate,$field_to_update,$values)
	{
		global $wpdb;
		$the_act = '';

		if(($idtoupdate != "") && ($field_to_update != "") && ($values != ""))
		{
			$sql =
				"UPDATE " . $wpdb->prefix . small_ad_table_prefix_AOS . self::$table . "
					SET " . $field_to_update . " = '" . $values . "'
					WHERE idattribut IN (".$idtoupdate.") ";
			if( $wpdb->query( ($sql) ))
			{
				$this->error_message = __('La s&eacute;lection a bien &eacute;t&eacute; modifi&eacute;e','annonces');
				$this->class_admin_notice = 'admin_notices_class_ok';

				$the_act = '';
			}
			elseif(mysql_error() != '')
			{
				$this->error_message = __('Erreur lors de la modification de la s&eacute;lection','annonces');
				if(is_admin())$this->error_message .= '<hr/>'.$sql.'<br/>'.mysql_error().'<hr/>';
				$this->class_admin_notice = 'admin_notices_class_notok';

				$the_act = 'add';
			}
		}

		return $the_act;
	}

	function delete_attribut_annonce($id_to_delete)
	{
		global $wpdb;
		$sql = "UPDATE " . $wpdb->prefix . small_ad_table_prefix_AOS . self::$table . " SET flagvalidattribut = 'deleted' WHERE idattribut IN (".$id_to_delete.") ";
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

	function attribute_for_annonce_form()
	{
		$attribute = array();
		$attribute_list = $this->get_attribut_annonce('' , " 'valid' " , 0 , $option = 'nolimit');

		$i = 0;
		foreach($attribute_list as $attribut_key => $attributdefinition )
		{
			$attribute[$i]['labelattribut'] = $attributdefinition->labelattribut;
			$attribute[$i]['nomattribut'] = $attributdefinition->nomattribut;
			$attribute[$i]['type'] = strtolower($attributdefinition->typeattribut);
			$attribute[$i]['idattribut'] = $attributdefinition->idattribut;
			$i++;
		}

		return $attribute;
	}

	function get_attribut_annonce($morequery = '' , $flag = DEFAULT_FLAG_ADMIN_AOS , $actual_page = 0 , $option = '')
	{
		global $wpdb;
		$real_page = $actual_page;if($actual_page!=0)$real_page = $actual_page-1;
		$debut = $real_page * NUMBER_OF_ITEM_PAR_PAGE_ADMIN_AOS;

		$moreflag = "";
		if($flag != "")$moreflag = " AND flagvalidattribut IN (".$flag.") ";

		$TheSelect = "ATTRIBUTE.* , LINK_ATT_GRP.idgroupeattribut , ATT_GRP.nomgroupeattribut";
		if($option == 'count')$TheSelect = "COUNT(ATTRIBUTE.idattribut) ";

		$sql =
			"SELECT ".$TheSelect."
			FROM " . $wpdb->prefix . small_ad_table_prefix_AOS . self::$table . " AS ATTRIBUTE
				INNER JOIN " . $wpdb->prefix . small_ad_table_prefix_AOS . "petiteannonce__groupeattribut_attribut AS LINK_ATT_GRP ON ((LINK_ATT_GRP.idattribut = ATTRIBUTE.idattribut) AND (flagvalidgroupeattribut_attribut != 'deleted'))
				INNER JOIN " . $wpdb->prefix . small_ad_table_prefix_AOS . "petiteannonce__groupeattribut AS ATT_GRP ON ((ATT_GRP.idgroupeattribut = LINK_ATT_GRP.idgroupeattribut))
			WHERE 1 "
				. $moreflag
				. $morequery;
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

	function show_attribut_annonce($attribut_annonce_to_show)
	{
		global $attribute_type_possibilities;
		global $flag_visible_attribut_possibilities;
		global $flag_possibilities;
		$entete =	'<script type="text/javascript" charset="utf-8">
							annoncejquery(document).ready(function() {
							annoncejquery(\'#example\').dataTable({
								"oLanguage": {
									"sUrl": "' .WP_PLUGIN_URL . '/' . ANNONCES_PLUGIN_DIR.'/includes/js/dataTables.french.txt"
								}
							});
						});
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
					<th class="sorting" >'.__('Validit&eacute; de l\'attribut','annonces').'</th>
					<th class="sorting" >'.__('Visibilit&eacute; de l\'attribut','annonces').'</th>
					<th class="sorting" >'.__('Nom de l\'attribut','annonces').'</th>
					<th class="sorting" >'.__('Type de l\'attribut','annonces').'</th>
					<th class="sorting" >'.__('Groupe de l\'attribut','annonces').'</th>
					<th class="sorting" >'.__('Unit&eacute; de mesure','annonces').'</th>
					<th class="sorting" colspan="5" >'.__('Op&eacute;ration','annonces').'</th>
				</tr></thead>';

		if( count($attribut_annonce_to_show) > 0 )
		{
			$output .= '<tbody>';
			foreach($attribut_annonce_to_show as $key => $attribut_annonce_content)
			{
				$output .=
				'<tr>';
		if(is_admin())$output .= '
					<td class="listing_header" >'.$attribut_annonce_content->idattribut.'</td>';
		$output .= '
					<td>'.$flag_possibilities[$attribut_annonce_content->flagvalidattribut].'</td>
					<td>'.$flag_visible_attribut_possibilities[$attribut_annonce_content->flagvisibleattribut].'</td>
					<td>'.$attribut_annonce_content->nomattribut.'</td>
					<td>'.$attribute_type_possibilities[strtolower($attribut_annonce_content->typeattribut)].'</td>
					<td>'.$attribut_annonce_content->nomgroupeattribut.'</td>
					<td>'.$attribut_annonce_content->measureunit.'</td>
					<td><img src="'.WP_PLUGIN_URL.'/'.ANNONCES_PLUGIN_DIR.'/medias/images/b_edit.png" alt="edit_pass" class="button_img"  onclick="javascript:document.getElementById(\'act\').value=\'edit\';document.getElementById(\'id_to_treat\').value=\''.$attribut_annonce_content->idattribut.'\';document.forms.treat_attribut.submit();"/></td>
					<td><img src="'.WP_PLUGIN_URL.'/'.ANNONCES_PLUGIN_DIR.'/medias/images/b_drop.png" alt="drop_pass" class="button_img" onclick="javascript:document.getElementById(\'act\').value=\'delete\';document.getElementById(\'id_to_treat\').value=\''.$attribut_annonce_content->idattribut.'\';var check = confirm(\'&Ecirc;tes vous s&ucirc;r de vouloir supprimer cet &eacute;l&eacute;ment ? \');if(check == true){document.forms.treat_attribut.submit();}" /></td>
					<td><input class="attribut_annonce_content" type="checkbox" name="attribut['.$attribut_annonce_content->idattribut.']" id="attribute_'.$attribut_annonce_content->idattribut.'" value="'.$attribut_annonce_content->idattribut.'" /></td>
				</tr>';
			}
		}
		else
		{
			$output .= '</tbody>';
			$output .= '<tr><td colspan="20" class="no_result" >Aucun r&eacute;sultat</td></tr>';
		}

		$output .= '</tbody></table></div></div></div>';

		return $entete.$output;
	}

}