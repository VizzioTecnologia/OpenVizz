<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Unid_mdl extends CI_Model{
	
	private $_continue_after_udgdps = TRUE;
	
	// --------------------------------------------------------------------
	
	public function __construct(){
		
		$this->load->language( 'unid_base' );
		
		$this->load->language( ( $this->mcm->environment == ADMIN_ALIAS ? 'admin/' : '' ) . 'unid' );
		
	}
	
	// --------------------------------------------------------------------
	
	public function menu_item_get_link_ud_data( $menu_item_id = NULL, $params = NULL ){
		
		return 'submit_forms/index' . '/miid/' . $menu_item_id . '/a/dd/did/' . ( isset( $params[ 'ud_data_id' ] ) ? $params[ 'ud_data_id' ] : 0 );
		
	}
	
	// --------------------------------------------------------------------
	
	public function continue_after_udgdps(){
		
		return $this->_continue_after_udgdps;
		
	}
	
	// --------------------------------------------------------------------
	
	public function continue_after_udgdps_on(){
		
		$this->_continue_after_udgdps = TRUE;
		
	}
	
	// --------------------------------------------------------------------
	
	public function continue_after_udgdps_off(){
		
		$this->_continue_after_udgdps = FALSE;
		
	}
	
	// --------------------------------------------------------------------
	
	public function get_data_title_prop_html( $submit_form, $user_submit, $items_prefix = '<span class="ud-title-prop item">', $items_suffix = '</span>' ){
		
		if ( $submit_form[ 'ud_title_prop' ] ) {
			
			$title = '';
			
			foreach( $submit_form[ 'ud_title_prop' ] as $k => $item ) {
				
				if ( isset( $user_submit[ $k ] ) ) {
					
					$title .= $items_prefix;
					
					$title .= $user_submit[ $k ];
					
					$title .= $items_suffix;
					
				}
				
			}
			
			return $title;
			
		}
		
		return '';
		
	}
	
	// --------------------------------------------------------------------
	
	public function get_data( $data_id ){
		
		$search_config = array(
			
			'plugins' => 'sf_us_search',
			'ipp' => 1,
			'allow_empty_terms' => TRUE,
			'plugins_params' => array(
				
				'sf_us_search' => array(
					
					'us_id' => $prop[ $data_id ],
					
				),
				
			),
			
		);
		
		$this->load->library( 'search' );
		$this->search->config( $search_config );
		
		return $this->search->get_full_results( 'sf_us_search', TRUE );
		
	}
	
	// --------------------------------------------------------------------
	
	public function copy_data( $data_id ){
		
		if ( is_int( $data_id ) AND $data_id > 0 ) {
			
			$title = '';
			
			foreach( $submit_form[ 'ud_title_prop' ] as $k => $item ) {
				
				if ( isset( $user_submit[ $k ] ) ) {
					
					$title .= $items_prefix;
					
					$title .= $user_submit[ $k ];
					
					$title .= $items_suffix;
					
				}
				
			}
			
			return $title;
			
		}
		
		return FALSE;
		
	}
	
	// --------------------------------------------------------------------
	
	public function api( $config ){
		
		// -------------------------------------------------
		// Parsing vars
		
		$allowed_actions = array(
			
			'get_data', // get data
			
		);
		
		if ( isset( $config[ 'a' ] ) AND ! in_array( $config[ 'a' ], $allowed_actions ) ) {
			
			exit( lang( 'unknow_action' ) );
			
		}
		
		$_config[ 'action' ] =									isset( $config[ 'a' ] ) ? $config[ 'a' ] : 'get_data'; // action
		$_config[ 'sub_action' ] =								isset( $config[ 'sa' ] ) ? $config[ 'sa' ] : NULL; // sub action
		$_config[ 'layout' ] =									isset( $config[ 'l' ] ) ? $config[ 'l' ] : 'default'; // layout
		
		
		$_config[ 'data_schema_id' ] =							isset( $config[ 'dsid' ] ) ? $config[ 'dsid' ] : NULL; // data schema id(s)
		
		if ( $_config[ 'data_schema_id' ] ) {
			
			// -------------------------------------------------
			// Adjusting the data schema id or ids
			
			$_config[ 'data_schema_id' ] = preg_split( "/[|;,-]/", rawurldecode( $_config[ 'data_schema_id' ] ) );
			
			if ( ! is_array( $_config[ 'data_schema_id' ] ) AND is_numeric( $_config[ 'data_schema_id' ] ) ) {
				
				$_config[ 'data_schema_id' ]  = array( $_config[ 'data_schema_id' ] );
				
			}
			
			foreach( $_config[ 'data_schema_id' ] as $k => $dsid ) {
				
				if ( ! ( $dsid AND is_numeric( $dsid ) AND is_int( $dsid + 0 ) ) ) {
					
					unset( $_config[ 'data_schema_id' ][ $k ] );
					
				}
				
			}
			
			// Adjusting the data schema id or ids
			// -------------------------------------------------
			
		}
		
		$_config[ 'data_id' ] =							isset( $config[ 'did' ] ) ? $config[ 'did' ] : NULL; // data_id id(s)
		
		if ( $_config[ 'data_id' ] ) {
			
			// -------------------------------------------------
			// Adjusting the data schema id or ids
			
			$_config[ 'data_id' ] = preg_split( "/[|;,-]/", rawurldecode( $_config[ 'data_id' ] ) );
			
			if ( ! is_array( $_config[ 'data_id' ] ) AND is_numeric( $_config[ 'data_id' ] ) ) {
				
				$_config[ 'data_id' ]  = array( $_config[ 'data_id' ] );
				
			}
			
			foreach( $_config[ 'data_id' ] as $k => $dsid ) {
				
				if ( ! ( $dsid AND is_numeric( $dsid ) AND is_int( $dsid + 0 ) ) ) {
					
					unset( $_config[ 'data_id' ][ $k ] );
					
				}
				
			}
			
			// Adjusting the data schema id or ids
			// -------------------------------------------------
			
		}
		
		$_config[ 'current_page' ] =							isset( $config[ 'cp' ] ) ? $config[ 'cp' ] : 1; // current page
		$_config[ 'items_per_page' ] =							isset( $config[ 'ipp' ] ) ? $config[ 'ipp' ] : 1; // items per page
		$_config[ 'order_by' ] =								isset( $config[ 'ob' ] ) ? $config[ 'ob' ] : 'id'; // order by
		$_config[ 'order_by_direction' ] =						isset( $config[ 'obd' ] ) ? $config[ 'obd' ] : 'ASC'; // order by direction
		$_config[ 'search' ] =									isset( $config[ 's' ] ) ? ( int )( ( bool ) $config[ 's' ] ) : FALSE; // search flag
		$_config[ 'filters' ] =									isset( $config[ 'f' ] ) ? json_decode( base64_decode( urldecode( $config[ 'f' ] ) ), TRUE ) : array(); // filters
		$_config[ 'include_data' ] =							isset( $config[ 'id' ] ) ? $config[ 'id' ] : FALSE; // include data
		$_config[ 'get_mode' ] =								isset( $config[ 'gm' ] ) ? $config[ 'gm' ] : 'compact'; // get mode: full or compact
		$_config[ 'csv_delimiter' ] =							isset( $config[ 'csvd' ] ) ? $config[ 'csvd' ] : NULL; // csv delimiter
		$_config[ 'csv_enclosure' ] =							( isset( $config[ 'csvd' ] ) AND $config[ 'csvd' ] !== FALSE ) ? $config[ 'csvd' ] : NULL; // csv enclosure
		$_config[ 'force_all_string' ] =						isset( $config[ 'csvfas' ] ) ? $config[ 'csvfas' ] : FALSE; // treat all collumns as string (use enclosure)
		$_config[ 'username' ] =								isset( $config[ 'u' ] ) ? $config[ 'u' ] : NULL; // username
		$_config[ 'password' ] =								isset( $config[ 'p' ] ) ? $config[ 'p' ] : NULL; // password
		$_config[ 'return_type' ] =								isset( $config[ 'rt' ] ) ? $config[ 'rt' ] : 'array'; // return type
		
		// Parsing vars
		// -------------------------------------------------
		
		if ( ! empty( $_config[ 'data_schema_id' ] ) ) {
			
			$search_config = array(
				
				'plugins' => 'unid_ds_search',
				'allow_empty_terms' => TRUE,
				'ipp' => $_config[ 'items_per_page' ],
				'cp' => $_config[ 'current_page' ],
				'plugins_params' => array(
					
					'unid_ds_search' => array(
						
						'dsid' => $_config[ 'data_schema_id' ],
						
					),
					
				),
				
			);
			
			$this->load->library( 'search' );
			$this->search->config( $search_config );
			
			$data_schemas = $this->search->get_full_results( 'unid_ds_search', TRUE );
			
			return $data_schemas;
			
// 			echo '<pre>' . print_r( $data_schemas, TRUE ) . '</pre>'; exit;
			
		}
		
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Parse a data schema
	 *
	 * @access public
	 * @param array
	 * @param bool | determines whether the parameters should be filtered
	 * @return void
	 */
	
	public function parse_ds( & $ds = NULL, $filter_params = FALSE, $menu_item_id = NULL ){
		
		reset( $ds );
		if ( is_array( $ds ) AND is_numeric( key( $ds ) ) ) {
			
			foreach ( $ds as $key => $item ) {
				
				if ( key_exists( 'id', $item ) ) {
					
					$this->parse_ds( $item );
					
				}
				
			}
			
		}
		
		if ( is_array( $ds ) AND key_exists( 'id', $ds ) ){
			
			if ( isset( $ds[ 'params' ] ) ) {
				
				if ( $filter_params ) {
					
					// obtendo os parâmetros globais do componente
					$component_params = $this->mcm->get_component( 'unid' );
					$component_params = $component_params[ 'params' ];
					
					// obtendo os parâmetros do item de menu
					if ( $this->mcm->current_menu_item ){
						
						$menu_item_params = get_params( $this->mcm->current_menu_item[ 'params' ] );
						$params = filter_params( $component_params, $menu_item_params );
						
					}
					else{
						
						$params = $component_params;
						
					}
					$ds[ 'params' ] = filter_params( $params, get_params( $ds[ 'params' ] ) );
					
				}
				else {
					
					$ds[ 'params' ] = get_params( $ds[ 'params' ] );
					
				}
				
			}
			
			if ( isset( $ds[ 'properties' ] ) ) {
				
				$ds[ 'properties' ] = get_params( $ds[ 'properties' ] );
				
			}
			
		}
		
	}
	
	// --------------------------------------------------------------------
	
}
