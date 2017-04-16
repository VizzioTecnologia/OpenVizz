<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

require( APPPATH.'controllers/admin/main.php' );

class Modules extends Main {
	
	public function __construct(){
		
		parent::__construct();
		
		$this->load->model( 
			
			array( 
				
				'admin/modules_model',
				'common/modules_common_model',
				
			 )
			
		 );
		
		set_current_component();
		
		// verifica se o usuário atual possui privilégios para gerenciar menus
		if ( ! $this->users->check_privileges( 'modules_management_modules_management' ) ){
			msg( ( 'access_denied' ),'title' );
			msg( ( 'access_denied_modules_management_modules_management' ),'error' );
			redirect_last_url();
		};
		
	}
	
	public function index(){
		
		$this->mm();
		
	}
	
	/*
	 **************************************************************************************************
	 **************************************************************************************************
	 --------------------------------------------------------------------------------------------------
	 Modules management
	 --------------------------------------------------------------------------------------------------
	 */
	
	public function mm(){
		
		// -------------------------------------------------
		// Parsing vars ------------------------------------
		
		$f_params = $this->uri->ruri_to_assoc();
		
		$action =								isset( $f_params[ 'a' ] ) ? $f_params[ 'a' ] : 'ml'; // action
		$sub_action =							isset( $f_params[ 'sa' ] ) ? $f_params[ 'sa' ] : NULL; // sub action
		$module_id =							isset( $f_params[ 'mid' ] ) ? $f_params[ 'mid' ] : NULL; // module id
		$type =									isset( $f_params[ 't' ] ) ? $f_params[ 't' ] : NULL; // module type
		$cp =									isset( $f_params[ 'cp' ] ) ? $f_params[ 'cp' ] : NULL; // current page
		$ipp =									isset( $f_params[ 'ipp' ] ) ? $f_params[ 'ipp' ] : NULL; // items per page
		$ob =									isset( $f_params[ 'ob' ] ) ? $f_params[ 'ob' ] : NULL; // order by
		
		// Parsing vars ------------------------------------
		// -------------------------------------------------
		
		// atualizando informações do componente atual
		$this->component_function = __FUNCTION__;
		$this->component_function_action = $action;
		
		$base_link_prefix = 'admin/' . $this->component_name . '/' . __FUNCTION__ . '/';
		
		$base_link_array = array( 
			
		 );
		
		$add_link_array = $base_link_array + array( 
			
			'a' => 'am',
			'sa' => 'select_module_type',
			
		 );
		
		$modules_list_link_array = $base_link_array + array( 
			
			'a' => 'ml',
			
		 );
		
		$data[ 'add_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $add_link_array );
		$data[ 'modules_list_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $modules_list_link_array );
		
		$url = get_url( 'admin' . $this->uri->ruri_string() );
		
		/*
		 ********************************************************
		 --------------------------------------------------------
		 Modules list
		 --------------------------------------------------------
		 */
		
		if ( $action == 'ml' ){
			
			$this->load->helper( array( 'pagination' ) );
			
			/*
			 ********************************************************
			 --------------------------------------------------------
			 Ordenção por colunas
			 --------------------------------------------------------
			 */
			
			if ( ! ( ( $order_by_direction = $this->users->get_user_preference( 'modules_list_order_by_direction' ) ) != FALSE ) ){
				
				$order_by_direction = 'ASC';
				
			}
			
			$comp_ob = '';
			
			if ( ( $order_by = $this->users->get_user_preference( 'modules_list_order_by' ) ) != FALSE ){
				
				$data[ 'order_by' ] = $order_by;
				
				// order by complement
				$comp_ob = '';
				
				switch ( $order_by ) {
					
					case 'id':
						
						$order_by = 't1.id';
						break;
						
					case 'title':
						
						$order_by = 't1.title';
						break;
						
					case 'type':
					
						$order_by = 't1.type';
						$comp_ob = ', t1.ordering ASC, t1.title ASC';
						break;
						
					case 'mi_cond':
					
						$order_by = 't1.mi_cond';
						break;
						
					case 'position':
					
						$order_by = 't1.position';
						$comp_ob = ', t1.ordering ASC, t1.title ASC';
						break;
						
					case 'ordering':
					
						$order_by = 't1.ordering';
						$comp_ob = ', t1.position ASC, t1.title ASC';
						break;
						
					case 'status':
					
						$order_by = 't1.status';
						$comp_ob = ', t1.position ASC, t1.ordering ASC, t1.title ASC';
						break;
						
				}
				
			}
			else{
				
				$order_by = 't1.title';
				$data[ 'order_by' ] = 'title';
				
			}
			
			$data[ 'order_by_direction' ] = $order_by_direction;
			
			$order_by = $order_by . ' ' . $order_by_direction . $comp_ob;
			
			/*
			 --------------------------------------------------------
			 Ordenção por colunas
			 --------------------------------------------------------
			 ********************************************************
			 */
			
			/*
			 ********************************************************
			 --------------------------------------------------------
			 Paginação
			 --------------------------------------------------------
			 */
			
			if ( $cp < 1 OR ! gettype( $cp ) == 'int' ) $cp = 1;
			if ( $ipp < 1 OR ! gettype( $ipp ) == 'int' ) $ipp = $this->mcm->filtered_system_params[ 'admin_items_per_page' ];
			$offset = ( $cp - 1 ) * $ipp;
			
			$data[ 'pagination' ] = get_pagination( 
			
				$data[ 'modules_list_link' ] . '/cp/%p%/ipp/%ipp%',
				$cp,
				$ipp,
				$this->modules_model->mng_get_modules( 
					
					array( 
						
						'return_type' => 'count_all_results',
						
					 )
					
				 )
				
			 );
			
			/*
			 --------------------------------------------------------
			 Paginação
			 --------------------------------------------------------
			 ********************************************************
			 */
			
			
			
			
			
			
			
			
			$gm_params = array( 
				
				'order_by' => $order_by,
				'limit' => $ipp,
				'offset' => $offset,
				
			 );
			
			$modules = $this->modules_model->mng_get_modules( $gm_params )->result_array();
			
			foreach ( $modules as $key => &$module ) {
				
				$base_link_array = array( 
					
				 );
				
				$edit_link_array = $base_link_array + array( 
					
					'a' => 'em',
					't' => $module[ 'type' ],
					'mid' => $module[ 'id' ],
					
				 );
				
				$remove_link_array = $base_link_array + array( 
					
					'a' => 'rm',
					'mid' => $module[ 'id' ],
					
				 );
				
				$change_order_link_array = $base_link_array + array( 
					
					'a' => 'co',
					
				 );
				
				$up_order_link_array = $base_link_array + array( 
					
					'a' => 'uo',
					
				 );
				
				$down_order_link_array = $base_link_array + array( 
					
					'a' => 'do',
					
				 );
				
				$change_status_publish_link_array = $base_link_array + array( 
					
					'a' => 'csp',
					'mid' => $module[ 'id' ],
					
				 );
				
				$change_status_unpublish_link_array = $base_link_array + array( 
					
					'a' => 'csu',
					'mid' => $module[ 'id' ],
					
				 );
				
				$module[ 'edit_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $edit_link_array );
				$module[ 'remove_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $remove_link_array );
				$module[ 'change_order_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $change_order_link_array );
				$module[ 'up_order_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $up_order_link_array );
				$module[ 'down_order_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $down_order_link_array );
				$module[ 'change_status_publish_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $change_status_publish_link_array );
				$module[ 'change_status_unpublish_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $change_status_unpublish_link_array );
				
			}
			
			$data[ 'modules' ] = $modules;
			
			set_last_url( $url );
			
			$this->_page( 
				
				array( 
					
					'component_view_folder' => $this->component_view_folder,
					'function' => 'modules_management',
					'action' => 'modules_list',
					'layout' => 'default',
					'view' => 'modules_list',
					'data' => $data,
					
				 )
				
			 );
			
		}
		
		/*
		 --------------------------------------------------------
		 Modules list
		 --------------------------------------------------------
		 ********************************************************
		 */
		
		/*
		 ********************************************************
		 --------------------------------------------------------
		 Change order by
		 --------------------------------------------------------
		 */
		
		else if ( ( $action == 'cob' ) AND $ob ){
			
			$this->users->set_user_preferences( array( 'modules_list_order_by' => $ob ) );
			
			if ( ( $order_by_direction = $this->users->get_user_preference( 'modules_list_order_by_direction' ) ) != FALSE ){
				
				switch ( $order_by_direction ) {
					
					case 'ASC':
						
						$order_by_direction = 'DESC';
						break;
						
					case 'DESC':
					
						$order_by_direction = 'ASC';
						break;
						
				}
				
				$this->users->set_user_preferences( array( 'modules_list_order_by_direction' => $order_by_direction ) );
				
			}
			else {
				
				$this->users->set_user_preferences( array( 'modules_list_order_by_direction' => 'ASC' ) );
				
			}
			
			redirect( get_last_url() );
			
		}
		
		/*
		 --------------------------------------------------------
		 Change order by
		 --------------------------------------------------------
		 ********************************************************
		 */
		
		/*
		 ********************************************************
		 --------------------------------------------------------
		 Change status
		 --------------------------------------------------------
		 */
		
		else if ( ( $action == 'csp' OR $action == 'csu' ) AND $module_id ){
				
			$update_data = array( 
				
				'status' => $action == 'csp' ? '1' : '0',
				
			 );
			
			if ( $this->modules_common_model->update( $update_data, array( 'id' => $module_id ) ) ){
				
				msg( ( 'module_' . ( $action == 'csp' ? 'published' : 'unpublished' ) ), 'success' );
				redirect_last_url();
				
			}
		}
		
		/*
		 --------------------------------------------------------
		 Change status
		 --------------------------------------------------------
		 ********************************************************
		 */
		
		/*
		 ********************************************************
		 --------------------------------------------------------
		 Change ordering
		 --------------------------------------------------------
		 */
		
		else if ( ( $action == 'co' ) AND $this->input->post( 'module_id' ) ){
			
			$update_data = array( 
				
				'ordering' => $this->input->post( 'ordering' ) > 0?$this->input->post( 'ordering' ):1,
				
			 );
			
			if ( $this->modules_common_model->update( $update_data, array( 'id' => $this->input->post( 'module_id' ) ) ) ){
				
				msg( ( 'module_ordering_changed' ), 'success' );
				redirect_last_url();
				
			}
			
		}
		
		/*
		 --------------------------------------------------------
		 Change ordering
		 --------------------------------------------------------
		 ********************************************************
		 */
		
		/*
		 ********************************************************
		 --------------------------------------------------------
		 Change ordering, up or down
		 --------------------------------------------------------
		 */
		
		else if ( ( $action == 'uo' OR $action == 'do' ) AND $this->input->post( 'module_id' ) ){
			
			$current_order = $this->input->post( 'ordering' );
			
			if ( $this->input->post( 'submit_up_order' ) ){
				
				$current_order += 1;
				
			}
			else if ( $this->input->post( 'submit_down_order' ) AND $current_order > 1 ){
				
				$current_order -= 1;
				
			}
			
			$new_order = $current_order > 0 ? $current_order : 1;
			
			$update_data = array( 
			
				'ordering' => $new_order,
				
			 );
			
			if ( $this->modules_common_model->update( $update_data, array( 'id' => $this->input->post( 'module_id' ) ) ) ){
				msg( ( 'module_ordering_changed' ),'success' );
				redirect_last_url();
			}
		}
		
		/*
		 --------------------------------------------------------
		 Change ordering, up or down
		 --------------------------------------------------------
		 ********************************************************
		 */
		
		/*
		 ********************************************************
		 --------------------------------------------------------
		 Add / edit module
		 --------------------------------------------------------
		 */
		
		else if ( $action == 'am' OR $action == 'em' ){
			
			if ( $this->input->post( 'params' ) ) {
				
				//print_r( $_POST ); exit;
				
			}
			
			$module = array();
			
			// verificamos se a ação é de edição ou adição
			// se for adição, desatribuímos a variável $module_id ( se esta estiver definida ), pois esta não é necessária nesta ação
			if ( $module_id ){
				
				if ( ! ( $module = $this->modules_model->mng_get_modules( array( 'where_condition' => 't1.id = ' . $module_id ) )->row_array() ) ){
					
					show_404();
					
				}
				
			}
			
			$data[ 'module' ] = &$module;
			
			if ( $sub_action AND $sub_action == 'select_module_type' ){
				
				$modules_types = &$this->mcm->modules_types;
				
				foreach ( $modules_types as $key => &$module_type ) {
					
					$assoc_to_uri_array = array( 
						
						'a' => $action,
						't' => $module_type[ 'alias' ],
						
					 );
					
					if ( $action == 'em' ){
						
						$assoc_to_uri_array[ 'mid' ] = $module_id;
						
					}
					
					$module_type[ 'link' ] = 'admin/' . $this->component_name . '/' . __FUNCTION__ . '/' . $this->uri->assoc_to_uri( $assoc_to_uri_array );
					
				}
				
				$data[ 'menu_items_types' ] = ul_menu( $modules_types );
				
				$this->_page( 
					
					array( 
						
						'component_view_folder' => $this->component_view_folder,
						'function' => 'modules_management',
						'action' => 'module_form',
						'layout' => 'default',
						'view' => 'select_module_type',
						'data' => $data,
						
					 )
					
				 );
				
				//echo ul_menu( $menu_items );
				
			}
			
			// se o tipo do módulo estiver definido, quer dizer que o tipo de módulo foi escolhido, logo podemos prosseguir para o formulário
			else if ( $type ){
				
				$module[ 'type' ] = $type;
				
				$this->load->model( 'common/menus_common_model' );
				$this->load->model( 'admin/menus_model' );
				
				
				
				/*
				 ********************************************************
				 --------------------------------------------------------
				 Menu items options
				 --------------------------------------------------------
				 */
				
				$data[ 'menus_items_options' ] = array();
				
				$menus_types = $this->menus_common_model->get_menus_types()->result_array();
				
				$menu_items_count = 0;
				
				foreach ( $menus_types as $key => $menu_type ) {
					
					if ( check_var( $menu_items[ $menu_type[ 'title' ] ] = $this->menus_model->get_menu_tree( 0, 0, 'list', $menu_type[ 'id' ] ) ) ) {
						
						foreach ( $menu_items[ $menu_type[ 'title' ] ] as $key => $menu_item ) {
							
							$data[ 'menus_items_options' ][ $menu_type[ 'title' ] ][ $menu_item[ 'id' ] ] = $menu_item[ 'indented_title' ];
							
							$menu_items_count++;
							
						}
						
						$menu_items_count++;
						
					}
					
				}
				
// 			echo '<h3>$menu_items:</h3><pre>' . print_r( $menus_types, TRUE ) . '</pre>'; exit;
			
				$data[ 'menu_items_count' ] = $menu_items_count;
				
				/*
				 --------------------------------------------------------
				 Menu items options
				 --------------------------------------------------------
				 ********************************************************
				 */
				
				if ( $this->input->post( 'mi_cond' ) ){
					
					$module[ 'title' ] =										$this->input->post( 'title' );
					$module[ 'alias' ] =										$this->input->post( 'alias' );
					$module[ 'status' ] =										$this->input->post( 'status' );
					$module[ 'position' ] =										$this->input->post( 'position' );
					$module[ 'mi_cond' ] =										$this->input->post( 'mi_cond' );
					$module[ 'menus_items' ] =									$this->input->post( 'menus_items' );
					
				}
				else if ( $action == 'em' ){
					
					$module[ 'mi_cond' ] =										json_decode( $module[ 'mi_cond' ], TRUE );
					$module[ 'menus_items' ] =									$module[ 'mi_cond' ][ key( $module[ 'mi_cond' ] ) ];
					$module[ 'mi_cond' ] =										key( $module[ 'mi_cond' ] );
					
				}
				else{
					
					$module[ 'mi_cond' ] =										NULL;
					$module[ 'menus_items' ] =									NULL;
					
				}
				
				$data[ 'users' ] = $this->users->get_users_checking_privileges()->result_array();
				//$data[ 'users_groups' ] = $this->users->get_accessible_users_groups( $this->users->user_data[ 'id' ] );
				$data[ 'users_groups' ] = $this->users->get_users_groups_tree( 0, 0, 'list' );
				
				/******************************/
				/********* Parâmetros *********/
				
				/*********** Módulo ***********/
				
				// obtendo as especificações dos parâmetros
				$data[ 'module_params_spec' ] = $this->modules_model->get_module_params();
				
				if ( $action == 'em' ){
					
					// obtendo os valores atuais dos parâmetros
					$data[ 'current_params_values' ] = get_params( $module[ 'params' ] );
					
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
					
					//-------------------
					
					// cruzando os valores padrões das especificações com os atuais
					//$data[ 'module_final_params_values' ] = array_merge( $data[ 'module_params_spec' ][ 'params_spec_values' ], $data[ 'current_params_values' ] );
					$data[ 'module_final_params_values' ] = $data[ 'current_params_values' ];
					
				}
				else {
					
					$data[ 'module_final_params_values' ] = $data[ 'module_params_spec' ][ 'params_spec_values' ];
					
				}
				
				// definindo as regras de validação
				set_params_validations( $data[ 'module_params_spec' ][ 'params_spec' ] );
				
				/*********** Módulo ***********/
				
				/********* Parâmetros *********/
				/******************************/
				
				// carregando o model do tipo de módulo
				$this->load->model( MODULES_DIR_NAME . DS . $type . '_module' );
				
				/******************************/
				/********* Parâmetros *********/
				
				/******** Module type *********/
					
				if ( $action == 'em' ){
					
					$module_params_values = array();
					
					if ( $this->input->post() ) {
						
						$module_params_values = array_merge_recursive_distinct( $data[ 'current_params_values' ], $this->input->post( 'params' ) );
						
					}
					else {
						
						$module_params_values = $data[ 'current_params_values' ];
						
					}
					
					// obtendo as especificações dos parâmetros
					$data[ 'module_type_params_spec' ] = $this->{ $type . '_module' }->get_module_params( $module_params_values );
					
					// cruzando os valores padrões das especificações com os atuais
					$data[ 'module_type_final_params_values' ] = array_merge_recursive_distinct( $data[ 'module_type_params_spec' ][ 'params_spec_values' ], $data[ 'current_params_values' ] );
					
					//print_r( $data[ 'module_type_params_spec' ] );
					
				}
				else {
					
					// obtendo as especificações dos parâmetros
					$data[ 'module_type_params_spec' ] = $this->{ $type . '_module' }->get_module_params();
					
					// cruzando os valores padrões das especificações com os atuais
					$data[ 'module_type_final_params_values' ] = $data[ 'module_type_params_spec' ][ 'params_spec_values' ];
					
				}
				
				// definindo as regras de validação
				
				if ( check_var( $data[ 'module_type_params_spec' ][ 'params_spec' ] ) ) {
					
					set_params_validations( $data[ 'module_type_params_spec' ][ 'params_spec' ] );
					
				}
				
				/******** Module type *********/
				
				/********* Parâmetros *********/
				/******************************/
				
				// ajustando as permissões
				if ( $action == 'em' ){
					
					if ( $module[ 'access_type' ] === 'users' ){
						
						$module[ 'access_user_id' ] = explode( '|', $module[ 'access_ids' ] );
						
					}
					else if ( $module[ 'access_type' ] === 'users_groups' ){
						
						$module[ 'access_user_group_id' ] = explode( '|', $module[ 'access_ids' ] );
						
					}
					else{
						
						$module[ 'access_type' ] = 'public';
						
					}
					
				}
				
				//validação dos campos
				$this->form_validation->set_rules( 'title', lang( 'title' ), 'trim|required' );
				$this->form_validation->set_rules( 'alias', lang( 'alias' ), 'trim' );
				$this->form_validation->set_rules( 'status', lang( 'status' ), 'trim|required|integer' );
				$this->form_validation->set_rules( 'position', lang( 'position' ), 'trim|required' );
				$this->form_validation->set_rules( 'mi_cond', lang( 'mi_cond' ), 'trim' );
				
				// se o tipo de condição de item de menu for "Específicos" ou "Todos, exceto" ou "Nenhum, exceto", aplicamos a validação pertinente
				if ( $this->input->post( 'mi_cond' ) == 'specific' OR $this->input->post( 'mi_cond' ) == 'all_except' OR $this->input->post( 'mi_cond' ) == 'none_except' ){
					
					$this->form_validation->set_rules( 'menus_items[]',lang( 'menus_items' ),'trim|required' );
					
				}
				
				// se o tipo de nível de acesso for para usuários específicos, aplicamos a validação pertinente
				if ( $this->input->post( 'access_type' ) == 'users' ){
					
					$this->form_validation->set_rules( 'access_user_id[]',lang( 'access_user' ),'trim|required' );
					
				}
				// caso contrário, se o tipo de nível de acesso for para grupos de usuários específicos, aplicamos a validação pertinente
				else if ( $this->input->post( 'access_type' ) == 'users_groups' ){
					
					$this->form_validation->set_rules( 'access_user_group_id[]', lang( 'access_user_group' ), 'trim|required' );
					
				}
				
				if( $this->input->post( 'submit_cancel' ) ){
					redirect_last_url();
				}
				// se a validação dos campos for positiva
				else if ( $this->form_validation->run() AND ( $this->input->post( 'submit' ) OR $this->input->post( 'submit_apply' ) ) ){
					
					$db_data = elements( array( 
						
						'type',
						'status',
						'position',
						'title',
						'alias',
						'ordering',
						'access_type',
						'access_ids',
						'params',
						'environment',
						
					 ), $this->input->post() );
					
					$db_data[ 'mi_cond' ][ $this->input->post( 'mi_cond' ) ] = array();
					if ( $this->input->post( 'mi_cond' ) == 'specific' OR $this->input->post( 'mi_cond' ) == 'all_except' OR $this->input->post( 'mi_cond' ) == 'none_except' ){
						
						$db_data[ 'mi_cond' ][ $this->input->post( 'mi_cond' ) ] = $this->input->post( 'menus_items' );
						
					}
					
					$db_data[ 'mi_cond' ] = json_encode( $db_data[ 'mi_cond' ] );
					
					if ( $db_data[ 'access_type' ] == 'users' ){
						
						$db_data[ 'access_ids' ] = implode( '|', $this->input->post( 'access_user_id' ) );
						
					}
					else if ( $db_data[ 'access_type' ] == 'users_groups' ){
						
						$db_data[ 'access_ids' ] = implode( '|', $this->input->post( 'access_user_group_id' ) );
						
					}
					else{
						
						$db_data[ 'access_ids' ] = 0;
						
					}
					
					if ( $db_data[ 'alias' ] == '' ){
						
						$db_data[ 'alias' ] = url_title( $db_data[ 'title' ], '-', TRUE );
						
					}
					
					$db_data[ 'params' ] = json_encode( $db_data[ 'params' ] );
					
					//print_r( $db_data );
					
					if ( $action == 'am' ){
						
						$return_id = $this->modules_common_model->insert( $db_data );
						
						if ( $return_id ){
							
							msg( ( 'module_added' ), 'success' );
							
							if ( $this->input->post( 'submit_apply' ) ){
								
								$assoc_to_uri_array = array( 
									
									'mid' => $return_id,
									'a' => 'em',
									't' => $type,
									
								 );
								
								$redirect_url = $this->uri->assoc_to_uri( $assoc_to_uri_array );
								
								$redirect_url = 'admin/' . $this->component_name . '/' . __FUNCTION__ . '/' . $redirect_url;
								
								redirect( $redirect_url );
								
							}
							else{
								redirect_last_url();
							}
							
						}
						
					}
					else if ( $action == 'em' ){
						
						if ( $this->modules_common_model->update( $db_data, array( 'id' => $module_id ) ) ){
							
							msg( ( 'module_updated' ), 'success' );
							
							if ( $this->input->post( 'submit_apply' ) ){
								
								redirect( get_url( 'admin' . $this->uri->ruri_string() ) );
								
							}
							else{
								
								redirect_last_url();
								
							}
							
						}
						
					}
					
				}
				// caso contrário se a validação dos campos for negativa e mensagens de erro conter strings
				else if ( !$this->form_validation->run() AND validation_errors() != '' ){
					
					$data[ 'post' ] = $this->input->post();
					
					msg( ( 'add_menu_item_fail' ),'title' );
					msg( validation_errors( '<div class="error">', '</div>' ),'error' );
				}
				
				$this->_page( 
					
					array( 
						
						'component_view_folder' => $this->component_view_folder,
						'function' => 'modules_management',
						'action' => 'module_form',
						'layout' => 'default',
						'view' => 'module_form',
						'data' => $data,
						
					 )
					
				 );
				
			}
			
		}
		else if ( $action == 'edit_menu_item' ){
			if ( $var1 AND ( $menu_item = $this->menus->get_menu_item( $var1 ) ) ){
				
				$target_component_item = $this->main_model->get_component_item( $menu_item->component_item_id );
				$this->load->model( array( 'admin/'.$target_component_item->row()->component_alias.'_model' ) );
				$data = array( 
					'menu_type_id' => $menu_type_id,
					'component_name' => $this->component_name,
					'target_component_item' => $target_component_item->row(),
					'menu_item' => $menu_item,
					'menu_items' => $this->menus_model->get_menu_tree( 0,0,'list', $menu_type_id ),
					'users' => $this->users->get_users_checking_privileges()->result_array(),
					'users_groups' => $this->users->get_accessible_users_groups( $this->users->user_data[ 'id' ] ),
				 );
				
				$this->load->language( array( 'admin/'.$target_component_item->row()->component_alias ) );
				
				// obtendo os parâmetros do item específico do componente
				//$data[ 'params' ] = $this->{$target_component_item->row()->component_alias.'_model'}->{'menu_item_'.$target_component_item->row()->alias}( $var1 );
				
				// obtendo os parâmetros do item específico do item de menu
				//$data[ 'menu_item_params' ] = $this->menus->get_menu_item_params();
				
				
				if ( $menu_item->access_type === 'users' ){
					$menu_item->access_user_id = explode( '|', $menu_item->access_ids );
				}
				else if ( $menu_item->access_type === 'users_groups' ){
					$menu_item->access_user_group_id = explode( '|', $menu_item->access_ids );
				}
				
				/******************************/
				/********* Parâmetros *********/
				
				// obtendo os valores atuais dos parâmetros
				$data[ 'current_params_values' ] = json_decode( $menu_item->params, TRUE );
				
				/********* Componente *********/
				
				// obtendo as especificações dos parâmetros
				$data[ 'component_params_spec' ] = $this->{$target_component_item->row()->component_alias.'_model'}->{'menu_item_'.$target_component_item->row()->alias}( $var1 );
				
				// cruzando os valores padrões das especificações com os atuais
				$data[ 'component_final_params_values' ] = array_merge( $data[ 'component_params_spec' ][ 'params_spec_values' ], $data[ 'current_params_values' ] );
				
				// definindo as regras de validação
				set_params_validations( $data[ 'component_params_spec' ][ 'params_spec' ] );
				
				/********* Componente *********/
				
				
				/******** Item de menu ********/
				
				// obtendo as especificações dos parâmetros
				$data[ 'menu_item_params_spec' ] = $this->menus_model->get_menu_item_params();
				
				// cruzando os valores padrões das especificações com os atuais
				$data[ 'menu_item_final_params_values' ] = array_merge( $data[ 'menu_item_params_spec' ][ 'params_spec_values' ], $data[ 'current_params_values' ] );
				
				// definindo as regras de validação
				set_params_validations( $data[ 'menu_item_params_spec' ][ 'params_spec' ] );
				
				/******** Item de menu ********/
				/********* Parâmetros *********/
				/******************************/
				
				
				//validação dos campos
				$this->form_validation->set_rules( 'title',lang( 'title' ),'trim|required' );
				$this->form_validation->set_rules( 'alias',lang( 'alias' ),'trim' );
				$this->form_validation->set_rules( 'status',lang( 'status' ),'trim|required|integer' );
				$this->form_validation->set_rules( 'parent',lang( 'parent' ),'trim|required|integer' );
				$this->form_validation->set_rules( 'description',lang( 'description' ),'trim' );
				
				// se o tipo de nível de acesso for para usuários específicos, aplicamos a validação pertinente
				if ( $this->input->post( 'access_type' ) == 'users' ){
					
					$this->form_validation->set_rules( 'access_user_id[]',lang( 'access_user' ),'trim|required' );
					
				}
				// caso contrário, se o tipo de nível de acesso for para grupos de usuários específicos, aplicamos a validação pertinente
				else if ( $this->input->post( 'access_type' ) == 'users_groups' ){
					
					$this->form_validation->set_rules( 'access_user_group_id[]',lang( 'access_user_group' ),'trim|required' );
					
				}
				
				if ( $this->input->post( 'submit_cancel' ) ){
					
					redirect_last_url();
					
				}
				
				// se a validação dos campos for positiva
				else if ( $this->form_validation->run() AND ( $this->input->post( 'submit' ) OR $this->input->post( 'submit_apply' ) ) ){
					
					$update_data = elements( array( 
					
						'alias',
						'title',
						'description',
						'status',
						'parent',
						'component_id',
						'menu_type_id',
						'access_type',
						'params',
						
					 ), $this->input->post() );
					
					if ( $update_data[ 'access_type' ] == 'users' ){
						
						$update_data[ 'access_ids' ] = implode( '|', $this->input->post( 'access_user_id' ) );
						
					}
					else if ( $update_data[ 'access_type' ] == 'users_groups' ){
						$update_data[ 'access_ids' ] = implode( '|', $this->input->post( 'access_user_group_id' ) );
					}
					else{
						$update_data[ 'access_ids' ] = 0;
					}
					
					if ( $update_data[ 'alias' ] == '' ){
						$update_data[ 'alias' ] = url_title( $update_data[ 'title' ],'-',TRUE );
					}
					
					$update_data[ 'params' ] = json_encode( $update_data[ 'params' ] );
					
					$data_for_component_get_link = get_params( $update_data[ 'params' ] );
					
					$update_data[ 'link' ] = $this->{$target_component_item->row()->component_alias.'_model'}->{'menu_item_get_link_'.$target_component_item->row()->alias}( $this->input->post( 'menu_item_id' ), $data_for_component_get_link );
					
					if ( $this->menus_model->update( $update_data, array( 'id' => $this->input->post( 'menu_item_id' ) ) ) ){
						msg( ( 'menu_item_updated' ),'success' );
						if ( $this->input->post( 'submit_apply' ) ){
							redirect( get_url( 'admin'.$this->uri->ruri_string() ) );
						}
						else{
							redirect_last_url();
						}
					}
					
				}
				// caso contrário se a validação dos campos for negativa e mensagens de erro conter strings
				else if ( !$this->form_validation->run() AND validation_errors() != '' ){
					
					$data[ 'post' ] = $this->input->post();
					
					msg( ( 'task_create_fail' ),'title' );
					msg( validation_errors( '<div class="error">', '</div>' ),'error' );
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
				show_404();
			}
		}
		
		/*
		 --------------------------------------------------------
		 Add / edit menu item
		 --------------------------------------------------------
		 ********************************************************
		 */
		
		/*
		 ********************************************************
		 --------------------------------------------------------
		 Remove module
		 --------------------------------------------------------
		 */
		
		else if ( $action == 'rm' ){
			
			if ( $module_id AND ( $module = $this->modules_common_model->get_module( $module_id )->row_array() ) ){
				
				if( $this->input->post( 'submit_cancel' ) ){
					
					redirect_last_url();
					
				}
				else if ( $module AND $module_id AND $this->input->post( 'submit' ) ){
					
					if ( $this->modules_common_model->delete( array( 'id'=> $module_id ) ) ){
						
						msg( ( 'module_deleted' ),'success' );
						redirect_last_url();
						
					}
					else{
						
						msg( $this->lang->line( 'module_deleted_fail' ),'error' );
						redirect_last_url();
						
					}
				}
				else{
					
					$data[ 'module' ] = $module;
					
					$this->_page( 
						
						array( 
							
							'component_view_folder' => $this->component_view_folder,
							'function' => 'modules_management',
							'action' => 'remove_module',
							'layout' => 'default',
							'view' => 'remove_module',
							'data' => $data,
							
						 )
						
					 );
					
				}
			}
			else{
				show_404();
			}
		}
		
		/*
		 --------------------------------------------------------
		 Remove module
		 --------------------------------------------------------
		 ********************************************************
		 */
		
		else{
			show_404();
		}
	}
	
	/*
	 --------------------------------------------------------------------------------------------------
	 Modules management
	 --------------------------------------------------------------------------------------------------
	 **************************************************************************************************
	 **************************************************************************************************
	 */
	
}
