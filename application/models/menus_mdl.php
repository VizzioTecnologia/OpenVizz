<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menus_mdl extends CI_Model{

	var $menu_items = array();
	private $_menu_items = array();
	private $_menu_types = array();

	private $_c_urls_array = array(); // Component urls in array format
	private $_c_urls = array(); // Component urls in string format

	// --------------------------------------------------------------------

	public function __construct(){

	}

	// --------------------------------------------------------------------

	/**
	 * Return a menu item as array
	 *
	 * @access public
	 * @param numeric
	 * @return array
	 */

	public function get_menu_item( $value = NULL ){

		if ( is_numeric( $value ) AND isset( $this->_menu_items[ $value ] ) ) {

			return $this->_menu_items[ $value ];

		}

		if ( is_array( $value ) ){

			$this->db->where( $value );

		}
		else if ( is_numeric( $value ) AND $value > 0 ){

			$this->db->where( 't1.id', ( int ) $value );

		}
		else{

			return FALSE;

		}

		
		$this->db->select('

			t1.*,

			t2.title as menu_type_title,

			t3.title as component_title,
			t3.unique_name as component_alias,

		');
		
		$this->db->from('tb_menus t1');
		$this->db->join('tb_menu_types t2', 't1.menu_type_id = t2.id', 'left');
		$this->db->join('tb_components t3', 't1.component_id = t3.id', 'left');
		
		$this->db->limit( 1 );

		$_query_id = $this->db->_compile_select();

		if ( $this->cache->cache( $_query_id ) ) {

			$this->db->_reset_write();
			$this->db->_reset_select();
			return $this->cache->cache( $_query_id );

		}

		$return = $this->db->get();

		if ( $return->num_rows() > 0 ) {

			$return = $return->row_array();

		}
		else {

			$return = FALSE;

		}

		$this->cache->cache( $_query_id, $return );

		return $return;

	}

	// --------------------------------------------------------------------

	/**
	 * Update a menu item
	 *
	 * @access public
	 * @param mixed
	 * @param mixed
	 * @return bool
	 */

	public function update_menu_item( $var1 = NULL, $var2 = NULL ){

		// -------------------------------------------------
		// Parsing vars ------------------------------------

		$id =									( isset( $var1 ) AND is_numeric( $var1 ) AND $var1 > 0 ) ? $var1 : NULL;
		$db_data =								( isset( $var1 ) AND check_var( $var1 ) AND is_array( $var1 ) ) ? $var1 : NULL;
		$db_data =								( $id AND check_var( $var2 ) AND is_array( $var2 ) ) ? $var2 : $db_data;
		$condition =							( ! $id AND isset( $var2 ) AND check_var( $var2 ) AND is_array( $var2 ) ) ? $var2 : NULL;

		// Parsing vars ------------------------------------
		// -------------------------------------------------

		if ( is_array( $db_data ) AND is_array( $condition ) ){

			return $this->db->update( 'tb_menus', $db_data, $condition );

		}
		else if ( $id AND is_array( $db_data ) ) {

			$condition = array( 'id' => $id );

			return $this->update_menu_item( $db_data, $condition );

		}

		return FALSE;

	}

	// --------------------------------------------------------------------

	/**
	 * Get / set menu item status
	 *
	 * @access public
	 * @param mixed
	 * @param string
	 * @return bool
	 */

	public function menu_item_status( $id = NULL, $value = NULL ){

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

				return $this->update_menu_item( $id, $update_data );

			}
			else {

				$menu_item = $this->menus->get_menu_item( $id );

				if ( $menu_item ) {

					return $menu_item[ 'status' ];

				}

			}

		}

		return FALSE;

	}

	// --------------------------------------------------------------------

	/**
	 * Down menu item ordering
	 *
	 * @access public
	 * @param numeric
	 * @return bool
	 */

	public function down_menu_item_ordering( $menu_item_id = NULL ){

		$menu_item = $this->menus->get_menu_item( $menu_item_id );

		if ( $menu_item ){

			$new_ordering = ( int ) $menu_item[ 'ordering' ] - 1;

			return $this->set_menu_item_ordering( $menu_item_id, $new_ordering );

		}

		return FALSE;

	}

	// --------------------------------------------------------------------

	/**
	 * Up menu item ordering
	 *
	 * @access public
	 * @param numeric
	 * @return bool
	 */

	public function up_menu_item_ordering( $menu_item_id = NULL ){

		$menu_item = $this->menus->get_menu_item( $menu_item_id );

		if ( $menu_item ){

			$new_ordering = ( int ) $menu_item[ 'ordering' ] + 1;

			return $this->set_menu_item_ordering( $menu_item_id, $new_ordering );

		}

		return FALSE;

	}

	// --------------------------------------------------------------------

	/**
	 * Set menu item ordering
	 *
	 * @access public
	 * @param numeric
	 * @param numeric
	 * @return bool
	 */

	public function set_menu_item_ordering( $id = NULL, $requested_position = NULL ){

		// set item ordering params
		$siop = array(

			'table_name' => 'tb_menus',
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
	 * Fix menu items ordering
	 *
	 * @access public
	 * @param numeric | the parent menu item, set NULL to all menu items
	 * @return bool
	 */

	public function fix_menu_items_ordering( $parent_value = NULL ){

		// fix items ordering params
		$fiop = array(

			'table_name' => 'tb_menus',
			'parent_column_name' => 'parent',
			'parent_value' => ( int ) $parent_value,

		);

		return $this->mcm->fix_items_ordering( $fiop );

	}

	// --------------------------------------------------------------------

	/**
	 * Return the highest ordering value within a specific menu item
	 *
	 * @access public
	 * @param numeric | the parent menu item, NULL to all menu items
	 * @return bool
	 */

	public function get_max_menu_item_ordering( $parent_value = NULL ){

		// get max ordering params
		$gmop = array(

			'table_name' => 'tb_menus',
			'parent_column_name' => 'parent',
			'parent_value' => ( int ) $parent_value,

		);

		return $this->mcm->get_max_ordering( $gmop );

	}

	// --------------------------------------------------------------------

	/**
	 * Return a menu type as array
	 *
	 * @access public
	 * @param numeric
	 * @return array
	 */

	public function get_menu_type( $value = NULL ){

		if ( is_array( $this->_menu_types ) AND empty( $this->_menu_types ) ) {

			$this->get_menu_types();

		}

		if ( is_numeric( $value ) AND isset( $this->_menu_types[ $value ] ) ) {

			return $this->_menu_types[ $value ];

		}

		if ( is_array( $value ) ){

			$this->db->where( $value );

		}
		else if ( is_numeric( $value ) AND $value > 0 ){

			$this->db->where( 't1.id', ( int ) $value );

		}
		else{

			return FALSE;

		}

		$this->db->select( '*' );
		$this->db->from( 'tb_menu_types t1' );
		$this->db->limit( 1 );
		$return = $this->db->get();

		return $return->num_rows() > 0 ? $return->row_array() : FALSE;

	}

	// --------------------------------------------------------------------

	/**
	 * Return a menu type as array
	 *
	 * @access public
	 * @param numeric
	 * @return array
	 */

	public function get_menu_types(){

		if ( isset( $this->_menu_types ) AND is_array( $this->_menu_types ) AND ! empty( $this->_menu_types ) ) {

			return $this->_menu_types;

		}

		$return = FALSE;

		$this->db->select( '*' );
		$this->db->from( 'tb_menu_types t1' );
		$return = $this->db->get();

		if ( $return->num_rows() > 0 ) {

			$return = $return->result_array();
			$this->_menu_types = $return;

		}

		return $return;

	}

	// --------------------------------------------------------------------

	/**
	 * Parse a menu item data
	 *
	 * @access public
	 * @param array
	 * @return void
	 */

	public function parse_menu_item( & $menu_item = NULL ){

		if ( $menu_item ){

			$menu_item[ 'alias' ] = strip_tags( $menu_item[ 'alias' ] );
			$menu_item[ 'ordering' ] = ( int ) $menu_item[ 'ordering' ];
			$menu_item[ 'title' ] = html_entity_decode( $menu_item[ 'title' ] );
			$menu_item[ 'description' ] = check_var( $menu_item[ 'description' ] ) ? html_entity_decode( $menu_item[ 'description' ] ) : '';
			$menu_item[ 'status' ] = ( int ) $menu_item[ 'status' ];

		}

	}

	// --------------------------------------------------------------------

	/**
	 * Return a menu item function url
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
	 * 			- batch
	 *
	 * @access public
	 * @param string
	 * @param mixed
	 * @param string
	 * @return string
	 */

	public function get_mi_url( $alias = NULL, $value = NULL, $environemnt = NULL ){

		// get url params
		$gup[ 'alias' ] = ( string ) $alias;
		$gup[ 'environemnt' ] = $environemnt;
		$gup[ 'function' ] = 'menu_items';

		if ( $alias === 'change_order_by' ){

			$gup[ 'order_by_value' ] = $value;

		}

		if ( isset( $value ) ){

			if ( is_array( $value ) ){

				// detect if array is menu_item or params array
				if ( isset( $value[ 'id' ] ) ) {

					$gup[ 'menu_item' ] = $value;

				}
				// is params array
				else {

					$gup = array_merge( $gup, $value );

				}

			}
			else {

				$gup[ 'menu_item_id' ] = ( int ) $value;

			}

		}

		return ( string ) self::_url( $gup );

	}

	// --------------------------------------------------------------------

	/**
	 * Return menu items array tree
	 *
	 * @see get_array_tree() for params
	 */

	public function get_menu_items_tree( $f_params = NULL ){

		// -------------------------------------------------
		// Parsing vars ------------------------------------

		$f_params[ 'array' ] =				( ! isset( $f_params[ 'array' ] ) OR ! is_array( $f_params[ 'array' ] ) ) ? $this->get_menu_items() : $f_params[ 'array' ];

		// Parsing vars ------------------------------------
		// -------------------------------------------------

		return $this->mcm->get_array_tree( $f_params );

	}

	// --------------------------------------------------------------------

	/**
	 * Return a Menus component url
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
		$function =								( isset( $f_params[ 'function' ] ) AND is_string( $f_params[ 'function' ] ) ) ? $f_params[ 'function' ] : 'menu_types';
		$environment =							( isset( $f_params[ 'environment' ] ) AND is_string( $f_params[ 'environment' ] ) ) ? $f_params[ 'environment' ] : $this->environment;
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

		$menu_item =							( $function == 'menu_items' AND isset( $f_params[ 'menu_item' ] ) ) ? $f_params[ 'menu_item' ] : NULL;
		$menu_item_type =						( $function == 'menu_items' AND isset( $f_params[ 'menu_item_type' ] ) AND is_string( $f_params[ 'menu_item_type' ] ) ) ? $f_params[ 'menu_item_type' ] : NULL;
		$menu_item_id =							( $function == 'menu_items' AND isset( $f_params[ 'menu_item_id' ] ) AND is_numeric( $f_params[ 'menu_item_id' ] ) AND $f_params[ 'menu_item_id' ] > 0 ) ? ( int ) $f_params[ 'menu_item_id' ] : NULL;

		// -------------

		$component_item =					( $function == 'menu_items' AND isset( $f_params[ 'component_item' ] ) AND is_string( $f_params[ 'component_item' ] ) ) ? $f_params[ 'component_item' ] : NULL;
		$component_id =						( $function == 'menu_items' AND isset( $f_params[ 'component_id' ] ) AND is_numeric( $f_params[ 'component_id' ] ) AND $f_params[ 'component_id' ] > 0 ) ? ( int ) $f_params[ 'component_id' ] : NULL;

		// -------------

		$menu_type =							isset( $f_params[ 'menu_type' ] ) ? $f_params[ 'menu_type' ] : NULL;
		$menu_type_id =							( isset( $f_params[ 'menu_type_id' ] ) AND is_numeric( $f_params[ 'menu_type_id' ] ) ) ? ( int ) $f_params[ 'menu_type_id' ] : NULL;

		// -------------

		$order_by_value =						isset( $f_params[ 'order_by_value' ] ) ? $f_params[ 'order_by_value' ] : NULL;

		// -------------

		if ( $function === 'menu_types' ){

			// if we don't have the menu type array, but we have the id
			if ( ! is_array( $menu_type ) AND isset( $menu_type_id ) ) {

				$menu_type = $this->get_menu_type( $menu_type_id );

			}
			// else, if we have the menu type array
			else if ( is_array( $menu_type ) AND isset( $menu_type[ 'id' ] ) ) {

				$this->_menu_types[ $menu_type_id ] = $menu_type;

			}

			// last try to get the category id
			$menu_type_id =							isset( $menu_type[ 'id' ] ) ? $menu_type[ 'id' ] : NULL;

		}
		else if ( $function === 'ajax' ){

			// nothing for now

		}
		else {

			// if we don't have the menu item array, but we have the id
			if ( ! is_array( $menu_item ) AND isset( $menu_item_id ) ) {

				$menu_item = $this->menus->get_menu_item( $menu_item_id );

			}
			// else, if we have the menu_item array
			else if ( is_array( $menu_item ) ) {

				$menu_item = $this->_menu_items[ $menu_item_id ] = $menu_item;
				$menu_type_id = $menu_item[ 'menu_type_id' ];

			}

			// last try to get the menu_item id
			$menu_item_id =							isset( $menu_item[ 'id' ] ) ? $menu_item[ 'id' ] : NULL;

		}

		// -------------

		// if we have menu item, set the type
		if ( is_array( $menu_item ) ) {

			if ( ! isset( $menu_item_type ) ) {
				
				$menu_item_type = $menu_item[ 'type' ];
				
			}
			if ( ! isset( $component_item ) ) {

				$component_item = $menu_item[ 'component_item' ];

			}
			if ( ! isset( $component_id ) ) {

				$component_id = $menu_item[ 'component_id' ];

			}

		}

		// -------------

		// if we don't have the menu_type array, but we have the id
		if ( ! is_array( $menu_type ) AND $menu_type_id >= 0 ) {

			// caching the menu_type
			if ( ! isset( $this->_menu_types[ $menu_type_id ] ) ){

				$menu_type = $this->_menu_types[ $menu_type_id ] = self::get_menu_type( $menu_type_id );
				$menu_type = $menu_type ? $menu_type : NULL;

			}

		}
		// else, if we have the menu_type array
		else if ( is_array( $menu_type ) ) {

			// caching the menu_type
			if ( ! isset( $this->_menu_types[ $menu_type[ 'id' ] ] ) ){

				$menu_type = $this->_menu_types[ $menu_type_id ] = $menu_type;

			}

		}

		$menu_type_id =							isset( $menu_type[ 'id' ] ) ? $menu_type[ 'id' ] : $menu_type_id;

		// Parsing vars ------------------------------------
		// -------------------------------------------------

		// -------------------------------------------------
		// Function ----------------------------------------

		// The function begins nearly here

		if ( $alias ){

			$complement = '';

			$base_url = $environment === 'site' ? '' : $environment;
			$base_url .= '/menus';

			$_final_array = array();

			if ( $menu_item_id ){

				$_final_array[ 'miid' ] = $menu_item_id;

			}

			if ( $menu_type_id ){

				$_final_array[ 'mtid' ] = $menu_type_id;

			}

			if ( $menu_item_type ){

				$_final_array[ 't' ] = $menu_item_type;

				if ( $menu_item_type == 'component' ) {

					if ( $component_item ){

						$_final_array[ 'ci' ] = $component_item;

					}
					if ( $component_id ){

						$_final_array[ 'cid' ] = $component_id;

					}

				}

			}

			if (

				$alias === 'list' OR
				$alias === 'search' OR
				$alias === 'menu_items_search' OR
				$alias === 'change_order_by' OR
				$alias === 'change_ordering' OR
				$alias === 'up_ordering' OR
				$alias === 'down_ordering' OR
				$alias === 'set_status_publish' OR
				$alias === 'set_status_unpublish' OR
				$alias === 'set_status_archived' OR
				$alias === 'change_order_by'

			) {

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

			if ( $function === 'menu_items' ) {

				// if we have ajax query sctring, remove
				unset( $get[ 'ajax' ] );

				$base_url .= '/mim';

				switch ( $alias ) {

					case 'list': $_final_array[ 'a' ] = 'l'; break;
					case 'search': $_final_array[ 'a' ] = 's'; break;
					case 'cancel_search': $_final_array[ 'a' ] = 'l'; break;
					case 'add': $_final_array[ 'a' ] = 'a'; break;
					case 'select_menu_item_type': $_final_array[ 'a' ] = ( $menu_item_id ? 'e' : 'a' ); $_final_array[ 'sa' ] = 'smit'; break;
					case 'edit': $_final_array[ 'a' ] = 'e'; break;
					case 'copy': $_final_array[ 'a' ] = 'c'; break;
					case 'remove': $_final_array[ 'a' ] = 'r';  break;
					case 'remove_all': $_final_array[ 'a' ] = 'ra'; break;
					case 'set_home_page': $_final_array[ 'a' ] = 'shp'; break;
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
					case 'menu_items_search': $_final_array[ 'a' ] = 'as'; break;
					case 'menu_types_search': $_final_array[ 'a' ] = 'cs'; break;

				}

				$return = $_final_array ? '/' . $this->uri->assoc_to_uri( $_final_array ) : '';

			}

			// Ajax ----------------
			// -------------------------

			// -------------------------
			// Categories --------------

			else if ( $function === 'menu_types' ) {

				// if we have ajax query sctring, remove
				unset( $get[ 'ajax' ] );

				$base_url .= '/mtm';

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

					if ( $menu_type_id ){

						$_final_array[ 'mtid' ] = $menu_type_id;

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

			return rtrim( $return, '/' );

		}

		return FALSE;

		// Function ----------------------------------------
		// -------------------------------------------------

	}

	// --------------------------------------------------------------------

	public function _get_mi_url( $f_params = NULL ){

		// -------------------------------------------------
		// Parsing vars ------------------------------------

		$alias =								isset( $f_params[ 'alias' ] ) ? $f_params[ 'alias' ] : NULL;

		if ( ! $alias ) return FALSE;

		$menu_item =							isset( $f_params[ 'menu_item' ] ) ? $f_params[ 'menu_item' ] : NULL;
		$menu_item_id =							isset( $f_params[ 'menu_item_id' ] ) ? $f_params[ 'menu_item_id' ] : NULL;

		if ( ! $menu_item AND $menu_item_id ) {

			// cacheando o item, evitando mais um acesso ao banco
			if ( ! isset( $this->menu_items[ $menu_item_id ] ) ){

				$menu_item = $this->menu_items[ $menu_item_id ] = $this->menus->get_menu_item( $menu_item_id );

			}

		}
		else if ( $menu_item ) {

			// cacheando o item, evitando mais um acesso ao banco
			if ( ! isset( $this->menu_items[ $menu_item[ 'id' ] ] ) ){

				$menu_item = $this->menu_items[ $menu_item_id ] = $menu_item;

			}

		}

		$menu_item_id =							isset( $menu_item[ 'id' ] ) ? $menu_item[ 'id' ] : NULL;
		$menu_type_id =							isset( $menu_item[ 'menu_type_id' ] ) ? $menu_item[ 'menu_type_id' ] : NULL;
		$menu_item_type =						isset( $menu_item[ 'type' ] ) ? $menu_item[ 'type' ] : NULL;

		// Parsing vars ------------------------------------
		// -------------------------------------------------

		if ( ! $menu_type_id ){

			$uri_t_a = $this->uri->ruri_to_assoc();

			$menu_type_id = isset( $uri_t_a[ 'mtid' ] ) ? $uri_t_a[ 'mtid' ] : FALSE;

		}
		if ( $menu_type_id ){

			$this->c_urls_array[ 'mi_base' ] = $this->c_urls_array[ 'mi_base' ] + array(

				'mtid' => $menu_type_id,

			);

		}
		if ( $menu_item_id ){

			$this->c_urls_array[ 'mi_base' ][ 'miid' ] = $menu_item_id;

		}

		if ( $alias ){

			$target_url_array = $this->c_urls_array[ 'mi_base' ];

			switch ( $alias ) {

				case 'list':

					return $this->c_urls[ 'mi_list' ] . '/' . $this->uri->assoc_to_uri( $target_url_array );

					break;

				case 'add':

					return $this->c_urls[ 'mi_add' ] . '/' . $this->uri->assoc_to_uri( $target_url_array );

					break;

				case 'edit':

					$target_url_array[ 't' ] = $menu_item_type;

					if ( $menu_item ) {

						if ( $menu_item[ 'type' ] == 'component' ){

							$target_url_array[ 'cid' ] = $menu_item[ 'component_id' ];
							$target_url_array[ 'ci' ] = $menu_item[ 'component_item' ];

						}

						return $this->c_urls[ 'mi_edit' ] . '/' . $this->uri->assoc_to_uri( $target_url_array );

					}

					break;

				case 'remove':

					return $this->c_urls[ 'mi_remove' ] . '/' . $this->uri->assoc_to_uri( $target_url_array );

					break;

				case 'change_ordering':

					return $this->c_urls[ 'mi_change_ordering' ] . '/' . $this->uri->assoc_to_uri( $target_url_array );

					break;

				case 'down_ordering':

					return $this->c_urls[ 'mi_down_ordering' ] . '/' . $this->uri->assoc_to_uri( $target_url_array );

					break;

				case 'up_ordering':

					return $this->c_urls[ 'mi_up_ordering' ] . '/' . $this->uri->assoc_to_uri( $target_url_array );

					break;

				case 'set_home_page':

					return $this->c_urls[ 'mi_set_home_page' ] . '/' . $this->uri->assoc_to_uri( $target_url_array );

					break;

				case 'set_status_publish':

					return $this->c_urls[ 'mi_change_status_publish' ] . '/' . $this->uri->assoc_to_uri( $target_url_array );

					break;

				case 'set_status_unpublish':

					return $this->c_urls[ 'mi_change_status_unpublish' ] . '/' . $this->uri->assoc_to_uri( $target_url_array );

					break;

				default:

					break;

			}

		}

		return FALSE;

	}

	// --------------------------------------------------------------------

	public function get_menus_types( $f_params = NULL ){

		// inicializando as variáveis
		$where_condition =						@$f_params['where_condition'] ? $f_params['where_condition'] : NULL;
		$or_where_condition =					@$f_params['or_where_condition'] ? $f_params['or_where_condition'] : NULL;
		$limit =								@$f_params['limit'] ? $f_params['limit'] : NULL;
		$offset =								@$f_params['offset'] ? $f_params['offset'] : NULL;
		$order_by =								@$f_params['order_by'] ? $f_params['order_by'] : 't1.title asc, t1.id asc';
		$order_by_escape =						@$f_params['order_by_escape'] ? $f_params['order_by_escape'] : TRUE;
		$return_type =							@$f_params['return_type'] ? $f_params['return_type'] : 'get';

		$this->db->select('

			t1.*,

		');

		$this->db->from('tb_menu_types t1');

		$this->db->order_by( $order_by, '', $order_by_escape );

		if ( $where_condition ){
			if( is_array( $where_condition ) ){
				foreach ($where_condition as $key => $value) {
					if(gettype($where_condition) === 'array' AND (strpos($key,'fake_index_') !== FALSE) ){
						$this->db->where($value);
					}
					else $this->db->where($key, $value);
				}
			}
			else $this->db->where($where_condition);
		}
		if ( $or_where_condition ){
			if( is_array( $or_where_condition ) ){
				foreach ($or_where_condition as $key => $value) {
					if(gettype($or_where_condition) === 'array' AND (strpos($key,'fake_index_') !== FALSE) ){
						$this->db->or_where($value);
					}
					else $this->db->or_where($key, $value);
				}
			}
			else $this->db->or_where($or_where_condition);
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

	/**
	 * Return menu items as array
	 *
	 * @access public
	 * @param numeric			the parent id
	 * @return array
	 */

	public function get_menu_items( $value = NULL ){

		if ( is_array( $this->_menu_items ) AND empty( $this->_menu_items ) ) {

			$this->db->select('

				t1.*,

				t2.title as menu_type_title,

				t3.title as component_title,
				t3.unique_name as component_alias,

			');

			$this->db->from('tb_menus t1');
			$this->db->join('tb_menu_types t2', 't1.menu_type_id = t2.id', 'left');
			$this->db->join('tb_components t3', 't1.component_id = t3.id', 'left');
			$this->db->order_by( 't1.ordering asc, t1.title asc, t1.id asc' );
			$menu_items = $this->db->get();

			if ( $menu_items->num_rows() > 0 ) {

				$menu_items = $menu_items->result_array();

				$this->_menu_items = NULL;

				foreach( $menu_items as $menu_item ) {

					$this->_menu_items[ $menu_item[ 'id' ] ] = $menu_item;

				}

			}
			else {

				$this->_menu_items = array();

			}

		}

		$p = array(

			'array' => $this->_menu_items,

		);

		if ( is_numeric( $value ) AND $value > 0 ){

			$p[ 'parent_id' ] = $value;

		}
		else if ( isset( $this->_menu_items[ $value ] ) AND is_array( $this->_menu_items[ $value ] ) AND check_var( $this->_menu_items[ $value ] ) ) {

			$this->_menu_items[ $value ] = array_filter( $this->_menu_items[ $value ] );

			return $this->_menu_items[ $value ];

		}
		else if ( is_array( $this->_menu_items ) AND check_var( $this->_menu_items ) ) {

			$this->_menu_items = array_filter( $this->_menu_items );

			return $this->_menu_items;

		}

		return $this->get_menu_items_tree( $p );

	}

	// --------------------------------------------------------------------

	private function _get_menu_items( $f_params = NULL ){

		// inicializando as variáveis
		$where_condition =						@$f_params['where_condition'] ? $f_params['where_condition'] : NULL;
		$or_where_condition =					@$f_params['or_where_condition'] ? $f_params['or_where_condition'] : NULL;
		$limit =								@$f_params['limit'] ? $f_params['limit'] : NULL;
		$offset =								@$f_params['offset'] ? $f_params['offset'] : NULL;
		$order_by =								@$f_params['order_by'] ? $f_params['order_by'] : 't1.title asc, t1.id asc';
		$order_by_escape =						@$f_params['order_by_escape'] ? $f_params['order_by_escape'] : TRUE;
		$return_type =							@$f_params['return_type'] ? $f_params['return_type'] : 'get';

		$this->db->select('

			t1.*,

			t2.title as menu_type_title,

			t3.title as component_title,
			t3.unique_name as component_alias,

		');

		$this->db->from('tb_menus t1');
		$this->db->join('tb_menu_types t2', 't1.menu_type_id = t2.id', 'left');
		$this->db->join('tb_components t3', 't1.component_id = t3.id', 'left');

		$this->db->order_by( $order_by, '', $order_by_escape );

		if ( $where_condition ){
			if( is_array( $where_condition ) ){
				foreach ($where_condition as $key => $value) {
					if(gettype($where_condition) === 'array' AND (strpos($key,'fake_index_') !== FALSE) ){
						$this->db->where($value);
					}
					else $this->db->where($key, $value);
				}
			}
			else $this->db->where($where_condition);
		}
		if ( $or_where_condition ){
			if( is_array( $or_where_condition ) ){
				foreach ($or_where_condition as $key => $value) {
					if(gettype($or_where_condition) === 'array' AND (strpos($key,'fake_index_') !== FALSE) ){
						$this->db->or_where($value);
					}
					else $this->db->or_where($key, $value);
				}
			}
			else $this->db->or_where($or_where_condition);
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

	public function get_menu_items_respecting_privileges( $f_params = NULL ){

		// -------------------------------------------------
		// Parsing vars ------------------------------------

		$login = $this->users->is_logged_in() ? $this->users->user_data['id'] : FALSE;

		// atribuindo valores às variávies
		$where_condition =						@isset( $f_params['where_condition'] ) ? $f_params['where_condition'] : NULL;
		$limit =								@isset( $f_params['limit'] ) ? $f_params['limit'] : NULL;
		$offset =								@isset( $f_params['offset'] ) ? $f_params['offset'] : NULL;
		$order_by =								@isset( $f_params['order_by'] ) ? $f_params['order_by'] : 't1.title asc, t1.id asc';
		$order_by_escape =						@isset( $f_params['order_by_escape'] ) ? $f_params['order_by_escape'] : TRUE;
		$return_type =							@isset( $f_params['return_type'] ) ? $f_params['return_type'] : 'get';

		// id do menu
		$menu_type_id =							@isset( $f_params['menu_type_id'] ) ? $f_params['menu_type_id'] : NULL;

		// id do item de menu
		$menu_item_id =							@isset( $f_params['menu_item_id'] ) ? $f_params['menu_item_id'] : NULL;

		// Parsing vars ------------------------------------
		// -------------------------------------------------

		// get menu items params
		$gmip = array(

			'limit' => $limit,
			'offset' => $offset,
			'order_by' => $order_by,
			'order_by_escape' => $order_by_escape,
			'return_type' => $return_type,

		);

		// -------------------------------------------------
		// montando as condições padrões -------------------

		$default_condition = '';

		if ( $menu_item_id ){

			$default_condition .= '`t1`.`id` = ' . $menu_item_id;

		}
		if ( $menu_type_id ){

			$default_condition .= ( $menu_item_id ? ' AND ' : '' ) . '`t1`.`menu_type_id` = ' . $menu_type_id;

		}

		$default_condition = ( $default_condition != '' ) ? $default_condition : NULL;

		$gmip['where_condition'] = '( ';
		$gmip['where_condition'] .= '`access_type` = "public"';
		$gmip['where_condition'] .= ' AND `t1`.`status` = 1';

		$gmip['where_condition'] .= $default_condition ? ' AND ' . $default_condition : NULL;
		$gmip['where_condition'] .= ' )';

		// montando as condições padrões -------------------
		// -------------------------------------------------

		// filtrando os itens de menus acessíveis ao usuário atual, caso esteja logado
		if ( $this->users->is_logged_in() ){

			if ( $this->users->check_privileges('menus_items_can_view_all') ){

				$gmip['where_condition'] = '( ';

				$gmip['where_condition'] .= $default_condition;

				$gmip['where_condition'] .= ' ) ';

			}
			else if ( $this->users->check_privileges('menus_items_can_view_only_accessible') ){

				$gmip['where_condition'] .= ' OR ( ';

				$gmip['where_condition'] .= ' ( ';
				$gmip['where_condition'] .= ' (`access_type` = \'users\' AND `access_ids` LIKE \'%>'.$this->users->user_data['id'].'<%\')';
				$gmip['where_condition'] .= ' OR (`access_type` = \'users_groups\' AND `access_ids` LIKE \'%>'.$this->users->user_data['group_id'].'<%\')';
				$gmip['where_condition'] .= ' ) ';

				$gmip['where_condition'] .= $default_condition ? ' AND ' . $default_condition : NULL;

				$gmip['where_condition'] .= ' ) ';

			}
			else{

				$gmip['or_where_condition']['fake_index_1'] = '( ';

				// artigos com as condições padrões ( cat_id, user_id, art_id )
				$gmip['or_where_condition']['fake_index_1'] .= $default_condition ? $default_condition . ' AND ' : NULL;

				$gmip['or_where_condition']['fake_index_1'] .= '( ';
				$gmip['or_where_condition']['fake_index_1'] .= '( `access_type` = \'users\' AND `access_ids` LIKE \'%>'.$this->users->user_data['id'].'<%\')';
				$gmip['or_where_condition']['fake_index_1'] .= ' OR (`access_type` = \'users_groups\' AND `access_ids` LIKE \'%>'.$this->users->user_data['group_id'].'<%\')';

				// obtendo os grupos de usuários acessíveis
				$accessible_users_groups = $this->view_menus_items_get_accessible_users_groups();

				// obtendo a lista de usuários com base nos grupos obtidos

				// get users params
				$gup = array(



				);

				$gup['or_where_condition'] = '';
				$i = 1;
				foreach ( $accessible_users_groups as $key => $accessible_users_group ) {

					$gup[ 'or_where_condition' ][ 'fake_index_' . $i ] = '`group_id` = "' . $accessible_users_group[ 'id' ] . '"';

					$i++;

				}

				$users = $this->users->get_users( $gup )->result_array();

				$users_ids = '';

				foreach ( $users as $key => $user) {

					$users_ids .= '(.*)>' . $user['id'] . '<(.*)|';

				}

				$users_ids = rtrim( $users_ids, '|' );


				$gmip['or_where_condition']['fake_index_1'] .= ' OR ( `access_type` = \'users\' AND `access_ids` REGEXP "' . $users_ids . '" ) ';

				end( $accessible_users_groups );
				$lugk = key( $accessible_users_groups );

				$gmip['or_where_condition']['fake_index_1'] .= ' OR ( `access_type` = \'users_groups\' AND `access_ids` REGEXP "';
				foreach ( $accessible_users_groups as $key => $users_group ) {

					$gmip['or_where_condition']['fake_index_1'] .= '(.*)>' . $users_group['id'] . '<(.*)';

					if ( ! ( $key == $lugk ) ){

						$gmip['or_where_condition']['fake_index_1'] .= '|';

					}

				}
				$gmip['or_where_condition']['fake_index_1'] .= '" )';



				$gmip['or_where_condition']['fake_index_1'] .= ')';

				$gmip['or_where_condition']['fake_index_1'] .= ' )';

			}

		}

		return $this->get_menu_items( $gmip );

	}

	// --------------------------------------------------------------------

	public function view_menus_items_get_accessible_users_groups( $user_id = NULL ) {

		$user_id = $this->users->user_data['id'];
		$accessible_groups = FALSE;

		$this->users->get_users_groups_query();

		// grupos de usuários de mesmo nível e abaixo
		if ($this->users->check_privileges('menus_items_can_view_only_same_and_low_group_level')){

			$accessible_groups = $this->users->get_users_groups_same_and_low_group_level();

		}
		// grupos de usuários de mesmo nível
		else if ($this->users->check_privileges('menus_items_can_view_only_same_group_level')){

			$accessible_groups = $this->users->get_users_groups_same_group_level();

		}
		else if ($this->users->check_privileges('menus_items_can_view_only_same_group_and_below')){

			$accessible_groups = $this->users->get_users_groups_same_group_and_below();

		}
		else if ($this->users->check_privileges('menus_items_can_view_only_same_group')){

			$accessible_groups = $this->users->get_users_groups_same_group();

		}
		else if ($this->users->check_privileges('menus_items_can_view_only_low_groups')){

			$accessible_groups = $this->users->get_users_groups_low_groups();

		}

		return $accessible_groups;

	}

	// --------------------------------------------------------------------

}
