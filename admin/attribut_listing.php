<?php
/***************************************************
*Date: 01/10/2009      File:attribut_listing.php   *
*Author: Eoxia							           *
*Comment:                                          *
***************************************************/

$attribut_annonce_form = new attribut_annonce_form();
$attribut_annonce = new attribut_annonce();
$attribut_filters_form = new attribut_filters_form();

$morequery = '';
$flag = DEFAULT_FLAG_ADMIN_AOS;
$act = isset($_REQUEST['act']) ? $tools->IsValid_Variable($_REQUEST['act']) : '' ;
$id_to_treat = isset($_REQUEST['id_to_treat']) ? $tools->IsValid_Variable($_REQUEST['id_to_treat']) : '' ;
$actual_page = isset($_REQUEST['actual_page']) ? $tools->IsValid_Variable($_REQUEST['actual_page']) : '' ;

//	IF ACTION IS ADD (ADD OR UPDATE) ATTRIBUTE
if(!empty($_POST) && is_array($_POST['attribut_annonce']) && ($act != ''))
{
	$attribut_annonce_form->bind($_POST['attribut_annonce']);
	if ($attribut_annonce_form->isValid())
	{
		$values = $attribut_annonce_form->getValues();
		
		if($values['idattribut'] == '')$attribut_annonce->create_attribut_annonce($values);
		elseif($values['idattribut'] != '') $attribut_annonce->update_attribut_annonce($values);
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
	$attribut_to_treat = $attribut_annonce->get_attribut_annonce(" AND ATTRIBUTE.idattribut = '".$id_to_treat."'");
	$attribut_annonce_form->setDefault('idattribut', $attribut_to_treat[0]->idattribut);
	$attribut_annonce_form->setDefault('flagvalidattribut', $attribut_to_treat[0]->flagvalidattribut);
	$attribut_annonce_form->setDefault('flagvisibleattribut', $attribut_to_treat[0]->flagvisibleattribut);
	$attribut_annonce_form->setDefault('typeattribut', $attribut_to_treat[0]->typeattribut);
	$attribut_annonce_form->setDefault('labelattribut', $attribut_to_treat[0]->labelattribut);
	$attribut_annonce_form->setDefault('group_attribut', $attribut_to_treat[0]->idgroupeattribut);
	$attribut_annonce_form->setDefault('nomattribut', $attribut_to_treat[0]->nomattribut);
	$attribut_annonce_form->setDefault('measureunit', $attribut_to_treat[0]->measureunit);
}

//	IF WE WANT TO FILTER RESULT
elseif(!empty($_POST) && is_array($_POST['attribut_filters']) && ($act == 'filter'))
{
	$attribut_filters_form->bind($_POST['attribut_filters']);
	if ($attribut_filters_form->isValid())
	{
		$filter_values = $attribut_filters_form->getValues();

		$morequery = $attribut_filters_form->setMorequery($filter_values);
	}
}

if($act == 'delete')
{
	$attribut_annonce->delete_attribut_annonce(" '".mysql_real_escape_string($id_to_treat)."' ");
	$act = '';
}

//	MASS ACTION
if(isset($_POST['attribut']) && is_array($_POST['attribut']))
{
	$id_list = "  ";
	foreach($_POST['attribut'] as $key => $value)
	{
		$id_list .= " '" . mysql_real_escape_string($value) . "', ";
	}

	$id_list = substr($id_list,0,-2);
	if($id_list != "")
	{
		if($act == 'selection_delete')
		{
			$attribut_annonce->delete_attribut_annonce($id_list);
			$act = '';
		}
		elseif($act == 'selection_moderated')
		{
			$attribut_annonce->update_attribut_annonce_status($id_list,'flagvalidattribut','moderated');
			$act = '';
		}
		elseif($act == 'selection_valid')
		{
			$attribut_annonce->update_attribut_annonce_status($id_list,'flagvalidattribut','valid');
			$act = '';
		}
		elseif($act == 'selection_visible')
		{
			$attribut_annonce->update_attribut_annonce_status($id_list,'flagvisibleattribut','oui');
			$act = '';
		}
		elseif($act == 'selection_not_visible')
		{
			$attribut_annonce->update_attribut_annonce_status($id_list,'flagvisibleattribut','non');
			$act = '';
		}
	}
}

?>

<form action="" method="POST" name="treat_attribut" >
	<input type="hidden" name="id_to_treat" id="id_to_treat" value="" />
	<input type="hidden" name="act" id="act" value="<?php echo $act; ?>" />
	<input type="hidden" name="actual_page" id="actual_page" value="<?php echo $actual_page; ?>" />
<div class="sub_admin_menu" >
	<input type="submit" name="submit_home" value="<?php _e("Listing des attributs","annonces") ?>" 
			onclick="javacsript:document.getElementById('act').value = '';document.getElementById('actual_page').value = '';document.forms.treat_attribut.submit();"/>
	<input type="submit" name="submit_add" value="<?php _e("Ajouter un attribut","annonces") ?>" 
			onclick="javacsript:document.getElementById('act').value = 'add';document.getElementById('actual_page').value = '';document.forms.treat_attribut.submit();"/>
</div>
<hr style="clear:both;" /><br/>
<div class="<?php echo $attribut_annonce->class_admin_notice; ?>" ><?php echo $attribut_annonce->error_message; ?></div>

<?php
if(($act == 'add') || ($act == 'edit'))
{
?>

  <table class="annonce_form" >
    <?php echo $attribut_annonce_form ?>
    <tr>
      <td colspan="2">
        <input type="button" value="<?php ( $act == 'add' ?_e('Cr&eacute;er','annonces') : _e('Modifier','annonces')) ?>" id="submit_attribut" onclick="javascript:document.getElementById('act').value='add';document.forms.treat_attribut.submit();"/>
      </td>
    </tr>
  </table>

<?php
}

$nb_total_items = 0;$nb_total_items = $attribut_annonce->get_attribut_annonce($morequery, $flag , $actual_page, 'count');
$Pagination = '';
if(ceil($nb_total_items/NUMBER_OF_ITEM_PAR_PAGE_ADMIN_AOS) > 1)$Pagination = $tools->DoPagination(' onclick="javascript:document.getElementById(\'actual_page\').value=\'#PAGE#\';document.forms.treat_attribut.submit()" ',$nb_total_items,$actual_page,NUMBER_OF_ITEM_PAR_PAGE_ADMIN_AOS,PAGINATION_OFFSET_ADMIN_AOS,'','','#CCCCCC','#FFFFFF');

if(($act == '') || ($act == 'filter'))
{

?>

		<div id="attribute_filter" class="margin18px" >
			<table summary="attribute filters" cellpadding="0" cellspacing="0" class="floatright margin18px" style="border:1px solid #333333;" >
				<tr><td colspan="2" style="text-align:center;background-color:#333333;color:#FFFFFF;font-weight:bold;font-size:14px;" ><?php _e('Rechercher un attribut','annonces') ?></td></tr> 
				<?php echo $attribut_filters_form ;?>
				<tr>
					<td colspan="2" > 
						<input type="button" value="<?php _e("Filtrer les r&eacute;sultats","annonces") ?>" class="floatright" 
							onclick="javascript:document.getElementById('act').value='filter';document.getElementById('actual_page').value='';document.forms.treat_attribut.submit();" /> 
						<input type="button" value="<?php _e("Tout afficher","annonces") ?>" class="floatright" 
							onclick="javascript:document.getElementById('act').value='';document.getElementById('actual_page').value='';document.forms.treat_attribut.submit();" />
					</td>
				</tr>
			</table>
		</div>
		<div id="attribut_annonce_listing" style="clear:both;" >
			<div >
				<div class="floatleft" >
				<?php
					echo $Pagination 
				?>
				</div>
				<div class="floatright margin18px" style="width:40%;" >
					<input type="button" name="general_submit" value="<?php _e("Effectuer","annonces") ?>" id="general_submit" 
						onclick="javacsript:document.getElementById('act').value = document.getElementById('general_action').options[document.getElementById('general_action').selectedIndex].value;document.forms.treat_attribut.submit();" class="floatright" />
					<select name="general_action" id="general_action" class="floatright" >
						<option value="" ><?php _e("Pour la s&eacute;lection","annonces") ?>&nbsp;</option>
					<?php
						foreach($attribut_general_action as $action_name => $action)
						{
							echo '<option value="'.$action.'" >'.$action_name.'</option>';
						}
					?>
					</select>
					<div class="floatright" style="clear:both;margin-right:64px;">
						<div class="floatright" onclick="javascript:check_selection(document.forms.treat_attribut,'uncheck_all');" style="cursor:pointer;" ><?php _e("Aucun","annonces") ?></div>
						<div class="floatright" onclick="javascript:check_selection(document.forms.treat_attribut,'check_all');" >&nbsp;/&nbsp;</div>
						<div class="floatright" onclick="javascript:check_selection(document.forms.treat_attribut,'check_all');" style="cursor:pointer;" ><?php _e("Tout","annonces") ?></div>
						<div class="floatright" onclick="javascript:check_selection(document.forms.treat_attribut,'check_all');" ><?php _e("S&eacute;lectionner","annonces") ?>&nbsp;</div>
					</div>
				</div>
			</div>
			<div class="margin18px" style="clear:both;" >
				<?php echo $attribut_annonce->show_attribut_annonce($attribut_annonce->get_attribut_annonce($morequery, $flag , $actual_page)) ?>
			</div>
			<?php echo $Pagination ?>
		</div>

<?php

}

?>
</form>