<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contacts_common_model extends CI_Model{
	
	public function fetch($where_condition = NULL){
		
		$this->db->select('t1.*, t2.title as category_title, t3.username as username, t3.name as name');
		$this->db->from('tb_articles t1');
		$this->db->join('tb_articles_categories t2', 't1.category_id = t2.id', 'left');
		$this->db->join('tb_users t3', 't1.created_by_id = t3.id', 'left');
		if ($where_condition){
			$this->db->where($where_condition);
		}
		return $this->db->get();
		
	}
	
	public function get_contacts( $f_params = NULL ){
		
		// inicializando as variáveis
		$where_condition =
		$or_where_condition =
		$limit =
		$offset =
		$order_by =
		$order_by_escape =
		$return_type = NULL;
		
		// atribuindo valores às variávies
		
		$where_condition =						isset( $f_params['where_condition'] ) ? $f_params['where_condition'] : NULL;
		$or_where_condition =					isset( $f_params['or_where_condition'] ) ? $f_params['or_where_condition'] : NULL;
		$limit =								isset( $f_params['limit'] ) ? $f_params['limit'] : NULL;
		$offset =								isset( $f_params['offset'] ) ? $f_params['offset'] : NULL;
		$order_by =								isset( $f_params['order_by'] ) ? $f_params['order_by'] : 't1.name asc, t1.id asc';
		$order_by_escape =						isset( $f_params['order_by_escape'] ) ? $f_params['order_by_escape'] : TRUE;
		$return_type =							isset( $f_params['return_type'] ) ? $f_params['return_type'] : 'get';
		
		
		$this->db->select('
			
			t1.*,
			
		');
		
		$this->db->from('tb_contacts t1');
		
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
	
	
	
	
	public function get_link_contact_details( $menu_item_id = 0, $contact_id = NULL ){
		if ( $contact_id ){
			
			return 'contacts/index/contact_details/'.$menu_item_id . '/' . $contact_id;
			
		}
		else{
			
			return FALSE;
			
		}
	}
	public function get_link_articles_list( $menu_item_id = 0, $cat_id = NULL, $user_id = 0, $pagination = FALSE ){
		
		if ( isset( $cat_id ) ){
			
			$out = 'articles/index/articles_list/' . $menu_item_id . '/' . $cat_id . '/' . $user_id . '/';
			
			if ( $pagination ){
				
				$out .= '%p%/%ipp%';
				
			}
			
			return $out;
			
		}
		else{
			
			return FALSE;
			
		}
		
	}
	
}
