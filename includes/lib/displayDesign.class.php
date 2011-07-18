<?php
/**
 * This class contains the methods allowing to dipslay a classical screen of the pluggin
 * @author Annonces
 * @version v5.0
 */

class annonces_display
{

	/**
	* Returns the header display of a classical HTML page.
	* @see afficherFinPage
	* @param string $titrePage Title of the page.
	* @param string $icone Path of the icon.
	* @param string $titreIcone Title attribute of the icon.
	* @param string $altIcon Alt attribute of the icon.
	* @param string $table Table where the page is link.
	* @param bool $boutonAjouter Must the page have a button "Add" next to the title ?
	* @param string $messageInfo The information message.
	* @param bool $choixAffichage Must the page offer a choice of display ?
	* @return string HTML code of the header display.
	*/
	function afficherDebutPage($titrePage, $icone, $titreIcone, $altIcon, $table, $boutonAjouter=true, $messageInfo='')
	{
		$debutPage = '
<div class="wrap">
	<div class="icon32"><img alt="' . $altIcon . '" src="' . $icone . '"title="' . $titreIcone . '"/></div>
	<h2 class="alignleft" >' . $titrePage;
		if($boutonAjouter)
		{
			$debutPage .= '<a class="button add-new-h2" onclick="javascript:document.getElementById(\'act\').value=\'add\'; document.forms.form.submit();">' . __('Ajouter', 'annonces') . '</a>';
		}
		$debutPage .= '
	</h2>
	<div id="message" class="fade below-h2 annonceMessage">' . $messageInfo . '</div>';

		return $debutPage;
	}

	/**
	* Closes the "div" tag open in the header display  of a classical HTML page.
	* @see afficherDebutPage
	* @return  the closure.
	*/
	function afficherFinPage()
	{
		return '
	<div class="clear" id="ajax-response"></div>
	<div class="clear"></div>
	<div class="alignright">
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_s-xclick" />
			<input type="hidden" name="hosted_button_id" value="10265740" />
			<input type="image" src="https://www.paypal.com/fr_FR/FR/i/btn/btn_donateCC_LG.gif" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus s&eacute;curis&eacute;e !" />
			<img alt="" border="0" src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" width="1" height="1" />
		</form>
	</div>
</div>';
	}

	/**
	*	Create a combo box output
	*
	*	@param string $identifier The name and unique identifier of the combo box
	* @param array $content A complete array containing all values to put into the combo box
	*	@param mixed $selectedValue The value we have to select into the combo
	*
	*	@return mixed $output The combo box output
	*/
	function createComboBox($identifier, $name, $content, $selectedValue)
	{
		$output = '<select id="' . $identifier . '" name="' . $name . '" >';

		foreach($content as $index => $datas)
		{
			if(is_object($datas))
			{
				$selected = ($selectedValue == $datas->id) ? ' selected="selected" ' : '';
				$output .= '<option value="' . $datas->id . '" ' . $selected . ' >' . $datas->nom . '</option>';
			}
			else
			{
				$selected = ($selectedValue == $index) ? ' selected="selected" ' : '';
				$output .= '<option value="' . $index . '" ' . $selected . ' >' . $datas . '</option>';
			}
		}

		$output .= '</select>';

		return $output;
	}

}