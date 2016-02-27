<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'controllers/admin/main.php');

class Submit_forms extends Main {

	var $c_urls;

	public function __construct(){

		parent::__construct();
		
		if ( ! $this->load->is_model_loaded( 'sfcm' ) ) {
			
			$this->load->model( 'common/submit_forms_common_model', 'sfcm' );
			
		}
		
		$this->load->language(array('admin/submit_forms', 'admin/unid'));

		set_current_component();

		// verifica se o usuário atual possui privilégios para gerenciar menus
		if ( ! $this->users->check_privileges('submit_forms_management_submit_forms_management') ){
			msg(('access_denied'),'title');
			msg(('access_denied_submit_forms_management_submit_forms_management'),'error');
			redirect_last_url();
		};

		// -------------------------------------------------
		// Component urls ----------------------------------

		$this->c_urls = array();
		$c_urls = & $this->c_urls;


		$base_link_prefix = $this->mcm->environment . '/' . $this->component_name . '/';

		$c_urls[ 'base_c_url' ] = $base_link_prefix;

		$base_link_array = array();

		$sf_add_link_array = $base_link_array + array(

			'a' => 'asf',

		);
		$sf_edit_link_array = $base_link_array + array(

			'a' => 'esf',

		);
		$sf_list_link_array = $base_link_array + array(

			'a' => 'sfl',

		);
		$sf_search_link_array = $base_link_array + array(

			'a' => 's',

		);
		$sf_remove_link_array = $base_link_array + array(

			'a' => 'r',

		);
		$sf_remove_all_link_array = $base_link_array + array(

			'a' => 'ra',

		);
		$sf_change_order_link_array = $base_link_array + array(

			'a' => 'co',

		);
		$sf_up_order_link_array = $base_link_array + array(

			'a' => 'uo',

		);
		$sf_down_order_link_array = $base_link_array + array(

			'a' => 'do',

		);

		$submit_forms_management_alias = 'sfm/';
		$c_urls[ 'sf_management_link' ] = $base_link_prefix . $submit_forms_management_alias;
		$c_urls[ 'sf_add_link' ] = $c_urls[ 'sf_management_link' ] . $this->uri->assoc_to_uri( $sf_add_link_array );
		$c_urls[ 'sf_edit_link' ] = $c_urls[ 'sf_management_link' ] . $this->uri->assoc_to_uri( $sf_edit_link_array );
		$c_urls[ 'sf_list_link' ] = $c_urls[ 'sf_management_link' ] . $this->uri->assoc_to_uri( $sf_list_link_array );
		$c_urls[ 'sf_search_link' ] = $c_urls[ 'sf_management_link' ] . $this->uri->assoc_to_uri( $sf_search_link_array );
		$c_urls[ 'sf_remove_link' ] = $c_urls[ 'sf_management_link' ] . $this->uri->assoc_to_uri( $sf_remove_link_array );
		$c_urls[ 'sf_remove_all_link' ] = $c_urls[ 'sf_management_link' ] . $this->uri->assoc_to_uri( $sf_remove_all_link_array );
		$c_urls[ 'sf_change_order_link' ] = $c_urls[ 'sf_management_link' ] . $this->uri->assoc_to_uri( $sf_change_order_link_array );
		$c_urls[ 'sf_up_order_link' ] = $c_urls[ 'sf_management_link' ] . $this->uri->assoc_to_uri( $sf_up_order_link_array );
		$c_urls[ 'sf_down_order_link' ] = $c_urls[ 'sf_management_link' ] . $this->uri->assoc_to_uri( $sf_down_order_link_array );

		$us_add_link_array = $base_link_array + array(

			'a' => 'aus',

		);
		$us_edit_link_array = $base_link_array + array(

			'a' => 'eus',

		);
		$us_view_link_array = $base_link_array + array(

			'a' => 'vus',

		);
		$us_list_link_array = $base_link_array + array(

			'a' => 'usl',

		);
		$us_search_link_array = $base_link_array + array(

			'a' => 's',

		);
		$us_remove_link_array = $base_link_array + array(

			'a' => 'r',

		);
		$us_remove_all_link_array = $base_link_array + array(

			'a' => 'ra',

		);
		$us_change_order_link_array = $base_link_array + array(

			'a' => 'co',

		);
		$us_up_order_link_array = $base_link_array + array(

			'a' => 'uo',

		);
		$us_down_order_link_array = $base_link_array + array(

			'a' => 'do',

		);
		$us_batch_link_array = $base_link_array + array(

			'a' => 'b',

		);

		$user_submit_management_alias = 'usm/';
		$c_urls[ 'us_management_link' ] = $base_link_prefix . $user_submit_management_alias;
		$c_urls[ 'us_add_link' ] = $c_urls[ 'us_management_link' ] . $this->uri->assoc_to_uri( $us_add_link_array );
		$c_urls[ 'us_edit_link' ] = $c_urls[ 'us_management_link' ] . $this->uri->assoc_to_uri( $us_edit_link_array );
		$c_urls[ 'us_view_link' ] = $c_urls[ 'us_management_link' ] . $this->uri->assoc_to_uri( $us_view_link_array );
		$c_urls[ 'us_list_link' ] = $c_urls[ 'us_management_link' ] . $this->uri->assoc_to_uri( $us_list_link_array );
		$c_urls[ 'us_search_link' ] = $c_urls[ 'us_management_link' ] . $this->uri->assoc_to_uri( $us_search_link_array );
		$c_urls[ 'us_remove_link' ] = $c_urls[ 'us_management_link' ] . $this->uri->assoc_to_uri( $us_remove_link_array );
		$c_urls[ 'us_remove_all_link' ] = $c_urls[ 'us_management_link' ] . $this->uri->assoc_to_uri( $us_remove_all_link_array );
		$c_urls[ 'us_change_order_link' ] = $c_urls[ 'us_management_link' ] . $this->uri->assoc_to_uri( $us_change_order_link_array );
		$c_urls[ 'us_up_order_link' ] = $c_urls[ 'us_management_link' ] . $this->uri->assoc_to_uri( $us_up_order_link_array );
		$c_urls[ 'us_down_order_link' ] = $c_urls[ 'us_management_link' ] . $this->uri->assoc_to_uri( $us_down_order_link_array );
		$c_urls[ 'us_batch_link' ] = $c_urls[ 'us_management_link' ] . $this->uri->assoc_to_uri( $us_batch_link_array );

		$us_ajax_add_link_array = $base_link_array + array(

			'a' => 'aus',

		);
		$us_ajax_edit_link_array = $base_link_array + array(

			'a' => 'eus',

		);
		$us_ajax_view_link_array = $base_link_array + array(

			'a' => 'gus',

		);
		$us_ajax_list_link_array = $base_link_array + array(

			'a' => 'usl',

		);
		$us_ajax_search_link_array = $base_link_array + array(

			'a' => 's',

		);
		$us_ajax_remove_link_array = $base_link_array + array(

			'a' => 'r',

		);
		$us_ajax_remove_all_link_array = $base_link_array + array(

			'a' => 'ra',

		);
		$us_ajax_change_order_link_array = $base_link_array + array(

			'a' => 'co',

		);
		$us_ajax_up_order_link_array = $base_link_array + array(

			'a' => 'uo',

		);
		$us_ajax_down_order_link_array = $base_link_array + array(

			'a' => 'do',

		);

		$us_ajax_management_link_alias = 'us_ajax/';
		$c_urls[ 'us_ajax_management_link' ] = $base_link_prefix . $us_ajax_management_link_alias;
		$c_urls[ 'us_ajax_add_link' ] = $c_urls[ 'us_ajax_management_link' ] . $this->uri->assoc_to_uri( $us_ajax_add_link_array );
		$c_urls[ 'us_ajax_edit_link' ] = $c_urls[ 'us_ajax_management_link' ] . $this->uri->assoc_to_uri( $us_ajax_edit_link_array );
		$c_urls[ 'us_ajax_view_link' ] = $c_urls[ 'us_ajax_management_link' ] . $this->uri->assoc_to_uri( $us_ajax_view_link_array );
		$c_urls[ 'us_ajax_list_link' ] = $c_urls[ 'us_ajax_management_link' ] . $this->uri->assoc_to_uri( $us_ajax_list_link_array );
		$c_urls[ 'us_ajax_search_link' ] = $c_urls[ 'us_ajax_management_link' ] . $this->uri->assoc_to_uri( $us_ajax_search_link_array );
		$c_urls[ 'us_ajax_remove_link' ] = $c_urls[ 'us_ajax_management_link' ] . $this->uri->assoc_to_uri( $us_ajax_remove_link_array );
		$c_urls[ 'us_ajax_remove_all_link' ] = $c_urls[ 'us_ajax_management_link' ] . $this->uri->assoc_to_uri( $us_ajax_remove_all_link_array );
		$c_urls[ 'us_ajax_change_order_link' ] = $c_urls[ 'us_ajax_management_link' ] . $this->uri->assoc_to_uri( $us_ajax_change_order_link_array );
		$c_urls[ 'us_ajax_up_order_link' ] = $c_urls[ 'us_ajax_management_link' ] . $this->uri->assoc_to_uri( $us_ajax_up_order_link_array );
		$c_urls[ 'us_ajax_down_order_link' ] = $c_urls[ 'us_ajax_management_link' ] . $this->uri->assoc_to_uri( $us_ajax_down_order_link_array );



		$us_export_list_link_array = $base_link_array + array(

			'a' => 'usl',

		);
		$us_export_view_csv_link_array = $us_export_list_link_array + array(

			'ct' => 'csv',

		);
		$us_export_download_csv_link_array = $us_export_list_link_array + array(

			'ct' => 'csv',
			'sa' => 'dl',

		);
		$us_export_view_json_link_array = $us_export_list_link_array + array(

			'ct' => 'json',

		);
		$us_export_download_json_link_array = $us_export_list_link_array + array(

			'ct' => 'json',
			'sa' => 'dl',

		);
		$us_export_view_xls_link_array = $us_export_list_link_array + array(

			'ct' => 'xls',

		);
		$us_export_download_xls_link_array = $us_export_list_link_array + array(

			'ct' => 'xls',
			'sa' => 'dl',

		);
		$us_export_view_html_link_array = $us_export_list_link_array + array(

			'ct' => 'html',

		);
		$us_export_download_html_link_array = $us_export_list_link_array + array(

			'ct' => 'html',
			'sa' => 'dl',

		);
		$us_export_view_txt_link_array = $us_export_list_link_array + array(

			'ct' => 'txt',

		);
		$us_export_download_txt_link_array = $us_export_list_link_array + array(

			'ct' => 'txt',
			'sa' => 'dl',

		);
		$us_export_view_pdf_link_array = $us_export_list_link_array + array(

			'ct' => 'pdf',

		);
		$us_export_download_pdf_link_array = $us_export_list_link_array + array(

			'ct' => 'pdf',
			'sa' => 'dl',

		);

		$us_export_management_link_alias = 'export/';
		$c_urls[ 'us_export_management_link' ] = $base_link_prefix . $us_export_management_link_alias;
		$c_urls[ 'us_export_view_csv_link' ] = $c_urls[ 'us_export_management_link' ] . $this->uri->assoc_to_uri( $us_export_view_csv_link_array );
		$c_urls[ 'us_export_download_csv_link' ] = $c_urls[ 'us_export_management_link' ] . $this->uri->assoc_to_uri( $us_export_download_csv_link_array );
		$c_urls[ 'us_export_view_json_link' ] = $c_urls[ 'us_export_management_link' ] . $this->uri->assoc_to_uri( $us_export_view_json_link_array );
		$c_urls[ 'us_export_download_json_link' ] = $c_urls[ 'us_export_management_link' ] . $this->uri->assoc_to_uri( $us_export_download_json_link_array );
		$c_urls[ 'us_export_view_xls_link' ] = $c_urls[ 'us_export_management_link' ] . $this->uri->assoc_to_uri( $us_export_view_xls_link_array );
		$c_urls[ 'us_export_download_xls_link' ] = $c_urls[ 'us_export_management_link' ] . $this->uri->assoc_to_uri( $us_export_download_xls_link_array );
		$c_urls[ 'us_export_view_html_link' ] = $c_urls[ 'us_export_management_link' ] . $this->uri->assoc_to_uri( $us_export_view_html_link_array );
		$c_urls[ 'us_export_download_html_link' ] = $c_urls[ 'us_export_management_link' ] . $this->uri->assoc_to_uri( $us_export_download_html_link_array );
		$c_urls[ 'us_export_view_txt_link' ] = $c_urls[ 'us_export_management_link' ] . $this->uri->assoc_to_uri( $us_export_view_txt_link_array );
		$c_urls[ 'us_export_download_txt_link' ] = $c_urls[ 'us_export_management_link' ] . $this->uri->assoc_to_uri( $us_export_download_txt_link_array );
		$c_urls[ 'us_export_view_pdf_link' ] = $c_urls[ 'us_export_management_link' ] . $this->uri->assoc_to_uri( $us_export_view_pdf_link_array );
		$c_urls[ 'us_export_download_pdf_link' ] = $c_urls[ 'us_export_management_link' ] . $this->uri->assoc_to_uri( $us_export_download_pdf_link_array );


		// Component urls ----------------------------------
		// -------------------------------------------------



	}

