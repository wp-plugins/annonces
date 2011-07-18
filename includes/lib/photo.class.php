<?php
class annonce_photo
{
	/**
	 * Save a new picture.
	 * @param string $tableElement Table name of the element
	 * @param int $idElement Id of the element in the table
	 * @param mixed $photo The path to the picture
	 * @return mixed $status The picture identifier if the photo is well insert and "error" else. 
	 */
	function saveNewPicture($tableElement, $idElement, $photo)
		{
			global $wpdb;
			$status = 'error';
			
			// $tableElement = eva_tools::IsValid_Variable($tableElement);
			// $idElement = eva_tools::IsValid_Variable($idElement);
			// $photo = eva_tools::IsValid_Variable(eva_tools::slugify($photo));

			$query = 
				$wpdb->prepare(
					"INSERT INTO " . ANNONCES_TABLE_PHOTOS . " 
						(idphotos, flagvalidphotos, idpetiteannonce, lot, original, titre, description, autoinsert, token) 
					VALUES 
						('', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')"
					, $photo);
			if($wpdb->query($query))
			{
				$status = evaPhoto::associatePicture($tableElement, $idElement, $wpdb->insert_id);
			}
			return $status;
		}

		
	/**
	* Return a upload form with a thumbnail if multiple is false
	*
	* @param string $repertoireDestination Repository of the uploaded file.
	* @param string $idUpload HTML div identifier.
	* @param string $allowedExtensions Allowed extensions for the upload (ex:"['jpeg','png']"). All extensions is written "[]".
	* @param bool $multiple Can the user upload multiple files in one time ?
	* @param string $actionUpload The url of the file call when the user press on upload button.
	* @param string $photoDefaut The default photo to display.
	*
	* @return string The upload form with eventually a thumbnail.
	*/
	function getFormulaireUploadPhoto($repertoireDestination, $idUpload, $allowedExtensions, $multiple, $actionUpload = '', $texteBoutton = '', $onCompleteAction = '')
	{
		require_once(ANNONCES_LIB_PLUGIN_DIR . 'photo/upload.php');

		$texteBoutton = ($texteBoutton == '') ? __("Envoyer un fichier", "evarisk") : $texteBoutton;
		$onCompleteAction = ($onCompleteAction == '') ? '' : $onCompleteAction;
		$actionUpload = ($actionUpload == '') ? ANNONCES_LIB_PLUGIN_URL . 'photo/uploadPhoto.php' : $actionUpload;
		$repertoireDestination = ($repertoireDestination == '') ? str_replace('\\', '/', WAY_TO_PICTURES_AOS . '/') : $repertoireDestination;
		$multiple = $multiple ? 'true' : 'false';

		$formulaireUpload = 
			'<script type="text/javascript">        
				annoncejquery(document).ready(function(){
					var uploader' . $idUpload . ' = new qq.FileUploader({
						element: document.getElementById("' . $idUpload . '"),
						action: "' . $actionUpload . '",
						allowedExtensions: ' . $allowedExtensions . ',
						multiple: ' . $multiple . ',
						params: {
							"folder": "' . $repertoireDestination . '",
							"abspath": "' . str_replace("\\", "/", ABSPATH) . '",
							"mainPluginFile": "' . str_replace("\\", "/", ANNONCES_HOME_DIR . "annonces.php") . '"
						},
						onComplete: function(file, response){
							' . $onCompleteAction . '
						}
					});

					annoncejquery("#' . $idUpload . ' .qq-upload-button").html("' . $texteBoutton . '");

					annoncejquery(".qq-upload-button").each(function(){
						uploader' . $idUpload . '._button = new qq.UploadButton({
							element: uploader' . $idUpload . '._getElement("button"),
							multiple: ' . $multiple . ',
							onChange: function(input){
								uploader' . $idUpload . '._onInputChange(input);
							}
						});
					});
					annoncejquery(".qq-upload-drop-area").each(function(){
						annoncejquery(this).html("<span>' . __("D&eacute;poser les fichiers ici pour les envoyer", "evarisk") . '</span>");
					});
				});
			</script>
			<div id="' . $idUpload . '" class="divUpload">		
				<noscript>			
					<p>Please enable JavaScript to use file uploader.</p>
					<!-- or put a simple form for upload here -->
				</noscript>         
			</div>';

		return $formulaireUpload;
	}

}
?>