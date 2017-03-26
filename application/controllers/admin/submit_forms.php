<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'controllers/admin/main.php');

class Submit_forms extends Main {

	var $c_urls;

	public function __construct() {
		
		parent::__construct();
		
		if ( ! $this->load->is_model_loaded( 'sfcm' ) ) {
			
			$this->load->model( 'common/submit_forms_common_model', 'sfcm' );
			
		}
		
		$this->load->language( array( 'admin/submit_forms', 'admin/unid' ) );
		
		set_current_component();
		
		// verifica se o usuário atual possui privilégios para gerenciar menus
		if ( ! $this->users->check_privileges( 'submit_forms_management_submit_forms_management' ) ){
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

			'a' => 'rds',

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

		$data_schemes_management_alias = 'sfm/';
		$c_urls[ 'sf_management_link' ] = $base_link_prefix . $data_schemes_management_alias;
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
		
		// -------------------------------------------------
		// Parsing vars ------------------------------------

		$f_params = $this->uri->ruri_to_assoc();

		$action =								isset( $f_params[ 'a' ] ) ? $f_params[ 'a' ] : 'sfl'; // action
		$sub_action =							isset( $f_params[ 'sa' ] ) ? $f_params[ 'sa' ] : NULL; // sub action
		$ds_id =						isset( $f_params[ 'sfid' ] ) ? $f_params[ 'sfid' ] : NULL; // submit form id
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
			
			$data_schemes = $this->sfcm->get_submit_forms( $gsf_params )->result_array();
			
			foreach ( $data_schemes as $key => & $data_scheme ) {
				
				$this->sfcm->parse_sf( $data_scheme );
				
				$data_scheme_base_link_array = array(
					
					'sfid' => $data_scheme[ 'id' ],
					
				);
				
				$data_scheme[ 'users_submits_link' ] = $c_urls[ 'us_list_link' ] . '/' . $this->uri->assoc_to_uri( $data_scheme_base_link_array );
				$data_scheme[ 'users_submits_add_link' ] = $c_urls[ 'us_add_link' ] . '/' . $this->uri->assoc_to_uri( $data_scheme_base_link_array );
				$data_scheme[ 'change_order_link' ] = $c_urls[ 'sf_change_order_link' ] . '/' . $this->uri->assoc_to_uri( $data_scheme_base_link_array );
				$data_scheme[ 'up_order_link' ] = $c_urls[ 'sf_up_order_link' ] . '/' . $this->uri->assoc_to_uri( $data_scheme_base_link_array );
				$data_scheme[ 'down_order_link' ] = $c_urls[ 'sf_down_order_link' ] . '/' . $this->uri->assoc_to_uri( $data_scheme_base_link_array );
				
				if ( ! empty( $terms ) ){
					
					foreach ( $terms as $term ) {
						
						$data_scheme[ 'id' ] = str_highlight( $data_scheme[ 'id' ], $term );
						$data_scheme[ 'sef_submit_form' ] = str_highlight( $data_scheme[ 'sef_submit_form' ], $term );
						$data_scheme[ 'target' ] = str_highlight( $data_scheme[ 'target' ], $term );
						
					}
					
				}
				
			}
			
			foreach ( $data_schemes as $key => & $data_scheme ) {
				
				$search_config = array(
					
					'plugins' => 'sf_us_search',
					'allow_empty_terms' => TRUE,
					'plugins_params' => array(
						
						'sf_us_search' => array(
							
							'sf_id' => $data_scheme[ 'id' ],
							
						),
						
					),
					
				);
				
				$this->load->library( 'search' );
				$this->search->config( $search_config );
				
				$users_submits = $this->search->get_full_results( 'sf_us_search', TRUE );
				
				$data_scheme[ 'users_submit_count' ] = $this->search->count_all_results( 'sf_us_search' );
				
			}
			
			$data[ 'submit_forms' ] = $data_schemes;
			
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
				
				$data_scheme = array();
				
			}
			
			// Caso contrário, se for de edição
			
			if ( $action == 'esf' ){
				
				// -------------------------
				// Loading UniD API model
				
				$this->load->model( 'common/unid_api_common_model', 'ud_api' );
				
				// -------------------------
				// Obtenção do formulário
				/*
				$data_scheme = $this->ud_api->get_submit_form( $ds_id )->row_array();
				*/
				
				$ud_api = & $this->ud_api;
				
				$data_scheme = $ud_api(
					
					array(
						
						'a' => 'gds',
						'rt' => 2,
						'dsi' => $ds_id,
						
					), TRUE, TRUE
					
				);
				
				if ( isset( $data_scheme[ 'errors' ] ) OR ! isset( $data_scheme[ 'out' ][ 'data_schemes' ][ $ds_id ] ) ) {
					
					echo $data_scheme[ 'errors' ];
					
				}
				else {
					
					$data_scheme = $data_scheme[ 'out' ][ 'data_schemes' ][ $ds_id ];
					
					$data_scheme_base_link_array = array(
						
						'sfid' => $data_scheme[ 'id' ],
						
					);
					
					$data_scheme[ 'users_submits_link' ] = $c_urls[ 'us_list_link' ] . '/' . $this->uri->assoc_to_uri( $data_scheme_base_link_array );
					$data_scheme[ 'users_submits_add_link' ] = $c_urls[ 'us_add_link' ] . '/' . $this->uri->assoc_to_uri( $data_scheme_base_link_array );
					$data_scheme[ 'change_order_link' ] = $c_urls[ 'sf_change_order_link' ] . '/' . $this->uri->assoc_to_uri( $data_scheme_base_link_array );
					$data_scheme[ 'up_order_link' ] = $c_urls[ 'sf_up_order_link' ] . '/' . $this->uri->assoc_to_uri( $data_scheme_base_link_array );
					$data_scheme[ 'down_order_link' ] = $c_urls[ 'sf_down_order_link' ] . '/' . $this->uri->assoc_to_uri( $data_scheme_base_link_array );
					
					$data[ 'submit_form' ] = & $data_scheme;
					
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
			
			$data[ 'data_schemes' ] = $this->sfcm->get_submit_forms()->result_array();
			$data_schemes = & $data[ 'data_schemes' ];
			
			foreach( $data_schemes as $key => & $ds ){
				
				$this->sfcm->parse_sf( $ds );
				
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
					
					$data_scheme[ 'params' ] = array();
					
				}
				
			}
			else {
				
				$admin_ud_dl_filters_profiles = isset( $data_scheme[ 'params' ][ 'admin_ud_dl_filters_profiles' ] ) ? $data_scheme[ 'params' ][ 'admin_ud_dl_filters_profiles' ]: array();
				
				$post_data[ 'params' ][ 'admin_ud_dl_filters_profiles' ] = $admin_ud_dl_filters_profiles;
				
				$data_scheme[ 'params' ] = $post_data[ 'params' ];
				
				// ------------------------------------------------------------------------
				// Fields
				
				// Checking for remove field request
				if ( isset( $post_data[ 'submit_remove_field' ] ) ){
					
					reset( $post_data[ 'submit_remove_field' ] );
					
					while ( list( $key, $value ) = each( $post_data[ 'submit_remove_field' ] ) ) {
						
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
					
					$data_scheme = $post_data;
					
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
			
			if ( isset( $data_scheme[ 'fields' ] ) AND is_array( $data_scheme[ 'fields' ] ) ) {
				
				$temp_array = array();
				
				// TODO TODO TODO TODO TODO TODO TODO
				// Migrar para o plugin tipo ud_gd
				
				// part of articles categories code
				// cache var
				$articles_categories_loaded = FALSE;
				
				reset( $data_scheme[ 'fields' ] );
				
				while ( list( $key, $field ) = each( $data_scheme[ 'fields' ] ) ) {
					
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
				
				/*
				foreach ( $data_scheme[ 'fields' ] as $key => $field ) {
					
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
				*/
				
				$data_scheme[ 'fields' ] = $temp_array;
				unset( $temp_array );
				
				ksort( $data_scheme[ 'fields' ] );
				
				$post_data[ 'fields' ] = & $data_scheme[ 'fields' ];
				
			}
			
			// Fields
			// ------------------------------------------------------------------------
			
			// ------------------------------------------------------------------------
			// Params
			
			if ( $action == 'esf' ){
				
				// obtendo os valores atuais dos parâmetros
				$data[ 'current_params_values' ] = get_params( $data_scheme[ 'params' ] );
				
				//-------------------
				// Adjusting params array values
				$new_values = array();
				
				$current_values = $data[ 'current_params_values' ];
				
				while ( list( $k, $item ) = each( $current_values ) ) {
					
					if ( is_array( $item ) ) {
						
						$new_values = _resolve_array_param_value( $k, $item );
						
						unset( $data[ 'current_params_values' ][ $k ] );
						
					}
					
					$data[ 'current_params_values' ] = $data[ 'current_params_values' ] + $new_values;
					
				}
				
				/*
				foreach( $data[ 'current_params_values' ] as $k => $item ) {
					
					if ( is_array( $item ) ) {
						
						$new_values = _resolve_array_param_value( $k, $item );
						
						unset( $data[ 'current_params_values' ][ $k ] );
						
					}
					
					$data[ 'current_params_values' ] = $data[ 'current_params_values' ] + $new_values;
					
				}
				*/
				
			}
			else{
				
				$data[ 'current_params_values' ] = array();
				
			}
			
			// obtendo as especificações dos parâmetros
			$data[ 'params_spec' ] = $this->sfcm->get_submit_form_params( $data_scheme, $data[ 'current_params_values' ] );
			
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
			
			$data[ 'submit_form' ] = $data_scheme;
			$data[ 'post' ] = & $post_data;
			
			// ------------------------------------------------------------------------
			// UniD plugin submit POST signal
			
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
			
			// UniD plugin submit POST signal
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
				else if ( $post_data AND isset( $post_data[ 'submit_cancel' ] ) ){
					
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
						
						if ( $this->sfcm->update( $db_data, array( 'id' => $ds_id ) ) ){

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

		else if ( $action == 'rds' ){
			
			// -------------------------
			// Loading UniD API model
			
			$this->load->model( 'common/unid_api_common_model', 'ud_api' );
			
			// -------------------------
			// Obtenção do formulário
			/*
			$data_scheme = $this->ud_api->get_submit_form( $ds_id )->row_array();
			*/
			
			$ud_api = & $this->ud_api;
			
			$data_scheme = $ud_api(
				
				array(
					
					'a' => 'gds',
					'rt' => 2,
					'dsi' => $ds_id,
					
				), TRUE, TRUE
				
			);
			
			if ( isset( $data_scheme[ 'errors' ] ) OR ! isset( $data_scheme[ 'out' ][ 'data_schemes' ][ $ds_id ] ) ) {
				
				echo $data_scheme[ 'errors' ];
				
			}
			else {
				
				$data_scheme = $data_scheme[ 'out' ][ 'data_schemes' ][ $ds_id ];
				
				if( $this->input->post( 'submit_cancel' ) ){

					redirect_last_url();

				}
				else if ( $this->input->post( 'submit' ) ){

					if ( $this->sfcm->delete( array( 'id'=>$ds_id ) ) ){

						msg( 'submit_form_deleted', 'success');
						redirect_last_url();

					}
					else{

						msg($this->lang->line( 'url_deleted_fail' ), 'error' );
						redirect_last_url();

					}

				}
				else{
					
					$data[ 'submit_form' ] = $data_scheme;
					
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
		
		log_message( 'debug', "[Components] Submit forms function [" . __FUNCTION__ . "] called." );
		
		$get = $this->input->get( NULL, TRUE );
		$post = $this->input->post( NULL, TRUE );
		
		$data[ 'get' ] = & $get;
		$data[ 'post' ] = & $post;
		
		// -------------------------------------------------
		// Parsing vars ------------------------------------
		
		$f_params = is_array( $f_params ) ? $f_params : $this->uri->ruri_to_assoc();
		
		$action =								isset( $f_params[ 'a' ] ) ? $f_params[ 'a' ] : 'usl'; // action
		$sub_action =							isset( $f_params[ 'sa' ] ) ? $f_params[ 'sa' ] : NULL; // sub action
		$ds_id =						( isset( $f_params[ 'sfid' ] ) AND $f_params[ 'sfid' ] AND is_numeric( $f_params[ 'sfid' ] ) AND $f_params[ 'sfid' ] > 0 ) ? $f_params[ 'sfid' ] : NULL; // submit form id
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

		$data_schemes_list_link_array = $base_link_array + array(

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
		$data[ 'submit_forms_list_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $data_schemes_list_link_array );
		$data[ 'users_submits_list_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $users_submits_list_link_array );
		$data[ 'search_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $search_link_array );
		$data[ 'delete_all_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $delete_all_link_array );

		$c_urls = & $this->c_urls;

		$data[ 'c_urls' ] = & $c_urls;

		// admin url
		$a_url = get_url( $this->environment . $this->uri->ruri_string() );

		if ( $ds_id ){

			$data[ 'submit_form_id' ] = $ds_id;

		}
		
		log_message( 'debug', "[Components] Submit forms function action [" . $action . "] called." );
		
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
			
			// se o id do formulário foi informado, iremos listar as submissões daquele formulário, e com as suas colunas
			if ( $data_scheme = $this->sfcm->get_submit_form( $ds_id )->row_array() ){
				
				$this->sfcm->parse_sf( $data_scheme );
				
				$data_scheme_base_link_array = array(
					
					'sfid' => $data_scheme[ 'id' ],
					
				);
				
				$data_scheme[ 'users_submits_link' ] = $c_urls[ 'us_list_link' ] . '/' . $this->uri->assoc_to_uri( $data_scheme_base_link_array );
				$data_scheme[ 'users_submits_add_link' ] = $c_urls[ 'us_add_link' ] . '/' . $this->uri->assoc_to_uri( $data_scheme_base_link_array );
				$data_scheme[ 'change_order_link' ] = $c_urls[ 'sf_change_order_link' ] . '/' . $this->uri->assoc_to_uri( $data_scheme_base_link_array );
				$data_scheme[ 'up_order_link' ] = $c_urls[ 'sf_up_order_link' ] . '/' . $this->uri->assoc_to_uri( $data_scheme_base_link_array );
				$data_scheme[ 'down_order_link' ] = $c_urls[ 'sf_down_order_link' ] . '/' . $this->uri->assoc_to_uri( $data_scheme_base_link_array );
				
				$search_config[ 'plugins_params' ][ 'sf_us_search' ][ 'sf_id' ] = $ds_id;
				
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
				
				$cols_equals = TRUE;
				
				$__filtered_params = check_var( $this->mcm->filtered_system_params[ 'admin_submit_form_users_submits_columns' ] ) ? $this->mcm->filtered_system_params[ 'admin_submit_form_users_submits_columns' ] : NULL;
				
				if ( check_var( $__filtered_params ) AND is_array( $__filtered_params ) ){
					
					$__sf_params = isset( $this->mcm->filtered_system_params[ 'admin_submit_form_users_submits_columns' ][ 'submit_form_' . $ds_id ] ) ? get_params( $this->mcm->filtered_system_params[ 'admin_submit_form_users_submits_columns' ][ 'submit_form_' . $ds_id ] ) : array();
					
					$this->mcm->filtered_system_params[ 'admin_submit_form_users_submits_columns' ][ 'submit_form_' . $ds_id ] = $__sf_params;
					$user_sf_us_columns = & $this->mcm->filtered_system_params[ 'admin_submit_form_users_submits_columns' ][ 'submit_form_' . $ds_id ];
					
// 					echo '<pre>' . print_r( $__sf_params, TRUE ) . '</pre>'; exit;
					
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
					
					reset( $data_scheme[ 'fields' ] );
					
					while ( list( $key, $field ) = each( $data_scheme[ 'fields' ] ) ) {
						
						if ( ! in_array( $field[ 'field_type' ], array( 'html', 'button' ) ) ){
							
							$new_column = & $columns[];
							
							$new_column[ 'alias' ] = $field[ 'alias' ];
							$new_column[ 'title' ] = ( isset( $field[ 'presentation_label' ] ) AND $field[ 'presentation_label' ] ) ? $field[ 'presentation_label' ] : $field[ 'label' ];
							$new_column[ 'visible' ] = TRUE;
							$new_column[ 'type' ] = $field[ 'field_type' ];
							
						}
						
					}
					
					if ( count( $columns ) != count( $user_sf_us_columns ) ) {
						
						$cols_equals = FALSE;
						
					}
					
					reset( $columns );
					
					if ( $cols_equals ) {
					
						while ( list( $key, $column ) = each( $columns ) ) {
							
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
						
						foreach ( $data_scheme[ 'fields' ] as $key => $field ) {
							
							if ( ! in_array( $field[ 'field_type' ], array( 'html', 'button' ) ) ){
								
								$new_column = & $columns[];
								
								$new_column[ 'alias' ] = $field[ 'alias' ];
								$new_column[ 'title' ] = ( isset( $field[ 'presentation_label' ] ) AND $field[ 'presentation_label' ] ) ? $field[ 'presentation_label' ] : $field[ 'label' ];
								$new_column[ 'visible' ] = in_array( $new_column[ 'alias' ], $post[ 'columns_to_show' ] ) ? TRUE : FALSE;
								$new_column[ 'type' ] = $field[ 'field_type' ];
								
							}
							
						}
						
						$user_preference[ 'admin_submit_form_users_submits_columns' ] = get_params( isset( $this->users->user_data[ 'params' ][ 'admin_submit_form_users_submits_columns' ] ) ? $this->users->user_data[ 'params' ][ 'admin_submit_form_users_submits_columns' ] : NULL );
						
// 						echo '<pre>' . print_r( $columns, TRUE ) . '</pre>';
						
						$user_preference[ 'admin_submit_form_users_submits_columns' ][ 'submit_form_' . $ds_id ] = $columns;
						
						$this->users->set_user_preferences( $user_preference, NULL, TRUE, TRUE );
						
// 						echo '<pre>' . print_r( $this->users->user_data[ 'params' ][ 'admin_submit_form_users_submits_columns' ][ 'submit_form_' . $ds_id ], TRUE ) . '</pre>'; exit;
						
					}
					else {
						
						$columns = $this->mcm->filtered_system_params[ 'admin_submit_form_users_submits_columns' ][ 'submit_form_' . $ds_id ];
						
					}
					
				}
				else if ( ! isset( $__filtered_params[ 'submit_form_' . $ds_id ] ) OR ! is_array( $__filtered_params[ 'submit_form_' . $ds_id ] ) OR ( ! $cols_equals ) ){
					
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
					
					foreach ( $data_scheme[ 'fields' ] as $key => $field ) {
						
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
					
					$user_preference[ 'admin_submit_form_users_submits_columns' ][ 'submit_form_' . $ds_id ] = $columns;
					
					$this->users->set_user_preferences( $user_preference, NULL, TRUE, TRUE );
					
				}
				else {
					
					$columns = $this->mcm->filtered_system_params[ 'admin_submit_form_users_submits_columns' ][ 'submit_form_' . $ds_id ];
					
				}
				
				$columns = is_array( $columns ) ? array_filter( $columns ) : array();
				
				$data[ 'columns' ] = $columns;
				
				/*
				--------------------------------------------------------
				Colunas a serem exibidas
				--------------------------------------------------------
				********************************************************
				*/
				
				$data[ 'submit_form' ] = $data_scheme;
				
			}
			
			/*
				********************************************************
				--------------------------------------------------------
				Ordenção por colunas
				--------------------------------------------------------
				*/
			
			$ob_user_preference_name = 'users_submits_list_order_by';
			$obd_user_preference_name = 'users_submits_list_order_by_direction';
			
			if ( $ds_id ){
				
				$ob_user_preference_name .= '_sf' . $ds_id;
				$obd_user_preference_name .= '_sf' . $ds_id;
				
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
				$ds_id AND
				is_numeric( $this->users->get_user_preference( $this->mcm->environment . '_users_submits_list_items_per_page_sf_' . $ds_id ) ) AND
				$this->users->get_user_preference( $this->mcm->environment . '_users_submits_list_items_per_page_sf_' . $ds_id ) > -1 AND
				! isset( $post[ 'ipp' ] )
				
			){
				
				$ipp = $this->users->get_user_preference( $this->mcm->environment . '_users_submits_list_items_per_page_sf_' . $ds_id );
				
			}
			else if (
				
				! isset( $ipp ) AND
				is_numeric( $this->users->get_user_preference( $this->mcm->environment . '_users_submits_list_items_per_page' ) ) AND
				$this->users->get_user_preference( $this->mcm->environment . '_users_submits_list_items_per_page' ) > -1 AND
				! isset( $post[ 'ipp' ] )
				
			){
				
				$ipp = $this->users->get_user_preference( $this->mcm->environment . '_users_submits_list_items_per_page' );
				
			}
			
			if ( ! isset( $ipp ) OR $ipp == -1 ){
				
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
			
			if ( $ipp == '' ){
				
				$ipp = NULL;
				
			}
			
			if ( isset( $data_scheme[ 'params' ][ 'sf_us_list_max_ipp' ] ) AND $ipp > $data_scheme[ 'params' ][ 'sf_us_list_max_ipp' ] ) {
				
				$ipp = $data_scheme[ 'params' ][ 'sf_us_list_max_ipp' ];
				
			}
			
			if ( $__new_ipp ) {
				
				if ( $ds_id ) {
					
					$this->users->set_user_preferences( array( $this->mcm->environment . '_users_submits_list_items_per_page_sf_' . $ds_id => $ipp ) );
					
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
				
				if ( $ds_id ){
					
					$sfbla = array(
						
						'sfid' => $ds_id,
						
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
			
			if ( $ds_id ){
				
				$ob_user_preference_name .= '_sf' . $ds_id;
				$obd_user_preference_name .= '_sf' . $ds_id;
				
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
				
	// 			echo 'cob: <pre>' . print_r( $f_params, TRUE ) . '</pre>';
				
				$this->{ $redirect_c_function }( $f_params );
				
			}
			else if ( ! $this->input->post( 'ajax', TRUE ) ) {
				
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
			
			if ( $ds_id ) {
				
				// get submit form params
				$gsfp = array(
					
					'where_condition' => 't1.id = ' . $ds_id,
					'limit' => 1,
					
				);
				
				$data_scheme = $this->sfcm->get_submit_forms( $gsfp )->row_array();
				
				if ( ! $data_scheme ) {
					
					msg( ( 'submit_form_do_not_exist' ), 'error' );
					redirect_last_url();
					
				}
				
				$this->sfcm->parse_sf( $data_scheme, TRUE );
				$data[ 'submit_form' ] = & $data_scheme;
				
				$xss_filtering = TRUE;
				
				if ( isset( $data_scheme[ 'params' ][ 'ud_data_props_enable_xss_filtering_admin' ] ) ) {
					
					if ( ! check_var( $data_scheme[ 'params' ][ 'ud_data_props_enable_xss_filtering_admin' ] ) ) {
						
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
				
				$data_scheme_base_link_array = array(
					
					'sfid' => $data_scheme[ 'id' ],
					
				);
				
				$data_scheme[ 'edit_link' ] = $c_urls[ 'sf_edit_link' ] . '/' . $this->uri->assoc_to_uri( $data_scheme_base_link_array );
				$data_scheme[ 'remove_link' ] = $c_urls[ 'sf_remove_link' ] . '/' . $this->uri->assoc_to_uri( $data_scheme_base_link_array );
				$data_scheme[ 'users_submits_link' ] = $c_urls[ 'us_list_link' ] . '/' . $this->uri->assoc_to_uri( $data_scheme_base_link_array );
				$data_scheme[ 'users_submits_add_link' ] = $c_urls[ 'us_add_link' ] . '/' . $this->uri->assoc_to_uri( $data_scheme_base_link_array );
				$data_scheme[ 'change_order_link' ] = $c_urls[ 'sf_change_order_link' ] . '/' . $this->uri->assoc_to_uri( $data_scheme_base_link_array );
				$data_scheme[ 'up_order_link' ] = $c_urls[ 'sf_up_order_link' ] . '/' . $this->uri->assoc_to_uri( $data_scheme_base_link_array );
				$data_scheme[ 'down_order_link' ] = $c_urls[ 'sf_down_order_link' ] . '/' . $this->uri->assoc_to_uri( $data_scheme_base_link_array );
				
				if ( $action == 'aus' ){
					
				}
				else if ( $action == 'eus' AND $user_submit_id ){
					
					$user_submit = $this->sfcm->get_user_submit( $user_submit_id );
					
					if ( ! $data_scheme ) {
						
						msg( ( 'user_submit_do_not_exist' ), 'error' );
						redirect_last_url();
						
					}
					
					$data[ 'user_submit' ] = & $user_submit;
					
				}
				
				foreach ( $data_scheme[ 'fields' ] as $key => $prop ) {
					
					$prop_name = $prop[ 'alias' ];
					
					$formatted_field_name = 'form[' . $prop_name . ']';
					
					$rules = array( 'trim' );
					
					if ( ! check_var( $no_validation_fields[ $prop_name ] ) ) {
						
						if ( check_var( $prop[ 'field_is_required' ] ) ){
							
							$rules[] = 'required';
							
						}
						
						if ( isset( $prop[ 'validation_rule' ] ) AND is_array( $prop[ 'validation_rule' ] ) ){
							
							foreach ( $prop[ 'validation_rule' ] as $key => $rule ) {
								
								$comp = '';
								$skip = FALSE;
								
								switch ( $rule ) {
									
									case 'matches':
										
										$comp .= '[form[' . $prop[ 'validation_rule_parameter_matches'] . ']]';
										break;
										
									case 'min_length':
										
										$comp .= '[' . $prop[ 'validation_rule_parameter_min_length'] . ']';
										break;
										
									case 'max_length':
										
										$comp .= '[' . $prop[ 'validation_rule_parameter_max_length'] . ']';
										break;
										
									case 'exact_length':
										
										$comp .= '[' . $prop[ 'validation_rule_parameter_exact_length'] . ']';
										break;
										
									case 'less_than':
										
										$comp .= '[' . $prop[ 'validation_rule_parameter_less_than'] . ']';
										break;
										
									case 'less_than':
										
										$comp .= '[' . $prop[ 'validation_rule_parameter_less_than'] . ']';
										break;
										
									case 'valid_email':
										
										if ( ! isset( $data[ 'post' ][ 'form' ][ $prop_name ] ) OR $data[ 'post' ][ 'form' ][ $prop_name ] != '' ) {
											
											$rules[] = $rule . $comp;
											
										}
										
									case 'mask':
										
										if ( $data[ 'post' ][ 'form' ][ $prop[ 'alias' ] ] AND isset( $prop[ 'ud_validation_rule_parameter_mask_type' ] ) ) {
											
											$_POST[ 'form' ][ $prop[ 'alias' ] ] = $data[ 'post' ][ 'form' ][ $prop[ 'alias' ] ] = unmask( $data[ 'post' ][ 'form' ][ $prop[ 'alias' ] ], $prop[ 'ud_validation_rule_parameter_mask_type' ], isset( $prop[ 'ud_validation_rule_parameter_mask_custom_mask' ] ) ? $prop[ 'ud_validation_rule_parameter_mask_custom_mask' ] : '' );
											
										}
										
										$skip = TRUE;
										unset( $prop[ 'validation_rule' ][ $key ] );
										break;
										
								}
								
								if ( ! $skip ) {
									
									$rules[] = $rule . $comp;
									
								}
								
							}
							
						}
						
					}
					
					// xss filtering
					if ( check_var( $data_scheme[ 'params' ][ 'ud_data_props_enable_xss_filtering_admin' ] ) ) {
						
						$rules[] = 'xss';
						
					}
					
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
						
						$data_scheme[ 'fields' ][ $key ][ 'articles' ] = check_var( $articles ) ? $articles : array();
						
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
					else if ( $prop[ 'field_type' ] === 'textarea' ) {
						
						if ( isset( $user_submit[ 'data' ][ $prop[ 'alias' ] ] ) AND ! is_array( $user_submit[ 'data' ][ $prop[ 'alias' ] ] ) ) {
							
							$user_submit[ 'data' ][ $prop[ 'alias' ] ] = htmlspecialchars_decode( $user_submit[ 'data' ][ $prop[ 'alias' ] ] );
							
						}
						
					}
					
					$rules = join( '|', $rules );
					
					$this->form_validation->set_rules( $formatted_field_name, lang( $prop[ 'label' ] ), $rules );
					
				}
				
				// aqui definimos as ações obtidas via post, ex.: ao salvar, cancelar, adicionar campo, etc.
				// acionados ao submeter os forms
				$submit_action =
					
					$this->input->post( 'submit_cancel' ) ? 'cancel' :
					( $this->input->post( 'submit' ) ? 'submit' :
					( $this->input->post( 'submit_apply' ) ? 'apply' :
					'none' ) );
					
				
				//echo '<pre>' . print_r( $user_submit, TRUE ) . '</pre>'; exit;
				/*
		echo '<pre>' . print_r( $_POST, TRUE ) . '</pre>'; exit;
		*/
				if ( in_array( $submit_action, array( 'cancel' ) ) ) {
					
					redirect_last_url();
					
				}
				
				// se a validação dos campos for positiva
				else if ( $this->form_validation->run() AND ( in_array( $submit_action, array( 'submit', 'apply' ) ) ) ){
					
					// Adjusting not showed fields
					
					$_tmp = array();
					
					foreach ( $data_scheme[ 'fields' ] as $k => $prop ) {
						
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
					
					$db_data[ 'id' ] = $user_submit_id;
					$db_data[ 'submit_form_id' ] = $ds_id;
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
							
							msg( ( 'submit_form_user_submit_created' ), 'success' );
							
							if ( $this->input->post( 'submit_apply' ) ){
								
								$assoc_to_uri_array = array(
									
									'sfid' => $ds_id,
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
							
							msg( ( 'submit_form_user_submit_updated' ), 'success' );
							
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
		View user submit
		--------------------------------------------------------
		*/

		else if ( $action == 'vus' ){

			if ( $ds_id AND $user_submit_id ){

				// get submit form params
				$gus_params = array(

					'where_condition' => 't1.id = ' . $user_submit_id,
					'limit' => 1,

					);

				// get submit form params
				$gsfp = array(

					'where_condition' => 't1.id = ' . $ds_id,
					'limit' => 1,

					);

				$data_scheme = $this->sfcm->get_submit_forms( $gsfp )->row_array();
				$user_submit = $this->sfcm->get_user_submit( $user_submit_id, $ds_id );

				$data[ 'submit_form' ] = & $data_scheme;
				$data[ 'user_submit' ] = & $user_submit;

				$data_scheme[ 'fields' ] = get_params( $data_scheme[ 'fields' ] );
				$data_scheme[ 'params' ] = get_params( $data_scheme[ 'params' ] );

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
		
		else {
			
			show_404();
			
		}
		
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
		$ds_id =						isset( $f_params[ 'sfid' ] ) ? $f_params[ 'sfid' ] : ( isset( $get[ 'sfid' ] ) ? $get[ 'sfid' ] : NULL ); // submit form id
		$user_submit_id =						isset( $f_params[ 'usid' ] ) ? $f_params[ 'usid' ] : ( isset( $get[ 'usid' ] ) ? $get[ 'usid' ] : NULL ); // user submit id
		$content_type =							isset( $f_params[ 'ct' ] ) ? $f_params[ 'ct' ] : ( isset( $get[ 'ct' ] ) ? $get[ 'ct' ] : 'txt' ); // return type: json, xml, pdf, etc.
		$cp =									isset( $f_params[ 'cp' ] ) ? $f_params[ 'cp' ] : ( isset( $get[ 'cp' ] ) ? $get[ 'cp' ] : NULL ); // current page
		$ipp =									isset( $f_params[ 'ipp' ] ) ? $f_params[ 'ipp' ] : ( isset( $get[ 'ipp' ] ) ? $get[ 'ipp' ] : NULL ); // items per page
		$ob =									isset( $f_params[ 'ob' ] ) ? $f_params[ 'ob' ] : ( isset( $get[ 'ob' ] ) ? $get[ 'ob' ] : NULL ); // order by
		
		$download = FALSE;
		
		if ( check_var( $post[ 'submit_export' ] ) ){
			
			if ( check_var( $post[ 'selected_users_submits_ids' ] ) ){
				
				$user_submit_id = $post[ 'selected_users_submits_ids' ];
				
			}
			
			if ( check_var( $post[ 'submit_form_id' ] ) ){
				
				$ds_id = $post[ 'submit_form_id' ];
				
			}
			
			$download = TRUE;
			
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
		
		$data_schemes = $users_submits = FALSE;
		
		if ( $action == 'usl' ){
			
			if ( $user_submit_id ){
				
				if ( is_array( $user_submit_id ) ){
					
					$search_config = array(
						
						'plugins' => 'sf_us_search',
						'allow_empty_terms' => TRUE,
						'plugins_params' => array(
							
							'sf_us_search' => array(
								
								'us_id' => $user_submit_id,
								'sf_id' => $ds_id,
								
							),
							
						),
						
					);
					
					$this->load->library( 'search' );
					$this->search->config( $search_config );
					
					$users_submits = $this->search->get_full_results( 'sf_us_search', TRUE );
					
				}
				else{
					
					$user_submit = $this->sfcm->get_user_submit( $user_submit_id, $ds_id );
					
				}
				
				// get submit form params
				
				if ( $users_submits ){
					
					foreach ( $users_submits as $key => & $user_submit ) {
						
						$user_submit = $this->sfcm->parse_ud_d_data( $user_submit );
						
						// get submit form params
						$gsfp = array(
							
							'where_condition' => 't1.id = ' . $user_submit[ 'submit_form_id' ],
							'limit' => 1,
							
						);
						
						if ( check_var( $data_schemes[ $user_submit[ 'submit_form_id' ] ] ) ){
							
							$data_schemes[ $user_submit[ 'submit_form_id' ] ][ 'users_submits' ][ $user_submit[ 'id' ] ] = $user_submit;
							
						}
						else {
							
							$data_scheme = $this->sfcm->get_submit_forms( $gsfp )->row_array();
							
							if ( $data_scheme ){
								
								$data_scheme[ 'users_submits' ][ $user_submit[ 'id' ] ] = $user_submit;
								
								$data_scheme[ 'fields' ] = get_params( $data_scheme[ 'fields' ] );
								$data_scheme[ 'params' ] = get_params( $data_scheme[ 'params' ] );
								
							}
							
							$data_schemes[ $data_scheme[ 'id' ] ] = $data_scheme;
							
						}
						
					}
					
				}
				else if ( $user_submit ){
					
					$user_submit = $this->sfcm->parse_ud_d_data( $user_submit );
					
					// get submit form params
					$gsfp = array(
						
						'where_condition' => 't1.id = ' . $user_submit[ 'submit_form_id' ],
						'limit' => 1,
						
					);
					
					$data_scheme = $this->sfcm->get_submit_forms( $gsfp )->row_array();
					
					$this->sfcm->parse_sf( $data_scheme );
					
					if ( $data_scheme ){
						
						$data_scheme[ 'users_submits' ][ $user_submit[ 'id' ] ] = $user_submit;
						
					}
					
					$data_schemes[ $data_scheme[ 'id' ] ] = $data_scheme;
					
				}
				
			}
			else {
				
				if ( $ds_id ){
					
					// get submit form params
					$gsfp = array(
						
						'where_condition' => 't1.id = ' . $ds_id,
						'limit' => 1,
						
					);
					
					$data_schemes = $this->sfcm->get_submit_forms( $gsfp )->result_array(); // note, we're calling result_array(), not row_array()
					
				}
				else{
					
					$data_schemes = $this->sfcm->get_submit_forms()->result_array();
					
				}
				
				foreach ( $data_schemes as $key => & $data_scheme ) {
					
					$this->sfcm->parse_sf( $data_scheme );
					
					$search_config = array(
						
						'plugins' => 'sf_us_search',
						'allow_empty_terms' => TRUE,
						'plugins_params' => array(
							
							'sf_us_search' => array(
								
								'sf_id' => $data_scheme[ 'id' ],
								
							),
							
						),
						
					);
					
					$this->load->library( 'search' );
					$this->search->config( $search_config );
					
					$users_submits = $this->search->get_full_results( 'sf_us_search', TRUE );
					
					$data_scheme[ 'users_submits' ] = array();
					
					foreach ( $users_submits as $key => $user_submit ) {
						
						$user_submit = $this->sfcm->parse_ud_d_data( $user_submit );
						
						$data_scheme[ 'users_submits' ][ $user_submit[ 'id' ] ] = $user_submit;
						
					}
					
				}
				
			}
			
			if ( $data_schemes ){
				
				$data[ 'submit_forms' ] = & $data_schemes;
				$data[ 'download' ] = $download ? TRUE : ( ( $sub_action == 'dl' ) ? TRUE : FALSE );
				
				$page_params = array(
					
					'component_view_folder' => $this->component_view_folder,
					'function' => __FUNCTION__,
					'action' => 'ud_data',
					'layout' => 'default',
					'view' => 'html',
					'html' => FALSE,
					'load_index' => FALSE,
					
				);
				
				$now = gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] );
				$now = strftime( '%Y-%m-%d %T', $now );
				
				$dl_filename = url_title( $now );
				$data[ 'dl_filename' ] = & $dl_filename;
				
				$page_params[ 'data' ] = & $data;
				
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
					
// 					header( 'Content-Type: application/vnd.ms-excel; charset=utf-8' );
// 					header( 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' );
// 					header( 'Content-Disposition: attachment; filename=' . $dl_filename . '.xlsx' );
// 					header( 'Cache-Control: max-age=0' );
					
					$page_params[ 'view' ] = 'xls';
					
					
					
// 					$page_params[ 'html' ] = TRUE;
// 					$html = $this->_page( $page_params );
// 					
// 					$this->load->library( 'excel' );
// 					
// 					$tmpfile = tempnam( sys_get_temp_dir(), 'html' );
// 					file_put_contents( $tmpfile, $html );
// 					
// 					$reader = new PHPExcel_Reader_HTML; 
// 					$content = $reader->load( $tmpfile ); 
// 					
// 					$objWriter = PHPExcel_IOFactory::createWriter( $content, 'Excel2007' );
// 					
// 					$objWriter->save( 'php://output' );
// 					
// 					// Delete temporary file
// 					unlink($tmpfile);
// 					
// 					exit;
					
					
					
// 					$page_params[ 'html' ] = TRUE;
// 					
// 					$html = $this->_page( $page_params );
// 					
// 					$tmpfile = tempnam( sys_get_temp_dir(), 'html' );
// 					file_put_contents( $tmpfile, $html );
// 					
// 					$this->load->library( 'excel' );
// 					
// 					$objPHPExcel     = new PHPExcel();
// 					$excelHTMLReader = PHPExcel_IOFactory::createReader('HTML');
// 					$excelHTMLReader->loadIntoExisting($tmpfile, $objPHPExcel);
// 					$objPHPExcel->getActiveSheet()->setTitle('any name you want');
// 					
// 					unlink( $tmpfile );
// 					
// 					$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
// 					
// 					foreach (range('A', $objPHPExcel->getActiveSheet()->getHighestDataColumn()) as $col) {
// 						$objPHPExcel->getActiveSheet()
// 								->getColumnDimension($col)
// 								->setAutoSize(true);
// 					} 
// 					
// 					$writer->save('php://output');
// 					
// 					exit;
					
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
					
					$mpdf->Output( $data[ 'dl_filename' ], $destination );
					
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
				
				$this->_page( $page_params );
				
			}
			
		}
		
		/*
		 --------------------------------------------------------
		 Get users submits
		 --------------------------------------------------------
		 ********************************************************
		 */
		
		else {
			
			show_404();
			
		}
		
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
	 Global config [IN PROGRESS]
	 --------------------------------------------------------------------------------------------------
	*/
	
	public function gc( $f_params = NULL ){
		
		$get = $this->input->get( NULL, TRUE );
		$post = $this->input->post( NULL, TRUE );
		
		$data[ 'get' ] = & $get;
		$data[ 'post' ] = & $post;
		
		// -------------------------------------------------
		// Parsing vars ------------------------------------
		
		$f_params = is_array( $f_params ) ? $f_params : $this->uri->ruri_to_assoc();
		
		$action =								isset( $f_params[ 'a' ] ) ? $f_params[ 'a' ] : 'uf'; // action
		
		// Parsing vars ------------------------------------
		// -------------------------------------------------
		
		// updating current component info
		$this->component_function = __FUNCTION__;
		$this->component_function_action = $action;
		
		if ( $action == 'uf' ){
			
			if ( $component = $this->main_model->get_component( array( 'unique_name' => $this->component_name ) )->row_array() ){
				
				$data[ 'component' ] = & $component;
				
				/******************************/
				/********* Parâmetros *********/
				
				// getting current component params
				$component[ 'params' ] = get_params( $component[ 'params' ] );
				
				echo '<pre>' . print_r( $component, TRUE ) . '</pre>'; exit;
				
				// getting params specifications only for users fields
				$data[ 'params' ] = $this->sfm->get_ud_users_fields_params();
				
				// cruzando os valores padrões das especificações com os do DB
				$params_values = array_merge( $data['params']['params_spec_values'], $data['component']->params );

				// convertendo todos os elementos para html
				$data['params'] = params_to_html( $data['params'], $params_values );

				/********* Parâmetros *********/
				/******************************/

				$this->form_validation->set_rules('component_id',lang('id'),'trim|required|integer');

				if($this->input->post('submit_cancel')){
					redirect_last_url();
				}
				// se a validação dos campos for positiva
				else if ($this->form_validation->run() AND ($this->input->post('submit') OR $this->input->post('submit_apply'))){

					$update_data = elements(array(
						'params',
					),$this->input->post());

					$update_data['params'] = json_encode($update_data['params']);

					if ($this->main_model->update_component($update_data, array('id' => $this->input->post('component_id')))){
						msg(('component_preferences_updated'),'success');

						if ($this->input->post('submit_apply')){

							//redirect('admin/'.$this->component_name . '/' . __FUNCTION__ . '/' . $action);
						}
						else{
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
	
	/*
	 --------------------------------------------------------------------------------------------------
	 Global config
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
		$ds_id =						isset( $f_params[ 'sfid' ] ) ? $f_params[ 'sfid' ] : ( isset( $get[ 'sfid' ] ) ? $get[ 'sfid' ] : NULL ); // submit form id
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

		if ( $action == 'gus' AND $ds_id AND $user_submit_id ){
			
			// get submit form params
			$gsfp = array(
				
				'where_condition' => 't1.id = ' . $ds_id,
				'limit' => 1,
				
			 );
			
			$data_scheme = $this->sfcm->get_submit_forms( $gsfp )->row_array();
			$user_submit = $this->sfcm->get_user_submit( $user_submit_id, $ds_id );
			
			if ( $data_scheme AND $user_submit ){
				
				$this->sfcm->parse_sf( $data_scheme );
				
				$data[ 'submit_form' ] = & $data_scheme;
				$data[ 'user_submit' ] = & $user_submit;
				
				$us_export_base_link_array = array(
					
					'sfid' => $ds_id,
					'usid' => $user_submit_id,
					
				);
				
				$c_urls[ 'us_export_download_json_link' ] = $c_urls[ 'us_export_download_json_link' ] . '/' . $this->uri->assoc_to_uri( $us_export_base_link_array );
				$c_urls[ 'us_export_download_csv_link' ] = $c_urls[ 'us_export_download_csv_link' ] . '/' . $this->uri->assoc_to_uri( $us_export_base_link_array );
				$c_urls[ 'us_export_download_xls_link' ] = $c_urls[ 'us_export_download_xls_link' ] . '/' . $this->uri->assoc_to_uri( $us_export_base_link_array );
				$c_urls[ 'us_export_download_html_link' ] = $c_urls[ 'us_export_download_html_link' ] . '/' . $this->uri->assoc_to_uri( $us_export_base_link_array );
				$c_urls[ 'us_export_download_txt_link' ] = $c_urls[ 'us_export_download_txt_link' ] . '/' . $this->uri->assoc_to_uri( $us_export_base_link_array );
				$c_urls[ 'us_export_download_pdf_link' ] = $c_urls[ 'us_export_download_pdf_link' ] . '/' . $this->uri->assoc_to_uri( $us_export_base_link_array );

				$dl_filename = url_title( $data_scheme[ 'title' ] . ' - ' . $user_submit[ 'submit_datetime' ] );

				if ( $return_format === 'json' ){

					$this->output->set_content_type( 'application/json' );

					if ( $sub_action == 'dl' ){

						header( 'Content-Disposition: attachement; filename="' . $dl_filename . '.json' . '"' );

					}

					$out = array();

					foreach ( $data_scheme[ 'fields' ] as $key => $field ) {

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

					foreach ( $data_scheme[ 'fields' ] as $key => $field ) {

						if ( ! in_array( $field[ 'field_type' ], array( 'html', 'button' ) ) ) {

							$out[ 0 ][] = $field[ 'label' ];

						}

					}

					foreach ( $data_scheme[ 'fields' ] as $key => $field ) {

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
