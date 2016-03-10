<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

require( APPPATH . 'controllers/main.php' );

class Contacts extends Main {
	
	public function __construct(){
		
		parent::__construct();
		
		$this->load->model( array( 'common/contacts_common_model' ) );
		$this->load->language( array( 'contacts' ) );
		
		set_current_component();
		
		$this->ccm = &$this->contacts_common_model;
		
	}
	
	/******************************************************************************/
	/******************************************************************************/
	/******************************* Component index ******************************/
	
	public function index( $action = NULL, $current_menu_item_id = 0, $var1 = NULL, $var2 = NULL, $var3 = NULL, $var4 = NULL ){
		
		$this->component_function = __FUNCTION__;
		$this->component_function_action = $action;
		
		// obtendo os parâmetros globais do componente
		$component_params = $this->current_component[ 'params' ];
		
		// obtendo os parâmetros do item de menu
		if ( $this->mcm->current_menu_item ){
			
			$menu_item_params = get_params( $this->mcm->current_menu_item[ 'params' ] );
			$data[ 'params' ] = filter_params( $component_params, $menu_item_params );
			
		}
		else{
			
			$data[ 'params' ] = $component_params;
			
		}
		
		/**************************************************/
		/***************** Contact details ****************/
		
		if ( $action === 'contact_details' ){
			
			// contact form id
			$c_id = $var1;
			
			// get contact params
			$gcp = array(
				
				'where_condition' => 't1.id = ' . $c_id,
				'limit' => 1,
				
			 );
			
			if ( $contact = $this->ccm->get_contacts( $gcp )->row_array() ){
				
				$url = get_url( $this->uri->ruri_string() );
				set_last_url( $url );
				
				$contact[ 'emails' ] = json_decode( $contact[ 'emails' ], TRUE );
				$contact[ 'addresses' ] = json_decode( $contact[ 'addresses' ], TRUE );
				$contact[ 'phones' ] = json_decode( $contact[ 'phones' ], TRUE );
				$contact[ 'params' ] = json_decode( $contact[ 'params' ], TRUE );
				
				// obtendo o título do conteúdo da página,
				$data[ 'params' ][ 'show_page_content_title' ] = @$data[ 'params' ][ 'show_page_content_title' ];
				
				if ( get_url( $this->mcm->current_menu_item[ 'link' ] ) != $url ){
					
					$this->mcm->html_data[ 'content' ][ 'title' ] = $contact[ 'name' ];
					
				}
				else if ( get_url( $this->mcm->current_menu_item[ 'link' ] ) == $url ){
					
					if ( @$data[ 'params' ][ 'custom_page_title' ] ){
						
						$this->mcm->html_data[ 'content' ][ 'title' ] = $data[ 'params' ][ 'custom_page_title' ];
						
					}
					else{
						
						$this->mcm->html_data[ 'content' ][ 'title' ] = $this->mcm->current_menu_item[ 'title' ];
						
					}
					
				}
				else{
					$this->mcm->html_data[ 'content' ][ 'title' ] = lang( 'contact' );
				}
				
				$this->voutput->set_head_title( $this->mcm->html_data[ 'content' ][ 'title' ] );
				
				$data[ 'params' ] = $contact[ 'params' ] = filter_params( $data[ 'params' ], get_params( $contact[ 'params' ] ) );
				$data[ 'contact' ] = $contact;
				$data[ 'contact' ][ 'url' ] = $url;
				
				//validação dos campos
				if ( isset( $data[ 'params' ][ 'contact_form_show_field_name' ] ) AND $data[ 'params' ][ 'contact_form_show_field_name' ] ){
					
					if ( isset( $data[ 'params' ][ 'contact_form_field_name_is_required' ] ) AND $data[ 'params' ][ 'contact_form_field_name_is_required' ] ){
						
						$this->form_validation->set_rules( 'name', lang( 'name' ), 'trim|xss|required' );
						
					}
					else{
						
						$this->form_validation->set_rules( 'name', lang( 'name' ), 'trim|xss' );
						
					}
					
				}
				
				if ( isset( $data[ 'params' ][ 'contact_form_show_field_email' ] ) AND $data[ 'params' ][ 'contact_form_show_field_email' ] ){
					
					if ( isset( $data[ 'params' ][ 'contact_form_field_email_is_required' ] ) AND $data[ 'params' ][ 'contact_form_field_email_is_required' ] ){
						
						$this->form_validation->set_rules( 'email', lang( 'email' ), 'trim|xss|required|valid_email' );
						
					}
					else{
						
						$this->form_validation->set_rules( 'email', lang( 'email' ), 'trim|xss|valid_email' );
						
					}
					
				}
				
				if ( isset( $data[ 'params' ][ 'contact_form_show_field_phone_1' ] ) AND $data[ 'params' ][ 'contact_form_show_field_phone_1' ] ){
					
					$phone_str_1 = ( isset( $data[ 'params' ][ 'contact_form_show_field_phone_2' ] ) AND $data[ 'params' ][ 'contact_form_show_field_phone_2' ] ) ? lang( 'phone_2' ) : lang( 'phone' );
					
					if ( isset( $data[ 'params' ][ 'contact_form_field_phone_1_is_required' ] ) AND $data[ 'params' ][ 'contact_form_field_phone_1_is_required' ] ){
						
						$this->form_validation->set_rules( 'phone_1', $phone_str_1, 'trim|xss|required' );
						
					}
					else{
						
						$this->form_validation->set_rules( 'phone_1', $phone_str_1, 'trim|xss' );
						
					}
					
				}
				
				if ( isset( $data[ 'params' ][ 'contact_form_show_field_phone_2' ] ) AND $data[ 'params' ][ 'contact_form_show_field_phone_2' ] ){
					
					$phone_str_2 = ( isset( $data[ 'params' ][ 'contact_form_show_field_phone_1' ] ) AND $data[ 'params' ][ 'contact_form_show_field_phone_1' ] ) ? lang( 'phone_2' ) : lang( 'phone' );
					
					if ( isset( $data[ 'params' ][ 'contact_form_field_phone_2_is_required' ] ) AND $data[ 'params' ][ 'contact_form_field_phone_2_is_required' ] ){
						
						$this->form_validation->set_rules( 'phone_2', $phone_str_2, 'trim|xss|required' );
						
					}
					else{
						
						$this->form_validation->set_rules( 'phone_2', $phone_str_2, 'trim|xss' );
						
					}
					
				}
				
				if ( isset( $data[ 'params' ][ 'contact_form_show_field_subject' ] ) AND $data[ 'params' ][ 'contact_form_show_field_subject' ] ){
					
					if ( isset( $data[ 'params' ][ 'contact_form_field_subject_is_required' ] ) AND $data[ 'params' ][ 'contact_form_field_subject_is_required' ] ){
						
						$this->form_validation->set_rules( 'subject', lang( 'subject' ), 'trim|xss|required' );
						
					}
					else{
						
						$this->form_validation->set_rules( 'subject', lang( 'subject' ), 'trim|xss' );
						
					}
					
				}
				
				if ( isset( $data[ 'params' ][ 'contact_form_show_field_message' ] ) AND $data[ 'params' ][ 'contact_form_show_field_message' ] ){
					
					if ( isset( $data[ 'params' ][ 'contact_form_field_message_is_required' ] ) AND $data[ 'params' ][ 'contact_form_field_message_is_required' ] ){
						
						$this->form_validation->set_rules( 'message', lang( 'message' ), 'trim|xss|required' );
						
					}
					else{
						
						$this->form_validation->set_rules( 'message', lang( 'message' ), 'trim|xss' );
						
					}
					
				}
				
				if ( isset( $data[ 'params' ][ 'contact_form_show_field_company' ] ) AND $data[ 'params' ][ 'contact_form_show_field_company' ] ){
					
					if ( isset( $data[ 'params' ][ 'contact_form_field_company_is_required' ] ) AND $data[ 'params' ][ 'contact_form_field_company_is_required' ] ){
						
						$this->form_validation->set_rules( 'company', lang( 'company' ), 'trim|xss|required' );
						
					}
					else{
						
						$this->form_validation->set_rules( 'company', lang( 'company' ), 'trim|xss' );
						
					}
					
				}
				
				if ( isset( $data[ 'params' ][ 'contact_form_show_field_addresses' ] ) AND $data[ 'params' ][ 'contact_form_show_field_addresses' ] ){
					
					if ( isset( $data[ 'params' ][ 'contact_form_show_field_address_country' ] ) AND $data[ 'params' ][ 'contact_form_show_field_address_country' ] ){
						
						if ( isset( $data[ 'params' ][ 'contact_form_field_address_country_is_required' ] ) AND $data[ 'params' ][ 'contact_form_field_address_country_is_required' ] ){
							
							$this->form_validation->set_rules( 'country', lang( 'country' ), 'trim|xss|required' );
							
						}
						else{
							
							$this->form_validation->set_rules( 'country', lang( 'country' ), 'trim|xss' );
							
						}
						
					}
					
					if ( isset( $data[ 'params' ][ 'contact_form_show_field_address_state' ] ) AND $data[ 'params' ][ 'contact_form_show_field_address_state' ] ){
						
						if ( isset( $data[ 'params' ][ 'contact_form_field_address_state_is_required' ] ) AND $data[ 'params' ][ 'contact_form_field_address_state_is_required' ] ){
							
							$this->form_validation->set_rules( 'state', lang( 'state' ), 'trim|xss|required' );
							
						}
						else{
							
							$this->form_validation->set_rules( 'state', lang( 'state' ), 'trim|xss' );
							
						}
						
					}
					
					if ( isset( $data[ 'params' ][ 'contact_form_show_field_address_city' ] ) AND $data[ 'params' ][ 'contact_form_show_field_address_city' ] ){
						
						if ( isset( $data[ 'params' ][ 'contact_form_field_address_city_is_required' ] ) AND $data[ 'params' ][ 'contact_form_field_address_city_is_required' ] ){
							
							$this->form_validation->set_rules( 'city', lang( 'city' ), 'trim|xss|required' );
							
						}
						else{
							
							$this->form_validation->set_rules( 'city', lang( 'city' ), 'trim|xss' );
							
						}
						
					}
					
					if ( isset( $data[ 'params' ][ 'contact_form_show_field_address_neighborhood' ] ) AND $data[ 'params' ][ 'contact_form_show_field_address_neighborhood' ] ){
						
						if ( isset( $data[ 'params' ][ 'contact_form_field_address_neighborhood_is_required' ] ) AND $data[ 'params' ][ 'contact_form_field_address_neighborhood_is_required' ] ){
							
							$this->form_validation->set_rules( 'neighborhood', lang( 'neighborhood' ), 'trim|xss|required' );
							
						}
						else{
							
							$this->form_validation->set_rules( 'neighborhood', lang( 'neighborhood' ), 'trim|xss' );
							
						}
						
					}
					
					if ( isset( $data[ 'params' ][ 'contact_form_show_field_address_public_area' ] ) AND $data[ 'params' ][ 'contact_form_show_field_address_public_area' ] ){
						
						if ( isset( $data[ 'params' ][ 'contact_form_field_address_public_area_is_required' ] ) AND $data[ 'params' ][ 'contact_form_field_address_public_area_is_required' ] ){
							
							$this->form_validation->set_rules( 'public_area', lang( 'public_area' ), 'trim|xss|required' );
							
						}
						else{
							
							$this->form_validation->set_rules( 'public_area', lang( 'public_area' ), 'trim|xss' );
							
						}
						
					}
					
					if ( isset( $data[ 'params' ][ 'contact_form_show_field_address_number' ] ) AND $data[ 'params' ][ 'contact_form_show_field_address_number' ] ){
						
						if ( isset( $data[ 'params' ][ 'contact_form_field_address_number_is_required' ] ) AND $data[ 'params' ][ 'contact_form_field_address_number_is_required' ] ){
							
							$this->form_validation->set_rules( 'number', lang( 'number' ), 'trim|xss|required' );
							
						}
						else{
							
							$this->form_validation->set_rules( 'number', lang( 'number' ), 'trim|xss' );
							
						}
						
					}
					
					if ( isset( $data[ 'params' ][ 'contact_form_show_field_address_complement' ] ) AND $data[ 'params' ][ 'contact_form_show_field_address_complement' ] ){
						
						if ( isset( $data[ 'params' ][ 'contact_form_field_address_complement_is_required' ] ) AND $data[ 'params' ][ 'contact_form_field_address_complement_is_required' ] ){
							
							$this->form_validation->set_rules( 'complement', lang( 'complement' ), 'trim|xss|required' );
							
						}
						else{
							
							$this->form_validation->set_rules( 'complement', lang( 'complement' ), 'trim|xss' );
							
						}
						
					}
					
					if ( isset( $data[ 'params' ][ 'contact_form_show_field_address_postal_code' ] ) AND $data[ 'params' ][ 'contact_form_show_field_address_postal_code' ] ){
						
						if ( isset( $data[ 'params' ][ 'contact_form_field_address_postal_code_is_required' ] ) AND $data[ 'params' ][ 'contact_form_field_address_postal_code_is_required' ] ){
							
							$this->form_validation->set_rules( 'postal_code', lang( 'postal_code' ), 'trim|xss|required' );
							
						}
						else{
							
							$this->form_validation->set_rules( 'postal_code', lang( 'postal_code' ), 'trim|xss' );
							
						}
						
					}
					
				}
				
				if ( ( isset( $data[ 'params' ][ 'contact_form_show_field_extra_combobox_1' ] ) AND $data[ 'params' ][ 'contact_form_show_field_extra_combobox_1' ] ) AND ( isset( $data[ 'params' ][ 'contact_form_title_field_extra_combobox_1' ] ) AND $data[ 'params' ][ 'contact_form_title_field_extra_combobox_1' ] ) ){
					
					if ( isset( $data[ 'params' ][ 'contact_form_field_extra_combobox_1_is_required' ] ) AND $data[ 'params' ][ 'contact_form_field_extra_combobox_1_is_required' ] ){
						
						$this->form_validation->set_rules( 'extra_combobox_1', lang( $data[ 'params' ][ 'contact_form_title_field_extra_combobox_1' ] ), 'trim|xss|required' );
						
					}
					else{
						
						$this->form_validation->set_rules( 'extra_combobox_1', lang( $data[ 'params' ][ 'contact_form_title_field_extra_combobox_1' ] ), 'trim|xss' );
						
					}
					
				}
				
				if ( ( isset( $data[ 'params' ][ 'contact_form_show_field_extra_combobox_2' ] ) AND $data[ 'params' ][ 'contact_form_show_field_extra_combobox_2' ] ) AND ( isset( $data[ 'params' ][ 'contact_form_title_field_extra_combobox_2' ] ) AND $data[ 'params' ][ 'contact_form_title_field_extra_combobox_2' ] ) ){
					
					if ( isset( $data[ 'params' ][ 'contact_form_field_extra_combobox_2_is_required' ] ) AND $data[ 'params' ][ 'contact_form_field_extra_combobox_2_is_required' ] ){
						
						$this->form_validation->set_rules( 'extra_combobox_2', lang( $data[ 'params' ][ 'contact_form_title_field_extra_combobox_2' ] ), 'trim|xss|required' );
						
					}
					else{
						
						$this->form_validation->set_rules( 'extra_combobox_2', lang( $data[ 'params' ][ 'contact_form_title_field_extra_combobox_2' ] ), 'trim|xss' );
						
					}
					
				}
				
				if ( ( isset( $data[ 'params' ][ 'contact_form_show_field_extra_combobox_3' ] ) AND $data[ 'params' ][ 'contact_form_show_field_extra_combobox_3' ] ) AND ( isset( $data[ 'params' ][ 'contact_form_title_field_extra_combobox_3' ] ) AND $data[ 'params' ][ 'contact_form_title_field_extra_combobox_3' ] ) ){
					
					if ( isset( $data[ 'params' ][ 'contact_form_field_extra_combobox_3_is_required' ] ) AND $data[ 'params' ][ 'contact_form_field_extra_combobox_3_is_required' ] ){
						
						$this->form_validation->set_rules( 'extra_combobox_3', lang( $data[ 'params' ][ 'contact_form_title_field_extra_combobox_3' ] ), 'trim|xss|required' );
						
					}
					else{
						
						$this->form_validation->set_rules( 'extra_combobox_3', lang( $data[ 'params' ][ 'contact_form_title_field_extra_combobox_3' ] ), 'trim|xss' );
						
					}
					
				}
				
				// Criando o array de emails para os quais as mensagens serão enviadas
				$emails_to = array();
				
				// Se os emails forem os do contato
				if ( isset( $data[ 'params' ][ 'contact_form_send_email_to' ] ) AND $data[ 'params' ][ 'contact_form_send_email_to' ] == 'contact_emails' ){
					
					foreach ( $contact[ 'emails' ] as $key => $email ) {
						
						if ( isset( $email[ 'site_msg' ] ) AND $email[ 'site_msg' ] )
							$emails_to[] = $email[ 'email' ];
						
					}
					
				}
				// Caso contrário, se os emails são personalizados
				else if ( ( isset( $data[ 'params' ][ 'contact_form_send_email_to' ] ) AND $data[ 'params' ][ 'contact_form_send_email_to' ] == 'custom_emails' ) AND ( isset( $data[ 'params' ][ 'contact_form_custom_emails' ] ) AND $data[ 'params' ][ 'contact_form_custom_emails' ] ) ){
					
					$custom_emails = explode( "\n", $data[ 'params' ][ 'contact_form_custom_emails' ] );
					
					foreach ( $custom_emails as $key => $email ) {
						
						$emails_to[] = $email;
						
					}
					
				}
				
				if ( ( isset( $this->mcm->system_params[ 'email_config_enabled' ] ) AND $this->mcm->system_params[ 'email_config_enabled' ] ) AND ( $this->form_validation->run() AND $this->input->post( 'submit', TRUE ) ) ){
					
					$data[ 'post' ] = $this->input->post();
					
					$email_from = $data[ 'post' ][ 'email' ];
					$email_from_name = $data[ 'post' ][ 'name' ];
					$email_reply_to = $email_from_name;
					
					// limpando tags html, caso seja exigido pela respectiva opção
					if ( isset( $data[ 'params' ][ 'contact_form_allow_html_tags' ] ) AND ! $data[ 'params' ][ 'contact_form_allow_html_tags' ] ){
						
						foreach ( $data[ 'post' ] as $key => &$value ) {
							
							$value = strip_tags( $value );
							
						}
						
					}
					
					if ( check_var( $this->mcm->system_params[ 'email_config_enabled' ] ) ) {
						
						$newline_search = array(
							
							'\r',
							'\n'
							
						);
						$newline_replace = array(
							
							"\r",
							"\n"
							
						);
						$newline = str_replace( $newline_search, $newline_replace, $this->mcm->system_params[ 'email_config_newline' ] );
						$mail_useragent = check_var( $this->mcm->system_params[ 'email_config_useragent' ] ) ? $this->mcm->system_params[ 'email_config_useragent' ] : NULL;
						
						$config = Array(
							
							'protocol' => $this->mcm->system_params[ 'email_config_protocol' ],
							'mailpath' => $this->mcm->system_params[ 'email_config_sendmail_path' ],
							'smtp_host' => $this->mcm->system_params[ 'email_config_smtp_host' ],
							'smtp_port' => $this->mcm->system_params[ 'email_config_smtp_port' ],
							'smtp_user' => $this->mcm->system_params[ 'email_config_smtp_user' ],
							'smtp_pass' => $this->mcm->system_params[ 'email_config_smtp_pass' ],
							'mailtype' => $this->mcm->system_params[ 'email_config_mailtype' ],
							'charset' => $this->mcm->system_params[ 'email_config_charset' ],
							'wordwrap' => $this->mcm->system_params[ 'email_config_wordwrap' ],
							'smtp_crypto' => $this->mcm->system_params[ 'email_config_smtp_crypto' ],
							'newline' => $newline,
							'useragent' => $mail_useragent,
							
						);
						
						$this->load->library( 'email', $config );
						
						$this->email->from( $email_from, $email_from_name );
						$this->email->reply_to( $email_reply_to );
						$this->email->to( $emails_to );
// 						$this->email->cc( $emails_cc);
// 						$this->email->bcc( $emails_bcc);
						$this->email->subject( $email_subject );
						$this->email->message( $email_body );
							
						$config = Array(
							
							'protocol' => $this->mcm->system_params[ 'email_config_protocol' ],
							'mailpath' => $this->mcm->system_params[ 'email_config_sendmail_path' ],
							'smtp_host' => $this->mcm->system_params[ 'email_config_smtp_host' ],
							'smtp_port' => $this->mcm->system_params[ 'email_config_smtp_port' ],
							'smtp_user' => $this->mcm->system_params[ 'email_config_smtp_user' ],
							'smtp_pass' => $this->mcm->system_params[ 'email_config_smtp_pass' ],
							'mailtype' => $this->mcm->system_params[ 'email_config_mailtype' ],
							'charset' => $this->mcm->system_params[ 'email_config_charset' ],
							'wordwrap' => $this->mcm->system_params[ 'email_config_wordwrap' ],
							'smtp_crypto' => $this->mcm->system_params[ 'email_config_smtp_crypto' ],
							'newline' => $newline,
							'useragent' => $mail_useragent,
							
						);
						
						$this->load->library( 'email', $config );
						
						//$this->email->cc( array( 'Produção' => 'fabiano@viaeletronica.com.br' ) ); 
						//$this->email->bcc( array( 'Suporte' => 'suporte@viaeletronica.com.br' ) ); 
						
						$this->email->subject( $data[ 'post' ][ 'subject' ] );
						
						// verificando se o tema atual possui a view
						if ( file_exists( THEMES_PATH . call_user_func( $this->mcm->environment . '_theme_components_views_path' ) . $this->component_view_folder . DS . __FUNCTION__ . DS . $action . DS . $data[ 'params' ][ 'contact_details_layout' ] . DS . 'sending_email.php') ){
							
							$this->email->message( $this->load->view( call_user_func( $this->mcm->environment . '_theme_components_views_path' ) . $this->component_view_folder . DS . __FUNCTION__ . DS . $action . DS . $data[ 'params' ][ 'contact_details_layout' ] . DS . 'sending_email', $data, TRUE ) );
							
						}
						// verificando se a view existe no diretório de views padrão
						else if ( file_exists( VIEWS_PATH . get_constant_name( $this->mcm->environment . '_COMPONENTS_VIEWS_PATH' ) . DS . $this->component_view_folder . DS . __FUNCTION__ . DS . $action . DS . $data[ 'params' ][ 'contact_details_layout' ] . DS . 'sending_email.php') ){
							
							$this->email->message( $this->load->view( get_constant_name( $this->mcm->environment . '_COMPONENTS_VIEWS_PATH' ) . $this->component_view_folder . DS . __FUNCTION__ . DS . $action . DS . $data[ 'params' ][ 'contact_details_layout' ] . DS . 'sending_email', $data, TRUE ) );
							//echo $this->load->view( get_constant_name( $this->mcm->environment . '_COMPONENTS_VIEWS_PATH' ) . $this->component_view_folder . DS . __FUNCTION__ . DS . $action . DS . $data[ 'params' ][ 'contact_details_layout' ] . DS . 'sending_email', $data, TRUE );
							
						}
						else{
							
							$this->email->message( lang( 'load_view_fail' ) . ': <b>' . VIEWS_PATH . get_constant_name( $this->mcm->environment . '_COMPONENTS_VIEWS_PATH' ) . $this->component_view_folder . DS . __FUNCTION__ . DS . $action . DS . $data[ 'params' ][ 'contact_details_layout' ] . DS . 'sending_email.php</b>' );
							
						}
						
						$this->email->to( $emails_to );
						
						if ( $this->input->post( 'email' ) ){
							
							$this->email->from( $data[ 'post' ][ 'email' ], $data[ 'post' ][ 'name' ] );
							$this->email->reply_to( $data[ 'post' ][ 'email' ] );
							
						}
						
						if ( $this->email->send() ){
							
							msg( ( 'sending_email_email_sent' ), 'success' );
							
						}
						else{
							
							echo $this->email->print_debugger();
							
						}
						
						redirect_last_url();
						
					}
					
				}
				else if ( ! $this->form_validation->run() AND validation_errors() != '' ){
					
					$data[ 'post' ] = $this->input->post();
					
					msg( ( 'sending_email_fail' ), 'title' );
					msg( validation_errors( '<div class="error">', '</div>' ), 'error' );
				}
				
				$this->_page(
					
					array(
						
						'component_view_folder' => $this->component_view_folder,
						'function' => __FUNCTION__,
						'action' => $action,
						'layout' => ( ( isset( $data[ 'params' ][ 'contact_details_layout' ] ) AND $data[ 'params' ][ 'contact_details_layout' ] ) ? $data[ 'params' ][ 'contact_details_layout' ] : 'default' ),
						'view' => $action,
						'data' => $data,
						
					 )
					
				 );
				
			}
			else{
				
				show_404();
				
			}
			
		}
		else{
			
			show_404();
			
		}
		
		/***************** Contact details ****************/
		/**************************************************/
		
	}
	
	/******************************* Component index ******************************/
	/******************************************************************************/
	/******************************************************************************/
	
}
