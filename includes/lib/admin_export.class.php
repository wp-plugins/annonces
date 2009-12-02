<?php
/***************************************************
*Date: 01/10/2009      File:export.class.php 			 *
*Author: Eoxia							                       *
*Comment:                                          *
***************************************************/

class export
{

	private static $table = 'petiteannonce__passerelle';
	public $error_message = '';
	public $class_admin_notice = '';

	function generate_csv_file_content($annonce, $structure)
	{
		global $tools;
		$file_line = 0;
		$file_content = array();
		foreach($annonce as $idannonce => $annonce_definition)
		{
			foreach($structure as $label => $column_number)
			{
				$file_content[$file_line][$structure[$label]] = $tools->slugify_accent(stripslashes($this->check_for_special_field($label,$annonce_definition)));
			}
			$file_line++;
		}

		return $file_content;
	}

	function check_for_special_field($field_to_check, $value_to_put)
	{
		global $file_name;
		global $eav_annonce;

		switch($field_to_check){
			case 'identifianttechnique':
				$output_value = $value_to_put['idpetiteannonce'];
			break;
			case 'libelle':
				$output_value = $value_to_put['titre'];
			break;
			case 'cp':
				$cp = '0';
				if(trim($value_to_put['cp']) != "")$cp = trim($value_to_put['cp']);
				$output_value = $cp;
			break;
			case 'ville':
				$ville = 'nc';
				if(trim($value_to_put['ville']) != "")$ville = trim($value_to_put['ville']);
				$output_value = $ville;
			break;
			case 'pays':
				$pays = 'nc';
				if(trim($value_to_put['pays']) != "")$pays = trim($value_to_put['pays']);
				$output_value = $pays;
			break;
			case 'adresse':
				$adresse = 'nc';
				if(trim($value_to_put['adresse']) != "")$adresse = trim($value_to_put['adresse']);
				$output_value = $adresse;
			break;
			case 'descriptif':
				$descriptif = 'nc';
				if(trim($value_to_put['descriptif']) != "")$descriptif = trim($value_to_put['descriptif']);
				$output_value = $descriptif;
			break;
			case 'nbdepieces':
				$nbdepieces = '0';
				if($value_to_put['nbdepieces'] > 0)$nbdepieces = $value_to_put['nbdepieces'];
				$output_value = $nbdepieces;
			break;
			case 'nbdechambres':
				$nbdechambres = '0';
				if($value_to_put['nbdechambres'] > 0)$nbdechambres = $value_to_put['nbdechambres'];
				$output_value = $nbdechambres;
			break;
			case 'honoraires':
				$honoraires = 'nc';
				if($value_to_put['honoraires'] > 0)$honoraires = $value_to_put['honoraires'];
				$output_value = $honoraires;
			break;
			case 'prixloyerprixdecession':
				$prixloyerprixdecession = '0.00';
				if($value_to_put['prixloyerprixdecession'] > 0)$prixloyerprixdecession = $value_to_put['prixloyerprixdecession'];
				$output_value = $prixloyerprixdecession;
			break;
			case 'typebien':
				$typebien = 'terrain';
				if(trim($value_to_put['typebien']) != "")$typebien = trim($value_to_put['typebien']);
				$output_value = $typebien;
			break;
			case 'typeannonce':
				$typeannonce = 'vente';
				if(trim($value_to_put['typeannonce']) != "")$typeannonce = trim($value_to_put['typeannonce']);
				$output_value = $typeannonce;
			break;
			case 'referenceagencedubien':
				$referenceagencedubien = $value_to_put['idpetiteannonce'];
				if(trim($value_to_put['referenceagencedubien']) != "")$referenceagencedubien = trim($value_to_put['referenceagencedubien']);
				$output_value = $referenceagencedubien;
			break;
			case 'identifiantagence':
				$identifiantagence = $file_name;
				if(trim($value_to_put['identifiantagence']) != "")$identifiantagence = trim($value_to_put['identifiantagence']);
				$output_value = $identifiantagence;
			break;
			case 'photo1':
		$photos = $eav_annonce->getPhotos($value_to_put['idpetiteannonce']);
				$output_value = $photos[0]->original;
			break;
			case 'photo2':
				$photos = $eav_annonce->getPhotos($value_to_put['idpetiteannonce']);
				$output_value = $photos[1]->original;
			break;
			case 'photo3':
				$photos = $eav_annonce->getPhotos($value_to_put['idpetiteannonce']);
				$output_value = $photos[2]->original;
			break;
			case 'photo4':
				$photos = $eav_annonce->getPhotos($value_to_put['idpetiteannonce']);
				$output_value = $photos[3]->original;
			break;
			case 'photo5':
				$photos = $eav_annonce->getPhotos($value_to_put['idpetiteannonce']);
				$output_value = $photos[4]->original;
			break;
			case 'photo6':
				$photos = $eav_annonce->getPhotos($value_to_put['idpetiteannonce']);
				$output_value = $photos[5]->original;
			break;
			case 'photo7':
				$photos = $eav_annonce->getPhotos($value_to_put['idpetiteannonce']);
				$output_value = $photos[6]->original;
			break;
			case 'photo8':
				$photos = $eav_annonce->getPhotos($value_to_put['idpetiteannonce']);
				$output_value = $photos[7]->original;
			break;
			case 'photo9':
				$photos = $eav_annonce->getPhotos($value_to_put['idpetiteannonce']);
				$output_value = $photos[8]->original;
			break;
			default:
				/*	IF THE FIELD IS SPECIFIED IN DEFINITION FOR EXPORT WE PUT THE GOOD VALUE INTO THE GOOD COLUMN 	*/
				if(isset($value_to_put[$field_to_check]) && !empty($value_to_put[$field_to_check]))
				{
					$output_value = $value_to_put[$field_to_check];
				}
				/*	IF THE FIELD IS NOT DEFINED SO WE PUT AN EMPTY VALUE	*/
				else{
					$output_value = '';
				}
		}

		return $output_value;
	}

	function save_csv_file($file, $content)
	{
		global $tools;

		/*	IF EXPORT DIR NOT EXIST WE CREATE IT	*/
		$export_dir = dirname($file);
		if(!is_dir($export_dir))$tools->make_recursiv_dir($export_dir);

		/*	OPEN CSV FILE IN MODE WRITABLE	*/
		if(!$f=fopen($file,'w+')){
			$this->error_message = __('Erreur lors de l&#146;ouverture du fichier ','annonces') . $file;
			$this->class_admin_notice = 'admin_notices_class_notok';
		}
		/*	WRITE CSV FILE	*/
		elseif(fwrite($f,$content)){
			$this->error_message = __('Cr&eacute;ation du fichier r&eacute;ussi','annonces');
			$this->class_admin_notice = 'admin_notices_class_ok';
		}

		return $file;
	}

}