<?php
/***************************************************
*Date: 01/10/2009      file:frontend.class.php     *
*Author: Eoxia                                     *
*Comment:                                          *
***************************************************/
require_once dirname(__FILE__).'/eav.class.php';
require_once dirname(__FILE__).'/gmap.class.php';


global $content;

class Frontend {

	private $annonce_content = '';
	/*---- Parse page content looking for RegEx matches and add modify HTML to acomodate display ----*/
	/**
	* Cette méthode recherche dans le contenu de la page Wordpress courante la balise <div rel="annonces" id="annonces" ></div>
	* puis la remplace par le code html généré pour les petites annonces.
	*/
	public function show($content)
	{
		if(ereg('<[Dd][Ii][Vv] [Rr][Ee][Ll]="[Aa][Nn][Nn][Oo][Nn][Cc][Ee][Ss]" [Ii][Dd]="[Aa][Nn][Nn][Oo][Nn][Cc][Ee][Ss]" >',$content)){
			return $this->generate($content);
		}else{
			return $content;
		}
	}
	
	public function generate($content){
		global $tools;
		$query        = isset($_REQUEST['query'])        ? $tools->IsValid_Variable($_REQUEST['query'])        : '' ;
		$query1       = isset($_REQUEST['query1'])       ? $tools->IsValid_Variable($_REQUEST['query1'])       : '' ;
		$query2       = isset($_REQUEST['query2'])       ? $tools->IsValid_Variable($_REQUEST['query2'])       : '' ;
		$query3       = isset($_REQUEST['query3'])       ? $tools->IsValid_Variable($_REQUEST['query3'])       : '' ;
		$query4       = isset($_REQUEST['query4'])       ? $tools->IsValid_Variable($_REQUEST['query4'])       : '' ;
		$mode         = isset($_REQUEST['mode'])         ? $tools->IsValid_Variable($_REQUEST['mode'])         : '' ;
		$show_map     = isset($_REQUEST['show_map'])     ? $tools->IsValid_Variable($_REQUEST['show_map'])     : '' ;
		$show_annonce = isset($_REQUEST['show_annonce']) ? $tools->IsValid_Variable($_REQUEST['show_annonce']) : '' ;
		
		if(!empty($query) OR !empty($query1) OR !empty($query2) OR !empty($query3) OR !empty($query4) OR !empty($mode)){
			/**
			Si le critère de recherche n'est que le type de bien, on rentre dans la fonction sinon on fait la recherche avec les autres critères
			**/
			if(empty($query) AND empty($query1) AND empty($query2) AND empty($query3) AND empty($query4) AND !empty($mode)){
				$eav_mode = new Eav();
				$annonces = $eav_mode->getAnnoncesEntete($morequery,DEFAULT_FLAG_AOS,'titre',0,'nolimit');
			
				if($mode != 'all'){
					foreach($annonces as $i){
						$eav_mode_value = $eav_mode->getTypeBien(null,'valid',null,$i->idpetiteannonce);
						if($eav_mode_value[0]->valueattributchar == $mode){
							$idarray .= $i->idpetiteannonce.',';
						}
					}
				}else{
					foreach($annonces as $i){
						$idarray .= $i->idpetiteannonce.',';
					}					
				}
			
				//Supprime la dernière virgule de la liste des IDs
				$size = strlen($idarray);
				$id = substr($idarray, 0, $size-1);

				$result_search = ' AND ANN.idpetiteannonce IN ('.$id.') ';

				if($show_map == 'true' and (get_option('annonces_maps_activation') == 1)){
					/*---- Show map ----*/
					$this->concatAnnonceContent($this->generate_search_map());
					if(!empty($id)){
						$this->concatAnnonceContent('<br/><br/><br/><br/><br/>');
						$this->concatAnnonceContent($this->show_map($result_search));
					}else{
						$result_search = ' AND ANN.idpetiteannonce IN (0) ';
						$this->concatAnnonceContent('<br/><br/><br/><p>'.__('Aucune annonce ne r&eacute;pond &agrave; vos crit&egrave;res.','annonces').'</p><br/>');
						$this->concatAnnonceContent($this->show_map($result_search));	
					}				
					/*---- Show annonce plugin ----*/
					return $this->addAnnoncesToContent($content);
				}
					
				/*---- Show search filter ----*/
				$this->concatAnnonceContent($this->generate_search());
				if(get_option('annonces_maps_activation') == 1){
					$this->concatAnnonceContent('<br/><center style="margin-top:80px;" id="annonces_listing" >');

					if(empty($id))
						{$result_search = ' AND ANN.idpetiteannonce IN (0) ';}
					$this->concatAnnonceContent($this->generate_map($result_search));
					$this->concatAnnonceContent('</center><br/><br/>');
				}else{
					$this->concatAnnonceContent('<br/><br/><br/><br/><br/><br/>');
				}
				/*---- Show list annonces ----*/
				if(!empty($id)){
					$this->concatAnnonceContent($this->list_annonce($result_search));
				}else{
					$this->concatAnnonceContent('<br/><p>'.__('Aucune annonce ne r&eacute;pond &agrave; vos crit&egrave;res.','annonces').'</p><br/>');	
				}

				/*---- Show annonce plugin ----*/
				return $this->addAnnoncesToContent($content);
			}else{
				if(file_exists(Search_index_AOS)){
					$index = Zend_Search_Lucene::open(Search_index_AOS);
					$query_sentence = new Zend_Search_Lucene_Search_Query_Phrase();//Search a sentence, not some words.
					$array_query = split(' ',$query);
					foreach($array_query as $word):
						$query_sentence->addTerm(new Zend_Search_Lucene_Index_Term($word));
					endforeach;
					$query_sentence->setSlop(3);
					$hits = $index->find($tools->slugify_noaccent($query_sentence));
					$values = null;
					$checkprix = null;
					$checksurface = null;
					$limite = count($hits);
					$check_status = new Eav();
					foreach ($hits as $i => $hit){
						$document = $hit->getDocument();
						$annonce = $check_status->getAnnoncesEntete(" AND ANN.idpetiteannonce ='".$document->getFieldValue('pk')."' ","'valid'",'titre',0,'nolimit','count');
						if($annonce>0){
							$values[$i] = $document->getFieldValue('pk');
						}
					}
					if(!empty($query3) OR !empty($query4)){
						$eav_value = new Eav();
						$morequery = (!empty($query4) ? " AND ATT_DEC.valueattributdec <= ".$query4." " : "").(!empty($query3) ? " AND ATT_DEC.valueattributdec >= ".$query3." " : "");
						$prix = $eav_value->getPrix($morequery,'valid');
						foreach($prix as $i => $instance){
							$checkprix[$i] = $instance->idpetiteannonce;
						}
					}
					
					if(!empty($query1) OR !empty($query2)){
						$eav_value = new Eav();
						$morequery = (!empty($query2) ? " AND ATT_DEC.valueattributdec <= ".$query2." " : "").(!empty($query1) ? " AND ATT_DEC.valueattributdec >= ".$query1." " : "");
						$surface = $eav_value->getSurface($morequery,'valid');
						foreach($surface as $i => $instance){
							$checksurface[$i] = $instance->idpetiteannonce;
						}
					}
					
					$filter = $this->Filter($checkprix,$checksurface);
					$checkid = $this->Filter($values,$filter);
					if(!is_null($checkid)){
						if(!empty($mode) and ($mode != 'all')){
							$eav_mode = new Eav();
							foreach($checkid as $i){
								$eav_mode_value = $eav_mode->getTypeBien(null,'valid',null,$i);
								if($eav_mode_value[0]->valueattributchar == $mode){
									$idarray .= $i.',';
								}
							}
						}else{
							foreach($checkid as $i){
								$idarray .= $i.',';
							}					
						}
					//Supprime la dernière virgule de la liste des IDs
					$size = strlen($idarray);
					$id = substr($idarray, 0, $size-1);
					
					$result_search = ' AND ANN.idpetiteannonce IN ('.$id.') ';
					}
					
					if($show_map == 'true'){
						/*---- Show map ----*/
						$this->concatAnnonceContent($this->generate_search_map());
						if(!empty($id)){
							$this->concatAnnonceContent('<br/><br/><br/><br/><br/><br/><br/><br/>');
							$this->concatAnnonceContent($this->show_map($result_search));
						}else{
							$result_search = ' AND ANN.idpetiteannonce IN (0) ';
							$this->concatAnnonceContent('<br/><br/><br/><br/><br/><br/><p>'.__('Aucune annonce ne r&eacute;pond &agrave; vos crit&egrave;res.','annonces').'</p><br/>');
							$this->concatAnnonceContent($this->show_map($result_search));	
						}
						/*---- Show annonce plugin ----*/
						return $this->addAnnoncesToContent($content);
					}
					
					/*---- Show search filter ----*/
					$this->concatAnnonceContent($this->generate_search());
					if(get_option('annonces_maps_activation') == 1){
						$this->concatAnnonceContent('<br/><center style="margin-top:80px;" id="annonces_listing" >');
						if(empty($id))
							{$result_search = ' AND ANN.idpetiteannonce IN (0) ';}
						$this->concatAnnonceContent($this->generate_map($result_search));
						$this->concatAnnonceContent('</center><br/><br/>');
					}else{
						$this->concatAnnonceContent('<br/><br/><br/><br/><br/><br/>');
					}
					/*---- Show list annonces ----*/
					if(!empty($id)){
						$this->concatAnnonceContent($this->list_annonce($result_search));
					}else{
						$this->concatAnnonceContent('<br/><p>'.__('Aucune annonce ne r&eacute;pond &agrave; vos crit&egrave;res.','annonces').'</p><br/>');	
					}

					/*---- Show annonce plugin ----*/
					return $this->addAnnoncesToContent($content);
				}else{
					/*---- Show search filter ----*/
					$this->concatAnnonceContent($this->generate_search());
					$this->concatAnnonceContent('<br/><br/><br/><br/><br/><br/><br/><p style="font-size:140%">Recherche momentanement indisponible.</p>');
			
					/*---- Show annonce plugin ----*/
					return $this->addAnnoncesToContent($content);
				}
			}
		}
	
		if(isset($_REQUEST['show_annonce']))
		{
			/*---- Show annonce ----*/
			$this->concatAnnonceContent($this->show_annonce($_REQUEST['show_annonce']));
			
			/*---- Show annonce plugin ----*/
			return $this->addAnnoncesToContent($content);
		}
	
		if(!empty($show_map) and (get_option('annonces_maps_activation') == 1))
		{
			/*---- Show map ----*/
			$this->concatAnnonceContent($this->generate_search_map());
			$this->concatAnnonceContent('<br/><br/><br/><br/><br/><br/><br/><br/>');
			$this->concatAnnonceContent($this->show_map());
			
			/*---- Show annonce plugin ----*/
			return $this->addAnnoncesToContent($content);
		}
	
		/*---- Show search filter ----*/
		$this->concatAnnonceContent($this->generate_search());
		if(get_option('annonces_maps_activation') == 1){
			$this->concatAnnonceContent('<br/><center style="margin-top:80px;" id="annonces_listing" >');
			$this->concatAnnonceContent($this->generate_map());
			$this->concatAnnonceContent('</center><br/><br/>');
		}else{
			$this->concatAnnonceContent('<br/><br/><br/><br/><br/><br/>');
		}
		/*---- Show list annonces ----*/
		$this->concatAnnonceContent($this->list_annonce());
		
		/*---- Show annonce plugin ----*/
		return $this->addAnnoncesToContent($content);
	}
	
