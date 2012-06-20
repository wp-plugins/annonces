<?php
	require_once('../../../../wp-config.php');
	require_once dirname(__FILE__).'./../includes/lib/options.class.php';
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
global $wpdb;

	if(isset($_POST)){
		echo "<script>window.top.document.getElementById('gmlb_close').style.display = 'block';</script>";
	}
?>
		<form name="annonces_theme_form" action="" method="post" enctype="multipart/form-data" >
			<div class='carte-annonce'>
				<h4>&#149;&nbsp;<?php _e('Type d\'annonce','annonces') ?></h4>
				<div class='filtretheme'>
					<label for='toutes' >
						<img src="<?php echo WP_CONTENT_URL . WAY_TO_PICTURES_AOS . url_radio_toutes_theme_courant; ?>" onclick="AddSubElement_frame('btn_filtre', 'theme[change_bouton_toutes]' , document.forms.annonces_theme_form,'Cancel');" alt='<?php _e('Toutes','annonces') ?>'/>
					</label>
					<input type='radio' id='toutes' name='mode'  value='all'>
					<label for='toutes' ><?php _e('Toutes','annonces') ?>&nbsp;</label>
					<label for='terrains' >
						<img src="<?php echo WP_CONTENT_URL . WAY_TO_PICTURES_AOS . url_radio_terrains_theme_courant; ?>" onclick="AddSubElement_frame('btn_filtre', 'theme[change_bouton_terrains]' , document.forms.annonces_theme_form);" alt='<?php _e('Terrains','annonces') ?>'/>
					</label>
					<input type='radio' id='terrains' name='mode'  value='terrain'>
					<label for='terrains' ><?php _e('Terrains','annonces') ?>&nbsp;</label>
					<label for='maisons' >
						<img src="<?php echo WP_CONTENT_URL . WAY_TO_PICTURES_AOS . url_radio_maisons_theme_courant; ?>" onclick="AddSubElement_frame('btn_filtre', 'theme[change_bouton_maisons]' , document.forms.annonces_theme_form);" alt='<?php _e('Maisons','annonces') ?>'/>
					</label>
					<input type='radio' id='maisons' name='mode' value='maison/villa'>
					<label for='maisons' ><?php _e('Maisons','annonces') ?></label>
					<br/>
				</div>
				<center><br/><div id="btn_filtre"></div></center>
				<h4>&#149;&nbsp;<?php _e('Filtre','annonces') ?></h4>
				<div class='budgettheme'>
					<p>
						<img src="<?php echo WP_CONTENT_URL . WAY_TO_PICTURES_AOS . url_budget_theme_courant; ?>" onclick="AddSubElement_frame('btn_budget', 'theme[change_bouton_budget]' , document.forms.annonces_theme_form);" alt='Budget'/>
						<b>&nbsp;&nbsp;<?php _e('Votre budget','annonces') ?><div id="btn_budget" ></div></b>
					</p>
				</div>
				<br/>
				<div class='superficietheme'>
					<p>
						<img src="<?php echo WP_CONTENT_URL . WAY_TO_PICTURES_AOS . url_superficie_theme_courant; ?>" onclick="AddSubElement_frame('btn_surface', 'theme[change_bouton_superfice]' , document.forms.annonces_theme_form);" alt='Superficie'/>
						<b>&nbsp;&nbsp;<?php _e('Superficie terrain souhait&eacute;e','annonces') ?><div id="btn_surface" ></div></b>
					</p>
				</div>
				<br/>
				<h4>&#149;&nbsp;<?php _e('Recherche','annonces') ?></h4>
				<div>
					<table class="irecherche">
						<tr>
							<th><img class="irecherche" onclick="AddSubElement_frame('btn_recherche', 'theme[change_bouton_recherche]' , document.forms.annonces_theme_form);" src="<?php echo WP_CONTENT_URL . WAY_TO_PICTURES_AOS . url_recherche_theme_courant; ?>" type='reset' value='' /></th> 
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