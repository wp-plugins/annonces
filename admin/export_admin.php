
<?php
/***************************************************
*Date: 01/10/2009      File:export_admin.php 	   *
*Author: Eoxia							           *
*Comment:                                          *
***************************************************/
$passerelle = new passerelle();
$form = new passerelle_form();
$passerelle_filters_form = new passerelle_filters_form();

$morequery = '';
$flag = DEFAULT_FLAG_ADMIN_AOS;
$act = isset($_REQUEST['act']) ? $tools->IsValid_Variable($_REQUEST['act']) : '' ;
$id_to_treat = isset($_REQUEST['id_to_treat']) ? $tools->IsValid_Variable($_REQUEST['id_to_treat']) : '' ;
$actual_page = isset($_REQUEST['actual_page']) ? $tools->IsValid_Variable($_REQUEST['actual_page']) : '' ;


//	IF ACTION IS ADD (ADD OR UPDATE) ATTRIBUTE
if(!empty($_POST) && is_array($_POST['passerelle']))
{
	$form->bind($_POST['passerelle']);
	if ($form->isValid())
	{
		$values = $form->getValues();
		
		if($values['idpasserelle'] == '')$passerelle->create_passerelle($values);
		elseif($values['idpasserelle'] != '') $passerelle->update_passerelle($values);
	}
	else
	{
		$act = 'add';
	}
}

//	IF WE ASK TO EDIT A ATTRIBUTE
elseif($act == 'edit')
{
	$passerelle_to_treat = $passerelle->get_passerelle(" AND idpasserelle = '".$id_to_treat."'");
	$form->setDefault('idpasserelle', $passerelle_to_treat[0]->idpasserelle);
	$form->setDefault('flagvalidpasserelle', $passerelle_to_treat[0]->flagvalidpasserelle);
	$form->setDefault('nompasserelle', $passerelle_to_treat[0]->nompasserelle);
	$form->setDefault('nomexport', $passerelle_to_treat[0]->nomexport);
	$form->setDefault('host', $passerelle_to_treat[0]->host);
	$form->setDefault('user', $passerelle_to_treat[0]->user);
	$form->setDefault('pass', $passerelle_to_treat[0]->pass);
	$form->setDefault('structure', $passerelle_to_treat[0]->structure);
	$form->setDefault('separateurtexte', $passerelle_to_treat[0]->separateurtexte);
	$form->setDefault('separateurchamp', $passerelle_to_treat[0]->separateurchamp);
	$form->setDefault('separateurligne', $passerelle_to_treat[0]->separateurligne);
}

//	IF WE WANT TO FILTER RESULT
elseif(!empty($_POST) && is_array($_POST['passerelle_filter']) && ($act == 'filter'))
{
	$passerelle_filters_form->bind($_POST['passerelle_filter']);
	if ($passerelle_filters_form->isValid())
	{
		$filter_values = $passerelle_filters_form->getValues();

		$morequery = $passerelle_filters_form->setMorequery($filter_values);
	}
}

?>
<div class="wrap">
	<h2>
		<?php echo __('Passerelles','annonces') ?>
		<a class="button add-new-h2" onclick="javascript:document.getElementById('act').value = 'add';document.forms.treat_passerelle.submit();"><?php echo __('Ajouter','annonces') ?></a>
	</h2>
</div>
<br/>
<div class="<?php echo $passerelle->class_admin_notice; ?>" ><?php echo $passerelle->error_message; ?></div>

<?php
if(($act == 'add') || ($act == 'edit'))
{
?>
<form action="" method="post" name="treat_passerelle">
	<input type="hidden" name="act" value="add" />
  <table class="annonce_form" >
    <?php echo $form ?>
    <tr>
      <td colspan="2">
       	<table class="attribut_listing" >
					<tr>
						<td class="td_att_listing" colspan="10" ><?php _e('Pour s&eacute;lectionner un attribut cliquez sur son nom. ATTENTION: l\'ordre est important','annonces') ?></td>
					</tr>
					<?php
						$attribut_annonce = new attribut_annonce();
						$attribut_listing = $attribut_annonce->get_attribut_annonce('' , DEFAULT_FLAG_ADMIN_AOS , 0 , 'nolimit');//print_r($attribut_listing);
						$i = 0;
						foreach($attribut_listing as $key => $content)
						{
							if($i == 0)
							{
								echo '<tr>';
							}

							echo '<td><input type="checkbox" name="attribut_'.$content->labelattribut.'" id="'.$content->labelattribut.'" value="'.$content->labelattribut.'" onclick="javascript:add_column_to_export_strucure(\'passerelle_structure\',\''.$content->labelattribut.'\',\'\');" /><label for="'.$content->labelattribut.'" >'.$content->nomattribut.'</label></td>';
							$i++;

							if(($i == 5) || ($i == count($attribut_listing)))
							{
								echo '</tr>';
								$i=0;
							}
						}
						if(($i != 0) || ($i < 5) || ($i < count($attribut_listing)))
						{
							echo '</tr>';
						}
					?>
				</table>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <input type="submit" value="<?php ( $act == 'add' ? _e('Cr&eacute;er','annonces') : _e('Modifier','annonces')) ?>" />
      </td>
    </tr>
  </table>
</form>
<?php
}
elseif($act == 'delete')
{
	$passerelle->delete_passerelle($id_to_treat);
	$act = '';
}

if(($act == '') || ($act == 'filter'))
{
?>
<form action="" method="post" name="treat_passerelle" >
	<input type="hidden" name="id_to_treat" id="id_to_treat" value="" />
	<input type="hidden" name="act" id="act" value="<?php echo $act; ?>" />
	<input type="hidden" name="actual_page" id="actual_page" value="<?php echo $actual_page; ?>" />
	<div id="attribut_annonce_listing" >
		<div class="margin18px" >
			<?php echo $passerelle->show_passerelle($passerelle->get_passerelle($morequery, $flag , $actual_page)) ?>
		</div>
	</div>
</form>
<?php
}
?>