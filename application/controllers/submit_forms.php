<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

require( APPPATH . 'controllers/main.php' );

class Submit_forms extends Main {
	
	public function __construct(){
		
		parent::__construct();
		
		if ( ! $this->load->is_model_loaded( 'sfcm' ) ) {
			
			$this->load->model( 'common/submit_forms_common_model', 'sfcm' );
			
		}
		
		$this->load->language( 'submit_forms' );
		
		set_current_component();
		
	}
	
	/******************************************************************************/
	/******************************************************************************/
	/************************************* API ************************************/
	
	public function api(){
		
		// -------------------------------------------------
		// Parsing vars ------------------------------------
		
		$f_params = $this->uri->ruri_to_assoc();
		
		$action =								$this->input->get( 'a' ) ? $this->input->get( 'a' ) : 'getsf'; // action
		$download =								$this->input->get( 'dl' ) ? ( bool ) $this->input->get( 'dl' ) : FALSE; // download
		$filename =								$this->input->get( 'fn' ) ? $this->input->get( 'fn' ) : FALSE; // File name without extension
		$include_us =							$this->input->get( 'ius' ) ? ( bool ) $this->input->get( 'ius' ) : FALSE; // include users submits
		$submit_form_id =						$this->input->get( 'sfid' ) ? $this->input->get( 'sfid' ) : NULL; // submit form id
		$user_submit_id =						$this->input->get( 'usid' ) ? $this->input->get( 'usid' ) : NULL; // user submit id
		$cp =									$this->input->get( 'cp' ) ? $this->input->get( 'cp' ) : NULL; // current page
		$ipp =									$this->input->get( 'ipp' ) ? $this->input->get( 'ipp' ) : NULL; // items per page
		$ob =									$this->input->get( 'ob' ) ? $this->input->get( 'ob' ) : NULL; // order by
		$obd =									$this->input->get( 'obd' ) ? $this->input->get( 'obd' ) : NULL; // order by direction
		$s =									$this->input->get( 's' ) ? ( int )( ( bool ) $this->input->get( 's' ) ) : NULL; // search flag
		$f =									$this->input->get( 'f' ) ? json_decode( base64_decode( urldecode( $this->input->get( 'f' ) ) ), TRUE ) : array(); // filters
		$content_type =							$this->input->get( 'ct' ) ? $this->input->get( 'ct' ) : 'json'; // content type: json (default), xml
		$get_mode =								$this->input->get( 'gm' ) ? $this->input->get( 'gm' ) : 'compact'; // Get mode: full or compact
		$csv_delimiter =						$this->input->get( 'csvd' ) ? $this->input->get( 'csvd' ) : NULL; // csv delimiter
		$csv_enclosure =						( $this->input->get( 'csve' ) !== FALSE ) ? $this->input->get( 'csve' ) : NULL; // csv enclosure
		$force_all_string =						$this->input->get( 'csvfas' ) ? $this->input->get( 'csvfas' ) : FALSE; // Treat all collumns as string (use enclosure)
		$username =								$this->input->get( 'u' ) ? $this->input->get( 'u' ) : NULL; // User
		$password =								$this->input->get( 'p' ) ? $this->input->get( 'p' ) : NULL; // Password
		
		// Setting the file name
		
		$_fn_prefix = gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] );
		$_fn_prefix = strftime( '%Y-%m-%d %T', $_fn_prefix );
		$_fn_prefix = 'sf-api-' . $_fn_prefix;
		
		$filename =  url_title( ( ( $filename ) ? $filename : $_fn_prefix ) );
		
		// Parsing vars ------------------------------------
		// -------------------------------------------------
		
		$this->component_function = __FUNCTION__;
		$this->component_function_action = $action;
		
		// obtendo os parâmetros globais do componente
		$component_params = $this->current_component[ 'params' ];
		
		$data[ 'params' ] = $component_params;
		$data[ 'config' ][ 'sfid' ] = & $submit_form_id;
		$data[ 'config' ][ 'usid' ] = & $user_submit_id;
		$data[ 'config' ][ 'download' ] = & $download;
		$data[ 'config' ][ 'filename' ] = & $filename;
		$data[ 'config' ][ 'include_us' ] = & $include_us;
		$data[ 'config' ][ 'cp' ] = & $cp;
		$data[ 'config' ][ 'ipp' ] = & $ipp;
		$data[ 'config' ][ 'ob' ] = & $ob;
		$data[ 'config' ][ 'obd' ] = & $obd;
		$data[ 'config' ][ 'ct' ] = & $content_type;
		$data[ 'config' ][ 'gm' ] = & $get_mode;
		$data[ 'config' ][ 'csvd' ] = & $csv_delimiter;
		$data[ 'config' ][ 'csve' ] = & $csv_enclosure;
		$data[ 'config' ][ 'csvfas' ] = & $force_all_string;
		$data[ 'username' ] = & $username;
		$data[ 'password' ] = & $password;
		
		if ( strpos( $submit_form_id, ',' ) ) {
			
			$submit_form_id = explode( ',', $submit_form_id );
			
		}
		else {
			
			$submit_form_id = array( $submit_form_id );
			
		}
		
		foreach( $submit_form_id as $k => $sfpid ) {
			
			if ( ! ( $sfpid AND is_numeric( $sfpid ) AND is_int( $sfpid + 0 ) ) ) {
				
				unset( $submit_form_id[ $k ] );
				
			}
			
		}
		
		if ( strpos( $user_submit_id, ',' ) ) {
			
			$user_submit_id = explode( ',', $user_submit_id );
			
		}
		else {
			
			$user_submit_id = array( $user_submit_id );
			
		}
		
		foreach( $user_submit_id as $k => $usid ) {
			
			if ( ! ( $usid AND is_numeric( $usid ) AND is_int( $usid + 0 ) ) ) {
				
				unset( $user_submit_id[ $k ] );
				
			}
			
		}
		
		
		if ( $action === 'getsf' AND $submit_form_id ){
			
			$data[ 'submit_forms' ] = array();
			
			foreach( $submit_form_id as $k => $sfpid ) {
				
				// get submit form params
				$gsfp = array(
					
					'where_condition' => 't1.id = ' . $sfpid,
					'limit' => 1,
					
				);
				
				$submit_form = $this->sfcm->get_submit_forms( $gsfp )->row_array();
				
				if ( $submit_form ){
					
					$this->sfcm->parse_sf( $submit_form );
					
					if ( ! check_var( $submit_form[ 'params' ][ 'ud_ds_api_access_active' ] ) ) {
						
						$submit_form = array(
							
							'id' => $sfpid,
							'error' => ( lang( 'api_disabled' ) ),
							
						);
						
						$data[ 'submit_forms' ][ $sfpid ] = $submit_form;
						continue;
						
					}
					
					$api_access = FALSE;
					
					// Public
					if ( $submit_form[ 'params' ][ 'ud_ds_api_access_type' ] == 3 ) {
						
						$api_access = TRUE;
						
					}
					// Users
					else if ( $submit_form[ 'params' ][ 'ud_ds_api_access_type' ] == 1 OR $submit_form[ 'params' ][ 'ud_ds_api_access_type' ] == 2 ) {
						
						if ( check_var( $username ) AND check_var( $password ) ) {
							
							if ( $user = $this->users->get_user( array( 't1.username' => $username ) )->row_array() ) {
								
								if ( $user[ 'password' ] != base64_encode( md5( $password ) ) ) {
									
									$submit_form = array(
										
										'id' => $sfpid,
										'error' => ( lang( 'api_login_wrong_password' ) ),
										
									);
									
									$data[ 'submit_forms' ][ $sfpid ] = $submit_form;
									continue;
									
								}
								else {
									
									$user[ 'params' ] = get_params( $user[ 'params' ] );
									$user[ 'privileges' ] = get_params( $user[ 'privileges' ] );
									
								}
								
								if ( ! $this->users->_check_privileges( 'submit_forms_management_submit_forms_api_management', $user ) ){
									
									$submit_form = array(
										
										'id' => $sfpid,
										'error' => ( lang( 'access_denied_submit_forms_management_submit_forms_api_management' ) ),
										
									);
									
									$data[ 'submit_forms' ][ $sfpid ] = $submit_form;
									continue;
									
								};
								
								if ( ( $submit_form[ 'params' ][ 'ud_ds_api_access_type' ] == 1 AND ( ! check_var( $submit_form[ 'params' ][ 'ud_ds_api_access_type_user_group' ] ) OR ! in_array( $user[ 'group_id' ], $submit_form[ 'params' ][ 'ud_ds_api_access_type_user_group' ] ) ) ) OR ( $submit_form[ 'params' ][ 'ud_ds_api_access_type' ] == 2 AND ( ! check_var( $submit_form[ 'params' ][ 'ud_ds_api_access_type_users' ] ) OR ! in_array( $user[ 'id' ], $submit_form[ 'params' ][ 'ud_ds_api_access_type_users' ] ) ) ) ) {
									
									$submit_form = array(
										
										'id' => $sfpid,
										'error' => ( lang( 'api_cant_access_this_submit_form' ) ),
										
									);
									
									$data[ 'submit_forms' ][ $sfpid ] = $submit_form;
									continue;
									
								}
								
							}
							else {
								
								$submit_form = array(
									
									'id' => $sfpid,
									'error' => ( lang( 'api_login_username_not_found' ) ),
									
								);
								
								$data[ 'submit_forms' ][ $sfpid ] = $submit_form;
								continue;
								
							}
							
						}
						else {
							
							$submit_form = array(
								
								'id' => $sfpid,
								'error' => ( lang( 'api_require_user_login' ) ),
								
							);
							
							$data[ 'submit_forms' ][ $sfpid ] = $submit_form;
							continue;
							
						}
						
						$api_access = TRUE;
						
					}
					
					
					if ( ! $api_access ) {
						
						$submit_form = array(
							
							'id' => $sfpid,
							'error' => ( lang( 'access_denied' ) ),
							
						);
						
						$data[ 'submit_forms' ][ $sfpid ] = $submit_form;
						continue;
						
					}
					
					if ( $include_us ) {
						
						// get submit form params
						$gus_params = array(
							
							'sf_id' => $sfpid,
							'us_id' => $user_submit_id,
							
						);
						
						$submit_form[ 'users_submits' ] = $this->sfcm->get_users_submits( $gus_params );
						
					}
					
					$data[ 'submit_forms' ][ $sfpid ] = $submit_form;
					
				}
				
			}
			
			// Loading the view
			
			$page_params = array(
				
				'component_view_folder' => $this->component_view_folder,
				'function' => __FUNCTION__,
				'action' => 'get_submit_forms',
				'layout' => 'default',
				'view' => $content_type,
				'html' => FALSE,
				'load_index' => FALSE,
				'data' => $data,
				
			);
			
			$this->output->enable_profiler( FALSE );
			
			$this->_page( $page_params );
			
			echo $this->voutput->get_content();
			
			//echo '<pre>' . print_r( $data[ 'submit_forms' ], TRUE ) . '</pre>'; exit;
			/*
			if ( $data[ 'submit_form' ] = $this->sfcm->get_submit_forms( $gsfp )->row_array() ){
				
				$this->sfcm->parse_sf( $data[ 'submit_form' ] );
				
				if ( $return_type == 'json' ) {
					
					if ( $download ) {
						
						$this->output->enable_profiler( FALSE );
						
						$this->load->helper( 'download' );
						$this->output->set_content_type( 'application/json' );
						$ddata = json_encode ( $data[ 'submit_form' ] );
						$name = 'arquivo.json';
						
						force_download( $name, $ddata );
						
					}
					else {
						
						$this->output->enable_profiler( FALSE );
						$this->output->set_content_type( 'application/json' );
						$ddata = json_encode ( $data[ 'submit_form' ] );
						
						$this->output->set_output( $ddata );
						
					}
					
				}
				
			}*/
			
		}
		else {
			
			echo 'error';
			
		}
		
	}
	
	/************************************* API ************************************/
	/******************************************************************************/
	/******************************************************************************/
	
	/******************************************************************************/
	/******************************************************************************/
	/******************************* Component index ******************************/
	
	public function index(){
		
		// -------------------------------------------------
		// Parsing vars
		
		$f_params = $this->uri->ruri_to_assoc();
		
		$action =								isset( $f_params[ 'a' ] ) ? $f_params[ 'a' ] : 'ul'; // action
		$sub_action =							isset( $f_params[ 'sa' ] ) ? $f_params[ 'sa' ] : NULL; // sub action
		$submit_form_id =						isset( $f_params[ 'sfid' ] ) ? $f_params[ 'sfid' ] : NULL; // submit form id
		$user_submit_id =						isset( $f_params[ 'usid' ] ) ? $f_params[ 'usid' ] : NULL; // submit form id
		$ud_data_id =							isset( $f_params[ 'did' ] ) ? $f_params[ 'did' ] : NULL; // UniD data id
		$cp =									isset( $f_params[ 'cp' ] ) ? $f_params[ 'cp' ] : NULL; // current page
		$ipp =									isset( $f_params[ 'ipp' ] ) ? $f_params[ 'ipp' ] : NULL; // items per page
		$ob =									isset( $f_params[ 'ob' ] ) ? $f_params[ 'ob' ] : NULL; // order by
		$obd =									isset( $f_params[ 'obd' ] ) ? $f_params[ 'obd' ] : NULL; // order by direction
		$s =									isset( $f_params[ 's' ] ) ? ( int )( ( bool ) $f_params[ 's' ] ) : NULL; // search flag
		$f =									isset( $f_params[ 'f' ] ) ? json_decode( base64_decode( urldecode( $f_params[ 'f' ] ) ), TRUE ) : array(); // filters
		$sfsp =									isset( $f_params[ 'sfsp' ] ) ? json_decode( base64_decode( urldecode( $f_params[ 'sfsp' ] ) ), TRUE ) : array(); // search filters
		
		
		// Parsing vars
		// -------------------------------------------------
		
		$this->component_function = __FUNCTION__;
		$this->component_function_action = $action;
		
		
		// -------------------------------------------------
		// Params filtering
		
		// obtendo os parâmetros globais do componente
		$component_params = $this->current_component[ 'params' ];
		
		// obtendo os parâmetros do item de menu
		if ( $this->mcm->current_menu_item ){
			
			$menu_item_params = get_params( $this->mcm->current_menu_item[ 'params' ] );
			$data[ 'params' ] = filter_params( $component_params, $menu_item_params );
			
		}
		else{
			
			$menu_item_params = array();
			$data[ 'params' ] = $component_params;
			
		}
		
		// Params filtering
		// -------------------------------------------------
		
		/**************************************************/
		/******************* Submit form ******************/
		
		if (
			
			$action === 'sf'
			AND ( $submit_form_id AND is_numeric( $submit_form_id )
			AND is_int( $submit_form_id + 0 ) )
			AND check_var( $this->mcm->current_menu_item )
			AND (
				
				$data[ 'submit_form' ] = $this->sfcm->get_submit_forms(
					
					array(
						
						'where_condition' => 't1.id = ' . $submit_form_id,
						'limit' => 1,
						
					)
					
				)->row_array()
				
			)
			
		){
			
			$url = get_url( $this->uri->ruri_string() );
			set_last_url( $url );
			
			$submit_form = & $data[ 'submit_form' ];
			
			$this->sfcm->parse_sf( $submit_form, TRUE );
			
			// -------------------------------------------------
			// Access stuffs
			
			if ( check_var( $submit_form[ 'params' ][ 'ud_ds_access_type' ] ) AND $submit_form[ 'params' ][ 'ud_ds_access_type' ] != 3 ) {
				
				if ( ! $this->users->is_logged_in() ){
					
					if ( check_var( $submit_form[ 'params' ][ 'ud_ds_login_required_custom_msg' ] ) ) {
						
						msg( lang( $submit_form[ 'params' ][ 'ud_ds_login_required_custom_msg' ] ), 'warning' );
						
					}
					
					redirect( get_url( $this->users->get_link_login_page() ) );
					
				}
				
				// Have a account
				if ( $submit_form[ 'params' ][ 'ud_ds_access_type' ] == 4 ) {
					
					// some stuff
					
				}
				
				// Users
				else if ( $submit_form[ 'params' ][ 'ud_ds_access_type' ] == 2 ) {
					
					if ( ! ( check_var( $submit_form[ 'params' ][ 'ud_ds_access_type_users' ] ) AND in_array( $this->users->user_data[ 'id' ], $submit_form[ 'params' ][ 'ud_ds_access_type_users' ] ) ) ) {
						
						msg( lang( 'notif_ud_ds_no_access_to_current_user' ), 'warning' );
						
						redirect();
						
					}
					
				}
				
				// Users groups
				else if ( $submit_form[ 'params' ][ 'ud_ds_access_type' ] == 1 ) {
					
					if ( ! ( check_var( $submit_form[ 'params' ][ 'ud_ds_access_type_users_groups' ] ) AND in_array( $this->users->user_data[ 'group_id' ], $submit_form[ 'params' ][ 'ud_ds_access_type_users_groups' ] ) ) ) {
						
						msg( lang( 'notif_ud_ds_no_access_to_current_user' ), 'warning' );
						
						redirect();
						
					}
					
				}
				
			}
			
			// Access stuffs
			// -------------------------------------------------
			
			if ( $this->input->post() ){
				
				if ( check_var( $submit_form[ 'submit_forms_enable_xss_filtering' ] ) ) {
					
					$data[ 'post' ] = $this->input->post( NULL, TRUE );
					
				}
				else {
					
					$data[ 'post' ] = $this->input->post( NULL, FALSE );
					
				}
				
				// Quando os campos condicionais entram em ação, os campos ocultados, por exemplo
				// ainda seriam validados, corrigimos isso com esta variável.
				// Quando um campo condicional é ocultado no formulário, lá mesmo adicionamos
				// via javascript o conjunto de elementos que não devem ser validados
				// e recebemos aqui via POST
				$no_validation_fields = isset( $data[ 'post' ][ 'no_validation_fields' ] ) ? $data[ 'post' ][ 'no_validation_fields' ] : array();
				
			}
			
			// -------------------------------------------------
			// Params filtering
			
			$submit_form[ 'params' ] = filter_params( $menu_item_params, $submit_form[ 'params' ] );
			$submit_form[ 'params' ] = filter_params( $component_params, $submit_form[ 'params' ] );
			$data[ 'params' ] = array_merge_recursive_distinct( $data[ 'params' ], $submit_form[ 'params' ] );
			$data[ 'params' ] = filter_params( $component_params, $data[ 'params' ] );
			
			// Params filtering
			// -------------------------------------------------
			
			$submit_form[ 'url' ] = $url;
			
			foreach ( $submit_form[ 'fields' ] as $key => $field ) {
				
				$field_name = $field[ 'alias' ];
				
				$formatted_field_name = 'form[' . $field_name . ']';
				
				$rules = array( 'trim' );
				
				if ( ! check_var( $no_validation_fields[ $field_name ] ) ) {
					
					if ( isset( $field[ 'field_is_required' ] ) AND $field[ 'field_is_required' ] === '1' ){
						
						$rules[] = 'required';
						
					}
					
					if ( isset( $field[ 'validation_rule' ] ) AND is_array( $field[ 'validation_rule' ] ) ){
						
						foreach ( $field[ 'validation_rule' ] as $key => $rule ) {
							
							$comp = '';
							
							switch ( $rule ) {
								
								case 'matches':
									
									$comp .= '[form[' . $field[ 'validation_rule_parameter_matches'] . ']]';
									break;
									
								case 'min_length':
									
									$comp .= '[' . $field[ 'validation_rule_parameter_min_length'] . ']';
									break;
									
								case 'max_length':
									
									$comp .= '[' . $field[ 'validation_rule_parameter_max_length'] . ']';
									break;
									
								case 'exact_length':
									
									$comp .= '[' . $field[ 'validation_rule_parameter_exact_length'] . ']';
									break;
									
								case 'less_than':
									
									$comp .= '[' . $field[ 'validation_rule_parameter_less_than'] . ']';
									break;
									
								case 'DESC':
									
									$order_by_direction = 'ASC';
									break;
									
							}
							
							$rules[] = $rule . $comp;
							
						}
						
					}
					
				}
				
				// xss fieltering
				if ( check_var( $data[ 'params' ][ 'submit_form_param_send_email_to' ] ) ) {
					
					$rules[] = 'xss';
					
				}
				
				// articles
				if ( $field[ 'field_type' ] === 'articles' AND isset( $field[ 'articles_category_id' ] ) ) {
					
					$search_config = array(
						
						'plugins' => 'articles_search',
						'ipp' => 0,
						'allow_empty_terms' => TRUE,
						'plugins_params' => array(
							
							'articles_search' => array(
								
								'category_id' => $field[ 'articles_category_id' ],
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
				if ( $field[ 'field_type' ] === 'date' ) {
					
					if ( isset( $data[ 'post' ][ 'form' ][ $field[ 'alias' ] ] ) ) {
						
						$d = ( isset( $data[ 'post' ][ 'form' ][ $field[ 'alias' ] ][ 'd' ] ) AND $data[ 'post' ][ 'form' ][ $field[ 'alias' ] ][ 'd' ] != '' ) ? $data[ 'post' ][ 'form' ][ $field[ 'alias' ] ][ 'd' ] : '00';
						$m = ( isset( $data[ 'post' ][ 'form' ][ $field[ 'alias' ] ][ 'm' ] ) AND $data[ 'post' ][ 'form' ][ $field[ 'alias' ] ][ 'm' ] != '' ) ? $data[ 'post' ][ 'form' ][ $field[ 'alias' ] ][ 'm' ] : '00';
						$y = ( isset( $data[ 'post' ][ 'form' ][ $field[ 'alias' ] ][ 'y' ] ) AND $data[ 'post' ][ 'form' ][ $field[ 'alias' ] ][ 'y' ] != '' ) ? $data[ 'post' ][ 'form' ][ $field[ 'alias' ] ][ 'y' ] : '0000';
						
						$data[ 'post' ][ 'form' ][ $field[ 'alias' ] ] = $y . '-' . $m . '-' . $d;
						
					}
					
					$rules_d = $rules_m = $rules_y = $rules;
					
					if ( check_var( $field[ 'sf_date_field_day_is_req' ] ) ) {
						
						$rules_d[] = 'required';
						
					}
					if ( check_var( $field[ 'sf_date_field_month_is_req' ] ) ) {
						
						$rules_m[] = 'required';
						
					}
					if ( check_var( $field[ 'sf_date_field_year_is_req' ] ) ) {
						
						$rules_y[] = 'required';
						
					}
					
					$rules_d = join( '|', $rules_d );
					$rules_m = join( '|', $rules_m );
					$rules_y = join( '|', $rules_y );
					$this->form_validation->set_rules( $formatted_field_name . '[d]', sprintf( lang( 'sprintf_field_day' ), $field[ 'label' ] ), $rules_d );
					$this->form_validation->set_rules( $formatted_field_name . '[m]', sprintf( lang( 'sprintf_field_month' ), $field[ 'label' ] ), $rules_m );
					$this->form_validation->set_rules( $formatted_field_name . '[y]', sprintf( lang( 'sprintf_field_year' ), $field[ 'label' ] ), $rules_y );
					
				}
				else {
					
					
					
				}
				
				$rules = join( '|', $rules );
				
				$this->form_validation->set_rules( $formatted_field_name, lang( $field[ 'label' ] ), $rules );
				
			}
			
			if ( isset( $data[ 'post' ] ) AND $this->form_validation->run() ){
				
				// Definindo variável de limpeza de tags html
				// atualmente efetiva na views
				// @TODO: fazer a limpeza dos valores recebidos aqui, no controller, não nas views
				$data[ 'submit_forms_allow_html_tags' ] = check_var( $data[ 'params' ][ 'submit_forms_allow_html_tags' ] );
				
				/**************************************************************************************************/
				/************* Criando o array de emails para os quais as mensagens serão enviadas ****************/
				
				$emails_to = array();
				
				// Se os emails forem os do contato
				if ( check_var( $data[ 'params' ][ 'submit_form_param_send_email_to' ] ) AND $data[ 'params' ][ 'submit_form_param_send_email_to' ] == 'contact_emails' AND check_var( $data[ 'params' ][ 'submit_form_param_send_email_to_contact' ] ) ){
					
					$this->load->model( array( 'common/contacts_common_model' ) );
					
					// get contact params
					$gcp = array(
						
						'where_condition' => 't1.id = ' . $data[ 'params' ][ 'submit_form_param_send_email_to_contact' ],
						'limit' => 1,
						
						);
					
					$contact = $this->contacts_common_model->get_contacts( $gcp )->row_array();
					
					$contact[ 'emails' ] = get_params( $contact[ 'emails' ] );
					
					foreach ( $contact[ 'emails' ] as $key => $email ) {
						
						if ( isset( $email[ 'site_msg' ] ) AND $email[ 'site_msg' ] )
							$emails_to[] = $email[ 'email' ];
							
					}
					
				}
				// Caso contrário, se os emails são personalizados
				else if ( check_var( $data[ 'params' ][ 'submit_form_param_send_email_to' ] ) AND $data[ 'params' ][ 'submit_form_param_send_email_to' ] == 'custom_emails' AND check_var( $data[ 'params' ][ 'submit_form_param_send_email_to_custom_emails' ] ) ){
					
					$custom_emails = explode( "\n", $data[ 'params' ][ 'submit_form_param_send_email_to_custom_emails' ] );
					
					foreach ( $custom_emails as $key => $email ) {
						
						$emails_to[] = $email;
						
					}
					
				}
				
				/************* Criando o array de emails para os quais as mensagens serão enviadas ****************/
				/**************************************************************************************************/
				
				/**************************************************************************************************/
				/*************************** Definindo o tipo de layout a ser usado *******************************/
				$layout_source = NULL;
				
				$email_body = '';
				
				// Se o layout for da lista
				if ( check_var( $data[ 'params' ][ 'submit_form_sending_email_layout_source' ] ) AND $data[ 'params' ][ 'submit_form_sending_email_layout_source' ] === 'layouts_list' AND check_var( $data[ 'params' ][ 'submit_form_sending_email_layout_view' ] ) ){
					
					$layout_source = $data[ 'params' ][ 'submit_form_sending_email_layout_source' ];
					
					$layout = $data[ 'params' ][ 'submit_form_sending_email_layout_view' ];
					
					// verificando se o tema atual possui a view
					if ( file_exists( THEMES_PATH . call_user_func( $this->mcm->environment . '_theme_components_views_path' ) . $this->component_view_folder . DS . __FUNCTION__ . DS . 'sending_email' . DS . $layout . DS . 'sending_email.php') ){
						
						$email_body = $this->load->view( call_user_func( $this->mcm->environment . '_theme_components_views_path' ) . $this->component_view_folder . DS . __FUNCTION__ . DS . 'sending_email' . DS . $layout . DS . 'sending_email', $data, TRUE );
						
					}
					// verificando se a view existe no diretório de views padrão
					else if ( file_exists( VIEWS_PATH . get_constant_name( $this->mcm->environment . '_COMPONENTS_VIEWS_PATH' ) . DS . $this->component_view_folder . DS . __FUNCTION__ . DS . 'sending_email' . DS . $layout . DS . 'sending_email.php') ){
						
						$email_body = $this->load->view( get_constant_name( $this->mcm->environment . '_COMPONENTS_VIEWS_PATH' ) . $this->component_view_folder . DS . __FUNCTION__ . DS . 'sending_email' . DS . $layout . DS . 'sending_email', $data, TRUE );
						
					}
					else{
						
						$email_body = lang( 'load_view_fail' ) . ': <b>' . VIEWS_PATH . get_constant_name( $this->mcm->environment . '_COMPONENTS_VIEWS_PATH' ) . $this->component_view_folder . DS . __FUNCTION__ . DS . 'submit_form' . DS . $layout . DS . 'sending_email.php</b>';
						
					}
					
				}
				// Caso contrário, se for do layout personalizado
				else if ( check_var( $data[ 'params' ][ 'submit_form_sending_email_layout_source' ] ) AND $data[ 'params' ][ 'submit_form_sending_email_layout_source' ] === 'custom_layout' AND check_var( $data[ 'params' ][ 'submit_form_sending_email_layout_custom' ] ) ){
					
					$layout_source = $data[ 'params' ][ 'submit_form_sending_email_layout_source' ];
					
					$email_body = $data[ 'params' ][ 'submit_form_sending_email_layout_custom' ];
					
				}
				
				// Mensagem para o usuário (submitter)
				$send_email_to_submitter = ( check_var( $data[ 'params' ][ 'sfsmr_send_copy_to_submitter' ] ) AND check_var( $data[ 'params' ][ 'sfsmr_email_field' ] ) ) ? TRUE : FALSE;
				
				$submitter_layout_source = NULL;
				
				$submitter_email_body = '';
				
				// Se o layout for da lista
				if ( check_var( $data[ 'params' ][ 'sfsmr_layout_source' ] ) AND $data[ 'params' ][ 'sfsmr_layout_source' ] === 'layouts_list' AND check_var( $data[ 'params' ][ 'sfsmr_layout_view' ] ) ){
					
					$submitter_layout_source = $data[ 'params' ][ 'sfsmr_layout_source' ];
					
					$submitter_layout = $data[ 'params' ][ 'sfsmr_layout_view' ];
					
					// verificando se o tema atual possui a view
					if ( file_exists( THEMES_PATH . call_user_func( $this->mcm->environment . '_theme_components_views_path' ) . $this->component_view_folder . DS . __FUNCTION__ . DS . 'sending_email_submitter' . DS . $layout . DS . 'sending_email_submitter.php') ){
						
						$submitter_email_body = $this->load->view( call_user_func( $this->mcm->environment . '_theme_components_views_path' ) . $this->component_view_folder . DS . __FUNCTION__ . DS . 'sending_email_submitter' . DS . $layout . DS . 'sending_email_submitter', $data, TRUE );
						
					}
					// verificando se a view existe no diretório de views padrão
					else if ( file_exists( VIEWS_PATH . get_constant_name( $this->mcm->environment . '_COMPONENTS_VIEWS_PATH' ) . DS . $this->component_view_folder . DS . __FUNCTION__ . DS . 'sending_email_submitter' . DS . $layout . DS . 'sending_email_submitter.php') ){
						
						$submitter_email_body = $this->load->view( get_constant_name( $this->mcm->environment . '_COMPONENTS_VIEWS_PATH' ) . $this->component_view_folder . DS . __FUNCTION__ . DS . 'sending_email_submitter' . DS . $layout . DS . 'sending_email_submitter', $data, TRUE );
						
					}
					else{
						
						$submitter_email_body = lang( 'load_view_fail' ) . ': <b>' . VIEWS_PATH . get_constant_name( $this->mcm->environment . '_COMPONENTS_VIEWS_PATH' ) . $this->component_view_folder . DS . __FUNCTION__ . DS . 'submit_form' . DS . $layout . DS . 'sending_email.php</b>';
						
					}
					
				}
				// Caso contrário, se for do layout personalizado
				else if ( check_var( $data[ 'params' ][ 'sfsmr_layout_source' ] ) AND $data[ 'params' ][ 'sfsmr_layout_source' ] === 'custom_layout' AND check_var( $data[ 'params' ][ 'sfsmr_layout_custom' ] ) ){
					
					$submitter_layout_source = $data[ 'params' ][ 'sfsmr_layout_source' ];
					
					$submitter_email_body = $data[ 'params' ][ 'sfsmr_layout_custom' ];
					
				}
				
				// inicializando variáveis de substituição dos campos
				$find = array();
				$replace = array();
				
				
				/**********************************************/
				/******** Inserting user submit into DB *******/
				
				// Adjusting not showed fields
				
				$_tmp = array();
				
				foreach ( $submit_form[ 'fields' ] as $k => $field ) {
					
					if ( ! check_var( $field[ 'availability' ][ 'site' ] ) AND check_var( $field[ 'default_value' ] ) ) {
						
						$_tmp[ $field[ 'alias' ] ] = $field[ 'default_value' ];
						
					}
					
				}
				
				if ( $_tmp ) {
					
					$post[ 'form' ] = array_merge_recursive_distinct( $post[ 'form' ], $_tmp );
					
				}
				
				$db_data = elements( array(
					
					'submit_form_id',
					'submit_datetime',
					'mod_datetime',
					
				), $data[ 'post' ] );
				
				$submit_datetime = gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] );
				$submit_datetime = strftime( '%Y-%m-%d %T', $submit_datetime );
				
				$db_data[ 'submit_form_id' ] = $submit_form_id;
				$db_data[ 'submit_datetime' ] = $submit_datetime;
				$db_data[ 'mod_datetime' ] = $submit_datetime;
				
				$user_submit_inserted = FALSE;
				
				if ( $user_submit_id = $this->sfcm->insert_user_submit( $db_data ) ){
					
					$user_submit_inserted = TRUE;
					
					$find[] = '{user_submit_id}';
					$replace[] = $user_submit_id;
					
				}
				else{
					
					redirect_last_url();
					
				}
				
				/******** Inserting user submit into DB *******/
				/**********************************************/
				
				/**********************************************/
				/********** Subistituição dos campos **********/
				
				foreach ( $submit_form[ 'fields' ] as $key => $field ) {
					
					if ( ! in_array( $field[ 'field_type' ], array( 'html' ) ) ){
						
						$field_label = $field[ 'label' ];
						$field_presentation_label = isset( $field[ 'presentation_label' ] ) ? $field[ 'presentation_label' ] : $field_label;
						$field_alias = $field[ 'alias' ];
						$formatted_field_name = 'form[' . $field_alias . ']';
						$field_value = '';
						
						if ( isset( $data[ 'post' ][ 'form' ][ $field_alias ] ) ) {
							
							if ( $field[ 'field_type' ] == 'date' ){
								
								$format = '';
								
								$format .= $field[ 'sf_date_field_use_year' ] ? 'y' : '';
								$format .= $field[ 'sf_date_field_use_month' ] ? 'm' : '';
								$format .= $field[ 'sf_date_field_use_day' ] ? 'd' : '';
								
								$format = 'sf_us_dt_ft_pt_' . $format . '_' . $field[ 'sf_date_field_presentation_format' ];
								
								$field_value =  strftime( lang( $format ), strtotime( $field_value ) );
								
							}
							else if ( in_array( $field[ 'field_type' ], array( 'checkbox', 'combo_box', ) ) ){
								
								$_field_value = array();
								
								if ( is_array( $data[ 'post' ][ 'form' ][ $field_alias ] ) ) {
									
									foreach ( $data[ 'post' ][ 'form' ][ $field_alias ] as $k => $value ) {
										
										if ( is_string( $value ) ) {
											
											if ( check_var( $field[ 'options_from_users_submits' ] ) AND ( check_var( $field[ 'options_title_field' ] ) OR check_var( $field[ 'options_title_field_custom' ] ) ) AND is_numeric( $value ) AND $_user_submit = $this->sfcm->get_user_submit( $value ) ) {
												
												$value = isset( $_user_submit[ 'data' ][ $field[ 'options_title_field' ] ] ) ? $_user_submit[ 'data' ][ $field[ 'options_title_field' ] ] : $_user_submit[ 'id' ];
												
												$_field_value[] = $value;
												
											}
											else {
												
												$_field_value[] = $value;
												
											}
											
										}
										
									}
									
									$field_value = join( ', ', $_field_value );
									
								}
								else {
									
									if ( check_var( $field[ 'options_from_users_submits' ] ) AND ( check_var( $field[ 'options_title_field' ] ) OR check_var( $field[ 'options_title_field_custom' ] ) ) AND is_numeric( $data[ 'post' ][ 'form' ][ $field_alias ] ) AND $_user_submit = $this->sfcm->get_user_submit( $data[ 'post' ][ 'form' ][ $field_alias ] ) ) {
										
										$field_value = isset( $_user_submit[ 'data' ][ $field[ 'options_title_field' ] ] ) ? $_user_submit[ 'data' ][ $field[ 'options_title_field' ] ] : $_user_submit[ 'id' ];
										
									}
									
								}
								
							}
							else if ( isset( $data[ 'post' ][ 'form' ][ $field_alias ] ) ) {
								
								$field_value = $data[ 'post' ][ 'form' ][ $field_alias ];
								
							}
							
						}
						
						
						$find[] = '{' . $field_alias . ':' . 'presentation_label}';
						$find[] = '{' . $field_alias . ':' . 'label}';
						$find[] = '{' . $field_alias . ':' . 'value}';
						
						$replace[] = $field_presentation_label;
						$replace[] = $field_label;
						$replace[] = $field_value;
						
					}
					
				}
				
				$email_body = str_replace( $find, $replace, $email_body );
				// Relativo a mensagem para o usuário (submitter)
				$submitter_email_body = str_replace( $find, $replace, $submitter_email_body );
				
				/********** Subistituição dos campos **********/
				/**********************************************/
				


				/**********************************************/
				/******* Subistituição dos outros campos ******/

				$email_from = str_replace( $find, $replace, $data[ 'params' ][ 'submit_form_param_send_email_to_from' ] );
				$email_from_name = str_replace( $find, $replace, $data[ 'params' ][ 'submit_form_param_send_email_to_from_name' ] );
				$email_reply_to = str_replace( $find, $replace, $data[ 'params' ][ 'submit_form_param_send_email_to_reply_to' ] );
				$emails_to = $emails_to;
				$emails_cc = str_replace( $find, $replace, $data[ 'params' ][ 'submit_form_param_send_email_to_cc' ] );
				$emails_bcc = str_replace( $find, $replace, $data[ 'params' ][ 'submit_form_param_send_email_to_bcc' ] );
				$email_subject = str_replace( $find, $replace, $data[ 'params' ][ 'submit_form_param_send_email_to_subject' ] );

				// Relativo a mensagem para o usuário (submitter)
				if ( $send_email_to_submitter ) {

					$submitter_email_from = str_replace( $find, $replace, $data[ 'params' ][ 'sfsmr_from' ] );
					$submitter_email_from_name = str_replace( $find, $replace, $data[ 'params' ][ 'sfsmr_from_name' ] );
					$submitter_email_reply_to = str_replace( $find, $replace, $data[ 'params' ][ 'sfsmr_reply_to' ] );
					$submitter_email_to = str_replace( $find, $replace, $data[ 'params' ][ 'sfsmr_email_field' ] );
					$submitter_email_subject = str_replace( $find, $replace, $data[ 'params' ][ 'sfsmr_subject' ] );

				}

				/******* Subistituição dos outros campos ******/
				/**********************************************/



				/**********************************************/
				/*** Subistituição dos campos nas mensagens ***/
				
				if ( check_var( $data[ 'params' ][ 'sfpsm_user_submit_save_into_db_success_title' ] ) ) {
					
					$data[ 'params' ][ 'sfpsm_user_submit_save_into_db_success_title' ] = str_replace( $find, $replace, $data[ 'params' ][ 'sfpsm_user_submit_save_into_db_success_title' ] );
					
				}
				
				if ( check_var( $data[ 'params' ][ 'sfpsm_user_submit_save_into_db_success' ] ) ) {
					
					$data[ 'params' ][ 'sfpsm_user_submit_save_into_db_success' ] = str_replace( $find, $replace, $data[ 'params' ][ 'sfpsm_user_submit_save_into_db_success' ] );
					
				}
				
				if ( check_var( $data[ 'params' ][ 'sfpsm_user_submit_save_into_db_error_title' ] ) ) {
					
					$data[ 'params' ][ 'sfpsm_user_submit_save_into_db_error_title' ] = str_replace( $find, $replace, $data[ 'params' ][ 'sfpsm_user_submit_save_into_db_error_title' ] );
					
				}
				
				if ( check_var( $data[ 'params' ][ 'sfpsm_user_submit_save_into_db_error' ] ) ) {
					
					$data[ 'params' ][ 'sfpsm_user_submit_save_into_db_error' ] = str_replace( $find, $replace, $data[ 'params' ][ 'sfpsm_user_submit_save_into_db_error' ] );
					
				}
				
				if ( check_var( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_success_title' ] ) ) {
					
					$data[ 'params' ][ 'sfpsm_user_submit_email_sent_success_title' ] = str_replace( $find, $replace, $data[ 'params' ][ 'sfpsm_user_submit_email_sent_success_title' ] );
					
				}
				
				if ( check_var( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_success' ] ) ) {
					
					$data[ 'params' ][ 'sfpsm_user_submit_email_sent_success' ] = str_replace( $find, $replace, $data[ 'params' ][ 'sfpsm_user_submit_email_sent_success' ] );
					
				}
				
				if ( check_var( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_validation_error_title' ] ) ) {
					
					$data[ 'params' ][ 'sfpsm_user_submit_email_sent_validation_error_title' ] = str_replace( $find, $replace, $data[ 'params' ][ 'sfpsm_user_submit_email_sent_validation_error_title' ] );
					
				}
				
				if ( check_var( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_validation_error' ] ) ) {
					
					$data[ 'params' ][ 'sfpsm_user_submit_email_sent_validation_error' ] = str_replace( $find, $replace, $data[ 'params' ][ 'sfpsm_user_submit_email_sent_validation_error' ] );
					
				}
				
				if ( check_var( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_internal_error_title' ] ) ) {
					
					$data[ 'params' ][ 'sfpsm_user_submit_email_sent_internal_error_title' ] = str_replace( $find, $replace, $data[ 'params' ][ 'sfpsm_user_submit_email_sent_internal_error_title' ] );
					
				}
				
				if ( check_var( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_internal_error' ] ) ) {
					
					$data[ 'params' ][ 'sfpsm_user_submit_email_sent_internal_error' ] = str_replace( $find, $replace, $data[ 'params' ][ 'sfpsm_user_submit_email_sent_internal_error' ] );
					
				}
				
				if ( check_var( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_submitter_success_title' ] ) ) {
					
					$data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_submitter_success_title' ] = str_replace( $find, $replace, $data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_submitter_success_title' ] );
					
				}
				
				if ( check_var( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_submitter_success' ] ) ) {
					
					$data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_submitter_success' ] = str_replace( $find, $replace, $data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_submitter_success' ] );
					
				}
				
				if ( check_var( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_submitter_internal_error_title' ] ) ) {
					
					$data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_submitter_internal_error_title' ] = str_replace( $find, $replace, $data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_submitter_internal_error_title' ] );
					
				}
				
				if ( check_var( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_submitter_internal_error' ] ) ) {
					
					$data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_submitter_internal_error' ] = str_replace( $find, $replace, $data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_submitter_internal_error' ] );
					
				}
				
				/*** Subistituição dos campos nas mensagens ***/
				/**********************************************/
				
				
				if ( $user_submit_inserted ){
					
					if ( check_var( $data[ 'params' ][ 'sfpsm_user_submit_save_into_db_success_title' ] ) ) {
						
						msg( lang( $data[ 'params' ][ 'sfpsm_user_submit_save_into_db_success_title' ] ), 'title' );
						
					}
					
					if ( check_var( $data[ 'params' ][ 'sfpsm_user_submit_save_into_db_success' ] ) ) {
						
						msg( lang( $data[ 'params' ][ 'sfpsm_user_submit_save_into_db_success' ] ), 'success' );
						
					}
					
				}
				else{
					
					if ( check_var( $data[ 'params' ][ 'sfpsm_user_submit_save_into_db_error_title' ] ) ) {
						
						msg( lang( $data[ 'params' ][ 'sfpsm_user_submit_save_into_db_error_title' ] ), 'title' );
						
					}
					
					if ( check_var( $data[ 'params' ][ 'sfpsm_user_submit_save_into_db_error' ] ) ) {
						
						msg( lang( $data[ 'params' ][ 'sfpsm_user_submit_save_into_db_error' ] ), 'error' );
						
					}
					
					redirect_last_url();
					
				}
				
				
				/**********************************************/
				/********* Updating user submit on DB *********/
				
				$db_update_data = elements( array(
					
					'output',
					'output_submitter',
					'data',
					
				), $data[ 'post' ] );
				
				$db_update_data[ 'params' ] = array();
				$db_update_data[ 'output' ] = $email_body;
				$db_update_data[ 'output_submitter' ] = $submitter_email_body;
				$db_update_data[ 'data' ] = json_encode( $data[ 'post' ][ 'form' ] );
				
				if ( $this->users->is_logged_in() ){
					
					$db_update_data[ 'params' ][ 'created_by_user_id' ] = $this->users->user_data[ 'id' ];
					
				}
				
				$db_update_data[ 'params' ] = json_encode( $db_update_data[ 'params' ] );
				
				if ( $this->sfcm->update_user_submit( $db_update_data, array( 'id' => $user_submit_id ) ) ){
					
					log_message( 'debug', '[Submit forms] User submit (' . $user_submit_id . ') updated successfully' );
					
				}
				
				/********* Updating user submit on DB *********/
				/**********************************************/
				
				
				
				/**********************************************/
				/*************** Sending e-mails **************/
				
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
					$this->email->cc( $emails_cc);
					$this->email->bcc( $emails_bcc);
					$this->email->subject( $email_subject );
					$this->email->message( $email_body );
					
					$errors_messages = 0;
					$success_messages = NULL;
					$info_messages = NULL;
					
					if ( check_var( $data[ 'params' ][ 'submit_form_param_email_receiving' ] ) ){
						
						if ( $this->email->send() ){
							
							if ( check_var( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_success_title' ] ) ) {
								
								msg( lang( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_success_title' ] ), 'title' );
								
							}
							
							if ( check_var( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_success' ] ) ) {
								
								msg( lang( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_success' ] ), 'success' );
								
							}
							
						}
						else{
							
							if ( check_var( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_internal_error_title' ] ) ) {
								
								msg( lang( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_internal_error_title' ] ), 'title' );
								
							}
							
							if ( check_var( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_internal_error' ] ) ) {
								
								msg( lang( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_internal_error' ] ), 'error' );
								
							}
							
							log_message( 'error', '[Submit forms] An internal error occurred while trying to send the message!' );
							
							$errors_messages ++;
							
						}
						
					}
					
					// Sending to submitter
					if ( $send_email_to_submitter ) {
						
						$this->email->clear();
						
						$this->email->from( $submitter_email_from, $submitter_email_from_name );
						$this->email->reply_to( $submitter_email_reply_to );
						$this->email->to( $submitter_email_to );
						$this->email->subject( $submitter_email_subject );
						$this->email->message( $submitter_email_body );
						
						if ( $this->email->send() ){
							
							if ( check_var( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_submitter_success_title' ] ) ) {
								
								msg( lang( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_submitter_success_title' ] ), 'title' );
								
							}
							
							if ( check_var( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_submitter_success' ] ) ) {
								
								msg( lang( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_submitter_success' ] ), 'success' );
								
							}
							
						}
						else{
							
							if ( check_var( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_submitter_internal_error_title' ] ) ) {
								
								msg( lang( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_submitter_internal_error_title' ] ), 'title' );
								
							}
							
							if ( check_var( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_submitter_internal_error' ] ) ) {
								
								msg( lang( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_submitter_internal_error' ] ), 'error' );
								
							}
							
							log_message( 'error', '[Submit forms] An internal error occurred while trying to send the message to submitter!' );
							
							$errors_messages ++;
							
						}
						
					}
					
				}
				
				/*************** Sending e-mails **************/
				/**********************************************/
				
				if ( ! $errors_messages ) {
					
					if ( check_var( $data[ 'params' ][ 'sf_redirecting_on_success_source' ] ) AND $data[ 'params' ][ 'sf_redirecting_on_success_source' ] == 'custom_url' AND check_var( $data[ 'params' ][ 'sf_redirecting_on_success' ] ) ) {
						
						redirect( get_url( $data[ 'params' ][ 'sf_redirecting_on_success' ] ) );
						
					}
					else if ( check_var( $data[ 'params' ][ 'sf_redirecting_on_success_source' ] ) AND $data[ 'params' ][ 'sf_redirecting_on_success_source' ] == 'custom_url' ) {
						
						redirect( get_url() );
						
					}
					else {
						
						redirect_last_url();
						
					}
					
				}
				
			}
			
			else if ( ! $this->form_validation->run() AND validation_errors() != '' ){
				
				$data[ 'post' ] = $this->input->post();
				
				if ( check_var( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_validation_error_title' ] ) ) {
					
					msg( lang( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_validation_error_title' ] ), 'title' );
					
				}
				
				if ( check_var( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_validation_error' ] ) ) {
					
					msg( lang( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_validation_error' ] ), 'error' );
					
				}
				
				msg( validation_errors( '<div class="error">', '</div>' ), 'error' );
				
			}
			
			$this->_page(
				
				array(
					
					'component_view_folder' => $this->component_view_folder,
					'function' => __FUNCTION__,
					'action' => 'submit_form',
					'layout' => ( ( isset( $data[ 'params' ][ 'submit_form_layout' ] ) AND $data[ 'params' ][ 'submit_form_layout' ] ) ? $data[ 'params' ][ 'submit_form_layout' ] : 'default' ),
					'view' => 'submit_form',
					'data' => $data,
					
				)
				
			);
			
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		else if ( $action === 'us' AND ( $submit_form_id AND is_numeric( $submit_form_id ) AND is_int( $submit_form_id + 0 ) ) ){
			
			$layout = $menu_item_params[ 'users_submits_layout' ];
			
			// get submit form params
			$gsfp = array(
				
				'where_condition' => 't1.id = ' . $submit_form_id,
				'limit' => 1,
				
			);
			
			if ( ( $submit_form = $this->sfcm->get_submit_forms( $gsfp )->row_array() ) ){
				
				// -------------------------------------------------
				// Params filtering
				
				// Params filtering
				// -------------------------------------------------
				
				$this->load->helper( array( 'pagination' ) );
				$this->load->library( 'search' );
				
				$this->sfcm->parse_sf( $submit_form, TRUE );
				
				$data[ 'submit_form' ] = & $submit_form;
				
				$search_config = array(
					
					'plugins' => 'sf_us_search',
					'allow_empty_terms' => TRUE,
					'plugins_params' => array(
						
						'sf_us_search' => array(
							
							'sf_id' => $submit_form_id,
							'menu_item_id' => $this->mcm->current_menu_item[ 'id' ],
							
						),
						
					),
					
				);
				
				if ( $this->input->post() ){
					
					$data[ 'post' ] = $this->input->post( NULL, TRUE);
					
				}
				else {
					
					$data[ 'post' ] = NULL;
					
				}
				
				if ( $this->input->get() ){
					
					$data[ 'get' ] = $this->input->get();
					
				}
				else {
					
					$data[ 'get' ] = NULL;
					
				}
				
				$get_query = array();
				$ob_fields = array(
					
					'id',
					'submit_datetime',
					'mod_datetime',
					'random',
					
				);
				
				foreach( $data[ 'submit_form' ][ 'fields' ] as $k => $field ){
					
					if ( ! in_array( $field[ 'field_type' ], array( 'html', 'button' ) ) ) {
						
						$ob_fields[] = isset( $field[ 'alias' ] ) ? $field[ 'alias' ] : ( isset( $field[ 'presentation_label' ] ) ? $field[ 'presentation_label' ] : ( isset( $field[ 'label' ] ) ? $field[ 'label' ] : '' ) );
						
					}
					
				}
				
				/*
				********************************************************
				--------------------------------------------------------
				Ordenção
				--------------------------------------------------------
				*/
				
				if ( isset( $data[ 'post' ][ 'users_submits_search' ][ 'order_by' ] ) ) {
					
					$ob = $data[ 'post' ][ 'users_submits_search' ][ 'order_by' ];
					
				}
				else if ( isset( $data[ 'get' ][ 'ob' ] ) ) {
					
					$ob = urldecode( $data[ 'get' ][ 'ob' ] );
					
				}
				else if ( isset( $f_params[ 'ob' ] ) ) {
					
					$ob = $f_params[ 'ob' ];
					
				}
				else if ( isset( $data[ 'params' ][ 'users_submits_order_by_field' ] ) ) {
					
					$ob = $data[ 'params' ][ 'users_submits_order_by_field' ];
					
				}
				
				if ( isset( $data[ 'post' ][ 'users_submits_search' ][ 'order_by_direction' ] ) ) {
					
					$obd = $data[ 'post' ][ 'users_submits_search' ][ 'order_by_direction' ];
					
				}
				else if ( isset( $data[ 'get' ][ 'obd' ] ) ) {
					
					$obd = urldecode( $data[ 'get' ][ 'obd' ] );
					
				}
				else if ( isset( $f_params[ 'ob' ] ) ) {
					
					$obd = $f_params[ 'ob' ];
					
				}
				else if ( isset( $data[ 'params' ][ 'users_submits_order_by_direction' ] ) ) {
					
					$obd = $data[ 'params' ][ 'users_submits_order_by_direction' ];
					
				}
				
				if ( $ob == 'random' OR $obd == 'random' ) {
					
					$search_config[ 'plugins_params' ][ 'sf_us_search' ][ 'random' ] = TRUE;
					
				}
				else {
					
					$search_config[ 'plugins_params' ][ 'sf_us_search' ][ 'order_by' ] = $data[ 'order_by' ] = $ob;
					$search_config[ 'plugins_params' ][ 'sf_us_search' ][ 'order_by_direction' ] = $data[ 'order_by_direction' ] = $obd;
					$search_config[ 'order_by' ][ 'sf_us_search' ] = $ob;
					$search_config[ 'order_by_direction' ][ 'sf_us_search' ] = $obd;
					
				}
				
				/*
				--------------------------------------------------------
				Ordenção
				--------------------------------------------------------
				********************************************************
				*/
				
				/*
				********************************************************
				--------------------------------------------------------
				Search flag
				--------------------------------------------------------
				*/
				
				if ( ! check_var( $s ) AND ( isset( $data[ 'post' ][ 'users_submits_search' ] ) OR
					( isset( $data[ 'get' ][ 's' ] ) AND $data[ 'get' ][ 's' ] ) ) ) {
					
					$s = 1;
					
				}
				
				$data[ 'search_mode' ] = $s;
				
				/*
				--------------------------------------------------------
				Search flag
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
				
				$_default_results_filters = check_var( $data[ 'params' ][ 'us_default_results_filters' ] ) ? json_decode( $data[ 'params' ][ 'us_default_results_filters' ], TRUE ) : array();
				$_default_results_filters = is_array( $_default_results_filters ) ? $_default_results_filters : array();
				
				if ( $_default_results_filters AND empty( $f ) ){
					
					$f = $_default_results_filters;
					
				}
				
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
					
					if ( $_default_results_filters ){
						
						$f = array_merge_recursive( $f, $_default_results_filters );
						
					}
					
				}
				
				//echo '<pre>' . print_r( $f, TRUE ) . '</pre>'; exit;
				
				$search_config[ 'plugins_params' ][ 'sf_us_search' ][ 'filters' ] = $f;
				
				$filters_url = urlencode( base64_encode( json_encode( $f ) ) );
				
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
				
				if ( isset( $data[ 'post' ][ 'users_submits_search' ][ 'ipp' ] ) ) {
					
					$ipp = $data[ 'post' ][ 'users_submits_search' ][ 'ipp' ];
					
				}
				else if ( isset( $data[ 'get' ][ 'ipp' ] ) ) {
					
					$ipp = urldecode( $data[ 'get' ][ 'ipp' ] );
					
				}
				else if ( isset( $f_params[ 'ipp' ] ) ) {
					
					$ipp = $f_params[ 'ipp' ];
					
				}
				else if ( isset( $data[ 'params' ][ 'users_submits_items_per_page' ] ) ) {
					
					$ipp = $data[ 'params' ][ 'users_submits_items_per_page' ];
					
				}
				
				if ( isset( $data[ 'post' ][ 'users_submits_search' ][ 'submit_search' ] ) ) {
					
					$cp = 1;
					
				}
				
				if ( $cp < 1 OR ! is_numeric( $cp ) ) $cp = 1;
				if ( $ipp < -1 OR ! is_numeric( $ipp ) ) $ipp = $this->mcm->filtered_system_params[ 'site_items_per_page' ];
				
				$search_config[ 'ipp' ] = $ipp;
				$search_config[ 'cp' ] = $cp;
				
				$users_submits = array();
				
				if ( check_var( $data[ 'post' ][ 'users_submits_search' ][ 'submit_search' ] ) OR
					check_var( $data[ 'post' ][ 'users_submits_search' ][ 'submit_search' ] ) OR
					check_var( $s ) OR
					( ! check_var( $data[ 'params' ][ 'use_search' ] ) ) OR
					( check_var( $data[ 'params' ][ 'use_search' ] ) AND check_var( $data[ 'params' ][ 'show_default_results' ] ) ) ) {
					
					$this->search->config( $search_config );
					$users_submits = $this->search->get_full_results( 'sf_us_search', TRUE );
					
				}
				
				if ( isset( $this->mcm->current_menu_item ) ) {
					
					$miid = $this->mcm->current_menu_item[ 'id' ];
					
				}
				else {
					
					$miid = 0;
					
				}
				
				foreach( $get_query as $k => & $query ) {
					
					$query = $k . '=' . urlencode( $query );
					
				}
				
				$get_query = ! empty( $get_query ) ? '?' . join( '&', $get_query ) : '';
				$s = $s ? '/s/1' : '';
				
				$sfsp = urlencode( base64_encode( json_encode( $sfsp ) ) );
				
				$pagination_url = 'submit_forms/index' . '/miid/' . $miid . '/a/us/sfid/' . $submit_form_id . $s . '/sfsp/' . $sfsp . '/f/' . $filters_url . '/cp/%p%/ipp/%ipp%' . $get_query;
				
				$data[ 'page_url' ] = 'submit_forms/index' . '/miid/' . $miid . '/a/us/sfid/' . $submit_form_id . $s . '/sfsp/' . $sfsp . '/f/' . $filters_url . $get_query;
// 				echo $data[ 'page_url' ];
				$data[ 'users_submits_total_results' ] = $this->search->count_all_results( 'sf_us_search' );
				
				$data[ 'pagination' ] = get_pagination( $pagination_url, $cp, $ipp, $data[ 'users_submits_total_results' ] );
				
				/*
				--------------------------------------------------------
				Paginação
				--------------------------------------------------------
				********************************************************
				*/
				
				//print_r( $data[ 'params' ] );
				
				if ( check_var( $data[ 'params' ][ 'fields_to_show' ] ) ) {
					
					foreach ( $data[ 'params' ][ 'fields_to_show' ] as $key => $value ) {
						
						if ( $value == '0' ) {
							
							unset( $data[ 'params' ][ 'fields_to_show' ][ $key ] );
							
						}
						
					}
					
				}
				
				$data[ 'users_submits' ] = $users_submits;
				
				$this->_page(
					
					array(
						
						'component_view_folder' => $this->component_view_folder,
						'function' => __FUNCTION__,
						'action' => 'users_submits',
						'layout' => ( ( check_var( $data[ 'params' ][ 'users_submits_layout' ] ) ) ? $data[ 'params' ][ 'users_submits_layout' ] : 'default' ),
						'view' => 'users_submits',
						'data' => $data,
						
					)
					
				);
				
				
			}
			
		}
		
		else if ( $action === 'dd' AND ( $ud_data_id AND is_numeric( $ud_data_id ) AND $ud_data_id > 0 ) ){
			
			$layout = isset( $menu_item_params[ 'user_submit_layout' ] ) ? $menu_item_params[ 'user_submit_layout' ] : 'default';
			
			$this->load->library( 'search' );
			
			$search_config = array(
				
				'plugins' => 'sf_us_search',
				'allow_empty_terms' => TRUE,
				'plugins_params' => array(
					
					'sf_us_search' => array(
						
						'us_id' => $ud_data_id,
						'menu_item_id' => $this->mcm->current_menu_item[ 'id' ],
						
					),
					
				),
				
			);
			
			$this->search->config( $search_config );
			$user_submit = $this->search->get_full_results( 'sf_us_search', TRUE );
			
			$user_submit = $user_submit[ 0 ];
			
			$submit_form_id = $user_submit[ 'submit_form_id' ];
			
			// get submit form params
			$gsfp = array(
				
				'where_condition' => 't1.id = ' . $submit_form_id,
				'limit' => 1,
				
			);
			
			$submit_form = $this->sfcm->get_submit_forms( $gsfp )->row_array();
				
			if ( $user_submit AND $submit_form ) {
				
				$this->sfcm->parse_sf( $submit_form, TRUE );
				
				$data[ 'submit_form' ] = & $submit_form;
				
				$data[ 'user_submit' ] = & $user_submit;
				
				if ( @$data[ 'params' ][ 'custom_page_title' ] ) {
					
					$this->mcm->html_data[ 'content' ][ 'title' ] = $data[ 'params' ][ 'custom_page_title' ];
					
				}
				else {
					
					$this->mcm->html_data[ 'content' ][ 'title' ] = $this->unid->get_data_title_prop_html( $submit_form, $user_submit );
					
				}
				
				$this->_page(
					
					array(
						
						'component_view_folder' => $this->component_view_folder,
						'function' => __FUNCTION__,
						'action' => 'user_submit',
						'layout' => $layout,
						'view' => 'user_submit',
						'data' => $data,
						
					)
					
				);
				
				
			}
			
		}
		
		else{

			show_404();

		}

		/******************* Submit form ******************/
		/**************************************************/

	}
	
	/******************************* Component index ******************************/
	/******************************************************************************/
	/******************************************************************************/
	
}
