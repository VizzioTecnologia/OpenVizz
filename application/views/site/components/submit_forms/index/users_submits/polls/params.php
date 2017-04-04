<?php

$new_params = array();

/*
* ------------------------------------
*/
//$params[ 'params_spec' ][ 'users_submits_config' ] = $params[ 'params_spec' ][ 'users_submits_config' ] + $new_params;

//echo '<pre>' . print_r( $params[ 'params_spec_values' ], TRUE ) . '</pre>'; exit;

// Removing the fields to show related fields

$_keys_to_keep = array(
	
	'submit_form_id',
	'ud_d_list_layout_site',
	'show_readmore_link',
	'readmore_text',
	'readmore_url',
	'readmore_link_target',
	'us_default_results_filters',
	'spacer_readmore',
	
);

foreach ( $params[ 'params_spec' ][ 'users_submits_config' ] as $k => $p ) {
	
	foreach ( $_keys_to_keep as $v ) {
		
		if ( ! ( check_var( $p[ 'name' ] ) AND in_array( $p[ 'name' ], $_keys_to_keep ) ) ) {
			
			unset( $params[ 'params_spec' ][ 'users_submits_config' ][ $k ] );
			
		}
		
		
	}
	
}


/*
* ------------------------------------
*/

$_tmp = array(
	
	'type' => 'hidden',
	'name' => 'users_submits_items_per_page',
	'label' => 'poll_submits_mum_items',
	'value' => '-1',
	'validation' => array(
		
		'rules' => 'trim|required',
		
	),
	
);

$params[ 'params_spec_values' ][ 'users_submits_items_per_page' ] = '-1';

$new_params[] = $_tmp;

/*
* ------------------------------------
*/

$_tmp = array(
	
	'type' => 'select',
	'inline' => TRUE,
	'name' => 'poll_data_field',
	'label' => 'poll_data_field',
	'tip' => 'tip_poll_data_field',
	'validation' => array(
		
		'rules' => 'trim|required',
		
	),
	'options' => $fields_options
	
);

$params[ 'params_spec_values' ][ 'show_readmore_link' ] = 0;

$new_params[] = $_tmp;


array_push_pos( $params[ 'params_spec' ][ 'users_submits_config' ], $new_params, 2  );



//echo '<pre>' . print_r( $params[ 'params_spec' ][ 'users_submits_config' ], TRUE ) . '</pre>'; exit;

