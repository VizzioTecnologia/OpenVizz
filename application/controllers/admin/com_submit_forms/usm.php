<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	$get = $this->input->get( NULL, TRUE );
	$post = $this->input->post( NULL, TRUE );
	
	$data[ 'get' ] = & $get;
	$data[ 'post' ] = & $post;
	
	// -------------------------------------------------
	// Parsing vars ------------------------------------
	
	$f_params = is_array( $f_params ) ? $f_params : $this->uri->ruri_to_assoc();
	
	$action =								isset( $f_params[ 'a' ] ) ? $f_params[ 'a' ] : 'usl'; // action
	$sub_action =							isset( $f_params[ 'sa' ] ) ? $f_params[ 'sa' ] : NULL; // sub action
	$submit_form_id =						( isset( $f_params[ 'sfid' ] ) AND $f_params[ 'sfid' ] AND is_numeric( $f_params[ 'sfid' ] ) AND $f_params[ 'sfid' ] > 0 ) ? $f_params[ 'sfid' ] : NULL; // submit form id
	$user_submit_id =						( isset( $f_params[ 'usid' ] ) AND $f_params[ 'usid' ] AND is_numeric( $f_params[ 'usid' ] ) AND $f_params[ 'usid' ] > 0 ) ? $f_params[ 'usid' ] : NULL; // user submit id
	$ob =									isset( $f_params[ 'ob' ] ) ? $f_params[ 'ob' ] : NULL; // order by
	$f =									isset( $f_params[ 'f' ] ) ? json_decode( base64_decode( urldecode( $f_params[ 'f' ] ) ), TRUE ) : array(); // filters
	$sfsp =									isset( $f_params[ 'sfsp' ] ) ? json_decode( base64_decode( urldecode( $f_params[ 'sfsp' ] ) ), TRUE ) : array(); // search filters
	
	$cp =									isset( $f_params[ 'cp' ] ) ? ( int ) $f_params[ 'cp' ] : NULL; // current page
		$cp =								( $cp < 1 ) ? 1 : $cp;
	$ipp =									isset( $f_params[ 'ipp' ] ) ? ( int ) $f_params[ 'ipp' ] : NULL; // items per page
		$ipp =								isset( $post[ 'ipp' ] ) ? ( int ) $post[ 'ipp' ] : $ipp; // items per page
	
	// Parsing vars ------------------------------------
	// -------------------------------------------------
	
	// atualizando informações do componente atual
	$this->component_function = 'usm';
	$this->component_function_action = $action;
	

	$base_link_prefix = 'admin/' . $this->component_name . '/' . $this->component_function . '/';

	$base_link_array = array(

	);

	$add_link_array = $base_link_array + array(

		'a' => 'aus',

	);

	$submit_forms_list_link_array = $base_link_array + array(

		'a' => 'sfl',

	);
	$users_submits_list_link_array = $base_link_array + array(

		'a' => 'usl',

	);

	$search_link_array = $base_link_array + array(

		'a' => 's',

	);

	$delete_all_link_array = $base_link_array + array(

		'a' => 'ra',

	);

	$data[ 'add_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $add_link_array );
	$data[ 'submit_forms_list_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $submit_forms_list_link_array );
	$data[ 'users_submits_list_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $users_submits_list_link_array );
	$data[ 'search_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $search_link_array );
	$data[ 'delete_all_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $delete_all_link_array );

	$c_urls = & $this->c_urls;

	$data[ 'c_urls' ] = & $c_urls;

	// admin url
	$a_url = get_url( $this->environment . $this->uri->ruri_string() );

	if ( $submit_form_id ){

		$data[ 'submit_form_id' ] = $submit_form_id;

	}

	//print_r($post);
	if ( check_var( $post[ 'submit_export' ] ) ){

		if ( check_var( $post[ 'selected_users_submits_ids' ] ) ){

			// export params
			$ep = array(

				'a' => 'usl',
				'usid' => $post[ 'selected_users_submits_ids' ],
				'ct' => $post[ 'submit_export' ],
				'sa' => 'dl',

			);

			$this->export( $ep );

		}
		else {

			msg( ( 'users_submits_export_error' ),'title' );
			msg( ( 'no_users_submits_selected' ), 'error' );
			msg( ( 'select_submissions_to_export' ), 'info' );

			redirect_last_url();

		}

	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/*
		********************************************************
		--------------------------------------------------------
		Users submits list
		--------------------------------------------------------
		*/
	else if ( $action == 'usl' OR $action == 's' ){
		
		$this->load->library( array( 'str_utils', 'search', ) );
		$this->load->helper( array( 'pagination' ) );
		
		$search_config = array(
			
			'plugins' => 'sf_us_search',
			'allow_empty_terms' => TRUE,
			
		);
		
		echo 'usl: <pre>' . print_r( $f_params, TRUE ) . '</pre>';
		
		// se o id do formulário foi informado, iremos listar as submissões daquele formulário, e com as suas colunas
		if ( $submit_form = $this->sfcm->get_submit_form( $submit_form_id )->row_array() ){
			
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
			
			$search_config[ 'plugins_params' ][ 'sf_us_search' ][ 'sf_id' ] = $submit_form_id;
			
			/*
			********************************************************
			--------------------------------------------------------
			Colunas a serem exibidas
			--------------------------------------------------------
			*/
			
			// ok, o trabalho com as colunas vai ser o seguinte:
			// primeiro, se é a primeira vez que o usuário acessa a listagem
			// devemos apresentar as colunas baseado em sua importância
			// e essa importância, quem vai definir em primeira instância, é o sistema
			
			// verificamos se existe o array com as colunas nos parâmetros filtrados
			// se não existir, é aqui que devemos criar
			
			$cols_equals = FALSE;
			
			$__filtered_params = $this->mcm->filtered_system_params[ 'admin_submit_form_users_submits_columns' ];
			
			if ( isset( $__filtered_params ) AND is_array( $__filtered_params ) ){
				
				$__sf_params = isset( $this->mcm->filtered_system_params[ 'admin_submit_form_users_submits_columns' ][ 'submit_form_' . $submit_form_id ] ) ? get_params( $this->mcm->filtered_system_params[ 'admin_submit_form_users_submits_columns' ][ 'submit_form_' . $submit_form_id ] ) : array();
				
				$this->mcm->filtered_system_params[ 'admin_submit_form_users_submits_columns' ][ 'submit_form_' . $submit_form_id ] = $__sf_params;
				$user_sf_us_columns = & $this->mcm->filtered_system_params[ 'admin_submit_form_users_submits_columns' ][ 'submit_form_' . $submit_form_id ];
				
				// comparando as colunas salvas nas preferências do usuário, com as atuais
				// esta comparação deve ser rápida e simples, pois será executada sempre
				
				$columns = array(
					
					array(
						
						'alias' => 'id',
						'title' => lang( 'id' ),
						'visible' => TRUE,
						'type' => 'built_in',
						
					),
					array(
						
						'alias' => 'submit_datetime',
						'title' => lang( 'submit_datetime' ),
						'visible' => TRUE,
						'type' => 'built_in',
						
					),
					array(
						
						'alias' => 'mod_datetime',
						'title' => lang( 'mod_datetime' ),
						'visible' => TRUE,
						'type' => 'built_in',
						
					),
					
				);
				
				foreach( $submit_form[ 'fields' ] as $key => $field ){
					
					if ( ! in_array( $field[ 'field_type' ], array( 'html', 'button' ) ) ){
						
						$new_column = & $columns[];
						
						$new_column[ 'alias' ] = $field[ 'alias' ];
						$new_column[ 'title' ] = ( isset( $field[ 'presentation_label' ] ) AND $field[ 'presentation_label' ] ) ? $field[ 'presentation_label' ] : $field[ 'label' ];
						$new_column[ 'visible' ] = TRUE;
						$new_column[ 'type' ] = $field[ 'field_type' ];
						
					}
					
				}
				
				foreach( $columns as $key => $column ){
					
					//echo 'comparando ' . $user_sf_us_columns[ $key ][ 'alias' ] . ': ' . $user_sf_us_columns[ $key ][ 'alias' ] . ' === ' . $column[ 'alias' ] . '?';
					//echo 'comparando ' . $user_sf_us_columns[ $key ][ 'title' ] . ': ' . $user_sf_us_columns[ $key ][ 'title' ] . ' === ' . $column[ 'title' ] . '?';
					
					if ( ! (
							isset( $user_sf_us_columns[ $key ][ 'alias' ] ) AND
							isset( $user_sf_us_columns[ $key ][ 'title' ] ) AND
							isset( $user_sf_us_columns[ $key ][ 'type' ] ) AND
							$user_sf_us_columns[ $key ][ 'alias' ] == $column[ 'alias' ] AND
							$user_sf_us_columns[ $key ][ 'title' ] == $column[ 'title' ] AND
							$user_sf_us_columns[ $key ][ 'type' ] == $column[ 'type' ]
						)
					) {
						
						//echo 'diferente!, parando!</br>';
						$cols_equals = FALSE;
						break;
						
					}
					else {
						
						//echo 'ok!</br>';
						$cols_equals = TRUE;
						
					}
					
				}
				
				//echo 'cols_equals: ' . ( int )$cols_equals;
				//echo '<br/><br/>------<br/><br/>';
				//echo print_r( $user_sf_us_columns, TRUE );
				//echo '<br/><br/>------<br/><br/>';
				//echo print_r( $columns, TRUE );
				//echo '<br/><br/>------<br/><br/>';
				
				// comparamos os tamanhos
				if ( $cols_equals ) {
					
					$cols_equals = count( $user_sf_us_columns ) === count( $columns );
					
				}
				
				//echo '<br/><br/>------<br/><br/>';
				//echo 'cols_equals: ' . ( int )$cols_equals;
				//echo '<br/><br/>------<br/><br/>';
				
			}
			
			//echo ( int )$cols_equals;
			//echo '<br/><br/>------<br/><br/>';
			if ( check_var( $post ) AND check_var( $post[ 'update_columns_to_show' ] ) ){
				
				if ( check_var( $post[ 'columns_to_show' ] ) ) {
					
					$columns = array(
						
						array(
							
							'alias' => 'id',
							'title' => lang( 'id' ),
							'visible' => in_array( 'id', $post[ 'columns_to_show' ] ) ? TRUE : FALSE,
							'type' => 'built_in',
							
						),
						array(
							
							'alias' => 'submit_datetime',
							'title' => lang( 'submit_datetime' ),
							'visible' => in_array( 'submit_datetime', $post[ 'columns_to_show' ] ) ? TRUE : FALSE,
							'type' => 'built_in',
							
						),
						array(
							
							'alias' => 'mod_datetime',
							'title' => lang( 'mod_datetime' ),
							'visible' => in_array( 'mod_datetime', $post[ 'columns_to_show' ] ) ? TRUE : FALSE,
							'type' => 'built_in',
							
						),
						
					);
					
					foreach ( $submit_form[ 'fields' ] as $key => $field ) {
						
						if ( ! in_array( $field[ 'field_type' ], array( 'html', 'button' ) ) ){
							
							$new_column = & $columns[];
							
							$new_column[ 'alias' ] = $field[ 'alias' ];
							$new_column[ 'title' ] = ( isset( $field[ 'presentation_label' ] ) AND $field[ 'presentation_label' ] ) ? $field[ 'presentation_label' ] : $field[ 'label' ];
							$new_column[ 'visible' ] = in_array( $new_column[ 'alias' ], $post[ 'columns_to_show' ] ) ? TRUE : FALSE;
							$new_column[ 'type' ] = $field[ 'field_type' ];
							
						}
						
					}
					
					$user_preference[ 'admin_submit_form_users_submits_columns' ] = get_params( isset( $this->users->user_data[ 'params' ][ 'admin_submit_form_users_submits_columns' ] ) ? $this->users->user_data[ 'params' ][ 'admin_submit_form_users_submits_columns' ] : NULL );
					
					$user_preference[ 'admin_submit_form_users_submits_columns' ][ 'submit_form_' . $submit_form_id ] = $columns;
					
					$this->users->set_user_preferences( $user_preference, NULL, TRUE, TRUE );
					
				}
				else {
					
					$columns = $this->mcm->filtered_system_params[ 'admin_submit_form_users_submits_columns' ][ 'submit_form_' . $submit_form_id ];
					
				}
				
			}
			else if ( ! isset( $__filtered_params[ 'submit_form_' . $submit_form_id ] ) OR ! is_array( $__filtered_params[ 'submit_form_' . $submit_form_id ] ) OR ( ! $cols_equals ) ){
				
				$max_columns = 7;
				
				$priority_search_strings = array(
					
					'title',
					'name',
					'e-mail',
					'email',
					'username',
					'date',
					'city',
					'state',
					'phone',
					'celphone',
					
				);
				
				// translated strings
				foreach ( $priority_search_strings as $key => $v ) {
					
					$priority_search_strings[] = url_title( lang( $v ), '-', TRUE );
					
				}
				
				$columns = array(
					
					array(
						
						'alias' => 'id',
						'title' => lang( 'id' ),
						'visible' => TRUE,
						'type' => 'built_in',
						
					),
					array(
						
						'alias' => 'submit_datetime',
						'title' => lang( 'submit_datetime' ),
						'visible' => TRUE,
						'type' => 'built_in',
						
					),
					array(
						
						'alias' => 'mod_datetime',
						'title' => lang( 'mod_datetime' ),
						'visible' => TRUE,
						'type' => 'built_in',
						
					),
					
				);
				
				foreach ( $submit_form[ 'fields' ] as $key => $field ) {
					
					if ( ! in_array( $field[ 'field_type' ], array( 'html', 'button' ) ) ){
						
						$new_column = & $columns[];
						
						$new_column[ 'alias' ] = $field[ 'alias' ];
						$new_column[ 'title' ] = ( isset( $field[ 'presentation_label' ] ) AND $field[ 'presentation_label' ] ) ? $field[ 'presentation_label' ] : $field[ 'label' ];
						$new_column[ 'visible' ] = TRUE;
						$new_column[ 'type' ] = $field[ 'field_type' ];
						
					}
					
				}
				
				if ( count( $columns ) > $max_columns ){
					
					$_diff = count( $columns ) - $max_columns;
					
					foreach ( $columns as $c_key => & $column ) {
						
						if ( $_diff > 0 ) {
							
							if ( ! ( check_var( $column[ 'visible' ] ) AND array_find( $column[ 'alias' ], $priority_search_strings ) ) AND
								! ( check_var( $column[ 'visible' ] ) AND array_find( $column[ 'title' ], $priority_search_strings ) ) ) {
								
								$column[ 'visible' ] = FALSE;
								
							}
							
							$_diff--;
							
						}
						else{
							
							break;
							
						}
						
					}
					
				}
				else {
					
					foreach ( $columns as $c_key => & $column ) {
						
						$column[ 'visible' ] = TRUE;
						
					}
					
				}
				
				$user_preference[ 'admin_submit_form_users_submits_columns' ] = get_params( isset( $this->users->user_data[ 'params' ][ 'admin_submit_form_users_submits_columns' ] ) ? $this->users->user_data[ 'params' ][ 'admin_submit_form_users_submits_columns' ] : NULL );
				
				$user_preference[ 'admin_submit_form_users_submits_columns' ][ 'submit_form_' . $submit_form_id ] = $columns;
				
				$this->users->set_user_preferences( $user_preference, NULL, TRUE, TRUE );
				
			}
			else {
				
				$columns = $this->mcm->filtered_system_params[ 'admin_submit_form_users_submits_columns' ][ 'submit_form_' . $submit_form_id ];
				
			}
			//echo '<pre>' . print_r( $columns, TRUE ) . '</pre>';
			$columns = is_array( $columns ) ? array_filter( $columns ) : array();
			
			$data[ 'columns' ] = $columns;
			
			/*
			--------------------------------------------------------
			Colunas a serem exibidas
			--------------------------------------------------------
			********************************************************
			*/
			
			$data[ 'submit_form' ] = $submit_form;
			
		}
		
		/*
			********************************************************
			--------------------------------------------------------
			Ordenção por colunas
			--------------------------------------------------------
			*/
		
		$ob_user_preference_name = 'users_submits_list_order_by';
		$obd_user_preference_name = 'users_submits_list_order_by_direction';
		
		if ( $submit_form_id ){
			
			$ob_user_preference_name .= '_sf' . $submit_form_id;
			$obd_user_preference_name .= '_sf' . $submit_form_id;
			
		}
		
		if ( ! ( ( $order_by_direction = $this->users->get_user_preference( $obd_user_preference_name ) ) != FALSE ) ){
			
			$order_by_direction = 'ASC';
			
		}
		
		// order by complement
		$ob_comp = '';
		
		if ( ! ( $order_by = $this->users->get_user_preference( $ob_user_preference_name ) ) ){
			
			$order_by = 'id';
			
		}
		
		$search_config[ 'plugins_params' ][ 'sf_us_search' ][ 'order_by' ] = $data[ 'order_by' ] = $order_by;
		$search_config[ 'plugins_params' ][ 'sf_us_search' ][ 'order_by_direction' ] = $data[ 'order_by_direction' ] = $order_by_direction;
		$search_config[ 'order_by' ][ 'sf_us_search' ] = $order_by;
		$search_config[ 'order_by_direction' ][ 'sf_us_search' ] = $order_by_direction;
		
		/*
		--------------------------------------------------------
		Ordenção por colunas
		--------------------------------------------------------
		********************************************************
		*/
		
		/*
		********************************************************
		--------------------------------------------------------
		Terms
		--------------------------------------------------------
		*/
		
		if ( isset( $sfsp[ 'terms' ] ) AND ! isset( $data[ 'post' ][ 'users_submits_search' ][ 'terms' ] ) ){
			
			$data[ 'post' ][ 'users_submits_search' ][ 'terms' ] = $sfsp[ 'terms' ];
			
		}
		
		$terms = isset( $data[ 'post' ][ 'users_submits_search' ][ 'terms' ] ) ? $data[ 'post' ][ 'users_submits_search' ][ 'terms' ] : ( isset( $data[ 'get' ][ 'q' ][ 'terms' ] ) ? urldecode( $data[ 'get' ][ 'q' ][ 'terms' ] ) : FALSE );
		$data[ 'terms' ] = $terms;
		$search_config[ 'terms' ] = $terms;
		
		if ( ( isset( $data[ 'post' ][ 'users_submits_search' ][ 'submit_search' ] ) OR $terms ) ){
			
			if ( $terms ) {
				
				$get_query[ 'q' ] = $terms;
				
			}
			
		}
		
		/*
		--------------------------------------------------------
		Terms
		--------------------------------------------------------
		********************************************************
		*/
		
		/*
		********************************************************
		--------------------------------------------------------
		Filters
		--------------------------------------------------------
		*/
		
		if ( ! isset( $data[ 'post' ][ 'users_submits_search' ][ 'submit_search' ] ) AND $sfsp ){
			
			$data[ 'post' ][ 'users_submits_search' ] = $sfsp;
			
		}
		
	//echo '<pre>' . print_r( $sfsp, TRUE ) . '</pre>'; exit;
	
		if ( isset( $data[ 'post' ][ 'users_submits_search' ][ 'submit_search' ] ) ){
			
			$f = array();
			
			$sfsp = $data[ 'post' ][ 'users_submits_search' ];
			unset( $sfsp[ 'submit_search' ] );
			
			if ( isset( $data[ 'post' ][ 'users_submits_search' ][ 'dinamic_filter_fields' ] ) ) {
				
				foreach ( $data[ 'post' ][ 'users_submits_search' ][ 'dinamic_filter_fields' ] as $key => $value ) {
					
					if ( trim( $value ) !== '' ) {
						
						$_filter = & $f[];
						
						$_filter[ 'alias' ] = $key;
						$_filter[ 'value' ] = $value;
						$_filter[ 'comp_op' ] = '=';
						
					}
					
				}
				
			}
			
		}
		
		//echo '<pre>' . print_r( $f, TRUE ) . '</pre>'; exit;
		
		$search_config[ 'plugins_params' ][ 'sf_us_search' ][ 'filters' ] = $f;
		
		$filters_url = urlencode( base64_encode( json_encode( $f ) ) );
		
		$data[ 'filters_url' ] = $filters_url;
		
		/*
		--------------------------------------------------------
		Filters
		--------------------------------------------------------
		********************************************************
		*/
		
		foreach ( $data[ 'submit_form' ][ 'fields' ] as $key => $field ) {
			
			$alias = isset( $field[ 'alias' ] ) ? $field[ 'alias' ] : $this->sfcm->make_field_name( $field[ 'label' ] );
			
			$data[ 'fields' ][ $alias ] = $field;
			$data[ 'fields' ][ $alias ][ 'alias' ] = $alias;
			
		}
		
		/*
		********************************************************
		--------------------------------------------------------
		Paginação
		--------------------------------------------------------
		*/
		
		$__new_ipp = FALSE;
		
		if ( isset( $data[ 'post' ][ 'users_submits_search' ][ 'submit_search' ] ) ){
			
			$cp = 1;
			
		}
		
		if ( isset( $post[ 'ipp' ] ) AND isset( $post[ 'submit_change_ipp' ] ) ){
			
			$ipp = $post[ 'ipp' ];
			
			$__new_ipp = TRUE;
			
			// we also have to set the current page as 1, to cut the risk of the search result falls outside the range
			$cp = 1;
			
		}
		else if (
			
			! isset( $ipp ) AND
			$submit_form_id AND
			is_numeric( $this->users->get_user_preference( $this->mcm->environment . '_users_submits_list_items_per_page_sf_' . $submit_form_id ) ) AND
			$this->users->get_user_preference( $this->mcm->environment . '_users_submits_list_items_per_page_sf_' . $submit_form_id ) > -1 AND
			! isset( $post[ 'ipp' ] )
			
		){
			
			$ipp = $this->users->get_user_preference( $this->mcm->environment . '_users_submits_list_items_per_page_sf_' . $submit_form_id );
			
		}
		else if (
			
			! isset( $ipp ) AND
			is_numeric( $this->users->get_user_preference( $this->mcm->environment . '_users_submits_list_items_per_page' ) ) AND
			$this->users->get_user_preference( $this->mcm->environment . '_users_submits_list_items_per_page' ) > -1 AND
			! isset( $post[ 'ipp' ] )
			
		){
			
			$ipp = $this->users->get_user_preference( $this->mcm->environment . '_users_submits_list_items_per_page' );
			
		}
		else if ( ! isset( $ipp ) OR $ipp == -1 ){
			
			$ipp = $this->mcm->filtered_system_params[ $this->mcm->environment . '_items_per_page' ];
			
		}
		
		if ( $ipp < -1 ){
			
			$ipp = -1;
			
			if ( isset( $post[ 'ipp' ] ) ) {
				
				$post[ 'ipp' ] = $ipp;
				
			}
			
		}
		
		if ( isset( $data[ 'get' ][ 'ipp' ] ) AND $data[ 'get' ][ 'ipp' ] != $ipp ) {
			
			$__new_ipp = TRUE;
			
			$ipp = urldecode( $data[ 'get' ][ 'ipp' ] );
			
			// we also have to set the current page as 1, to cut the risk of the search result falls outside the range
			$cp = 1;
			
		}
		
		if ( ! $ipp ){
			
			$ipp = NULL;
			
		}
		
		if ( isset( $submit_form[ 'params' ][ 'sf_us_list_max_ipp' ] ) AND $ipp > $submit_form[ 'params' ][ 'sf_us_list_max_ipp' ] ) {
			
			$ipp = $submit_form[ 'params' ][ 'sf_us_list_max_ipp' ];
			
		}
		
		if ( $__new_ipp ) {
			
			if ( $submit_form_id ) {
				
				$this->users->set_user_preferences( array( $this->mcm->environment . '_users_submits_list_items_per_page_sf_' . $submit_form_id => $ipp ) );
				
			}
			else {
				
				$this->users->set_user_preferences( array( $this->mcm->environment . '_users_submits_list_items_per_page' => $ipp ) );
				
			}
			
		}
		
		$search_config[ 'ipp' ] = $ipp;
		$data[ 'ipp' ] = $ipp;
		$search_config[ 'cp' ] = & $cp;
		
		if ( $this->input->post( 'submit_cancel_search', TRUE ) ){
			
			redirect( $data[ 'users_submits_list_link' ] );
			
		}
		
		$get_query = '';
		
		/*
		if ( $this->input->post() ) {
			
			echo '<pre>' . print_r( $this->input->post(), TRUE ) . '</pre>'; 
			exit;
			
		}
		*/
		$this->search->config( $search_config );
		$users_submits = $this->search->get_full_results( 'sf_us_search', TRUE );
		
		// submit_form_base_link_array
		$sfbla = array();

		// user_submit_base_link_array
		$usbla = array();

		foreach ( $users_submits as $key => & $user_submit ) {
			
			if ( $submit_form_id ){
				
				$sfbla = array(
					
					'sfid' => $submit_form_id,
					
				);
				
				$c_urls[ 'back_link' ] = $c_urls[ 'us_list_link' ];
				
			}
			
			$usbla = array(
				
				'usid' => $user_submit[ 'id' ],
				
			);
			
			$user_submit[ 'edit_link' ] = $c_urls[ 'us_edit_link' ] . '/' . $this->uri->assoc_to_uri( $sfbla + $usbla );
			$user_submit[ 'view_link' ] = $c_urls[ 'us_view_link' ] . '/' . $this->uri->assoc_to_uri( $sfbla + $usbla );
			$user_submit[ 'remove_link' ] = $c_urls[ 'us_remove_link' ] . '/' . $this->uri->assoc_to_uri( $sfbla + $usbla );
			$user_submit[ 'users_submits_link' ] = $c_urls[ 'us_list_link' ] . '/' . $this->uri->assoc_to_uri( array( 'sfid' => $user_submit[ 'submit_form_id' ], ) );
			$user_submit[ 'change_order_link' ] = $c_urls[ 'us_change_order_link' ] . '/' . $this->uri->assoc_to_uri( $sfbla + $usbla );
			$user_submit[ 'up_order_link' ] = $c_urls[ 'us_up_order_link' ] . '/' . $this->uri->assoc_to_uri( $sfbla + $usbla );
			$user_submit[ 'down_order_link' ] = $c_urls[ 'us_down_order_link' ] . '/' . $this->uri->assoc_to_uri( $sfbla + $usbla );
			
			$user_submit[ 'data' ] = get_params( $user_submit[ 'data' ] );
			
		}
		
		$data[ 'users_submits' ] = $users_submits;
		
		$sfsp = urlencode( base64_encode( json_encode( $sfsp ) ) );
		$data[ 'search_filters_url' ] = $sfsp;
		$pagination_url = ( ( ! empty( $terms ) ) ? $data[ 'c_urls' ][ 'us_search_link' ] : $data[ 'c_urls' ][ 'us_list_link' ] ) . '/' . $this->uri->assoc_to_uri( $sfbla ) . '/sfsp/' . $sfsp . '/f/' . $filters_url . '/cp/%p%/ipp/%ipp%' . $get_query;
		
		
		$data[ 'users_submits_total_results' ] = $this->search->count_all_results( 'sf_us_search' );
		
		$data[ 'pagination' ] = get_pagination( $pagination_url, $cp, $ipp, $this->search->count_all_results( 'sf_us_search' ) );
		
		/*
		--------------------------------------------------------
		Paginação
		--------------------------------------------------------
		********************************************************
		*/
		
		// -------------------------------------------------
		// Last url ----------------------------------------
		
		// setting up the last url
		set_last_url( $a_url );
		
		// Last url ----------------------------------------
		// -------------------------------------------------
		
		$this->_page(
			
			array(
				
				'component_view_folder' => $this->component_view_folder,
				'function' => 'users_submits_management',
				'action' => 'users_submits_list',
				'layout' => 'default',
				'view' => 'users_submits_list',
				'data' => $data,
				
			)
			
		);
		
	}
	
	/*
		--------------------------------------------------------
		Users submits list
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
		
		$ob_user_preference_name = 'users_submits_list_order_by';
		$obd_user_preference_name = 'users_submits_list_order_by_direction';
		
		if ( $submit_form_id ){
			
			$ob_user_preference_name .= '_sf' . $submit_form_id;
			$obd_user_preference_name .= '_sf' . $submit_form_id;
			
		}
		
		$this->users->set_user_preferences( array( $ob_user_preference_name => $ob ) );

		if ( ( $order_by_direction = $this->users->get_user_preference( $obd_user_preference_name ) ) != FALSE ){

			switch ( $order_by_direction ) {

				case 'ASC':

					$order_by_direction = 'DESC';
					break;

				case 'DESC':

					$order_by_direction = 'ASC';
					break;

			}

			$this->users->set_user_preferences( array( $obd_user_preference_name => $order_by_direction ) );

		}
		else {

			$this->users->set_user_preferences( array( $obd_user_preference_name => 'ASC' ) );

		}
		
		if ( $this->input->post( 'ajax', TRUE ) AND $this->input->post( 'redirect_c_function', TRUE ) ) {
			
			$f_params = $this->uri->ruri_to_assoc();
			
			$f_params = $this->input->post( 'redirect_c_function', TRUE );
			
			$f_params = explode( '/', $f_params );
			
			$redirect_c_function = $f_params[ 2 ];
			
			unset( $f_params[ 0 ] );
			unset( $f_params[ 1 ] );
			unset( $f_params[ 2 ] );
			
			$_tmp = array();
			
			foreach( $f_params as $k => $v ) {
				
				if ( $k % 2 != 0 ) {
					
					$_tmp[ $v ] = $f_params[ $k + 1 ];
					
				}
				
			}
			
			$f_params = $_tmp;
			
			echo 'cob: <pre>' . print_r( $f_params, TRUE ) . '</pre>';
			
			$this->{ $redirect_c_function }( $f_params );
			
		}
		else if ( ! $this->input->post( 'ajax', TRUE ) ) {
			
			redirect_last_url();
			
		}
		
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
	Copy
	--------------------------------------------------------
	*/

	else if ( ( $action == 'cp' ) AND isset( $post[ '' ] ) ){
		
		if ( ( $order_by_direction = $this->users->get_user_preference( $obd_user_preference_name ) ) != FALSE ){

			switch ( $order_by_direction ) {

				case 'ASC':

					$order_by_direction = 'DESC';
					break;

				case 'DESC':

					$order_by_direction = 'ASC';
					break;

			}

			$this->users->set_user_preferences( array( $obd_user_preference_name => $order_by_direction ) );

		}
		else {

			$this->users->set_user_preferences( array( $obd_user_preference_name => 'ASC' ) );

		}
		
		if ( $this->input->post( 'ajax', TRUE ) AND $this->input->post( 'redirect_c_function', TRUE ) ) {
			
			$f_params = $this->uri->ruri_to_assoc();
			
			$f_params = $this->input->post( 'redirect_c_function', TRUE );
			
			$f_params = explode( '/', $f_params );
			
			$redirect_c_function = $f_params[ 2 ];
			
			unset( $f_params[ 0 ] );
			unset( $f_params[ 1 ] );
			unset( $f_params[ 2 ] );
			
			$_tmp = array();
			
			foreach( $f_params as $k => $v ) {
				
				if ( $k % 2 != 0 ) {
					
					$_tmp[ $v ] = $f_params[ $k + 1 ];
					
				}
				
			}
			
			$f_params = $_tmp;
			
			//echo '<pre>' . print_r( $redirect_c_function, TRUE ) . '</pre>'; exit;
			
			$this->{ $redirect_c_function }( $f_params );
			
		}
		else if ( ! $this->input->post( 'ajax', TRUE ) ) {
			
			redirect_last_url();
			
		}
		
	}

	/*
	--------------------------------------------------------
	Copy
	--------------------------------------------------------
	********************************************************
	*/
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/*
		********************************************************
		--------------------------------------------------------
		Add / edit submit form
		--------------------------------------------------------
		*/
	
	else if ( $action == 'aus' OR $action == 'eus' ){
		
		if ( $submit_form_id ) {
			
			// get submit form params
			$gsfp = array(
				
				'where_condition' => 't1.id = ' . $submit_form_id,
				'limit' => 1,
				
			);
			
			$submit_form = $this->sfcm->get_submit_forms( $gsfp )->row_array();
			
			if ( ! $submit_form ) {
				
				msg( ( 'submit_form_do_not_exist' ), 'error' );
				redirect_last_url();
				
			}
			
			$this->sfcm->parse_sf( $submit_form, TRUE );
			$data[ 'submit_form' ] = & $submit_form;
			
			if ( $this->input->post( NULL, TRUE ) ){
				
				$data[ 'post' ] = $this->input->post( NULL, TRUE );
				
				// Quando os campos condicionais entram em ação, os campos ocultados, por exemplo
				// ainda seriam validados, corrigimos isso com esta variável.
				// Quando um campo condicional é ocultado no formulário, lá mesmo adicionamos
				// via javascript o conjunto de elementos que não devem ser validados
				// e recebemos aqui via POST
				$no_validation_fields = isset( $data[ 'post' ][ 'no_validation_fields' ] ) ? $data[ 'post' ][ 'no_validation_fields' ] : array();
				
			}
			
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
			
			if ( $action == 'aus' ){
				
			}
			else if ( $action == 'eus' AND $user_submit_id ){
				
				$user_submit = $this->sfcm->get_user_submit( $user_submit_id );
				
				if ( ! $submit_form ) {
					
					msg( ( 'user_submit_do_not_exist' ), 'error' );
					redirect_last_url();
					
				}
				
				$data[ 'user_submit' ] = & $user_submit;
				
			}
			
			foreach ( $submit_form[ 'fields' ] as $key => $prop ) {
				
				$prop_name = $prop[ 'alias' ];
				
				$formatted_field_name = 'form[' . $prop_name . ']';
				
				$rules = array( 'trim' );
				
				if ( ! check_var( $no_validation_fields[ $prop_name ] ) ) {
					
					if ( isset( $prop[ 'field_is_required' ] ) AND $prop[ 'field_is_required' ] === '1' ){
						
						$rules[] = 'required';
						
					}
					
					if ( isset( $prop[ 'validation_rule' ] ) AND is_array( $prop[ 'validation_rule' ] ) ){
						
						foreach ( $prop[ 'validation_rule' ] as $key => $rule ) {
							
							$comp = '';
							
							switch ( $rule ) {
								
								case 'matches':
									
									$comp .= '[form[' . $prop[ 'alias' ] . ']]';
									break;
									
							}
							
							if ( $rule == 'valid_email') {
								
								if ( ! isset( $data[ 'post' ][ 'form' ][ $prop_name ] ) OR $data[ 'post' ][ 'form' ][ $prop_name ] != '' ) {
									
									$rules[] = $rule . $comp;
									
								}
								
							}
							else {
								
								$rules[] = $rule . $comp;
								
							}
							
						}
						
					}
					
				}
				
				// xss fieltering
				if ( check_var( $submit_form[ 'params' ][ 'submit_form_param_send_email_to' ] ) ) {
					
					$rules[] = 'xss';
					
				}
				
				$rules = join( '|', $rules );
				
				$this->form_validation->set_rules( $formatted_field_name, lang( $prop[ 'label' ] ), $rules );
				
				// articles
				if ( $prop[ 'field_type' ] === 'articles' AND isset( $prop[ 'articles_category_id' ] ) ) {
					
					$search_config = array(
						
						'plugins' => 'articles_search',
						'ipp' => 0,
						'allow_empty_terms' => TRUE,
						'plugins_params' => array(
							
							'articles_search' => array(
								
								'category_id' => $prop[ 'articles_category_id' ],
								'recursive_categories' => TRUE,
								
							),
							
						),
						
					);
					
					$this->load->library( 'search' );
					$this->search->config( $search_config );
					
					$articles = $this->search->get_full_results( 'articles_search', TRUE );
					
					$submit_form[ 'fields' ][ $key ][ 'articles' ] = check_var( $articles ) ? $articles : array();
					
				}
				
				// date
				else if ( $prop[ 'field_type' ] === 'date' ) {
					
					if ( isset( $data[ 'post' ][ 'form' ][ $prop[ 'alias' ] ] ) AND is_array( $data[ 'post' ][ 'form' ][ $prop[ 'alias' ] ] ) ) {
						
						$d = ( isset( $data[ 'post' ][ 'form' ][ $prop[ 'alias' ] ][ 'd' ] ) AND $data[ 'post' ][ 'form' ][ $prop[ 'alias' ] ][ 'd' ] != '' ) ? $data[ 'post' ][ 'form' ][ $prop[ 'alias' ] ][ 'd' ] : '00';
						$m = ( isset( $data[ 'post' ][ 'form' ][ $prop[ 'alias' ] ][ 'm' ] ) AND $data[ 'post' ][ 'form' ][ $prop[ 'alias' ] ][ 'm' ] != '' ) ? $data[ 'post' ][ 'form' ][ $prop[ 'alias' ] ][ 'm' ] : '00';
						$y = ( isset( $data[ 'post' ][ 'form' ][ $prop[ 'alias' ] ][ 'y' ] ) AND $data[ 'post' ][ 'form' ][ $prop[ 'alias' ] ][ 'y' ] != '' ) ? $data[ 'post' ][ 'form' ][ $prop[ 'alias' ] ][ 'y' ] : '0000';
						
						$data[ 'post' ][ 'form' ][ $prop[ 'alias' ] ] = $y . '-' . $m . '-' . $d;
						
					}
					
				}
				
				// textarea
				else if ( $prop[ 'field_type' ] == 'textarea' ) {
					
					if ( isset( $user_submit[ 'data' ][ $prop[ 'alias' ] ] ) AND ! is_array( $user_submit[ 'data' ][ $prop[ 'alias' ] ] ) ) {
						
						$user_submit[ 'data' ][ $prop[ 'alias' ] ] = htmlspecialchars_decode( $user_submit[ 'data' ][ $prop[ 'alias' ] ] );
						
					}
					
				}
				
			}
			
			// aqui definimos as ações obtidas via post, ex.: ao salvar, cancelar, adicionar campo, etc.
			// acionados ao submeter os forms
			$submit_action =
				
				$this->input->post( 'submit_cancel' ) ? 'cancel' :
				( $this->input->post( 'submit' ) ? 'submit' :
				( $this->input->post( 'submit_apply' ) ? 'apply' :
				'none' ) );
				
			
			//echo '<pre>' . print_r( $user_submit, TRUE ) . '</pre>'; exit;
			
			//echo '<pre>' . print_r( $user_submit, TRUE ) . '</pre>'; exit;
			
			if ( in_array( $submit_action, array( 'cancel' ) ) ) {
				
				redirect_last_url();
				
			}
			// se a validação dos campos for positiva
			else if ( $this->form_validation->run() AND ( in_array( $submit_action, array( 'submit', 'apply' ) ) ) ){
				
				// Adjusting not showed fields
				
				$_tmp = array();
				
				foreach ( $submit_form[ 'fields' ] as $k => $prop ) {
					
					if ( $prop[ 'field_type' ] == 'textarea' AND ! is_array( $data[ 'post' ][ 'form' ][ $prop[ 'alias' ] ] ) ) {
						
						$data[ 'post' ][ 'form' ][ $prop[ 'alias' ] ] = htmlspecialchars( $data[ 'post' ][ 'form' ][ $prop[ 'alias' ] ] );
						
					}
					
					if ( ! check_var( $prop[ 'availability' ][ 'admin' ] ) AND check_var( $prop[ 'default_value' ] ) ) {
						
						$post[ 'form' ] = array_merge_recursive_distinct( $post[ 'form' ], array( $prop[ 'alias' ] => $prop[ 'default_value' ] ) );
						
					}
					
				}
				
				$db_data = elements( array(
					
					'submit_form_id',
					'mod_datetime',
					'data',
					
				), $post );
				
				$mod_datetime = gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] );
				$mod_datetime = strftime( '%Y-%m-%d %T', $mod_datetime );
				
				$db_data[ 'submit_form_id' ] = $submit_form_id;
				$db_data[ 'mod_datetime' ] = $mod_datetime;
				
				
				//echo '<pre>' . print_r( $data[ 'post' ][ 'form' ], TRUE ) . '</pre>'; exit;
				
				$db_data[ 'data' ] = json_encode( $post[ 'form' ] );
				
				if ( $action == 'aus' ){
					
					$db_data[ 'params' ] = array();
					
					if ( $this->users->is_logged_in() ){
						
						$db_data[ 'params' ][ 'created_by_user_id' ] = $this->users->user_data[ 'id' ];
						
					}
					
					$db_data[ 'params' ] = json_encode( $db_data[ 'params' ] );
					
					$db_data[ 'submit_datetime' ] = $mod_datetime;
					
					$return_id = $this->sfcm->insert_user_submit( $db_data );
					
					if ( $return_id ){
						
						msg( ( 'submit_form_created' ),'success' );
						
						if ( $this->input->post( 'submit_apply' ) ){
							
							$assoc_to_uri_array = array(
								
								'sfid' => $submit_form_id,
								'usid' => $return_id,
								'a' => 'eus',
								
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
				else if ( $action == 'eus' ){
					
					$db_data[ 'params' ] = $user_submit[ 'params' ];
					
					if ( $this->users->is_logged_in() ){
						
						$db_data[ 'params' ][ 'modified_by_user_id' ] = $this->users->user_data[ 'id' ];
						
					}
					
					$db_data[ 'params' ] = json_encode( $db_data[ 'params' ] );
					
					if ( $this->sfcm->update_user_submit( $db_data, array( 'id' => $user_submit_id ) ) ){
						
						msg( ( 'user_submit_updated' ), 'success' );
						
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
					'function' => 'users_submits_management',
					'action' => 'user_submit_form',
					'layout' => 'default',
					'view' => 'user_submit_form',
					'data' => $data,
					
				)
				
			);
			
		}
		
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
		View submit form
		--------------------------------------------------------
		*/

	else if ( $action == 'vus' ){

		if ( $submit_form_id AND $user_submit_id ){

			// get submit form params
			$gus_params = array(

				'where_condition' => 't1.id = ' . $user_submit_id,
				'limit' => 1,

				);

			// get submit form params
			$gsfp = array(

				'where_condition' => 't1.id = ' . $submit_form_id,
				'limit' => 1,

				);

			$submit_form = $this->sfcm->get_submit_forms( $gsfp )->row_array();
			$user_submit = $this->sfcm->get_user_submit( $user_submit_id, $submit_form_id );

			$data[ 'submit_form' ] = & $submit_form;
			$data[ 'user_submit' ] = & $user_submit;

			$submit_form[ 'fields' ] = get_params( $submit_form[ 'fields' ] );
			$submit_form[ 'params' ] = get_params( $submit_form[ 'params' ] );

		}

		$this->_page(

			array(

				'component_view_folder' => $this->component_view_folder,
				'function' => 'users_submits_management',
				'action' => 'view_user_submit',
				'layout' => 'default',
				'view' => 'view_user_submit',
				'data' => $data,

			)

		);

	}

	/*
		--------------------------------------------------------
		View user submit
		--------------------------------------------------------
		********************************************************
		*/
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/*
		********************************************************
		--------------------------------------------------------
		Batch
		--------------------------------------------------------
		*/

	else if ( $action == 'b' ){
		
		$ids = array();
		$success = FALSE;
		$errors_count = 0;
		$msg_result = '';
		
		if ( isset( $post[ 'selected_users_submits_ids' ] ) ){
			
			$ids = $post[ 'selected_users_submits_ids' ];
			
		}
		else if ( $id AND ! is_array( $id ) ){
			
			$ids[] = $id;
			
		}
		
		if ( isset( $post[ 'submit_remove' ] ) AND isset( $post[ 'selected_users_submits_ids' ] ) ){
			
			$ids = $this->input->post( 'selected_users_submits_ids' );
			
			foreach ( $ids as $key => $id ) {
				
				if ( $this->sfcm->remove_user_submit( $id ) ){
					
					$success = TRUE;
					
				}
				else{
					
					$errors_count++;
					$msg_result .= lang( 'error_removed_user_submit' ) . ' #' . $id . '<br />';
					
				}
				
			}
			
			if ( $errors_count > 0 ){
				
				msg( 'error_removing_users_submits', 'title' );
				msg( $msg_result, 'error' );
				
			}
			else {
				
				msg( 'users_submits_removed_success', 'success' );
				
			}
			
			redirect_last_url();
			
		}
		else {

			redirect_last_url();

		}

	}

	/*
		--------------------------------------------------------
		Batch
		--------------------------------------------------------
		********************************************************
		*/
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/*
		********************************************************
		--------------------------------------------------------
		Remove submit form
		--------------------------------------------------------
		*/

	else if ( $action == 'r' ){

		$id = $user_submit_id;

		if ( $id AND ( $user_submit = $this->sfcm->get_user_submit( $id ) ) ){

			if( $this->input->post( 'submit_cancel' ) ){

				redirect_last_url();

			}
			else if ( $this->input->post( 'submit' ) ){

				if ( $this->sfcm->remove_user_submit( $id ) ){

					msg( 'user_submit_deleted', 'success');
					redirect_last_url();

				}
				else{

					msg($this->lang->line( 'user_submit_deleted_fail' ), 'error' );
					redirect_last_url();

				}

			}
			else{

				$data[ 'user_submit' ] = $user_submit;

				$this->_page(

					array(

						'component_view_folder' => $this->component_view_folder,
						'function' => 'users_submits_management',
						'action' => 'remove_user_submit',
						'layout' => 'default',
						'view' => 'remove_user_submit',
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

	else{
		show_404();
	}
