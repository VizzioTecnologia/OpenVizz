<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_submits_module extends CI_Model{
	
	public function run( $module_data = NULL ){
		
		/*
		 * -------------------------------------------------------------------------------------------------
		 * Loading models and helpers
		 */
		
		if ( ! $this->load->is_model_loaded( 'sfcm' ) ) {
			
			$this->load->model( 'common/submit_forms_common_model', 'sfcm' );
			
		}
		
		$this->load->language( 'submit_forms' );
		
		$this->load->helper(
			
			array(
				
				'html',
				
			)
			
		);
		
		/*
		 * -------------------------------------------------------------------------------------------------
		 */
		 
		$layout = $module_data[ 'params' ][ 'users_submits_layout' ];
		
		//print_r( $module_data[ 'params' ] );
		
		$submit_form_id = $module_data[ 'params' ][ 'submit_form_id' ];
		
		// get submit form params
		$gsfp = array(
			
			'where_condition' => 't1.id = ' . $submit_form_id,
			'limit' => 1,
			
		);
		
		if ( ( $submit_form = $this->sfcm->get_submit_forms( $gsfp )->row_array() ) ){
			
			$this->sfcm->parse_sf( $submit_form );
			$data[ 'submit_form' ] = & $submit_form;
			
			$this->load->library( 'search' );
			
			$search_config = array(
				
				'plugins' => 'sf_us_search',
				'allow_empty_terms' => TRUE,
				'plugins_params' => array(
					
					'sf_us_search' => array(
						
						'sf_id' => $submit_form_id,
						
					),
					
				),
				
			);
			
			$ob_fields = array(
				
				'id',
				'submit_datetime',
				'mod_datetime',
				'random',
				
			);
			
			foreach( $submit_form[ 'fields' ] as $k => $field ){
				
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
			
			$ob = 'id';
			$obd = 'ASC';
			
			if ( isset( $module_data[ 'params' ][ 'users_submits_order_by_field' ] ) ) {
				
				$ob = $module_data[ 'params' ][ 'users_submits_order_by_field' ];
				
			}
			
			if ( isset( $module_data[ 'params' ][ 'users_submits_order_by_direction' ] ) ) {
				
				$obd = $module_data[ 'params' ][ 'users_submits_order_by_direction' ];
				
			}
			
			if ( $ob == 'random' OR $obd == 'random' ) {
				
				$search_config[ 'plugins_params' ][ 'sf_us_search' ][ 'random' ] = TRUE;
				
			}
			else {
				
				$search_config[ 'plugins_params' ][ 'sf_us_search' ][ 'order_by' ] = $data[ 'order_by' ] = $ob;
				$search_config[ 'plugins_params' ][ 'sf_us_search' ][ 'order_by_direction' ] = $data[ 'order_by_direction' ] = $obd;
				
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
			Filters
			--------------------------------------------------------
			*/
			
			$f = array();
			
			$_default_results_filters = check_var( $module_data[ 'params' ][ 'us_default_results_filters' ] ) ? json_decode( $module_data[ 'params' ][ 'us_default_results_filters' ], TRUE ) : array();
			$_default_results_filters = is_array( $_default_results_filters ) ? $_default_results_filters : array();
			
			if ( $_default_results_filters ){
				
				$f = array_merge_recursive( json_decode( $module_data[ 'params' ][ 'us_default_results_filters' ], TRUE ), $f );
				
			}
			
			$search_config[ 'plugins_params' ][ 'sf_us_search' ][ 'filters' ] = $f;
			
			/*
			--------------------------------------------------------
			Filters
			--------------------------------------------------------
			********************************************************
			*/
			
			foreach ( $submit_form[ 'fields' ] as $key => $field ) {
				
				$alias = isset( $field[ 'alias' ] ) ? $field[ 'alias' ] : $this->sfcm->make_field_name( $field[ 'label' ] );
				
				$data[ 'fields' ][ $alias ] = $field;
				$data[ 'fields' ][ $alias ][ 'alias' ] = $alias;
				
			}
			
			/*
			********************************************************
			--------------------------------------------------------
			Num items
			--------------------------------------------------------
			*/
			
			$ipp = NULL;
			$cp = 1;
			
			if ( isset( $module_data[ 'params' ][ 'users_submits_module_items_per_page' ] ) ) {
				
				$ipp = ( int ) $module_data[ 'params' ][ 'users_submits_module_items_per_page' ];
				
			}
			
			if ( $ipp < 1 OR ! is_int( $ipp ) ) $ipp = $this->mcm->filtered_system_params[ $this->mcm->environment . '_items_per_page' ];
			$search_config[ 'ipp' ] = $ipp;
			$search_config[ 'cp' ] = $cp;
			
			/*
			--------------------------------------------------------
			Num items
			--------------------------------------------------------
			********************************************************
			*/
			
			$this->search->config( $search_config );
			$users_submits = $this->search->get_full_results( 'sf_us_search', TRUE );
			
			if ( count( $users_submits ) == 0 AND ! check_var( $module_data[ 'params' ][ 'us_module_dont_show_on_no_results' ] ) ) {
				
				return FALSE;
				
			}
			
			$data[ 'users_submits_total_results' ] = $this->search->count_all_results( 'sf_us_search' );
			
			
			if ( check_var( $users_submits ) ) {
				
				if ( ! in_array( $module_data[ 'params' ][ 'users_submits_order_by_field' ], array( 'id', 'submit_datetime', 'mod_datetime' ) ) ) {
					
					foreach ( $users_submits as $key => & $user_submit ) {
						
						$user_submit[ 'data' ] = get_params( $user_submit[ 'data' ] );
						
						$user_submit[ 'order_by_' . $module_data[ 'params' ][ 'users_submits_order_by_field' ] ] = isset( $user_submit[ 'data' ][ $module_data[ 'params' ][ 'users_submits_order_by_field' ] ] ) ? $user_submit[ 'data' ][ $module_data[ 'params' ][ 'users_submits_order_by_field' ] ] : 'id';
						
					}
					
					$users_submits = multi_sort( $users_submits, 'order_by_' . $module_data[ 'params' ][ 'users_submits_order_by_field' ] );
					
					if ( check_var( $module_data[ 'params' ][ 'users_submits_order_by_direction' ] ) AND strtolower( $module_data[ 'params' ][ 'users_submits_order_by_direction' ] ) == 'desc' ){
						
						rsort( $users_submits );
						
					}
					
				}
				else {
					
					foreach ( $users_submits as $key => & $user_submit ) {
						
						$user_submit[ 'data' ] = get_params( $user_submit[ 'data' ] );
						
					}
					
				}
				
			}
			
			$data[ 'users_submits' ] = $users_submits;
			
			
			
			$theme_views_url = call_user_func( $this->mcm->environment . '_theme_modules_views_url' ) . '/' . 'users_submits' . '/' . $layout;
			$theme_load_views_path = call_user_func( $this->mcm->environment . '_theme_modules_views_path' ) . 'users_submits' . DS . $layout . DS;
			$theme_views_path = THEMES_PATH . $theme_load_views_path;
			
			$default_modules_views_styles_path = MODULES_VIEWS_STYLES_PATH . 'users_submits' . DS . $layout . DS;
			$default_modules_views_styles_url = MODULES_VIEWS_STYLES_URL . '/' . 'users_submits' . '/' . $layout;
			$default_load_views_path = MODULES_DIR_NAME . DS . 'users_submits' . DS . $layout . DS;
			$default_views_path = VIEWS_PATH . $default_load_views_path;
			
			$data[ 'module_data' ] = & $module_data;
			
			$output_html = lang( 'layout_not_found' );
			
			// verificando se o tema atual possui a view
			if ( file_exists( $theme_views_path . $layout . '.php') ){
				
				$data[ 'module_data' ][ 'load_view_path' ] = $theme_load_views_path;
				$data[ 'module_data' ][ 'view_path' ] = $theme_views_path;
				
				$output_html = $this->load->view( $theme_load_views_path . $layout, $data, TRUE );
				
			}
			// verificando se a view existe no diretório de views padrão
			else if ( file_exists( $default_views_path . $layout . '.php') ){
				
				$data[ 'module_data' ][ 'load_view_path' ] = $default_load_views_path;
				$data[ 'module_data' ][ 'view_path' ] = $default_views_path;
				
				$output_html = $this->load->view( $default_load_views_path . $layout, $data, TRUE );
				
			}
			
			return $output_html;
			
		}
		
		return FALSE;
		
	}
	
	public function get_module_params( $current_params_values = NULL ){
		
		//print_r( $current_params_values );
		
		/*
		 * -------------------------------------------------------------------------------------------------
		 */
		
		if ( ! $this->load->is_model_loaded( 'sfcm' ) ) {
			
			$this->load->model( 'common/submit_forms_common_model', 'sfcm' );
			
		}
		
		$this->load->language( 'submit_forms' );
		
		$this->load->helper(
			
			array(
				
				'html',
				
			)
			
		);
		
		/*
		 * -------------------------------------------------------------------------------------------------
		 */
		
		// If we have submit forms
		if ( ( $submit_forms = $this->sfcm->get_submit_forms()->result_array() ) ){
			
			/*
			* -------------------------------------------------------------------------------------------------
			*/
			
			$params = array();
			
			if ( file_exists( MODULES_PATH . 'users_submits' . DS . 'params.xml' ) ) {
				
				$params = get_params_spec_from_xml( MODULES_PATH . 'users_submits' . DS . 'params.xml' );
				
			}
			
			if ( check_var( $params[ 'params_spec_values' ] ) ) {
				
				$current_params_values = filter_params( $params[ 'params_spec_values' ], $current_params_values );
				
			}
			
			$directories_options = array( MEDIA_DIR_NAME => MEDIA_DIR_NAME );
			$directories_options = $directories_options + scandir_to_array( MEDIA_DIR_NAME );
			
			// carregando os layouts do tema atual
			$_options_us_layouts = dir_list_to_array( THEMES_PATH . site_theme_modules_views_path() . 'users_submits' );
			// carregando os layouts do diretório de views padrão
			$_options_us_layouts = array_merge( $_options_us_layouts, dir_list_to_array( MODULES_VIEWS_PATH . 'users_submits' ) );
			
			foreach ( $submit_forms as $key => $_submit_form ) {
				
				if ( ! check_var( $current_params_values[ 'submit_form_id' ] ) ) {
					
					$current_params_values[ 'submit_form_id' ] = $_submit_form[ 'id' ];
					
				}
				
				if ( check_var( $current_params_values[ 'submit_form_id' ] ) AND $_submit_form[ 'id' ] == $current_params_values[ 'submit_form_id' ] ) {
					
					$submit_form[ 'fields' ] = get_params( $_submit_form[ 'fields' ] );
					$submit_form[ 'params' ] = get_params( $_submit_form[ 'params' ] );
					
				}
				
				$_options_sf[ $_submit_form[ 'id' ] ] = $_submit_form[ 'title' ];
				
			}
			
			foreach ( $params[ 'params_spec' ][ 'users_submits_module_config' ] as $key => $element ) {
				
				$_params_spec_key = 'users_submits_module_config';
				
				if ( $element[ 'name' ] == 'submit_form_id' ){
					
					$spec_options = array();
					
					if ( isset( $params[ 'params_spec' ][ $_params_spec_key ][ $key ][ 'options' ] ) )
						$spec_options = $params[ 'params_spec' ][ $_params_spec_key ][ $key ][ 'options' ];
					
					$params[ 'params_spec' ][ $_params_spec_key ][ $key ][ 'options' ] = is_array( $spec_options ) ? $spec_options + $_options_sf : $_options_sf;
					
				}
				
				if ( $element[ 'name' ] == 'users_submits_layout' ){
					
					$spec_options = array();
					
					if ( isset( $params[ 'params_spec' ][ $_params_spec_key ][ $key ][ 'options' ] ) )
						$spec_options = $params[ 'params_spec' ][ $_params_spec_key ][ $key ][ 'options' ];
					
					$params[ 'params_spec' ][ $_params_spec_key ][ $key ][ 'options' ] = is_array( $spec_options ) ? $spec_options + $_options_us_layouts : $_options_us_layouts;
					
				}
				
			}
			
			/*
			* -------------------------------------------------------------------------------------------------
			*/
			
			if ( check_var( $submit_form ) ) {
				
				/*
				* ------------------------------------
				*/
				
				$new_params[] = array(
					
					'type' => 'spacer',
					'label' => 'ud_title_prop',
					'tip' => 'tip_ud_title_prop',
					
				);
				
				/*
				* ------------------------------------
				*/
				
				$_tmp = array(
					
					'type' => 'checkbox',
					'inline' => TRUE,
					'name' => 'ud_title_prop[id]',
					'label' => 'id',
					'value' => 'id',
					'validation' => array(
						
						'rules' => 'trim',
						
					),
					
				);
				
				if ( ! check_var( $current_params_values ) AND ! check_var( $current_params_values[ 'ud_title_prop[id]' ] ) ) {
					
					$params[ 'params_spec_values' ][ 'ud_title_prop[id]' ] = 'id';
					
				}
				
				$new_params[] = $_tmp;
				
				/*
				* ------------------------------------
				*/
				
				$_tmp = array(
					
					'type' => 'checkbox',
					'inline' => TRUE,
					'name' => 'ud_title_prop[submit_datetime]',
					'label' => 'submit_datetime',
					'value' => 'submit_datetime',
					'validation' => array(
						
						'rules' => 'trim',
						
					),
					
				);
				
				if ( ! check_var( $current_params_values ) AND ! check_var( $current_params_values[ 'ud_title_prop[submit_datetime]' ] ) ) {
					
					$params[ 'params_spec_values' ][ 'ud_title_prop[submit_datetime]' ] = 'submit_datetime';
					
				}
				
				$new_params[] = $_tmp;
				
				/*
				* ------------------------------------
				*/
				
				$_tmp = array(
					
					'type' => 'checkbox',
					'inline' => TRUE,
					'name' => 'ud_title_prop[mod_datetime]',
					'label' => 'mod_datetime',
					'value' => 'mod_datetime',
					'validation' => array(
						
						'rules' => 'trim',
						
					),
					
				);
				
				if ( ! check_var( $current_params_values ) AND ! check_var( $current_params_values[ 'ud_title_prop[mod_datetime]' ] ) ) {
					
					$params[ 'params_spec_values' ][ 'ud_title_prop[mod_datetime]' ] = 'mod_datetime';
					
				}
				
				$new_params[] = $_tmp;
				
				/*
				* ------------------------------------
				*/
				
				foreach ( $submit_form[ 'fields' ] as $key => $field ) {
					
					$alias = check_var( $field[ 'alias' ] ) ? $field[ 'alias' ] : FALSE;
					
					if ( $alias ) {
						
						$fields_options[ $alias ] = $field[ 'label' ];
						
						$_tmp = array(
							
							'type' => 'checkbox',
							'inline' => TRUE,
							'name' => 'ud_title_prop[' . $alias . ']',
							'label' => $field[ 'label' ],
							'value' => $alias,
							'validation' => array(
								
								'rules' => 'trim',
								
							),
							
						);
						
						if ( ! in_array( $field[ 'field_type' ], array( 'button', 'html', ) ) ) {
							
							if ( ! check_var( $current_params_values ) AND ! check_var( $current_params_values[ 'ud_title_prop[' . $alias . ']' ] ) ) {
								
								$params[ 'params_spec_values' ][ 'ud_title_prop[' . $alias . ']' ] = $alias;
								
							}
							
						}
						
						if ( $field[ 'field_type' ] == 'combo_box' ) {
							
							$select_fields[] = $field;
							
						}
						
						$new_params[] = $_tmp;
						
					}
					
				}
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				/*
				* ------------------------------------
				*/
				
				$new_params[] = array(
					
					'type' => 'spacer',
					'label' => 'ud_content_prop',
					'tip' => 'tip_ud_content_prop',
					
				);
				
				/*
				* ------------------------------------
				*/
				
				$_tmp = array(
					
					'type' => 'checkbox',
					'inline' => TRUE,
					'name' => 'ud_content_prop[id]',
					'label' => 'id',
					'value' => 'id',
					'validation' => array(
						
						'rules' => 'trim',
						
					),
					
				);
				
				if ( ! check_var( $current_params_values ) AND ! check_var( $current_params_values[ 'ud_content_prop[id]' ] ) ) {
					
					$params[ 'params_spec_values' ][ 'ud_content_prop[id]' ] = 'id';
					
				}
				
				$new_params[] = $_tmp;
				
				/*
				* ------------------------------------
				*/
				
				$_tmp = array(
					
					'type' => 'checkbox',
					'inline' => TRUE,
					'name' => 'ud_content_prop[submit_datetime]',
					'label' => 'submit_datetime',
					'value' => 'submit_datetime',
					'validation' => array(
						
						'rules' => 'trim',
						
					),
					
				);
				
				if ( ! check_var( $current_params_values ) AND ! check_var( $current_params_values[ 'ud_content_prop[submit_datetime]' ] ) ) {
					
					$params[ 'params_spec_values' ][ 'ud_content_prop[submit_datetime]' ] = 'submit_datetime';
					
				}
				
				$new_params[] = $_tmp;
				
				/*
				* ------------------------------------
				*/
				
				$_tmp = array(
					
					'type' => 'checkbox',
					'inline' => TRUE,
					'name' => 'ud_content_prop[mod_datetime]',
					'label' => 'mod_datetime',
					'value' => 'mod_datetime',
					'validation' => array(
						
						'rules' => 'trim',
						
					),
					
				);
				
				if ( ! check_var( $current_params_values ) AND ! check_var( $current_params_values[ 'ud_content_prop[mod_datetime]' ] ) ) {
					
					$params[ 'params_spec_values' ][ 'ud_content_prop[mod_datetime]' ] = 'mod_datetime';
					
				}
				
				$new_params[] = $_tmp;
				
				/*
				* ------------------------------------
				*/
				
				foreach ( $submit_form[ 'fields' ] as $key => $field ) {
					
					$alias = check_var( $field[ 'alias' ] ) ? $field[ 'alias' ] : FALSE;
					
					if ( $alias ) {
						
						$fields_options[ $alias ] = $field[ 'label' ];
						
						$_tmp = array(
							
							'type' => 'checkbox',
							'inline' => TRUE,
							'name' => 'ud_content_prop[' . $alias . ']',
							'label' => $field[ 'label' ],
							'value' => $alias,
							'validation' => array(
								
								'rules' => 'trim',
								
							),
							
						);
						
						if ( ! in_array( $field[ 'field_type' ], array( 'button', 'html', ) ) ) {
							
							if ( ! check_var( $current_params_values ) AND ! check_var( $current_params_values[ 'ud_content_prop[' . $alias . ']' ] ) ) {
								
								$params[ 'params_spec_values' ][ 'ud_content_prop[' . $alias . ']' ] = $alias;
								
							}
							
						}
						
						if ( $field[ 'field_type' ] == 'combo_box' ) {
							
							$select_fields[] = $field;
							
						}
						
						$new_params[] = $_tmp;
						
					}
					
				}
				
				/*
				* ------------------------------------
				*/
				
				$_tmp = array(
					
					'type' => 'number',
					'name' => 'users_submit_content_word_limit',
					'min' => 1,
					'label' => 'users_submit_content_word_limit',
					'validation' => array(
						
						'rules' => 'trim|integer',
						
					),
					
				);
				
				if ( ! check_var( $current_params_values[ 'users_submit_content_word_limit' ] ) ) {
					
					$params[ 'params_spec_values' ][ 'users_submit_content_word_limit' ] = 120;
					
				}
				
				$new_params[] = $_tmp;
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				/*
				* ------------------------------------
				*/

				$new_params[] = array(
					
					'type' => 'spacer',
					'label' => 'ud_other_info_prop',
					'tip' => 'tip_ud_other_info_prop',
					
				);

				/*
				* ------------------------------------
				*/

				$_tmp = array(
					
					'type' => 'checkbox',
					'inline' => TRUE,
					'name' => 'ud_other_info_prop[id]',
					'label' => 'id',
					'value' => 'id',
					'validation' => array(
						
						'rules' => 'trim',
						
					),
					
				);

				if ( ! check_var( $current_params_values ) AND ! check_var( $current_params_values[ 'ud_other_info_prop[id]' ] ) ) {
					
					$params[ 'params_spec_values' ][ 'ud_other_info_prop[id]' ] = 'id';
					
				}

				$new_params[] = $_tmp;

				/*
				* ------------------------------------
				*/

				$_tmp = array(
					
					'type' => 'checkbox',
					'inline' => TRUE,
					'name' => 'ud_other_info_prop[submit_datetime]',
					'label' => 'submit_datetime',
					'value' => 'submit_datetime',
					'validation' => array(
						
						'rules' => 'trim',
						
					),
					
				);

				if ( ! check_var( $current_params_values ) AND ! check_var( $current_params_values[ 'ud_other_info_prop[submit_datetime]' ] ) ) {
					
					$params[ 'params_spec_values' ][ 'ud_other_info_prop[submit_datetime]' ] = 'submit_datetime';
					
				}

				$new_params[] = $_tmp;

				/*
				* ------------------------------------
				*/

				$_tmp = array(
					
					'type' => 'checkbox',
					'inline' => TRUE,
					'name' => 'ud_other_info_prop[mod_datetime]',
					'label' => 'mod_datetime',
					'value' => 'mod_datetime',
					'validation' => array(
						
						'rules' => 'trim',
						
					),
					
				);

				if ( ! check_var( $current_params_values ) AND ! check_var( $current_params_values[ 'ud_other_info_prop[mod_datetime]' ] ) ) {
					
					$params[ 'params_spec_values' ][ 'ud_other_info_prop[mod_datetime]' ] = 'mod_datetime';
					
				}

				$new_params[] = $_tmp;

				/*
				* ------------------------------------
				*/

				foreach ( $submit_form[ 'fields' ] as $key => $field ) {
					
					$alias = check_var( $field[ 'alias' ] ) ? $field[ 'alias' ] : FALSE;
					
					if ( $alias ) {
						
						$fields_options[ $alias ] = $field[ 'label' ];
						
						$_tmp = array(
							
							'type' => 'checkbox',
							'inline' => TRUE,
							'name' => 'ud_other_info_prop[' . $alias . ']',
							'label' => $field[ 'label' ],
							'value' => $alias,
							'validation' => array(
								
								'rules' => 'trim',
								
							),
							
						);
						
						if ( ! in_array( $field[ 'field_type' ], array( 'button', 'html', ) ) ) {
							
							if ( ! check_var( $current_params_values ) AND ! check_var( $current_params_values[ 'ud_other_info_prop[' . $alias . ']' ] ) ) {
								
								$params[ 'params_spec_values' ][ 'ud_other_info_prop[' . $alias . ']' ] = $alias;
								
							}
							
						}
						
						if ( $field[ 'field_type' ] == 'combo_box' ) {
							
							$select_fields[] = $field;
							
						}
						
						$new_params[] = $_tmp;
						
					}
					
				}
				
				
				
				
				
				
				
				
				
				
				
				
				
				/*
				* ------------------------------------
				*/
				
				$new_params[] = array(
					
					'type' => 'spacer',
					'label' => 'us_results',
					
				);
				
				/*
				* ------------------------------------
				*/
				
				$_tmp = array(
					
					'type' => 'textarea',
					'inline' => TRUE,
					'name' => 'us_default_results_filters',
					'label' => 'us_default_results_filters',
					'default' => '',
					'tip' => 'us_default_results_filters',
					'validation' => array(
						
						'rules' => 'trim',
						
					),
					
				);
				
				$new_params[] = $_tmp;
				
				/*
				* ------------------------------------
				*/
				
				$_tmp = array(
					
					'type' => 'select',
					'inline' => TRUE,
					'name' => 'users_submits_order_by_field',
					'label' => 'users_submits_order_by_field',
					'tip' => 'tip_users_submits_order_by_field',
					'validation' => array(
						
						'rules' => 'trim|required',
						
					),
					'options' => array(
						
						'random' => 'random',
						'id' => 'id',
						'submit_datetime' => 'submit_datetime',
						'mod_datetime' => 'mod_datetime',
						
					),
					
				);
				
				$_tmp[ 'options' ] = array_merge( $_tmp[ 'options' ], $fields_options );
				
				$params[ 'params_spec_values' ][ 'users_submits_order_by_field' ] = 'submit_datetime';
				
				$new_params[] = $_tmp;
				
				/*
					* ------------------------------------
				*/
				
				$_tmp = array(
					
					'type' => 'select',
					'inline' => TRUE,
					'name' => 'users_submits_order_by_direction',
					'label' => 'users_submits_order_by_direction',
					'tip' => 'tip_users_submits_order_by_direction',
					'validation' => array(
						
						'rules' => 'trim|required',
						
					),
					'options' => array(
						
						'ASC' => 'ascendant',
						'DESC' => 'decrescent',
						
					),
					
				);
				
				$params[ 'params_spec_values' ][ 'users_submits_order_by_direction' ] = 'DESC';
				
				$new_params[] = $_tmp;
				
				/*
				* ------------------------------------
				*/
				
				$_tmp = array(
					
					'type' => 'number',
					'inline' => TRUE,
					'min' => 1,
					'name' => 'users_submits_module_items_per_page',
					'label' => 'users_submits_num_items_to_show',
					'tip' => 'tip_users_submits_num_items_to_show',
					'validation' => array(
						
						'rules' => 'trim|required',
						
					),
					
				);
				
				$params[ 'params_spec_values' ][ 'users_submits_items_per_page' ] = 3;
				
				$new_params[] = $_tmp;
				
				/*
				* ------------------------------------
				*/
				
				$_tmp = array(
					
					'type' => 'select',
					'inline' => TRUE,
					'name' => 'us_module_dont_show_on_no_results',
					'label' => 'us_module_dont_show_on_no_results',
					'validation' => array(
						
						'rules' => 'trim|required',
						
					),
					'options' => array(
						
						1 => 'yes',
						0 => 'no',
						
					),
					
				);
				
				$params[ 'params_spec_values' ][ 'us_module_dont_show_on_no_results' ] = 1;
				
				$new_params[] = $_tmp;
				
				/*
				* ------------------------------------
				*/
				
				$_tmp = array(
					
					'type' => 'textarea',
					'inline' => TRUE,
					'name' => 'us_module_no_results_message',
					'label' => 'us_module_no_results_message',
					'default' => '',
					'tip' => 'tip_us_module_no_results_message',
					'validation' => array(
						
						'rules' => 'trim',
						
					),
					
				);
				
				$new_params[] = $_tmp;
				
				/*
				* ------------------------------------
				*/
				
				$_tmp = array(
					
					'type' => 'spacer',
					'label' => 'readmore',
					
				);
				
				$new_params[] = $_tmp;
				
				/*
				* ------------------------------------
				*/
				
				$_tmp = array(
					
					'type' => 'select',
					'inline' => TRUE,
					'name' => 'show_readmore_link',
					'label' => 'show_readmore_link',
					'tip' => 'tip_show_readmore_link',
					'validation' => array(
						
						'rules' => 'trim|required',
						
					),
					'options' => array(
						
						1 => 'yes',
						0 => 'no',
						
					),
					
				);
				
				$params[ 'params_spec_values' ][ 'show_readmore_link' ] = 0;
				
				$new_params[] = $_tmp;
				
				/*
				* ------------------------------------
				*/
				
				$_tmp = array(
					
					'type' => 'select',
					'inline' => TRUE,
					'name' => 'hide_on_no_results',
					'label' => 'hide_on_no_results',
					'validation' => array(
						
						'rules' => 'trim|required',
						
					),
					'options' => array(
						
						1 => 'yes',
						0 => 'no',
						
					),
					
				);
				
				$params[ 'params_spec_values' ][ 'hide_on_no_results' ] = 0;
				
				$new_params[] = $_tmp;
				
				/*
					* ------------------------------------
				*/
				
				$_tmp = array(
					
					'type' => 'text',
					'inline' => TRUE,
					'name' => 'readmore_text',
					'label' => 'readmore_text',
					'tip' => 'tip_readmore_text',
					'validation' => array(
						
						'rules' => 'trim|required',
						
					),
					
				);
				
				$params[ 'params_spec_values' ][ 'readmore_text' ] = 'readmore';
				
				$new_params[] = $_tmp;
				
				/*
					* ------------------------------------
				*/
				$_tmp = array(
					
					'type' => 'text',
					'inline' => TRUE,
					'name' => 'readmore_url',
					'label' => 'readmore_url',
					'tip' => 'tip_readmore_url',
					'validation' => array(
						
						'rules' => 'trim',
						
					),
					
				);
				
				$params[ 'params_spec_values' ][ 'readmore_url' ] = '';
				
				$new_params[] = $_tmp;
				
				/*
					* ------------------------------------
				*/
				
				$_tmp = array(
					
					'type' => 'select',
					'inline' => TRUE,
					'name' => 'readmore_link_target',
					'label' => 'readmore_link_target',
					'validation' => array(
						
						'rules' => 'trim',
						
					),
					'options' => array(
						
						'' => 'combobox_select',
						'_self' => 'url_target_self',
						'_blank' => 'url_target_blank',
						
					),
					
				);
				
				$params[ 'params_spec_values' ][ 'readmore_link_target' ] = '';
				
				$new_params[] = $_tmp;
				
				/*
					* ------------------------------------
				*/
				
				array_push_pos( $params[ 'params_spec' ][ 'users_submits_module_config' ], $new_params, 10  );
				
				if ( isset( $current_params_values[ 'users_submits_layout' ] ) AND $current_params_values[ 'users_submits_layout' ] != 'global' ) {
					
					$system_views_path = MODULES_VIEWS_PATH . 'users_submits' . DS;
					$theme_views_path = THEMES_PATH . site_theme_modules_views_path() . 'users_submits' . DS;
					
					if ( file_exists( $system_views_path . $current_params_values[ 'users_submits_layout' ] . DS . 'params.php' ) ) {
						
						include_once $system_views_path . $current_params_values[ 'users_submits_layout' ] . DS . 'params.php';
						
					}
					
					//echo '<pre>' . print_r( $layout_params, TRUE ) . '</pre>';
					
				}
				
			}
			
			/*
			* -------------------------------------------------------------------------------------------------
			*/
			
		}
		else {
			
			if ( file_exists( MODULES_PATH . 'users_submits/params_no_submit_forms.xml' ) ) {
				
				$params = get_params_spec_from_xml( MODULES_PATH . 'users_submits/params_no_submit_forms.xml' );
				
			}
			
		}
		
		return $params;
		
	}
	
}
