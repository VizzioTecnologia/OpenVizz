<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'controllers/admin/main.php');

class Courses extends Main {

	public function __construct(){
		
		parent::__construct();
		
		$this->load->database();
		
		if ( ! $this->load->is_model_loaded( 'courses' ) ) {
			
			$this->load->model( 'courses_mdl', 'courses' );
			
		}
		
		set_current_component();
		
		// verifica se o usuário atual possui privilégios para gerenciar artigos
		if ( ! $this->users->check_privileges('courses_management_courses_management') ){
			msg(('access_denied'),'title');
			msg(('access_denied_courses_management_courses_management'),'error');
			redirect_last_url();
		};
		
	}
	
	public function index(){
		
		$this->ctm();
		
	}
	
	/*
	 **************************************************************************************************
	 **************************************************************************************************
	 --------------------------------------------------------------------------------------------------
	 Courses centers management
	 --------------------------------------------------------------------------------------------------
	 */
	
	public function ctm(){
		
		// -------------------------------------------------
		// Parsing vars ------------------------------------
		
		$f_params = $this->uri->ruri_to_assoc();
		
		$action =								isset( $f_params[ 'a' ] ) ? $f_params[ 'a' ] : 'l'; // action
		$sub_action =							isset( $f_params[ 'sa' ] ) ? $f_params[ 'sa' ] : NULL; // sub action
		$id =									isset( $f_params[ 'ctid' ] ) ? $f_params[ 'ctid' ] : NULL; // id
		$category_id =							isset( $f_params[ 'cid' ] ) ? $f_params[ 'cid' ] : NULL; // category id
		$order_by =								isset( $f_params[ 'ob' ] ) ? $f_params[ 'ob' ] : NULL; // order by
		$post =									$this->input->post( NULL, TRUE ); // post array input
		$get =									$this->input->get(); // get array input
		
		// -------------
		
		$cp =									isset( $f_params[ 'cp' ] ) ? ( int ) $f_params[ 'cp' ] : NULL; // current page
			$cp =								( $cp < 1 ) ? 1 : $cp;
		$ipp =									isset( $f_params[ 'ipp' ] ) ? ( int ) $f_params[ 'ipp' ] : NULL; // items per page
			$ipp =								isset( $post[ 'ipp' ] ) ? ( int ) $post[ 'ipp' ] : $ipp; // items per page
		
		if (
			
			is_numeric( $this->users->get_user_preference( $this->mcm->environment . '_courses_centers_items_per_page' ) ) AND
			$this->users->get_user_preference( $this->mcm->environment . '_courses_centers_items_per_page' ) > -1 AND
			! isset( $post[ 'ipp' ] )
			
		){
			
			$ipp = $this->users->get_user_preference( $this->mcm->environment . '_courses_centers_items_per_page' );
			
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
		
		// Parsing vars ------------------------------------
		// -------------------------------------------------
		
		$this->component_function = __FUNCTION__;
		$this->component_function_action = $action;
		
		// admin url
		$a_url = get_url( $this->environment . $this->uri->ruri_string() );
		
		if ( $action ){
		
			/*
			 ********************************************************
			 --------------------------------------------------------
			 List / Search
			 --------------------------------------------------------
			 */

			if ( $action == 'l' OR $action === 's' ){

				$this->load->helper( array( 'pagination' ) );

				// -------------------------------------------------
				// Ordering ----------------------------------------

				if ( ! ( ( $order_by_direction = $this->users->get_user_preference( 'articles_order_by_direction' ) ) != FALSE ) ){

					$order_by_direction = 'ASC';

				}

				if ( check_var( $this->users->get_user_preference( 'articles_order_by' ) ) ){

					$order_by = $this->users->get_user_preference( 'articles_order_by' );


				}
				$data[ 'order_by' ] = $order_by;

				$data[ 'order_by_direction' ] = $order_by_direction;

				// Ordering ----------------------------------------
				// -------------------------------------------------

				// -------------------------------------------------
				// Filtering ---------------------------------------

				// items per page
				if ( isset( $post[ 'ipp' ] ) AND isset( $post[ 'submit_change_ipp' ] ) ){

					// setting the user preference
					$this->users->set_user_preferences( array( $this->mcm->environment . '_articles_items_per_page' => $post[ 'ipp' ] ) );

					// we also have to set the current page as 1, to cut the risk of the search result falls outside the range
					$cp = 1;

				}

				// -------------

				// category
				$filter_by_category = $this->users->get_user_preference( 'articles_filter_by_category' );

				if ( isset( $post[ 'articles_filter_by_category' ] ) AND isset( $post[ 'submit_filter_by_category' ] ) ){

					$filter_by_category = $post[ 'articles_filter_by_category' ];

					// setting the user preference
					$this->users->set_user_preferences( array( 'articles_filter_by_category' => $filter_by_category ) );

					// we also have to set the current page as 1, to cut the risk of the search result falls outside the range
					$cp = 1;

				}
				else if ( $filter_by_category === FALSE ){

					$filter_by_category = '-1';

				}

				$category_id = $filter_by_category;

				// Filtering ---------------------------------------
				// -------------------------------------------------

				// -------------------------------------------------
				// List / Search -----------------------------------

				$terms = trim( $this->input->post( 'terms', TRUE ) ? $this->input->post( 'terms', TRUE ) : ( ( $this->input->get( 'q' ) != FALSE ) ? $this->input->get( 'q' ) : FALSE ) );

				$search_config = array(

					'plugins' => 'articles_search',
					'ipp' => $ipp,
					'cp' => $cp,
					'terms' => $terms,
					'allow_empty_terms' => ( $action === 's' ? FALSE : TRUE ),
					'plugins_params' => array( // se deve passar um array associativo, onde cada chave deve ser so nome do plugin

						'articles_search' => array(

							'category_id' => $category_id,
							'order_by' => $order_by,
							'order_by_direction' => $order_by_direction,

						),

					),

				);

				$this->load->library( 'search' );
				$this->search->config( $search_config );

				$articles = $this->search->get_full_results( 'articles_search' );

				// List / Search -----------------------------------
				// -------------------------------------------------

				// -------------------------------------------------
				// Pagination --------------------------------------

				// get article url params
				$gaup = array(

					'ipp' => $ipp,
					'cp' => $cp,
					'get' => $this->input->get(),
					'return' => 'template',
					'template_fields' => array(

						'ipp' => '%ipp%',
						'cp' => '%p%',

					),

				);

				if ( $terms ) {

					$gaup[ 'get' ][ 'q' ] = $terms;

				}

				$pagination_url = $this->articles->get_a_url( ( $action === 's' ? 'search' : 'list' ), $gaup );
				$data[ 'pagination' ] = get_pagination( $pagination_url, $cp, $ipp, $this->search->count_all_results( 'articles_search' ) );

				// Pagination --------------------------------------
				// -------------------------------------------------

				// -------------------------------------------------
				// Last url ----------------------------------------

				// setting up the last url
				unset( $gaup[ 'return' ] );
				unset( $gaup[ 'template_fields' ] );
				set_last_url( $this->articles->get_a_url( ( $action === 's' ? 'search' : 'list' ), $gaup ) );
				
				// Last url ----------------------------------------
				// -------------------------------------------------
				
				$data[ 'ipp' ] = $ipp;
				$data[ 'articles' ] = $articles;
				$data[ 'categories' ] = $this->articles->get_categories_tree();
				$data[ 'filter_by_category' ] = $filter_by_category;
				
				// -------------------------------------------------
				// Load page ---------------------------------------
				
				$this->_page(
					
					array(
						
						'component_view_folder' => $this->component_view_folder,
						'function' => 'articles_management',
						'action' => 'list',
						'layout' => 'default',
						'view' => 'list',
						'data' => $data,
						
					)
					
				);
				
				// Load page ---------------------------------------
				// -------------------------------------------------
				
			}
			
			/*
			 --------------------------------------------------------
			 List / Search
			 --------------------------------------------------------
			 ********************************************************
			 */
		}
		
	}
	
	/*
	 --------------------------------------------------------------------------------------------------
	 Courses centers management
	 --------------------------------------------------------------------------------------------------
	 **************************************************************************************************
	 **************************************************************************************************
	 */
	
}
