<?php
/***************************************************
*Date: 01/10/2009      File:annonce_listing.php    *
*Author: Eoxia							           *
*Comment:                                          *
***************************************************/
$eav_annonce = new Eav();
$attribut_annonce = new attribut_annonce();
$annonce_form = new annonce_form();
$annonce = new annonce();
$annonce_filters_form = new annonce_filters_form();

$morequery = $on_edit_load_map = $error = '';
$geoloc['adresse'] = $geoloc['ville'] = $geoloc['cp'] = $geoloc['region'] = $geoloc['departement'] = $geoloc['pays'] = $geoloc['latitude'] = $geoloc['longitude'] = '';
$flag = DEFAULT_FLAG_ADMIN_AOS;
$act = isset($_REQUEST['act']) ? $tools->IsValid_Variable($_REQUEST['act']) : '' ;
$id_to_treat = isset($_REQUEST['id_to_treat']) ? $tools->IsValid_Variable($_REQUEST['id_to_treat']) : (isset($_REQUEST['idpetiteannonce']) ? $tools->IsValid_Variable($_REQUEST['idpetiteannonce']) : '') ;
$actual_page = isset($_REQUEST['actual_page']) ? $tools->IsValid_Variable($_REQUEST['actual_page']) : '' ;
$actual_number_of_picture = isset($_REQUEST['actual_number_of_picture']) ? $tools->IsValid_Variable($_REQUEST['actual_number_of_picture']) : NB_PICTURES_ALLOWED_AOS ;
$token = isset($_REQUEST['annonce_form']['unique_token']) ? $tools->IsValid_Variable($_REQUEST['annonce_form']['unique_token']) : date('dHis').rand(0,5) ;
global $current_user;get_currentuserinfo();

//	IF ACTION IS ADD (ADD OR UPDATE) ANNONCE
if(!empty($_POST['annonce_form']) && is_array($_POST['annonce_form']) && ($act != ''))
{
	$annonce_form->bind($_POST['annonce_form']);
	if ($annonce_form->isValid())
	{
		$values = $annonce_form->getValues();
		if ($values['urlannonce'] == Eav::get_link($values['idpetiteannonce']))
		{
			if($values['idpetiteannonce'] == '')$annonce->create_annonce($values);
			elseif($values['idpetiteannonce'] != '')$annonce->update_annonce($values);
			$act = '';
		}
		else
		{
			if (Eav::url_exist($values[urlannonce]) == '')
			{
				if($values['idpetiteannonce'] == '')$annonce->create_annonce($values);
				elseif($values['idpetiteannonce'] != '')$annonce->update_annonce($values);
				$act = '';
			}
			else
			{
				$geo_loc->adresse =$values[adresse];
				$geo_loc->ville =$values[ville];
				$geo_loc->cp =$values[cp];
				$geo_loc->region =$values[region];
				$geo_loc->departement =$values[departement];
				$geo_loc->pays =$values[pays];
				$geo_loc->longitude =$values[longitude];
				$geo_loc->latitude =$values[latitude];

				$id_to_treat = $values['idpetiteannonce'];

				$error = __('L\'Url personnalis&eacute;e choisie : ', 'annonces');
				$error .= $values[urlannonce];
				$error .= __(' est d&eacute;j&agrave; utilis&eacute;e', 'annonces');
			}
		}
	}
	else
	{
		$act = 'add';
	}
}