	public function index(){

		$this->sfm();

	}
	
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	/*
	 **************************************************************************************************
	 **************************************************************************************************
	 --------------------------------------------------------------------------------------------------
	 Submit_forms management
	 --------------------------------------------------------------------------------------------------
	 */

	public function sfm(){
		
		require_once 'com_submit_forms' . DS . 'sfm.php';
		
	}

	/*
	 --------------------------------------------------------------------------------------------------
	 Submit_forms management
	 --------------------------------------------------------------------------------------------------
	 **************************************************************************************************
	 **************************************************************************************************
	 */
	
	/*
	 **************************************************************************************************
	 **************************************************************************************************
	 --------------------------------------------------------------------------------------------------
	 Users submits (Função em desenvolvimento)
	 --------------------------------------------------------------------------------------------------
	*/
	
	public function us(){
		
		$get = $this->input->get();
		$post = $this->input->post();
		$f_params = $this->sfcm->get_us_url_params();
		
		// Updating the current component's info
		$this->component_function = __FUNCTION__;
		$this->component_function_action = $f_params[ 'action' ];
		/*
		******************************************
		******************************************
		------------------------------------------
		Users submits list
		------------------------------------------
		*/
		
		if ( $f_params[ 'action' ] == 'l' ){
			
			$api_config[ 'a' ] = 'getsf';
			$api_config[ 'ius' ] = TRUE;
			
			
			// loading the string utils and search libraries
			$this->load->library( array( 'str_utils', 'search', ) );
			
			// loading the pagination helper
			$this->load->helper( array( 'pagination' ) );
			
			// Preparing users submits search plugin params
			$search_config = array(
				
				'plugins' => 'sf_us_search',
				'allow_empty_terms' => TRUE,
				
			);
			
		}
		
		/*
		------------------------------------------
		Users submits list
		------------------------------------------
		******************************************
		******************************************
		*/
		
		print_r( $f_params );
		
	}
	
