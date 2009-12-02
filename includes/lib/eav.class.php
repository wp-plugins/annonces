<?php
/***************************************************
*Date: 01/10/2009      File:eav.class.php 		   *
*Author:Eoxia							           *
*Comment:                                          *
***************************************************/

class Eav {
	public function getFlag($flag = null)
	{
		if(is_null($flag)){return 'valid';}
		return $flag;
	}
	
	public function getOrder($order = null)
	{
		if(is_null($order)){return '';}
		$generate_query = ' ORDER BY ';
		$generate_query .= $order;
		return $generate_query;
	}
	
	public function getResult(){
		return $this->result;
	}
	
	public function setResult($result_new){
		$this->result = $result_new;
	}
	
	public function getMorequery($morequery = null){
		if(is_null($morequery)){return '';}
		$generate_query .= $morequery;
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
			WHERE 1 "
				. $moreflag
				.$this->getMorequery($morequery)
			.$this->getOrder($order);

		if($limit != 'nolimit')$sql .= " LIMIT " . $debut . "," . $itemperpage;

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
}