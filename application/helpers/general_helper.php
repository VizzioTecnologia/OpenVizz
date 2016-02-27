<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function get_class_name( $str ){

	return strtolower( str_replace( array( '_model' ), '', $str) );

}

function get_constant_name( $str ){

	return constant( strtoupper( $str ) );

}

function set_current_component(){

	$CI =& get_instance();

	$CI->component_name = get_class_name( get_class( $CI ) );

	if ( $CI->mcm->validate_component_dependencies( $CI->component_name ) ){

		$CI->current_component = $CI->mcm->get_component( $CI->component_name );
		$CI->component_view_folder = $CI->component_name;

		$CI->mcm->filtered_system_params = filter_params( $CI->current_component[ 'params' ], $CI->mcm->filtered_system_params );

	}
	else{

		log_message( 'error', "[Components] Unable to set current component to " . $CI->component_name );
		msg( lang( 'Component dependecies error' ), 'title' );
		msg( lang( 'Unable to set current component to ' . $CI->component_name ), 'error' );
		redirect( 'admin' );

	}
}

// retorna o ambiente atual, admin ou site
function environment(){

	$CI =& get_instance();

	return $CI->mcm->environment;

}

function array_filter_cb( $var ) {

	if ( is_string( $var ) ) {

		$var = trim( $var );

	}

	$allowed_values = array( "0" ); // add valid values
	return in_array( $var, $allowed_values, TRUE ) ? TRUE : ( $var ? TRUE : FALSE );

}

function check_var( & $var, $zero_is_valid = FALSE, $debug = FALSE ){
	
	if ( isset( $var ) AND $var !== NULL ){
		
		if ( is_array( $var ) AND count( array_filter( $var, 'array_filter_cb' ) ) > 0 ){
			
			return TRUE;
			
		}
		else if ( is_array( $var ) AND count( array_filter( $var, 'array_filter_cb' ) ) === 0 ){
			
			return FALSE;
			
		}
		else if ( is_numeric( $var ) ){
			
			if ( $debug ) { echo 'O valor da variável é numérico e igual a ' . $var . "<br />"; };
			
			if ( ( int ) $var === 0 ) {
				
				if ( $zero_is_valid ) {
					
					if ( $debug ) { echo 'O valor da variável é 0, contudo o zero neste caso é válido como TRUE. Retornando TRUE' . "<br />"; };
					
					return TRUE;
					
				}
				else {
					
					if ( $debug ) { echo 'O valor da variável é 0, e neste caso zero é um valor inválido. Retornando FALSE' . "<br />"; };
					
					return FALSE;
					
				}
				
			}
			else {
				
				if ( $debug ) { echo 'O valor da variável é diferente de 0. Retornando TRUE' . "<br />"; };
				
				return TRUE;
				
			}
			
		}
		else if ( is_string( $var ) AND strlen( trim( $var ) ) > 0 ){
			
			return TRUE;
			
		}
		else if ( ( is_string( $var ) AND strlen( trim( $var ) ) == 0 ) ){
			
			return FALSE;
			
		}
		else if ( is_bool( $var ) ){
			
			return $var;
			
		}
		
	}
	
	return FALSE;

}

// limpa campos dinâmicos
function clear_dinamics_fields( $dinamic_fields, $key = 'key', $target_fields ){

	foreach ( $dinamic_fields as $df_key => $df ) {

		if ( is_array( $df ) ){

			if ( is_array( $target_fields ) ){

				$remove = TRUE;

				foreach ( $target_fields as $tf_key => $tf ) {

					if ( ! empty( $df[$tf] ) ){

						$remove = FALSE;

					}

				}

				if ( $remove )
					unset( $dinamic_fields[$df_key] );

			}
			else {

				if ( $df[$target_fields] == '' ){

					unset( $dinamic_fields[$df_key] );

				}

			}

		}

	}

	$dinamic_fields = array_values( $dinamic_fields );
	array_unshift( $dinamic_fields, 0 );
	unset( $dinamic_fields[0] );

	foreach ( $dinamic_fields as $df_key => $df ) {

		$dinamic_fields[$df_key][$key] = $df_key;

	}

	return $dinamic_fields;

}

function getFloat($pString){
	if (strlen($pString) == 0) {
			return false;
	}
	$pregResult = array();

	$commaset = strpos($pString,',');
	if ($commaset === false) {$commaset = -1;}

	$pointset = strpos($pString,'.');
	if ($pointset === false) {$pointset = -1;}

	$pregResultA = array();
	$pregResultB = array();

	if ($pointset < $commaset) {
		preg_match('#(([-]?[0-9]+(\.[0-9])?)+(,[0-9]+)?)#', $pString, $pregResultA);
	}
	preg_match('#(([-]?[0-9]+(,[0-9])?)+(\.[0-9]+)?)#', $pString, $pregResultB);
	if ((isset($pregResultA[0]) && (!isset($pregResultB[0])
			|| strstr($preResultA[0],$pregResultB[0]) == 0
			|| !$pointset))) {
		$numberString = $pregResultA[0];
		$numberString = str_replace('.','',$numberString);
		$numberString = str_replace(',','.',$numberString);
	}
	elseif (isset($pregResultB[0]) && (!isset($pregResultA[0])
			|| strstr($pregResultB[0],$preResultA[0]) == 0
			|| !$commaset)) {
		$numberString = $pregResultB[0];
		$numberString = str_replace(',','',$numberString);
	}
	else {
		return false;
	}
	$result = (float)$numberString;
	return $result;
}

