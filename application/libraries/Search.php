<?php

class Search{

	private $_allowed_config = array(

		'plugins' => NULL,
		'plugins_params' => NULL,
		'terms' => NULL,
		'separate_terms' => NULL,
		'allow_empty_terms' => TRUE, // if no terms given, decide if we must show all results or not
		'ignore_terms' => FALSE, // enable if you want ignore any terms source (from config, post and get)
		'ipp' => NULL,
		'cp' => NULL,
		'order_by' => NULL,
		'order_by_escape' => NULL,
		'order_by_direction' => NULL,
		'random' => NULL,

	);
	private $_config = NULL;
	private $_results = NULL;
	private $_full_results = NULL;
	private $_count_all_results = array(

		'total' => 0,

	);

	private $_cache = array();

	// --------------------------------------------------------------------

	public function __construct( $config = array() ) {

		$this->CI = & get_instance();
		
		if ( ! $this->CI->load->is_model_loaded( 'plugins' ) ) {
			
			$this->CI->load->model( 'plugins_mdl', 'plugins' );
			
		}
		
		$this->_reset_config();

		$this->config( $config );

		$this->_check_config();

	}

	// --------------------------------------------------------------------

	public function reset_config() {

		$this->_reset_config();

	}

	// --------------------------------------------------------------------

	private function _reset_config() {

		foreach ( $this->_allowed_config as $config => $value ) {

			$this->_config[ $config ] = $value;

		}

	}

	// --------------------------------------------------------------------

	public function config( $config = NULL, $value = NULL ){

		// batch config setter, associative array must be given
		if ( is_array( $config ) AND ! isset( $value ) ){

			foreach ( $config as $c_key => $c_value ) {

				if ( key_exists( $c_key, $this->_allowed_config ) ) {

					$this->config( $c_key, $c_value );

				}

			}

		}
		// getter
		else if ( $config AND is_string( $config ) AND ! isset( $value ) ){

			if ( key_exists( $config, $this->_allowed_config ) ) {

				return isset( $this->_config[ $config ] ) ? $this->_config[ $config ] : NULL;

			}

		}
		// setter
		else if ( $config AND is_string( $config ) AND isset( $value ) ) {

			if ( key_exists( $config, $this->_allowed_config ) ) {

				if ( $config === 'plugins' ){

					if ( is_string( $value ) ) {

						$this->_config[ $config ] = array( $value );

					}
					else {

						$this->_config[ $config ] = $value;

					}

				}
				else if ( $config === 'ipp' AND ! ( is_numeric( $value ) AND $value > 0 ) ){

					$this->_config[ $config ] = FALSE;

				}
				else if ( $config === 'random' ){

					$this->_config[ $config ] = ( bool ) $value;

				}
				// order_by must be associative array and plugin search specific
				else if ( $config === 'order_by' AND ! is_array( $value ) ){
					
					return FALSE;

				}
				// plugins_params must be associative array and plugin search specific
				else if ( $config === 'plugins_params' AND ! is_array( $value ) ){

					return FALSE;

				}
				else if ( $config === 'allow_empty_terms' ){

					$this->_config[ $config ] = ( bool ) $value;

				}
				else if ( $config === 'terms' AND is_array( $value ) ){

					if ( ! empty( $value ) ){

						foreach ( $value as $key => & $term ) {

							if ( is_array( $term ) ) {

								$term = '';

							}

							$term = trim( $term );

						}

						$value = join( ' ', $terms );

						$this->_config[ 'terms' ] = $value;

					}
					else {

						return FALSE;

					}

				}
				else {

					$this->_config[ $config ] = $value;

				}

				return TRUE;

			}

		}
		// return all configs
		else {

			return $this->_config;

		}

	}

	// --------------------------------------------------------------------

