<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menus_model extends CI_Model{
	
	private $menu_tree = '';
	private $next_has_children = FALSE;
	
	// Essa função sempre gera o html em formato de menu, sempre com um </li> no final,
	// mas no retorno da função get_menu_tree ele é retirado
	private function get_children_as_menu($parent_id, $level, $menu_type = NULL) { 
		
		if ($menu_type){
			// pega todos os filhos de $parent_id
			$this->db->where('parent = '.$parent_id);
			$this->db->order_by('ordering asc, title asc, id asc');
			$query = $this->db->get('tb_menus');
			
			// variável que determina se o item atual possui filhos
			$has_children = $this->get_num_childrens($parent_id);
			
			// se o item atual possui filhos, insere a primeira <ul>
			if ($has_children) $this->menu_tree .= "<ul>\n";
			
			// display each child
			foreach($query->result() as $row) {
				
				// verifica se este filho possui outros filhos
				$next_has_children = $this->get_num_childrens($row->id)?TRUE:FALSE;
				
				// indent and display the title of this child
				$this->menu_tree .= '<li class="level-'.$level.'"><a href="'.($row->home?site_url():get_url($row->link, $row->id)).'">'.$row->title.($next_has_children?"\n":'</a></li>'."\n"); 
				
				// chama esta função novamente para mostrar os filhos deste filho
				$this->get_children_as_menu($row->id, $level+1); 
				
			}
			if ($has_children) $this->menu_tree .= $next_has_children?"</ul>\n":"</ul>\n</li>\n";
		}
		else {
			return FALSE;
		}
	}
	
	private $menu_tree_array = array();
	
	// Essa função sempre gera uma lista para comboboxes em formato de array
	// O parâmetro $query serve para economizar consultas ao bando de dados, caso a função tenha que se "auto chamar" novamente
	private function get_children_as_list($parent_id, $level, $menu_type = NULL, $query = NULL) { 
		
		if ($menu_type){
			
			// Caso a query não tenha sido informada, executa isto
			if (!$query){
				// pega todos os filhos de $parent_id
				$this->db->select('t1.*, t2.title as component_title, t2.unique_name as component_alias');
				$this->db->from('tb_menus t1');
				$this->db->join('tb_components t2', 't1.component_id = t2.id', 'left');
				
				$this->db->where('t1.menu_type_id', $menu_type);
				$this->db->order_by('ordering asc, title asc, id asc');
				$query = $this->db->get();
				$query = $query->result_array();
				
			}
			// atribui todos os filhos para a variável $menu_tree
			foreach($query as $row) {
				if ($row['parent'] == $parent_id) {
					// atribui cada valor da categoria em um array
					$this->menu_tree[] = array(
						'id' => $row['id'],
						'alias' => $row['alias'],
						'title' => $row['title'],
						'link' => $row['link'],
						'type' => $row['type'],
						'component_id' => $row['component_id'],
						'component_alias' => $row['component_alias'],
						'component_title' => $row['component_title'],
						'component_item' => $row['component_item'],
						'status' => $row['status'],
						'menu_type_id' => $row['menu_type_id'],
						'home' => $row['home'],
						'ordering' => $row['ordering'],
						'indented_title' => str_repeat( '&nbsp;' , $level * 4 + 4). lang( 'indented_symbol' ).$row['title'],
						'parent' => $row['parent'],
						'description' => $row['description'],
						'level' => $level,
					);
					
					// chama esta função novamente para mostrar os filhos deste filho
					$this->get_children_as_list($row['id'], $level+1, $menu_type, $query); 
				}
			}
		}
		else {
			return FALSE;
		}
	}
	
	// Função que retorna o número de filhos de um item
	public function get_num_childrens($id){
		
		$this->db->where('parent = '.$id);
		$this->db->order_by('ordering asc, title asc, id asc');
		$this->db->from('tb_menus');
		// retorna a quantidade de registros com item pai $id
		return $this->db->count_all_results();
		
	}
	
	public function get_menu_tree($parent_id, $level, $type = 'menu', $menu_type = NULL){
		
		if ($menu_type) {
			if ($type == 'menu'){
				$this->get_children_as_menu($parent_id, $level, $menu_type);
				// remove o último </li> da string
				return substr_replace($this->menu_tree,'',-6);
			}
			else if ($type == 'list'){
				$this->get_children_as_list($parent_id, $level, $menu_type);
				return $this->menu_tree;
			}
		}
		else {
			return FALSE;
		}
		
	}
	
	
	
	
	public function get_menu_types(){
		$this->db->from('tb_menu_types');
		$this->db->order_by('title asc, id asc');
		return $this->db->get();
	}
	
	public function get_menu_type($id = NULL){
		if ($id!=NULL){
			$this->db->select('*');
			$this->db->from('tb_menu_types');
			$this->db->where('id',$id);
			// limitando o resultando em apenas 1
			$this->db->limit(1);
			return $this->db->get();
		}
		else {
			return FALSE;
		}
	}
	
	public function get_menu_item( $id = NULL ){
		if ($id!=NULL){
			$this->db->select('t1.*, t2.title as menu_type_title, t3.title as component_title, t3.unique_name as component_alias');
			$this->db->from('tb_menus t1');
			$this->db->join('tb_menu_types t2', 't1.menu_type_id = t2.id', 'left');
			$this->db->join('tb_components t3', 't1.component_id = t3.id', 'left');
			$this->db->where('t1.id',$id);
			// limitando o resultando em apenas 1
			$this->db->limit(1);
			return $this->db->get();
		}
		else {
			return FALSE;
		}
	}
	
	public function insert($data = NULL){
		if ($data != NULL){
			if ($this->db->insert('tb_menus',$data)){
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
		if ($data != NULL && $condition != NULL){
			if ($this->db->update('tb_menus',$data,$condition)){
				// confirm update for controller
				return TRUE;
			}
			else {
				// case update fails, return false
				return FALSE;
			}
		}
		redirect('admin/menus');
	}
	
	public function set_home_page( $menu_item_id = NULL ){
		
		if ( $menu_item_id != NULL ){
			
			$menu_item = $this->menus->get_menu_item( $menu_item_id );
			
			if ( $this->db->update( 'tb_menus', array( 'home' => '0' ), array( 'home' => '1' ) ) ){
				
				if ( $this->db->update( 'tb_menus', array( 'home' => '1', 'status' => '1' ), array( 'id' => $menu_item_id ) ) ){
					
					$this->db->update( 'tb_urls', array( 'target' => $menu_item[ 'link' ] ), array( 'sef_url' => 'default_controller' ) );
					
					if ( ! $this->urls_common_model->update_urls_cache() ){
						
						return FALSE;
						
					}
					
					// confirm update for controller
					return TRUE;
					
				}
				else {
					
					// case update fails, return false
					return FALSE;
					
				}
				
			}
			else {
				
				// case update fails, return false
				return FALSE;
				
			}
			
		}
		
		msg( ( 'error' ), 'error' );
		redirect_last_url();
		
	}
	
	public function delete($condition = NULL){
		if ($condition != null){
			if ($this->db->delete('tb_menus',$condition)){
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
	
	public function insert_menu_type($data = NULL){
		if ($data != NULL){
			if ($this->db->insert('tb_menu_types',$data)){
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
	
	public function update_menu_type($data = NULL,$condition = NULL){
		if ($data != NULL && $condition != NULL){
			if ($this->db->update('tb_menu_types',$data,$condition)){
				// confirm update for controller
				return TRUE;
			}
			else {
				// case update fails, return false
				return FALSE;
			}
		}
		redirect('admin/menus');
	}
	
	public function delete_menu_type($condition = NULL){
		if ($condition != null){
			if ($this->db->delete('tb_menu_types',$condition)){
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
	
	
	
	
	public function get_link_blank_content( $menu_item_id = NULL ){
		
		return 'main/bc/0/'.$menu_item_id;
		
	}
	
	public function get_link_html_content( $menu_item_id = NULL ){
		
		return 'main/hc/0/' . $menu_item_id;
		
	}
	
	
	
	
	public function get_menu_item_params(){
		
		$params = get_params_spec_from_xml( APPPATH . 'controllers/admin/com_menus/menu_item_params.xml' );
		
		return $params;
		
	}
}
