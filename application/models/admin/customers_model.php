<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Customers_model extends CI_Model{
		
		/**************************************************/
		/************** Customers categories **************/
		
		private $categories_tree = '';
		private $next_has_children = FALSE;
		private $categories_tree_array = array();
		
		// Essa função sempre gera o html em formato de menu, sempre com um </li> no final,
		// mas no retorno da função get_menu_tree ele é retirado
		private function get_children_as_menu($parent_id, $level) {
			
			// pega todos os filhos de $parent_id
			$this->db->where('parent = '.$parent_id);
			$this->db->order_by('ordering asc, title asc, id asc');
			$query = $this->db->get('tb_customers_categories');
			
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
		
		// Essa função sempre gera uma lista para comboboxes em formato de array
		// O parâmetro $query serve para economizar consultas ao bando de dados, caso a função tenha que se "auto chamar" novamente
		private function get_children_as_list($parent_id, $level, $query = NULL) { 
			
			// Caso a query não tenha sido informada, executa isto
			if (!$query){
				$this->db->select('t1.*, t2.title as parent_title, t2.alias as parent_alias');
				$this->db->from('tb_customers_categories t1');
				$this->db->join('tb_customers_categories t2', 't1.parent = t2.id', 'left');
				
				$this->db->order_by('ordering asc, title asc, id asc');
				$query = $this->db->get();
				$query = $query->result_array();
			}
			// atribui todos os filhos para a variável $menu_tree
			foreach($query as $row) {
				if ( $row['parent'] == $parent_id ) {
					// atribui cada valor da categoria em um array
					$this->categories_tree[$row['id']] = array(
						'id' => $row['id'],
						'alias' => $row['alias'],
						'title' => $row['title'],
						'parent' => $row['parent'],
						'parent_alias' => $row['parent_alias'],
						'parent_title' => $row['parent_title'],
						'status' => $row['status'],
						'indented_title' => str_repeat( '&nbsp;' , $level * 4 + 4). lang( 'indented_symbol' ).$row['title'],
						'description' => $row['description'],
						'level' => $level,
					);
					
					// chama esta função novamente para mostrar os filhos deste filho
					$this->get_children_as_list($row['id'], $level+1, $query); 
				}
			}
		}
		// Função que retorna o número de filhos de um item
		public function get_num_childrens($id){
			
			$this->db->where('parent = '.$id);
			$this->db->order_by('ordering asc, title asc, id asc');
			$this->db->from('tb_customers_categories');
			// retorna a quantidade de registros com item pai $id
			return $this->db->count_all_results();
			
		}
		public function get_categories_tree($parent_id, $level, $type = 'menu'){
			
			$this->categories_tree = '';
			
			if ($type == 'menu'){
				$this->get_children_as_menu($parent_id, $level);
				// remove o último </li> da string
				return substr_replace($this->categories_tree,'',-6);
			}
			else if ($type == 'list'){
				$this->get_children_as_list($parent_id, $level);
				return $this->categories_tree;
			}
			else {
				return FALSE;
			}
			
		}
		public function get_category_path($category_id, $parent_limit = 0, $query = NULL){
			
			// Caso a query não tenha sido informada, executa isto
			if (!$query){
				$this->db->select('t1.*, t2.title as parent_title, t2.alias as parent_alias');
				$this->db->from('tb_customers_categories t1');
				$this->db->join('tb_customers_categories t2', 't1.parent = t2.id', 'left');
				
				$this->db->order_by('ordering asc, title asc, id asc');
				$query = $this->db->get();
				$query = $query->result_array();
				
			}
			
			$path = array();
			
			foreach($query as $row) {
				if ($row['id'] == $category_id AND $row['parent'] != $parent_limit) {
					
					$path[] = array(
						'id' => $row['parent'],
						'title' => $row['parent_title'],
					);
					
					$path = array_merge($this->get_category_path($row['parent'], $parent_limit, $query), $path);
				}
			}
			
			// return the path
			return $path;
			
		}
		public function get_categories_as_list_childrens_hidden($root_parent = 0, $parent_to_hide = NULL){
			
			if ($parent_to_hide){
				
				$this->db->select('t1.*, t2.title as parent_title, t2.alias as parent_alias');
				$this->db->from('tb_customers_categories t1');
				$this->db->join('tb_customers_categories t2', 't1.parent = t2.id', 'left');
				
				$this->db->order_by('ordering asc, title asc, id asc');
				$query = $this->db->get();
				$query = $query->result_array();
				
				// obtendo o array completo das categorias
				$this->get_children_as_list($root_parent,0,$query);
				$categories = $this->categories_tree;
				
				// zerando a variável categories_tree,
				// que continha o array completo com as categorias
				$this->categories_tree = array();
				
				// agora obtendo o array das categorias, com raiz $parent_to_hide
				$this->get_children_as_list($parent_to_hide,0,$query);
				$categories_to_hide = $this->categories_tree;
				
				foreach ($categories_to_hide as $key => $value) {
					if (array_key_exists($key,$categories)){
						unset($categories[$key]);
					}
				}
				
				return $categories;
			}
			
		}
		public function get_category($id = NULL){
			if ($id!=NULL){
				$this->db->select('t1.*, t2.title as parent_category_title');
				$this->db->from('tb_customers_categories t1');
				$this->db->join('tb_customers_categories t2', 't1.parent = t2.id', 'left');
				$this->db->where('t1.id',$id);
				// limitando o resultando em apenas 1
				$this->db->limit(1);
				return $this->db->get();
			}
			else {
				return false;
			}
		}
		public function get_categories($condition = NULL, $limit = NULL, $offset = NULL, $return_type = 'get'){
			
			$this->db->select('t1.*, t2.title as parent_category_title');
			$this->db->from('tb_customers_categories t1');
			$this->db->join('tb_customers_categories t2', 't1.parent = t2.id', 'left');
			$this->db->order_by('t1.title asc, t1.id asc');
			
			if ($condition){
				$this->db->where($condition);
			}
			if ($limit){
				$this->db->limit($limit, $offset?$offset:NULL);
			}
			if ( $return_type === 'count_all_results' ){
				return $this->db->count_all_results();
			}
			
			return $this->db->get();
		}
		public function insert_category($data = NULL){
			if ($data != NULL){
				if ($this->db->insert('tb_customers_categories',$data)){
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
		public function update_category($data = NULL, $condition = NULL){
			if ($data != NULL && $condition != NULL){
				if ($this->db->update('tb_customers_categories',$data,$condition)){
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
				if ($this->db->delete('tb_customers_categories',$condition)){
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
		/************** Customers categories **************/
		/**************************************************/
		
		/**************************************************/
		/******************** Customers *******************/
		
		public function get_customers($condition = NULL, $limit = NULL, $offset = NULL, $return_type = 'get', $or_condition = NULL){
			
			$this->db->select('
				
				t1.*,
				
				t2.name as user_name,
				t2.username,
				
				t3.trading_name,
				t3.company_name,
				t3.corporate_tax_register,
				t3.sic, t3.state_registration,
				t3.logo,
				t3.logo_thumb,
				t3.contacts,
				t3.emails as company_emails,
				t3.phones as company_phones,
				t3.addresses as company_addresses,
				t3.websites as company_websites,
				
				t4.name as contact_name,
				t4.photo_local as contact_photo,
				t4.thumb_local as contact_photo_thumb,
				t4.emails as contact_emails,
				t4.phones as contact_phones,
				t4.addresses as contact_addresses,
				t4.websites as contact_websites,
				
				t5.alias as category_alias,
				t5.title as category_title,
				t5.parent as category_parent_id
				
			');
			$this->db->from('tb_customers t1');
			$this->db->join('tb_users t2', 't1.user_id = t2.id', 'left');
			$this->db->join('tb_companies t3', 't1.company_id = t3.id', 'left');
			$this->db->join('tb_contacts t4', 't1.contact_id = t4.id', 'left');
			$this->db->join('tb_customers_categories t5', 't1.category_id = t5.id', 'left');
			$this->db->order_by('t1.title asc, t1.id asc');
			
			if ($condition){
				$this->db->where($condition);
			}
			if ($or_condition){
				$this->db->or_where($or_condition);
			}
			if ($limit){
				$this->db->limit($limit, $offset?$offset:NULL);
			}
			if ( $return_type === 'count_all_results' ){
				return $this->db->count_all_results();
			}
			
			return $this->db->get();
		}
		public function get_customers_simple($condition = NULL, $limit = NULL, $offset = NULL, $return_type = 'get', $or_condition = NULL){
			
			$this->db->select('
				
				t1.*
				
			');
			$this->db->from('tb_customers t1');
			$this->db->order_by('t1.title asc, t1.id asc');
			
			if ($condition){
				$this->db->where($condition);
			}
			if ($or_condition){
				$this->db->or_where($or_condition);
			}
			if ($limit){
				$this->db->limit($limit, $offset?$offset:NULL);
			}
			if ( $return_type === 'count_all_results' ){
				return $this->db->count_all_results();
			}
			
			return $this->db->get();
		}
		public function insert_customer($data = NULL){
			if ($data != NULL){
				if ($this->db->insert('tb_customers',$data)){
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
		public function update_customer($data = NULL, $condition = NULL){
			if ($data != NULL && $condition != NULL){
				if ($this->db->update('tb_customers',$data,$condition)){
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
		public function delete_customer($condition = NULL){
			if ($condition != null){
				if ($this->db->delete('tb_customers',$condition)){
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
		
		/******************** Customers *******************/
		/**************************************************/
		
	}
