<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'controllers/admin/main.php');

class Urls extends Main {
	
	public function __construct(){
		
		parent::__construct();
		
		$this->load->model(array('common/urls_common_model'));
		$this->load->language(array('admin/menus'));
		
		set_current_component();
		
		// verifica se o usuário atual possui privilégios para gerenciar menus
		if ( ! $this->users->check_privileges('urls_management_urls_management') ){
			msg(('access_denied'),'title');
			msg(('access_denied_urls_management_urls_management'),'error');
			redirect_last_url();
		};
		
	}
	
	public function index(){
		
		$this->um( 'a/ul' );
		
	}
	
	/*
	 **************************************************************************************************
	 **************************************************************************************************
	 --------------------------------------------------------------------------------------------------
	 Urls management
	 --------------------------------------------------------------------------------------------------
	 */
	
	public function um(){
		
		// -------------------------------------------------
		// Parsing vars ------------------------------------
		
		$f_params = $this->uri->ruri_to_assoc();
		
		$action =								@isset( $f_params['a'] ) ? $f_params['a'] : 'ul'; // action
		$sub_action =							@isset( $f_params['sa'] ) ? $f_params['sa'] : NULL; // sub action
		$url_id =								@isset( $f_params['uid'] ) ? $f_params['uid'] : NULL; // menu item id
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
			
			'a' => 'au',
			
		);
		
		$urls_list_link_array = $base_link_array + array(
			
			'a' => 'ul',
			
		);
		
		$urls_search_link_array = $base_link_array + array(
			
			'a' => 's',
			
		);
		
		$delete_all_urls_link_array = $base_link_array + array(
			
			'a' => 'ra',
			
		);
		
