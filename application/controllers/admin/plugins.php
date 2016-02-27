<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'controllers/admin/main.php');

class Plugins extends Main {
	
	public function __construct(){
		
		parent::__construct();
		
		$this->load->model(array('common/plugins_common_model'));
		$this->load->language(array('admin/plugins'));
		
		set_current_component();
		
		// verifica se o usuário atual possui privilégios para gerenciar menus
		if ( ! $this->users->check_privileges('plugins_management') ){
			msg(('access_denied'),'title');
			msg(('access_denied_plugins_management'),'error');
			redirect_last_url();
		};
		
	}
	
	public function index(){
		
		$this->pm();
		
	}
	
	/*
	 **************************************************************************************************
	 **************************************************************************************************
	 --------------------------------------------------------------------------------------------------
	 Submit_forms management
	 --------------------------------------------------------------------------------------------------
	 */
	
	public function pm(){
		
		// -------------------------------------------------
		// Parsing vars ------------------------------------
		
		$f_params = $this->uri->ruri_to_assoc();
		
		$action =								@isset( $f_params['a'] ) ? $f_params['a'] : 'pl'; // action
		$sub_action =							@isset( $f_params['sa'] ) ? $f_params['sa'] : NULL; // sub action
		$plugin_id =							@isset( $f_params['pid'] ) ? $f_params['pid'] : NULL; // submit form id
		$cp =									@isset( $f_params[ 'cp' ] ) ? $f_params[ 'cp' ] : NULL; // current page
		$ipp =									@isset( $f_params[ 'ipp' ] ) ? $f_params[ 'ipp' ] : NULL; // items per page
		$ob =									@isset( $f_params[ 'ob' ] ) ? $f_params[ 'ob' ] : NULL; // order by
		
		// Parsing vars ------------------------------------
		// -------------------------------------------------
		
		// atualizando informações do componente atual
		$this->component_function = __FUNCTION__;
		$this->component_function_action = $action;
		
		
		
		$base_link_prefix = 'admin/' . $this->component_name . '/' . __FUNCTION__ . '/';
		
		$base_link_array = array(
			
		);
		
		$add_link_array = $base_link_array + array(
			
			'a' => 'ap',
			
		);
		
		$list_link_array = $base_link_array + array(
			
			'a' => 'pl',
			
		);
		
		$search_link_array = $base_link_array + array(
			
			'a' => 's',
			
		);
		
		$delete_all_link_array = $base_link_array + array(
			
			'a' => 'ra',
			
		);
		
		$data[ 'add_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $add_link_array );
		$data[ 'list_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $list_link_array );
		$data[ 'search_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $search_link_array );
		$data[ 'delete_all_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $delete_all_link_array );
		
		// admin url
		$a_url = get_url( 'admin' . $this->uri->ruri_string() );
		
		/*
		 ********************************************************
		 --------------------------------------------------------
		 Plugins list
		 --------------------------------------------------------
		 */
		
		if ( $action == 'pl' OR $action == 's' ){
			
			$this->load->library(array('str_utils'));
			$this->load->helper( array( 'pagination' ) );
			
			/*
			 ********************************************************
			 --------------------------------------------------------
			 Ordenação por colunas
			 --------------------------------------------------------
			 */
			
			if ( ! ( ( $order_by_direction = $this->users->get_user_preference( 'plugins_list_order_by_direction' ) ) != FALSE ) ){
				
				$order_by_direction = 'ASC';
				
			}
			
			// order by complement
			$comp_ob = '';
			
			if ( ( $order_by = $this->users->get_user_preference( 'plugins_list_order_by' ) ) != FALSE ){
				
				$data[ 'order_by' ] = $order_by;
				
				switch ( $order_by ) {
					
					case 'id':
						
						$order_by = 't1.id';
						break;
						
					case 'title':
					
						$order_by = 't1.title';
						$comp_ob = ', t1.title '. $order_by_direction;
						break;
						
					case 'alias':
						
						$order_by = 't1.name';
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
			 Ordenação por colunas
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
			
			//validação dos campos
			$errors = FALSE;
			$errors_msg = '';
			$terms = trim( $this->input->post( 'terms', TRUE ) ? $this->input->post( 'terms', TRUE ) : ( $this->input->get( 'q' ) ? urldecode( $this->input->get( 'q' ) ) : FALSE ) );
			
			if ( $this->input->post( 'submit_search', TRUE ) AND ( $terms OR $terms == 0) ){
				
				if ( strlen( $terms ) == 0 ){
					
					$errors = TRUE;
					$errors_msg .= '<div class="error">' . lang( 'validation_error_terms_not_blank' ).'</div>';
					
				}
				if ( strlen( $terms ) < 2 ){
					
					$errors = TRUE;
					$errors_msg .= '<div class="error">' . sprintf( lang( 'validation_error_terms_min_lenght' ), 2 ).'</div>';
					
				}
				
			}
			else if ( $this->input->post( 'submit_cancel_search', TRUE ) ){
				
				redirect( $data[ 'plugins_list_link' ] );
				
			}
			
			$data['search']['terms'] = $terms;
			
			$this->form_validation->set_rules( 'terms', lang('terms'), 'trim|min_length[2]' );
			
			$gsf_params = array( 
				
				'order_by' => $order_by,
				'limit' => $ipp,
				'offset' => $offset,
				
			);
			
			$get_query = '';
			
			if( ( $this->input->post( 'submit_search' ) OR $terms ) AND ! $errors){
				
				$condition = NULL;
				$or_condition = NULL;
				
				if( $terms ){
					
					$get_query = urlencode( $terms );
					
					$full_term = $terms;
					
					$condition['fake_index_1'] = '';
					$condition['fake_index_1'] .= '(';
					$condition['fake_index_1'] .= '`t1`.`id` LIKE \'%'.$full_term.'%\' ';
					$condition['fake_index_1'] .= 'OR `t1`.`title` LIKE \'%'.$full_term.'%\' ';
					$condition['fake_index_1'] .= 'OR `t1`.`name` LIKE \'%'.$full_term.'%\' ';
					$condition['fake_index_1'] .= 'OR `t1`.`version` LIKE \'%'.$full_term.'%\' ';
					$condition['fake_index_1'] .= 'OR `t1`.`type` LIKE \'%'.$full_term.'%\' ';
					$condition['fake_index_1'] .= ')';
					
					$terms = str_replace('#', ' ', $terms);
					$terms = explode(" ", $terms);
					
					$and_operator = FALSE;
					$like_query = '';
					
					foreach ( $terms as $key => $term ) {
						
						$like_query .= $and_operator === TRUE ? 'AND ' : '';
						$like_query .= '(';
						$like_query .= '`t1`.`id` LIKE \'%'.$full_term.'%\' ';
						$like_query .= 'OR `t1`.`title` LIKE \'%'.$full_term.'%\' ';
						$like_query .= 'OR `t1`.`name` LIKE \'%'.$full_term.'%\' ';
						$like_query .= 'OR `t1`.`version` LIKE \'%'.$full_term.'%\' ';
						$like_query .= 'OR `t1`.`type` LIKE \'%'.$full_term.'%\' ';
						$like_query .= ')';
						
						if ( ! $and_operator ){
							$and_operator = TRUE;
						}
						
					}
					
					$or_condition = '(' . $like_query . ')';
					
					$gsf_params[ 'or_where_condition' ] = $or_condition;
					
					$get_query = '?q=' . $get_query;
					
				}
				
			}
			else if ( $errors ){
				
				$data[ 'post' ] = $this->input->post();
				
				msg( ( 'search_fail' ), 'title' );
				msg( $errors_msg,'error' );
				
				redirect( get_last_url() );
				
			}
			
			$plugins = $this->plugins_common_model->get_plugins( $gsf_params )->result_array();
			
			foreach ( $plugins as $key => & $plugin ) {
				
				$base_link_array = array(
					
					'pid' => $plugin[ 'id' ],
					
				);
				
				$edit_link_array = $base_link_array + array(
					
					'a' => 'ep',
					
				);
				
				$remove_link_array = $base_link_array + array(
					
					'a' => 'rp',
					
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
					'pid' => $plugin[ 'id' ],
					
				 );
				
				$change_status_unpublish_link_array = $base_link_array + array( 
					
					'a' => 'csu',
					'pid' => $plugin[ 'id' ],
					
				 );
				 
				$plugin[ 'edit_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $edit_link_array );
				$plugin[ 'remove_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $remove_link_array );
				$plugin[ 'change_order_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $change_order_link_array );
				$plugin[ 'up_order_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $up_order_link_array );
				$plugin[ 'down_order_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $down_order_link_array );
				$plugin[ 'change_status_publish_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $change_status_publish_link_array );
				$plugin[ 'change_status_unpublish_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $change_status_unpublish_link_array );
				
				if ( ! empty( $terms ) ){
					
					foreach ( $terms as $term ) {
						
						$plugin[ 'id' ] = str_highlight( $plugin[ 'id' ], $term );
						$plugin[ 'title' ] = str_highlight( $plugin[ 'title' ], $term );
						$plugin[ 'name' ] = str_highlight( $plugin[ 'name' ], $term );
						$plugin[ 'version' ] = str_highlight( $plugin[ 'version' ], $term );
						$plugin[ 'type' ] = str_highlight( $plugin[ 'type' ], $term );
						
					}
					
				}
				
			}
			
			$data[ 'plugins' ] = $plugins;
			
			unset( $gsf_params[ 'order_by' ] );
			unset( $gsf_params[ 'limit' ] );
			unset( $gsf_params[ 'offset' ] );
			
			$gsf_params[ 'return_type' ] = 'count_all_results';
			
			$data[ 'pagination' ] = get_pagination( 
			
				( ( ! empty( $terms ) ) ? $data[ 'search_link' ] : $data[ 'list_link' ] ) . '/cp/%p%/ipp/%ipp%' . $get_query,
				$cp,
				$ipp,
				$this->plugins_common_model->get_plugins( $gsf_params )
				
			);
			
			/*
			 --------------------------------------------------------
			 Paginação
			 --------------------------------------------------------
			 ********************************************************
			 */
			
			set_last_url( $a_url );
			
			$this->_page(
				
				array(
					
					'component_view_folder' => $this->component_view_folder,
					'function' => 'plugins_management',
					'action' => 'plugins_list',
					'layout' => 'default',
					'view' => 'plugins_list',
					'data' => $data,
					
				)
				
			);
			
		}
		
		/*
		 --------------------------------------------------------
		 Plugins list
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
			
			$this->users->set_user_preferences( array( 'plugins_list_order_by' => $ob ) );
			
			if ( ( $order_by_direction = $this->users->get_user_preference( 'plugins_list_order_by_direction' ) ) != FALSE ){
				
				switch ( $order_by_direction ) {
					
					case 'ASC':
						
						$order_by_direction = 'DESC';
						break;
						
					case 'DESC':
					
						$order_by_direction = 'ASC';
						break;
						
				}
				
				$this->users->set_user_preferences( array( 'plugins_list_order_by_direction' => $order_by_direction ) );
				
			}
			else {
				
				$this->users->set_user_preferences( array( 'plugins_list_order_by_direction' => 'ASC' ) );
				
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
		
		else if ( ( $action == 'csp' OR $action == 'csu' ) AND $plugin_id ){
				
			$update_data = array( 
				
				'status' => $action == 'csp' ? '1' : '0',
				
			 );
			
			if ( $this->plugins_common_model->update_plugin( $update_data, array( 'id' => $plugin_id ) ) ){
				
				msg( ( 'plugin_' . ( $action == 'csp' ? 'published' : 'unpublished' ) ), 'success' );
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
		
		else if ( ( $action == 'co' ) AND $this->input->post( 'plugin_id' ) ){
			
			$update_data = array(
				
				'ordering' => $this->input->post( 'ordering' ) > 0 ? $this->input->post( 'ordering' ) : 1,
				
			);
			
			if ( $this->plugins_common_model->update_plugin( $update_data, array( 'id' => $this->input->post( 'plugin_id' ) ) ) ){
				
				msg( ( 'plugin_order_changed' ), 'success' );
				redirect( get_url( $data[ 'list_link' ] ) );
				
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
		
		else if ( ( $action == 'uo' OR $action == 'do' ) AND $this->input->post( 'plugin_id' ) ){
			
			$current_order = $this->input->post( 'ordering' );
			
			if ( $this->input->post( 'submit_up_order' ) ){
				
				$current_order += 1;
				$closest_affected_plugin_increment = -1;
				
			}
			else if ( $this->input->post( 'submit_down_order' ) AND $current_order > 1 ){
				
				$current_order -= 1;
				$closest_affected_plugin_increment = +1;
				
			}
			
			
			
			$gp_params = array( 
				
				'order_by' => $order_by,
				'limit' => $ipp,
				'offset' => $offset,
				'where_condition' => array(
					
					'ordering' => $current_order,
					
				),
				
			);
			
			$closest_affected_plugin = $this->plugins_common_model->get_plugins( $gp_params )->row_array();
			
			if ( $closest_affected_plugin ){
				
				$$closest_affected_plugin_update_data = array(
				
					'ordering' => $current_order + $closest_affected_plugin_increment,
					
				);
				
				$this->plugins_common_model->update_plugin( $$closest_affected_plugin_update_data, array( 'id' => $closest_affected_plugin[ 'id' ] ) );
				
			}
			
			
			
			$new_order = $current_order > 0 ? $current_order : 1;
			
			$update_data = array(
			
				'ordering' => $new_order,
				
			);
			
			if ( $this->plugins_common_model->update_plugin( $update_data, array( 'id' => $this->input->post( 'plugin_id' ) ) ) ){
				
				msg(('plugin_order_changed'),'success');
				redirect( get_url( $data[ 'list_link' ] ) );
				
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
		 Add / edit submit form
		 --------------------------------------------------------
		 */
		
		else if ( $action == 'asf' OR $action == 'esf' ){
			
			if ( $action == 'asf' ){
				
				$plugin = array();
				
			}
			if ( $action == 'esf' ){
				
				$plugin = $this->plugins_common_model->get_plugin( $plugin_id )->row_array();
				$data[ 'plugin' ] = $plugin;
				
			}
			
			// capturando os dados obtidos via post, e guardando-os na variável $post_data
			$post_data = $this->input->post();
			
			// aqui definimos as ações obtidas via post, ex.: ao salvar, cancelar, adicionar campo, etc.
			// acionados ao submeter os forms
			$submit_action =
				
				$this->input->post( 'submit_add_field' ) ? 'add_field' :
				( $this->input->post( 'submit_cancel' ) ? 'cancel' :
				( $this->input->post( 'submit' ) ? 'submit' :
				( $this->input->post( 'submit_apply' ) ? 'apply' :
				'none' ) ) );
			
			if ( ! $post_data ){
				
				if ( $action == 'esf' ) {
					
					/*************************************/
					/*************** fields **************/
					
					$plugin[ 'fields' ] = json_decode( $plugin[ 'fields' ], TRUE );
					
					// verifica se $plugin['fields'] é um json válido
					if ( $plugin[ 'fields' ] ){
						
						// inicializando o array de field
						// o índice 0 é temporário, definido aqui apenas para evitar que seja preenchido pelo json_decode( $plugin['fields'], TRUE )
						// a ideia é reservar o espaço, e em seguida excluí-lo
						$plugin[ 'fields' ] = array_merge( array( 0 ), $plugin[ 'fields' ] );
						
						// aqui, excluo o primeiro índice, deixando o array começando de 1, e não de 0
						unset( $plugin[ 'fields' ][ 0 ] );
						
					}
					else{
						
						$plugin[ 'fields' ] = array();
						
					}
					
					/*************** fields **************/
					/*************************************/
					
					$plugin[ 'params' ] = json_decode( $plugin[ 'params' ], TRUE );
					
				}
				else if( $action == 'asf' ){
					
					$plugin[ 'params' ] = array();
					
				}
				
			}
			else{
				
				$plugin[ 'params' ] = $post_data[ 'params' ];
				
				/*************************************/
				/*************** fields **************/
				
				// verifica se há pedido de remoção de campos de fields
				if ( isset( $post_data[ 'submit_remove_field' ] ) ){
					
					// obtem o primeiro índice do array de remoção de fields
					reset( $post_data[ 'submit_remove_field' ] );
					unset( $post_data[ 'fields' ][ key( $post_data[ 'submit_remove_field' ] ) ] );
					
				}
				
				// verifica se há pedido de adição de campos de fields
				if( in_array( $submit_action, array( 'add_field' ) ) ){
					
					$field_key = 0;
					
					$field_type = isset( $post_data[ 'field_type_to_add' ] ) ? $post_data[ 'field_type_to_add' ] : 'input_text';
					
					// se existem campos de field, obtem o último índice
					if ( isset( $post_data[ 'fields' ] ) ){
						
						end( $post_data[ 'fields' ] );
						$field_key = key( $post_data[ 'fields' ] );
						
					}
					
					for ( $i = $field_key; $i < $field_key + $post_data[ 'field_fields_to_add' ]; $i++ ) {
						
						$post_data[ 'fields' ][ $i + 1 ] = array();
						
						// key é a ordem do field na listagem
						$post_data[ 'fields' ][ $i + 1 ][ 'key' ] = $i + 1;
						
						$post_data[ 'fields' ][ $i + 1 ][ 'field_type' ] = $field_type;
						
					}
					
				}
				
				// reordenando os índices dos fields
				$post_data[ 'fields' ] = isset( $post_data[ 'fields' ] ) ? $post_data[ 'fields' ] : array();
				$post_data[ 'fields' ] = array_merge( array( 0 ), $post_data[ 'fields' ] );
				$post_data[ 'fields' ] = array_values( $post_data[ 'fields' ] );
				unset( $post_data[ 'fields' ][ 0 ] );
				
				/*************** fields **************/
				/*************************************/
				
				$plugin = array_merge( $plugin, $post_data );
				
			}
			
			/*************************************/
			/*** Reordenando os fields pela key **/
			
			if ( isset( $plugin[ 'fields' ] ) AND gettype( $plugin[ 'fields' ] ) === 'array' ){
				
				$temp_array = array();
				
				foreach ( $plugin[ 'fields' ] as $key => $field ) {
					
					$temp_array[ $field[ 'key' ] ] = $field;
					
				}
				$plugin[ 'fields' ] = $temp_array;
				ksort( $plugin[ 'fields' ] );
				
				$post_data[ 'fields' ] = $plugin[ 'fields' ];
				
			}
			
			/*** Reordenando os fields pela key **/
			/*************************************/
			
			if ( $action == 'asf' ){
				
				if ( empty( $plugin[ 'fields' ] ) ){
					
					//$plugin[ 'fields' ][ 1 ][ 'key' ] = 1;
					
				}
				
			}
			
			$data[ 'plugin' ] = $plugin;
			
			/******************************/
			/********* Parâmetros *********/
			
			if ( $action == 'esf' ){
				
				// cruzando os parâmetros globais com os parâmetros locais para obter os valores atuais
				$data[ 'current_params_values' ] = get_params( $plugin[ 'params' ] );
				
			}
			else{
				
				$data[ 'current_params_values' ] = array();
				
			}
			
			// obtendo as especificações dos parâmetros
			$data[ 'params_spec' ] = $this->plugins_common_model->get_plugin_params();
			
			// cruzando os valores padrões das especificações com os do DB
			$data[ 'final_params_values' ] = array_merge( $data[ 'params_spec' ][ 'params_spec_values' ], $data[ 'current_params_values' ] );
			
			// definindo as regras de validação
			set_params_validations( $data[ 'params_spec' ][ 'params_spec' ] );
			
			/********* Parâmetros *********/
			/******************************/
			
			//validação dos campos
			$this->form_validation->set_rules( 'title', lang( 'title' ), 'trim|required' );
			$this->form_validation->set_rules( 'alias', lang( 'alias' ), 'trim' );
			
			if( in_array( $submit_action, array( 'add_field', 'remove_field' ) ) ){
				
				msg( ( $submit_action . '_success_message' ), 'success' );
				
			}
			else if( in_array( $submit_action, array( 'cancel' ) ) ){
				
				redirect_last_url();
				
			}
			// se a validação dos campos for positiva
			else if ( $this->form_validation->run() AND ( in_array( $submit_action, array( 'submit', 'apply' ) ) ) ){
				
				// convertendo os arrays de campos dinâmicos em json para inserção no db
				$post_data[ 'fields' ] = json_encode( $post_data[ 'fields' ] );
				$post_data[ 'params' ] = json_encode( $post_data[ 'params' ] );
				
				$db_data = elements( array(
					
					'alias',
					'title',
					'fields',
					'params',
					
				), $post_data );
				
				if ( $db_data[ 'alias' ] == '' ){
					$db_data[ 'alias' ] = url_title( $db_data[ 'title' ], '-', TRUE );
				}
				
				if ( $action == 'asf' ){
					
					$return_id = $this->plugins_common_model->insert( $db_data );
					
					if ( $return_id ){
						
						msg(('plugin_created'),'success');
						
						if ( $this->input->post( 'submit_apply' ) ){
							
							$assoc_to_uri_array = array(
								
								'sfid' => $return_id,
								'a' => 'esf',
								
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
				else if ( $action == 'esf' ){
					
					if ( $this->plugins_common_model->update( $db_data, array( 'id' => $plugin_id ) ) ){
						
						msg( ( 'plugin_updated' ), 'success' );
						
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
			else if ( ! $this->form_validation->run() AND validation_errors() != '' ){
				
				$data[ 'post' ] = $this->input->post();
				
				msg( ( 'add_plugin_fail' ),'title' );
				msg( validation_errors( '<div class="error">', '</div>' ), 'error' );
				
			}
			
			$this->_page(
				
				array(
					
					'component_view_folder' => $this->component_view_folder,
					'function' => 'plugins_management',
					'action' => 'plugin_form',
					'layout' => 'default',
					'view' => 'plugin_form',
					'data' => $data,
					
				)
				
			);
			
		}
		
		/*
		 ********************************************************
		 --------------------------------------------------------
		 Remove url
		 --------------------------------------------------------
		 */
		
		else if ( $action == 'rsf' ){
			
			if ( $plugin_id AND ( $plugin = $this->plugins_common_model->get_plugin( $plugin_id )->row_array() ) ){
				
				if( $this->input->post( 'submit_cancel' ) ){
					
					redirect_last_url();
					
				}
				else if ( $this->input->post( 'submit' ) ){
					
					if ( $this->plugins_common_model->delete( array( 'id'=>$plugin_id ) ) ){
						
						msg( 'plugin_deleted', 'success');
						redirect_last_url();
						
					}
					else{
						
						msg($this->lang->line( 'url_deleted_fail' ), 'error' );
						redirect_last_url();
						
					}
					
				}
				else{
					
					$data[ 'plugin' ] = $plugin;
					
					$this->_page(
						
						array(
							
							'component_view_folder' => $this->component_view_folder,
							'function' => 'plugins_management',
							'action' => 'remove_plugin',
							'layout' => 'default',
							'view' => 'remove_plugin',
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
		 Remove url
		 --------------------------------------------------------
		 ********************************************************
		 */
		
		/*
		 ********************************************************
		 --------------------------------------------------------
		 Remove url
		 --------------------------------------------------------
		 */
		
		else if ( $action == 'ra' ){
			
			
			if ( $this->plugins_common_model->delete_all() ){
				
				msg( 'all_plugins_deleted', 'success');
				redirect_last_url();
				
			}
			else{
				
				msg($this->lang->line( 'all_plugins_deleted_fail' ), 'error' );
				redirect_last_url();
				
			}
			
		}
		
		/*
		 --------------------------------------------------------
		 Remove all
		 --------------------------------------------------------
		 ********************************************************
		 */
		
		else{
			show_404();
		}
	}
	
	/*
	 --------------------------------------------------------------------------------------------------
	 Submit_forms management
	 --------------------------------------------------------------------------------------------------
	 **************************************************************************************************
	 **************************************************************************************************
	 */
	
}
