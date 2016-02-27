<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'controllers/admin/main.php');

class Users extends Main {
	
	public function __construct(){
		
		parent::__construct();
		
		set_current_component();
		
	}
	
	public function index(){
		$this->users_management('users_list');
	}
	
	public function users_management($action = NULL, $id = NULL){
		
		$this->component_function = __FUNCTION__;
		$this->component_function_action = $action;
		
		if ($action){
			
			$url = get_url('admin'.$this->uri->ruri_string());
			
			// verifica se o usuário atual possui privilégios para gerenciar usuários, porém pode editar seu próprio usuário
			if ( $action != 'edit_user' AND ! $this->users->check_privileges('users_management_users_management') AND ! $id ){
				msg(('access_denied'),'title');
				msg(('access_denied_users_management_users_management'),'error');
				redirect('admin');
			};
			
			if ($action == 'users_list'){
				
				$users = $this->users->get_users_checking_privileges();
				if ($users){
					
					$data = array(
						'component_name' => $this->component_name,
						'users' => $users->result(),
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
			else if (($action == 'fetch_publish' OR $action == 'fetch_unpublish') AND $id){
					
				$update_data = array(
					'status' => $action == 'fetch_publish'?'2':'1',
				);
				
				$update_data['params'] = '';
				
				if ($this->articles_model->update_article($update_data, array('id' => $id))){
					msg(('article_'.($action == 'fetch_publish'?'published':'unpublished')),'success');
					redirect($url);
				}
			}
			else if ($action == 'add_user'){
				
				// verifica se o usuário atual possui privilégios para adicionar novos usuários
				if ( ! $this->users->check_privileges('users_management_can_add_user') ){
					msg(('access_denied'),'title');
					msg(('access_denied_users_management_can_add_user'),'error');
					redirect_last_url();
				};
				
				$data = array(
					'component_name' => $this->component_name,
					'users_groups' => $this->users->get_accessible_users_groups($this->users->user_data['id']),
					//'categories' => $this->articles_model->get_categories_tree(0,0,'list'),
				);
				
				/******************************/
				/********* Parâmetros *********/
				
				// obtendo as especificações dos parâmetros
				$data['params_spec'] = $this->users->get_user_params();
				
				// cruzando os valores padrões das especificações com os atuais
				$data['final_params_values'] = $data['params_spec']['params_spec_values'];
				
				// definindo as regras de validação
				set_params_validations( $data['params_spec']['params_spec'] );
				
				/********* Parâmetros *********/
				/******************************/
				
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
				
				unset( $_name_rules );
				unset( $_confirm_password_rules );
				
				// Validation
				// -------------------------------------------------
				
				if($this->input->post('submit_cancel')){
					redirect_last_url();
				}
				// se a validação dos campos for positiva
				else if ($this->form_validation->run() AND ($this->input->post('submit') OR $this->input->post('submit_apply'))){
					$insert_data = elements(array(
						'username',
						'name',
						'email',
						'group_id',
						'params',
					),$this->input->post());
					
					$insert_data['password'] = base64_encode(md5($this->input->post('password')));
					
					$insert_data['params'] = json_encode( $insert_data['params'] );
					
					$return_id=$this->users->insert_user($insert_data);
					if ($return_id){
						msg(('user_created'),'success');
						if ($this->input->post('submit_apply')){
							redirect('admin/'.$this->component_name . '/' . __FUNCTION__.'/edit_user/'.base64_encode(base64_encode(base64_encode(base64_encode($return_id)))));
						}
						else{
							redirect_last_url();
						}
					}
					
				}
				// caso contrário se a validação dos campos for negativa e mensagens de erro conter strings
				else if (!$this->form_validation->run() AND validation_errors() != ''){
					
					$data['post'] = $this->input->post();
					
					msg(('create_user_fail'),'title');
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
			else if ($action == 'edit_user' AND $id AND ($user = $this->users->get_user(array('t1.id' => base64_decode(base64_decode(base64_decode(base64_decode($id))))))->row())){
				
				$id = base64_decode(base64_decode(base64_decode(base64_decode($id))));
				
				// verifica se o usuário atual possui privilégios para gerenciar usuários
				if ( ! $this->users->check_privileges('users_management_users_management') AND $id != $this->users->user_data['id'] ){
					msg(('access_denied'),'title');
					msg(('access_denied_users_management_users_management'),'error');
					redirect('admin');
				};
				
				// verifica se o usuário atual possui privilégios para ver informações dos usuários, exceto o seu próprio
				if ( ! $this->users->check_privileges('users_management_view_personal_info') AND $id != $this->users->user_data['id'] ){
					msg(('access_denied'),'title');
					msg(('access_denied_users_management_view_personal_info'),'error');
					redirect_last_url();
				};
				
				// checa as permissões
				if ( $this->input->post() AND ! $this->input->post('submit_cancel') ){
					
					if ( $id != $this->users->user_data['id'] AND ! $this->users->check_privileges('users_management_can_edit_only_your_own_user') ){
						
						$is_on_same_or_below_group_level = $this->users->check_if_user_is_on_same_and_low_group_level( $id );
						$is_on_same_group_level = $this->users->check_if_user_is_on_same_group_level($id);
						$is_on_same_group_or_below = $this->users->check_if_user_is_on_same_group_and_below($id);
						$is_on_same_group = $this->users->check_if_user_is_on_same_group($id);
						$is_on_below_groups = $this->users->check_if_user_is_on_below_groups($id);
						
						$can_edit_all_users = $this->users->check_privileges( 'can_edit_all_users' );
						$can_edit_only_same_and_low_group_level = $this->users->check_privileges('users_management_can_edit_only_same_and_low_group_level');
						$can_edit_only_same_group_level = $this->users->check_privileges('users_management_can_edit_only_same_group_level');
						$can_edit_only_same_group_and_below = $this->users->check_privileges('users_management_can_edit_only_same_group_and_below');
						$can_edit_only_same_group = $this->users->check_privileges('users_management_can_edit_only_same_group');
						$can_edit_only_below_groups = $this->users->check_privileges('users_management_can_edit_only_low_groups');
							
						if ( $can_edit_only_same_and_low_group_level AND ! ( $is_on_same_or_below_group_level ) ){
							
							msg(('access_denied'),'title');
							msg(('access_denied_users_management_can_edit_only_same_and_low_group_level'),'error');
							
							redirect_last_url();
							
						}
						else if ( $can_edit_only_same_group_level AND ! ( $is_on_same_group_level ) ){
							
							msg(('access_denied'),'title');
							msg(('access_denied_users_management_edit_same_group_level'),'error');
							
							redirect_last_url();
							
						}
						else if ( $can_edit_only_same_group_and_below AND ! ( $is_on_same_group_or_below ) ){
							
							msg(('access_denied'),'title');
							msg(('access_denied_users_management_edit_same_group_and_below'),'error');
							
							redirect_last_url();
							
						}
						else if ( $can_edit_only_same_group AND ! ( $is_on_same_group ) ){
							
							msg(('access_denied'),'title');
							msg(('access_denied_users_management_edit_same_group'),'error');
							
							redirect_last_url();
							
						}
						else if ( $can_edit_only_below_groups AND ! ( $is_on_below_groups )){
							
							msg(('access_denied'),'title');
							msg(('access_denied_users_management_edit_below_groups'),'error');
							
							redirect_last_url();
							
						}
						
					}
					else if ( $id != $this->users->user_data['id'] AND $this->users->check_privileges('users_management_can_edit_only_your_own_user')){
						
						msg(('access_denied'),'title');
						msg(('access_denied_users_management_edit_only_your_own_user'),'error');
						
							redirect_last_url();
						
					};
				}
				
				$data = array(
					'component_name' => $this->component_name,
					//'categories' => $this->articles_model->get_categories_tree(0,0,'list'),
					'user' => $user,
					'users_groups' => $this->users->get_accessible_users_groups($id),
				);
				
				/******************************/
				/********* Parâmetros *********/
				
				// obtendo os valores atuais dos parâmetros
				$data['current_params_values'] = get_params( $user->params );
				
				// obtendo as especificações dos parâmetros
				$data['params_spec'] = $this->users->get_user_params();
				
				// cruzando os valores padrões das especificações com os atuais
				$data['final_params_values'] = array_merge_recursive_distinct( $data['params_spec']['params_spec_values'], $data['current_params_values'] );
				
				// definindo as regras de validação
				set_params_validations( $data['params_spec']['params_spec'] );
				
				/********* Parâmetros *********/
				/******************************/
				
				//validação dos campos
				$this->form_validation->set_rules('name',lang('name'),'trim|required');
				$this->form_validation->set_rules('group_id',lang('user_group'),'trim|required|integer');
				if($this->input->post('password') OR $this->input->post('confirm_password')){
					$this->form_validation->set_rules('password',lang('password'),'trim|required|min_length[6]|max_length[24]');
					$this->form_validation->set_rules('confirm_password',lang('confirm_password'),'trim|required|min_length[6]|max_length[24]|matches[password]');
				}
				
				if($this->input->post('submit_cancel')){
					redirect_last_url();
				}
				// se a validação dos campos for positiva
				else if ($this->form_validation->run() AND ($this->input->post('submit') OR $this->input->post('submit_apply'))){
					
					$update_data = elements(array(
						'name',
						'group_id',
						'params',
					),$this->input->post());
					
					if($this->input->post('password')){
						$update_data['password'] = base64_encode(md5($this->input->post('password')));
					}
					
					$update_data['params'] = json_encode( $update_data['params'] );
					
					if ($this->users->update_user($update_data, array('id' => $id))){
						
						if ($id == $this->users->user_data['id']){
							
							$user_data = $this->users->get_user(array('t1.id' => $id))->row_array();
							
							$user_data['privileges'] = get_params( $user_data['privileges'] );
							$user_data['privileges'] = array_flatten( $user_data['privileges'] );
							
							$user_data['params'] = get_params( $user_data['params'] );
							
							$this->users->set_user_preferences( $user_data['params'], FALSE );
							
						}
						
						msg( 'user_updated', 'success' );
						
						if ($this->input->post('submit_apply')){
							redirect('admin/'.$this->component_name . '/' . __FUNCTION__.'/edit_user/'.$this->input->post('user_id'));
						}
						else{
							redirect_last_url();
						}
					}
					
				}
				// caso contrário se a validação dos campos for negativa e mensagens de erro conter strings
				else if (!$this->form_validation->run() AND validation_errors() != ''){
					
					$data['post'] = $this->input->post();
					
					msg(('update_user_fail'),'title');
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
			else if ($action == 'remove_user' AND $id){
				
				$id = base64_decode(base64_decode(base64_decode(base64_decode($id))));
				
				$user = $this->users->get_user(array('t1.id'=>$id))->row();
				
				if ($user){
					
					if($this->input->post('submit_cancel')){
						redirect_last_url();
					}
					else if ($this->input->post('submit')){
						if ($this->users->delete_user(array('id'=>$id))){
							msg(('user_deleted'),'success');
							redirect_last_url();
						}
						else{
							msg($this->lang->line('user_deleted_fail'),'error');
							redirect_last_url();
						}
					}
					else{
						$data=array(
							'component_name' => $this->component_name,
							'user'=>$user,
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
			}
			else{
				show_404();
			}
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
