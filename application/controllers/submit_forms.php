<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

require( APPPATH . 'controllers/main.php' );

class Submit_forms extends Main {
	
	public function __construct(){
		
		parent::__construct();
		
		if ( ! $this->load->is_model_loaded( 'sfcm' ) ) {
			
			$this->load->model( 'common/submit_forms_common_model', 'sfcm' );
			
		}
		
		$this->load->language( 'submit_forms' );
		
		$this->load->language( array( 'admin/submit_forms', 'admin/unid' ) );
		
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
								
								if ( $user[ 'password' ] != $this->users->encode_password( $password ) ) {
									
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
								
								if ( ! $this->users->_check_privileges( 'priv_ds_api', $user ) ){
									
									$submit_form = array(
										
										'id' => $sfpid,
										'error' => ( lang( 'access_denied_priv_ds_api' ) ),
										
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
		$ud_d_id =								isset( $f_params[ 'usid' ] ) ? $f_params[ 'usid' ] : NULL; // submit form id
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
			
			$data[ 'post' ] = FALSE;
			
			$xss_filtering = TRUE;
			
			if ( isset( $submit_form[ 'params' ][ 'ud_data_props_enable_xss_filtering_site' ] ) ) {
				
				if ( ! check_var( $submit_form[ 'params' ][ 'ud_data_props_enable_xss_filtering_site' ] ) ) {
					
					$xss_filtering = FALSE;
					
				}
				
			}
			
			if ( $data[ 'post' ] = $this->input->post( NULL, $xss_filtering ) ){
				
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
			
			// -------------------------------------------------
			// User registration stuff
			
			/*
			 $users_fields
			 array contendo os campos de usuários
			*/
			$users_fields =
			
			/*
			 $allow_user_registration
			 somente permite cadastro de usuários se:
			 - existirem campos de usuário
			 - o formulário atual possui um grupo padrão selecionado
			 - nos campos de usuário existir pelo menos campos relacionados
			   ao "nome" e "email" dos usuários. Se não existir campo relacionado
			   ao username (nome de login), o username será o e-mail
			*/
			$allow_user_registration =
			
			/*
			 $form_ok_to_registration
			 somente permite cadastro de usuários se existirem pelo menos
			 os campos ligados ao "nome" e "email" dos usuários existirem no formulário
			*/
			$form_ok_to_registration =
			
			/*
			 $uf_username
			 define se o campo de usuário "username" existe
			*/
			$uf_username =
			
			/*
			 $uf_name
			 define se o campo de usuário "name" existe
			*/
			$uf_name =
			
			/*
			 $uf_email
			 define se o campo de usuário "email" existe
			*/
			$uf_email =
			
			/*
			 $ds_user_username_field (data scheme user username field)
			 cópia array do campo do formulário ligado ao campo de usuário "username"
			*/
			$ds_user_username_field =
			
			/*
			 $ds_user_name_field (data scheme user name field)
			 cópia array do campo do formulário ligado ao campo de usuário "name"
			*/
			$ds_user_name_field =
			
			/*
			 $ds_user_email_field (data scheme user email field)
			 cópia array do campo do formulário ligado ao campo de usuário "email"
			*/
			$ds_user_email_field =
			
			/*
			 $uf_username_field_alias (user field username field alias)
			 aliás do campo de usuário "username" (não do campo do formulário)
			*/
			$uf_username_field_alias =
			
			/*
			 $uf_name_field_alias (user field name field alias)
			 aliás do campo de usuário "name" (não do campo do formulário)
			*/
			$uf_name_field_alias =
			
			/*
			 $uf_email_field_alias (user field email field alias)
			 aliás do campo de usuário "email" (não do campo do formulário)
			*/
			$uf_email_field_alias =
			
			/*
			 $uf_unique
			 array contendo as cópias dos campos do formulário ligados aos campos
			 de usuários únicos (as chaves são os aliases dos users fields, não dos campos)
			*/
			$uf_unique = FALSE;
			
			
			// checa se existem campos de usuários e o grupo de usuários padrão para novos registros
			if ( check_var( $this->current_component[ 'params' ][ 'users_fields' ] ) AND check_var( $submit_form[ 'params' ][ 'ud_ds_default_user_group_registered_from_form' ] ) ) {
				
				$users_fields = $this->current_component[ 'params' ][ 'users_fields' ];
				
				// The system needs the default user account fields in order to create new users
				foreach( $users_fields as $uf_alias => $user_field ) {
					
					if ( check_var( $user_field[ 'user_field' ] ) AND $user_field[ 'user_field' ] == 'username' ) {
						
						$uf_username = TRUE;
						$uf_username_field_alias = $uf_alias;
						
					}
					
					if ( check_var( $user_field[ 'user_field' ] ) AND $user_field[ 'user_field' ] == 'name' ) {
						
						$uf_name = TRUE;
						$uf_name_field_alias = $uf_alias;
						
					}
					
					if ( check_var( $user_field[ 'user_field' ] ) AND $user_field[ 'user_field' ] == 'email' ) {
						
						$uf_email = TRUE;
						$uf_email_field_alias = $uf_alias;
						
					}
					
					if ( check_var( $user_field[ 'is_unique' ] ) ) {
						
						$uf_unique[ $uf_alias ] = 1;
						
					}
					
					// we need at least these two fields in order to create an user account
					if ( $uf_name AND $uf_email ) {
						
						$allow_user_registration = TRUE;
						
					}
					
				}
				
			}
			
			foreach ( $submit_form[ 'fields' ] as $key => $field ) {
				
				if ( check_var( $field[ 'advanced_options' ][ 'prop_is_ud_file' ] ) ) {
					
					$form_has_file_field = TRUE;
					
				}
				
				// -------------------------------------------------
				// User registration stuff
				
				if ( $allow_user_registration AND check_var( $field[ 'is_user_field' ] ) AND check_var( $field[ 'user_field' ] ) ) {
					
					if ( $uf_username AND $field[ 'user_field' ] == $uf_username_field_alias ) {
						
						$ds_user_username_field = $field;
						
					}
					
					if ( $uf_name AND $field[ 'user_field' ] == $uf_name_field_alias ) {
						
						$ds_user_name_field = $field;
						
					}
					
					if ( $uf_email AND $field[ 'user_field' ] == $uf_email_field_alias ) {
						
						$ds_user_email_field = $field;
						
					}
					
					if ( isset( $uf_unique[ $field[ 'user_field' ] ] ) ) {
						
						$uf_unique[ $field[ 'user_field' ] ] = $field;
						
					}
					
					// we need at least these two fields in order to allow user registration from current form
					if ( $ds_user_name_field AND $ds_user_email_field ) {
						
						$form_ok_to_registration = TRUE;
						
					}
					
				}
				
				// User registration stuff
				// -------------------------------------------------
				
				$field_name = $field[ 'alias' ];
				
				$formatted_field_name = 'form[' . $field_name . ']';
				
				$rules = array( 'trim' );
				
				if ( ! check_var( $no_validation_fields[ $field_name ] ) AND check_var( $field[ 'availability' ][ 'site' ] ) ) {
					
					if ( ! check_var( $field[ 'advanced_options' ][ 'prop_is_ud_file' ] ) AND check_var( $field[ 'field_is_required' ] ) ){
						
						$rules[] = 'required';
						$skip = FALSE;
						
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
									
								case 'valid_email':
									
									if ( ! isset( $data[ 'post' ][ 'form' ][ $prop_name ] ) OR $data[ 'post' ][ 'form' ][ $prop_name ] != '' ) {
										
										$rules[] = $rule . $comp;
										
									}
									
								case 'mask':
									
									if ( $data[ 'post' ][ 'form' ][ $field[ 'alias' ] ] AND isset( $field[ 'ud_validation_rule_parameter_mask_type' ] ) ) {
										
										$_POST[ 'form' ][ $field[ 'alias' ] ] = $data[ 'post' ][ 'form' ][ $field[ 'alias' ] ] = unmask( $data[ 'post' ][ 'form' ][ $field[ 'alias' ] ], $field[ 'ud_validation_rule_parameter_mask_type' ], isset( $field[ 'ud_validation_rule_parameter_mask_custom_mask' ] ) ? $field[ 'ud_validation_rule_parameter_mask_custom_mask' ] : '' );
										
									}
									
									$skip = TRUE;
									unset( $field[ 'validation_rule' ][ $key ] );
									break;
									
							}
							
							if ( ! $skip ) {
								
								$rules[] = $rule . $comp;
								
							}
							
						}
						
					}
					
				}
				
				// xss filtering
				if ( check_var( $data[ 'params' ][ 'ud_data_props_enable_xss_filtering_site' ] ) ) {
					
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
				
				$rules = join( '|', $rules );
				
				$this->form_validation->set_rules( $formatted_field_name, lang( $field[ 'label' ] ), $rules );
				
			}
			
			// -----------------------------------------------
			// Validating uploaded files
			
			if ( check_var( $form_has_file_field ) AND check_var( $_FILES[ 'form' ] ) ) {
				
				// seting the session tmp upload path name
				if ( ! $this->session->envdata( 'ud_data_tmp_upload_path_name' ) ) {
					
					$this->session->set_envdata( 'ud_data_tmp_upload_path_name', md5( uniqid( rand(), TRUE ) ) );
					
				}
				
// 				echo '<pre>' . print_r( $this->session->all_envdata(), TRUE ) . '</pre>';
				
				$tmp_upload_path_name = $this->session->envdata( 'ud_data_tmp_upload_path_name' );
 				$tmp_upload_path = TMP_PATH . DS;
				
				if ( ! @is_dir( $tmp_upload_path ) ) {
					
					$file_upload_errors[] = lang( 'ud_data_prop_file_tmp_path_error_not_valid' );
					
				}
				else if ( ! is_really_writable( $tmp_upload_path ) ) {
					
					$file_upload_errors[] = lang( 'ud_data_prop_file_tmp_path_error_not_writable' );
					
				}
				else {
					
					if ( ! file_exists( $tmp_upload_path . $tmp_upload_path_name ) AND ! mkdir( $tmp_upload_path . $tmp_upload_path_name ) ) {
						
						$file_upload_errors[] = lang( 'ud_data_prop_file_tmp_upload_path_error_cant_create_dir' );
						
					}
					else {
						
						$tmp_upload_path .= $tmp_upload_path_name . DS;
						
						if ( ! @is_dir( $tmp_upload_path ) ) {
							
							$file_upload_errors[] = lang( 'ud_data_prop_file_tmp_upload_path_error_not_valid' );
							
						}
						else if ( ! is_really_writable( $tmp_upload_path ) ) {
							
							$file_upload_errors[] = lang( 'ud_data_prop_file_tmp_upload_path_error_not_writable' );
							
						}
						
					}
					
				}
				
				if ( ! check_var( $file_upload_errors ) ) {
					
					foreach( $_FILES[ 'form' ][ 'name' ] as $alias => $file_name ) {
						
						if ( check_var( $submit_form[ 'fields' ][ $alias ] ) ) {
							
							$file_field = $submit_form[ 'fields' ][ $alias ];
							
							if ( check_var( $_FILES[ 'form' ][ 'error' ][ $alias ] ) AND $_FILES[ 'form' ][ 'error' ][ $alias ] == 4 AND check_var( $file_field[ 'field_is_required' ] ) ) {
								
								$file_upload_errors[] = lang( 'ud_data_prop_file_required_error', NULL, $file_field[ 'label' ] );
								
							}
							
							if ( check_var( $_FILES[ 'form' ][ 'size' ][ $alias ] ) AND check_var( $file_field[ 'advanced_options' ][ 'prop_is_ud_file_max_size' ] ) AND $_FILES[ 'form' ][ 'size' ][ $alias ] > kilobytes_to_b( $file_field[ 'advanced_options' ][ 'prop_is_ud_file_max_size' ] ) ) {
								
								$file_upload_errors[] = lang( 'ud_data_prop_file_size_exceeds_limit_error', NULL, $file_field[ 'label' ], format_file_size( kilobytes_to_b( $file_field[ 'advanced_options' ][ 'prop_is_ud_file_max_size' ] ) ), format_file_size( $_FILES[ 'form' ][ 'size' ][ $alias ] ) );
								
							}
							
							// ------------------------
							// Uploading
							
							if ( check_var( $_FILES[ 'form' ][ 'error' ][ $alias ], TRUE ) AND $_FILES[ 'form' ][ 'error' ][ $alias ] == 0 ) {
								
								$this->load->library( 'upload' );
								
								// upload library config
								$ul_config = array();
								$upload_path = check_var( $file_field[ 'advanced_options' ][ 'prop_is_ud_file_upload_dir' ] ) ? BASE_PATH . rtrim( $file_field[ 'advanced_options' ][ 'prop_is_ud_file_upload_dir' ], DS ) . DS : FALSE;
								
								// validating the destination path
								if ( $upload_path ) {
									
									if ( ! @is_dir( $upload_path ) ) {
										
										$file_upload_errors[] = lang( 'ud_data_prop_file_upload_path_error_not_a_valid_path', NULL, $file_field[ 'label' ] );
										
									}
									else if ( ! is_really_writable( $upload_path ) ) {
										
										$file_upload_errors[] = lang( 'ud_data_prop_file_upload_path_error_not_writable', NULL, $file_field[ 'label' ] );
										
									}
									else {
										
										// for now, we set the temporary upload path, after we move all files to final destination
										$ul_config[ 'upload_path' ] = $tmp_upload_path;
										
									}
									
								}
								else if ( ! $upload_path ) {
									
									$file_upload_errors[] = lang( 'ud_data_prop_file_upload_path_error_no_path', NULL, $file_field[ 'label' ] );
									
								}
								
								if ( ! check_var( $file_upload_errors ) ) {
									
									// setting up overwrite param
									if ( check_var( $file_field[ 'advanced_options' ][ 'prop_is_ud_file_overwrite' ] ) ) {
										
										$ul_config[ 'overwrite' ] = TRUE;
										
									}
									
									// setting up the allowed types for validation
									if ( check_var( $file_field[ 'advanced_options' ][ 'prop_is_ud_file_allowed_types' ] ) ) {
										
										$ul_config[ 'allowed_types' ] = $file_field[ 'advanced_options' ][ 'prop_is_ud_file_allowed_types' ];
										
									}
									else {
										
										$ul_config[ 'allowed_types' ] = '*';
										
									}
									$this->upload->set_allowed_types( $ul_config[ 'allowed_types' ] );
									
									// setting up the file name
									$ul_config[ 'file_name' ] = $this->upload->clean_file_name( $this->upload->_prep_filename( $file_name ) );
									
			// 						$ul_config[ 'file_name' ] = url_title( $file_field[ 'alias' ] . '__' . date("Y-m-d H:i:s") . '__' . pathinfo( $ul_config[ 'file_name' ], PATHINFO_FILENAME ) );
									
									$this->upload->initialize( $ul_config );
									
									if ( ! check_var( $file_upload_errors ) ) {
										
										if ( ! $this->upload->do_upload( 'form[' . $alias . ']', FALSE, FALSE ) ) {
											
											$file_upload_errors[] = lang( 'ud_data_prop_file_upload_library_error', NULL, $this->upload->display_errors() );
											
										}
										
										if ( ! $this->upload->is_allowed_filetype() ) {
											
											$file_upload_errors[] = lang( 'ud_data_prop_file_allowed_types_error', NULL, $file_field[ 'label' ] );
											
										}
										
									}
									
									$_uploaded_file_data = $this->upload->data();
									
// 									echo '<pre>' . print_r( $_uploaded_file_data, TRUE ) . '</pre>';
									
									$uploaded_files[ $alias ] = array(
										
										'file_name' => $_uploaded_file_data[ 'file_name' ],
										'raw_name' => $_uploaded_file_data[ 'raw_name' ],
										'file_ext' => $_uploaded_file_data[ 'file_ext' ],
										'file' => $_uploaded_file_data[ 'full_path' ],
										'destination' => $upload_path,
										'rel_destination' => $file_field[ 'advanced_options' ][ 'prop_is_ud_file_upload_dir' ],
										'is_image' => $_uploaded_file_data[ 'is_image' ],
										'image_width' => $_uploaded_file_data[ 'image_width' ],
										'image_height' => $_uploaded_file_data[ 'image_height' ],
										'image_type' => $_uploaded_file_data[ 'image_type' ],
										
									);
									
								}
								
							}
							
							// Uploading
							// ------------------------
							
						}
						
	// 					echo '<pre>' . print_r( ( int ) $_FILES[ 'form' ][ 'size' ][ $alias ] > ( int ) $file_field[ 'advanced_options' ][ 'prop_is_ud_file_max_size' ], TRUE ) . '</pre>'; exit;
						
					}
					
					unset( $_uploaded_file_data );
					unset( $file_fields );
					
					
				}
				
// 				echo '<pre>' . print_r( $uploaded_files, TRUE ) . '</pre>'; exit;
				
//  			echo '<pre>' . print_r( $file_upload_errors, TRUE ) . '</pre>'; exit;
				
// 				echo '<pre>' . print_r( get_ini_max_file_upload(), TRUE ) . '</pre>'; exit;
				
// 				echo '<pre>' . print_r( ( int ) $_FILES[ 'form' ][ 'size' ][ $alias ] > ( int ) $file_field[ 'advanced_options' ][ 'prop_is_ud_file_max_size' ], TRUE ) . '</pre>'; exit;
				
// 				echo '<pre>' . print_r( $_FILES[ 'form' ], TRUE ) . '</pre>'; exit;
				
// 				echo '<pre>' . print_r( $submit_form[ 'fields' ], TRUE ) . '</pre>'; exit;
				
			}
			
			// Validating uploaded files
			// -----------------------------------------------
			
			if ( $data[ 'post' ] AND $this->form_validation->run() AND ! check_var( $file_upload_errors ) ){
				
				// We need set this variable again, because some validation rules may change its values on $this->input->post()
				$data[ 'post' ] = $this->input->post( NULL, $xss_filtering );
				
				if ( $form_ok_to_registration ) {
					
					// Variavel controla se o usuario já enviou o formulario e esta devidamente cadastrado
					$user_new_form = TRUE; // Variavel para checar se o formulario é novo para este usuario
					$user_new = FALSE; // Variavel para checar se o usuario é novo
					
					// preparing the user username from linked username field
					if ( $ds_user_username_field AND check_var( $data[ 'post' ][ 'form' ][ $ds_user_username_field[ 'alias' ] ] ) ) {
						
						$user_username = $data[ 'post' ][ 'form' ][ $ds_user_username_field[ 'alias' ] ];
						
					}
					// else, we create from email field
					else if ( $ds_user_email_field AND check_var( $data[ 'post' ][ 'form' ][ $ds_user_email_field[ 'alias' ] ] ) ) {
						
						$user_username = $data[ 'post' ][ 'form' ][ $ds_user_email_field[ 'alias' ] ];
						
					}
					
					if ( $ds_user_email_field AND check_var( $data[ 'post' ][ 'form' ][ $ds_user_email_field[ 'alias' ] ] ) ) {
						
						$user_email = $data[ 'post' ][ 'form' ][ $ds_user_email_field[ 'alias' ] ];
						
					}
					
					// -----------------------------------------------
					// Procurar por usuários
					
					if ( isset( $data[ 'post' ][ 'user_id' ] ) ) {
						
						// TODO
						// DEFINIR ID DE USUARIO NO HTML PARECE VULNERABILIDADE
						$db_params[ 'where_condition' ] = 't1.id = ' . $data[ 'post' ][ 'user_id' ] . '';
						
					}
					else {
						
						$db_params[ 'where_condition' ] = '`t1`.`username` LIKE "' . $user_username . '"';
						$db_params[ 'or_where_condition' ][ 'fake_index_1' ] = '`t1`.`email` LIKE "' . $user_email . '"';
						
						if ( $uf_unique ) {
							
							foreach( $uf_unique as $unique_field_alias => $unique_field ) {
								
								foreach ( $submit_form[ 'fields' ] as $key => $field ) {
									
									if ( check_var( $field[ 'is_user_field' ] ) AND check_var( $field[ 'user_field' ] ) AND $field[ 'user_field' ] == $unique_field_alias AND isset( $data[ 'post' ][ 'form' ][ $field[ 'alias' ] ] ) ) {
										
										$db_params[ 'or_where_condition' ][ 'fake_index_' . $unique_field_alias ] = '`t1`.`params` LIKE \'%"' . $unique_field_alias . '":"' . $data[ 'post' ][ 'form' ][ $key ] . '%"%\' COLLATE \'utf8_general_ci\' ';
										
									}
									
								}
								
							}
							
						}
						
					}
					
					//echo '<pre>' . print_r( $db_params, TRUE ) . '</pre>'; exit;
					
					$db_params[ 'limit' ] = 1;
					
					$user = $this->users->get_users( $db_params )->row_array();
					
					// Usuário encontrado
					if ( check_var( $user ) ) {
						
						if ( check_var( $data[ 'params' ][ 'ud_psm_uar_t' ] ) ) {
							
							msg( lang( $data[ 'params' ][ 'ud_psm_uar_t' ] ), 'title' );
							
						}
						
						if ( check_var( $data[ 'params' ][ 'ud_psm_uar_m' ] ) ) {
							
							msg( lang( $data[ 'params' ][ 'ud_psm_uar_m' ] ), 'warning' );
							
						}
						
						$user[ 'params' ] = get_params( $user[ "params" ] );
						
						if ( check_var( $user[ 'params' ][ 'forms_submitted' ][ 'ids' ] ) ) {
							
							foreach( $user[ 'params' ][ 'forms_submitted' ][ 'ids' ] as $id ) {
								
								// user already submitted the current form
								if ( $id == $submit_form_id ) {
									
									$user_new_form = FALSE;
									
									if ( check_var( $data[ 'params' ][ 'ud_psm_uasf_t' ] ) ) {
										
										msg( lang( $data[ 'params' ][ 'ud_psm_uasf_t' ] ), 'title' );
										
									}
									
									if ( check_var( $data[ 'params' ][ 'ud_psm_uasf_m' ] ) ) {
										
										msg( lang( $data[ 'params' ][ 'ud_psm_uasf_m' ] ), 'warning' );
										
									}
									
									break;
									
								}
								
							}
							
						}
						
						// user has not yet submitted the current form
						if ( $user_new_form ) {
							
							$user[ 'params' ][ 'forms_submitted' ][ 'ids' ][] = $submit_form_id;
							
						}
						
					}
					
					// user not registered
					else {
						
						$user_new = TRUE; // Novo usuario
						$user_password = $this->users->generatePassword();
						$user_name = $data[ 'post' ][ 'form' ][ $ds_user_name_field[ 'alias' ] ];
						
						foreach( $users_fields as $uf_alias => $user_field ) {
							
							foreach ( $submit_form[ 'fields' ] as $key => $field ) {
								
								if ( check_var( $field[ 'is_user_field' ] ) AND check_var( $field[ 'user_field' ] ) AND $field[ 'user_field' ] == $uf_alias AND isset( $data[ 'post' ][ 'form' ][ $field[ 'alias' ] ] ) ) {
									
									if (
										
										! (
											
											( $ds_user_name_field AND $ds_user_name_field[ 'alias' ] == $field[ 'alias' ] ) OR
											( $ds_user_username_field AND $ds_user_username_field[ 'alias' ] == $field[ 'alias' ] ) OR
											( $ds_user_email_field AND $ds_user_email_field[ 'alias' ] == $field[ 'alias' ] )
										
										)
										
									) {
										
										$user[ 'params' ][ 'user_fields' ][ $field[ 'user_field' ] ] = $data[ 'post' ][ 'form' ][ $field[ 'alias' ] ];
										
									}
									
								}
								
							}
							
						}
						
						$user[ 'params' ][ 'forms_submitted' ][ 'ids' ][] = $submit_form_id;
						
						$db_user[ "params" ] = $user[ 'params' ];
						$db_user[ "username" ] = $user_username;
						$db_user[ "password" ] = $user_password;
						$db_user[ "email" ] = $user_email;
						$db_user[ "name" ] = $user_name;
						$db_user[ "group_id" ] = $submit_form[ 'params' ][ 'ud_ds_default_user_group_registered_from_form' ];
						
						$data[ 'user_data' ] = $db_user;
						
						$db_user[ "password" ] = $this->users->encode_password( $user_password );
						
					}
					
					//echo '<pre>' . print_r( $db_user, TRUE ) . '</pre>'; exit;
					
					$db_user[ "params" ] = json_encode( $user[ 'params' ] );
					
					//echo '<pre>' . print_r( $db_user, TRUE ) . '</pre>'; exit;
					
					// -----------------------------------------------
					// Enviando dados para a tabela tb_users
					// Caso o usuario seja novo insert
					if ( $user_new AND $user_new_form ) {
						
						if ( $return_user_id = $this->users->insert_user( $db_user ) ) {
							
							$user = $db_user;
							$user[ 'id' ] = $return_user_id;
							
							msg( lang( 'submit_forms_registry_success' ), 'success' );
							
						}
						else {
							
							msg( lang( 'submit_forms_registry_failed' ), 'error' );
							
						}
						
					}
					// Caso contrario realiza o update
					else if ( $user_new_form ) {
						
						if ( $this->users->update_user( $db_user, 'id = ' . $user[ 'id' ] . '' ) ) {
							
							msg( lang( 'submit_forms_registry_success' ), 'success' );
							
						}
						else {
							
							msg( lang( 'submit_forms_registry_failed' ), 'error' );
							
						}
						
					}
					
				}
				
				if ( ! $form_ok_to_registration OR ( $form_ok_to_registration AND $user_new_form ) ) {
					
					// Definindo variável de limpeza de tags html
					// atualmente efetiva na views
					// @TODO: fazer a limpeza dos valores recebidos aqui, no controller, não nas views
					$data[ 'submit_forms_allow_html_tags' ] = check_var( $data[ 'params' ][ 'submit_forms_allow_html_tags' ] );
					
					// find replace strings arrays. Eg. {submit_form_id} is overwrited by the unid_data id
					$find = array();
					$replace = array();
					
					$find[] = '{submit_form_id}';
					$find[] = '{submit_form_url}';
					$find[] = '{submit_form_title}';
					$replace[] = $submit_form[ 'id' ];
					$replace[] = $submit_form[ 'url' ];
					$replace[] = $submit_form[ 'title' ];
					
					// -----------------------------------------------
					// parsing unid_data data and creating field values replacement array
					
					$ud_d_data = $data[ 'post' ][ 'form' ];
					
					foreach ( $submit_form[ 'fields' ] as $key => $field ) {
						
						if ( ! in_array( $field[ 'field_type' ], array( 'html' ) ) ){
							
							$field_label = $field[ 'label' ];
							$field_presentation_label = isset( $field[ 'presentation_label' ] ) ? $field[ 'presentation_label' ] : $field_label;
							$field_alias = $field[ 'alias' ];
							$formatted_field_name = 'form[' . $field_alias . ']';
							$field_value = '';
							
							if ( isset( $data[ 'post' ][ 'form' ][ $field_alias ] ) ) {
								
								if ( $field[ 'field_type' ] == 'date' ){
									
									if ( isset( $data[ 'post' ][ 'form' ][ $field[ 'alias' ] ] ) ) {
										
										$d = ( isset( $data[ 'post' ][ 'form' ][ $field[ 'alias' ] ][ 'd' ] ) AND $data[ 'post' ][ 'form' ][ $field[ 'alias' ] ][ 'd' ] != '' ) ? $data[ 'post' ][ 'form' ][ $field[ 'alias' ] ][ 'd' ] : '00';
										$m = ( isset( $data[ 'post' ][ 'form' ][ $field[ 'alias' ] ][ 'm' ] ) AND $data[ 'post' ][ 'form' ][ $field[ 'alias' ] ][ 'm' ] != '' ) ? $data[ 'post' ][ 'form' ][ $field[ 'alias' ] ][ 'm' ] : '00';
										$y = ( isset( $data[ 'post' ][ 'form' ][ $field[ 'alias' ] ][ 'y' ] ) AND $data[ 'post' ][ 'form' ][ $field[ 'alias' ] ][ 'y' ] != '' ) ? $data[ 'post' ][ 'form' ][ $field[ 'alias' ] ][ 'y' ] : '0000';
										
										$ud_d_data[ $field[ 'alias' ] ] = $y . '-' . $m . '-' . $d;
										
									}
							
									$format = '';
									
									$format .= $field[ 'sf_date_field_use_year' ] ? 'y' : '';
									$format .= $field[ 'sf_date_field_use_month' ] ? 'm' : '';
									$format .= $field[ 'sf_date_field_use_day' ] ? 'd' : '';
									
									$format = 'sf_us_dt_ft_pt_' . $format . '_' . $field[ 'sf_date_field_presentation_format' ];
									
									$field_value =  strftime( lang( $format ), strtotime( $field_value ) );
									
								}
								else if ( in_array( $field[ 'field_type' ], array( 'checkbox', 'radiobox', 'combo_box', ) ) ){
									
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
					
					// parsing unid_data data and creating field values replacement array
					// -----------------------------------------------
					
					// -----------------------------------------------
					// Inserting user submit into DB
					
					// ------------------
					// Adjusting not showed fields
					// Add the fields unavailable on site environment, but have a default value
					
					$_tmp = array();
					
					foreach ( $submit_form[ 'fields' ] as $k => $field ) {
						
						if ( ! check_var( $field[ 'availability' ][ 'site' ] ) AND check_var( $field[ 'default_value' ] ) ) {
							
							$_tmp[ $field[ 'alias' ] ] = $field[ 'default_value' ];
							
						}
						
					}
					
					if ( $_tmp ) {
						
						$post[ 'form' ] = array_merge_recursive_distinct( $post[ 'form' ], $_tmp );
						
					}
					
					// Adjusting not showed fields
					// ------------------
					
					$ud_d_db_insert_data = array();
					
					$submit_datetime = gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] );
					$submit_datetime = strftime( '%Y-%m-%d %T', $submit_datetime );
					
					// ------------------
					// Parsing and moving the uploaded files
					
					if ( check_var( $uploaded_files ) ) {
						
						$this->load->library('image_lib');
						
						foreach( $uploaded_files as $alias => $file ) {
							
							$_raw_name = $file[ 'raw_name' ];
							$_file_ext = $file[ 'file_ext' ];
							$_file_name = url_title( strtolower( trim( $_raw_name ) . '__' . $submit_datetime . '__' . $alias ) ) . $_file_ext;
							$_orig = realpath( $file[ 'file' ] );
							$_dest = realpath( $file[ 'destination' ] ) . DS . $_file_name;
							$_rel_dest = $file[ 'rel_destination' ];
							$_is_image = ( bool ) $file[ 'is_image' ];
							$_image_width = ( int ) $file[ 'image_width' ];
							$_image_height = ( int ) $file[ 'image_height' ];
							$_image_thumb_path = THUMBS_PATH . $_rel_dest . DS;
							$_image_thumb_dest = $_image_thumb_path . $_file_name;
							$_image_thumb_width = check_var( $this->mcm->filtered_system_params[ 'thumbnails_width' ] ) ? ( int ) $this->mcm->filtered_system_params[ 'thumbnails_width' ] : 200;
							$_image_thumb_height = check_var( $this->mcm->filtered_system_params[ 'thumbnails_height' ] ) ? ( int ) $this->mcm->filtered_system_params[ 'thumbnails_height' ] : 200;
							$dest_file_ok = FALSE;
							
							$ud_d_data[ $alias ] = $_rel_dest . DS . $_file_name;
							
							if ( ! @copy( $_orig, $_dest ) ) {
								
								if ( ! @move_uploaded_file( $_orig, $_dest ) ) {
									
									log_message( 'error', lang( 'ud_data_prop_file_moving_error', NULL, $_orig, $_dest ) );
									msg( lang( 'ud_data_prop_file_moving_error', NULL, $_orig, $_dest ), 'error' );
									
								}
								else {
									
									$dest_file_ok = TRUE;
									
								}
								
							}
							else {
								
								$dest_file_ok = TRUE;
								
							}
							
							// ------------------
							// creating thumb
							
							if ( $dest_file_ok AND $_is_image ) {
								
								if ( ! @is_dir( $_image_thumb_path ) AND ! @mkdir( $_image_thumb_path ) ) {
									
									msg( lang( 'ud_data_prop_file_create_thumb_path_error', NULL, $_image_thumb_path ), 'error' );
									
								}
								
								if ( ! is_really_writable( $_image_thumb_path ) ) {
									
									msg( lang( 'ud_data_prop_file_thumb_path_not_writable_error', NULL, $_image_thumb_path ), 'error' );
									
								}
								
								if ( @copy( $_dest, $_image_thumb_dest ) ) {
									
									if ( @is_dir( $_image_thumb_path ) ) {
										
										$config[ 'image_library' ] = 'gd2';
										$config[ 'maintain_ratio' ] = FALSE;
										$config[ 'source_image' ]	= $_image_thumb_dest;
										
										if ( $_image_width > $_image_height ) {
											
											$_fake_thumb_height = $_image_height;
											$_fake_thumb_width = $_fake_thumb_height * $_image_thumb_width / $_image_thumb_height;
											
											$config[ 'height' ]	= $_image_height;
											$config[ 'width' ]	= $_fake_thumb_width;
											$config[ 'y_axis' ] = 0;
											$config[ 'x_axis' ] = $_image_width / 2 - $_fake_thumb_width / 2;
											
										}
										else {
											
											$_fake_thumb_width = $_image_width;
											$_fake_thumb_height = $_fake_thumb_width * $_image_thumb_height / $_image_thumb_width;
											
											$config[ 'width' ]	= $_image_width;
											$config[ 'height' ]	= $_fake_thumb_height;
											$config[ 'x_axis' ] = 0;
											$config[ 'y_axis' ] = $_image_height / 2 - $_fake_thumb_height / 2;
											
										}
										
										$this->image_lib->initialize( $config );
										
										if ( ! $this->image_lib->crop() ){
											
											msg( $this->image_lib->display_errors(), 'error' );
											
										}
										
										$this->image_lib->clear();
										
										$config[ 'width' ]	= $_image_thumb_width;
										$config[ 'height' ]	= $_image_thumb_height;
										$config[ 'maintain_ratio' ] = TRUE;
										
										$this->image_lib->initialize( $config );
										
										if ( ! $this->image_lib->resize() ){
											
											msg( $this->image_lib->display_errors(), 'error' );
											
										}
										
									}
									
								}
								else {
									
									msg( lang( 'ud_data_prop_file_copy_thumb_error', NULL, $_dest, $_image_thumb_dest ), 'error' );
									
								}
								
							}
							
							// creating thumb
							// ------------------
							
						}
						
						rrmdir( $tmp_upload_path );
						
					}
					
					// Parsing and moving the uploaded files
					// ------------------
					
// 					echo '<pre>' . print_r( $ud_d_data, TRUE ) . '</pre>'; exit;
				
					$ud_d_db_insert_data[ 'submit_form_id' ] = $submit_form_id;
					$ud_d_db_insert_data[ 'submit_datetime' ] = $submit_datetime;
					$ud_d_db_insert_data[ 'mod_datetime' ] = $submit_datetime;
					$ud_d_db_insert_data[ 'data' ] = json_encode( $ud_d_data );
					$ud_d_db_insert_data[ 'params' ] = array();
					
					if ( $this->users->is_logged_in() ) {
						
						$ud_d_db_insert_data[ 'params' ][ 'created_by_user_id' ] = $this->users->user_data[ 'id' ];
						
					}
					else if ( check_var( $user[ 'id' ] ) ) {
						
						$ud_d_db_insert_data[ 'params' ][ 'created_by_user_id' ] = $user[ 'id' ];
						
					}
					
					$ud_d_db_insert_data[ 'params' ] = json_encode( $ud_d_db_insert_data[ 'params' ] );
					
					$user_submit_inserted = FALSE;
					
					if ( $ud_d_id = $this->sfcm->insert_user_submit( $ud_d_db_insert_data ) ){
						
						$user_submit_inserted = TRUE;
						
						$data[ 'ud_data' ] = $ud_d_db_insert_data;
						$data[ 'ud_data' ][ 'id' ] = $ud_d_id;
						
						$find[] = '{user_submit_id}';
						$find[] = '{ud_data_submit_datetime}';
						$find[] = '{ud_data_mod_datetime}';
						$replace[] = $ud_d_id;
						$replace[] = $data[ 'ud_data' ][ 'submit_datetime' ];
						$replace[] = $data[ 'ud_data' ][ 'mod_datetime' ];
						
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
					
					$data[ 'ud_data' ] = $this->sfcm->parse_ud_d_data( $data[ 'ud_data' ] );
					
					// Inserting user submit into DB
					// -----------------------------------------------
					
					// -----------------------------------------------
					// Applying find / replace strings on messages
					
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
					
					if ( check_var( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_new_user_success_title' ] ) ) {
						
						$data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_new_user_success_title' ] = str_replace( $find, $replace, $data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_new_user_success_title' ] );
						
					}
					
					if ( check_var( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_new_user_success' ] ) ) {
						
						$data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_new_user_success' ] = str_replace( $find, $replace, $data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_new_user_success' ] );
						
					}
					
					if ( check_var( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_new_user_internal_error_title' ] ) ) {
						
						$data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_new_user_internal_error_title' ] = str_replace( $find, $replace, $data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_new_user_internal_error_title' ] );
						
					}
					
					if ( check_var( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_new_user_internal_error' ] ) ) {
						
						$data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_new_user_internal_error' ] = str_replace( $find, $replace, $data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_new_user_internal_error' ] );
						
					}
					
					// Applying find / replace strings on messages
					// -----------------------------------------------
					
					// -----------------------------------------------
					// Creating e-mail receivers array (not for the submitter)
					
					$emails_to = array();
					
					// If from contact ...
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
					// Else, from custom list ...
					else if ( check_var( $data[ 'params' ][ 'submit_form_param_send_email_to' ] ) AND $data[ 'params' ][ 'submit_form_param_send_email_to' ] == 'custom_emails' AND check_var( $data[ 'params' ][ 'submit_form_param_send_email_to_custom_emails' ] ) ){
						
						$custom_emails = explode( "\n", $data[ 'params' ][ 'submit_form_param_send_email_to_custom_emails' ] );
						
						foreach ( $custom_emails as $key => $email ) {
							
							$emails_to[] = $email;
							
						}
						
					}
					
					// Creating e-mail receivers array (not for the submitter)
					// -----------------------------------------------
					
					// -----------------------------------------------
					// Receivers e-mail layout
					
					$notify_receivers = ( check_var( $data[ 'params' ][ 'ud_ds_notify_unid_data_via_email' ] ) AND ( ( check_var( $data[ 'params' ][ 'submit_form_sending_email_layout_source' ] ) AND $data[ 'params' ][ 'submit_form_sending_email_layout_source' ] === 'layouts_list' AND check_var( $data[ 'params' ][ 'submit_form_sending_email_layout_view' ] ) ) OR check_var( $data[ 'params' ][ 'submit_form_sending_email_layout_custom' ], TRUE ) ) ) ? TRUE : FALSE;
					
					if ( $notify_receivers ){
						
						$layout_source = NULL;
						
						$email_body_receiver = '';
						
						// Se o layout for da lista
						if ( check_var( $data[ 'params' ][ 'submit_form_sending_email_layout_source' ] ) AND $data[ 'params' ][ 'submit_form_sending_email_layout_source' ] === 'layouts_list' AND check_var( $data[ 'params' ][ 'submit_form_sending_email_layout_view' ] ) ) {
							
							$layout_source = $data[ 'params' ][ 'submit_form_sending_email_layout_source' ];
							
							$layout = $data[ 'params' ][ 'submit_form_sending_email_layout_view' ];
							
							// checking if the theme has the layout
							if ( file_exists( THEMES_PATH . call_user_func( $this->mcm->environment . '_theme_components_views_path' ) . $this->component_view_folder . DS . __FUNCTION__ . DS . 'emails' . DS . 'receivers' . DS . $layout . DS . 'receivers.php' ) ){
								
								$email_body_receiver = $this->load->view( call_user_func( $this->mcm->environment . '_theme_components_views_path' ) . $this->component_view_folder . DS . __FUNCTION__ . DS . 'emails' . DS . 'receivers' . DS . $layout . DS . 'receivers', $data, TRUE );
								
							}
							// checking if the view exists in default views directory
							else if ( file_exists( VIEWS_PATH . get_constant_name( $this->mcm->environment . '_COMPONENTS_VIEWS_PATH' ) . $this->component_view_folder . DS . __FUNCTION__ . DS . 'emails' . DS . 'receivers' . DS . $layout . DS . 'receivers.php' ) ){
								
								$email_body_receiver = $this->load->view( get_constant_name( $this->mcm->environment . '_COMPONENTS_VIEWS_PATH' ) . $this->component_view_folder . DS . __FUNCTION__ . DS . 'emails' . DS . 'receivers' . DS . $layout . DS . 'receivers', $data, TRUE );
								
							}
							else{
								
								exit( lang( 'load_view_fail' ) . ': <b>' . VIEWS_PATH . get_constant_name( $this->mcm->environment . '_COMPONENTS_VIEWS_PATH' ) . $this->component_view_folder . DS . __FUNCTION__ . DS . 'emails' . DS . 'receivers' . DS . $layout . DS . 'receivers.php</b>' );
								
							}
							
						}
						// Caso contrário, se for do layout personalizado
						else if ( check_var( $data[ 'params' ][ 'submit_form_sending_email_layout_source' ] ) AND $data[ 'params' ][ 'submit_form_sending_email_layout_source' ] === 'custom_layout' AND check_var( $data[ 'params' ][ 'submit_form_sending_email_layout_custom' ], TRUE ) ){
							
							// checking if the theme has the layout
							if ( file_exists( THEMES_PATH . call_user_func( $this->mcm->environment . '_theme_components_views_path' ) . $this->component_view_folder . DS . __FUNCTION__ . DS . 'emails' . DS . 'receivers' . DS . 'default' . DS . 'data.php' ) ){
								
								$ud_d_data_html = $this->load->view( call_user_func( $this->mcm->environment . '_theme_components_views_path' ) . $this->component_view_folder . DS . __FUNCTION__ . DS . 'emails' . DS . 'receivers' . DS . 'default' . DS . 'data', $data, TRUE );
								
							}
							// checking if the view exists in default views directory
							else if ( file_exists( VIEWS_PATH . get_constant_name( $this->mcm->environment . '_COMPONENTS_VIEWS_PATH' ) . $this->component_view_folder . DS . __FUNCTION__ . DS . 'emails' . DS . 'receivers' . DS . 'default' . DS . 'data.php' ) ){
								
								$ud_d_data_html = $this->load->view( get_constant_name( $this->mcm->environment . '_COMPONENTS_VIEWS_PATH' ) . $this->component_view_folder . DS . __FUNCTION__ . DS . 'emails' . DS . 'receivers' . DS . 'default' . DS . 'data', $data, TRUE );
								
							}
							else{
								
								exit( lang( 'load_view_fail' ) . ': <b>' . VIEWS_PATH . get_constant_name( $this->mcm->environment . '_COMPONENTS_VIEWS_PATH' ) . $this->component_view_folder . DS . __FUNCTION__ . DS . 'emails' . DS . 'receivers' . DS . 'default' . DS . 'data.php</b>' );
								
							}
							
							$find[] = '{ud_d_data}';
							$replace[] = $ud_d_data_html;
							
							$layout_source = $data[ 'params' ][ 'submit_form_sending_email_layout_source' ];
							
							$email_body_receiver = $data[ 'params' ][ 'submit_form_sending_email_layout_custom' ];
							
						}
						
						$email_body_receiver = str_replace( $find, $replace, $email_body_receiver );
						
					}
					
					// Receivers e-mail layout
					// -----------------------------------------------
					
					// -----------------------------------------------
					// Submitters e-mail layout
					
					$send_email_to_submitter = ( check_var( $data[ 'params' ][ 'sfsmr_send_copy_to_submitter' ] ) AND check_var( $data[ 'params' ][ 'sfsmr_email_field' ] ) ) ? TRUE : FALSE;
					
					if ( $send_email_to_submitter ){
						
						$submitter_layout_source = NULL;
						
						$submitter_email_body = '';
						
						// Se o layout for da lista
						if ( check_var( $data[ 'params' ][ 'sfsmr_layout_source' ] ) AND $data[ 'params' ][ 'sfsmr_layout_source' ] === 'layouts_list' AND check_var( $data[ 'params' ][ 'sfsmr_layout_view' ] ) ){
							
							$submitter_layout_source = $data[ 'params' ][ 'submit_form_sending_email_layout_source' ];
							
							$submitter_layout = $data[ 'params' ][ 'submit_form_sending_email_layout_view' ];
							
							// checking if the theme has the layout
							if ( file_exists( THEMES_PATH . call_user_func( $this->mcm->environment . '_theme_components_views_path' ) . $this->component_view_folder . DS . __FUNCTION__ . DS . 'emails' . DS . 'submitters' . DS . $submitter_layout . DS . 'submitters.php' ) ){
								
								$submitter_email_body = $this->load->view( call_user_func( $this->mcm->environment . '_theme_components_views_path' ) . $this->component_view_folder . DS . __FUNCTION__ . DS . 'emails' . DS . 'submitters' . DS . $submitter_layout . DS . 'submitters', $data, TRUE );
								
							}
							// checking if the view exists in default views directory
							else if ( file_exists( VIEWS_PATH . get_constant_name( $this->mcm->environment . '_COMPONENTS_VIEWS_PATH' ) . $this->component_view_folder . DS . __FUNCTION__ . DS . 'emails' . DS . 'submitters' . DS . $submitter_layout . DS . 'submitters.php' ) ){
								
								$submitter_email_body = $this->load->view( get_constant_name( $this->mcm->environment . '_COMPONENTS_VIEWS_PATH' ) . $this->component_view_folder . DS . __FUNCTION__ . DS . 'emails' . DS . 'submitters' . DS . $submitter_layout . DS . 'submitters', $data, TRUE );
								
							}
							else{
								
								exit( lang( 'load_view_fail' ) . ': <b>' . VIEWS_PATH . get_constant_name( $this->mcm->environment . '_COMPONENTS_VIEWS_PATH' ) . $this->component_view_folder . DS . __FUNCTION__ . DS . 'emails' . DS . 'submitters' . DS . $submitter_layout . DS . 'submitters.php</b>' );
								
							}
							
						}
						// Caso contrário, se for do layout personalizado
						else if ( check_var( $data[ 'params' ][ 'sfsmr_layout_source' ] ) AND $data[ 'params' ][ 'sfsmr_layout_source' ] === 'custom_layout' AND check_var( $data[ 'params' ][ 'sfsmr_layout_custom' ] ) ){
							
							$submitter_layout_source = $data[ 'params' ][ 'sfsmr_layout_source' ];
							
							$submitter_email_body = $data[ 'params' ][ 'sfsmr_layout_custom' ];
							
						}
						
						// Relativo a mensagem para o usuário (submitter)
						$submitter_email_body = str_replace( $find, $replace, $submitter_email_body );
						
					}
					
					/**********************************************/
					/******* Substituição dos outros campos ******/
					
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
					
					/******* Substituição dos outros campos ******/
					/**********************************************/
					
					
					/**********************************************/
					/********* Updating user submit on DB *********/
					
					if ( $notify_receivers ){
						
						$ud_d_db_update_data[ 'output' ] = $email_body_receiver;
						
					}
					if ( $send_email_to_submitter ){
						
						$ud_d_db_update_data[ 'output_submitter' ] = $submitter_email_body;
						
					}
					
					if ( check_var( $ud_d_db_update_data ) ){
						
						if ( $this->sfcm->update_user_submit( $ud_d_db_update_data, array( 'id' => $ud_d_id ) ) ) {
							
							log_message( 'debug', '[Submit forms] User submit (' . $ud_d_id . ') updated successfully' );
							
						}
						
					}
					
					/********* Updating user submit on DB *********/
					/**********************************************/
					
					
					
					/**********************************************/
					/*************** Sending e-mails **************/
					
					if ( $this->mcm->load_email_system() ) {
						
						$errors_messages = 0;
						$success_messages = NULL;
						$info_messages = NULL;
						
						if ( $notify_receivers ){
							
							$this->email->from( $email_from, $email_from_name );
							$this->email->reply_to( $email_reply_to );
							$this->email->to( $emails_to );
							$this->email->cc( $emails_cc);
							$this->email->bcc( $emails_bcc);
							$this->email->subject( $email_subject );
							$this->email->message( $email_body_receiver );
							
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
								
								log_message( 'error', '[Submit forms][Email] An internal error occurred while trying to send the message!' );
								
								foreach ( $this->email->get_debugger_messages() as $debugger_message ) {
									
									//echo $debugger_message;
									
									log_message( 'error', '[Submit forms][Email] Debugger message dump: ' . $debugger_message );
									
								}
								
								$this->email->clear_debugger_messages();
								
								$errors_messages ++;
								
							}
							
						}
						
						// Sending to submitter
						if ( $send_email_to_submitter ) {
							
							$this->mcm->email_system_clear();
							
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
								
								log_message( 'error', '[Submit forms][Email] An internal error occurred while trying to send the message to submitter!' );
								
								foreach ( $this->email->get_debugger_messages() as $debugger_message ) {
									
									//echo $debugger_message;
									
									log_message( 'error', '[Submit forms][Email] Debugger message dump: ' . $debugger_message );
									
								}
								
								$this->email->clear_debugger_messages();
								
								$errors_messages ++;
								
							}
							
							// Sending to new user login and password
							if ( $form_ok_to_registration AND $user_new AND check_var( $data[ 'params' ][ 'ud_ds_send_user_data_to_submitter_on_registration' ] ) ) {
								
								$body = $this->load->view( get_constant_name( $this->mcm->environment . '_COMPONENTS_VIEWS_PATH' ) . $this->component_view_folder . DS . __FUNCTION__ . DS . 'sending_email_new_user' . DS . $layout . DS . 'sending_email_new_user', $data, TRUE );
								
								$this->mcm->email_system_clear();
								
								$this->email->from( $submitter_email_from, $submitter_email_from_name );
								$this->email->reply_to( $submitter_email_reply_to );
								$this->email->to( $submitter_email_to );
								$this->email->subject( lang( 'submit_forms_email_to_new_user_subject' ) );
								$this->email->message( $body );
								
								if ( $this->email->send() ) {
									
									if ( check_var( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_new_user_success_title' ] ) ) {
										
										msg( lang( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_new_user_success_title' ] ), 'title' );
										
									}
									
									if ( check_var( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_new_user_success' ] ) ) {
										
										msg( lang( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_new_user_success' ] ), 'success' );
										
									}
									
								}
								else {
									
									if ( check_var( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_new_user_internal_error_title' ] ) ) {
										
										msg( lang( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_new_user_internal_error_title' ] ), 'title' );
										
									}
									
									if ( check_var( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_new_user_internal_error' ] ) ) {
										
										msg( lang( $data[ 'params' ][ 'sfpsm_user_submit_email_sent_to_new_user_internal_error' ] ), 'success' );
										
									}
									
									log_message( 'error', '[Submit forms][E-mail] An internal error occurred while trying to send the message to new user with system data access!' );
									
									foreach ( $this->email->get_debugger_messages() as $debugger_message ) {
										
										//echo $debugger_message;
										
										log_message( 'error', '[Submit forms][Email] Debugger message dump: ' . $debugger_message );
										
									}
									
									$this->email->clear_debugger_messages();
									
									$errors_messages ++;
									
								}
								
							}
							
						}
						
					}
					
					/*************** Sending e-mails **************/
					/**********************************************/
					
				}
				
				if ( ! check_var( $errors_messages ) ) {
					
					if ( check_var( $data[ 'params' ][ 'sf_redirecting_on_success_source' ] ) AND $data[ 'params' ][ 'sf_redirecting_on_success_source' ] == 'custom_url' AND check_var( $data[ 'params' ][ 'sf_redirecting_on_success' ] ) ) {
						
						//redirect( get_url( $data[ 'params' ][ 'sf_redirecting_on_success' ] ) );
						
					}
					else if ( check_var( $data[ 'params' ][ 'sf_redirecting_on_success_source' ] ) AND $data[ 'params' ][ 'sf_redirecting_on_success_source' ] == 'custom_url' ) {
						
						//redirect( get_url() );
						
					}
					else {
						
						//redirect_last_url();
						
					}
					
				}
			
			}
			
			else if ( check_var( $file_upload_errors ) ){
				
				// removing uploaded files if we got errors
				if ( check_var( $uploaded_files ) ) {
					
					foreach( $uploaded_files as $file ) {
						
						$_file = realpath( $file[ 'destination' ] ) . DS . $file[ 'file' ];
						
						if ( file_exists( $_file ) ) {
							
							unlink( $_file );
							
						}
						
					}
					
				}
				
				rrmdir( $tmp_upload_path );
				
				msg( lang( 'ud_data_prop_file_error_title' ), 'title' );
				
				foreach( $file_upload_errors as $error ) {
					
					msg( $error, 'error' );
					
				}
				
				if ( ! $this->form_validation->run() AND validation_errors() != '' ) {
					
					msg( validation_errors( '<div class="error">', '</div>' ), 'error' );
					
				}
				
			}
			
			else if ( ! $this->form_validation->run() AND validation_errors() != '' ){
				
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
			
			$layout = isset( $menu_item_params[ 'users_submits_layout' ] ) ? $menu_item_params[ 'users_submits_layout' ] : 'default';
			
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
				
				if ( check_var( $this->mcm->current_menu_item ) ) {
					
					$miid = $this->mcm->current_menu_item[ 'id' ];
					
				}
				else {
					
					$miid = '0';
					
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
