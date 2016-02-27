<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact_module extends CI_Model{
	
	public $module_name = 'contact';
	
	public function run( $module_data = NULL ){
		
		
		/* 
		 * -------------------------------------------------------------------------------------------------
		 * Loading models, languages and helpers
		 */
		
		$this->load->model(
		
			array(
				
				'common/contacts_common_model',
				'admin/contacts_model',
				
			)
			
		);
		
		$ccm = &$this->contacts_common_model;
		
		$this->load->language(
		
			array(
				
				'contacts',
				'admin/modules/contact',
				
			)
			
		);
		
		$this->load->helper(
			
			array(
				
				'html',
				
			)
			
		);
		
		/* 
		 * -------------------------------------------------------------------------------------------------
		 */
		
		
		
		/* 
		 * -------------------------------------------------------------------------------------------------
		 * Declarando as folhas de estilos
		 */
		
		$this->voutput->append_head_stylesheet( $this->module_name . '-module', STYLES_DIR_URL . '/' . MODULES_ALIAS . '/' . $this->module_name . '/contact-module.css' );
		$this->voutput->append_head_stylesheet( $this->module_name . '-theme-' . $module_data[ 'params' ][ 'contact_module_theme' ], STYLES_DIR_URL . '/' . MODULES_ALIAS . '/' . $this->module_name . '/themes/' . $module_data[ 'params' ][ 'contact_module_theme' ] . '/' . $module_data[ 'params' ][ 'contact_module_theme' ] . '.css' );
		
		/* 
		 * -------------------------------------------------------------------------------------------------
		 */
		
		
		
		/* 
		 * -------------------------------------------------------------------------------------------------
		 * Views
		 */
		
		// get contact params
		$gcp = array(
			
			'where_condition' => 't1.id = ' . $module_data[ 'params' ][ 'contact_module_contact_id' ],
			'limit' => 1,
			
		 );
		
		// definindo os dados a serem enviados às views
		$data[ 'module_data' ] = & $module_data;
		$data[ 'contact' ] = $ccm->get_contacts( $gcp )->row_array() ;
		
		$data[ 'contact' ];
		
		$data[ 'contact' ][ 'emails' ] = json_decode( $data[ 'contact' ][ 'emails' ], TRUE );
		$data[ 'contact' ][ 'addresses' ] = json_decode( $data[ 'contact' ][ 'addresses' ], TRUE );
		$data[ 'contact' ][ 'phones' ] = json_decode( $data[ 'contact' ][ 'phones' ], TRUE );
		$data[ 'contact' ][ 'websites' ] = json_decode( $data[ 'contact' ][ 'websites' ], TRUE );
		
		//print_r( $data[ 'contact' ] );
		
		// carregando as views
		// verificando se o tema do site possui a view
		if ( file_exists( THEMES_PATH . site_theme_modules_views_path() . $this->module_name . DS . $module_data[ 'params' ][ 'contact_module_layout' ] . DS . $module_data[ 'params' ][ 'contact_module_layout' ] . '.php' ) ){
			
			return $this->load->view( site_theme_modules_views_path() . $this->module_name . DS . $module_data[ 'params' ][ 'contact_module_layout' ] . DS . $module_data[ 'params' ][ 'contact_module_layout' ], $data, TRUE );
			
		}
		// verificando se a view existe no diretório de views de módulos padrão
		else if ( file_exists( VIEWS_PATH . SITE_MODULES_VIEWS_PATH . $this->module_name . DS . $module_data[ 'params' ][ 'contact_module_layout' ] . DS . $module_data[ 'params' ][ 'contact_module_layout' ] . '.php' ) ){
			
			return $this->load->view( SITE_MODULES_VIEWS_PATH . $this->module_name . DS . $module_data[ 'params' ][ 'contact_module_layout' ] . DS . $module_data[ 'params' ][ 'contact_module_layout' ], $data, TRUE );
			
		}
		else {
			
			return lang( 'load_view_fail' ) . ':<br />' . THEMES_PATH . site_theme_modules_views_path() . $this->module_name . DS . $module_data[ 'params' ][ 'contact_module_layout' ] . DS . $module_data[ 'params' ][ 'contact_module_layout' ] . '.php' . '<br />' . VIEWS_PATH . SITE_MODULES_VIEWS_PATH . 'menu' . DS . $module_data[ 'params' ][ 'contact_module_layout' ] . DS . $module_data[ 'params' ][ 'contact_module_layout' ] . '.php';
			
		}
		
		/* 
		 * -------------------------------------------------------------------------------------------------
		 */
		
		
		
	}
	
	public function get_module_params( $current_params_values = NULL ){
		
		$params = get_params_spec_from_xml( MODULES_PATH . $this->module_name . '/params.xml' );
		
		$this->load->model( 'admin/articles_model' );
		$this->load->model(
		
			array(
				
				'common/contacts_common_model',
				'admin/contacts_model',
				
			)
			
		);
		
		$contacts = $this->contacts_model->get_contacts()->result_array();
		$contact = NULL;
		
		
		foreach ( $contacts as $key => $_contact ) {
			
			if ( ! isset( $_first_contact ) ) {
				
				$_first_contact = TRUE;
				
			}
			
			if (
				
				( isset( $current_params_values[ 'contact_module_contact_id' ] ) AND $current_params_values[ 'contact_module_contact_id' ] == $_contact[ 'id' ] ) OR
				( ! isset( $current_params_values[ 'contact_module_contact_id' ] ) AND isset( $_first_contact ) )
				
			) {
				
				$contact = $_contact;
				
			}
			
			$contact_options[ $_contact[ 'id' ] ] = $_contact[ 'name' ];
			
		}
		
		// carregando os layouts do tema atual
		$contact_module_layouts = dir_list_to_array( THEMES_PATH . site_theme_modules_views_path() . $this->module_name );
		// carregando os layouts do diretório de views padrão
		$contact_module_layouts = array_merge( $contact_module_layouts, dir_list_to_array( VIEWS_PATH . SITE_MODULES_VIEWS_PATH . $this->module_name ) );
		
		// carregando os temas
		$themes_options = dir_list_to_array( STYLES_PATH . MODULES_DIR_NAME . DS . $this->module_name . DS . 'themes' );
		
		$params_section_name = 'contact_module_config_contact';
		
		foreach ( $params['params_spec'][ $params_section_name ] as $key => $element ) {
			
			if ( $element['name'] == 'contact_module_contact_id' ){
				
				$spec_options = array();
				
				if ( isset($params['params_spec'][ $params_section_name ][$key]['options']) )
					$spec_options = $params['params_spec'][ $params_section_name ][$key]['options'];
				
				$params['params_spec'][ $params_section_name ][$key]['options'] = is_array( $spec_options ) ? $spec_options + $contact_options : $contact_options;
				
			}
			
		}
		
		$params_section_name = 'contact_module_config_look_and_feel';
		
		foreach ( $params['params_spec'][ $params_section_name ] as $key => $element ) {
			
			if ( $element['name'] == 'contact_module_layout' ){
				
				$spec_options = array();
				
				if ( isset($params['params_spec'][ $params_section_name ][$key]['options']) )
					$spec_options = $params['params_spec'][ $params_section_name ][$key]['options'];
				
				$params['params_spec'][ $params_section_name ][$key]['options'] = is_array( $spec_options ) ? $spec_options + $contact_module_layouts : $contact_module_layouts;
				
			}
			
			if ( $element['name'] == 'contact_module_theme' ){
				
				$spec_options = array();
				
				if ( isset( $params[ 'params_spec' ][ $params_section_name ][ $key ][ 'options' ] ) )
					$spec_options = $params[ 'params_spec' ][ $params_section_name ][ $key ][ 'options' ];
				
				$params[ 'params_spec' ][ $params_section_name ][ $key ][ 'options' ] = is_array( $spec_options ) ? $spec_options + $themes_options : $themes_options;
				
			}
			
		}
		
		if ( $contact ) {
			
			/*
			* ------------------------------------
			*/
			
			if ( isset( $contact[ 'phones' ] ) ) {
				
				$new_params = NULL;
				
				$contact[ 'phones' ] = get_params( $contact[ 'phones' ] );
				
				/*
				* ------------------------------------
				*/
				
				$new_params[] = array(
					
					'type' => 'spacer',
					'label' => 'contact_module_phones_to_show',
					'tip' => 'tip_contact_module_phones_to_show',
					
				);
				
				/*
				* ------------------------------------
				*/
				
				foreach ( $contact[ 'phones' ] as $key => $phone ) {
					
					if ( isset( $phone[ 'number' ] ) ) {
						
						$phone_key = check_var( $phone[ 'key' ] ) ? $phone[ 'key' ] : FALSE;
						
						if ( $phone_key ) {
							
							$label = '';
							
							if ( isset( $phone[ 'area_code' ] ) ) {
								
								$label .= '(' . $phone[ 'area_code' ] . ')';
								
							}
							
							$label .= ' ' . $phone[ 'number' ] . ' ';
							
							if ( isset( $phone[ 'phone_title_publicly_visible' ] ) AND isset( $phone[ 'title' ] ) ){
								
								$label .= '(' . $phone[ 'title' ] . ')';
								
							}
							
							$_tmp = array(
								
								'type' => 'checkbox',
								'inline' => TRUE,
								'name' => 'contact_module_phones_to_show[' . $phone_key . ']',
								'label' => $label,
								'value' => $phone_key,
								'validation' => array(
									
									'rules' => 'trim',
									
								),
								
							);
							
							if ( isset( $phone[ 'publicly_visible' ] ) AND $phone[ 'publicly_visible' ] ) {
								
								if ( ! check_var( $current_params_values ) AND ! check_var( $current_params_values[ 'contact_module_phones_to_show[' . $phone_key . ']' ] ) ) {
									
									$params[ 'params_spec_values' ][ 'contact_module_phones_to_show[' . $phone_key . ']' ] = $phone_key;
									
								}
								
							}
							
							$new_params[] = $_tmp;
							
						}
						
					}
					
				}
				
				array_push_pos( $params[ 'params_spec' ][ 'contact_module_config_phones' ], $new_params, 10  );
				
			}
			
			/*
			* ------------------------------------
			*/
			
			if ( isset( $contact[ 'emails' ] ) ) {
				
				$new_params = NULL;
				
				$contact[ 'emails' ] = get_params( $contact[ 'emails' ] );
				
				/*
				* ------------------------------------
				*/
				
				$new_params[] = array(
					
					'type' => 'spacer',
					'label' => 'contact_module_emails_to_show',
					'tip' => 'tip_contact_module_emails_to_show',
					
				);
				
				/*
				* ------------------------------------
				*/
				
				foreach ( $contact[ 'emails' ] as $key => $email ) {
					
					if ( isset( $email[ 'email' ] ) ) {
						
						$email_key = check_var( $email[ 'key' ] ) ? $email[ 'key' ] : FALSE;
						
						if ( $email_key ) {
							
							$label = ' ' . $email[ 'email' ] . ' ';
							
							if ( isset( $email[ 'email_title_publicly_visible' ] ) AND isset( $email[ 'title' ] ) ){
								
								$label .= '(' . $email[ 'title' ] . ')';
								
							}
							
							$_tmp = array(
								
								'type' => 'checkbox',
								'inline' => TRUE,
								'name' => 'contact_module_emails_to_show[' . $email_key . ']',
								'label' => $label,
								'value' => $email_key,
								'validation' => array(
									
									'rules' => 'trim',
									
								),
								
							);
							
							if ( isset( $email[ 'publicly_visible' ] ) AND $email[ 'publicly_visible' ] ) {
								
								if ( ! check_var( $current_params_values ) AND ! check_var( $current_params_values[ 'contact_module_emails_to_show[' . $email_key . ']' ] ) ) {
									
									$params[ 'params_spec_values' ][ 'contact_module_emails_to_show[' . $email_key . ']' ] = $email_key;
									
								}
								
							}
							
							$new_params[] = $_tmp;
							
						}
						
					}
					
				}
				
				array_push_pos( $params[ 'params_spec' ][ 'contact_module_config_emails' ], $new_params, 10  );
				
			}
			
			/*
			* ------------------------------------
			*/
			
			if ( isset( $contact[ 'websites' ] ) ) {
				
				$new_params = NULL;
				
				$contact[ 'websites' ] = get_params( $contact[ 'websites' ] );
				
				/*
				* ------------------------------------
				*/
				
				$new_params[] = array(
					
					'type' => 'spacer',
					'label' => 'contact_module_websites_to_show',
					'tip' => 'tip_contact_module_websites_to_show',
					
				);
				
				/*
				* ------------------------------------
				*/
				
				foreach ( $contact[ 'websites' ] as $key => $website ) {
					
					if ( isset( $website[ 'url' ] ) ) {
						
						$website_key = check_var( $website[ 'key' ] ) ? $website[ 'key' ] : FALSE;
						
						if ( $website_key ) {
							
							$label = ' ' . $website[ 'url' ] . ' ';
							
							if ( isset( $website[ 'website_title_publicly_visible' ] ) AND isset( $website[ 'title' ] ) ){
								
								$label .= '(' . $website[ 'title' ] . ')';
								
							}
							
							$_tmp = array(
								
								'type' => 'checkbox',
								'inline' => TRUE,
								'name' => 'contact_module_websites_to_show[' . $website_key . ']',
								'label' => $label,
								'value' => $website_key,
								'validation' => array(
									
									'rules' => 'trim',
									
								),
								
							);
							
							if ( isset( $website[ 'publicly_visible' ] ) AND $website[ 'publicly_visible' ] ) {
								
								if ( ! check_var( $current_params_values ) AND ! check_var( $current_params_values[ 'contact_module_websites_to_show[' . $website_key . ']' ] ) ) {
									
									$params[ 'params_spec_values' ][ 'contact_module_websites_to_show[' . $website_key . ']' ] = $website_key;
									
								}
								
							}
							
							$new_params[] = $_tmp;
							
						}
						
					}
					
				}
				
				array_push_pos( $params[ 'params_spec' ][ 'contact_module_config_websites' ], $new_params, 10  );
				
			}
			
			/*
			* ------------------------------------
			*/
			
		}
		
		return $params;
	}
	
}
