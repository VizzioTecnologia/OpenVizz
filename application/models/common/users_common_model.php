<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_common_model extends CI_Model{

	public $users_groups_tree = '';
	public $next_has_children = FALSE;
	public $user_data = array();
	public $users_query = NULL;
	public $users_groups_query = NULL;
	public $users_groups_tree_array = array();
	
	// --------------------------------------------------------------------
	
	// Define preferências de um usuário
	// a variável $update_db pode ser definida como FALSE se esta função estiver sendo chamada de alguma função que já atualize os dados do usuário
	public function set_user_preferences( $data = NULL, $user_id = NULL, $update_db = TRUE, $override = FALSE ){
		
		if ( isset($data) AND is_array($data) ){
			
			// se o id do usuário alvo não for informado, utilizamos o id do usuário atual
			$user_id = $user_id ? $user_id : $this->user_data[ 'id' ];
			
			$current_user = FALSE;
			
			// verifica se estamos lidando com o usuário atual
			if ( $user_id == $this->user_data['id'] )
				$current_user = TRUE;
			
			$user_params = array();
			
			if ( $current_user ){
				
				// preferências atuais do usuário atual
				$user_params = $this->user_data['params'];
				
			}
			else{
				
				$user_params = $this->get_user( array( 't1.id' => $user_id ) )->row_array();
				$user_params = get_params( $user_params[ 'params' ] );
				
			}
			
			if ( $override ) {
				
				foreach( $data as $k => $param ) {
					
					$params[ $k ] = $param;
					
				}
				
			}
			else {
				
				$params = array_merge_recursive_distinct( $user_params, $data );
				
			}
			
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
	
	public function insert_user($data = NULL){
		if ($data != NULL){
			if ($this->db->insert('tb_users',$data)){
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
	
	public function insert_user_respecting_privileges( $data = NULL ){

		if ( $this->check_privileges( 'users_management_can_add_user' ) ){

			$this->insert_user( $data );

		}
		else {

			redirect_last_url( array( 'title' => 'access_denied', 'type' => 'error', 'msg' => 'access_denied_users_management_can_add_user' ) );

		}

	}
	
	// --------------------------------------------------------------------
	
	public function update_user($data = NULL,$condition = NULL){
		if ($data != NULL && $condition != NULL){
			if ($this->db->update('tb_users',$data,$condition)){
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
		$this->plugins->load( 'js_text_editor' );
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
	public function check_if_user_is_on_same_and_low_group_level($user_id){

		$this->get_users_groups_query();
		$this->get_users_query();

		$user = $this->get_user(array('t1.id' => $user_id))->row();

		$user_group_id = $user->group_id;
		$current_user_group_id = $this->user_data['group_id'];

		$above_groups = $this->get_user_group_path($user_group_id);

		$output = FALSE;
		foreach($above_groups as $row) {
			if ( $this->user_data['parent_user_group_id'] == $row['parent'] OR $this->user_data['id'] == $row['parent'] OR $this->user_data['group_id'] == $row['id'] ) {
				$output = TRUE;
			}
		}
		return $output;
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
		
		if ( ! $this->is_logged_in() ){
			
			redirect( 'admin/main/index/login' );
			
			return FALSE;
			
		}
		else{
			
			return $this->_check_privileges( $privilege, $this->user_data );
			
		}
		
		return FALSE;
		
	}
	
	// --------------------------------------------------------------------
	
	public function insert_category($data = NULL){
		if ($data != NULL){
			if ($this->db->insert('tb_articles_categories',$data)){
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
	
	public function update_category($data = NULL,$condition = NULL){
		if ($data != NULL && $condition != NULL){
			if ($this->db->update('tb_articles_categories',$data,$condition)){
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
	
	public function menu_item_get_link_login_page($menu_item_id = NULL, $params = NULL){
		return 'users/index/login/'.$menu_item_id;
	}
	
	// --------------------------------------------------------------------
	
	public function get_link_login_page($menu_item_id = NULL){
		return 'users/index/login/'.$menu_item_id;
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

		}

		// If we have username / email and password...
		if ( $login_mode === 'login' AND $password AND ( $username OR $email ) ){
			
			if ( $username ){
				
				$user = $this->users->get_user( array( 't1.username' => $username ) )->row_array();
				
			}
			if ( $email ){
				
				$user = $this->users->get_user( array( 't1.email' => $email ) )->row_array();
				
			}
			
			if ( $user ){

				if ( base64_encode( md5( $password ) ) == $user[ 'password' ] ){

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

					$user = array(

						'id' => $return_id,

					);

					// _make_login params
					$mlp = array(

						'user' => $user,
						'redirect' => FALSE,
						'session_mode' => $session_mode,

					);

					$this->_make_login( $mlp );

					redirect( 'admin/users/users_management/edit_user/' . base64_encode( base64_encode( base64_encode( base64_encode( $return_id ) ) ) ) );

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
	
}

















