<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Users_model extends CI_Model{
		
		private $users_groups_tree = '';
		private $next_has_children = FALSE;
		public $user_data = array();
		
		
		public $users_groups_query = NULL;
		public function get_users_groups_query($condition = NULL, $limit = NULL, $offset = NULL, $order_by = 'title asc, id asc'){
			
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
				
				
				if ($this->users_groups_query = $this->db->get(NULL, $limit, $offset)){
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
		
		public $users_query = NULL;
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
		
		// Essa função sempre gera o html em formato de menu, sempre com um </li> no final,
		// mas no retorno da função get_menu_tree ele é retirado
		private function get_children_as_menu($parent_id, $level) {
			
			// pega todos os filhos de $parent_id
			$this->db->where('parent = '.$parent_id);
			$this->db->order_by('order asc, title asc, id asc');
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
		
		private $users_groups_tree_array = array();
		
		// Essa função sempre gera uma lista para comboboxes em formato de array
		// O parâmetro $query serve para economizar consultas ao bando de dados, caso a função tenha que se "auto chamar" novamente
		private function get_children_as_list($parent_id, $level, $query = NULL) { 
			
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
						'indented_title' => str_repeat('&#8226;&nbsp;', $level).'&#8226; '.$row['title'],
						'description' => $row['description'],
						'level' => $level,
					);
					
					// chama esta função novamente para mostrar os filhos deste filho
					$this->get_children_as_list($row['id'], $level+1, $query); 
				}
			}
		}
		public function get_accessible_users_groups($user_id) { 
			
			$this->get_users_groups_query();
			
			if ($this->check_privileges('users_management_can_edit_only_same_and_low_group_level')){
				$this->get_children_as_list($this->user_data['parent_user_group_id'], 0, $this->users_groups_query->result_array());
				$accessible_groups = $this->users_groups_tree;
			}
			else if ($this->check_privileges('users_management_can_edit_only_same_group_level')){
				$accessible_groups = $this->get_users_groups_as_list_childrens_hidden($this->user_data['parent_user_group_id'], $this->user_data['group_id']);
				foreach ($accessible_groups as $key => $value) {
					if ($value['parent'] != $this->user_data['parent_user_group_id']){
						unset($accessible_groups[$key]);
					}
				}
			}
			else if ($this->check_privileges('users_management_can_edit_only_same_group_and_below')){
				$accessible_groups = $this->get_users_groups_as_list_childrens_hidden($this->user_data['parent_user_group_id'], $this->user_data['group_id']);
				foreach ($accessible_groups as $key => $value) {
					if ($value['parent'] != $this->user_data['parent_user_group_id']){
						unset($accessible_groups[$key]);
					}
				}
				foreach ($accessible_groups as $key => $value) {
					if ($value['parent'] == $this->user_data['parent_user_group_id'] AND $value['id'] != $this->user_data['group_id']){
						unset($accessible_groups[$key]);
					}
				}
				$this->get_children_as_list($this->user_data['group_id'], 0, $accessible_groups);
				$accessible_groups = array_merge_recursive($accessible_groups,$this->users_groups_tree);
				
				// zerando a variável users_groups_tree
				$this->users_groups_tree = array();
				
				// chamando a função get_children_as_list() para reformatar a lista de grupos
				$this->get_children_as_list($this->user_data['parent_user_group_id'], 0, $accessible_groups);
				$accessible_groups = $this->users_groups_tree;
			}
			else if ($this->check_privileges('users_management_can_edit_only_same_group')){
				$accessible_groups = $this->get_users_groups_as_list_childrens_hidden($this->user_data['parent_user_group_id'], $this->user_data['group_id']);
				foreach ($accessible_groups as $key => $value) {
					if ($value['parent'] != $this->user_data['parent_user_group_id']){
						unset($accessible_groups[$key]);
					}
				}
				foreach ($accessible_groups as $key => $value) {
					if ($value['parent'] == $this->user_data['parent_user_group_id'] AND $value['id'] != $this->user_data['group_id']){
						unset($accessible_groups[$key]);
					}
				}
			}
			else if ($this->check_privileges('users_management_can_edit_only_low_groups')){
				$accessible_groups = array();
				if ( $this->user_data['id'] == $user_id ){
					$accessible_groups = $this->get_users_groups_as_list_childrens_hidden($this->user_data['parent_user_group_id'], $this->user_data['group_id']);
					foreach ($accessible_groups as $key => $value) {
						if ($value['parent'] != $this->user_data['parent_user_group_id']){
							unset($accessible_groups[$key]);
						}
					}
					foreach ($accessible_groups as $key => $value) {
						if ($value['parent'] == $this->user_data['parent_user_group_id'] AND $value['id'] != $this->user_data['group_id']){
							unset($accessible_groups[$key]);
						}
					}
					$this->get_children_as_list($this->user_data['group_id'], 0, $accessible_groups);
					$accessible_groups = array_merge_recursive($accessible_groups,$this->users_groups_tree);
					
					// zerando a variável users_groups_tree
					$this->users_groups_tree = array();
					
					// chamando a função get_children_as_list() para reformatar a lista de grupos
					$this->get_children_as_list($this->user_data['parent_user_group_id'], 0, $accessible_groups);
					$accessible_groups = $this->users_groups_tree;
				}
				else {
					$accessible_groups = array_merge_recursive($accessible_groups,$this->get_users_groups_tree($this->user_data['group_id'], 0, 'list'));
				}
			}
			else if ($this->check_privileges('users_management_can_edit_only_your_own_user')){
				if ( $this->user_data['id'] == $user_id ){
					$accessible_groups = $this->get_users_groups_as_list_childrens_hidden($this->user_data['parent_user_group_id'], $this->user_data['group_id']);
					foreach ($accessible_groups as $key => $value) {
						if ($value['parent'] != $this->user_data['parent_user_group_id']){
							unset($accessible_groups[$key]);
						}
					}
					foreach ($accessible_groups as $key => $value) {
						if ($value['parent'] == $this->user_data['parent_user_group_id'] AND $value['id'] != $this->user_data['group_id']){
							unset($accessible_groups[$key]);
						}
					}
				}
				else{
					$accessible_groups = FALSE;
				}
			}
			
			return $accessible_groups;
			
		}
		
		
		
		
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
		
		
		
		
		// Função que retorna o número de filhos de um item
		public function get_num_childrens($id){
			
			$this->db->where('parent = '.$id);
			$this->db->order_by('title asc, id asc');
			$this->db->from('tb_users_groups');
			// retorna a quantidade de registros com item pai $id
			return $this->db->count_all_results();
			
		}
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
						
						$path = array_merge($this->get_user_group_path($row['parent'], $parent_limit), $path);
					}
				}
				
				// return the path
				return $path;
				
			}
			else{
				return FALSE;
			}
			
		}
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
		public function get_user_group($condition = NULL){
			if ($condition){
				$this->db->select('t1.*, t2.title as parent_title, t2.alias as parent_alias');
				$this->db->from('tb_users_groups t1');
				$this->db->join('tb_users_groups t2', 't1.parent = t2.id', 'left');
				$this->db->where($condition);
				// limitando o resultando em apenas 1
				$this->db->limit(1);
				return $this->db->get();
			}
			else {
				return false;
			}
		}
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
		
		
		
		
		
		public function check_privileges($privilege = 'admin_access'){
			
			if ( ! in_array($privilege, $this->user_data['privileges']) ){
				return FALSE;
			}
			else{
				return TRUE;
			}
			
			return FALSE;
			
		}
		public function get_users(){
			
			if ( $this->users_model->users_model->check_privileges('users_management_users_management') ){
				
				if ($this->check_privileges('users_management_can_see_only_same_and_low_group_level')){
					
					$childrens_users_groups = $this->get_users_groups_tree($this->user_data['parent_user_group_id'],0,'list');
					
				}
				else if ($this->check_privileges('users_management_can_see_only_low_group_level')){
					
					$childrens_users_groups = $this->get_users_groups_tree($this->user_data['group_id'],0,'list');
					
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
		
		public function delete_article($condition = NULL){
			if ($condition != null){
				if ($this->db->delete('tb_articles',$condition)){
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
		
		public function delete_category($condition = NULL){
			if ($condition != null){
				if ($this->db->delete('tb_articles_categories',$condition)){
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
		
		/* Component privileges */
		public function get_component_privileges(){
			
			$privileges = get_params_spec_from_xml(APPPATH.'controllers/admin/com_users/privileges.xml');
			
			return $privileges;
		}
		
		/* General params */
		public function get_general_params(){
			
			$params = get_params_spec_from_xml(APPPATH.'controllers/admin/com_articles/general_params.xml');
			
			$layouts_articles_list = dir_list_to_array('application/views/site/'.$this->config->item('site_theme').'/components/articles/articles_list');
			$params['params']['articles_list']['layout_articles_list'] = array(
				'title' => 'layout',
				'name' => 'layout_articles_list',
				'description' => 'tip_layout_articles_list',
				'type' => 'combobox',
				'default' => 'default',
				'options' => $layouts_articles_list,
			);
			$layouts_article_detail = dir_list_to_array('application/views/site/'.$this->config->item('site_theme').'/components/articles/article_detail');
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
		
		
		
		public function get_user_params(){
			
			$params = get_params_spec_from_xml(APPPATH.'controllers/admin/com_users/user_preferences.xml');
			
			// idiomas
			$languages_list = dir_list_to_array('application/language');
			$params['params']['user_preferences']['admin_language'] = array(
				'title' => 'admin_language',
				'name' => 'admin_language',
				'description' => 'tip_admin_language',
				'type' => 'combobox',
				'default' => $this->config->item('language'),
				'options' => $languages_list,
			);
			$params['params']['user_preferences']['site_language'] = array(
				'title' => 'site_language',
				'name' => 'site_language',
				'description' => 'tip_site_language',
				'type' => 'combobox',
				'default' => $this->config->item('language'),
				'options' => $languages_list,
			);
			
			// ajustar para os futuros editores
			/*
			$layouts_articles_list = dir_list_to_array('application/views/site/'.$this->config->item('site_theme').'/components/articles/articles_list');
			$params['params']['articles_list']['layout_articles_list'] = array(
				'title' => 'layout',
				'name' => 'layout_articles_list',
				'description' => 'tip_layout_articles_list',
				'type' => 'combobox',
				'default' => 'default',
				'options' => $layouts_articles_list,
			);
			*/
			
			// print_r($params);
			
			return $params;
		}
		
		
		public function get_users_groups_params(){
			
			$components = $this->main_model->get_components(array('status'=>1))->result();
			
			$params = array();
			
			foreach ($components as $key => $component) {
				if (file_exists(APPPATH.'controllers/admin/com_'.$component->alias.'/privileges.xml')) {
					$params = array_merge_recursive(get_params_spec_from_xml(APPPATH.'controllers/admin/com_'.$component->alias.'/privileges.xml'),$params);
				}
			}
			
			return $params;
		}
		
		
		public function get_link_login_page($menu_item_id = NULL, $params = NULL){
			return 'users/index/login/'.$menu_item_id;
		}
		public function get_link_logout_page($menu_item_id = NULL, $params = NULL){
			return 'users/index/logout/'.$menu_item_id;
		}
		
		
	}
