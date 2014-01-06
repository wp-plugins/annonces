<?php
/**
* Plugin ajax request management.
*
*	Every ajax request will be send to this page wich will return the request result regarding all the parameters
* @author Eoxia <dev@eoxia.com>
* @version 1.0
* @package annonces
* @subpackage includes
*/

/**
*	Wordpress - Ajax functionnality activation
*/
DEFINE('DOING_AJAX', true);
/**
*	Wordpress - Specify that we are in wordpress admin
*/
DEFINE('WP_ADMIN', true);
/**
*	Wordpress - Main bootstrap file that load wordpress basic files
*/
require_once('../../../../wp-load.php');
/**
*	Wordpress - Admin page that define some needed vars and include file
*/
require_once(ABSPATH . 'wp-admin/includes/admin.php');


/**
*	First thing we define the main directory for our plugin in a super global var
*/
DEFINE('ANNONCES_PLUGIN_DIR', basename(dirname(__FILE__)));
/**
*	Include the different config for the plugin
*/
require_once(WP_PLUGIN_DIR . '/' . ANNONCES_PLUGIN_DIR . '/includes/config/config.php' );
/**
*	Include the file which includes the different files used by all the plugin
*/
require_once(	ANNONCES_INC_PLUGIN_DIR . 'includes.php' );

/*	Get the different resquest vars to sanitize them before using	*/
$method = tools::IsValid_Variable($_REQUEST['post']);
$action = tools::IsValid_Variable($_REQUEST['action']);

/*	Element code define the main element type we are working on	*/
$elementCode = tools::IsValid_Variable($_REQUEST['elementCode']);

/*	Element code define the secondary element type we are working on.	*/
$elementType = tools::IsValid_Variable($_REQUEST['elementType']);
$elementIdentifier = tools::IsValid_Variable($_REQUEST['elementIdentifier']);

