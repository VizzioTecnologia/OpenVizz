<?php  if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );


// ------------------------------------------------------------------------

/**
 * Number Input Field
 *
 * @access	public
 * @param	mixed
 * @param	string
 * @param	string
 * @return	string
 */

function form_input_number( $data = '', $value = '', $extra = '' ){

	$defaults = array('type' => 'number', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value );

	return "<input " . _parse_form_attributes( $data, $defaults ) . $extra . " />";

}

// ------------------------------------------------------------------------

function form_hidden( $name, $value = '', $recursing = FALSE, $extra = '' ){
	
	if ( is_array( $name ) ) {
		
		$attrs = '';
		
		foreach ( $name as $key => $val ) {
			
			$attrs .= $key != 'type' ? $key . '="' . $val . '"' : '';
			
		}
		
		$out = '<input type="hidden" ' . $attrs . ' />' . "\n";
		return $out;
		
	}
	
	static $form;
	
	if ( $recursing === FALSE)
	{
		$form = "\n";
	}
	
	if ( ! is_array($value))
	{
		$form .= '<input type="hidden" name="'.$name.'" value="'.form_prep($value, $name) . '" ' . $extra . ' />'."\n";
	}
	else
	{
		foreach ($value as $k => $v)
		{
			$k = (is_int($k)) ? '' : $k;
			form_hidden($name.'['.$k.']', $v, TRUE);
		}
	}
	
	return $form;
	
}

// ------------------------------------------------------------------------

function form_checkbox( $data = '', $value = '', $checked = FALSE, $extra = '' ){

	$defaults = array( 'type' => 'checkbox', 'name' => ( ( ! is_array( $data ) ) ? $data : '' ), 'value' => $value );

	if ( is_array( $data ) AND array_key_exists( 'checked', $data ) ){

		$checked = $data[ 'checked' ];

		if ( $checked == FALSE ){

			unset( $data[ 'checked' ] );

		}
		else {

			$data[ 'checked' ] = 'checked';

		}
	}

	if ( $checked == TRUE ){

		$defaults[ 'checked' ] = 'checked';

	}
	else{

		unset( $defaults[ 'checked' ] );

	}

	return "<input " . _parse_form_attributes( $data, $defaults ) . $extra . " />";
}

// ------------------------------------------------------------------------

// In this custom form_dropdown function, we just wrap all
// options into a default optgroup tag, allowing some css customizations
function form_dropdown($name = '', $options = array(), $selected = array(), $extra = ''){
	if ( ! is_array($selected))
	{
		$selected = array($selected);
	}

	// If no selected state was submitted we will attempt to set it automatically
	if (count($selected) === 0)
	{
		// If the form name appears in the $_POST array we have a winner!
		if (isset($_POST[$name]))
		{
			$selected = array($_POST[$name]);
		}
	}
	
	if ($extra != '') $extra = ' '.$extra;
	
	$multiple = (count($selected) > 1 && strpos($extra, 'multiple') === FALSE) ? ' multiple="multiple"' : '';
	
	$form = '<select name="'.$name.'"'.$extra.$multiple.">\n";
	
	$form .= '<optgroup>'."\n";
	
	if ( is_array( $options ) AND count( $options ) ) {
		
		foreach ( $options as $key => $val)
		{
			$key = (string) $key;
			
			if (is_array($val) && ! empty($val))
			{
				$form .= '<optgroup label="'.$key.'">'."\n";
				
				foreach ($val as $optgroup_key => $optgroup_val)
				{
					$sel = (in_array($optgroup_key, $selected)) ? ' selected="selected"' : '';
					
					$form .= '<option value="'.$optgroup_key.'"'.$sel.'>'.(string) $optgroup_val."</option>\n";
				}
				
				$form .= '</optgroup>'."\n";
			}
			else
			{
				$sel = (in_array($key, $selected)) ? ' selected="selected"' : '';
				
				$form .= '<option value="'.$key.'"'.$sel.'>'.(string) $val."</option>\n";
			}
		}
		
	}
	
	$form .= '</optgroup>'."\n";

	$form .= '</select>';

	return $form;
}
/* End of file VECMS_url_helper.php */
/* Location: ./application/helpers/VECMS_url_helper.php */