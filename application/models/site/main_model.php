<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Main_model extends CI_Model{
		
		public $params = array();
		
		public function parse_params( $params = NULL ){
			
			if ( $params ){
				
				if ( isset( $params[ $this->mcm->environment . '_items_per_page' ] ) AND $params[ $this->mcm->environment . '_items_per_page' ] == -1 ){
					
					if ( ! ( (int) $params[ $this->mcm->environment . '_custom_items_per_page' ] > 0 ) ){
						
						$params[ $this->mcm->environment . '_items_per_page' ] = 10;
						
					}
					else{
						
						$params[ $this->mcm->environment . '_items_per_page' ] = $params[ $this->mcm->environment . '_custom_items_per_page' ];
						
					}
					
				}
				
			}
			
			return $params;
			
		}
		
		public function get_components( $condition = NULL ){
			$this->db->select('*');
			$this->db->from('tb_components');
			if ($condition){
				$this->db->where($condition);
			}
			$this->db->order_by('title asc, id asc');
			return $this->db->get();
		}
		
		private $menu_tree = '';
		private $next_has_children = FALSE;
		
		// Essa função sempre gera o html em formato de menu, sempre com um </li> no final,
		// mas no retorno da função get_menu_tree ele é retirado
		private function get_children_as_menu($parent_id, $level, $query = NULL) {
			
			// Caso a query não tenha sido informada, cria-o agora
			if (!$query){
				$this->db->select('t1.*, t2.title as parent_title, t2.alias as parent_alias');
				$this->db->from('tb_menus t1');
				$this->db->join('tb_menus t2', 't1.parent = t2.id', 'left');
				$this->db->where('t1.status','1');
				
				$this->db->order_by('ordering asc, title asc, id asc');
				$query = $this->db->get();
				$query = $query->result_array();
				
			}
			
			// variável que determina se o item atual possui filhos
			$has_children = $this->get_num_childrens($parent_id, $query);
			
			// se o item atual possui filhos, insere a primeira <ul>
			if ($has_children) $this->menu_tree .= "<ul>\n";
			
			foreach($query as $row) {
				if ($row['parent'] == $parent_id) {
					
					// verifica se este filho possui outros filhos
					$next_has_children = $this->get_num_childrens($row['id'], $query)?TRUE:FALSE;
					
					// indent and display the title of this child
					$this->menu_tree .= '<li class="level-'.$level.'"><a href="'.($row['home']?site_url():get_url($row['link'], $row['id'])).'">'.$row['title'].($next_has_children?"</a>\n":'</a></li>'."\n"); 
					
					// chama esta função novamente para mostrar os filhos deste filho
					$this->get_children_as_menu($row['id'], $level+1, $query);
				}
			}
			
			if ($has_children) $this->menu_tree .= $next_has_children?"</ul>\n":"</ul>\n</li>\n";
		}
		
		public $menu_tree_array = array();
		
		// Essa função sempre gera uma lista para comboboxes em formato de array
		private function get_children_as_list($parent_id, $level) { 
			
			// pega todos os filhos de $parent_id
			$this->db->where('parent = '.$parent_id);
			$this->db->order_by('ordering asc, title asc, id asc');
			$query = $this->db->get('tb_menus');
			
			// atribui todos os filhos para a variável $menu_tree
			foreach($query->result() as $row) {
				
				// atribui cada valor da categoria em um array
				$this->menu_tree[] = array(
					'id' => $row->id,
					'title' => $row->title,
					'indented_title' => str_repeat('&nbsp;&nbsp;&nbsp;', $level).'- '.$row->title,
					'parent' => $row->parent,
					'description' => $row->description,
					'level' => $level,
				);
				
				// chama esta função novamente para mostrar os filhos deste filho
				$this->get_children_as_list($row->id, $level+1); 
				
			}
		}
		
		// Função que retorna o número de filhos de um item
		public function get_num_childrens($id, $query = NULL){
			
			// Caso a query não tenha sido informada, cria-o agora
			if (!$query){
				$this->db->select('t1.*, t2.title as parent_title, t2.alias as parent_alias');
				$this->db->from('tb_menus t1');
				$this->db->join('tb_menus t2', 't1.parent = t2.id', 'left');
				
				$this->db->order_by('ordering asc, title asc, id asc');
				$query = $this->db->get();
				$query = $query->result_array();
				
			}
			
			$length = 0;
			
			foreach($query as $row) {
				if ($row['parent'] == $id) {
					$length++; 
				}
			}
			// retorna a quantidade de registros com item pai $id
			return $length;
			
		}
		
		public function get_menu_tree($parent_id, $level, $type = 'menu'){
			
			if ($type == 'menu'){
				$this->get_children_as_menu($parent_id, $level);
				// remove o último </li> da string
				return substr_replace($this->menu_tree,'',-6);
			}
			else if ($type == 'list'){
				$this->get_children_as_list($parent_id, $level);
				return $this->menu_tree;
			}
			
			
		}
		
		
		
		public function get_path($menu_id){
			
			// look up the parent of this node
			$this->db->select('t1.*, t2.id as parent_menu_id, t2.title as parent_menu_title');
			$this->db->from('categories t1');
			$this->db->join('categories t2', 't1.parent_menu = t2.id', 'left');
			$this->db->where('t1.id = '.$menu_id);
			$query = $this->db->get('categories');
			
			$row = $query->row();
			
			// save the path in this array
			$path = array();
			
			// only continue if this $node isn't the root node
			// (that's the node with no parent)
			if ($row->parent_menu) {
				
				// the last part of the path to $node, is the name of the parent of $node
				$path[] = array(
					'id' => $row->parent_menu_id,
					'title' => $row->parent_menu_title,
				);
				
				// we should add the path to the parent of this node to the path
				$path = array_merge($this->get_path($row->parent_menu), $path);
				
			}
			
			// return the path
			return $path;
			
		}
		
		
		
		
		public function get_component($condition = NULL){
			if ($condition){
				$this->db->select('*');
				$this->db->from('tb_components');
				$this->db->where($condition);
				// limitando o resultando em apenas 1
				$this->db->limit(1);
				return $this->db->get();
			}
			else {
				return FALSE;
			}
		}
		
		
		
		
		
		
		
		
		
		public function get_current_menu_item(){
			// verificando se o id do item de menu existe na uri, e se é inteiro
			if ( $menu_item = $this->menus->get_menu_item( current_menu_id() ) ){
				return $menu_item->row();
			}
			else return 0;
		}
		
		
		
		
		public function fetch(){
			$this->db->select('t1.*, t2.title as parent_menu_title');
			$this->db->from('categories t1');
			$this->db->join('categories t2', 't1.parent_menu = t2.id', 'left');
			$this->db->order_by('title asc, id asc');
			return $this->db->get();
		}
		
		public function fetch_by_id($id = NULL){
			if ($id!=NULL){
				$this->db->select('t1.*, t2.title as parent_menu_title');
				$this->db->from('categories t1');
				$this->db->join('categories t2', 't1.parent_menu = t2.id', 'left');
				$this->db->where('t1.id',$id);
				// limitando o resultando em apenas 1
				$this->db->limit(1);
				return $this->db->get('categories');
			}
			else {
				return false;
			}
		}
		
		public function insert($data = NULL){
			if ($data != NULL){
				if ($this->db->insert('categories',$data)){
					// confirm the insertion for controller
					return $this->db->insert_id();
				}
				else {
					// case the insertion fails, return false
					return FALSE;
				}
			}
			else {
				redirect('categories');
			}
		}
		
		public function update($data = NULL,$condition = NULL){
			if ($data != null && $condition != NULL){
				if ($this->db->update('categories',$data,$condition)){
					// confirm update for controller
					return TRUE;
				}
				else {
					// case update fails, return false
					return FALSE;
				}
			}
			redirect('categories');
		}
		
		public function delete($condition = NULL){
			if ($condition != null){
				if ($this->db->delete('categories',$condition)){
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
	}
