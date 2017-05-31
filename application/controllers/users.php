<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'controllers/main.php');

class Users extends Main {

	public function __construct(){
		
		parent::__construct();
		
		$this->load->language( array( 'calendar' ) );
		$this->load->model( 'users_mdl', 'users' );
		
		set_current_component();
		
	}
	
	public function index( $f_params = NULL ){
		
		// -------------------------------------------------
		// Parsing vars
		
		$f_params = $this->uri->ruri_to_assoc();
		
		$action =								isset( $f_params[ 'a' ] ) ? $f_params[ 'a' ] : NULL; // action
		$sub_action =							isset( $f_params[ 'sa' ] ) ? $f_params[ 'sa' ] : NULL; // sub action
		$acode =								isset( $f_params[ 'ac' ] ) ? $f_params[ 'ac' ] : NULL; // activation code
		$cpcode =								isset( $f_params[ 'cpssc' ] ) ? $f_params[ 'cpssc' ] : NULL; // change pass code
		$menu_item_id =							check_var( $this->mcm->current_menu_item ) ? $this->mcm->current_menu_item[ 'id' ] : 0; // menu item id
		
		// Parsing vars
		// -------------------------------------------------
		
		$this->component_function = __FUNCTION__;
		$this->component_function_action = $action;
		
		// obtendo os parâmetros globais do componente
		$component_params = get_params( $this->current_component[ 'params' ] );
		
		// obtendo os parâmetros do item de menu
		if ( check_var( $this->mcm->current_menu_item ) ){
			
			$menu_item_params = get_params( $this->mcm->current_menu_item[ 'params' ] );
			$data[ 'params' ] = filter_params( $component_params, $menu_item_params );
			
		}
		else {
			
			$data[ 'params' ] = $component_params;
			
		}
		
		// -------------------------------------------------
		// Login
		
		if ( $action === 'login' ){
			
			if ( $this->users->is_logged_in() ){
				
				redirect( get_url( $this->users->get_link_logout_page() ) );
				
			}
			
			$url = get_url( $this->uri->ruri_string() );
			
			$data[ 'url' ] = $url;
			
			// obtendo o título do conteúdo da página,
			$this->mcm->html_data[ 'page_content_title' ] = '';
			
			if ( isset( $data[ 'params' ][ 'show_page_content_title' ] ) ) {
				
				$data[ 'params' ][ 'show_page_content_title' ] = $data['params']['show_page_content_title'] ? 1 : FALSE;
				
			}
			else {
				
				$data[ 'params' ][ 'show_page_content_title' ] = 1;
				
			}
			
			if ( @$data[ 'params' ][ 'custom_page_title' ] ) {
				
				$this->mcm->html_data[ 'content' ][ 'title' ] = $data[ 'params' ][ 'custom_page_title' ];
				
			}
			else if ( $this->mcm->current_menu_item ) {
				
				$this->mcm->html_data[ 'content' ][ 'title' ] = $this->mcm->current_menu_item[ 'title' ];
				
			}
			else {
				
				$this->mcm->html_data[ 'content' ][ 'title' ] = lang( 'login' );
				
			}
			
			$this->voutput->set_head_title( $this->mcm->html_data[ 'content' ][ 'title' ] );
			
			//validação dos campos
			$this->form_validation->set_rules( 'username', lang( 'username' ), 'trim|required' );
			$this->form_validation->set_rules( 'password', lang( 'password' ), 'trim|required' );
			
			if ( $this->input->post( NULL, TRUE ) AND $this->form_validation->run() ) {
				
				
				// do login params
				$dlp = array(
					
					'user_data' => array(
						
						'username' => $this->input->post( 'username', TRUE ),
						'email' => $this->input->post( 'username', TRUE ),
						'password' => $this->input->post( 'password', TRUE ),
						
					)
					
				);
				
				if ( $this->input->post( 'keep_me_logged_in', TRUE ) ){
					
					$dlp[ 'session_mode' ] = 'persistent';
					
				}
				
				$dlp[ 'notif_success' ] = TRUE;
				
				$this->users->do_login( $dlp );
				
			}
			// caso contrário se a validação dos campos falhar e existir mensagens de erro
			else if ( !$this->form_validation->run() AND validation_errors() != '' ) {
				
				$data[ 'post' ] = $this->input->post();
				
				msg( ( 'login_fail' ), 'title' );
				msg( validation_errors( '<div class="error">', '</div>' ), 'error' );
				
			}
			
			$this->_page(
				
				array(
					
					'component_view_folder' => $this->component_view_folder,
					'function' => __FUNCTION__,
					'action' => $action,
					'view' => $action,
					'data' => $data,
					
				)
				
			);
			
		}
		
		// Login
		// -------------------------------------------------
		
		// -------------------------------------------------
		// Logout
		
		else if ( $action === 'logout' ){
			
			if ( ! $this->users->is_logged_in() ){
				
				redirect( get_url( $this->users->get_link_login_page() ) );
				
			}
			
			$url = get_url( $this->uri->ruri_string() );
			
			$data = array(
				
				'url' => $url,
				'params' => array(),
				
			);
			
			$data[ 'params' ] = filter_params( get_params( $this->mcm->current_component[ 'params' ] ), get_params( $this->mcm->current_menu_item[ 'params' ] ) );
			
			// obtendo o título do conteúdo da página,
			$this->mcm->html_data['page_content_title'] = '';
			if ( isset($data['params']['show_page_content_title']) ){
				$data['params']['show_page_content_title'] = $data['params']['show_page_content_title']?1:0;
			}
			else{
				$data['params']['show_page_content_title'] = 1;
			}
			
			if ( @$data['params']['custom_page_title'] ){
				$this->mcm->html_data['content']['title'] = $data['params']['custom_page_title'];
			}
			else if ( $this->mcm->current_menu_item ){
				$this->mcm->html_data['content']['title'] = $this->mcm->current_menu_item['title'];
			}
			else{
				$this->mcm->html_data['content']['title'] = lang('logout');
			}
			
			$this->voutput->set_head_title( $this->mcm->html_data[ 'content' ][ 'title' ] );
			
			if ( $this->input->post('submit_logout') ){
				
				$this->users->remove_access_hash();
				$this->users->remove_session_from_user();
				
				$this->session->unset_envdata();
				
				msg(('users_logout_success'),'success');
				
				redirect( get_url( $this->users->get_link_login_page( $current_menu_item_id ) ) );
				
			}
			
			$this->_page(
				
				array(
					
					'component_view_folder' => $this->component_view_folder,
					'function' => __FUNCTION__,
					'action' => $action,
					'view' => $action,
					'data' => $data,
					
				)
				
			);
			
		}
		
		// Logout
		// -------------------------------------------------
		
		// -------------------------------------------------
		// Register
		
		else if ( $action === 'register' AND check_var( $data[ 'params' ][ 'c_users_register_allow_new_site_registers' ] ) ){
			
			if ( $this->users->is_logged_in() ){
				
				redirect( get_url( $this->users->get_link_logout_page( $current_menu_item_id ) ) );
				
			}
			
			if ( ! check_var( $data[ 'params' ][ 'c_users_default_user_group_id_register' ] ) ) {
				
				msg( ( 'c_users_error_new_registers_need_group_id' ), 'error' );
				redirect();
				
			}
			
			$url = get_url( $this->uri->ruri_string() );
			
			$data[ 'url' ] = $url;
			
			// -------------------------------------------------
			// Html page stuff
			
			// obtendo o título do conteúdo da página,
			$this->mcm->html_data[ 'page_content_title' ] = '';
			
			if ( isset( $data[ 'params' ][ 'show_page_content_title' ] ) ) {
				
				$data[ 'params' ][ 'show_page_content_title' ] = $data['params']['show_page_content_title'] ? TRUE : FALSE;
				
			}
			else {
				
				$data[ 'params' ][ 'show_page_content_title' ] = TRUE;
				
			}
			
			if ( @$data[ 'params' ][ 'custom_page_title' ] ) {
				
				$this->mcm->html_data[ 'content' ][ 'title' ] = $data[ 'params' ][ 'custom_page_title' ];
				
			}
			else if ( $this->mcm->current_menu_item ) {
				
				$this->mcm->html_data[ 'content' ][ 'title' ] = $this->mcm->current_menu_item[ 'title' ];
				
			}
			else {
				
				$this->mcm->html_data[ 'content' ][ 'title' ] = lang( 'c_users_register_page_title' );
				
			}
			
			$this->voutput->set_head_title( $this->mcm->html_data[ 'content' ][ 'title' ] );
			
			// Html page stuff
			// -------------------------------------------------
			
			// -------------------------------------------------
			// Validation
			
			// -------------------------------------------------
			// validation form callbacks for pt-BR lang
			
			$_name_rules = 'trim|required|min_length[5]';
			
			if ( $this->mcm->filtered_system_params[ $this->mcm->environment . '_language' ] == 'pt-BR' ){
				
				$_name_rules .= '|normalizar_nome_ptbr';
				
			}
			
			// validation form callbacks for pt-BR lang
			// -------------------------------------------------
			
			$this->form_validation->set_rules( 'name', lang( 'name' ), $_name_rules );
			$this->form_validation->set_custom_message( 'name', 'required', lang( 'c_users_form_validation_name_required_error' ) );
			$this->form_validation->set_custom_message( 'name', 'min_length', lang( 'c_users_form_validation_name_min_length_error' ) );
			
			$this->form_validation->set_rules( 'username', lang( 'username' ), 'trim|required|min_length[3]|max_length[24]|is_unique[tb_users.username]|alpha_dash|lowercase' );
			$this->form_validation->set_custom_message( 'username', 'required', lang( 'c_users_form_validation_username_required_error' ) );
			$this->form_validation->set_custom_message( 'username', 'min_length', lang( 'c_users_form_validation_username_min_length_error' ) );
			$this->form_validation->set_custom_message( 'username', 'max_length', lang( 'c_users_form_validation_username_max_length_error' ) );
			$this->form_validation->set_custom_message( 'username', 'alpha_dash', lang( 'c_users_form_validation_username_alpha_dash_error' ) );
			/*
				1° e-mail address
				2° login page
				3° pass recover page
				4° email recover page
				5° username recover page
				
			*/
			$this->form_validation->set_custom_message( 'username', 'is_unique', sprintf(
				
				lang( 'c_users_form_validation_username_is_unique_error' ),
				$this->input->post( 'username', TRUE ),
				$this->users->get_link_login_page(),
				$this->users->get_link_get_cplink_page(),
				$this->users->get_link_email_recover_page(),
				$this->users->get_link_recover_username_page()
				
				)
				
			);
			
			$this->form_validation->set_rules( 'email', lang( 'email' ), 'trim|required|email|is_unique[tb_users.email]|lowercase|valid_email' );
			$this->form_validation->set_custom_message( 'email', 'required', lang( 'c_users_form_validation_email_required_error' ) );
			$this->form_validation->set_custom_message( 'email', 'valid_email', lang( 'c_users_form_validation_email_valid_email_error' ) );
			$this->form_validation->set_custom_message( 'email', 'is_unique', sprintf(
				
				lang( 'c_users_form_validation_email_is_unique_error' ),
				$this->input->post( 'email', TRUE ),
				$this->users->get_link_login_page(),
				$this->users->get_link_get_cplink_page(),
				$this->users->get_link_email_recover_page(),
				$this->users->get_link_recover_username_page()
				
				)
				
			);
			
			$this->form_validation->set_rules( 'password',lang( 'password' ),'trim|required|min_length[6]|max_length[24]' );
			$this->form_validation->set_custom_message( 'password', 'required', lang( 'c_users_form_validation_password_required_error' ) );
			$this->form_validation->set_custom_message( 'password', 'min_length', lang( 'c_users_form_validation_password_min_length_error' ) );
			$this->form_validation->set_custom_message( 'password', 'max_length', lang( 'c_users_form_validation_password_max_length_error' ) );
			
			$_confirm_password_rules = 'trim|matches[password]';
			
			if ( $this->input->post( 'password', TRUE ) AND ! $this->input->post( 'confirm_password', TRUE ) ){
				
				$_confirm_password_rules .= '|required';
				
			}
			
			$this->form_validation->set_rules( 'confirm_password', lang( 'confirm_password' ), $_confirm_password_rules );
			$this->form_validation->set_custom_message( 'confirm_password', 'required', lang( 'c_users_form_validation_confirm_password_required_error' ) );
			$this->form_validation->set_custom_message( 'confirm_password', 'matches', lang( 'c_users_form_validation_confirm_password_matche_error' ) );
			
			$this->form_validation->set_rules( 'captcha', lang( 'captcha' ), 'trim|required|lowercase|captcha' );
			$this->form_validation->set_custom_message( 'captcha', 'required', lang( 'c_users_form_validation_captcha_required_error' ) );
			$this->form_validation->set_custom_message( 'captcha', 'captcha', lang( 'c_users_form_validation_captcha_captcha_error' ) );
			
			unset( $_name_rules );
			unset( $_confirm_password_rules );
			
			// Validation
			// -------------------------------------------------
			
			// -------------------------------------------------
			// Finalization
			
			if ( $this->input->post( NULL, TRUE ) AND $this->form_validation->run() ) {
				
				$db_data = elements( array(
					
					'username',
					'password',
					'name',
					'email',
					'group_id',
					
				), $this->input->post( NULL, TRUE ) );
				
				$db_data[ 'password' ] = $this->users->encode_password( $db_data[ 'password' ] );
				
				if ( check_var( $data[ 'params' ][ 'c_users_register_new_users_need_activate_account' ] ) ) {
					
					$db_data[ 'status' ] = 0;
					
				}
				else {
					
					$db_data[ 'status' ] = 1;
					
				}
				
				$db_data[ 'group_id' ] = $data[ 'params' ][ 'c_users_default_user_group_id_register' ];
				
				$db_data[ 'params' ][ 'login_wrong_pass_count' ] = 0;
				
				$db_data[ 'params' ] = json_encode( $db_data[ 'params' ] );
				
				//echo '<pre>' . print_r( $db_data, TRUE ) . '</pre>'; exit;
				
				if ( $return_id = $this->users->insert_user( $db_data ) ) {
					
					if ( check_var( $data[ 'params' ][ 'c_users_register_new_users_need_activate_account' ] ) ) {
						
						if ( $this->users->send_acode( array(
							
							'user_id' => $return_id,
							'notif' => FALSE,
							
						) ) ) {
							
							msg( ( 'notif_c_users_account_created_success_no_active_site' ), 'success' );
							
						}
						else {
							
							msg( ( 'notif_c_users_account_created_success_no_active_site_no_send_acode' ), 'success' );
							msg( ( 'err_c_users_cant_send_acode' ), 'error' );
							
						}
						
					}
					else {
						
						msg( ( 'c_users_user_register_success' ), 'success' );
						
					}
					
					// -------------------------------------------------
					// E-nail stuff
					
				}
				
			}
			// caso contrário se a validação dos campos falhar e existir mensagens de erro
			else if ( !$this->form_validation->run() AND validation_errors() != '' ) {
				
				$data[ 'post' ] = $this->input->post();
				
				msg( ( 'login_fail' ), 'title' );
				msg( validation_errors( '<div class="error">', '</div>' ), 'error' );
				
			}
			
			// Finalization
			// -------------------------------------------------
			
			$this->_page(
				
				array(
					
					'component_view_folder' => $this->component_view_folder,
					'function' => __FUNCTION__,
					'action' => $action,
					'view' => $action,
					'data' => $data,
					
				)
				
			);
			
		}
		
		// Register
		// -------------------------------------------------
		
		// -------------------------------------------------
		// Activate account
		
		else if ( $action === 'activate_account' ){
			
			// -------------------------------------------------
			// Validation
			
			$this->form_validation->set_rules( 'acode', lang( 'acode' ), 'trim|required' );
			$this->form_validation->set_custom_message( 'acode', 'required', lang( 'c_users_form_validation_acode_required_error' ) );
			
			$this->form_validation->set_rules( 'captcha', lang( 'captcha' ), 'trim|required|lowercase|captcha' );
			$this->form_validation->set_custom_message( 'captcha', 'required', lang( 'c_users_form_validation_captcha_required_error' ) );
			$this->form_validation->set_custom_message( 'captcha', 'captcha', lang( 'c_users_form_validation_captcha_captcha_error' ) );
			
			// Validation
			// -------------------------------------------------
			
			// -------------------------------------------------
			// Finalization
			
			
			if ( $acode AND $valid_acode = $this->users->validate_tmp_code( $acode ) ) {
				
				msg( ( 'notif_c_users_activation_account_success' ), 'success' );
				
				redirect( get_url( $this->users->get_link_login_page() ) );
				
			}
			else if ( $acode AND ! $valid_acode ) {
				
				msg( ( 'notif_c_users_invalid_acode_error' ), 'error' );
				
				redirect( get_url( $this->users->get_link_resend_acode_page() ) );
				
			}
			else if ( $this->input->post( 'acode', TRUE ) AND $this->form_validation->run() ) {
				
				$acode = $this->input->post( 'acode', TRUE );
				
				if ( $acode AND $this->users->validate_tmp_code( $acode ) ){
					
					msg( ( 'notif_c_users_activation_account_success' ), 'success' );
					
					redirect( get_url( $this->users->get_link_login_page() ) );
					
				}
				else {
					
					msg( ( 'notif_c_users_invalid_acode_error' ), 'error' );
					
				}
				
			}
			// caso contrário se a validação dos campos falhar e existir mensagens de erro
			else if ( ! $this->form_validation->run() AND validation_errors() != '' ) {
				
				msg( ( 'notif_c_users_activate_account_error' ), 'title' );
				msg( validation_errors( '<div class="error">', '</div>' ), 'error' );
				
			}
			
			// Finalization
			// -------------------------------------------------
			
			$this->_page(
				
				array(
					
					'component_view_folder' => $this->component_view_folder,
					'function' => __FUNCTION__,
					'action' => $action,
					'view' => $action,
					'data' => $data,
					
				)
				
			);
			
			//show_404();
			
		}
		
		// Activate account
		// -------------------------------------------------
		
		// -------------------------------------------------
		// Resend activation code
		
		else if ( $action === 'resend_acode' ){
			
			// -------------------------------------------------
			// Validation
			
			$this->form_validation->set_rules( 'email', lang( 'email' ), 'trim|required|email|lowercase|valid_email' );
			$this->form_validation->set_custom_message( 'email', 'required', lang( 'c_users_form_validation_email_required_error' ) );
			$this->form_validation->set_custom_message( 'email', 'valid_email', lang( 'c_users_form_validation_email_valid_email_error' ) );
			
			$this->form_validation->set_rules( 'captcha', lang( 'captcha' ), 'trim|required|lowercase|captcha' );
			$this->form_validation->set_custom_message( 'captcha', 'required', lang( 'c_users_form_validation_captcha_required_error' ) );
			$this->form_validation->set_custom_message( 'captcha', 'captcha', lang( 'c_users_form_validation_captcha_captcha_error' ) );
			
			if ( $this->input->post( NULL, TRUE ) ) {
				
				$email = $this->input->post( 'email', TRUE );
				
			}
			
			// Validation
			// -------------------------------------------------
			
			// -------------------------------------------------
			// Finalization
			
			if ( $this->input->post( NULL, TRUE ) AND $this->form_validation->run() ) {
				
				if ( $user = $this->users->get_user( array( 't1.email' => $this->input->post( 'email', TRUE ) ) )->row_array() ) {
					
					if ( $this->users->send_acode( $user[ 'id' ], TRUE ) ) {
						
						redirect();
						
					}
					else {
						
						msg( ( 'notif_c_users_acode_sent_error' ), 'error' );
						
					}
					
				}
				else {
					
					msg( ( 'notif_c_users_invalid_acode_error' ), 'error' );
					
				}
				
			}
			// caso contrário se a validação dos campos falhar e existir mensagens de erro
			else if ( !$this->form_validation->run() AND validation_errors() != '' ) {
				
				$data[ 'post' ] = $this->input->post();
				
				msg( ( 'c_users_resend_acode_error' ), 'title' );
				msg( validation_errors( '<div class="error">', '</div>' ), 'error' );
				
			}
			
			// Finalization
			// -------------------------------------------------
			
			$this->_page(
				
				array(
					
					'component_view_folder' => $this->component_view_folder,
					'function' => __FUNCTION__,
					'action' => $action,
					'view' => $action,
					'data' => $data,
					
				)
				
			);
			
		}
		
		// Resend activation code
		// -------------------------------------------------
		
		// -------------------------------------------------
		// Get change password link
		
		else if ( $action === 'get_cplink' ){
			
			// -------------------------------------------------
			// Validation
			
			$this->form_validation->set_rules( 'email', lang( 'email' ), 'trim|required|email|lowercase|valid_email' );
			$this->form_validation->set_custom_message( 'email', 'required', lang( 'c_users_form_validation_email_required_error' ) );
			$this->form_validation->set_custom_message( 'email', 'valid_email', lang( 'c_users_form_validation_email_valid_email_error' ) );
			
			$this->form_validation->set_rules( 'captcha', lang( 'captcha' ), 'trim|required|lowercase|captcha' );
			$this->form_validation->set_custom_message( 'captcha', 'required', lang( 'c_users_form_validation_captcha_required_error' ) );
			$this->form_validation->set_custom_message( 'captcha', 'captcha', lang( 'c_users_form_validation_captcha_captcha_error' ) );
			
			if ( $this->input->post( NULL, TRUE ) ) {
				
				$email = $this->input->post( 'email', TRUE );
				
			}
			
			// Validation
			// -------------------------------------------------
			
			// -------------------------------------------------
			// Finalization
			
			if ( $this->input->post( NULL, TRUE ) AND $this->form_validation->run() ) {
				
				if ( $user = $this->users->get_user( array( 't1.email' => $this->input->post( 'email', TRUE ) ) )->row_array() AND $user[ 'status' ] != 0 ) {
					
					if ( $this->users->send_cplink( $user[ 'id' ], TRUE ) ) {
						
						redirect();
						
					}
					else {
						
						msg( ( 'notif_c_users_cplink_sent_error' ), 'error' );
						
					}
					
				}
				else if ( $user[ 'status' ] == 0 ) {
					
					msg( ( 'notif_c_users_already_have_account_disabled' ), 'error' );
					
					msg( lang( sprintf(
							
							lang( 'notif_c_users_send_cplink_account_disabled_desc' ),
							/*
								1° e-mail address
								2° login page
								3° pass recover page
								4° email recover page
								5° username recover page
								6° resend activation code page
								
							*/
							$user[ 'email' ],
							$this->users->get_link_login_page(),
							$this->users->get_link_get_cplink_page(),
							$this->users->get_link_email_recover_page(),
							$this->users->get_link_recover_username_page(),
							$this->users->get_link_resend_acode_page()
							
						)
						
					), 'error' );
					
				}
				else {
					
					msg( ( 'notif_c_users_send_cplink_invalid_user_error' ), 'error' );
					
				}
				
			}
			// caso contrário se a validação dos campos falhar e existir mensagens de erro
			else if ( !$this->form_validation->run() AND validation_errors() != '' ) {
				
				$data[ 'post' ] = $this->input->post();
				
				msg( ( 'c_users_send_cplink_error' ), 'title' );
				msg( validation_errors( '<div class="error">', '</div>' ), 'error' );
				
			}
			
			// Finalization
			// -------------------------------------------------
			
			$this->_page(
				
				array(
					
					'component_view_folder' => $this->component_view_folder,
					'function' => __FUNCTION__,
					'action' => $action,
					'view' => $action,
					'data' => $data,
					
				)
				
			);
			
		}
		
		// Get change password link
		// -------------------------------------------------
		
		// -------------------------------------------------
		// Change password
		
		else if ( $action === 'change_pass' AND $cpcode ){
			
			// -------------------------------------------------
			// Validation
			
			$this->form_validation->set_rules( 'password',lang( 'password' ),'trim|required|min_length[6]|max_length[24]' );
			$this->form_validation->set_custom_message( 'password', 'required', lang( 'c_users_form_validation_new_password_required_error' ) );
			$this->form_validation->set_custom_message( 'password', 'min_length', lang( 'c_users_form_validation_password_min_length_error' ) );
			$this->form_validation->set_custom_message( 'password', 'max_length', lang( 'c_users_form_validation_password_max_length_error' ) );
			
			$_confirm_password_rules = 'trim|matches[password]';
			
			if ( $this->input->post( 'password', TRUE ) AND ! $this->input->post( 'confirm_password', TRUE ) ){
				
				$_confirm_password_rules .= '|required';
				
			}
			
			$this->form_validation->set_rules( 'confirm_password', lang( 'confirm_password' ), $_confirm_password_rules );
			$this->form_validation->set_custom_message( 'confirm_password', 'required', lang( 'c_users_form_validation_confirm_password_required_error' ) );
			$this->form_validation->set_custom_message( 'confirm_password', 'matches', lang( 'c_users_form_validation_confirm_password_matche_error' ) );
			
			$this->form_validation->set_rules( 'captcha', lang( 'captcha' ), 'trim|required|lowercase|captcha' );
			$this->form_validation->set_custom_message( 'captcha', 'required', lang( 'c_users_form_validation_captcha_required_error' ) );
			$this->form_validation->set_custom_message( 'captcha', 'captcha', lang( 'c_users_form_validation_captcha_captcha_error' ) );
			
			unset( $_name_rules );
			unset( $_confirm_password_rules );
			
			// Validation
			// -------------------------------------------------
			
			// -------------------------------------------------
			// Finalization
			
			if ( $valid_code = $this->users->tmp_code_is_valid( $cpcode ) ) {
				
				if ( $this->form_validation->run() ) {
					
					$db_data = elements( array(
						
						'password',
						
					), $this->input->post( NULL, TRUE ) );
					
					$db_data[ 'password' ] = $this->users->encode_password( $db_data[ 'password' ] );
					
					$db_data[ 'params' ] = get_params( $valid_code[ 'user' ][ 'params' ] );
					
					$last_pass_update_datetime = gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] );
					$last_pass_update_datetime = ov_strftime( '%Y-%m-%d %T', $last_pass_update_datetime );
					
					$db_data[ 'params' ][ 'last_pass_update_datetime' ] = $last_pass_update_datetime;
					
					$db_data[ 'params' ] = json_encode( $db_data[ 'params' ] );
					
					if ( $this->users->update_user( $db_data, array( 'id' => $valid_code[ 'user_id' ] ) ) ) {
						
						msg( ( 'notif_c_users_pass_changed_success' ), 'success' );
						
						$this->users->validate_tmp_code( $cpcode );
						
						redirect( get_url( $this->users->get_link_login_page() ) );
						
					}
					
				}
				
				else if ( $this->input->post( 'acode', TRUE ) AND $this->form_validation->run() ) {
					
					$acode = $this->input->post( 'acode', TRUE );
					
					if ( $acode AND $this->users->validate_tmp_code( $acode ) ){
						
						msg( ( 'notif_c_users_activation_account_success' ), 'success' );
						
						redirect( get_url( $this->users->get_link_login_page() ) );
						
					}
					else {
						
						msg( ( 'notif_c_users_invalid_acode_error' ), 'error' );
						
					}
					
				}
				// caso contrário se a validação dos campos falhar e existir mensagens de erro
				else if ( ! $this->form_validation->run() AND validation_errors() != '' ) {
					
					msg( ( 'notif_c_users_change_pass_error' ), 'title' );
					msg( validation_errors( '<div class="error">', '</div>' ), 'error' );
					
				}
				
			}
			else {
				
				msg( ( 'notif_c_users_invalid_cpcode_error' ), 'error' );
				
				redirect( get_url( $this->users->get_link_get_cplink_page() ) );
				
			}
			
