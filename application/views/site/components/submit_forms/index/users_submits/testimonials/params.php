<?php

$new_params = array();











































/*
* ------------------------------------
*/
//$params[ 'params_spec' ][ 'ud_params_section_d_list_general_params' ] = $params[ 'params_spec' ][ 'ud_params_section_d_list_general_params' ] + $new_params;

//echo '<pre>' . print_r( $params[ 'params_spec_values' ], TRUE ) . '</pre>'; exit;

// Removing the fields to show related fields
foreach ( $params[ 'params_spec' ][ 'ud_params_section_d_list_general_params' ] as $k => $p ) {
	
	if ( check_var( $p[ 'label' ] ) AND $p[ 'label' ] === 'fields_to_show' ) {
		
		unset( $params[ 'params_spec' ][ 'ud_params_section_d_list_general_params' ][ $k ] );
		
	}
	
	if ( check_var( $p[ 'name' ] ) AND strpos(  $p[ 'name' ], 'fields_to_show[' ) !== FALSE ) {
		
		unset( $params[ 'params_spec' ][ 'ud_params_section_d_list_general_params' ][ $k ] );
		
	}
	
}

foreach ( $params[ 'params_spec' ][ 'ud_params_section_d_list_general_params' ] as $k => $p ) {
	
	if ( check_var( $p[ 'name' ] ) AND $p[ 'name' ] == 'ud_d_list_layout_site' ) {
		
		array_push_pos( $params[ 'params_spec' ][ 'ud_params_section_d_list_general_params' ], $new_params, $k + 1  );
		
	}
	
}


//echo '<pre>' . print_r( $params[ 'params_spec' ][ 'ud_params_section_d_list_general_params' ], TRUE ) . '</pre>'; exit;