	public function addAnnoncesToContent($content){
		$pattern = '/<div rel="annonces" id="annonces" ><\/div>/';
		$replacement = '<div rel="annonces" id="annonces"  >'.$this->getAnnonceContent().'</div>';

		$content = preg_replace( $pattern, $replacement, $content );
		return $content;
	}
	
	function filter_plugin_actions_links($links, $file)
	{
		
		if ($file == Basename_Dirname_AOS. '/annonces.php')
		{
			$settings_link = $settings_link = '<a href="options-general.php?page=annonces/options.php">' . __('R&eacute;glages', 'annonces') . '</a>';
			array_unshift($links, $settings_link);
		}
		return $links;
	}
	
	public function getActualPage()
	{
		global $tools;
		$actual_page = isset($_REQUEST['page_nav_annonces']) ? $tools->IsValid_Variable($_REQUEST['page_nav_annonces']) : 0 ;

		return $actual_page;
	}

	/**
	* Cette méthode est appelé lorsque que le formulaire de recherche fait une requête sur le prix et/ou la superficie
	*/
	public function Filter($needle, $haystack){
		if(is_null($needle)){
			return $haystack;
		}
		if(is_null($haystack)){
			return $needle;
		}
		if(is_null($needle) and is_null($haystack)){
			return null;
		}
		$result = array();
		foreach($needle as $tinyneedle){
			if(in_array($tinyneedle,$haystack)){
				$result[count($result)] = $tinyneedle;
			}
		}
		return $result;
	}
	
