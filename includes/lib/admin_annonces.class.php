<?php
/***************************************************
*Date: 01/10/2009   File:admin_annonces.class.php  *
*Author: Eoxia							           *
*Comment:                                          *
*	GENERATE UTILITIES FOR ANNONCES                *
***************************************************/
class annonce_form extends sfForm
{

	public function configure()
	{
		global $attribut_annonce;

		//	FIELD TYPE DEFINITION IN CONFIGS.PHP
		global $field_select_yes_no;
		global $fields_validator_string_1;
		global $fields_validator_string_5;
		global $fields_validator_string_6;
		global $fields_validator_string_10;
		global $fields_validator_string_15;
		global $fields_validator_string_30;
		global $fields_validator_string_32;
		global $fields_validator_string_50;
		global $fields_validator_string_64;
		global $fields_validator_string_128;
		global $fields_validator_string_256;
		global $fields_validator_string_4000;

		global $flag_possibilities;
		global $attribute_group_possibilities;
		global $flag_a_exporter_possibilities;
		global $select_yes_no;
		global $select_special_attribut;

		$this->setWidgets(array(
		  'flagvalidpetiteannonce' 	=> new sfWidgetFormSelect(array(
				'choices' => $flag_possibilities,
				'label' => __('Validit&eacute;','annonces'),
		  )),
		  'idgroupeattribut' 				=> new sfWidgetFormSelect(array(
				'choices' => $attribute_group_possibilities,
				'label' => __('Cat&eacute;gorie de l\'annonce','annonces'),
		  )),
		  'aexporter' 							=> new sfWidgetFormSelect(array(
				'choices' => $flag_a_exporter_possibilities,
				'label' => __('Exportable','annonces'),
		  )),
		  'titre'      							=> new sfWidgetFormInput(array(
				'label' => __('Titre','annonces'),
		  )),
		  'urlannonce'      							=> new sfWidgetFormInput(array(
				'label' => __('Url personnalis&eacute;e','annonces'),
		  )),
		  'referenceagencedubien'   => new sfWidgetFormInput(array(
				'label' => __('R&eacute;f&eacute;rence','annonces'),
		  )),
		  'idpetiteannonce' 				=> new sfWidgetFormInputHidden(),
		  'autoinsert' 							=> new sfWidgetFormInputHidden(),
		  'autolastmodif' 					=> new sfWidgetFormInputHidden(),
		));
		$this->widgetSchema->setNameFormat('annonce_form[%s]');

		$this->setDefault('autoinsert', date('Y-m-d H:i:s'));
		$this->setDefault('autolastmodif', date('Y-m-d H:i:s'));

		$this->setValidators(array(
		  'flagvalidpetiteannonce' => new sfValidatorChoice(array('choices' => array_keys($flag_possibilities))),
		  'idgroupeattribut' 			 => new sfValidatorChoice(array('choices' => array_keys($attribute_group_possibilities))),
		  'aexporter' 			 			 => new sfValidatorChoice(array('choices' => array_keys($flag_a_exporter_possibilities))),
		  'titre'						       => new sfValidatorString(array('max_length' => 64,'required' => true)),
		  'urlannonce'						       => new sfValidatorString(array('max_length' => 150,'required' => false)),
		  'referenceagencedubien'  => new sfValidatorString(array('max_length' => 20,'required' => true)),
		  'idpetiteannonce'     	 => new sfValidatorString(array('required' => false)),
		  'autoinsert'     	 			 => new sfValidatorString(array('required' => false)),
		  'autolastmodif'     	 	 => new sfValidatorString(array('required' => false)),

		  'unique_token'     	 	 => new sfValidatorString(array('required' => false)),

			'ville'  => new sfValidatorString(array('max_length' => 255,'required' => false)),
		  'adresse'  => new sfValidatorString(array('max_length' => 255,'required' => false)),
		  'cp'  => new sfValidatorString(array('max_length' => 11,'required' => false)),
		  'departement'  => new sfValidatorString(array('max_length' => 255,'required' => false)),
		  'region'  => new sfValidatorString(array('max_length' => 255,'required' => false)),
		  'pays'  => new sfValidatorString(array('max_length' => 255,'required' => false)),
		  'latitude'  => new sfValidatorNumber(array('required' => false)),
		  'longitude'  => new sfValidatorNumber(array('required' => false)),

		));

		foreach($attribut_annonce->attribute_for_annonce_form() as $attribut_key => $attributdefinition )
		{
			//	IF TYPE IS DEFINED AS YES OR NOT IN ARRAY DEFINE ON TOP
			if(in_array($attributdefinition['labelattribut'],$field_select_yes_no))
			{
				$this->widgetSchema[$attributdefinition['labelattribut']] = new sfWidgetFormSelect(array(
				'choices' => $select_yes_no,
				'label' => $attributdefinition['nomattribut'],
		  ));
				$this->validatorSchema[$attributdefinition['labelattribut']] = new sfValidatorChoice(array('choices' => array_keys($select_yes_no)));
			}

			//	IF FIELD LABEL IN ARRAY AF SPECIAL FIELDS DEFINE IN CONFIGS.PHP
			elseif(array_key_exists($attributdefinition['labelattribut'],$select_special_attribut))
			{
				$this->widgetSchema[$attributdefinition['labelattribut']] = new sfWidgetFormSelect(array(
				'choices' => $select_special_attribut[$attributdefinition['labelattribut']],
				'label' => $attributdefinition['nomattribut'],
		  ));
				$this->validatorSchema[$attributdefinition['labelattribut']] = new sfValidatorChoice(array('choices' => array_keys($select_special_attribut[$attributdefinition['labelattribut']])));
			}

			//	DEFAULT TYPE INPUT STRING
			else
			{
				if((in_array($attributdefinition['labelattribut'],$fields_validator_string_4000)) || (in_array($attributdefinition['labelattribut'],$fields_validator_string_256)))
				{
					$this->widgetSchema[$attributdefinition['labelattribut']] = new sfWidgetFormTextarea(array('label' => $attributdefinition['nomattribut']));
				}
				else
				{
					$this->widgetSchema[$attributdefinition['labelattribut']] = new sfWidgetFormInput(array('label' => $attributdefinition['nomattribut']));
				}

				//	DEFINE THE VALIDATOR NEEDED FOR FIELD TYPE
				if(strtolower($attributdefinition['type']) == 'dec')
				{
					$this->validatorSchema[$attributdefinition['labelattribut']] = new sfValidatorNumber(array('required' => false));
				}
				elseif(strtolower($attributdefinition['type']) == 'int')
				{
					$this->validatorSchema[$attributdefinition['labelattribut']] = new sfValidatorInteger(array('required' => false));
				}
				elseif(strtolower($attributdefinition['type']) == 'date')
				{
					$this->validatorSchema[$attributdefinition['labelattribut']] = new sfValidatorDate(array('required' => false));
				}
				elseif(in_array($attributdefinition['labelattribut'],$fields_validator_string_1))
				{
					$this->validatorSchema[$attributdefinition['labelattribut']] = new sfValidatorString(array('max_length' => 1, 'required' => false));
				}
				elseif(in_array($attributdefinition['labelattribut'],$fields_validator_string_5))
				{
					$this->validatorSchema[$attributdefinition['labelattribut']] = new sfValidatorString(array('max_length' => 5, 'required' => false));
				}
				elseif(in_array($attributdefinition['labelattribut'],$fields_validator_string_6))
				{
					$this->validatorSchema[$attributdefinition['labelattribut']] = new sfValidatorString(array('max_length' => 6, 'required' => false));
				}
				elseif(in_array($attributdefinition['labelattribut'],$fields_validator_string_10))
				{
					$this->validatorSchema[$attributdefinition['labelattribut']] = new sfValidatorString(array('max_length' => 10, 'required' => false));
				}
				elseif(in_array($attributdefinition['labelattribut'],$fields_validator_string_15))
				{
					$this->validatorSchema[$attributdefinition['labelattribut']] = new sfValidatorString(array('max_length' => 15, 'required' => false));
				}
				elseif(in_array($attributdefinition['labelattribut'],$fields_validator_string_30))
				{
					$this->validatorSchema[$attributdefinition['labelattribut']] = new sfValidatorString(array('max_length' => 30, 'required' => false));
				}
				elseif(in_array($attributdefinition['labelattribut'],$fields_validator_string_32))
				{
					$this->validatorSchema[$attributdefinition['labelattribut']] = new sfValidatorString(array('max_length' => 32, 'required' => false));
				}
				elseif(in_array($attributdefinition['labelattribut'],$fields_validator_string_50))
				{
					$this->validatorSchema[$attributdefinition['labelattribut']] = new sfValidatorString(array('max_length' => 50, 'required' => false));
				}
				elseif(in_array($attributdefinition['labelattribut'],$fields_validator_string_64))
				{
					$this->validatorSchema[$attributdefinition['labelattribut']] = new sfValidatorString(array('max_length' => 64, 'required' => false));
				}
				elseif(in_array($attributdefinition['labelattribut'],$fields_validator_string_128))
				{
					$this->validatorSchema[$attributdefinition['labelattribut']] = new sfValidatorString(array('max_length' => 128, 'required' => false));
				}
				elseif(in_array($attributdefinition['labelattribut'],$fields_validator_string_256))
				{
					$this->validatorSchema[$attributdefinition['labelattribut']] = new sfValidatorString(array('max_length' => 256, 'required' => false));
				}
				elseif(in_array($attributdefinition['labelattribut'],$fields_validator_string_4000))
				{
					$this->validatorSchema[$attributdefinition['labelattribut']] = new sfValidatorString(array('max_length' => 4000, 'required' => false));
				}
				else
				{
					$this->validatorSchema[$attributdefinition['labelattribut']] = new sfValidatorString(array('required' => false));
				}
			}
		}
	}

}

