<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	// -------------------------------------------------
	// Parsing vars ------------------------------------

	$f_params = $this->uri->ruri_to_assoc();

	$action =								isset( $f_params[ 'a' ] ) ? $f_params[ 'a' ] : 'sfl'; // action
	$sub_action =							isset( $f_params[ 'sa' ] ) ? $f_params[ 'sa' ] : NULL; // sub action
	$submit_form_id =						isset( $f_params[ 'sfid' ] ) ? $f_params[ 'sfid' ] : NULL; // submit form id
	$cp =									isset( $f_params[ 'cp' ] ) ? $f_params[ 'cp' ] : NULL; // current page
	$ipp =									isset( $f_params[ 'ipp' ] ) ? $f_params[ 'ipp' ] : NULL; // items per page
	$ob =									isset( $f_params[ 'ob' ] ) ? $f_params[ 'ob' ] : NULL; // order by
	
	// Parsing vars ------------------------------------
	// -------------------------------------------------

	// atualizando informações do componente atual
	$this->component_function = __FUNCTION__;
	$this->component_function_action = $action;
	
	
	$c_urls = & $this->c_urls;
	
	$data[ 'c_urls' ] = & $c_urls;
	
	// admin url
	$a_url = get_url( 'admin' . $this->uri->ruri_string() );
	
	/*
		********************************************************
		--------------------------------------------------------
		Submit forms list
		--------------------------------------------------------
		*/

	if ( $action == 'sfl' OR $action == 's' ){

		$this->load->library(array('str_utils'));
		$this->load->helper( array( 'pagination' ) );

		/*
			********************************************************
			--------------------------------------------------------
			Ordenção por colunas
			--------------------------------------------------------
			*/
		
		$order_by_direction = $this->users->get_user_preference( 'submit_forms_list_order_by_direction' );

		if ( ! ( $order_by_direction != FALSE AND ( $order_by_direction === 'ASC' OR $order_by_direction === 'DESC' ) ) ){

			$order_by_direction = 'ASC';

		}

		// order by complement
		$comp_ob = '';
		
		if ( ( $order_by = $this->users->get_user_preference( 'submit_forms_list_order_by' ) ) != FALSE ){
			
			$data[ 'order_by' ] = $order_by;

			switch ( $order_by ) {

				case 'id':

					$order_by = 't1.id';
					break;

				case 'alias':

					$order_by = 't1.alias';
					break;

				case 'create_datetime':

					$order_by = 't1.create_datetime';
					break;

				case 'mod_datetime':

					$order_by = 't1.mod_datetime';
					break;

				default:

					$order_by = 't1.title';
					$comp_ob = ', t1.title '. $order_by_direction;
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

			redirect( $data[ 'submit_forms_list_link' ] );

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
					$like_query .= 'OR `t1`.`title` LIKE \'%'.$full_term.'%\' ';
					$like_query .= 'OR `t1`.`target` LIKE \'%"name":"%'.$full_term.'%"%\' ';
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
			
			msg( ( 'search_fail' ), 'title' );
			msg( $errors_msg,'error' );

			redirect( get_last_url() );

		}
		
		$submit_forms = $this->sfcm->get_submit_forms( $gsf_params )->result_array();
		
		foreach ( $submit_forms as $key => & $submit_form ) {
			
			$this->sfcm->parse_sf( $submit_form );
			
			$submit_form_base_link_array = array(
				
				'sfid' => $submit_form[ 'id' ],
				
			);

			$submit_form[ 'edit_link' ] = $c_urls[ 'sf_edit_link' ] . '/' . $this->uri->assoc_to_uri( $submit_form_base_link_array );
			$submit_form[ 'remove_link' ] = $c_urls[ 'sf_remove_link' ] . '/' . $this->uri->assoc_to_uri( $submit_form_base_link_array );
			$submit_form[ 'users_submits_link' ] = $c_urls[ 'us_list_link' ] . '/' . $this->uri->assoc_to_uri( $submit_form_base_link_array );
			$submit_form[ 'users_submits_add_link' ] = $c_urls[ 'us_add_link' ] . '/' . $this->uri->assoc_to_uri( $submit_form_base_link_array );
			$submit_form[ 'change_order_link' ] = $c_urls[ 'sf_change_order_link' ] . '/' . $this->uri->assoc_to_uri( $submit_form_base_link_array );
			$submit_form[ 'up_order_link' ] = $c_urls[ 'sf_up_order_link' ] . '/' . $this->uri->assoc_to_uri( $submit_form_base_link_array );
			$submit_form[ 'down_order_link' ] = $c_urls[ 'sf_down_order_link' ] . '/' . $this->uri->assoc_to_uri( $submit_form_base_link_array );

			if ( ! empty( $terms ) ){

				foreach ( $terms as $term ) {

					$submit_form[ 'id' ] = str_highlight( $submit_form[ 'id' ], $term );
					$submit_form[ 'sef_submit_form' ] = str_highlight( $submit_form[ 'sef_submit_form' ], $term );
					$submit_form[ 'target' ] = str_highlight( $submit_form[ 'target' ], $term );

				}

			}

		}
		
		foreach ( $submit_forms as $key => & $submit_form ) {
			
			$search_config = array(
				
				'plugins' => 'sf_us_search',
				'allow_empty_terms' => TRUE,
				'plugins_params' => array(
					
					'sf_us_search' => array(
						
						'sf_id' => $submit_form[ 'id' ],
						
					),
					
				),
				
			);
			
			$this->load->library( 'search' );
			$this->search->config( $search_config );
			
			$users_submits = $this->search->get_full_results( 'sf_us_search', TRUE );
			
			$submit_form[ 'users_submit_count' ] = $this->search->count_all_results( 'sf_us_search' );
			
		}
		
		$data[ 'submit_forms' ] = $submit_forms;
		
		unset( $gsf_params[ 'order_by' ] );
		unset( $gsf_params[ 'limit' ] );
		unset( $gsf_params[ 'offset' ] );
		
		$gsf_params[ 'return_type' ] = 'count_all_results';
		
		$total_results = $this->sfcm->get_submit_forms( $gsf_params );
		
		$data[ 'pagination' ] = get_pagination(
			
			( ( ! empty( $terms ) ) ? $data[ 'c_urls' ][ 'sf_search_link' ] : $data[ 'c_urls' ][ 'sf_list_link' ] ) . '/cp/%p%/ipp/%ipp%' . $get_query,
			$cp,
			$ipp,
			$total_results
			
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
				'function' => 'submit_forms_management',
				'action' => 'submit_forms_list',
				'layout' => 'default',
				'view' => 'submit_forms_list',
				'data' => $data,

			)

		);

	}

	/*
		--------------------------------------------------------
		Submit forms list
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

		$this->users->set_user_preferences( array( 'submit_forms_list_order_by' => $ob ) );

		if ( ( $order_by_direction = $this->users->get_user_preference( 'submit_forms_list_order_by_direction' ) ) != FALSE ){

			switch ( $order_by_direction ) {

				case 'ASC':

					$order_by_direction = 'DESC';
					break;

				case 'DESC':

					$order_by_direction = 'ASC';
					break;

			}

			$this->users->set_user_preferences( array( 'submit_forms_list_order_by_direction' => $order_by_direction ) );

		}
		else {

			$this->users->set_user_preferences( array( 'submit_forms_list_order_by_direction' => 'ASC' ) );

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
		Add / edit submit form
		--------------------------------------------------------
		*/

	else if ( $action == 'asf' OR $action == 'esf' ){
		
		// Se for adição , criamos o formulário como sendo um array vazio
		
		if ( $action == 'asf' ){
			
			$submit_form = array();
			
		}
		
		// Caso contrário, se for de edição
		
		if ( $action == 'esf' ){
			
			// -------------------------
			// Obtenção do formulário
			
			$submit_form = $this->sfcm->get_submit_form( $submit_form_id )->row_array();
			
			if ( ! $submit_form ) {
				
				show_404();
				
			}
			else {
				
				$this->sfcm->parse_sf( $submit_form );
				
				$submit_form_base_link_array = array(
					
					'sfid' => $submit_form[ 'id' ],
					
				);
				
				$submit_form[ 'edit_link' ] = $c_urls[ 'sf_edit_link' ] . '/' . $this->uri->assoc_to_uri( $submit_form_base_link_array );
				$submit_form[ 'remove_link' ] = $c_urls[ 'sf_remove_link' ] . '/' . $this->uri->assoc_to_uri( $submit_form_base_link_array );
				$submit_form[ 'users_submits_link' ] = $c_urls[ 'us_list_link' ] . '/' . $this->uri->assoc_to_uri( $submit_form_base_link_array );
				$submit_form[ 'users_submits_add_link' ] = $c_urls[ 'us_add_link' ] . '/' . $this->uri->assoc_to_uri( $submit_form_base_link_array );
				$submit_form[ 'change_order_link' ] = $c_urls[ 'sf_change_order_link' ] . '/' . $this->uri->assoc_to_uri( $submit_form_base_link_array );
				$submit_form[ 'up_order_link' ] = $c_urls[ 'sf_up_order_link' ] . '/' . $this->uri->assoc_to_uri( $submit_form_base_link_array );
				$submit_form[ 'down_order_link' ] = $c_urls[ 'sf_down_order_link' ] . '/' . $this->uri->assoc_to_uri( $submit_form_base_link_array );
				
				$data[ 'submit_form' ] = & $submit_form;
				
			}
			
			// Obtenção do formulário
			// -------------------------
			
		}
		
		// ------------------------------------------------------------------------
		// Obtenção de todos os formulários
		
		/*
		Inicialmente, presumimos [somente eu, claro] que a quantidade de formulários (group datas)
		não alcance uma quantidade absurda, portanto acreditamos [acredito]
		que está ok se chamarmos todos os formulários.
		
		Esses formulários são usados para montar o menu na barra de tarefas.
		Talvez seja interessante deixar o usuário decidir, como uma preferência,
		se deseja ver o menu o não, economizando esta chamada.
		Claro que isso não é um problema nas páginas em ajax.
		*/
		
		$data[ 'submit_forms' ] = $this->sfcm->get_submit_forms()->result_array();
		$submit_forms = & $data[ 'submit_forms' ];
		
		foreach( $submit_forms as $key => & $_submit_form ){
			
			$this->sfcm->parse_sf( $_submit_form );
			
		}
		
		// Obtenção de todos os formulários
		// ------------------------------------------------------------------------
		
		// ------------------------------------------------------------------------
		// Verificações de requisições POST
		
		// capturando os dados obtidos via post, e guardando-os na variável $post_data
		$post_data = $this->input->post( NULL, TRUE );
		
		// Se não temos dados POST
		if ( ! $post_data ){
			
			if( $action == 'asf' ){
				
				$submit_form[ 'params' ] = array();
				
			}
			
		}
		else {
			
			$admin_ud_dl_filters_profiles = isset( $submit_form[ 'params' ][ 'admin_ud_dl_filters_profiles' ] ) ? $submit_form[ 'params' ][ 'admin_ud_dl_filters_profiles' ]: array();
			
			$post_data[ 'params' ][ 'admin_ud_dl_filters_profiles' ] = $admin_ud_dl_filters_profiles;
			
			$submit_form[ 'params' ] = isset( $post_data[ 'params' ] ) ? $post_data[ 'params' ] : array();
			
			// ------------------------------------------------------------------------
			// Fields
			
			// Checking for remove field request
			if ( isset( $post_data[ 'submit_remove_field' ] ) ){
				
				foreach ( $post_data[ 'submit_remove_field' ] as $key => $value ) {
					
					unset( $post_data[ 'fields' ][ $key ] );
					
				}
				
			}
			
			// Checking for add field request
			if ( isset( $post_data[ 'submit_add_field' ] ) ){
				
				$field_key = 0;
				
				$field_type = isset( $post_data[ 'field_type_to_add' ] ) ? $post_data[ 'field_type_to_add' ] : 'input_text';
				
				// TODO TODO TODO TODO TODO TODO TODO
				// Fazer uma checagem se o tipo de campo é permitido
				
				// se existem campos de field, obtem o último índice
				if ( isset( $post_data[ 'fields' ] ) ){
					
					end( $post_data[ 'fields' ] );
					$field_key = key( $post_data[ 'fields' ] );
					
				}
				
				for ( $i = $field_key + 1; $i < $field_key + 1 + $post_data[ 'field_fields_to_add' ]; $i++ ) {
					
					// TODO TODO TODO TODO TODO TODO TODO
					// criar uma função para obter os valores padrões de um dado tipo de campo
					
					$post_data[ 'fields' ][ $i ] = array();
					
					// key é a ordem do field na listagem
					$post_data[ 'fields' ][ $i ][ 'key' ] = $i;
					
					$post_data[ 'fields' ][ $i ][ 'field_type' ] = $field_type;
					$post_data[ 'fields' ][ $i ][ 'availability' ][ 'admin' ] = TRUE;
					$post_data[ 'fields' ][ $i ][ 'availability' ][ 'site' ] = TRUE;
					
				}
				
			}
			
			// reordenando os índices dos fields
			$post_data[ 'fields' ] = isset( $post_data[ 'fields' ] ) ? $post_data[ 'fields' ] : array();
			$post_data[ 'fields' ] = array_merge( array( 0 ), $post_data[ 'fields' ] );
			$post_data[ 'fields' ] = array_values( $post_data[ 'fields' ] );
			unset( $post_data[ 'fields' ][ 0 ] );
			
			// Fields
			// ------------------------------------------------------------------------
			
			if ( $this->sfcm->parse_sf( $post_data ) === TRUE ) {
				
				$submit_form = $post_data;
				
			}
			else if ( $errors = $this->sfcm->parse_sf( $post_data ) ) {
				
				msg( ( 'submit_form_error' ),'title' );
				msg( $errors, 'error' );
				
			}
			
		}
		
		// Verificações de requisições POST
		// ------------------------------------------------------------------------
		
		// ------------------------------------------------------------------------
		// Fields
		// Processos pós definição final dos campos (se é a original ou POST)
		
		if ( isset( $submit_form[ 'fields' ] ) AND is_array( $submit_form[ 'fields' ] ) ) {
			
			$temp_array = array();
			
			// TODO TODO TODO TODO TODO TODO TODO
			// Migrar para o plugin tipo ud_gd
			
			// part of articles categories code
			// cache var
			$articles_categories_loaded = FALSE;
			
			foreach ( $submit_form[ 'fields' ] as $key => $field ) {
				
				if ( ! isset( $field[ 'key' ] ) ) { echo '<pre>' . print_r( $field, TRUE ) . '</pre>'; exit; }
				
				$temp_array[ $field[ 'key' ] ] = $field;
				
				// articles categories
				if ( $field[ 'field_type' ] === 'articles' AND ! $articles_categories_loaded ) {
					
					// loading articles main model
					if ( ! $this->load->is_model_loaded( 'articles' ) ) {
						
						$this->load->model( 'articles_mdl', 'articles' );
						
					}
					
					// loading articles categories
					$data[ 'articles_categories' ] = $this->articles->get_categories_tree( array( 'array' => $this->articles->get_categories( array( 'status' => '1' ) ) ) );
					
					// cache for reuse
					$articles_categories_loaded = TRUE;
					
				}
				
			}
			
			$submit_form[ 'fields' ] = $temp_array;
			unset( $temp_array );
			
			ksort( $submit_form[ 'fields' ] );
			
			$post_data[ 'fields' ] = & $submit_form[ 'fields' ];
			
		}
		
		// Fields
		// ------------------------------------------------------------------------
		
		// ------------------------------------------------------------------------
		// Params
		
		if ( $action == 'esf' ){
			
			// obtendo os valores atuais dos parâmetros
			$data[ 'current_params_values' ] = get_params( $submit_form[ 'params' ] );
			
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
			
		}
		else{
			
			$data[ 'current_params_values' ] = array();
			
		}
		
		// obtendo as especificações dos parâmetros
		$data[ 'params_spec' ] = $this->sfcm->get_submit_form_params( $submit_form, $data[ 'current_params_values' ] );
		
		// cruzando os valores padrões das especificações com os do DB
		$data[ 'final_params_values' ] = array_merge( $data[ 'params_spec' ][ 'params_spec_values' ], $data[ 'current_params_values' ] );
		
		// definindo as regras de validação
		set_params_validations( $data[ 'params_spec' ][ 'params_spec' ] );
		
		// Params
		// ------------------------------------------------------------------------
		
		// ------------------------------------------------------------------------
		// Validation
		
		//validação dos campos
		$this->form_validation->set_rules( 'title', lang( 'title' ), 'trim|required' );
		$this->form_validation->set_rules( 'alias', lang( 'alias' ), 'trim' );
		
		// Validation
		// ------------------------------------------------------------------------
		
		$data[ 'submit_form' ] = $submit_form;
		$data[ 'post' ] = & $post_data;
		
		// ------------------------------------------------------------------------
		// UniData plugin submit POST signal
		
		/*
		Definimos aqui uma variável que servirá de permissão para a verficação
		dos outros tipos de POST.
		
		Por ex.: Algum plugin do tipo ud_gd poderá setar
		esta variável para FALSE, impedindo que as próximas rotinas sejam executadas.
		Neste caso, este plugin poderia finalizar a chamada a sua própria maneira
		*/
		
		$this->unid->continue_after_udgdps_on(); // udgdps = UniData plugin submit POST signal
		$this->unid->continue_after_plugins = array();
		
		if ( isset( $post_data[ 'continue_after_udgdps' ] ) ){
			
			/*
			Cada plugin pode setar essa variável como desejar. A função de obtenção do valor desta
			variável ( $this->unid->continue_after_udgdps() ) fará a rotina de verificação completa
			em todos os índices do array, retornando um valor boleano definitivo
			*/
			
			// ud_gd plugin stuff
			
			if ( $this->unid->continue_after_udgdps() ) {
				
				$continue_after_udgdps = FALSE;
				
			}
			
		}
		
		// UniData plugin submit POST signal
		// ------------------------------------------------------------------------
		
		if ( $this->unid->continue_after_udgdps() ) {
			
			if ( ( isset( $post_data[ 'submit_add_field' ] ) OR isset( $post_data[ 'submit_remove_field' ] ) ) AND ! isset( $post_data[ 'submit_apply' ] ) ){
				
				if ( isset( $post_data[ 'submit_add_field' ] ) ){
					
					msg( ( 'notification_success_add_field' ), 'success' );
					
				}
				
				if ( isset( $post_data[ 'submit_remove_field' ] ) ){
					
					msg( ( 'notification_success_remove_field' ), 'success' );
					
				}
				
			}
			else if ( $post_data AND in_array( 'submit_cancel', $post_data ) ){
				
				redirect_last_url();
				
			}
			// se a validação dos campos for positiva
			else if ( $this->form_validation->run() AND ( isset( $post_data[ 'submit_apply' ] ) OR isset( $post_data[ 'submit' ] ) ) ){
				
				if ( isset( $post_data[ 'submit_add_field' ] ) ){
					
					msg( ( 'notification_success_add_field' ), 'success' );
					
				}
				
				// convertendo os arrays de campos dinâmicos em json para inserção no db
				$post_data[ 'fields' ] = json_encode( $post_data[ 'fields' ] );
				$post_data[ 'params' ] = json_encode( $post_data[ 'params' ] );
				
				$db_data = elements( array(
					
					'alias',
					'title',
					'fields',
					'params',
					
				), $post_data );
				
				$modified_date_time = gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] );
				$modified_date_time = strftime( '%Y-%m-%d %T', $modified_date_time );
				
				$db_data[ 'mod_datetime' ] = $modified_date_time;

				if ( $db_data[ 'alias' ] == '' ){
					
					$db_data[ 'alias' ] = url_title( $db_data[ 'title' ], '-', TRUE );
					
				}
				
				if ( $action == 'asf' ){

					$db_data[ 'create_datetime' ] = $modified_date_time;

					$return_id = $this->sfcm->insert( $db_data );

					if ( $return_id ){

						msg(('submit_form_created'),'success');

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
					
					if ( $this->sfcm->update( $db_data, array( 'id' => $submit_form_id ) ) ){

						msg( ( 'submit_form_updated' ), 'success' );

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

				msg( ( 'add_submit_form_fail' ),'title' );
				msg( validation_errors( '<div class="error">', '</div>' ), 'error' );

			}
			
			$this->_page(
				
				array(
					
					'component_view_folder' => $this->component_view_folder,
					'function' => 'submit_forms_management',
					'action' => 'submit_form_form',
					'layout' => 'default',
					'view' => 'submit_form_form',
					'data' => $data,

				)

			);

		}
		
		unset( $continue_after_udgdps );
		
	}

	/*
		--------------------------------------------------------
		Add / edit submit form
		--------------------------------------------------------
		********************************************************
		*/

	/*
		********************************************************
		--------------------------------------------------------
		Remove submit form
		--------------------------------------------------------
		*/

	else if ( $action == 'rsf' ){

		if ( $submit_form_id AND ( $submit_form = $this->sfcm->get_submit_form( $submit_form_id )->row_array() ) ){

			if( $this->input->post( 'submit_cancel' ) ){

				redirect_last_url();

			}
			else if ( $this->input->post( 'submit' ) ){

				if ( $this->sfcm->delete( array( 'id'=>$submit_form_id ) ) ){

					msg( 'submit_form_deleted', 'success');
					redirect_last_url();

				}
				else{

					msg($this->lang->line( 'url_deleted_fail' ), 'error' );
					redirect_last_url();

				}

			}
			else{

				$data[ 'submit_form' ] = $submit_form;

				$this->_page(

					array(

						'component_view_folder' => $this->component_view_folder,
						'function' => 'submit_forms_management',
						'action' => 'remove_submit_form',
						'layout' => 'default',
						'view' => 'remove_submit_form',
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
		Remove submit form
		--------------------------------------------------------
		********************************************************
		*/

	/*
		********************************************************
		--------------------------------------------------------
		Remove all
		--------------------------------------------------------
		*/

	else if ( $action == 'ra' ){


		if ( $this->sfcm->delete_all() ){

			msg( 'all_submit_forms_deleted', 'success');
			redirect_last_url();

		}
		else{

			msg($this->lang->line( 'all_submit_forms_deleted_fail' ), 'error' );
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
