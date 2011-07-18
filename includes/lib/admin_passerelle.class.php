<?php
/***************************************************
*Date: 01/10/2009      File:admin.class.php    		 *
*Author: Eoxia							                       *
*Comment:                                          *
*	GENERATE MENU FOR ADMIN PANEL                    *
***************************************************/

/*												*/
/*	PASSERELLE FORM	CLASS	*/
/*												*/
class passerelle_form extends sfForm
{
	public function configure()
	{
		global $flag_possibilities;
		$this->setWidgets(array(
		  'flagvalidpasserelle' 	=> new sfWidgetFormSelect(array(
				'choices' => $flag_possibilities,
				'label' => __('Validit&eacute;','annonces'),
		  )),
		  'nompasserelle'      		=> new sfWidgetFormInput(array(
				'label' => __('Nom de la passerelle','annonces'),
		  )),
		  'nomexport'      				=> new sfWidgetFormInput(array(
				'label' => __('Nom export','annonces'),
		  )),
		  'host'      						=> new sfWidgetFormInput(array(
				'label' => __('H&ocirc;te de la passerelle','annonces'),
		  )),
		  'user'     						 	=> new sfWidgetFormInput(array(
				'label' => __('Identifiant','annonces'),
		  )),
		  'pass'      						=> new sfWidgetFormInputPassword(array(
				'label' => __('Mot de passe','annonces'),
		  )),
		  'separateurtexte'      	=> new sfWidgetFormInput(array(
				'label' => __('S&eacute;parateur de texte','annonces'),
		  )),
		  'separateurchamp'      	=> new sfWidgetFormInput(array(
				'label' => __('S&eacute;parateur de champ','annonces'),
		  )),
		  'separateurligne'      	=> new sfWidgetFormInput(array(
				'label' => __('S&eacute;parateur de ligne','annonces'),
		  )),
		  'structure' 			=> new sfWidgetFormTextarea(array(
				'label' => __('Structure de l\'export','annonces'),
		  )),
		  'idpasserelle' 				=> new sfWidgetFormInputHidden(),
		));
		$this->widgetSchema->setNameFormat('passerelle[%s]');
	 
		$this->setValidators(array(
		  'flagvalidpasserelle' => new sfValidatorChoice(array('choices' => array_keys($flag_possibilities))),
		  'nompasserelle'       => new sfValidatorString(array('max_length' => 255,'required' => true)),
		  'nomexport'    				=> new sfValidatorString(array('max_length' => 255,'required' => true)),
		  'host'  							=> new sfValidatorString(array('max_length' => 255,'required' => true)),
		  'user'        				=> new sfValidatorString(array('max_length' => 255,'required' => true)),
		  'pass'     						=> new sfValidatorString(array('max_length' => 255,'required' => false)),
		  'structure'      			=> new sfValidatorString(array('required' => true)),
		  'separateurtexte' 		=> new sfValidatorString(array('max_length' => 2,'required' => true)),
		  'separateurchamp'  		=> new sfValidatorString(array('max_length' => 10,'required' => true)),
		  'separateurligne'     => new sfValidatorString(array('max_length' => 10,'required' => true)),
		  'idpasserelle'     		=> new sfValidatorString(array('required' => false)),
		));
	}
}

/*												*/
/*	PASSERELLE CLASS			*/
/*												*/
class passerelle
{

	protected static $table = "petiteannonce__passerelle";
	public $error_message = '';
	public $class_admin_notice = '';