		$data[ 'add_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $add_link_array );
		$data[ 'urls_list_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $urls_list_link_array );
		$data[ 'urls_search_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $urls_search_link_array );
		$data[ 'delete_all_urls_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $delete_all_urls_link_array );
		
		// admin url
		$a_url = get_url( 'admin' . $this->uri->ruri_string() );
		
		/*
		 ********************************************************
		 --------------------------------------------------------
		 Urls list
		 --------------------------------------------------------
		 */
		
		if ( $action == 'ul' OR $action == 's' ){
			
			$this->load->library(array('str_utils'));
			$this->load->helper( array( 'pagination' ) );
			
			/*
			 ********************************************************
			 --------------------------------------------------------
			 Ordenção por colunas
			 --------------------------------------------------------
			 */
			
			if ( ! ( ( $order_by_direction = $this->users->get_user_preference( 'urls_list_order_by_direction' ) ) != FALSE ) ){
				
				$order_by_direction = 'ASC';
				
			}
			
			// order by complement
			$comp_ob = '';
			
			if ( ( $order_by = $this->users->get_user_preference( 'urls_list_order_by' ) ) != FALSE ){
				
				$data[ 'order_by' ] = $order_by;
				
				switch ( $order_by ) {
					
					case 'id':
						
						$order_by = 't1.id';
						break;
						
					case 'sef_url':
						
						$order_by = 't1.sef_url';
						break;
						
					case 'target':
					
						$order_by = 't1.target';
						$comp_ob = ', t1.sef_url '. $order_by_direction;
						break;
						
				}
				
			}
			else{
				
				$order_by = 't1.sef_url';
				$data[ 'order_by' ] = 'sef_url';
				
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
			
			//validação dos campos
			$errors = FALSE;
			$errors_msg = '';
			$terms = trim( $this->input->post('terms', TRUE) ? $this->input->post('terms', TRUE) : ($this->input->get('q') ? urldecode($this->input->get('q')) : FALSE) );
			
			if ( $this->input->post( 'submit_search', TRUE ) AND ( $terms OR $terms == 0) ){
				
				if ( strlen($terms) == 0 ){
					$errors = TRUE;
					$errors_msg .= '<div class="error">'.lang('validation_error_terms_not_blank').'</div>';
				}
				if ( strlen($terms) < 2 ){
					$errors = TRUE;
					$errors_msg .= '<div class="error">'.sprintf(lang('validation_error_terms_min_lenght'), 2).'</div>';
				}
				
			}
			else if ( $this->input->post('submit_cancel_search', TRUE) ){
				
				redirect( $data[ 'urls_list_link' ] );
				
			}
			
			$data['search']['terms'] = $terms;
			
			$this->form_validation->set_rules('terms',lang('terms'),'trim|min_length[2]');
			
			$gu_params = array( 
				
				'order_by' => $order_by,
				'limit' => $ipp,
				'offset' => $offset,
				
			);
			
			$get_query = '';
			
			if( ( $this->input->post('submit_search') OR $terms ) AND ! $errors){
				
				$condition = NULL;
				$or_condition = NULL;
				
				if( $terms ){
					
					$get_query = urlencode( $terms );
					
					$full_term = $terms;
					
					$condition['fake_index_1'] = '';
					$condition['fake_index_1'] .= '(';
					$condition['fake_index_1'] .= '`t1`.`id` LIKE \'%'.$full_term.'%\' ';
					$condition['fake_index_1'] .= 'OR `t1`.`sef_url` LIKE \'%'.$full_term.'%\' ';
					$condition['fake_index_1'] .= 'OR `t1`.`target` LIKE \'%"name":"%'.$full_term.'%"%\' ';
					$condition['fake_index_1'] .= ')';
					
					$terms = str_replace('#', ' ', $terms);
					$terms = explode(" ", $terms);
					
					$and_operator = FALSE;
					$like_query = '';
					
					foreach ($terms as $key => $term) {
						
						$like_query .= $and_operator === TRUE ? 'AND ' : '';
						$like_query .= '(';
						$like_query .= '`t1`.`id` LIKE \'%'.$full_term.'%\' ';
						$like_query .= 'OR `t1`.`sef_url` LIKE \'%'.$full_term.'%\' ';
						$like_query .= 'OR `t1`.`target` LIKE \'%"name":"%'.$full_term.'%"%\' ';
						$like_query .= ')';
						
						if ( ! $and_operator ){
							$and_operator = TRUE;
						}
						
					}
					
					$or_condition = '(' . $like_query . ')';
					
					$gu_params[ 'or_where_condition' ] = $or_condition;
					
					$get_query = '?q=' . $get_query;
					
				}
				
			}
			else if ( $errors ){
				
				$data[ 'post' ] = $this->input->post();
				
				msg( ( 'search_fail' ), 'title' );
				msg( $errors_msg,'error' );
				
				redirect( get_last_url() );
				
			}
			
			$urls = $this->urls_common_model->mng_get_urls( $gu_params )->result_array();
			
			foreach ( $urls as $key => &$url ) {
				
				$base_link_array = array(
					
					'uid' => $url[ 'id' ],
					
				);
				
				$edit_link_array = $base_link_array + array(
					
					'a' => 'eu',
					
				);
				
				$remove_link_array = $base_link_array + array(
					
					'a' => 'ru',
					
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
				
				$url[ 'edit_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $edit_link_array );
				$url[ 'remove_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $remove_link_array );
				$url[ 'change_order_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $change_order_link_array );
				$url[ 'up_order_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $up_order_link_array );
				$url[ 'down_order_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $down_order_link_array );
				
				if ( ! empty( $terms ) ){
					
					foreach ( $terms as $term ) {
						
						$url[ 'id' ] = str_highlight( $url[ 'id' ], $term );
						$url[ 'sef_url' ] = str_highlight( $url[ 'sef_url' ], $term );
						$url[ 'target' ] = str_highlight( $url[ 'target' ], $term );
						
					}
					
				}
				
			}
			
			$data[ 'urls' ] = $urls;
			
			unset( $gu_params[ 'order_by' ] );
			unset( $gu_params[ 'limit' ] );
			unset( $gu_params[ 'offset' ] );
			
			$gu_params[ 'return_type' ] = 'count_all_results';
			
			$data[ 'pagination' ] = get_pagination( 
			
				( ( ! empty( $terms ) ) ? $data[ 'urls_search_link' ] : $data[ 'urls_list_link' ] ) . '/cp/%p%/ipp/%ipp%' . $get_query,
				$cp,
				$ipp,
				$this->urls_common_model->mng_get_urls( $gu_params )
				
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
					'function' => 'urls_management',
					'action' => 'urls_list',
					'layout' => 'default',
					'view' => 'urls_list',
					'data' => $data,
					
				)
				
			);
			
		}
		
		/*
		 --------------------------------------------------------
		 Urls list
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
			
			$this->users->set_user_preferences( array( 'urls_list_order_by' => $ob ) );
			
			if ( ( $order_by_direction = $this->users->get_user_preference( 'urls_list_order_by_direction' ) ) != FALSE ){
				
				switch ( $order_by_direction ) {
					
					case 'ASC':
						
						$order_by_direction = 'DESC';
						break;
						
					case 'DESC':
					
						$order_by_direction = 'ASC';
						break;
						
				}
				
				$this->users->set_user_preferences( array( 'urls_list_order_by_direction' => $order_by_direction ) );
				
			}
			else {
				
				$this->users->set_user_preferences( array( 'urls_list_order_by_direction' => 'ASC' ) );
				
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
		 Add / edit url
		 --------------------------------------------------------
		 */
		
		else if ( $action == 'au' OR $action == 'eu' ){
			
			if ( $action == 'eu' ){
				
				$url = $this->urls_common_model->get_url( $url_id )->row_array();
				$data[ 'url' ] = $url;
				
			}
			
			//validação dos campos
			$this->form_validation->set_rules( 'sef_url', lang( 'sef_url' ), 'trim|required' );
			$this->form_validation->set_rules( 'target', lang( 'target' ), 'trim|required' );
			
			if ( $this->input->post( 'submit_cancel' ) ){
				
				redirect_last_url();
				
			}
			// se a validação dos campos for positiva
			else if ( $this->form_validation->run() AND ( $this->input->post( 'submit' ) OR $this->input->post( 'submit_apply' ) ) ){
				
				$db_data = elements( array(
					
					'sef_url',
					'target',
					
				), $this->input->post() );
				
				if ( $action == 'au' ){
					
					$return_id = $this->urls_common_model->insert( $db_data );
					
					if ( $return_id ){
						
						msg(('menu_item_created'),'success');
						
						if ( $this->input->post( 'submit_apply' ) ){
							
							$assoc_to_uri_array = array(
								
								'uid' => $return_id,
								'a' => 'eu',
								
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
				else if ( $action == 'eu' ){
					
					if ( $this->urls_common_model->update( $db_data, array( 'id' => $url_id ) ) ){
						
						msg( ( 'menu_item_updated' ), 'success' );
						
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
			else if (!$this->form_validation->run() AND validation_errors() != ''){
				
				$data['post'] = $this->input->post();
				
				msg(('add_menu_item_fail'),'title');
				msg(validation_errors('<div class="error">', '</div>'),'error');
			}
			//print_r($data);
			$this->_page(
				
				array(
					
					'component_view_folder' => $this->component_view_folder,
					'function' => 'urls_management',
					'action' => 'url_form',
					'layout' => 'default',
					'view' => 'url_form',
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
		
		else if ( $action == 'ru' ){
			
			if ( $url_id AND ( $url = $this->urls_common_model->get_url( $url_id )->row_array() ) ){
				
				if( $this->input->post( 'submit_cancel' ) ){
					
					redirect_last_url();
					
				}
				else if ( $this->input->post( 'submit' ) ){
					
					if ( $this->urls_common_model->delete( array( 'id'=>$url_id ) ) ){
						
						msg( 'url_deleted', 'success');
						redirect_last_url();
						
					}
					else{
						
						msg($this->lang->line( 'url_deleted_fail' ), 'error' );
						redirect_last_url();
						
					}
					
				}
				else{
					
					$data[ 'url' ] = $url;
					
					$this->_page(
						
						array(
							
							'component_view_folder' => $this->component_view_folder,
							'function' => 'urls_management',
							'action' => 'remove_url',
							'layout' => 'default',
							'view' => 'remove_url',
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
			
			
			if ( $this->urls_common_model->delete_all() ){
				
				msg( 'all_urls_deleted', 'success');
				redirect_last_url();
				
			}
			else{
				
				msg($this->lang->line( 'all_urls_deleted_fail' ), 'error' );
				redirect_last_url();
				
			}
			
		}
		
		/*
		 --------------------------------------------------------
		 Remove all urls
		 --------------------------------------------------------
		 ********************************************************
		 */
		
		else{
			show_404();
		}
	}
	
	/*
	 --------------------------------------------------------------------------------------------------
	 Menu items management
	 --------------------------------------------------------------------------------------------------
	 **************************************************************************************************
	 **************************************************************************************************
	 */
	
}
