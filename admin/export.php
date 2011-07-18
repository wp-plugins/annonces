<?php
/***************************************************
*Date: 01/10/2009      File:export.php 	 		   *
*Author: Eoxia							           *
*Comment:                                          *
***************************************************/

/*	INSTANCIATE EXPORT OBJECT			*/
$export = new export();

/*	INSTANCIATE PASSERELLE OBJECT	*/
$available_passerelle = new passerelle();
$available_export = $available_passerelle->get_passerelle("", "'valid'" , 0 , 'nolimit');

/*	INSTANCIATE ANNONCE OBJECT	*/
$eav_annonce = new eav();
$annonce = new annonce();

$choosen_passerelle = isset($_REQUEST['passerelle_list']) ? $tools->IsValid_Variable($_REQUEST['passerelle_list']) : '' ;

if($choosen_passerelle != '')
{
	ini_set("memory_limit","2048M");
	$file_to_zip = array();

	$choosen_export = $available_passerelle->get_passerelle(" AND idpasserelle = '".mysql_real_escape_string($choosen_passerelle)."' ", "'valid'" , 0 , 'nolimit');
	$writen_file="";

	/*	FIELD FOR FILE SPECIFICATION	*/
	$file_structure = explode(',',$choosen_export[0]->structure);
	$file_structure = array_change_key_case(array_flip($file_structure));
	$file_text_separator = $choosen_export[0]->separateurtexte;
	$file_field_separator = $choosen_export[0]->separateurchamp;
	$file_line_separator = $choosen_export[0]->separateurligne;
	$file_name = (is_null($choosen_export[0]->nomexport)?'archive':$choosen_export[0]->nomexport);

	/*	FIELD FOR FTP SPECIFICATION	*/
	$ftp_host = $choosen_export[0]->host;
	$ftp_user = $choosen_export[0]->user;
	$ftp_pass = $choosen_export[0]->pass;

	/*	RETRIEVE ALL SMALL AD HEADER (NO ATTRIBUTE) INFORMATION	*/
	//Exporter annonces valide et modéré
	//$annonce_to_treat = $eav_annonce->getAnnoncesEntete(" AND aexporter = 'oui' "," 'valid','moderated' ",'titre',0,'nolimit');
	//Exporter uniquement les annonces valides
	$annonce_to_treat = $eav_annonce->getAnnoncesEntete(" AND aexporter = 'oui' "," 'valid' ",'titre',0,'nolimit');
	if(count($annonce_to_treat) > 0)
	{
		$annonce_array = array();
		$annonce_id_list = "  ";
		foreach( $annonce_to_treat as $key => $annonce_content)
		{
			foreach( $annonce_content as $label => $value)
			{
				$annonce_array[$annonce_content->idpetiteannonce][$label] = $value;

				if($label == 'idpetiteannonce')
				{
					$annonce_id_list .= " '" . mysql_real_escape_string($annonce_content->idpetiteannonce) . "', ";
				}
			}
		}
		$annonce_id_list = substr($annonce_id_list,0,-2);

		/*	RETRIEVE ALL SMALL AD ATTRIBUTE INFORMATION FOR SMALL AD LIST DEFINED ABOVE	*/
		$annonce_array_attributes = $annonce->getAttributs(DEFAULT_FLAG_ADMIN_AOS, $annonce_id_list);

		/*	ADD ATTRIBUTE TO SMALL AD		*/
		foreach($annonce_array_attributes as $key => $attribute_definition)
		{
			$annonce_array[$attribute_definition->ID_ANNONCE][strtolower($attribute_definition->ATTRIBUT_NAME)] = $attribute_definition->ATTRIBUT_VALUE;
		}

		$photos_list = $annonce->get_photos_for_annonce($annonce_id_list);
		$photos_for_export = array();
		foreach($photos_list as $key => $photo_definition)
		{
			if(is_file(WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . $photo_definition->original))
			{
				$photos_for_export[$photo_definition->idpetiteannonce][] = $photo_definition->original;
			}
		}

		if($choosen_export[0]->typeexport == 'csv')
		{
			/*	GENERATE FILE	*/
			$output_file = $export->generate_csv_file_content($annonce_array, $file_structure);

			/*	SAVE FILE	*/
			foreach($output_file as $line => $line_content)
			{
				$eachline[] = implode($file_field_separator,$line_content);
			}
			if($file_line_separator == '\r\n')
			{
				$file_line_separator = "
";
			}
			$file_content = implode($file_line_separator,$eachline);
			$writen_file = $export->save_file(WP_CONTENT_DIR . WAY_TO_EXPORT_AOS . 'Annonces.csv', $file_content);

			$file_to_zip[] = WP_PLUGIN_DIR . '/' . ANNONCES_PLUGIN_DIR . '/includes/seloger/Config.txt';
			$file_to_zip[] = WP_PLUGIN_DIR . '/' . ANNONCES_PLUGIN_DIR . '/includes/seloger/Photos.cfg';
		}
		elseif($choosen_export[0]->typeexport == 'xml')
		{
			/*	GENERATE FILE	*/
			$output_file = $export->generate_xml_file_content($annonce_array, $file_structure, $photos_list);
			$writen_file = $export->save_file(WP_CONTENT_DIR . WAY_TO_EXPORT_AOS . 'Annonces.xml', $output_file);
		}
	}
	else
	{
		$export->error_message = __('Aucune annonce &agrave; exporter nb : ','annonces').count($annonce_to_treat);
		$export->class_admin_notice = 'admin_notices_class_notok';
	}

	if($writen_file != "")
	{
		$zip_file = $file_name.'.zip';
		@unlink($zip_file);

		$file_to_zip[] = $writen_file;

		/*	GETTING PHOTOS TO ADD TO ZIP	*/
		if(annonce_export_picture == 'file')
		{
			foreach($photos_list as $key => $photos_definition)
			{
				if(is_file(WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . $photos_definition->original)
						&&	(!in_array(WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . $photos_definition->original, $file_to_zip)))
				{
					$file_to_zip[] = WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . $photos_definition->original;
				}
			}
		}

		/*	ZIP THE FILE	*/
		$archive = new Zip($zip_file);
		$archive->setFiles($file_to_zip);
		$archive->compressToPath(WP_CONTENT_DIR . WAY_TO_EXPORT_AOS);

		$full_path_to_zip = WP_CONTENT_DIR . WAY_TO_EXPORT_AOS . $zip_file;
		$full_path_to_zip_url = WP_CONTENT_URL . WAY_TO_EXPORT_AOS;

		if(($choosen_passerelle == 'local') || ($ftp_host == '127.0.0.1'))
		{
			$name=$zip_file;
			$file=$full_path_to_zip_url.$name;

			header("content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=".$name);
			flush();
			readfile($file);
			header("Location:".$file);
		}
		else
		{
			$ftp_instance = new Ftp($ftp_host, $ftp_user, $ftp_pass);
			$path = "/";
			if($check = $ftp_instance->uploadToServer($full_path_to_zip, $path))
			{
				$export->error_message = __('Fichier zip envoy&eacute; avec succ&egrave;s','annonces');
				$export->class_admin_notice = 'admin_notices_class_ok';
			}
			else
			{
				$export->error_message = __('Erreur lors de l\'envoi du fichier zip ','annonces') . $full_path_to_zip;
				$export->class_admin_notice = 'admin_notices_class_notok';
			}
		}

		@unlink($writen_file);
	}

	unset($choosen_export);
}

?>
<div class="<?php echo $export->class_admin_notice; ?>" ><?php echo $export->error_message; ?></div>

<form name="export_annonce" action="" method="post" >

	<table summary="<?php _e('liste des passerelles pour l\'export des annonces','annonces') ?>" cellpadding="0" cellspacing="0" class="table_export_annonce" > 
		<tr>
			<td>
				<?php _e('Exporter vers','annonces') ?>&nbsp;:&nbsp;
			</td>
			<td>
				<select id="passerelle_list" name="passerelle_list" >
					<?php
						foreach($available_export as $key => $passerelle_definition)
						{
							$selected = (isset($choosen_passerelle) && ($choosen_passerelle != '')) ? ' selected="selected" ' : '' ;
							echo '<option value="' . $passerelle_definition->idpasserelle . '" ' . $selected . ' >' . $passerelle_definition->nompasserelle . '</option>';
						}
					?>
					<!--<option value="local"><?php //_e('l\'ordinateur (Bureau)','annonces') ?></option>-->
				</select>
			</td>
			<td colspan="2" ><input type="submit" name="exporter" value="<?php _e('Exporter','annonces') ?>" /></td>
		</tr>
	</table>

</form>