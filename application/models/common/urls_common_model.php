<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Urls_common_model extends CI_Model{
	
	public $urls = array(); // array with all urls
	
	private function _urls_cache_file_is_writable(){
		
		$urls_cache_file = APPPATH . 'cache/urls.php';
		
		$this->load->helper( 'file' );
		
		if ( ! file_exists( $urls_cache_file ) OR is_really_writable( $urls_cache_file ) ){
			
			return TRUE;
			
		}
		else{
			
			log_message( 'error', '[Urls common model] ' . lang( 'unable_to_write_urls_cache_file', NULL, $urls_cache_file ) );
			msg( lang( 'unable_to_write_urls_cache_file', NULL, $urls_cache_file ), 'error' );
			
			return FALSE;
			
		}
		
	}
	
	public function update_urls_cache( $urls = NULL ){
		
		// verifica se o arquivo de cache das urls é gravável
		if ( $this->_urls_cache_file_is_writable() ){
			
			$this->load->helper( 'url' );
			
			if ( ! isset( $urls ) OR gettype( $urls ) !== 'array' ){
				
				$urls = $this->mng_get_urls()->result_array();
				$this->urls = $urls;
				
			}
			
			$data = array();
			
			if ( ! empty( $urls ) ) {
				
				if ( $urls AND gettype( $urls ) === 'array' ){
					
					$data[] = '<?php if ( ! defined( \'BASEPATH\' ) ) exit( \'No direct script access allowed\' );';
					
					foreach ( $urls as $url ) {
						
						$data[] = '$route[\'' . $url[ 'sef_url' ] . '\'] = \'' . $url[ 'target' ] . '\';';
						
					}
					
					$output = implode( "\n", $data );
					
					if ( write_file( APPPATH . 'cache/urls.php', $output ) ){
						
						return TRUE;
						
					}
					
				}
				
			}
			
		}
		
		return FALSE;
		
	}
	
	public function setup_reverse_urls(){
		
		$urls = $this->mng_get_urls()->result_array();
		
		if ( $urls ){
			
			$reverse_urls = array();
			
			foreach( $urls as $url ){
				
				$route[ $url[ 'sef_url' ] ]						= $url[ 'target' ];
				
				if ( $url[ 'sef_url' ] != 'default_controller' ){
					
					$reverse_urls[ $url[ 'target' ] ] = $url[ 'sef_url' ];
					
				}
				
			}
			//print_r($route);
			
			$this->mcm->system_params[ 'reverse_urls' ] = $reverse_urls;
			
		}
		else{
			
			return FALSE;
			
		}
		
	}
	
	// get urls for management
	public function mng_get_urls( $f_params = NULL ){
		
		// inicializando as variáveis
		$where_condition =						@$f_params['where_condition'] ? $f_params['where_condition'] : NULL;
		$or_where_condition =					@$f_params['or_where_condition'] ? $f_params['or_where_condition'] : NULL;
		$limit =								@$f_params['limit'] ? $f_params['limit'] : NULL;
		$offset =								@$f_params['offset'] ? $f_params['offset'] : NULL;
		$order_by =								@$f_params['order_by'] ? $f_params['order_by'] : 't1.sef_url asc, t1.id asc';
		$order_by_escape =						@$f_params['order_by_escape'] ? $f_params['order_by_escape'] : TRUE;
		$return_type =							@$f_params['return_type'] ? $f_params['return_type'] : 'get';
			
		$this->db->select('
			
			t1.*,
			
		');
		
		$this->db->from('tb_urls t1');
		
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
	
	public function get_url( $id = NULL ){
		
		if ( $id!=NULL ){
			$this->db->select('t1.*');
			$this->db->from('tb_urls t1');
			$this->db->where( 't1.id', $id );
			// limitando o resultando em apenas 1
			$this->db->limit(1);
			return $this->db->get();
		}
		else {
			return FALSE;
		}
		
	}
	
	public function insert( $data = NULL ){
		
		if ( $data ){
			
			if ( $this->_urls_cache_file_is_writable() ){
				
				if ( $this->db->insert( 'tb_urls', $data ) ){
					
					$return_id = $this->db->insert_id();
					
					if ( $this->update_urls_cache() ){
						
						if( $return_id ){
							
							log_message( 'debug', '[Urls common model] ' . sprintf( lang( 'new_url_created' ), $return_id, $data[ 'sef_url' ] ) );
							return $return_id;
							
						}
						else{
							
							log_message( 'error', '[Urls common model] ' . lang( 'erro ao tentar inserir url no bd' ) );
							return FALSE;
							
						}
						
					}
					
				}
				else {
					
					log_message( 'error', '[Urls common model] ' . lang( 'erro ao tentar inserir url' ) );
					return FALSE;
					
				}
				
			}
			else{
				
				return FALSE;
				
			}
			
		}
		
		log_message( 'error', '[Urls common model] ' . lang( 'dados não informados' ) );
		return FALSE;
		
	}
	
	public function update( $data = NULL, $condition = NULL ){
		
		if ( $data != NULL && $condition != NULL ){
			
			if ( $this->_urls_cache_file_is_writable() ){
				
				if ( $this->db->update( 'tb_urls', $data, $condition ) ){
					
					if ( $this->update_urls_cache() ){
						
						return TRUE;
						
					}
					else{
						
						return FALSE;
						
					}
					
				}
				else {
					
					return FALSE;
					
				}
				
			}
			else{
				
				return FALSE;
				
			}
			
		}
		
		redirect( 'admin/urls' );
		
	}
	
	public function delete( $condition = NULL ){
		
		if ( $condition != null ){
			
			if ( $this->_urls_cache_file_is_writable() ){
				
				if ( $this->db->delete( 'tb_urls', $condition ) ){
					
					if( $this->update_urls_cache() ){
						
						return TRUE;
						
					}
					else{
						
						return FALSE;
						
					}
					
				}
				else {
					
					return FALSE;
					
				}
				
			}
			else{
				
				return FALSE;
				
			}
			
		}
		
	}
	
	public function delete_all(){
		
		if ( $this->_urls_cache_file_is_writable() ){
			
			$this->db->where( 'sef_url !=', 'default_controller' );
			$this->db->where( 'sef_url !=', '404_override' );
			
			if ( $this->db->delete( 'tb_urls' ) ){
				
				if( $this->update_urls_cache() ){
					
					return TRUE;
					
				}
				else{
					
					return FALSE;
					
				}
				
			}
			else {
				
				return FALSE;
				
			}
			
		}
		else{
			
			return FALSE;
			
		}
		
	}
	
	
}
