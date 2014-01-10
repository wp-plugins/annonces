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
$encours = isset($_REQUEST['encours']) ? $tools->IsValid_Variable($_REQUEST['encours']) : '' ;
$id_to_treat = isset($_REQUEST['id_to_treat']) ? $tools->IsValid_Variable($_REQUEST['id_to_treat']) : (isset($_REQUEST['idpetiteannonce']) ? $tools->IsValid_Variable($_REQUEST['idpetiteannonce']) : '') ;
$actual_page = isset($_REQUEST['actual_page']) ? $tools->IsValid_Variable($_REQUEST['actual_page']) : '' ;
$actual_number_of_picture = isset($_REQUEST['actual_number_of_picture']) ? $tools->IsValid_Variable($_REQUEST['actual_number_of_picture']) : NB_PICTURES_ALLOWED_AOS ;
$token = isset($_REQUEST['annonce_form']['unique_token']) ? $tools->IsValid_Variable($_REQUEST['annonce_form']['unique_token']) : date('dHis').rand(0,5) ;
global $current_user;get_currentuserinfo();

if ($act == '')
{
	$act = 'add';
}
if ($encours == 'ajout' || $encours == '')
{
	$act = 'add';
}
else
{
	$act = 'edit';
}

//	IF ACTION IS ADD (ADD OR UPDATE) ANNONCE
if(!empty($_POST['annonce_form']) && is_array($_POST['annonce_form']) && ($act != ''))
{
	$annonce_form->bind($_POST['annonce_form']);

	$values = $annonce_form->getValues();

		$idAnnonce = Eav::getLatestIDAnnonce()+1;

		$values[urlannonce] = stripslashes($values[urlannonce]);

		if (empty($values[urlannonce]))
		{
			$values[urlannonce] = Eav::set_type_url($values[urlannonce], $idAnnonce, $values);
		}
		else
		{
			$values[urlannonce] = str_replace(' ', '-', trim(annonces_options::slugify_noaccent($values[urlannonce])));
			$values[urlannonce] = str_replace('\'', '-', $values[urlannonce]);
			$values[urlannonce] = str_replace('"', '-', $values[urlannonce]);
			$values[urlannonce] = str_replace('\\', '-', $values[urlannonce]);
			$values[urlannonce] = str_replace('?', '', $values[urlannonce]);
			$values[urlannonce] = str_replace('!', '', $values[urlannonce]);
			$values[urlannonce] = str_replace('@', '', $values[urlannonce]);
		}

		$is_Url = $values[urlannonce];


		$isId = Eav::url_exist($is_Url);

	if ($annonce_form->isValid() && $isId == '')
	{
		$values = $annonce_form->getValues();
		$values[urlannonce] = $is_Url;

		if ($act == 'add') {
			$annonce->create_annonce($values);

			echo '<form name="form_ajout" action="admin.php?page=' . ANNONCES_PLUGIN_DIR . '/admin/annonce_listing.php" method="post">';
			echo '<input type="hidden" name="id_to_treat" id="id_to_treat" value="' . Eav::getLatestIDAnnonce() . '"/>';
			echo '<input type="hidden" name="act" id="act" value="edit"/>';
			echo '<input type="hidden" name="aj" id="aj" value="ok"/>';
			echo '</form>';
			echo'<script type="text/javascript">
						document.forms.form_ajout.submit();
					</script>';
		}
	}
// 	else
// 	{
// 		$error = __('L\'Url personnalis&eacute;e choisie : ', 'annonces');
// 		$error .= $is_Url;
// 		$error .= __(' est d&eacute;j&agrave; utilis&eacute;e', 'annonces');
// 	}
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
	$annonce_form->setDefault('urlannonce', stripslashes($annonce_to_treat[0]->urlannonce));
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
?>
<script type="text/javascript">
	annoncejquery(document).ready(function() {
	annoncejquery('#annonce_form_titre').bind('keyup', function() {

			annoncejquery('#annonce_form_urlannonce').val(annoncejquery('#annonce_form_titre').val());

			var txt = annoncejquery('#annonce_form_urlannonce').val();

				txt = txt.replace(new RegExp("[ ]", "g"),"-");
				txt = txt.replace(new RegExp("[<?php echo utf8_encode('����'); ?>]", "g"),"e");
				txt = txt.replace(new RegExp("[<?php echo utf8_encode('������'); ?>]", "g"),"a");
				txt = txt.replace(new RegExp("[<?php echo utf8_encode('�'); ?>]", "g"),"ae");
				txt = txt.replace(new RegExp("[<?php echo utf8_encode('�'); ?>]", "g"),"c");
				txt = txt.replace(new RegExp("[<?php echo utf8_encode('����'); ?>]", "g"),"i");
				txt = txt.replace(new RegExp("[<?php echo utf8_encode('�'); ?>]", "g"),"n");
				txt = txt.replace(new RegExp("[<?php echo utf8_encode('�����'); ?>]", "g"),"o");
				txt = txt.replace(new RegExp("[<?php echo utf8_encode('�'); ?>]", "g"),"oe");
				txt = txt.replace(new RegExp("[<?php echo utf8_encode('����'); ?>]", "g"),"u");
				txt = txt.replace(new RegExp("[<?php echo utf8_encode('��'); ?>]", "g"),"y");

			var reg = new RegExp("[^0-9a-zA-Z-_]", "g");
			txt = txt.replace(reg,"");

			annoncejquery('#annonce_form_urlannonce').val(txt.toLowerCase());
		});
	});
</script>
<div class="wrap">
	<h2>
		<?php echo __('Annonces','annonces') ?>
		<a class="button add-new-h2" onclick="javascript:document.getElementById('act').value='add';document.getElementById('actual_page').value='';document.forms.treat_annonce.submit();"><?php echo __('Ajouter','annonces') ?></a>
	</h2>
</div>
<br/><br/>

<form action="" method="post" name="treat_annonce" >
	<input type="hidden" name="id_to_treat" id="id_to_treat" value="" />
	<input type="hidden" name="act" id="act" value="<?php echo $act; ?>" />
	<input type="hidden" name="actual_page" id="actual_page" value="<?php echo $actual_page; ?>" />
	<input type="hidden" name="encours" id="encours" value="<?php echo $encours; ?>" />

<?php
if(($act == 'add') || ($act == 'edit'))
{
	if ($error != '')
	{?>
		<div class="echec_annonce" id="erreur_ok"><?php echo $error ?></div><br/>
	<?php
	}
	?>
  <table class="annonce_form" >
		<tr>
      <td >
				<table class="annonce_form" style="width:100%;" >
					<tr>
						<th colspan="2" >
							<div class="ajout_effectue" id="ajout_effectue" >

<?php
	if($encours != '' &&  $act != 'edit')
	{
		_e('Cr&eacute;ation effectu&eacute; avec succ&eacute;s','annonces');
	}
	elseif ($act != 'add')
	{
		_e('Modification effectu&eacute; avec succ&eacute;s','annonces');
	}
?>
							</div>
						</th>
					</tr>
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
							<input type="text" name="annonce_form[adresse]" id="annonce_form_adresse" value="<?php echo ($act != 'add' ? Eav::get_geoloc(Eav::getLatestIDAnnonce())->adresse : $_REQUEST[ 'annonce_form' ][ 'adresse' ]); ?>" />
						</td>
					</tr>
					<tr>
						<th>
							<label for="annonce_form_ville" ><?php _e('Ville','annonces') ?></label>
						</th>
						<td>
							<input type="text" name="annonce_form[ville]" id="annonce_form_ville" value="<?php echo ($act != 'add' ? Eav::get_geoloc(Eav::getLatestIDAnnonce())->ville : $_REQUEST[ 'annonce_form' ][ 'ville' ]); ?>" onblur="javascript:getCoordonnees();" />
						</td>
					</tr>
					<tr>
						<th>
							<label for="annonce_form_cp" ><?php _e('Code Postal','annonces') ?></label>
						</th>
						<td>
							<input type="text" name="annonce_form[cp]" id="annonce_form_cp" value="<?php echo ($act != 'add' ? Eav::get_geoloc(Eav::getLatestIDAnnonce())->cp : $_REQUEST[ 'annonce_form' ][ 'cp' ]); ?>" onkeyup="javascript:getCoordonnees() ;" />
						</td>
					</tr>
				</table>
				<input type="hidden" name="annonce_form[region]" id="annonce_form_region" value="<?php echo ($act != 'add' ? Eav::get_geoloc(Eav::getLatestIDAnnonce())->region : $_REQUEST[ 'annonce_form' ][ 'region' ]); ?>" />
				<input type="hidden" name="annonce_form[departement]" id="annonce_form_departement" value="<?php echo ($act != 'add' ? Eav::get_geoloc(Eav::getLatestIDAnnonce())->departement : $_REQUEST[ 'annonce_form' ][ 'departement' ]); ?>" />
				<input type="hidden" name="annonce_form[pays]" id="annonce_form_pays" value="<?php echo ($act != 'add' ? Eav::get_geoloc(Eav::getLatestIDAnnonce())->pays : $_REQUEST[ 'annonce_form' ][ 'pays' ]); ?>" />
				<input type="hidden" name="annonce_form[latitude]" id="annonce_form_latitude" value="<?php echo ($act != 'add' ? Eav::get_geoloc(Eav::getLatestIDAnnonce())->latitude : $_REQUEST[ 'annonce_form' ][ 'latitude' ]); ?>" />
				<input type="hidden" name="annonce_form[longitude]" id="annonce_form_longitude" value="<?php echo ($act != 'add' ? Eav::get_geoloc(Eav::getLatestIDAnnonce())->longitude : $_REQUEST[ 'annonce_form' ][ 'longitude' ]); ?>" />
      </td>
    </tr>
		<tr><td><hr/></td></tr>
    <tr>
      <td colspan="5" >
				<div id="galerie" style="width:100%;">
				<?php
					if ($act == 'edit')
					{
						$id_to_treat = Eav::getLatestIDAnnonce();

						echo '<iframe src ="'.WP_PLUGIN_URL.'/'.ANNONCES_PLUGIN_DIR.'/includes/lib/image_galery.php?idgallery='.$id_to_treat.'&amp;token='.$token.'" height="21" style="border:0px solid red;margin:0;padding:0;height:300px;width:100%;overflow-y:no-scroll;" ><p>Votre navigateur ne supporter pas les frame</p></iframe>';
					}
				?>
				</div>
      </td>
    </tr>
		<tr>
      <td colspan="5" style="text-align:center;"  >
        <input type="button" value="<?php ( $act == 'add' ?_e('Cr&eacute;er','annonces') : _e('Enregistrer les modifications','annonces')) ?>" id="submit_annonce" onclick="document.forms.treat_annonce.submit();"/>
      </td>
    </tr>
  </table>

<?php
}
?>
</form>
<div style="float:right;">
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_s-xclick" />
		<input type="hidden" name="hosted_button_id" value="10265740" />
		<input type="image" src="https://www.paypal.com/fr_FR/FR/i/btn/btn_donateCC_LG.gif" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus s�curis�e !" />
		<img alt="" border="0" src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" width="1" height="1" />
	</form>
</div>