class annonce
{

	private static $table = 'petiteannonce';
	public $error_message = '';
	public $class_admin_notice = '';

	function create_annonce($values)
	{
		global $wpdb;
		global $tools;
		global $attribut_annonce;
		global $field_name_value_by_type;
		global $table_name_by_type;
		global $geolocalisation_field;

		$fields = $content = "  ";
		$attribute_group_id = $attribute_id = $picture_token_to_update = 0;
		$sql_att = $geoloc_value = array();
		$eav_array = array();
		$the_act = "";

		$valid_attribute = $attribut_annonce->attribute_for_annonce_form();
		$attribute_array = array();
		foreach($valid_attribute as $key => $attribute_definition)
		{
			$attribute_array[$attribute_definition['labelattribut']]['type'] = $attribute_definition['type'];
			$attribute_array[$attribute_definition['labelattribut']]['id'] = $attribute_definition['idattribut'];
		}

		foreach($values as $field_name => $field_value)
		{
			if(($field_value != '') && !array_key_exists($field_name,$attribute_array) && !in_array($field_name,$geolocalisation_field) && ( $field_name != 'unique_token'))
			{
				$fields .= " ".$field_name.", ";
				$content .= " '".mysql_real_escape_string($tools->IsValid_Variable($field_value))."', ";
			}
			elseif(array_key_exists($field_name,$attribute_array) && !in_array($field_name,$geolocalisation_field) && ( $field_name != 'unique_token'))
			{
				$eav_array[count($eav_array)] = $field_name;
				$sql_att[] =
						"INSERT INTO " . $table_name_by_type[$attribute_array[$field_name]['type']] . "
							(idpetiteannonce, idattribut, " . $field_name_value_by_type[$attribute_array[$field_name]['type']] . ")
						VALUES
							('#IDPETITEANNONCE#', '" . $attribute_array[$field_name]['id'] . "', '" . mysql_real_escape_string($tools->IsValid_Variable($field_value)) . "') ";
			}
			elseif(in_array($field_name,$geolocalisation_field) && ( $field_name != 'unique_token'))
			{
				$geoloc_value[$field_name] = $field_value;
			}
			elseif( $field_name == 'unique_token')
			{
				$picture_token_to_update = $field_value;
			}
		}

		$fields = substr($fields,0,-2);
		$content = substr($content,0,-2);
		if(($fields != "") && ($content != ""))
		{
			$sql = $wpdb->prepare("SELECT idpetiteannonce+1 FROM " . PREFIXE_ANNONCES . " WHERE idpetiteannonce = (SELECT MAX(idpetiteannonce) FROM  " . PREFIXE_ANNONCES . " )", array() );
			$reqbudget = mysql_query($sql) or die(mysql_error() . '_' . __FILE__);
			while($data = mysql_fetch_array($reqbudget))
			{
				$idAnnonce = $data[0];
			}

			$sql = "INSERT INTO " . $wpdb->prefix . small_ad_table_prefix_AOS . self::$table . " (".$fields.")
				VALUES
				(".$content.")";
			if($wpdb->query( ($sql) ))
			{
				$idpetiteannonce = mysql_insert_id($wpdb->dbh);
				$this->error_message = __('Cr&eacute;ation effectu&eacute; avec succ&eacute;s','annonces');
				$this->class_admin_notice = 'admin_notices_class_ok';

				//	ADD PHOTO
				if($picture_token_to_update != 0)
				{
					$sql =
						"UPDATE " . $wpdb->prefix . small_ad_table_prefix_AOS . "petiteannonce__photos
							SET idpetiteannonce = '" . mysql_real_escape_string($idpetiteannonce) . "'
							WHERE token = '" . mysql_real_escape_string($picture_token_to_update) . "' AND idpetiteannonce = '-1'";
					if( !$wpdb->query( $sql ) && (mysql_error() != ''))
					{
						$this->error_message .= '<br/>'.__('Erreur de g&eacute;olocalisation','annonces');
						if(is_admin())$this->error_message .= '<hr/>'.$sql.'<br/>'.mysql_error().'<hr/>';
						$this->class_admin_notice = 'admin_notices_class_notok';
					}
				}

				//	CREATE ATTRIBUTE VALUES FOR SMALL AD
				if(isset($sql_att) && (count($sql_att) > 0))
				{
					foreach($sql_att as $key => $query_to_do)
					{
						$query_to_do = str_replace('#IDPETITEANNONCE#',$idpetiteannonce,$query_to_do);
						if( !$wpdb->query( ($query_to_do) ) && (mysql_error() != ''))
						{
							$this->error_message .= __('Erreur lors de la modification','annonces');
							if(is_admin())$this->error_message .= '<hr/>'.$query_to_do.'<br/>'.mysql_error().'<hr/>';
							$this->class_admin_notice = 'admin_notices_class_notok';
						}
					}
				}

				//	CREATE GEOLOC ENTRY FOR SMALL AD
				if(count($geoloc_value) > 0)
				{
					$this->setGeoloc($geoloc_value,$idpetiteannonce);
				}

				$the_act = $idpetiteannonce;
			}
			else
			{
				$this->error_message = __('Erreur lors de l\'insertion','annonces');
				if(is_admin())$this->error_message .= '<hr/>'.$sql.'<br/>'.mysql_error().'<hr/>';
				$this->class_admin_notice = 'admin_notices_class_notok';

				$the_act = 'add';
			}
		}

		return $the_act;
	}

