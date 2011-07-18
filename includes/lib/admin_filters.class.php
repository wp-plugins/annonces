<?php
/********************************************************
*Date: 01/10/2009      File:admin_filters.class.php     *
*Author: Eoxia							                *
*Comment:                                          		*
********************************************************/

/*			FILTER ANNONCE LIST						*/
class annonce_filters_form extends sfForm
{

	public function configure()
	{
		global $flag_possibilities_for_filters;
		global $attribute_group_possibilities_for_filters;
		global $flag_a_exporter_possibilities_for_filters;
		$this->setWidgets(array(
		  'flagvalidpetiteannonce' 	=> new sfWidgetFormSelect(array(
				'choices' => $flag_possibilities_for_filters,
				'label' => __('Validit&eacute;','annonces'),
		  )),
		  'idgroupeattribut' 				=> new sfWidgetFormSelect(array(
				'choices' => $attribute_group_possibilities_for_filters,
				'label' => __('Cat&eacute;gorie de l\'annonce','annonces'),
		  )),
		  'aexporter' 							=> new sfWidgetFormSelect(array(
				'choices' => $flag_a_exporter_possibilities_for_filters,
				'label' => __('Exportable','annonces'),
		  )),
		  'titre'      							=> new sfWidgetFormInput(array(
				'label' => __('Titre','annonces'),
		  )),
		  'referenceagencedubien'   => new sfWidgetFormInput(array(
				'label' => __('R&eacute;f&eacute;rence','annonces'),
		  )),
		));
		$this->widgetSchema->setNameFormat('annonce_filters[%s]');

		$this->setValidators(array(
		  'flagvalidpetiteannonce' => new sfValidatorChoice(array('choices' => array_keys($flag_possibilities_for_filters))),
		  'idgroupeattribut' 			 => new sfValidatorChoice(array('choices' => array_keys($attribute_group_possibilities_for_filters))),
		  'aexporter' 			 			 => new sfValidatorChoice(array('choices' => array_keys($flag_a_exporter_possibilities_for_filters))),
		  'titre'						       => new sfValidatorString(array('required' => false)),
		  'referenceagencedubien'  => new sfValidatorString(array('required' => false)),
		));
	}


	function setMorequery($filter_crit)
	{
		$morequery = "";

		foreach($filter_crit as $field_name => $value)
		{
			if(($value != '') && ($value != DEFAULT_FILTERS_EMPTY_VALUE_AOS))
			{
				if($field_name == 'flagvalidpetiteannonce')$morequery .= " AND " . $field_name . " = '". $value ."' ";
				elseif($field_name == 'idgroupeattribut')$morequery .= " AND ANN." . $field_name . " = '". $value ."' ";
				elseif($field_name == 'aexporter')$morequery .= " AND " . $field_name . " = '". $value ."' ";
				else $morequery .= " AND " . $field_name . " LIKE '%". $value ."%' ";
			}
		}

		return $morequery;
	}

}


/*			FILTER ATTRIBUTE LIST						*/
class attribut_filters_form extends sfForm
{

	public function configure()
	{

		global $flag_possibilities_for_filters;
		global $attribute_type_possibilities_for_filters;
		global $flag_visible_attribut_possibilities_for_filters;
		global $attribute_group_possibilities_for_filters;

		$this->setWidgets(array(
		  'flagvalidattribut' 	=> new sfWidgetFormSelect(array(
				'choices' => $flag_possibilities_for_filters,
				'label' => __('Validit&eacute;','annonces'),
		  )),
			'flagvisibleattribut' 	=> new sfWidgetFormSelect(array(
				'choices' => $flag_visible_attribut_possibilities_for_filters,
				'label' => __('Visibilit&eacute;','annonces'),
		  )),
		  'group_attribut'      	=> new sfWidgetFormSelect(array(
				'choices' => $attribute_group_possibilities_for_filters,
				'label' => __('Groupe de l\'attribut','annonces'),
		  )),
		  'typeattribut'      	=> new sfWidgetFormSelect(array(
				'choices' => $attribute_type_possibilities_for_filters,
				'label' => __('Type de l\'attribut','annonces'),
		  )),
		  'nomattribut'				 	=> new sfWidgetFormInput(array(
				'label' => __('Nom de l\'attribut','annonces'),
		  )),
			'measureunit' 				=> new sfWidgetFormInput(array(
				'label' => __('Unit&eacute; de mesure','annonces'),
		  )),
		));
		$this->widgetSchema->setNameFormat('attribut_filters[%s]');

		$this->setValidators(array(
		  'flagvalidattribut' 		=> new sfValidatorChoice(array('choices' => array_keys($flag_possibilities_for_filters))),
		  'flagvisibleattribut' 	=> new sfValidatorChoice(array('choices' => array_keys($flag_visible_attribut_possibilities_for_filters))),
		  'typeattribut'       		=> new sfValidatorChoice(array('choices' => array_keys($attribute_type_possibilities_for_filters))),
		  'group_attribut' 				=> new sfValidatorString(array('required' => false)),
		  'nomattribut' 					=> new sfValidatorString(array('required' => false)),
		  'measureunit'						=> new sfValidatorString(array('required' => false)),
		));
	}