//	IF WE ASK TO EDIT A SMALL AD
elseif($act == 'edit')
{
	$geo_loc = Eav::get_geoloc($_POST['id_to_treat']);
	$annonce_to_treat = $annonce->admin_get_annonce(" AND ANN.idpetiteannonce = '".$id_to_treat."'",DEFAULT_FLAG_ADMIN_AOS,0,'nolimit');

	$annonce_form->setDefault('idpetiteannonce', stripslashes($annonce_to_treat[0]->idpetiteannonce));
	$annonce_form->setDefault('flagvalidpetiteannonce', stripslashes($annonce_to_treat[0]->flagvalidpetiteannonce));
	$annonce_form->setDefault('idgroupeattribut', stripslashes($annonce_to_treat[0]->idgroupeattribut));
	$annonce_form->setDefault('aexporter', stripslashes($annonce_to_treat[0]->aexporter));
	$annonce_form->setDefault('titre', stripslashes($annonce_to_treat[0]->titre));
	$annonce_form->setDefault('urlannonce', stripslashes($annonce_to_treat[0]->urlannonce));
	$annonce_form->setDefault('referenceagencedubien', stripslashes($annonce_to_treat[0]->referenceagencedubien));

	////	ADD DYNIMICALLY ATTRIBUTE
	foreach($geolocalisation_field as $geoloc_attribute_key => $geoloc_attribute_name )
	{
		$geoloc[$geoloc_attribute_name] = stripslashes($annonce_to_treat[0]->$geoloc_attribute_name);
	}
	$on_edit_load_map = 'the_new_coord = new google.maps.LatLng('.$geoloc['latitude'].','.$geoloc['longitude'].'); generateMarker(the_new_coord,map);';

	unset($annonce_to_treat[0]);

	////	ADD DYNIMICALLY ATTRIBUTE
	foreach($annonce_to_treat as $annonce_key => $annonce_definition )
	{
		$annonce_form->setDefault($annonce_definition->labelattribut, stripslashes($annonce_definition->ATTRIBUT_VALUE));
	}

	$idAnnonce = $id_to_treat;
}

//	IF WE WANT TO FILTER RESULT
elseif(!empty($_POST['annonce_filters']) && is_array($_POST['annonce_filters']) && ($act == 'filter'))
{
	$annonce_filters_form->bind($_POST['annonce_filters']);
	if ($annonce_filters_form->isValid())
	{
		$filter_values = $annonce_filters_form->getValues();

		$morequery = $annonce_filters_form->setMorequery($filter_values);
	}
}

//	DELETE A SMALL AD
if($act == 'delete')
{
	$annonce->delete_annonce("'".mysql_real_escape_string($id_to_treat)."'");
	$act = '';
}

// EXPORT SOME AD TO WPSHOP
if($act == 'export_annonce')
{
	$annonce->export_annonce($_REQUEST['annonce']);
	$act = '';
}

//	MASS ACTION
if(isset($_POST['annonce']) && is_array($_POST['annonce']))
{
	$id_list = "  ";
	foreach($_POST['annonce'] as $key => $value)
	{
		$id_list .= " '" . mysql_real_escape_string($value) . "', ";
	}

	$id_list = substr($id_list,0,-2);
	if($id_list != "")
	{
		if($act == 'selection_delete')
		{
			$annonce->delete_annonce($id_list);
			$act = '';
		}
		elseif($act == 'selection_moderated')
		{
			$annonce->update_annonce_status($id_list,'flagvalidpetiteannonce','moderated');
			$act = '';
		}
		elseif($act == 'selection_valid')
		{
			$annonce->update_annonce_status($id_list,'flagvalidpetiteannonce','valid');
			$act = '';
		}
		elseif($act == 'selection_exportable')
		{
			$annonce->update_annonce_status($id_list,'aexporter','oui');
			$act = '';
		}
		elseif($act == 'selection_not_exportable')
		{
			$annonce->update_annonce_status($id_list,'aexporter','non');
			$act = '';
		}
	}
}


