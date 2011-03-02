<?php
class annonces_options {

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