	private function _check_config(){

		$return = TRUE;

		if ( is_array( $this->_config ) AND ! empty( $this->_config ) ){

			// -------------------------------------------------
			// Parsing vars ------------------------------------

			// -------------------------
			// Setting terms -----------

			$this->_config[ 'terms' ] =							( isset( $this->_config[ 'terms' ] ) AND is_string( $this->_config[ 'terms' ] ) ) ? $this->_config[ 'terms' ] : NULL;
			$this->_config[ 'terms' ] =							( ! isset( $this->_config[ 'terms' ] ) AND is_string( $this->CI->input->post( 'terms' ) ) ) ? $this->CI->input->post( 'terms' ) : $this->_config[ 'terms' ];
			$this->_config[ 'terms' ] =							( ! isset( $this->_config[ 'terms' ] ) AND is_string( $this->CI->input->get( 'q' ) ) ) ? $this->CI->input->get( 'q' ) : $this->_config[ 'terms' ];

			// Setting terms -----------
			// -------------------------

			// Parsing vars ------------------------------------
			// -------------------------------------------------

		}

		if ( $return ){

		}

		return $return;

	}

	// --------------------------------------------------------------------

	/**
	 * Perform a search
	 *
	 * @access	public
	 * @param	mixed
	 * @return	array
	 */
	public function run( $f_params = NULL ){

		// -------------------------------------------------
		// Parsing vars ------------------------------------

		if ( is_string( $f_params ) AND trim( $f_params ) !== '' ){

			$terms = $f_params;
			$this->config( 'terms', $terms );

		}
		else if ( is_array( $f_params ) ){

			$f_params_ok = FALSE;

			foreach ( $f_params as $key => $f_param ) {

				if ( check_var( $f_param ) ) {

					$f_params_ok = TRUE;
					break;

				}

			}

			$this->config( $f_params );

		}

		// Parsing vars ------------------------------------
		// -------------------------------------------------

		if ( $this->_check_config() ) {

			// if we have plugins names defined
			if ( is_array( $this->config( 'plugins' ) ) ){

				foreach ( $this->config( 'plugins' ) as $key => $plugin_name ) {

					$this->CI->plugins->load( $plugin_name );

				}

			}
			// run all plugins
			else if ( ! $this->config( 'plugins' ) ) {

				$this->CI->plugins->load( NULL, 'search' );

			}

		}

		return $this->_results;

	}

	// --------------------------------------------------------------------

