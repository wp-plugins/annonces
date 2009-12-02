<?php
	require_once('../../../../wp-config.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8;">
		<script type="text/javascript" >
			var id = 0;
			var check = true;
			function AddSubElement_frame(wheretoadd, input_file_name, form)
			{	
				if(check){
					id++;
					check = false;
					var d=document.createElement("div");
					d.id="dynamic_file";
					var i=document.createElement("input");	// ajout input file
					i.type="file";
					i.id= input_file_name+id;
					i.name= input_file_name;
					i.size=34;
					i.onchange=function(){
						form.submit();
						window.top.document.getElementById("gmlb_close").style.display = 'none';
					}

					d.appendChild(i);
					
					var b=document.createElement("input");	// ajout du bouton pour supprimer
					b.type="button";
					b.value="<?php _e('Annuler','annonces') ?>";
					b.onclick=function(){
						this.parentNode.style.display="none";
						check = true;
						//document.getElementById('preview_marker').style.display = 'block';
					}
					d.appendChild(b);

					document.getElementById(wheretoadd).appendChild(d);
				}
			}
		</script>
	</head>

	<body>
<?php
	if(isset($_POST)){
		
		if(isset($_FILES['theme']['tmp_name']))
		{
			$extensions = array(".png", ".jpg", ".bmp");
			if (is_uploaded_file($_FILES['theme']['tmp_name']['change_bouton_recherche']))
			{
				$img = $_FILES['theme']['name']['change_bouton_recherche'];
				$extension = strtolower(strrchr($img,'.'));
				if(in_array($extension,$extensions)){
					if(move_uploaded_file($_FILES['theme']['tmp_name']['change_bouton_recherche'],WP_PLUGIN_DIR.'/'.Basename_Dirname_AOS.'/medias/images/'.$img))
					{
						update_option('url_recherche_theme_perso',$img);
					}
				}
			}
			if (is_uploaded_file($_FILES['theme']['tmp_name']['change_bouton_superfice']))
			{
				$img = $_FILES['theme']['name']['change_bouton_superfice'];
				$extension = strtolower(strrchr($img,'.'));
				if(in_array($extension,$extensions)){
					if(move_uploaded_file($_FILES['theme']['tmp_name']['change_bouton_superfice'],WP_PLUGIN_DIR.'/'.Basename_Dirname_AOS.'/medias/images/'.$img))
					{
						update_option('url_superficie_theme_perso',$img);
					}
				}
			}
			if (is_uploaded_file($_FILES['theme']['tmp_name']['change_bouton_budget']))
			{
				$img = $_FILES['theme']['name']['change_bouton_budget'];
				$extension = strtolower(strrchr($img,'.'));
				if(in_array($extension,$extensions)){
					if(move_uploaded_file($_FILES['theme']['tmp_name']['change_bouton_budget'],WP_PLUGIN_DIR.'/'.Basename_Dirname_AOS.'/medias/images/'.$img))
					{
						update_option('url_budget_theme_perso',$img);
					}
				}
			}
			if (is_uploaded_file($_FILES['theme']['tmp_name']['change_bouton_maisons']))
			{
				$img = $_FILES['theme']['name']['change_bouton_maisons'];
				$extension = strtolower(strrchr($img,'.'));
				if(in_array($extension,$extensions)){
					if(move_uploaded_file($_FILES['theme']['tmp_name']['change_bouton_maisons'],WP_PLUGIN_DIR.'/'.Basename_Dirname_AOS.'/medias/images/'.$img))
					{
						update_option('url_radio_maisons_theme_perso',$img);
					}
				}
			}
			if (is_uploaded_file($_FILES['theme']['tmp_name']['change_bouton_terrains']))
			{
				$img = $_FILES['theme']['name']['change_bouton_terrains'];
				$extension = strtolower(strrchr($img,'.'));
				if(in_array($extension,$extensions)){
					if(move_uploaded_file($_FILES['theme']['tmp_name']['change_bouton_terrains'],WP_PLUGIN_DIR.'/'.Basename_Dirname_AOS.'/medias/images/'.$img))
					{
						update_option('url_radio_terrains_theme_perso',$img);
					}
				}
			}
			if (is_uploaded_file($_FILES['theme']['tmp_name']['change_bouton_toutes']))
			{
				$img = $_FILES['theme']['name']['change_bouton_toutes'];
				$extension = strtolower(strrchr($img,'.'));
				if(in_array($extension,$extensions)){
					if(move_uploaded_file($_FILES['theme']['tmp_name']['change_bouton_toutes'],WP_PLUGIN_DIR.'/'.Basename_Dirname_AOS.'/medias/images/'.$img))
					{
						update_option('url_radio_toutes_theme_perso',$img);
					}
				}
			}
		}
		echo "<script>window.top.document.getElementById('gmlb_close').style.display = 'block';</script>";
	}
?>
		<form name="annonces_theme_form" action="" method="post" enctype="multipart/form-data" >
			<div class='carte-annonce'>
				<h4>&#149;&nbsp;<?php _e('Type d&#146;annonce','annonces') ?></h4>
				<div class='filtre'>
					<label for='toutes' style='cursor:pointer;'>
						<img src="<?php echo WP_PLUGIN_URL.'/'.Basename_Dirname_AOS.'/medias/images/'.get_option('url_radio_toutes_theme_perso'); ?>" style='margin-right:4px;vertical-align:middle;width:43px;height:43px;' onclick="AddSubElement_frame('btn_filtre', 'theme[change_bouton_toutes]' , document.forms.annonces_theme_form,'Cancel');" alt='<?php _e('Toutes','annonces') ?>'/>
					</label>
					<input type='radio' id='toutes' name='mode'  value='all'>
					<label for='toutes' style='cursor:pointer;'><?php _e('Toutes','annonces') ?>&nbsp;</label>
					<label for='terrains' style='cursor:pointer;'>
						<img src="<?php echo WP_PLUGIN_URL.'/'.Basename_Dirname_AOS.'/medias/images/'.get_option('url_radio_terrains_theme_perso'); ?>" style='margin-right:4px;vertical-align:middle;width:43px;height:43px;' onclick="AddSubElement_frame('btn_filtre', 'theme[change_bouton_terrains]' , document.forms.annonces_theme_form);" alt='<?php _e('Terrains','annonces') ?>'/>
					</label>
					<input type='radio' id='terrains' name='mode'  value='terrain'>
					<label for='terrains' style='cursor:pointer;'><?php _e('Terrains','annonces') ?>&nbsp;</label>
					<label for='maisons' style='cursor:pointer;'>
						<img src="<?php echo WP_PLUGIN_URL.'/'.Basename_Dirname_AOS.'/medias/images/'.get_option('url_radio_maisons_theme_perso'); ?>" style='margin-right:4px;vertical-align:middle;width:43px;height:43px;' onclick="AddSubElement_frame('btn_filtre', 'theme[change_bouton_maisons]' , document.forms.annonces_theme_form);" alt='<?php _e('Maisons','annonces') ?>'/>
					</label>
					<input type='radio' id='maisons' name='mode' value='maison/villa'>
					<label for='maisons' style='cursor:pointer;'><?php _e('Maisons','annonces') ?></label>
					<br/>
				</div>
				<center><br/><div id="btn_filtre"></div></center>
				<h4>&#149;&nbsp;<?php _e('Filtre','annonces') ?></h4>
				<div class='budget'>
					<p>
						<img src="<?php echo WP_PLUGIN_URL.'/'.Basename_Dirname_AOS.'/medias/images/'.get_option('url_budget_theme_perso'); ?>" onclick="AddSubElement_frame('btn_budget', 'theme[change_bouton_budget]' , document.forms.annonces_theme_form);" style='float:left;margin-right:2px;cursor:pointer;width:44px;height:44px;' alt='Budget'/>
						<b>&nbsp;&nbsp;<?php _e('Votre budget','annonces') ?><div id="btn_budget" ></div></b>
					</p>
				</div>
				<br/>
				<div class='superficie'>
					<p>
						<img src="<?php echo WP_PLUGIN_URL.'/'.Basename_Dirname_AOS.'/medias/images/'.get_option('url_superficie_theme_perso'); ?>" onclick="AddSubElement_frame('btn_surface', 'theme[change_bouton_superfice]' , document.forms.annonces_theme_form);" style='float:left;margin-right:2px;cursor:pointer;width:44px;height:44px;' alt='Superficie'/>
						<b>&nbsp;&nbsp;<?php _e('Superficie terrain souhait&eacute;e','annonces') ?><div id="btn_surface" ></div></b>
					</p>
				</div>
				<br/>
				<h4>&#149;&nbsp;<?php _e('Recherche','annonces') ?></h4>
				<div>
					<table>	
						<tr>
							<th><input onclick="AddSubElement_frame('btn_recherche', 'theme[change_bouton_recherche]' , document.forms.annonces_theme_form);" style='border-width:0px;background:url(<?php echo WP_PLUGIN_URL.'/'.Basename_Dirname_AOS.'/medias/images/'.get_option('url_recherche_theme_perso'); ?>) no-repeat;width:168px;height:47px; cursor:pointer;' type='reset' value='' /></th> 
							<td>
								<div id="btn_recherche"></div>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</form>
	</body>
</html>