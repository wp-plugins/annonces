<?php
/**
 * @author Annonces
 * @version v1
 */
include_once(ANNONCES_CONFIG);

class version
{
	public function getVersion()
	{
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		global $wpdb;
		if( $wpdb->get_var("show tables like '" . ANNONCES_TABLE_VERSION . "'") == ANNONCES_TABLE_VERSION)
		{
			$query = $wpdb->prepare("SELECT version FROM " . ANNONCES_TABLE_VERSION . " WHERE nomVersion = 'annonces_version'");
			$resultat = $wpdb->get_row($query);
			return $resultat->version;
		}
		else
		{
			return -1;
		}
	}

	public function majVersion()
	{
		global $wpdb;
		$sql = "UPDATE " . ANNONCES_TABLE_VERSION . " SET version = version + 1 where id = 1";
		$wpdb->query($sql);
	}
}
?>