/*	First look at the request method Could be post or get	*/
switch($method)
{
	case 'true':
	{/*	In case request method is equal to true, it means that we are working with post request method	*/
		/*	Look at the element type we have to work on	*/
		switch($elementCode)
		{
			case 'annonce_map_marker':
			case 'url_radio_maisons':
			case 'url_radio_terrains':
			case 'url_radio_toutes':
			case 'url_budget':
			case 'url_superficie':
			case 'url_recherche':
			{
				if($elementCode != 'annonce_map_marker'){
					$finalDir = explode('_', $elementCode);
					$the_final_dir = 'searchPicto/' . $finalDir[count($finalDir) - 1];
				}
				else{
					$the_final_dir = 'gmapMarker';
				}

				switch($action)
				{
					case 'frontend_picto_upload':
						if(!is_dir(str_replace('\\', '/', WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . $the_final_dir . '/'))){
							mkdir(str_replace('\\', '/', WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . $the_final_dir . '/'), 0755, true);
							exec('chown -R 0755 ' . WP_CONTENT_DIR . '/uploads');
						}
						$target_path = str_replace('\\', '/', WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . $the_final_dir . '/') . basename( $_FILES['new_picto_for_frontend']['name']);
						if(move_uploaded_file($_FILES['new_picto_for_frontend']['tmp_name'], $target_path)) {
							echo sprintf(__('Le fichier %s a bien &eacute;t&eacute; envoy&eacute; sur le serveur', 'annonces'), basename( $_FILES['new_picto_for_frontend']['name']));
						}
						else{
							echo sprintf(__('Une erreur est survenue lors de l\'envoi du fichier %s sur le serveur', 'annonces'), basename( $_FILES['new_picto_for_frontend']['name']));
						}
					break;

					case 'loadPictureUploadForm':
						$picture_form = '<form action="' . ANNONCES_INC_PLUGIN_URL . 'ajax.php" name="frontend_picto_change_form" id="frontend_picto_change_form" method="post" ><input type="hidden" name="fieldToUpdate" id="fieldToUpdate" value="' . $elementCode . '" /><input type="hidden" name="previewToUpdate" id="previewToUpdate" value="preview_' . $elementCode . '" /><input type="hidden" name="post" value="true" /><input type="hidden" name="elementCode" value="' . $elementCode . '" /><input type="hidden" name="action" value="frontend_picto_upload" /><input type="file" name="new_picto_for_frontend" /><input type="button" value="' . __('Envoyer', 'annonces') . '" class="button-primary" id="frontend_picto_change_form_submit" /></form>
						<script type="text/javascript">
							annoncejquery(document).ready(function(){
								jQuery("#frontend_picto_change_form_submit").click(function(){
									jQuery("#frontend_picto_change_form").ajaxSubmit({
										resetForm:true,
										target:"#annoncePictoChangerContent",
										success:function(){
											jQuery("#annoncePictoChangerContent").load("' . ANNONCES_INC_PLUGIN_URL . 'ajax.php",{
												"post": "true", "elementCode": "' . $elementCode . '", "action": "loadPictureDirContent"
											});
										}
									});
								});
							});
						</script>';

						echo $picture_form;
					break;
					case 'loadPictureDirContent':
						$output = '';
						$i = 1;
						$options = get_option('annonces_options');
						$directoryToRead = str_replace('\\', '/', WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . $the_final_dir . '/');
						if(is_dir($directoryToRead)) {
							$directory = opendir($directoryToRead);
							while($element = readdir($directory)) {
								if(($element != '.') && ($element != '..')) {
									if(is_file($directoryToRead . $element)) {
										$checked = (str_replace($finalDir[count($finalDir) - 1] . '//', $finalDir[count($finalDir) - 1] . '/', $options[$elementCode]) == $the_final_dir . '/' . $element) ? ' checked="checked" ' : '';
										$output .= '<div class="alignleft" style="margin:9px" ><input type="radio" class="pictoToChoose" name="choosenMarker[]" id="choosenMarker_' . $i . '" value="' . $the_final_dir . '/' . $element . '" ' . $checked. ' /><label for="choosenMarker_' . $i . '" ><img src="' . str_replace(str_replace('\\', '/', WP_CONTENT_DIR), WP_CONTENT_URL, $directoryToRead) . $element . '" alt="' . $element . '" style="max-height:43px;vertical-align:middle;" /></label></div>';
										$i++;
									}
								}
							}
						}
						else {
							$output .= __('Aucun fichier n\'a &eacute;t&eacute; envoy&eacute; pour le moment', 'annonces');
						}

						$output .= '<script type="text/javascript" >annoncejquery(".qq-upload-list").hide();</script>';

						echo $output;
					break;
				}
			}
			break;

			case 'urlRewriteFormat':
			{
				switch($action)
				{
					case 'loadUrlPossibleParams';
					{
						$output = $script = '';

						$output .= __('Liste des param&egrave;tres possible pour la r&eacute;&eacute;criture d\'url', 'annonces') . '<br/><span class="helpUrlRewriteFormat" >' . __('Cliquez sur le nom du param&egrave;tre pour ajouter &agrave; la r&egrave;gle de r&eacute;&eacute;criture. L\'ordre du choix est important.', 'annonces') . '</span><div class="urlParamsList" >';
						foreach($urlKeyword as $key => $humanReadableName)
						{
							$output .= '<div id="' . $key . '" ><span class="ui-icon alignleft deleteUrlParam" id="keyWord_' . $key . '" style="display:none;" >&nbsp;</span><span class="urlParams" >' . $humanReadableName . '</span></div>';
							$script .= 'if(annoncejquery("#urlRewriteFormat").val().match("' . $key . '")){annoncejquery("#keyWord_' . $key . '").show();}';
						}
						$output .= '</div>
						<input type="text" value="' . annonces_expression_url . '" name="urlRewriteFormat" id="urlRewriteFormat" />
						<script type="text/javascript" >
							if(annoncejquery("#annonce_url_rewrite_template").val() != "' . annonces_expression_url . '"){
								annoncejquery("#urlRewriteFormat").val(annoncejquery("#annonce_url_rewrite_template").val());
							}
							annoncejquery(".urlParams").click(function(){
								var newParam = "%" + annoncejquery(this).parent("div").attr("id") + "%"; annoncejquery("#urlRewriteFormat").val(annoncejquery("#urlRewriteFormat").val().replace(newParam, "") + newParam);
								annoncejquery("#keyWord_" + annoncejquery(this).parent("div").attr("id")).show();
							});
							annoncejquery(".deleteUrlParam").click(function(){
								annoncejquery(this).hide();
								annoncejquery("#urlRewriteFormat").val(annoncejquery("#urlRewriteFormat").val().replace("%" + annoncejquery(this).parent("div").attr("id") + "%", ""));
							});
							' . $script . '
						</script>';

						echo $output;
					}
					break;
				}
			}
			break;
		}
	}
	break;

	default:
	{/*	Default case is get request method	*/

	}
	break;
}