	function clearDir($chemin)
	{
        // v�rifie si le nom du repertoire contient "/" � la fin
        if ($chemin[strlen($chemin)-1] != '/') // place le pointeur en fin d'url
           { $chemin .= '/'; } // rajoute '/'

        if (is_dir($chemin)) {
             $sq = opendir($chemin); // lecture
             while ($f = readdir($sq)) {
             if ($f != '.' && $f != '..')
             {
             $fichier = $chemin.$f; // chemin fichier
             if (is_dir($fichier))
             {sup_repertoire($fichier);} // rapel la fonction de mani�re r�cursive
             else
             {unlink($fichier);} // sup le fichier
             }
                }
                closedir($sq);
                rmdir($chemin); // sup le r�pertoire
                             }
        else {
                unlink($chemin);  // sup le fichier
             }
    }

	function update_annonce($values)
	{
		global $wpdb;
		global $tools;
		global $attribut_annonce;
		global $field_name_value_by_type;
		global $geolocalisation_field;

		$the_act = $flag = '';
		$sql = "  ";
		$sql_att = $geoloc_value = array();
		$eav_array = array();
		$idtoupdate = $attribute_group_id = $picture_token_to_update = 0;

		$valid_attribute = $attribut_annonce->attribute_for_annonce_form();
		$attribute_array = array();
		foreach($valid_attribute as $key => $attribute_definition)
		{
			$attribute_array[$attribute_definition['labelattribut']]['type'] = strtolower($attribute_definition['type']);
			$attribute_array[$attribute_definition['labelattribut']]['id'] = $attribute_definition['idattribut'];
		}

		foreach($values as $field_name => $field_value)
		{
			if(($field_value != '') && ($field_name!='idpetiteannonce') && ($field_name!='unique_token') && !array_key_exists($field_name,$attribute_array) && !in_array($field_name,$geolocalisation_field))
			{
				if ($field_name == 'urlannonce')
				{
					$field_value = stripslashes($field_value);
					$field_value = annonces_options::slugify_noaccent($field_value);
					$field_value = trim($field_value);
					// $field_value = str_replace(' ', '-', $field_value);
					// $field_value = str_replace('\'', '-', $field_value);
					// $field_value = str_replace('"', '-', $field_value);
					// $field_value = str_replace('\\', '-', $field_value);
					// $field_value = str_replace('?', '', $field_value);
					// $field_value = str_replace('!', '', $field_value);
					// $field_value = str_replace('@', '', $field_value);
					$field_value = preg_replace('([^0-9a-zA-Z-_])', '-', $field_value);

					$sql .= " ".$field_name." = '".mysql_real_escape_string($tools->IsValid_Variable($field_value))."', ";
				}
				else
				{
					$sql .= " ".$field_name." = '".mysql_real_escape_string($tools->IsValid_Variable($field_value))."', ";
				}
			}
			elseif($field_name == 'idpetiteannonce')
			{
				$idtoupdate = mysql_real_escape_string($field_value);
			}
			elseif(array_key_exists($field_name,$attribute_array) && !in_array($field_name,$geolocalisation_field) && ($field_name!='unique_token'))
			{
				$eav_array[count($eav_array)] = $field_name;
				$sql_att[$attribute_array[$field_name]['type']][$attribute_array[$field_name]['id']]['name'] = $field_name_value_by_type[$attribute_array[$field_name]['type']];
				$sql_att[$attribute_array[$field_name]['type']][$attribute_array[$field_name]['id']]['value'] = $field_value;
			}
			elseif(in_array($field_name,$geolocalisation_field) && ($field_name!='unique_token'))
			{
				$geoloc_value[$field_name] = $field_value;
			}
			elseif( $field_name == 'unique_token')
			{
				$picture_token_to_update = $field_value;
			}
		}

		$sql = substr($sql,0,-2);
		if(($sql != "") && ($idtoupdate!=0))
		{
			$sql = "UPDATE " . $wpdb->prefix . small_ad_table_prefix_AOS . self::$table . "
				SET ".$sql." WHERE idpetiteannonce = '" . mysql_real_escape_string($idtoupdate) . "' ";
			if( $wpdb->query( ($sql) ))
			{
				$this->error_message = __('Modification effectu&eacute;e avec succ&eacute;s','annonces');
				$this->class_admin_notice = 'admin_notices_class_ok';

				$the_act = '';
			}
			elseif(mysql_error() != '')
			{
				$this->error_message = __('Erreur lors de la modification','annonces');
				if(is_admin())$this->error_message .= '<hr/>'.$sql.'<br/>'.mysql_error().'<hr/>';
				$this->class_admin_notice = 'admin_notices_class_notok';

				$the_act = 'add';
			}

			//	ADD PHOTO
			if($picture_token_to_update != 0)
			{
				$sql =
					"UPDATE " . $wpdb->prefix . small_ad_table_prefix_AOS . "petiteannonce__photos
						SET idpetiteannonce = '" . mysql_real_escape_string($idtoupdate) . "'
						WHERE token = '" . mysql_real_escape_string($picture_token_to_update) . "' AND idpetiteannonce = '-1'";
				if( !$wpdb->query( $sql ) && (mysql_error() != ''))
				{
					$this->error_message .= '<br/>'.__('Erreur de g&eacute;olocalisation','annonces');
					if(is_admin())$this->error_message .= '<hr/>'.$sql.'<br/>'.mysql_error().'<hr/>';
					$this->class_admin_notice = 'admin_notices_class_notok';
				}
			}

			//	CREATE ATTRIBUTE VALUES FOR SMALL AD
			if(isset($sql_att) && (count($sql_att) > 0))
			{
				$this->setAttribut($sql_att,$idtoupdate);
			}

			//	CREATE GEOLOC ENTRY FOR SMALL AD
			if(count($geoloc_value) > 0)
			{
				$this->setGeoloc($geoloc_value,$idtoupdate);
			}
		}

		return $the_act;
	}

