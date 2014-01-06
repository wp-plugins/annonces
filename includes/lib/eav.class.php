<?php
/***************************************************
*Date: 01/10/2009      File:eav.class.php 		   *
*Author:Eoxia							           *
*Comment:                                          *
***************************************************/
require_once dirname(__FILE__).'/options.class.php';

class Eav {

	function url_exist($url)
	{
		global $wpdb;

		$recup_id = $wpdb->prepare('select idpetiteannonce from '.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce where urlannonce = "' . $url . '"', array() );
				$req_id = $wpdb->get_row($recup_id);
				$id = $req_id->idpetiteannonce;
		return $id;
	}

	function get_groupe($id)
	{
		global $wpdb;

		$recup_grp = $wpdb->prepare('SELECT nomgroupeattribut, descriptiongroupeattribut
										FROM '. ANNONCES_TABLE_GROUPEATTRIBUT . '
										WHERE idgroupeattribut = '. $id .'
										', array() );
				$req_grp = $wpdb->get_row($recup_grp);
		return $req_grp;
	}

	function get_geoloc($id)
	{
		global $wpdb;

		$recup_adr = $wpdb->prepare('select autolocalisation, adresse, ville, departement, region, cp, pays, latitude, longitude from '.ANNONCES_TABLE_GEOLOCALISATION.' where iddest = "' . $id . '"', array() );
				$req_adr = $wpdb->get_row($recup_adr);
		return $req_adr;
	}


	function recupPageAnnonce() {
		global $wpdb;

		$query = $wpdb->prepare( "SELECT post_name FROM {$wpdb->posts} WHERE post_status = %s AND post_content LIKE \"%%%s%%\"", "publish", '<div rel="annonces" id="annonces" ></div>' );
		$page = $wpdb->get_var($query);

		return $page;
	}

	function setUrl($id, $url)
	{
		global $wpdb;

		$sql = "UPDATE " . PREFIXE_ANNONCES . " SET urlannonce = '" . $url . "' WHERE idpetiteannonce = '" . $id . "'";
		$wpdb->query($sql);
	}
	function getgroupeattribut($id)
	{
		global $wpdb;

		$recup_grp = $wpdb->prepare('select nomgroupeattribut from '.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce where idpetiteannonce = "' . $id . '"', array() );
				$req_grp = $wpdb->get_row($recup_grp);
				$grp = $req_grp->nomgroupeattribut;
		return $grp;
	}
	function getdescattribut($id)
	{
		global $wpdb;

		$recup_grp = $wpdb->prepare('select descriptiongroupeattribut from '.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce where idpetiteannonce = "' . $id . '"', array() );
				$req_grp = $wpdb->get_row($recup_grp);
				$grp = $req_grp->descriptiongroupeattribut;
		return $grp;
	}
	function setUrlType($id, $titre, $refagence, $nomgrpatt, $descgrpatt, $ville, $dep, $region, $cp, $pays, $date)
	{
		global $wpdb;

		$eav_mode = new Eav();

		$recup_link = annonces_expression_url;

		$recup_link = str_replace('%idpetiteannonce%', $id, $recup_link);
		$recup_link = str_replace('%titre_annonce%', $titre, $recup_link);
		$recup_link = str_replace('%referenceagencedubien%', $refagence, $recup_link);
		$recup_link = str_replace('%nomgroupeattribut%', $nomgrpatt, $recup_link);
		$recup_link = str_replace('%descriptiongroupeattribut%', $descgrpatt, $recup_link);
		$recup_link = str_replace('%ville%', $ville, $recup_link);
		$recup_link = str_replace('%departement%', $dep, $recup_link);
		$recup_link = str_replace('%region%', $region, $recup_link);
		$recup_link = str_replace('%cp%', $cp, $recup_link);
		$recup_link = str_replace('%pays%', str_replace("'", '-', $pays), $recup_link);
		$recup_link = str_replace('%date_publication%', date("d/m/Y",strtotime($date)), $recup_link);
		$recup_link = str_replace('%type_bien%', str_replace('/','-',$eav_mode->getBien($id)), $recup_link);

		$recup_link = annonces_options::slugify_noaccent($recup_link);
		$recup_link = trim($recup_link);
		$recup_link = str_replace(' ', '-', $recup_link);
		$recup_link = str_replace('\'', '-', $recup_link);
		$recup_link = str_replace('"', '-', $recup_link);
		$recup_link = mb_strtolower($recup_link);

		if (annonce_url_rewrite_template_suffix != '')
		{
			$recup_link = $recup_link . annonce_url_rewrite_template_suffix;
		}

		$maj_annonce = $wpdb->prepare('UPDATE `'.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce`
								SET urlannonce ="'. $recup_link .'"
								WHERE idpetiteannonce="' . $id . '"', array() );
		$wpdb->query($maj_annonce);
	}
	function set_type_url($url, $id, $values)
	{
		global $wpdb;

		$eav_mode = new Eav();

		$url = annonces_expression_url;

		$date = explode ('-', $values[autolastmodif]);
		$date2 = explode(' ', $date[2]);

		$ladate = $date2[0].'/'.$date[1].'/'.$date[0];

		$url = str_replace('%idpetiteannonce%', $id, $url);
		$url = str_replace('%titre_annonce%', $values[titre], $url);
		$url = str_replace('%referenceagencedubien%', $values[referenceagencedubien], $url);
		$url = str_replace('%nomgroupeattribut%', Eav::get_groupe($values[idgroupeattribut])->nomgroupeattribut, $url);
		$url = str_replace('%descriptiongroupeattribut%', Eav::get_groupe($values[idgroupeattribut])->descriptiongroupeattribut, $url);
		$url = str_replace('%ville%', $values[ville], $url);
		$url = str_replace('%departement%', $values[departement], $url);
		$url = str_replace('%region%', $values[region], $url);
		$url = str_replace('%cp%', $values[cp], $url);
		$url = str_replace('%pays%', str_replace("'", '-', $values[pays]), $url);
		$url = str_replace('%date_publication%', $ladate, $url);
		$url = str_replace('%type_bien%', str_replace('/','-',$values[TypeBien]), $url);

		$url = annonces_options::slugify_noaccent($url);
		$url = trim($url);
		$url = str_replace(' ', '-', $url);
		$url = str_replace('\'', '-', $url);
		$url = str_replace('"', '-', $url);
		$url = mb_strtolower($url);

		if (annonce_url_rewrite_template_suffix != '')
		{
			$url = $url . annonce_url_rewrite_template_suffix;
		}

		return $url;
	}

	public function get_titre($id)
	{
		global $wpdb;

		$recup_titre = $wpdb->prepare('select titre from '.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce where idpetiteannonce = "' . $id . '"', array() );
				$req_titre = $wpdb->get_row($recup_titre);
				$titre = $req_titre->titre;
		return $titre;
	}

	public function get_autoinsert($id)
	{
		global $wpdb;

		$recup_autoinsert = $wpdb->prepare('select autoinsert from '.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce where idpetiteannonce = "' . $id . '"', array() );
				$req_autoinsert = $wpdb->get_row($recup_autoinsert);
				$autoinsert = $req_autoinsert->autoinsert;
		return $autoinsert;
	}
	public function get_description($id)
	{
		global $wpdb;

		$recup_description = $wpdb->prepare('select valueattributtextcourt from '.$wpdb->prefix . small_ad_table_prefix_AOS .'petiteannonce__attributtext where idpetiteannonce = "' . $id . '"', array() );
				$req_desc = $wpdb->get_row($recup_description);
				$desc = $req_desc->valueattributtextcourt;
		return $desc;
	}

	public function get_autolastmodif($id)
	{
		global $wpdb;

		$recup_autolastmodif = $wpdb->prepare('select autolastmodif from '.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce where idpetiteannonce = "' . $id . '"', array() );
				$req_autolastmodif = $wpdb->get_row($recup_autolastmodif);
				$autolastmodif = $req_autolastmodif->autolastmodif;
		return $autolastmodif;
	}

	public function get_link($id)
	{
		global $wpdb;

		$recup_url = $wpdb->prepare('select urlannonce from '.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce where idpetiteannonce = "' . $id . '"', array() );
				$req_url = $wpdb->get_row($recup_url);
				$url = $req_url->urlannonce;

		return $url;
	}

	public function get_annonce($url) {
		global $wpdb;

		$recup_url = $wpdb->prepare('select idpetiteannonce from '.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce where urlannonce = "' . $url . '"', array() );
		$id = $wpdb->get_var($recup_url);

		return $id;
	}

	public function getFlag($flag = null)
	{
		if(is_null($flag)){return 'valid';}
		return $flag;
	}

	public function getOrder($order = null, $orderSide = 'DESC')
	{
		if(is_null($order))
		{
			return '';
		}

		$generate_query = ' ORDER BY ' . $order . ' ' . $orderSide;
		return $generate_query;
	}

	public function getResult(){
		return $this->result;
	}

	public function setResult($result_new){
		$this->result = $result_new;
	}

	public function getMorequery( $morequery = null ){
		if(is_null($morequery)){return '';}
		$generate_query = $morequery;
		return $generate_query;
	}

	public function getPhotos($idannonce = null)
	{
		global $wpdb;
		return $wpdb->get_results( "SELECT * FROM ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__photos
				WHERE idpetiteannonce ='".$idannonce."' AND flagvalidphotos = 'valid' ");
	}

	public function getPrix($morequery = null, $flag = null, $order = null, $idannonce = null)
	{
		global $wpdb;
		$id = (!is_null($idannonce)? " AND ATT_DEC.idpetiteannonce='".$idannonce."' " :"");
		$sql =
			"SELECT distinct * FROM ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attribut AS ATT
				LEFT JOIN ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attributdec AS ATT_DEC ON ATT.idattribut = ATT_DEC.idattribut
			WHERE  flagvalidattribut ='".$this->getFlag($flag)."' ".$id." AND ATT.labelattribut = 'PrixLoyerPrixDeCession' ".$this->getMorequery($morequery).$this->getOrder($order);

		return $wpdb->get_results($sql);
	}

	public function getTypeBien($morequery = null, $flag = null, $order = null, $idannonce = null)
	{
		global $wpdb;
		$id = (!is_null($idannonce)? " AND ATT_CHAR.idpetiteannonce='".$idannonce."' " :"");
		$sql =
			"SELECT distinct * FROM ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attribut AS ATT
				LEFT JOIN ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attributchar AS ATT_CHAR ON ATT.idattribut = ATT_CHAR.idattribut
			WHERE  flagvalidattribut ='".$this->getFlag($flag)."' ".$id." AND ATT.labelattribut = 'TypeBien' ".$this->getMorequery($morequery).$this->getOrder($order);

		return $wpdb->get_results($sql);
	}

	public function getSurface($morequery = null, $flag = null, $order = null, $idannonce = null)
	{
		global $wpdb;
		$id = (!is_null($idannonce)? " AND ATT_DEC.idpetiteannonce='".$idannonce."' " :"");
		$sql =
			"SELECT distinct * FROM ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attribut AS ATT
				LEFT JOIN ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attributdec AS ATT_DEC ON ATT.idattribut = ATT_DEC.idattribut
			WHERE  flagvalidattribut ='".$this->getFlag($flag)."' ".$id." AND ATT.labelattribut = 'SFTerrain' ".$this->getMorequery($morequery).$this->getOrder($order);

		return $wpdb->get_results($sql);
	}

	public function getDescription($morequery = null, $flag = null, $order = null, $idannonce = null)
	{
		global $wpdb;
		return $wpdb->get_results( "SELECT distinct * FROM ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attribut
		LEFT JOIN ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attributtext ON ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attribut.idattribut = ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attributtext.idattribut
		WHERE  flagvalidattribut ='".$this->getFlag($flag)."' AND ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attributtext.idpetiteannonce = '".$idannonce."'  AND ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attribut.labelattribut = 'Descriptif' ".$this->getMorequery($morequery).$this->getOrder($order));
	}

	public function getReference($morequery = null, $flag = null, $order = null, $idannonce = null)
	{
		global $wpdb;
		return $wpdb->get_results( "SELECT distinct * FROM ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attribut
		LEFT JOIN ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attributchar ON ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attribut.idattribut = ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attributchar.idattribut
		WHERE  flagvalidattribut ='".$this->getFlag($flag)."' AND ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attributchar.idpetiteannonce = '".$idannonce."'  AND ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attribut.labelattribut = 'ReferenceAgenceDuBien' ".$this->getMorequery($morequery).$this->getOrder($order));
	}

	public function getLesAnnonces()
	{
		global $wpdb;

		$TheSelect = " * ";
		if($option == "count")$TheSelect = " COUNT(ANN.idpetiteannonce) ";
		$sql =
			"SELECT ".$TheSelect."
			FROM ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce AS ANN
				LEFT JOIN ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__groupeattribut AS GRP_ATT ON ANN.idgroupeattribut = GRP_ATT.idgroupeattribut
				LEFT JOIN ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__geolocalisation AS GEO ON ANN.idpetiteannonce = GEO.iddest
				LEFT JOIN ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attributdec AS ATTRDEC ON ( (ATTRDEC.idpetiteannonce = ANN.idpetiteannonce) AND (ATTRDEC.idattribut = 11) )
			WHERE 1 ";

		if($option == "count")
		{
			return $wpdb->get_var($sql);
		}
		else
		{
			$this->setResult($wpdb->get_results( $sql ));
			return $this->getResult();
		}
	}

	public function getLatestIDAnnonce() {
		global $wpdb;

		$sql = $wpdb->prepare("SELECT idpetiteannonce FROM " . PREFIXE_ANNONCES . " WHERE idpetiteannonce= (SELECT MAX(idpetiteannonce) FROM " . PREFIXE_ANNONCES . ") ", array() );
		$annonce = $wpdb->get_var($sql);

		return $annonce;
	}

	public function getAnnoncesEntete($morequery = null, $flag = DEFAULT_FLAG_AOS, $order = null, $actual_page = 0, $limit = null, $option = null, $itemperpage = NUMBER_OF_ITEM_PAR_PAGE_FRONTEND_AOS )
	{
		global $wpdb;
		$real_page = $actual_page;if($actual_page!=0)$real_page = $actual_page-1;
		$debut = $real_page * $itemperpage;

		$moreflag = "";
		if($flag != "")$moreflag = " AND flagvalidpetiteannonce IN (".$flag.") ";

		$TheSelect = " * ";
		if($option == "count")$TheSelect = " COUNT(ANN.idpetiteannonce) ";
		$sql =
			"SELECT ".$TheSelect."
			FROM ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce AS ANN
				LEFT JOIN ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__groupeattribut AS GRP_ATT ON ANN.idgroupeattribut = GRP_ATT.idgroupeattribut
				LEFT JOIN ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__geolocalisation AS GEO ON ANN.idpetiteannonce = GEO.iddest
				LEFT JOIN ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attributdec AS ATTRDEC ON ( (ATTRDEC.idpetiteannonce = ANN.idpetiteannonce) AND (ATTRDEC.idattribut = 11) )
			WHERE 1 "
				. $moreflag
				. $this->getMorequery($morequery)
				. $this->getOrder($order, annonce_frontend_listing_order_side);

		if($limit != 'nolimit')$sql .= " LIMIT " . $debut . "," . $itemperpage;

		if($option == "count")
		{
			return $wpdb->get_var($sql);
		}
		else
		{
			$results = $wpdb->get_results( $sql );
			$this->setResult($wpdb->get_results( $sql ));
			return $this->getResult();
		}
	}

	public function getAnnoncesAttributs($morequery = null, $flag = null, $order = null, $idannonce = null, $flagvisible = null)
	{
		global $wpdb;
		if(is_null($flagvisible)){
			$tag_visible = '';
		}else{
			$tag_visible= 'AND (ATT.flagvisibleattribut = \''.$flagvisible.'\')';
		}

		$sql =
			"SELECT distinct *
			FROM ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__groupeattribut AS CAT
				LEFT JOIN ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__groupeattribut_attribut AS LINK_CAT ON CAT.idgroupeattribut = LINK_CAT.idgroupeattribut
				LEFT JOIN ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attribut AS ATT ON LINK_CAT.idattribut = ATT.idattribut
				LEFT JOIN ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attributchar AS ATT_CHAR ON ATT_CHAR.idattribut = ATT.idattribut
				LEFT JOIN ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attributdate AS ATT_DATE ON ATT_DATE.idattribut = ATT.idattribut
				LEFT JOIN ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attributdec AS ATT_DEC ON ATT_DEC.idattribut = ATT.idattribut
				LEFT JOIN ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attributint AS ATT_INT ON ATT_INT.idattribut = ATT.idattribut
				LEFT JOIN ". $wpdb->prefix . small_ad_table_prefix_AOS ."petiteannonce__attributtext AS ATT_TEXT ON ATT_TEXT.idattribut = ATT.idattribut
			WHERE  flagvalidgroupeattribut ='".$this->getFlag($flag)."'
				".$tag_visible."
				AND flagvalidattribut ='valid'
				AND (ATT_CHAR.idpetiteannonce = '".$idannonce."'
				OR ATT_DATE.idpetiteannonce = '".$idannonce."'
				OR ATT_INT.idpetiteannonce = '".$idannonce."'
				OR ATT_DEC.idpetiteannonce = '".$idannonce."'
				OR ATT_TEXT.idpetiteannonce = '".$idannonce."') "
			.$this->getMorequery($morequery).
			$this->getOrder($order);

		$this->setResult($wpdb->get_results( $sql ));

		return $this->getResult();
	}
	public function getBien($id)
	{
		global $wpdb;

		$query = $wpdb->prepare('select valueattributchar from '.$wpdb->prefix.small_ad_table_prefix_AOS.'petiteannonce__attributchar where idpetiteannonce = "'.$id.'"', array() );
		$reqid = $wpdb->get_row($query);
		$idA = $reqid->valueattributchar;

		return $idA;
	}
}