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

$morequery = $on_edit_load_map = '';
$geoloc['adresse'] = $geoloc['ville'] = $geoloc['cp'] = $geoloc['region'] = $geoloc['departement'] = $geoloc['pays'] = $geoloc['latitude'] = $geoloc['longitude'] = '';
$flag = DEFAULT_FLAG_ADMIN_AOS;
$act = isset($_REQUEST['act']) ? $tools->IsValid_Variable($_REQUEST['act']) : '' ;
$id_to_treat = isset($_REQUEST['id_to_treat']) ? $tools->IsValid_Variable($_REQUEST['id_to_treat']) : (isset($_REQUEST['idpetiteannonce']) ? $tools->IsValid_Variable($_REQUEST['idpetiteannonce']) : '') ;
$actual_page = isset($_REQUEST['actual_page']) ? $tools->IsValid_Variable($_REQUEST['actual_page']) : '' ;
$actual_number_of_picture = isset($_REQUEST['actual_number_of_picture']) ? $tools->IsValid_Variable($_REQUEST['actual_number_of_picture']) : NB_PICTURES_ALLOWED_AOS ;
$token = isset($_REQUEST['annonce_form']['unique_token']) ? $tools->IsValid_Variable($_REQUEST['annonce_form']['unique_token']) : date('dHis').rand(0,5) ;
global $current_user;get_currentuserinfo();


//	UPDATE SEARCH ENGINE
if(!empty($_POST['update_lucene']) && $_POST['update_lucene'])
{
	$annonce->rewriteSeachEngine();
}

//	IF ACTION IS ADD (ADD OR UPDATE) ANNONCE
if(!empty($_POST['annonce_form']) && is_array($_POST['annonce_form']) && ($act != ''))
{
	$annonce_form->bind($_POST['annonce_form']);
	if ($annonce_form->isValid())
	{
		$values = $annonce_form->getValues();

		if($values['idpetiteannonce'] == '')$annonce->create_annonce($values);
		elseif($values['idpetiteannonce'] != '')$annonce->update_annonce($values);
		$act = '';
	}
	else
	{
		$act = 'add';
	}
}

