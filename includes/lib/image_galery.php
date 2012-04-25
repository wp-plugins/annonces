<?php
	require_once('../../../../../wp-config.php');
	require_once('../configs.php');
	require_once('../lib/admin_annonces.class.php');
	$annonce = new annonce();

	$url_for_uploaded_file = WP_CONTENT_DIR . WAY_TO_PICTURES_AOS;
	$url_for_uploaded_file_thumbnail = WP_CONTENT_DIR . WAY_TO_PICTURES_THUMBNAIL_AOS;
	$button_text_del = 'Supprimer';
	$onload = '';

	$idgallery = (isset($_REQUEST['idgallery']) && ($_REQUEST['idgallery']!='')) ? $_REQUEST['idgallery'] : -1 ;
	$idtodelete = (isset($_REQUEST['idtodelete']) && ($_REQUEST['idtodelete']!='')) ? $_REQUEST['idtodelete'] : 0 ;
	$token = (isset($_REQUEST['token']) && ($_REQUEST['token']!='')) ? $_REQUEST['token'] : 0 ;

	if(isset($_FILES['photo']['tmp_name']))
	{
		if (is_uploaded_file($_FILES['photo']['tmp_name']))
		{
			if(!is_dir($url_for_uploaded_file))mkdir($url_for_uploaded_file, 0755, true);
			exec('chown -R 0755 ' . WP_CONTENT_DIR . '/uploads');
			chmod($url_for_uploaded_file, 0755);
			if(!is_dir($url_for_uploaded_file_thumbnail))mkdir($url_for_uploaded_file_thumbnail, 0755, true);
			exec('chown -R 0755 ' . WP_CONTENT_DIR . '/uploads');
			chmod($url_for_uploaded_file_thumbnail, 0755);

			$extension=strtolower(strrchr($_FILES['photo']['name'],'.'));
			if(isset($TABALLOWEDEXT[$extension]))
			{
				$photo='img'.date('YmdHis').$extension;
				if(move_uploaded_file($_FILES['photo']['tmp_name'],$url_for_uploaded_file.$photo))
				{
					if ($idgallery == -1)
					{
						$sqltemp = "SELECT numphoto FROM `".$wpdb->prefix.small_ad_table_prefix_AOS."petiteannonce__tempphoto`";
						$reqtemp = mysql_query($sqltemp) or die(mysql_error());
						while($datatemp = mysql_fetch_array($reqtemp))
						{
							$temp = $datatemp[0];
						}
						
						$sqlphotos = "SELECT max(idpetiteannonce)+1 FROM `".$wpdb->prefix.small_ad_table_prefix_AOS."petiteannonce`";
						$reqphotos = mysql_query($sqlphotos) or die(mysql_error());
						while($dataphotos = mysql_fetch_array($reqphotos))
						{
							$photos = $dataphotos[0];
						}
						
						if ($temp == $photos || $photos == 0)
						{
							$idgallery = $temp;
						}
						else
						{
							$idgallery = $photos;
						}
					}
					$sql = 
						"INSERT INTO " . $wpdb->prefix . small_ad_table_prefix_AOS . "petiteannonce__photos
								(idphotos, flagvalidphotos, idpetiteannonce, lot, original, titre, description, autoinsert, token)
							VALUES
								('', 'valid', '".mysql_real_escape_string($idgallery)."', 'divers', '" . mysql_real_escape_string($photo) . "', '" . mysql_real_escape_string($_FILES['photo']['name']) . "', '" . mysql_real_escape_string($_FILES['photo']['name']) . "', NOW(), '" . mysql_real_escape_string($token) . "')";
					$wpdb->query($sql);
					$idphotos_insert = mysql_insert_id($wpdb->dbh);

					list($width, $height, $type, $attr) = getimagesize($url_for_uploaded_file.$photo);

						//RETAILLAGE DE L'IMAGE
					if($width > MAX_PICTURE_WIDTH_AOS)
					{
						$ImageChoisie = imagecreatefromjpeg($url_for_uploaded_file.$photo);
						$NouvelleLargeur = MAX_PICTURE_WIDTH_AOS;
						$Reduction = (($NouvelleLargeur * 100)/$width); // Index 0 = Largeur - Index 1 = Hauteur
						$NouvelleHauteur = (($height * $Reduction)/100);
					
						$NouvelleImage = imagecreatetruecolor($NouvelleLargeur , $NouvelleHauteur);
						imagecopyresampled($NouvelleImage , $ImageChoisie  , 0,0, 0,0, $NouvelleLargeur, $NouvelleHauteur, $width,$height);
						imagedestroy($ImageChoisie);
						$NomImageChoisie = explode('.', $ImageNews);
						$NomImageExploitable = time();

						imagejpeg($NouvelleImage,$url_for_uploaded_file_thumbnail.$photo, 100);
					}
				}
			}
			else
			{
				$onload.='alert(\''.__('Ce fichier ne semble pas &ecirc;tre valide.','annonces').'\')';
				// $onload.='alert(\'Ce fichier ne semble pas être valide !\')';
			}
		}
	}

	if($idtodelete != 0)
	{
		$sql =
			"UPDATE " . $wpdb->prefix . small_ad_table_prefix_AOS . "petiteannonce__photos
				SET flagvalidphotos = 'deleted'
				WHERE	idphotos = '". mysql_real_escape_string($idtodelete) ."' ";
		$wpdb->query($sql);
	}

	$morequery = "";if($idgallery <= 0)$morequery = " OR token = '".mysql_real_escape_string($token)."' ";
	$image_gallery_output = '';
	$photos_list = $annonce->get_photos_for_annonce("'".mysql_real_escape_string($idgallery)."'", $morequery);
	$i = 0;
	foreach($photos_list as $key => $photos_definition)
	{
		if(is_file(WP_CONTENT_DIR . WAY_TO_PICTURES_AOS . $photos_definition->original))
		{	
			if($i == 0)
			{
				$image_gallery_output .= '<tr>';
			}
			$image_gallery_output .= '<td><img src="'.WP_CONTENT_URL . WAY_TO_PICTURES_THUMBNAIL_AOS . $photos_definition->original.'" alt="'.$photos_definition->description.'" title="'.$photos_definition->description.'" style="border:0px solid red;" /><div><input type="button" name="del_pic'.$key.'" id="del_pic'.$key.'" style="border:0px solid red;color:red;font-size:11px;background-color:#FFFFFF;" value="'.$button_text_del.'" 
				onclick = "javascript:document.getElementById(\'idtodelete\').value = \'' . $photos_definition->idphotos . '\';document.forms.photo_gallery.submit();" /></div><td>';

			$i++;
			if(($i == 6) || ($i >= count($photos_list)))
			{
				$image_gallery_output .= '</tr>';
				$i = 0;
			}
		}
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1;">
<title>Petites annonces | upload medias</title>
<script type="text/javascript" >
var id = 0;
function AddSubElement_frame(wheretoadd, input_file_name, form)
{	
	id++;

	var d=document.createElement("div");
	d.id="dynamic_file";
	var i=document.createElement("input");	// ajout input file
	i.type="file";
	i.id= input_file_name+id;
	i.name= input_file_name;
	i.size=34;
	i.onchange=function(){
		form.submit();
		window.top.document.getElementById("submit_annonce").style.display = 'none';
	}

	d.appendChild(i);
	
	var b=document.createElement("input");	// ajout du bouton pour supprimer
	b.type="button";
	b.value="<?php _e('Supprimer','annonces') ?>";
	b.onclick=function(){
		this.parentNode.style.display="none";
		this.parentNode.innerHTML="";
		document.getElementById('add_photo_button').style.display = 'block';
	}
	d.appendChild(b);

	document.getElementById(wheretoadd).appendChild(d);
}
</script>
</head>
<body onload="window.top.document.getElementById('submit_annonce').style.display = 'block';<?php echo $onload;?>" style="margin:0px;font-size:11px;padding:0;">
	<form name="photo_gallery" action="" method="post" enctype="multipart/form-data" >
		<input type="button" value="<?php _e('Ajouter une photo','annonces') ?>" id="add_photo_button"
			onclick="AddSubElement_frame('btn_galerie', 'photo' , document.forms.photo_gallery);this.style.display='none'" />
		<div id="btn_galerie" style="clear:both;"></div>
		<input type="hidden" name="idgallery" value="<?php echo $idgallery; ?>" id="idgallery" />
		<input type="hidden" name="idtodelete" value="" id="idtodelete" />
		<table summary="" cellpadding="0" cellspacing="0" style="border-collapse:collapse;" > 
			<?php echo $image_gallery_output ;?>
		</table>
	</form>
</body>
</html>