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

	function generate_xml_file_content($annonce, $structure, $listPhoto)
	{
		global $tools;
		$photos_for_export = array();
		foreach($listPhoto as $key => $photo_definition)
		{
			if(is_file(WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . $photo_definition->original))
			{
				$photos_for_export[$photo_definition->idpetiteannonce][] = WP_CONTENT_URL . WAY_TO_PICTURES_AOS . $photo_definition->original;
			}
		}

		$ignoreFieldList = array('identifianttechnique');
		$mandatoryField = array('idpetiteannonce', 'referenceagencedubien', 'titre', 'descriptif', 'autoinsert', 'prixloyerprixdecession', 'adresse', 'cp', 'ville');

		$xmlFile_TPL = 
'<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>
<annonces>
	#ANNONCESLIST#
</annonces>';
		$annonce_TPL = 
'
<annonce id="#ANNONCEID#" >
	<reference>#REFERENCE#</reference>
	<titre>#TITRE#</titre>
	<texte>#DESCRIPTION#</texte>
	<date_integration>#DATECREATION#</date_integration>
	<photos>
		#LISTPHOTOS#
	</photos>
	<rubrique>#RUBRIQUE#</rubrique>
	<prix>#PRIX#</prix>
	<adresse>#ADRESSE#</adresse>
	<code_postal>#CODEPOSTAL#</code_postal>
	<ville>#VILLE#</ville>
	<email>#EMAIL#</email>
	<tel>#TELEPHONE#</tel>

	#SPECIFICANNONCEFIELD#
</annonce>';

		$listeAnnonces = '';
		foreach($annonce as $idannonce => $annonce_definition)
		{
			$specialFieldList = '';
			foreach($structure as $label => $column_number)
			{
				if(!in_array($label, $mandatoryField))
				{
					$value = '';
					$value = $tools->slugify_accent_ut8(stripslashes($this->check_for_special_field($label,$annonce_definition)));
					if(($value != '') && (!in_array($label, $ignoreFieldList)))
					{
						$specialFieldList .= '<' . $label . '>' . $value . '</' . $label . '>
	';
					}
				}
			}
			$Lannonce = str_replace('#ANNONCEID#', $idannonce, $annonce_TPL);
			$Lannonce = str_replace('#REFERENCE#', $tools->slugify_accent_ut8(stripslashes($this->check_for_special_field('referenceagencedubien',$annonce_definition))), $Lannonce);
			$Lannonce = str_replace('#TITRE#', $tools->slugify_accent_ut8(stripslashes($this->check_for_special_field('titre',$annonce_definition))), $Lannonce);
			$Lannonce = str_replace('#DESCRIPTION#', $tools->slugify_accent_ut8(stripslashes($this->check_for_special_field('descriptif',$annonce_definition))), $Lannonce);
			$Lannonce = str_replace('#DATECREATION#', $tools->slugify_accent_ut8(stripslashes($this->check_for_special_field('autoinsert',$annonce_definition))), $Lannonce);
			$Lannonce = str_replace('#RUBRIQUE#', 'IMO003002', $Lannonce);
			$Lannonce = str_replace('#PRIX#', $tools->slugify_accent_ut8(stripslashes($this->check_for_special_field('prixloyerprixdecession',$annonce_definition))), $Lannonce);
			$Lannonce = str_replace('#ADRESSE#', $tools->slugify_accent_ut8(stripslashes($this->check_for_special_field('adresse',$annonce_definition))), $Lannonce);
			$Lannonce = str_replace('#CODEPOSTAL#', $tools->slugify_accent_ut8(stripslashes($this->check_for_special_field('cp',$annonce_definition))), $Lannonce);
			$Lannonce = str_replace('#VILLE#', $tools->slugify_accent_ut8(stripslashes($this->check_for_special_field('ville',$annonce_definition))), $Lannonce);
			$Lannonce = str_replace('#EMAIL#', '', $Lannonce);
			$Lannonce = str_replace('#TELEPHONE#', '', $Lannonce);

			$annoncePicture = '';
			if(isset($photos_for_export[$idannonce]))
			{
				foreach($photos_for_export[$idannonce] as $pictureIndex => $picturePath)
				{
					$annoncePicture .= '<photo>' . $picturePath . '</photo>
		';
				}
			}
			$Lannonce = str_replace('#LISTPHOTOS#', $annoncePicture, $Lannonce);

			$Lannonce = str_replace('#SPECIFICANNONCEFIELD#', $specialFieldList, $Lannonce);

			$listeAnnonces .= $Lannonce . '
';
		}

		$listeAnnonces = str_replace('#ANNONCESLIST#', $listeAnnonces, $xmlFile_TPL);

		return $listeAnnonces;
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

	function save_file($file, $content)
	{
		global $tools;

		/*	IF EXPORT DIR NOT EXIST WE CREATE IT	*/
		$export_dir = dirname($file);
		if(!is_dir($export_dir))$tools->make_recursiv_dir($export_dir);

		/*	OPEN CSV FILE IN MODE WRITABLE	*/
		if(!$f=fopen($file,'w+')){
			$this->error_message = __('Erreur lors de l\'ouverture du fichier ','annonces') . $file;
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