			// Finalization
			// -------------------------------------------------
			
			$this->_page(
				
				array(
					
					'component_view_folder' => $this->component_view_folder,
					'function' => __FUNCTION__,
					'action' => $action,
					'view' => $action,
					'data' => $data,
					
				)
				
			);
			
			//show_404();
			
		}
		
		// Change password
		// -------------------------------------------------
		
		// -------------------------------------------------
		// Get recover username link
		
		else if ( $action === 'recover_username' ){
			
			// -------------------------------------------------
			// Validation
			
			$this->form_validation->set_rules( 'email', lang( 'email' ), 'trim|required|email|lowercase|valid_email' );
			$this->form_validation->set_custom_message( 'email', 'required', lang( 'c_users_form_validation_email_required_error' ) );
			$this->form_validation->set_custom_message( 'email', 'valid_email', lang( 'c_users_form_validation_email_valid_email_error' ) );
			
			$this->form_validation->set_rules( 'captcha', lang( 'captcha' ), 'trim|required|lowercase|captcha' );
			$this->form_validation->set_custom_message( 'captcha', 'required', lang( 'c_users_form_validation_captcha_required_error' ) );
			$this->form_validation->set_custom_message( 'captcha', 'captcha', lang( 'c_users_form_validation_captcha_captcha_error' ) );
			
			if ( $this->input->post( NULL, TRUE ) ) {
				
				$email = $this->input->post( 'email', TRUE );
				
			}
			
			// Validation
			// -------------------------------------------------
			
			// -------------------------------------------------
			// Finalization
			
			if ( $this->input->post( NULL, TRUE ) AND $this->form_validation->run() ) {
				
				if ( $user = $this->users->get_user( array( 't1.email' => $this->input->post( 'email', TRUE ) ) )->row_array() ) {
					
					if ( $this->users->send_username( $user[ 'id' ], TRUE ) ) {
						
						redirect();
						
					}
					else {
						
						msg( ( 'notif_c_users_recover_username_sent_error' ), 'error' );
						
					}
					
				}
				else {
					
					msg( ( 'notif_c_users_recover_username_invalid_user_error' ), 'error' );
					
				}
				
			}
			// caso contrário se a validação dos campos falhar e existir mensagens de erro
			else if ( !$this->form_validation->run() AND validation_errors() != '' ) {
				
				$data[ 'post' ] = $this->input->post();
				
				msg( ( 'c_users_recover_username_invalid_data_error' ), 'title' );
				msg( validation_errors( '<div class="error">', '</div>' ), 'error' );
				
			}
			
			// Finalization
			// -------------------------------------------------
			
			$this->_page(
				
				array(
					
					'component_view_folder' => $this->component_view_folder,
					'function' => __FUNCTION__,
					'action' => $action,
					'view' => $action,
					'data' => $data,
					
				)
				
			);
			
		}
		
		// Get recover username link
		// -------------------------------------------------
		
		else {
			
			show_404();
			
		}
		
	}
	
}