//	IF WE ASK TO EDIT A SMALL AD
elseif($act == 'edit')
{
	$annonce_to_treat = $annonce->admin_get_annonce(" AND ANN.idpetiteannonce = '".$id_to_treat."'",DEFAULT_FLAG_ADMIN_AOS,0,'nolimit');

	$annonce_form->setDefault('idpetiteannonce', stripslashes($annonce_to_treat[0]->idpetiteannonce));
	$annonce_form->setDefault('flagvalidpetiteannonce', stripslashes($annonce_to_treat[0]->flagvalidpetiteannonce));
	$annonce_form->setDefault('idgroupeattribut', stripslashes($annonce_to_treat[0]->idgroupeattribut));
	$annonce_form->setDefault('aexporter', stripslashes($annonce_to_treat[0]->aexporter));
	$annonce_form->setDefault('titre', stripslashes($annonce_to_treat[0]->titre));
	$annonce_form->setDefault('referenceagencedubien', stripslashes($annonce_to_treat[0]->referenceagencedubien));

	////	ADD DYNIMICALLY ATTRIBUTE
	foreach($geolocalisation_field as $geoloc_attribute_key => $geoloc_attribute_name )
	{
		$geoloc[$geoloc_attribute_name] = stripslashes($annonce_to_treat[0]->$geoloc_attribute_name);
	}
	$on_edit_load_map = 'the_new_coord = new GLatLng('.$geoloc['latitude'].','.$geoloc['longitude'].');generateMarker(the_new_coord,map,geocoder);';

	unset($annonce_to_treat[0]);

	////	ADD DYNIMICALLY ATTRIBUTE
	foreach($annonce_to_treat as $annonce_key => $annonce_definition )
	{
		$annonce_form->setDefault($annonce_definition->labelattribut, stripslashes($annonce_definition->ATTRIBUT_VALUE));
	}
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

?>

<div class="sub_admin_menu" >
	<button name="submit_home" onclick="javascript:document.getElementById('act').value='';document.getElementById('actual_page').value='';document.forms.treat_annonce.submit();"><?php _e('Listing des annonces','annonces') ?></button>
	<button name="submit_add" onclick="javascript:document.getElementById('act').value='add';document.getElementById('actual_page').value='';document.forms.treat_annonce.submit();"><?php _e('Ajouter une annonce','annonces') ?></button>
<?php
if($current_user->user_level == 10)
{
?>
	<form action="" method="POST" name="form_update_lucene" ><input name="update_lucene" type="submit" value="<?php _e('Actualiser moteur de recherche','annonces') ?>" /></form>
<?php 
}
?>
</div>
<hr style="clear:both;" /><br/>
<div style="clear:both;" class="<?php echo $annonce->class_admin_notice; ?>" ><?php echo $annonce->error_message; ?></div>


<form action="" method="POST" name="treat_annonce" >
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
    <?php echo $annonce_form ?>
				</table>
			</td>
			<td style="width:18px;" >&nbsp;</td>
      <td rowspan="3" >
				<div id="annonceGmap" style="width: 512px; height: 400px">
					<script type="text/javascript">
						var image_icon = '<?php echo WP_PLUGIN_URL.'/'.Basename_Dirname_AOS; ?>/medias/images/<?php echo get_option('url_marqueur_courant') ?>';
						var input_country = 'annonce_form[pays]';
						var input_dept = 'annonce_form[departement]';
						var input_region = 'annonce_form[region]';
						var adress = 'annonce_form[adresse]';
						var town = 'annonce_form[ville]';
						var postal_code = 'annonce_form[cp]';
						var latitude_input = 'annonce_form[latitude]';
						var longitude_input = 'annonce_form[longitude]';
						show_map();
						<?php
						echo $on_edit_load_map;
						?>
					</script>
				</div>
      </td>
		</tr>
    <tr><td><hr/></td></tr>
    <tr>
      <td>
				<table class="annonce_form" style="width:100%;" >
					<tr>
						<th>
							<?php _e('Adresse','annonces') ?>
						</th>
						<td>
							<input type="text" name="annonce_form[adresse]" id="annonce_form[adresse]" value="<?php echo $geoloc['adresse']; ?>" /> 
						</td>
					</tr>
					<tr>
						<th>
							<?php _e('Ville','annonces') ?>
						</th>
						<td>
							<input type="text" name="annonce_form[ville]" id="annonce_form[ville]" value="<?php echo $geoloc['ville']; ?>" onblur="javascript:getCoordonnees();" /> 
						</td>
					</tr>
					<tr>
						<th>
							<?php _e('Code Postal','annonces') ?>
						</th>
						<td>
							<input type="text" name="annonce_form[cp]" id="annonce_form[cp]" value="<?php echo $geoloc['cp']; ?>" onkeyup="javascript:getCoordonnees() ;" /> 
						</td>
					</tr>
				</table>
				<input type="hidden" name="annonce_form[region]" id="annonce_form[region]" value="<?php echo $geoloc['region']; ?>" />
				<input type="hidden" name="annonce_form[departement]" id="annonce_form[departement]" value="<?php echo $geoloc['departement']; ?>" />
				<input type="hidden" name="annonce_form[pays]" id="annonce_form[pays]" value="<?php echo $geoloc['pays']; ?>" />
				<input type="hidden" name="annonce_form[latitude]" id="annonce_form[latitude]" value="<?php echo $geoloc['latitude']; ?>" /> 
				<input type="hidden" name="annonce_form[longitude]" id="annonce_form[longitude]" value="<?php echo $geoloc['longitude']; ?>" /> 
      </td>
    </tr>
		<tr><td><hr/></td></tr>
    <tr>
      <td colspan="5" >
				<div id="galerie" style="width:100%;">
				<?php
					if(($id_to_treat != '') || ($token != ''))
					{
						echo '<iframe src ="'.WP_PLUGIN_URL.'/'.Basename_Dirname_AOS.'/includes/lib/image_galery.php?idgallery='.$id_to_treat.'&token='.$token.'" height="21" style="border:0px solid red;margin:0;padding:0;height:300px;width:100%;overflow-y:no-scroll;" ><p>Votre navigateur ne supporter pas les frame</p></iframe>';
					}
				?>
				</div>
      </td>
    </tr>
		<tr>
      <td colspan="5" style="text-align:center;"  >
        <input type="button" value="<?php ( $act == 'add' ?_e('Cr&eacute;er','annonces') : _e('Modifier','annonces')) ?>" id="submit_annonce" onclick="javascript:document.getElementById('act').value='add';document.forms.treat_annonce.submit();"/>
      </td>
    </tr>
  </table>

<?php
}
else
{

$nb_total_items = 0;$nb_total_items = $eav_annonce->getAnnoncesEntete($morequery,$flag,'autolastmodif',$actual_page,'nolimit','count');
$Pagination = '';
if(ceil($nb_total_items/NUMBER_OF_ITEM_PAR_PAGE_ADMIN_AOS) > 1)$Pagination = $tools->DoPagination(' onclick="javascript:document.getElementById(\'actual_page\').value=\'#PAGE#\';document.forms.treat_annonce.submit()" ',$nb_total_items,$actual_page,NUMBER_OF_ITEM_PAR_PAGE_ADMIN_AOS,PAGINATION_OFFSET_ADMIN_AOS,'','','#CCCCCC','#FFFFFF');

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
					echo $Pagination 
				?>
				</div>
				<div class="floatright margin18px" style="width:40%;" >
					<input type="button" name="general_submit" value="<?php _e('Effectuer','annonces') ?>" id="general_submit" 
						onclick="javacsript:document.getElementById('act').value = document.getElementById('general_action').options[document.getElementById('general_action').selectedIndex].value;document.forms.treat_annonce.submit();" class="floatright" />
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
				<?php echo $annonce->show_annonce($eav_annonce->getAnnoncesEntete($morequery,$flag,'autolastmodif DESC',$actual_page,'','',NUMBER_OF_ITEM_PAR_PAGE_ADMIN_AOS)) ?>
			</div>
			<?php echo $Pagination ?>
		</div>

<?php
}
?>
</form>
<div style="float:right;">
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="10265740">
		<input type="image" src="https://www.paypal.com/fr_FR/FR/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !">
		<img alt="" border="0" src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
	</form>
</div>