if($current_user->user_level >= 5)
{
	require_once dirname(__FILE__).'./../includes/lib/options.class.php';
	if (isset($_POST["razLesUrl"]))
	{
		annonces_options::majUrlAnnonces();
	}
?>
	<form method="post" name="raz_url" action="" >
		<input type="hidden" name="razLesUrl" id="razLesUrl" value="raz" />
		<input  name="razurl" type="button" value="<?php echo __('R&eacute;initaliser les URLs', 'annonces') ?>" onclick="var check = confirm('&Ecirc;tes vous s&ucirc;r de vouloir remettre par d&eacute;faut selon l\'url type toutes les URLs y compris celles personnalis&eacute;es ?'); if (check ==true) document.forms.raz_url.submit();" />
	</form>
<?php
}
if ( !empty($_POST['aj']) ) {
?>
	<div class="ajout_effectue" id="ajout_ok"><?php echo __('Votre annonce a &eacute;t&eacute; ajout&eacute;.','annonces') ?></div><br/>
<?php
}
if ($error != '')
{?>
	<div class="echec_annonce" id="erreur_ok"><?php echo $error ?></div><br/>
<?php
}
?>
<div class="wrap">
	<h2>
		<?php echo __('Annonces','annonces') ?>
		<a class="button add-new-h2" href="<?php echo 'admin.php?page=' . ANNONCES_PLUGIN_DIR . '/admin/add_annonce.php' ?>"><?php echo __('Ajouter','annonces') ?></a>
	</h2>
</div>
<br/>
<div style="clear:both;" id="error_message" class="<?php echo $annonce->class_admin_notice; ?>" ><?php echo $annonce->error_message; ?></div>
<form action="" method="post" name="treat_annonce" >
	<input type="hidden" name="id_to_treat" id="id_to_treat" value="" />
	<input type="hidden" name="act" id="act" value="<?php echo $act; ?>" />
	<input type="hidden" name="actual_page" id="actual_page" value="<?php echo $actual_page; ?>" />
<?php
if(($act == 'add') || ($act == 'edit'))
{
?>

  <table class="annonce_form" >
		<tr>
      <td >
				<table class="annonce_form" style="width:100%;" >
					<?php echo $annonce_form; ?>
				</table>
			</td>
			<td style="width:18px;" >&nbsp;</td>
      <td rowspan="3" >
      					<script type="text/javascript">
						var image_icon = '<?php echo WP_CONTENT_URL . WAY_TO_PICTURES_AOS . url_marqueur_courant; ?>';

						function initialize(){
					        var mapOptions = {
					          center: new google.maps.LatLng(43.61,3.88),
					          zoom: 12
					        };
				        	map = new google.maps.Map(document.getElementById("annonceGmap"), mapOptions);

							<?php echo $on_edit_load_map; ?>
				      	}
				      	google.maps.event.addDomListener(window, "load", initialize);
					</script>
				<div id="annonceGmap" style="width: 512px; height: 400px"></div>
      </td>
		</tr>
    <tr><td><hr/></td></tr>
    <tr>
      <td>
				<table class="annonce_form" style="width:100%;" >
					<tr>
						<th>
							<label for="annonce_form_adresse" ><?php _e('Adresse','annonces') ?></label>
						</th>
						<td>
							<input type="text" name="annonce_form[adresse]" id="annonce_form_adresse" value="<?php echo stripslashes($geo_loc->adresse) ?>" />
						</td>
					</tr>
					<tr>
						<th>
							<label for="annonce_form_ville" ><?php _e('Ville','annonces') ?></label>
						</th>
						<td>
							<input type="text" name="annonce_form[ville]" id="annonce_form_ville" value="<?php echo stripslashes($geo_loc->ville) ?>" onblur="javascript:getCoordonnees();" />
						</td>
					</tr>
					<tr>
						<th>
							<label for="annonce_form_cp" ><?php _e('Code Postal','annonces') ?></label>
						</th>
						<td>
							<input type="text" name="annonce_form[cp]" id="annonce_form_cp" value="<?php echo stripslashes($geo_loc->cp) ?>" onkeyup="javascript:getCoordonnees() ;" />
						</td>
					</tr>
				</table>
				<input type="hidden" name="annonce_form[region]" id="annonce_form_region" value="<?php echo stripslashes($geo_loc->region) ?>" />
				<input type="hidden" name="annonce_form[departement]" id="annonce_form_departement" value="<?php echo stripslashes($geo_loc->departement) ?>" />
				<input type="hidden" name="annonce_form[pays]" id="annonce_form_pays" value="<?php echo stripslashes($geo_loc->pays) ?>" />
				<input type="hidden" name="annonce_form[latitude]" id="annonce_form_latitude" value="<?php echo stripslashes($geo_loc->latitude) ?>" />
				<input type="hidden" name="annonce_form[longitude]" id="annonce_form_longitude" value="<?php echo stripslashes($geo_loc->longitude) ?>" />
      </td>
    </tr>
		<tr><td><hr/></td></tr>
    <tr>
      <td colspan="5" >
				<div id="galerie" style="width:100%;">
				<?php
					if(($id_to_treat != '') || ($token != ''))
					{
						echo '<iframe src ="'.WP_PLUGIN_URL.'/'.ANNONCES_PLUGIN_DIR.'/includes/lib/image_galery.php?idgallery='.$id_to_treat.'&amp;token='.$token.'" height="21" style="border:0px solid red;margin:0;padding:0;height:300px;width:100%;overflow-y:no-scroll;" ><p>Votre navigateur ne supporter pas les frame</p></iframe>';
					}
				?>
				</div>
      </td>
    </tr>
		<tr>
      <td colspan="5" style="text-align:center;"  >
        <input type="button" value="<?php echo __('Enregistrer les modifications','annonces') ?>" id="submit_annonce" onclick="javascript:document.getElementById('act').value='add';document.forms.treat_annonce.submit();"/>
      </td>
    </tr>
  </table>

<?php
}

