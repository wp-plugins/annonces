<?php
/***************************************************
*Date: 05/11/2009      File:import_admin.php 	   *
*Author: Eoxia							           *
*Comment:                                          *
***************************************************/
require_once(dirname(__FILE__). '/../includes/lib/Excel/reader.php');
require_once(dirname(__FILE__). '/../includes/lib/Csv/Csv.class.php');
global $tools;
global $attribute_group_possibilities;
$attribut_annonce = new attribut_annonce();

if($_POST){

	$step 						= isset($_POST['step']) ? $tools->IsValid_Variable($_POST['step']) : '' ;
	$file_type 					= isset($_POST['file_type']) ? $tools->IsValid_Variable($_POST['file_type']) : '' ;
	$import_structure 			= isset($_POST['import_structure']) ? $tools->IsValid_Variable($_POST['import_structure']) : '' ;
	$announcement_categorie 	= isset($_POST['announcement_categorie']) ? $tools->IsValid_Variable($_POST['announcement_categorie']) : '' ;
	$import_separateurtexte 	= isset($_POST['import_separateurtexte']) ? $tools->IsValid_Variable($_POST['import_separateurtexte']) : '' ;
	$import_separateurchamp 	= isset($_POST['import_separateurchamp']) ? $tools->IsValid_Variable($_POST['import_separateurchamp']) : '' ;
	$import_separateurligne 	= isset($_POST['import_separateurligne']) ? $tools->IsValid_Variable($_POST['import_separateurligne']) : '' ;
	$progress 					= isset($_POST['progress']) ? $tools->IsValid_Variable($_POST['progress']) : '' ;
	$id_announcement_category 	= isset($_POST['id_announcement_category']) ? $tools->IsValid_Variable($_POST['id_announcement_category']) : '' ;
	$file 						= isset($_POST['file']) ? $tools->IsValid_Variable($_POST['file']) : '' ;
	
	switch($step){
		case 1:
?>
			<form name="export_annonce" action="" method="post" >
				<center>
					<textarea id="import_structure" name="import_structure" cols="70" rows="4"></textarea><br/>
					<label class="label_import_admin"><?php echo _e('S&eacute;lectionner les attributs pr&eacute;sent dans les annonces &agrave; importer.','annonces') ?></label>
				</center>
				<table class="attribut_listing" >
					<tr>
						<td colspan="10" class="td_attribut_listing">
							<?php _e('Pour s&eacute;lectionner un attribut cliquez sur son nom. ATTENTION: l\'ordre est important','annonces') ?>
						</td>
					</tr>
					<?php
						$attribut_listing = $attribut_annonce->get_attribut_annonce(($announcement_categorie == "none" ? '' : ' AND ATT_GRP.nomgroupeattribut=\''.$announcement_categorie.'\' ') , DEFAULT_FLAG_ADMIN_AOS , 0 , 'nolimit');
						$i = 0;
						foreach($attribut_listing as $key => $content)
						{
							if($i == 0)
							{
								echo '<tr>';
							}

							echo '<td><input type="checkbox" name="attribut_'.$content->labelattribut.'" id="'.$content->labelattribut.'" value="'.$content->labelattribut.'" onclick="javascript:add_column_to_export_strucure(\'import_structure\',\''.$content->labelattribut.'\',\'\');" /><label for="'.$content->labelattribut.'" >'.$content->nomattribut.'</label></td>';
							$i++;

							if(($i == 5) || ($i == count($attribut_listing)))
							{
								echo '</tr>';
								$i=0;
							}
						}
					?>
				</table>
				<center>
					<input type="hidden" name="step" value="<?php echo (($file_type == "csv")? '2' : '3') ?>" />
					<input type="hidden" name="file_type" value="<?php echo $file_type; ?>" />
					<input type="hidden" name="announcement_categorie" value="<?php echo $announcement_categorie; ?>" />
					<input type="hidden" name="import_separateurtexte" value="<?php echo $tools->slugify_noaccent_no_utf8decode($import_separateurtexte); ?>" />
					<input type="hidden" name="import_separateurchamp" value="<?php echo $tools->slugify_noaccent_no_utf8decode($import_separateurchamp); ?>" />
					<input type="hidden" name="import_separateurligne" value="<?php echo $tools->slugify_noaccent_no_utf8decode($import_separateurligne); ?>" />
					<input type="submit" name="exporter" value="<?php _e('Suivant','annonces') ?>" />
				</center>
			</form>
<?php
		break;
		case 2:
			$attributs = split(',',$import_structure);
			if(count($attributs) <= 1)
			{
				echo '<label>'.__('Attention aucun attribut n\'a &eacute;t&eacute; s&eacute;lectionn&eacute;.','annonces').'</lable>';
			}
			if($file_type == "csv")
			{
			?>
			<form name="export_annonce" action="" method="post" >
				<table class="annonce_form">
					<tbody>
						<tr>
							<th>
								<label for="import_separateurtexte"><?php _e('S&eacute;parateur de texte','annonces') ?></label>
							</th>
							<td>
								<input id="import_separateurtexte" type="text" name="import_separateurtexte" value="<?php echo $import_separateurtexte; ?>"/><label class="lblannonceform"><?php _e('(exemple: ").','annonces') ?></label>
							</td>
							
						</tr>
						<tr>
							<th>
								<label for="import_separateurchamp"><?php _e('S&eacute;parateur de champ','annonces') ?></label>
							</th>
							<td>
								<input id="import_separateurchamp" type="text" name="import_separateurchamp" value="<?php echo $import_separateurchamp;  ?>"/><label class="lblannonceform"><?php _e('(exemple: ,).','annonces') ?></label>
							</td>
							
						</tr>
						<tr>
							<th>
								<label for="import_separateurligne"><?php _e('S&eacute;parateur de ligne','annonces') ?></label>
							</th>
							<td>
								<input id="import_separateurligne" type="text" name="import_separateurligne" value="<?php echo $import_separateurligne; ?>"/>
							</td>
						</tr>
					</tbody>
				</table>
				<center class="center_export_annonce">
					<input type="hidden" name="step" value="<?php echo ((count($attributs) <= 1) ? '1' : '4' ) ?>" />
					<input type="hidden" name="import_structure" value="<?php echo $import_structure; ?>" />
					<input type="hidden" name="announcement_categorie" value="<?php echo $announcement_categorie; ?>" />
					<input type="hidden" name="file_type" value="<?php echo $file_type; ?>" />
					<input type="submit" name="exporter" value="<?php echo ((count($attributs) <= 1) ? _e('Retour','annonces') : _e('Suivant','annonces') ) ?>" />
				</center>
			</form>
			<?php
			}
		break;
		case 3:
			$attributs = split(',',$import_structure);
			if(count($attributs) <= 1)
			{
				echo '<label>'.__('Attention aucun attribut n\'a &eacute;t&eacute; s&eacute;lectionn&eacute;.','annonces').'</lable>';
			}
			if($file_type == "xls")
			{
			?>
				<form name="export_annonce" action="" method="post" >
					<center>
						<input type="hidden" name="step" value="<?php echo ((count($attributs) <= 1) ? '1' : '5' ) ?>" />
						<input type="hidden" name="import_structure" value="<?php echo $import_structure; ?>" />
						<input type="hidden" name="announcement_categorie" value="<?php echo $announcement_categorie; ?>" />
						<input type="hidden" name="file_type" value="<?php echo $file_type; ?>" />
						<input type="submit" name="exporter" value="<?php echo ((count($attributs) <= 1) ? _e('Retour','annonces') : _e('Suivant','annonces') ) ?>" />
					</center>
				</form>
			<?php
			}
		break;
		case 4:
			echo '<label>'.__('Vous &ecirc;tes d\'importer un fichier CSV avec les param&egrave;tres suivant:','annonces').'</label><br/>';
			echo '<label>'.__('S&eacute;parateur de texte:','annonces').'</label>&nbsp;'.$import_separateurtexte.'<br/>';
			echo '<label>'.__('S&eacute;parateur de champ:','annonces').'</label>&nbsp;'.$import_separateurchamp.'<br/>';
			echo '<label>'.__('S&eacute;parateur de ligne:','annonces').'</label>&nbsp;'.$import_separateurligne.'<br/>';
			echo '<label>'.__('Attribut pr&eacute;sent dans le fichier:','annonces').'</label>&nbsp;'.$import_structure.'<br/>';
?>
			<form name="export_annonce" action="" method="post"  enctype="multipart/form-data">
				<label><?php echo _e('Fichier &agrave; importer:','annonces') ?>&nbsp;</label><input id="file_to_import" type="file" name="file_to_import"/>
				<center>
					<input type="hidden" name="step" value="8" />
					<input type="hidden" name="import_structure" value="<?php echo $import_structure; ?>" />
					<input type="hidden" name="announcement_categorie" value="<?php echo $announcement_categorie; ?>" />
					<input type="hidden" name="file_type" value="<?php echo $file_type; ?>" />
					<input type="hidden" name="import_separateurtexte" value="<?php echo $tools->slugify_noaccent_no_utf8decode($import_separateurtexte); ?>" />
					<input type="hidden" name="import_separateurchamp" value="<?php echo $tools->slugify_noaccent_no_utf8decode($import_separateurchamp); ?>" />
					<input type="hidden" name="import_separateurligne" value="<?php echo $tools->slugify_noaccent_no_utf8decode($import_separateurligne); ?>" />
					<input type="submit" name="exporter" value="<?php echo _e('Importer','annonces') ?>" />
				</center>
			</form>
<?php
		break;
		case 5:
			echo '<label>'.__('Vous &ecirc;tes sur le point d\'importer un fichier EXCEL avec les param&egrave;tres suivant:','annonces').'</label><br/>';
			echo '<label>'.__('Attribut pr&eacute;sent dans le fichier:','annonces').'</lable>&nbsp;'.$import_structure.'<br/><br/>';
?>
			<form name="export_annonce" action="" method="post"  enctype="multipart/form-data">
				<label><?php echo _e('Fichier &agrave; importer:','annonces') ?>&nbsp;</label><input id="file_to_import" type="file" name="file_to_import"/>
				<center>
					<input type="hidden" name="step" value="6" />
					<input type="hidden" name="import_structure" value="<?php echo $import_structure; ?>" />
					<input type="hidden" name="announcement_categorie" value="<?php echo $announcement_categorie; ?>" />
					<input type="hidden" name="file_type" value="<?php echo $file_type; ?>" />
					<input type="submit" name="exporter" value="<?php echo _e('Importer','annonces') ?>" />
				</center>
			</form>
<?php
		break;
		case 6:
			echo '<center><b>En cours de d&eacute;veloppement.(Import en cours)</b></center>';
			if(isset($_FILES['file_to_import']))
			{
				$extensions = array(".xls");
				if (is_uploaded_file($_FILES['file_to_import']['tmp_name']))
				{
					$file = $_FILES['file_to_import']['name'];
					$extension = strtolower(strrchr($file,'.'));
					if(in_array($extension,$extensions))
					{
						$path_file = WP_PLUGIN_DIR.'/'.ANNONCES_PLUGIN_DIR.'/medias/uploads/';
						if(move_uploaded_file($_FILES['file_to_import']['tmp_name'],$path_file.$file))
						{
							$data = new Spreadsheet_Excel_Reader();
							$data->setOutputEncoding('utf-8');
							$data->read($path_file.$file);
							error_reporting(E_ALL ^ E_NOTICE);
							
							//Retain names of field 
							$fields_name = split(',',$import_structure);
							$fields_name_size = count($fields_name) - 1;
							
							$colonne = array();
							$check_geolocalisation = false;
							for($i = 0;$i < $fields_name_size; $i++){
								$colonne[($fields_name[$i] == "Ville" ?strtolower($fields_name[$i]):$fields_name[$i])] = $i + 1;//First column begin with 1
								if($fields_name[$i] == "Ville" OR $fields_name[$i] == "Pays" OR $fields_name[$i] == "Adresse" OR $fields_name[$i] == "CP")
								{
									$check_geolocalisation = true;
								}
							}
							
							//Retain the progress reading of the file when we load the script
							if(!empty($progress)){
								$init = $progress;
							}else{
								$init = 1;
							}
							
							if(empty($id_announcement_category))
							{
								$category = new attribut_group();
								$sql = $category->get_attribut_group((empty($announcement_categorie) ? '' : ' AND ATT_GRP.nomgroupeattribut=\''.$announcement_categorie.'\' ') , DEFAULT_FLAG_ADMIN_AOS , 0 , 'nolimit');
								$id_announcement_category = $sql[0]->idgroupeattribut;
							}
							
							$size = $data->sheets[0]['numRows'];
							//Mode intervient lorsque le modulo arrive � 0 et qui nous faisons une redirection
							//Mode=true empeche la redirection (Prend tout son sens dans le case suivant: 7) 
							$mode = true;
							echo '<b>Rapport&nbsp;:</b><br/>';
							for ($i = $init; $i <= $size; $i++) {
								if((($i%5) != 0) OR $mode)
								{
									$mode = false;
									$announcement_data = array();
									$announcement_data['flagvalidpetiteannonce'] = 'valid';
									$announcement_data['idgroupeattribut'] = $id_announcement_category; 
									$announcement_data['aexporter'] = 'oui'; 
									$announcement_data['titre'] = __('Nouvelle annonce','annonces').' '.$i;
									
									foreach($colonne as $column => $number):
										$announcement_data[$column] = $data->sheets[0]['cells'][$i][$number];
									endforeach;

									if($check_geolocalisation)
									{
										$location = (empty($announcement_data['Adresse'])? '': $tools->slugify_nospace($announcement_data['Adresse']).'+').(empty($announcement_data['ville'])? '': $tools->slugify_nospace($announcement_data['ville']).'+').(empty($announcement_data['CP'])? '': $tools->slugify_nospace($announcement_data['CP']).'+').(empty($announcement_data['Pays'])? '': $tools->slugify_nospace($announcement_data['Pays']));
										$address = "http://maps.google.com/maps/geo?q=".$location."&output=xml";

										// Retrieve the URL contents*
										$page = file_get_contents($address);
										
										// Parse the returned XML file*
										$xml = simplexml_load_string(utf8_encode($page));
										
										if(!is_null($xml))
										{
											if(empty($announcement_data['ville'])){
												$announcement_data['ville'] = (string)$tools->slugify_noaccent_no_utf8decode($xml->Response->Placemark->AddressDetails->Country->AdministrativeArea->SubAdministrativeArea->Locality->LocalityName);
											}
											$announcement_data['departement'] = (string)$tools->slugify_noaccent_no_utf8decode($xml->Response->Placemark->AddressDetails->Country->AdministrativeArea->SubAdministrativeArea->SubAdministrativeAreaName);
											$announcement_data['region'] = (string)$tools->slugify_noaccent_no_utf8decode($xml->Response->Placemark->AddressDetails->Country->AdministrativeArea->SubAdministrativeArea->AdministrativeAreaName);
											
											$cp = explode(' ',$xml->Response->Placemark->address);
											if(empty($announcement_data['CP']))
											{
												$announcement_data['cp'] = (int)$cp[0];
											}
	
											if(empty($announcement_data['Pays']))
											{
												$announcement_data['pays'] = (string)$xml->Response->Placemark->AddressDetails->Country->CountryName;
											}
											$coord = explode(',',$xml->Response->Placemark->Point->coordinates);
											$announcement_data['latitude'] = (float)$coord[1];
											$announcement_data['longitude'] = (float)$coord[0];
										}
									}
									
									$announcement_data['autoinsert'] = date('Y-m-d H:i:s');
									$announcement_data['autolastmodif'] = date('Y-m-d H:i:s');
									
									$ctlg_annonce = new annonce();
									$result = $ctlg_annonce->create_annonce($announcement_data);
									echo '<hr/>'.$ctlg_annonce->error_message.'&nbsp;'.$announcement_data['ville'];
								}else{
									?>
									<form action="" method="post" name="progress_import">
										<input type="hidden" name="step" value="7" />
										<input type="hidden" name="progress" value="<?php echo $i; ?>" />
										<input type="hidden" name="file" value="<?php echo $file; ?>" />
										<input type="hidden" name="import_structure" value="<?php echo $import_structure; ?>" />
										<input type="hidden" name="announcement_categorie" value="<?php echo $announcement_categorie; ?>" />
										<input type="hidden" name="id_announcement_category" value="<?php echo $id_announcement_category; ?>" />
										<input type="hidden" name="file_type" value="<?php echo $file_type; ?>" />
									</form>
									<script type="text/javascript">document.progress_import.submit();</script>
									<?php
									exit;//Corrige bug terminaison de la boucle
								}
							}
						}else{echo __('Erreur sur le chemin du fichier.','annonces');}
					}else{echo __('Extension non compatible.','annonces');}
				}else{echo __('Erreur lors du chargement.','annonces');}
			}else{echo __('Fichier inconnu.','annonces');}
		break;
		case 7:
			$path_file = WP_PLUGIN_DIR.'/'.ANNONCES_PLUGIN_DIR.'/medias/uploads/';
			if(is_file($path_file.$file))
			{
				$data = new Spreadsheet_Excel_Reader();
				$data->setOutputEncoding('utf-8');
				$data->read($path_file.$file);
				error_reporting(E_ALL ^ E_NOTICE);
				
				//Retain names of field 
				$fields_name = split(',',$import_structure);
				$fields_name_size = count($fields_name) - 1;
				
				$colonne = array();
				$check_geolocalisation = false;
				for($i = 0;$i < $fields_name_size; $i++){
					$colonne[($fields_name[$i] == "Ville" ?strtolower($fields_name[$i]):$fields_name[$i])] = $i + 1;//First column begin with 1
					if($fields_name[$i] == "Ville" OR $fields_name[$i] == "Pays" OR $fields_name[$i] == "Adresse" OR $fields_name[$i] == "CP")
					{
						$check_geolocalisation = true;
					}
				}
				
				//Retain the progress reading of the file when we load the script
				if(!empty($progress)){
					$init = $progress;
				}else{
					$init = 1;
				}
				
				if(empty($id_announcement_category))
				{
					$category = new attribut_group();
					$sql = $category->get_attribut_group((empty($announcement_categorie) ? '' : ' AND ATT_GRP.nomgroupeattribut=\''.$announcement_categorie.'\' ') , DEFAULT_FLAG_ADMIN_AOS , 0 , 'nolimit');
					$id_announcement_category = $sql[0]->idgroupeattribut;
				}
				
				$size = $data->sheets[0]['numRows'];
				//Mode intervient lorsque le modulo arrive � 0 et qui nous faisons une redirection
				//Mode=true empeche la redirection 
				$mode = true;
				echo '<b>Rapport&nbsp;:</b><br/>';
				for ($i = $init; $i <= $size; $i++) {
					if((($i%5) != 0) OR $mode)
					{
						$mode = false;
						$announcement_data['flagvalidpetiteannonce'] = 'valid';
						$announcement_data['idgroupeattribut'] = $id_announcement_category; 
						$announcement_data['aexporter'] = 'oui'; 
						$announcement_data['titre'] = __('Nouvelle annonce','annonces').' '.$i;
						foreach($colonne as $column => $number):
							$announcement_data[$column] = $data->sheets[0]['cells'][$i][$number];
						endforeach;

						if($check_geolocalisation)
						{
							$location = (empty($announcement_data['Adresse'])? '': $tools->slugify_nospace($announcement_data['Adresse']).'+').(empty($announcement_data['ville'])? '': $tools->slugify_nospace($announcement_data['ville']).'+').(empty($announcement_data['CP'])? '': $tools->slugify_nospace($announcement_data['CP']).'+').(empty($announcement_data['Pays'])? '': $tools->slugify_nospace($announcement_data['Pays']));
							$address = "http://maps.google.com/maps/geo?q=".$location."&output=xml";

							// Retrieve the URL contents*
							$page = file_get_contents($address);
										
							// Parse the returned XML file*
							$xml = simplexml_load_string(utf8_encode($page));
										
							if(!is_null($xml))
							{
								if(empty($announcement_data['ville'])){
									$announcement_data['ville'] = (string)$tools->slugify_noaccent_no_utf8decode($xml->Response->Placemark->AddressDetails->Country->AdministrativeArea->SubAdministrativeArea->Locality->LocalityName);
								}
								$announcement_data['departement'] = (string)$tools->slugify_noaccent_no_utf8decode($xml->Response->Placemark->AddressDetails->Country->AdministrativeArea->SubAdministrativeArea->SubAdministrativeAreaName);
								$announcement_data['region'] = (string)$tools->slugify_noaccent_no_utf8decode($xml->Response->Placemark->AddressDetails->Country->AdministrativeArea->SubAdministrativeArea->AdministrativeAreaName);
											
								$cp = explode(' ',$xml->Response->Placemark->address);
								if(empty($announcement_data['CP']))
								{
									$announcement_data['cp'] = (int)$cp[0];
								}
	
								if(empty($announcement_data['Pays']))
								{
									$announcement_data['pays'] = (string)$xml->Response->Placemark->AddressDetails->Country->CountryName;
								}
								$coord = explode(',',$xml->Response->Placemark->Point->coordinates);
								$announcement_data['latitude'] = (float)$coord[1];
								$announcement_data['longitude'] = (float)$coord[0];
							}
						}
						
						$announcement_data['autoinsert'] = date('Y-m-d H:i:s');
						$announcement_data['autolastmodif'] = date('Y-m-d H:i:s');
						
						$ctlg_annonce = new annonce();
						$result = $ctlg_annonce->create_annonce($announcement_data);
						echo '<hr/>'.$ctlg_annonce->error_message.'&nbsp;'.$announcement_data['ville'];
					}else{
						?>
						<form action="" method="post" name="progress_import">
							<input type="hidden" name="step" value="7" />
							<input type="hidden" name="progress" value="<?php echo $i; ?>" />
							<input type="hidden" name="file" value="<?php echo $file; ?>" />
							<input type="hidden" name="import_structure" value="<?php echo $import_structure; ?>" />
							<input type="hidden" name="announcement_categorie" value="<?php echo $announcement_categorie; ?>" />
							<input type="hidden" name="id_announcement_category" value="<?php echo $id_announcement_category; ?>" />
						</form>
						<script type="text/javascript">document.progress_import.submit();</script>
						<?php
						exit;//Corrige bug terminaison de la boucle
					}
				}
			}else{echo __('Erreur sur le chemin du fichier.','annonces');}
		break;
		case 8:
			echo '<center><b>En cours de d&eacute;veloppement.(Import en cours)</b></center>';

			if(isset($_FILES['file_to_import']))
			{
				$extensions = array(".csv");
				if (is_uploaded_file($_FILES['file_to_import']['tmp_name']))
				{
					$file = $_FILES['file_to_import']['name'];
					$extension = strtolower(strrchr($file,'.'));
					if(in_array($extension,$extensions))
					{
						$path_file = WP_PLUGIN_DIR.'/'.ANNONCES_PLUGIN_DIR.'/medias/uploads/';
						if(move_uploaded_file($_FILES['file_to_import']['tmp_name'],$path_file.$file))
						{
							$import_file = new Csv($path_file.$file,$import_separateurchamp,$import_separateurtexte);
							
							//Retain names of field 
							$fields_name = split(',',$import_structure);
							$fields_name_size = count($fields_name) - 1;
							
							$colonne = array();
							$check_geolocalisation = false;
							for($i = 0;$i < $fields_name_size; $i++){
								$colonne[($fields_name[$i] == "Ville" ?strtolower($fields_name[$i]):$fields_name[$i])] = $i;
								if($fields_name[$i] == "Ville" OR $fields_name[$i] == "Pays" OR $fields_name[$i] == "Adresse" OR $fields_name[$i] == "CP")
								{
									$check_geolocalisation = true;
								}
							}
							
							//Retain the progress reading of the file when we load the script
							if(!empty($progress)){
								$init = $progress;
							}else{
								$init = 0;
							}
							
							if(empty($id_announcement_category))
							{
								$category = new attribut_group();
								$sql = $category->get_attribut_group((empty($announcement_categorie) ? '' : ' AND ATT_GRP.nomgroupeattribut=\''.$announcement_categorie.'\' ') , DEFAULT_FLAG_ADMIN_AOS , 0 , 'nolimit');
								$id_announcement_category = $sql[0]->idgroupeattribut;
							}
							
							$size = $import_file->getNumberLine();
							//Mode intervient lorsque le modulo arrive � 0 et qui nous faisons une redirection
							//Mode=true empeche la redirection (Prend tout son sens dans le case suivant: 7) 
							$mode = true;
							echo '<b>Rapport&nbsp;:</b><br/>';
							for ($i = $init; $i < $size; $i++) {
								if((($i%5) != 0) OR $mode)
								{
									$mode = false;
									$announcement_data = array();
									$announcement_data['flagvalidpetiteannonce'] = 'valid';
									$announcement_data['idgroupeattribut'] = $id_announcement_category; 
									$announcement_data['aexporter'] = 'oui'; 
									$announcement_data['titre'] = __('Nouvelle annonce','annonces').' '.$i;
							
									foreach($colonne as $column => $number):
										$announcement_data[$column] = $import_file->getCell($i,$number);
									endforeach;
			
									if($check_geolocalisation)
									{
										$location = (empty($announcement_data['Adresse'])? '': $tools->slugify_nospace($announcement_data['Adresse']).'+').(empty($announcement_data['ville'])? '': $tools->slugify_nospace($announcement_data['ville']).'+').(empty($announcement_data['CP'])? '': $tools->slugify_nospace($announcement_data['CP']).'+').(empty($announcement_data['Pays'])? '': $tools->slugify_nospace($announcement_data['Pays']));
										$address = "http://maps.google.com/maps/geo?q=".$location."&output=xml";

										// Retrieve the URL contents*
										$page = file_get_contents($address);
										
										// Parse the returned XML file*
										$xml = simplexml_load_string(utf8_encode($page));
										
										if(!is_null($xml))
										{
											if(empty($announcement_data['ville'])){
												$announcement_data['ville'] = (string)$tools->slugify_noaccent_no_utf8decode($xml->Response->Placemark->AddressDetails->Country->AdministrativeArea->SubAdministrativeArea->Locality->LocalityName);
											}
											$announcement_data['departement'] = (string)$tools->slugify_noaccent_no_utf8decode($xml->Response->Placemark->AddressDetails->Country->AdministrativeArea->SubAdministrativeArea->SubAdministrativeAreaName);
											$announcement_data['region'] = (string)$tools->slugify_noaccent_no_utf8decode($xml->Response->Placemark->AddressDetails->Country->AdministrativeArea->SubAdministrativeArea->AdministrativeAreaName);
											
											$cp = explode(' ',$xml->Response->Placemark->address);
											if(empty($announcement_data['CP']))
											{
												$announcement_data['cp'] = (int)$cp[0];
											}
	
											if(empty($announcement_data['Pays']))
											{
												$announcement_data['pays'] = (string)$xml->Response->Placemark->AddressDetails->Country->CountryName;
											}
											$coord = explode(',',$xml->Response->Placemark->Point->coordinates);
											$announcement_data['latitude'] = (float)$coord[1];
											$announcement_data['longitude'] = (float)$coord[0];
										}
									}

									$announcement_data['autoinsert'] = date('Y-m-d H:i:s');
									$announcement_data['autolastmodif'] = date('Y-m-d H:i:s');
									
									$ctlg_annonce = new annonce();
									$result = $ctlg_annonce->create_annonce($announcement_data);
									echo '<hr/>'.$ctlg_annonce->error_message.'&nbsp;'.$announcement_data['ville'];
								}else{
									?>
									<form action="" method="post" name="progress_import">
										<input type="hidden" name="step" value="9" />
										<input type="hidden" name="progress" value="<?php echo $i; ?>" />
										<input type="hidden" name="file" value="<?php echo $file; ?>" />
										<input type="hidden" name="import_structure" value="<?php echo $import_structure; ?>" />
										<input type="hidden" name="import_separateurtexte" value="<?php echo $tools->slugify_noaccent_no_utf8decode($import_separateurtexte); ?>" />
										<input type="hidden" name="import_separateurchamp" value="<?php echo $tools->slugify_noaccent_no_utf8decode($import_separateurchamp); ?>" />
										<input type="hidden" name="import_separateurligne" value="<?php echo $tools->slugify_noaccent_no_utf8decode($import_separateurligne); ?>" />
										<input type="hidden" name="announcement_categorie" value="<?php echo $announcement_categorie; ?>" />
										<input type="hidden" name="id_announcement_category" value="<?php echo $id_announcement_category; ?>" />
										<input type="hidden" name="file_type" value="<?php echo $file_type; ?>" />
									</form>
									<script type="text/javascript">document.progress_import.submit();</script>
									<?php
									exit;//Corrige bug terminaison de la boucle
								}
							}
						
						}else{echo __('Erreur sur le chemin du fichier.','annonces');}
					}else{echo __('Extension non compatible.','annonces');}
				}else{echo __('Erreur lors du chargement.','annonces');}
			}else{echo __('Fichier inconnu.','annonces');}
		break;
		case 9:
			$path_file = WP_PLUGIN_DIR.'/'.ANNONCES_PLUGIN_DIR.'/medias/uploads/';
			if(is_file($path_file.$file))
			{
				$import_file = new Csv($path_file.$file,$import_separateurchamp,$import_separateurtexte);
				
				//Retain names of field 
				$fields_name = split(',',$import_structure);
				$fields_name_size = count($fields_name) - 1;
				
				$colonne = array();
				$check_geolocalisation = false;
				for($i = 0;$i < $fields_name_size; $i++){
					$colonne[($fields_name[$i] == "Ville" ?strtolower($fields_name[$i]):$fields_name[$i])] = $i;
					if($fields_name[$i] == "Ville" OR $fields_name[$i] == "Pays" OR $fields_name[$i] == "Adresse" OR $fields_name[$i] == "CP")
					{
						$check_geolocalisation = true;
					}
				}
				
				//Retain the progress reading of the file when we load the script
				if(!empty($progress)){
					$init = $progress;
				}else{
					$init = 0;
				}
				
				if(empty($id_announcement_category))
				{
					$category = new attribut_group();
					$sql = $category->get_attribut_group((empty($announcement_categorie) ? '' : ' AND ATT_GRP.nomgroupeattribut=\''.$announcement_categorie.'\' ') , DEFAULT_FLAG_ADMIN_AOS , 0 , 'nolimit');
					$id_announcement_category = $sql[0]->idgroupeattribut;
				}

				$size = $import_file->getNumberLine();
				//Mode intervient lorsque le modulo arrive � 0 et qui nous faisons une redirection
				//Mode=true empeche la redirection (Prend tout son sens dans le case suivant: 7) 
				$mode = true;
				echo '<b>Rapport&nbsp;:</b><br/>';
				for ($i = $init; $i < $size; $i++) {
					if((($i%5) != 0) OR $mode)
					{
						$mode = false;
						$announcement_data = array();
						$announcement_data['flagvalidpetiteannonce'] = 'valid';
						$announcement_data['idgroupeattribut'] = $id_announcement_category; 
						$announcement_data['aexporter'] = 'oui'; 
						$announcement_data['titre'] = __('Nouvelle annonce','annonces').' '.$i;
				
						foreach($colonne as $column => $number):
							$announcement_data[$column] = $import_file->getCell($i,$number);
						endforeach;
						if($check_geolocalisation)
						{
							$location = (empty($announcement_data['Adresse'])? '': $tools->slugify_nospace($announcement_data['Adresse']).'+').(empty($announcement_data['ville'])? '': $tools->slugify_nospace($announcement_data['ville']).'+').(empty($announcement_data['CP'])? '': $tools->slugify_nospace($announcement_data['CP']).'+').(empty($announcement_data['Pays'])? '': $tools->slugify_nospace($announcement_data['Pays']));
							$address = "http://maps.google.com/maps/geo?q=".$location."&output=xml";

							// Retrieve the URL contents*
							$page = file_get_contents($address);
										
							// Parse the returned XML file*
							$xml = simplexml_load_string(utf8_encode($page));
							
							if(!is_null($xml))
							{
								if(empty($announcement_data['ville'])){
									$announcement_data['ville'] = (string)$tools->slugify_noaccent_no_utf8decode($xml->Response->Placemark->AddressDetails->Country->AdministrativeArea->SubAdministrativeArea->Locality->LocalityName);
								}
								$announcement_data['departement'] = (string)$tools->slugify_noaccent_no_utf8decode($xml->Response->Placemark->AddressDetails->Country->AdministrativeArea->SubAdministrativeArea->SubAdministrativeAreaName);
								$announcement_data['region'] = (string)$tools->slugify_noaccent_no_utf8decode($xml->Response->Placemark->AddressDetails->Country->AdministrativeArea->SubAdministrativeArea->AdministrativeAreaName);
											
								$cp = explode(' ',$xml->Response->Placemark->address);
								if(empty($announcement_data['CP']))
								{
									$announcement_data['cp'] = (int)$cp[0];
								}
								if(empty($announcement_data['Pays']))
								{
									$announcement_data['pays'] = (string)$xml->Response->Placemark->AddressDetails->Country->CountryName;
								}
								$coord = explode(',',$xml->Response->Placemark->Point->coordinates);
								$announcement_data['latitude'] = (float)$coord[1];
								$announcement_data['longitude'] = (float)$coord[0];
							}
						}

						$announcement_data['autoinsert'] = date('Y-m-d H:i:s');
						$announcement_data['autolastmodif'] = date('Y-m-d H:i:s');
						
						$ctlg_annonce = new annonce();
						$result = $ctlg_annonce->create_annonce($announcement_data);
						echo '<hr/>'.$ctlg_annonce->error_message.'&nbsp;'.$announcement_data['ville'];
					}else{
						?>
						<form action="" method="post" name="progress_import">
							<input type="hidden" name="step" value="9" />
							<input type="hidden" name="progress" value="<?php echo $i; ?>" />
							<input type="hidden" name="file" value="<?php echo $file; ?>" />
							<input type="hidden" name="import_structure" value="<?php echo $import_structure; ?>" />
							<input type="hidden" name="announcement_categorie" value="<?php echo $announcement_categorie; ?>" />
							<input type="hidden" name="id_announcement_category" value="<?php echo $id_announcement_category; ?>" />
							<input type="hidden" name="import_separateurtexte" value="<?php echo $tools->slugify_noaccent_no_utf8decode($import_separateurtexte); ?>" />
							<input type="hidden" name="import_separateurchamp" value="<?php echo $tools->slugify_noaccent_no_utf8decode($import_separateurchamp); ?>" />
							<input type="hidden" name="import_separateurligne" value="<?php echo $tools->slugify_noaccent_no_utf8decode($import_separateurligne); ?>" />
							<input type="hidden" name="file_type" value="<?php echo $file_type; ?>" />
						</form>
						<script type="text/javascript">document.progress_import.submit();</script>
						<?php
						exit;//Corrige bug terminaison de la boucle
					}
				}

			}
		break;
		default:
?>
			<form name="export_annonce" action="" method="post" >
				<table summary="<?php _e('choix du type de fichier pour l\'import des annonces','annonces') ?>" cellpadding="0" cellspacing="0" class="table_export_annonce" > 
					<tr>
						<td>
							<?php _e('Choix du type de fichier contenant les annonces','annonces') ?>&nbsp;:&nbsp;
						</td>
						<td>
							<select id="file_type" name="file_type" >
								<option value="xls" ><?php _e('Fichier Excel','annonces') ?></option>
								<option value="csv" ><?php _e('Fichier CSV','annonces') ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<?php _e('Choix de la cat&eacute;gorie des annonces &agrave; importer','annonces') ?>&nbsp;:&nbsp;<br/>
							<label class="lblannonceform">
								<?php _e('*Si aucune cat&eacute;gorie n\'est s&eacute;lectionn&eacute;e, tout les attributs seront affich&eacute;s.','annonces'); ?>
							</label>
						</td>
						<td>
							<select id="announcement_categorie" name="announcement_categorie" >
								<option value="none" ><?php _e('Aucune','annonces') ?></option>
								<?php
									foreach($attribute_group_possibilities as $category):
										echo '<option value="'.$category.'" >'.__($category,'annonces').'</option>';
									endforeach;
								?>
							</select>
						</td>
					</tr>
				</table>
				<center>
					<input type="hidden" name="step" value="1" />
					<input type="submit" name="exporter" value="<?php _e('Suivant','annonces') ?>" />
				</center>
			</form>
<?php
		break;
	}
}
else{
?>
<form name="export_annonce" action="" method="post" >

	<table summary="<?php _e('choix du type de fichier pour l\'import des annonces','annonces') ?>" cellpadding="0" cellspacing="0" class="table_export_annonce" > 
		<tr>
			<td>
				<?php _e('Choix du type de fichier contenant les annonces','annonces') ?>&nbsp;:&nbsp;
			</td>
			<td>
				<select id="file_type" name="file_type" >
					<option value="xls" ><?php _e('Fichier Excel','annonces') ?></option>
					<option value="csv" ><?php _e('Fichier CSV','annonces') ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<?php _e('Choix de la cat&eacute;gorie des annonces &agrave; importer&sup1;','annonces') ?>&nbsp;:&nbsp;<br/>
				<?php _e('&sup1;','annonces'); ?>
				<label class="lblannonceform">
					<?php _e('Si aucune cat&eacute;gorie n\'est s&eacute;lectionn&eacute;e, tout les attributs seront affich&eacute;s.','annonces'); ?>
				</label>
			</td>
			<td>
				<select id="announcement_categorie" name="announcement_categorie" >
					<option value="none" ><?php _e('Aucune','annonces') ?></option>
					<?php
						foreach($attribute_group_possibilities as $category):
							echo '<option value="'.$category.'" >'.__($category,'annonces').'</option>';
						endforeach;
					?>
				</select>
			</td>
		</tr>
	</table>
	<center><input type="hidden" name="step" value="1" /><input type="submit" name="exporter" value="<?php _e('Suivant','annonces') ?>" /></center>
</form>
<?php
}
?>