<?php
	function getVersion($nom)
	{
		global $wpdb;
		if( $wpdb->get_var("show tables like '" . ANNONCES_TABLE_VERSION . "'") == ANNONCES_TABLE_VERSION)
		{
			$query = $wpdb->prepare("SELECT version version
				FROM " . ANNONCES_TABLE_VERSION . "
				WHERE nom = %s", $nom);
			$resultat = $wpdb->get_row($query);
			return $resultat->version;
		}
		else
		{
			return -1;
		}
	}
?>