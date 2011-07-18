<?php
/***************************************************
*Date: 01/10/2009      File:attribut_listing.php 	 *
*Author: Eoxia							                       *
*Comment:                                          *
***************************************************/

$attribut_group_form = new attribut_group_form();
$attribut_group = new attribut_group();
$attribut_group_filters_form = new attribut_group_filters_form();

$morequery = '';
$flag = DEFAULT_FLAG_ADMIN_AOS;
$act = isset($_REQUEST['act']) ? $tools->IsValid_Variable($_REQUEST['act']) : '' ;
$id_to_treat = isset($_REQUEST['id_to_treat']) ? $tools->IsValid_Variable($_REQUEST['id_to_treat']) : '' ;
$actual_page = isset($_REQUEST['actual_page']) ? $tools->IsValid_Variable($_REQUEST['actual_page']) : 0 ;


//	IF ACTION IS ADD (ADD OR UPDATE) ATTRIBUTE
if(!empty($_POST) && is_array($_POST['attribut_group']) && ($act != ''))
{
	$attribut_group_form->bind($_POST['attribut_group']);
	if ($attribut_group_form->isValid())
	{
		$values = $attribut_group_form->getValues();
		
		if($values['idgroupeattribut'] == '')$attribut_group->create_attribut_group($values);
		elseif($values['idgroupeattribut'] != '') $attribut_group->update_attribut_group($values);
		$act = '';
	}
	else
	{
		$act = 'add';
	}
}

//	IF WE ASK TO EDIT A ATTRIBUTE
elseif($act == 'edit')
{
	$attribut_group_to_treat = $attribut_group->get_attribut_group(" AND idgroupeattribut = '".$id_to_treat."'");
	$attribut_group_form->setDefault('idgroupeattribut', $attribut_group_to_treat[0]->idgroupeattribut);
	$attribut_group_form->setDefault('flagvalidgroupeattribut', $attribut_group_to_treat[0]->flagvalidgroupeattribut);
	$attribut_group_form->setDefault('descriptiongroupeattribut', $attribut_group_to_treat[0]->descriptiongroupeattribut);
	$attribut_group_form->setDefault('nomgroupeattribut', $attribut_group_to_treat[0]->nomgroupeattribut);
}

//	IF WE WANT TO FILTER RESULT
elseif(!empty($_POST) && is_array($_POST['attribut_group_filter']) && ($act == 'filter'))
{
	$attribut_group_filters_form->bind($_POST['attribut_group_filter']);
	if ($attribut_group_filters_form->isValid())
	{
		$filter_values = $attribut_group_filters_form->getValues();

		$morequery = $attribut_group_filters_form->setMorequery($filter_values);
	}
}

?>

<form action="" method="post" name="treat_group_att" >
	<input type="hidden" name="id_to_treat" id="id_to_treat" value="" />
	<input type="hidden" name="act" id="act" value="<?php echo $act; ?>" />
	<input type="hidden" name="actual_page" id="actual_page" value="<?php echo $actual_page; ?>" />
<div class="wrap">
	<h2>
		<?php echo __('Cat&eacute;gorie','annonces') ?>
		<a class="button add-new-h2" onclick="javascript:document.getElementById('act').value = 'add';document.getElementById('actual_page').value = '';document.forms.treat_group_att.submit();"><?php echo __('Ajouter','annonces') ?></a>
	</h2>
</div>
<br/><br/><br/><br/><br/>
<div class="<?php echo $attribut_group->class_admin_notice; ?>" ><?php echo $attribut_group->error_message; ?></div>

<?php
if(($act == 'add') || ($act == 'edit'))
{
?>

  <table class="annonce_form" >
    <?php echo $attribut_group_form ?>
    <tr>
      <td colspan="2">
        <input type="submit" value="<?php ( $act == 'add' ?_e('Cr&eacute;er','annonces') : _e('Modifier','annonces')) ?>" />
      </td>
    </tr>
  </table>

<?php
}
elseif($act == 'delete')
{
	$attribut_group->delete_attribut_group($id_to_treat);
	$act = '';
}

$nb_total_items = 0;$nb_total_items = $attribut_group->get_attribut_group($morequery, $flag , $actual_page, 'count');
/*$Pagination = '';
if(ceil($nb_total_items/NUMBER_OF_ITEM_PAR_PAGE_ADMIN_AOS) > 1)$Pagination = $tools->DoPagination(' onclick="javascript:document.getElementById(\'actual_page\').value=\'#PAGE#\';document.forms.treat_group_att.submit()" ',$nb_total_items,$actual_page,NUMBER_OF_ITEM_PAR_PAGE_ADMIN_AOS,PAGINATION_OFFSET_ADMIN_AOS,'','','#CCCCCC','#FFFFFF',-1);*/

if(($act == '') || ($act == 'filter'))
{

?>

		<div id="attribut_group_listing" class="margin18px" >
			<?php /*echo $Pagination*/ ?>
			<div class="margin18px" >
				<?php echo $attribut_group->show_attribut_group($attribut_group->get_attribut_group($morequery, $flag , $actual_page)) ?>
			</div>
			<?php /*echo $Pagination*/?>
		</div>

<?php

}

?>
</form>