<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Submit_forms_common_model extends CI_Model{
	
	// --------------------------------------------------------------------
	
	public function __construct(){
		
		if ( ! $this->load->is_model_loaded( 'unid' ) ) {
			
			$this->load->model( 'unid_mdl', 'unid' );
			
		}
		
	}
	
	// --------------------------------------------------------------------
	
	private function _get_users_submits_layout_params( $menu_item, $params_spec_values ) {
		
		$component_params = $this->mcm->get_component( 'submit_forms' );
		$component_params = $component_params[ 'params' ];
		$menu_item_params = $menu_item[ 'params' ];
		
		
		$params_values = filter_params( $component_params, $params_spec_values );
		$params_values = filter_params( $component_params, $menu_item_params );
		
		$layout_params = array();
		
		if ( isset( $params_values[ 'users_submits_layout' ] ) AND $params_values[ 'users_submits_layout' ] != 'global' ) {
			
			$system_views_path = VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . get_class_name( get_class() ) . DS . 'index' . DS . 'list' . DS;
			$theme_views_path = THEMES_PATH . site_theme_components_views_path() . get_class_name( get_class() ) . DS . 'index' . DS . 'list' . DS;
			
			if ( file_exists( $system_views_path . $params_values[ 'users_submits_layout' ] . DS . 'params.xml' ) ) {
				
				$layout_params = get_params_spec_from_xml( $system_views_path . $params_values[ 'users_submits_layout' ] . DS . 'params.xml' );
				
				if ( file_exists( $system_views_path . $params_values[ 'users_submits_layout' ] . DS . 'params.php' ) ) {
					
					include_once $system_views_path . $params_values[ 'users_submits_layout' ] . DS . 'params.php';
					
				}
				
			}
			else if ( file_exists( $theme_views_path . $params_values[ 'users_submits_layout' ] . DS . 'params.xml' ) ) {
				
				$layout_params = get_params_spec_from_xml( $theme_views_path . $params_values[ 'users_submits_layout' ] . DS . 'params.xml' );
				
				if ( file_exists( $theme_views_path . $params_values[ 'users_submits_layout' ] . DS . 'params.php' ) ) {
					
					include_once $theme_views_path . $params_values[ 'users_submits_layout' ] . DS . 'params.php';
					
				}
				
			}
			
			//echo '<pre>' . print_r( $layout_params, TRUE ) . '</pre>';
			
		}
		
		return $layout_params;
		
	}
	
	// --------------------------------------------------------------------
	
	public function get_submit_forms( $f_params = NULL ){
		
		// inicializando as variáveis
		$where_condition =						@$f_params['where_condition'] ? $f_params['where_condition'] : NULL;
		$or_where_condition =					@$f_params['or_where_condition'] ? $f_params['or_where_condition'] : NULL;
		$limit =								@$f_params['limit'] ? $f_params['limit'] : NULL;
		$offset =								@$f_params['offset'] ? $f_params['offset'] : NULL;
		$order_by =								@$f_params['order_by'] ? $f_params['order_by'] : 't1.title asc, t1.id asc';
		$order_by_escape =						@$f_params['order_by_escape'] ? $f_params['order_by_escape'] : TRUE;
		$return_type =							@$f_params['return_type'] ? $f_params['return_type'] : 'get';
		
		$this->db->select('
			
			t1.*
			
		');
		
		$this->db->from('tb_submit_forms t1');
		
		$this->db->order_by( $order_by, '', $order_by_escape );
		
		if ( $where_condition ){
			
			if( is_array( $where_condition ) ){

				foreach ( $where_condition as $key => $value ) {

					if( gettype( $where_condition ) === 'array' AND ( strpos( $key, 'fake_index_' ) !== FALSE ) ){

						$this->db->where( $value );
					}
					else $this->db->where( $key, $value );
				}
			}
			else $this->db->where( $where_condition );
		}
		if ( $or_where_condition ){
			if( is_array( $or_where_condition ) ){
				foreach ( $or_where_condition as $key => $value ) {

					if( gettype( $or_where_condition ) === 'array' AND ( strpos( $key, 'fake_index_' ) !== FALSE ) ){

						$this->db->or_where( $value );

					}
					else $this->db->or_where( $key, $value );

				}
			}
			else $this->db->or_where( $or_where_condition );

		}
		if ( $return_type === 'count_all_results' ){

			return $this->db->count_all_results();

		}
		if ( $limit ){

			$this->db->limit( $limit, $offset ? $offset : NULL );

		}
		
		return $this->db->get();

	}
	
	// --------------------------------------------------------------------
	
	public function get_submit_form( $id = NULL ){
		
		if ( $id!=NULL ){
			$this->db->select('t1.*');
			$this->db->from('tb_submit_forms t1');
			$this->db->where( 't1.id', $id );
			// limitando o resultando em apenas 1
			$this->db->limit(1);
			return $this->db->get();
		}
		else {
			return FALSE;
		}
		
	}
	
	// --------------------------------------------------------------------
	
	public function insert( $data = NULL ){
		if ($data != NULL){
			if ($this->db->insert('tb_submit_forms',$data)){
				// confirm the insertion for controller
				return $this->db->insert_id();
			}
			else {
				// case the insertion fails, return false
				return FALSE;
			}
		}
		else {
			redirect('categories');
		}
	}
	
	// --------------------------------------------------------------------
	
	public function update( $data = NULL, $condition = NULL ){
		if ($data != NULL && $condition != NULL){
			if ($this->db->update('tb_submit_forms',$data,$condition)){
				// confirm update for controller
				return TRUE;
			}
			else {
				// case update fails, return false
				return FALSE;
			}
		}
		redirect('admin/menus');
	}
	
	// --------------------------------------------------------------------
	
	public function delete( $condition = NULL ){
		if ($condition != null){
			if ($this->db->delete('tb_submit_forms',$condition)){
				// confirm delete for controller
				return TRUE;
			}
			else {
				// case delete fails, return false
				return FALSE;
			}
		}

	}
	
	// --------------------------------------------------------------------
	
	public function delete_all(){

		$sql = 'DELETE FROM tb_submit_forms';

		if ( $this->db->query( $sql ) ){

			// confirm delete for controller
			return TRUE;

		}
		else {

			// case delete fails, return false
			return FALSE;

		}

	}

	// --------------------------------------------------------------------
	
	public function get_submit_form_params( $submit_form, $current_params_values ){
		
		$post = $this->input->post( NULL, TRUE ) ? $this->input->post( NULL, TRUE ) : NULL;
		$params = get_params_spec_from_xml( APPPATH . 'controllers/admin/com_submit_forms/submit_form_params.xml' );
		
		$_current_params_values = isset( $current_params_values ) ? $current_params_values : array();
		$current_params_values = isset( $post[ 'params' ] ) ? array_merge_recursive( $_current_params_values, $post[ 'params' ] ) : $_current_params_values;
		
		
		/*
		 * ------------------------------------
		 * Api access
		 */
		
		$new_params[ 'ud_ds_api_access' ][] = array(
			
			'type' => 'spacer',
			'label' => 'user_group',
			
		);
		
		/*
		 * ------------------------------------
		 * User groups
		 */
		
		//$users_groups = $this->users->get_accessible_users_groups( $this->users->user_data[ 'id' ] );
		$users_groups = $this->users->get_users_groups_tree( 0, 0, 'list' );
		
		foreach ( $users_groups as $key => $ug ) {
			
			$ug_id = $ug[ 'id' ];
			
			$_tmp = array(
				
				'type' => 'checkbox',
				'name' => 'ud_ds_api_access_type_user_group[' . $ug_id . ']',
				'label' => $ug[ 'indented_title' ],
				'value' => $ug_id,
				'validation' => array(
					
					'rules' => 'trim',
					
				),
				
			);
			
			if ( ! $current_params_values ) {
				
				$params[ 'params_spec_values' ][ 'ud_ds_api_access_type_user_group[' . $ug_id . ']' ] = $ug_id;
				
			}
			
			$new_params[ 'ud_ds_api_access' ][] = $_tmp;
			
		}
		
		/*
		 * ------------------------------------
		 */
		
		$new_params[ 'ud_ds_api_access' ][] = array(
			
			'type' => 'spacer',
			'label' => 'users',
			
		);
		
		/*
		 * ------------------------------------
		 */
		
		$users = $this->users->get_users_checking_privileges()->result_array();
		
		foreach ( $users as $key => $user ) {
			
			$user_id = $user[ 'id' ];
			
			$_tmp = array(
				
				'type' => 'checkbox',
				'inline' => TRUE,
				'name' => 'ud_ds_api_access_type_users[' . $user_id . ']',
				'label' => $user[ 'name' ] . ' (<strong class="small" title="' . lang( 'username' ) . '">' . $user[ 'username' ] . '</strong>)',
				'value' => $user_id,
				'validation' => array(
					
					'rules' => 'trim',
					
				),
				
			);
			
			if ( ! $current_params_values ) {
				
				$params[ 'params_spec_values' ][ 'ud_ds_api_access_type_users[' . $user_id . ']' ] = $user_id;
				
			}
			
			$new_params[ 'ud_ds_api_access' ][] = $_tmp;
			
		}
		
		array_push_pos( $params[ 'params_spec' ][ 'ud_ds_api' ], $new_params[ 'ud_ds_api_access' ], 4  );
		
		/*
		 * Api access
		 * ------------------------------------
		 * Security
		 */
		
		$new_params[ 'ud_ds_access' ][] = array(
			
			'type' => 'spacer',
			'label' => 'user_group',
			
		);
		
		/*
		 * ------------------------------------
		 * User groups
		 */
		
		//$users_groups = $this->users->get_accessible_users_groups( $this->users->user_data[ 'id' ] );
		$users_groups = $this->users->get_users_groups_tree( 0, 0, 'list' );
		
		foreach ( $users_groups as $key => $ug ) {
			
			$ug_id = $ug[ 'id' ];
			
			$_tmp = array(
				
				'type' => 'checkbox',
				'name' => 'ud_ds_access_type_users_groups[' . $ug_id . ']',
				'label' => $ug[ 'indented_title' ],
				'value' => $ug_id,
				'validation' => array(
					
					'rules' => 'trim',
					
				),
				
			);
			
			if ( ! $current_params_values ) {
				
				$params[ 'params_spec_values' ][ 'ud_ds_access_type_users_groups[' . $ug_id . ']' ] = $ug_id;
				
			}
			
			$new_params[ 'ud_ds_access' ][] = $_tmp;
			
		}
		
		/*
		 * ------------------------------------
		 */
		
		$new_params[ 'ud_ds_access' ][] = array(
			
			'type' => 'spacer',
			'label' => 'ud_ds_access_users_label',
			'tip' => 'tip_ud_ds_access_users_label',
			
		);
		
		/*
		 * ------------------------------------
		 */
		
		foreach ( $users as $key => $user ) {
			
			$user_id = $user[ 'id' ];
			
			$_tmp = array(
				
				'type' => 'checkbox',
				'inline' => TRUE,
				'name' => 'ud_ds_access_type_users[' . $user_id . ']',
				'label' => $user[ 'name' ] . ' (<strong class="small" title="' . lang( 'username' ) . '">' . $user[ 'username' ] . '</strong>)',
				'value' => $user_id,
				'validation' => array(
					
					'rules' => 'trim',
					
				),
				
			);
			
			if ( ! $current_params_values ) {
				
				$params[ 'params_spec_values' ][ 'ud_ds_access_type_users[' . $user_id . ']' ] = $user_id;
				
			}
			
			$new_params[ 'ud_ds_access' ][] = $_tmp;
			
		}
		
		array_push_pos( $params[ 'params_spec' ][ 'sf_security_and_access' ], $new_params[ 'ud_ds_access' ], 2  );
		
		/*
		 * ------------------------------------
		 */
		
		if ( check_var( $submit_form ) ) {
			
		}
		
		/*************************************/
		/******* Carregando os contatos ******/

		$this->load->model( array( 'common/contacts_common_model' ) );

		$contacts = $this->contacts_common_model->get_contacts()->result_array();

		$contacts_options = array();

		foreach ( $contacts as $contact ){

			$contacts_options[ $contact[ 'id' ] ] = $contact[ 'name' ];

		}

		/******* Carregando os contatos ******/
		/*************************************/


		// carregando os layouts do tema atual
		$layouts_sending_email = dir_list_to_array( THEMES_PATH . site_theme_components_views_path() . 'submit_forms' . DS . 'index' . DS . 'sending_email' );
		// carregando os layouts do diretório de views padrão
		$layouts_sending_email = array_merge( $layouts_sending_email, dir_list_to_array( VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . 'submit_forms' . DS . 'index' . DS . 'sending_email' ) );

		// carregando os layouts de cópia para o usuário do tema atual
		$layouts_sending_email_submitter = dir_list_to_array( THEMES_PATH . site_theme_components_views_path() . 'submit_forms' . DS . 'index' . DS . 'sending_email_submitter' );
		// carregando os layouts de cópia para o usuário do diretório de views padrão
		$layouts_sending_email_submitter = array_merge( $layouts_sending_email, dir_list_to_array( VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . 'submit_forms' . DS . 'index' . DS . 'sending_email_submitter' ) );

		$current_section = 'users_submits';
		foreach ( $params[ 'params_spec' ][ $current_section ] as $key => $element ) {

			if ( $element[ 'name' ] == 'submit_form_param_send_email_to_contact' ){

				$spec_options = array();

				if ( isset( $params[ 'params_spec' ][ $current_section ][ $key ][ 'options' ] ) )
					$spec_options = $params[ 'params_spec' ][ $current_section ][ $key ][ 'options' ];

				$params[ 'params_spec' ][ $current_section ][ $key ][ 'options' ] = is_array( $spec_options ) ? $spec_options + $contacts_options : $contacts_options;

			}
			if ( $element[ 'name' ] == 'submit_form_sending_email_layout_view' ){

				$spec_options = array();

				if ( isset( $params[ 'params_spec' ][ $current_section ][ $key ][ 'options' ] ) )
					$spec_options = $params[ 'params_spec' ][ $current_section ][ $key ][ 'options' ];

				$params[ 'params_spec' ][ $current_section ][ $key ][ 'options' ] = is_array( $spec_options ) ? $spec_options + $layouts_sending_email : $layouts_sending_email;

			}

		}

		$current_section = 'submitter_message_receiving';
		foreach ( $params[ 'params_spec' ][ $current_section ] as $key => $element ) {

			if ( $element[ 'name' ] == 'sfsmr_layout_view' ){

				$spec_options = array();

				if ( isset( $params[ 'params_spec' ][ $current_section ][ $key ][ 'options' ] ) )
					$spec_options = $params[ 'params_spec' ][ $current_section ][ $key ][ 'options' ];

				$params[ 'params_spec' ][ $current_section ][ $key ][ 'options' ] = is_array( $spec_options ) ? $spec_options + $layouts_sending_email_submitter : $layouts_sending_email_submitter;

			}

		}

		// carregando os layouts do tema atual
		$layouts = dir_list_to_array( THEMES_PATH . site_theme_components_views_path() . 'submit_forms' . DS . 'index' . DS . 'submit_form' );
		// carregando os layouts do diretório de views padrão
		$layouts = array_merge( $layouts, dir_list_to_array( VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . 'submit_forms' . DS . 'index' . DS . 'submit_form' ) );

		$current_section = 'look_and_feel';
		foreach ( $params[ 'params_spec' ][ $current_section ] as $key => $element ) {

			if ( $element[ 'name' ] == 'submit_form_layout_view' ){

				$spec_options = array();

				if ( isset( $params[ 'params_spec' ][ $current_section ][ $key ][ 'options' ] ) )
					$spec_options = $params[ 'params_spec' ][ $current_section ][ $key ][ 'options' ];

				$params[ 'params_spec' ][ $current_section ][ $key ][ 'options' ] = is_array( $spec_options ) ? $spec_options + $layouts : $layouts;

			}

		}
		
		
		// print_r($params);

		return $params;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Parse a user submit data
	 *
	 * @access public
	 * @param array
	 * @param bool | determines whether the parameters should be filtered
	 * @return mixed
	 */
	
	public function parse_sf( & $sf = NULL, $filter_params = FALSE ){
		
		$errors = FALSE;
		
		reset( $sf );
		if ( is_array( $sf ) AND is_numeric( key( $sf ) ) ) {
			
			foreach ( $sf as $key => $item ) {
				
				if ( key_exists( 'id', $item ) ) {
					
					$this->parse_sf( $item );
					
				}
				
			}
			
		}
		
		if ( is_array( $sf ) AND key_exists( 'id', $sf ) ){
			
			// data submit
			$_ds_query_id = 'ud_ds_' . $sf[ 'id' ] . '_data_submit_site';
			// data list
			$_dl_query_id = 'ud_ds_' . $sf[ 'id' ] . '_data_list_site';
			
			$_ds_menu_item = NULL;
			$_dl_menu_item = NULL;
			
			$menu_item = NULL;
			
			// -------------------------------------------------
			// Data submit menu item
			
			if ( $this->cache->cache( $_ds_query_id ) ) {
				
				$_ds_menu_item = $this->cache->cache( $_ds_query_id );
				
			}
			else {
				
				$this->db->from( 'tb_menus' );
				$this->db->where( 'component_item', 'submit_form' );
				$this->db->like( 'params', '"submit_form_id":"' . $sf[ 'id' ] . '"' );
				$this->db->limit( 1 );
				
				$_ds_menu_item = $this->db->get();
				
				if ( $_ds_menu_item->num_rows() > 0 ) {
					
					$_ds_menu_item = $_ds_menu_item->row_array();
					
					$_ds_menu_item[ 'params' ] = get_params( $_ds_menu_item[ 'params' ] );
					
				}
				else {
					
					$_ds_menu_item = FALSE;
					
				}
				
				$this->cache->cache( $_ds_query_id, $_ds_menu_item );
				
			}
			
			if ( $_ds_menu_item ) {
				
				$sf[ 'site_link' ] = base_url() . $_ds_menu_item[ 'link' ];
				
			}
			
			// Data submit menu item
			// -------------------------------------------------
			
			// -------------------------------------------------
			// Data list menu item
			
			if ( $this->cache->cache( $_dl_query_id ) ) {
				
				$_dl_query_id = $this->cache->cache( $_dl_query_id );
				
			}
			else {
				
				$this->db->from( 'tb_menus' );
				$this->db->where( 'component_item', 'users_submits' );
				$this->db->like( 'params', '"submit_form_id":"' . $sf[ 'id' ] . '"' );
				$this->db->limit( 1 );
				
				$_dl_menu_item = $this->db->get();
				
				if ( $_dl_menu_item->num_rows() > 0 ) {
					
					$_dl_menu_item = $_dl_menu_item->row_array();
					
					$_dl_menu_item[ 'params' ] = get_params( $_dl_menu_item[ 'params' ] );
					
				}
				else {
					
					$_dl_menu_item = FALSE;
					
				}
				
				$this->cache->cache( $_dl_query_id, $_ds_menu_item );
				
			}
			
			if ( $_dl_menu_item ) {
				
				$sf[ 'users_submits_site_link' ] = base_url() . $_dl_menu_item[ 'link' ]; /*TODO Remove all references to "users submits and submit forms"*/
				$sf[ 'data_list_site_link' ] = base_url() . $_dl_menu_item[ 'link' ];
				
			}
			else {
				
				$sf[ 'users_submits_site_link' ] = base_url() . 'submit_forms/index/miid/0/a/us/sfid/' . $sf[ 'id' ]; /*TODO Remove all references to "users submits and submit forms"*/
				$sf[ 'data_list_site_link' ] = base_url() . 'submit_forms/index/miid/0/a/us/sfid/' . $sf[ 'id' ]; 
				
			}
			
			// Data list menu item
			// -------------------------------------------------
			
			
			
			
			
			
			$sf[ 'params' ] = get_params( $sf[ 'params' ] );
			
			if ( $filter_params ) {
				
				// obtendo os parâmetros globais do componente
				$component_params = $this->mcm->get_component( 'submit_forms' );
				$component_params = $component_params[ 'params' ];
				
				$params = array();
				
				if ( $_ds_menu_item ){
					
					$sf[ 'params' ] = filter_params( $sf[ 'params' ], $_ds_menu_item[ 'params' ] );
					
					// -------------------------------------------------
					// 
					
					// 
					// -------------------------------------------------
					
				}
				
				if ( $_dl_menu_item ){
					
					$sf[ 'params' ] = filter_params( $sf[ 'params' ], $_dl_menu_item[ 'params' ] );
					
					// -------------------------------------------------
					// 
					
					// 
					// -------------------------------------------------
					
				}
				
				$sf[ 'params' ] = filter_params( $component_params, $sf[ 'params' ] );
				
			}
			
			$sf[ 'fields' ] = get_params( $sf[ 'fields' ], TRUE );
			$sf[ 'edit_link' ] = $this->mcm->environment . '/' . $this->component_name . '/sfm/' . $this->uri->assoc_to_uri(
				
				array(
					
					'a' => 'esf',
					'sfid' => $sf[ 'id' ],
					
				)
				
			);
			
			$_fields = array();
			
			$sf[ 'ud_image_prop' ] = NULL;
			
			foreach( $sf[ 'fields' ] as $k => & $prop ) {
				
				if ( ! check_var( $prop[ 'key' ] ) ) {
					
					unset( $sf[ 'fields' ][ $k ] );
					//$prop[ 'alias' ] = isset( $prop[ 'alias' ] ) ? $prop[ 'alias' ] : $this->make_field_name( $prop[ 'label' ] );
					
				}
				else {
					
					if ( empty( $prop[ 'label' ] ) ) {
						
						if ( isset( $prop[ 'presentation_label' ] ) ) {
							
							$prop[ 'label' ] = $prop[ 'presentation_label' ];
							
						}
						else {
							
							$prop[ 'label' ] = lang( 'field' ) . ' ' . $prop[ 'key' ];
							
						}
						
					}
					
					if ( empty( $prop[ 'presentation_label' ] ) ) {
						
						if ( isset( $prop[ 'label' ] ) ) {
							
							$prop[ 'presentation_label' ] = $prop[ 'label' ];
							
						}
						else {
							
							$prop[ 'presentation_label' ] = lang( 'field' ) . ' ' . $prop[ 'key' ];
							
						}
						
					}
					
					if ( empty( $prop[ 'alias' ] ) ) {
						
						if ( isset( $prop[ 'label' ] ) ) {
							
							$prop[ 'alias' ] = $this->make_field_name( $prop[ 'label' ] );
							
						}
						else {
							
							$prop[ 'alias' ] = $this->make_field_name( lang( 'field' ) . ' ' . $prop[ 'key' ] );
							
						}
						
					}
					
					// -------------------------------------------------
					// Properties types
					
					if ( isset( $prop[ 'advanced_options' ][ 'prop_is_ud_image' ] ) ) {
						
						$sf[ 'ud_image_prop' ][ $prop[ 'alias' ] ] = 1;
						
					}
					
					if ( isset( $prop[ 'advanced_options' ][ 'prop_is_ud_title' ] ) ) {
						
						$sf[ 'ud_title_prop' ][ $prop[ 'alias' ] ] = 1;
						
					}
					
					if ( isset( $prop[ 'advanced_options' ][ 'prop_is_ud_content' ] ) ) {
						
						$sf[ 'ud_content_prop' ][ $prop[ 'alias' ] ] = 1;
						
					}
					
					if ( isset( $prop[ 'advanced_options' ][ 'prop_is_ud_other_info' ] ) ) {
						
						$sf[ 'ud_other_info_prop' ][ $prop[ 'alias' ] ] = 1;
						
					}
					
					if ( isset( $prop[ 'advanced_options' ][ 'prop_is_ud_status' ] ) ) {
						
						$sf[ 'ud_status_prop' ][ $prop[ 'alias' ] ] = 1;
						
					}
					
					if ( isset( $prop[ 'advanced_options' ][ 'prop_is_ud_event_datetime' ] ) ) {
						
						$sf[ 'ud_event_datetime_prop' ][ $prop[ 'alias' ] ] = 1;
						
					}
					
					// Properties types
					// -------------------------------------------------
					
					$_fields[ $prop[ 'alias' ] ] = $prop;
					
				}
				
			}
			
			$sf[ 'fields' ] = $_fields;
			
			array_unshift( $sf[ 'fields' ], NULL );
			
			unset( $sf[ 'fields' ][ 0 ] );
			unset( $_fields );
			
		}
		
		if ( ! $errors ) {
			
			return TRUE;
			
		}
		else if ( $errors ) {
			
			return $errors;
			
		}
		
	}
	
	// --------------------------------------------------------------------
	
	public function get_user_submit( $id, $submit_form_id = NULL ) {
		
		$search_config = array(
			
			'plugins' => 'sf_us_search',
			'allow_empty_terms' => TRUE,
			'ipp' => 1,
			'cp' => 1,
			'plugins_params' => array(
				
				'sf_us_search' => array(
					
					'us_id' => $id,
					'sf_id' => $submit_form_id,
					
				),
				
			),
			
		);
		
		$this->load->library( 'search' );
		$this->search->config( $search_config );
		
		$user_submit = $this->search->get_full_results( 'sf_us_search', TRUE );
		
		if ( isset( $user_submit[ 0 ] ) ) {
			
			return $user_submit[ 0 ];
			
		}
		else {
			
			return FALSE;
			
		}
		
	}
	
	// --------------------------------------------------------------------
	
	public function get_users_submits( $f_params = NULL ){
		
		// atribuindo valores às variávies
		$where_condition =						isset( $f_params[ 'where_condition' ] ) ? $f_params[ 'where_condition' ] : NULL;
		$or_where_condition =					isset( $f_params[ 'or_where_condition' ] ) ? $f_params[ 'or_where_condition' ] : NULL;
		$ipp =									isset( $f_params[ 'ipp' ] ) ? $f_params[ 'ipp' ] : NULL;
		$cp =									isset( $f_params[ 'cp' ] ) ? $f_params[ 'cp' ] : NULL;
		$order_by =								isset( $f_params[ 'order_by' ] ) ? $f_params[ 'order_by' ] : 't1.title asc, t1.id asc';
		$order_by_direction =					isset( $f_params[ 'order_by_direction' ] ) ? $f_params[ 'order_by_direction' ] : 'ASC';
		$order_by_escape =						isset( $f_params[ 'order_by_escape' ] ) ? $f_params[ 'order_by_escape' ] : TRUE;
		$return_type =							isset( $f_params[ 'return_type' ] ) ? $f_params[ 'return_type' ] : 'get';
		$random =								check_var( $f_params[ 'random' ] ) ? TRUE : FALSE;
		$has_image_first =						check_var( $f_params[ 'has_image_first' ] ) ? TRUE : FALSE;
		$terms =								isset( $f_params[ 'terms' ] ) ? $f_params[ 'terms' ] : NULL;
		
		// user submit id
		$us_id =								isset( $f_params[ 'us_id' ] ) ? $f_params[ 'us_id' ] : ( isset( $f_params[ 'id' ] ) ? $f_params[ 'id' ] : ( isset( $f_params[ 'user_submit_id' ] ) ? $f_params[ 'user_submit_id' ] : NULL ) );
		$sf_id =								isset( $f_params[ 'sf_id' ] ) ? $f_params[ 'sf_id' ] : ( isset( $f_params[ 'submit_form_id' ] ) ? $f_params[ 'submit_form_id' ] : NULL );
		
		$search_config = array(
			
			'plugins' => 'sf_us_search',
			'ipp' => $ipp,
			'cp' => $cp,
			'allow_empty_terms' => TRUE,
			'plugins_params' => array(
				
				'sf_us_search' => array(
					
					'order_by' => $order_by,
					'order_by_direction' => $order_by_direction,
					'order_by_escape' => $order_by_escape,
					'us_id' => $us_id,
					'sf_id' => $sf_id,
					
				),
				
			),
			
		);
		
		$this->load->library( 'search' );
		$this->search->config( $search_config );
		
		$users_submits = $this->search->get_full_results( 'sf_us_search', TRUE );
		
		return $users_submits;
		
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Get formated fied name
	 *
	 * @access public
	 * @param string
	 * @return string
	 */
	
	public function make_field_name( $label ){
		
		return url_title( $label, '-', TRUE );
		
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Parse a user submit data
	 *
	 * @access public
	 * @param array
	 * @param bool | determines whether the parameters should be filtered
	 * @return void
	 */
	
	public function parse_us( & $us = NULL, $filter_params = FALSE, $menu_item_id = 0 ){
		
		if ( ! $menu_item_id ) $menu_item_id = 0;
		
		reset( $us );
		if ( is_array( $us ) AND is_numeric( key( $us ) ) ) {
			
			foreach ( $us as $key => $item ) {
				
				if ( key_exists( 'id', $item ) ) {
					
					$this->parse_us( $item );
					
				}
				
			}
			
		}
		
		if ( is_array( $us ) AND key_exists( 'id', $us ) ){
			
			if ( $filter_params ) {
				
				// obtendo os parâmetros globais do componente
				$component_params = $this->mcm->get_component( 'submit_forms' );
				$component_params = $component_params[ 'params' ];
				
				// obtendo os parâmetros do item de menu
				if ( $this->mcm->current_menu_item ){
					
					$menu_item_params = get_params( $this->mcm->current_menu_item[ 'params' ] );
					$params = filter_params( $component_params, $menu_item_params );
					
				}
				else{
					
					$params = $component_params;
					
				}
				$us[ 'params' ] = filter_params( $params, get_params( $us[ 'params' ] ) );
				
			}
			else {
				
				$us[ 'params' ] = get_params( $us[ 'params' ] );
				
			}
			
			$us[ 'data' ] = get_params( $us[ 'data' ] );
			
			$us[ 'site_link' ] = $this->unid->menu_item_get_link_ud_data( $menu_item_id, array( 'ud_data_id' => $us[ 'id' ] ) );
			
		}
		
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Parse a users submits list
	 *
	 * @access public
	 * @param array
	 * @param bool | determines whether the parameters should be filtered
	 * @return void
	 */
	
	public function parse_users_submits_list( & $users_submits = NULL, $fields_to_show = NULL, $submit_form_id = NULL ){
		
		if ( is_array( $users_submits ) ) {
			
			$submit_form = FALSE;
			
			if ( is_int( $submit_form_id ) ) {
				
				// get submit form params
				$gsfp = array(
					
					'where_condition' => 't1.id = ' . $submit_form_id,
					'limit' => 1,
					
				);
				
				if ( $submit_form = $this->sfcm->get_submit_forms( $gsfp )->row_array() ){
					
					$this->sfcm->parse_sf( $submit_form );
					
				}
				
			}
			else if ( is_array( $submit_form_id ) ) {
				
				$submit_form = $submit_form_id;
				
			}
			
			if ( $submit_form ) {
				
				$_fields = $submit_form[ 'fields' ];
				$fields = array();
				
				foreach ( $_fields as $key => $field ) {
					
					$fields[ $field[ 'alias' ] ] = $field;
					
				}
				
				foreach ( $users_submits as $key => & $user_submit ) {
					
					$us_fields = $_titles_fields = $_contents_fields = array();
					
					$us_fields[ 'id' ][ 'label' ] = lang( 'id' );
					$us_fields[ 'id' ][ 'value' ] = $user_submit[ 'id' ];
					$us_fields[ 'id' ][ 'visible' ] = ( ! check_var( $fields_to_show ) OR in_array( 'id', $fields_to_show ) ) ? TRUE : FALSE;
					
					$us_fields[ 'submit_datetime' ][ 'label' ] = lang( 'submit_datetime' );
					$us_fields[ 'submit_datetime' ][ 'value' ] = $user_submit[ 'submit_datetime' ];
					$us_fields[ 'submit_datetime' ][ 'visible' ] = ( ! check_var( $fields_to_show ) OR in_array( 'submit_datetime', $fields_to_show ) ) ? TRUE : FALSE;
					
					$us_fields[ 'mod_datetime' ][ 'label' ] = lang( 'mod_datetime' );
					$us_fields[ 'mod_datetime' ][ 'value' ] = $user_submit[ 'mod_datetime' ];
					$us_fields[ 'mod_datetime' ][ 'visible' ] = ( ! check_var( $fields_to_show ) OR in_array( 'mod_datetime', $fields_to_show ) ) ? TRUE : FALSE;
					
					foreach ( $user_submit[ 'data' ] as $key_2 => $field_value ) {
						
						//echo $key_2 . ': <br/><pre>' . print_r( $field_value, TRUE ) . '</pre><br/>'; 
						
						if ( isset( $fields[ $key_2 ] ) ) {
							
							//echo $key_2 . ': <br/><pre>' . print_r( $fields[ $key_2 ], TRUE ) . '</pre><br/>'; 
							
							if ( ! is_numeric( $key_2 ) ) {
								
								if ( $fields[ $key_2 ][ 'field_type' ] == 'date' ){
									
									$___date = explode( '-', $field_value );
									
									$format = '';
									
									$format .= ( check_var( $fields[ $key_2 ][ 'sf_date_field_use_year' ] ) AND isset( $___date[ 0 ] ) AND ( int ) $___date[ 0 ] > 0 ) ? 'y' : '';
									$format .= ( check_var( $fields[ $key_2 ][ 'sf_date_field_use_month' ] ) AND isset( $___date[ 1 ] ) AND ( int ) $___date[ 1 ] > 0 ) ? 'm' : '';
									$format .= ( check_var( $fields[ $key_2 ][ 'sf_date_field_use_day' ] ) AND isset( $___date[ 2 ] ) AND ( int ) $___date[ 2 ] > 0 ) ? 'd' : '';
									
									if ( ! empty( $format ) ) {
										
										$format = 'sf_us_dt_ft_pt_' . $format . '_' . $fields[ $key_2 ][ 'sf_date_field_presentation_format' ];
										
										$field_value =  strftime( lang( $format ), strtotime( $field_value ) );
										
									}
									else {
										
										$field_value =  '';
										
									}
									
								}
								else if ( in_array( $fields[ $key_2 ][ 'field_type' ], array( 'checkbox', 'combo_box', ) ) ){
									
									if ( is_string( $field_value ) ) {
										
										$field_value = json_decode( $field_value, TRUE );
										
									}
									
									$_field_value = array();
									
									if ( is_array( $field_value ) ) {
										
										foreach ( $field_value as $k => $value ) {
											
											if ( is_string( $value ) ) {
												
												if ( check_var( $fields[ $key_2 ][ 'options_from_users_submits' ] ) AND ( check_var( $fields[ $key_2 ][ 'options_title_field' ] ) OR check_var( $fields[ $key_2 ][ 'options_title_field_custom' ] ) ) AND is_numeric( $value ) AND $_user_submit = $this->sfcm->get_user_submit( $value ) ) {
													
													$value = isset( $_user_submit[ 'data' ][ $fields[ $key_2 ][ 'options_title_field' ] ] ) ? $_user_submit[ 'data' ][ $fields[ $key_2 ][ 'options_title_field' ] ] : $_user_submit[ 'id' ];
													
													$_field_value[] = $value;
													
												}
												else {
													
													$_field_value[] = $value;
													
												}
												
											}
											
										}
										
										$field_value = join( ', ', $_field_value );
										
									}
									else {
										
										if ( check_var( $fields[ $key_2 ][ 'options_from_users_submits' ] ) AND ( check_var( $fields[ $key_2 ][ 'options_title_field' ] ) OR check_var( $fields[ $key_2 ][ 'options_title_field_custom' ] ) ) AND is_numeric( $field_value ) AND $_user_submit = $this->sfcm->get_user_submit( $field_value ) ) {
											
											$field_value = isset( $_user_submit[ 'data' ][ $fields[ $key_2 ][ 'options_title_field' ] ] ) ? $_user_submit[ 'data' ][ $fields[ $key_2 ][ 'options_title_field' ] ] : $_user_submit[ 'id' ];
											
										}
										
									}
									
								}
								
								$us_fields[ $key_2 ][ 'label' ] = isset( $fields[ $key_2 ][ 'presentation_label' ] ) ? $fields[ $key_2 ][ 'presentation_label' ] : $fields[ $key_2 ][ 'label' ];
								$us_fields[ $key_2 ][ 'value' ] = $field_value;
								$us_fields[ $key_2 ][ 'visible' ] = ( ! check_var( $fields_to_show ) OR in_array( $key_2, $fields_to_show ) ) ? TRUE : FALSE;
								
							}
							
						}
						else {
							
							$us_fields[ $key_2 ][ 'label' ] = '[' . $key_2 . ']';
							$us_fields[ $key_2 ][ 'value' ] = $field_value;
							$us_fields[ $key_2 ][ 'visible' ] = ( ! check_var( $fields_to_show ) OR in_array( $key_2, $fields_to_show ) ) ? TRUE : FALSE;
							
						}
						
					}
					
					// defining user submit titles and contents
					foreach ( $us_fields as $key_2 => $us_field ) {
						
						if ( check_var( $params[ 'results_title_field' ] ) AND $key_2 == $params[ 'results_title_field' ] ) {
							
							$_titles_fields[ $key_2 ] = $us_field;
							
						}
						else {
							
							$_contents_fields[ $key_2 ] = $us_field;
							
						}
						
					}
					
					$user_submit[ 'parsed_data' ][ 'full' ] = $us_fields;
					$user_submit[ 'parsed_data' ][ 'titles' ] = $_titles_fields;
					$user_submit[ 'parsed_data' ][ 'contents' ] = $_contents_fields;
					
				}
				
				return $users_submits;
				
			}
			
		}
		
	}
	
	// --------------------------------------------------------------------
	
	public function insert_user_submit( $data = NULL ){
		
		if ( $data != NULL AND is_array( $data ) ){
			
			if ( $this->db->insert( 'tb_submit_forms_us', $data ) ){
				
				return $this->db->insert_id();
				
			}
			
		}
		
		log_message( 'error', '[Submit forms] Error attempting to insert submit record!' );
		
		return FALSE;
		
	}
	
	// --------------------------------------------------------------------
	
	public function update_user_submit( $data = NULL, $condition = NULL ){
		
		if ( $data != NULL && $condition != NULL ){
			
			$data[ 'xml_data' ] = $this->us_json_data_to_xml( $data[ 'data' ] );
			
			if ( $this->db->update( 'tb_submit_forms_us', $data, $condition ) ){
				
				// confirm update for controller
				return TRUE;
				
			}
			else {
				
				// case update fails, return false
				return FALSE;
				
			}
			
		}
		
	}
	
	// --------------------------------------------------------------------
	
	public function remove_user_submit( $id = NULL ){
		
		if ( $id ) {
			
			if ( $this->db->delete( 'tb_submit_forms_us', array( 'id' => $id ) ) ){
				
				return TRUE;
				
			}
			
		}
		
		return FALSE;
		
	}
	
	// --------------------------------------------------------------------
	
	public function us_json_data_to_xml( $us_data ){
		
		if ( is_string( $us_data ) ) {
			
			$us_data = get_params( $us_data );
			
		}
		
		if ( is_array( $us_data ) ) {
			
			$xml = '<?xml version="1.0" encoding="UTF-8" ?>';
			
			foreach ( $us_data as $key_1 => & $data_value_1 ) {
				
				if ( is_array( $data_value_1 ) ) {
					
					$data_value_1 = json_encode( $data_value_1 );
					
				}
				
				$xml .= '<' . $key_1 . '>';
				$xml .= $data_value_1;
				$xml .= '</' . $key_1 . '>';
				
				
			}
			
			return $xml;
			
		}
		
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Get the current url params and return the params allowed
	 *
	 * @access private
	 * @return array
	 */
	
	public function get_us_url_params() {
		
		$url_params = $this->uri->ruri_to_assoc();
		
		$allowed_actions = array(
			
			'l', // List
			'd', // Detail
			'a', // Add
			'e', // Edit
			'r', // Remove
			'cob', // Change order by
			'b', // Batch
			
		);
		
		if ( isset( $url_params[ 'a' ] ) AND ! in_array( $url_params[ 'a' ], $allowed_actions ) ) {
			
			exit( lang( 'unknow_action' ) );
			
		}
		
		$params[ 'action' ] =									isset( $url_params[ 'a' ] ) ? $url_params[ 'a' ] : 'l'; // action
		$params[ 'sub_action' ] =								isset( $url_params[ 'sa' ] ) ? $url_params[ 'sa' ] : NULL; // sub action
		$params[ 'layout' ] =									isset( $url_params[ 'l' ] ) ? $url_params[ 'l' ] : 'default'; // layout
		
		
		$params[ 'submit_form_id' ] =							isset( $url_params[ 'sfid' ] ) ? $url_params[ 'sfid' ] : NULL; // submit form id(s)
		
		// Adjusting the submit form id or ids
		if ( strpos( $params[ 'submit_form_id' ] , ',' ) ) {
			
			$params[ 'submit_form_id' ]  = explode( ',', $params[ 'submit_form_id' ] );
			
		}
		else {
			
			$params[ 'submit_form_id' ]  = array( $params[ 'submit_form_id' ] );
			
		}
		
		foreach( $params[ 'submit_form_id' ] as $k => $sfid ) {
			
			if ( ! ( $sfid AND is_numeric( $sfid ) AND is_int( $sfid + 0 ) ) ) {
				
				unset( $params[ 'submit_form_id' ][ $k ] );
				
			}
			
		}
		
		$params[ 'user_submit_id' ] =							isset( $url_params[ 'usid' ] ) ? $url_params[ 'usid' ] : NULL; // user submit id(s)
		
		// Adjusting the users submits id or ids
		if ( strpos( $params[ 'user_submit_id' ], ',' ) ) {
			
			$params[ 'user_submit_id' ] = explode( ',', $params[ 'user_submit_id' ] );
			
		}
		else {
			
			$params[ 'user_submit_id' ] = array( $params[ 'user_submit_id' ] );
			
		}
		
		foreach( $params[ 'user_submit_id' ] as $k => $usid ) {
			
			if ( ! ( $usid AND is_numeric( $usid ) AND is_int( $usid + 0 ) ) ) {
				
				unset( $params[ 'user_submit_id' ][ $k ] );
				
			}
			
		}
		
		$params[ 'current_page' ] =								isset( $url_params[ 'cp' ] ) ? $url_params[ 'cp' ] : NULL; // current page
		$params[ 'items_per_page' ] =							isset( $url_params[ 'ipp' ] ) ? $url_params[ 'ipp' ] : NULL; // items per page
		$params[ 'order_by' ] =									isset( $url_params[ 'ob' ] ) ? $url_params[ 'ob' ] : NULL; // order by
		$params[ 'order_by_direction' ] =						isset( $url_params[ 'obd' ] ) ? $url_params[ 'obd' ] : NULL; // order by direction
		$params[ 'search' ] =									isset( $url_params[ 's' ] ) ? ( int )( ( bool ) $url_params[ 's' ] ) : NULL; // search flag
		$params[ 'filters' ] =									isset( $url_params[ 'f' ] ) ? json_decode( base64_decode( urldecode( $url_params[ 'f' ] ) ), TRUE ) : array(); // filters
		
		return $params;
		
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Get the current url params and return the params allowed
	 *
	 * @access private
	 * @return array
	 */
	
	public function api( $config ) {
		
		// -------------------------------------------------
		// Parsing vars ------------------------------------
		
		$allowed_actions = array(
			
			'getsf', // get submit form
			'getus', // get user submit
			
		);
		
		if ( isset( $config[ 'a' ] ) AND ! in_array( $config[ 'a' ], $allowed_actions ) ) {
			
			exit( lang( 'unknow_action' ) );
			
		}
		
		$_config[ 'action' ] =									isset( $config[ 'a' ] ) ? $config[ 'a' ] : 'getsf'; // action
		$_config[ 'sub_action' ] =								isset( $config[ 'sa' ] ) ? $config[ 'sa' ] : NULL; // sub action
		$_config[ 'layout' ] =									isset( $config[ 'l' ] ) ? $config[ 'l' ] : 'default'; // layout
		
		
		$_config[ 'submit_form_id' ] =							isset( $config[ 'sfid' ] ) ? $config[ 'sfid' ] : NULL; // submit form id(s)
		
		// Adjusting the submit form id or ids
		if ( strpos( $_config[ 'submit_form_id' ] , ',' ) ) {
			
			$_config[ 'submit_form_id' ]  = explode( ',', $_config[ 'submit_form_id' ] );
			
		}
		else if ( ! is_array( $_config[ 'submit_form_id' ] ) AND is_numeric( $_config[ 'submit_form_id' ] ) ) {
			
			$_config[ 'submit_form_id' ]  = array( $_config[ 'submit_form_id' ] );
			
		}
		
		foreach( $_config[ 'submit_form_id' ] as $k => $sfid ) {
			
			if ( ! ( $sfid AND is_numeric( $sfid ) AND is_int( $sfid + 0 ) ) ) {
				
				unset( $_config[ 'submit_form_id' ][ $k ] );
				
			}
			
		}
		
		$_config[ 'user_submit_id' ] =							isset( $config[ 'usid' ] ) ? $config[ 'usid' ] : NULL; // user submit id(s)
		
		// Adjusting the users submits id or ids
		
		if ( strpos( $_config[ 'user_submit_id' ], ',' ) ) {
			
			$_config[ 'user_submit_id' ] = explode( ',', $_config[ 'user_submit_id' ] );
			
		}
		else if ( ! is_array( $_config[ 'user_submit_id' ] ) AND is_numeric( $_config[ 'user_submit_id' ] ) ) {
			
			$_config[ 'user_submit_id' ]  = array( $_config[ 'user_submit_id' ] );
			
		}
		
		foreach( $_config[ 'user_submit_id' ] as $k => $usid ) {
			
			if ( ! ( $usid AND is_numeric( $usid ) AND is_int( $usid + 0 ) ) ) {
				
				unset( $_config[ 'user_submit_id' ][ $k ] );
				
			}
			
		}
		
		$_config[ 'current_page' ] =							isset( $config[ 'cp' ] ) ? $config[ 'cp' ] : NULL; // current page
		$_config[ 'items_per_page' ] =							isset( $config[ 'ipp' ] ) ? $config[ 'ipp' ] : NULL; // items per page
		$_config[ 'order_by' ] =								isset( $config[ 'ob' ] ) ? $config[ 'ob' ] : NULL; // order by
		$_config[ 'order_by_direction' ] =						isset( $config[ 'obd' ] ) ? $config[ 'obd' ] : NULL; // order by direction
		$_config[ 'search' ] =									isset( $config[ 's' ] ) ? ( int )( ( bool ) $config[ 's' ] ) : NULL; // search flag
		$_config[ 'filters' ] =								isset( $config[ 'f' ] ) ? json_decode( base64_decode( urldecode( $config[ 'f' ] ) ), TRUE ) : array(); // filters
		$_config[ 'download' ] =								isset( $config[ 'dl' ] ) ? ( int )( ( bool ) $config[ 'dl' ] ) : NULL; // download flag
		$_config[ 'filename' ] =								isset( $config[ 'fn' ] ) ? $config[ 'fn' ] : NULL; // file name without extension
		
		// Adjusting the file name
		
		$_fn_prefix = gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] );
		$_fn_prefix = strftime( '%Y-%m-%d %T', $_fn_prefix );
		$_fn_prefix = 'sf-api-' . $_fn_prefix;
		
		$_config[ 'filename' ] =  url_title( ( ( $_config[ 'filename' ] ) ? $_config[ 'filename' ] : $_fn_prefix ) );
		
		$_config[ 'include_us' ] =								isset( $config[ 'ius' ] ) ? $config[ 'ius' ] : FALSE; // include users submits
		$_config[ 'content_type' ] =							isset( $config[ 'ct' ] ) ? $config[ 'ct' ] : 'json'; // content type: json (default), xml
		$_config[ 'get_mode' ] =								isset( $config[ 'gm' ] ) ? $config[ 'gm' ] : 'compact'; // get mode: full or compact
		$_config[ 'csv_delimiter' ] =							isset( $config[ 'csvd' ] ) ? $config[ 'csvd' ] : NULL; // csv delimiter
		$_config[ 'csv_enclosure' ] =							( isset( $config[ 'csvd' ] ) AND $config[ 'csvd' ] !== FALSE ) ? $config[ 'csvd' ] : NULL; // csv enclosure
		$_config[ 'force_all_string' ] =						isset( $config[ 'csvfas' ] ) ? $config[ 'csvfas' ] : FALSE; // treat all collumns as string (use enclosure)
		$_config[ 'username' ] =								isset( $config[ 'u' ] ) ? $config[ 'u' ] : NULL; // username
		$_config[ 'password' ] =								isset( $config[ 'p' ] ) ? $config[ 'p' ] : NULL; // password
		
		// Parsing vars ------------------------------------
		// -------------------------------------------------
		
		$config = $_config;
		unset( $_config );
		
		// getting the component global params
		$component_params = $this->current_component[ 'params' ];
		
		$data[ 'params' ] = $component_params;
		
		foreach( $config as $k => $v ) {
			
			$data[ 'config' ][ $k ] = & $v;
			
		}
		
	}
	
	// --------------------------------------------------------------------
	
}