	function setMorequery($filter_crit)
	{
		$morequery = "";

		foreach($filter_crit as $field_name => $value)
		{
			if(($value != '') && ($value != DEFAULT_FILTERS_EMPTY_VALUE_AOS))
			{
				if( $field_name == 'group_attribut' )$field_name = " LINK_ATT_GRP.idgroupeattribut ";
				
				if($field_name == 'flagvalidattribut')$morequery .= " AND " . $field_name . " = '". $value ."' ";
				else $morequery .= " AND " . $field_name . " LIKE '%". $value ."%' ";
			}
		}

		return $morequery;
	}
}


/*			FILTER ATTRIBUTE GROUP LIST			*/
class attribut_group_filters_form extends sfForm
{
	public function configure()
	{
		global $flag_possibilities_for_filters;
		$this->setWidgets(array(
		  'flagvalidgroupeattribut' 	=> new sfWidgetFormSelect(array(
				'choices' => $flag_possibilities_for_filters,
				'label' => __('Validit&eacute;','annonces'),
		  )),
		  'nomgroupeattribut'      		=> new sfWidgetFormInput(array(
				'label' => 'Nom de la cat&eacute;gorie',
		  )),
		  'descriptiongroupeattribut' => new sfWidgetFormInput(array(
				'label' => __('Description','annonces'),
		  )),
		));
		$this->widgetSchema->setNameFormat('attribut_group_filter[%s]');

		$this->setValidators(array(
		  'flagvalidgroupeattribut' 	=> new sfValidatorChoice(array('choices' => array_keys($flag_possibilities_for_filters))),
		  'nomgroupeattribut'       	=> new sfValidatorString(array('required' => false)),
		  'descriptiongroupeattribut' => new sfValidatorString(array('required' => false)),
		));
	}

	function setMorequery($filter_crit)
	{
		$morequery = "";

		foreach($filter_crit as $field_name => $value)
		{
			if(($value != '') && ($value != DEFAULT_FILTERS_EMPTY_VALUE_AOS))
			{
				if($field_name == 'flagvalidgroupeattribut')$morequery .= " AND " . $field_name . " = '". $value ."' ";
				else $morequery .= " AND " . $field_name . " LIKE '%". $value ."%' ";
			}
		}

		return $morequery;
	}
}


/*				FILTER PASSERELLE	LIST				*/
class passerelle_filters_form extends sfForm
{
	public function configure()
	{
		global $flag_possibilities_for_filters;
		$this->setWidgets(array(
		  'flagvalidpasserelle' 	=> new sfWidgetFormSelect(array(
				'choices' => $flag_possibilities_for_filters,
				'label' => __('Validit&eacute;','annonces'),
		  )),
		  'nompasserelle'      		=> new sfWidgetFormInput(array(
				'label' => __('Nom de la passerelle','annonces'),
		  )),
		  'nomexport'      				=> new sfWidgetFormInput(array(
				'label' => __('Nom export','annonces'),
		  )),
		  'host'      						=> new sfWidgetFormInput(array(
				'label' => __('H&ocirc;te de la passerelle','annonces'),
		  )),
		  'user'     						 	=> new sfWidgetFormInput(array(
				'label' => __('Identifiant','annonces'),
		  )),
		  'separateurtexte'      	=> new sfWidgetFormInput(array(
				'label' => __('S&eacute;parateur de texte','annonces'),
		  )),
		  'separateurchamp'      	=> new sfWidgetFormInput(array(
				'label' => __('S&eacute;parateur de champ','annonces'),
		  )),
		  'separateurligne'      	=> new sfWidgetFormInput(array(
				'label' => __('S&eacute;parateur de ligne','annonces'),
		  )),
		  'structure' 			=> new sfWidgetFormTextarea(array(
				'label' => __('Structure de l\'export','annonces'),
		  )),
		));
		$this->widgetSchema->setNameFormat('passerelle_filter[%s]');
	 
		$this->setValidators(array(
		  'flagvalidpasserelle' => new sfValidatorChoice(array('choices' => array_keys($flag_possibilities_for_filters))),
		  'nompasserelle'       => new sfValidatorString(array('required' => false)),
		  'nomexport'    				=> new sfValidatorString(array('required' => false)),
		  'host'  							=> new sfValidatorString(array('required' => false)),
		  'user'        				=> new sfValidatorString(array('required' => false)),
		  'structure'      			=> new sfValidatorString(array('required' => false)),
		  'separateurtexte' 		=> new sfValidatorString(array('required' => false)),
		  'separateurchamp'  		=> new sfValidatorString(array('required' => false)),
		  'separateurligne'     => new sfValidatorString(array('required' => false)),
		));
	}
	function setMorequery($filter_crit)
	{
		$morequery = "";

		foreach($filter_crit as $field_name => $value)
		{
			if(($value != '') && ($value != DEFAULT_FILTERS_EMPTY_VALUE_AOS))
			{
				if($field_name == 'flagvalidpasserelle')$morequery .= " AND " . $field_name . " = '". $value ."' ";
				else $morequery .= " AND " . $field_name . " LIKE '%". $value ."%' ";
			}
		}

		return $morequery;
	}
}