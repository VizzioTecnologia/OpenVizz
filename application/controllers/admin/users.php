<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'controllers/admin/main.php');

class Users extends Main {
	
	public function __construct(){
		
		parent::__construct();
		
		set_current_component();
		
	}
	
	public function index( $f_params = NULL ){
		
		$this->um( $f_params );
		
	}
	
	public function um( $f_params = NULL ){
		
		$get = $this->input->get( NULL, TRUE );
		$post = $this->input->post( NULL, TRUE );
		
		$data[ 'get' ] = & $get;
		$data[ 'post' ] = & $post;
		
		// -------------------------------------------------
		// Parsing vars ------------------------------------
		
		$f_params = is_array( $f_params ) ? $f_params : $this->uri->ruri_to_assoc();
		
		$action =								isset( $f_params[ 'a' ] ) ? $f_params[ 'a' ] : 'ul'; // action
		$user_id =								isset( $f_params[ 'uid' ] ) ? $this->users->decode_user_id( $f_params[ 'uid' ] ) : NULL; // user id
		$ob =									isset( $f_params[ 'ob' ] ) ? $f_params[ 'ob' ] : NULL; // order by
		$f =									isset( $f_params[ 'f' ] ) ? json_decode( base64_decode( urldecode( $f_params[ 'f' ] ) ), TRUE ) : array(); // filters
		$sfsp =									isset( $f_params[ 'sfsp' ] ) ? json_decode( base64_decode( urldecode( $f_params[ 'sfsp' ] ) ), TRUE ) : array(); // search filters
		
		$cp =									isset( $f_params[ 'cp' ] ) ? ( int ) $f_params[ 'cp' ] : NULL; // current page
			$cp =								( $cp < 1 ) ? 1 : $cp;
		$ipp =									isset( $f_params[ 'ipp' ] ) ? ( int ) $f_params[ 'ipp' ] : NULL; // items per page
			$ipp =								isset( $post[ 'ipp' ] ) ? ( int ) $post[ 'ipp' ] : $ipp; // items per page
		
		$data = array();
		
		// -------------------------------------------------
		// Parsing / setting up vars -----------------------
		
		$this->component_function = __FUNCTION__;
		$this->component_function_action = $action;
		
		$url = get_url( 'admin' . $this->uri->ruri_string() );
		
		// verifica se o usuário atual possui privilégios para gerenciar usuários, porém pode editar seu próprio usuário
		if ( ! ( $action == 'e' AND $user_id == $this->users->user_data[ 'id' ] ) AND ! $this->users->check_privileges( 'users_management_users_management' ) ){
			
			msg( ( 'access_denied' ), 'title' );
			msg( ( 'access_denied_users_management_users_management' ), 'error' );
			redirect( 'admin' );
			
		};
		
		if ( $user_id ) {
			
			$is_on_same_or_below_group_level = $this->users->check_if_user_is_on_same_and_low_group_level( $user_id );
			$is_on_same_group_level = $this->users->check_if_user_is_on_same_group_level( $user_id );
			$is_on_same_group_or_below = $this->users->check_if_user_is_on_same_group_and_below( $user_id );
			$is_on_same_group = $this->users->check_if_user_is_on_same_group( $user_id );
			$is_on_below_groups = $this->users->check_if_user_is_on_below_groups( $user_id );
			
			$can_edit_all_users = $this->users->check_privileges( 'can_edit_all_users' );
			$can_edit_only_same_and_low_group_level = $this->users->check_privileges( 'users_management_can_edit_only_same_and_low_group_level' );
			$can_edit_only_same_group_level = $this->users->check_privileges( 'users_management_can_edit_only_same_group_level' );
			$can_edit_only_same_group_and_below = $this->users->check_privileges( 'users_management_can_edit_only_same_group_and_below' );
			$can_edit_only_same_group = $this->users->check_privileges( 'users_management_can_edit_only_same_group' );
			$can_edit_only_below_groups = $this->users->check_privileges( 'users_management_can_edit_only_low_groups' );
			
		}
		
		// -------------------------------------------------
		// Parsing / setting up vars -----------------------
		
		/*
		 ********************************************************
		 --------------------------------------------------------
		 Users list
		 --------------------------------------------------------
		*/
		
		if ( $action == 'ul' ){
			
			// get users checking privileges
			$users = $this->users->get_users_checking_privileges()->result_array();
			
			if ( ! check_var( $users ) ){
				
				msg( 'system_no_users', 'title' );
				msg( 'access_denied_users_management_users_management', 'error' );
				redirect( 'admin/main/index/logout' );
				
			}
			
			if ( check_var( $users ) ){
				
				$data[ 'users' ] = $users;
				
				set_last_url( $url );
				
				$this->_page(
					
					array(
						
						'component_view_folder' => $this->component_view_folder,
						'function' => __FUNCTION__,
						'action' => 'users_list',
						'layout' => 'default',
						'view' => 'users_list',
						'data' => $data,
						
					)
					
				);
				
			}
			
		}
		
		/*
		 --------------------------------------------------------
		 Users list
		 --------------------------------------------------------
		 ********************************************************
		*/
		
		/*
		 ********************************************************
		 --------------------------------------------------------
		 Activate user
		 --------------------------------------------------------
		*/
		
		else if ( ( $action == 'ena' OR $action == 'dis' ) AND $user_id AND $user_id != $this->users->user_data[ 'id' ] ){
			
			if ( $can_edit_only_same_and_low_group_level AND ! ( $is_on_same_or_below_group_level ) ) {
				
				msg( ( 'access_denied' ), 'title' );
				msg( ( 'access_denied_users_management_can_edit_only_same_and_low_group_level' ), 'error' );
				
				redirect_last_url();
				
			}
			else if ( $can_edit_only_same_group_level AND ! ( $is_on_same_group_level ) ) {
				
				msg( ( 'access_denied' ), 'title' );
				msg( ( 'access_denied_users_management_edit_same_group_level' ), 'error' );
				
				redirect_last_url();
				
			}
			else if ( $can_edit_only_same_group_and_below AND ! ( $is_on_same_group_or_below ) ) {
				
				msg( ( 'access_denied' ), 'title' );
				msg( ( 'access_denied_users_management_edit_same_group_and_below' ), 'error' );
				
				redirect_last_url();
				
			}
			else if ( $can_edit_only_same_group AND ! ( $is_on_same_group ) ) {
				
				msg( ( 'access_denied' ), 'title' );
				msg( ( 'access_denied_users_management_edit_same_group' ), 'error' );
				
				redirect_last_url();
				
			}
			else if ( $can_edit_only_below_groups AND ! ( $is_on_below_groups ) ) {
				
				msg( ( 'access_denied' ), 'title' );
				msg( ( 'access_denied_users_management_edit_below_groups' ), 'error' );
				
				redirect_last_url();
				
			}
			
			$update_data = array(
				
				'status' => $action == 'ena' ? '1' : '0',
				
			);
			
			if ( $this->users->update_user( $update_data, array( 'id' => $user_id ) ) ) {
				
				msg( 'user_updated', 'success' );
				
				redirect_last_url();
				
			}
			
		}
		
		/*
		 --------------------------------------------------------
		 Activate user
		 --------------------------------------------------------
		 ********************************************************
		*/
		
		/*
		 --------------------------------------------------------
		 Add / edit user
		 --------------------------------------------------------
		 ********************************************************
		*/
		
		else if ( $action == 'a' OR ( $action == 'e' AND $user_id ) ){
			
			$user = $this->users->get_user( array( 't1.id' => $user_id ) )->row_array();
			
			$data[ 'f_action' ] = $action;
			
			// verifica se o usuário atual possui privilégios para cadastrar usuários
			if ( $action == 'a' AND ! $this->users->check_privileges( 'users_management_can_add_user' ) ) {
				
				msg( ( 'access_denied' ), 'title' );
				msg( ( 'access_denied_users_management_can_add_user' ), 'error' );
				redirect_last_url();
				
			};
			
			// verifica se o usuário atual possui privilégios para ver informações dos usuários, exceto o seu próprio
			if ( ! $this->users->check_privileges( 'users_management_view_personal_info' ) AND $user_id != $this->users->user_data[ 'id' ] ) {
				
				msg( ( 'access_denied' ), 'title' );
				msg( ( 'access_denied_users_management_view_personal_info' ), 'error' );
				redirect_last_url();
				
			};
			
			// checa as permissões
			if ( $action == 'e' AND $this->input->post( NULL, TRUE ) AND ! $this->input->post( 'submit_cancel' ) ){
				
				if ( $user_id != $this->users->user_data[ 'id' ] AND ! $this->users->check_privileges( 'users_management_can_edit_only_your_own_user' ) ){
					
					if ( $can_edit_only_same_and_low_group_level AND ! ( $is_on_same_or_below_group_level ) ) {
						
						msg( ( 'access_denied' ), 'title' );
						msg( ( 'access_denied_users_management_can_edit_only_same_and_low_group_level' ), 'error' );
						
						redirect_last_url();
						
					}
					else if ( $can_edit_only_same_group_level AND ! ( $is_on_same_group_level ) ) {
						
						msg( ( 'access_denied' ), 'title' );
						msg( ( 'access_denied_users_management_edit_same_group_level' ), 'error' );
						
						redirect_last_url();
						
					}
					else if ( $can_edit_only_same_group_and_below AND ! ( $is_on_same_group_or_below ) ) {
						
						msg( ( 'access_denied' ), 'title' );
						msg( ( 'access_denied_users_management_edit_same_group_and_below' ), 'error' );
						
						redirect_last_url();
						
					}
					else if ( $can_edit_only_same_group AND ! ( $is_on_same_group ) ) {
						
						msg( ( 'access_denied' ), 'title' );
						msg( ( 'access_denied_users_management_edit_same_group' ), 'error' );
						
						redirect_last_url();
						
					}
					else if ( $can_edit_only_below_groups AND ! ( $is_on_below_groups ) ) {
						
						msg( ( 'access_denied' ), 'title' );
						msg( ( 'access_denied_users_management_edit_below_groups' ), 'error' );
						
						redirect_last_url();
						
					}
					
				}
				else if ( $user_id != $this->users->user_data[ 'id' ] AND $this->users->check_privileges( 'users_management_can_edit_only_your_own_user' ) ){
					
					msg( ( 'access_denied' ), 'title' );
					msg( ( 'access_denied_users_management_edit_only_your_own_user' ), 'error' );
					
					redirect_last_url();
					
				};
				
			}
			
			$data[ 'user' ] = $user;
			$data[ 'users_groups' ] = $this->users->get_accessible_users_groups( $user_id );
			$data[ 'users_groups' ] = $this->mcm->get_array_tree(
				
				array(
					
					'array' => $data[ 'users_groups' ],
					'parent_id_string' => 'parent',
					
				)
				
			);
			
			/******************************/
			/********* Parâmetros *********/
			
			// obtendo as especificações dos parâmetros
			$data[ 'params_spec' ] = $this->users->get_user_params();
			
			if ( $action == 'e' ) {
				
				// obtendo os valores atuais dos parâmetros
				$data[ 'current_params_values' ] = get_params( $user[ 'params' ] );
				
				// cruzando os valores padrões das especificações com os atuais
				$data['final_params_values'] = array_merge_recursive_distinct( $data[ 'params_spec' ][ 'params_spec_values' ], $data[ 'current_params_values' ] );
				
			}
			else {
				
				$data['final_params_values'] = $data['params_spec']['params_spec_values'];
				
			}
			
			// definindo as regras de validação
			set_params_validations( $data[ 'params_spec' ][ 'params_spec' ] );
			
			/********* Parâmetros *********/
			/******************************/
			
			// -------------------------------------------------
			// Validation
			
			// --------------------------
			// id field
			
			if ( $action == 'e' ) {
				
				$field_rules = array(
					
					'trim',
					'required',
					'numeric',
					'integer',
					
				);
				
				if ( $user_id != $this->input->post( 'id', TRUE ) ) {
					
					$field_rules[] = 'is_unique[tb_users.id]';
					
				}
				
				$field_rules = join( '|', $field_rules );
				
				if ( ( ( $user_id != $this->users->user_data[ 'id' ] AND $this->users->check_privileges( 'users_management_can_change_id_others' ) )
					OR ( $user_id == $this->users->user_data[ 'id' ] AND $this->users->check_privileges( 'users_management_can_change_id_email' ) ) )
					) {
					
					$this->form_validation->set_rules( 'id', lang( 'id' ), $field_rules );
					
					$this->form_validation->set_custom_message( 'id', 'required', lang( 'admin_c_users_form_validation_id_required_error' ) );
					$this->form_validation->set_custom_message( 'id', 'numeric', lang( 'admin_c_users_form_validation_id_numeric_error' ) );
					$this->form_validation->set_custom_message( 'id', 'integer', lang( 'admin_c_users_form_validation_id_integer_error' ) );
					$this->form_validation->set_custom_message( 'id', 'is_unique', sprintf(
						
						lang( 'admin_c_users_form_validation_id_is_unique_error' ),
						$this->input->post( 'id', TRUE )
						
						)
						
					);
					
				};
				
			}
			
			// id field
			// --------------------------
			
			// --------------------------
			// username field
			
			$field_rules = array(
				
				'trim',
				'required',
				'min_length[3]',
				'max_length[24]',
				'lowercase',
				'alpha_dash',
				
			);
			
			if ( $action == 'a' OR ( $action == 'e' AND $user[ 'username' ] != $this->input->post( 'username', TRUE ) ) ) {
				
				$field_rules[] = 'is_unique[tb_users.username]';
				
			}
			
			$field_rules = join( '|', $field_rules );
			
			$this->form_validation->set_rules( 'username', lang( 'username' ), $field_rules );
			$this->form_validation->set_custom_message( 'username', 'required', lang( 'admin_c_users_form_validation_username_required_error' ) );
			$this->form_validation->set_custom_message( 'username', 'min_length', lang( 'admin_c_users_form_validation_username_min_length_error' ) );
			$this->form_validation->set_custom_message( 'username', 'max_length', lang( 'admin_c_users_form_validation_username_max_length_error' ) );
			$this->form_validation->set_custom_message( 'username', 'alpha_dash', lang( 'admin_c_users_form_validation_username_alpha_dash_error' ) );
			/*
				1° e-mail address
				2° login page
				3° pass recover page
				4° email recover page
				5° username recover page
				
			*/
			$this->form_validation->set_custom_message( 'username', 'is_unique', sprintf(
				
				lang( 'admin_c_users_form_validation_username_is_unique_error' ),
				$this->input->post( 'username', TRUE )
				
				)
				
			);
			
			// username field
			// --------------------------
			
			// --------------------------
			// name field
			
			// --------------------------
			// validation form callbacks for pt-BR lang
			
			$field_rules = array(
				
				'trim',
				'required',
				'min_length[5]',
				
			);
			
			if ( $this->mcm->filtered_system_params[ $this->mcm->environment . '_language' ] == 'pt-BR' ){
				
				$field_rules[] = '|normalizar_nome_ptbr';
				
			}
			
			$field_rules = join( '|', $field_rules );
			
			// validation form callbacks for pt-BR lang
			// --------------------------
			
			$this->form_validation->set_rules( 'name', lang( 'name' ), $field_rules );
			$this->form_validation->set_custom_message( 'name', 'required', lang( 'admin_c_users_form_validation_name_required_error' ) );
			$this->form_validation->set_custom_message( 'name', 'min_length', lang( 'admin_c_users_form_validation_name_min_length_error' ) );
			
			// name field
			// --------------------------
			
			// --------------------------
			// email field
			
			$field_rules = array(
				
				'trim',
				'required',
				'email',
				'valid_email',
				'lowercase',
				
			);
			
			if ( $action == 'a' OR ( $action == 'e' AND $user[ 'email' ] != $this->input->post( 'email', TRUE ) ) ) {
				
				$field_rules[] = 'is_unique[tb_users.email]';
				
			}
			
			$field_rules = join( '|', $field_rules );
			
			$this->form_validation->set_rules( 'email', lang( 'email' ), $field_rules );
			$this->form_validation->set_custom_message( 'email', 'required', lang( 'admin_c_users_form_validation_email_required_error' ) );
			$this->form_validation->set_custom_message( 'email', 'valid_email', lang( 'admin_c_users_form_validation_email_valid_email_error' ) );
			$this->form_validation->set_custom_message( 'email', 'is_unique', sprintf(
				
				lang( 'admin_c_users_form_validation_email_is_unique_error' ),
				$this->input->post( 'email', TRUE )
				
				)
				
			);
			
			// email field
			// --------------------------
			
			// --------------------------
			// group id field
			
			$field_rules = array(
				
				'trim',
				'required',
				'integer',
				
			);
			
			$field_rules = join( '|', $field_rules );
			
			$this->form_validation->set_rules( 'group_id', lang( 'user_group' ), $field_rules );
			$this->form_validation->set_custom_message( 'group_id', 'required', lang( 'admin_c_users_form_validation_group_id_required_error' ) );
			
			// group id field
			// --------------------------
			
			// --------------------------
			// password field
			
			$field_rules = array(
				
				'trim',
				'min_length[6]',
				'max_length[24]',
				
			);
			
			if ( $action == 'a' ) {
				
				$field_rules[] = 'required';
				
			}
			
			$field_rules = join( '|', $field_rules );
			
			$this->form_validation->set_rules( 'password',lang( 'password' ),'$field_rules' );
			$this->form_validation->set_custom_message( 'password', 'required', lang( 'admin_c_users_form_validation_password_required_error' ) );
			$this->form_validation->set_custom_message( 'password', 'min_length', lang( 'admin_c_users_form_validation_password_min_length_error' ) );
			$this->form_validation->set_custom_message( 'password', 'max_length', lang( 'admin_c_users_form_validation_password_max_length_error' ) );
			
			// password field
			// --------------------------
			
			// --------------------------
			// confirm password field
			
			$field_rules = array(
				
				'trim',
				'matches[password]',
				
			);
			
			$_confirm_password_rules = 'trim|matches[password]';
			
			if ( $action == 'a' OR ( $this->input->post( 'password', TRUE ) AND ! $this->input->post( 'confirm_password', TRUE ) ) ){
				
				$field_rules[] = 'required';
				
			}
			
			$field_rules = join( '|', $field_rules );
			
			$this->form_validation->set_rules( 'confirm_password', lang( 'confirm_password' ), $field_rules );
			$this->form_validation->set_custom_message( 'confirm_password', 'required', lang( 'admin_c_users_form_validation_confirm_password_required_error' ) );
			$this->form_validation->set_custom_message( 'confirm_password', 'matches', lang( 'admin_c_users_form_validation_confirm_password_matche_error' ) );
			
			// confirm password field
			// --------------------------
			
			unset( $field_rules );
			
			// Validation
			// -------------------------------------------------
			
			if( $this->input->post( 'submit_cancel', TRUE ) ) {
				
				redirect_last_url();
				
			}
			
			// se a validação dos campos for positiva
			else if ( $this->form_validation->run() AND ( $this->input->post( 'submit' ) OR $this->input->post( 'submit_apply' ) ) ) {
				
				$db_data = elements( array(
					
					'group_id',
					'params',
					
				), $this->input->post( NULL, TRUE ) );
				
				if ( $action == 'e' AND
					( ( $user_id != $this->users->user_data[ 'id' ] AND $this->users->check_privileges( 'users_management_can_change_id_others' ) )
					OR ( $user_id == $this->users->user_data[ 'id' ] AND $this->users->check_privileges( 'users_management_can_change_own_id' ) ) )
				) {
					
					$db_data[ 'id' ] = $this->input->post( 'id', TRUE );
					
				};
				
				if ( $action == 'a' OR
					( $action == 'e' AND
					( ( $user_id != $this->users->user_data[ 'id' ] AND $this->users->check_privileges( 'users_management_can_change_username_others' ) )
					OR ( $user_id == $this->users->user_data[ 'id' ] AND $this->users->check_privileges( 'users_management_can_change_own_username' ) ) )
					)
				) {
					
					$db_data[ 'username' ] = $this->input->post( 'username', TRUE );
					
				};
				
				if ( $action == 'a' OR
					( $action == 'e' AND
					( ( $user_id != $this->users->user_data[ 'id' ] AND $this->users->check_privileges( 'users_management_can_change_name_others' ) )
					OR ( $user_id == $this->users->user_data[ 'id' ] AND $this->users->check_privileges( 'users_management_can_change_own_name' ) ) )
					)
				) {
					
					$db_data[ 'name' ] = $this->input->post( 'name', TRUE );
					
				};
				
				if ( $action == 'a' OR
					( $action == 'e' AND
					( ( $user_id != $this->users->user_data[ 'email' ] AND $this->users->check_privileges( 'users_management_can_change_email_others' ) )
					OR ( $user_id == $this->users->user_data[ 'email' ] AND $this->users->check_privileges( 'users_management_can_change_own_email' ) ) )
					)
				) {
					
					$db_data[ 'email' ] = $this->input->post( 'email', TRUE );
					
				};
				
				if( $this->input->post( 'password', TRUE ) ) {
					
					$db_data[ 'password' ] = base64_encode( md5( $this->input->post( 'password' ) ) );
					
				}
				
				$db_data[ 'params' ] = json_encode( $db_data[ 'params' ] );
				
				if ( $action == 'a' ) {
					
					$return_id = $this->users->insert_user( $db_data );
					
					if ( $return_id ){
						
						msg( ( 'user_created' ), 'success' );
						
						if ( $this->input->post( 'submit_apply', TRUE ) ) {
							
							redirect( 'admin/' . $this->component_name . '/' . __FUNCTION__.'/a/e/uid/' . $this->users->encode_user_id( $return_id ) );
							
						}
						else {
							
							redirect_last_url();
							
						}
					}
					
				}
				else if ( $action == 'e' ) {
					
					if ( $this->users->update_user( $db_data, array( 'id' => $user_id ) ) ){
						
						if ( $user_id == $this->users->user_data[ 'id' ] ){
							
							$user_data = $this->users->get_user( array( 't1.id' => $user_id ) )->row_array();
							
							$user_data[ 'privileges' ] = get_params( $user_data[ 'privileges' ] );
							$user_data[ 'privileges' ] = array_flatten( $user_data[ 'privileges' ] );
							
							$user_data[ 'params' ] = get_params( $user_data[ 'params' ] );
							
							$this->users->set_user_preferences( $user_data[ 'params' ], FALSE );
							
						}
						
						msg( 'user_updated', 'success' );
						
						if ( $this->input->post( 'submit_apply', TRUE ) ) {
							
							redirect( 'admin' . $this->uri->ruri_string() );
							
						}
						else{
							redirect_last_url();
						}
					}
					
				}
				
			}
			// caso contrário se a validação dos campos for negativa e mensagens de erro conter strings
			else if ( ! $this->form_validation->run() AND validation_errors() != '' ) {
				
				$data['post'] = $this->input->post();
				
				if ( $action == 'e' ) {
					
					msg( ( 'update_user_fail' ), 'error' );
					
				}
				else {
					
					msg( ( 'create_user_fail' ), 'error' );
					
				}
				
				msg( validation_errors( '<div class="error">', '</div>' ), 'error' );
				
			}
			
			$this->_page(
				
				array(
					
					'component_view_folder' => $this->component_view_folder,
					'function' => __FUNCTION__,
					'action' => 'form',
					'layout' => 'default',
					'view' => 'form',
					'data' => $data,
					
				)
				
			);
			
		}
		
		/*
		 --------------------------------------------------------
		 Add / edit user
		 --------------------------------------------------------
		 ********************************************************
		*/
		
		/*
		 ********************************************************
		 --------------------------------------------------------
		 Remove user
		 --------------------------------------------------------
		*/
		
		else if ( $action == 'r' AND $user_id ){
			
			$user = $this->users->get_user( array( 't1.id' => $user_id ) )->row_array();
			
			if ( $user ){
				
				if ( $this->input->post( 'submit_cancel' ) ) {
					
					redirect_last_url();
					
				}
				else if ( $this->input->post( 'submit' ) ) {
					
					if ( $this->users->delete_user( array( 'id' => $user_id ) ) ) {
						
						msg( ( 'user_deleted' ), 'success' );
						redirect_last_url();
						
					}
					else {
						
						msg( $this->lang->line( 'user_deleted_fail' ), 'error' );
						redirect_last_url();
						
					}
					
				}
				else {
					
					$data = array(
						
						'component_name' => $this->component_name,
						'user'=>$user,
						
					);
					
					$this->_page(
						
						array(
							
							'component_view_folder' => $this->component_view_folder,
							'function' => __FUNCTION__,
							'action' => 'remove',
							'layout' => 'default',
							'view' => 'remove',
							'data' => $data,
							
						)
						
					);
					
				}
				
			}
		}
		
		/*
		 --------------------------------------------------------
		 Remove user
		 --------------------------------------------------------
		 ********************************************************
		*/
		
		else {
			
			show_404();
			
		}
		
	}
	
