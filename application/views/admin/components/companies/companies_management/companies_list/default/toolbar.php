<?php
	
	if ( isset( $companies ) AND $companies OR $this->input->post( 'submit_search' ) ){
		
		$search_box_params = array(
			
			'url' => 'admin/' . $component_name . '/'. $component_function . '/search',
			'terms' => isset( $search[ 'terms' ] ) ? $search[ 'terms' ] : '',
			'wrapper_class' => 'search-toolbar-wrapper fr',
			'name' => 'terms',
			'cancel_url' => 'admin/' . $component_name . '/'. $component_function . '/companies_list',
			'live_search_url' => 'admin/' . $component_name . '/'. $component_function . '/ajax/live_search',
			
		);
		
		echo vui_el_search( $search_box_params );
		
	}
	
	if ( $component_function != 'companies_select' ) {
			
	require( dirname(__FILE__) . DS . '..' . DS . '..' . DS . '..' . DS . 'toolbar.php');
	
	}

?>