	public function getPagination($morequery = null)
	{
		global $tools;
		$eav_value = new Eav();
		$link = ' onclick="javascript:document.getElementById(\'page_nav_annonces\').value=\'#PAGE#\';document.forms.navigation_form.submit();" ';

		$nb_total_items = 0;$nb_total_items = $eav_value->getAnnoncesEntete($morequery,DEFAULT_FLAG_AOS,'titre',$this->getActualPage(),'nolimit','count');
		$Pagination = '';
		if(ceil($nb_total_items/NUMBER_OF_ITEM_PAR_PAGE_FRONTEND_AOS) > 1)
		{
			$Pagination = $tools->DoPagination($link,$nb_total_items,$this->getActualPage(),NUMBER_OF_ITEM_PAR_PAGE_FRONTEND_AOS,PAGINATION_OFFSET_FRONTEND_AOS,'','','#999999','#FFFFFF');
		}
	
		return $Pagination;
	}

	public function add_css()
	{
		echo '<link rel="stylesheet" type="text/css" href="'. WP_PLUGIN_URL.'/'.Basename_Dirname_AOS. '/includes/css/annonce.css" />';
	}
	
	/**
	* Cette méthode ajoute de préférence dans le header la clé de l'API Google Maps qui permet d'afficher les cartes
	*/
	public function add_gmap()
	{
		echo '<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key='.GOOGLE_MAP_KEY_AOS.'" type="text/javascript"></script>';
	}
	