function m_format($format, $number){
	$regex  = '/%((?:[\^!\-]|\+|\(|\=.)*)([0-9]+)?'.
			  '(?:#([0-9]+))?(?:\.([0-9]+))?([in%])/';
	if (setlocale(LC_MONETARY, 0) == 'C'){
		setlocale(LC_MONETARY, '');
	}
	$locale = localeconv();
	preg_match_all($regex, $format, $matches, PREG_SET_ORDER);
	foreach ($matches as $fmatch){
		$value = floatval($number);
		$flags = array(
			'fillchar'  => preg_match('/\=(.)/', $fmatch[1], $match) ?
						   $match[1] : ' ',
			'nogroup'   => preg_match('/\^/', $fmatch[1]) > 0,
			'usesignal' => preg_match('/\+|\(/', $fmatch[1], $match) ?
						   $match[0] : '+',
			'nosimbol'  => preg_match('/\!/', $fmatch[1]) > 0,
			'isleft'	=> preg_match('/\-/', $fmatch[1]) > 0
		);
		$width	  = trim($fmatch[2]) ? (int)$fmatch[2] : 0;
		$left	   = trim($fmatch[3]) ? (int)$fmatch[3] : 0;
		$right	  = trim($fmatch[4]) ? (int)$fmatch[4] : $locale['int_frac_digits'];
		$conversion = $fmatch[5];

		$positive = true;
		if ($value < 0){
			$positive = false;
			$value  *= -1;
		}
		$letter = $positive ? 'p' : 'n';

		$prefix = $suffix = $cprefix = $csuffix = $signal = '';

		$signal = $positive ? $locale['positive_sign'] : $locale['negative_sign'];
		switch (true){
			case $locale["{$letter}_sign_posn"] == 1 && $flags['usesignal'] == '+':
				$prefix = $signal;
				break;
			case $locale["{$letter}_sign_posn"] == 2 && $flags['usesignal'] == '+':
				$suffix = $signal;
				break;
			case $locale["{$letter}_sign_posn"] == 3 && $flags['usesignal'] == '+':
				$cprefix = $signal;
				break;
			case $locale["{$letter}_sign_posn"] == 4 && $flags['usesignal'] == '+':
				$csuffix = $signal;
				break;
			case $flags['usesignal'] == '(':
			case $locale["{$letter}_sign_posn"] == 0:
				$prefix = '(';
				$suffix = ')';
				break;
		}
		if (!$flags['nosimbol']){
			$currency = $cprefix .
						($conversion == 'i' ? $locale['int_curr_symbol'] : $locale['currency_symbol']) .
						$csuffix;
		} else{
			$currency = '';
		}
		$space  = $locale["{$letter}_sep_by_space"] ? ' ' : '';

		$value = number_format($value, $right, $locale['mon_decimal_point'],
				 $flags['nogroup'] ? '' : $locale['mon_thousands_sep']);
		$value = @explode($locale['mon_decimal_point'], $value);

		$n = strlen($prefix) + strlen($currency) + strlen($value[0]);
		if ($left > 0 && $left > $n){
			$value[0] = str_repeat($flags['fillchar'], $left - $n) . $value[0];
		}
		$value = implode($locale['mon_decimal_point'], $value);
		if ($locale["{$letter}_cs_precedes"]){
			$value = $prefix . $currency . $space . $value . $suffix;
		} else{
			$value = $prefix . $value . $space . $currency . $suffix;
		}
		if ($width > 0){
			$value = str_pad($value, $width, $flags['fillchar'], $flags['isleft'] ?
					 STR_PAD_RIGHT : STR_PAD_LEFT);
		}

		$format = str_replace($fmatch[0], $value, $format);
	}
	return $format;
}





/**
 * Get dynamic fields
 *
 * Obtém um array de campos dinâmicos
 *
 * @access		public
 * @author		Frank Souza <franksouza183@gmail.com>
 * @param		string		$identifier		a string que identifica o grupo de campos dinâmicos, sem o número. Ex.: "df_phone_".
 * 											O índice deve estar obrigatoriamente localizado imediatamente após a string $identifier. Ex.: "df_phone_1_title", "df_phone_1_number".
 * 											Note que também é obrigatório um underline (_) após o índice
 * @param		array		$array			uma array contendo os campos
 * @return		array
 */

function get_dynamic_fields( $identifier, $array ){

	if ( $identifier AND $array ){

		$return = array();

		foreach ($array as $item => $value) {

			$index_name = $item;

			$id = (int)preg_replace("/[^0-9]/", "", $index_name);

			if ( strpos( $index_name, $identifier ) !== FALSE ) {

				$field_title = str_replace($identifier.$id.'_','',$index_name);
				$return[$id][$field_title] = isset($array[$index_name]) ? $array[$index_name] : '';

				//print_r($products_fields_array[$dpf_id.'field_'.$id]);
			}

		}

		return $return;

	}

}

function get_json_array( $var ) {
	
	if ( is_string( $var ) ) {
		
		$var = json_decode( $var, TRUE );
		
		return is_array( $var ) ? $var : FALSE;
		
	}
	else if ( is_array( $var ) ) {
		
		return $var;
		
	}
	
	return FALSE;
	
}

function get_menu_item_id_by_uri( $uri = NULL ){
	if ($uri){
		$uri = explode("/", $uri);
		return $uri[4];
	}
	else return FALSE;
}

/* End of file general_helper.php */
/* Location: ./application/helpers/general_helper.php */