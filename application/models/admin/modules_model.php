<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modules_model extends CI_Model{
	
	// get modules for management
	public function mng_get_modules( $f_params = NULL ){
		
		// inicializando as variáveis
		$where_condition =						isset( $f_params['where_condition'] ) ? $f_params['where_condition'] : NULL;
		$or_where_condition =					isset( $f_params['or_where_condition'] ) ? $f_params['or_where_condition'] : NULL;
		$limit =								isset( $f_params['limit'] ) ? $f_params['limit'] : NULL;
		$offset =								isset( $f_params['offset'] ) ? $f_params['offset'] : NULL;
		$order_by =								isset( $f_params['order_by'] ) ? $f_params['order_by'] : 't1.title asc, t1.id asc';
		$order_by_escape =						isset( $f_params['order_by_escape'] ) ? $f_params['order_by_escape'] : TRUE;
		$return_type =							isset( $f_params['return_type'] ) ? $f_params['return_type'] : 'get';
			
		$this->db->select('
			
			t1.*,
			
		');
		
		$this->db->from('tb_modules t1');
		
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
	
	public function get_module_params( $params_values = NULL ){
		
		$params = get_params_spec_from_xml( APPPATH . 'controllers/admin/com_modules/module_params.xml');
		
		return $params;
	}
}
