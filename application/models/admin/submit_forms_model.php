<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Submit_forms_model extends CI_Model{
	
	public function get_component_url_admin(){
		
		return RELATIVE_BASE_URL . '/' . ADMIN_ALIAS . '/submit_forms';
		
	}
	
	public function menu_item_submit_form(){
		
		if ( isset( $this->mcm->system_params[ 'email_config_enabled' ] ) AND $this->mcm->system_params[ 'email_config_enabled' ] ){
			
			$this->load->model( 'common/submit_forms_common_model', 'sfcm' );
			
			$params = get_params_spec_from_xml( APPPATH . 'controllers/admin/com_submit_forms/menu_item_submit_form.xml' );
			
			// obtendo a lista de layouts
			
			// carregando os layouts do tema atual
			$layouts_options = dir_list_to_array( THEMES_PATH . site_theme_components_views_path() . get_class_name( get_class() ) . DS . 'index' . DS . 'submit_form' );
			// carregando os layouts do diretório de views padrão
			$layouts_options = array_merge( $layouts_options, dir_list_to_array( VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . get_class_name( get_class() ) . DS . 'index' . DS . 'submit_form' ) );
			
			
			$submit_forms = $this->sfcm->get_submit_forms()->result_array();
			$submit_forms_options = array();
			
			foreach ( $submit_forms as $submit_form ){
				
				$submit_forms_options[ $submit_form[ 'id' ] ] = $submit_form[ 'title' ];
				
			}
			
			$current_section = 'submit_form';
			foreach ( $params[ 'params_spec' ][ $current_section ] as $key => $element ) {
				
				if ( $element[ 'name' ] == 'submit_form_id' ){
					
					$spec_options = array();
					
					if ( isset( $params[ 'params_spec' ][ $current_section ][ $key ][ 'options' ] ) )
						$spec_options = $params[ 'params_spec' ][ $current_section ][ $key ][ 'options' ];
					
					$params[ 'params_spec' ][ $current_section ][ $key ][ 'options' ] = is_array( $spec_options ) ? $spec_options + $submit_forms_options : $submit_forms_options;
					
				}
				else if ( $element[ 'name' ] == 'submit_form_layout' ){
					
					$spec_options = array();
					
					if ( isset( $params[ 'params_spec' ][ $current_section ][ $key ][ 'options' ] ) )
						$spec_options = $params[ 'params_spec' ][ $current_section ][ $key ][ 'options' ];
					
					$params[ 'params_spec' ][ $current_section ][ $key ][ 'options' ] = is_array( $spec_options ) ? $spec_options + $layouts_options : $layouts_options;
					
				}
				
			}
			
			//print_r($params);
			
			return $params;
			
		}
		else {
			
			msg( ('email_config_not_enabled' ), 'title' );
			msg( validation_errors( '<div class="error">', '</div>' ), 'error' );
			
			redirect( get_last_url() );
			
		}
		
	}
	
	
	
	
	
	
	
	
	
	public function site_submit_form_base_url(){
		
		return 'submit_forms/index/a/sf';
		
	}
	
	
	
	
	
	
	
	
	public function menu_item_get_link_submit_form( $menu_item_id = NULL, $params = NULL ){
		
		return $this->site_submit_form_base_url() . '/miid/' . $menu_item_id . '/sfid/' . $params[ 'submit_form_id' ];
		
	}
	
	
	
	public function get_link_submit_form( $submit_form, $env = NULL, $menu_item_id = NULL ){
		
		if ( ! isset( $env ) ) {
			
			$env = environment();
			
		}
		
		if ( $env == SITE_ALIAS ) {
			
			return $this->site_submit_form_base_url() . '/miid/' . ( int ) $menu_item_id . '/sfid/' . $submit_form[ 'id' ];
			
		}
		else if ( $env == ADMIN_ALIAS ) {
			
			return '/admin/submit_forms/sfm/a/esf/sfid/' . $submit_form[ 'id' ];
			
		}
		
		return '';
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function menu_item_users_submits( $menu_item = NULL ){
		
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
			
			if ( file_exists( APPPATH . 'controllers/admin/com_submit_forms/menu_item_users_submits.xml' ) ) {
				
				$params = get_params_spec_from_xml( APPPATH . 'controllers/admin/com_submit_forms/menu_item_users_submits.xml' );
				
			}
			
			$post = $this->input->post( NULL, TRUE ) ? $this->input->post( NULL, TRUE ) : NULL;
			
			
			$current_params_values = isset( $menu_item[ 'params' ] ) ? $menu_item[ 'params' ] : array();
			
			// merging with post data
			$current_params_values = isset( $post[ 'params' ] ) ? array_merge_recursive( $current_params_values, $post[ 'params' ] ) : $current_params_values;
			
			if ( check_var( $params[ 'params_spec_values' ] ) ) {
				
				$current_params_values = filter_params( $params[ 'params_spec_values' ], $current_params_values );
				
			}
			
			
			
			//echo '<pre>' . print_r( $params[ 'params_spec_values' ], TRUE ) . '</pre>';
			//echo '<pre>' . print_r( $current_params_values, TRUE ) . '</pre>'; exit;
			
			// obtendo a lista de layouts
			// carregando os layouts do tema atual
			$_options_us_layouts = dir_list_to_array( THEMES_PATH . site_theme_components_views_path() . get_class_name( get_class() ) . DS . 'index' . DS . 'users_submits' );
			// carregando os layouts do diretório de views padrão
			$_options_us_layouts = array_merge( $_options_us_layouts, dir_list_to_array( VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . get_class_name( get_class() ) . DS . 'index' . DS . 'users_submits' ) );
			
			$new_flag = FALSE;
			
			foreach ( $submit_forms as $key => $_submit_form ) {
				
				if ( ! check_var( $current_params_values[ 'submit_form_id' ] ) ) {
					
					$current_params_values[ 'submit_form_id' ] = $_submit_form[ 'id' ];
					
					$new_flag = TRUE;
					
				}
				
				if ( check_var( $current_params_values[ 'submit_form_id' ] ) AND $_submit_form[ 'id' ] == $current_params_values[ 'submit_form_id' ] ) {
					
					$submit_form[ 'fields' ] = get_params( $_submit_form[ 'fields' ] );
					$submit_form[ 'params' ] = get_params( $_submit_form[ 'params' ] );
					
				}
				
				$_options_sf[ $_submit_form[ 'id' ] ] = $_submit_form[ 'title' ];
				
			}
			
			foreach ( $params[ 'params_spec' ][ 'users_submits_config' ] as $key => $element ) {
				
				$_params_spec_key = 'users_submits_config';
				
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
				
				//------------------------------------------------------
				
				$new_params[] = array(
					
					'type' => 'spacer',
					'label' => 'ud_title_prop',
					'tip' => 'tip_ud_title_prop',
					
				);
				
				//------------------------------------------------------
				
				$_tmp = array(
					
					'type' => 'checkbox',
					'name' => 'ud_data_list_d_titles_as_link',
					'label' => 'ud_data_list_d_titles_as_link',
					'tip' => 'tip_ud_data_list_d_titles_as_link',
					'validation' => array(
						
						'rules' => 'trim',
						
					),
					'value' => 1,
					
				);
				
				if ( $new_flag ) {
					
					// $params[ 'params_spec_values' ][ 'ud_data_list_d_titles_as_link' ] = 1;
					
				}
				
				$new_params[] = $_tmp;
				
				//------------------------------------------------------
				
				$new_params[] = array(
					
					'type' => 'spacer',
					
				);
				
				//------------------------------------------------------
				
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

				if ( $new_flag ) {
					
					// $params[ 'params_spec_values' ][ 'ud_title_prop[id]' ] = 'id';
					
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

				if ( $new_flag ) {
					
					// $params[ 'params_spec_values' ][ 'ud_title_prop[submit_datetime]' ] = 'submit_datetime';
					
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

				if ( $new_flag ) {
					
					// $params[ 'params_spec_values' ][ 'ud_title_prop[mod_datetime]' ] = 'mod_datetime';
					
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
							
							if ( $new_flag ) {
								
								// $params[ 'params_spec_values' ][ 'ud_title_prop[' . $alias . ']' ] = $alias;
								
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

				if ( $new_flag ) {
					
					// $params[ 'params_spec_values' ][ 'ud_content_prop[id]' ] = 'id';
					
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

				if ( $new_flag ) {
					
					// $params[ 'params_spec_values' ][ 'ud_content_prop[submit_datetime]' ] = 'submit_datetime';
					
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
				
				if ( $new_flag ) {
					
					// $params[ 'params_spec_values' ][ 'ud_content_prop[mod_datetime]' ] = 'mod_datetime';
					
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
							
							if ( $new_flag ) {
								
								// $params[ 'params_spec_values' ][ 'ud_content_prop[' . $alias . ']' ] = $alias;
								
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

				if ( $new_flag ) {
					
					// $params[ 'params_spec_values' ][ 'ud_other_info_prop[id]' ] = 'id';
					
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

				if ( $new_flag ) {
					
					// $params[ 'params_spec_values' ][ 'ud_other_info_prop[submit_datetime]' ] = 'submit_datetime';
					
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

				if ( $new_flag ) {
					
					// $params[ 'params_spec_values' ][ 'ud_other_info_prop[mod_datetime]' ] = 'mod_datetime';
					
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
							
							if ( $new_flag ) {
								
								// $params[ 'params_spec_values' ][ 'ud_other_info_prop[' . $alias . ']' ] = $alias;
								
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
					
					'type' => 'spacer',
					'label' => 'us_results',
					
				);
				
				$new_params[] = $_tmp;
				
				/*
				* ------------------------------------
				*/
				
				$_tmp = array(
					
					'type' => 'select',
					'name' => 'use_search',
					'label' => 'use_search',
					'tip' => 'tip_use_search',
					'validation' => array(
						
						'rules' => 'trim|required',
						
					),
					'options' => array(
						
						'1' => 'yes',
						'0' => 'no',
						
					),
					
				);
				
				$use_search = TRUE;
				
				if ( ! $new_flag AND ! check_var( $current_params_values[ 'use_search' ] ) ) {
					
					$use_search = FALSE;
					
				}
				
				$params[ 'params_spec_values' ][ 'use_search' ] = 0;
				
				$new_params[] = $_tmp;
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				if ( $use_search ) {
					
					//------------------------------------------------------
					
					$new_params[] = array(
						
						'type' => 'spacer',
						'label' => 'ud_data_list_search_results_specific_config',
						'name' => 'ud_data_list_search_results_specific_config',
						'level' => '4',
						
					);
					
					//------------------------------------------------------
					
					$new_params[] = array(
						
						'type' => 'spacer',
						'label' => 'ud_data_list_visible_prop_search_fields_lbl',
						'name' => 'ud_data_list_visible_prop_search_fields_lbl',
						
					);
					
					//------------------------------------------------------
					
					$_tmp = array(
						
						'type' => 'checkbox',
						'inline' => TRUE,
						'name' => 'ud_data_list_visible_prop_search_fields[__terms]',
						'label' => 'ud_data_list_prop_search_field_terms',
						'value' => '__terms',
						'validation' => array(
							
							'rules' => 'trim',
							
						),
						
					);
					
					if ( $new_flag ) {
						
						$params[ 'params_spec_values' ][ 'ud_data_list_visible_prop_search_fields[__terms]' ] = '__terms';
						
					}
					
					$select_fields[] = $field;
					
					$new_params[] = $_tmp;
					
					//------------------------------------------------------
					
					foreach ( $submit_form[ 'fields' ] as $key => $field ) {
						
						$alias = check_var( $field[ 'alias' ] ) ? $field[ 'alias' ] : FALSE;
						
						if ( $alias ) {
							
							if ( in_array( $field[ 'field_type' ], array( 'combo_box', ) ) ) {
								
								$fields_options[ $alias ] = $field[ 'label' ];
								
								$_tmp = array(
									
									'type' => 'checkbox',
									'inline' => TRUE,
									'name' => 'ud_data_list_visible_prop_search_fields[' . $alias . ']',
									'label' => $field[ 'label' ],
									'value' => $alias,
									'validation' => array(
										
										'rules' => 'trim',
										
									),
									
								);
								
								if ( $new_flag ) {
									
									$params[ 'params_spec_values' ][ 'ud_data_list_visible_prop_search_fields[' . $alias . ']' ] = $alias;
									
								}
								
								$select_fields[] = $field;
								
								$new_params[] = $_tmp;
								
							}
							
						}
						
					}
					
					//------------------------------------------------------
					
					$_tmp = array(
						
						'type' => 'text',
						'inline' => TRUE,
						'name' => 'search_terms_string',
						'label' => 'search_terms_string',
						'tip' => 'tip_search_terms_string',
						'validation' => array(
							
							'rules' => 'trim',
							
						),
						
					);
					
					$params[ 'params_spec_values' ][ 'search_terms_string' ] = lang( 'search_terms' );
					
					$new_params[] = $_tmp;
					
					//------------------------------------------------------
					
					$_tmp = array(
						
						'type' => 'select',
						'inline' => TRUE,
						'name' => 'show_default_results',
						'label' => 'show_default_results',
						'tip' => 'tip_show_default_results',
						'validation' => array(
							
							'rules' => 'trim|required',
							
						),
						'options' => array(
							
							'1' => 'yes',
							'0' => 'no',
							
						),
						
					);
					
					$params[ 'params_spec_values' ][ 'show_default_results' ] = 1;
					
					$new_params[] = $_tmp;
					
					//------------------------------------------------------
					
					$_tmp = array(
						
						'type' => 'select',
						'inline' => TRUE,
						'name' => 'show_results_count',
						'label' => 'show_results_count',
						'tip' => 'tip_show_results_count',
						'validation' => array(
							
							'rules' => 'trim|required',
							
						),
						'options' => array(
							
							'1' => 'yes',
							'0' => 'no',
							
						),
						
					);
					
					$params[ 'params_spec_values' ][ 'show_results_count' ] = 1;
					
					$new_params[] = $_tmp;
					
					//------------------------------------------------------
					
					$_tmp = array(
						
						'type' => 'text',
						'inline' => TRUE,
						'name' => 'users_submits_search_results_string',
						'label' => 'users_submits_search_results_string_config_label',
						'tip' => 'tip_users_submits_search_results_string_config_label',
						'validation' => array(
							
							'rules' => 'trim',
							
						),
						
					);
					
					$params[ 'params_spec_values' ][ 'users_submits_search_results_string' ] = lang( 'users_submits_search_results_string' );
					
					$new_params[] = $_tmp;
					
					//------------------------------------------------------
					
					$_tmp = array(
						
						'type' => 'text',
						'inline' => TRUE,
						'name' => 'users_submits_search_single_result_string',
						'label' => 'users_submits_search_single_result_string_config_label',
						'tip' => 'tip_users_submits_search_single_result_string_config_label',
						'validation' => array(
							
							'rules' => 'trim',
							
						),
						
					);
					
					$params[ 'params_spec_values' ][ 'users_submits_search_single_result_string' ] = lang( 'users_submits_search_single_result_string' );
					
					$new_params[] = $_tmp;
					
					//------------------------------------------------------
					
					$_tmp = array(
						
						'type' => 'text',
						'inline' => TRUE,
						'name' => 'ud_data_no_search_result_str',
						'label' => 'ud_data_no_search_result_str',
						'tip' => 'tip_ud_data_no_search_result_str',
						'validation' => array(
							
							'rules' => 'trim',
							
						),
						
					);
					
					$params[ 'params_spec_values' ][ 'ud_data_no_search_result_str' ] = lang( 'ud_data_no_search_result_str_value' );
					
					$new_params[] = $_tmp;
					
				}
				
				//------------------------------------------------------
				
				$_tmp = array(
					
					'type' => 'spacer',
					'label' => 'ud_data_list_results',
					'name' => 'ud_data_list_results',
					
				);
				
				$new_params[] = $_tmp;
				
				//------------------------------------------------------
				
				$_tmp = array(
					
					'type' => 'text',
					'inline' => TRUE,
					'name' => 'ud_data_no_result_str',
					'label' => 'ud_data_no_result_str',
					'tip' => 'tip_ud_data_no_result_str',
					'validation' => array(
						
						'rules' => 'trim',
						
					),
					
				);
				
				$params[ 'params_spec_values' ][ 'ud_data_no_result_str' ] = lang( 'ud_data_no_result_str_value' );
				
				$new_params[] = $_tmp;
				
				//------------------------------------------------------
				
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
				
				//------------------------------------------------------
				
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
				
				//------------------------------------------------------
				
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
				
				//------------------------------------------------------
				
				$_tmp = array(
					
					'type' => 'select',
					'inline' => TRUE,
					'name' => 'users_submits_items_per_page',
					'label' => 'users_submits_items_per_page',
					'tip' => 'tip_users_submits_items_per_page',
					'validation' => array(
						
						'rules' => 'trim|required',
						
					),
					'options' => array(
						
						'global' => 'global',
						'1' => 1,
						'2' => 2,
						'3' => 3,
						'4' => 4,
						'5' => 5,
						'6' => 6,
						'8' => 8,
						'9' => 9,
						'10' => 10,
						'12' => 12,
						'15' => 15,
						'20' => 20,
						'25' => 25,
						'30' => 30,
						'35' => 35,
						'40' => 40,
						'45' => 45,
						'50' => 50,
						'100' => 100,
						
					),
					
				);
				
				$params[ 'params_spec_values' ][ 'users_submits_items_per_page' ] = '12';
				
				$new_params[] = $_tmp;
				
				//------------------------------------------------------
				
				$_tmp = array(
					
					'type' => 'spacer',
					'label' => 'readmore',
					'name' => 'spacer_readmore',
					
				);
				
				$new_params[] = $_tmp;
				
				//------------------------------------------------------
				
				$_tmp = array(
					
					'type' => 'checkbox',
					'inline' => TRUE,
					'name' => 'ud_data_list_d_readmore_link',
					'label' => 'ud_data_list_d_readmore_link',
					'tip' => 'tip_ud_data_list_d_readmore_link',
					'validation' => array(
						
						'rules' => 'trim',
						
					),
					'value' => 1,
					
				);
				
				if ( $new_flag ) {
					
					$params[ 'params_spec_values' ][ 'ud_data_list_d_readmore_link' ] = 1;
					
				}
				
				$new_params[] = $_tmp;
				
				//------------------------------------------------------
				
				$_tmp = array(
					
					'type' => 'text',
					'inline' => TRUE,
					'name' => 'ud_data_list_d_readmore_link_custom_str',
					'label' => 'ud_data_list_d_readmore_link_custom_str',
					'tip' => 'tip_ud_data_list_d_readmore_link_custom_str',
					'validation' => array(
						
						'rules' => 'trim|required',
						
					),
					
				);
				
				$params[ 'params_spec_values' ][ 'ud_data_list_d_readmore_link_custom_str' ] = 'readmore';
				
				$new_params[] = $_tmp;
				
				//------------------------------------------------------
				
				$_tmp = array(
					
					'type' => 'text',
					'inline' => TRUE,
					'name' => 'ud_data_list_d_readmore_link_url',
					'label' => 'ud_data_list_d_readmore_link_url',
					'tip' => 'tip_ud_data_list_d_readmore_link_url',
					'validation' => array(
						
						'rules' => 'trim',
						
					),
					
				);
				
				$params[ 'params_spec_values' ][ 'ud_data_list_d_readmore_link_url' ] = '';
				
				$new_params[] = $_tmp;
				
				//------------------------------------------------------
				
				$_tmp = array(
					
					'type' => 'select',
					'inline' => TRUE,
					'name' => 'ud_data_list_d_readmore_link_target',
					'label' => 'ud_data_list_d_readmore_link_target',
					'validation' => array(
						
						'rules' => 'trim',
						
					),
					'options' => array(
						
						'' => 'combobox_select',
						'_self' => 'url_target_self',
						'_blank' => 'url_target_blank',
						
					),
					
				);
				
				$params[ 'params_spec_values' ][ 'ud_data_list_d_readmore_link_target' ] = '';
				
				$new_params[] = $_tmp;
				
				//------------------------------------------------------
				
				array_push_pos( $params[ 'params_spec' ][ 'users_submits_config' ], $new_params, 10  );
				
				
				if ( isset( $current_params_values[ 'users_submits_layout' ] ) AND $current_params_values[ 'users_submits_layout' ] != 'global' ) {
					
					$system_views_path = VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . get_class_name( get_class() ) . DS . 'index' . DS . 'users_submits' . DS;
					$theme_views_path = THEMES_PATH . site_theme_components_views_path() . get_class_name( get_class() ) . DS . 'index' . DS . 'users_submits' . DS;
					
					if ( file_exists( $system_views_path . $current_params_values[ 'users_submits_layout' ] . DS . 'params.php' ) ) {
						
						include_once $system_views_path . $current_params_values[ 'users_submits_layout' ] . DS . 'params.php';
						
					}
					
					//echo '<pre>' . print_r( $layout_params, TRUE ) . '</pre>';
					
				}
				//echo '<pre>' . print_r( $params[ 'params_spec_values' ], TRUE ) . '</pre>'; exit;
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
	
	public function menu_item_get_link_users_submits( $menu_item_id = NULL, $params = NULL ){
		
		return 'submit_forms/index' . '/miid/' . $menu_item_id . '/a/us/sfid/' . $params[ 'submit_form_id' ];
		
	}
	
	//------------------------------------------------------
	// Menu item user submit detail
	
	public function menu_item_ud_data( $menu_item = NULL ){
		
		//------------------------------------------------------
		// Loading submit forms common model
		
		if ( ! $this->load->is_model_loaded( 'sfcm' ) ) {
			
			$this->load->model( 'common/submit_forms_common_model', 'sfcm' );
			
		}
		
		$submit_forms = $this->sfcm->get_submit_forms()->result_array();
		
		$group_str = 'menu_item_ud_data_config';
		
		if ( $submit_forms ) {
			
			$params = array(
				
				'params_spec' => array(
					
					$group_str => array(),
					
				),
				'params_spec_values' => array(),
				
			);
			
			//------------------------------------------------------
			// parsing submit forms / creating submit forms options
			
			$submit_forms_options = array();
			
			foreach( $submit_forms as $key => & $submit_form ) {
				
				$this->sfcm->parse_sf( $submit_form );
				
				$submit_forms_options[ $submit_form[ 'id' ] ] = $submit_form[ 'title' ];
				
			}
			
			//------------------------------------------------------
			// Loading submit forms common model
			
			//------------------------------------------------------
			
			$_tmp = array(
				
				'type' => 'text',
				'inline' => TRUE,
				'name' => 'ud_data_id',
				'label' => 'ud_data_id',
				'tip' => 'tip_ud_data_id',
				'validation' => array(
					
					'rules' => 'trim|required',
					
				),
				'options' => array(
					
					'' => 'combobox_select',
					
				) + $submit_forms_options,
				
			);
			
			$params[ 'params_spec_values' ][ 'show_readmore_link' ] = 0;
			
			$new_params[] = $_tmp;
			
			//------------------------------------------------------
			
			$params[ 'params_spec' ][ $group_str ] = $new_params;
			
			//echo '<pre>' . print_r( $params, TRUE ) . '</pre>'; exit;
			
			return $params;
			
		}
		
		return FALSE;
		
	}
	
	//------------------------------------------------------
	// Menu item user submit detail link
	
	public function menu_item_get_link_ud_data( $menu_item_id = NULL, $params = NULL ){
		
		return $this->unid->menu_item_get_link_ud_data( $menu_item_id, $params );
		
	}
	
}
