<?php
/***************************************************
*Date: 01/10/2009      File:export_admin.php 	 		 *
*Author: Eoxia							                       *
*Comment:                                          *
***************************************************/

class forms_tools
{
	/*	CREATE A FORM	*/
	function form($action, $method, $name, $input_list)
	{
		$the_form = '';
		$the_form .= 
			'<form name="'.$name.'" id="'.$name.'" method="'.$method.'" action="'.$action.'" >';

		foreach($input_list as $input_key => $input_def){

			$input_name = $input_def['name'];
			$input_value = $input_def['value'];
			$input_type = $input_def['type'];

			$the_form .= $input_name.'&nbsp;:&nbsp;';

			if($input_type == 'text')$the_form .= $this->form_input($input_name, $input_value);
			elseif($input_type == 'textarea')$the_form .= $this->form_input_textarea($input_name, $input_value);
			elseif($input_type == 'hidden')$the_form .= $this->form_input($input_name, $input_value, 'hidden');
			elseif($input_type == 'select'){
				$the_form .= $this->form_input_select($input_name, $input_value);
			}

			$the_form .= '<br/>';

		}

		$the_form .= 
			'</form>';

		return $the_form;
	}
	
	/*	CREATE A INPUT TEXT	OR HIDDEN OR PASSWORD	*/
	/*
		option parameter could be: readonly / disabled / style
		type parameter could be: text / hidden / password
	*/
	function form_input($name , $value = '' , $type = 'text' , $option = ''){
		return '<input type="'.$type.'" name="'.$name.'" id="'.$name.'" value="'.$value.'" '.$option.' />' ;
	}

	/*	CREATE A INPUT TEXTAREA	*/
	/*
		option parameter could be: style / maxlength
	*/
	function form_input_textarea($name , $value = '' , $option = ''){
		return '<textarea name="'.$name.' " id="'.$name.' " '.$option.' >'.$value.'</textarea';
	}

	/*	CREATE A INPUT SELECT	*/
	function form_input_select($name, $value, $option = ''){
		$output_select = '';
		if(count($value) != 0){
			$output_select .= '<select name="" id="" >';
				foreach($value as $key => $values){
					$output_select .= '<option value="'.$values.'" >'.$values.'</option>';
				}
			$output_select .= '</select>';
		}

		return $output_select;
	}

	/*	CREATE A INPUT CHECKBOX	*/
	function form_input_checkbox(){
	
	}

	/*	CREATE A INPUT RADIO	*/
	function form_input_radio(){
	
	}

}