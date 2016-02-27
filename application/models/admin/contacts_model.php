<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contacts_model extends CI_Model{
	
	/**************************************************/
	/********************* Contacts *******************/
	
	public function get_contacts($condition = NULL, $limit = NULL, $offset = NULL, $return_type = 'get', $or_where_condition = NULL, $where_in = NULL){
		
		$this->db->select('t1.*, t2.name as user_name_owner, t3.name as user_name_assoc, t3.username as username_assoc');
		$this->db->from('tb_contacts t1');
		$this->db->join('tb_users t2', 't1.user_id = t2.id', 'left');
		$this->db->join('tb_users t3', 't1.user_id_assoc = t3.id', 'left');
		$this->db->order_by('t1.name asc, t1.id asc');
		
		if ($condition){
			$this->db->where($condition);
		}
		if ($or_where_condition){
			print_r($or_where_condition);
			if(gettype($or_where_condition) === 'array'){
				foreach ($or_where_condition as $key => $value) {
					if(gettype($or_where_condition) === 'array' AND (strpos($key,'fake_index_') !== FALSE) ){
						$this->db->or_where($value);
					}
					else $this->db->or_where($key, $value);
				}
			}
			else $this->db->or_where($or_where_condition);
		}
		if ($where_in){
			$this->db->where_in($where_in[0], $where_in[1]);
		}
		if ($limit){
			$this->db->limit($limit, $offset?$offset:NULL);
		}
		if ( $return_type === 'count_all_results' ){
			return $this->db->count_all_results();
		}
		
		return $this->db->get();
	}
	public function insert_contact($data = NULL){
		if ($data != NULL){
			if ($this->db->insert('tb_contacts',$data)){
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
	public function update_contact($data = NULL, $condition = NULL){
		if ($data != NULL && $condition != NULL){
			if ($this->db->update('tb_contacts',$data,$condition)){
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
	public function delete_contact($condition = NULL){
		if ($condition != null){
			if ($this->db->delete('tb_contacts',$condition)){
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
	
	/********************* Contacts *******************/
	/**************************************************/
	
	/**************************************************/
	/***************** Contacts search ****************/
	
	public function get_contacts_search_results($where_condition = NULL, $or_where_condition = NULL, $limit = NULL, $offset = NULL, $return_type = 'get', $order_by = 't1.name asc, t1.id asc', $order_by_escape = TRUE ){
		
		$this->db->select('
			
			t1.*,
			
			t2.name as user_name_owner,
			
			t3.name as user_name_assoc,
			t3.username as username_assoc');
		
		$this->db->from('tb_contacts t1');
		$this->db->join('tb_users t2', 't1.user_id = t2.id', 'left');
		$this->db->join('tb_users t3', 't1.user_id_assoc = t3.id', 'left');
		$this->db->order_by($order_by, '', $order_by_escape);
		
		if ($where_condition){
			if(gettype($where_condition) === 'array'){
				foreach ($where_condition as $key => $value) {
					if(gettype($where_condition) === 'array' AND (strpos($key,'fake_index_') !== FALSE) ){
						$this->db->where($value);
					}
					else $this->db->where($key, $value);
				}
			}
			else $this->db->where($where_condition);
		}
		if ($or_where_condition){
			if(gettype($or_where_condition) === 'array'){
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
		if ($limit){
			$this->db->limit($limit, $offset?$offset:NULL);
		}
		
		return $this->db->get();
	}
	
	/***************** Contacts search ****************/
	/**************************************************/
	
	public function get_component_url_admin(){
		
		return RELATIVE_BASE_URL . '/' . ADMIN_ALIAS . '/contacts';
		
	}
	
	public function menu_item_contact_details(){
		
		if ( isset( $this->mcm->system_params[ 'email_config_enabled' ] ) AND $this->mcm->system_params[ 'email_config_enabled' ] ){
			
			$params = get_params_spec_from_xml( APPPATH . 'controllers/admin/com_contacts/menu_item_contact_details.xml' );
			
			// obtendo a lista de layouts
			
			// carregando os layouts do tema atual
			$layouts_contact_details = dir_list_to_array( THEMES_PATH . site_theme_components_views_path() . get_class_name( get_class() ) . DS . 'index' . DS . 'contact_details' );
			// carregando os layouts do diretório de views padrão
			$layouts_contact_details = array_merge( $layouts_contact_details, dir_list_to_array( VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . get_class_name( get_class() ) . DS . 'index' . DS . 'contact_details' ) );
			
			$contacts = $this->get_contacts()->result_array();
			
			$contacts_options = array();
			
			foreach ( $contacts as $contact ){
				
				$contacts_options[ $contact[ 'id' ] ] = $contact[ 'name' ];
				
			}
			
			foreach ( $params['params_spec']['contact_details'] as $key => $element ) {
				
				if ( $element['name'] == 'contact_id' ){
					
					$spec_options = array();
					
					if ( isset($params['params_spec']['contact_details'][$key]['options']) )
						$spec_options = $params['params_spec']['contact_details'][$key]['options'];
					
					$params['params_spec']['contact_details'][$key]['options'] = is_array( $spec_options ) ? $spec_options + $contacts_options : $contacts_options;
					
				}
				
			}
			
			foreach ( $params['params_spec']['contact_details'] as $key => $element ) {
				
				if ( $element['name'] == 'contact_details_layout' ){
					
					$spec_options = array();
					
					if ( isset($params['params_spec']['contact_details'][$key]['options']) )
						$spec_options = $params['params_spec']['contact_details'][$key]['options'];
					
					$params['params_spec']['contact_details'][$key]['options'] = is_array($spec_options) ? $spec_options + $layouts_contact_details : $layouts_contact_details;
					
				}
				
			}
			
			//print_r($params);
			
			return $params;
			
		}
		else {
			
			msg( ('email_config_not_enabled' ), 'title' );
			msg( validation_errors( '<div class="error">', '</div>' ), 'error' );
			
			redirect( get_last_url() );
			
		}
		
	}
	
	public function menu_item_get_link_contact_details($menu_item_id = NULL, $params = NULL){
		
		return 'contacts/index/contact_details/'.$menu_item_id . '/' . $params[ 'contact_id' ];
		
	}
	
}