	/**
	* Cette méthode génère la petite carte que l'on peut voir dans le listing des annonces
	*/
	public function generate_map($morequery = null)
	{
		$markers = '';
		$eav_value = new Eav();
		//Afficher que les marqueurs de la page courante
		//$annonces = $eav_value->getAnnoncesEntete($morequery,DEFAULT_FLAG_AOS,'titre',$this->getActualPage());
		//Afficher tout les marqueurs sans distinction de page
		$annonces = $eav_value->getAnnoncesEntete($morequery,DEFAULT_FLAG_AOS,'titre',0,'nolimit');
		$sizei = count($annonces);
		$icon = get_option('url_marqueur_courant');
		for($i = 0; $i < $sizei; $i++)
		{
			if(!is_null($annonces[$i]->latitude) AND !is_null($annonces[$i]->longitude)){
				$surface = $eav_value->getSurface(null,'valid',null,$annonces[$i]->idpetiteannonce);
				$prix = $eav_value->getPrix(null,'valid',null,$annonces[$i]->idpetiteannonce);
				$markers .= 'var marker'.$i.' = new GMarker(new GLatLng('.$annonces[$i]->latitude.','.$annonces[$i]->longitude.'),icon);';
				$markers .='GEvent.addListener(marker'.$i.', "mouseover", function() {
							annoncemap.openInfoWindowHtml(new GLatLng('.$annonces[$i]->latitude.','.$annonces[$i]->longitude.'),  "<div style=\"color:black;cursor:pointer;\" onclick=\"window.location.href=\''.(strstr(get_permalink(), '?')? get_permalink().'&' : get_permalink().'?').'show_annonce='.$annonces[$i]->idpetiteannonce.'&show_mode=list\">&Agrave;&nbsp;<b>'.$annonces[$i]->ville.'</b>&nbsp;terrain&nbsp;&agrave;&nbsp;b&acirc;tir&nbsp;viabilis&eacute;&nbsp;surface&nbsp;<b>'.number_format($surface[0]->valueattributdec,0,',',' ').'</b>&nbsp;'.$surface[0]->measureunit.',&nbsp;prix&nbsp;<b>'.number_format($prix[0]->valueattributdec,0,',',' ').'</b>&nbsp;'.$prix[0]->measureunit.'</div><br/><a style=\"float: right;\" href=\''.(strstr(get_permalink(), '?')? get_permalink().'&' : get_permalink().'?').'show_annonce='.$annonces[$i]->idpetiteannonce.'&show_mode=list\'>'.__('En savoir plus','annonces').'</a>");
				});
				GEvent.addListener(marker'.$i.', "click", function() {
						window.location.href = \''.(strstr(get_permalink(), '?')? get_permalink().'&' : get_permalink().'?').'show_annonce='.$annonces[$i]->idpetiteannonce.'&show_mode=list\';
				});'; 
				
				$markers .='annoncemap.addOverlay(marker'.$i.');';
			}
		}
		
		$list_map = '
			<script type="text/javascript">
				<!-- Google map -->
				var annoncemap;

				function show_map() {
					if(GBrowserIsCompatible()) {
						var annoncemap = new GMap2(document.getElementById("annonceGmap"));
						<!-- controle du zoom -->
						annoncemap.addControl(new GLargeMapControl());
						annoncemap.enableScrollWheelZoom();
						<!-- controle satellite -->
						annoncemap.addControl(new GMapTypeControl());
						<!-- longitude, latitude et niveau de zoom initial -->
						annoncemap.setCenter(new GLatLng(43.604262,3.768311), 8);
						var icon = new GIcon();
						icon.image = "'.WP_PLUGIN_URL.'/'.Basename_Dirname_AOS.'/medias/images/'.$icon.'";
						icon.iconSize=new GSize(18,26);
						icon.iconAnchor=new GPoint(16,16);
						
						'.$markers.'
					}
				}
			</script>
			<div id="annonceGmap" style="height: 300px">
				<script type="text/javascript">
					show_map();
				</script>
			</div>
		';
		
		return $list_map;
	}
	
	public function getAnnonceContent()
	{
		return $this->annonce_content;
	}
	
	public function setAnnonceContent($new_annonce_content)
	{
		$this->annonce_content = $new_annonce_content;
	}
	
	public function concatAnnonceContent($new_annonce_content)
	{
		$this->annonce_content .= $new_annonce_content;
	}
	
	/*---- diplay template Search ----*/
	function generate_search()
	{	
		global $tools;
		$query = isset($_REQUEST['query']) ? $tools->IsValid_Variable($_REQUEST['query']) : '' ;
		$query1 = isset($_REQUEST['query1']) ? $tools->IsValid_Variable($_REQUEST['query1']) : '' ;
		$query2 = isset($_REQUEST['query2']) ? $tools->IsValid_Variable($_REQUEST['query2']) : '' ;
		$query3 = isset($_REQUEST['query3']) ? $tools->IsValid_Variable($_REQUEST['query3']) : '' ;
		$query4 = isset($_REQUEST['query4']) ? $tools->IsValid_Variable($_REQUEST['query4']) : '' ;
		$mode = isset($_REQUEST['mode']) ? $tools->IsValid_Variable($_REQUEST['mode']) : '' ;
	
		$filter_search = '';
		if(get_option('annonces_maps_activation') == 1){
			$filter_search .= '		
			<div class="carte-annonce">
				<div class="carte-annonce-text">
					<form name="lien" action="'.get_permalink().'" method="post">
						<input type="hidden" name="show_map" value="true"> 
						<input type="hidden" name="query" value="'.$query.'"> 
						<input type="hidden" name="query1" value="'.$query1.'"> 
						<input type="hidden" name="query2" value="'.$query2.'"> 
						<input type="hidden" name="query3" value="'.$query3.'"> 
						<input type="hidden" name="query4" value="'.$query4.'"> 
						<input type="hidden" name="mode" value="'.$mode.'"> 
						<a onclick="javascript:document.forms.lien.submit();" href=\'#\'>'.__('Affichage carte','annonces').'<div class="carte-seule-img"></div></a>
					</form>
				</div>
			</div>';
		}
		$filter_search .= '
		<form action="#annonces_listing" method="POST" style="margin-bottom:35px;" name="navigation_form" >
			<input type="hidden" name="page_nav_annonces" id="page_nav_annonces" value="" />
			<h5>'.__('Recherchez une annonce','annonces').'</h5>
			<div class="filtre">
				<img src="'.WP_PLUGIN_URL.'/'.Basename_Dirname_AOS.'/medias/images/'.get_option("url_radio_toutes_theme_courant").'" style="margin-right:4px;vertical-align:middle;cursor:pointer;width:43px;height:43px;" onclick="javascript:document.getElementById(\'toutes\').checked=true;" alt="'.__('Toutes','annonces').'"/>
				<input type="radio" id="toutes" name="mode" '.(($mode == 'all' or $mode == '')? 'checked': '').' value="all">&nbsp;
				<label for="toutes" style="cursor:pointer;">'.__('Toutes','annonces').'</label>&nbsp;&nbsp;&nbsp;
				<img src="'.WP_PLUGIN_URL.'/'.Basename_Dirname_AOS.'/medias/images/'.get_option("url_radio_terrains_theme_courant").'" style="margin-right:4px;vertical-align:middle;cursor:pointer;width:43px;height:43px;" onclick="javascript:document.getElementById(\'terrains\').checked=true;" alt="'.__('Terrains','annonces').'"/>
				<input type="radio" id="terrains" name="mode" '.(($mode == 'terrain')? 'checked': '').' value="terrain">&nbsp;
				<label for="terrains" style="cursor:pointer;">'.__('Terrains','annonces').'</label>&nbsp;&nbsp;&nbsp;
				<img src="'.WP_PLUGIN_URL.'/'.Basename_Dirname_AOS.'/medias/images/'.get_option("url_radio_maisons_theme_courant").'" style="margin-right:4px;vertical-align:middle;cursor:pointer;width:43px;height:43px;" onclick="javascript:document.getElementById(\'maisons\').checked=true;" alt="'.__('Maisons','annonces').'"/>
				<input type="radio" id="maisons" name="mode" '.(($mode == 'maison/villa')? 'checked': '').' value="maison/villa">&nbsp;
				<label for="maisons" style="cursor:pointer;">'.__('Maisons','annonces').'</label><BR/>
			</div>
			<div class="sidebar_search">    
				<b>'.__('Recherche','annonces').'&nbsp;:</b><input type="text" name="query" value="'.$query.'" id="search_keywords" /><i>'.__('(Exemple : mot-cl&eacute;, ville, d&eacute;partement...)','annonces').'</i>
			</div>
			<div class="budget">
				<p>
					<img src="'.WP_PLUGIN_URL.'/'.Basename_Dirname_AOS.'/medias/images/'.get_option("url_budget_theme_courant").'" style="float:left;margin-right:2px;width:44px;height:44px;" alt="'.__('Votre budget','annonces').'"/>
					<b>'.__('Votre budget','annonces').'&nbsp;:</b><br/>
					<label for="search_keywords_3">Min. : </label>
						<input type="text" name="query3" value="'.$query3.'" id="search_keywords_3"/>
					<label for="search_keywords_4">Max. : </label>
						<input type="text" name="query4" value="'.$query4.'" id="search_keywords_4"/>
				</p>
			</div>
			<div class="superficie">
				<p>
					<img src="'.WP_PLUGIN_URL.'/'.Basename_Dirname_AOS.'/medias/images/'.get_option("url_superficie_theme_courant").'" style="float:left;margin-right:2px;width:44px;height:44px;" alt="'.__('Superficie terrain souhait&eacute;e','annonces').'"/>
					<b>'.__('Superficie terrain souhait&eacute;e','annonces').'&nbsp;:</b><br/>
					<label for="search_keywords_1">Min. : </label>
						<input type="text" name="query1" value="'.$quer1.'" id="search_keywords_1"/>
					<label for="search_keywords_2">Max. : </label>
						<input type="text" name="query2" value="'.$query2.'" id="search_keywords_2"/>
				</p>
			</div>
			<div>
				<input style="border-width:0px;float:right;margin-top:20px;background:url('.WP_PLUGIN_URL.'/'.Basename_Dirname_AOS.'/medias/images/'.get_option("url_recherche_theme_courant").') no-repeat;width:168px;height:47px; cursor:pointer;" type="submit" value="" />
			</div>
		</form>';
		return $filter_search;
    }

	/*---- diplay template Search for map (TODO: factoriser les méthodes de recherche)----*/
  function generate_search_map()
	{
		global $tools;
		$query = isset($_REQUEST['query']) ? $tools->IsValid_Variable($_REQUEST['query']) : '' ;
		$query1 = isset($_REQUEST['query1']) ? $tools->IsValid_Variable($_REQUEST['query1']) : '' ;
		$query2 = isset($_REQUEST['query2']) ? $tools->IsValid_Variable($_REQUEST['query2']) : '' ;
		$query3 = isset($_REQUEST['query3']) ? $tools->IsValid_Variable($_REQUEST['query3']) : '' ;
		$query4 = isset($_REQUEST['query4']) ? $tools->IsValid_Variable($_REQUEST['query4']) : '' ;
		$mode = isset($_REQUEST['mode']) ? $tools->IsValid_Variable($_REQUEST['mode']) : '' ;
	
		$filter_search_map = '		
		<div class="carte-annonce">
			<div class="carte-annonce-text">
				<form name="lien" action="'.get_permalink().'" method="post">
					<input type="hidden" name="show_list" value="true"> 
					<input type="hidden" name="query" value="'.$query.'"> 
					<input type="hidden" name="query1" value="'.$query1.'"> 
					<input type="hidden" name="query2" value="'.$query2.'"> 
					<input type="hidden" name="query3" value="'.$query3.'"> 
					<input type="hidden" name="query4" value="'.$query4.'"> 
					<input type="hidden" name="mode" value="'.$mode.'"> 
					<a onclick="javascript:document.forms.lien.submit();" href=\'#\'>'.__('Liste des annonces','annonces').'<div class="annonces-img"></div></a>
				</form>
			</div>
		</div>
		
		<form action="'.(strstr(get_permalink(), '?')? get_permalink().'&' : get_permalink().'?').'show_map=true#annonces_listing" method="POST" style="margin-bottom:35px;" name="navigation_form" >
			<input type="hidden" name="page_nav_annonces" id="page_nav_annonces" value="" />
			<h5>'.__('Recherchez une annonce','annonces').'</h5>
			<div class="filtre">
				<img src="'.WP_PLUGIN_URL.'/'.Basename_Dirname_AOS.'/medias/images/'.get_option("url_radio_toutes_theme_courant").'" style="margin-right:4px;vertical-align:middle;cursor:pointer;width:43px;height:43px;" onclick="javascript:document.getElementById(\'toutes\').checked=true;" alt="'.__('Toutes','annonces').'"/>
				<input type="radio" id="toutes" name="mode" '.(($mode == 'all' or $mode == '')? 'checked': '').' value="all">&nbsp;
				<label for="toutes" style="cursor:pointer;">'.__('Toutes','annonces').'</label>&nbsp;&nbsp;&nbsp;
				<img src="'.WP_PLUGIN_URL.'/'.Basename_Dirname_AOS.'/medias/images/'.get_option("url_radio_terrains_theme_courant").'" style="margin-right:4px;vertical-align:middle;cursor:pointer;width:43px;height:43px;" onclick="javascript:document.getElementById(\'terrains\').checked=true;" alt="'.__('Terrains','annonces').'"/>
				<input type="radio" id="terrains" name="mode" '.(($mode == 'terrain')? 'checked': '').' value="terrain">&nbsp;
				<label for="terrains" style="cursor:pointer;">'.__('Terrains','annonces').'</label>&nbsp;&nbsp;&nbsp;
				<img src="'.WP_PLUGIN_URL.'/'.Basename_Dirname_AOS.'/medias/images/'.get_option("url_radio_maisons_theme_courant").'" style="margin-right:4px;vertical-align:middle;cursor:pointer;width:43px;height:43px;" onclick="javascript:document.getElementById(\'maisons\').checked=true;" alt="'.__('Maisons','annonces').'"/>
				<input type="radio" id="maisons" name="mode" '.(($mode == 'maison/villa')? 'checked': '').' value="maison/villa">&nbsp;
				<label for="maisons" style="cursor:pointer;">'.__('Maisons','annonces').'</label><BR/>
			</div>
			<div class="sidebar_search">    
				<b>'.__('Recherche','annonces').'&nbsp;:</b><input type="text" name="query" value="'.$query.'" id="search_keywords" /><i>'.__('(Exemple : mot-cl&eacute;, ville, d&eacute;partement...)','annonces').'</i>
			</div>
			<div class="budget">
				<p>
					<img src="'.WP_PLUGIN_URL.'/'.Basename_Dirname_AOS.'/medias/images/'.get_option("url_budget_theme_courant").'" style="float:left;margin-right:2px;width:44px;height:44px;" alt="'.__('Votre budget','annonces').'"/>
					<b>'.__('Votre budget','annonces').'&nbsp;:</b><br/>
					<label for="search_keywords_3">Min. : </label>
						<input type="text" name="query3" value="'.$query3.'" id="search_keywords_3"/>
					<label for="search_keywords_4">Max. : </label>
						<input type="text" name="query4" value="'.$query4.'" id="search_keywords_4"/>
				</p>
			</div>
			<div class="superficie">
				<p>
					<img src="'.WP_PLUGIN_URL.'/'.Basename_Dirname_AOS.'/medias/images/'.get_option("url_superficie_theme_courant").'" style="float:left;margin-right:2px;width:44px;height:44px;" alt="'.__('Superficie terrain souhait&eacute;e','annonces').'"/>
					<b>'.__('Superficie terrain souhait&eacute;e','annonces').'&nbsp;:</b><br/>
					<label for="search_keywords_1">Min. : </label>
						<input type="text" name="query1" value="'.$quer1.'" id="search_keywords_1"/>
					<label for="search_keywords_2">Max. : </label>
						<input type="text" name="query2" value="'.$query2.'" id="search_keywords_2"/>
				</p>
			</div>
			<div>
				<input style="border-width:0px;float:right;margin-top:20px;background:url('.WP_PLUGIN_URL.'/'.Basename_Dirname_AOS.'/medias/images/'.get_option("url_recherche_theme_courant").') no-repeat;width:168px;height:47px; cursor:pointer;" type="submit" value="" />
			</div>
		</form>';
		return $filter_search_map;
    }
	
	public function show_map($morequery = null)
	{
		$eav_value = new Eav();
		$generate_map = '';
		
		$markers = '';
		$eav_value = new Eav();
		$annonces = $eav_value->getAnnoncesEntete($morequery,DEFAULT_FLAG_AOS,'titre',0,'nolimit');
		
		$sizei = count($annonces);

		for($i = 0; $i < $sizei; $i++)
		{
			if(!is_null($annonces[$i]->latitude) AND !is_null($annonces[$i]->longitude)){
				$surface = $eav_value->getSurface(null,'valid',null,$annonces[$i]->idpetiteannonce);
				$prix = $eav_value->getPrix(null,'valid',null,$annonces[$i]->idpetiteannonce);
				$markers .='var marker'.$i.' = new GMarker(new GLatLng('.$annonces[$i]->latitude.','.$annonces[$i]->longitude.'),icon);';

				$markers .='GEvent.addListener(marker'.$i.', "mouseover", function() {
							annoncemap.openInfoWindowHtml(new GLatLng('.$annonces[$i]->latitude.','.$annonces[$i]->longitude.'),  "<div style=\"color:black;cursor:pointer;\" onclick=\"window.location.href=\''.(strstr(get_permalink(), '?')? get_permalink().'&' : get_permalink().'?').'show_annonce='.$annonces[$i]->idpetiteannonce.'&show_mode=map\'\">&Agrave;&nbsp;<b>'.$annonces[$i]->ville.'</b>&nbsp;terrain&nbsp;&agrave;&nbsp;b&acirc;tir&nbsp;viabilis&eacute;&nbsp;surface&nbsp;<b>'.number_format($surface[0]->valueattributdec,0,',',' ').'</b>&nbsp;'.$surface[0]->measureunit.',&nbsp;prix&nbsp;<b>'.number_format($prix[0]->valueattributdec,0,',',' ').'</b>&nbsp;'.$prix[0]->measureunit.'</div><br/><a style=\"float: right;\" href=\''.(strstr(get_permalink(), '?')? get_permalink().'&' : get_permalink().'?').'show_annonce='.$annonces[$i]->idpetiteannonce.'&show_mode=map\'>'.__('En savoir plus','annonces').'</a>");
				});
				GEvent.addListener(marker'.$i.', "click", function() {
						window.location.href = \''.(strstr(get_permalink(), '?')? get_permalink().'&' : get_permalink().'?').'show_annonce='.$annonces[$i]->idpetiteannonce.'&show_mode=map\';
				});';
				
				$markers .='annoncemap.addOverlay(marker'.$i.');';
			}
		}
		$icon = get_option('url_marqueur_courant');
		$generate_map = '
			<script type="text/javascript">
				<!-- Google map -->
				var annoncemap;

				function show_map() {
					if(GBrowserIsCompatible()) {
						var annoncemap = new GMap2(document.getElementById("annonceGmap"));
						<!-- controle du zoom -->
						annoncemap.addControl(new GLargeMapControl());
						annoncemap.enableScrollWheelZoom();
						<!-- controle satellite -->
						annoncemap.addControl(new GMapTypeControl());
						<!-- longitude, latitude et niveau de zoom initial -->
						annoncemap.setCenter(new GLatLng(43.496768,3.674927), 9);
						var icon = new GIcon();
						icon.image = "'.WP_PLUGIN_URL.'/'.Basename_Dirname_AOS.'/medias/images/'.$icon.'";
						icon.iconSize=new GSize(18,26);
						icon.iconAnchor=new GPoint(16,16);
						
						'.$markers.'
					}
				}
			</script>
			<div id="annonceGmap" style="height: 800px">
				<script type="text/javascript">
					show_map();
				</script>
			</div>
		';
		
		return $generate_map;	
	}
	
	public function show_annonce($id = null)
	{
		global $tools;
		$show_mode = isset($_REQUEST['show_mode']) ? $tools->IsValid_Variable($_REQUEST['show_mode']) : '' ;
	
		$eav_value = new Eav();
		$generate_annonce = '';
		
		$generate_annonce .= '<p class="retour">';
		$generate_annonce .= '<a href="'.(strstr(get_permalink(), '?')? get_permalink().'&' : get_permalink().'?').((!empty($show_mode) and ($show_mode == 'list'))? 'show_list=true' : 'show_map=true').'">';
		$generate_annonce .= '<b>&laquo;&nbsp;'.__('Retour','annonces').'</b>';
		$generate_annonce .= '</a>';
		$reference = $eav_value->getReference(null,'moderated',null,$id);
		$generate_annonce .= '<div style="float:right;font-size:120%;margin-top:-29px;">'.__('R&eacute;f&eacute;rence','annonces').':'.(is_null($reference[0]->valueattributchar) ? 'aucune' : $reference[0]->valueattributchar);
		$generate_annonce .= '</div>';
		$generate_annonce .= '</p>';
		
		$annonce = $eav_value->getAnnoncesEntete(' AND idpetiteannonce='.$id,"'valid'");

		$generate_annonce .= '<div class="annonce-titre2" style="clear:both;">';
		$generate_annonce .= $annonce[0]->titre;
		$generate_annonce .= '<br/>';
		$generate_annonce .= '</div>';
		
		$prix = $eav_value->getPrix(null,'valid',null,$id);
		$generate_annonce .= '<div class="annonce-price-show" >';
		$generate_annonce .= number_format($prix[0]->valueattributdec,0,',',' ').'&nbsp;'.$prix[0]->measureunit;
		$generate_annonce .= '</div><br/><br/>';
		
		$generate_annonce .= '<div class="annonce-surface">';
		$generate_annonce .= $annonce[0]->cp.'&nbsp;'.$annonce[0]->ville;
		$generate_annonce .= '<br/>';
		$surface = $eav_value->getSurface(null,'valid',null,$id);
		$generate_annonce .= number_format($surface[0]->valueattributdec,0,',',' ').'&nbsp;'.$surface[0]->measureunit;
		$generate_annonce .= '<br/></div>';
		
		$generate_annonce .= '<div class="annonce-description">'.__('Description','annonces').'&nbsp;:</div>';
		$generate_annonce .= '<div class="annonce-texte">';
		$description = $eav_value->getDescription(null,'valid',null,$id);
		
		/*** Attention ici faut mettre description longue et non la courte ***/
		$generate_annonce .= stripslashes($description[0]->valueattributtextcourt);
		$generate_annonce .= '</div>';
		if(get_option('annonces_date_activation') == 1){
			$generate_annonce .= '<div class="published-date">';
			$generate_annonce .= '<p id="img-date">';
			$generate_annonce .= __('Publi&eacute;e le','annonces').'&nbsp;:&nbsp;'.date("d/m/Y",strtotime($annonce[0]->autolastmodif));
			$generate_annonce .= '</p>';
			$generate_annonce .= '</div>';
		}else{
			$generate_annonce .= '<br/><br/>';
		}
		if(get_option('annonces_photos_activation') == 1){
			$generate_annonce .= '<div class="annonce-img">';
			$generate_annonce .= '<h5>'.__('Autres vues','annonces').'</h5><br/>';
			
			$photos = $eav_value->getPhotos($id);
			$sizei = count($photos);
			for($i = 0; $i < $sizei; $i++){
				if(is_file(WP_CONTENT_DIR . WAY_TO_PICTURES_THUMBNAIL_AOS . $photos[$i]->original))
				{
					$generate_annonce .= '<a title="'. $photos[$i]->original .'" href="'.WP_CONTENT_URL . WAY_TO_PICTURES_AOS . $photos[$i]->original .'" rel="lightbox[roadtrip]">';
					
					$generate_annonce .= '<img src="'.WP_CONTENT_URL . WAY_TO_PICTURES_THUMBNAIL_AOS . $photos[$i]->original.'" alt="'.$annonces[$i]->titre.'" width="140" height="132"/>';
					
					
					$generate_annonce .=  '</a>';
				}
			}
			$generate_annonce .= '</div>';
		}
		$generate_annonce .= '<div class="infocomp">';
		$generate_annonce .= '<fieldset>';
		$generate_annonce .= '<legend>'.__('INFORMATIONS COMPL&Eacute;MENTAIRES','annonces').'</legend>';
		$generate_annonce .= '<div class="infos">';
		
		$attributs = $eav_value->getAnnoncesAttributs(null,'valid',null,$id,'oui');
		$sizej = count($attributs);
		for($j = 0; $j < $sizej; $j++){
			if($attributs[$j]->labelattribut != 'Descriptif'){
				$generate_annonce .= '&#149;&nbsp;'.$attributs[$j]->nomattribut.'&nbsp;:&nbsp;';
				switch($attributs[$j]->typeattribut){
					case 'INT':
						$generate_annonce .= $attributs[$j]->valueattributint.'&nbsp;'.$attributs[$j]->measureunit.'<br/>';
					break;
					case 'DEC':
						switch($attributs[$j]->labelattribut){
							case 'PrixLoyerPrixDeCession':
								$generate_annonce .= number_format($attributs[$j]->valueattributdec,0,',',' ').'&nbsp;'.$attributs[$j]->measureunit.'<br/>';
							break;
							case 'SFTerrain':
								$generate_annonce .= number_format($attributs[$j]->valueattributdec,0,',','').'&nbsp;'.$attributs[$j]->measureunit.'<br/>';
							break;
							default:
								$generate_annonce .= $attributs[$j]->valueattributdec.'&nbsp;'.$attributs[$j]->measureunit.'<br/>';
						}
					break;
					case 'CHAR':
						$generate_annonce .= stripslashes($attributs[$j]->valueattributchar).'<br/>';
					break;
					case 'DATE':
						$generate_annonce .= $attributs[$j]->valueattributdate.'<br/>';
					break;
					case 'TEXT':
						$generate_annonce .= stripslashes($attributs[$j]->valueattributtextcourt).'<br/>';
					break;
				}
			}
		}
		
		$generate_annonce .= '</div>';
		$generate_annonce .= '</fieldset>';
		$generate_annonce .= '</div>';
		if(get_option('annonces_maps_activation') == 1){
			$generate_annonce .= '<p>';
			$generate_annonce .= '<h5>'.__('Localisation','annonces').'</h5><br/>';
			$generate_annonce .= '<center>';
			$icon = get_option('url_marqueur_courant');
			if(!is_null($annonce[0]->latitude) AND !is_null($annonce[0]->longitude)){
				$generate_annonce .= '
					<script type="text/javascript">
						<!-- Google map -->
						var annoncemap;

						function show_map() {
							if(GBrowserIsCompatible()) {
								var annoncemap = new GMap2(document.getElementById("annonceGmap"));
								<!-- controle du zoom -->
								annoncemap.addControl(new GLargeMapControl());
								annoncemap.enableScrollWheelZoom();
								<!-- controle satellite -->
								annoncemap.addControl(new GMapTypeControl());
								<!-- longitude, latitude et niveau de zoom initial -->
								annoncemap.setCenter(new GLatLng('.$annonce[0]->latitude.','.$annonce[0]->longitude.'), 12);
								var icon = new GIcon();
								icon.image = "'.WP_PLUGIN_URL.'/'.Basename_Dirname_AOS.'/medias/images/'.$icon.'";
								icon.iconSize=new GSize(18,26);
								icon.iconAnchor=new GPoint(16,16);
								
								var marker = new GMarker(new GLatLng('.$annonce[0]->latitude.','.$annonce[0]->longitude.'),icon);
								annoncemap.addOverlay(marker);
							}
						}
					</script>
					<div id="annonceGmap" style="height: 400px">
						<script type="text/javascript">
							show_map();
						</script>
					</div>
				';
			}
			$generate_annonce .= '</center>';
			$generate_annonce .= '<br/>';
			
			$generate_annonce .= '</p>';
		}
		$generate_annonce .= '<p class="retour"><a href="'.(strstr(get_permalink(), '?')? get_permalink().'&' : get_permalink().'?').((!empty($show_mode) and ($show_mode == 'list'))? 'show_list=true' : 'show_map=true').'"><b>&laquo;&nbsp;'.__('Retour','annonces').'</b></a></p>';

		return $generate_annonce;
	}
	
	public function list_annonce($morequery = null)
	{
		$eav_value = new Eav();
		$annonces = $eav_value->getAnnoncesEntete($morequery,DEFAULT_FLAG_AOS,'titre',$this->getActualPage(),null,null);
		$sizei = count($annonces);

		$generate_annonce = '';
		for($i = 0; $i < $sizei; $i++)
		{
			$generate_annonce .= '<tr class="annonce-ligne">';
			if(get_option('annonces_photos_activation') == 1){
				$generate_annonce .= '<td><div class="annonce-photos">';
				$generate_annonce .= '<a href="'.(strstr(get_permalink(), '?')? get_permalink().'&' : get_permalink().'?').'show_annonce='.$annonces[$i]->idpetiteannonce.'&show_mode=list">';
				$photos = $eav_value->getPhotos($annonces[$i]->idpetiteannonce);
				if(is_file(WP_CONTENT_DIR . WAY_TO_PICTURES_THUMBNAIL_AOS . $photos[0]->original))
				$generate_annonce .= '<img src="'.WP_CONTENT_URL . WAY_TO_PICTURES_THUMBNAIL_AOS . $photos[0]->original.'" alt="'.$annonces[$i]->titre.'" width="140" height="132"/>';
				$generate_annonce .= '</a></div></td>';
			}
			$attributs = $eav_value->getAnnoncesAttributs(null,'moderated',null,$annonces[$i]->idpetiteannonce);
			
			$generate_annonce .= '<td valign="top"><div class="annonce-container" >';
			$generate_annonce .= '<div class="annonce-titre">';
			$generate_annonce .= '<a href="'.(strstr(get_permalink(), '?')? get_permalink().'&' : get_permalink().'?').'&show_annonce='.$annonces[$i]->idpetiteannonce.'&show_mode=list">';
			$generate_annonce .= $annonces[$i]->titre;
			$generate_annonce .= '</a></div>';
			$prix = $eav_value->getPrix(null,'valid',null,$annonces[$i]->idpetiteannonce);
			$generate_annonce .= '<div class="annonce-price-list" >';
			$generate_annonce .= number_format($prix[0]->valueattributdec,0,',',' ').'&nbsp;'.$prix[0]->measureunit;
			$generate_annonce .= '</div><br/>';

			$generate_annonce .= '<div class="annonce-surface">';
			$generate_annonce .= $annonces[$i]->cp.'&nbsp;'.$annonces[$i]->ville;
			$generate_annonce .= '<br/>';
			$surface = $eav_value->getSurface(null,'valid',null,$annonces[$i]->idpetiteannonce);
			$generate_annonce .= number_format($surface[0]->valueattributdec,0,',',' ').'&nbsp;'.$surface[0]->measureunit;
			$generate_annonce .= '<br/></div>';
			
			$description = $eav_value->getDescription(null,'valid',null,$annonces[$i]->idpetiteannonce);
			$generate_annonce .= '<div class="annonce-description">'.__('Description','annonces').'&nbsp;:</div>';
			$generate_annonce .= '<div class="annonce-texte">';
			$generate_annonce .= stripslashes($description[0]->valueattributtextcourt);
			$generate_annonce .= '</div></td>';
			
			if(get_option('annonces_date_activation') == 1){
				$generate_annonce .= '<td>';
				$generate_annonce .= '<div class="annonce-date">';
				$generate_annonce .= __('Publi&eacute;e le','annonces').'<br/>';
				$generate_annonce .= date("d/m/Y",strtotime($annonces[$i]->autoinsert));
				$generate_annonce .= '</div>';
				$generate_annonce .= '</td>';
			}
			$generate_annonce .= '</tr>';
		}
		$list_result = 
			'<h5>'.__('Consultez toutes les annonces','annonces').'</h5>
				'.$this->getPagination($morequery).'
				<div >
					<div class="resultats_annonces">
						<table>
							<thead>
								<tr>';
									if(get_option('annonces_photos_activation') == 1){
										$list_result .= '<th>'.__('Photos','annonces').'</th>';
									}
									$list_result .= '<th>'.__('Annonces','annonces').'</th>';
									if(get_option('annonces_date_activation') == 1){
										$list_result .= '<th>'.__('Date','annonces').'</th>';
									}
								$list_result .= '</tr>
							</thead>
							<tbody id="annonces_list">
								'.$generate_annonce.'
							</tbody>
						</table>
					</div>
				</div>
				'.$this->getPagination($morequery);
		return $list_result;
	}
}