	/**
	 * resolve active records where conditions from multidimensionals arrays for db_search function
	 *
	 * @access	private
	 * @param	array
	 * @return	string
	 */
	private function _resolve_where_conditions( $conditions = NULL ){
		
		$return = '';
		
		if ( check_var( $conditions ) ) {
			
			if ( is_array( $conditions ) ) {
				
				foreach ( $conditions as $key => $condition ) {
					
					if ( is_numeric( $key ) ){
						
						if ( is_array( $condition ) ){
							
							$return .= $this->_resolve_where_conditions( $condition );
							
						}
						else if ( is_string( $condition ) ){
							
							$this->CI->db->where( $condition );
							
							$return .= $condition;
							
						}
						
					}
					else if ( is_string( $key ) AND ! is_string( $condition ) ){
						
						$this->CI->db->where( $key, $condition );
						
						$return .= $condition;
						
					}
					else if ( is_array( $condition ) ){
						
						$return .= $this->_resolve_where_conditions( $condition );
						
					}
					
				}
				
			}
			
		}
		
		return $return;
		
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * resolve active records or where conditions from multidimensionals arrays for db_search function
	 *
	 * @access	private
	 * @param	array
	 * @return	void
	 */
	private function _resolve_or_where_conditions( $conditions = NULL ){

		$return = '';
		
		if ( check_var( $conditions ) ) {
			
			if ( is_array( $conditions ) ) {
				
				foreach ( $conditions as $key => $condition ) {
					
					if ( is_numeric( $key ) ){
						
						if ( is_array( $condition ) ){
							
							$return .= $this->_resolve_where_conditions( $condition );
							
						}
						else if ( is_string( $condition ) ){
							
							$this->CI->db->or_where( $condition );
							
							$return .= $condition;
							
						}
						
					}
					else if ( is_string( $key ) AND ! is_string( $condition ) ){
						
						$this->CI->db->or_where( $key, $condition );
						
						$return .= $condition;
						
					}
					else if ( is_array( $condition ) ){
						
						$return .= $this->_resolve_where_conditions( $condition );
						
					}
					
				}
				
			}
			
		}
		
		return $return;
		
	}

	// --------------------------------------------------------------------

	/**
	 * Make a db search and return a active records result object
	 *
	 * If no terms given and allow_empty_terms is set to FALSE, return FALSE
	 * Commonly set allow_empty_terms to FALSE when target page is a search
	 * and TRUE if you is fetching for a data listing page
	 *
	 * @access	public
	 * @param	array
	 * @return	mixed
	 */
	public function db_search( $f_params = NULL ){

		// -------------------------------------------------
		// Parsing vars ------------------------------------

		$plugin_name =							( isset( $f_params[ 'plugin_name' ] ) AND is_string( $f_params[ 'plugin_name' ] ) ) ? $f_params[ 'plugin_name' ] : NULL; // string
		$table_name =							isset( $f_params[ 'table_name' ] ) ? $f_params[ 'table_name' ] : NULL; // string
		$select_columns =						isset( $f_params[ 'select_columns' ] ) ? $f_params[ 'select_columns' ] : NULL; // array / string
		$select_escape =						isset( $f_params[ 'select_escape' ] ) ? ( bool ) $f_params[ 'select_escape' ] : NULL; // array / string
		$search_columns =						isset( $f_params[ 'search_columns' ] ) ? $f_params[ 'search_columns' ] : NULL; // array / string
		$tables_to_join =						isset( $f_params[ 'tables_to_join' ] ) ? $f_params[ 'tables_to_join' ] : NULL; // array[ array[ join_param_1, join_param_2, join_param_3 ], array[ join_param_1, join_param_2, join_param_3 ], ... ]

		$return_type =							isset( $f_params[ 'return_type' ] ) ? $f_params[ 'return_type' ] : 'get'; // string
		$_where_conditions =					isset( $f_params[ 'where_conditions' ] ) ? $f_params[ 'where_conditions' ] : array(); // array
		$_or_where_conditions =					isset( $f_params[ 'or_where_conditions' ] ) ? $f_params[ 'or_where_conditions' ] : array(); // array
		$where_conditions =						array();
		$or_where_conditions =					array();

		$random =								check_var( $f_params[ 'random' ] ) ? TRUE : $this->config( 'random' );

		/**
		 * Se order_by for do tipo array
		 * cada item deve ser um array onde:
		 * 1° item é a coluna de ordenação
		 * 2° item é a direção
		 * 3° é o order_by_escape // esta configuração anula o uso da configuração order_by_escape global
		 */
		$limit =								( isset( $f_params[ 'ipp' ] ) AND is_numeric( $f_params[ 'ipp' ] ) AND $f_params[ 'ipp' ] > 0 ) ? ( int ) $f_params[ 'ipp' ] : $this->config( 'ipp' );
		$offset =								isset( $f_params[ 'cp' ] ) ? ( ( int ) ( $f_params[ 'cp' ] > 0 ? $f_params[ 'cp' ] : 1 ) - 1 ) * $limit : ( ( int ) ( $this->config( 'cp' ) > 0 ? $this->config( 'cp' ) : 1 ) - 1 ) * $limit;
		$_order_by_temp =						$this->config( 'order_by' );
		
		if ( isset( $f_params[ 'order_by' ] ) ) {
			
			//echo '[' . $plugin_name . '] order_by definido diretamente:<pre>' . print_r( $order_by, TRUE ) . '</pre>';
			$order_by = $f_params[ 'order_by' ];
			
		}
		else if ( isset( $_order_by_temp[ $plugin_name ] ) ) {
			
			//echo '[' . $plugin_name . '] order_by obtido do array global:<pre>' . print_r( $_order_by_temp[ $plugin_name ], TRUE ) . '</pre>';
			$order_by = $_order_by_temp[ $plugin_name ];
			
		}
		else {
			
			//echo '[' . $plugin_name . '] order_by nulo<br/>';
			$order_by = NULL;
			
		}
		
		$order_by_escape =						isset( $f_params[ 'order_by_escape' ] ) ?  $f_params[ 'order_by_escape' ] : $this->config( 'order_by_escape' );
		$_order_by_direction_temp =				$this->config( 'order_by_direction' );
		$order_by_direction =					isset( $f_params[ 'order_by_direction' ] ) ? $f_params[ 'order_by_direction' ] : ( isset( $_order_by_direction_temp[ $plugin_name ] ) ? $_order_by_direction_temp[ $plugin_name ] : '' ); // array / string
		
		if ( $order_by === 'image_first' ) {
			
			$order_by = 'if( `t1`.`image` = \'\' or `t1`.`image` is null, 1, 0 ), `t1`.`image` ' . $order_by_direction . ', ' . $order_by;
			$order_by_escape = FALSE;
			
		}
		
		// -------------
		
		$terms =								isset( $f_params[ 'terms' ] ) ? $f_params[ 'terms' ] : $this->config( 'terms' ); // string
		$allow_empty_terms =					$this->config( 'allow_empty_terms' ); // bool
		$result =								FALSE;
		
		if ( ! $terms AND ! $allow_empty_terms ) {
			
			return FALSE;
			
		}
		
		$this->config( 'terms', $terms );
		
		// Parsing vars ------------------------------------
		// -------------------------------------------------

		if ( $table_name AND $search_columns ){

			$_query_id = '';

			// -------------------------
			// Select ------------------

			if ( ! $select_columns ){

				$select_columns = '*';

			}

			$select = $select_columns;

			if ( is_array( $select_columns ) AND ! empty( $select_columns ) ){

				$select = array();

				foreach ( $select_columns as $key => $select_column ) {

					$select[] = $select_column;

				}

				$select = join( ',', $select );

			}

			if ( is_string( $select ) ){

				$this->CI->db->select( $select, $select_escape );
				$_query_id .= $select;

			}

			// Select ------------------
			// -------------------------

			// -------------------------
			// From --------------------

			$this->CI->db->from( $table_name );
			$_query_id .= $table_name;

			// From --------------------
			// -------------------------

			// -------------------------
			// Join --------------------

			if ( $tables_to_join AND is_array( $tables_to_join ) AND ! empty( $tables_to_join ) ){

				foreach ( $tables_to_join as $key => $join ) {

					call_user_func_array( array( $this->CI->db, 'join' ), $join );
					$_query_id .= join( '', $join );

				}

			}

			// Join --------------------
			// -------------------------

			// -------------------------
			// Order by ----------------
			
			if ( $order_by AND $return_type !== 'count_all_results' ) {
				
				if ( $random ) {
					
					$order_by_direction = 'RANDOM';
					
				}
				
				if ( is_string( $order_by ) ){
					
					$this->CI->db->order_by( $order_by, $order_by_direction, $order_by_escape );
					$_query_id .= $order_by . $order_by_direction . ( int ) $order_by_escape;
					
				}
				else if ( is_array( $order_by ) ){
					
					call_user_func_array( array( $this->CI->db, 'order_by' ), $order_by );
					
					$_query_id .= join( '', $order_by );
					
				}
				
			}
			
			// Order by ----------------
			// -------------------------
			
			// -------------------------
			// Columns -----------------
			
			$columns = array();
			
			if ( is_array( $search_columns ) AND ! empty( $search_columns ) ){
				
				$_query_id .= join( '', $search_columns );
				foreach ( $search_columns as $key => $search_column ) {
					
					$columns[] = $search_column;
					
				}
				
			}
			else if ( is_string( $search_columns ) AND check_var( $search_columns ) ){
				
				$_query_id .= $search_columns;
				$columns_temp = explode( ',', ltrim( rtrim( trim( $search_columns ), ',' ), ',' ) );
				
				foreach ( $columns_temp as $key => $search_column ) {
					
					$columns[] = trim( $search_column );
					
				}
				
			}
			
			$search_columns = $columns;
			
			// Columns -----------------
			// -------------------------
			
			// -------------------------
			// Limit / offset ----------
			
			if ( $limit AND $return_type !== 'count_all_results' ){
				
				$this->CI->db->limit( $limit, $offset ? $offset : NULL );
				$_query_id .= ( int ) $limit . ( int ) $offset;
				
			}
			
			// Limit / offset ----------
			// -------------------------
			
			// -------------------------
			// Append plugin (or_)where_conditions

			if ( $_where_conditions ) {

				$where_conditions[] = $_where_conditions;

			}

			if ( $_or_where_conditions ) {

				$or_where_conditions[] = $_or_where_conditions;

			}

			// Append plugin where/or where conditions
			// -------------------------

			// -------------------------
			// Preparing search conditions

			if ( $terms AND ! $this->config( 'ignore_terms' ) ) {

				$terms = $this->get_separate_terms();

				$full_term = join( ' ', $terms );

				$_query_id .= $full_term;

				// -------------
				// full term

				$terms_where_cond = & $where_conditions[];
				$terms_where_cond = ( ( count( $terms ) > 1 ) ? '(' : '' ) . '(';

				$or_operator = FALSE;

				foreach ( $search_columns as $key => $search_column ) {

					$terms_where_cond .= ( $or_operator ? 'OR ' : '' ) . $search_column . ' LIKE \'%' . $full_term . '%\' ';
					$or_operator = ! $or_operator ? TRUE : $or_operator;

				}

				$terms_where_cond .= ')';

				// -------------
				// now, conditions for each word

				if ( count( $terms ) > 1 ) {

					$terms_or_where_cond = & $or_where_conditions[];

					$and_operator = FALSE;

					$terms_or_where_cond = '';

					foreach ( $terms as $key => $term ) {

						$or_operator = FALSE;

						$terms_or_where_cond .= $and_operator ? 'AND ' : '';
						$terms_or_where_cond .= '(';

						foreach ( $search_columns as $key => $search_column ) {

							$terms_or_where_cond .= ( $or_operator ? 'OR ' : '' ) . $search_column . ' LIKE \'%' . $term . '%\' ';
							$or_operator = ! $or_operator ? TRUE : $or_operator;

						}

						$terms_or_where_cond .= ')';

						$and_operator = ! $and_operator ? TRUE : $or_operator;

					}

					$terms_or_where_cond = $terms_or_where_cond . ')';

				}

			}

			// Preparing search conditions
			// -------------------------

			if ( $where_conditions ){
				
				$_query_id .= $this->_resolve_where_conditions( $where_conditions );
				
			}
			if ( $or_where_conditions ){
				
				$_query_id .= $this->_resolve_or_where_conditions( $or_where_conditions );
				
			}
			
			$_query_id .= $return_type;
			
			if ( $this->CI->cache->cache( $_query_id ) ) {
				
				$query = $this->CI->db->_compile_select();
				$this->CI->db->_reset_write();
				$this->CI->db->_reset_select();
				return $this->CI->cache->cache( $_query_id );
				
			}
			else {
				
				if ( $return_type === 'count_all_results' ) {
					
					$count_all_results = $this->CI->db->count_all_results();
					$this->CI->cache->cache( $_query_id, $count_all_results );
					$result = & $count_all_results;

					$this->_count_all_results[ 'total' ] += $count_all_results;
					$this->_count_all_results[ $plugin_name ] = $count_all_results;

				}
				else {

					$result = $this->CI->db->get();
					$this->CI->cache->cache( $_query_id, $result );

					$f_params[ 'return_type' ] = 'count_all_results';
					$this->db_search( $f_params );

				}

			}

		}

		return $result;

	}

	// --------------------------------------------------------------------

	public function count_all_results( $plugin_name = NULL ){

		$return = $this->_count_all_results[ 'total' ];

		if ( $this->_check_config() ) {

			if ( $plugin_name ){

				$return = isset( $this->_count_all_results[ $plugin_name ] ) ? $this->_count_all_results[ $plugin_name ] : 0;

			}

		}

		return $return;

	}

	// --------------------------------------------------------------------

	public function append_results( $plugin_name = NULL, $search_results = NULL ){

		if ( is_array( $search_results ) ){

			if ( isset( $search_results[ 'results' ] ) ){

				$this->_results[ $plugin_name ] = $search_results[ 'results' ];

			}
			if ( isset( $search_results[ 'full_results' ] ) ){

				$this->_full_results[ $plugin_name ] = $search_results[ 'full_results' ];

			}


			return TRUE;

		}

		return FALSE;

	}

	// --------------------------------------------------------------------

	public function str_highlight( $text = NULL, $needle = NULL, $options = NULL, $highlight = NULL ){

		$this->CI->load->helper( array( 'string' ) );

		return str_highlight( $text, $needle, $options, $highlight );

	}

	// --------------------------------------------------------------------

	public function array_highlight( $array = NULL, $allowed_keys = NULL ){

		$this->CI->load->helper( array( 'string' ) );

		$terms = $this->get_separate_terms();

		$allowed_keys = is_array( $allowed_keys ) ? $allowed_keys : FALSE;

		if ( is_array( $array ) AND is_array( $terms ) ){

			foreach ( $terms as $term ) {

				foreach ( $array as $i_key => $item ) {

					if ( is_array( $item ) ) {

						$array[ $i_key ] = $this->array_highlight( $item, $allowed_keys );

					}
					else if ( is_string( $item ) ) {

						if ( ( $allowed_keys AND ( key_exists( $i_key, $allowed_keys ) OR in_array( $i_key, $allowed_keys ) ) ) OR ! $allowed_keys ) {

							$array[ 'highlight_st_' . $i_key ] = str_highlight( strip_tags( $item ), $term );
							$array[ 'highlight_' . $i_key ] = str_highlight( $item, $term );

						}

					}

				}

			}

		}

		return $array;

	}

	// --------------------------------------------------------------------

	public function get_separate_terms( $terms = NULL ){

		if ( ! $terms OR ! is_string( $terms ) ) {

			$terms = $this->config( 'terms' );

		}
		else {

			$this->config( 'terms', $terms );

		}

		if ( $terms ) {

			$final_terms = array();

			// get strings between quotes
			preg_match_all( '/".*?"|\'.*?\'/', $terms, $matches );

			$matches = $matches[ 0 ];

			$find = array(

				"\"",
				"'",
				",",

			);

			foreach ( $matches as $key => & $matche ) {

				$matche = str_replace( $find , '', $matche );

			}

			$find = array_merge( $find, $matches );

			$terms = str_replace( $find , '', $terms );

			// remove double spaces
			$terms = preg_replace('!\s+!', ' ', $terms );

			// explode terms, merge with matches, remove empty values and remove duplicate values
			$terms = array_unique( array_filter( array_merge( $matches, explode( ' ', $terms ) ) ) );

			$this->config( 'separate_terms', $terms );

			return $this->config( 'separate_terms' );

		}
		else {

			return FALSE;

		}

	}

	// --------------------------------------------------------------------

	public function get_results( $plugin_name = NULL, $force = FALSE ){

		$return = array();

		if ( $this->_check_config() ) {

			if ( $plugin_name ){

				if ( ! isset( $this->_results[ $plugin_name ] ) OR $force ) {

					// run params
					$rp = NULL;

					$rp[ 'plugins' ] = $plugin_name;
					$this->run( $rp );

				}

			}
			else {

				if ( ! isset( $this->_results ) OR $force ) {

					$this->run();

				}

			}

		}

		$return = $this->_get_results( $plugin_name );

		return $return;

	}

	// --------------------------------------------------------------------

	private function _get_results( $plugin_name = NULL ){

		if ( isset( $this->_results[ $plugin_name ] ) ) {

			return $this->_results[ $plugin_name ];

		}
		else {

			return $this->_results;

		}

	}

	// --------------------------------------------------------------------

	public function get_full_results( $plugin_name = NULL, $force = FALSE ){

		$return = array();

		if ( $this->_check_config() ) {

			if ( $plugin_name ){

				if ( ! isset( $this->_full_results[ $plugin_name ] ) OR $force ) {

					// run params
					$rp = NULL;

					if ( $force ) {

						$this->_full_results[ $plugin_name ] = array();

					}

					$rp[ 'plugins' ] = $plugin_name;
					$this->run( $rp );

				}

			}
			else {

				if ( ! isset( $this->_full_results ) OR $force ) {

					if ( $force ) {

						$this->_full_results[ $plugin_name ] = array();

					}

					$this->run();

				}

			}

		}

		$return = $this->_get_full_results( $plugin_name );

		return $return;

	}

	// --------------------------------------------------------------------

	private function _get_full_results( $plugin_name = NULL ){

		if ( isset( $plugin_name ) AND is_string( $plugin_name ) AND isset( $this->_full_results[ $plugin_name ] ) ) {

			return $this->_full_results[ $plugin_name ];

		}
		else if ( ! isset( $plugin_name ) ) {

			return $this->_full_results;

		}

		return FALSE;

	}

}