	function create_passerelle($values)
	{
		global $wpdb;
		global $tools;
		$fields = $content = "  ";
		foreach($values as $field_name => $field_value)
		{
			if(($field_value != '') && (substr($field_name,0,9)!='attribut_'))
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
				$this->error_message = __('Erreur lors de l#146;insertion','annonces');
				if(is_admin())$this->error_message .= '<hr/>'.$sql.'<br/>'.mysql_error().'<hr/>';
				$this->class_admin_notice = 'admin_notices_class_notok';
			}
		}
	}

	function update_passerelle($values)
	{
		global $wpdb;
		global $tools;
		$sql = "  ";
		$idtoupdate = 0;

		foreach($values as $field_name => $field_value)
		{
			if(($field_value != '') && ($field_name!='idpasserelle') && (substr($field_name,0,9)!='attribut_'))
			{
				$sql .= " ".$field_name." = '".mysql_real_escape_string($tools->IsValid_Variable($field_value))."', ";
			}
			elseif($field_name == 'idpasserelle')$idtoupdate = mysql_real_escape_string($field_value);
		}

		$sql = substr($sql,0,-2);
		if(($sql != "") && ($idtoupdate!=0))
		{
			$sql = "UPDATE " . $wpdb->prefix . small_ad_table_prefix_AOS . self::$table . " 
				SET ".$sql." WHERE idpasserelle = '" . $idtoupdate . "' ";
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

	function delete_passerelle($id_to_delete)
	{
		global $wpdb;
		$sql = "UPDATE " . $wpdb->prefix . small_ad_table_prefix_AOS . self::$table . " SET flagvalidpasserelle = 'deleted' WHERE idpasserelle = '".$id_to_delete."' ";
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

	function get_passerelle($morequery = '', $flag = DEFAULT_FLAG_ADMIN_AOS , $actual_page = 0 , $option = '')
	{
		global $wpdb;
		$real_page = $actual_page;if($actual_page!=0)$real_page = $actual_page-1;
		$debut = $real_page * NUMBER_OF_ITEM_PAR_PAGE_ADMIN_AOS;

		$moreflag = "";
		if($flag != "")$moreflag = " AND flagvalidpasserelle IN (".$flag.") ";

		$TheSelect = " * ";
		if($option == 'count')$TheSelect = "COUNT(idpasserelle) ";

		$sql = 
			"SELECT ".$TheSelect."
			FROM " . $wpdb->prefix . small_ad_table_prefix_AOS . self::$table . " 
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

	function show_passerelle($passerelle_to_show)
	{
		$entete = '
				<script type="text/javascript" charset="utf-8">
						annoncejquery(document).ready(function() {
						annoncejquery(\'#example\').dataTable({
								"oLanguage": {
									"sUrl": "' .WP_PLUGIN_URL . '/' . ANNONCES_PLUGIN_DIR . '/includes/js/dataTables.french.txt"
								}
							});
					} );
				</script>';
		$output = 
			'<div id="container">
					<div id="demo">
						<div id="example_wrapper" class="dataTables_wrapper">
							<table class="display" id="example" border="0" cellpadding="0" cellspacing="0">
								<thead><tr class="titre_listing">';
		if(is_admin())$output .= '
					<th class="listing_header" >ID</th>';
		$output .= '  
					<th class="listing_header" >'.__('Nom de la passerelle','annonces').'</th>
					<th class="listing_header" >'.__('Nom de l\'export','annonces').'</th>
					<th class="listing_header" >'.__('H&ocirc;te','annonces').'</th>
					<th class="listing_header" colspan="5" >'.__('Op&eacute;ration','annonces').'</th>
				</tr></thead>';

		if( count($passerelle_to_show) > 0 )
		{
			$output .= '<tbody>';
			foreach($passerelle_to_show as $key => $passerelle_content)
			{
				$output .= 
				'<tr>';
		if(is_admin())$output .= '
					<td class="listing_header" >'.$passerelle_content->idpasserelle.'</td>';
		$output .= '
					<td>'.$passerelle_content->nompasserelle.'</td>
					<td>'.$passerelle_content->nomexport.'</td>
					<td>'.$passerelle_content->host.'</td>
					<td><img src="'.WP_PLUGIN_URL.'/'.ANNONCES_PLUGIN_DIR.'/medias/images/b_edit.png" alt="edit_pass" class="button_img"  onclick="javascript:document.getElementById(\'act\').value=\'edit\';document.getElementById(\'id_to_treat\').value=\''.$passerelle_content->idpasserelle.'\';document.forms.treat_passerelle.submit();"/></td>
					<td><img src="'.WP_PLUGIN_URL.'/'.ANNONCES_PLUGIN_DIR.'/medias/images/b_drop.png" alt="drop_pass" class="button_img" onclick="javascript:document.getElementById(\'act\').value=\'delete\';document.getElementById(\'id_to_treat\').value=\''.$passerelle_content->idpasserelle.'\';var check = confirm(\'&Ecirc;tes vous s&ucirc;r de vouloir supprimer cet &eacute;l&eacute;ment ? \');if(check == true){document.forms.treat_passerelle.submit();}" /></td>
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