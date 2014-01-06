<?php
/*
Installation de l'extension
	- Cr�ation des tables
*/

	function createTable($table,$champ)
	{
		global $wpdb;

		if( $wpdb->get_var("show tables like '" . $table . "'") != $table) {
			// On construit la requete SQL de cr�ation de table
			$sql =
				"CREATE TABLE " . $table . " (
					" . $champ . "
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
			// Execution de la requete
			$wpdb->query($wpdb->prepare($sql, array() ));
		}
	}


function annonces_creationTables()
{// Cr�ation des tables lors de l'installation

	require_once(ANNONCES_LIB_PLUGIN_DIR . 'version/version.class.php');
	require_once('insertions.php');
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	global $wpdb;

		$champversion = "id INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
					nomVersion VARCHAR( 255 ) NOT NULL UNIQUE,
					version INT( 10 ) NOT NULL";
		// On v�rifie si la table petiteannonce n'existe pas
		createTable(ANNONCES_TABLE_VERSION,$champversion);

		{// Various tables


			$champattatt = "`idattribut` int(11) NOT NULL default '0',
						idgroupeattribut int(11) NOT NULL default '0',
						flagvalidgroupeattribut_attribut enum('deleted','moderated','valid') collate utf8_unicode_ci default 'moderated',
						PRIMARY KEY  (idattribut,idgroupeattribut),
						KEY idgroupeattribut (idgroupeattribut)";
			// On v�rifie si la table petiteannonce n'existe pas
			createTable(ANNONCES_TABLE_GROUPEATTRIBUTATTRIBUT,$champattatt);


			$champpetiteannonce = "idpetiteannonce int(11) NOT NULL auto_increment,
						flagvalidpetiteannonce enum('deleted','moderated','valid') collate utf8_unicode_ci default 'moderated',
						idgroupeattribut int(11) NOT NULL default '0',
						titre char(64) collate utf8_unicode_ci default NULL,
						aexporter enum('oui','non') NOT NULL default 'oui',
						referenceagencedubien varchar(20) collate utf8_unicode_ci default NULL,
						autoinsert datetime default NULL,
						autolastmodif datetime default NULL,
						urlannonce varchar(200) collate utf8_unicode_ci,
						PRIMARY KEY  (idpetiteannonce),
						KEY idgroupeattribut_idx (idgroupeattribut)";
			// On v�rifie si la table petiteannonce n'existe pas
			createTable(ANNONCES_TABLE_ANNONCES,$champpetiteannonce);


			$champattribut = "idattribut int(11) NOT NULL auto_increment,
						flagvalidattribut enum('deleted','moderated','valid') collate utf8_unicode_ci default 'moderated',
						flagvisibleattribut enum('oui','non') collate utf8_unicode_ci default NULL,
						typeattribut varchar(4) collate utf8_unicode_ci default NULL,
						labelattribut char(50) collate utf8_unicode_ci default NULL,
						nomattribut char(70) collate utf8_unicode_ci default NULL,
						measureunit char(10) collate utf8_unicode_ci default NULL,
						PRIMARY KEY  (idattribut)";
			// On v�rifie si la table attribut n'existe pas
			createTable(ANNONCES_TABLE_ATTRIBUT,$champattribut);


			$champattributchar = "idattributchar int(11) NOT NULL auto_increment,
						flagvalidattributchar enum('deleted','moderated','valid') collate utf8_unicode_ci default 'valid',
						idpetiteannonce int(11) NOT NULL default '0',
						idattribut int(11) NOT NULL default '0',
						valueattributchar char(255) collate utf8_unicode_ci default NULL,
						PRIMARY KEY  (idattributchar),
						KEY idpetiteannonce_idx (idpetiteannonce),
						KEY idattribut_idx (idattribut)";
			// On v�rifie si la table attributchar n'existe pas
			createTable(ANNONCES_TABLE_ATTRIBUTCHAR,$champattributchar);


			$champattributdate = "idattributdate int(11) NOT NULL auto_increment,
						flagvalidattributdate enum('deleted','moderated','valid') collate utf8_unicode_ci default 'valid',
						idpetiteannonce int(11) NOT NULL default '0',
						idattribut int(11) NOT NULL default '0',
						valueattributdate date default NULL,
						PRIMARY KEY  (idattributdate),
						KEY idpetiteannonce_idx (idpetiteannonce),
						KEY idattribut_idx (idattribut)";
			// On v�rifie si la table attributdate n'existe pas
			createTable(ANNONCES_TABLE_ATTRIBUTDATE,$champattributdate);


			$champattributdec = "idattributdec int(11) NOT NULL auto_increment,
						flagvalidattributdec enum('deleted','moderated','valid') collate utf8_unicode_ci default 'valid',
						idpetiteannonce int(11) NOT NULL default '0',
						idattribut int(11) NOT NULL default '0',
						valueattributdec decimal(32,2) default NULL,
						PRIMARY KEY  (idattributdec),
						KEY idpetiteannonce_idx (idpetiteannonce),
						KEY idattribut_idx (idattribut)";
			// On v�rifie si la table attributdec n'existe pas
			createTable(ANNONCES_TABLE_ATTRIBUTDEC,$champattributdec);


			$champattributint = "idattributint int(11) NOT NULL auto_increment,
						flagvalidattributint enum('deleted','moderated','valid') collate utf8_unicode_ci default 'valid',
						idpetiteannonce int(11) NOT NULL default '0',
						idattribut int(11) NOT NULL default '0',
						valueattributint int(11) default NULL,
						PRIMARY KEY  (idattributint),
						KEY idpetiteannonce_idx (idpetiteannonce),
						KEY idattribut_idx (idattribut)";
			// On v�rifie si la table attributint n'existe pas
			createTable(ANNONCES_TABLE_ATTRIBUTINT,$champattributint);


			$champattributtext = "idattributtext int(11) NOT NULL auto_increment,
						flagvalidattributtext enum('deleted','moderated','valid') collate utf8_unicode_ci default 'valid',
						idpetiteannonce int(11) NOT NULL default '0',
						idattribut int(11) NOT NULL default '0',
						valueattributtextlong int(11) NOT NULL default '0',
						valueattributtextcourt text collate utf8_unicode_ci,
						PRIMARY KEY  (idattributtext),
						KEY idpetiteannonce_idx (idpetiteannonce),
						KEY idattribut_idx (idattribut),
						KEY valueattributtextlong_idx (valueattributtextlong)";
			// On v�rifie si la table attributtext n'existe pas
			createTable(ANNONCES_TABLE_ATTRIBUTTEXT,$champattributtext);


			$champgeolocalisation = "idsrc int(11) NOT NULL auto_increment,
						iddest int(11) NOT NULL default '0',
						autolocalisation char(255) collate utf8_unicode_ci default NULL,
						adresse char(255) collate utf8_unicode_ci default NULL,
						ville char(255) collate utf8_unicode_ci default NULL,
						departement char(255) collate utf8_unicode_ci default NULL,
						region char(255) collate utf8_unicode_ci default NULL,
						cp int(11) default NULL,
						pays char(255) collate utf8_unicode_ci default NULL,
						token varchar(255) collate utf8_unicode_ci NOT NULL,
						flagvalidgeolocalisation enum('deleted','moderated','valid') collate utf8_unicode_ci default 'moderated',
						latitude decimal(32,16) default NULL,
						longitude decimal(32,16) default NULL,
						PRIMARY KEY  (idsrc,iddest),
						UNIQUE KEY token (token),
						KEY iddest (iddest)";
			// On v�rifie si la table geolocalisation n'existe pas
			createTable(ANNONCES_TABLE_GEOLOCALISATION,$champgeolocalisation);


			$champgroupeattribut = "idgroupeattribut int(11) NOT NULL auto_increment,
						flagvalidgroupeattribut enum('deleted','moderated','valid') collate utf8_unicode_ci default 'moderated',
						nomgroupeattribut char(50) collate utf8_unicode_ci default NULL,
						descriptiongroupeattribut char(255) collate utf8_unicode_ci default NULL,
						PRIMARY KEY  (idgroupeattribut)";
			// On v�rifie si la table groupeattribut n'existe pas
			createTable(ANNONCES_TABLE_GROUPEATTRIBUT,$champgroupeattribut);


			$champgroupeattributattribut = "idattribut int(11) NOT NULL default '0',
						idgroupeattribut int(11) NOT NULL default '0',
						flagvalidgroupeattribut_attribut enum('deleted','moderated','valid') collate utf8_unicode_ci default 'moderated',
						PRIMARY KEY  (idattribut,idgroupeattribut),
						KEY `idgroupeattribut (idgroupeattribut)";
			// On v�rifie si la table groupeattributattribut n'existe pas
			createTable(ANNONCES_TABLE_GROUPEATTRIBUTATTRIBUT,$champgroupeattributattribut);


			$champoption = "idoption int(11) NOT NULL auto_increment,
					flagvalidoption enum('deleted','moderated','valid') collate utf8_unicode_ci default 'moderated',
					labeloption char(50) collate utf8_unicode_ci default NULL,
					nomoption varchar(1000) collate utf8_unicode_ci default NULL,
					PRIMARY KEY  (idoption)";
			// On v�rifie si la table option n'existe pas
		//	NOT USED FROM DB VERSION 18


			$champpasserelle = "idpasserelle int(11) NOT NULL auto_increment,
						flagvalidpasserelle enum('deleted','moderated','valid') collate utf8_unicode_ci default 'moderated',
						typeexport enum('csv','xml') collate utf8_unicode_ci NOT NULL default 'csv',
						nompasserelle char(255) collate utf8_unicode_ci default NULL,
						nomexport char(255) collate utf8_unicode_ci default NULL,
						host char(255) collate utf8_unicode_ci default NULL,
						user char(255) collate utf8_unicode_ci default NULL,
						pass char(255) collate utf8_unicode_ci default NULL,
						structure text collate utf8_unicode_ci,
						separateurtexte char(1) collate utf8_unicode_ci default NULL,
						separateurchamp char(10) collate utf8_unicode_ci default NULL,
						separateurligne char(10) collate utf8_unicode_ci default NULL,
						PRIMARY KEY  (idpasserelle),
						UNIQUE KEY nompasserelle (nompasserelle),
						KEY typeexport (typeexport)";
			// On v�rifie si la table passerelle n'existe pas
			createTable(ANNONCES_TABLE_PASSERELLE,$champpasserelle);


			$champphotos = "idphotos int(11) NOT NULL auto_increment,
						flagvalidphotos enum('deleted','moderated','valid') collate utf8_unicode_ci default 'moderated',
						idpetiteannonce int(11) default NULL,
						lot char(20) collate utf8_unicode_ci default NULL,
						original char(255) collate utf8_unicode_ci default NULL,
						titre char(255) collate utf8_unicode_ci default NULL,
						description char(255) collate utf8_unicode_ci default NULL,
						autoinsert datetime default NULL,
						token varchar(255) collate utf8_unicode_ci NOT NULL,
						PRIMARY KEY  (idphotos),
						KEY idpetiteannonce_idx (idpetiteannonce)";
			// On v�rifie si la table photos n'existe pas
			createTable(ANNONCES_TABLE_PHOTOS,$champphotos);


			$champtempphoto = "numphoto int(11),
						PRIMARY KEY  (numphoto)";
			// On v�rifie si la table tempphoto n'existe pas
			createTable(ANNONCES_TABLE_TEMPPHOTO,$champtempphoto);


			$champtxt = "idtxt int(11) NOT NULL auto_increment,
						flagvalidtxt enum('deleted','moderated','valid') collate utf8_unicode_ci default NULL,
						txtlong text collate utf8_unicode_ci,
						PRIMARY KEY  (idtxt)";
			// On v�rifie si la table txt n'existe pas
			createTable(ANNONCES_TABLE_TXT,$champtxt);
		}
		annonces_insertions();

	$geoloc_table = "
CREATE TABLE wp_ctlg_petiteannonce__geolocalisation (
  idsrc int(11) NOT NULL auto_increment,
  iddest int(11) NOT NULL default '0',
  autolocalisation char(255) collate utf8_unicode_ci default NULL,
  adresse char(255) collate utf8_unicode_ci default NULL,
  ville char(255) collate utf8_unicode_ci default NULL,
  departement char(255) collate utf8_unicode_ci default NULL,
  region char(255) collate utf8_unicode_ci default NULL,
  cp char(20) default NULL,
  pays char(255) collate utf8_unicode_ci default NULL,
  token varchar(255) collate utf8_unicode_ci NOT NULL,
  flagvalidgeolocalisation enum('deleted','moderated','valid') collate utf8_unicode_ci default 'moderated',
  latitude decimal(32,16) default NULL,
  longitude decimal(32,16) default NULL,
  PRIMARY KEY (idsrc,iddest),
  UNIQUE KEY token (token),
  KEY iddest (iddest)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
	dbDelta($geoloc_table);
}
?>