	function update_annonce_status($idtoupdate,$field_to_update,$values)
	{
		global $wpdb;
		$the_act = '';

		if(($idtoupdate != "") && ($field_to_update != "") && ($values != ""))
		{
			$sql =
				"UPDATE " . $wpdb->prefix . small_ad_table_prefix_AOS . self::$table . "
					SET " . $field_to_update . " = '" . $values . "'
					WHERE idpetiteannonce IN (".$idtoupdate.") ";
			if( $wpdb->query( ($sql) ))
			{
				$this->error_message = __('La s&eacute;lection a bien &eacute;t&eacute; modifi&eacute;e','annonces');
				$this->class_admin_notice = 'admin_notices_class_ok';
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

	function delete_annonce($id_to_delete)
	{
		global $wpdb;
		$sql =
			"UPDATE " . $wpdb->prefix . small_ad_table_prefix_AOS . self::$table . "
				SET flagvalidpetiteannonce = 'deleted'
				WHERE idpetiteannonce IN (".$id_to_delete.") ";
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
	function export_annonce($id_to_export) {
		global $wpdb;
		global $current_user;

		$eav_value = new Eav();

		foreach ($id_to_export as $id_annonce) {

			$titre_annonce = $eav_value->get_titre($id_annonce);
			$autoinsert = $eav_value->get_autoinsert($id_annonce);
			$autolastmodif = $eav_value->get_autolastmodif($id_annonce);
			$description = $eav_value->getDescription(null, 'valid', null, $id_annonce);
			$attributs = $eav_value->getAnnoncesAttributs(null, 'valid', null, $id_annonce, 'oui');

			$description = (array)$description;

			$description[0] = (array)$description[0];

			if ($description[0]['valueattributtextlong'] == 0) {
				$description_value = $description[0]['valueattributtextcourt'];
			}
			else {
				$description_value = $description[0]['valueattributtextlong'];
			}
			$trans = array(" " => "-", "�" => "e", "�" => "e", "�" => "c", "�" => "a", "�" => "a", "�" => "e");
			$name_annonce = strtolower(strtr(trim($titre_annonce, "/'"), $trans));

			$post = array(
			  'comment_status' => 'open', // 'closed' means no comments.
			  'ping_status' => 'open',  // 'closed' means pingbacks or trackbacks turned off
			  'pinged' => '', //?
			  'post_author' => $current_user->ID, //The user ID number of the author.
			  'post_content' => $description_value, //The full text of the post.
			  'post_date' => $autoinsert, //The time post was made.
			  'post_date_gmt' => $autoinsert, //The time post was made, in GMT.
			  'post_modified' => $autolastmodif,
			  'post_name' => $name_annonce, // The name (slug) for your post
			  'post_status' => 'publish', //Set the status of the new post.
			  'post_title' => $titre_annonce, //The title of your post.
			  'post_type' => WPSHOP_NEWTYPE_IDENTIFIER_PRODUCT //You may want to insert a regular post, page, link, a menu item or some custom post type
			);
			$last_post = wp_insert_post( $post );
			$this->error_message = __('Exportation effectu&eacute; avec succes','annonces');
			$this->class_admin_notice = 'admin_notices_class_ok';

			foreach ($attributs as $attribut) {
				$attribut = (array)$attribut;
				$myattribute = $wpdb->get_row("SELECT * FROM " . WPSHOP_DBT_ATTRIBUTE . " WHERE code = '" . $attribut['labelattribut'] . "'");
				if ( !empty($myattribute) ) {
					if ($myattribute->frontend_input == 'select') {
						switch ($attribut['typeattribut']) {
							case 'CHAR' :
								$myoption_value = $attribut['valueattributchar'];
							break;

							case 'DEC' :
								$myoption_value = $attribut['valueattributdec'];
							break;

							case 'INT' :
								$myoption_value = $attribut['valueattributint'];
							break;

							case 'TEXT' :
								$myoption_value = $attribut['valueattributtextcourt'];
							break;

							case 'DATE' :
								$myoption_value = $attribut['valueattributdate'];
							break;
						}

						$myoption_attribute = $wpdb->get_row("SELECT * FROM " . WPSHOP_DBT_ATTRIBUTE_VALUE_OPTIONS . " WHERE attribute_id = '" . $myattribute->id . "' AND ((value = '" . $myoption_value ."')||(label = '" . $myoption_value ."'))");
						$wpdb->insert(WPSHOP_DBT_ATTRIBUTE_VALUES_INTEGER, array('entity_type_id' => 1,  'attribute_id' => $myattribute->id, 'entity_id' => $last_post, 'unit_id' => 0, 'user_id' => $current_user->ID, 'creation_date_value' => current_time('mysql', 0), 'language' => 'fr_FR', 'value' => $myoption_attribute->id));
					}
					else {
						switch ($attribut['typeattribut']) {
							case 'CHAR' :
								$wpdb->insert(WPSHOP_DBT_ATTRIBUTE_VALUES_VARCHAR, array('entity_type_id' => 1,  'attribute_id' => $myattribute->id, 'entity_id' => $last_post, 'unit_id' => $myattribute->_default_unit, 'user_id' => $current_user->ID, 'creation_date_value' => current_time('mysql', 0), 'language' => 'fr_FR', 'value' => $attribut['valueattributchar']));
							break;

							case 'DEC' :
								$wpdb->insert(WPSHOP_DBT_ATTRIBUTE_VALUES_DECIMAL, array('entity_type_id' => 1,  'attribute_id' => $myattribute->id, 'entity_id' => $last_post, 'unit_id' => $myattribute->_default_unit, 'user_id' => $current_user->ID, 'creation_date_value' => current_time('mysql', 0), 'language' => 'fr_FR', 'value' => $attribut['valueattributdec']));
							break;

							case 'INT' :
								$wpdb->insert(WPSHOP_DBT_ATTRIBUTE_VALUES_INTEGER, array('entity_type_id' => 1,  'attribute_id' => $myattribute->id, 'entity_id' => $last_post, 'unit_id' => $myattribute->_default_unit, 'user_id' => $current_user->ID, 'creation_date_value' => current_time('mysql', 0), 'language' => 'fr_FR', 'value' => $attribut['valueattributint']));
							break;

							case 'TEXT' :
								if ($attribut['valueattributtextlong'] == 0) {
									$wpdb->insert(WPSHOP_DBT_ATTRIBUTE_VALUES_TEXT, array('entity_type_id' => 1,  'attribute_id' => $myattribute->id, 'entity_id' => $last_post, 'unit_id' => $myattribute->_default_unit, 'user_id' => $current_user->ID, 'creation_date_value' => current_time('mysql', 0), 'language' => 'fr_FR', 'value' => $attribut['valueattributtextcourt']));
								}
								else {
									$wpdb->insert(WPSHOP_DBT_ATTRIBUTE_VALUES_TEXT, array('entity_type_id' => 1,  'attribute_id' => $myattribute->id, 'entity_id' => $last_post, 'unit_id' => $myattribute->_default_unit, 'user_id' => $current_user->ID, 'creation_date_value' => current_time('mysql', 0), 'language' => 'fr_FR', 'value' => $attribut['valueattributtextlong']));
								}
							break;

							case 'DATE' :
								$wpdb->insert(WPSHOP_DBT_ATTRIBUTE_VALUES_DATETIME, array('entity_type_id' => 1,  'attribute_id' => $myattribute->id, 'entity_id' => $last_post, 'unit_id' => $myattribute->_default_unit, 'user_id' => $current_user->ID, 'creation_date_value' => current_time('mysql', 0), 'language' => 'fr_FR', 'value' => $attribut['valueattributdate']));
							break;
						}
					}
				}
			}
		}
	}

	function setGeoloc($values, $id)
	{
		global $wpdb;
		$user_info = get_userdata(1);
		$autolocalisation = '';

		$ok = 0;
		$sql =
			"SELECT COUNT(idsrc)
			FROM " . $wpdb->prefix . small_ad_table_prefix_AOS . "petiteannonce__geolocalisation
			WHERE iddest = '".mysql_real_escape_string($id)."' ";
		if($wpdb->get_var($sql) == 0)
		{
			$fields = $field_values = "";
			foreach($values as $fieldname => $value)
			{
				$fields .= $fieldname . ", ";
				$field_values .= "'" . $value . "', ";
				if($fieldname == 'ville')
				{
					$autolocalisation .= ucwords($value).'('.$values['pays'].')';
				}
				$ok = 1;
			}
			$sql =
				"INSERT INTO " . $wpdb->prefix . small_ad_table_prefix_AOS . "petiteannonce__geolocalisation
					(idsrc, iddest, flagvalidgeolocalisation, token, autolocalisation, " . substr($fields,0,-2) . ")
				VALUES
					('" . mysql_real_escape_string($user_info->ID) . "','" . mysql_real_escape_string($id) . "', 'valid', '" . mysql_real_escape_string($user_info->ID.date('YmdHis').$id) . "', '" . $autolocalisation . "', " . substr($field_values,0,-2) . ")";
		}
		else
		{
			$sql = "UPDATE " . $wpdb->prefix . small_ad_table_prefix_AOS . "petiteannonce__geolocalisation SET idsrc =  '" . mysql_real_escape_string($user_info->ID) . "', iddest = '" . mysql_real_escape_string($id) . "', token = '" . mysql_real_escape_string($user_info->ID.date('YmdHis').$id) . "',  ";
			foreach($values as $fieldname => $value)
			{
				$sql .= $fieldname . " = '" . mysql_real_escape_string($value) . "', ";
				if($fieldname == 'ville')
				{
					$autolocalisation .= ucwords($value).'('.$values['pays'].')';
				}
				$ok = 1;
			}
			$sql = substr($sql,0,-2) . ", autolocalisation = '" . $autolocalisation . "' WHERE iddest = '".mysql_real_escape_string($id)."' ";
		}

		if($ok == 1)
		{
			if( !$wpdb->query( $sql ) && (mysql_error() != ''))
			{
				$this->error_message .= '<br/>'.__('Erreur de g&eacute;olocalisation','annonces');
				if(is_admin())$this->error_message .= '<hr/>'.$sql.'<br/>'.mysql_error().'<hr/>';
				$this->class_admin_notice = 'admin_notices_class_notok';
			}
		}
	}

	function setAttribut($values, $id)
	{
		global $wpdb;
		global $table_name_by_type;
		global $id_attribute_by_type;

		foreach($values as $attribute_type => $attribute_type_content)
		{
			foreach($attribute_type_content as $attribute_id => $attribute_type_definition)
			{
				$sql =
					"SELECT COUNT(" . $id_attribute_by_type[$attribute_type] . ")
					FROM " . $table_name_by_type[$attribute_type] . "
					WHERE idattribut = '" . mysql_real_escape_string($attribute_id) . "'
						AND idpetiteannonce = " . mysql_real_escape_string($id) . "";
				if($wpdb->get_var($sql) == 0)
				{
					$sql =
						"INSERT INTO " . $table_name_by_type[$attribute_type] . "
							(" . $id_attribute_by_type[$attribute_type] . ", idpetiteannonce, idattribut, " . stripslashes($attribute_type_definition['name']) . ")
						VALUES
							('', '" . mysql_real_escape_string($id) . "', '" . mysql_real_escape_string($attribute_id) . "', '" . mysql_real_escape_string($attribute_type_definition['value']) . "') ;";
				}
				else
				{
					$sql =
						"UPDATE " . $table_name_by_type[$attribute_type] . "
							SET " . $attribute_type_definition['name'] . " = '" . mysql_real_escape_string($attribute_type_definition['value']) . "'
						WHERE idpetiteannonce = '" . mysql_real_escape_string($id) . "' AND idattribut = '" . mysql_real_escape_string($attribute_id) . "' ;";
				}

				if( !$wpdb->query( $sql ) && (mysql_error() != ''))
				{
					$this->error_message .= '<br/>'.__('Erreur lors de la modification des informations compl&eacute;mentaires','annonces');
					if(is_admin())$this->error_message .= '<hr/>'.$sql.'<br/>'.mysql_error().'<hr/>';
					$this->class_admin_notice = 'admin_notices_class_notok';
				}
			}
		}
	}

	function admin_get_annonce($morequery = '' , $flag = DEFAULT_FLAG_ADMIN_AOS , $actual_page = 0 , $option = '')
	{
		global $wpdb;
		$real_page = $actual_page;if($actual_page!=0)$real_page = $actual_page-1;
		$debut = $real_page * NUMBER_OF_ITEM_PAR_PAGE_ADMIN_AOS;

		$moreflag = "";
		if($flag != "")$moreflag = " AND flagvalidpetiteannonce IN (".$flag.") ";

		$TheSelect = " ANN.* , ATT.* , IF( ATT.typeattribut = 'CHAR' , ATT_CHAR.valueattributchar , IF( ATT.typeattribut = 'DATE' , ATT_DATE.valueattributdate , IF( ATT.typeattribut = 'DEC' , ATT_DEC.valueattributdec , IF( ATT.typeattribut = 'INT' , ATT_INT.valueattributint , IF( ATT.typeattribut = 'TEXT' , ATT_TEXT.valueattributtextcourt , '' ) ) )) ) AS ATTRIBUT_VALUE, GEOLOC.* ";
		if($option == 'count')$TheSelect = "COUNT(ANN.idpetiteannonce) ";

		$sql =
			"SELECT ".$TheSelect."
			FROM " . $wpdb->prefix . small_ad_table_prefix_AOS . self::$table . " AS ANN
				LEFT JOIN ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__groupeattribut AS GRP_ATT ON (( ANN.idgroupeattribut = GRP_ATT.idgroupeattribut ) AND (GRP_ATT.flagvalidgroupeattribut = 'valid'))
				LEFT JOIN ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__groupeattribut_attribut AS LINK_CAT ON (( GRP_ATT.idgroupeattribut = LINK_CAT.idgroupeattribut ))
				LEFT JOIN ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attribut AS ATT ON (( LINK_CAT.idattribut = ATT.idattribut ) AND (ATT.flagvalidattribut = 'valid'))
				LEFT JOIN ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attributchar AS ATT_CHAR ON (( ATT_CHAR.idattribut = ATT.idattribut ) AND (ATT_CHAR.idpetiteannonce = ANN.idpetiteannonce))
				LEFT JOIN ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attributdate AS ATT_DATE ON (( ATT_DATE.idattribut = ATT.idattribut ) AND (ATT_DATE.idpetiteannonce = ANN.idpetiteannonce))
				LEFT JOIN ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attributdec AS ATT_DEC ON (( ATT_DEC.idattribut = ATT.idattribut ) AND (ATT_DEC.idpetiteannonce = ANN.idpetiteannonce))
				LEFT JOIN ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attributint AS ATT_INT ON (( ATT_INT.idattribut = ATT.idattribut ) AND (ATT_INT.idpetiteannonce = ANN.idpetiteannonce))
				LEFT JOIN ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attributtext AS ATT_TEXT ON (( ATT_TEXT.idattribut = ATT.idattribut ) AND (ATT_TEXT.idpetiteannonce = ANN.idpetiteannonce))
				LEFT JOIN ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__geolocalisation AS GEOLOC ON (( GEOLOC.iddest = ANN.idpetiteannonce ))
			WHERE 1 "
				. $moreflag
				. $morequery .
			" GROUP BY ATT.idattribut ";
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

	function getAttributs($flag = DEFAULT_FLAG_ADMIN_AOS, $idannonce = null, $flagvisible = null)
	{
		global $wpdb;
		if(is_null($flagvisible))
		{
			$tag_visible = '';
		}
		else
		{
			$tag_visible= " AND (ATT.flagvisibleattribut = '".$flagvisible."') ";
		}

		$sql =
			"SELECT ATT.labelattribut AS ATTRIBUT_NAME, IF( ATT.typeattribut = 'CHAR' , ATT_CHAR.valueattributchar , IF( ATT.typeattribut = 'DATE' , ATT_DATE.valueattributdate , IF( ATT.typeattribut = 'DEC' , ATT_DEC.valueattributdec , IF( ATT.typeattribut = 'INT' , ATT_INT.valueattributint , IF( ATT.typeattribut = 'TEXT' , ATT_TEXT.valueattributtextcourt , '' ) ) )) ) AS ATTRIBUT_VALUE, IF( ATT.typeattribut = 'CHAR' , ATT_CHAR.idpetiteannonce , IF( ATT.typeattribut = 'DATE' , ATT_DATE.idpetiteannonce , IF( ATT.typeattribut = 'DEC' , ATT_DEC.idpetiteannonce , IF( ATT.typeattribut = 'INT' , ATT_INT.idpetiteannonce , IF( ATT.typeattribut = 'TEXT' , ATT_TEXT.idpetiteannonce , '0' ) ) )) ) AS ID_ANNONCE
			FROM ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__groupeattribut AS CAT
				LEFT JOIN ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__groupeattribut_attribut AS LINK_CAT ON (CAT.idgroupeattribut = LINK_CAT.idgroupeattribut)
				LEFT JOIN ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attribut AS ATT ON (LINK_CAT.idattribut = ATT.idattribut )
				LEFT JOIN ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attributchar AS ATT_CHAR ON (ATT_CHAR.idattribut = ATT.idattribut)
				LEFT JOIN ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attributdate AS ATT_DATE ON (ATT_DATE.idattribut = ATT.idattribut )
				LEFT JOIN ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attributdec AS ATT_DEC ON (ATT_DEC.idattribut = ATT.idattribut )
				LEFT JOIN ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attributint AS ATT_INT ON (ATT_INT.idattribut = ATT.idattribut )
				LEFT JOIN ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attributtext AS ATT_TEXT ON (ATT_TEXT.idattribut = ATT.idattribut )
			WHERE  flagvalidgroupeattribut = 'valid'
				AND flagvalidattribut IN (" . $flag . ")
				".$tag_visible."
				AND (ATT_CHAR.idpetiteannonce IN (".$idannonce.")
				OR ATT_DATE.idpetiteannonce IN (".$idannonce.")
				OR ATT_INT.idpetiteannonce IN (".$idannonce.")
				OR ATT_DEC.idpetiteannonce IN (".$idannonce.")
				OR ATT_TEXT.idpetiteannonce IN (".$idannonce.")) ";

		return $wpdb->get_results( $sql );
	}

	function get_photos_for_annonce($idpetiteannonce, $morequery = "")
	{
		global $wpdb;

		$sql =
			"SELECT *
			FROM " . $wpdb->prefix . small_ad_table_prefix_AOS . "petiteannonce__photos
			WHERE (idpetiteannonce IN (" . $idpetiteannonce . ") ".$morequery." )
				AND flagvalidphotos = 'valid' ";
		return $wpdb->get_results( $sql );
	}

	function show_annonce($annonce_to_show)
	{
		global $attribute_type_possibilities;
		global $flag_visible_attribut_possibilities;
		global $flag_a_exporter_possibilities;
		global $flag_possibilities;
		// $ent�te =	'<script type="text/javascript" charset="utf-8">
						// annoncejquery(document).ready(function() {
							// var oTable = annoncejquery(\'#example\').dataTable({
							// "aaSorting": [[ 4, "desc" ]]
							// });
						// });
					// </script>';
		// $ent�te .='<div id="container">
					// <div id="demo">
					// <div id="example_wrapper" class="dataTables_wrapper">';
		// $output =
			// '<table class="display" id="example" border="0" cellpadding="0" cellspacing="0">
				// <thead><tr class="titre_listing">
					// <th class="sorting" >'.__('Validit&eacute; de l\'annonce','annonces').'</th>
					// <th class="sorting" >'.__('Exportable','annonces').'</th>
					// <th class="sorting" >'.__('R&eacute;f&eacute;rence','annonces').'</th>
					// <th class="sorting" >'.__('Titre','annonces').'</th>
					// <th class="sorting" >'.__('Derni&egrave;re modification','annonces').'</th>
					// <th class="sorting" colspan="3" >'.__('Op&eacute;ration','annonces').'</th>
				// </tr></thead>';

		// if( count($annonce_to_show) > 0 )
		// {
			// $output .= '<tbody>';
			// foreach($annonce_to_show as $key => $annonce_content)
			// {
				// $output .=
				// '<tr>
					// <td>'.$flag_possibilities[$annonce_content->flagvalidpetiteannonce].'</td>
					// <td>'.$flag_a_exporter_possibilities[$annonce_content->aexporter].'</td>
					// <td>'.$annonce_content->referenceagencedubien.'</td>
					// <td>'.$annonce_content->titre.'</td>
					// <td>'.date("d/m/Y",strtotime($annonce_content->autolastmodif)).'</td>
					// <td>
					// <img src="'.WP_PLUGIN_URL.'/'.ANNONCES_PLUGIN_DIR.'/medias/images/b_edit.png" alt="edit_annonce" class="button_img"  onclick="javascript:document.getElementById(\'act\').value=\'edit\';document.getElementById(\'id_to_treat\').value=\''.$annonce_content->idpetiteannonce.'\';document.forms.treat_annonce.submit();"/>
					// </td>
					// <td>
					// <img src="'.WP_PLUGIN_URL.'/'.ANNONCES_PLUGIN_DIR.'/medias/images/b_drop.png" alt="drop_annonce" class="button_img" onclick="javascript:document.getElementById(\'act\').value=\'delete\';document.getElementById(\'id_to_treat\').value=\''.$annonce_content->idpetiteannonce.'\';var check = confirm(\'&Ecirc;tes vous s&ucirc;r de vouloir supprimer cet &eacute;l&eacute;ment ? \');if(check == true){document.forms.treat_annonce.submit();}"/></td>
					// <td><input class="attribut_annonce_content" type="checkbox" name="annonce['.$annonce_content->idpetiteannonce.']" id="'.$annonce_content->idpetiteannonce.'" value="'.$annonce_content->idpetiteannonce.'" /></td>
				// </tr>';
			// }
		// }
		// else
		// {
			// $output .= '<tr><td colspan="20" class="no_result" >'.__('Aucun r&eacute;sultat','annonces').'</td></tr>';
		// }

		// $output .= '</tbody></table></div></div></div>';

		// return $ent�te.$output;
	// }
		$output =
			'<table summary="annonce listing" cellpadding="2" cellspacing="1" class="listing" >
				<tr>
					<td class="listing_header" >'.__('Validit&eacute; de l\'annonce','annonces').'</td>
					<td class="listing_header" >'.__('Exportable','annonces').'</td>
					<td class="listing_header" >'.__('R&eacute;f&eacute;rence','annonces').'</td>
					<td class="listing_header" >'.__('Titre','annonces').'</td>
					<td class="listing_header" >'.__('Derni&egrave;re modification','annonces').'</td>
					<td class="listing_header" colspan="3" >'.__('Op&eacute;ration','annonces').'</td>
				</tr>';
		if( count($annonce_to_show) > 0 )
		{
			foreach($annonce_to_show as $key => $annonce_content)
			{
				$output .=
				'<tr>
					<td>'.$flag_possibilities[$annonce_content->flagvalidpetiteannonce].'</td>
					<td>'.$flag_a_exporter_possibilities[$annonce_content->aexporter].'</td>
					<td>'.$annonce_content->referenceagencedubien.'</td>
					<td>'.$annonce_content->titre.'</td>
					<td>'.date("d/m/Y",strtotime($annonce_content->autolastmodif)).'</td>
					<td><img src="'.WP_PLUGIN_URL.'/'.ANNONCES_PLUGIN_DIR.'/medias/images/b_edit.png" alt="edit_annonce" class="button_img"  onclick="javascript:document.getElementById(\'act\').value=\'edit\';document.getElementById(\'id_to_treat\').value=\''.$annonce_content->idpetiteannonce.'\';document.forms.treat_annonce.submit();"/></td>
					<td><img src="'.WP_PLUGIN_URL.'/'.ANNONCES_PLUGIN_DIR.'/medias/images/b_drop.png" alt="drop_annonce" class="button_img" onclick="javascript:document.getElementById(\'act\').value=\'delete\';document.getElementById(\'id_to_treat\').value=\''.$annonce_content->idpetiteannonce.'\';var check = confirm(\'&Ecirc;tes vous s&ucirc;r de vouloir supprimer cet &eacute;l&eacute;ment ? \');if(check == true){document.forms.treat_annonce.submit();}"/></td>
					<td><input type="checkbox" name="annonce['.$annonce_content->idpetiteannonce.']" id="annonce_'.$annonce_content->idpetiteannonce.'" value="'.$annonce_content->idpetiteannonce.'" style="cursor:pointer;" /></td>
				</tr>';
			}
		}
		else
		{
			$output .= '<tr><td colspan="20" class="no_result" >'.__('Aucun r&eacute;sultat','annonces').'</td></tr>';
		}

		$output .= '</table><br/><br/>';
		return $output;
	}
}