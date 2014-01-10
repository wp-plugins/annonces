<?php
/***************************************************
*Date: 01/10/2009      file:frontend.class.php     *
*Author: Eoxia                                     *
*Comment:                                          *
***************************************************/
require_once dirname(__FILE__).'/eav.class.php';
require_once dirname(__FILE__).'/gmap.class.php';
require_once dirname(__FILE__).'/options.class.php';

global $content;
global $post;

class annonce_frontend {

	private $annonce_content = '';


	/**
	*	Return an adds url by lookig at the configuration
	*
	*	@param integer $id The adds identifier we want to have the url for
	*
	*	@return string $annonce_link The url of the adds
	*/
	function lienUrl($id)
	{
		if (preg_match_all('(\?page_id=)', $_SERVER['REQUEST_URI'], $match) == 0)
		{
			if (annonces_url_activation == 'oui')
			{
				$annonce_link = Eav::get_link($id);
			}
			else
			{
				$annonce_link = (strstr(get_permalink(), '?')? get_permalink().'&' : get_permalink().'?').'show_annonce='.$id.'&show_mode=list';
			}
		}
		else
		{
			$annonce_link = (strstr(get_permalink(), '?')? get_permalink().'&' : get_permalink().'?').'show_annonce='.$id.'&show_mode=list';
		}

		return $annonce_link;
	}

	/*---- Parse page content looking for RegEx matches and add modify HTML to acomodate display ----*/
	/**
	*	Check the frontend current page content if there is the content <div rel="annonces" id="annonces" ></div> to replace it with the plugin output content
	*
	* @param mixed $content The current page content
	*
	*	@return mixed If the page contains the plugin shortcode, replace with the plugin content, else return the content
	*/
	function show($content) {
		if(preg_match('<[Dd][Ii][Vv] [Rr][Ee][Ll]="[Aa][Nn][Nn][Oo][Nn][Cc][Ee][Ss]" [Ii][Dd]="[Aa][Nn][Nn][Oo][Nn][Cc][Ee][Ss]" >',$content)) {
			return $this->generate($content);
		}
		else {
			return $content;
		}
	}