	/*
	 --------------------------------------------------------------------------------------------------
	 Users submits
	 --------------------------------------------------------------------------------------------------
	 **************************************************************************************************
	 **************************************************************************************************
	*/
	
	/*
	 **************************************************************************************************
	 **************************************************************************************************
	 --------------------------------------------------------------------------------------------------
	 Users submits management
	 --------------------------------------------------------------------------------------------------
	 */

	public function usm( $f_params ){
		
		require_once 'com_submit_forms' . DS . 'usm.php';
		
	}

	/*
	 --------------------------------------------------------------------------------------------------
	 Users submits management
	 --------------------------------------------------------------------------------------------------
	 **************************************************************************************************
	 **************************************************************************************************
	 */
	
	/*
	 **************************************************************************************************
	 **************************************************************************************************
	 --------------------------------------------------------------------------------------------------
	 Export
	 --------------------------------------------------------------------------------------------------
	 */

	public function export( $f_params = NULL ){

		$get = $this->input->get();
		$post = $this->input->post();

		// -------------------------------------------------
		// Parsing vars ------------------------------------

		$f_params = is_array( $f_params ) ? $f_params : $this->uri->ruri_to_assoc();

		$action =								isset( $f_params[ 'a' ] ) ? $f_params[ 'a' ] : ( isset( $get[ 'a' ] ) ? $get[ 'a' ] : NULL ); // action
		$sub_action =							isset( $f_params[ 'sa' ] ) ? $f_params[ 'sa' ] : ( isset( $get[ 'sa' ] ) ? $get[ 'sa' ] : NULL ); // sub action
		$submit_form_id =						isset( $f_params[ 'sfid' ] ) ? $f_params[ 'sfid' ] : ( isset( $get[ 'sfid' ] ) ? $get[ 'sfid' ] : NULL ); // submit form id
		$user_submit_id =						isset( $f_params[ 'usid' ] ) ? $f_params[ 'usid' ] : ( isset( $get[ 'usid' ] ) ? $get[ 'usid' ] : NULL ); // user submit id
		$content_type =							isset( $f_params[ 'ct' ] ) ? $f_params[ 'ct' ] : ( isset( $get[ 'ct' ] ) ? $get[ 'ct' ] : 'txt' ); // return type: json, xml, pdf, etc.
		$cp =									isset( $f_params[ 'cp' ] ) ? $f_params[ 'cp' ] : ( isset( $get[ 'cp' ] ) ? $get[ 'cp' ] : NULL ); // current page
		$ipp =									isset( $f_params[ 'ipp' ] ) ? $f_params[ 'ipp' ] : ( isset( $get[ 'ipp' ] ) ? $get[ 'ipp' ] : NULL ); // items per page
		$ob =									isset( $f_params[ 'ob' ] ) ? $f_params[ 'ob' ] : ( isset( $get[ 'ob' ] ) ? $get[ 'ob' ] : NULL ); // order by
		
		if ( check_var( $post[ 'submit_export' ] ) ){
			
			if ( check_var( $post[ 'selected_users_submits_ids' ] ) ){
				
				$user_submit_id = $post[ 'selected_users_submits_ids' ];
				
			}
			
			if ( check_var( $post[ 'submit_form_id' ] ) ){
				
				$submit_form_id = $post[ 'submit_form_id' ];
				
			}
			
		}

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
		 Get users submits
		 --------------------------------------------------------
		 */
		
		$submit_forms = $users_submits = FALSE;
		
		if ( $action == 'usl' ){
			
			if ( $user_submit_id ){
				
				if ( is_array( $user_submit_id ) ){
					
					$search_config = array(
						
						'plugins' => 'sf_us_search',
						'allow_empty_terms' => TRUE,
						'plugins_params' => array(
							
							'sf_us_search' => array(
								
								'us_id' => $user_submit_id,
								'sf_id' => $submit_form_id,
								
							),
							
						),
						
					);
					
					$this->load->library( 'search' );
					$this->search->config( $search_config );
					
					$users_submits = $this->search->get_full_results( 'sf_us_search', TRUE );
					
				}
				else{
					
					$user_submit = $this->sfcm->get_user_submit( $user_submit_id, $submit_form_id );
					
				}
				
				// get submit form params
				
				
				if ( $users_submits ){
					
					foreach ( $users_submits as $key => & $user_submit ) {
						
						$user_submit[ 'data' ] = get_params( $user_submit[ 'data' ] );
						
						// get submit form params
						$gsfp = array(
							
							'where_condition' => 't1.id = ' . $user_submit[ 'submit_form_id' ],
							'limit' => 1,
							
						);
						
						if ( check_var( $submit_forms[ $user_submit[ 'submit_form_id' ] ] ) ){
							
							$submit_forms[ $user_submit[ 'submit_form_id' ] ][ 'users_submits' ][ $user_submit[ 'id' ] ] = $user_submit;
							
						}
						else {
							
							$submit_form = $this->sfcm->get_submit_forms( $gsfp )->row_array();
							
							if ( $submit_form ){
								
								$submit_form[ 'users_submits' ][ $user_submit[ 'id' ] ] = $user_submit;
								
								$submit_form[ 'fields' ] = get_params( $submit_form[ 'fields' ] );
								$submit_form[ 'params' ] = get_params( $submit_form[ 'params' ] );
								
							}
							
							$submit_forms[ $submit_form[ 'id' ] ] = $submit_form;
							
						}
						
					}
					
				}
				else if ( $user_submit ){
					
					$user_submit[ 'data' ] = get_params( $user_submit[ 'data' ] );
					
					// get submit form params
					$gsfp = array(
						
						'where_condition' => 't1.id = ' . $user_submit[ 'submit_form_id' ],
						'limit' => 1,
						
					);
					
					$submit_form = $this->sfcm->get_submit_forms( $gsfp )->row_array();
					
					if ( $submit_form ){
						
						$submit_form[ 'users_submits' ][ $user_submit[ 'id' ] ] = $user_submit;
						
						$submit_form[ 'fields' ] = get_params( $submit_form[ 'fields' ] );
						$submit_form[ 'params' ] = get_params( $submit_form[ 'params' ] );
						
					}
					
					$submit_forms[ $submit_form[ 'id' ] ] = $submit_form;
					
				}
				
			}
			else {
				
				if ( $submit_form_id ){
					
					// get submit form params
					$gsfp = array(
						
						'where_condition' => 't1.id = ' . $submit_form_id,
						'limit' => 1,
						
					);
					
					$submit_forms = $this->sfcm->get_submit_forms( $gsfp )->result_array(); // note, we're calling result_array(), not row_array()
					
				}
				else{
					
					$submit_forms = $this->sfcm->get_submit_forms()->result_array();
					
				}
				
				// @TODO por aqui deve ser implantado os novos filtros
				
				foreach ( $submit_forms as $key => & $submit_form ) {
					
					$submit_form[ 'fields' ] = get_params( $submit_form[ 'fields' ] );
					$submit_form[ 'params' ] = get_params( $submit_form[ 'params' ] );
					
					// get submit form params
					$gus_params = array(
						
						'sf_id' => $submit_form[ 'id' ],
						
					);
					
					$users_submits = $this->sfcm->get_users_submits( $gus_params )->result_array();
					
					$submit_form[ 'users_submits' ] = array();
					
					foreach ( $users_submits as $key => $user_submit ) {
						
						$user_submit[ 'data' ] = get_params( $user_submit[ 'data' ] );
						
						$submit_form[ 'users_submits' ][ $user_submit[ 'id' ] ] = $user_submit;
						
					}
					
				}
				
			}
			
			if ( $submit_forms ){
				
				$data[ 'submit_forms' ] = & $submit_forms;
				$data[ 'download' ] = ( $sub_action == 'dl' ) ? TRUE : FALSE;
				
				$page_params = array(
					
					'component_view_folder' => $this->component_view_folder,
					'function' => __FUNCTION__,
					'action' => 'get_users_submits',
					'layout' => 'default',
					'view' => 'html',
					'html' => FALSE,
					'load_index' => FALSE,
					
				);
				
				$now = gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] );
				$now = strftime( '%Y-%m-%d %T', $now );
				
				$dl_filename = url_title( $now );
				$data[ 'dl_filename' ] = & $dl_filename;
				
				if ( $content_type == 'json' ){
					
					if ( $data[ 'download' ] ){
						
						header( 'Content-Type: application/json' );
						header( 'Content-Disposition: attachement; filename="' . $dl_filename . '.json' . '"' );
						
					}
					
					$page_params[ 'view' ] = 'json';
					
				}
				else if ( $content_type == 'csv' ){
					
					$page_params[ 'view' ] = 'csv';
					$data[ 'dl_filename' ] .= '.csv';
					
				}
				else if ( $content_type == 'xls' ){
					
					if ( $data[ 'download' ] ){
						
						header( 'Content-Encoding: UTF-8' );
						header( 'Content-type: application/xls;charset=UTF-8' );
						header( "Content-Type: application/force-download" );
						header( "Content-Type: application/octet-stream" );
						header( "Content-Type: application/download" );
						header( 'Content-Disposition: attachment; filename=' . $dl_filename . '.xls' );
						
					}
					
					$page_params[ 'view' ] = 'xls';
					
				}
				else if ( $content_type == 'html' ){
					
					if ( $data[ 'download' ] ){
						
						header( 'Content-Encoding: UTF-8' );
						header( 'Content-type: text/html;charset=UTF-8' );
						header( "Content-Type: application/force-download" );
						header( "Content-Type: application/octet-stream" );
						header( "Content-Type: application/download" );
						header( 'Content-Disposition: attachment; filename=' . $dl_filename . '.html' );
						
					}
					
					$page_params[ 'view' ] = 'html';
					
				}
				else if ( $content_type == 'pdf' ){
					
					
					$this->load->library( 'mpdf' );
					$this->load->helper( 'mpdf' );
					
					$data[ 'dl_filename' ] .= '.pdf';
					$page_params[ 'view' ] = 'pdf';
					$page_params[ 'html' ] = TRUE;
					$page_params[ 'data' ] = $data;
					
					$dimension = 'A4';
					$mpdf = new mPDF('utf-8', $dimension);
					
					$mpdf->WriteHTML( $this->_page( $page_params ) );
					
					$destination = 'I';
					
					if ( $data[ 'download' ] ){
						
						$destination = 'D';
						
					}
					
					$mpdf->Output( $dl_filename . '.pdf', $destination );
					
					exit;
					
				}
				else {
					
					header( 'Content-Type: text/plain' );
					
					if ( $data[ 'download' ] ){
						
						header( 'Content-Encoding: UTF-8' );
						header( 'Content-type: text/plain;charset=UTF-8' );
						header( "Content-Type: application/force-download" );
						header( "Content-Type: application/octet-stream" );
						header( "Content-Type: application/download" );
						header( 'Content-Disposition: attachment; filename=' . $dl_filename . '.txt' );
						
					}
					
					$page_params[ 'view' ] = 'txt';
					
				}
				
				$page_params[ 'data' ] = $data;
				
				$this->_page( $page_params );
				
			}
			
		}
		
		/*
		 --------------------------------------------------------
		 Get users submits
		 --------------------------------------------------------
		 ********************************************************
		 */

	}

	/*
	 --------------------------------------------------------------------------------------------------
	 Export
	 --------------------------------------------------------------------------------------------------
	 **************************************************************************************************
	 **************************************************************************************************
	 */

	/*
	 **************************************************************************************************
	 **************************************************************************************************
	 --------------------------------------------------------------------------------------------------
	 Ajax
	 --------------------------------------------------------------------------------------------------
	 */
	
	public function us_ajax( $action = NULL, $var1 = NULL ){
		
		$get = $this->input->get( NULL, TRUE );
		$post = $this->input->post( NULL, TRUE );
		
		// -------------------------------------------------
		// Parsing vars ------------------------------------
		
		$f_params = $this->uri->ruri_to_assoc();
		
		$ajax =									( isset( $post[ 'ajax' ] ) ? $post[ 'ajax' ] : NULL );
		$action =								isset( $f_params[ 'a' ] ) ? $f_params[ 'a' ] : ( isset( $get[ 'a' ] ) ? $get[ 'a' ] : NULL ); // action
		$sub_action =							isset( $f_params[ 'sa' ] ) ? $f_params[ 'sa' ] : ( isset( $get[ 'sa' ] ) ? $get[ 'sa' ] : NULL ); // sub action
		$submit_form_id =						isset( $f_params[ 'sfid' ] ) ? $f_params[ 'sfid' ] : ( isset( $get[ 'sfid' ] ) ? $get[ 'sfid' ] : NULL ); // submit form id
		$user_submit_id =						isset( $f_params[ 'usid' ] ) ? $f_params[ 'usid' ] : ( isset( $get[ 'usid' ] ) ? $get[ 'usid' ] : NULL ); // user submit id
		$return_format =						isset( $f_params[ 'rf' ] ) ? $f_params[ 'rf' ] : ( isset( $get[ 'rf' ] ) ? $get[ 'rf' ] : 'ajax' ); // return type: json, xml, pdf, etc.
		$cp =									isset( $f_params[ 'cp' ] ) ? $f_params[ 'cp' ] : ( isset( $get[ 'cp' ] ) ? $get[ 'cp' ] : NULL ); // current page
		$ipp =									isset( $f_params[ 'ipp' ] ) ? $f_params[ 'ipp' ] : ( isset( $get[ 'ipp' ] ) ? $get[ 'ipp' ] : NULL ); // items per page
		$ob =									isset( $f_params[ 'ob' ] ) ? $f_params[ 'ob' ] : ( isset( $get[ 'ob' ] ) ? $get[ 'ob' ] : NULL ); // order by
		
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
		 Get user submit
		 --------------------------------------------------------
		 */

		if ( $action == 'gus' AND $submit_form_id AND $user_submit_id ){
			
			// get submit form params
			$gsfp = array(
				
				'where_condition' => 't1.id = ' . $submit_form_id,
				'limit' => 1,
				
			 );
			
			$submit_form = $this->sfcm->get_submit_forms( $gsfp )->row_array();
			$user_submit = $this->sfcm->get_user_submit( $user_submit_id, $submit_form_id );
			
			if ( $submit_form AND $user_submit ){
				
				$data[ 'submit_form' ] = & $submit_form;
				$data[ 'user_submit' ] = & $user_submit;
				
				$submit_form[ 'fields' ] = get_params( $submit_form[ 'fields' ] );
				$submit_form[ 'params' ] = get_params( $submit_form[ 'params' ] );
				
				$us_export_base_link_array = array(
					
					'sfid' => $submit_form_id,
					'usid' => $user_submit_id,
					
				);
				
				$c_urls[ 'us_export_download_json_link' ] = $c_urls[ 'us_export_download_json_link' ] . '/' . $this->uri->assoc_to_uri( $us_export_base_link_array );
				$c_urls[ 'us_export_download_csv_link' ] = $c_urls[ 'us_export_download_csv_link' ] . '/' . $this->uri->assoc_to_uri( $us_export_base_link_array );
				$c_urls[ 'us_export_download_xls_link' ] = $c_urls[ 'us_export_download_xls_link' ] . '/' . $this->uri->assoc_to_uri( $us_export_base_link_array );
				$c_urls[ 'us_export_download_html_link' ] = $c_urls[ 'us_export_download_html_link' ] . '/' . $this->uri->assoc_to_uri( $us_export_base_link_array );
				$c_urls[ 'us_export_download_txt_link' ] = $c_urls[ 'us_export_download_txt_link' ] . '/' . $this->uri->assoc_to_uri( $us_export_base_link_array );
				$c_urls[ 'us_export_download_pdf_link' ] = $c_urls[ 'us_export_download_pdf_link' ] . '/' . $this->uri->assoc_to_uri( $us_export_base_link_array );

				$dl_filename = url_title( $submit_form[ 'title' ] . ' - ' . $user_submit[ 'submit_datetime' ] );

				if ( $return_format === 'json' ){

					$this->output->set_content_type( 'application/json' );

					if ( $sub_action == 'dl' ){

						header( 'Content-Disposition: attachement; filename="' . $dl_filename . '.json' . '"' );

					}

					$out = array();

					foreach ( $submit_form[ 'fields' ] as $key => $field ) {

						if ( ! in_array( $field[ 'field_type' ], array( 'html', 'button' ) ) ) {

							$out[ lang( $field[ 'label' ] ) ] = $user_submit[ 'data' ][ isset( $field[ 'alias' ] ) ? $field[ 'alias' ] : $this->sfcm->make_field_name( $field[ 'label' ] ) ];

						}

					}

					$this->output->set_output( json_encode( $out ) );

				}
				else if ( $return_format === 'csv' ){

					$this->load->helper( 'csv' );

					//$this->output->set_content_type( 'text/csv' );

					$out = array();

					foreach ( $submit_form[ 'fields' ] as $key => $field ) {

						if ( ! in_array( $field[ 'field_type' ], array( 'html', 'button' ) ) ) {

							$out[ 0 ][] = $field[ 'label' ];

						}

					}

					foreach ( $submit_form[ 'fields' ] as $key => $field ) {

						if ( ! in_array( $field[ 'field_type' ], array( 'html', 'button' ) ) ) {

							$out[ 1 ][] = $user_submit[ 'data' ][ isset( $field[ 'alias' ] ) ? $field[ 'alias' ] : $this->sfcm->make_field_name( $field[ 'label' ] ) ];

						}

					}

					if ( $sub_action == 'dl' ){

						$this->output->set_output( array_to_csv( $out, $dl_filename . '.csv' ) );

					}
					else {

						$this->output->set_output( array_to_csv( $out ) );

					}

				}
				else if ( $return_format === 'xls' ){

					header('Content-Encoding: UTF-8');
					header( 'Content-type: application/vnd.ms-excel;charset=UTF-8' );

					if ( $sub_action == 'dl' ){

						header("Content-Type: application/force-download");
						header("Content-Type: application/octet-stream");
						header("Content-Type: application/download");;
						header('Content-Disposition: attachment; filename=' . $dl_filename . '.xls');

					}

					$this->_page(

						array(

							'component_view_folder' => $this->component_view_folder,
							'function' => __FUNCTION__,
							'action' => 'get_user_submit',
							'layout' => 'default',
							'view' => 'get_xls',
							'data' => $data,
							'html' => FALSE,
							'load_index' => FALSE,

						)

					);

				}
				else {

					$this->_page(

						array(

							'component_view_folder' => $this->component_view_folder,
							'function' => __FUNCTION__,
							'action' => 'get_user_submit',
							'layout' => 'default',
							'view' => 'get_user_submit',
							'data' => $data,
							'html' => FALSE,
							'load_index' => FALSE,

						)

					);

				}

			}

		}

		/*
		 --------------------------------------------------------
		 Get user submit
		 --------------------------------------------------------
		 ********************************************************
		 */
		
	}
	
	/*
	 --------------------------------------------------------------------------------------------------
	 Ajax
	 --------------------------------------------------------------------------------------------------
	 **************************************************************************************************
	 **************************************************************************************************
	 */

}