	/******************************************************************************/
	/******************************************************************************/
	/******************************** Users groups ********************************/
	
	public function users_groups_management($action = NULL, $id = NULL){
		
		$this->component_function = __FUNCTION__;
		$this->component_function_action = $action;
		
		// verifica se o usuário atual possui privilégios para gerenciar grupos de usuários
		if ( ! $this->users->check_privileges('users_management_users_groups_management') ){
			msg(('access_denied'),'title');
			msg(('access_denied_users_management_users_groups_management'),'error');
			redirect_last_url();
		};
		
		if ($action){
			
			$url = get_url('admin'.$this->uri->ruri_string());
			
			
			/**************************************************/
			/********************** fetch *********************/
			if ($action == 'users_groups_list'){
				
				if ($users_groups = $this->users->get_users_groups_tree(0,0,'list')){
					
					$data = array(
						'component_name' => $this->component_name,
						'users_groups' => $users_groups,
					);
					
					set_last_url($url);
					
					$this->_page(
						
						array(
							
							'component_view_folder' => $this->component_view_folder,
							'function' => __FUNCTION__,
							'action' => $action,
							'layout' => 'default',
							'view' => $action,
							'data' => $data,
							
						)
						
					);
					
				}
			}
			/********************** fetch *********************/
			/**************************************************/
			
			/**************************************************/
			/*********************** add **********************/
			else if ($action == 'add_users_group'){
				
				$data = array(
					'component_name' => $this->component_name,
					'users_groups' => $this->users->get_users_groups_tree(0,0,'list'),
				);
				
				/******************************/
				/******** Privilégios *********/
				
				// obtendo os valores atuais dos parâmetros
				$data['privileges_current_params_values'] = array();
				
				// obtendo as especificações dos parâmetros
				$data['privileges_params_spec'] = $this->users->get_users_groups_privileges();
				
				// cruzando os valores padrões das especificações com os atuais
				$data['privileges_final_params_values'] = array_merge_recursive_distinct( $data['privileges_params_spec']['params_spec_values'], $data['privileges_current_params_values'] );
				
				// definindo as regras de validação, o último argumento define o prefixo dos campos, que é o mesmo da coluna no DB
				set_params_validations( $data['privileges_params_spec']['params_spec'], 'privileges' );
				
				/******** Privilégios *********/
				/******************************/
				
				//validação dos campos
				$this->form_validation->set_rules('title',lang('title'),'trim|required');
				$this->form_validation->set_rules('alias',lang('alias'),'trim');
				$this->form_validation->set_rules('parent',lang('parent'),'trim|required|integer');
				
				if($this->input->post('submit_cancel')){
					redirect_last_url();
				}
				
				// se a validação dos campos for bem sucessida
				else if ($this->form_validation->run() AND ($this->input->post('submit') OR $this->input->post('submit_apply'))){
					$insert_data = elements(array(
						'title',
						'alias',
						'parent',
						'privileges',
					),$this->input->post());
					
					if ($insert_data['alias'] == ''){
						$insert_data['alias'] = url_title($insert_data['title'],'-',TRUE);
					}
					
					$insert_data['privileges'] = json_encode( $insert_data[ 'privileges' ] );
					
					$return_id=$this->users->insert_user_group($insert_data);
					if ($return_id){
						msg(('user_group_created'),'success');
						
						if ($this->input->post('submit_apply')){
							redirect('admin/'.$this->component_name . '/' . __FUNCTION__.'/edit_users_group/'.$return_id);
						}
						else{
							redirect_last_url();
						}
					}
					
				}
				// caso contrário se a validação dos campos for negativa e mensagens de erro conter strings
				else if (!$this->form_validation->run() AND validation_errors() != ''){
					
					$data['post'] = $this->input->post();
					
					msg(('create_user_group_fail'),'title');
					msg(validation_errors('<div class="error">', '</div>'),'error');
				}
				
				$this->_page(
					
					array(
						
						'component_view_folder' => $this->component_view_folder,
						'function' => __FUNCTION__,
						'action' => $action,
						'layout' => 'default',
						'view' => $action,
						'data' => $data,
						
					)
					
				);
				
			}
			/*********************** add **********************/
			/**************************************************/
			
			/**************************************************/
			/********************** edit **********************/
			else if ( $action == 'edit_users_group' AND $id AND ( $user_group = $this->users->get_user_group( array( 't1.id' => $id ), 'row' ) ) ){
				
				$data = array(
					'component_name' => $this->component_name,
					'users_groups' => $this->users->get_users_groups_as_list_childrens_hidden(0,$id),
					'user_group' => $user_group,
				);
				
				/******************************/
				/******** Privilégios *********/
				
				// obtendo os valores atuais dos parâmetros
				$data['privileges_current_params_values'] = get_params( $user_group->privileges );
				
				//-------------------
				// Adjusting params array values
				$new_values = array();
				
				foreach( $data[ 'privileges_current_params_values' ] as $k => $item ) {
					
					if ( is_array( $item ) ) {
						
						$new_values = _resolve_array_param_value( $k, $item );
						
						unset( $data[ 'privileges_current_params_values' ][ $k ] );
						
					}
					
				}
				$data[ 'privileges_current_params_values' ] = $data[ 'privileges_current_params_values' ] + $new_values;
				
				// obtendo as especificações dos parâmetros
				$data['privileges_params_spec'] = $this->users->get_users_groups_privileges();
				
				// cruzando os valores padrões das especificações com os atuais
				$data['privileges_final_params_values'] = array_merge_recursive_distinct( $data['privileges_params_spec']['params_spec_values'], $data['privileges_current_params_values'] );
				
				// definindo as regras de validação, o último argumento define o prefixo dos campos, que é o mesmo da coluna no DB
				set_params_validations( $data['privileges_params_spec']['params_spec'], 'privileges' );
				
				/******** Privilégios *********/
				/******************************/
				
				//validação dos campos
				$this->form_validation->set_rules('title',lang('title'),'trim|required');
				$this->form_validation->set_rules('alias',lang('alias'),'trim');
				$this->form_validation->set_rules('parent',lang('parent'),'trim|required|integer');
				
				if($this->input->post('submit_cancel')){
					redirect_last_url();
				}
				// se a validação dos campos for bem sucessida
				else if ($this->form_validation->run() AND ($this->input->post('submit') OR $this->input->post('submit_apply'))){
					$update_data = elements(array(
						'title',
						'alias',
						'parent',
						'privileges',
						'params',
					),$this->input->post());
					
					if ($update_data['alias'] == ''){
						$update_data['alias'] = url_title($update_data['title'],'-',TRUE);
					}
					
					if ( isset( $update_data[ 'privileges' ][ 'privileges' ] ) ){
						
						foreach( $update_data[ 'privileges' ][ 'privileges' ] as $key => $value ) {
							
							if ( ( ! is_array( $value ) ) AND $value !== 0 AND $value !== '0' AND trim( $value ) !== '' ) {
								
								$update_data[ 'privileges' ][ $value ] = $value;
								
							}
							
						}
						
						unset( $update_data[ 'privileges' ][ 'privileges' ] );
						
					}
					
					$update_data[ 'privileges' ] = json_encode( $this->input->post( 'privileges' ) );
					
					if ( $this->users->update_user_group( $update_data, array( 'id' => $this->input->post( 'user_group_id' ) ) ) ){
						
						msg( ( 'user_group_updated' ), 'success' );
						
						if ( $this->input->post( 'submit_apply' ) ){
							
							//redirect('admin'.$this->uri->ruri_string());
							
						}
						else{
							
							redirect_last_url();
							
						}
						
					}
					
				}
				// caso contrário se a validação dos campos falhar e se existir mensagens de erro
				else if (!$this->form_validation->run() AND validation_errors() != ''){
					
					$data['post'] = $this->input->post();
					
					msg(('update_user_group_fail'),'title');
					msg(validation_errors('<div class="error">', '</div>'),'error');
				}
				
				$this->_page(
					
					array(
						
						'component_view_folder' => $this->component_view_folder,
						'function' => __FUNCTION__,
						'action' => $action,
						'layout' => 'default',
						'view' => $action,
						'data' => $data,
						
					)
					
				);
				
			}
			/********************** edit **********************/
			/**************************************************/
			
			/**************************************************/
			/********************* remove *********************/
			else if ( $action == 'remove_users_group' AND $id AND ( $users_group = $this->users->get_user_group( array( 't1.id' => $id ), 'row' ) ) ){
				
				if($this->input->post('submit_cancel')){
					redirect_last_url();
				}
				else if ($this->input->post('users_group_id')>0 AND $this->input->post('submit')){
					if ($this->users->delete_users_group(array('id'=>$id))){
						msg(('users_group_deleted'),'success');
						redirect_last_url();
					}
					else{
						msg($this->lang->line('users_group_deleted_fail'),'error');
						redirect_last_url();
					}
				}
				else{
					$data=array(
						'component_name' => $this->component_name,
						'users_group'=>$users_group,
					);
					
					$this->_page(
						
						array(
							
							'component_view_folder' => $this->component_view_folder,
							'function' => __FUNCTION__,
							'action' => $action,
							'layout' => 'default',
							'view' => $action,
							'data' => $data,
							
						)
						
					);
					
				}
				
			}
			/********************** remove **********************/
			/**************************************************/
			
			else{
				//show_404();
			}
		}
		
	}
	
