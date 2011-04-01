<?php
/***************************************************
*Date: 01/10/2009      file:frontend.class.php     *
*Author: Eoxia                                     *
*Comment:                                          *
***************************************************/
require_once dirname(__FILE__).'/eav.class.php';
require_once dirname(__FILE__).'/gmap.class.php';

global $content;
global $post;

class annonces_options {

function slugify_noaccent($text){
	$pattern  = Array("/&eacute;/", "/&egrave;/", "/&ecirc;/", "/&ccedil;/", "/&agrave;/", "/&acirc;/", "/&icirc;/", "/&iuml;/", "/&ucirc;/", "/&ocirc;/", "/&Egrave;/", "/&Eacute;/", "/&Ecirc;/", "/&Euml;/", "/&Igrave;/", "/&Iacute;/", "/&Icirc;/", "/&Iuml;/", "/&Ouml;/", "/&Ugrave;/", "/&Ucirc;/", "/&Uuml;/","/é/", "/è/", "/ê/", "/ç/", "/à/", "/â/", "/î/", "/ï/", "/ù/", "/ô/", "/È/", "/É/", "/Ê/", "/Ë/", "/Ì/", "/Í/", "/Î/", "/Ï/", "/Ö/", "/Ù/", "/Û/", "/Ü/");
	$rep_pat = Array("e", "e", "e", "c", "a", "a", "i", "i", "u", "o", "E", "E", "E", "E", "I", "I", "I", "I", "O", "U", "U", "U","e", "e", "e", "c", "a", "a", "i", "i", "u", "o", "E", "E", "E", "E", "I", "I", "I", "I", "O", "U", "U", "U");
	if ($text == '')
	{
		return '';
	}
	else
	{
		$text = preg_replace($pattern, $rep_pat, utf8_decode($text));
	}
  return $text;
}

function slugify_noaccent_no_utf8decode($text){
	$pattern  = Array("/&eacute;/", "/&egrave;/", "/&ecirc;/", "/&ccedil;/", "/&agrave;/", "/&acirc;/", "/&icirc;/", "/&iuml;/", "/&ucirc;/", "/&ocirc;/", "/&Egrave;/", "/&Eacute;/", "/&Ecirc;/", "/&Euml;/", "/&Igrave;/", "/&Iacute;/", "/&Icirc;/", "/&Iuml;/", "/&Ouml;/", "/&Ugrave;/", "/&Ucirc;/", "/&Uuml;/","/é/", "/è/", "/ê/", "/ç/", "/à/", "/â/", "/î/", "/ï/", "/ù/", "/ô/", "/È/", "/É/", "/Ê/", "/Ë/", "/Ì/", "/Í/", "/Î/", "/Ï/", "/Ö/", "/Ù/", "/Û/", "/Ü/");
	$rep_pat = Array("e", "e", "e", "c", "a", "a", "i", "i", "u", "o", "E", "E", "E", "E", "I", "I", "I", "I", "O", "U", "U", "U","e", "e", "e", "c", "a", "a", "i", "i", "u", "o", "E", "E", "E", "E", "I", "I", "I", "I", "O", "U", "U", "U");
	if ($text == '')
	{
		return '';
	}
	else
	{
		$text = preg_replace($pattern, $rep_pat, $text);
  }
  
  return $text;
}

function majUrlAnnonces()
{
	$eav_value = new Eav();
	global $wpdb;
	
	$annonces = $eav_value->getLesAnnonces();
	$sizei = count($annonces);
	
	for($i = 0; $i < $sizei; $i++)
		{
			$eav_mode = new Eav();
			
			$recup_link = Eav::get_expression();
			
			$recup_link = str_replace('%idpetiteannonce%', $annonces[$i]->idpetiteannonce, $recup_link);
			$recup_link = str_replace('%titre_annonce%', $annonces[$i]->titre, $recup_link);
			$recup_link = str_replace('%referenceagencedubien%', $annonces[$i]->referenceagencedubien, $recup_link);
			$recup_link = str_replace('%nomgroupeattribut%', $annonces[$i]->nomgroupeattribut, $recup_link);
			$recup_link = str_replace('%descriptiongroupeattribut%', $annonces[$i]->descriptiongroupeattribut, $recup_link);
			$recup_link = str_replace('%ville%', $annonces[$i]->ville, $recup_link);
			$recup_link = str_replace('%departement%', $annonces[$i]->departement, $recup_link);
			$recup_link = str_replace('%region%', $annonces[$i]->region, $recup_link);
			$recup_link = str_replace('%cp%', $annonces[$i]->cp, $recup_link);
			$recup_link = str_replace('%pays%', str_replace("'", '-', $annonces[$i]->pays), $recup_link);
			$recup_link = str_replace('%date_publication%', date("d/m/Y",strtotime($annonces[$i]->autoinsert)), $recup_link);
			$recup_link = str_replace('%type_bien%', str_replace('/','-',$eav_mode->getBien($annonces[$i]->idpetiteannonce)), $recup_link);
			
			$recup_link = annonces_options::slugify_noaccent($recup_link);
			$recup_link = trim($recup_link);
			$recup_link = str_replace(' ', '-', $recup_link);
			$recup_link = mb_strtolower($recup_link);
			
			$recup_link = $recup_link . Eav::get_suffix();
			
			$maj_annonce = $wpdb->prepare('UPDATE `'.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce`
								SET urlannonce ="'. $recup_link .'"
								WHERE idpetiteannonce="' . $annonces[$i]->idpetiteannonce . '"');
			$wpdb->query($maj_annonce);
		}
}

function recupNumImage()
	{
		global $wpdb;
			
			$sqlbudget = "SELECT numphoto FROM " . ANNONCES_TABLE_TEMPPHOTO . "";
			$reqbudget = mysql_query($sqlbudget) or die(mysql_error());
			while($data = mysql_fetch_array($reqbudget))
			{
				$budget_theme = $data["numphoto"];
			}
			return $budget_theme;
	}

function recupinfo($lbloption)
	{
		global $wpdb;
		
		$sqlbudget = "SELECT nomoption FROM `".$wpdb->prefix.small_ad_table_prefix_AOS."petiteannonce__option` WHERE labeloption='".$lbloption."'";
		$reqbudget = mysql_query($sqlbudget) or die(mysql_error());
		while($data = mysql_fetch_array($reqbudget))
		{
			$budget_theme = $data["nomoption"];
		}
		return $budget_theme;
	}
	
function updateoption($lbldefaut,$lblcourant)
	{
		global $wpdb;
		
		$query = $wpdb->prepare(
			'UPDATE `'.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__option` 
			SET nomoption ="%s" 
			WHERE labeloption="%s"', 
				annonces_options::recupinfo($lbldefaut), $lblcourant);
		$wpdb->query($query);
	}

function monnaie()
	{
		global $wpdb;
		
		$query = $wpdb->prepare('select measureunit from '.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__attribut where labelattribut = "PrixLoyerPrixDeCession"');
		$reqmonnaie = $wpdb->get_row($query);
		$monnaie = $reqmonnaie->measureunit;

		return $monnaie;
	}
}
?>