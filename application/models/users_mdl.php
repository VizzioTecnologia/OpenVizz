<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_mdl extends CI_Model{

	public $users_groups_tree = '';
	public $next_has_children = FALSE;
	public $user_data = array();
	public $users_query = NULL;
	public $users_groups_query = NULL;
	public $users_groups_tree_array = array();
	
	// --------------------------------------------------------------------
	
	public function get_global_config_params( $current_params_values = NULL ){
		
		$post = $this->input->post( NULL, TRUE ) ? $this->input->post( NULL, TRUE ) : NULL;
		$params = get_params_spec_from_xml( APPPATH . 'controllers/admin/com_users/global_config_params.xml' );
		
		$_current_params_values = isset( $current_params_values ) ? $current_params_values : array();
		$current_params_values = isset( $post[ 'params' ] ) ? array_merge_recursive( $_current_params_values, $post[ 'params' ] ) : $_current_params_values;
		
		$new_params = array();
		
		// ------------------------------------
		
		//$users_groups = $this->users->get_accessible_users_groups( $this->users->user_data[ 'id' ] );
		$users_groups = $this->users->get_users_groups_tree( 0, 0, 'list' );
		
		$new_params[ 'c_users_register_section' ][] = array(
			
			'type' => 'spacer',
			'label' => 'c_users_default_user_group_id_register',
			'tip' => 'tip_c_users_default_user_group_id_register',
			
		);
		
		// ------------------------------------
		// User groups
		
		//$users_groups = $this->users->get_accessible_users_groups( $this->users->user_data[ 'id' ] );
		$users_groups = $this->users->get_users_groups_tree( 0, 0, 'list' );
		
		foreach ( $users_groups as $key => $ug ) {
			
			$_options[ $ug[ 'id' ] ] = $ug[ 'indented_title' ];
			
			if ( ! $current_params_values ) {
				
				$params[ 'params_spec_values' ][ 'c_users_default_user_group_id_register' ][] = $ug[ 'id' ];
				
			}
			
		}
		
		$new_params[ 'c_users_register_section' ][] = array(
			
			'type' => 'radio',
			'name' => 'c_users_default_user_group_id_register',
			'label' => 'c_users_default_user_group_id_register',
			'options' => $_options,
			'validation' => array(
				
				'rules' => 'trim|required',
				
			),
			
		);
		
		// ------------------------------------
		
		array_push_pos( $params[ 'params_spec' ][ 'c_users_register_section' ], $new_params[ 'c_users_register_section' ], 20  );
		
		// ------------------------------------
		
		// carregando os layouts do tema atual
		$layouts_login = dir_list_to_array( THEMES_PATH . site_theme_components_views_path() . 'users' . DS . 'index' . DS . 'login' );
		// carregando os layouts do diretório de views padrão
		$layouts_login = array_merge( $layouts_login, dir_list_to_array( VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . 'users' . DS . 'index' . DS . 'login' ) );
		
		$current_section = 'c_users_look_and_feel';
		foreach ( $params[ 'params_spec' ][ $current_section ] as $key => $element ) {
			
			if ( $element[ 'name' ] == 'c_users_layouts_login' ){
				
				$spec_options = array();
				
				if ( isset( $params[ 'params_spec' ][ $current_section ][ $key ][ 'options' ] ) )
					$spec_options = $params[ 'params_spec' ][ $current_section ][ $key ][ 'options' ];
					
				$params[ 'params_spec' ][ $current_section ][ $key ][ 'options' ] = is_array( $spec_options ) ? $spec_options + $layouts_login : $layouts_login;
				
			}
			
		}
		
		
		// print_r($params);
		
		return $params;
		
	}
	
	// --------------------------------------------------------------------
	
	public function decode_user_id( $user_id ){
		
		return base64_decode( base64_decode( base64_decode( base64_decode( $user_id ) ) ) );
		
	}
	
	public function encode_user_id( $user_id ){
		
		return base64_encode( base64_encode( base64_encode( base64_encode( $user_id ) ) ) );
		
	}
	
	// --------------------------------------------------------------------
	
	// Define preferências de um usuário
	// a variável $update_db pode ser definida como FALSE se esta função estiver sendo chamada de alguma função que já atualize os dados do usuário
	public function set_user_preferences( $data = NULL, $user_id = NULL, $update_db = TRUE, $override_per_key = FALSE ){
		
		if ( isset( $data ) AND is_array( $data ) ){
			
// 			echo '<h1>Dados recebidos:</h1><pre>' . print_r( $data, TRUE ) . '</pre>';
			
			// se o id do usuário alvo não for informado, utilizamos o id do usuário atual
			$user_id = $user_id ? $user_id : $this->user_data[ 'id' ];
			
			$current_user = FALSE;
			
			// verifica se estamos lidando com o usuário atual
			if ( $user_id == $this->user_data['id'] )
				$current_user = TRUE;
			
			$user_params = array();
			
			if ( $current_user ){
				
				// preferências atuais do usuário atual
				$user_params = $this->user_data[ 'params' ];
				
			}
			else{
				
				$user_params = $this->get_user( array( 't1.id' => $user_id ) )->row_array();
				$user_params = get_params( $user_params[ 'params' ] );
				
			}
			
// 			echo '<h1>Params atuais do usuário:</h1><pre>' . print_r( $user_params, TRUE ) . '</pre>';
			
			$params = array_merge( $user_params, $data );
			
// 			echo '<h1>Params final:</h1><pre>' . print_r( $params, TRUE ) . '</pre>'; exit;
			
			// se o usuário for o atual, atualizamos a variável com os dados do usuário
			if ( $current_user )
				$this->user_data['params'] = $params;
			
			$data = array(
				
				'params' => json_encode( $params ),
				
			);
			
			if ( $update_db ){

				if ( $this->db->update( 'tb_users', $data, array( 'id' => $user_id ) ) ){
					// confirm the update
					return TRUE;
				}
				else {
					// case the update fails, return false
					return FALSE;
				}

			}

		}
		else {
			return FALSE;
		}

	}
	
	// --------------------------------------------------------------------
	
	// Retorna um parâmetro de preferência do usuário atual
	public function get_user_preference( $param = NULL ){

		if ( isset( $param ) AND check_var( $this->user_data[ 'params' ] ) ){

			if ( key_exists( $param, $this->user_data[ 'params' ] ) ){

				return $this->user_data['params'][$param];

			}

		}

		return FALSE;

	}
	
	// --------------------------------------------------------------------
	
	public function get_users_query($condition = NULL, $limit = NULL, $offset = NULL, $order_by = 'name asc, id asc'){

		// Caso a query dos users groups esteja vazia, obtem agora
		if ( $this->users_query === NULL ){
			$this->db->select('t1.*, t2.title as user_group_title, t2.alias as user_group_alias, t2.description as user_group_description, t2.parent as user_group_parent_id');
			$this->db->from('tb_users t1');
			$this->db->join('tb_users_groups t2', 't1.group_id = t2.id', 'left');

			if ($condition){
				$this->db->where($condition);
			}
			if ($order_by){
				$this->db->order_by($order_by);
			}


			if ($this->users_query = $this->db->get(NULL, $limit, $offset)){
				return TRUE;
			}
			else {
				$this->users_query = NULL;
				return FALSE;
			}
		}
		else {
			return TRUE;
		}

	}
	
	// --------------------------------------------------------------------
	
	public function get_users( $f_params = NULL ){
		
		// inicializando as variáveis
		$where_condition =
		$or_where_condition =
		$limit =
		$offset =
		$order_by =
		$order_by_escape =
		$return_type = NULL;
		
		// atribuindo valores às variávies
		if ( isset( $f_params ) ){
			
			$where_condition =						isset( $f_params['where_condition'] ) ? $f_params['where_condition'] : NULL;
			$or_where_condition =					isset( $f_params['or_where_condition'] ) ? $f_params['or_where_condition'] : NULL;
			$limit =								isset( $f_params['limit'] ) ? $f_params['limit'] : NULL;
			$offset =								isset( $f_params['offset'] ) ? $f_params['offset'] : NULL;
			$order_by =								isset( $f_params['order_by'] ) ? $f_params['order_by'] : 't1.name asc, t1.id asc';
			$order_by_escape =						isset( $f_params['order_by_escape'] ) ? $f_params['order_by_escape'] : TRUE;
			$return_type =							isset( $f_params['return_type'] ) ? $f_params['return_type'] : 'get';

		}

		$this->db->select('

			t1.*,

			t2.title as user_group,
			t2.parent as parent_user_group_id,
			t2.parent as parent_user_group

		');

		$this->db->from('tb_users t1');
		$this->db->join('tb_users_groups t2', 't1.group_id = t2.id', 'left');

		if( isset( $f_params[ 'order_by' ] ) ) $this->db->order_by( $order_by, '', $order_by_escape );

		if ( $where_condition ){
			if( is_array( $where_condition ) ){
				foreach ( $where_condition as $key => $value ) {
					if(gettype( $where_condition ) === 'array' AND (strpos($key,'fake_index_') !== FALSE) ){
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
	
	// obtém a lista de usuários com base nos privilégios de gerenciamento
	public function get_users_checking_privileges(){
		
		if ( $this->check_privileges('users_management_users_management') ){
			
			if ($this->check_privileges('users_management_can_see_only_same_and_low_group_level')){

				$childrens_users_groups = $this->get_users_groups_same_and_low_group_level();

			}
			else if ($this->check_privileges('users_management_can_see_only_low_group_level')){

				$childrens_users_groups = $this->get_users_groups_same_group_level();

			}

			$this->db->select('t1.*, t2.title as user_group, t2.parent as parent_user_group_id, t2.parent as parent_user_group');
			$this->db->from('tb_users t1');
			$this->db->join('tb_users_groups t2', 't1.group_id = t2.id', 'left');
			$this->db->order_by('name asc, id asc');

			if ($this->check_privileges('users_management_can_see_only_same_and_low_group_level')){

				if ($childrens_users_groups){
					foreach ($childrens_users_groups as $key => $value) {
						$this->db->or_like('t1.group_id', $key);
					}
				}

			}
			else if ($this->check_privileges('users_management_can_see_only_same_group_level')){

				$this->db->or_like('t2.parent', $this->user_data['parent_user_group_id']);

			}
			else if ($this->check_privileges('users_management_can_see_only_low_group_level')){

				$this->db->or_like('t1.id', $this->user_data['id']);
				if ($childrens_users_groups){
					foreach ($childrens_users_groups as $key => $value) {
						$this->db->or_like('t1.group_id', $key);
					}
				}

			}
			else if ($this->check_privileges('users_management_cant_see_others_users')){

				$this->db->limit(1);
				$this->db->where('t1.id', $this->user_data['id']);

			}

		}
		else{

			$this->db->select('t1.*, t2.title as user_group, t2.parent as parent_user_group_id, t2.parent as parent_user_group');
			$this->db->from('tb_users t1');
			$this->db->join('tb_users_groups t2', 't1.group_id = t2.id', 'left');
			$this->db->order_by('name asc, id asc');
			$this->db->limit(1);

			$this->db->where('t1.id', $this->user_data['id']);

		};

		return $this->db->get();

	}
	
	// --------------------------------------------------------------------
	
	public function get_user( $condition = NULL ){
		
		if ( ! is_array( $condition ) AND is_numeric( $condition ) ) {
			
			$condition = array( 't1.id' => $condition );
			
		}
		
		$this->db->select('
			
			t1.*,
			
			t2.title as user_group,
			t2.parent as parent_user_group_id,
			t2.params as user_group_params,
			t2.privileges,
			
		');
		$this->db->from('tb_users t1');
		$this->db->join('tb_users_groups t2', 't1.group_id = t2.id', 'left');
		if ($condition){
			$this->db->where( $condition );
		}
		// limitando o resultando em apenas 1
		$this->db->limit(1);
		
		return $this->db->get('tb_users');
		
	}
	
	// --------------------------------------------------------------------
	
	public function insert_user( $data = NULL ) {
		
		log_message( 'info', "[Users] Insert user function called" );
		
		if ( $data != NULL ) {
			
			if ( ! isset( $data[ 'created_datetime' ] ) ) {
				
				$data[ 'created_datetime' ] = ov_strftime( '%Y-%m-%d %T', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) );
				
			}
			
			if ( $this->db->insert( 'tb_users', $data ) ) {
				
				// confirm the insertion for controller
				return $this->db->insert_id();
				
			}
			else {
			
				// case the insertion fails, return false
				return FALSE;
				
			}
			
		}
		else {
			
			log_message( 'error', "[Users] " . lang( 'error_inserting_user_no_data' ) );
			
			msg( ( 'error_inserting_user_no_data' ), 'error' );
			
		}
	}
	
	// --------------------------------------------------------------------
	
	public function insert_user_respecting_privileges( $data = NULL ){

		if ( $this->check_privileges( 'users_management_can_add_user' ) ){

			$this->insert_user( $data );

		}
		else {

			redirect_last_url( array( 'title' => 'access_denied', 'type' => 'error', 'msg' => 'access_denied_users_management_can_add_user' ) );

		}

	}
	
	// --------------------------------------------------------------------
	
	public function update_user( $data = NULL, $condition = NULL ){
		
		if ( $data != NULL && $condition != NULL ){
			
			if ( $this->db->update( 'tb_users', $data, $condition ) ){
				
				// confirm update to controller
				return TRUE;
				
			}
			else {
				
				// case update fails, return false
				return FALSE;
				
			}
			
		}
		
		redirect('admin');
		
	}
	
	// --------------------------------------------------------------------
	
	public function delete_user($condition = NULL){
		if ($condition != null){
			if ($this->db->delete('tb_users',$condition)){
				// confirm delete for controller
				return TRUE;
			}
			else {
				// case delete fails, return false
				return FALSE;
			}
		}
		redirect();
	}
	
	// --------------------------------------------------------------------
	
	public function get_user_params(){

		$components = $this->mcm->components;

		$params = array();

		foreach ( $components as $key => $component ) {

			if ( file_exists( APPPATH . 'controllers/admin/com_' . $component[ 'unique_name' ] . '/user_preferences.xml') ) {

				$params = array_merge_recursive_distinct( get_params_spec_from_xml( APPPATH . 'controllers/admin/com_' . $component[ 'unique_name' ] . '/user_preferences.xml' ), $params );

			}

		}

		// idiomas
		$languages_list = dir_list_to_array( 'application/language' );

		foreach ( $params['params_spec']['user_preferences'] as $key => $element ) {

			if ( $element['name'] == 'admin_language' OR $element['name'] == 'site_language' ){

				$spec_options = array();

				if ( isset( $params['params_spec']['user_preferences'][$key]['options'] ) )
					$spec_options = $params['params_spec']['user_preferences'][$key]['options'];

				$params['params_spec']['user_preferences'][$key]['options'] = is_array( $spec_options ) ? $spec_options + $languages_list : $languages_list;

			}

		}

		// editores de texto
		$this->plugins->load( NULL, 'js_text_editor' );
		foreach ( $this->plugins->get_plugins( 'js_text_editor' ) as $key => $plugin ) {

			if ( $plugin['status'] == 1 ){

				$js_text_editors_list[ $plugin['name'] ] = lang( $plugin['title'] );

			}

		}

		foreach ( $params['params_spec']['user_preferences'] as $key => $element ) {

			if ( $element['name'] == 'js_text_editor' OR $element['name'] == 'js_text_editor' ){

				$spec_options = array();

				if ( isset( $params['params_spec']['user_preferences'][$key]['options'] ) )
					$spec_options = $params['params_spec']['user_preferences'][$key]['options'];

				$params['params_spec']['user_preferences'][$key]['options'] = is_array( $spec_options ) ? $spec_options + $js_text_editors_list : $js_text_editors_list;

			}

		}

		return $params;
	}
	
	// --------------------------------------------------------------------
	// --------------------------------------------------------------------
	// --------------------------------------------------------------------
	
	public function get_users_groups_query( $condition = NULL, $limit = NULL, $offset = NULL, $order_by = 'title asc, id asc' ){

		// Caso a query dos users groups esteja vazia, obtem agora
		if ( $this->users_groups_query === NULL ){
			$this->db->select('t1.*, t2.title as parent_title, t2.alias as parent_alias');
			$this->db->from('tb_users_groups t1');
			$this->db->join('tb_users_groups t2', 't1.parent = t2.id', 'left');

			if ($condition){
				$this->db->where($condition);
			}
			if ($order_by){
				$this->db->order_by($order_by);
			}


			if ($this->users_groups_query = $this->db->get( NULL, $limit, $offset) ){
				return TRUE;
			}
			else {
				$this->users_groups_query = NULL;
				return FALSE;
			}
		}
		else {
			return TRUE;
		}
	}
	
	// --------------------------------------------------------------------
	
	// Essa função sempre gera o html em formato de menu, sempre com um </li> no final,
	// mas no retorno da função get_menu_tree ele é retirado
	public function get_children_as_menu($parent_id, $level) {

		// pega todos os filhos de $parent_id
		$this->db->where('parent = '.$parent_id);
		$this->db->order_by('ordering asc, title asc, id asc');
		$query = $this->db->get('tb_articles_categories');

		// variável que determina se o item atual possui filhos
		$has_children = $this->get_num_childrens($parent_id);

		// se o item atual possui filhos, insere a primeira <ul>
		if ($has_children) $this->categories_tree .= "<ul>\n";

		// display each child
		foreach($query->result() as $row) {

			// verifica se este filho possui outros filhos
			$next_has_children = $this->get_num_childrens($row->id)?TRUE:FALSE;

			// indent and display the title of this child
			$this->categories_tree .= '<li class="level-'.$level.'">'.$row->title.($next_has_children?"\n":'</li>'."\n");

			// chama esta função novamente para mostrar os filhos deste filho
			$this->get_children_as_menu($row->id, $level+1);

		}
		if ($has_children) $this->categories_tree .= $next_has_children?"</ul>\n":"</ul>\n</li>\n";

	}
	
	// --------------------------------------------------------------------
	
	// Essa função sempre gera uma lista para comboboxes em formato de array
	// O parâmetro $query serve para economizar consultas ao bando de dados, caso a função tenha que se "auto chamar" novamente
	public function get_children_as_list( $parent_id, $level, $query = NULL ) {

		if ( ! $query ){
			$this->get_users_groups_query();
			$query = $this->users_groups_query->result_array();
		}

		// atribui todos os filhos para a variável $menu_tree
		foreach($query as $row) {
			if ($row['parent'] == $parent_id) {
				// atribui cada valor da categoria em um array
				$this->users_groups_tree[$row['id']] = array(
					'id' => $row['id'],
					'alias' => $row['alias'],
					'title' => $row['title'],
					'parent' => $row['parent'],
					'parent_alias' => $row['parent_alias'],
					'parent_title' => $row['parent_title'],
					'indented_title' => str_repeat( '&nbsp;&nbsp;&nbsp;&nbsp;', $level ) . lang( 'indented_symbol' ) . ' '.$row['title'],
					'description' => $row['description'],
					'level' => $level,
				);

				// chama esta função novamente para mostrar os filhos deste filho
				$this->get_children_as_list($row['id'], $level+1, $query);
			}
		}

	}
	
	// --------------------------------------------------------------------
	
	public function get_accessible_users_groups( $user_id ) {

		$this->get_users_groups_query();

		if ( $this->check_privileges( 'users_management_can_edit_only_same_and_low_group_level' ) ){

			$accessible_groups = $this->get_users_groups_same_and_low_group_level();

		}
		else if ($this->check_privileges('users_management_can_edit_only_same_group_level')){

			$accessible_groups = $this->get_users_groups_same_group_level();

		}
		else if ($this->check_privileges('users_management_can_edit_only_same_group_and_below')){

			$accessible_groups = $this->get_users_groups_same_group_and_below();

		}
		else if ($this->check_privileges('users_management_can_edit_only_same_group')){

			$accessible_groups = $this->get_users_groups_same_group();

		}
		else if ($this->check_privileges('users_management_can_edit_only_low_groups')){

			$accessible_groups = $this->get_users_groups_low_groups( $this->user_data );

		}
		else if ( $this->check_privileges( 'users_management_can_edit_only_your_own_user' ) ){

			if ( $this->user_data[ 'id' ] == $user_id ){

				$accessible_groups = $this->get_users_groups_as_list_childrens_hidden( $this->user_data[ 'parent_user_group_id' ], $this->user_data[ 'group_id' ] );

				foreach ( $accessible_groups as $key => $value ) {

					if ( $value[ 'parent' ] != $this->user_data[ 'parent_user_group_id' ] ){

						unset( $accessible_groups[ $key ] );

					}

				}

				foreach ( $accessible_groups as $key => $value ) {

					if ( $value[ 'parent' ] == $this->user_data[ 'parent_user_group_id' ] AND $value[ 'id' ] != $this->user_data[ 'group_id' ] ){

						unset( $accessible_groups[ $key ] );

					}

				}

			}

			else{

				$accessible_groups = FALSE;

			}

		}
		else {

			$accessible_groups = $this->users_groups_query->result_array();

		}

		return $accessible_groups;

	}
	
	// --------------------------------------------------------------------
	
	// verifica se o usuário está em um grupo de mesmo nível ou abaixo do atual usuário
	public function check_if_user_is_on_same_and_low_group_level( $user_id ){
		
		$this->get_users_groups_query();
		$this->get_users_query();
		
		$user = $this->get_user( array( 't1.id' => $user_id ) )->row_array();
		
		if ( $user ) {
			
			$current_user_group_id = $this->user_data[ 'group_id' ];
			$user_group_id = $user[ 'group_id' ];
			
			$above_groups = $this->get_user_group_path( $user_group_id );
			
			$output = FALSE;
			
			foreach( $above_groups as $row ) {
				
				if ( $this->user_data['parent_user_group_id'] == $row[ 'parent' ] OR $this->user_data[ 'id' ] == $row[ 'parent' ] OR $this->user_data[ 'group_id' ] == $row[ 'id' ] ) {
					
					$output = TRUE;
					
				}
				
			}
			
			return $output;
			
		}
		
		return FALSE;
		
	}
	
	// --------------------------------------------------------------------
	
	// verifica se o usuário está em um grupo de mesmo nível do atual usuário
	public function check_if_user_is_on_same_group_level($user_id){

		$this->get_users_groups_query();
		$this->get_users_query();

		$user = $this->get_user(array('t1.id' => $user_id))->row();

		$user_group_id = $user->group_id;
		$current_user_group_id = $this->user_data['group_id'];

		$above_groups = $this->get_user_group_path($user_group_id);

		$output = FALSE;
		if ( $this->user_data['parent_user_group_id'] == $user->parent_user_group_id ) {
			$output = TRUE;
		}

		return $output;
	}
	
	// --------------------------------------------------------------------
	
	// verifica se o usuário está no mesmo grupo do usuário atual ou em um grupo abaixo
	public function check_if_user_is_on_same_group_and_below($user_id){

		$this->get_users_groups_query();
		$this->get_users_query();

		$user = $this->get_user(array('t1.id' => $user_id))->row();

		$user_group_id = $user->group_id;
		$current_user_group_id = $this->user_data['group_id'];

		$above_groups = $this->get_user_group_path($user_group_id);

		$output = FALSE;
		foreach($above_groups as $row) {
			if ( $this->user_data['group_id'] == $row['id'] OR $this->user_data['group_id'] == $row['parent'] ) {
				$output = TRUE;
			}
		}
		return $output;
	}
	
	// --------------------------------------------------------------------
	
	// verifica se o usuário está no mesmo grupo do usuário atual
	public function check_if_user_is_on_same_group($user_id){

		$this->get_users_groups_query();
		$this->get_users_query();

		$user = $this->get_user(array('t1.id' => $user_id))->row_array();

		$output = FALSE;
		if ( $this->user_data['group_id'] == $user['group_id'] ) {
			$output = TRUE;
		}
		return $output;
	}
	
	// --------------------------------------------------------------------
	
	// verifica se o usuário está em grupos abaixo do usuário atual
	public function check_if_user_is_on_below_groups($user_id){

		$this->get_users_groups_query();
		$this->get_users_query();

		$user = $this->get_user(array('t1.id' => $user_id))->row();

		$user_group_id = $user->group_id;
		$current_user_group_id = $this->user_data['group_id'];

		$above_groups = $this->get_user_group_path($user_group_id);

		$output = FALSE;
		foreach($above_groups as $row) {
			if ( $this->user_data['group_id'] == $row['parent'] ) {
				$output = TRUE;
			}
		}
		return $output;
	}
	
	// --------------------------------------------------------------------
	
	// Função que retorna o número de filhos de um item
	public function get_num_childrens($id){

		$this->db->where('parent = '.$id);
		$this->db->order_by('title asc, id asc');
		$this->db->from('tb_users_groups');
		// retorna a quantidade de registros com item pai $id
		return $this->db->count_all_results();

	}
	
	// --------------------------------------------------------------------
	
	public function get_users_groups_tree($parent_id, $level, $type = 'menu'){

		if ($type == 'menu'){
			$this->get_children_as_menu($parent_id, $level);
			// remove o último </li> da string
			return substr_replace($this->users_groups_tree,'',-6);
		}
		else if ($type == 'list'){
			$this->get_children_as_list($parent_id, $level);
			return $this->users_groups_tree;
		}
		else {
			return FALSE;
		}

	}
	
	// --------------------------------------------------------------------
	
	public function get_user_group_path($user_group_id, $parent_limit = 0){

		$this->get_users_groups_query();

		if ($this->users_groups_query){

			$path = array();

			foreach($this->users_groups_query->result_array() as $row) {
				if ($row['id'] == $user_group_id AND $row['id'] != $parent_limit) {

					$path[] = array(
						'id' => $row['id'],
						'parent' => $row['parent'],
						'title' => $row['title'],
					);

					$path = array_merge_recursive_distinct($this->get_user_group_path($row['parent'], $parent_limit), $path);
				}
			}

			// return the path
			return $path;

		}
		else{
			return FALSE;
		}

	}
	
	// --------------------------------------------------------------------
	
	public function get_users_groups_as_list_childrens_hidden($root_parent = 0, $parent_to_hide = NULL){

		$this->get_users_groups_query();

		if ($this->users_groups_query AND $parent_to_hide){

			// obtendo o array completo dos grupos
			$this->get_children_as_list($root_parent,0,$this->users_groups_query->result_array());
			$users_groups = $this->users_groups_tree;

			// zerando a variável users_groups_tree,
			// que continha o array completo dos grupos
			$this->users_groups_tree = array();

			// agora obtendo o array dos grupos, com raiz $parent_to_hide
			$this->get_children_as_list($parent_to_hide,0,$this->users_groups_query->result_array());
			$users_groups_to_hide = $this->users_groups_tree;

			foreach ($users_groups_to_hide as $key => $value) {
				if (array_key_exists($key,$users_groups)){
					unset($users_groups[$key]);
				}
			}

			return $users_groups;
		}

	}
	
	// --------------------------------------------------------------------
	
	// obtém os grupos de mesmo nível e abaixo
	public function get_users_groups_same_and_low_group_level(){

		$this->get_users_groups_query();
		$this->get_children_as_list( $this->user_data['parent_user_group_id'], 0, $this->users_groups_query->result_array() );
		return $this->users_groups_tree;

	}
	
	// --------------------------------------------------------------------
	
	// grupos de usuários de mesmo nível
	public function get_users_groups_same_group_level(){

		$users_groups = $this->get_users_groups_as_list_childrens_hidden( $this->user_data['parent_user_group_id'], $this->user_data['group_id'] );

		foreach ( $users_groups as $key => $value ) {

			if ( $value['parent'] != $this->user_data['parent_user_group_id'] ){

				unset( $users_groups[$key] );

			}

		}

		return $users_groups;

	}
	
	// --------------------------------------------------------------------
	
	// grupos de usuários de mesmo grupo e abaixo
	public function get_users_groups_same_group_and_below(){

		$users_groups = $this->get_users_groups_as_list_childrens_hidden( $this->user_data['parent_user_group_id'], $this->user_data['group_id'] );

		foreach ( $users_groups as $key => $value ) {

			if ($value['parent'] != $this->user_data['parent_user_group_id']){
				unset( $users_groups[$key] );
			}

		}

		foreach ( $users_groups as $key => $value ) {

			if ( $value['parent'] == $this->user_data['parent_user_group_id'] AND $value['id'] != $this->user_data['group_id'] ){
				unset( $users_groups[$key] );
			}

		}

		$this->get_children_as_list( $this->user_data['group_id'], 0, $users_groups );

		$users_groups = array_merge_recursive_distinct( $users_groups, $this->users_groups_tree );

		// zerando a variável users_groups_tree
		$this->users_groups_tree = array();

		// chamando a função get_children_as_list() para reformatar a lista de grupos
		$this->get_children_as_list( $this->user_data['parent_user_group_id'], 0, $users_groups );

		return $this->users_groups_tree;

	}
	
	// --------------------------------------------------------------------
	
	// grupo de usuário do usuário atual
	public function get_users_groups_same_group(){

		$users_groups = $this->get_users_groups_as_list_childrens_hidden( $this->user_data['parent_user_group_id'], $this->user_data['group_id'] );

		foreach ( $users_groups as $key => $value ) {

			if ( $value['id'] == $this->user_data['group_id'] ){

				$users_groups = array(
					0 => $value,
				);
				break;
			}

		}

		return $users_groups;

	}
	
	// --------------------------------------------------------------------
	
	// grupos de usuários abaixo do grupo atual
	public function get_users_groups_low_groups( $user = NULL ){

		$user = isset( $user ) ? $user : ( $this->is_logged_in() ? $this->user_data : NULL );

		if ( $user ) {

			$users_groups = array();
			/*
			if ( $user['id'] == $user['id'] ){

				$users_groups = $this->get_users_groups_as_list_childrens_hidden( $user['parent_user_group_id'], $user['group_id'] );

				foreach ( $users_groups as $key => $value ) {
					if ($value['parent'] != $this->user_data['parent_user_group_id']){
						unset( $users_groups[$key] );
					}
				}
				foreach ( $users_groups as $key => $value ) {
					if ($value['parent'] == $user['parent_user_group_id'] AND $value['id'] != $user['group_id']){
						unset( $users_groups[$key] );
					}
				}
				$this->get_children_as_list( $user['group_id'], 0, $users_groups );
				$users_groups = array_merge_recursive( $users_groups, $this->users_groups_tree );

				// zerando a variável users_groups_tree
				$this->users_groups_tree = array();

				// chamando a função get_children_as_list() para reformatar a lista de grupos
				$this->get_children_as_list( $this->user_data['parent_user_group_id'], 0, $users_groups ) ;
				$users_groups = $this->users_groups_tree;

			}
			else {

				$users_groups = array_merge_recursive( $users_groups, $this->get_users_groups_tree( $user['group_id'], 0, 'list' ) );

			}
			*/
			$users_groups = array_merge_recursive_distinct( $users_groups, $this->get_users_groups_tree( $user['group_id'], 0, 'list' ) );

			return $users_groups;

		}

	}
	
	// --------------------------------------------------------------------
	
	public function get_user_group( $condition = NULL, $output = 'result' ){
		if ($condition){
			$this->db->select('t1.*, t2.title as parent_title, t2.alias as parent_alias');
			$this->db->from('tb_users_groups t1');
			$this->db->join('tb_users_groups t2', 't1.parent = t2.id', 'left');
			$this->db->where($condition);
			// limitando o resultando em apenas 1
			$this->db->limit(1);
			
			if ( $return = $this->db->get() ) {
				
				if ( $output === 'result_array' ) {
					
					$return = $return->result_array();
					
				}
				else if ( $output === 'row_array' ) {
					
					$return = $return->row_array();
					
				}
				else if ( $output === 'row' ) {
					
					$return = $return->row();
					
				}
				else if ( $output === 'result' ) {
					
					$return = $return->result();
					
				}
				
			}
			else {
				
				$return = NULL;
				
			}
			
			return $return;
		}
		else {
			return false;
		}
	}
	
	// --------------------------------------------------------------------
	
	public function get_users_groups($condition = NULL){

		$this->db->select('t1.*, t2.title as parent_title, t2.alias as parent_alias');
		$this->db->from('tb_users_groups t1');
		$this->db->join('tb_users_groups t2', 't1.parent = t2.id', 'left');
		$this->db->order_by('title asc, id asc');
		if ($condition){
			$this->db->where($condition);
		}
		return $this->db->get();

	}
	
	// --------------------------------------------------------------------
	
	public function insert_user_group($data = NULL){
		if ($data != NULL){
			if ($this->db->insert('tb_users_groups',$data)){
				// confirm the insertion for controller
				return $this->db->insert_id();
			}
			else {
				// case the insertion fails, return false
				return FALSE;
			}
		}
		else {
			redirect('admin');
		}
	}
	
	// --------------------------------------------------------------------
	
	public function update_user_group($data = NULL,$condition = NULL){
		if ($data != NULL && $condition != NULL){
			if ($this->db->update('tb_users_groups',$data,$condition)){
				// confirm update for controller
				return TRUE;
			}
			else {
				// case update fails, return false
				return FALSE;
			}
		}
		redirect('admin');
	}
	
	// --------------------------------------------------------------------
	
	public function delete_users_group($condition = NULL){
		if ($condition != null){
			if ($this->db->delete('tb_users_groups',$condition)){
				// confirm delete for controller
				return TRUE;
			}
			else {
				// case delete fails, return false
				return FALSE;
			}
		}
		redirect();
	}
	
	// --------------------------------------------------------------------
	
	public function is_logged_in(){

		return ( $this->session->envdata( 'login' ) AND $this->session->envdata( 'user' ) ) ? TRUE : FALSE;

	}
	
	// --------------------------------------------------------------------
	
	public function _check_privileges( $privilege = 'admin_access', $user ){
		
		if ( check_var( $user[ 'privileges' ][ 'privileges' ] ) AND is_array( $user[ 'privileges' ][ 'privileges' ] ) AND in_array( $privilege, $user[ 'privileges' ][ 'privileges' ] ) ){
			
			return TRUE;
			
		}
		
		if ( check_var( $user[ 'privileges' ] ) AND is_array( $user[ 'privileges' ] ) AND in_array( $privilege, $user[ 'privileges' ] ) ){
			
			return TRUE;
			
		}
		
		return FALSE;
		
	}
	
	// --------------------------------------------------------------------
	
	public function check_privileges( $privilege = 'admin_access' ){
		
		if ( $this->mcm->environment != SITE_ALIAS AND ! $this->is_logged_in() ){
			
			redirect( 'admin/main/index/login' );
			
			return FALSE;
			
		}
		else{
			
			return $this->_check_privileges( $privilege, $this->user_data );
			
		}
		
		return FALSE;
		
	}
	
	// --------------------------------------------------------------------
	
	/* Component privileges */
	public function get_component_privileges(){

		$privileges = get_params_spec_from_xml(APPPATH.'controllers/admin/com_users/privileges.xml');

		return $privileges;
	}
	
	// --------------------------------------------------------------------
	
	/* General params */
	public function get_general_params(){

		$params = get_params_spec_from_xml(APPPATH.'controllers/admin/com_articles/general_params.xml');

		$layouts_articles_list = dir_list_to_array('application/views/site/'.$this->mcm->filtered_system_params[ 'site_name' ].'/components/articles/articles_list');
		$params['params']['articles_list']['layout_articles_list'] = array(
			'title' => 'layout',
			'name' => 'layout_articles_list',
			'description' => 'tip_layout_articles_list',
			'type' => 'combobox',
			'default' => 'default',
			'options' => $layouts_articles_list,
		);
		$layouts_article_detail = dir_list_to_array('application/views/site/'.$this->mcm->filtered_system_params[ 'site_name' ].'/components/articles/article_detail');
		$params['params']['detail_view']['layout_article_detail'] = array(
			'title' => 'layout',
			'name' => 'layout_article_detail',
			'description' => 'tip_layout_article_detail',
			'type' => 'combobox',
			'default' => 'default',
			'options' => $layouts_article_detail,
		);

		// print_r($params);

		return $params;
	}
	
	// --------------------------------------------------------------------
	
	public function get_users_groups_privileges(){

		$components = $this->main_model->get_components(array('status'=>1))->result();

		$params = array();

		foreach ( $components as $key => $component ) {
			
			if (file_exists(APPPATH.'controllers/admin/com_'.$component->unique_name.'/privileges.xml')) {
				
				$_params = get_params_spec_from_xml( APPPATH.'controllers/admin/com_'.$component->unique_name.'/privileges.xml' );
				
				$params = array_merge_recursive( $_params, $params );
				
			}
			
		}
		
		return $params;
	}
	
	// --------------------------------------------------------------------
	
	public function menu_item_login_page(){

		$params = get_params_spec_from_xml(APPPATH.'controllers/admin/com_users/login_page.xml');

		$layouts_login = dir_list_to_array('application/'.VIEWS_DIR_NAME.'/site/'.$this->mcm->filtered_system_params[ 'site_name' ].'/views/components/users/index/login');
		$params['params']['login_page']['layout_login'] = array(
			'title' => 'layout',
			'name' => 'layout_login',
			'description' => 'tip_layout_login',
			'type' => 'combobox',
			'default' => 'default',
			'options' => $layouts_login,
		);
		$layouts_logout = dir_list_to_array('application/'.VIEWS_DIR_NAME.'/site/'.$this->mcm->filtered_system_params[ 'site_name' ].'/views/components/users/index/logout');
		$params['params']['logout_page']['layout_logout'] = array(
			'title' => 'layout',
			'name' => 'layout_logout',
			'description' => 'tip_layout_logout',
			'type' => 'combobox',
			'default' => 'default',
			'options' => $layouts_logout,
		);
		return $params;
	}
	
	// --------------------------------------------------------------------
	
	public function menu_item_get_link_login_page( $menu_item_id = 0, $params = NULL ){
		
		if ( trim( $menu_item_id ) == '' ) {
			
			$menu_item_id = 0;
			
		}
		
		return 'users/index/miid/' . $menu_item_id . '/a/login';
		
	}
	
	// --------------------------------------------------------------------
	
	public function get_link_logout_page( $menu_item_id = 0, $params = NULL ){
		
		if ( trim( $menu_item_id ) == '' ) {
			
			$menu_item_id = 0;
			
		}
		
		return 'users/index/miid/' . $menu_item_id . '/a/logout';
		
	}
	
	// --------------------------------------------------------------------
	
	public function get_link_login_page( $menu_item_id = 0 ){
		
		if ( trim( $menu_item_id ) == '' ) {
			
			$menu_item_id = 0;
			
		}
		
		return 'users/index/miid/' . $menu_item_id . '/a/login';
		
	}
	
	// --------------------------------------------------------------------
	
	public function get_link_register_page( $menu_item_id = 0 ){
		
		if ( trim( $menu_item_id ) == '' ) {
			
			$menu_item_id = 0;
			
		}
		
		return 'users/index/miid/' . $menu_item_id . '/a/register';
		
	}
	
	// --------------------------------------------------------------------
	
	public function get_link_resend_acode_page( $menu_item_id = 0 ){
		
		if ( trim( $menu_item_id ) == '' ) {
			
			$menu_item_id = 0;
			
		}
		
		return 'users/index/miid/' . $menu_item_id . '/a/resend_acode';
		
	}
	
	// --------------------------------------------------------------------
	
	public function get_link_get_cplink_page( $menu_item_id = 0 ){
		
		if ( trim( $menu_item_id ) == '' ) {
			
			$menu_item_id = 0;
			
		}
		
		return 'users/index/miid/' . $menu_item_id . '/a/get_cplink';
		
	}
	// --------------------------------------------------------------------
	
	public function get_link_change_pass_page( $menu_item_id = 0, $cpcode = NULL ){
		
		if ( trim( $menu_item_id ) == '' ) {
			
			$menu_item_id = 0;
			
		}
		
		if ( ! $cpcode OR trim( $cpcode ) == '' ) {
			
			$cpcode = '';
			
		}
		else {
			
			$cpcode = '/cpssc/' . $cpcode;
			
		}
		
		return 'users/index/miid/' . $menu_item_id . '/a/change_pass' . $cpcode;
		
	}
	
	// --------------------------------------------------------------------
	
	public function get_link_recover_username_page( $menu_item_id = '0' ){
		
		if ( trim( $menu_item_id ) == '' ) {
			
			$menu_item_id = '0';
			
		}
		
		return 'users/index/miid/' . $menu_item_id . '/a/recover_username';
		
	}
	
	// --------------------------------------------------------------------
	
	public function get_link_email_recover_page( $menu_item_id = '0' ){
		
		if ( trim( $menu_item_id ) == '' ) {
			
			$menu_item_id = '0';
			
		}
		
		return 'users/index/miid/' . $menu_item_id . '/a/email_recover';
		
	}
	
	// --------------------------------------------------------------------
	
	public function get_link_activate_account_page( $menu_item_id = '0', $acode = NULL ){
		
		if ( trim( $menu_item_id ) == '' ) {
			
			$menu_item_id = '0';
			
		}
		
		if ( ! $acode OR trim( $acode ) == '' ) {
			
			$acode = '';
			
		}
		else {
			
			$acode = '/ac/' . $acode;
			
		}
		
		return 'users/index/miid/' . $menu_item_id . '/a/activate_account' . $acode;
		
	}
	
	// --------------------------------------------------------------------
	
	public function admin_get_link_edit( $user_id ){
		
		$enc_user_id = $this->users->encode_user_id( $user_id );
		
		return 'admin/users/um/a/e/uid/' . $enc_user_id;
		
	}
	
	// --------------------------------------------------------------------
	
	public function admin_get_link_remove( $user_id ){
		
		$enc_user_id = $this->users->encode_user_id( $user_id );
		
		return 'admin/users/um/a/r/uid/' . $enc_user_id;
		
	}
	
	// --------------------------------------------------------------------
	
	public function admin_get_link_enable( $user_id ){
		
		$enc_user_id = $this->users->encode_user_id( $user_id );
		
		return 'admin/users/um/a/ena/uid/' . $enc_user_id;
		
	}
	
	// --------------------------------------------------------------------
	
	public function admin_get_link_disable( $user_id ){
		
		$enc_user_id = $this->users->encode_user_id( $user_id );
		
		return 'admin/users/um/a/dis/uid/' . $enc_user_id;
		
	}
	
	// --------------------------------------------------------------------
	
	public function encode_password( $pass ){
		
		return base64_encode( md5( $pass ) );
		
	}
	
	// --------------------------------------------------------------------
	
	public function make_tmp_code(){
		
		return md5( uniqid( rand(), TRUE ) );
		
	}
	
	// --------------------------------------------------------------------
	
	public function insert_tmp_code( $db_data ){
		
		if ( $this->db->insert( 'tb_users_acodes', $db_data ) ) {
			
			// confirm the insertion for controller
			return $this->db->insert_id();
			
		}
		else {
			
			// case the insertion fails, return false
			return FALSE;
			
		}
		
	}
	
	// --------------------------------------------------------------------
	
	public function remove_tmp_code( $condition ) {
		
		if ( $this->db->delete( 'tb_users_acodes', $condition ) ){
			
			// confirm delete for controller
			return TRUE;
			
		}
		else {
			
			// case delete fails, return false
			return FALSE;
			
		}
		redirect();
	}
	
	// --------------------------------------------------------------------
	
	public function clear_expired_tmp_codes() {
		
		$this->db->query( "DELETE FROM tb_users_acodes WHERE validity_datetime < '" . ov_strftime( '%Y-%m-%d %T', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) ) . "'" );	
		
	}
	
	// --------------------------------------------------------------------
	
	public function validate_tmp_code( $acode ) {
		
		$this->clear_expired_tmp_codes();
		
		$condition = array( 't1.code' => $acode );
		
		$this->db->select('
			
			t1.*
			
		');
		
		$this->db->from( 'tb_users_acodes t1' );
		$this->db->where( $condition );
		$this->db->limit( 1 );
		
		if ( $acode = $this->db->get()->row_array() ) {
			
			$this->remove_tmp_code( array( 'user_id' => $acode[ 'user_id' ] ) );
			
			if ( $user = $this->get_user( $acode[ 'user_id' ] ) ) {
				
				$db_data[ 'status' ] = 1;
				
				$acode[ 'user' ] = $user;
				
				if ( $this->update_user( $db_data, array( 'id' => $acode[ 'user_id' ] ) ) ) {
					
					return $acode;
					
				}
				
			}
			
		}
		
		return FALSE;
		
	}
	
	// --------------------------------------------------------------------
	
	// same as validate_tmp_code(), but don't remove from DB 
	public function tmp_code_is_valid( $code ) {
		
		$this->clear_expired_tmp_codes();
		
		$condition = array( 't1.code' => $code );
		
		$this->db->select('
			
			t1.*
			
		');
		
		$this->db->from( 'tb_users_acodes t1' );
		$this->db->where( $condition );
		$this->db->limit( 1 );
		
		if ( $code = $this->db->get()->row_array() ) {
			
			if ( $user = $this->get_user( $code[ 'user_id' ] ) ) {
				
				$user = $user->row_array();
				
				$code[ 'user' ] = $user;
				
				$db_data[ 'status' ] = 1;
				
				if ( $this->update_user( $db_data, array( 'id' => $code[ 'user_id' ] ) ) ) {
					
					return $code;
					
				}
				
			}
			
		}
		
		return FALSE;
		
	}
	
	// --------------------------------------------------------------------
	
	public function send_acode( $user_id, $notif = FALSE ){
		
		if ( $this->mcm->load_email_system() ) {
			
			// -------------------------------------------------
			// Parsing vars
			
			if ( is_array( $user_id ) ) {
				
				$f_params = $user_id;
				
				$user_id =								isset( $f_params[ 'user_id' ] ) ? $f_params[ 'user_id' ] : NULL; // user id
				$subject =								isset( $f_params[ 'subject' ] ) ? $f_params[ 'subject' ] : lang( 'email_c_users_your_acode_subject_string' ); // subject
				$email_body =							isset( $f_params[ 'email_body' ] ) ? $f_params[ 'email_body' ] : lang( 'email_c_users_your_acode_body_string' ); // email_body
				$notif =								isset( $f_params[ 'notif' ] ) ? $f_params[ 'notif' ] : NULL; // notifications
				
			}
			else {
				
				$subject =								lang( 'email_c_users_your_acode_subject_string' ); // subject
				$email_body =							lang( 'email_c_users_your_acode_body_string' ); // email_body
				
			}
			
			// Parsing vars
			// -------------------------------------------------
			
			$this->clear_expired_tmp_codes();
			
			if ( ! ( $user = $this->get_user( $user_id )->row_array() ) ) {
				
				if ( $notif ) {
					
					msg( ( 'notif_c_users_send_acode_invalid_user_error' ), 'error' );
					
				}
				
				log_message( 'error', "[Users] " . lang( 'notif_c_users_send_acode_invalid_user_error' ) );
				
				return FALSE;
				
			}
			
			if ( $user[ 'status' ] == 1 ) {
				
				if ( $notif ) {
					
					msg( ( 'notif_c_users_account_already_active_error' ), 'error' );
					
				}
				
				log_message( 'error', "[Users] " . lang( 'notif_c_users_account_already_active_error' ) );
				
				return FALSE;
				
			}
			
			$user[ 'params' ] = get_params( $user[ 'params' ] );
			
			$db_data = array(
				
				'code' => $this->make_tmp_code(),
				'user_id' => $user_id,
				
			);
			
			$created_datetime = gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] );
			$created_datetime = ov_strftime( '%Y-%m-%d %T', $created_datetime );
			
			$db_data[ 'created_datetime' ] = $created_datetime;
			
			$date = strtotime( "+" . 1 . " days", strtotime( $created_datetime ) );
			$db_data[ 'validity_datetime' ] =  date( "Y-m-d", $date );
			
			if ( ! $this->insert_tmp_code( $db_data ) ) {
				
				if ( $notif ) {
					
					msg( ( 'notif_c_users_insert_tmp_code_error' ), 'error' );
					
				}
				
				log_message( 'error', "[Users] " . lang( 'notif_c_users_insert_tmp_code_error' ) );
				
				return FALSE;
				
			}
			
			$this->email->from( $this->mcm->system_params[ 'email_config_smtp_user' ], $this->mcm->system_params[ 'site_name' ] );
			$this->email->reply_to( $this->mcm->system_params[ 'email_config_smtp_user' ] );
			$this->email->to( $user[ 'email' ] );
	// 			$this->email->cc( $emails_cc);
	// 			$this->email->bcc( $emails_bcc);
			$this->email->subject( sprintf(
				
				$subject,
				/*
					1° name
					2° username
					3° e-mail
					4° login page link
					5° change pass page link
					6° recover email page link
					7° recover username page link
					8° resend activation code page link
					9° send change pass link page link
					10° activate account page link
					11° activate account page link with code
					12° site name
					13° base url
					14° code
					
				*/
				$user[ 'name' ],
				$user[ 'username' ],
				$user[ 'email' ],
				get_url( $this->get_link_login_page() ),
				get_url( $this->get_link_change_pass_page( NULL, $db_data[ 'code' ] ) ),
				get_url( $this->get_link_email_recover_page() ),
				get_url( $this->get_link_recover_username_page() ),
				get_url( $this->get_link_resend_acode_page() ),
				get_url( $this->get_link_get_cplink_page() ),
				get_url( $this->get_link_activate_account_page() ),
				get_url( $this->get_link_activate_account_page( NULL, $db_data[ 'code' ] ) ),
				$this->mcm->filtered_system_params[ 'site_name' ],
				base_url(),
				$db_data[ 'code' ]
				
			));
			
			// -------------------------
			
			$theme_load_views_path = call_user_func( $this->mcm->environment . '_theme_components_views_path' ) . 'users' . DS . 'email' . DS . 'send_acode' . DS . 'default' . DS;
			$theme_views_path = THEMES_PATH . $theme_load_views_path;
			
			$default_load_views_path = get_constant_name( $this->mcm->environment . '_COMPONENTS_VIEWS_PATH' ) . 'users' . DS . 'email' . DS . 'send_acode' . DS . 'default' . DS;
			$default_views_path = VIEWS_PATH . $default_load_views_path;
			
			$data[ 'user' ] = $user;
			$data[ 'code' ] = $db_data[ 'code' ];
			
			if ( file_exists( $theme_views_path . 'email.php' ) ){
				
				$email_body = $this->load->view( $theme_load_views_path . 'email', $data, TRUE );
				
			}
			else if ( file_exists( $default_views_path . 'email.php') ){
				
				$email_body = $this->load->view( $default_load_views_path . 'email', $data, TRUE );
				
			}
			
			$email_body = sprintf(
				
				$email_body,
				/*
					1° name
					2° username
					3° e-mail
					4° login page link
					5° change pass page link
					6° recover email page link
					7° recover username page link
					8° resend activation code page link
					9° send change pass link page link
					10° activate account page link
					11° activate account page link with code
					12° site name
					13° base url
					14° code
					
				*/
				$user[ 'name' ],
				$user[ 'username' ],
				$user[ 'email' ],
				get_url( $this->get_link_login_page() ),
				get_url( $this->get_link_change_pass_page( NULL, $db_data[ 'code' ] ) ),
				get_url( $this->get_link_email_recover_page() ),
				get_url( $this->get_link_recover_username_page() ),
				get_url( $this->get_link_resend_acode_page() ),
				get_url( $this->get_link_get_cplink_page() ),
				get_url( $this->get_link_activate_account_page() ),
				get_url( $this->get_link_activate_account_page( NULL, $db_data[ 'code' ] ) ),
				$this->mcm->filtered_system_params[ 'site_name' ],
				base_url(),
				$db_data[ 'code' ]
				
			);
			
			$this->email->message( $email_body );
			
// 			echo print_r( $email_body, TRUE ); exit;
			
			// -------------------------
			
			if ( $this->email->send() ) {
				
				if ( $notif ) {
					
					msg( ( 'notif_c_users_acode_sent_success' ), 'success' );
					
				}
				
				return TRUE;
				
			}
			else {
				
				if ( $notif ) {
					
					msg( ( 'notif_c_users_acode_sent_error' ), 'error' );
					
				}
				
				log_message( 'error', "[Users] " . lang( 'notif_c_users_acode_sent_error' ) );
				
				return FALSE;
				
			}
			
		}
		
		return FALSE;
		
	}
	
	// --------------------------------------------------------------------
	
	public function send_cplink( $user_id, $notif = FALSE ){
		
		if ( $this->mcm->load_email_system() ) {
			
			// -------------------------------------------------
			// Parsing vars
			
			if ( is_array( $user_id ) ) {
				
				$f_params = $user_id;
				
				$user_id =								isset( $f_params[ 'user_id' ] ) ? $f_params[ 'user_id' ] : NULL; // user id
				$subject =								isset( $f_params[ 'subject' ] ) ? $f_params[ 'subject' ] : lang( 'email_c_users_your_cplink_subject_string' ); // subject
				$email_body =							isset( $f_params[ 'email_body' ] ) ? $f_params[ 'email_body' ] : lang( 'email_c_users_your_cplink_body_string' ); // email_body
				$notif =								isset( $f_params[ 'notif' ] ) ? $f_params[ 'notif' ] : NULL; // notifications
				
			}
			else {
				
				$subject =								lang( 'email_c_users_your_cplink_subject_string' ); // subject
				$email_body =							lang( 'email_c_users_your_cplink_body_string' ); // email_body
				
			}
			
			// Parsing vars
			// -------------------------------------------------
			
			$this->clear_expired_tmp_codes();
			
			if ( ! ( $user = $this->get_user( $user_id )->row_array() ) ) {
				
				if ( $notif ) {
					
					msg( ( 'notif_c_users_send_cplink_invalid_user_error' ), 'error' );
					
				}
				
				return FALSE;
				
			}
			
			if ( $user[ 'status' ] == 0 ) {
				
				if ( $notif ) {
					
					msg( ( 'notif_c_users_already_have_account_disabled' ), 'error' );
					
					msg( lang( sprintf(
							
							lang( 'notif_c_users_already_have_account_disabled_desc' ),
							/*
								1° e-mail address
								2° login page
								3° pass recover page
								4° email recover page
								5° username recover page
								6° resend activation code page
								
							*/
							$user[ 'email' ],
							$this->users->get_link_login_page(),
							$this->users->get_link_get_cplink_page(),
							$this->users->get_link_email_recover_page(),
							$this->users->get_link_recover_username_page(),
							$this->users->get_link_resend_acode_page()
							
						)
						
					), 'error' );
					
				}
				
				return FALSE;
				
			}
			
			$user[ 'params' ] = get_params( $user[ 'params' ] );
			
			$db_data = array(
				
				'code' => $this->make_tmp_code(),
				'user_id' => $user_id,
				
			);
			
			$created_datetime = gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] );
			$created_datetime = ov_strftime( '%Y-%m-%d %T', $created_datetime );
			
			$db_data[ 'created_datetime' ] = $created_datetime;
			
			$date = strtotime( "+" . 1 . " days", strtotime( $created_datetime ) );
			$db_data[ 'validity_datetime' ] =  date( "Y-m-d", $date );
			
			if ( ! $this->insert_tmp_code( $db_data ) ) {
				
				if ( $notif ) {
					
					msg( ( 'notif_c_users_insert_tmp_code_error' ), 'error' );
					
				}
				
				return FALSE;
				
			}
			
			$this->email->from( $this->mcm->system_params[ 'email_config_smtp_user' ], $this->mcm->system_params[ 'site_name' ] );
			$this->email->reply_to( $this->mcm->system_params[ 'email_config_smtp_user' ] );
			$this->email->to( $user[ 'email' ] );
// 			$this->email->cc( $emails_cc);
// 			$this->email->bcc( $emails_bcc);
			$this->email->subject( sprintf(
				
				$subject,
				/*
					1° name
					2° username
					3° e-mail
					4° login page link
					5° change pass page link
					6° recover email page link
					7° recover username page link
					8° resend activation code page link
					9° send change pass link page link
					10° activate account page link
					11° activate account page link with code
					12° site name
					13° base url
					14° code
					
				*/
				$user[ 'name' ],
				$user[ 'username' ],
				$user[ 'email' ],
				get_url( $this->get_link_login_page() ),
				get_url( $this->get_link_change_pass_page( NULL, $db_data[ 'code' ] ) ),
				get_url( $this->get_link_email_recover_page() ),
				get_url( $this->get_link_recover_username_page() ),
				get_url( $this->get_link_resend_acode_page() ),
				get_url( $this->get_link_get_cplink_page() ),
				get_url( $this->get_link_activate_account_page() ),
				get_url( $this->get_link_activate_account_page( NULL, $db_data[ 'code' ] ) ),
				$this->mcm->filtered_system_params[ 'site_name' ],
				base_url(),
				$db_data[ 'code' ]
				
				
			));
			
			// -------------------------
			
			$theme_load_views_path = call_user_func( $this->mcm->environment . '_theme_components_views_path' ) . 'users' . DS . 'email' . DS . 'get_cplink' . DS . $layout . DS;
			$theme_views_path = THEMES_PATH . $theme_load_views_path;
			
			$default_load_views_path = get_constant_name( $this->mcm->environment . '_COMPONENTS_VIEWS_PATH' ) . 'users' . DS . 'email' . DS . 'get_cplink' . DS . $layout . DS;
			$default_views_path = VIEWS_PATH . $default_load_views_path;
			
			$data[ 'user' ] = $user;
			$data[ 'code' ] = $db_data[ 'code' ];
			
			if ( file_exists( $theme_views_path . 'get_cplink.php' ) ){
				
				$email_body = $this->load->view( $theme_load_views_path . 'get_cplink', $data, TRUE );
				
			}
			else if ( file_exists( $default_views_path . 'get_cplink.php') ){
				
				$email_body = $this->load->view( $default_views_path . 'get_cplink', $data, TRUE );
				
			}
			
			$this->email->message( sprintf(
				
				$email_body,
				/*
					1° name
					2° username
					3° e-mail
					4° login page link
					5° change pass page link
					6° recover email page link
					7° recover username page link
					8° resend activation code page link
					9° send change pass link page link
					10° activate account page link
					11° activate account page link with code
					12° site name
					13° base url
					14° code
					
				*/
				$user[ 'name' ],
				$user[ 'username' ],
				$user[ 'email' ],
				get_url( $this->get_link_login_page() ),
				get_url( $this->get_link_change_pass_page( NULL, $db_data[ 'code' ] ) ),
				get_url( $this->get_link_email_recover_page() ),
				get_url( $this->get_link_recover_username_page() ),
				get_url( $this->get_link_resend_acode_page() ),
				get_url( $this->get_link_get_cplink_page() ),
				get_url( $this->get_link_activate_account_page() ),
				get_url( $this->get_link_activate_account_page( NULL, $db_data[ 'code' ] ) ),
				$this->mcm->filtered_system_params[ 'site_name' ],
				base_url(),
				$db_data[ 'code' ]
				
				
			));
			
			// -------------------------
			
			if ( $this->email->send() ) {
				
				if ( $notif ) {
					
					msg( ( 'notif_c_users_cplink_sent_success' ), 'success' );
					
				}
				
				return TRUE;
				
			}
			else {
				
				if ( $notif ) {
					
					msg( ( 'notif_c_users_acode_sent_error' ), 'error' );
					
				}
				
				return FALSE;
				
			}
			
		}
		
		return FALSE;
		
	}
	
	// --------------------------------------------------------------------
	
	public function send_username( $user_id, $notif = FALSE ){
		
		if ( $this->mcm->load_email_system() ) {
			
			// -------------------------------------------------
			// Parsing vars
			
			$layout =									'default'; // layout
			
			if ( is_array( $user_id ) ) {
				
				$f_params = $user_id;
				
				$user_id =								isset( $f_params[ 'user_id' ] ) ? $f_params[ 'user_id' ] : NULL; // user id
				$subject =								isset( $f_params[ 'subject' ] ) ? $f_params[ 'subject' ] : lang( 'email_c_users_recover_username_subject_string' ); // subject
				$email_body =							isset( $f_params[ 'email_body' ] ) ? $f_params[ 'email_body' ] : lang( 'email_c_users_recover_username_body_string' ); // email_body
				$notif =								isset( $f_params[ 'notif' ] ) ? $f_params[ 'notif' ] : NULL; // notifications
				$layout =								isset( $f_params[ 'layout' ] ) ? $f_params[ 'layout' ] : $layout; // layout
				
			}
			else {
				
				$subject =								lang( 'email_c_users_recover_username_subject_string' ); // subject
				$email_body =							lang( 'email_c_users_recover_username_body_string' ); // email_body
				
			}
			
			// Parsing vars
			// -------------------------------------------------
			
			if ( ! ( $user = $this->get_user( $user_id )->row_array() ) ) {
				
				if ( $notif ) {
					
					msg( ( 'notif_c_users_recover_username_invalid_user_error' ), 'error' );
					
				}
				
				return FALSE;
				
			}
			
			$user[ 'params' ] = get_params( $user[ 'params' ] );
			
			$this->email->from( $this->mcm->system_params[ 'email_config_smtp_user' ], $this->mcm->system_params[ 'site_name' ] );
			$this->email->reply_to( $this->mcm->system_params[ 'email_config_smtp_user' ] );
			$this->email->to( $user[ 'email' ] );
// 			$this->email->cc( $emails_cc);
// 			$this->email->bcc( $emails_bcc);
			$this->email->subject( sprintf(
				
				$subject,
				/*
					1° name
					2° username
					3° e-mail
					4° login page link
					5° change pass page link
					6° recover email page link
					7° recover username page link
					8° resend activation code page link
					9° send change pass link page link
					10° activate account page link
					11° activate account page link with code
					11° site name
					12° base url
					
				*/
				$user[ 'name' ],
				$user[ 'username' ],
				$user[ 'email' ],
				get_url( $this->get_link_login_page() ),
				get_url( $this->get_link_change_pass_page() ),
				get_url( $this->get_link_email_recover_page() ),
				get_url( $this->get_link_recover_username_page() ),
				get_url( $this->get_link_resend_acode_page() ),
				get_url( $this->get_link_get_cplink_page() ),
				get_url( $this->get_link_activate_account_page() ),
				$this->mcm->filtered_system_params[ 'site_name' ],
				base_url()
				
			));
			
			// -------------------------
			
			$theme_load_views_path = call_user_func( $this->mcm->environment . '_theme_components_views_path' ) . 'users' . DS . 'email' . DS . 'recover_username' . DS . $layout . DS;
			$theme_views_path = THEMES_PATH . $theme_load_views_path;
			
			$default_load_views_path = get_constant_name( $this->mcm->environment . '_COMPONENTS_VIEWS_PATH' ) . 'users' . DS . 'email' . DS . 'recover_username' . DS . $layout . DS;
			$default_views_path = VIEWS_PATH . $default_load_views_path;
			
			$data[ 'user' ] = $user;
			
			if ( file_exists( $theme_views_path . 'recover_username.php' ) ){
				
				$email_body = $this->load->view( $theme_load_views_path . 'recover_username', $data, TRUE );
				
			}
			else if ( file_exists( $default_views_path . 'recover_username.php') ){
				
				$email_body = $this->load->view( $default_load_views_path . 'recover_username', $data, TRUE );
				
			}
			
			$this->email->message( sprintf(
				
				$email_body,
				/*
					
					1° name
					2° username
					3° e-mail
					4° login page link
					5° change pass page link
					6° recover email page link
					7° recover username page link
					8° resend activation code page link
					9° send change pass link page link
					10° activate account page link
					11° site name
					12° base url
					
				*/
				$user[ 'name' ],
				$user[ 'username' ],
				$user[ 'email' ],
				get_url( $this->get_link_login_page() ),
				get_url( $this->get_link_change_pass_page() ),
				get_url( $this->get_link_email_recover_page() ),
				get_url( $this->get_link_recover_username_page() ),
				get_url( $this->get_link_resend_acode_page() ),
				get_url( $this->get_link_get_cplink_page() ),
				get_url( $this->get_link_activate_account_page() ),
				$this->mcm->filtered_system_params[ 'site_name' ],
				base_url()
				
			));
			
			// -------------------------
			
			if ( $this->email->send() ) {
				
				if ( $notif ) {
					
					msg( ( 'notif_c_users_recover_username_sent_success' ), 'success' );
					
				}
				
				return TRUE;
				
			}
			else {
				
				if ( $notif ) {
					
					msg( ( 'notif_c_users_recover_username_sent_error' ), 'error' );
					
				}
				
				return FALSE;
				
			}
			
		}
		
		return FALSE;
		
	}
	
	// --------------------------------------------------------------------
	
	public function do_login( $f_params = NULL ){

		if ( check_var( $f_params ) AND gettype( $f_params ) === 'array' ){

			$this->load->helper( 'email' );

			$env =										isset( $f_params[ 'env' ] ) ? $f_params[ 'env' ] : $this->mcm->environment; // environment
			$id =										isset( $f_params[ 'user_data' ][ 'id' ] ) ? $f_params[ 'user_data' ][ 'id' ] : NULL; // user id
			$username =									isset( $f_params[ 'user_data' ][ 'username' ] ) ? $f_params[ 'user_data' ][ 'username' ] : NULL;
			$name =										isset( $f_params[ 'user_data' ][ 'name' ] ) ? $f_params[ 'user_data' ][ 'name' ] : NULL;
			$email =									isset( $f_params[ 'user_data' ][ 'email' ] ) ? $f_params[ 'user_data' ][ 'email' ] : NULL;
			$password =									isset( $f_params[ 'user_data' ][ 'password' ] ) ? $f_params[ 'user_data' ][ 'password' ] : NULL;
			$picture =									( isset( $f_params[ 'user_data' ][ 'picture' ] ) AND gettype( $f_params[ 'user_data' ][ 'picture' ] ) === 'array' ) ? $f_params[ 'user_data' ][ 'picture' ] : NULL; // array ONLY!!!
			$params =									( isset( $f_params[ 'user_data' ][ 'params' ] ) AND gettype( $f_params[ 'user_data' ][ 'params' ] ) === 'array' ) ? $f_params[ 'user_data' ][ 'params' ] : NULL; // array ONLY!!!
			$login_mode =								isset( $f_params[ 'login_mode' ] ) ? $f_params[ 'login_mode' ] : 'login'; // login, force, insert
			$session_mode =								isset( $f_params[ 'session_mode' ] ) ? $f_params[ 'session_mode' ] : 'normal'; // normal, persistent
			$redirect =									isset( $f_params[ 'redirect' ] ) ? $f_params[ 'redirect' ] : TRUE;
			$notif_success =							isset( $f_params[ 'notif_success' ] ) ? $f_params[ 'notif_success' ] : NULL;
			
		}
		
		// If we have username / email and password...
		if ( $login_mode === 'login' AND $password AND ( $username OR $email ) ){
			
			if ( $username ){
				
				$this->load->library( 'form_validation' );
				
				if ( $this->form_validation->valid_email( $username ) ){
					
					$user = $this->users->get_user( array( 't1.email' => $username ) )->row_array();
					$email = ! $email ? $username : $email;
					
				}
				else {
					
					$user = $this->users->get_user( array( 't1.username' => $username ) )->row_array();
					
				}
				
			}
			else if ( $email ){
				
				$user = $this->users->get_user( array( 't1.email' => $email ) )->row_array();
				
			}
			
			if ( $user ){
				
				if ( $user[ 'status' ] == 0 ) {
					
					if ( $redirect ) {
						
						msg( ( 'notif_c_users_already_have_account_disabled' ), 'error' );
						
						redirect( $this->get_link_resend_acode_page() );
						
					}
					
					return FALSE;
					
				}
				
				if ( $this->encode_password( $password ) == $user[ 'password' ] ) {
					
					$user = array(
						
						'id' => $user[ 'id' ],
						
					);
					
					// _make_login params
					$mlp = array(
						
						'user' => $user,
						'session_mode' => $session_mode,
						'redirect' => $redirect,
						
					);
					
					return $this->_make_login( $mlp );
					
				}
				else{
					
					msg( ( 'login_fail' ), 'title' );
					msg( ( 'incorrect_password' ),'error' );
					
				}
				
			}
			else{
				
				msg( ( 'login_fail' ), 'title' );
				msg( ( 'the_user_does_not_exist' ), 'error' );
				
			}
			
		}
		// force mode ignore password if we found a match
		else if ( $login_mode === 'force' AND ( $username OR $email ) ){
			
			if ( $username ){
				
				$user = $this->users->get_user( array( 't1.username' => $username ) )->row_array();
				
			}
			else if ( $email ){
				
				$user = $this->users->get_user( array( 't1.email' => $email ) )->row_array();
				
			}
			
			if ( $user ){
				
				if ( $user[ 'status' ] == 0 ) {
					
					if ( $redirect ) {
						
						msg( ( 'notif_c_users_already_have_account_disabled' ), 'error' );
						
						msg( lang( sprintf(
								
								lang( 'notif_c_users_already_have_account_disabled_desc' ),
								/*
									1° e-mail address
									2° login page
									3° pass recover page
									4° email recover page
									5° username recover page
									6° resend activation code page
									
								*/
								$user[ 'email' ],
								$this->users->get_link_login_page(),
								$this->users->get_link_get_cplink_page(),
								$this->users->get_link_email_recover_page(),
								$this->users->get_link_recover_username_page(),
								$this->users->get_link_resend_acode_page()
								
							)
							
						), 'error' );
						
						redirect( $this->get_link_resend_acode_page() );
						
					}
					
					return FALSE;
					
				}
				
				$user = array(

					'id' => $user[ 'id' ],

				);

				// _make_login params
				$mlp = array(

					'user' => $user,
					'session_mode' => $session_mode,
					'redirect' => FALSE,

				);

				$this->_make_login( $mlp );

				return TRUE;

			}
			else{

				msg( ( 'login_fail' ), 'title' );
				msg( ( 'the_user_does_not_exist' ), 'error' );

			}

		}
		// insert mode ignore pass if we find a match, and insert new user if not exists
		else if ( $login_mode === 'insert' AND $email ){

			if ( $username ){

				$user = $this->users->get_user( array( 't1.username' => $username ) )->row_array();

			}
			else if ( $email ){

				$user = $this->users->get_user( array( 't1.email' => $email ) )->row_array();

			}

			if ( $user ){
				
				if ( $user[ 'status' ] == 0 ) {
					
					if ( $redirect ) {
						
						msg( ( 'notif_c_users_already_have_account_disabled' ), 'error' );
						
						msg( lang( sprintf(
								
								lang( 'notif_c_users_already_have_account_disabled_desc' ),
								/*
									1° e-mail address
									2° login page
									3° pass recover page
									4° email recover page
									5° username recover page
									6° resend activation code page
									
								*/
								$user[ 'email' ],
								$this->users->get_link_login_page(),
								$this->users->get_link_get_cplink_page(),
								$this->users->get_link_email_recover_page(),
								$this->users->get_link_recover_username_page(),
								$this->users->get_link_resend_acode_page()
								
							)
							
						), 'error' );
						
						redirect( $this->get_link_resend_acode_page() );
						
					}
					
					return FALSE;
					
				}
				
				// _make_login params
				$mlp = array(
					
					'user' => $user,
					'session_mode' => $session_mode,
					'redirect' => TRUE,
					
				);
				
				$this->_make_login( $mlp );
				
			}
			else{
				
				$insert_data = array(
					
					'username' => $username,
					'email' => $email,
					'group_id' => 4,
					
				);
				
				if ( ! $username ){
					
					$insert_data[ 'username' ] = explode( '@', $email );
					$insert_data[ 'username' ] = $insert_data[ 'username' ][ 0 ];
					
					$insert_data[ 'username' ] = $this->_make_random_username( $insert_data[ 'username' ] );
					
				}
				
				if ( $name ){
					
					$insert_data[ 'name' ] = $name;
					
				}
				
				if ( $params ){
					
					$insert_data[ 'params' ] = $params;
					
				}
				else{
					
					$params = array();
					
				}
				
				if ( $picture ){
					
					$insert_data[ 'params' ][ 'picture' ] = $picture;
					
				}
				
				$insert_data[ 'params' ] = json_encode( $insert_data[ 'params' ] );
				
				$return_id = $this->insert_user( $insert_data );
				
				if ( $return_id ){
					
					msg( ( 'user_created' ), 'success' );
					
					$user = $this->users->get_user( array( 't1.id' => $return_id ) )->row_array();
					
					if ( $user[ 'status' ] == 0 ) {
						
						if ( $redirect ) {
							
							msg( ( 'notif_c_users_already_have_account_disabled' ), 'error' );
							
							msg( lang( sprintf(
									
									lang( 'notif_c_users_already_have_account_disabled_desc' ),
									/*
										1° e-mail address
										2° login page
										3° pass recover page
										4° email recover page
										5° username recover page
										6° resend activation code page
										
									*/
									$user[ 'email' ],
									$this->users->get_link_login_page(),
									$this->users->get_link_get_cplink_page(),
									$this->users->get_link_email_recover_page(),
									$this->users->get_link_recover_username_page(),
									$this->users->get_link_resend_acode_page()
									
								)
								
							), 'error' );
							
							redirect( $this->get_link_resend_acode_page() );
							
						}
						
						return FALSE;
						
					}
					
					// _make_login params
					$mlp = array(
						
						'user' => $user,
						'redirect' => FALSE,
						'session_mode' => $session_mode,
						
					);
					
					$this->_make_login( $mlp );
					
					redirect( 'admin/users/users_management/edit_user/' . $this->encode_user_id( $return_id ) );
					
				}
				else{
					
					msg( 'create_user_fail', 'title' );
					msg( 'undefined_error', 'error' );
					redirect_last_url();
					
				}
				
			}
			
		}
		else{
			
			msg( 'login_fail', 'title' );
			msg( 'insufficient_information', 'error' );
			redirect_last_url();
			
		}
		
	}
	
	// --------------------------------------------------------------------
	
	private function _make_login( $f_params ){
		
		if ( check_var( $f_params ) AND gettype( $f_params ) === 'array' AND check_var( $f_params[ 'user' ] ) ){
			
			$env =										isset( $f_params[ 'env' ] ) ? $f_params[ 'env' ] : $this->mcm->environment; // environment
			$user =										isset( $f_params[ 'user' ] ) ? $f_params[ 'user' ] : NULL; // user
			$session_mode =								isset( $f_params[ 'session_mode' ] ) ? $f_params[ 'session_mode' ] : 'normal'; // normal, persistent
			$redirect =									isset( $f_params[ 'redirect' ] ) ? $f_params[ 'redirect' ] : TRUE; // if the user will be redirected after logged in
			$notif_success =							isset( $f_params[ 'notif_success' ] ) ? $f_params[ 'notif_success' ] : NULL;
			
		}
		
		$user = $this->users->get_user( array( 't1.id' => $user[ 'id' ] ) )->row_array();
		$user[ 'params' ] = get_params( $user[ 'params' ] );
		
		$user_data = array(
			
			'id' => $user[ 'id' ],
			'username' => $user[ 'username' ],
			'name' => $user[ 'name' ],
			'email' => $user[ 'email' ],
			
		);
		
		/*********************************************/
		/************* Saving to session *************/
		
		$session_data[ 'login' ] = TRUE;
		$session_data[ 'login_mode' ] = $session_mode;
		$session_data[ 'user' ] = $user_data;
		
		$this->session->set_envdata( $session_data );
		
		/************* Saving to session *************/
		/*********************************************/
		
		// recover previous session data
		if ( $session_mode === 'persistent' ){
			
			$this->_save_access_hash( $user );
			
			if ( check_var( $user[ 'params' ][ $env . '_user_session' ] ) ){
				
				foreach ( $user[ 'params' ][ $env . '_user_session' ] as $key => $value ) {
					
					if ( ! in_array( $key, array( 'user', 'msg', 'access_hash' ) ) ){
						
						$this->session->set_envdata( $key, $value );
						
					}
					
				}
				
			}
			
		}
		else{
			
			//$this->main_model->delete_temp_data( array( 'user_id' => $user[ 'id' ] ) );
			
		}
		
		if ( $notif_success ) {
			
			if ( is_bool( $notif_success ) ) {
				
				msg( 'notif_c_users_login_success', 'success' );
				
			}
			else {
				
				msg( $notif_success, 'success' );
				
			}
			
		}
		
		if ( $redirect ){
			
			if ( $this->session->envdata( 'uri_after_login_' . $this->mcm->environment ) ){
				
				redirect( get_url( $this->session->envdata( 'uri_after_login_' . $this->mcm->environment ) ) );
				
			}
			else {
				
				redirect_last_url();
				
			}
			
		}
		else{
			
			return TRUE;
			
		}
		
	}
	
	// --------------------------------------------------------------------
	
	/*
	 * procura pelo hash atual no banco de users, se encontrado,
	 * quer dizer que alguém se logou com a opção "Mantenha-me conectado"
	 * em seguida, retorna o usuário, caso contrário, FALSE
	 */
	public function check_client_hash(){

		$access_hash = $this->_make_access_hash();
		$client_hash = $access_hash[ 'client' ];

		if ( $client_hash ){

			$this->db->select( '*' );
			$this->db->from('tb_users');
			$this->db->like( 'params', '"client_hash' . $client_hash . '":"' . $client_hash . '"' );
			$this->db->limit( 1 );
			$user = $this->db->get()->row_array();

			return $user;

		}
		else{

			return FALSE;

		}

	}
	
	// --------------------------------------------------------------------
	
	public function remove_access_hash( $user_hash = NULL ){

		$user = $this->session->envdata( 'user' );
		$user = $this->users->get_user( array( 't1.id' => $user[ 'id' ] ) )->row_array();

		$access_hash = $this->_make_access_hash( $user );

		if ( $user ){

			$data[ 'params' ] = get_params( $user[ 'params' ] );
			unset( $data[ 'params' ][ $this->mcm->environment . '_user_hashes' ][ 'user_hash' . $access_hash[ 'user' ] ] );
			unset( $data[ 'params' ][ $this->mcm->environment . '_client_hashes' ][ 'client_hash' . $access_hash[ 'client' ] ] );
			$data[ 'params' ] = json_encode( $data[ 'params' ] );

			if ( $this->db->update( 'tb_users', $data, array( 'id' => $user[ 'id' ] ) ) ){

				$this->session->unset_envdata( 'access_hash' );
				return TRUE;

			}

		}
		else{

			return TRUE;

		}

	}
	
	// --------------------------------------------------------------------
	
	private function _save_access_hash( $user ){

		if ( $user ){

			$user = $this->get_user( array( 't1.id' => $user[ 'id' ] ) )->row_array();

			$access_hash = $this->_make_access_hash( $user );

			$data[ 'params' ] = get_params( $user[ 'params' ] );
			$data[ 'params' ][ $this->mcm->environment . '_user_hashes' ][ 'user_hash' . $access_hash[ 'user' ] ] = $access_hash[ 'user' ];
			$data[ 'params' ][ $this->mcm->environment . '_client_hashes' ][ 'client_hash' . $access_hash[ 'client' ] ] = $access_hash[ 'client' ];
			$data[ 'params' ] = json_encode( $data[ 'params' ] );

			if ( $this->db->update( 'tb_users', $data, array( 'id' => $user[ 'id' ] ) ) ){

				$this->session->set_envdata( 'access_hash', array( 'user_hash' => $access_hash[ 'user' ], 'client_hash' => $access_hash[ 'client' ], ) );
				return TRUE;

			}

		}
		else{

			return FALSE;

		}

	}
	
	// --------------------------------------------------------------------
	
	private function _make_access_hash( $user = FALSE ){
		
		$client_ip = ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '' ) . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . $this->mcm->environment;
		$client_hash = md5( $client_ip . $_SERVER['HTTP_USER_AGENT'] );
		
		$access_hash[ 'client' ] = $client_hash;
		
		if ( $user ){
			
			$user = $this->users->get_user( array( 't1.id' => $user[ 'id' ] ) )->row_array();
			
			$user_hash = md5( $user[ 'id' ] . $client_ip . $user[ 'id' ] . $_SERVER['HTTP_USER_AGENT'] );
			
			$access_hash[ 'user' ] = $user_hash;
			
		}
		
		return $access_hash;
		
	}
	
	// --------------------------------------------------------------------
	
	private function _make_random_username( $prefix = 'user' ){

		$user = $this->users->get_user( array( 't1.username' => $prefix ) )->row_array();

		if ( $user ){

			$new_rand = rand( 1, 1000 );

			return $this->_make_random_username( $prefix . $new_rand );

		}
		else{

			return $prefix;

		}

	}
	
	// --------------------------------------------------------------------
	
	public function remove_session_from_user() {

		$user = $this->session->envdata( 'user' );
		$id = check_var( $user[ 'id' ] ) ? $user[ 'id' ] : NULL;

		if ( $user AND $id ){

			$this->db->where( 'id', $id );
			$this->db->limit( 1 );
			$user = $this->db->get( 'tb_users' )->row_array();

			$data[ 'params' ] = get_params( $user[ 'params' ] );
			$data[ 'params' ][ $this->mcm->environment . '_user_session' ] = array();
			$data[ 'params' ] = json_encode( $data[ 'params' ] );

			if ( $this->db->update( 'tb_users', $data, array( 'id' => $user[ 'id' ] ) ) ){

				return TRUE;

			}

		}

	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Gera um password aleatorio
	 * @return String
	 */
	public function generatePassword() {
		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890$#%@.';
		$pass = array();
		$alphaLength = strlen($alphabet) - 1; //Tamanho da string de caracters
		
		for ($i = 0; $i < 8; $i++)
		{
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		
		return implode($pass); //Retorna a string formatada
	}
	
}
