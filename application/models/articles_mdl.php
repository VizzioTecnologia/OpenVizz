<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Articles_mdl extends CI_Model{

	private $_articles = array();
	private $_categories = NULL; // NULL = not initialized, FALSE = initialized, but no categories
	private $_c_urls_array = array(); // Component urls in array format
	private $_c_urls = array(); // Component urls in string format

	private $categories_tree = '';
	private $next_has_children = FALSE;
	private $categories_tree_array = array();
	public $categories_array = array();

	private $_cache = array();

	// --------------------------------------------------------------------

	public function __construct(){



	}


	// --------------------------------------------------------------------

	public function article( $id ){

		if ( is_numeric( $id ) AND isset( $this->_articles[ $id ] ) ) {

			return $this->_articles[ $id ];

		}
		else return FALSE;

	}

	// --------------------------------------------------------------------

	/**
	 * Return a article as array
	 *
	 * @access public
	 * @param numeric
	 * @return array
	 */

	public function get( $value = NULL ){

		if ( is_numeric( $value ) AND $this->article( $value ) ) {

			return $this->article( $value );

		}

		$search_config = array(

			'plugins' => 'articles_search',
			'ipp' => 1,
			'allow_empty_terms' => TRUE,

		);

		if ( is_array( $value ) ){

			$search_config[ 'plugins_params' ][ 'articles_search' ][ 'where_conditions' ] = $value;

		}
		else if ( is_numeric( $value ) AND $value > 0 ){

			$search_config[ 'plugins_params' ][ 'articles_search' ][ 'article_id' ] = ( int ) $value;

		}
		else{

			return FALSE;

		}

		$this->load->library( 'search' );
		$this->search->config( $search_config );

		$article = $this->search->get_full_results( 'articles_search', TRUE );
		
		if ( check_var( $article[ 0 ] ) ) {
			
			$article = $article[ 0 ];
			
			$this->articles->append_articles( $article );
			
			return check_var( $article ) ? $article : FALSE;
		
		}
		else {
			
			return FALSE;
			
		}
		
	}

	// --------------------------------------------------------------------

	/**
	 * Append articles to articles buffer array
	 *
	 * @access public
	 * @param array				articles array
	 * @return avoid
	 */

	public function append_articles( $array ){

		if ( is_array( $array ) AND ( key_exists( 0 , $array ) OR ! key_exists( 'id' , $array ) ) ) {

			foreach ( $array as $key => $article ) {

				$this->append_articles( $article );

			}

		}

		if ( is_array( $array ) AND key_exists( 'id' , $array ) ) {

			$this->_articles[ $array[ 'id' ] ] = $array;

		}

	}

	// --------------------------------------------------------------------

	/**
	 * Update a article
	 *
	 * @access public
	 * @param mixed
	 * @param mixed
	 * @return bool
	 */

	public function update( $var1 = NULL, $var2 = NULL ){

		// -------------------------------------------------
		// Parsing vars ------------------------------------

		$id =									( isset( $var1 ) AND is_numeric( $var1 ) AND $var1 > 0 ) ? $var1 : NULL;
		$db_data =								( isset( $var1 ) AND check_var( $var1 ) AND is_array( $var1 ) ) ? $var1 : NULL;
		$db_data =								( $id AND check_var( $var2 ) AND is_array( $var2 ) ) ? $var2 : $db_data;
		$condition =							( ! $id AND isset( $var2 ) AND check_var( $var2 ) AND is_array( $var2 ) ) ? $var2 : NULL;

		// Parsing vars ------------------------------------
		// -------------------------------------------------

		if ( is_array( $db_data ) AND is_array( $condition ) ){

			return $this->db->update( 'tb_articles', $db_data, $condition );

		}
		else if ( $id AND is_array( $db_data ) ) {

			$condition = array( 'id' => $id );

			return $this->update( $db_data, $condition );

		}

		return FALSE;

	}

	// --------------------------------------------------------------------

	/**
	 * Copy a article
	 *
	 * @access public
	 * @param numeric
	 * @return bool
	 */

	public function copy( $id = NULL ){

		if ( $id ) {

			// get articles params
			$gap = array(

				'art_id' => $id,
				'limit' => 1,

			);

			$article = $this->get_articles_respecting_privileges( $gap )->row_array();

			$db_data = elements( array(

				'created_by_id',
				'status',
				'created_date',
				'title',
				'alias',
				'category_id',
				'introtext',
				'fulltext',
				'access_type',
				'access_id',
				'params',
				'image',

			), $article );

			$db_data[ 'title' ] = $db_data[ 'title' ] . ' ' . lang( 'article_copied_sufix' );
			$db_data[ 'alias' ] = url_title( $db_data['title'],'-',TRUE );
			$db_data[ 'status' ] = '0';
			$db_data[ 'created_by_id' ] = $this->users->user_data[ 'id' ];

			if ( $db_data != NULL ){

				if ( $this->db->insert( 'tb_articles', $db_data ) ){
					// confirm the insertion for controller
					$result = $this->db->insert_id();

				}
				else {

					// case the insertion fails, return false
					$result = FALSE;

				}
			}
			else {

				redirect( 'admin' );

			}

		}

		return $result;

	}

	// --------------------------------------------------------------------

	// @TODO organizar e analisar a função
	public function remove_article( $id = NULL ){


		if ( $id ) {

			if ( $this->db->delete( 'tb_articles', array( 'id' => $id ) ) ){

				return TRUE;

			}

		}

		return FALSE;

	}

	// --------------------------------------------------------------------

	/**
	 * Increment article hit
	 *
	 * @access public
	 * @param numeric
	 * @return bool
	 */

	// @TODO organizar e analisar a função
	public function increment_hit( $id = NULL ){

		// get articles params
		$gap = array(

			'art_id' => $id,
			'limit' => 1,

		);

		if ( $article = $this->get( $id ) ){

			$article[ 'params' ] = get_params( $article[ 'params' ] );

			if ( ! check_var( $article[ 'params' ][ 'hits' ] ) OR gettype( $article[ 'params' ][ 'hits' ] ) !== 'integer' ){

				$article[ 'params' ][ 'hits' ] = 0;

			}

			$article[ 'params' ][ 'hits' ] = ( int )$article[ 'params' ][ 'hits' ] + 1;

			$article[ 'params' ] = json_encode( $article[ 'params' ] );

			$db_data = elements( array(

				'params',

			), $article );

			if ( $db_data != NULL ){

				$this->db->where( 'id', $id );

				return $this->db->update( 'tb_articles', $db_data ) ? TRUE : FALSE;

			}

		}

		return FALSE;

	}

	// --------------------------------------------------------------------

	/**
	 * Get / set article status
	 *
	 * @access public
	 * @param mixed
	 * @param string
	 * @return bool
	 */

	public function status( $id = NULL, $value = NULL ){

		// -------------------------------------------------
		// Parsing vars ------------------------------------

		$id =								( isset( $id ) AND is_numeric( $id ) AND $id > 0 ) ? ( int ) $id : NULL;
		$value =							( isset( $value ) AND is_string( $value ) ) ? $value : NULL;

		// Parsing vars ------------------------------------
		// -------------------------------------------------

		if ( $id ) {

			if ( $value ) {

				$update_data = array(

					'status' => $value == 'p' ? '1' : ( $value == 'u' ? '0' : '-1' ),

				);

				return $this->update( $id, $update_data );

			}
			else {

				$article = $this->get( $id );

				if ( $article ) {

					return $article[ 'status' ];

				}

			}

		}

		return FALSE;

	}

	// --------------------------------------------------------------------

	/**
	 * Down article ordering
	 *
	 * @access public
	 * @param numeric
	 * @return bool
	 */

	public function down_ordering( $article_id = NULL ){

		$article = $this->get( $article_id );

		if ( $article ){

			$new_ordering = ( int ) $article[ 'ordering' ] - 1;

			return $this->set_ordering( $article_id, $new_ordering );

		}

		return FALSE;

	}

	// --------------------------------------------------------------------

	/**
	 * Up article ordering
	 *
	 * @access public
	 * @param numeric
	 * @return bool
	 */

	public function up_ordering( $article_id = NULL ){

		$article = $this->get( $article_id );

		if ( $article ){

			$new_ordering = ( int ) $article[ 'ordering' ] + 1;

			return $this->set_ordering( $article_id, $new_ordering );

		}

		return FALSE;

	}

	// --------------------------------------------------------------------

	/**
	 * Set article ordering
	 *
	 * @access public
	 * @param numeric
	 * @param numeric
	 * @return bool
	 */

	public function set_ordering( $id = NULL, $requested_position = NULL ){

		// set item ordering params
		$siop = array(

			'table_name' => 'tb_articles',
			'id_column_name' => 'id',
			'id_value' => $id,
			'parent_column_name' => 'category_id',
			'ordering_column_name' => 'ordering',
			'requested_position' => ( int ) $requested_position,

		);

		return $this->mcm->set_item_ordering( $siop );

	}

	// --------------------------------------------------------------------

	/**
	 * Fix articles ordering
	 *
	 * @access public
	 * @param numeric | the parent category, set NULL to all articles
	 * @return bool
	 */

	public function fix_ordering( $parent_value = NULL ){

		// fix items ordering params
		$fiop = array(

			'table_name' => 'tb_articles',
			'parent_column_name' => 'category_id',
			'parent_value' => ( int ) $parent_value,

		);

		return $this->mcm->fix_items_ordering( $fiop );

	}

	// --------------------------------------------------------------------

	/**
	 * Return the highest ordering value within a specific category
	 *
	 * @access public
	 * @param numeric | the parent category, NULL to all articles
	 * @return bool
	 */

	public function get_max_ordering( $parent_value = NULL ){

		// get max ordering params
		$gmop = array(

			'table_name' => 'tb_articles',
			'parent_column_name' => 'category_id',
			'parent_value' => ( int ) $parent_value,

		);

		return $this->mcm->get_max_ordering( $gmop );

	}

	/**
	 * Return a prepared url for site articles detail page
	 *
	 * @TODO migrar para a nova versão de obtenção de urls
	 * @access public
	 * @param numeric
	 * @param numeric
	 * @param numeric
	 * @return mixed
	 */

	public function get_link_article_detail( $menu_item_id = NULL, $article_id = NULL, $category_id = NULL ){

		if ( $article_id ){

			$_where = '`status` = \'1\'';
			$_where .= ' AND (';
			$_where .= ' ( `component_item` = \'article_detail\' AND `params` LIKE \'%"article_id":"' . $article_id . '"%\' )';
			$_where .= $category_id ? ' OR ( `component_item` = \'articles_list\' AND `params` LIKE \'%"category_id":"' . $category_id . '"%\' )' : '';
			$_where .= ')';

			$_query_id = $_where;

			if ( ! isset( $menu_item_id ) ) {
				
				if ( $this->mcm->environment == 'site' ){
					
					$menu_item_id = current_menu_id();
					
				}
				
				if ( $this->cache->cache( $_query_id ) ) {

					$menu_item = $this->cache->cache( $_query_id );
					$menu_item_id = $menu_item[ 'id' ];

				}
				else if ( NULL === $this->cache->cache( $_query_id ) ) {

					$this->db->select( 'id' );
					$this->db->from( 'tb_menus' );
					$this->db->where( $_where, NULL, FALSE );

					$this->db->limit( 1 );

					$_result = $this->db->get();

					if ( $_result->num_rows() > 0 ) {

						$menu_item = $_result->row_array();
						$menu_item_id = $menu_item[ 'id' ];

					}
					else {

						$menu_item = FALSE;

					}

					$this->cache->cache( $_query_id, $menu_item );

				}
				
			}
			
			return 'articles/index/article_detail/' . $menu_item_id . '/' . $article_id;

		}
		else{

			return FALSE;

		}

	}

	// --------------------------------------------------------------------

	/**
	 * Return a prepared url for site articles list page
	 *
	 * @TODO migrar para a nova versão de obtenção de urls
	 * @access public
	 * @param numeric
	 * @param numeric
	 * @param numeric
	 * @param bool
	 * @param bool
	 * @return mixed
	 */
	
	public function get_link_articles_list( $menu_item_id = NULL, $cat_id = NULL, $user_id = 0, $pagination = FALSE, $for_url = FALSE, $p = NULL, $ipp = NULL ){
		
		if ( isset( $cat_id ) ){
			
			$_where = '`status` = \'1\'';
			$_where .= ' AND ( `component_item` = \'articles_list\' AND `params` LIKE \'%"category_id":"' . $cat_id . '"%\' )';
			
			//$CI->db->query( 'SELECT * FROM tb_urls WHERE target RLIKE \'^articles/index/' . $component_item . '/([0-9]*[0-9])/' . ( $category_id == '0' ? '0' : $category_id ) . '/([0-9]*[0-9])' . ( isset ( $p ) ? '/' . $p : '' ) . ( isset ( $ipp ) ? '/' . $ipp : '' ) . '$\' LIMIT 1' );
			
			$_query_id = $_where;
			
			if ( ! isset( $menu_item_id ) ) {
				
				$this->load->helper( 'menus' );
				
				$menu_item_id = current_menu_id();
				
				if ( $this->cache->cache( $_query_id ) ) {
					
					$menu_item = $this->cache->cache( $_query_id );
					$menu_item_id = $menu_item[ 'id' ];
					
				}
				else if ( NULL === $this->cache->cache( $_query_id ) ) {
					
					$this->db->select( 'id' );
					$this->db->from( 'tb_menus' );
					$this->db->where( $_where, NULL, FALSE );
					
					$this->db->limit( 1 );
					
					$_result = $this->db->get();
					
					if ( $_result->num_rows() > 0 ) {
						
						$menu_item = $_result->row_array();
						$menu_item_id = $menu_item[ 'id' ];
						
					}
					else {
						
						$menu_item = FALSE;
						
					}
					
					$this->cache->cache( $_query_id, $menu_item );
					
				}
				
			}
			
			$out = 'articles/index/articles_list/' . $menu_item_id . '/' . $cat_id . '/' . $user_id;
			
			if ( $pagination ){
				
				if ( $for_url ){
					
					if ( isset( $p ) )
						$out .= '/' . $p;
					if ( isset( $ipp ) )
						$out .= '/' . $ipp;
						
				}
				else{
					
					$out .= '/%p%/%ipp%';
					
				}
				
			}
			
			return $out;
			
		}
		else{
			
			return FALSE;
			
		}
		
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Parse a article data
	 *
	 * @access public
	 * @param array
	 * @param bool | determines whether the parameters should be filtered
	 * @return void
	 */

	public function parse( & $article = NULL, $filter_params = FALSE, $menu_item_id = NULL ){

		reset( $article );
		if ( is_array( $article ) AND is_numeric( key( $article ) ) ) {

			foreach ( $article as $key => $item ) {

				if ( key_exists( 'id', $item ) ) {

					$this->parse( $item );

				}

			}

		}

		if ( is_array( $article ) AND key_exists( 'id', $article ) ){
			
			if ( $filter_params ) {
				
				// obtendo os parâmetros globais do componente
				$component_params = $this->mcm->get_component( 'articles' );
				$component_params = $component_params[ 'params' ];

				// obtendo os parâmetros do item de menu
				if ( $this->mcm->current_menu_item ){

					$menu_item_params = get_params( $this->mcm->current_menu_item[ 'params' ] );
					$params = filter_params( $component_params, $menu_item_params );

				}
				else{

					$params = $component_params;

				}
				$article[ 'params' ] = filter_params( $params, get_params( $article[ 'params' ] ) );

			}
			else {

				$article[ 'params' ] = get_params( $article[ 'params' ] );

			}

			$article[ 'image' ] = isset( $article[ 'image' ] ) ? ( string ) $article[ 'image' ] : '';
			$article[ 'alias' ] = strip_tags( $article[ 'alias' ] );
			$article[ 'category_id' ] = ( int ) $article[ 'category_id' ];
			$article[ 'ordering' ] = ( int ) $article[ 'ordering' ];
			$article[ 'title' ] = html_entity_decode( $article[ 'title' ] );
			$article[ 'introtext' ] = check_var( $article[ 'introtext' ] ) ? html_entity_decode( $article[ 'introtext' ] ) : '';
			$article[ 'fulltext' ] = check_var( $article[ 'fulltext' ] ) ? html_entity_decode( $article[ 'fulltext' ] ) : '';
			$article[ 'fullcontent' ] = $article[ 'introtext' ] . ( check_var( $article[ 'introtext' ] ) ? '<hr id="vcms-readmore" />' : '' ) . $article[ 'fulltext' ];
			$article[ 'status' ] = ( int ) $article[ 'status' ];

			if ( $article[ 'access_type' ] === 'users' ){

				$article[ 'access_user_id' ] = explode( '|', $article[ 'access_id' ] );

			}
			else if ( $article[ 'access_type' ] === 'users_groups' ){

				$article[ 'access_user_group_id' ] = explode( '|', $article[ 'access_id' ] );

			}

			// Article URL
			if ( check_var( $article[ 'params' ][ 'article_custom_url' ] ) ) {

				$article[ 'url' ] = get_url( $article[ 'params' ][ 'article_custom_url' ] );

			}
			else {

				$article[ 'url' ] = get_url( $this->get_link_article_detail( $menu_item_id, $article[ 'id' ], $article[ 'category_id' ] ) );

			}

			$article[ 'category_url' ] = get_url( $this->get_link_articles_list( $menu_item_id, $article[ 'category_id' ] ) );

			$article[ 'created_by_id' ] = ( int ) $article[ 'created_by_id' ];
			$article[ 'created_date' ] = strip_tags( $article[ 'created_date' ] );

			$article[ 'modified_by_id' ] = ( int ) $article[ 'modified_by_id' ];
			$article[ 'modified_date' ] = strip_tags( $article[ 'modified_date' ] );

			$article[ 'publish_user_id' ] = ( int ) $article[ 'publish_user_id' ];
			$article[ 'publish_datetime' ] = strip_tags( $article[ 'publish_datetime' ] );

		}

	}

	// --------------------------------------------------------------------

	/**
	 * Return a article function url
	 *
	 * Available aliases:
	 *
	 * 		Admin:
	 * 			- list
	 * 			- search
	 * 			- cancel_search
	 * 			- live_search
	 * 			- add
	 * 			- edit
	 * 			- copy
	 * 			- remove
	 * 			- remove_all
	 * 			- change_order_by
	 * 			- change_ordering
	 * 			- up_ordering
	 * 			- down_ordering
	 * 			- set_status_publish
	 * 			- set_status_unpublish
	 * 			- set_status_archived @TODO
	 * 			- batch
	 *
	 * @access public
	 * @param string
	 * @param mixed
	 * @param string
	 * @return string
	 */

	public function get_a_url( $alias = NULL, $value = NULL, $environemnt = NULL ){

		// get url params
		$gup[ 'alias' ] = ( string ) $alias;
		$gup[ 'environemnt' ] = $environemnt;

		if ( $alias === 'change_order_by' ){

			$gup[ 'order_by_value' ] = $value;

		}

		if ( isset( $value ) ){

			if ( is_array( $value ) ){

				// detect if array is article or params array
				if ( isset( $value[ 'id' ] ) ) {

					$gup[ 'article' ] = $value;

				}
				// is params array
				else {

					$gup = array_merge( $gup, $value );

				}

			}
			else {

				$gup[ 'article_id' ] = ( int ) $value;

			}

		}

		return ( string ) $this->_url( $gup );

	}

	// --------------------------------------------------------------------

	/**
	 * Return a article gallery function url
	 *
	 * Available aliases:
	 *
	 * 		Admin:
	 * 			- add
	 *
	 * @access public
	 * @param string
	 * @param mixed
	 * @param string
	 * @return string
	 */

	public function get_ag_url( $alias = NULL, $value = NULL, $environemnt = NULL ){

		// get url params
		$gup[ 'alias' ] = ( string ) $alias;
		$gup[ 'environemnt' ] = $environemnt;
		$gup[ 'function' ] = 'gallery';

		if ( isset( $value ) ){

			if ( is_array( $value ) ){

				$gup = array_merge( $gup, $value );

			}

		}

		return ( string ) $this->_url( $gup );

	}

	// --------------------------------------------------------------------

	/**
	 * Return a ajax function url
	 *
	 * Available aliases:
	 *
	 * 		Admin:
	 * 			- search | this make a global search (articles + categories)
	 * 			- articles_search
	 * 			- categories_search
	 *
	 * @access public
	 * @param string
	 * @param mixed
	 * @param string
	 * @return string
	 */

	public function get_ajax_url( $alias = NULL, $value = NULL, $environemnt = NULL ){

		// get url params
		$gup[ 'alias' ] = ( string ) $alias;
		$gup[ 'environemnt' ] = $environemnt;
		$gup[ 'function' ] = 'ajax';

		if ( isset( $value ) ){

			if ( is_array( $value ) ){

				// detect if array is article or params array
				if ( isset( $value[ 'id' ] ) ) {

					$gup[ 'article' ] = $value;

				}
				// is params array
				else {

					$gup = array_merge( $gup, $value );

				}

			}
			else {

				$gup[ 'article_id' ] = ( int ) $value;

			}

		}

		return ( string ) $this->_url( $gup );

	}

	// --------------------------------------------------------------------

	/**
	 * Return a Article component url
	 *
	 * Available aliases:
	 *
	 * @access private
	 * @param array
	 * @return array
	 */

	private function _url( $f_params = NULL ){

		// -------------------------------------------------
		// Parsing vars ------------------------------------

		$alias =								( isset( $f_params[ 'alias' ] ) AND is_string( $f_params[ 'alias' ] ) ) ? $f_params[ 'alias' ] : 'list';
		$function =								( isset( $f_params[ 'function' ] ) AND is_string( $f_params[ 'function' ] ) ) ? $f_params[ 'function' ] : 'articles';
		$environment =							( isset( $f_params[ 'environment' ] ) AND is_string( $f_params[ 'environment' ] ) ) ? $f_params[ 'environment' ] : $this->mcm->environment;
		$return_type =							( isset( $f_params[ 'return' ] ) AND is_string( $f_params[ 'return' ] ) ) ? $f_params[ 'return' ] : 'resolved';
		$template_fields =						( $return_type === 'template' AND isset( $f_params[ 'template_fields' ] ) AND is_array( $f_params[ 'template_fields' ] ) ) ? $f_params[ 'template_fields' ] : array();

		// -------------

		$cp =									( isset( $f_params[ 'cp' ] ) AND is_numeric( $f_params[ 'cp' ] ) ) ? $f_params[ 'cp' ] : NULL;
		$ipp =									( isset( $f_params[ 'ipp' ] ) AND is_numeric( $f_params[ 'ipp' ] ) ) ? $f_params[ 'ipp' ] : NULL;

		// -------------

		$get =									( isset( $f_params[ 'get' ] ) AND is_array( $f_params[ 'get' ] ) ) ? $f_params[ 'get' ] : NULL; // array
		$_g = $this->input->get();
		$get =									( ! isset( $get ) AND ! empty( $_g ) ) ? $this->input->get() : $get;

		// setting the q query string variable value
		if ( $this->input->post( 'terms' ) ) {

			$get[ 'q' ] = $this->input->post( 'terms' );

		}

		// -------------

		$article =								( $function == 'articles' AND isset( $f_params[ 'article' ] ) ) ? $f_params[ 'article' ] : NULL;
		$article_id =							( $function == 'articles' AND isset( $f_params[ 'article_id' ] ) AND is_numeric( $f_params[ 'article_id' ] ) AND $f_params[ 'article_id' ] > 0 ) ? ( int ) $f_params[ 'article_id' ] : NULL;

		// -------------

		$user_id =								( isset( $f_params[ 'user_id' ] ) AND is_numeric( $f_params[ 'user_id' ] ) AND $f_params[ 'user_id' ] > 0 ) ? ( int ) $f_params[ 'user_id' ] : 0;

		// -------------

		$category =								isset( $f_params[ 'category' ] ) ? $f_params[ 'category' ] : NULL;
		$category_id =							( isset( $f_params[ 'category_id' ] ) AND is_numeric( $f_params[ 'category_id' ] ) ) ? ( int ) $f_params[ 'category_id' ] : NULL;
		$ct =									( isset( $f_params[ 'ct' ] ) ) ? ( boolean ) $f_params[ 'ct' ] : FALSE;

		// -------------

		$menu_item_id =							( isset( $f_params[ 'menu_item_id' ] ) AND is_numeric( $f_params[ 'menu_item_id' ] ) ) ? ( int ) $f_params[ 'menu_item_id' ] : NULL;

		// -------------

		$order_by_value =						isset( $f_params[ 'order_by_value' ] ) ? $f_params[ 'order_by_value' ] : NULL;

		// -------------

		if ( $function === 'categories' ){

			// if we don't have the category array, but we have the id
			if ( ! is_array( $category ) AND isset( $category_id ) ) {

				$category = $this->get_category( $category_id );

			}

			// last try to get the category id
			$category_id =							isset( $category[ 'id' ] ) ? $category[ 'id' ] : NULL;

		}
		else if ( $function === 'ajax' ){

			// nothing for now

		}
		else if ( $function === 'gallery' ){

			// nothing for now

		}
		else {

			// if we don't have the article array, but we have the id
			if ( ! is_array( $article ) AND isset( $article_id ) ) {

				$article = $this->get( $article_id );

			}
			// else, if we have the article array
			else if ( is_array( $article ) ) {

				$article = $this->_articles[ $article_id ] = $article;

			}

			// last try to get the article id
			$article_id =							isset( $article[ 'id' ] ) ? $article[ 'id' ] : NULL;

		}

		// -------------

		// if we don't have the category array, but we have the id
		if ( ! is_array( $category ) AND $category_id >= 0 ) {

			$category = $this->get_category( $category_id );
			$category = $category ? $category : NULL;

		}

		$category_id =							isset( $category[ 'id' ] ) ? $category[ 'id' ] : $category_id;

		// Parsing vars ------------------------------------
		// -------------------------------------------------

		// -------------------------------------------------
		// Function ----------------------------------------

		// The function begins nearly here

		if ( $alias ){

			$complement = '';

			$base_url = $environment === 'site' ? '' : $environment;
			$base_url .= '/articles';

			$_final_array = array();

			if ( $article_id ){

				$_final_array[ 'aid' ] = $article_id;

			}

			if ( $menu_item_id ){

				$_final_array[ 'miid' ] = $menu_item_id;

			}

			if (

				$alias === 'list' OR
				$alias === 'search' OR
				$alias === 'articles_search' OR
				$alias === 'change_order_by' OR
				$alias === 'change_ordering' OR
				$alias === 'up_ordering' OR
				$alias === 'down_ordering' OR
				$alias === 'set_status_publish' OR
				$alias === 'set_status_unpublish' OR
				$alias === 'set_status_archived' OR
				$alias === 'change_order_by'

			) {

				if ( $category_id AND $function !== 'categories' ){

					$_final_array[ 'cid' ] = $category_id;

				}
				
				if ( key_exists( 'ct' , $template_fields ) ){
					
					$_final_array[ 'ct' ] = $template_fields[ 'ct' ];
					
				}
				
				if ( key_exists( 'cp' , $template_fields ) ){
					
					$_final_array[ 'cp' ] = $template_fields[ 'cp' ];
					
				}
				else if ( $cp ){

					$_final_array[ 'cp' ] = $cp;

				}

				if ( key_exists( 'ipp' , $template_fields ) ){

					$_final_array[ 'ipp' ] = $template_fields[ 'ipp' ];

				}
				else if ( $ipp ){

					$_final_array[ 'ipp' ] = $ipp;

				}

			}

			// in cancel search url, remove query string terms
			if ( $alias === 'cancel_search' ) {

				unset( $get[ 'q' ] );

			}

			// -------------------------
			// Articles ----------------

			if ( $function === 'articles' ) {

				if ( $environment == 'site' ){

					$_where = NULL;

					if ( $category_id AND $alias == 'list' ){

						$_where = '`status` = \'1\'';
						$_where .= ' AND ( `component_item` = \'articles_list\' AND `params` LIKE \'%"category_id":"' . $category_id . '"%\' )';

					}
					else if ( $article_id AND ( $alias == 'view' OR $alias == 'detail' ) ){

						$_where = '`status` = \'1\'';
						$_where .= ' AND (';
						$_where .= ' ( `component_item` = \'article_detail\' AND `params` LIKE \'%"article_id":"' . $article_id . '"%\' )';
						$_where .= $category_id ? ' OR ( `component_item` = \'articles_list\' AND `params` LIKE \'%"category_id":"' . $category_id . '"%\' )' : '';
						$_where .= ')';

					}

					if ( $_where ) {

						$_query_id = $_where;

						if ( ! isset( $menu_item_id ) ) {

							$this->load->helper( 'menus' );

							$menu_item_id = current_menu_id();

							if ( $this->cache->cache( $_query_id ) ) {

								$menu_item = $this->cache->cache( $_query_id );
								$menu_item_id = $menu_item[ 'id' ];

							}
							else if ( NULL === $this->cache->cache( $_query_id ) ) {

								$this->db->select( 'id' );
								$this->db->from( 'tb_menus' );
								$this->db->where( $_where, NULL, FALSE );

								$this->db->limit( 1 );

								$_result = $this->db->get();

								if ( $_result->num_rows() > 0 ) {

									$menu_item = $_result->row_array();
									$menu_item_id = $menu_item[ 'id' ];

								}
								else {

									$menu_item = FALSE;

								}

								$this->cache->cache( $_query_id, $menu_item );

							}

						}

					}

				}

				// if we have ajax query string, remove
				unset( $get[ 'ajax' ] );

				$base_url .= '/am';

				switch ( $alias ) {
					
					case 'list':
						
						if ( $environment == 'site' ){
							
							$out = 'articles/index/articles_list/' . $menu_item_id . '/' . $category_id . '/' . $user_id;

							if ( $return_type === 'template' ){

								$out .= '/%p%/%ipp%';

							}
							else {

								if ( isset( $cp ) )
									$out .= '/' . $cp;
								if ( isset( $ipp ) )
									$out .= '/' . $ipp;

							}

							return $out;

						}
						else {

							$_final_array[ 'a' ] = 'l';

						}

					break;
					case 'view':
						//falling though
					case 'detail':

						if ( $environment == 'site' ){

							$out = 'articles/index/article_detail/' . $menu_item_id . '/' . $article_id . '/' . $user_id;

							return $out;

						}

					break;
					case 'search': $_final_array[ 'a' ] = 's'; break;
					case 'cancel_search': $_final_array[ 'a' ] = 'l'; break;
					case 'add': $_final_array[ 'a' ] = 'a'; break;
					case 'edit': $_final_array[ 'a' ] = 'e'; break;
					case 'copy': $_final_array[ 'a' ] = 'c'; break;
					case 'remove': $_final_array[ 'a' ] = 'r';  break;
					case 'remove_all': $_final_array[ 'a' ] = 'ra'; break;
					case 'change_order_by': $_final_array[ 'a' ] = 'cob'; $_final_array[ 'ob' ] = $order_by_value; break;
					case 'change_ordering': $_final_array[ 'a' ] = 'co'; break;
					case 'fix_ordering': $_final_array[ 'a' ] = 'fo'; break;
					case 'up_ordering': $_final_array[ 'a' ] = 'co'; $_final_array[ 'sa' ] = 'u'; break;
					case 'down_ordering': $_final_array[ 'a' ] = 'co'; $_final_array[ 'sa' ] = 'd'; break;
					case 'set_status_publish': $_final_array[ 'a' ] = 'ss'; $_final_array[ 'sa' ] = 'p'; break;
					case 'set_status_unpublish': $_final_array[ 'a' ] = 'ss'; $_final_array[ 'sa' ] = 'u'; break;
					case 'set_status_archived': $_final_array[ 'a' ] = 'ss'; $_final_array[ 'sa' ] = 'a'; break;
					case 'batch': $_final_array[ 'a' ] = 'b'; break;

				}

				$return = $_final_array ? '/' . $this->uri->assoc_to_uri( $_final_array ) : '';

			}

			// Articles ----------------
			// -------------------------

			// -------------------------
			// Ajax --------------------

			else if ( $function === 'ajax' ) {

				$base_url .= '/ajax';

				switch ( $alias ) {

					case 'search': $_final_array[ 'a' ] = 's'; break;
					case 'articles_search': $_final_array[ 'a' ] = 'as'; break;
					case 'categories_search': $_final_array[ 'a' ] = 'cs'; break;

				}
				
				if ( $ct ){
					
					$_final_array[ 'ct' ] = ( int ) $ct;
					
				}
				
				$return = $_final_array ? '/' . $this->uri->assoc_to_uri( $_final_array ) : '';

			}

			// Ajax --------------------
			// -------------------------

			// -------------------------
			// Gallery -----------------

			else if ( $function === 'gallery' ) {

				$base_url .= '/ag';

				switch ( $alias ) {

					case 'add': $_final_array[ 'a' ] = 'a'; break;

				}

				$return = $_final_array ? '/' . $this->uri->assoc_to_uri( $_final_array ) : '';

			}

			// Gallery -----------------
			// -------------------------

			// -------------------------
			// Categories --------------

			else if ( $function === 'categories' ) {

				// if we have ajax query sctring, remove
				unset( $get[ 'ajax' ] );

				$base_url .= '/cm';

				if (

					$alias === 'edit' OR
					$alias === 'copy' OR
					$alias === 'remove' OR
					$alias === 'change_order_by' OR
					$alias === 'change_ordering' OR
					$alias === 'up_ordering' OR
					$alias === 'down_ordering' OR
					$alias === 'set_status_publish' OR
					$alias === 'set_status_unpublish'

				) {

					if ( $category_id ){

						$_final_array[ 'cid' ] = $category_id;

					}

				}

				switch ( $alias ) {

					case 'list': $_final_array[ 'a' ] = 'l'; break;
					case 'search': $_final_array[ 'a' ] = 's'; break;
					case 'cancel_search': $_final_array[ 'a' ] = 'l'; break;
					case 'add': $_final_array[ 'a' ] = 'a'; break;
					case 'edit': $_final_array[ 'a' ] = 'e'; break;
					case 'copy': $_final_array[ 'a' ] = 'c'; break;
					case 'remove': $_final_array[ 'a' ] = 'r';  break;
					case 'remove_all': $_final_array[ 'a' ] = 'ra'; break;
					case 'change_order_by': $_final_array[ 'a' ] = 'cob'; $_final_array[ 'ob' ] = $order_by_value; break;
					case 'change_ordering': $_final_array[ 'a' ] = 'co'; break;
					case 'fix_ordering': $_final_array[ 'a' ] = 'fo'; break;
					case 'up_ordering': $_final_array[ 'a' ] = 'co'; $_final_array[ 'sa' ] = 'u'; break;
					case 'down_ordering': $_final_array[ 'a' ] = 'co'; $_final_array[ 'sa' ] = 'd'; break;
					case 'set_status_publish': $_final_array[ 'a' ] = 'ss'; $_final_array[ 'sa' ] = 'p'; break;
					case 'set_status_unpublish': $_final_array[ 'a' ] = 'ss'; $_final_array[ 'sa' ] = 'u'; break;
					case 'set_status_archived': $_final_array[ 'a' ] = 'ss'; $_final_array[ 'sa' ] = 'a'; break;
					case 'batch': $_final_array[ 'a' ] = 'b'; break;

				}

				$return = $_final_array ? '/' . $this->uri->assoc_to_uri( $_final_array ) : '';

			}

			// Categories --------------
			// -------------------------

			$get = assoc_array_to_qs( $get, FALSE );

			$return =  "$base_url$return$get";

			return get_url( rtrim( $return, '/' ) );

		}

		return FALSE;

		// Function ----------------------------------------
		// -------------------------------------------------

	}

	// --------------------------------------------------------------------

	private function get_category_childrens( $parent_id, $level, $query = NULL ) {

		// Caso a query não tenha sido informada, executa isto
		if ( ! $query ){

			$this->db->select( 't1.*, t2.title as parent_title, t2.alias as parent_alias, t2.status as parent_status' );
			$this->db->from( 'tb_articles_categories t1' );
			$this->db->join( 'tb_articles_categories t2', 't1.parent = t2.id', 'left' );

			$this->db->order_by( 't1.ordering asc, t1.title asc, t1.id asc' );
			$query = $this->db->get();
			$query = $query->result_array();

			$this->categories_array = $query;

		}
		// atribue todos os filhos para a variável $menu_tree
		foreach( $query as $row ) {

			if ( $row[ 'parent' ] == $parent_id AND $row[ 'status' ] == 1 ) {
				// atribue cada valor da categoria em um array
				$this->categories_tree[ $row[ 'id' ] ] = array(
					'id' => $row[ 'id' ],
					'image' => isset( $row[ 'image' ] ) ? $row[ 'image' ] : '',
					'alias' => $row[ 'alias' ],
					'title' => $row[ 'title' ],
					'parent' => $row[ 'parent' ],
					'parent_alias' => $row[ 'parent_alias' ],
					'parent_status' => $row[ 'parent_status' ],
					'parent_title' => $row[ 'parent_title' ],
					'status' => $row[ 'status' ],
					'indented_title' => str_repeat( '&#8226;&nbsp;', $level ).'&#8226; '.$row[ 'title' ],
					'description' => $row[ 'description' ],
					'level' => $level,
				 );

				// chama esta função novamente para mostrar os filhos deste filho
				$this->get_category_childrens( $row[ 'id' ], $level + 1, $query );

			}
		}
	}

	// --------------------------------------------------------------------

	public function fetch( $where_condition = NULL ){

		$this->db->select( 't1.*, t2.title as category_title, t3.username as username, t3.name as name' );
		$this->db->from( 'tb_articles t1' );
		$this->db->join( 'tb_articles_categories t2', 't1.category_id = t2.id', 'left' );
		$this->db->join( 'tb_users t3', 't1.created_by_id = t3.id', 'left' );
		if ( $where_condition ){
			$this->db->where( $where_condition );
		}
		return $this->db->get();

	}

	// --------------------------------------------------------------------

	public function get_articles( $f_params = NULL ){

		// atribuindo valores às variávies

		$where_condition =						isset( $f_params[ 'where_condition' ] ) ? $f_params[ 'where_condition' ] : NULL;
		$or_where_condition =					isset( $f_params[ 'or_where_condition' ] ) ? $f_params[ 'or_where_condition' ] : NULL;
		$limit =								isset( $f_params[ 'limit' ] ) ? $f_params[ 'limit' ] : NULL;
		$offset =								isset( $f_params[ 'offset' ] ) ? $f_params[ 'offset' ] : NULL;
		$order_by =								isset( $f_params[ 'order_by' ] ) ? $f_params[ 'order_by' ] : 't1.title asc, t1.id asc';
		$order_by_escape =						isset( $f_params[ 'order_by_escape' ] ) ? $f_params[ 'order_by_escape' ] : TRUE;
		$return_type =							isset( $f_params[ 'return_type' ] ) ? $f_params[ 'return_type' ] : 'get';
		$random =								check_var( $f_params[ 'random' ] ) ? TRUE : FALSE;
		$has_image_first =						check_var( $f_params[ 'has_image_first' ] ) ? TRUE : FALSE;


		$this->db->select( '

			t1.*,

			t2.alias as category_alias,
			t2.parent as category_parent_id,
			t2.title as category_title,
			t2.description as category_description,
			t2.ordering as category_ordering,
			t2.status as category_status,

			t3.username as user_username,
			t3.group_id as users_group_id,
			t3.name as user_name,
			t3.email as user_email,
			t3.params as user_params,

		' );

		$this->db->from( 'tb_articles t1' );
		$this->db->join( 'tb_articles_categories t2', 't1.category_id = t2.id', 'left' );
		$this->db->join( 'tb_users t3', 't1.created_by_id = t3.id', 'left' );

		if ( $has_image_first ){

			$order_by = 'if( `t1`.`image` = \'\' or `t1`.`image` is null, 1, 0 ), `t1`.`image` ASC, ' . $order_by;
			$order_by_escape = FALSE;

		}

		$this->db->order_by( $order_by, ( $random ? 'RANDOM' : '' ), $order_by_escape );

		if ( $where_condition ){

			if( is_array( $where_condition ) ){

				foreach ( $where_condition as $key => $value ) {

					if( gettype( $where_condition ) === 'array' AND ( strpos( $key,'fake_index_' ) !== FALSE ) ){

						$this->db->where( $value );

					}
					else $this->db->where( $key, $value );

				}

			}
			else $this->db->where( $where_condition );

		}
		if ( $or_where_condition ){

			if( is_array( $or_where_condition ) ){

				foreach ( $or_where_condition as $key => $value ) {

					if( gettype( $or_where_condition ) === 'array' AND ( strpos( $key,'fake_index_' ) !== FALSE ) ){

						$this->db->or_where( $value );

					}
					else $this->db->or_where( $key, $value );

				}
			}
			else $this->db->or_where( $or_where_condition );

		}

		if ( $return_type === 'count_all_results' ){

			return $this->db->count_all_results();

		}
		if ( $limit ){

			$this->db->limit( $limit, $offset ? $offset : NULL );
			
		}
		
		return $this->db->get();
		
	}
	
	// --------------------------------------------------------------------
	
	public function get_articles_respecting_privileges( $f_params = NULL ){
		
		// -------------------------------------------------
		// Parsing vars ------------------------------------
		
		$login = $this->users->is_logged_in() ? $this->users->user_data[ 'id' ] : FALSE;
		
		// atribuindo valores às variávies
		$where_condition =						isset( $f_params[ 'where_condition' ] ) ? $f_params[ 'where_condition' ] : NULL;
		$limit =								isset( $f_params[ 'limit' ] ) ? $f_params[ 'limit' ] : NULL;
		$offset =								isset( $f_params[ 'offset' ] ) ? $f_params[ 'offset' ] : NULL;
		$order_by =								isset( $f_params[ 'order_by' ] ) ? $f_params[ 'order_by' ] : 't1.title asc, t1.id asc';
		$order_by_escape =						isset( $f_params[ 'order_by_escape' ] ) ? $f_params[ 'order_by_escape' ] : TRUE;
		$return_type =							isset( $f_params[ 'return_type' ] ) ? $f_params[ 'return_type' ] : 'get';
		$random =								check_var( $f_params[ 'random' ] ) ? TRUE : FALSE;
		$has_image_first =						check_var( $f_params[ 'has_image_first' ] ) ? TRUE : FALSE;
		$terms =								isset( $f_params[ 'terms' ] ) ? $f_params[ 'terms' ] : NULL;
		
		// user_id relativo aos artigos, ou seja, artigos do usuário com id $user_id
		$user_id =								isset( $f_params[ 'user_id' ] ) ? $f_params[ 'user_id' ] : NULL;
		
		// -1 = todas as categorias
		// 0 = artigos sem categoria
		// >0 = categoria específica
		$cat_id =								isset( $f_params[ 'cat_id' ] ) ? $f_params[ 'cat_id' ] : '-1';
		
		// id do artigo
		$art_id =								isset( $f_params[ 'art_id' ] ) ? $f_params[ 'art_id' ] : NULL;
		
		// Parsing vars ------------------------------------
		// -------------------------------------------------
		
		// get articles params
		$gap = array(
			
			'limit' => $limit,
			'offset' => $offset,
			'order_by' => $order_by,
			'order_by_escape' => $order_by_escape,
			'return_type' => $return_type,
			'random' => $random,
			'has_image_first' => $has_image_first,
			
		 );
		
		// -------------------------------------------------
		// montando as condições padrões -------------------

		$default_condition = '';

		if ( $art_id ){

			$default_condition .= '`t1`.`id` = ' . $art_id;

		}
		if ( $cat_id > 0 ){

			$this->get_category_childrens( $cat_id, 0 );

			if ( $this->categories_tree ){

				foreach( $this->categories_tree as $category ) {

					$cat_id .= '|' . $category[ 'id' ];

				}

				// Esta linha previne que esta variável seja usada por outras chamadas de outros componentes
				// Tinhamos um problema aqui, principalmente no módulo articles

				$this->categories_tree = NULL;

			}

			$default_condition .= ( $art_id ? ' AND ' : '' ) . '`category_id` REGEXP "' . $cat_id . '"  AND `t2`.`status` = 1 ';

		}
		else if ( $cat_id == 0 ){

			$default_condition .= ( $art_id ? ' AND ' : '' ) . '`category_id` = "0"';

		}
		else if ( $cat_id == '-1' ){

			$cat_id = '^0$';

			$this->get_category_childrens( 0, 0 );

			if ( $this->categories_tree ){

				foreach( $this->categories_tree as $category ) {

					$cat_id .= '|^' . $category[ 'id' ] . '$';

				}

				// Esta linha previne que esta variável seja usada por outras chamadas de outros componentes
				// Tinhamos um problema aqui, principalmente no módulo articles

				$this->categories_tree = NULL;

			}

			$default_condition .= ( $art_id ? ' AND ' : '' ) . '`category_id` REGEXP "' . $cat_id . '" ';

		}


		if ( $terms ){

			$full_term = $terms;

			$gap[ 'where_condition' ]  = '';
			$gap[ 'where_condition' ]  .= '(';
			$gap[ 'where_condition' ]  .= '`t1`.`title` LIKE \'%'.$full_term.'%\' ';
			$gap[ 'where_condition' ]  .= 'OR `t1`.`fulltext` LIKE \'%'.$full_term.'%\' ';
			$gap[ 'where_condition' ]  .= ') AND ( ';

			$terms = str_replace( '#', ' ', $terms );
			$terms = explode( " ", $terms );


		}



		if ( $user_id ){

			$default_condition .= ( ( $art_id OR $cat_id != '-1' ) ? ' AND ' : '' ) . '`created_by_id` = ' . $user_id;

		}

		$default_condition = ( $default_condition != '' ) ? $default_condition : NULL;

		$gap[ 'where_condition' ] = isset( $gap[ 'where_condition' ] ) ? $gap[ 'where_condition' ] . '( ' : '( ' ;
		$gap[ 'where_condition' ] .= '`access_type` = "public"';
		$gap[ 'where_condition' ] .= ' AND `t1`.`status` = 1';

		$gap[ 'where_condition' ] .= $default_condition ? ' AND ' . $default_condition : NULL;
		$gap[ 'where_condition' ] .= ' )';

		// montando as condições padrões -------------------
		// -------------------------------------------------

		// filtrando os artigos acessíveis ao usuário atual, caso esteja logado
		if ( $this->users->is_logged_in() ){

			if ( $this->users->check_privileges( 'articles_management_can_view_all_articles' ) ){

				$gap[ 'where_condition' ] .= ' OR ( ';

				$gap[ 'where_condition' ] .= ' ( t1.status REGEXP "-1|0|1" )';

				$gap[ 'where_condition' ] .= $default_condition ? ' AND ' . $default_condition : NULL;

				$gap[ 'where_condition' ] .= ' ) ';

			}
			else if ( $this->users->check_privileges( 'articles_management_can_view_only_your_own' ) ){

				$gap[ 'where_condition' ] .= ' OR ( ';

				$gap[ 'where_condition' ] .= ' ( ';
				$gap[ 'where_condition' ] .= ' `t1`.`created_by_id` = ' . $this->users->user_data[ 'id' ];
				$gap[ 'where_condition' ] .= ' OR ( `access_type` = \'users\' AND `access_id` LIKE \'%>'.$this->users->user_data[ 'id' ].'<%\' )';
				$gap[ 'where_condition' ] .= ' OR ( `access_type` = \'users_groups\' AND `access_id` LIKE \'%>'.$this->users->user_data[ 'group_id' ].'<%\' )';
				$gap[ 'where_condition' ] .= ' ) ';

				$gap[ 'where_condition' ] .= $default_condition ? ' AND ' . $default_condition : NULL;

				$gap[ 'where_condition' ] .= ' ) ';

			}
			else{

				// artigos do usuário atual com cat_id, art_id ou user_id
				$gap[ 'where_condition' ] .= ' OR ( ';
				$gap[ 'where_condition' ] .= '`t1`.`created_by_id` = ' . $this->users->user_data[ 'id' ];
				$gap[ 'where_condition' ] .= $default_condition ? ' AND ' . $default_condition : NULL;
				$gap[ 'where_condition' ] .= ' )';

				$gap[ 'or_where_condition' ][ 'fake_index_1' ] = '( ';

				// artigos com as condições padrões ( cat_id, user_id, art_id )
				$gap[ 'or_where_condition' ][ 'fake_index_1' ] .= $default_condition ? $default_condition . ' AND ' : NULL;

				$gap[ 'or_where_condition' ][ 'fake_index_1' ] .= '( ';
				$gap[ 'or_where_condition' ][ 'fake_index_1' ] .= '( `access_type` = \'users\' AND `access_id` LIKE \'%>'.$this->users->user_data[ 'id' ].'<%\' )';
				$gap[ 'or_where_condition' ][ 'fake_index_1' ] .= ' OR ( `access_type` = \'users_groups\' AND `access_id` LIKE \'%>'.$this->users->user_data[ 'group_id' ].'<%\' )';

				// artigos de grupos acessíveis
				$accessible_users_groups = $this->view_articles_get_accessible_users_groups();

				end( $accessible_users_groups );
				$lugk = key( $accessible_users_groups );

				$gap[ 'or_where_condition' ][ 'fake_index_1' ] .= ' OR ( `access_type` = \'users_groups\' AND `access_id` REGEXP "';
				foreach ( $accessible_users_groups as $key => $users_group ) {

					$gap[ 'or_where_condition' ][ 'fake_index_1' ] .= '(.*)>' . $users_group[ 'id' ] . '<(.*)';

					if ( ! ( $key == $lugk ) ){

						$gap[ 'or_where_condition' ][ 'fake_index_1' ] .= '|';

					}

				}
				$gap[ 'or_where_condition' ][ 'fake_index_1' ] .= '" ';

				$gap[ 'or_where_condition' ][ 'fake_index_1' ] .= ' OR `t3`.`group_id` REGEXP "';
				foreach ( $accessible_users_groups as $key => $users_group ) {

					$gap[ 'or_where_condition' ][ 'fake_index_1' ] .= '(.*)>' . $users_group[ 'id' ] . '<(.*)';

					if ( ! ( $key == $lugk ) ){

						$gap[ 'or_where_condition' ][ 'fake_index_1' ] .= '|';

					}

				}
				$gap[ 'or_where_condition' ][ 'fake_index_1' ] .= '" )';




				$gap[ 'or_where_condition' ][ 'fake_index_1' ] .= ' OR ( `t1`.`status` = \'-1\' OR `t1`.`status` = \'0\' )';
				$gap[ 'or_where_condition' ][ 'fake_index_1' ] .= ' )';

				$gap[ 'or_where_condition' ][ 'fake_index_1' ] .= ' )';

			}

		}

		if ( $terms ){

			$full_term = $terms;

			$gap[ 'where_condition' ]  .= ' ) ';
		}

		return $this->get_articles( $gap );
		
	}

	// --------------------------------------------------------------------

	public function view_articles_get_accessible_users_groups( $user_id = NULL ) {

		$user_id = $this->users->user_data[ 'id' ];
		$accessible_groups = FALSE;

		$this->users->get_users_groups_query();

		// grupos de usuários de mesmo nível e abaixo
		if ( $this->users->check_privileges( 'articles_management_can_view_only_same_and_low_group_level' ) ){

			$accessible_groups = $this->users->get_users_groups_same_and_low_group_level();

		}
		// grupos de usuários de mesmo nível
		else if ( $this->users->check_privileges( 'articles_management_can_view_only_same_group_level' ) ){

			$accessible_groups = $this->users->get_users_groups_same_group_level();

		}
		else if ( $this->users->check_privileges( 'articles_management_can_view_only_same_group_and_below' ) ){

			$accessible_groups = $this->users->get_users_groups_same_group_and_below();

		}
		else if ( $this->users->check_privileges( 'articles_management_can_view_only_same_group' ) ){

			$accessible_groups = $this->users->get_users_groups_same_group();

		}
		else if ( $this->users->check_privileges( 'articles_management_can_view_only_low_groups' ) ){

			$accessible_groups = $this->users->get_users_groups_low_groups();

		}

		return $accessible_groups;

	}

	// --------------------------------------------------------------------

	public function get_articles_checking_privileges( $cat_id = NULL, $user_id = NULL, $limit = NULL, $offset = NULL, $return_type = 'get', $order_by = 't1.ordering asc, t1.title asc, t1.id asc' ){


		$random = FALSE;
		$has_image_first = FALSE;
		$users = FALSE;

		if ( is_array( $cat_id ) ){

			$f_params = $cat_id;

			// -------------------------------------------------
			// Parsing vars ------------------------------------

			$login = $this->users->is_logged_in() ? $this->users->user_data[ 'id' ] : FALSE;

			// atribuindo valores às variávies
			$limit =								isset( $f_params[ 'limit' ] ) ? $f_params[ 'limit' ] : NULL;
			$offset =								isset( $f_params[ 'offset' ] ) ? $f_params[ 'offset' ] : NULL;
			$order_by =								isset( $f_params[ 'order_by' ] ) ? $f_params[ 'order_by' ] : 't1.title asc, t1.id asc';
			$order_by_escape =						isset( $f_params[ 'order_by_escape' ] ) ? $f_params[ 'order_by_escape' ] : TRUE;
			$return_type =							isset( $f_params[ 'return_type' ] ) ? $f_params[ 'return_type' ] : 'get';
			$terms =								isset( $f_params[ 'terms' ] ) ? $f_params[ 'terms' ] : NULL;
			$random =								check_var( $f_params[ 'random' ] ) ? TRUE : FALSE;
			$has_image_first =						check_var( $f_params[ 'has_image_first' ] ) ? TRUE : FALSE;

			// user_id relativo aos artigos, ou seja, artigos do usuário com id $user_id
			$user_id =								isset( $f_params[ 'user_id' ] ) ? $f_params[ 'user_id' ] : NULL;

			// -1 = todas as categorias
			// 0 = artigos sem categoria
			// >0 = categoria específica
			$cat_id =								isset( $f_params[ 'cat_id' ] ) ? $f_params[ 'cat_id' ] : '-1';

		}


		// usuários de grupos de mesmo nível e inferiores
		if ( $this->users->check_privileges( 'articles_management_can_view_only_same_and_low_group_level' ) ){

			$childrens_users_groups = $this->users->get_users_groups_tree( $this->users->user_data[ 'parent_user_group_id' ], 0, 'list' );

		}
		else if ( $this->users->check_privileges( 'articles_management_can_view_only_same_group_and_below' ) OR $this->users->check_privileges( 'articles_management_can_view_only_low_groups' ) ){

			$childrens_users_groups = $this->users->get_users_groups_tree( $this->users->user_data[ 'group_id' ],0,'list' );

		}

		$this->db->select( 't1.*, t2.title as user_group, t2.parent as parent_user_group_id, t2.parent as parent_user_group' );
		$this->db->from( 'tb_users t1' );
		$this->db->join( 'tb_users_groups t2', 't1.group_id = t2.id', 'left' );
		$this->db->order_by( 'name asc, id asc' );

		if ( $this->users->check_privileges( 'articles_management_can_view_only_same_and_low_group_level' ) ){

			if ( $childrens_users_groups ){
				foreach ( $childrens_users_groups as $key => $value ) {
					$this->db->or_where( 't1.group_id', $key );
				}
			}

		}
		// usuários de grupos de mesmo nível
		else if ( $this->users->check_privileges( 'articles_management_can_view_only_same_group_level' ) ){

			$this->db->or_where( 't2.parent', $this->users->user_data[ 'parent_user_group_id' ] );

		}
		// usuários de mesmo grupo e inferiores
		else if ( $this->users->check_privileges( 'articles_management_can_view_only_same_group_and_below' ) ){

			$this->db->or_where( 't1.group_id', $this->users->user_data[ 'group_id' ] );

			if ( $childrens_users_groups ){
				foreach ( $childrens_users_groups as $key => $value ) {
					$this->db->or_where( 't1.group_id', $key );
				}
			}

		}
		// usuários de mesmo grupo
		else if ( $this->users->check_privileges( 'articles_management_can_view_only_same_group' ) ){

			$this->db->or_where( 't1.id', $this->users->user_data[ 'id' ] );
			$this->db->or_where( 't1.group_id', $this->users->user_data[ 'group_id' ] );

		}
		else if ( $this->users->check_privileges( 'articles_management_can_view_only_low_groups' ) ){

			$this->db->or_where( 't1.id', $this->users->user_data[ 'id' ] );
			if ( $childrens_users_groups ){
				foreach ( $childrens_users_groups as $key => $value ) {
					if ( $value[ 'id' ] != $this->users->user_data[ 'group_id' ] ){
						$this->db->or_where( 't1.group_id', $value[ 'id' ] );
					}
				}
			}

		}
		else if ( $this->users->check_privileges( 'articles_management_can_view_only_your_own' ) ){

			$this->db->or_where( 't1.id', $this->users->user_data[ 'id' ] );

		}

		$users = $this->db->get()->result();

		if ( $cat_id > 0 ){

			$this->get_category_childrens( $cat_id, 0 );

			$cat_id = '^' . $cat_id . '$';

			if ( $this->categories_tree ){

				foreach( $this->categories_tree as $category ) {

					$cat_id .= '|^' . $category[ 'id' ] . '$';

				}

				// Esta linha previne que esta variável seja usada por outras chamadas de outros componentes
				// Tinhamos um problema aqui, principalmente no módulo articles

				$this->categories_tree = NULL;

			}

			$cat_id = '`category_id` REGEXP "'  . $cat_id . '"';

		}
		else if ( $cat_id === '0' ){

			$cat_id = '`category_id` REGEXP "^0$"';

		}
		else if ( $cat_id == '-1' ){

			$cat_id = NULL;

		}

		$this->db->select(

			'
				t1.*,

				t2.title as category_title,

				t3.username as created_by_username,
				t3.name as created_by_name,

				t4.username as modified_by_username,
				t4.name as modified_by_name,

				t5.id as access_user_id,
				t5.username as access_username,
				t5.name as access_user_name,

				t6.id as user_group_id,
				t6.alias as user_group_alias,
				t6.title as user_group_title,

				t7.id as parent_category_id,
				t7.title as parent_category_title

			'

		);
		$this->db->from( 'tb_articles t1' );
		$this->db->join( 'tb_articles_categories t2', 't1.category_id = t2.id', 'left' );
		$this->db->join( 'tb_users t3', 't1.created_by_id = t3.id', 'left' );
		$this->db->join( 'tb_users t4', 't1.modified_by_id = t4.id', 'left' );
		$this->db->join( 'tb_users t5', 't1.access_id = t5.id', 'left' );
		$this->db->join( 'tb_users_groups t6', 't1.access_id = t6.id', 'left' );
		$this->db->join( 'tb_articles_categories t7', 't2.parent = t7.id', 'left' );

		if ( $has_image_first ){

			$order_by = 'if( `t1`.`image` = \'\' or `t1`.`image` is null, 1, 0 ), `t1`.`image` ASC, ' . $order_by;
			$order_by_escape = FALSE;

		}

		$this->db->order_by( $order_by, ( $random ? 'RANDOM' : '' ), $order_by_escape );

		if ( $cat_id !== NULL ){

			$this->db->where( '( ' . $cat_id . ' )' );

		}

		$this->db->where( '( `access_type` = \'public\' ' );

		if ( $users ){
			foreach ( $users as $key => $user ) {
				$this->db->or_where( 'created_by_id', $user->id );
			}
		}
		// adicionando os artigos em que o usuário foi adicionado a lista de acesso
		$this->db->or_where( '( `access_type` = \'users\' AND `access_id` LIKE \'%>' . $this->users->user_data[ 'id' ] . '<%\' )' );
		$this->db->or_where( '( `access_type` = \'users_groups\' AND `access_id` LIKE \'%>' . $this->users->user_data[ 'group_id' ] . '<%\' ) )' );

		if ( $limit AND $return_type !== 'count_all_results' ){

			$this->db->limit( $limit, $offset ? $offset: NULL );

		}

		if ( $return_type === 'count_all_results' ){

			return $this->db->count_all_results();

		} else {

			return $this->db->get();

		}
	}

	// --------------------------------------------------------------------

	/**
	 * Return articles categories array tree
	 *
	 * @see get_array_tree() for params
	 */

	public function get_categories_tree( $f_params = NULL ){

		if ( is_array( $this->_categories ) AND empty( $this->_categories ) ) {

			return array();

		}

		// -------------------------------------------------
		// Parsing vars ------------------------------------

		$f_params[ 'array' ] =				( ! isset( $f_params[ 'array' ] ) OR ! is_array( $f_params[ 'array' ] ) ) ? $this->get_categories() : $f_params[ 'array' ];
		$f_params[ 'include_parent' ] =		isset( $f_params[ 'include_parent' ] ) ? ( bool ) $f_params[ 'include_parent' ] : TRUE;

		// Parsing vars ------------------------------------
		// -------------------------------------------------

		return $this->mcm->get_array_tree( $f_params );

	}

	// --------------------------------------------------------------------

	public function get_category_path( $id = 0, $parent_limit = 0, $categories = NULL, $html = FALSE, $separator = NULL ){

		if ( is_array( $this->_categories ) AND empty( $this->_categories ) ) {

			return array();

		}

		// -------------------------------------------------
		// Parsing vars ------------------------------------

		$id =								( isset( $id ) AND is_numeric( $id ) AND $id >= 0 ) ? ( int ) $id : 0;
		$parent_limit =						( isset( $parent_limit ) AND is_numeric( $parent_limit ) AND $parent_limit >= 0 ) ? ( int ) $parent_limit : 0;
		$separator =						( isset( $separator ) AND is_string( $separator ) ) ? $separator : lang( 'breadcrumb_separator' );

		// Parsing vars ------------------------------------
		// -------------------------------------------------

		// Caso a query não tenha sido informada, executa isto
		if ( ! $categories ){

			$categories = $this->get_categories();

		}

		$path = array();

		$category = $this->get_category( $id );

		if ( $category ) {

			$path[] = $category;

		}

		if ( $categories ) {

			$path = array_merge( $path, $this->_get_category_path( $category[ 'parent' ], $parent_limit, $categories ) );

			$path = array_reverse( $path );
			//$path = ( ( bool ) $html ) ? rtrim( $path, $separator ) : $path;

		}

		if ( ( bool ) $html ) {

			$_tmp = '';

			foreach ( $path as $key => $category ) {

				$url = $this->_url( array(

					'alias' => 'list',
					'environment' => 'site',
					'function' => 'articles',
					'category' => $category,

				) );

				$_tmp .= anchor( $url, $category[ 'title' ], 'class="" title="' . $category[ 'title' ] . '"' ) . $separator;

			}

			$path = rtrim( $_tmp, $separator );

		}

		return $path;

	}

	// --------------------------------------------------------------------

	/**
	 * Called by get_category_path function
	 *
	 * @access private
	 * @param numeric
	 * @param numeric
	 * @param array
	 * @return array
	 */

	private function _get_category_path( $id = 0, $parent_limit = 0, $categories = NULL ){

		if ( is_array( $this->_categories ) AND empty( $this->_categories ) ) {

			return array();

		}

		// -------------------------------------------------
		// Parsing vars ------------------------------------

		$id =								( isset( $id ) AND is_numeric( $id ) AND $id >= 0 ) ? ( int ) $id : 0;
		$parent_limit =						( isset( $parent_limit ) AND is_numeric( $parent_limit ) AND $parent_limit >= 0 ) ? ( int ) $parent_limit : 0;

		// Parsing vars ------------------------------------
		// -------------------------------------------------

		$path = array();

		$category = $this->get_category( $id );

		if ( $category ) {

			$path[] = $category;

		}

		if ( $categories ) {

			foreach( $categories as $search_category ) {

				if ( $category ) {

					if ( $search_category[ 'id' ] == $category[ 'parent' ] ) {

						$path[] = $search_category;
						$path = array_merge( $path, $this->_get_category_path( $search_category[ 'parent' ], $parent_limit, $categories ) );
						break;

					}

				}

				if ( $id == $parent_limit ) {

					break;

				}

			}

			//$path = array_reverse( $path );
			//$path = ( ( bool ) $html ) ? rtrim( $path, $separator ) : $path;

		}

		return $path;

	}

	// --------------------------------------------------------------------

	/**
	 * Check if a category has articles
	 *
	 * @access public
	 * @param numeric			the category id
	 * @param bool
	 * @param bool				if we have to search for articles on childrens categories
	 * @return array
	 */

	// --------------------------------------------------------------------

	public function category_has_articles( $id, $status = NULL, $recursive = FALSE ){

		if ( isset( $this->_categories ) AND is_array( $this->_categories ) AND isset( $this->_categories[ $id ] ) ) {

			$search_config = array(

				'plugins' => 'articles_search',
				'allow_empty_terms' => TRUE,
				'ipp' => FALSE,
				'plugins_params' => array(

					'articles_search' => array(

						'category_id' => $id,
						'recursive_categories' => $recursive,

					),

				),

			);

			if ( isset( $status ) ){

				$search_config[ 'plugins_params' ][ 'articles_search' ][ 'status' ] = $status;

			}

			$this->load->library( 'search' );
			$this->search->config( $search_config );

			$articles = $this->search->get_full_results( 'articles_search', TRUE );

			if ( count( $articles ) > 0 ){

				return count( $articles );

			}

		}

		return FALSE;

	}
	// --------------------------------------------------------------------

	/**
	 * Return categories as array
	 *
	 * @access public
	 * @param mix			can be a parent category id or a array
	 * @param bool
	 * @return array
	 */

	public function get_categories( $f_params = NULL, $recursive = TRUE, $include_parent = TRUE ){

		if ( ! isset( $this->_categories ) ) {

			$search_config = array(

				'plugins' => 'articles_categories_search',
				'allow_empty_terms' => TRUE,
				'ignore_terms' => TRUE,
				'ipp' => FALSE,
				'plugins_params' => array(

					'articles_categories_search' => array(

						'recursive_categories' => FALSE,
						'status' => isset( $f_params[ 'status' ] ) ? $f_params[ 'status' ] : NULL,

					),

				),

			);

			$this->load->library( 'search' );
			$this->search->reset_config();
			$this->search->config( $search_config );

			$categories = $this->search->get_full_results( 'articles_categories_search', TRUE );

			$this->append_categories( $categories );

			if ( ! $categories ) {

				$this->_categories = FALSE;
				return FALSE;

			}

		}
		else if ( $this->_categories === FALSE ) {

			return FALSE;

		}

		// -------------------------------------------------
		// Parsing vars ------------------------------------

		$parent_id = 0;

		if ( is_numeric( $f_params ) AND $f_params > 0 ){

			$parent_id =							$f_params;
			$recursive =							( isset( $recursive ) ) ? ( bool ) $recursive : TRUE;
			$include_parent =						( isset( $include_parent ) ) ? ( bool ) $include_parent : TRUE;

		}
		else if ( is_array( $f_params ) AND check_var( $f_params ) ){

			$parent_id =							( isset( $f_params[ 'parent_id' ] ) AND is_numeric( $f_params[ 'parent_id' ] ) ) ? ( int ) $f_params[ 'parent_id' ] : NULL;
			$recursive =							( isset( $f_params[ 'recursive' ] ) ) ? ( bool ) $f_params[ 'recursive' ] : TRUE;
			$include_parent =						( isset( $f_params[ 'include_parent' ] ) ) ? ( bool ) $f_params[ 'include_parent' ] : TRUE;

		}

		// Parsing vars ------------------------------------
		// -------------------------------------------------

		if ( ! $parent_id ) {

			$this->_categories = array_filter( $this->_categories );

			return $this->_categories;

		}
		else if ( $parent_id AND ! $recursive ) {

			$this->_categories = array_filter( $this->_categories );

			$_out = array();

			foreach ( $this->_categories as $key => $category ) {

				if ( $category[ 'parent' ] == $parent_id ) {

					$_out[ $category[ 'id' ] ] = $category;

				}

			}

			return $_out;

		}

		$p[ 'array' ] = $this->_categories;
		$p[ 'parent_id' ] = $parent_id;

		return $this->get_categories_tree( $p );

	}

	// --------------------------------------------------------------------

	public function get_category( $value = NULL ){

		if ( ! isset( $this->_categories ) ) {

			$this->get_categories();

		}
		else if ( $this->_categories === FALSE ) {

			return FALSE;

		}

		if ( is_numeric( $value ) AND isset( $this->_categories[ $value ] ) ) {

			return $this->_categories[ $value ];

		}

		$search_config = array(

			'plugins' => 'articles_categories_search',
			'ipp' => 1,
			'allow_empty_terms' => TRUE,

		);

		if ( is_array( $value ) ){

			$search_config[ 'plugins_params' ][ 'articles_categories_search' ][ 'where_conditions' ] = $value;

		}
		else if ( is_numeric( $value ) AND $value > 0 ){

			$search_config[ 'plugins_params' ][ 'articles_categories_search' ][ 'category_id' ] = ( int ) $value;

		}
		else{

			return FALSE;

		}

		$this->load->library( 'search' );
		$this->search->config( $search_config );

		$category = $this->search->get_full_results( 'articles_categories_search' );
		$category = $category[ 0 ];

		return check_var( $category ) ? $category : FALSE;

	}

	// --------------------------------------------------------------------

	/**
	 * Append categories to categories buffer array
	 *
	 * @access public
	 * @param array				categories array
	 * @return avoid
	 */

	public function append_categories( $array ){

		if ( is_array( $array ) AND ( key_exists( 0 , $array ) OR ! key_exists( 'id' , $array ) ) ) {

			foreach ( $array as $key => $category ) {

				$this->append_categories( $category );

			}

		}

		if ( is_array( $array ) AND key_exists( 'id' , $array ) ) {

			$this->_categories[ $array[ 'id' ] ] = $array;

		}

	}

	// --------------------------------------------------------------------

	/**
	 * Update a category
	 *
	 * @access public
	 * @param mixed
	 * @param mixed
	 * @return bool
	 */

	public function update_category( $var1 = NULL, $var2 = NULL ){

		// -------------------------------------------------
		// Parsing vars ------------------------------------

		$id =									( isset( $var1 ) AND is_numeric( $var1 ) AND $var1 > 0 ) ? $var1 : NULL;
		$db_data =								( isset( $var1 ) AND check_var( $var1 ) AND is_array( $var1 ) ) ? $var1 : NULL;
		$db_data =								( $id AND check_var( $var2 ) AND is_array( $var2 ) ) ? $var2 : $db_data;
		$condition =							( ! $id AND isset( $var2 ) AND check_var( $var2 ) AND is_array( $var2 ) ) ? $var2 : NULL;

		// Parsing vars ------------------------------------
		// -------------------------------------------------

		if ( is_array( $db_data ) AND is_array( $condition ) ){

			return $this->db->update( 'tb_articles_categories', $db_data, $condition );

		}
		else if ( $id AND is_array( $db_data ) ) {

			$condition = array( 'id' => $id );

			return $this->update_category( $db_data, $condition );

		}

		return FALSE;

	}

	// --------------------------------------------------------------------

	/**
	 * remove a category, without confirmation
	 *
	 * @access public
	 * @param int
	 * @return bool
	 */

	public function remove_category( $id = NULL ){

		if ( is_numeric( $id ) AND $id > 0 ) {

			if ( $this->db->delete( 'tb_articles_categories', array( 'id' => $id ) ) ){

				return TRUE;

			}

		}

		return FALSE;

	}

	// --------------------------------------------------------------------

	/**
	 * Get / set category status
	 *
	 * @access public
	 * @param int
	 * @param string
	 * @return bool
	 */

	public function category_status( $id = NULL, $value = NULL ){

		// -------------------------------------------------
		// Parsing vars ------------------------------------

		$id =								( isset( $id ) AND is_numeric( $id ) AND $id > 0 ) ? ( int ) $id : NULL;
		$value =							( isset( $value ) AND is_string( $value ) ) ? $value : NULL;

		// Parsing vars ------------------------------------
		// -------------------------------------------------

		if ( $id ) {

			if ( $value ) {

				$update_data = array(

					'status' => $value == 'p' ? '1' : '0',

				);

				return $this->update_category( $id, $update_data );

			}
			else {

				$category = $this->get_category( $id );

				if ( $category ) {

					return $category[ 'status' ];

				}

			}

		}

		return FALSE;

	}

	// --------------------------------------------------------------------

	/**
	 * Down category ordering
	 *
	 * @access public
	 * @param numeric
	 * @return bool
	 */

	public function down_c_ordering( $category_id = NULL ){

		$category = $this->get_category( $category_id );

		if ( $category ){

			$new_ordering = ( int ) $category[ 'ordering' ] - 1;

			return $this->set_c_ordering( $category_id, $new_ordering );

		}

		return FALSE;

	}

	// --------------------------------------------------------------------

	/**
	 * Up category ordering
	 *
	 * @access public
	 * @param numeric
	 * @return bool
	 */

	public function up_c_ordering( $category_id = NULL ){

		$category = $this->get_category( $category_id );

		if ( $category ){

			$new_ordering = ( int ) $category[ 'ordering' ] + 1;

			return $this->set_c_ordering( $category_id, $new_ordering );

		}

		return FALSE;

	}

	// --------------------------------------------------------------------

	/**
	 * Set category ordering
	 *
	 * @access public
	 * @param numeric
	 * @param numeric
	 * @return bool
	 */

	public function set_c_ordering( $id = NULL, $requested_position = NULL ){

		// set item ordering params
		$siop = array(

			'table_name' => 'tb_articles_categories',
			'id_column_name' => 'id',
			'id_value' => $id,
			'parent_column_name' => 'parent',
			'ordering_column_name' => 'ordering',
			'requested_position' => ( int ) $requested_position,

		);

		return $this->mcm->set_item_ordering( $siop );

	}

	// --------------------------------------------------------------------

	/**
	 * Fix categories ordering
	 *
	 * @access public
	 * @param numeric | the parent category, set NULL to all categories
	 * @return bool
	 */

	public function fix_c_ordering( $parent_value = NULL ){

		// fix items ordering params
		$fiop = array(

			'table_name' => 'tb_articles_categories',
			'parent_column_name' => 'parent',
			'ordering_column_name' => 'ordering',
			'parent_value' => ( int ) $parent_value,

		);

		return $this->mcm->fix_items_ordering( $fiop );

	}

	// --------------------------------------------------------------------

	/**
	 * Return the highest ordering value within a specific category
	 *
	 * @access public
	 * @param numeric | the parent category, NULL to all categories
	 * @return bool
	 */

	public function get_c_max_ordering( $parent_value = NULL ){

		// get max ordering params
		$gmop = array(

			'table_name' => 'tb_articles',
			'parent_column_name' => 'category_id',
			'parent_value' => ( int ) $parent_value,

		);

		return $this->mcm->get_max_ordering( $gmop );

	}

	// --------------------------------------------------------------------

	/**
	 * Return a category function url
	 *
	 * Available aliases:
	 *
	 * 		Admin:
	 * 			- list
	 * 			- search
	 * 			- cancel_search
	 * 			- add
	 * 			- edit
	 * 			- copy
	 * 			- remove
	 * 			- remove_all
	 * 			- change_order_by
	 * 			- change_ordering
	 * 			- up_ordering
	 * 			- down_ordering
	 * 			- set_status_publish
	 * 			- set_status_unpublish
	 * 			- batch
	 *
	 * @access public
	 * @param string
	 * @param mixed
	 * @param string
	 * @return string
	 */

	public function get_c_url( $alias = NULL, $value = NULL, $environemnt = NULL ){

		// get url params
		$gup[ 'alias' ] = ( string ) $alias;
		$gup[ 'environemnt' ] = $environemnt;
		$gup[ 'function' ] = 'categories';

		if ( $alias === 'change_order_by' ){

			$gup[ 'order_by_value' ] = $value;

		}

		if ( isset( $value ) ){

			if ( is_array( $value ) ){

				// detect if array is category or params array
				if ( isset( $value[ 'id' ] ) ) {

					$gup[ 'category' ] = $value;

				}
				// is params array
				else {

					$gup = array_merge( $gup, $value );

				}

			}
			if ( is_numeric( $value ) ){

				$gup[ 'category_id' ] = ( int ) $value;

			}

		}

		return ( string ) $this->_url( $gup );

	}

	// --------------------------------------------------------------------

	/**
	 * Parse a category data
	 *
	 * @access public
	 * @param array
	 * @return void
	 */

	public function parse_category( & $category = NULL ){

		reset( $category );
		if ( is_array( $category ) AND is_numeric( key( $category ) ) ) {

			foreach ( $category as $key => $item ) {

				if ( key_exists( 'id', $item ) ) {

					$this->parse_category( $item );

				}

			}

		}

		if ( is_array( $category ) AND key_exists( 'id', $category ) ){

			$category[ 'image' ] = isset( $category[ 'image' ] ) ? ( string ) $category[ 'image' ] : '';
			$category[ 'alias' ] = strip_tags( $category[ 'alias' ] );
			$category[ 'ordering' ] = ( int ) $category[ 'ordering' ];
			$category[ 'title' ] = html_entity_decode( $category[ 'title' ] );

			$category[ 'url' ] = $this->_url( array(

				'alias' => 'list',
				'environment' => 'site',
				'function' => 'articles',
				'category' => $category,

			) );

			$category[ 'description' ] = check_var( $category[ 'description' ] ) ? html_entity_decode( $category[ 'description' ] ) : '';
			$category[ 'status' ] = ( int ) $category[ 'status' ];

		}



	}

}
