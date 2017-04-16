<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'controllers/admin/main.php');

class Menus extends Main {
	
	public function __construct(){
		
		parent::__construct();
		
		$this->load->database();
		
		$this->load->model( 'menus_mdl', 'menus' );
		$this->load->model( 'admin/menus_model' );
		$this->load->model( 'common/menus_common_model' );
		
		set_current_component();
		
		// verifica se o usuário atual possui privilégios para gerenciar menus
		if ( ! $this->users->check_privileges( 'menus_management_menus_management' ) ){
			msg(('access_denied'),'title');
			msg(('access_denied_menus_management_menus_management'),'error');
			redirect_last_url();
		};
		
	}
	
	public function index(){
		
		$this->mim();
		
	}
	
	/*
	 **************************************************************************************************
	 **************************************************************************************************
	 --------------------------------------------------------------------------------------------------
	 Menu items management
	 --------------------------------------------------------------------------------------------------
	 */
	
	public function mim(){
		
		// -------------------------------------------------
		// Parsing vars ------------------------------------
		
		$f_params = $this->uri->ruri_to_assoc();
		
		$action =								isset( $f_params[ 'a' ] ) ? $f_params[ 'a' ] : 'l'; // action
		$sub_action =							isset( $f_params[ 'sa' ] ) ? $f_params[ 'sa' ] : NULL; // sub action
		$menu_item_id =							isset( $f_params[ 'miid' ] ) ? $f_params[ 'miid' ] : NULL; // menu item id
		$menu_type_id =							isset( $f_params[ 'mtid' ] ) ? $f_params[ 'mtid' ] : NULL; // menu type id
		$type =									isset( $f_params[ 't' ] ) ? $f_params[ 't' ] : NULL; // menu item id
		$component_id =							isset( $f_params[ 'cid' ] ) ? $f_params[ 'cid' ] : NULL; // component id
		$component_item =						isset( $f_params[ 'ci' ] ) ? $f_params[ 'ci' ] : NULL; // component item
		$order_by =								isset( $f_params[ 'ob' ] ) ? $f_params[ 'ob' ] : NULL; // order by
		$post =									$this->input->post( NULL, TRUE ); // post array input
		$get =									$this->input->get(); // get array input
		
		// -------------
		
		$cp =									isset( $f_params[ 'cp' ] ) ? ( int ) $f_params[ 'cp' ] : NULL; // current page
			$cp =								( $cp < 1 ) ? 1 : $cp;
		$ipp =									isset( $f_params[ 'ipp' ] ) ? ( int ) $f_params[ 'ipp' ] : NULL; // items per page
			$ipp =								isset( $post[ 'ipp' ] ) ? ( int ) $post[ 'ipp' ] : $ipp; // items per page
		
		if ( isset( $post[ 'ipp' ] ) ){
			
			$ipp = $post[ 'ipp' ];
			
		}
		else if (
			
			is_numeric( $this->users->get_user_preference( $this->mcm->environment . '_menu_items_items_per_page' ) ) AND
			$this->users->get_user_preference( $this->mcm->environment . '_menu_items_items_per_page' ) > -1
			
		){
			
			$ipp = $this->users->get_user_preference( $this->mcm->environment . '_menu_items_items_per_page' );
			
		}
		
		if ( $ipp < -1 ){
			
			$ipp = -1;
			
			if ( isset( $post[ 'ipp' ] ) ) {
				
				$post[ 'ipp' ] = $ipp;
				
			}
			
		}
		
		if ( $ipp == -1 ){
			
			$ipp = $this->mcm->filtered_system_params[ $this->mcm->environment . '_items_per_page' ];
			
		}
		
		// Parsing vars ------------------------------------
		// -------------------------------------------------
		
		// atualizando informações do componente atual
		$this->component_function = __FUNCTION__;
		$this->component_function_action = $action;
		
		// admin url
		$a_url = get_url( $this->environment . $this->uri->ruri_string() );
		
		$data[ 'type' ] = $type;
		
		if ( isset( $menu_type_id ) ) {
			
			$data[ 'menu_type_id' ] = $menu_type_id;
			
		}
		
		$data[ 'menu_types' ] = $this->menus->get_menu_types();
		
		if ( ! check_var( $data[ 'menu_types' ] ) ) {
			
			msg( 'no_menu_types_add_one_first', 'error' );
			redirect( 'admin/menus/menu_types_management/menu_types_list' );
			
		}
		
		if ( $action ){
			
			/*
			 ********************************************************
			 --------------------------------------------------------
			 List
			 --------------------------------------------------------
			 */
			
			if ( $action == 'l' OR $action === 's' ){
				
				$this->load->helper( array( 'pagination' ) );
				
				// -------------------------------------------------
				// Columns ordering --------------------------------
				
				if ( ! ( ( $order_by_direction = $this->users->get_user_preference( 'menu_items_order_by_direction' ) ) != FALSE ) ){
					
					$order_by_direction = 'ASC';
					
				}
				
				// order by complement
				$comp_ob = '';
				
				if ( ( $order_by = $this->users->get_user_preference( 'menu_items_order_by' ) ) != FALSE ){
					
					$data[ 'order_by' ] = $order_by;
					
					switch ( $order_by ) {
						
						case 'id':
							
							$order_by = 't1.id';
							break;
							
						case 'title':
							
							$order_by = 't1.title';
							break;
							
						case 'alias':
							
							$order_by = 't1.alias';
							break;
							
						case 'ordering':
							
							$order_by = 't1.parent';
							$comp_ob = ', t1.ordering ' . $order_by_direction . ', t1.title ' . $order_by_direction;
							break;
							
						case 'status':
							
							$order_by = 't1.status';
							$comp_ob = ', t1.parent ' . $order_by_direction . ', t1.ordering ' . $order_by_direction . ', t1.title ' . $order_by_direction;
							break;
							
						default:
							
							$order_by = 't1.id';
							$data[ 'order_by' ] = 'id';
							
							break;
							
					}
					
				}
				else{
					
					$order_by = 't1.id';
					$data[ 'order_by' ] = 'id';
					
				}
				
				$data[ 'order_by_direction' ] = $order_by_direction;
				
				$order_by = $order_by . ' ' . $order_by_direction . $comp_ob;
				
				// Columns ordering --------------------------------
				// -------------------------------------------------
				
				// -------------------------------------------------
				// Filtering ---------------------------------------
				
				// menu type
				$filter_by_menu_type = $this->users->get_user_preference( 'menus_filter_by_menu_type' );
				
				if ( isset( $post[ 'menus_filter_by_menu_type' ] ) AND isset( $post[ 'submit_filter_by_menu_type' ] ) ){
					
					$filter_by_menu_type = $post[ 'menus_filter_by_menu_type' ];
					
					// setting the user preference
					$this->users->set_user_preferences( array( 'menus_filter_by_menu_type' => $filter_by_menu_type ) );
					
					redirect( $this->menus->get_mi_url( ( $action == 'l' ? 'list' : 'search' ), array(
						
						'menu_type_id' => $filter_by_menu_type,
						'ipp' => $ipp,
						'cp' => 1,
						
					) ) );
					
					$menu_type_id = $filter_by_menu_type;
					
				}
				
				// -------------
				
				// items per page filtering
				if ( isset( $post[ 'ipp' ] ) AND isset( $post[ 'submit_change_ipp' ] ) ){
					
					// setting the user preference
					$this->users->set_user_preferences( array( $this->mcm->environment . '_menu_items_items_per_page' => $post[ 'ipp' ] ) );
					
					redirect( $this->menus->get_mi_url( ( $action == 'l' ? 'list' : 'search' ), array(
						
						'menu_type_id' => $menu_type_id,
						'ipp' => $post[ 'ipp' ],
						'cp' => 1,
						
					) ) );
					
				}
				
				// Filtering ---------------------------------------
				// -------------------------------------------------
				
				// -------------------------------------------------
				// List / Search -----------------------------------
				
				$terms = trim( $this->input->post( 'terms', TRUE ) ? $this->input->post( 'terms', TRUE ) : ( ( $this->input->get( 'q' ) != FALSE ) ? $this->input->get( 'q' ) : FALSE ) );
				
				$search_config = array(
					
					'plugins' => 'menu_items_search',
					'ipp' => $ipp,
					'cp' => $cp,
					'terms' => $terms,
					'allow_empty_terms' => ( $action === 's' ? FALSE : TRUE ),
					'order_by' => array( // deve-se enviar um array associativo, onde cada chave deve ser so nome do plugin, order by não pode ser usado globalmente, apenas por plugin
						
						'menu_items_search' => $order_by, // pode ser um array
						
					),
					'plugins_params' => array(
						
						'menu_items_search' => array(
							
							'menu_type_id' => $menu_type_id,
							
						),
						
					),
					
				);
				
				$this->load->library( 'search', $search_config );
				
				$menu_items = $this->search->get_full_results( 'menu_items_search' );
				
				// List / Search -----------------------------------
				// -------------------------------------------------
				
				// -------------------------------------------------
				// Pagination --------------------------------------
				
				// get menu item url params
				$gmiup = array(
					
					'ipp' => $ipp,
					'cp' => $cp,
					'get' => $this->input->get(),
					'menu_type_id' => $menu_type_id,
					'return' => 'template',
					'template_fields' => array(
						
						'ipp' => '%ipp%',
						'cp' => '%p%',
						
					),
					
				);
				
				if ( $terms ) {
					
					$gmiup[ 'get' ][ 'q' ] = $terms;
					
				}
				
				$pagination_url = $this->menus->get_mi_url( ( $action === 's' ? 'search' : 'list' ), $gmiup );
				
				// Pagination --------------------------------------
				// -------------------------------------------------
				
				// -------------------------------------------------
				// Last url ----------------------------------------
				
				// setting up the last url
				unset( $gmiup[ 'return' ] );
				unset( $gmiup[ 'template_fields' ] );
				set_last_url( $this->menus->get_mi_url( ( $action === 's' ? 'search' : 'list' ), $gmiup ) );
				
				// Last url ----------------------------------------
				// -------------------------------------------------
				
				// -------------------------------------------------
				// Load page ---------------------------------------
				
				$data[ 'ipp' ] = $ipp;
				$data[ 'menu_items' ] = $menu_items;
				$data[ 'pagination' ] = get_pagination( $pagination_url, $cp, $ipp, $this->search->count_all_results( 'menu_items_search' ) );
				$data[ 'filter_by_menu_type' ] = $filter_by_menu_type;
				$data[ 'menu_type' ] = $this->menus->get_menu_type( $menu_type_id );
				$data[ 'menu_type_id' ] = $menu_type_id;
				
				$this->_page(
					
					array(
						
						'component_view_folder' => $this->component_view_folder,
						'function' => 'menu_items_management',
						'action' => 'menu_items_list',
						'layout' => 'default',
						'view' => 'menu_items_list',
						'data' => $data,
						
					)
					
				);
				
				// Load page ---------------------------------------
				// -------------------------------------------------
				
			}
			
			/*
			 --------------------------------------------------------
			 List
			 --------------------------------------------------------
			 ********************************************************
			 */
			
			/*
			 ********************************************************
			 --------------------------------------------------------
			 Change order by
			 --------------------------------------------------------
			 */
			
			else if ( ( $action == 'cob' ) AND $order_by ){
				
				$this->users->set_user_preferences( array( 'menu_items_order_by' => $order_by ) );
				
				if ( ( $order_by_direction = $this->users->get_user_preference( 'menu_items_order_by_direction' ) ) != FALSE ){
					
					switch ( $order_by_direction ) {
						
						case 'ASC':
							
							$order_by_direction = 'DESC';
							break;
							
						case 'DESC':
						
							$order_by_direction = 'ASC';
							break;
							
					}
					
					$this->users->set_user_preferences( array( 'menu_items_order_by_direction' => $order_by_direction ) );
					
				}
				else {
					
					$this->users->set_user_preferences( array( 'menu_items_order_by_direction' => 'ASC' ) );
					
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
			 Set home page
			 --------------------------------------------------------
			 */
			
			else if ( ( $action == 'shp' ) AND $menu_item_id ){
				
				if ( $this->menus_model->set_home_page( $menu_item_id ) ){
					
					msg( ( 'menu_item_set_as_home_page' ), 'success' );
					redirect_last_url();
					
				}
				
			}
			
			/*
			 --------------------------------------------------------
			 Set home page
			 --------------------------------------------------------
			 ********************************************************
			 */
			
			/*
			 ********************************************************
			 --------------------------------------------------------
			 Set status
			 --------------------------------------------------------
			 */
			
			else if ( ( $action == 'ss' ) AND $menu_item_id AND $sub_action ) {
				
				if ( $this->menus->menu_item_status( $menu_item_id, $sub_action ) ) {
					
					msg( ( 'menu_item_' . ( $sub_action == 'p' ? 'published' : ( $sub_action == 'u' ? 'unpublished' : 'archived' ) ) ), 'success' );
					redirect_last_url();
					
				}
				
			}
			
			/*
			 --------------------------------------------------------
			 Set status
			 --------------------------------------------------------
			 ********************************************************
			 */
			
			/*
			 ********************************************************
			 --------------------------------------------------------
			 Change ordering
			 --------------------------------------------------------
			 */
			
			else if ( ( $action == 'co' ) ){
				
				$menu_item_id = $menu_item_id ? ( int ) $menu_item_id : ( $this->input->post( 'menu_item_id' ) ? ( int ) $this->input->post( 'menu_item_id' ) : FALSE );
				$set_up = $sub_action == 'u' ? TRUE : ( $this->input->post( 'submit_up_ordering' ) ? TRUE : FALSE );
				$set_down = $sub_action == 'd' ? TRUE : ( $this->input->post( 'submit_down_ordering' ) ? TRUE : FALSE );
				$set_custom_ordering = $this->input->post( 'ordering' ) ? ( string ) ( ( int ) $this->input->post( 'ordering' ) ) : FALSE;
				
				if ( $menu_item_id ){
					
					if ( $set_up ){
						
						$this->menus->up_menu_item_ordering( $menu_item_id );
						msg( ( 'menu_item_order_changed' ), 'success' );
						redirect_last_url();
						
					}
					else if ( $set_down ){
						
						$this->menus->down_menu_item_ordering( $menu_item_id );
						msg( ( 'menu_item_order_changed' ), 'success' );
						redirect_last_url();
						
					}
					else if ( $set_custom_ordering AND ( $article = $this->menus->get_menu_item( $menu_item_id ) ) ){
						
						if ( $this->menus->set_menu_item_ordering( $menu_item_id, ( int ) $set_custom_ordering ) ){
							
							msg( ( 'menu_item_order_changed' ), 'success' );
							redirect_last_url();
							
						}
						
					}
			
					msg( ( 'nothing_changed' ), 'info' );
					redirect_last_url();
					
				}
				else {
					
					msg( ( 'no_menu_item_id_informed' ), 'error' );
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
			 Add / edit menu item
			 --------------------------------------------------------
			 */
			
			else if ( $action == 'a' OR $action == 'e' ){
				
				$this->load->helper( 'menus' );
				
				$menu_item = array();
				
				// verificamos se a ação é de edição ou adição
				// se for adição, desatribuímos a variável $menu_item_id ( se esta estiver definida ), pois esta não é necessária nesta ação
				if ( $menu_item_id ){
					
					if ( $menu_item = $this->menus->get_menu_item( $menu_item_id ) ){
						
						$data[ 'menu_item' ] = & $menu_item;
						$data[ 'params' ] = & $menu_item[ 'params' ];
						$menu_item[ 'params' ] = get_params( $menu_item[ 'params' ] );
						
					}
					else {
						
						show_404();
						
					}
					
				}
				
				if ( $sub_action == 'smit' ){
					
					$this->db->select('
						
						t1.*,
						
						t2.title as component_title,
						t2.unique_name as component_alias,
						
					');
					
					$this->db->from('tb_menu_items_types t1');
					$this->db->join('tb_components t2', 't1.component_id = t2.id', 'left');
					$menu_items_types = $this->db->get()->result_array();
					
					foreach ( $menu_items_types as $key => & $menu_items_type ) {
						
						if ( $menu_items_type[ 'type' ] == 'component' ){
							
							// get menu item url params
							$gmiup = array(
								
								'menu_type_id' => $menu_type_id ? $menu_type_id : NULL,
								'menu_item_id' => $menu_item_id ? $menu_item_id : NULL,
								'alias' => $action == 'e' ? 'edit' : 'add',
								'menu_item_type' => $menu_items_type[ 'type' ],
								'component_id' => $menu_items_type[ 'component_id' ],
								'component_item' => $menu_items_type[ 'component_item' ],
								
							);
							
							if ( $action == 'e' ){
								
								$assoc_to_uri_array[ 'miid' ] = $menu_item_id;
								
							}
							
							$menu_items_type[ 'link' ] = $this->menus->get_mi_url( $action == 'e' ? 'edit' : 'add', $gmiup );
							
						}
						else if ( $menu_items_type[ 'type' ] == 'blank_content' ){
							
							$assoc_to_uri_array = array(
								
								'mtid' => $menu_type_id,
								'a' => $action,
								't' => $menu_items_type[ 'type' ],
								
							);
							
							if ( $action == 'e' ){
								
								$assoc_to_uri_array[ 'miid' ] = $menu_item_id;
								
							}
							
							$menu_items_type[ 'link' ] = $this->uri->assoc_to_uri( $assoc_to_uri_array );
							
							$menu_items_type[ 'link' ] = 'admin/' . $this->component_name . '/' . __FUNCTION__ . '/' . $menu_items_type[ 'link' ];
							
						}
						else if ( $menu_items_type[ 'type' ] == 'html_content' ){
							
							$assoc_to_uri_array = array(
								
								'mtid' => $menu_type_id,
								'a' => $action,
								't' => $menu_items_type[ 'type' ],
								
							);
							
							if ( $action == 'e' ){
								
								$assoc_to_uri_array[ 'miid' ] = $menu_item_id;
								
							}
							
							$menu_items_type[ 'link' ] = $this->uri->assoc_to_uri( $assoc_to_uri_array );
							
							$menu_items_type[ 'link' ] = 'admin/' . $this->component_name . '/' . __FUNCTION__ . '/' . $menu_items_type[ 'link' ];
							
						}
						else if ( $menu_items_type[ 'type' ] == 'external_link' ){
							
							$assoc_to_uri_array = array(
								
								'mtid' => $menu_type_id,
								'a' => $action,
								't' => $menu_items_type[ 'type' ],
								
							);
							
							if ( $action == 'e' ){
								
								$assoc_to_uri_array[ 'miid' ] = $menu_item_id;
								
							}
							
							$menu_items_type[ 'link' ] = $this->uri->assoc_to_uri( $assoc_to_uri_array );
							
							$menu_items_type[ 'link' ] = 'admin/' . $this->component_name . '/' . __FUNCTION__ . '/' . $menu_items_type[ 'link' ];
							
						}
						
					}
					
					$menu_items_types = array_menu_to_array_tree( $menu_items_types, 'id', 'parent' );
					
					$data[ 'menu_items_types' ] = ul_menu( $menu_items_types );
					
					$this->_page(
						
						array(
							
							'component_view_folder' => $this->component_view_folder,
							'function' => 'menu_items_management',
							'action' => 'menu_item_form',
							'layout' => 'default',
							'view' => 'select_menu_item_type',
							'data' => $data,
							
						)
						
					);
					
					//echo ul_menu( $menu_items );
					
				}
				
				// se o tipo do item de menu estiver definido, quer dizer que o tipo de item de menu foi escolhido, logo, podemos prosseguir para o formulário
				else if ( $type ){
					
					$params_values = array();
					
					if ( $action == 'e' ){
						
						$db_params_values = get_params( $menu_item[ 'params' ] );
						
					}
					
					$get_params_values = $this->input->get( 'params' );
					$post_params_values = $this->input->post( 'params' );
					
					$params_values = array_merge_recursive_distinct( $db_params_values, $get_params_values );
					$params_values = array_merge_recursive_distinct( $params_values, $post_params_values );
					
					$data[ 'menu_items' ] = $this->menus->get_menu_items_tree();
					$data[ 'users' ] = $this->users->get_users_checking_privileges()->result_array();
					//$data[ 'users_groups' ] = $this->users->get_accessible_users_groups( $this->users->user_data[ 'id' ] );
					$data[ 'users_groups' ] = $this->users->get_users_groups_tree( 0, 0, 'list' );
					
					// se o tipo for component, então preparamos / obtemos os parâmetros necessários
					if ( $type == 'component' AND $component_id ){
						
						// obtendo o componente relacionado
						foreach ( $this->mcm->components as $key => $component ) {
							
							if ( $component[ 'id' ] == $component_id ){
								
								$target_component = $component;
								
								break;
								
							}
							
						}
						
						$data[ 'target_component' ] = $target_component;
						$data[ 'component_item' ] = $component_item;
						
						// carregando o model do componente
						$this->load->model( 'admin/' . $target_component[ 'unique_name' ] . '_model' );
						
						/******************************/
						/********* Parâmetros *********/
						
						/********* Componente *********/
						
						// Registrando o item do componente para identificação
						$this->menu_item_component_item = 'menu_item_' . $component_item;
						
						// Aplicando um array padrão para as especificações dos parâmetros do menu
						$menu_params_spec = array();
						
						if ( $action == 'e' ){
							
							$menu_item[ 'params' ] = & $params_values;
							
							// obtendo as especificações dos parâmetros do menu
							$menu_params_spec = $this->menus_model->get_menu_item_params( $menu_item );
							
						}
						
						// obtendo as especificações dos parâmetros do componente
						$comp_params_spec = $this->{ $target_component[ 'unique_name' ] . '_model' }->{ 'menu_item_' . $component_item }( $menu_item, $menu_params_spec );
						
						$params_spec = array_merge_recursive_distinct( $menu_params_spec, $comp_params_spec );
						
						if ( $action == 'a' ){
							
							$params_values = check_var( $params_spec[ 'params_spec_values' ] ) ? $params_spec[ 'params_spec_values' ] : array();
							
						}
						
// 						echo '<strong>$params_values:</strong><pre>' . print_r( $params_values, TRUE ) . '</pre>';EXIT;
						
						foreach( $params_values as $k => $item ) {
							
							$new_values = array();
							
							if ( is_array( $item ) ) {
								
								$new_values = _resolve_array_param_value( $k, $item );
								
								unset( $params_values[ $k ] );
								
							}
							
							$params_values = $params_values + $new_values;
							
						}
						
// 						echo '<strong>$params_values:</strong><pre>' . print_r( $params_values, TRUE ) . '</pre>';EXIT;
						
						$data[ 'params_spec' ] = & $params_spec;
						$data[ 'params_values' ] = & $params_values;
						
						// definindo as regras de validação
						set_params_validations( $params_spec[ 'params_spec' ] );
						
						/********* Componente *********/
						
						/********* Parâmetros *********/
						/******************************/
						
// 						echo '<strong>$post:</strong><pre>' . print_r( $this->input->post( 'params' ), TRUE ) . '</pre>';EXIT;
						
						$data[ 'menu_item_link_disabled' ] = TRUE;
						
					}
					else if ( $type == 'blank_content' ) {
						
						$data[ 'menu_item_link_disabled' ] = TRUE;
						
					}
					else if ( $type == 'html_content' ) {
						
						$data[ 'menu_item_link_disabled' ] = TRUE;
						
						$menu_item[ 'html_content' ] = array();
						
					}
					else if ( $type == 'external_link' ) {
						
						$data[ 'menu_item_link_disabled' ] = FALSE;
						
					}
					
					// ajustando as permissões
					if ( $action == 'e' ){
						
						if ( $menu_item[ 'access_type' ] === 'users' ){
							
							$menu_item[ 'access_user_id' ] = explode( '|', $menu_item[ 'access_ids' ] );
							
						}
						else if ( $menu_item[ 'access_type' ] === 'users_groups' ){
							
							$menu_item[ 'access_user_group_id' ] = explode( '|', $menu_item[ 'access_ids' ] );
							
						}
						
						if ( $type == 'html_content' ) {
							
							$menu_item[ 'html_content' ] = check_var( $menu_item[ 'params' ][ 'html_content' ] ) ? $menu_item[ 'params' ][ 'html_content' ] : '';
							
						}
						
					}
					
					//validação dos campos
					$this->form_validation->set_rules( 'title', lang( 'title' ), 'trim|required' );
					$this->form_validation->set_rules( 'alias', lang( 'alias' ), 'trim' );
					$this->form_validation->set_rules( 'status', lang('status' ), 'trim|required|integer' );
					$this->form_validation->set_rules( 'parent', lang( 'parent' ), 'trim|required|integer' );
					$this->form_validation->set_rules( 'description', lang( 'description' ), 'trim' );
					
					// se o tipo de nível de acesso for para usuários específicos, aplicamos a validação pertinente
					if ( $this->input->post( 'access_type' ) == 'users' ){
						
						$this->form_validation->set_rules('access_user_id[]',lang('access_user'),'trim|required');
						
					}
					// caso contrário, se o tipo de nível de acesso for para grupos de usuários específicos, aplicamos a validação pertinente
					else if ( $this->input->post( 'access_type' ) == 'users_groups' ){
						
						$this->form_validation->set_rules( 'access_user_group_id[]', lang( 'access_user_group' ), 'trim|required' );
						
					}
					
					if($this->input->post('submit_cancel')){
						
						redirect_last_url();
						
					}
					// se a validação dos campos for positiva
					else if ( $this->form_validation->run() AND ( $this->input->post( 'submit' ) OR $this->input->post( 'submit_apply' ) ) ){
						
						if ( $action == 'e' AND $menu_item[ 'home' ] == 1 ){
							
							$this->menus_model->set_home_page( $menu_item[ 'id' ] );
							
						}
						
						$db_data = elements( array(
							
							'alias',
							'title',
							'description',
							'status',
							'parent',
							'component_id',
							'component_item',
							'menu_type_id',
							'access_type',
							'params',
							
						), $this->input->post( NULL, TRUE ) );
						
						if ( $type == 'component' ){
							
							$db_data[ 'type' ] = 'component';
							$db_data[ 'component_item' ] = $component_item;
							
						}
						else if ( $type == 'blank_content' ){
							
							$db_data[ 'type' ] = 'blank_content';
							
						}
						else if ( $type == 'html_content' ){
							
							$db_data[ 'type' ] = 'html_content';
							$db_data[ 'params' ][ 'html_content' ] = $this->input->post( 'html_content' );
							
						}
						else if ( $type == 'external_link' ){
							
							$db_data[ 'type' ] = 'external_link';
							$db_data[ 'link' ] = $this->input->post( 'link' );
							
						}
						
						if ( $db_data[ 'access_type' ] == 'users'){
							
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
						
						$data_for_component_get_link = get_params( $db_data['params'] );
						
						if ( $action == 'a' ){
							
							$return_id = $this->menus_model->insert( $db_data );
							
							if ( $return_id ){
								
								if ( $type == 'component' ){
									
									$db_data[ 'link' ] = $this->{ $target_component[ 'unique_name' ] . '_model'}->{'menu_item_get_link_' . $component_item }( $return_id, $data_for_component_get_link );
									$this->menus_model->update( $db_data, 'id = ' . $return_id );
									
								}
								else if ( $type == 'html_content' ){
									
									$db_data[ 'link' ] = $this->menus_model->get_link_html_content( $return_id );
									$this->menus_model->update( $db_data, 'id = ' . $return_id );
									
								}
								else if ( $type == 'blank_content' ){
									
									$db_data[ 'link' ] = $this->menus_model->get_link_blank_content( $return_id );
									$this->menus_model->update( $db_data, 'id = ' . $return_id );
									
								}
								
								$this->urls_common_model->update_urls_cache();
								
								msg( ( 'menu_item_created' ),'success' );
								
								if ( $this->input->post( 'submit_apply' ) ){
									
									if ( $type == 'component' ){
										
										$gmiup = array(
											
											'menu_type_id' => $menu_type_id,
											'menu_item_type' => $type,
											'component_id' => $component[ 'id' ],
											'component_item' => $component_item,
											'menu_item_id' => $return_id,
											
										);
										
									}
									else if ( in_array( $type, array( 'blank_content', 'html_content', 'external_link' ) ) ){
										
										$gmiup = array(
											
											'menu_type_id' => $menu_type_id,
											'menu_item_type' => $type,
											'menu_item_id' => $return_id,
											'function' => 'menu_items',
											
										);
										
									}
									
									redirect( $this->menus->get_mi_url( 'edit', $gmiup ) );
									
								}
								else{
									redirect_last_url();
								}
								
							}
							
						}
						else if ( $action == 'e' ){
							
							if ( $type == 'component' ){
								
								$db_data[ 'link' ] = $this->{ $target_component[ 'unique_name' ] . '_model' }->{ 'menu_item_get_link_' . $component_item }( $menu_item_id, $data_for_component_get_link );
								
							}
							else if ( $type == 'html_content' ){
								
								$db_data[ 'link' ] = $this->menus_model->get_link_html_content( $menu_item_id );
								
							}
							else if ( $type == 'blank_content' ){
								
								$db_data[ 'link' ] = $this->menus_model->get_link_blank_content( $menu_item_id );
								
							}
							
							if ( $this->menus_model->update( $db_data, array( 'id' => $menu_item_id ) ) ){
								
								$this->urls_common_model->update_urls_cache();
								
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
							'function' => 'menu_items_management',
							'action' => 'menu_item_form',
							'layout' => 'default',
							'view' => 'menu_item_form',
							'data' => $data,
							
						)
						
					);
					
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
			 Remove menu item
			 --------------------------------------------------------
			 */
			
			else if ( $action == 'r' ){
				
				if ( $menu_item_id AND ( $menu_item = $this->menus->get_menu_item( $menu_item_id ) ) ){
					
					if( $this->input->post( 'submit_cancel' ) ){
						
						redirect_last_url();
						
					}
					else if ($this->input->post('menu_item_id')>0 AND $menu_item AND $this->input->post('submit')){
						if ($this->menus_model->delete(array('id'=>$this->input->post('menu_item_id')))){
							msg(('menu_item_deleted'),'success');
							redirect_last_url();
						}
						else{
							msg($this->lang->line('menu_item_deleted_fail'),'error');
							redirect_last_url();
						}
					}
					else{
						
						$data[ 'menu_item' ] = $menu_item;
						
						$this->_page(
							
							array(
								
								'component_view_folder' => $this->component_view_folder,
								'function' => 'menu_items_management',
								'action' => 'remove_menu_item',
								'layout' => 'default',
								'view' => 'remove_menu_item',
								'data' => $data,
								
							)
							
						);
						
					}
				}
				else{
					
				}
			}
			
			/*
			 --------------------------------------------------------
			 Remove menu item
			 --------------------------------------------------------
			 ********************************************************
			 */
			
			else{
				show_404();
			}
			
		}
		
	}
	
	/*
	 --------------------------------------------------------------------------------------------------
	 Menu items management
	 --------------------------------------------------------------------------------------------------
	 **************************************************************************************************
	 **************************************************************************************************
	 */
	
	// Padrão da montagem das urls: admin/ [componente] / [função] / [ação] / [layout] / [outros parâmetros]
	
	public function menu_types_management( $action = NULL, $menu_type_id = 0 ){
		
		$this->component_function = __FUNCTION__;
		$this->component_function_action = $action;
		
		if ($action){
			
			$url = get_url('admin'.$this->uri->ruri_string());
			
			if ( $action == 'menu_types_list' ){
				
				$menu_types = $this->menus_model->get_menu_types()->result_array();
				
				$data = array(
					'component_name' => $this->component_name,
					'menu_types' => $menu_types,
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
			else if ($action == 'add_menu_type'){
				
				$data = array(
					'component_name' => $this->component_name,
				);
				
				//validação dos campos
				$this->form_validation->set_rules('title',lang('title'),'trim|required');
				$this->form_validation->set_rules('alias',lang('alias'),'trim');
				$this->form_validation->set_rules('description',lang('description'),'trim');
				
				if($this->input->post('submit_cancel')){
					redirect_last_url();
				}
				// se a validação dos campos for positiva
				else if ($this->form_validation->run() AND ($this->input->post('submit') OR $this->input->post('submit_apply'))){
					$insert_data = elements(array(
						'title',
						'alias',
						'description',
					),$this->input->post());
					
					if ($insert_data['alias'] == ''){
						$insert_data['alias'] = url_title($insert_data['title'],'-',TRUE);
					}
					
					$return_id=$this->menus_model->insert_menu_type($insert_data);
					if ($return_id){
						msg(('menu_type_created'),'success');
						$this->menus_model->update($insert_data, 'id = '.$return_id);
						if ($this->input->post('submit_apply')){
							redirect('admin/'.$this->component_name . '/' . __FUNCTION__.'/edit_menu_type/'.$return_id);
						}
						else{
							redirect_last_url();
						}
					}
					
				}
				// caso contrário se a validação dos campos for negativa e mensagens de erro conter strings
				else if (!$this->form_validation->run() AND validation_errors() != ''){
					
					$data['post'] = $this->input->post();
					
					msg(('add_menu_type_fail'),'title');
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
			else if ($action == 'edit_menu_type'){
				
				if ($menu_type_id AND ($menu_type = $this->menus_model->get_menu_type($menu_type_id)->row())){
					
					$data = array(
						'menu_type' => $menu_type,
						'menu_type_id' => $menu_type_id,
						'component_name' => $this->component_name,
					);
					
					//validação dos campos
					$this->form_validation->set_rules('title',lang('title'),'trim|required');
					$this->form_validation->set_rules('alias',lang('alias'),'trim');
					$this->form_validation->set_rules('description',lang('description'),'trim');
					
					if($this->input->post('submit_cancel')){
						redirect_last_url();
					}
					// se a validação dos campos for positiva
					else if ($this->form_validation->run() AND ($this->input->post('submit') OR $this->input->post('submit_apply'))){
						$update_data = elements(array(
							'title',
							'alias',
							'description',
						),$this->input->post());
						
						if ($update_data['alias'] == ''){
							$update_data['alias'] = url_title($update_data['title'],'-',TRUE);
						}
						
						if ($this->menus_model->update_menu_type($update_data, array('id' => $this->input->post('menu_type_id')))){
							msg(('menu_item_updated'),'success');
							if ($this->input->post('submit_apply')){
								redirect(get_url('admin'.$this->uri->ruri_string()));
							}
							else{
								redirect_last_url();
							}
						}
						
					}
					// caso contrário se a validação dos campos for negativa e mensagens de erro conter strings
					else if (!$this->form_validation->run() AND validation_errors() != ''){
						
						$data['post'] = $this->input->post();
						
						msg(('edit_menu_type_fail'),'title');
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
			}
			else if ($action == 'remove_menu_type'){
				
				if ($menu_type_id AND ($menu_type = $this->menus_model->get_menu_type($menu_type_id)->row())){
					if($this->input->post('submit_cancel')){
						redirect_last_url();
					}
					else if ($this->input->post('menu_type_id')>0 AND $menu_type AND $this->input->post('submit')){
						if ($this->menus_model->delete_menu_type(array('id'=>$this->input->post('menu_type_id')))){
							msg(('menu_type_deleted'),'success');
							redirect_last_url();
						}
						else{
							msg($this->lang->line('menu_type_deleted_fail'),'error');
							redirect_last_url();
						}
					}
					else{
						$data=array(
							'menu_type_id' => $menu_type_id,
							'component_name' => $this->component_name,
							'menu_type'=>$menu_type,
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
				else{
					show_404();
				}
			}
			else{
				show_404();
			}
		}
	}
	
}
