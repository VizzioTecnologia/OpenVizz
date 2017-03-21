<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

require( APPPATH . 'controllers/admin/main.php' );

class Unid extends Main {
	
	public function __construct(){
		
		parent::__construct();
		
		$this->load->model( 'unid_mdl', 'unid' );
		$this->load->language( 'submit_forms' );
		
		set_current_component();
		
	}
	
	public function index(){
		
		$this->data();
		
	}
	
	public function data(){
		
		// -------------------------------------------------
		// Params filtering
		
		// obtendo os parâmetros globais do componente
		$component_params = $this->current_component[ 'params' ];
		$menu_item_params = array();
		
		// obtendo os parâmetros do item de menu
		if ( $this->mcm->current_menu_item ){
			
			$menu_item_params = get_params( $this->mcm->current_menu_item[ 'params' ] );
			$data[ 'params' ] = filter_params( $component_params, $menu_item_params );
			
		}
		else {
			
			$data[ 'params' ] = $component_params;
			
		}
		
		// Params filtering
		// -------------------------------------------------
		
		// -------------------------------------------------
		// Parsing vars
		
		$f_params = $this->uri->ruri_to_assoc();
		
		$action =								isset( $f_params[ 'a' ] ) ? $f_params[ 'a' ] : 'l'; // action
		$sub_action =							isset( $f_params[ 'sa' ] ) ? $f_params[ 'sa' ] : NULL; // sub action
		$data_schema_id =						isset( $f_params[ 'dsid' ] ) ? $f_params[ 'dsid' ] : NULL; // data schema id
		$data_id =								isset( $f_params[ 'did' ] ) ? $f_params[ 'did' ] : NULL; // data id
		$current_page =							isset( $f_params[ 'cp' ] ) ? $f_params[ 'cp' ] : NULL; // current page
		$items_per_page =						isset( $f_params[ 'ipp' ] ) ? $f_params[ 'ipp' ] : NULL; // items per page
		$order_by =								isset( $f_params[ 'ob' ] ) ? $f_params[ 'ob' ] : NULL; // order by
		$order_by_direction =					isset( $f_params[ 'obd' ] ) ? $f_params[ 'obd' ] : NULL; // order by direction
		$layout =								isset( $f_params[ 'l' ] ) ? $f_params[ 'l' ] : 'default'; // layout
		$is_search =							isset( $f_params[ 's' ] ) ? ( int )( ( bool ) $f_params[ 's' ] ) : NULL; // search flag
		$filters =								isset( $f_params[ 'f' ] ) ? json_decode( base64_decode( urldecode( $f_params[ 'f' ] ) ), TRUE ) : array(); // filters
		$search_filters =						isset( $f_params[ 'sf' ] ) ? json_decode( base64_decode( urldecode( $f_params[ 'sf' ] ) ), TRUE ) : array(); // search filters
		
		// --------------------
		
		$layout = isset( $data[ 'params' ][ 'layout' ] ) ? $data[ 'params' ][ 'layout' ] : $layout;
		
		$this->component_function = __FUNCTION__;
		$this->component_function_action = $action;
		
		// Parsing vars
		// -------------------------------------------------
		
		if ( $action === 'l' ) {
			
			if ( ( $data_schemas = $this->unid->api( $f_params ) ) ){
				
				$this->load->helper( array( 'pagination' ) );
				$this->load->library( 'search' );
				
				$data[ 'data_schemas' ] = & $data_schemas;
				
				$search_config = array(
					
					'plugins' => 'sf_us_search',
					'allow_empty_terms' => TRUE,
					'plugins_params' => array(
						
						'sf_us_search' => array(
							
							'sf_id' => $submit_form_id,
							
						),
						
					),
					
				);
				
				if ( $this->input->post() ){
					
					$data[ 'post' ] = $this->input->post( NULL, TRUE);
					
				}
				else {
					
					$data[ 'post' ] = NULL;
					
				}
				
				if ( $this->input->get() ){
					
					$data[ 'get' ] = $this->input->get();
					
				}
				else {
					
					$data[ 'get' ] = NULL;
					
				}
				
				$get_query = array();
				$ob_fields = array(
					
					'id',
					'submit_datetime',
					'mod_datetime',
					'random',
					
				);
				
				foreach( $data[ 'submit_form' ][ 'fields' ] as $k => $field ){
					
					if ( ! in_array( $field[ 'field_type' ], array( 'html', 'button' ) ) ) {
						
						$ob_fields[] = isset( $field[ 'alias' ] ) ? $field[ 'alias' ] : ( isset( $field[ 'presentation_label' ] ) ? $field[ 'presentation_label' ] : ( isset( $field[ 'label' ] ) ? $field[ 'label' ] : '' ) );
						
					}
					
				}
				
				/*
				********************************************************
				--------------------------------------------------------
				Ordenção
				--------------------------------------------------------
				*/
				
				if ( isset( $data[ 'post' ][ 'users_submits_search' ][ 'order_by' ] ) ) {
					
					$ob = $data[ 'post' ][ 'users_submits_search' ][ 'order_by' ];
					
				}
				else if ( isset( $data[ 'get' ][ 'ob' ] ) ) {
					
					$ob = urldecode( $data[ 'get' ][ 'ob' ] );
					
				}
				else if ( isset( $f_params[ 'ob' ] ) ) {
					
					$ob = $f_params[ 'ob' ];
					
				}
				else if ( isset( $data[ 'params' ][ 'users_submits_order_by_field' ] ) ) {
					
					$ob = $data[ 'params' ][ 'users_submits_order_by_field' ];
					
				}
				
				if ( isset( $data[ 'post' ][ 'users_submits_search' ][ 'order_by_direction' ] ) ) {
					
					$obd = $data[ 'post' ][ 'users_submits_search' ][ 'order_by_direction' ];
					
				}
				else if ( isset( $data[ 'get' ][ 'obd' ] ) ) {
					
					$obd = urldecode( $data[ 'get' ][ 'obd' ] );
					
				}
				else if ( isset( $f_params[ 'ob' ] ) ) {
					
					$obd = $f_params[ 'ob' ];
					
				}
				else if ( isset( $data[ 'params' ][ 'users_submits_order_by_direction' ] ) ) {
					
					$obd = $data[ 'params' ][ 'users_submits_order_by_direction' ];
					
				}
				
				if ( $ob == 'random' OR $obd == 'random' ) {
					
					$search_config[ 'plugins_params' ][ 'sf_us_search' ][ 'random' ] = TRUE;
					
				}
				else {
					
					$search_config[ 'plugins_params' ][ 'sf_us_search' ][ 'order_by' ] = $data[ 'order_by' ] = $ob;
					$search_config[ 'plugins_params' ][ 'sf_us_search' ][ 'order_by_direction' ] = $data[ 'order_by_direction' ] = $obd;
					$search_config[ 'order_by' ][ 'sf_us_search' ] = $ob;
					$search_config[ 'order_by_direction' ][ 'sf_us_search' ] = $obd;
					
				}
				
				/*
				--------------------------------------------------------
				Ordenção
				--------------------------------------------------------
				********************************************************
				*/
				
				/*
				********************************************************
				--------------------------------------------------------
				Search flag
				--------------------------------------------------------
				*/
				
				if ( ! check_var( $s ) AND ( isset( $data[ 'post' ][ 'users_submits_search' ] ) OR
					( isset( $data[ 'get' ][ 's' ] ) AND $data[ 'get' ][ 's' ] ) ) ) {
					
					$s = 1;
					
				}
				
				$data[ 'search_mode' ] = $s;
				
				/*
				--------------------------------------------------------
				Search flag
				--------------------------------------------------------
				********************************************************
				*/
				
				/*
				********************************************************
				--------------------------------------------------------
				Terms
				--------------------------------------------------------
				*/
				
				if ( isset( $sfsp[ 'terms' ] ) AND ! isset( $data[ 'post' ][ 'users_submits_search' ][ 'terms' ] ) ){
					
					$data[ 'post' ][ 'users_submits_search' ][ 'terms' ] = $sfsp[ 'terms' ];
					
				}
				
				$terms = isset( $data[ 'post' ][ 'users_submits_search' ][ 'terms' ] ) ? $data[ 'post' ][ 'users_submits_search' ][ 'terms' ] : ( isset( $data[ 'get' ][ 'q' ][ 'terms' ] ) ? urldecode( $data[ 'get' ][ 'q' ][ 'terms' ] ) : FALSE );
				$data[ 'terms' ] = $terms;
				$search_config[ 'terms' ] = $terms;
				
				if ( ( isset( $data[ 'post' ][ 'users_submits_search' ][ 'submit_search' ] ) OR $terms ) ){
					
					if ( $terms ) {
						
						$get_query[ 'q' ] = $terms;
						
					}
					
				}
				
				/*
				--------------------------------------------------------
				Terms
				--------------------------------------------------------
				********************************************************
				*/
				
				/*
				********************************************************
				--------------------------------------------------------
				Filters
				--------------------------------------------------------
				*/
				
				$_default_results_filters = check_var( $data[ 'params' ][ 'us_default_results_filters' ] ) ? json_decode( $data[ 'params' ][ 'us_default_results_filters' ], TRUE ) : array();
				$_default_results_filters = is_array( $_default_results_filters ) ? $_default_results_filters : array();
				
				if ( $_default_results_filters AND empty( $f ) ){
					
					$f = $_default_results_filters;
					
				}
				
				if ( ! isset( $data[ 'post' ][ 'users_submits_search' ][ 'submit_search' ] ) AND $sfsp ){
					
					$data[ 'post' ][ 'users_submits_search' ] = $sfsp;
					
				}
				
			//echo '<pre>' . print_r( $sfsp, TRUE ) . '</pre>'; exit;
			
				if ( isset( $data[ 'post' ][ 'users_submits_search' ][ 'submit_search' ] ) ){
					
					$f = array();
					
					$sfsp = $data[ 'post' ][ 'users_submits_search' ];
					unset( $sfsp[ 'submit_search' ] );
					
					if ( isset( $data[ 'post' ][ 'users_submits_search' ][ 'dinamic_filter_fields' ] ) ) {
						
						foreach ( $data[ 'post' ][ 'users_submits_search' ][ 'dinamic_filter_fields' ] as $key => $value ) {
							
							if ( trim( $value ) !== '' ) {
								
								$_filter = & $f[];
								
								$_filter[ 'alias' ] = $key;
								$_filter[ 'value' ] = $value;
								$_filter[ 'comp_op' ] = '=';
								
							}
							
						}
						
					}
					
					if ( $_default_results_filters ){
						
						$f = array_merge_recursive( $f, $_default_results_filters );
						
					}
					
				}
				
				//echo '<pre>' . print_r( $f, TRUE ) . '</pre>'; exit;
				
				$search_config[ 'plugins_params' ][ 'sf_us_search' ][ 'filters' ] = $f;
				
				$filters_url = urlencode( base64_encode( json_encode( $f ) ) );
				
				/*
				--------------------------------------------------------
				Filters
				--------------------------------------------------------
				********************************************************
				*/
				
				foreach ( $data[ 'submit_form' ][ 'fields' ] as $key => $field ) {
					
					$alias = isset( $field[ 'alias' ] ) ? $field[ 'alias' ] : $this->sfcm->make_field_name( $field[ 'label' ] );
					
					$data[ 'fields' ][ $alias ] = $field;
					$data[ 'fields' ][ $alias ][ 'alias' ] = $alias;
					
				}
				
				/*
				********************************************************
				--------------------------------------------------------
				Paginação
				--------------------------------------------------------
				*/
				
				if ( isset( $data[ 'post' ][ 'users_submits_search' ][ 'ipp' ] ) ) {
					
					$ipp = $data[ 'post' ][ 'users_submits_search' ][ 'ipp' ];
					
				}
				else if ( isset( $data[ 'get' ][ 'ipp' ] ) ) {
					
					$ipp = urldecode( $data[ 'get' ][ 'ipp' ] );
					
				}
				else if ( isset( $f_params[ 'ipp' ] ) ) {
					
					$ipp = $f_params[ 'ipp' ];
					
				}
				else if ( isset( $data[ 'params' ][ 'users_submits_items_per_page' ] ) ) {
					
					$ipp = $data[ 'params' ][ 'users_submits_items_per_page' ];
					
				}
				
				if ( isset( $data[ 'post' ][ 'users_submits_search' ][ 'submit_search' ] ) ) {
					
					$cp = 1;
					
				}
				
				if ( $cp < 1 OR ! is_numeric( $cp ) ) $cp = 1;
				if ( $ipp < -1 OR ! is_numeric( $ipp ) ) $ipp = $this->mcm->filtered_system_params[ 'site_items_per_page' ];
				
				$search_config[ 'ipp' ] = $ipp;
				$search_config[ 'cp' ] = $cp;
				
				$users_submits = array();
				
				if ( check_var( $data[ 'post' ][ 'users_submits_search' ][ 'submit_search' ] ) OR
					check_var( $data[ 'post' ][ 'users_submits_search' ][ 'submit_search' ] ) OR
					check_var( $s ) OR
					( ! check_var( $data[ 'params' ][ 'use_search' ] ) ) OR
					( check_var( $data[ 'params' ][ 'use_search' ] ) AND check_var( $data[ 'params' ][ 'show_default_results' ] ) ) ) {
					
					$this->search->config( $search_config );
					$users_submits = $this->search->get_full_results( 'sf_us_search', TRUE );
					
				}
				
				if ( isset( $this->mcm->current_menu_item ) ) {
					
					$miid = $this->mcm->current_menu_item[ 'id' ];
					
				}
				else {
					
					$miid = 0;
					
				}
				
				foreach( $get_query as $k => & $query ) {
					
					$query = $k . '=' . urlencode( $query );
					
				}
				
				$get_query = ! empty( $get_query ) ? '?' . join( '&', $get_query ) : '';
				$s = $s ? '/s/1' : '';
				
				$sfsp = urlencode( base64_encode( json_encode( $sfsp ) ) );
				
				$pagination_url = 'submit_forms/index' . '/miid/' . $miid . '/a/us/sfid/' . $submit_form_id . $s . '/sfsp/' . $sfsp . '/f/' . $filters_url . '/cp/%p%/ipp/%ipp%' . $get_query;
				
				$data[ 'page_url' ] = 'submit_forms/index' . '/miid/' . $miid . '/a/us/sfid/' . $submit_form_id . $s . '/sfsp/' . $sfsp . '/f/' . $filters_url . $get_query;
// 				echo $data[ 'page_url' ];
				$data[ 'users_submits_total_results' ] = $this->search->count_all_results( 'sf_us_search' );
				
				$data[ 'pagination' ] = get_pagination( $pagination_url, $cp, $ipp, $data[ 'users_submits_total_results' ] );
				
				/*
				--------------------------------------------------------
				Paginação
				--------------------------------------------------------
				********************************************************
				*/
				
				//print_r( $data[ 'params' ] );
				
				if ( check_var( $data[ 'params' ][ 'fields_to_show' ] ) ) {
					
					foreach ( $data[ 'params' ][ 'fields_to_show' ] as $key => $value ) {
						
						if ( $value == '0' ) {
							
							unset( $data[ 'params' ][ 'fields_to_show' ][ $key ] );
							
						}
						
					}
					
				}
				
				$data[ 'users_submits' ] = $users_submits;
				
				$this->_page(
					
					array(
						
						'component_view_folder' => $this->component_view_folder,
						'function' => __FUNCTION__,
						'action' => 'users_submits',
						'layout' => ( ( check_var( $data[ 'params' ][ 'users_submits_layout' ] ) ) ? $data[ 'params' ][ 'users_submits_layout' ] : 'default' ),
						'view' => 'users_submits',
						'data' => $data,
						
					)
					
				);
				
				
			}
			
		}
		
	}
	
}
