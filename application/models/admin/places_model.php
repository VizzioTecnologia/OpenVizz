<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Places_model extends CI_Model{
		
		/**************************************************/
		/******************** Countries *******************/
		
		public function get_countries($condition = NULL, $limit = NULL, $offset = NULL, $return_type = 'get'){
			
			$this->db->select('*');
			$this->db->from('tb_countries');
			$this->db->order_by('title asc, id asc');
			
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
		public function insert_country($data = NULL){
			if ($data != NULL){
				if ($this->db->insert('tb_countries',$data)){
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
		public function update_country($data = NULL, $condition = NULL){
			if ($data != NULL && $condition != NULL){
				if ($this->db->update('tb_countries',$data,$condition)){
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
		public function delete_country($condition = NULL){
			if ($condition != null){
				if ($this->db->delete('tb_countries',$condition)){
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
		
		/******************** Countries *******************/
		/**************************************************/
		
		/**************************************************/
		/********************** States ********************/
		
		public function get_states($condition = NULL, $limit = NULL, $offset = NULL, $return_type = 'get'){
			
			$this->db->select('t1.*, t2.title as country_title, t2.alias as country_alias, t2.status as country_status');
			$this->db->from('tb_states t1');
			$this->db->join('tb_countries t2', 't1.country_id = t2.id', 'left');
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
		public function insert_state($data = NULL){
			if ($data != NULL){
				if ($this->db->insert('tb_states',$data)){
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
		public function update_state($data = NULL, $condition = NULL){
			if ($data != NULL && $condition != NULL){
				if ($this->db->update('tb_states',$data,$condition)){
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
		public function delete_state($condition = NULL){
			if ($condition != null){
				if ($this->db->delete('tb_states',$condition)){
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
		
		/********************** States ********************/
		/**************************************************/
		
		/**************************************************/
		/********************** Cities ********************/
		
		public function get_cities($condition = NULL, $limit = NULL, $offset = NULL, $return_type = 'get'){
			
			$this->db->select('t1.*, t2.title as state_title, t2.acronym as state_acronym, t2.status as state_status, t3.id as country_id, t3.title as country_title, t3.alias as country_alias, t3.status as country_status');
			$this->db->from('tb_cities t1');
			$this->db->join('tb_states t2', 't1.state_id = t2.id', 'left');
			$this->db->join('tb_countries t3', 't2.country_id = t3.id', 'left');
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
		public function insert_city($data = NULL){
			if ($data != NULL){
				if ($this->db->insert('tb_cities',$data)){
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
		public function update_city($data = NULL, $condition = NULL){
			if ($data != NULL && $condition != NULL){
				if ($this->db->update('tb_cities',$data,$condition)){
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
		public function delete_city($condition = NULL){
			if ($condition != null){
				if ($this->db->delete('tb_cities',$condition)){
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
		
		/********************** Cities ********************/
		/**************************************************/
		
		/**************************************************/
		/****************** Neighborhoods *****************/
		
		public function get_neighborhoods($condition = NULL, $limit = NULL, $offset = NULL, $return_type = 'get'){
			
			$this->db->select('t1.*, t2.title as city_title, t3.id as state_id, t3.title as state_title, t3.acronym as state_acronym, t3.status as state_status, t4.id as country_id, t4.title as country_title, t4.alias as country_alias, t4.status as country_status');
			$this->db->from('tb_neighborhoods t1');
			$this->db->join('tb_cities t2', 't1.city_id = t2.id', 'left');
			$this->db->join('tb_states t3', 't2.state_id = t3.id', 'left');
			$this->db->join('tb_countries t4', 't3.country_id = t4.id', 'left');
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
		public function insert_neighborhood($data = NULL){
			if ($data != NULL){
				if ($this->db->insert('tb_neighborhoods',$data)){
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
		public function update_neighborhood($data = NULL, $condition = NULL){
			if ($data != NULL && $condition != NULL){
				if ($this->db->update('tb_neighborhoods',$data,$condition)){
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
		public function delete_neighborhood($condition = NULL){
			if ($condition != null){
				if ($this->db->delete('tb_neighborhoods',$condition)){
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
		
		/****************** Neighborhoods *****************/
		/**************************************************/
		
		/**************************************************/
		/******************* Public areas *****************/
		
		public function get_public_areas($condition = NULL, $limit = NULL, $offset = NULL, $return_type = 'get'){
			
			$this->db->select('t1.*, t2.title as neighborhood_title, t3.id as city_id, t3.title as city_title, t4.id as state_id, t4.title as state_title, t4.acronym as state_acronym, t4.status as state_status, t5.id as country_id, t5.title as country_title, t5.alias as country_alias, t5.status as country_status');
			$this->db->from('tb_public_areas t1');
			$this->db->join('tb_neighborhoods t2', 't1.neighborhood_id = t2.id', 'left');
			$this->db->join('tb_cities t3', 't2.city_id = t3.id', 'left');
			$this->db->join('tb_states t4', 't3.state_id = t4.id', 'left');
			$this->db->join('tb_countries t5', 't4.country_id = t5.id', 'left');
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
		public function insert_public_area($data = NULL){
			if ($data != NULL){
				if ($this->db->insert('tb_public_areas',$data)){
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
		public function update_public_area($data = NULL, $condition = NULL){
			if ($data != NULL && $condition != NULL){
				if ($this->db->update('tb_public_areas',$data,$condition)){
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
		public function delete_public_area($condition = NULL){
			if ($condition != null){
				if ($this->db->delete('tb_public_areas',$condition)){
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
		
		/******************* Public areas *****************/
		/**************************************************/
		
		/**************************************************/
		/********************** Search ********************/
		
		public function get_search_results($where_condition = NULL, $or_where_condition = NULL, $limit = NULL, $offset = NULL, $return_type = 'get'){
			
			$this->db->select('t1.*, t2.title as neighborhood_title,
				t3.id as city_id, t3.title as city_title,
				t4.id as state_id, t4.title as state_title, t4.acronym as state_acronym, t4.status as state_status,
				t5.id as country_id, t5.title as country_title, t5.alias as country_alias, t5.status as country_status');
			$this->db->from('tb_public_areas t1');
			$this->db->join('tb_neighborhoods t2', 't1.neighborhood_id = t2.id', 'left');
			$this->db->join('tb_cities t3', 't2.city_id = t3.id', 'left');
			$this->db->join('tb_states t4', 't3.state_id = t4.id', 'left');
			$this->db->join('tb_countries t5', 't4.country_id = t5.id', 'left');
			$this->db->order_by('t1.title asc, t1.id asc');
			
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
		
		/********************** Search ********************/
		/**************************************************/
		
	}
