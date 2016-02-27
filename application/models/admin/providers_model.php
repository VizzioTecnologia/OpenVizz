<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Providers_model extends CI_Model{
		
		/**************************************************/
		/******************** Companies *******************/
		
		public function get_providers($condition = NULL, $limit = NULL, $offset = NULL, $return_type = 'get'){
			
			$this->db->select('
				
				t1.*,
				
				t2.name as user_name,
				t2.username,
				
				t3.trading_name,
				t3.company_name,
				t3.state_registration,
				t3.sic,
				t3.corporate_tax_register,
				t3.addresses,
				t3.websites,
				t3.logo,
				t3.logo_thumb,
			
			');
			$this->db->from('tb_providers t1');
			$this->db->join('tb_users t2', 't1.user_id = t2.id', 'left');
			$this->db->join('tb_companies t3', 't1.company_id = t3.id', 'left');
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
		public function insert_provider($data = NULL){
			if ($data != NULL){
				if ($this->db->insert('tb_providers',$data)){
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
		public function update_provider($data = NULL, $condition = NULL){
			if ($data != NULL && $condition != NULL){
				if ($this->db->update('tb_providers',$data,$condition)){
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
		public function delete_provider($condition = NULL){
			if ($condition != null){
				if ($this->db->delete('tb_providers',$condition)){
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
		
		/******************** Companies *******************/
		/**************************************************/
		
	}
