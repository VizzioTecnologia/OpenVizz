<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menus_common_model extends CI_Model{

	var $menu_items = array();
	var $c_urls_array = array(); // Component urls in array format
	var $c_urls = array(); // Component urls in string format

	// --------------------------------------------------------------------

	public function __construct(){

		// -------------------------------------------------
		// Component urls ----------------------------------

		$c_urls = & $this->c_urls;
		$c_urls_array = & $this->c_urls_array;

		$mi_management_alias = 'mim';

		$c_urls_array[ 'mi_base' ] = array(); // Incomplete url, must have complement

		$c_urls_array[ 'mi_add' ] = $c_urls_array[ 'mi_base' ] + array( // Incomplete url, must have complement

			'a' => 'ami',
			'sa' => 'select_menu_item_type',

		);
		$c_urls_array[ 'mi_edit' ] = $c_urls_array[ 'mi_base' ] + array( // Incomplete url, must have complement

			'a' => 'emi',

		);
		$c_urls_array[ 'mi_list' ] = $c_urls_array[ 'mi_base' ] + array(

			'a' => 'mil',

		);
		$c_urls_array[ 'mi_search' ] = $c_urls_array[ 'mi_base' ] + array(

			'a' => 's',

		);
		$c_urls_array[ 'mi_remove' ] = $c_urls_array[ 'mi_base' ] + array( // Incomplete url, must have complement

			'a' => 'r',

		);
		$c_urls_array[ 'mi_remove_all' ] = $c_urls_array[ 'mi_base' ] + array(

			'a' => 'ra',

		);
		$c_urls_array[ 'mi_change_ordering' ] = $c_urls_array[ 'mi_base' ] + array( // Incomplete url, must have complement

			'a' => 'co',

		);
		$c_urls_array[ 'mi_up_ordering' ] = $c_urls_array[ 'mi_change_ordering' ] + array( // Incomplete url, must have complement

			'sa' => 'u',

		);
		$c_urls_array[ 'mi_down_ordering' ] = $c_urls_array[ 'mi_change_ordering' ] + array( // Incomplete url, must have complement

			'sa' => 'd',

		);
		$c_urls_array[ 'mi_set_home_page' ] = $c_urls_array[ 'mi_base' ] + array( // Incomplete url, must have complement

			'a' => 'shp',

		);
		$c_urls_array[ 'mi_change_status_publish' ] = $c_urls_array[ 'mi_base' ] + array( // Incomplete url, must have complement

			'a' => 'csp',

		);
		$c_urls_array[ 'mi_change_status_unpublish' ] = $c_urls_array[ 'mi_base' ] + array( // Incomplete url, must have complement

			'a' => 'csu',

		);

		$c_urls = $c_urls_array;

		$menus_base_url = $this->mcm->environment . '/menus';

		$mi_management_url = $menus_base_url . '/' . $mi_management_alias;

		foreach ( $c_urls as $key => & $c_url ) {

			$c_url = $mi_management_url . '/' . $this->uri->assoc_to_uri( $c_url );

		}

		$c_urls[ 'base_url' ] = $menus_base_url;
		$c_urls[ 'mi_management' ] = $mi_management_url;

	}

	// --------------------------------------------------------------------

	public function get_menu_item( $value = NULL ){

		$this->db->select('*');
		$this->db->from('tb_menus');

		if ( is_array( $value ) ){

			$this->db->where( $value );

		}
		else if ( $value > 0 ){

			$this->db->where( 'id', ( int )$value );

		}
		else{

			return FALSE;

		}

		$this->db->limit( 1 );

		$return = $this->db->get();

		return $return->num_rows() > 0 ? $return : FALSE;

	}

	// --------------------------------------------------------------------

	public function update_menu_item( $data = NULL, $condition = NULL ){

		if ( $data != NULL && $condition != NULL ){

			return $this->db->update( 'tb_menus', $data, $condition );
		}

		return FALSE;

	}

	// --------------------------------------------------------------------

	public function down_menu_item_ordering( $menu_item_id = NULL ){

		$menu_item = $this->menus->get_menu_item( $menu_item_id );

		if ( $menu_item ){

			$menu_item = $menu_item->row_array();

			$new_ordering = ( int ) $menu_item[ 'ordering' ] - 1;

			return $this->set_menu_item_ordering( $menu_item_id, $new_ordering );

		}

		return FALSE;

	}

	// --------------------------------------------------------------------

	public function up_menu_item_ordering( $menu_item_id = NULL ){

		$menu_item = $this->menus->get_menu_item( $menu_item_id );

		if ( $menu_item ){

			$menu_item = $menu_item->row_array();

			$new_ordering = ( int ) $menu_item[ 'ordering' ] + 1;

			return $this->set_menu_item_ordering( $menu_item_id, $new_ordering );

		}

		return FALSE;

	}

	// --------------------------------------------------------------------

	public function set_menu_item_ordering( $id = NULL, $requested_position = NULL ){

		// set item ordering params
		$siop = array(

			'table_name' => 'tb_menus',
			'id_column_name' => 'id',
			'id_value' => $id,
			'parent_column_name' => 'parent',
			'ordering_column_name' => 'ordering',
			'requested_position' => $requested_position,

		);

		return $this->mcm->set_item_ordering( $siop );

	}

	// --------------------------------------------------------------------

	public function fix_menu_items_ordering( $parent_value = NULL ){

		// fix items ordering params
		$fiop = array(

			'table_name' => 'tb_menus',
			'parent_column_name' => 'parent',
			'parent_value' => $parent_value,

		);

		return $this->mcm->fix_items_ordering( $fiop );

	}

	// --------------------------------------------------------------------

	public function get_mi_list_url( $menu_item_id = NULL ){

		return $this->_get_url( 'list', $menu_item_id );

	}

	// --------------------------------------------------------------------

	public function get_mi_add_url( $menu_item_id = NULL ){

		return $this->_get_url( 'add', $menu_item_id );

	}

	// --------------------------------------------------------------------

	public function get_mi_edit_url( $menu_item_id = NULL ){

		return $this->_get_url( 'edit', $menu_item_id );

	}

	// --------------------------------------------------------------------

	public function get_mi_remove_url( $menu_item_id = NULL ){

		return $this->_get_url( 'remove', $menu_item_id );

	}

	// --------------------------------------------------------------------

	public function get_mi_change_ordering_url( $menu_item_id = NULL ){

		return $this->_get_url( 'change_ordering', $menu_item_id );

	}

	// --------------------------------------------------------------------

	public function get_mi_down_ordering_url( $menu_item_id = NULL ){

		return $this->_get_url( 'down_ordering', $menu_item_id );

	}

	// --------------------------------------------------------------------

	public function get_mi_up_ordering_url( $menu_item_id = NULL ){

		return $this->_get_url( 'up_ordering', $menu_item_id );

	}

	// --------------------------------------------------------------------

	public function get_mi_set_home_page_url( $menu_item_id = NULL ){

		return $this->_get_url( 'set_home_page', $menu_item_id );

	}

	// --------------------------------------------------------------------

	public function get_mi_change_status_publish_url( $menu_item_id = NULL ){

		return $this->_get_url( 'set_status_publish', $menu_item_id );

	}

	// --------------------------------------------------------------------

	public function get_mi_change_status_unpublish_url( $menu_item_id = NULL ){

		return $this->_get_url( 'set_status_unpublish', $menu_item_id );

	}

	// --------------------------------------------------------------------

	public function _get_url( $alias = NULL, $menu_item_id = NULL ){

		if ( $alias ) {

			// get url params
			$gup = array(

				'alias' => ( string ) $alias,

			);

			if ( $menu_item_id ){

				if ( is_array( $menu_item_id ) ){

					$gup[ 'menu_item' ] = $menu_item_id;

				}
				else {

					$gup[ 'menu_item_id' ] = ( int ) $menu_item_id;

				}

			}

			return $this->_get_mi_url( $gup );

		}

		return false;

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

	public function get_menu_items( $f_params = NULL ){

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
