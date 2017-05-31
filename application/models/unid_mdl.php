<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Unid_mdl extends CI_Model{
	
	private $_continue_after_udgdps = TRUE;
	
	// --------------------------------------------------------------------
	
	public function __construct(){
		
		$this->load->language( 'unid_base' );
		
		$this->load->language( ( $this->mcm->environment == ADMIN_ALIAS ? 'admin/' : '' ) . 'unid' );
		
	}
	
	// --------------------------------------------------------------------
	
	public function menu_item_get_link_ud_data_detail( $menu_item_id = NULL, $params = NULL ){
		
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
	
	// --------------------------------------------------------------------
	
	/**
	 * Copy a Data Scheme
	 *
	 * @access public
	 * @param numeric
	 * @return bool
	 */
	
	public function copy_ds( $id = NULL ){
		
		$result = FALSE;
		
		if ( $id ) {
			
			// get Data Scheme params
			$gdsp = array(
				
				'where_condition' => 't1.id = ' . $id,
				'limit' => 1,
				
			);
			
			$data_scheme = $this->ud_api->get_data_schemes( $gdsp )->row_array();
			
			if ( check_var( $data_scheme ) ) {
				
				$data_scheme = elements( array(
					
					'alias',
					'title',
					'create_datetime',
					'mod_datetime',
					'ordering',
					'status',
					'fields',
					'params',
					
				), $data_scheme );
				
				$data_scheme[ 'title' ] = $data_scheme[ 'title' ] . ' ' . lang( 'ud_ds_filename_copy_sufix' );
				$data_scheme[ 'alias' ] = url_title( $data_scheme[ 'title' ],'-',TRUE );
				
				$return_id = $this->sfcm->insert( $data_scheme );
				
				if ( $return_id ){
				
					$result = $return_id;
					
				}
				
			}
			
		}
		
		return $result;
		
	}
	
	// --------------------------------------------------------------------
	
	public function get_data_title( $params, $ud_data, $data_scheme ){
		
		$return = array();
		
		if ( isset( $params[ 'ud_title_prop' ] ) ) {
			
			foreach( $params[ 'ud_title_prop' ] as $k => $item ) {
				
				if ( isset( $ud_data[ 'parsed_data' ][ 'full' ][ $k ][ 'value' ] ) AND isset( $data_scheme[ 'fields' ][ $k ][ 'presentation_label' ] ) ) {
					
// 					echo '<pre>' . print_r( $ud_data[ 'parsed_data' ][ 'full' ][ $k ], TRUE ) . '</pre><br/>';
					
					$return[ 'label' ] = $data_scheme[ 'fields' ][ $k ][ 'presentation_label' ];
					$return[ 'value' ] = $ud_data[ 'parsed_data' ][ 'full' ][ $k ][ 'value' ];
					
				}
				
			}
			
			return $return;
			
		}
		
		return $return;
		
	}
	
	// --------------------------------------------------------------------
	
	public function get_data_title_prop_html( $params, $ud_data, $data_scheme ){
		
		if ( isset( $params[ 'ud_title_prop' ] ) ) {
			
			$_tmp = $this->get_data_title( $params, $ud_data, $data_scheme );
			
			$title = '';
			
			$title .= '<span class="item ud-title-prop ud-title-prop ' . $k . ' ud-data-value-wrapper">';
			
			$title .= '<span class="title">';
			
			$title .= $_tmp[ 'label' ];
			
			$title .= '</span> ';
			
			$title .= '<span class="value">';
			
			$title .= $_tmp[ 'value' ];
			
			$title .= '</span>';
			
			$title .= '</span>';
			
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
	
	public function copy_data( $data_id ) {
		
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
	
	public function url_encode_ud_filters( $filters ) {
		
		return urlencode( base64_encode( json_encode( $filters ) ) );
		
	}
	
	// --------------------------------------------------------------------
	
	public function url_decode_ud_filters( $filters ) {
		
		return get_params( base64_decode( urldecode( $filters ) ) );
		
	}
	
	// --------------------------------------------------------------------
	
	public function get_link( $config = NULL ) {
		
		if ( isset( $config[ 'url_alias' ] ) ) {
			
			$url = FALSE;
			
			$config[ 'menu_item_id' ] = isset( $config[ 'menu_item_id' ] ) ? ( int ) $config[ 'menu_item_id' ] : 0;
			$config[ 'ds' ] = ( isset( $config[ 'ds' ] ) AND is_array( $config[ 'ds' ] ) )  ? $config[ 'ds' ] : NULL;
			$config[ 'ds_id' ] = ( isset( $config[ 'ds_id' ] ) AND ( ( int ) $config[ 'ds_id' ] ) )  ? ( int ) $config[ 'ds_id' ] : NULL;
			$config[ 'ds_id' ] = ( isset( $config[ 'ds' ][ 'id' ] ) AND ( ( int ) $config[ 'ds' ][ 'id' ] ) )  ? ( int ) $config[ 'ds' ][ 'id' ] : $config[ 'ds_id' ];
			$config[ 'filters' ] = ( isset( $config[ 'filters' ] ) AND is_array( $config[ 'filters' ] ) )  ? $config[ 'filters' ] : NULL;
			
			// component name
			$cn = 'submit_forms';
			
			if ( $config[ 'url_alias' ] == 'site_data_list' AND check_var( $config[ 'ds_id' ] ) ) {
				
				// -------------------------------------------------
				// Data list menu item
				
				// data list
				$query_id = 'ud_ds_site_' . $config[ 'ds_id' ] . '_data_list';
				
				if ( $this->cache->cache( $query_id ) ) {
					
					$query_id = $this->cache->cache( $query_id );
					
				}
				else {
					
					$this->db->from( 'tb_menus' );
					$this->db->where( 'component_item', 'users_submits' );
					$this->db->like( 'params', '"submit_form_id":"' . $config[ 'ds_id' ] . '"' );
					$this->db->limit( 1 );
					
					$menu_item = $this->db->get();
					
					if ( $menu_item->num_rows() > 0 ) {
						
						$menu_item = $menu_item->row_array();
						
						$menu_item[ 'params' ] = get_params( $menu_item[ 'params' ] );
						
					}
					else {
						
						$menu_item = FALSE;
						
					}
					
					$config[ 'menu_item_id' ] = $menu_item[ 'id' ];
					
					$this->cache->cache( $query_id, $menu_item );
					
				}
				
				$url = array(
					
					'submit_forms' => 'index',
					'miid' => ( int ) $config[ 'menu_item_id' ],
					'a' => 'us',
					'sfid' => ( int ) $config[ 'ds_id' ],
					
				);
				
				if ( $config[ 'filters' ] ) {
					
					$url[ 'f' ] = $this->unid->url_encode_ud_filters( $config[ 'filters' ] );
					
				}
				
			}
			else if ( $config[ 'url_alias' ] == 'site_add_data' AND check_var( $config[ 'ds_id' ] ) ) {
				
				// -------------------------------------------------
				// Data submit menu item
				
				$query_id = 'ud_ds_site_' . $config[ 'ds_id' ] . '_data_add';
				if ( $this->cache->cache( $query_id ) ) {
					
					$menu_item = $this->cache->cache( $query_id );
					
				}
				else {
					
					$this->db->from( 'tb_menus' );
					$this->db->where( 'component_item', 'submit_form' );
					$this->db->like( 'params', '"submit_form_id":"' . $config[ 'ds_id' ] . '"' );
					$this->db->limit( 1 );
					
					$menu_item = $this->db->get();
					
					if ( $menu_item->num_rows() > 0 ) {
						
						$menu_item = $menu_item->row_array();
						
						$menu_item[ 'params' ] = get_params( $menu_item[ 'params' ] );
						
					}
					else {
						
						$menu_item = FALSE;
						
					}
					
					$this->cache->cache( $query_id, $menu_item );
					
				}
				
				$url = array(
					
					'submit_forms' => 'index',
					'miid' => ( int ) $config[ 'menu_item_id' ],
					'a' => 'sf',
					'sfid' => ( int ) $config[ 'ds_id' ],
					
				);
				
			}
			
			$url = $this->uri->assoc_to_uri( $url ); 
			
		}
		
		return $url;
		
	}
	
}
