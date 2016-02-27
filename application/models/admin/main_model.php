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
				
				if ( ! isset( $params[ 'dst' ] ) ){
					
					$params[ 'dst' ] = TRUE;
					
				}
				
			}
			
			return $params;
			
		}
		
		public function get_components($condition = NULL){
			$this->db->select('*');
			$this->db->from('tb_components');
			if ($condition){
				$this->db->where($condition);
			}
			$this->db->order_by('title asc, id asc');
			return $this->db->get();
		}
		public function get_component_items($id = NULL){
			if ($id!=NULL){
				$this->db->select('t1.*, t2.unique_name as component_alias, t2.title as component_title');
				$this->db->from('tb_component_items t1');
				$this->db->join('tb_components t2', 't1.component_id = t2.id', 'left');
				$this->db->order_by('title asc, id asc');
				$this->db->where('t1.component_id',$id);
				return $this->db->get();
			}
			else {
				return FALSE;
			}
		}
		public function get_component( $condition = NULL ){
			
			if ( $condition ){
				$this->db->select( '*' );
				$this->db->from( 'tb_components' );
				$this->db->where( $condition );
				
				// limitando o resultando em apenas 1
				$this->db->limit( 1 );
				
				return $this->db->get();
				
			}
			else {
				return FALSE;
			}
		}
		public function get_component_item($id){
			if ($id!=NULL){
				$this->db->select('t1.*, t2.title as component_title, t2.unique_name as component_alias');
				$this->db->from('tb_component_items t1');
				$this->db->join('tb_components t2', 't1.component_id = t2.id', 'left');
				$this->db->where('t1.id',$id);
				// limitando o resultando em apenas 1
				$this->db->limit(1);
				return $this->db->get();
			}
			else {
				return false;
			}
		}
		public function update_component($data = NULL,$condition = NULL){
			if ($data != NULL && $condition != NULL){
				if ($this->db->update('tb_components',$data,$condition)){
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
		
		
		
		/* General params */
		public function get_config_params(){
			
			$params = get_params_spec_from_xml( APPPATH . 'controllers/admin/com_main/config.xml' );
			
			// idiomas
			$languages_list = dir_list_to_array( 'application/language' );
			$admin_themes_list = dir_list_to_array( 'themes/admin' );
			$site_themes_list = dir_list_to_array( 'themes/site' );
			
			foreach ( $params['params_spec']['admin_config'] as $key => $element ) {
				
				if ( $element['name'] == 'admin_language' ){
					
					$spec_options = array();
					
					if ( isset($params['params_spec']['admin_config'][$key]['options']) )
						$spec_options = $params['params_spec']['admin_config'][$key]['options'];
					
					$params['params_spec']['admin_config'][$key]['options'] = is_array($spec_options) ? $spec_options + $languages_list : $languages_list;
					
				}
				
				if ( $element['name'] == 'admin_theme' ){
					
					$spec_options = array();
					
					if ( isset($params['params_spec']['admin_config'][$key]['options']) )
						$spec_options = $params['params_spec']['admin_config'][$key]['options'];
					
					$params['params_spec']['admin_config'][$key]['options'] = is_array($spec_options) ? $spec_options + $admin_themes_list : $admin_themes_list;
					
				}
				
			}
			
			foreach ( $params['params_spec']['site_config'] as $key => $element ) {
				
				if ( $element['name'] == 'site_language' ){
					
					$spec_options = array();
					
					if ( isset($params['params_spec']['site_config'][$key]['options']) )
						$spec_options = $params['params_spec']['site_config'][$key]['options'];
					
					$params['params_spec']['site_config'][$key]['options'] = is_array($spec_options) ? $spec_options + $languages_list : $languages_list;
					
				}
				
				if ( $element['name'] == 'site_theme' ){
					
					$spec_options = array();
					
					if ( isset($params['params_spec']['site_config'][$key]['options']) )
						$spec_options = $params['params_spec']['site_config'][$key]['options'];
					
					$params['params_spec']['site_config'][$key]['options'] = is_array($spec_options) ? $spec_options + $site_themes_list : $site_themes_list;
					
				}
				
			}
			
			// editores de texto
			
			foreach ( $this->plugins->get_plugins( 'js_text_editor' ) as $key => $plugin ) {
				
				if ( $plugin['type'] == 'js_text_editor' AND $plugin['status'] == 1 ){
					
					$js_text_editors_list[ $plugin['name'] ] = lang( $plugin['title'] );
					
				}
				
			}
			
			foreach ( $params['params_spec']['system_config'] as $key => $element ) {
				
				if ( $element['name'] == 'js_text_editor' ){
					
					$spec_options = array();
					
					if ( isset( $params['params_spec']['system_config'][$key]['options'] ) )
						$spec_options = $params['params_spec']['system_config'][$key]['options'];
					
					$params['params_spec']['system_config'][$key]['options'] = is_array( $spec_options ) ? $spec_options + $js_text_editors_list : $js_text_editors_list;
					
				}
				
			}
			
			// print_r($params);
			
			$after_content_params = $this->plugins->get_params_spec( NULL, 'after_content' );
			
			$params = array_merge_recursive( $params, $after_content_params );
			
			return $params;
		}
		
		
		
		/**************************************************/
		/******************** Temp data *******************/
		
		public function insert_temp_data($reference = NULL, $data = NULL){
			if ( $reference AND $data ){
				
				$data = array(
					'user_id' => $this->users->user_data['id'],
					'reference' => $reference,
					'data' => $data,
				);
				
				if ($this->db->insert('tb_tmp_data',$data)){
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
		public function get_temp_data($condition = NULL){
			if ($condition){
				$this->db->select('*');
				$this->db->from('tb_tmp_data');
				$this->db->where($condition);
				// limitando o resultando em apenas 1
				$this->db->limit(1);
				return $this->db->get();
			}
			else {
				return FALSE;
			}
		}
		public function update_temp_data($data = NULL, $condition = NULL){
			if ($data != NULL && $condition != NULL){
				if ($this->db->update('tb_tmp_data',$data,$condition)){
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
		public function delete_temp_data($condition = NULL){
			if ($condition != null){
				if ($this->db->delete('tb_tmp_data',$condition)){
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
		
		/******************** Temp data *******************/
		/**************************************************/
		
		public function get_params_teste(){
			
			$params = get_params_spec_from_xml(APPPATH.'controllers/admin/com_main/testes.xml');
			
			return $params;
			
		}
		
	}
