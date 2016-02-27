<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Companies_model extends CI_Model{
	
	/**************************************************/
	/******************** Companies *******************/
	
	public function get_companies( $condition = NULL, $limit = NULL, $offset = NULL, $return_type = 'get', $order_by = 't1.trading_name asc, t1.id asc' ){
		
		$this->db->select('
		
			t1.*,
			
			t2.name as user_name,
			t2.username,
			
		');
		$this->db->from('tb_companies t1');
		$this->db->join('tb_users t2', 't1.user_id = t2.id', 'left');
		$this->db->order_by( $order_by );
		
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
	public function insert_company($data = NULL){
		if ($data != NULL){
			if ($this->db->insert('tb_companies',$data)){
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
	public function update_company($data = NULL, $condition = NULL){
		if ($data != NULL && $condition != NULL){
			if ($this->db->update('tb_companies',$data,$condition)){
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
	public function delete_company($condition = NULL){
		if ($condition != null){
			if ($this->db->delete('tb_companies',$condition)){
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
	
	/**************************************************/
	/*************** Companies contacts ***************/
	
	public function company_contact_operation( $data = NULL ){
		
		if ( $data ){
			
			// se o array recebido contiver o id da empresa e os dados do contato, continua
			/*
			 * Dados do contato necessários:
			 * 		id
			 * 		title
			 */
			if ( isset( $data['company_id'] )
				AND isset( $data['contact_data'] )
				AND isset( $data['contact_data']['id'] )
				AND isset( $data['contact_data']['title'] )
				AND isset( $data['contact_data']['name'] )
				AND isset( $data['op'] )
			){
				
				if ( $company = $this->get_companies( array( 't1.id' => $data['company_id'] ), 1 )->row_array() ){
					
					$company['contacts'] = json_decode( $company['contacts'], TRUE );
					
					if ( ! is_array( $company['contacts'] ) ){
						
						$company['contacts'] = array();
						
					}
					
					// se a ação refere-se a adicionar o contato...
					if ( $data['op'] == 'add' ) {
						
						$next_contact_key = count( $company['contacts'] ) + 1;
						
						$data['contact_data']['key'] = $next_contact_key;
						
						$company['contacts'][$next_contact_key] = $data['contact_data'];
						
						foreach ($company['contacts'][$next_contact_key] as $key => $value) {
							
							$company['contacts'][$next_contact_key][$key] = (string)$value;
							
						}
						
						$db_data['contacts'] = json_encode( $company['contacts'] );
						
						if ( $this->update_company( $db_data, array( 'id' => $data['company_id'] ) ) ){
							
							return TRUE;
							
						}
						
					}
					// se a ação refere-se a editar o contato...
					else if ( $data['op'] == 'edit' ) {
						
						$ck = $data['contact_data']['key'];
						
						$company['contacts'][$ck] = $data['contact_data'];
						
						foreach ($company['contacts'][$ck] as $key => $value) {
							
							$company['contacts'][$ck][$key] = (string)$value;
							
						}
						
						$db_data['contacts'] = json_encode( $company['contacts'] );
						
						if ( $this->update_company( $db_data, array( 'id' => $data['company_id'] ) ) ){
							
							return TRUE;
							
						}
						
					}
					// se a ação refere-se a remover o contato...
					else if ( $data['op'] == 'remove' ) {
						
						$ck = $data['contact_data']['key'];
						
						unset ( $company['contacts'][$ck] );
						
						$db_data['contacts'] = json_encode( $company['contacts'] );
						
						if ( $this->update_company( $db_data, array( 'id' => $data['company_id'] ) ) ){
							
							return TRUE;
							
						}
						
					}
					
				}
				
			}
			
		}
		
		return FALSE;
		
	}
	
	/*************** Companies contacts ***************/
	/**************************************************/
	
	/**************************************************/
	/***************** Products search ****************/
	
	public function get_companies_search_results( $where_condition = NULL, $or_where_condition = NULL, $limit = NULL, $offset = NULL, $return_type = 'get', $order_by = 't1.trading_name asc, t1.id asc', $order_by_escape = TRUE ){
		
		$this->db->select('t1.*, t2.name as user_name, t2.username');
		$this->db->from('tb_companies t1');
		$this->db->join('tb_users t2', 't1.user_id = t2.id', 'left');
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
	
	/***************** Products search ****************/
	/**************************************************/
	
}
