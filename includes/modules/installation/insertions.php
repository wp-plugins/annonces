<?php

function annonces_insertions($insertions = null)
{
	require_once(ANNONCES_LIB_PLUGIN_DIR . 'version/version.class.php');
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	global $wpdb;

		$sujet = htmlentities('Questions sur l\'annonce %id_annonce% de la part de %nom%');
		$txt = htmlentities('%message%<br/><br/><br/>Coordonn�es du contact :<br/><br/>Nom : %nom%<br/>T�l�phone : %tel%<br/>Email : %mail%<br/><br/><br/>PS : Tant que vous n\'aurez pas r�pondu, la personne qui vous a contact� ne connaitra pas votre adresse email.');
		$html = htmlentities('%message%<br/><br/><br/>Coordonn�es du contact :<br/><br/>Nom : %nom%<br/>T�l�phone : %tel%<br/>Email : %mail%<br/><br/><br/>PS : Tant que vous n\'aurez pas r�pondu, la personne qui vous a contact� ne connaitra pas votre adresse email.');

	if(version::getVersion() <= 1)
	{
		$sql = "INSERT INTO " . ANNONCES_TABLE_VERSION . " (id, nomVersion, version) VALUES
			('1', 'annonces_version', 1);";
		$wpdb->query($sql);

		$sql = "INSERT INTO " . ANNONCES_TABLE_ATTRIBUT . " (idattribut, flagvalidattribut, flagvisibleattribut, typeattribut, labelattribut, nomattribut, measureunit) VALUES
			(1, 'moderated', 'non', 'CHAR', 'IdentifiantAgence', 'Identifiant agence', NULL),
			(2, 'moderated', 'oui', 'CHAR', 'ReferenceAgenceDuBien', 'R�f�rence agence du bien', NULL),
			(3, 'moderated', 'oui', 'CHAR', 'TypeAnnonce', 'Type d\'annonce', NULL),
			(4, 'valid', 'oui', 'CHAR', 'TypeBien', 'Type de bien', NULL),
			(5, 'moderated', 'non', 'CHAR', 'CP', 'CP', NULL),
			(6, 'moderated', 'non', 'CHAR', 'Ville', 'Ville', NULL),
			(7, 'moderated', 'non', 'CHAR', 'Pays', 'Pays', NULL),
			(8, 'moderated', 'non', 'CHAR', 'Adresse', 'Adresse', NULL),
			(9, 'moderated', 'non', 'CHAR', 'QuartierProximite', 'Quartier/Proximit�', NULL),
			(10, 'moderated', 'non', 'CHAR', 'ActivitesCommerciales', 'Activit�s commerciales', NULL),
			(11, 'valid', 'oui', 'DEC', 'PrixLoyerPrixDeCession', 'Prix/Loyer/Prix de cession', NULL),
			(12, 'moderated', 'non', 'DEC', 'LoyerMoisMurs', 'Loyer/mois murs', NULL),
			(13, 'moderated', 'non', 'CHAR', 'LoyerCC', 'Loyer CC', NULL),
			(14, 'moderated', 'non', 'CHAR', 'LoyerHT', 'Loyer HT', NULL),
			(15, 'moderated', 'non', 'DEC', 'Honnoraires', 'Honnoraires', NULL),
			(16, 'moderated', 'non', 'DEC', 'Surface', 'Surface', 'm�'),
			(17, 'valid', 'oui', 'DEC', 'SFTerrain', 'Surface terrain', 'm�'),
			(18, 'moderated', 'non', 'INT', 'NBDePieces', 'NB de pi�ces', NULL),
			(19, 'moderated', 'non', 'INT', 'NBDeChambres', 'NB de chambres', NULL),
			(20, 'moderated', 'non', 'CHAR', 'Libelle', 'Libell�', NULL),
			(21, 'valid', 'oui', 'TEXT', 'Descriptif', 'Descriptif', NULL),
			(22, 'moderated', 'non', 'DATE', 'DateDeDisponibilite', 'Date de disponibilit�', NULL),
			(23, 'moderated', 'non', 'DEC', 'Charges', 'Charges', NULL),
			(24, 'moderated', 'non', 'INT', 'Etage', 'Etage', NULL),
			(25, 'moderated', 'non', 'INT', 'NBEtages', 'NB d', NULL),
			(26, 'moderated', 'non', 'CHAR', 'Meuble', 'Meubl�', NULL),
			(27, 'moderated', 'non', 'INT', 'AnneeDeConstruction', 'Ann�e de construction', NULL),
			(28, 'moderated', 'non', 'CHAR', 'RefaitANeuf', 'Refait � neuf', NULL),
			(29, 'moderated', 'non', 'INT', 'NBDeSallesDeBain', 'NB de salles de bain', NULL),
			(30, 'moderated', 'non', 'INT', 'NBDeSallesEau', 'NB de salles d', NULL),
			(31, 'moderated', 'non', 'INT', 'NBDeWC', 'NB de WC', NULL),
			(32, 'moderated', 'non', 'CHAR', 'WCSepares', 'WC s�par�s', NULL),
			(33, 'moderated', 'non', 'INT', 'TypeDeChauffage', 'Type de chauffage', NULL),
			(34, 'moderated', 'non', 'INT', 'TypeDeCuisine', 'Type de cuisine', NULL),
			(35, 'moderated', 'non', 'CHAR', 'OrientationSud', 'Orientation sud', NULL),
			(36, 'moderated', 'non', 'CHAR', 'OrientationEst', 'Orientation est', NULL),
			(37, 'moderated', 'non', 'CHAR', 'OrientationOuest', 'Orientation ouest', NULL),
			(38, 'moderated', 'non', 'CHAR', 'OrientationNord', 'Orientation nord', NULL),
			(39, 'moderated', 'non', 'INT', 'NBBalcons', 'NB balcons', NULL),
			(40, 'moderated', 'non', 'DEC', 'SFBalcon', 'SF Balcon', NULL),
			(41, 'moderated', 'non', 'CHAR', 'Ascenseur', 'Ascenseur', NULL),
			(42, 'moderated', 'non', 'CHAR', 'Cave', 'Cave', NULL),
			(43, 'moderated', 'non', 'INT', 'NBDeParkings', 'NB de parkings', NULL),
			(44, 'moderated', 'non', 'INT', 'NBDeBoxes', 'NB de boxes', NULL),
			(45, 'moderated', 'non', 'CHAR', 'Digicode', 'Digicode', NULL),
			(46, 'moderated', 'non', 'CHAR', 'Interphone', 'Interphone', NULL),
			(47, 'moderated', 'non', 'CHAR', 'Gardien', 'Gardien', NULL),
			(48, 'moderated', 'non', 'CHAR', 'Terrasse', 'Terrasse', NULL),
			(49, 'moderated', 'non', 'DEC', 'PrixSemaineBasseSaison', 'Prix semaine / basse saison', NULL),
			(50, 'moderated', 'non', 'DEC', 'PrixQuinzaineBasseSaison', 'Prix quinzaine / basse saison', NULL),
			(51, 'moderated', 'non', 'DEC', 'PrixMoisBasseSaison', 'Prix mois / basse saison', NULL),
			(52, 'moderated', 'non', 'DEC', 'PrixSemaineHauteSaison', 'Prix semaine / haute saison', NULL),
			(53, 'moderated', 'non', 'DEC', 'PrixQuinzaineHauteSaison', 'Prix quinzaine / haute saison', NULL),
			(54, 'moderated', 'non', 'DEC', 'PrixMoisHauteSaison', 'Prix mois / haute saison', NULL),
			(55, 'moderated', 'non', 'INT', 'NBDePersonnes', 'NB de personnes', NULL),
			(56, 'moderated', 'non', 'CHAR', 'TypeDeResidence', 'Type de r�sidence', NULL),
			(57, 'moderated', 'non', 'CHAR', 'Situation', 'Situation', NULL),
			(58, 'moderated', 'non', 'INT', 'NBDeCouverts', 'NB de couverts', NULL),
			(59, 'moderated', 'non', 'INT', 'NBDeLitsDoubles', 'NB de lits doubles', NULL),
			(60, 'moderated', 'non', 'INT', 'NBDeLitsSimples', 'NB de lits simples', NULL),
			(61, 'moderated', 'non', 'CHAR', 'Alarme', 'Alarme', NULL),
			(62, 'moderated', 'non', 'CHAR', 'CableTV', 'C�ble TV', NULL),
			(63, 'moderated', 'non', 'CHAR', 'Calme', 'Calme', NULL),
			(64, 'moderated', 'non', 'CHAR', 'Climatisation', 'Climatisation', NULL),
			(65, 'moderated', 'non', 'CHAR', 'Piscine', 'Piscine', NULL),
			(66, 'moderated', 'non', 'CHAR', 'AmenagementPourHandicapes', 'Am�nagement pour handicap�s', NULL),
			(67, 'moderated', 'non', 'CHAR', 'AnimauxAcceptes', 'Animaux accept�s', NULL),
			(68, 'moderated', 'non', 'CHAR', 'Cheminee', 'Chemin�e', NULL),
			(69, 'moderated', 'non', 'CHAR', 'Congelateur', 'Cong�lateur', NULL),
			(70, 'moderated', 'non', 'CHAR', 'Four', 'Four', NULL),
			(71, 'moderated', 'non', 'CHAR', 'LaveVaisselle', 'Lave-vaisselle', NULL),
			(72, 'moderated', 'non', 'CHAR', 'MicroOndes', 'Micro-ondes', NULL),
			(73, 'moderated', 'non', 'CHAR', 'Placards', 'Placards', NULL),
			(74, 'moderated', 'non', 'CHAR', 'Telephone', 'T�l�phone', NULL),
			(75, 'moderated', 'non', 'CHAR', 'ProcheLac', 'Proche lac', NULL),
			(76, 'moderated', 'non', 'CHAR', 'ProcheTennis', 'Proche tennis', NULL),
			(77, 'moderated', 'non', 'CHAR', 'ProchePistesDeSki', 'Proche pistes de ski', NULL),
			(78, 'moderated', 'non', 'CHAR', 'VueDegagee', 'Vue d�gag�e', NULL),
			(79, 'moderated', 'non', 'DEC', 'ChiffreAffaire', 'Chiffre d\'affaire', NULL),
			(80, 'moderated', 'non', 'DEC', 'LongueurFacade', 'Longueur fa�ade (m)', NULL),
			(81, 'moderated', 'non', 'CHAR', 'Duplex', 'Duplex', NULL),
			(82, 'moderated', 'non', 'CHAR', 'Publications', 'Publications', NULL),
			(83, 'moderated', 'non', 'CHAR', 'MandatEnExclusivite', 'Mandat en exclusivit�', NULL),
			(84, 'moderated', 'non', 'CHAR', 'CoupDeCoeur', 'Coup de coeur', NULL),
			(85, 'moderated', 'non', 'CHAR', 'Photo1', 'Photo 1', NULL),
			(86, 'moderated', 'non', 'CHAR', 'Photo2', 'Photo 2', NULL),
			(87, 'moderated', 'non', 'CHAR', 'Photo3', 'Photo 3', NULL),
			(88, 'moderated', 'non', 'CHAR', 'Photo4', 'Photo 4', NULL),
			(89, 'moderated', 'non', 'CHAR', 'Photo5', 'Photo 5', NULL),
			(90, 'moderated', 'non', 'CHAR', 'Photo6', 'Photo 6', NULL),
			(91, 'moderated', 'non', 'CHAR', 'Photo7', 'Photo 7', NULL),
			(92, 'moderated', 'non', 'CHAR', 'Photo8', 'Photo 8', NULL),
			(93, 'moderated', 'non', 'CHAR', 'Photo9', 'Photo 9', NULL),
			(94, 'moderated', 'non', 'CHAR', 'TitrePhoto1', 'Titre photo 1', NULL),
			(95, 'moderated', 'non', 'CHAR', 'TitrePhoto2', 'Titre photo 2', NULL),
			(96, 'moderated', 'non', 'CHAR', 'TitrePhoto3', 'Titre photo 3', NULL),
			(97, 'moderated', 'non', 'CHAR', 'TitrePhoto4', 'Titre photo 4', NULL),
			(98, 'moderated', 'non', 'CHAR', 'TitrePhoto5', 'Titre photo 5', NULL),
			(99, 'moderated', 'non', 'CHAR', 'TitrePhoto6', 'Titre photo 6', NULL),
			(100, 'moderated', 'non', 'CHAR', 'TitrePhoto7', 'Titre photo 7', NULL),
			(101, 'moderated', 'non', 'CHAR', 'TitrePhoto8', 'Titre photo 8', NULL),
			(102, 'moderated', 'non', 'CHAR', 'TitrePhoto9', 'Titre photo 9', NULL),
			(103, 'moderated', 'non', 'CHAR', 'PhotoPanoramique', 'Photo panoramique', NULL),
			(104, 'moderated', 'non', 'CHAR', 'URLVisiteVirtuelle', 'URL visite virtuelle', NULL),
			(105, 'moderated', 'non', 'CHAR', 'TelephoneAAfficher', 'T�l�phone � afficher', NULL),
			(106, 'moderated', 'non', 'CHAR', 'ContactAAfficher', 'Contact � afficher', NULL),
			(107, 'moderated', 'non', 'CHAR', 'EmailAAfficher', 'Email � afficher', NULL),
			(108, 'moderated', 'non', 'CHAR', 'CPReelDuBien', 'CP R�el du bien', NULL),
			(109, 'moderated', 'non', 'CHAR', 'VilleReelleDuBien', 'Ville r�elle du bien', NULL),
			(110, 'moderated', 'non', 'CHAR', 'Intercabinet', 'Intercabinet', NULL),
			(111, 'moderated', 'non', 'CHAR', 'IntercabinetPrive', 'Intercabinet prive', NULL),
			(112, 'moderated', 'non', 'CHAR', 'NDeMandat', 'Numero de mandat', NULL),
			(113, 'moderated', 'non', 'DATE', 'DateMandat', 'Date mandat', NULL),
			(114, 'moderated', 'non', 'CHAR', 'NomMandataire', 'Nom mandataire', NULL),
			(115, 'moderated', 'non', 'CHAR', 'PrenomMandataire', 'Pr�nom mandataire', NULL),
			(116, 'moderated', 'non', 'CHAR', 'RaisonSocialeMandataire', 'Raison sociale mandataire', NULL),
			(117, 'moderated', 'non', 'CHAR', 'AdresseMandataire', 'Adresse mandataire', NULL),
			(118, 'moderated', 'non', 'CHAR', 'CPMandataire', 'CP mandataire', NULL),
			(119, 'moderated', 'non', 'CHAR', 'VilleMandataire', 'Ville mandataire', NULL),
			(120, 'moderated', 'non', 'CHAR', 'TelephoneMandataire', 'T�l�phone mandataire', NULL),
			(121, 'moderated', 'non', 'TEXT', 'CommentairesMandataire', 'Commentaires mandataire', NULL),
			(122, 'moderated', 'non', 'TEXT', 'CommentairesPrives', 'Commentaires priv�s', NULL),
			(123, 'moderated', 'non', 'CHAR', 'CodeNegociateur', 'Code n�gociateur', NULL),
			(124, 'moderated', 'non', 'CHAR', 'CodeLangue1', 'Code Langue 1', NULL),
			(125, 'moderated', 'non', 'CHAR', 'ProximiteLangue1', 'Proximit� Langue 1', NULL),
			(126, 'moderated', 'non', 'CHAR', 'LibelleLangue1', 'Libell� Langue 1', NULL),
			(127, 'moderated', 'non', 'TEXT', 'DescriptifLangue1', 'Descriptif Langue 1', NULL),
			(128, 'moderated', 'non', 'CHAR', 'CodeLangue2', 'Code Langue 2', NULL),
			(129, 'moderated', 'non', 'CHAR', 'ProximiteLangue2', 'Proximit� Langue 2', NULL),
			(130, 'moderated', 'non', 'CHAR', 'LibelleLangue2', 'Libell� Langue 2', NULL),
			(131, 'moderated', 'non', 'TEXT', 'DescriptifLangue2', 'Descriptif Langue 2', NULL),
			(132, 'moderated', 'non', 'CHAR', 'CodeLangue3', 'Code Langue 3', NULL),
			(133, 'moderated', 'non', 'CHAR', 'ProximiteLangue3', 'Proximit� Langue 3', NULL),
			(134, 'moderated', 'non', 'CHAR', 'LibelleLangue3', 'Libell� Langue 3', NULL),
			(135, 'moderated', 'non', 'TEXT', 'DescriptifLangue3', 'Descriptif Langue 3', NULL),
			(136, 'moderated', 'non', 'CHAR', 'ChampPersonnalise1', 'Champ personnalis� 1', NULL),
			(137, 'moderated', 'non', 'CHAR', 'ChampPersonnalise2', 'Champ personnalis� 2', NULL),
			(138, 'moderated', 'non', 'CHAR', 'ChampPersonnalise3', 'Champ personnalis� 3', NULL),
			(139, 'moderated', 'non', 'CHAR', 'ChampPersonnalise4', 'Champ personnalis� 4', NULL),
			(140, 'moderated', 'non', 'CHAR', 'ChampPersonnalise5', 'Champ personnalis� 5', NULL),
			(141, 'moderated', 'non', 'CHAR', 'ChampPersonnalise6', 'Champ personnalis� 6', NULL),
			(142, 'moderated', 'non', 'CHAR', 'ChampPersonnalise7', 'Champ personnalis� 7', NULL),
			(143, 'moderated', 'non', 'CHAR', 'ChampPersonnalise8', 'Champ personnalis� 8', NULL),
			(144, 'moderated', 'non', 'CHAR', 'ChampPersonnalise9', 'Champ personnalis� 9', NULL),
			(145, 'moderated', 'non', 'CHAR', 'ChampPersonnalise10', 'Champ personnalis� 10', NULL),
			(146, 'moderated', 'non', 'CHAR', 'ChampPersonnalise11', 'Champ personnalis� 11', NULL),
			(147, 'moderated', 'non', 'CHAR', 'ChampPersonnalise12', 'Champ personnalis� 12', NULL),
			(148, 'moderated', 'non', 'CHAR', 'ChampPersonnalise13', 'Champ personnalis� 13', NULL),
			(149, 'moderated', 'non', 'CHAR', 'ChampPersonnalise14', 'Champ personnalis� 14', NULL),
			(150, 'moderated', 'non', 'CHAR', 'ChampPersonnalise15', 'Champ personnalis� 15', NULL),
			(151, 'moderated', 'non', 'CHAR', 'ChampPersonnalise16', 'Champ personnalis� 16', NULL),
			(152, 'moderated', 'non', 'CHAR', 'ChampPersonnalise17', 'Champ personnalis� 17', NULL),
			(153, 'moderated', 'non', 'CHAR', 'ChampPersonnalise18', 'Champ personnalis� 18', NULL),
			(154, 'moderated', 'non', 'CHAR', 'ChampPersonnalise19', 'Champ personnalis� 19', NULL),
			(155, 'moderated', 'non', 'CHAR', 'ChampPersonnalise20', 'Champ personnalis� 20', NULL),
			(156, 'moderated', 'non', 'CHAR', 'ChampPersonnalise21', 'Champ personnalis� 21', NULL),
			(157, 'moderated', 'non', 'CHAR', 'ChampPersonnalise22', 'Champ personnalis� 22', NULL),
			(158, 'moderated', 'non', 'CHAR', 'ChampPersonnalise23', 'Champ personnalis� 23', NULL),
			(159, 'moderated', 'non', 'CHAR', 'ChampPersonnalise24', 'Champ personnalis� 24', NULL),
			(160, 'moderated', 'non', 'CHAR', 'ChampPersonnalise25', 'Champ personnalis� 25', NULL),
			(161, 'moderated', 'non', 'DEC', 'DepotDeGarantie', 'D�p�t de garantie', NULL),
			(162, 'moderated', 'non', 'CHAR', 'Recent', 'R�cent', NULL),
			(163, 'moderated', 'non', 'CHAR', 'TravauxAPrevoir', 'Travaux � pr�voir', NULL),
			(164, 'moderated', 'non', 'CHAR', 'Photo10', 'Photo 10', NULL),
			(165, 'moderated', 'non', 'CHAR', 'Photo11', 'Photo 11', NULL),
			(166, 'moderated', 'non', 'CHAR', 'Photo12', 'Photo 12', NULL),
			(167, 'moderated', 'non', 'CHAR', 'Photo13', 'Photo 13', NULL),
			(168, 'moderated', 'non', 'CHAR', 'Photo14', 'Photo 14', NULL),
			(169, 'moderated', 'non', 'CHAR', 'Photo15', 'Photo 15', NULL),
			(170, 'moderated', 'non', 'CHAR', 'Photo16', 'Photo 16', NULL),
			(171, 'moderated', 'non', 'CHAR', 'Photo17', 'Photo 17', NULL),
			(172, 'moderated', 'non', 'CHAR', 'Photo18', 'Photo 18', NULL),
			(173, 'moderated', 'non', 'CHAR', 'Photo19', 'Photo 19', NULL),
			(174, 'moderated', 'non', 'CHAR', 'Photo20', 'Photo 20', NULL),
			(175, 'moderated', 'non', 'CHAR', 'IdentifiantTechnique', 'Identifiant technique', NULL),
			(176, 'moderated', 'non', 'INT', 'ConsommationEnergie', 'Consommation �nergie', NULL),
			(177, 'moderated', 'non', 'CHAR', 'BilanConsommationEnergie', 'Bilan consommation �nergie', NULL),
			(178, 'moderated', 'non', 'INT', 'EmissionsGES', 'Emissions GES', NULL),
			(179, 'moderated', 'non', 'CHAR', 'BilanEmissionGES', 'Bilan �mission GES', NULL),
			(180, 'moderated', 'non', 'INT', 'IdentifiantQuartier', 'Identifiant quartier', NULL),
			(181, 'moderated', 'non', 'CHAR', 'SousTypeDeBien', 'Sous type de bien', NULL),
			(182, 'moderated', 'non', 'CHAR', 'PeriodesDeDisponibilite', 'P�riodes de disponibilit�', NULL),
			(183, 'moderated', 'non', 'CHAR', 'PeriodesBasseSaison', 'P�riodes basse saison', NULL),
			(184, 'moderated', 'non', 'DEC', 'RenteMensuelle', 'P�riodes haute saison', NULL),
			(185, 'moderated', 'non', 'CHAR', 'PeriodesHauteSaison', 'Prix du bouquet', NULL),
			(186, 'moderated', 'non', 'DEC', 'PrixDuBouquet', 'Rente mensuelle', NULL),
			(187, 'moderated', 'non', 'INT', 'AgeDehomme', 'Age de l\'homme', NULL),
			(188, 'moderated', 'non', 'INT', 'AgeDeLaFemme', 'Age de la femme', NULL),
			(189, 'moderated', 'non', 'CHAR', 'Entree', 'Entr�e', NULL),
			(190, 'moderated', 'non', 'CHAR', 'Residence', 'R�sidence', NULL),
			(191, 'moderated', 'non', 'CHAR', 'Parquet', 'Parquet', NULL),
			(192, 'moderated', 'non', 'CHAR', 'VisAVis', 'Vis-�-vis', NULL),
			(193, 'moderated', 'non', 'CHAR', 'TransportLigne', 'Transport : Ligne', NULL),
			(194, 'moderated', 'non', 'CHAR', 'TransportStation', 'Transport : Station', NULL),
			(195, 'moderated', 'non', 'INT', 'DureeBail', 'Dur�e bail', NULL),
			(196, 'moderated', 'non', 'INT', 'PlacesEnSalle', 'Places en salle', NULL),
			(197, 'moderated', 'non', 'CHAR', 'MonteCharge', 'Monte charge', NULL),
			(198, 'moderated', 'non', 'CHAR', 'Quai', 'Quai', NULL),
			(199, 'moderated', 'non', 'INT', 'NombreDeBureaux', 'Nombre de bureaux', NULL),
			(200, 'moderated', 'non', 'DEC', 'PrixDuDroitEntree', 'Prix du droit d\'entr�e', NULL),
			(201, 'moderated', 'non', 'CHAR', 'PrixMasque', 'Prix masqu�', NULL),
			(202, 'moderated', 'non', 'DEC', 'LoyerAnnuelGlobal', 'Loyer annuel global', NULL),
			(203, 'moderated', 'non', 'DEC', 'ChargesAnnuellesGlobales', 'Charges annuelles globales', NULL),
			(204, 'moderated', 'non', 'DEC', 'LoyerAnnuelAuM2', 'Loyer annuel au m�', NULL),
			(205, 'moderated', 'non', 'DEC', 'ChargesAnnuellesAuM2', 'Charges annuelles au m2', NULL),
			(206, 'moderated', 'non', 'CHAR', 'ChargesMensuellesHT', 'Charges mensuelles HT', NULL),
			(207, 'moderated', 'non', 'CHAR', 'LoyerAnnuelCC', 'Loyer annuel CC', NULL),
			(208, 'moderated', 'non', 'CHAR', 'LoyerAnnuelHT', 'Loyer annuel HT', NULL),
			(209, 'moderated', 'non', 'CHAR', 'ChargesAnnuellesHT', 'Charges annuelles HT', NULL),
			(210, 'moderated', 'non', 'CHAR', 'LoyerAnnuelAuM2CC', 'Loyer annuel au m2 CC', NULL),
			(211, 'moderated', 'non', 'CHAR', 'LoyerAnnuelAuM2HT', 'Loyer annuel au m2 HT', NULL),
			(212, 'moderated', 'non', 'CHAR', 'ChargesAnnuellesAuM2HT', 'Charges annuelles au m2 HT', NULL),
			(213, 'moderated', 'non', 'CHAR', 'Divisible', 'Divisible', NULL),
			(214, 'moderated', 'non', 'DEC', 'SurfaceDivisibleMinimale', 'Surface divisible minimale', NULL),
			(215, 'moderated', 'non', 'DEC', 'SurfaceDivisibleMaximale', 'Surface divisible maximale', NULL);";
		$wpdb->query($wpdb->prepare($sql, array() ));

		$sql = "INSERT INTO " . ANNONCES_TABLE_GROUPEATTRIBUT . " (idgroupeattribut, flagvalidgroupeattribut, nomgroupeattribut, descriptiongroupeattribut) VALUES
			(1, 'valid', 'Immobilier', 'Annonce Immobili&egrave;re'),
			(2, 'moderated', 'Vehicule', 'Annonce Automobile');";
		$wpdb->query($wpdb->prepare($sql, array() ));

		$sql = "INSERT INTO " . ANNONCES_TABLE_GROUPEATTRIBUTATTRIBUT . " (idattribut, idgroupeattribut, flagvalidgroupeattribut_attribut) VALUES
			(1, 1, 'valid'),
			(2, 1, 'valid'),
			(3, 1, 'valid'),
			(4, 1, 'valid'),
			(5, 1, 'valid'),
			(6, 1, 'valid'),
			(7, 1, 'valid'),
			(8, 1, 'valid'),
			(9, 1, 'valid'),
			(10, 1, 'valid'),
			(11, 1, 'valid'),
			(12, 1, 'valid'),
			(13, 1, 'valid'),
			(14, 1, 'valid'),
			(15, 1, 'valid'),
			(16, 1, 'valid'),
			(17, 1, 'valid'),
			(18, 1, 'valid'),
			(19, 1, 'valid'),
			(20, 1, 'valid'),
			(21, 1, 'valid'),
			(22, 1, 'valid'),
			(23, 1, 'valid'),
			(24, 1, 'valid'),
			(25, 1, 'valid'),
			(26, 1, 'valid'),
			(27, 1, 'valid'),
			(28, 1, 'valid'),
			(29, 1, 'valid'),
			(30, 1, 'valid'),
			(31, 1, 'valid'),
			(32, 1, 'valid'),
			(33, 1, 'valid'),
			(34, 1, 'valid'),
			(35, 1, 'valid'),
			(36, 1, 'valid'),
			(37, 1, 'valid'),
			(38, 1, 'valid'),
			(39, 1, 'valid'),
			(40, 1, 'valid'),
			(41, 1, 'valid'),
			(42, 1, 'valid'),
			(43, 1, 'valid'),
			(44, 1, 'valid'),
			(45, 1, 'valid'),
			(46, 1, 'valid'),
			(47, 1, 'valid'),
			(48, 1, 'valid'),
			(49, 1, 'valid'),
			(50, 1, 'valid'),
			(51, 1, 'valid'),
			(52, 1, 'valid'),
			(53, 1, 'valid'),
			(54, 1, 'valid'),
			(55, 1, 'valid'),
			(56, 1, 'valid'),
			(57, 1, 'valid'),
			(58, 1, 'valid'),
			(59, 1, 'valid'),
			(60, 1, 'valid'),
			(61, 1, 'valid'),
			(62, 1, 'valid'),
			(63, 1, 'valid'),
			(64, 1, 'valid'),
			(65, 1, 'valid'),
			(66, 1, 'valid'),
			(67, 1, 'valid'),
			(68, 1, 'valid'),
			(69, 1, 'valid'),
			(70, 1, 'valid'),
			(71, 1, 'valid'),
			(72, 1, 'valid'),
			(73, 1, 'valid'),
			(74, 1, 'valid'),
			(75, 1, 'valid'),
			(76, 1, 'valid'),
			(77, 1, 'valid'),
			(78, 1, 'valid'),
			(79, 1, 'valid'),
			(80, 1, 'valid'),
			(81, 1, 'valid'),
			(82, 1, 'valid'),
			(83, 1, 'valid'),
			(84, 1, 'valid'),
			(85, 1, 'valid'),
			(86, 1, 'valid'),
			(87, 1, 'valid'),
			(88, 1, 'valid'),
			(89, 1, 'valid'),
			(90, 1, 'valid'),
			(91, 1, 'valid'),
			(92, 1, 'valid'),
			(93, 1, 'valid'),
			(94, 1, 'valid'),
			(95, 1, 'valid'),
			(96, 1, 'valid'),
			(97, 1, 'valid'),
			(98, 1, 'valid'),
			(99, 1, 'valid'),
			(100, 1, 'valid'),
			(101, 1, 'valid'),
			(102, 1, 'valid'),
			(103, 1, 'valid'),
			(104, 1, 'valid'),
			(105, 1, 'valid'),
			(106, 1, 'valid'),
			(107, 1, 'valid'),
			(108, 1, 'valid'),
			(109, 1, 'valid'),
			(110, 1, 'valid'),
			(111, 1, 'valid'),
			(112, 1, 'valid'),
			(113, 1, 'valid'),
			(114, 1, 'valid'),
			(115, 1, 'valid'),
			(116, 1, 'valid'),
			(117, 1, 'valid'),
			(118, 1, 'valid'),
			(119, 1, 'valid'),
			(120, 1, 'valid'),
			(121, 1, 'valid'),
			(122, 1, 'valid'),
			(123, 1, 'valid'),
			(124, 1, 'valid'),
			(125, 1, 'valid'),
			(126, 1, 'valid'),
			(127, 1, 'valid'),
			(128, 1, 'valid'),
			(129, 1, 'valid'),
			(130, 1, 'valid'),
			(131, 1, 'valid'),
			(132, 1, 'valid'),
			(133, 1, 'valid'),
			(134, 1, 'valid'),
			(135, 1, 'valid'),
			(136, 1, 'valid'),
			(137, 1, 'valid'),
			(138, 1, 'valid'),
			(139, 1, 'valid'),
			(140, 1, 'valid'),
			(141, 1, 'valid'),
			(142, 1, 'valid'),
			(143, 1, 'valid'),
			(144, 1, 'valid'),
			(145, 1, 'valid'),
			(146, 1, 'valid'),
			(147, 1, 'valid'),
			(148, 1, 'valid'),
			(149, 1, 'valid'),
			(150, 1, 'valid'),
			(151, 1, 'valid'),
			(152, 1, 'valid'),
			(153, 1, 'valid'),
			(154, 1, 'valid'),
			(155, 1, 'valid'),
			(156, 1, 'valid'),
			(157, 1, 'valid'),
			(158, 1, 'valid'),
			(159, 1, 'valid'),
			(160, 1, 'valid'),
			(161, 1, 'valid'),
			(162, 1, 'valid'),
			(163, 1, 'valid'),
			(164, 1, 'valid'),
			(165, 1, 'valid'),
			(166, 1, 'valid'),
			(167, 1, 'valid'),
			(168, 1, 'valid'),
			(169, 1, 'valid'),
			(170, 1, 'valid'),
			(171, 1, 'valid'),
			(172, 1, 'valid'),
			(173, 1, 'valid'),
			(174, 1, 'valid'),
			(175, 1, 'valid'),
			(176, 1, 'valid'),
			(177, 1, 'valid'),
			(178, 1, 'valid'),
			(179, 1, 'valid'),
			(180, 1, 'valid'),
			(181, 1, 'valid'),
			(182, 1, 'valid'),
			(183, 1, 'valid'),
			(184, 1, 'valid'),
			(185, 1, 'valid'),
			(186, 1, 'valid'),
			(187, 1, 'valid'),
			(188, 1, 'valid'),
			(189, 1, 'valid'),
			(190, 1, 'valid'),
			(191, 1, 'valid'),
			(192, 1, 'valid'),
			(193, 1, 'valid'),
			(194, 1, 'valid'),
			(195, 1, 'valid'),
			(196, 1, 'valid'),
			(197, 1, 'valid'),
			(198, 1, 'valid'),
			(199, 1, 'valid'),
			(200, 1, 'valid'),
			(201, 1, 'valid'),
			(202, 1, 'valid'),
			(203, 1, 'valid'),
			(204, 1, 'valid'),
			(205, 1, 'valid'),
			(206, 1, 'valid'),
			(207, 1, 'valid'),
			(208, 1, 'valid'),
			(209, 1, 'valid'),
			(210, 1, 'valid'),
			(211, 1, 'valid'),
			(212, 1, 'valid'),
			(213, 1, 'valid'),
			(214, 1, 'valid'),
			(215, 1, 'valid');";
		$wpdb->query($sql);

		$sql = "INSERT INTO " . ANNONCES_TABLE_TEMPPHOTO . " (`numphoto`) VALUES
			(0);";
		$wpdb->query($sql);

		$sql = "INSERT INTO " . ANNONCES_TABLE_OPTION . " (idoption, flagvalidoption, labeloption, nomoption) VALUES
			(1, 'valid', 'annonces_api_key', ''),
			(2, 'valid', 'annonces_maps_activation', '1'),
			(3, 'valid', 'annonces_photos_activation', '1'),
			(4, 'valid', 'annonces_date_activation', '1'),
			(5, 'valid', 'url_marqueur_courant', 'red-dot_default.png'),
			(6, 'valid', 'url_marqueur_defaut', 'red-dot_default.png'),
			(7, 'valid', 'annonces_marqueur_activation', '1'),
			(8, 'valid', 'theme_activation', '1'),
			(9, 'valid', 'url_radio_toutes_theme_courant', 'toutes_default.png'),
			(10, 'valid','url_radio_terrains_theme_courant', 'terrains_default.png'),
			(11, 'valid', 'url_radio_maisons_theme_courant', 'maisons_default.png'),
			(12, 'valid', 'url_budget_theme_courant', 'budget_default.png'),
			(13, 'valid', 'url_superficie_theme_courant', 'surface_default.png'),
			(14, 'valid', 'url_recherche_theme_courant', 'recherche_default.png'),
			(15, 'valid', 'url_radio_toutes_theme_defaut', 'toutes_default.png'),
			(16, 'valid', 'url_radio_terrains_theme_defaut', 'terrains_default.png'),
			(17, 'valid', 'url_radio_maisons_theme_defaut', 'maisons_default.png'),
			(18, 'valid', 'url_budget_theme_defaut', 'budget_default.png'),
			(19, 'valid', 'url_superficie_theme_defaut', 'surface_default.png'),
			(20, 'valid', 'url_recherche_theme_defaut', 'recherche_default.png')";
		//	NOT USED FROM DB VERSION 18

		version::majVersion();
	}

	if(version::getVersion() <= 2)
	{
		$sql = "UPDATE " . ANNONCES_TABLE_ATTRIBUT . " SET flagvalidattribut='valid' WHERE labelattribut='BilanEmissionGES'";
		$wpdb->query($sql);

		$sql = "UPDATE " . ANNONCES_TABLE_ATTRIBUT . " SET flagvisibleattribut='oui' WHERE labelattribut='BilanEmissionGES'";
		$wpdb->query($sql);

		$sql = "UPDATE " . ANNONCES_TABLE_ATTRIBUT . " SET flagvalidattribut='valid' WHERE labelattribut='BilanConsommationEnergie'";
		$wpdb->query($sql);

		$sql = "UPDATE " . ANNONCES_TABLE_ATTRIBUT . " SET flagvisibleattribut='oui' WHERE labelattribut='BilanConsommationEnergie'";
		$wpdb->query($sql);

		version::majVersion();
	}

	if(version::getVersion() <= 3)
	{
		$sql = "ALTER TABLE " . PREFIXE_ANNONCES . " ADD urlannonce varchar(200)";
		$wpdb->query($sql);

		version::majVersion();
	}

	if(version::getVersion() <= 4)
	{
		$sql = "UPDATE " . ANNONCES_TABLE_OPTION . " SET annonces_expression_url = '%titre_annonce%_%idpetiteannonce%.html'";
		//	NOT USED FROM DB VERSION 18

		$sql = "UPDATE " . ANNONCES_TABLE_ATTRIBUT . " SET measureunit = '&euro;' where labelattribut='PrixLoyerPrixDeCession'";
		$wpdb->query($sql);

		$sql = "UPDATE " . ANNONCES_TABLE_ATTRIBUT . " SET measureunit = 'm&sup2;' where labelattribut='Surface'";
		$wpdb->query($sql);

		$sql = "UPDATE " . ANNONCES_TABLE_ATTRIBUT . " SET measureunit = 'm&sup2;' where labelattribut='SFTerrain'";
		$wpdb->query($sql);

		annonces_options::majUrlAnnonces();

		version::majVersion();
	}

	if(version::getVersion() <= 5)
	{

		$sql = "INSERT INTO " . ANNONCES_TABLE_OPTION . " (idoption, flagvalidoption, labeloption, nomoption) VALUES
			(21, 'valid', 'annonces_email_reception', 'achanger@achanger.achanger'),
			(22, 'valid', 'annonces_sujet_reception', \"" . $sujet . "\"),
			(23, 'valid', 'annonces_txt_reception', \"" . $txt . "\"),
			(24, 'valid', 'annonces_html_reception', \"" . $html . "\"),
			(25, 'valid', 'annonces_email_activation','0'),
			(26, 'valid', 'annonces_expression_url','annonce_%idpetiteannonce%.html'),
			(27, 'valid', 'annonces_page_install', 'annonces'),
			(28, 'valid', 'annonces_url_activation', '0')";
		//	NOT USED FROM DB VERSION 18

		version::majVersion();
	}

	if(version::getVersion() <= 6)
	{
		$sql = "UPDATE " . ANNONCES_TABLE_OPTION . " SET flagvalidoption='deleted' where labeloption='annonces_page_install'";
		//	NOT USED FROM DB VERSION 18

		$sql = "INSERT INTO " . ANNONCES_TABLE_OPTION . " (idoption, flagvalidoption, labeloption, nomoption) VALUES
			(29, 'valid', 'annonces_suffix', '.html')";
		//	NOT USED FROM DB VERSION 18

		version::majVersion();
	}

	if (version::getVersion() <= 7)
	{
		$sql = "UPDATE " . ANNONCES_TABLE_ATTRIBUT . " SET nomattribut = 'Bilan consommation &eacute;nergie' where labelattribut='BilanConsommationEnergie'";
		$wpdb->query($sql);

		$sql = "UPDATE " . ANNONCES_TABLE_ATTRIBUT . " SET nomattribut  = 'Bilan &eacute;mission GES' where labelattribut='BilanEmissionGES'";
		$wpdb->query($sql);

		$sql = "INSERT INTO " . ANNONCES_TABLE_PASSERELLE . " (`idpasserelle`, `flagvalidpasserelle`, `typeexport`, `nompasserelle`, `nomexport`, `host`, `user`, `pass`, `structure`, `separateurtexte`, `separateurchamp`, `separateurligne`) VALUES
			('', 'valid', 'csv', 'SeLoger.com', 'nomExportSeLoger', 'transferts.seloger.com', 'loginSeLoger', 'passSeLoger', 'IdentifiantAgence,ReferenceAgenceDuBien,TypeAnnonce,TypeBien,CP,Ville,Pays,Adresse,QuartierProximite,ActivitesCommerciales,PrixLoyerPrixDeCession,LoyerMoisMurs,LoyerCC,LoyerHT,Honnoraires,Surface,SFTerrain,NBDePieces,NBDeChambres,Libelle,Descriptif,DateDeDisponibilite,Charges,Etage,NBEtages,Meuble,AnneeDeConstruction,RefaitANeuf,NBDeSallesDeBain,NBDeSallesEau,NBDeWC,WCSepares,TypeDeChauffage,TypeDeCuisine,OrientationSud,OrientationEst,OrientationOuest,OrientationNord,NBBalcons,SFBalcon,Ascenseur,Cave,NBDeParkings,NBDeBoxes,Digicode,Interphone,Gardien,Terrasse,PrixSemaineBasseSaison,PrixQuinzaineBasseSaison,PrixMoisBasseSaison,PrixSemaineHauteSaison,PrixQuinzaineHauteSaison,PrixMoisHauteSaison,NBDePersonnes,TypeDeResidence,Situation,NBDeCouverts,NBDeLitsDoubles,NBDeLitsSimples,Alarme,CableTV,Calme,Climatisation,Piscine,AmenagementPourHandicapes,AnimauxAcceptes,Cheminee,Congelateur,Four,LaveVaisselle,MicroOndes,Placards,Telephone,ProcheLac,ProcheTennis,ProchePistesDeSki,VueDegagee,ChiffreAffaire,LongueurFacade,Duplex,Publications,MandatEnExclusivite,CoupDeCoeur,Photo1,Photo2,Photo3,Photo4,Photo5,Photo6,Photo7,Photo8,Photo9,TitrePhoto1,TitrePhoto2,TitrePhoto3,TitrePhoto4,TitrePhoto5,TitrePhoto6,TitrePhoto7,TitrePhoto8,TitrePhoto9,PhotoPanoramique,URLVisiteVirtuelle,TelephoneAAfficher,ContactAAfficher,EmailAAfficher,CPReelDuBien,VilleReelleDuBien,Intercabinet,IntercabinetPrive,NDeMandat,DateMandat,NomMandataire,PrenomMandataire,RaisonSocialeMandataire,AdresseMandataire,CPMandataire,VilleMandataire,TelephoneMandataire,CommentairesMandataire,CommentairesPrives,CodeNegociateur,CodeLangue1,ProximiteLangue1,LibelleLangue1,DescriptifLangue1,CodeLangue2,ProximiteLangue2,LibelleLangue2,DescriptifLangue2,CodeLangue3,ProximiteLangue3,LibelleLangue3,DescriptifLangue3,ChampPersonnalise1,ChampPersonnalise2,ChampPersonnalise3,ChampPersonnalise4,ChampPersonnalise5,ChampPersonnalise6,ChampPersonnalise7,ChampPersonnalise8,ChampPersonnalise9,ChampPersonnalise10,ChampPersonnalise11,ChampPersonnalise12,ChampPersonnalise13,ChampPersonnalise14,ChampPersonnalise15,ChampPersonnalise16,ChampPersonnalise17,ChampPersonnalise18,ChampPersonnalise19,ChampPersonnalise20,ChampPersonnalise21,ChampPersonnalise22,ChampPersonnalise23,ChampPersonnalise24,ChampPersonnalise25,DepotDeGarantie,Recent,TravauxAPrevoir,Photo10,Photo11,Photo12,Photo13,Photo14,Photo15,Photo16,Photo17,Photo18,Photo19,Photo20,IdentifiantTechnique,ConsommationEnergie,BilanConsommationEnergie,EmissionsGES,BilanEmissionGES,IdentifiantQuartier,SousTypeDeBien,PeriodesDeDisponibilite,PeriodesBasseSaison,RenteMensuelle,PeriodesHauteSaison,PrixDuBouquet,AgeDehomme,AgeDeLaFemme,Entree,Residence,Parquet,VisAVis,TransportLigne,TransportStation,DureeBail,PlacesEnSalle,MonteCharge,Quai,NombreDeBureaux,PrixDuDroitEntree,PrixMasque,LoyerAnnuelGlobal,ChargesAnnuellesGlobales,LoyerAnnuelAuM2,ChargesAnnuellesAuM2,ChargesMensuellesHT,LoyerAnnuelCC,LoyerAnnuelHT,ChargesAnnuellesHT,LoyerAnnuelAuM2CC,LoyerAnnuelAuM2HT,ChargesAnnuellesAuM2HT,Divisible,SurfaceDivisibleMinimale,SurfaceDivisibleMaximale', '\"', '!#', '\\r\\n'),
			('', 'valid', 'csv', 'Lesclesdumidi.com', 'nomExportLesClesDuMidi', 'ftp.passimmopro.com', 'loginClesDuMidi', '�ssClesDuMidi', 'IdentifiantAgence,ReferenceAgenceDuBien,TypeAnnonce,TypeBien,CP,Ville,Pays,Adresse,QuartierProximite,ActivitesCommerciales,PrixLoyerPrixDeCession,LoyerMoisMurs,LoyerCC,LoyerHT,Honnoraires,Surface,SFTerrain,NBDePieces,NBDeChambres,Libelle,Descriptif,DateDeDisponibilite,Charges,Etage,NBEtages,Meuble,AnneeDeConstruction,RefaitANeuf,NBDeSallesDeBain,NBDeSallesEau,NBDeWC,WCSepares,TypeDeChauffage,TypeDeCuisine,OrientationSud,OrientationEst,OrientationOuest,OrientationNord,NBBalcons,SFBalcon,Ascenseur,Cave,NBDeParkings,NBDeBoxes,Digicode,Interphone,Gardien,Terrasse,PrixSemaineBasseSaison,PrixQuinzaineBasseSaison,PrixMoisBasseSaison,PrixSemaineHauteSaison,PrixQuinzaineHauteSaison,PrixMoisHauteSaison,NBDePersonnes,TypeDeResidence,Situation,NBDeCouverts,NBDeLitsDoubles,NBDeLitsSimples,Alarme,CableTV,Calme,Climatisation,Piscine,AmenagementPourHandicapes,AnimauxAcceptes,Cheminee,Congelateur,Four,LaveVaisselle,MicroOndes,Placards,Telephone,ProcheLac,ProcheTennis,ProchePistesDeSki,VueDegagee,ChiffreAffaire,LongueurFacade,Duplex,Publications,MandatEnExclusivite,CoupDeCoeur,Photo1,Photo2,Photo3,Photo4,Photo5,Photo6,Photo7,Photo8,Photo9,TitrePhoto1,TitrePhoto2,TitrePhoto3,TitrePhoto4,TitrePhoto5,TitrePhoto6,TitrePhoto7,TitrePhoto8,TitrePhoto9,PhotoPanoramique,URLVisiteVirtuelle,TelephoneAAfficher,ContactAAfficher,EmailAAfficher,CPReelDuBien,VilleReelleDuBien,Intercabinet,IntercabinetPrive,NDeMandat,DateMandat,NomMandataire,PrenomMandataire,RaisonSocialeMandataire,AdresseMandataire,CPMandataire,VilleMandataire,TelephoneMandataire,CommentairesMandataire,CommentairesPrives,CodeNegociateur,CodeLangue1,ProximiteLangue1,LibelleLangue1,DescriptifLangue1,CodeLangue2,ProximiteLangue2,LibelleLangue2,DescriptifLangue2,CodeLangue3,ProximiteLangue3,LibelleLangue3,DescriptifLangue3,ChampPersonnalise1,ChampPersonnalise2,ChampPersonnalise3,ChampPersonnalise4,ChampPersonnalise5,ChampPersonnalise6,ChampPersonnalise7,ChampPersonnalise8,ChampPersonnalise9,ChampPersonnalise10,ChampPersonnalise11,ChampPersonnalise12,ChampPersonnalise13,ChampPersonnalise14,ChampPersonnalise15,ChampPersonnalise16,ChampPersonnalise17,ChampPersonnalise18,ChampPersonnalise19,ChampPersonnalise20,ChampPersonnalise21,ChampPersonnalise22,ChampPersonnalise23,ChampPersonnalise24,ChampPersonnalise25,DepotDeGarantie,Recent,TravauxAPrevoir,Photo10,Photo11,Photo12,Photo13,Photo14,Photo15,Photo16,Photo17,Photo18,Photo19,Photo20,IdentifiantTechnique,ConsommationEnergie,BilanConsommationEnergie,EmissionsGES,BilanEmissionGES,IdentifiantQuartier,SousTypeDeBien,PeriodesDeDisponibilite,PeriodesBasseSaison,RenteMensuelle,PeriodesHauteSaison,PrixDuBouquet,AgeDehomme,AgeDeLaFemme,Entree,Residence,Parquet,VisAVis,TransportLigne,TransportStation,DureeBail,PlacesEnSalle,MonteCharge,Quai,NombreDeBureaux,PrixDuDroitEntree,PrixMasque,LoyerAnnuelGlobal,ChargesAnnuellesGlobales,LoyerAnnuelAuM2,ChargesAnnuellesAuM2,ChargesMensuellesHT,LoyerAnnuelCC,LoyerAnnuelHT,ChargesAnnuellesHT,LoyerAnnuelAuM2CC,LoyerAnnuelAuM2HT,ChargesAnnuellesAuM2HT,Divisible,SurfaceDivisibleMinimale,SurfaceDivisibleMaximale', '\"', '!#', '\\r\\n'),
			('', 'valid', 'csv', 'Refleximmo', 'nomExportRefleximo', 'ftp.refleximmo.com', 'loginRefleximo', 'passRefleximo', 'IdentifiantAgence,ReferenceAgenceDuBien,TypeAnnonce,TypeBien,CP,Ville,Pays,Adresse,QuartierProximite,ActivitesCommerciales,PrixLoyerPrixDeCession,LoyerMoisMurs,LoyerCC,LoyerHT,Honnoraires,Surface,SFTerrain,NBDePieces,NBDeChambres,Libelle,Descriptif,DateDeDisponibilite,Charges,Etage,NBEtages,Meuble,AnneeDeConstruction,RefaitANeuf,NBDeSallesDeBain,NBDeSallesEau,NBDeWC,WCSepares,TypeDeChauffage,TypeDeCuisine,OrientationSud,OrientationEst,OrientationOuest,OrientationNord,NBBalcons,SFBalcon,Ascenseur,Cave,NBDeParkings,NBDeBoxes,Digicode,Interphone,Gardien,Terrasse,PrixSemaineBasseSaison,PrixQuinzaineBasseSaison,PrixMoisBasseSaison,PrixSemaineHauteSaison,PrixQuinzaineHauteSaison,PrixMoisHauteSaison,NBDePersonnes,TypeDeResidence,Situation,NBDeCouverts,NBDeLitsDoubles,NBDeLitsSimples,Alarme,CableTV,Calme,Climatisation,Piscine,AmenagementPourHandicapes,AnimauxAcceptes,Cheminee,Congelateur,Four,LaveVaisselle,MicroOndes,Placards,Telephone,ProcheLac,ProcheTennis,ProchePistesDeSki,VueDegagee,ChiffreAffaire,LongueurFacade,Duplex,Publications,MandatEnExclusivite,CoupDeCoeur,Photo1,Photo2,Photo3,Photo4,Photo5,Photo6,Photo7,Photo8,Photo9,TitrePhoto1,TitrePhoto2,TitrePhoto3,TitrePhoto4,TitrePhoto5,TitrePhoto6,TitrePhoto7,TitrePhoto8,TitrePhoto9,PhotoPanoramique,URLVisiteVirtuelle,TelephoneAAfficher,ContactAAfficher,EmailAAfficher,CPReelDuBien,VilleReelleDuBien,Intercabinet,IntercabinetPrive,NDeMandat,DateMandat,NomMandataire,PrenomMandataire,RaisonSocialeMandataire,AdresseMandataire,CPMandataire,VilleMandataire,TelephoneMandataire,CommentairesMandataire,CommentairesPrives,CodeNegociateur,CodeLangue1,ProximiteLangue1,LibelleLangue1,DescriptifLangue1,CodeLangue2,ProximiteLangue2,LibelleLangue2,DescriptifLangue2,CodeLangue3,ProximiteLangue3,LibelleLangue3,DescriptifLangue3,ChampPersonnalise1,ChampPersonnalise2,ChampPersonnalise3,ChampPersonnalise4,ChampPersonnalise5,ChampPersonnalise6,ChampPersonnalise7,ChampPersonnalise8,ChampPersonnalise9,ChampPersonnalise10,ChampPersonnalise11,ChampPersonnalise12,ChampPersonnalise13,ChampPersonnalise14,ChampPersonnalise15,ChampPersonnalise16,ChampPersonnalise17,ChampPersonnalise18,ChampPersonnalise19,ChampPersonnalise20,ChampPersonnalise21,ChampPersonnalise22,ChampPersonnalise23,ChampPersonnalise24,ChampPersonnalise25,DepotDeGarantie,Recent,TravauxAPrevoir,Photo10,Photo11,Photo12,Photo13,Photo14,Photo15,Photo16,Photo17,Photo18,Photo19,Photo20,IdentifiantTechnique,ConsommationEnergie,BilanConsommationEnergie,EmissionsGES,BilanEmissionGES,IdentifiantQuartier,SousTypeDeBien,PeriodesDeDisponibilite,PeriodesBasseSaison,RenteMensuelle,PeriodesHauteSaison,PrixDuBouquet,AgeDehomme,AgeDeLaFemme,Entree,Residence,Parquet,VisAVis,TransportLigne,TransportStation,DureeBail,PlacesEnSalle,MonteCharge,Quai,NombreDeBureaux,PrixDuDroitEntree,PrixMasque,LoyerAnnuelGlobal,ChargesAnnuellesGlobales,LoyerAnnuelAuM2,ChargesAnnuellesAuM2,ChargesMensuellesHT,LoyerAnnuelCC,LoyerAnnuelHT,ChargesAnnuellesHT,LoyerAnnuelAuM2CC,LoyerAnnuelAuM2HT,ChargesAnnuellesAuM2HT,Divisible,SurfaceDivisibleMinimale,SurfaceDivisibleMaximale', '\"', '!#', '\\r\\n'),
			('', 'valid', 'xml', 'Midi libre', 'nomExportMidiLibre', 'ftp.ubiflow.net', 'loginMidiLibre', 'passMidiLibre', 'IdentifiantAgence,ReferenceAgenceDuBien,TypeAnnonce,TypeBien,CP,Ville,Pays,Adresse,QuartierProximite,ActivitesCommerciales,PrixLoyerPrixDeCession,LoyerMoisMurs,LoyerCC,LoyerHT,Honnoraires,Surface,SFTerrain,NBDePieces,NBDeChambres,Libelle,Descriptif,DateDeDisponibilite,Charges,Etage,NBEtages,Meuble,AnneeDeConstruction,RefaitANeuf,NBDeSallesDeBain,NBDeSallesEau,NBDeWC,WCSepares,TypeDeChauffage,TypeDeCuisine,OrientationSud,OrientationEst,OrientationOuest,OrientationNord,NBBalcons,SFBalcon,Ascenseur,Cave,NBDeParkings,NBDeBoxes,Digicode,Interphone,Gardien,Terrasse,PrixSemaineBasseSaison,PrixQuinzaineBasseSaison,PrixMoisBasseSaison,PrixSemaineHauteSaison,PrixQuinzaineHauteSaison,PrixMoisHauteSaison,NBDePersonnes,TypeDeResidence,Situation,NBDeCouverts,NBDeLitsDoubles,NBDeLitsSimples,Alarme,CableTV,Calme,Climatisation,Piscine,AmenagementPourHandicapes,AnimauxAcceptes,Cheminee,Congelateur,Four,LaveVaisselle,MicroOndes,Placards,Telephone,ProcheLac,ProcheTennis,ProchePistesDeSki,VueDegagee,ChiffreAffaire,LongueurFacade,Duplex,Publications,MandatEnExclusivite,CoupDeCoeur,Photo1,Photo2,Photo3,Photo4,Photo5,Photo6,Photo7,Photo8,Photo9,TitrePhoto1,TitrePhoto2,TitrePhoto3,TitrePhoto4,TitrePhoto5,TitrePhoto6,TitrePhoto7,TitrePhoto8,TitrePhoto9,PhotoPanoramique,URLVisiteVirtuelle,TelephoneAAfficher,ContactAAfficher,EmailAAfficher,CPReelDuBien,VilleReelleDuBien,Intercabinet,IntercabinetPrive,NDeMandat,DateMandat,NomMandataire,PrenomMandataire,RaisonSocialeMandataire,AdresseMandataire,CPMandataire,VilleMandataire,TelephoneMandataire,CommentairesMandataire,CommentairesPrives,CodeNegociateur,CodeLangue1,ProximiteLangue1,LibelleLangue1,DescriptifLangue1,CodeLangue2,ProximiteLangue2,LibelleLangue2,DescriptifLangue2,CodeLangue3,ProximiteLangue3,LibelleLangue3,DescriptifLangue3,ChampPersonnalise1,ChampPersonnalise2,ChampPersonnalise3,ChampPersonnalise4,ChampPersonnalise5,ChampPersonnalise6,ChampPersonnalise7,ChampPersonnalise8,ChampPersonnalise9,ChampPersonnalise10,ChampPersonnalise11,ChampPersonnalise12,ChampPersonnalise13,ChampPersonnalise14,ChampPersonnalise15,ChampPersonnalise16,ChampPersonnalise17,ChampPersonnalise18,ChampPersonnalise19,ChampPersonnalise20,ChampPersonnalise21,ChampPersonnalise22,ChampPersonnalise23,ChampPersonnalise24,ChampPersonnalise25,DepotDeGarantie,Recent,TravauxAPrevoir,Photo10,Photo11,Photo12,Photo13,Photo14,Photo15,Photo16,Photo17,Photo18,Photo19,Photo20,IdentifiantTechnique,ConsommationEnergie,BilanConsommationEnergie,EmissionsGES,BilanEmissionGES,IdentifiantQuartier,SousTypeDeBien,PeriodesDeDisponibilite,PeriodesBasseSaison,RenteMensuelle,PeriodesHauteSaison,PrixDuBouquet,AgeDehomme,AgeDeLaFemme,Entree,Residence,Parquet,VisAVis,TransportLigne,TransportStation,DureeBail,PlacesEnSalle,MonteCharge,Quai,NombreDeBureaux,PrixDuDroitEntree,PrixMasque,LoyerAnnuelGlobal,ChargesAnnuellesGlobales,LoyerAnnuelAuM2,ChargesAnnuellesAuM2,ChargesMensuellesHT,LoyerAnnuelCC,LoyerAnnuelHT,ChargesAnnuellesHT,LoyerAnnuelAuM2CC,LoyerAnnuelAuM2HT,ChargesAnnuellesAuM2HT,Divisible,SurfaceDivisibleMinimale,SurfaceDivisibleMaximale', '\"', '!#', '\\r\\n');";
		$wpdb->query($sql);

		version::majVersion();
	}
	if (version::getVersion() <= 8)
	{
		$sql = $wpdb->prepare("UPDATE " . ANNONCES_TABLE_TEMPPHOTO . "SET numphoto = (SELECT MAX(idpetiteannonce) FROM " . PREFIXE_ANNONCES . ")", array() );
		$wpdb->query($sql);

		version::majVersion();
	}
	if (version::getVersion() <= 9)
	{
		$sql = $wpdb->prepare("UPDATE " . ANNONCES_TABLE_OPTION . "SET nomption = '%date_publication%/%type_bien%/%ville%-%departement%/%idpetiteannonce%' WHERE labeloption = 'annonces_expression_url'", array() );
		//	NOT USED FROM DB VERSION 18

		$sql = $wpdb->prepare("INSERT INTO " . ANNONCES_TABLE_GROUPEATTRIBUTATTRIBUT . " VALUES ('216', '1', 'valid')", array() );
		$wpdb->query($sql);

		version::majVersion();
	}
	if (version::getVersion() <=10)
	{
		$sql = $wpdb->prepare("INSERT INTO " . ANNONCES_TABLE_ATTRIBUT . " VALUES ('216', 'valid', 'oui', 'CHAR', 'UrlPersonnalisee', 'Url personnalis&eacute;e', '')", array() );
		$wpdb->query($sql);

		version::majVersion();
	}
	if (version::getVersion() <=12)
	{
		$sql = $wpdb->prepare("UPDATE " . ANNONCES_TABLE_ATTRIBUT . " SET flagvalidattribut = 'moderated'  WHERE labelattribut = 'UrlPersonnalisee'", array() );
		$wpdb->query($sql);

		version::majVersion();
	}
	if (version::getVersion() <= 13)
	{
		$sql = $wpdb->prepare("UPDATE " . ANNONCES_TABLE_OPTION . " SET nomoption='' where labeloption = 'annonces_suffix'", array() );
		//	NOT USED FROM DB VERSION 18

		version::majVersion();
	}
	if (version::getVersion() <= 14)
	{
		$sql = $wpdb->prepare("UPDATE " . ANNONCES_TABLE_OPTION . " SET nomoption='annonce_%idpetiteannonce%' where labeloption = 'annonces_expression_url'", array() );
		//	NOT USED FROM DB VERSION 18

		version::majVersion();
	}
	if (version::getVersion() <= 15)
	{
		$sql = "UPDATE " . ANNONCES_TABLE_OPTION . " SET nomoption='ee' where labeloption = 'annonces_expression_url' ";
		//	NOT USED FROM DB VERSION 18

		version::majVersion();
	}
	if (version::getVersion() <= 16)
	{
		$sql = "UPDATE " . ANNONCES_TABLE_OPTION . " SET nomoption='ee' where labeloption = 'annonces_expression_url' ";
		//	NOT USED FROM DB VERSION 18

		version::majVersion();
	}
	if (version::getVersion() <= 17)
	{
		$sql = "UPDATE " . ANNONCES_TABLE_OPTION . " SET nomoption='annonce_%idpetiteannonce%' where labeloption = 'annonces_expression_url' ";
		//	NOT USED FROM DB VERSION 18

		version::majVersion();
	}
	if (version::getVersion() <= 18)
	{/*	Transfert the different option from the specific table to the wordpress table	*/
		/*	Transfert the different file into the good directory	*/
		if(!is_dir(WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . 'gmapMarker'))
		{
			tools::make_recursiv_dir(WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . 'gmapMarker');
		}
		copy(ANNONCES_IMG_PLUGIN_DIR . 'red-dot_default.png', WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . 'gmapMarker/red-dot_default.png');
		/*	Transfert the different file into the good directory	*/
		if(!is_dir(WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . 'searchPicto'))
		{
			tools::make_recursiv_dir(WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . 'searchPicto');
		}
		copy(ANNONCES_IMG_PLUGIN_DIR . 'budget_default.png', WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . 'searchPicto/budget_default.png');
		copy(ANNONCES_IMG_PLUGIN_DIR . 'maisons_default.png', WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . 'searchPicto/maisons_default.png');
		copy(ANNONCES_IMG_PLUGIN_DIR . 'recherche_default.png', WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . 'searchPicto/recherche_default.png');
		copy(ANNONCES_IMG_PLUGIN_DIR . 'surface_default.png', WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . 'searchPicto/surface_default.png');
		copy(ANNONCES_IMG_PLUGIN_DIR . 'terrains_default.png', WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . 'searchPicto/terrains_default.png');
		copy(ANNONCES_IMG_PLUGIN_DIR . 'toutes_default.png', WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . 'searchPicto/toutes_default.png');

		$annonceWPOption = array();
		$annonceWPOptionEmail = array();
		if( $wpdb->get_var("show tables like '" . ANNONCES_TABLE_OPTION . "'") == ANNONCES_TABLE_OPTION)
		{
			$query = $wpdb->prepare("SELECT * FROM " . ANNONCES_TABLE_OPTION, array() );
			$annonceOption = $wpdb->get_results($query);
			foreach($annonceOption as $option)
			{
				switch($option->labeloption)
				{
					case 'annonces_api_key':
						$optionValue = $option->nomoption;
						if($optionValue == '')
						{
							$optionValue = get_option('annonces_api_key');
						}
						$annonceWPOption['gmap_api_key'] = $optionValue;
					break;
					case 'annonces_maps_activation':
						$valeur = 'non';
						if($option->nomoption == '1')
						{
							$valeur = 'oui';
						}
						$annonceWPOption['annonce_activate_map'] = $valeur;
					break;
					case 'url_marqueur_courant':
						$annonceWPOption['annonce_map_marker'] = 'gmapMarker/' . $option->nomoption;
						if(!is_file(WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . 'gmapMarker/' . $option->nomoption))
						{
							copy(ANNONCES_IMG_PLUGIN_DIR . $option->nomoption, WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . 'searchPicto/' . $option->nomoption);
						}
					break;
					case 'annonces_photos_activation':
						$valeur = 'non';
						if($option->nomoption == '1')
						{
							$valeur = 'oui';
						}
						$annonceWPOption['annonce_show_picture'] = $valeur;
					break;
					case 'annonces_date_activation':
						$valeur = 'non';
						if($option->nomoption == '1')
						{
							$valeur = 'oui';
						}
						$annonceWPOption['annonce_show_date'] = $valeur;
					break;
					case 'annonces_url_activation':
						$valeur = 'non';
						if($option->nomoption == '1')
						{
							$valeur = 'oui';
						}
						$annonceWPOption['annonce_activate_url_rewrite'] = $valeur;
					break;
					case 'annonces_expression_url':
						$annonceWPOption['annonce_url_rewrite_template'] = $option->nomoption;
					break;
					case 'annonces_suffix':
						$annonceWPOption['annonce_url_rewrite_template_suffix'] = $option->nomoption;
					break;
					case 'url_radio_maisons_theme_defaut':
					case 'url_radio_terrains_theme_defaut':
					case 'url_radio_toutes_theme_defaut':
					case 'url_budget_theme_defaut':
					case 'url_superficie_theme_defaut':
					case 'url_recherche_theme_defaut':
					case 'theme_activation':
					case 'annonces_page_install':
					case 'url_marqueur_defaut':

					break;
					case 'url_radio_maisons_theme_courant':
					case 'url_radio_terrains_theme_courant':
					case 'url_radio_toutes_theme_courant':
					case 'url_budget_theme_courant':
					case 'url_superficie_theme_courant':
					case 'url_recherche_theme_courant':
						$newOptionName = str_replace('_theme_courant', '', $option->labeloption);
						$finalDir = explode('_', $newOptionName);
						if(!is_dir(WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . 'searchPicto/' . $finalDir[count($finalDir) - 1]))
						{
							tools::make_recursiv_dir(WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . 'searchPicto/' . $finalDir[count($finalDir) - 1]);
						}
						$annonceWPOption[$newOptionName] = 'searchPicto/' . $finalDir[count($finalDir) - 1] . '/' . $option->nomoption;
						if(!is_file(WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . 'searchPicto/' . $finalDir[count($finalDir) - 1] . '/' . $option->nomoption))
						{
							copy(ANNONCES_IMG_PLUGIN_DIR . $option->nomoption, WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . 'searchPicto/' . $finalDir[count($finalDir) - 1] . '/' .  $option->nomoption);
						}
					break;
					case 'annonces_email_reception':
					case 'annonces_sujet_reception':
					case 'annonces_txt_reception':
					case 'annonces_html_reception':
						$annonceWPOptionEmail[$option->labeloption] = $option->nomoption;
					break;
					case 'annonces_email_activation':
						$valeur = 'non';
						if($option->nomoption == '1')
						{
							$valeur = 'oui';
						}
						$annonceWPOption['annonces_email_activation'] = $valeur;
					break;
					default:
						$annonceWPOption[$option->labeloption] = $option->nomoption;
					break;
				}
			}

			/*	Rename annonce option table for future deletion	*/
			$query = $wpdb->prepare("RENAME TABLE " . ANNONCES_TABLE_OPTION . " TO " . TRASH__ANNONCES_TABLE_OPTION, array() );
			$wpdb->query($query);
		}
		else
		{
			$annonceWPOption['gmap_api_key'] = get_option('annonces_api_key');
			$annonceWPOption['annonce_activate_map'] = 'oui';
			$annonceWPOption['annonce_map_marker'] = 'gmapMarker/red-dot_default.png';
			$annonceWPOption['annonce_show_picture'] = 'oui';
			$annonceWPOption['annonce_show_date'] = 'oui';
			$annonceWPOption['annonce_activate_url_rewrite'] = 'non';
			$annonceWPOption['annonce_url_rewrite_template'] = 'annonce_%idpetiteannonce%';
			$annonceWPOption['annonce_url_rewrite_template_suffix'] = '.html';
			$annonceWPOption['url_radio_maisons'] = 'searchPicto/maisons/maisons_default.png';
			if(!is_dir(WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . dirname($annonceWPOption['url_radio_maisons'])))
			{
				tools::make_recursiv_dir(WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . dirname($annonceWPOption['url_radio_maisons']));
			}
			if(!is_file(WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . $annonceWPOption['url_radio_maisons']))
			{
				copy(WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . str_replace('/maisons/', '/', $annonceWPOption['url_radio_maisons']), WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . $annonceWPOption['url_radio_maisons']);
			}
			$annonceWPOption['url_radio_terrains'] = 'searchPicto/terrains/terrains_default.png';
			if(!is_dir(WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . dirname($annonceWPOption['url_radio_terrains'])))
			{
				tools::make_recursiv_dir(WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . dirname($annonceWPOption['url_radio_terrains']));
			}
			if(!is_file(WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . $annonceWPOption['url_radio_terrains']))
			{
				copy(WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . str_replace('/terrains/', '/', $annonceWPOption['url_radio_terrains']), WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . $annonceWPOption['url_radio_terrains']);
			}
			$annonceWPOption['url_radio_toutes'] = 'searchPicto/toutes/toutes_default.png';
			if(!is_dir(WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . dirname($annonceWPOption['url_radio_toutes'])))
			{
				tools::make_recursiv_dir(WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . dirname($annonceWPOption['url_radio_toutes']));
			}
			if(!is_file(WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . $annonceWPOption['url_radio_toutes']))
			{
				copy(WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . str_replace('/toutes/', '/', $annonceWPOption['url_radio_toutes']), WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . $annonceWPOption['url_radio_toutes']);
			}
			$annonceWPOption['url_budget'] = 'searchPicto/budget/budget_default.png';
			if(!is_dir(WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . dirname($annonceWPOption['url_budget'])))
			{
				tools::make_recursiv_dir(WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . dirname($annonceWPOption['url_budget']));
			}
			if(!is_file(WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . $annonceWPOption['url_budget']))
			{
				copy(WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . str_replace('/budget/', '/', $annonceWPOption['url_budget']), WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . $annonceWPOption['url_budget']);
			}
			$annonceWPOption['url_superficie'] = 'searchPicto/superficie/surface_default.png';
			if(!is_dir(WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . dirname($annonceWPOption['url_superficie'])))
			{
				tools::make_recursiv_dir(WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . dirname($annonceWPOption['url_superficie']));
			}
			if(!is_file(WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . $annonceWPOption['url_superficie']))
			{
				copy(WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . str_replace('/superficie/', '/', $annonceWPOption['url_superficie']), WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . $annonceWPOption['url_superficie']);
			}
			$annonceWPOption['url_recherche'] = 'searchPicto/recherche/recherche_default.png';
			if(!is_dir(WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . dirname($annonceWPOption['url_recherche'])))
			{
				tools::make_recursiv_dir(WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . dirname($annonceWPOption['url_recherche']));
			}
			if(!is_file(WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . $annonceWPOption['url_recherche']))
			{
				copy(WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . str_replace('/recherche/', '/', $annonceWPOption['url_recherche']), WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . $annonceWPOption['url_recherche']);
			}
			$annonceWPOptionEmail['annonces_email_reception'] = 'mail@mondomaine.com';
			$annonceWPOptionEmail['annonces_sujet_reception'] = $sujet;
			$annonceWPOptionEmail['annonces_txt_reception'] = $txt;
			$annonceWPOptionEmail['annonces_html_reception'] = $html;
			$annonceWPOptionEmail['annonces_email_activation'] = 'non';
		}

		/*	Add new options	*/
		$annonceWPOption['annonce_map_marker_size'] = '32';
		$annonceWPOption['annonce_currency'] = '&euro;';
		$annonceWPOption['annonce_frontend_listing_order'] = 'autoinsert';

		/*	Set option into wordpress table	*/
		add_option('annonces_options', $annonceWPOption);
		/*	Set option into wordpress table	*/
		add_option('annonces_email_options', $annonceWPOptionEmail);

		version::majVersion();
	}

}