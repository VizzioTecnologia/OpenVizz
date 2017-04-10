<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
	
	if ( ! is_array( $prop_value ) AND get_json_array( $prop_value ) ) {
		
		$prop_value = json_decode( $prop_value, TRUE );
		
	}
	
	$_field_value = array();
	
	if ( is_array( $prop_value ) ) {
		
		foreach ( $prop_value as $k => $value ) {
			
			if ( is_string( $value ) ) {
				
				if ( check_var( $_prop[ 'options_from_users_submits' ] ) AND ( check_var( $_prop[ 'options_title_field' ] ) OR check_var( $_prop[ 'options_title_field_custom' ] ) ) AND is_numeric( $value ) AND $_user_submit = $this->sfcm->get_user_submit( $value ) ) {
					
					$value = isset( $_user_submit[ 'data' ][ $_prop[ 'options_title_field' ] ] ) ? $_user_submit[ 'data' ][ $_prop[ 'options_title_field' ] ] : $_user_submit[ 'id' ];
					
					$_field_value[] = $value;
					
				}
				else {
					
					$_field_value[] = $value;
					
				}
				
			}
			
		}
		
		$prop_value = join( ', ', $_field_value );
		
	}
	else {
		
		if ( check_var( $_prop[ 'options_from_users_submits' ] ) AND ( check_var( $_prop[ 'options_title_field' ] ) OR check_var( $_prop[ 'options_title_field_custom' ] ) ) AND is_numeric( $prop_value ) AND $_user_submit = $this->sfcm->get_user_submit( $prop_value ) ) {
			
			$prop_value = isset( $_user_submit[ 'data' ][ $_prop[ 'options_title_field' ] ] ) ? $_user_submit[ 'data' ][ $_prop[ 'options_title_field' ] ] : $_user_submit[ 'id' ];
			
		}
		
	}
					
?>