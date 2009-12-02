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
<div class="sub_admin_menu" >
	<form action="" method="POST" style="float:left;" ><input type="hidden" name="act" value="" /><input type="submit" name="submit_home" value="<?php _e('Listing des passerelles existantes','annonces') ?>" /></form>
	<form action="" method="POST"><input type="hidden" name="act" value="add" /><input type="submit" name="submit_add" value="<?php _e('Ajouter une passerelle','annonces') ?>" /></form>
</div>
<hr style="clear:both;" /><br/>
<div class="<?php echo $passerelle->class_admin_notice; ?>" ><?php echo $passerelle->error_message; ?></div>

<?php
if(($act == 'add') || ($act == 'edit'))
{
?>
<form action="" method="POST">
  <table class="annonce_form" >
    <?php echo $form ?>
    <tr>
      <td colspan="2">
       	<table class="attribut_listing" >
					<tr>
						<td colspan="10" style="text-align:center;" ><?php _e('Pour s&eacute;lectionner un attribut cliquez sur son nom. ATTENTION: l&#146;ordre est important','annonces') ?></td>
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


$nb_total_items = 0;$nb_total_items = $passerelle->get_passerelle($morequery, $flag , $actual_page, 'count');
$Pagination = '';
if(ceil($nb_total_items/NUMBER_OF_ITEM_PAR_PAGE_ADMIN_AOS) > 1)$Pagination = $tools->DoPagination(' onclick="javascript:document.getElementById(\'actual_page\').value=\'#PAGE#\';document.forms.treat_passerelle.submit()" ',$nb_total_items,$actual_page,NUMBER_OF_ITEM_PAR_PAGE_ADMIN_AOS,PAGINATION_OFFSET_ADMIN_AOS,'','','#CCCCCC','#FFFFFF',-1);

if(($act == '') || ($act == 'filter'))
{
?>

<form action="" method="POST" name="treat_passerelle" >
	<input type="hidden" name="id_to_treat" id="id_to_treat" value="" />
	<input type="hidden" name="act" id="act" value="<?php echo $act; ?>" />
	<input type="hidden" name="actual_page" id="actual_page" value="<?php echo $actual_page; ?>" />
		<div id="passerelle_filter" class="margin18px" >
			<table summary="admin passerelle filters" cellpadding="0" cellspacing="0" class="floatright margin18px" style="border:1px solid #333333;" >
				<tr><td colspan="2" style="text-align:center;background-color:#333333;color:#FFFFFF;font-weight:bold;font-size:14px;" ><?php _e('Rechercher une passerelle','annonces') ?></td></tr> 
				<?php echo $passerelle_filters_form ;?>
				<tr>
					<td colspan="2" > 
						<input type="button" value="<?php _e('Filtrer les r&eacute;sultats','annonces') ?>" class="floatright" 
							onclick="javascript:document.getElementById('act').value='filter';document.getElementById('actual_page').value='';document.forms.treat_passerelle.submit();" /> 
						<input type="button" value="<?php _e('Tout afficher','annonces') ?>" class="floatright" 
							onclick="javascript:document.getElementById('act').value='';document.getElementById('actual_page').value='';document.forms.treat_passerelle.submit();" />
					</td>
				</tr>
			</table>
		</div>
		<div id="attribut_annonce_listing" style="clear:both;" >
			<?php echo $Pagination ?>
			<div class="margin18px" >
				<?php echo $passerelle->show_passerelle($passerelle->get_passerelle($morequery, $flag , $actual_page)) ?>
			</div>
			<?php echo $Pagination ?>
		</div>
</form>

<?php
}
?>