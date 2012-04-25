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

}
?>