	/******************************** Users groups ********************************/
	/******************************************************************************/
	/******************************************************************************/
	
	
	public function component_config( $action = NULL, $layout = 'default'){
		
		$this->component_function = __FUNCTION__;
		$this->component_function_action = $action;
		
		if ( $action ){
			
			if ( $action == 'edit_config' AND ( $data[ 'component' ] = $this->mcm->current_component ) ){
				
				// -------------------------------------------------
				// Params
				
				// obtendo os valores atuais dos parâmetros
				$data[ 'current_params_values' ] = get_params( $data['component'][ 'params' ] );
				
				//-------------------
				// Adjusting params array values
				$new_values = array();
				foreach( $data[ 'current_params_values' ] as $k => $item ) {
					
					if ( is_array( $item ) ) {
						
						$new_values = _resolve_array_param_value( $k, $item );
						
						unset( $data[ 'current_params_values' ][ $k ] );
						
					}
					
					$data[ 'current_params_values' ] = $data[ 'current_params_values' ] + $new_values;
					
				}
				
				// obtendo as especificações dos parâmetros
				$data[ 'params_spec' ] = $this->users->get_global_config_params( $data['component'][ 'params' ] );
				
				// cruzando os valores padrões das especificações com os do DB
				$data[ 'final_params_values' ] = array_merge( $data[ 'params_spec' ][ 'params_spec_values' ], $data[ 'current_params_values' ] );
				
				// definindo as regras de validação
				set_params_validations( $data[ 'params_spec' ][ 'params_spec' ] );
				
				// Params
				// -------------------------------------------------
				
				if ( $this->input->post( 'submit_cancel' ) ) {
					
					redirect_last_url();
					
				}
				// se a validação dos campos for positiva
				else if ( $this->form_validation->run() AND ( $this->input->post( 'submit', TRUE ) OR $this->input->post( 'submit_apply', TRUE ) ) ){
					
					$update_data = elements(array(
						
						'params',
						
					), $this->input->post( NULL, TRUE ) ) ;
					
					$update_data[ 'params' ] = json_encode( $update_data[ 'params' ] );
					
					if ( $this->main_model->update_component( $update_data, array( 'id' => $data[ 'component' ][ 'id' ] ) ) ){
						
						msg( ( 'component_preferences_updated' ), 'success' );
						
						if ( $this->input->post( 'submit_apply' ) ){
							
							//redirect('admin/'.$this->component_name . '/' . __FUNCTION__ . '/' . $action);
							
						}
						else {
							
							redirect_last_url();
							
						}
					}

				}
				// caso contrário se a validação dos campos for negativa e mensagens de erro conter strings
				else if (!$this->form_validation->run() AND validation_errors() != ''){

					$data['post'] = $this->input->post();

					msg(('update_component_preferences_fail'),'title');
					msg(validation_errors('<div class="error">', '</div>'),'error');
				}

				$this->_page(

					array(

						'component_view_folder' => $this->component_view_folder,
						'function' => __FUNCTION__,
						'action' => $action,
						'layout' => 'default',
						'view' => $action,
						'data' => $data,

					)

				);

			}
			else{
				redirect('admin/'.$this->component_name.'/articles_management/articles_list/1');
			}
		}
		
	}
	
}
