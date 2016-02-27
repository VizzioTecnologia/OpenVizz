<?php

$new_params = array();











































/*
* ------------------------------------
*/
//$params[ 'params_spec' ][ 'users_submits_config' ] = $params[ 'params_spec' ][ 'users_submits_config' ] + $new_params;

//echo '<pre>' . print_r( $params[ 'params_spec_values' ], TRUE ) . '</pre>'; exit;

// Removing the fields to show related fields
foreach ( $params[ 'params_spec' ][ 'users_submits_config' ] as $k => $p ) {
	
	if ( check_var( $p[ 'label' ] ) AND $p[ 'label' ] === 'fields_to_show' ) {
		
		unset( $params[ 'params_spec' ][ 'users_submits_config' ][ $k ] );
		
	}
	
	if ( check_var( $p[ 'name' ] ) AND strpos(  $p[ 'name' ], 'fields_to_show[' ) !== FALSE ) {
		
		unset( $params[ 'params_spec' ][ 'users_submits_config' ][ $k ] );
		
	}
	
}

foreach ( $params[ 'params_spec' ][ 'users_submits_config' ] as $k => $p ) {
	
	if ( check_var( $p[ 'name' ] ) AND $p[ 'name' ] == 'users_submits_layout' ) {
		
		array_push_pos( $params[ 'params_spec' ][ 'users_submits_config' ], $new_params, $k + 1  );
		
	}
	
}


//echo '<pre>' . print_r( $params[ 'params_spec' ][ 'users_submits_config' ], TRUE ) . '</pre>'; exit;

