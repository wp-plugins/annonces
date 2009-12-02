<?php

/*	Structure de la table `_ctlg_petiteannonce`	*/
$create_small_ad_table[$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce'] = "CREATE TABLE `".$wpdb->prefix.small_ad_table_prefix_AOS."petiteannonce` (
  `idpetiteannonce` int(11) NOT NULL auto_increment,
  `flagvalidpetiteannonce` enum('deleted','moderated','valid') collate utf8_unicode_ci default 'moderated',
  `idgroupeattribut` int(11) NOT NULL default '0',
  `titre` char(64) collate utf8_unicode_ci default NULL,
  `aexporter` enum('oui','non') NOT NULL default 'oui',
  `referenceagencedubien` varchar(20) collate utf8_unicode_ci default NULL,
  `autoinsert` datetime default NULL,
  `autolastmodif` datetime default NULL,
  PRIMARY KEY  (`idpetiteannonce`),
  KEY `idgroupeattribut_idx` (`idgroupeattribut`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; ";


/*	Structure de la table `_ctlg_petiteannonce__attribut`	*/
$create_small_ad_table[$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__attribut'] = "CREATE TABLE `".$wpdb->prefix.small_ad_table_prefix_AOS."petiteannonce__attribut` (
  `idattribut` int(11) NOT NULL auto_increment,
  `flagvalidattribut` enum('deleted','moderated','valid') collate utf8_unicode_ci default 'moderated',
  `flagvisibleattribut` enum('oui','non') collate utf8_unicode_ci default NULL,
  `typeattribut` varchar(4) collate utf8_unicode_ci default NULL,
  `labelattribut` char(50) collate utf8_unicode_ci default NULL,
  `nomattribut` char(70) collate utf8_unicode_ci default NULL,
  `measureunit` char(10) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`idattribut`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;";


/*	Structure de la table `_ctlg_petiteannonce__attributchar`	*/
$create_small_ad_table[$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__attributchar'] = "CREATE TABLE `".$wpdb->prefix.small_ad_table_prefix_AOS."petiteannonce__attributchar` (
  `idattributchar` int(11) NOT NULL auto_increment,
  `flagvalidattributchar` enum('deleted','moderated','valid') collate utf8_unicode_ci default 'valid',
  `idpetiteannonce` int(11) NOT NULL default '0',
  `idattribut` int(11) NOT NULL default '0',
  `valueattributchar` char(255) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`idattributchar`),
  KEY `idpetiteannonce_idx` (`idpetiteannonce`),
  KEY `idattribut_idx` (`idattribut`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;";


/*	Structure de la table `_ctlg_petiteannonce__attributdate`	*/
$create_small_ad_table[$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__attributdate'] = "CREATE TABLE `".$wpdb->prefix.small_ad_table_prefix_AOS."petiteannonce__attributdate` (
  `idattributdate` int(11) NOT NULL auto_increment,
  `flagvalidattributdate` enum('deleted','moderated','valid') collate utf8_unicode_ci default 'valid',
  `idpetiteannonce` int(11) NOT NULL default '0',
  `idattribut` int(11) NOT NULL default '0',
  `valueattributdate` date default NULL,
  PRIMARY KEY  (`idattributdate`),
  KEY `idpetiteannonce_idx` (`idpetiteannonce`),
  KEY `idattribut_idx` (`idattribut`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;";


/*	Structure de la table `_ctlg_petiteannonce__attributdec`	*/
$create_small_ad_table[$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__attributdec'] = "CREATE TABLE `".$wpdb->prefix.small_ad_table_prefix_AOS."petiteannonce__attributdec` (
  `idattributdec` int(11) NOT NULL auto_increment,
  `flagvalidattributdec` enum('deleted','moderated','valid') collate utf8_unicode_ci default 'valid',
  `idpetiteannonce` int(11) NOT NULL default '0',
  `idattribut` int(11) NOT NULL default '0',
  `valueattributdec` decimal(32,2) default NULL,
  PRIMARY KEY  (`idattributdec`),
  KEY `idpetiteannonce_idx` (`idpetiteannonce`),
  KEY `idattribut_idx` (`idattribut`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;";


/*	Structure de la table `_ctlg_petiteannonce__attributint`	*/
$create_small_ad_table[$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__attributint'] = "CREATE TABLE `".$wpdb->prefix.small_ad_table_prefix_AOS."petiteannonce__attributint` (
  `idattributint` int(11) NOT NULL auto_increment,
  `flagvalidattributint` enum('deleted','moderated','valid') collate utf8_unicode_ci default 'valid',
  `idpetiteannonce` int(11) NOT NULL default '0',
  `idattribut` int(11) NOT NULL default '0',
  `valueattributint` int(11) default NULL,
  PRIMARY KEY  (`idattributint`),
  KEY `idpetiteannonce_idx` (`idpetiteannonce`),
  KEY `idattribut_idx` (`idattribut`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;";


/*	Structure de la table `_ctlg_petiteannonce__attributtext`	*/
$create_small_ad_table[$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__attributtext'] = "CREATE TABLE `".$wpdb->prefix.small_ad_table_prefix_AOS."petiteannonce__attributtext` (
  `idattributtext` int(11) NOT NULL auto_increment,
  `flagvalidattributtext` enum('deleted','moderated','valid') collate utf8_unicode_ci default 'valid',
  `idpetiteannonce` int(11) NOT NULL default '0',
  `idattribut` int(11) NOT NULL default '0',
  `valueattributtextlong` int(11) NOT NULL default '0',
  `valueattributtextcourt` text collate utf8_unicode_ci,
  PRIMARY KEY  (`idattributtext`),
  KEY `idpetiteannonce_idx` (`idpetiteannonce`),
  KEY `idattribut_idx` (`idattribut`),
  KEY `valueattributtextlong_idx` (`valueattributtextlong`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;";


/*	Structure de la table `_ctlg_petiteannonce__geolocalisation`	*/
$create_small_ad_table[$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__geolocalisation'] = "CREATE TABLE `".$wpdb->prefix.small_ad_table_prefix_AOS."petiteannonce__geolocalisation` (
  `idsrc` int(11) NOT NULL auto_increment,
  `iddest` int(11) NOT NULL default '0',
  `autolocalisation` char(255) collate utf8_unicode_ci default NULL,
  `adresse` char(255) collate utf8_unicode_ci default NULL,
  `ville` char(255) collate utf8_unicode_ci default NULL,
  `departement` char(255) collate utf8_unicode_ci default NULL,
  `region` char(255) collate utf8_unicode_ci default NULL,
  `cp` int(11) default NULL,
  `pays` char(255) collate utf8_unicode_ci default NULL,
  `token` varchar(255) collate utf8_unicode_ci NOT NULL,
  `flagvalidgeolocalisation` enum('deleted','moderated','valid') collate utf8_unicode_ci default 'moderated',
  `latitude` decimal(32,16) default NULL,
  `longitude` decimal(32,16) default NULL,
  PRIMARY KEY  (`idsrc`,`iddest`),
  UNIQUE KEY `token` (`token`),
  KEY `iddest` (`iddest`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;";


/*	Structure de la table `_ctlg_petiteannonce__groupeattribut`	*/
$create_small_ad_table[$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__groupeattribut'] = "CREATE TABLE `".$wpdb->prefix.small_ad_table_prefix_AOS."petiteannonce__groupeattribut` (
  `idgroupeattribut` int(11) NOT NULL auto_increment,
  `flagvalidgroupeattribut` enum('deleted','moderated','valid') collate utf8_unicode_ci default 'moderated',
  `nomgroupeattribut` char(50) collate utf8_unicode_ci default NULL,
  `descriptiongroupeattribut` char(255) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`idgroupeattribut`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;";


/*	Structure de la table `_ctlg_petiteannonce__groupeattribut_attribut`	*/
$create_small_ad_table[$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__groupeattribut_attribut'] = "CREATE TABLE `".$wpdb->prefix.small_ad_table_prefix_AOS."petiteannonce__groupeattribut_attribut` (
  `idattribut` int(11) NOT NULL default '0',
  `idgroupeattribut` int(11) NOT NULL default '0',
  `flagvalidgroupeattribut_attribut` enum('deleted','moderated','valid') collate utf8_unicode_ci default 'moderated',
  PRIMARY KEY  (`idattribut`,`idgroupeattribut`),
  KEY `idgroupeattribut` (`idgroupeattribut`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";


/*	Structure de la table `_ctlg_petiteannonce__passerelle`	*/
$create_small_ad_table[$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__passerelle'] = "CREATE TABLE `".$wpdb->prefix.small_ad_table_prefix_AOS."petiteannonce__passerelle` (
  `idpasserelle` int(11) NOT NULL auto_increment,
  `flagvalidpasserelle` enum('deleted','moderated','valid') collate utf8_unicode_ci default 'moderated',
  `nompasserelle` char(255) collate utf8_unicode_ci default NULL,
  `nomexport` char(255) collate utf8_unicode_ci default NULL,
  `host` char(255) collate utf8_unicode_ci default NULL,
  `user` char(255) collate utf8_unicode_ci default NULL,
  `pass` char(255) collate utf8_unicode_ci default NULL,
  `structure` text collate utf8_unicode_ci,
  `separateurtexte` char(1) collate utf8_unicode_ci default NULL,
  `separateurchamp` char(10) collate utf8_unicode_ci default NULL,
  `separateurligne` char(10) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`idpasserelle`),
  UNIQUE KEY `nompasserelle` (`nompasserelle`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";


/*	Structure de la table `_ctlg_petiteannonce__photos`	*/
$create_small_ad_table[$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__photos'] = "CREATE TABLE `".$wpdb->prefix.small_ad_table_prefix_AOS."petiteannonce__photos` (
  `idphotos` int(11) NOT NULL auto_increment,
  `flagvalidphotos` enum('deleted','moderated','valid') collate utf8_unicode_ci default 'moderated',
  `idpetiteannonce` int(11) default NULL,
  `lot` char(20) collate utf8_unicode_ci default NULL,
  `original` char(255) collate utf8_unicode_ci default NULL,
  `titre` char(255) collate utf8_unicode_ci default NULL,
  `description` char(255) collate utf8_unicode_ci default NULL,
  `autoinsert` datetime default NULL,
  `token` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`idphotos`),
  KEY `idpetiteannonce_idx` (`idpetiteannonce`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;";


/*	Structure de la table `_ctlg_petiteannonce__txt`	*/
$create_small_ad_table[$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__txt'] = "CREATE TABLE `".$wpdb->prefix.small_ad_table_prefix_AOS."petiteannonce__txt` (
  `idtxt` int(11) NOT NULL auto_increment,
  `flagvalidtxt` enum('deleted','moderated','valid') collate utf8_unicode_ci default NULL,
  `txtlong` text collate utf8_unicode_ci,
  PRIMARY KEY  (`idtxt`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;";


?>