	/**
	*	Send an email when the user click on the "contact vendor"
	*/
	function sendMail()
	{
		/**
		*	Envoi des mails gr�ce au lien "Contacter le vendeur par email"
		**/
		if (isset ($_POST['submit']))
		{
			if (!empty($_POST['txtNom']) && preg_match('`[0-9]{10}`', $_POST['txtTel']) && preg_match('#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#', $_POST['txtEmail']) && !empty($_POST['txtMessage']))
			{
				/**
				*	Email de r�ception des demandes d'informations
				**/
					$mail = annonces_email_reception;

				/**
				*	On filtre les serveurs qui rencontrent des bogues.
				**/
					if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail))
					{
						$passage_ligne = "\r\n";
					}
					else
					{
						$passage_ligne = "\n";
					}

				/**
				*	Remplacement des variables %xxxx% par leurs valeurs POST dans la personnalisation des emails HTML
				**/
					$html = annonces_html_reception;

					$html = stripslashes(str_replace('%nom%',$_POST['txtNom'], $html));
					$html = stripslashes(str_replace('%tel%',$_POST['txtTel'], $html));
					$html = stripslashes(str_replace('%mail%',$_POST['txtEmail'], $html));
					$html = stripslashes(str_replace('%message%',$_POST['txtMessage'], $html));
					$html = stripslashes(str_replace('%id_annonce%',$_POST['id_annonce'], $html));
					$html = stripslashes(str_replace('%titre%',$_POST['titre_annonce'], $html));
					$html = stripslashes(str_replace('%url_annonce%', Eav::get_link($_POST['id_annonce']), $html));

				/**
				*	Remplacement des variables %xxxx% par leurs valeurs POST dans la personnalisation des emails TXT
				**/
					$txt = annonces_txt_reception;

					$txt = stripslashes(str_replace('%nom%',$_POST['txtNom'], $txt));
					$txt = stripslashes(str_replace('%tel%',$_POST['txtTel'], $txt));
					$txt = stripslashes(str_replace('%mail%',$_POST['txtEmail'], $txt));
					$txt = stripslashes(str_replace('%message%',$_POST['txtMessage'], $txt));
					$txt = stripslashes(str_replace('%id_annonce%',$_POST['id_annonce'], $txt));
					$txt = stripslashes(str_replace('%titre%',$_POST['titre_annonce'], $txt));
					$txt = stripslashes(str_replace('%url_annonce%', Eav::get_link($_POST['id_annonce']), $txt));


				/**
				*	D�claration des messages au format texte et au format HTML.
				**/
					$message_txt = $txt."";
					$message_html = "<html><head></head><body>" . $html . "</body></html>";

				/**
				*	Cr�ation de la boundary
				**/
					$boundary = "-----=".md5(rand());
					$boundary_alt = "-----=".md5(rand());

				/**
				*	D�finition du sujet
				**/
					$sujet = annonces_sujet_reception;

					$sujet = stripslashes(str_replace('%nom%',$_POST['txtNom'], $sujet));
					$sujet = stripslashes(str_replace('%tel%',$_POST['txtTel'], $sujet));
					$sujet = stripslashes(str_replace('%mail%',$_POST['txtEmail'], $sujet));
					$sujet = stripslashes(str_replace('%message%',$_POST['txtMessage'], $sujet));
					$sujet = stripslashes(str_replace('%id_annonce%',$_POST['id_annonce'], $sujet));
					$sujet = stripslashes(str_replace('%titre%',$_POST['titre_annonce'], $sujet));
					$sujet = stripslashes(str_replace('%url_annonce%', Eav::get_link($_POST['id_annonce']), $sujet));

				/**
				*	Cr�ation du header de l'e-mail
				**/

					$header = "From: \"" . $_POST['txtNom'] . "\"<" . $_POST['txtEmail'] . ">".$passage_ligne;
					$header.= "Reply-to: \"" . $_POST['txtNom'] . "\" <" . $_POST['txtEmail'] . ">".$passage_ligne;
					$header.= "MIME-Version: 1.0".$passage_ligne;
					$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;

				/**
				*	Cr�ation du message
				**/
					$message = $passage_ligne.$boundary.$passage_ligne;

				/**
				*	Ajout du message au format texte
				**/
					$message.= "Content-Type: text/plain; charset=\"utf-8\"".$passage_ligne;
					$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
					$message.= $passage_ligne.$message_txt.$passage_ligne;

					$message.= $passage_ligne."--".$boundary.$passage_ligne;
				/**
				*	Ajout du message au format HTML
				**/
					$message.= "Content-Type: text/html; charset=\"utf-8\"".$passage_ligne;
					$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
					$message.= $passage_ligne.$message_html.$passage_ligne;

					$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
					$message.= $passage_ligne."--".$boundary."--".$passage_ligne;

				/**
				*	Envoi de l'e-mail
				**/
					mail($mail,$sujet,$message,$header);

				echo '<div class="contact_success">' . __('Votre demande a correctement &eacute;t&eacute; envoy&eacute;e, vous recevrez prochainement une r&eacute;ponse.<br/>Cordialement','annonce') . '</div><br/>';
			}
			else
			{
				$message_error = __('L\'envoi de votre demande d\'information(s) n\'a pu aboutir :','annonces') . '<br/>';

				if (empty($_POST['txtNom']))
				{
					$message_error .= '<div class="contact_error">' . __('Le nom est incomplet','annonces') . '</div>';
				}
				if (empty($_POST['txtMessage']))
				{
					$message_error .= '<div class="contact_error">' . __('Le message est incomplet','annonces') . '</div>';
				}
				if (!preg_match('`[0-9]{10}`', $_POST['txtTel']))
				{
					$message_error .= '<div class="contact_error">' . __('Le t&eacute;l&eacute;phone est incomplet ou incorrect','annonces') . '</div>';
				}
				if (!preg_match('#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#', $_POST['txtEmail']))
				{
					$message_error .= '<div class="contact_error">' . __('L\'adresse email est incompl&egrave;te ou incorrecte','annonces') . '</div>';
				}

				$message_error .= __('Veuillez rectifier ces champs pour que l\'envoi de votre email se fasse','annonces');
				$message_error .= '<br/><br/>';
				echo $message_error;
			}
		}
	}

	/**
	*
	*/
	function generate($content)
	{
		global $tools;
		global $wpdb;

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
			Si le crit�re de recherche n'est que le type de bien, on rentre dans la fonction sinon on fait la recherche avec les autres crit�res
			**/
			if(empty($query) AND empty($query1) AND empty($query2) AND empty($query3) AND empty($query4) AND !empty($mode)){
				$eav_mode = new Eav();
				$annonces = $eav_mode->getAnnoncesEntete($morequery,DEFAULT_FLAG_AOS,annonce_frontend_listing_order,0,'nolimit');

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

				//Supprime la derni�re virgule de la liste des IDs
				$size = strlen($idarray);
				$id = substr($idarray, 0, $size-1);

				$result_search = ' AND ANN.idpetiteannonce IN ('.$id.') ';

				if($show_map == 'true' and (annonces_maps_activation == 'oui')){
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
				if(annonces_maps_activation == 'oui'){
					$this->concatAnnonceContent('<br/><center class="annonces_listing" id="annonces_listing" >');

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
			}
			else
			{
				$searchResult = array();
				$array_query = split(' ',$query);
				foreach($array_query as $searchWordIndex => $search)
				{
					$query =
						"(SELECT idpetiteannonce
						FROM " . $wpdb->prefix . "ctlg_petiteannonce
						WHERE titre LIKE '%" . mysql_real_escape_string($search) . "%'
							OR referenceagencedubien LIKE '%" . mysql_real_escape_string($search) . "%'
							OR urlannonce LIKE '%" . mysql_real_escape_string($search) . "%'
							AND flagvalidpetiteannonce = 'valid')
							UNION
						(SELECT idpetiteannonce
						FROM " . $wpdb->prefix . "ctlg_petiteannonce__attributchar
						WHERE valueattributchar LIKE '%" . mysql_real_escape_string($search) . "%'
							AND flagvalidattributchar = 'valid')
							UNION
						(SELECT idpetiteannonce
						FROM " . $wpdb->prefix . "ctlg_petiteannonce__attributdate
						WHERE valueattributdate LIKE '%" . mysql_real_escape_string($search) . "%'
							AND flagvalidattributdate = 'valid')
							UNION
						(SELECT idpetiteannonce
						FROM " . $wpdb->prefix . "ctlg_petiteannonce__attributdec
						WHERE valueattributdec LIKE '%" . mysql_real_escape_string($search) . "%'
							AND flagvalidattributdec = 'valid')
							UNION
						(SELECT idpetiteannonce
						FROM " . $wpdb->prefix . "ctlg_petiteannonce__attributint
						WHERE valueattributint LIKE '%" . mysql_real_escape_string($search) . "%'
							AND flagvalidattributint = 'valid')
							UNION
						(SELECT idpetiteannonce
						FROM " . $wpdb->prefix . "ctlg_petiteannonce__attributtext
							LEFT JOIN " . $wpdb->prefix . "ctlg_petiteannonce__txt ON (idtxt = valueattributtextlong)
						WHERE ((valueattributtextcourt LIKE '%" . mysql_real_escape_string($search) . "%') OR (txtlong LIKE '%" . mysql_real_escape_string($search) . "%'))
							AND flagvalidattributtext = 'valid')
							UNION
						(SELECT iddest
						FROM " . $wpdb->prefix . "ctlg_petiteannonce__geolocalisation
						WHERE (
							(autolocalisation LIKE '%" . mysql_real_escape_string($search) . "%') OR (adresse LIKE '%" . mysql_real_escape_string($search) . "%')
							OR (ville LIKE '%" . mysql_real_escape_string($search) . "%') OR (departement LIKE '%" . mysql_real_escape_string($search) . "%')
							OR (region LIKE '%" . mysql_real_escape_string($search) . "%') OR (cp LIKE '%" . mysql_real_escape_string($search) . "%')
							OR (pays LIKE '%" . mysql_real_escape_string($search) . "%')
						)
							AND flagvalidgeolocalisation = 'valid') ";
					$searchResult = array_merge($searchResult, $wpdb->get_results($query));
				}
				$values = null;
				$checkprix = null;
				$checksurface = null;
				$limite = count($hits);
				$check_status = new Eav();
				foreach ($searchResult as $i => $annonces)
				{
					$annonce = $check_status->getAnnoncesEntete(" AND ANN.idpetiteannonce ='".$annonces->idpetiteannonce."' ","'valid'",annonce_frontend_listing_order,0,'nolimit','count');
					if($annonce>0)
					{
						$values[$i] = $annonces->idpetiteannonce;
					}
				}

				if(!empty($query3) OR !empty($query4))
				{
					$eav_value = new Eav();
					$morequery = (!empty($query4) ? " AND ATT_DEC.valueattributdec <= ".$query4." " : "").(!empty($query3) ? " AND ATT_DEC.valueattributdec >= ".$query3." " : "");
					$prix = $eav_value->getPrix($morequery,'valid');
					foreach($prix as $i => $instance)
					{
						$checkprix[$i] = $instance->idpetiteannonce;
					}
				}

				if(!empty($query1) OR !empty($query2))
				{
					$eav_value = new Eav();
					$morequery = (!empty($query2) ? " AND ATT_DEC.valueattributdec <= ".$query2." " : "").(!empty($query1) ? " AND ATT_DEC.valueattributdec >= ".$query1." " : "");
					$surface = $eav_value->getSurface($morequery,'valid');
					foreach($surface as $i => $instance)
					{
						$checksurface[$i] = $instance->idpetiteannonce;
					}
				}

				$filter = $this->Filter($checkprix,$checksurface);
				$checkid = $this->Filter($values,$filter);
				if(!is_null($checkid))
				{
					if(!empty($mode) and ($mode != 'all'))
					{
						$eav_mode = new Eav();
						foreach($checkid as $i)
						{
							$eav_mode_value = $eav_mode->getTypeBien(null,'valid',null,$i);
							if($eav_mode_value[0]->valueattributchar == $mode)
							{
								$idarray .= $i.',';
							}
						}
					}
					else
					{
						foreach($checkid as $i)
						{
							$idarray .= $i.',';
						}
					}

					//Supprime la derni�re virgule de la liste des IDs
					$size = strlen($idarray);
					$id = substr($idarray, 0, $size-1);

					$result_search = ' AND ANN.idpetiteannonce IN ('.$id.') ';
				}

				if($show_map == 'true')
				{
					/*---- Show map ----*/
					$this->concatAnnonceContent($this->generate_search_map());
					if(!empty($id))
					{
						$this->concatAnnonceContent('<br/><br/><br/><br/><br/><br/><br/><br/>');
						$this->concatAnnonceContent($this->show_map($result_search));
					}
					else
					{
						$result_search = ' AND ANN.idpetiteannonce IN (0) ';
						$this->concatAnnonceContent('<br/><br/><br/><br/><br/><br/><p>'.__('Aucune annonce ne r&eacute;pond &agrave; vos crit&egrave;res.','annonces').'</p><br/>');
						$this->concatAnnonceContent($this->show_map($result_search));
					}
					/*---- Show annonce plugin ----*/
					return $this->addAnnoncesToContent($content);
				}

				/*---- Show search filter ----*/
				$this->concatAnnonceContent($this->generate_search());
				if(annonces_maps_activation == 'oui')
				{
					$this->concatAnnonceContent('<br/><center class="annonces_listing" id="annonces_listing" >');
					if(empty($id))
						{$result_search = ' AND ANN.idpetiteannonce IN (0) ';}
					$this->concatAnnonceContent($this->generate_map($result_search));
					$this->concatAnnonceContent('</center><br/><br/>');
				}
				else
				{
					$this->concatAnnonceContent('<br/><br/><br/><br/><br/><br/>');
				}

				/*---- Show list annonces ----*/
				if(!empty($id))
				{
					$this->concatAnnonceContent($this->list_annonce($result_search));
				}
				else
				{
					$this->concatAnnonceContent('<br/><p>'.__('Aucune annonce ne r&eacute;pond &agrave; vos crit&egrave;res.','annonces').'</p><br/>');
				}

				/*---- Show annonce plugin ----*/
				return $this->addAnnoncesToContent($content);
			}
		}

		/**
		*	METHODE QUI AFFICHE L'ANNONCE
		**/
		$url_page_annonce = site_url() . '/' . Eav::recupPageAnnonce();

		$nb_carac_url_page = strlen($url_page_annonce);

		$nb_url = strlen($url_page_annonce)+1;

		if (strlen('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) > $nb_url)
		{
			if (annonces_url_activation == 'oui')
			{
				$lurl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
				$url = substr($lurl, $nb_url , 500);

				if(Eav::get_annonce($url))
				{
					if(annonces_email_activation == 'oui')
					{
						$this->sendMail();
					}
					$this->concatAnnonceContent($this->show_annonce(Eav::get_annonce($url)));
					return $this->addAnnoncesToContent($content);
				}
				else
				{
					if(isset($_REQUEST['show_annonce']))
					{
						/*---- Show annonce ----*/
						$this->concatAnnonceContent($this->show_annonce($_REQUEST['show_annonce']));

						/*---- Show annonce plugin ----*/
						return $this->addAnnoncesToContent($content);
					}
				}
			}
			else
			{
				if(isset($_REQUEST['show_annonce']))
				{
					/*---- Show annonce ----*/
					$this->concatAnnonceContent($this->show_annonce($_REQUEST['show_annonce']));

					/*---- Show annonce plugin ----*/
					return $this->addAnnoncesToContent($content);
				}
			}
		}

		if(!empty($show_map) and (annonces_maps_activation == 'oui'))
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
		if(annonces_maps_activation == 'oui'){
			$this->concatAnnonceContent('<br/><center class="annonces_listing" id="annonces_listing" >');
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

	/**
	*
	*/
	function addAnnoncesToContent($content)
	{
		$pattern = '/<div rel="annonces" id="annonces" ><\/div>/';
		$replacement = '<div rel="annonces" id="annonces"  >'.$this->getAnnonceContent().'</div>';

		$content = preg_replace( $pattern, $replacement, $content );
		return $content;
	}

	/**
	*
	*/
	function filter_plugin_actions_links($links, $file)
	{

		if ($file == ANNONCES_PLUGIN_DIR. '/annonces.php')
		{
			$settings_link = $settings_link = '<a href="options-general.php?page=annonces_options">' . __('R&eacute;glages', 'annonces') . '</a>';
			array_unshift($links, $settings_link);
		}
		return $links;
	}

	/**
	*
	*/
	function getActualPage()
	{
		global $tools;
		$actual_page = isset($_REQUEST['page_nav_annonces']) ? $tools->IsValid_Variable($_REQUEST['page_nav_annonces']) : 0 ;

		return $actual_page;
	}

	/**
	* Cette m�thode est appel� lorsque que le formulaire de recherche fait une requ�te sur le prix et/ou la superficie
	*/
	function Filter($needle, $haystack)
	{
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

	/**
	*
	*/
	function getPagination($morequery = null)
	{
		global $tools;
		$eav_value = new Eav();
		$link = ' onclick="javascript:document.getElementById(\'page_nav_annonces\').value=\'#PAGE#\';document.forms.navigation_form.submit();" ';

		$nb_total_items = 0;$nb_total_items = $eav_value->getAnnoncesEntete($morequery,DEFAULT_FLAG_AOS,annonce_frontend_listing_order,$this->getActualPage(),'nolimit','count');
		$Pagination = '';
		if(ceil($nb_total_items/NUMBER_OF_ITEM_PAR_PAGE_FRONTEND_AOS) > 1)
		{
			$Pagination = $tools->DoPagination($link,$nb_total_items,$this->getActualPage(),NUMBER_OF_ITEM_PAR_PAGE_FRONTEND_AOS,PAGINATION_OFFSET_FRONTEND_AOS,'','','#999999','#FFFFFF');
		}

		return $Pagination;
	}


	/**
	* Add the google maps api callback into wordpress header
	*/
	function add_gmap() {
		if (annonces_maps_activation == 'oui') {
			wp_enqueue_script('annonces_js_maps', 'http://maps.google.com/maps/api/js?sensor=true', '', ANNONCE_PLUGIN_VERSION);
		}
	}

	/**
	* Add the different needed javascript into the header
	*/
	function add_js()
	{
		if(!wp_script_is('jquery', 'queue')) {
			wp_enqueue_script('jquery');
		}
		wp_enqueue_script('annonces_js_jq_swfobject', ANNONCES_JS_URL . 'swfobject.js', '', ANNONCE_PLUGIN_VERSION);
		$this->add_gmap();
	}

	/**
	* Add the different needed css into the header
	*/
	function add_css()
	{
		wp_register_style('annonces_css_main', ANNONCES_CSS_URL . 'annonce.css', '', ANNONCE_PLUGIN_VERSION);
		wp_enqueue_style('annonces_css_main');
		wp_register_style('annonces_css_fileuploader', ANNONCES_CSS_URL . 'fileuploader.css', '', ANNONCE_PLUGIN_VERSION);
		wp_enqueue_style('annonces_css_fileuploader');
	}

	/**
	* Cette m�thode g�n�re la petite carte que l'on peut voir dans le listing des annonces
	*/
	function generate_map($morequery = null)
	{
		$markers = '';
		$eav_value = new Eav();
		//Afficher que les marqueurs de la page courante
		$annonces = $eav_value->getAnnoncesEntete($morequery,DEFAULT_FLAG_AOS,annonce_frontend_listing_order,$this->getActualPage());
		//Afficher tout les marqueurs sans distinction de page
		// $annonces = $eav_value->getAnnoncesEntete($morequery,DEFAULT_FLAG_AOS,annonce_frontend_listing_order,0,'nolimit');
		$sizei = count($annonces);
		for($i = 0; $i < $sizei; $i++)
		{
			$annonce_link_1 = $this->lienUrl( $annonces[$i]->idpetiteannonce );

			if(!is_null($annonces[$i]->latitude) AND !is_null($annonces[$i]->longitude)){
				$surface = $eav_value->getSurface(null,'valid',null,$annonces[$i]->idpetiteannonce);
				$prix = $eav_value->getPrix(null,'valid',null,$annonces[$i]->idpetiteannonce);
				$description = $eav_value->getDescription(null,'valid',null,$annonces[$i]->idpetiteannonce);

				$markers .= '
				var marker'.$i.' = new google.maps.Marker({
						position: new google.maps.LatLng('.$annonces[$i]->latitude.','.$annonces[$i]->longitude.'),
						icon: "' . WP_CONTENT_URL . WAY_TO_PICTURES_AOS . url_marqueur_courant . '",
						map: annoncemap,
					});';
				$markers .='google.maps.event.addListener(marker'.$i.', "mouseover", function() {
					infowindow.setContent( "<div class=\"markersgoogle\" onclick=\"window.location.href=\''.Eav::recupPageAnnonce().'/'.$annonce_link_1.'\">'.$annonces[$i]->titre.'<br/><b>'.number_format($surface[0]->valueattributdec,0,',',' ').'</b>&nbsp;'.$surface[0]->measureunit.'&nbsp;&agrave;&nbsp;<b>'.$annonces[$i]->ville.'</b>,&nbsp;prix&nbsp;<b>'.number_format($prix[0]->valueattributdec,0,',',' ').'</b>&nbsp;'.$prix[0]->measureunit.'</div><br/><a class=\"amarkers\" href=\''.Eav::recupPageAnnonce().'/'.$annonce_link_1.'\'>'.__('En savoir plus','annonces').'</a>" );
		        	infowindow.open(annoncemap, marker'.$i.');
				});
				google.maps.event.addListener(marker'.$i.', "click", function() {
						window.location.href = \''.Eav::recupPageAnnonce().'/'.$annonce_link_1.'\';
				});';

			}
		}

		$list_map = '
			<script type="text/javascript">
				<!-- Google map -->
				var annoncemap;
				var infowindow = new google.maps.InfoWindow();
				function initialize(){
			        var mapOptions = {
			          center: new google.maps.LatLng(43.61,3.88),
			          zoom: 9
			        };
		        	var annoncemap = new google.maps.Map(document.getElementById("annonceGmap"),
		            	mapOptions);

					'.$markers.'
		      	}
		      	google.maps.event.addDomListener(window, "load", initialize);
			</script>
			<div id="annonceGmap" class="GMAP1"></div>
		';

		return $list_map;
	}

	function search_engine()
	{

	}

	function getAnnonceContent()
	{
		return $this->annonce_content;
	}

	function setAnnonceContent($new_annonce_content)
	{
		$this->annonce_content = $new_annonce_content;
	}

	function concatAnnonceContent($new_annonce_content)
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
		if(annonces_maps_activation == 'oui')
		{
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
						<div class="carte-seule-texte"><a onclick="javascript:document.forms.lien.submit();" href=\'#\'>'.__('Affichage carte','annonces').'</div><div class="carte-seule-image">&nbsp;</div></a>
					</form>
				</div>
			</div>';
		}
		$filter_search .= '
		<form action="#annonces_listing" method="post" class="navigation_form" name="navigation_form" >
			<input type="hidden" name="page_nav_annonces" id="page_nav_annonces" value="" />
			<h5>'.__('Recherchez une annonce','annonces').'</h5>
			<div class="filtre">
				<img src="'.WP_CONTENT_URL . WAY_TO_PICTURES_AOS . url_radio_toutes_theme_courant.'"  onclick="javascript:document.getElementById(\'toutes\').checked=true;" alt="'.__('Toutes','annonces').'" />
				<input type="radio" id="toutes" name="mode" '.(($mode == 'all' or $mode == '')? 'checked': '').' value="all">&nbsp;
				<label for="toutes" >'.__('Toutes','annonces').'</label>&nbsp;&nbsp;&nbsp;
				<img src="'.WP_CONTENT_URL . WAY_TO_PICTURES_AOS . url_radio_terrains_theme_courant.'"  onclick="javascript:document.getElementById(\'terrains\').checked=true;" alt="'.__('Terrains','annonces').'"/>
				<input type="radio" id="terrains" name="mode" '.(($mode == 'terrain')? 'checked': '').' value="terrain">&nbsp;
				<label for="terrains" >'.__('Terrains','annonces').'</label>&nbsp;&nbsp;&nbsp;
				<img src="'.WP_CONTENT_URL . WAY_TO_PICTURES_AOS . url_radio_maisons_theme_courant.'"  onclick="javascript:document.getElementById(\'maisons\').checked=true;" alt="'.__('Maisons','annonces').'"/>
				<input type="radio" id="maisons" name="mode" '.(($mode == 'maison/villa')? 'checked': '').' value="maison/villa">&nbsp;
				<label for="maisons" >'.__('Maisons','annonces').'</label><BR/>
			</div>
			<div class="sidebar_search">
				<b>'.__('Recherche','annonces').'&nbsp;:</b><input type="text" name="query" value="'.$query.'" id="search_keywords" /><i>'.__('(Exemple : mot-cl&eacute;, ville, d&eacute;partement...)','annonces').'</i>
			</div>
			<div class="budget">
				<p>
					<img src="'.WP_CONTENT_URL . WAY_TO_PICTURES_AOS . url_budget_theme_courant.'"  alt="'.__('Votre budget','annonces').'"/>
					<b>'.__('Votre budget','annonces').'&nbsp;:</b><br/>
					<label for="search_keywords_3">Min. : </label>
						<input type="text" name="query3" value="'.$query3.'" id="search_keywords_3"/>
					<label for="search_keywords_4">Max. : </label>
						<input type="text" name="query4" value="'.$query4.'" id="search_keywords_4"/>
				</p>
			</div>
			<div class="superficie">
				<p>
					<img src="'.WP_CONTENT_URL . WAY_TO_PICTURES_AOS . url_superficie_theme_courant.'" '.__('Superficie terrain souhait&eacute;e','annonces').'"/>
					<b>'.__('Superficie terrain souhait&eacute;e','annonces').'&nbsp;:</b><br/>
					<label for="search_keywords_1">Min. : </label>
						<input type="text" name="query1" value="'.$query1.'" id="search_keywords_1"/>
					<label for="search_keywords_2">Max. : </label>
						<input type="text" name="query2" value="'.$query2.'" id="search_keywords_2"/>
				</p>
			</div>
			<div>
				<input class="inputrecherche" style="background:url('.WP_CONTENT_URL . WAY_TO_PICTURES_AOS . url_recherche_theme_courant.') no-repeat;" type="submit" value="" />
			</div>
		</form>';
		return $filter_search;
	}

	/*---- diplay template Search for map (TODO: factoriser les m�thodes de recherche)----*/
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
					<div class="lst_annonce"><a onclick="javascript:document.forms.lien.submit();" href=\'#\'>'.__('Liste des annonces','annonces').'<div class="annonces-img"></div></a></div>
				</form>
			</div>
		</div>

		<form action="'.(strstr(get_permalink(), '?')? get_permalink().'&' : get_permalink().'?').'show_map=true#annonces_listing" method="post" class="navigation_form" name="navigation_form" >
			<input type="hidden" name="page_nav_annonces" id="page_nav_annonces" value="" />
			<h5>'.__('Recherchez une annonce','annonces').'</h5>
			<div class="filtre">
				<img src="'.WP_CONTENT_URL . WAY_TO_PICTURES_AOS . url_radio_toutes_theme_courant.'"  onclick="javascript:document.getElementById(\'toutes\').checked=true;" alt="'.__('Toutes','annonces').'"/>
				<input type="radio" id="toutes" name="mode" '.(($mode == 'all' or $mode == '')? 'checked': '').' value="all">&nbsp;
				<label for="toutes" >'.__('Toutes','annonces').'</label>&nbsp;&nbsp;&nbsp;
				<img src="'.WP_CONTENT_URL . WAY_TO_PICTURES_AOS . url_radio_terrains_theme_courant.'"  onclick="javascript:document.getElementById(\'terrains\').checked=true;" alt="'.__('Terrains','annonces').'"/>
				<input type="radio" id="terrains" name="mode" '.(($mode == 'terrain')? 'checked': '').' value="terrain">&nbsp;
				<label for="terrains" >'.__('Terrains','annonces').'</label>&nbsp;&nbsp;&nbsp;
				<img src="'.WP_CONTENT_URL . WAY_TO_PICTURES_AOS . url_radio_maisons_theme_courant.'"  onclick="javascript:document.getElementById(\'maisons\').checked=true;" alt="'.__('Maisons','annonces').'"/>
				<input type="radio" id="maisons" name="mode" '.(($mode == 'maison/villa')? 'checked': '').' value="maison/villa">&nbsp;
				<label for="maisons" >'.__('Maisons','annonces').'</label><BR/>
			</div>
			<div class="sidebar_search">
				<b>'.__('Recherche','annonces').'&nbsp;:</b><input type="text" name="query" value="'.$query.'" id="search_keywords" /><i>'.__('(Exemple : mot-cl&eacute;, ville, d&eacute;partement...)','annonces').'</i>
			</div>
			<div class="budget">
				<p>
					<img src="'.WP_CONTENT_URL . WAY_TO_PICTURES_AOS . url_budget_theme_courant.'"  alt="'.__('Votre budget','annonces').'"/>
					<b>'.__('Votre budget','annonces').'&nbsp;:</b><br/>
					<label for="search_keywords_3">Min. : </label>
						<input type="text" name="query3" value="'.$query3.'" id="search_keywords_3"/>
					<label for="search_keywords_4">Max. : </label>
						<input type="text" name="query4" value="'.$query4.'" id="search_keywords_4"/>
				</p>
			</div>
			<div class="superficie">
				<p>
					<img src="'.WP_CONTENT_URL . WAY_TO_PICTURES_AOS . url_superficie_theme_courant.'"  alt="'.__('Superficie terrain souhait&eacute;e','annonces').'"/>
					<b>'.__('Superficie terrain souhait&eacute;e','annonces').'&nbsp;:</b><br/>
					<label for="search_keywords_1">Min. : </label>
						<input type="text" name="query1" value="'.$query1.'" id="search_keywords_1"/>
					<label for="search_keywords_2">Max. : </label>
						<input type="text" name="query2" value="'.$query2.'" id="search_keywords_2"/>
				</p>
			</div>
			<div>
				<input class="inputrecherche" style="background:url('.WP_CONTENT_URL . WAY_TO_PICTURES_AOS . url_recherche_theme_courant.') no-repeat;" type="submit" value="" />
			</div>
		</form>';
		return $filter_search_map;
	}

	function show_map($morequery = null)
	{
		$eav_value = new Eav();
		$generate_map = '';

		$markers = '';
		$eav_value = new Eav();
		$annonces = $eav_value->getAnnoncesEntete($morequery,DEFAULT_FLAG_AOS,annonce_frontend_listing_order,0,'nolimit');

		$sizei = count($annonces);

		for($i = 0; $i < $sizei; $i++)
		{
			$annonce_link_1 = $this->lienUrl($annonces[$i]->idpetiteannonce);

			if(!is_null($annonces[$i]->latitude) AND !is_null($annonces[$i]->longitude)){
				$surface = $eav_value->getSurface(null,'valid',null,$annonces[$i]->idpetiteannonce);
				$prix = $eav_value->getPrix(null,'valid',null,$annonces[$i]->idpetiteannonce);
				$description = $eav_value->getDescription(null,'valid',null,$annonces[$i]->idpetiteannonce);

				$markers .='var marker'.$i.' = new google.maps.Marker({
						position: new google.maps.LatLng('.$annonces[$i]->latitude.','.$annonces[$i]->longitude.'),
						icon: "' . WP_CONTENT_URL . WAY_TO_PICTURES_AOS . url_marqueur_courant . '",
						map: annoncemap,
					});';
				$markers .='google.maps.event.addListener(marker'.$i.', "mouseover", function() {
					infowindow.setContent( "<div class=\"markersgoogle2\" onclick=\"window.location.href=\''.Eav::recupPageAnnonce().'/'.$annonce_link_1.'\">'.$annonces[$i]->titre.'<br/><b>'.number_format($surface[0]->valueattributdec,0,',',' ').'</b>&nbsp;'.$surface[0]->measureunit.'&nbsp;&agrave;&nbsp;<b>'.$annonces[$i]->ville.'</b>,&nbsp;prix&nbsp;<b>'.number_format($prix[0]->valueattributdec,0,',',' ').'</b>&nbsp;'.$prix[0]->measureunit.'</div><br/><a class=\"amarkers\" href=\''.Eav::recupPageAnnonce().'/'.$annonce_link_1.'\'>En savoir plus</a>" );
		        	infowindow.open(annoncemap, marker'.$i.');
				});
				google.maps.event.addListener(marker'.$i.', "click", function() {
						window.location.href=\''.Eav::recupPageAnnonce().'/'.$annonce_link_1.'\';
				});';
			}
		}
		$generate_map = '
			<script type="text/javascript">
				<!-- Google map -->
				var annoncemap;
				var infowindow = new google.maps.InfoWindow();

				function initialize(){
			        var mapOptions = {
			          center: new google.maps.LatLng(43.61,3.88),
			          zoom: 9
			        };
		        	var annoncemap = new google.maps.Map(document.getElementById("annonceGmap"),
		            	mapOptions);
					'.$markers.'
		      	}
		      	google.maps.event.addDomListener(window, "load", initialize);
			</script>
			<div id="annonceGmap" class="GMAP2" ></div>
		';

		return $generate_map;
	}

	function show_annonce($id = null)
	{

		if (preg_match_all('(\?page_id=)', $_SERVER['REQUEST_URI'], $match) == 0)
		{
			$retour_link = Eav::recupPageAnnonce();
		}
		else
		{
			$retour_link =(strstr(get_permalink(), '?')? get_permalink().'&' : get_permalink().'?').((!empty($show_mode) and ($show_mode == 'list'))? 'show_list=true' : '');
		}

		global $tools;
		$show_mode = isset($_REQUEST['show_mode']) ? $tools->IsValid_Variable($_REQUEST['show_mode']) : '' ;

		$eav_value = new Eav();
		$generate_annonce = '';

		$annonce = $eav_value->getAnnoncesEntete(' AND ANN.idpetiteannonce='.$id,"'valid'");

		$generate_annonce .= '<a href="' . get_option('siteurl') . '/' . $retour_link . '">';
		$generate_annonce .= '<b>&laquo;&nbsp;'.__('Retour','annonces').'</b>';
		$generate_annonce .= '</a>';
		$reference = $annonce[0]->referenceagencedubien;
		$generate_annonce .= '<div class="GAreference">'.__('R&eacute;f&eacute;rence','annonces').':'.(is_null($reference) ? 'aucune' : $reference);
		$generate_annonce .= '</div>';
		$generate_annonce .= '</p>';

		$generate_annonce .= '<div class="annonce-titre2">';
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
		if(annonces_date_activation == 'oui'){
			$generate_annonce .= '<div class="published-date">';
			$generate_annonce .= '<p id="img-date">';
			$generate_annonce .= __('Publi&eacute;e le','annonces').'&nbsp;:&nbsp;'.date("d/m/Y",strtotime($annonce[0]->autolastmodif));
			$generate_annonce .= '</p>';
			$generate_annonce .= '</div>';
		}else{
			$generate_annonce .= '<br/><br/>';
		}
		if(annonces_photos_activation == 'oui'){
			$generate_annonce .= '<div class="annonce-img">';
			$generate_annonce .= '<h5>'.__('Autres vues','annonces').'</h5><br/>';

			$photos = $eav_value->getPhotos($id);
			$sizei = count($photos);
			for($i = 0; $i < $sizei; $i++)
			{
				if(is_file(WP_CONTENT_DIR . WAY_TO_PICTURES_THUMBNAIL_AOS . $photos[$i]->original))
				{
					$generate_annonce .= '<a id="example' . ($i + 1) . '" title="'. $photos[$i]->original .'" href="'.WP_CONTENT_URL . WAY_TO_PICTURES_AOS . $photos[$i]->original .'" ><img src="'.WP_CONTENT_URL . WAY_TO_PICTURES_THUMBNAIL_AOS . $photos[$i]->original.'" alt="'.$annonces[$i]->titre.'" class="GAimg"/></a>';
				}
			}
			$generate_annonce .= '</div>';
		}

		if(annonces_email_activation == 'oui')
		{
			$generate_annonce .= '<br/><br/><div class="lien_contact">';
			$generate_annonce .= '<a href="#form_contact"><label>';
			$generate_annonce .= __('Contacter le vendeur par email','annonces');
			$generate_annonce .= '</label></a>';
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
						$generate_annonce .= ucwords(stripslashes($attributs[$j]->valueattributchar)).'<br/>';
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
		if(annonces_maps_activation == 'oui'){
			$generate_annonce .= '<p>';
			$generate_annonce .= '<h5>'.__('Localisation','annonces').'</h5><br/>';
			$generate_annonce .= '<center>';
			if(!is_null($annonce[0]->latitude) AND !is_null($annonce[0]->longitude)){
				$generate_annonce .= '
				<script type="text/javascript">
					<!-- Google map -->
					var annoncemap;
					function initialize(){
				        var mapOptions = {
				          center: new google.maps.LatLng('.$annonce[0]->latitude.','.$annonce[0]->longitude.'),
				          zoom: 13
				        };
			        	var annoncemap = new google.maps.Map(document.getElementById("annonceGmap"), mapOptions);

						var marker = new google.maps.Marker({
				          	position: new google.maps.LatLng('.$annonce[0]->latitude.','.$annonce[0]->longitude.'),
				          	map: annoncemap,
				          	icon: "' . WP_CONTENT_URL . WAY_TO_PICTURES_AOS . url_marqueur_courant . '",
				        });
			      	}
			      	google.maps.event.addDomListener(window, "load", initialize);
				</script>
				<div id="annonceGmap" class="GMAP4"></div>
				';
			}
			$generate_annonce .= '</center>';
			$generate_annonce .= '<br/>';

			$generate_annonce .= '</p>';
		}
		$generate_annonce .= '</center>';
		$generate_annonce .= '<br/>';

		if(annonces_email_activation == 'oui')
		{
			/**
			*	Impl�mentation du "Contacter le vendeur par email"
			**/
			$generate_annonce .= '<div class="form_contact" id="form_contact"><table>';
			$generate_annonce .= '<tr>';
			$generate_annonce .= '<td colspan="3"><label> ' . __('Laissez un message au vendeur','annonces') . '</label></td><td></td>';
			$generate_annonce .= '</tr>';

			$generate_annonce .= '<tr>';
			$generate_annonce .= '<td>';
			$generate_annonce .= '<form method="post">';
			$generate_annonce .= '<div>';
			$generate_annonce .= '<input name="id_annonce" type="hidden" value="' . $id . '" id="id_annonce"/>';
			$generate_annonce .= '<input name="titre_annonce" type="hidden" value="' . $annonce[0]->titre . '" id="titre_annonce"/>';

			$generate_annonce .= '<label>' . __('Nom','annonces') . '</label><br/><input name="txtNom" id="txtNom" type="text" value="' . stripslashes(trim($_POST['txtNom'])) . '"/>';
			$generate_annonce .= '<label>' . __('Telephone','annonces') . '</label><br/><input name="txtTel" id="txtTel" type="text" value="' . stripslashes(trim($_POST['txtTel'])) . '"/>';
			$generate_annonce .= '<label>' . __('Email','annonces') . '</label><br/><input name="txtEmail" id="txtEmail" type="text" value="' . stripslashes(trim($_POST['txtEmail'])) . '"/>';


			$generate_annonce .= '</div>';
			$generate_annonce .= '</td>';

			$generate_annonce .= '<td colspan="2">';
			$generate_annonce .= '<label>' . __('Message','annonces') . '</label><textarea id="txtMessage" name="txtMessage" cols="40" rows="6">' . stripslashes(trim($_POST['txtMessage'])) . '</textarea>';
			$generate_annonce .= '<div></div>
								<input type="submit" name="submit" value="' . __('Envoyer','annonces') . '"/>';
			$generate_annonce .= '</td>';
			$generate_annonce .= '</form>';
			$generate_annonce .= '</tr>';
			$generate_annonce .= '</table></div>';
		}
		$generate_annonce .= '</p>';
		$generate_annonce .= '<p class="retour">';
		$generate_annonce .= '<a href="' . get_option('siteurl') . '/' . $retour_link . '">';
		$generate_annonce .= '<b>&laquo;&nbsp;'.__('Retour','annonces').'</b></a></p>';

		return $generate_annonce;
	}

	function list_annonce($morequery = null)
	{
		$eav_value = new Eav();
		// $annonces = $eav_value->getAnnoncesEntete($morequery,DEFAULT_FLAG_AOS,annonce_frontend_listing_order,$this->getActualPage(),null,null);
		/*
		*	Modification Alex le 14/04/2010 pour tri par prix (mauvaise version � reprendre)
		*/
		$annonces = $eav_value->getAnnoncesEntete($morequery,DEFAULT_FLAG_AOS,annonce_frontend_listing_order,$this->getActualPage(),null,null);
		$sizei = count($annonces);

		$generate_annonce = '';

		for($i = 0; $i < $sizei; $i++)
		{
			if (preg_match_all('(\?page_id=)', $_SERVER['REQUEST_URI'], $match) == 0)
			{
				if (annonces_url_activation == 'oui')
				{
					$annonce_link = get_permalink() . '/' . Eav::get_link($annonces[$i]->idpetiteannonce);
				}
				else
				{
					$annonce_link = (strstr(get_permalink(), '?')? get_permalink().'&' : get_permalink().'?').'show_annonce='.$annonces[$i]->idpetiteannonce.'&show_mode=list';
				}
			}
			else
			{
				$annonce_link = (strstr(get_permalink(), '?')? get_permalink().'&' : get_permalink().'?').'show_annonce='.$annonces[$i]->idpetiteannonce.'&show_mode=list';
			}

			$generate_annonce .= '<tr class="annonce-ligne">';
			if(annonces_photos_activation == 'oui')
			{
				$generate_annonce .= '<td><div class="annonce-photos">';
				$generate_annonce .= '<a href="' . $annonce_link . '">';
				$photos = $eav_value->getPhotos($annonces[$i]->idpetiteannonce);
				if(!empty($photos) && !empty($photos[0]) && is_file(WP_CONTENT_DIR . WAY_TO_PICTURES_THUMBNAIL_AOS . $photos[0]->original))
				$generate_annonce .= '<img src="'.WP_CONTENT_URL . WAY_TO_PICTURES_THUMBNAIL_AOS . $photos[0]->original.'" alt="'.$annonces[$i]->titre.'" class="GAimg"/>';
				$generate_annonce .= '</a></div></td>';
			}
			$attributs = $eav_value->getAnnoncesAttributs(null,'moderated',null,$annonces[$i]->idpetiteannonce);
			$generate_annonce .= '<td class="annonce-cont">';
			$generate_annonce .= '<div class="annonce-container" >';
			$generate_annonce .= '<div class="annonce-titre">';
			//$generate_annonce .= '<a href="' . get_permalink() . '/' . $annonce_link . '">';
			$generate_annonce .= '<a href="' . $annonce_link . '">';
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
			$generate_annonce .= '</div>';
			$generate_annonce .= '</td>';

			if(annonces_date_activation == 'oui'){
				$generate_annonce .= '<td>';
				$generate_annonce .= '<div class="annonce-date">';
				$generate_annonce .= __('Publi&eacute;e le','annonces').'<br/>';
				$generate_annonce .= date("d/m/Y",strtotime($annonces[$i]->autoinsert));
				$generate_annonce .= '</div>';
				$generate_annonce .= '</div>';
				$generate_annonce .= '</td>';
			}
			else{
				$generate_annonce .= '<td>';
				$generate_annonce .= '<div class="annonce-date">';
				$reference = $annonces[$i]->referenceagencedubien;
				$generate_annonce .= '<div class="GAreference">'.(is_null($reference) ? '-' : $reference);
				$generate_annonce .= '</div>';
				$generate_annonce .= '</td>';
			}
			$generate_annonce .= '</tr>';

		}
		$list_result =
			'<h5>'.__('Consultez toutes les annonces','annonces').'</h5>
				'.$this->getPagination($morequery).'
				<div>
					<div class="resultats_annonces">
						<table>
							<thead>
								<tr>';
									if(annonces_photos_activation == 'oui'){
										$list_result .= '<th>'.__('Photos','annonces').'</th>';
									}
									$list_result .= '<th>'.__('Annonces','annonces').'</th>';
									if(annonces_date_activation == 'oui'){
										$list_result .= '<th>'.__('Date','annonces').'</th>';
									}
									else{
										$list_result .= '<th>'.__('R&eacute;f&eacute;rence','annonces').'</th>';
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