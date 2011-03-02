<?php
global $wpdb;
$attribut_annonce = new attribut_annonce();
$annonce_form = new annonce_form();
$annonce = new annonce();
echo '<link rel="stylesheet" type="text/css" href="'. WP_PLUGIN_URL . '/' . Basename_Dirname_AOS. '/includes/css/add_annonce.css" />';
require_once dirname(__FILE__).'./../includes/lib/options.class.php';

/**
*	Si on ajoute une annonce
**/
if(!empty($_POST['annonce_form']) && is_array($_POST['annonce_form']))
{
	$annonce_form->bind($_POST['annonce_form']);
	if ($annonce_form->isValid())
	{
		$sql  = $wpdb->prepare("UPDATE " . ANNONCES_TABLE_TEMPPHOTO . "
								SET numphoto = numphoto + 1");
		$requete = $wpdb->query($sql);
		$numimage = annonces_options::recupNumImage();
		
		$values = $annonce_form->getValues();

		if($values['idpetiteannonce'] == '')$annonce->create_annonce($values);
		elseif($values['idpetiteannonce'] != '')$annonce->update_annonce($values);
		
		echo '<br/><br/>';
		echo '<div class="ajout_effectue">'. __('Votre annonce a &eacute;t&eacute; ajout&eacute;, si vous souhaitez ajouter des images &agrave; votre annonce faites le ci-dessous sinon cliquez sur Terminer','annonces') .'</div><br/>';
				
		echo '<iframe src ="'.WP_PLUGIN_URL.'/'.Basename_Dirname_AOS.'/includes/lib/image_galery.php?idgallery=' . $numimage . '" height="21" style="border:0px solid red;margin:0;padding:0;height:300px;width:100%;overflow-y:no-scroll;" ><p>Votre navigateur ne supporter pas les frame</p></iframe>';
		
		echo '<input type="button" value="' . __('Terminer','annonces') .'" onclick="javascript:history.back()"/>';
		
	}
	else
	{
		echo 'Votre annonce est incompl&egrave;te, veuillez saisir correctement les champs.';
		echo '<div class="wrap"><h2>';
		echo '<a class="button add-new-h2" onclick="javascript:history.back()">' . __('Retour','annonces') . '</a>';
		echo '</h2></div>';
	}
}
else
{
?>
<div class="wrap">
	<h2>
		<?php echo __('Annonces','annonces') ?>
		<a class="button add-new-h2" onclick="javascript:document.getElementById('act').value='add';document.getElementById('actual_page').value='';document.forms.treat_annonce.submit();"><?php echo __('Ajouter','annonces') ?></a>
	</h2>
</div>
<br/><br/>
<div style="clear:both;" class="<?php echo $annonce->class_admin_notice; ?>" ><?php echo $annonce->error_message; ?></div>
<form action="" method="POST" name="treat_annonce" >
	<input type="hidden" name="id_to_treat" id="id_to_treat" value="" />
	<input type="hidden" name="act" id="act" value="<?php echo $act; ?>" />
	<input type="hidden" name="actual_page" id="actual_page" value="<?php echo $actual_page; ?>" />

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
						var image_icon = '<?php echo WP_PLUGIN_URL.'/'.Basename_Dirname_AOS; ?>/medias/images/<?php echo url_marqueur_courant ?>';
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
		<?php
			echo '<br/><br/>';
		?>
      </td>
    </tr>
		<tr>
      <td colspan="5" style="text-align:center;"  >
        <input type="button" value="<?php echo __('Cr&eacute;er','annonces') ?>" id="submit_annonce" onclick="javascript:document.getElementById('act').value='add';document.forms.treat_annonce.submit();"/>
		<input type="reset" value="<?php echo __('Effacer tout','annonces') ?>" id="reset" />
      </td>
    </tr>
  </table>
</form>
<?php
}
?>
<div style="float:right;">
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="10265740">
		<input type="image" src="https://www.paypal.com/fr_FR/FR/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !">
		<img alt="" border="0" src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
	</form>
</div>