elseif($act == 'export_to_wpshop')
{
	$eav_value = new Eav();

	echo '<h2>' . __('Exporter vos annonces vers WP-Shop','annonces') . '</h2>';

	// echo '<pre>'; print_r($_REQUEST); echo '</pre>';

	if (count($_REQUEST['annonce']) > 0) {
		echo "<input type='hidden' name='act' value='export_annonce'/>";
		foreach ($_REQUEST['annonce'] as $id_annonce) {
			echo "<input type='hidden' name='annonce[]' value='$id_annonce'/>";
			$titre_annonce = $eav_value->get_titre($id_annonce);
			echo '<fieldset><legend>Titre : ' . $titre_annonce . '</legend>';
			$attributs = $eav_value->getAnnoncesAttributs(null,'valid',null,$id_annonce,'oui');
			// echo '<pre>'; print_r($attributs); echo '</pre>';
			foreach ($attributs as $attribut) {
			$attribut = (array)$attribut;
				echo $attribut['nomattribut'] . " : ";
				switch ($attribut['typeattribut']) {
					case 'CHAR' :
						echo $attribut['valueattributchar'];
					break;

					case 'DEC' :
						echo $attribut['valueattributdec'];
					break;

					case 'INT' :
						echo $attribut['valueattributint'];
					break;

					case 'TEXT' :
						if ($attribut['valueattributtextlong'] == 0) {
							echo $attribut['valueattributtextcourt'];
						}
						else {
							echo $attribut['valueattributtextlong'];
						}
					break;

					case 'DATE' :
						echo $attribut['valueattributdate'];
					break;
				}
				echo '<br>';
			}
			echo '</fieldset>';
		}
		echo '<br><h3>' . __('Souhaitez-vous transf&eacute;rer ces annonces vers WP-Shop, avec les valeurs suivantes ?','annonces') . '</h3>';
		echo '	<input type="button" value=" ' . __('Annuler','annonces') . '" onclick="javascript:history.go(-1);"/>
				<input type="submit" value=" ' . __('Transf&eacute;rer','annonces') . '"/>';
	}
}
else
{

$nb_total_items = 0;$nb_total_items = $eav_annonce->getAnnoncesEntete($morequery,$flag,'autolastmodif',$actual_page,'nolimit','count');
$Pagination = '';
if(ceil($nb_total_items/NUMBER_OF_ITEM_PAR_PAGE_ADMIN_AOS_LISTING) > 1)$Pagination = $tools->DoPagination(' onclick="javascript:document.getElementById(\'actual_page\').value=\'#PAGE#\';document.forms.treat_annonce.submit()" ',$nb_total_items,$actual_page,NUMBER_OF_ITEM_PAR_PAGE_ADMIN_AOS_LISTING,PAGINATION_OFFSET_ADMIN_AOS,'','','#CCCCCC','#FFFFFF');
?>
		<div id="annonce_filter" class="margin18px" >
			<table summary="annonce filters" cellpadding="0" cellspacing="0" class="floatright margin18px" style="border:1px solid #333333;" >
				<tr><td colspan="2" style="text-align:center;background-color:#333333;color:#FFFFFF;font-weight:bold;font-size:14px;" ><?php _e('Rechercher des annonces','annonces') ?></td></tr>
				<?php echo $annonce_filters_form ;?>
				<tr>
					<td colspan="2" >
						<input type="button" value="<?php _e('Filtrer les r&eacute;sultats','annonces') ?>" class="floatright"
							onclick="javascript:document.getElementById('act').value='filter';document.getElementById('actual_page').value='';document.forms.treat_annonce.submit();" />
						<input type="button" value="<?php _e('Tout afficher','annonces') ?>" class="floatright"
							onclick="javascript:document.getElementById('act').value='';document.getElementById('actual_page').value='';document.forms.treat_annonce.submit();" />
					</td>
				</tr>
			</table>
		</div>
		<div id="annonce_listing" style="clear:both;" >
			<div >
				<div class="floatleft" >
				<?php
					echo $Pagination;
				?>
				</div>
				<div class="floatright margin18px" style="width:40%;" >
					<input type="button" name="general_submit" value="<?php _e('Effectuer','annonces') ?>" id="general_submit"
						onclick="javascript:document.getElementById('act').value = document.getElementById('general_action').options[document.getElementById('general_action').selectedIndex].value;document.forms.treat_annonce.submit();" class="floatright" />
					<select name="general_action" id="general_action" class="floatright" >
						<option value="" ><?php _e('Pour la s&eacute;lection','annonces') ?>&nbsp;</option>
					<?php
						foreach($annonce_general_action as $action_name => $action)
						{
							echo '<option value="'.$action.'" >'.$action_name.'</option>';
						}
					?>
					</select>
					<div class="floatright" style="clear:both;margin-right:64px;">
						<div class="floatright" onclick="javascript:check_selection(document.forms.treat_annonce,'uncheck_all');" style="cursor:pointer;" ><?php _e('Aucun','annonces') ?></div>
						<div class="floatright" onclick="javascript:check_selection(document.forms.treat_annonce,'check_all');" >&nbsp;/&nbsp;</div>
						<div class="floatright" onclick="javascript:check_selection(document.forms.treat_annonce,'check_all');" style="cursor:pointer;" ><?php _e('Tout','annonces') ?></div>
						<div class="floatright" onclick="javascript:check_selection(document.forms.treat_annonce,'check_all');" ><?php _e('S&eacute;lectionner','annonces') ?>&nbsp;</div>
					</div>
				</div>
			</div>
			<div class="margin18px" style="clear:both;" >
				<?php echo $annonce->show_annonce($eav_annonce->getAnnoncesEntete($morequery,$flag,'autolastmodif',$actual_page,'','',NUMBER_OF_ITEM_PAR_PAGE_ADMIN_AOS_LISTING)) ?>
			</div>
			<?php echo $Pagination; ?>
		</div>

<?php
}
?>
</form>
<div style="float:right;">
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_s-xclick" />
		<input type="hidden" name="hosted_button_id" value="10265740" />
		<input type="image" src="https://www.paypal.com/fr_FR/FR/i/btn/btn_donateCC_LG.gif" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus s&eacute;curis&eacute;e !" />
		<img alt="" border="0" src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" width="1" height="1" />
	</form>
</div>