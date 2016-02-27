<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plugins_common_model extends CI_Model{
	
	public $_active_plugins = NULL; // pilha com todos os plugins
	public $_active_plugins_by_type = NULL; // pilha com todos os plugins, agrupados por tipo
	
	public $_plugins_to_run = NULL; // pilha de plugins que serão executados, são adicionados no decorrer da aplicação
	public $_plugins_to_run_by_type = NULL; // pilha de plugins que serão executados, agrupados por tipo
	
	public $plugins_performed = NULL; // pilha de plugins já executados, objetivo: prevenir re-execução
	
	public $output = array();
	
	// --------------------------------------------------------------------
	
	public function __construct( $config = array() ) {
		
	}
	
	// --------------------------------------------------------------------
	
	public function get_output( $plugin_type = NULL, $plugin_name = NULL ){
		
		if ( $plugin_type OR $plugin_name ){
			
			if ( ! empty( $this->output ) AND ( $plugin_type OR $plugin_name ) ){
				
				// se foi especificado o tipo e o nome do plugin, é a requisição mais específica
				if ( $plugin_type AND $plugin_name ){
					
					foreach ( $this->output as $key => $output ) {
						
						if ( isset( $output[ $plugin_type ] ) AND isset( $output[ $plugin_type ][ $plugin_name ] ) ){
							
							return $output[ $plugin_type ][ $plugin_name ];
							
						}
						
					}
					
				}
				else if ( $plugin_type ){
					
					$out = '';
					
					foreach ( $this->output as $key => $plugin_type_val ) {
						
						foreach ( $plugin_type_val as $key2 => $plugin_name_val ) {
							
							if ( is_array( $plugin_name_val ) OR is_array( $out ) ){
								
								$out[ $key2 ] = $plugin_name_val;
								
							}
							else {
								
								$out .= $plugin_name_val;
								
							}
							
						}
						
					}
					
					return $out;
					
				}
				else if ( $plugin_name ){
					
					$out = '';
					
					foreach ( $this->output as $key => $output ) {
						
						if ( isset( $output ) AND ! empty( $output ) ){
							
							foreach ( $output as $key2 => $output_item ) {
								
								if ( is_array( $output_item ) OR is_array( $out ) ){
									
									$out[ $key2 ] = $output_item;
									
								}
								else {
									
									$out .= $output_item;
									
								}
								
							}
							
						}
						
					}
					
					return $out;
					
				}
				
			}
			
		}
		else{
			
			return $output;
			
		}
		
	}
	
	// --------------------------------------------------------------------
	
	public function get_plugins( $f_params = NULL ){
		
		// inicializando as variáveis
		$where_condition =
		$or_where_condition =
		$limit =
		$offset =
		$order_by =
		$order_by_escape =
		$return_type = NULL;
		
		// atribuindo valores às variávies
		if ( isset( $f_params ) ){
			
			$where_condition =						isset( $f_params['where_condition'] ) ? $f_params['where_condition'] : NULL;
			$or_where_condition =					isset( $f_params['or_where_condition'] ) ? $f_params['or_where_condition'] : NULL;
			$limit =								isset( $f_params['limit'] ) ? $f_params['limit'] : NULL;
			$offset =								isset( $f_params['offset'] ) ? $f_params['offset'] : NULL;
			$order_by =								isset( $f_params['order_by'] ) ? $f_params['order_by'] : 't1.ordering ASC, t1.id ASC, t1.name ASC';
			$order_by_escape =						isset( $f_params['order_by_escape'] ) ? $f_params['order_by_escape'] : TRUE;
			$return_type =							isset( $f_params['return_type'] ) ? $f_params['return_type'] : 'get';
			
		}
		
		$this->db->select('
			
			t1.*,
			
		');
		
		$this->db->from('tb_plugins t1');
		
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
	
	// --------------------------------------------------------------------
	
	public function insert_plugin( $data = NULL ){
		if ( $data != NULL ){
			if ( $this->db->insert( 'tb_plugins', $data ) ){
				// confirm the insertion for controller
				$return_id = $this->db->insert_id();
				var_dump( $return_id );
				exit;
			}
			else {
				// case the insertion fails, return false
				return FALSE;
			}
		}
		else {
			redirect( 'admin' );
		}
	}
	
	// --------------------------------------------------------------------
	
	public function update_plugin( $data = NULL,$condition = NULL ){
		
		if ( $data != NULL && $condition != NULL ){
			if ( $this->db->update( 'tb_plugins', $data,$condition ) ){
				// confirm update for controller
				return TRUE;
			}
			else {
				// case update fails, return false
				return FALSE;
			}
		}
		redirect( 'admin' );
		
	}
	
	// --------------------------------------------------------------------
	
	public function delete_plugin( $condition = NULL ){
		if ( $condition != null ){
			if ( $this->db->delete( 'tb_plugins', $condition ) ){
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
	
	// --------------------------------------------------------------------
	
	public function get_plugins_to_run( $name = NULL, $type = NULL, $return_type = 'type' ){
		
		// -------------------------------------------------
		// inicializando as variáveis ----------------------
		
		if ( is_array( $name ) ) {
			
			$f_params = $name;
			
			$name =										( check_var( $f_params[ 'plugin_name' ] ) AND is_string( $f_params[ 'plugin_name' ] ) ) ? $f_params[ 'plugin_name' ] : NULL;
			$type =										( check_var( $f_params[ 'plugin_type' ] ) AND is_string( $f_params[ 'plugin_type' ] ) ) ? $f_params[ 'plugin_type' ] : NULL;
			$return_by_type =							( check_var( $f_params[ 'return_type' ] ) AND is_string( $f_params[ 'return_type' ] ) ) ? $f_params[ 'return_type' ] : 'type';
			
		}
		else {
			
			$name =										( check_var( $name ) AND is_string( $name ) ) ? $name : NULL;
			$type =										( check_var( $type ) AND is_string( $type ) ) ? $type : NULL;
			$return_by_type =							( check_var( $return_by_type ) AND is_string( $return_by_type ) ) ? $return_by_type : 'type';
			
		}
		
		// inicializando as variáveis ----------------------
		// -------------------------------------------------
		
		if ( $name OR $type ) {
			
			if ( isset( $name ) AND isset( $type ) ) { // get by name and type
				
				if ( check_var( $this->_plugins_to_run_by_type[ $type ][ $name ] ) ) {
					
					return $this->_plugins_to_run_by_type[ $type ][ $name ];
					
				}
				
			}
			else if ( isset( $name ) AND ! isset( $type ) ) { // get by name
				
				if ( check_var( $this->_plugins_to_run[ $name ] ) ) {
					
					return $this->_plugins_to_run[ $name ];
					
				}
				
			}
			else if ( ! isset( $name ) AND isset( $type ) ) { // get by type
				
				if ( check_var( $this->_plugins_to_run_by_type[ $type ] ) ) {
					
					return $this->_plugins_to_run_by_type[ $type ];
					
				}
				
			}
			
		}
		else {
			
			if ( $return_by_type === 'type' ) {
				
				return $this->_plugins_to_run_by_type;
				
			}
			else if ( $return_by_type === 'name' ) {
				
				return $this->_plugins_to_run;
				
			}
			
		}
		
		return NULL;
		
	}
	
	// --------------------------------------------------------------------
	
	public function get_active_plugins( $name = NULL, $type = NULL, $return_type = 'type' ){
		
		// -------------------------------------------------
		// inicializando as variáveis ----------------------
		
		if ( is_array( $name ) ) {
			
			$f_params = $name;
			
			$name =										( check_var( $f_params[ 'plugin_name' ] ) AND is_string( $f_params[ 'plugin_name' ] ) ) ? $f_params[ 'plugin_name' ] : NULL;
			$type =										( check_var( $f_params[ 'plugin_type' ] ) AND is_string( $f_params[ 'plugin_type' ] ) ) ? $f_params[ 'plugin_type' ] : NULL;
			$return_by_type =							( check_var( $f_params[ 'return_type' ] ) AND is_string( $f_params[ 'return_type' ] ) ) ? $f_params[ 'return_type' ] : 'type';
			
		}
		else {
			
			$name =										( check_var( $name ) AND is_string( $name ) ) ? $name : NULL;
			$type =										( check_var( $type ) AND is_string( $type ) ) ? $type : NULL;
			$return_by_type =							( check_var( $return_by_type ) AND is_string( $return_by_type ) ) ? $return_by_type : 'type';
			
		}
		
		// inicializando as variáveis ----------------------
		// -------------------------------------------------
		
		if ( ! isset( $this->_active_plugins ) ) {
			
			log_message( 'debug', "[Plugins] Active plugins list not available yet" );
			$this->_set_active_plugins();
			
		}
		
		if ( $name OR $type ) {
			
			if ( isset( $name ) AND isset( $type ) ) { // get by name and type
				
				if ( check_var( $this->_active_plugins_by_type[ $type ][ $name ] ) ) {
					
					return $this->_active_plugins_by_type[ $type ][ $name ];
					
				}
				
			}
			else if ( isset( $name ) AND ! isset( $type ) ) { // get by name
				
				if ( check_var( $this->_active_plugins[ $name ] ) ) {
					
					return $this->_active_plugins[ $name ];
					
				}
				
			}
			else if ( ! isset( $name ) AND isset( $type ) ) { // get by type
				
				if ( check_var( $this->_active_plugins_by_type[ $type ] ) ) {
					
					return $this->_active_plugins_by_type[ $type ];
					
				}
				
			}
			
		}
		else {
			
			if ( $return_by_type === 'type' ) {
				
				return $this->_active_plugins_by_type;
				
			}
			else if ( $return_by_type === 'name' ) {
				
				return $this->_active_plugins;
				
			}
			
		}
		
		return NULL;
		
	}
	
	// --------------------------------------------------------------------
	
	private function _set_active_plugins(){
		
		// get plugins params
		$gpp[ 'where_condition' ] = 't1.status = 1';
		$gpp[ 'where_condition' ] .= ' AND ( ';
		$gpp[ 'where_condition' ] .= ' t1.environment REGEXP \'' . $this->mcm->environment . '|both\'';
		$gpp[ 'where_condition' ] .= ' )';
		
		log_message( 'debug', "[Plugins] Loading active plugins from DB ... " );
		
		$plugins = $this->get_plugins( $gpp );
		
		if ( $plugins->num_rows() > 0 ) {
			
			log_message( 'debug', "[Plugins] Active plugins loaded" );
			
			$this->_active_plugins = $plugins->result_array();
			
			if ( check_var( $this->_active_plugins ) ) {
				
				foreach ( $this->_active_plugins as $key => $active_plugin ) {
					
					$this->_active_plugins_by_type[ $active_plugin[ 'type' ] ][ $active_plugin[ 'name' ] ] = $active_plugin;
					
				}
				
				return TRUE;
				
			}
			else {
				
				log_message( 'debug', "[Plugins] No active plugins founded" );
				return FALSE;
				
			}
			
		}
		
		log_message( 'debug', "[Plugins] No active plugins founded" );
		return FALSE;
		
	}
	
	// --------------------------------------------------------------------
	
	public function require_plugin( $plugin_name = NULL, $plugin_type = NULL ){
		
		// -------------------------------------------------
		// inicializando as variáveis ----------------------
		
		if ( is_array( $plugin_name ) ) {
			
			$f_params = $plugin_name;
			
			$plugin_name = ( check_var( $f_params[ 'plugin_name' ] ) AND is_string( $f_params[ 'plugin_name' ] ) ) ? $f_params[ 'plugin_name' ] : FALSE;
			$plugin_type = ( check_var( $f_params[ 'plugin_type' ] ) AND is_string( $f_params[ 'plugin_type' ] ) ) ? $f_params[ 'plugin_type' ] : FALSE;
			
		}
		else {
			
			$plugin_name = ( check_var( $plugin_name ) AND is_string( $plugin_name ) ) ? $plugin_name : FALSE;
			$plugin_type = ( check_var( $plugin_type ) AND is_string( $plugin_type ) ) ? $plugin_type : FALSE;
			
		}
		
		if ( ! $plugin_name AND ! $plugin_type ) {
			
			log_message( 'error', "[Plugins] Require plugin function called, but no valid args informed, exiting ..." );
			return FALSE;
			
		}
		
		// inicializando as variáveis ----------------------
		// -------------------------------------------------
		
		// for logs
		$log_plugin_str = ( $plugin_name ? $plugin_name : '' ) . ( $plugin_type ? ' ' . $plugin_type : '' );
		log_message( 'debug', "[Plugins] Plugin required: " . $log_plugin_str );
		
		if ( $plugin_name OR $plugin_type ){
			
			$precise_search = FALSE; // search by plugin name and plugin type
			$search_by_name = FALSE; // search by plugin name
			$search_by_type = FALSE; // search by plugin type
			
			if ( isset( $plugin_name ) AND isset( $plugin_type ) ) {
				
				$precise_search = TRUE;
				
			}
			else if ( isset( $plugin_name ) AND ! isset( $plugin_type ) ) {
				
				$search_by_name = TRUE;
				
			}
			else if ( ! isset( $plugin_name ) AND isset( $plugin_type ) ) {
				
				$search_by_type = TRUE;
				
			}
			
			
			$match = FALSE;
			
			log_message( 'debug', "[Plugins] Searching " . $log_plugin_str . " plugin on plugins to run stack ... " );
			$plugins_to_run = $this->get_plugins_to_run( $plugin_name, $plugin_type );
			
			if ( $this->get_plugins_to_run( $plugin_name, $plugin_type ) ) {
				
				$match = TRUE;
				log_message( 'debug', "[Plugins] " . $log_plugin_str . " founded on plugins to run stack, skiping" );
				
			}
			
			if ( ! $match ) {
				
				log_message( 'debug', "[Plugins] " . $log_plugin_str . " not founded on plugins to run stack" );
				log_message( 'debug', "[Plugins] Searching " . $log_plugin_str . " plugin on active plugins list ... " );
				
				$active_plugins = $this->get_active_plugins( $plugin_name, $plugin_type );
				
				foreach ( $active_plugins as $key => $active_plugin ) {
					
					if (
						
						( $precise_search AND $plugin_name === $active_plugin[ 'name' ] AND $plugin_type === $active_plugin[ 'type' ] ) OR
						( $search_by_name AND $plugin_name === $active_plugin[ 'name' ] ) OR
						( $search_by_type AND $plugin_type === $active_plugin[ 'type' ] )
						
					){
						
						log_message( 'debug', "[Plugins] " . $log_plugin_str . " founded on active plugins list" );
						
						log_message( 'debug', "[Plugins] Validating " . $log_plugin_str . " dependencies ..." );
						
						if ( $this->validate_plugin_dependencies( $plugin[ 'name' ] ) ){
							
							log_message( 'debug', "[Plugins] Validation for " . $log_plugin_str . " plugin passed! Appending to plugins to run stack" );
							
							$this->_plugins_to_run[ $plugin[ 'ordering' ] ] = $plugin;
							
							$match = TRUE;
							break;
							
						}
						else{
							
							log_message( 'debug', "[Plugins] Validation for " . $log_plugin_str . " plugin fail!" );
							
						}
						
					}
					
				}
				
			}
			
			if ( ! $match ) log_message( 'debug', "[Plugins] " . $log_plugin_str . " not founded" );
			
			return $match;
			
		}
		
	}
	
	// --------------------------------------------------------------------
	
	public function validate_plugin_dependencies( $plugin_name = NULL, $plugin_type = NULL ){
		
		if ( $plugin_name OR $plugin_type ){
			
			// echo 'Validando dependências do plugin ' . $plugin_name . "\n";
			$validate = FALSE;
			foreach ( $this->_plugins_to_run as $key => $plugin ) {
				
				if ( $plugin[ 'name' ] === $plugin_name OR $plugin[ 'type' ] === $plugin_type ){
					
					if ( $plugin[ 'depends' ] ){
						
						$plugin[ 'depends' ] = json_decode( $plugin[ 'depends' ], TRUE );
						
						foreach ( $plugin[ 'depends' ] as $key => $depend ) {
							
							return $this->require_plugin( $depend[ 'name' ] ? $depend[ 'name' ] : $depend[ 'type' ] );
							
						}
						
					}
					else $validate = TRUE;
					
				}
				
			}
			foreach ( $this->plugins as $key => $plugin ) {
				
				if ( $plugin[ 'name' ] === $plugin_name OR $plugin[ 'type' ] === $plugin_type ){
					
					if ( $plugin[ 'depends' ] ){
						
						$plugin[ 'depends' ] = json_decode( $plugin[ 'depends' ], TRUE );
						
						foreach ( $plugin[ 'depends' ] as $key => $depend ) {
							
							return $this->require_plugin( $depend[ 'name' ] ? $depend[ 'name' ] : $depend[ 'type' ] );
							
						}
						
					}
					else $validate = TRUE;
					
				}
				
			}
			
		}
		
		return $validate;
	}
	
	// --------------------------------------------------------------------
	
	public function remove_plugin_from_stack( $plugin_name = NULL, $plugin_type = NULL ){
		
		if ( $plugin_name ){
			
			foreach ( $this->_plugins_to_run as $key => $plugin ) {
				
				if ( $plugin[ 'name' ] === $plugin_name ){
					
					unset( $this->_plugins_to_run[ $key ] );
					
				}
				
			}
			
		}
		else if ( $plugin_type ){
			
			foreach ( $this->_plugins_to_run as $key => $plugin ) {
				
				if ( $plugin[ 'type' ] === $plugin_type ){
					
					unset( $this->_plugins_to_run[ $key ] );
					
				}
				
			}
			
		}
		
	}
	
	// --------------------------------------------------------------------
	
	public function run_plugins( & $data = NULL, $params = NULL ){
		
		$plugins_result = array();
		
		ksort( $this->_plugins_to_run );
		
		log_message( 'debug', "[Plugins] Running plugins ..." );
		
		foreach ( $this->_plugins_to_run as $key => $plugin ) {
			
			log_message( 'debug', "[Plugins] Request type: " . ( ( isset( $data[ 'plugin_type' ] ) AND $data[ 'plugin_type' ] ) ? 'plugin_type: ' . $data[ 'plugin_type' ] : ( ( isset( $data[ 'plugin_name' ] ) AND $data[ 'plugin_name' ] ) ? 'plugin_name: ' . $data[ 'plugin_name' ] : 'normal' ) ) );
			
			log_message( 'debug', "[Plugins] Analyzing plugin: " . $plugin[ 'name' ] );
			
			if ( ( isset( $data[ 'plugin_type' ] ) AND $data[ 'plugin_type' ] == $plugin[ 'type' ] ) AND ( isset( $data[ 'plugin_name' ] ) AND $data[ 'plugin_name' ] == $plugin[ 'name' ] ) ){
				
				log_message( 'debug', "[Plugins] Plugin passed by precise request" );
				
				if ( ! isset( $this->plugins_performed[ $plugin[ 'ordering' ] ] ) ){
					
					if ( $plugin[ 'prevent_rerunning' ] ){
						
						log_message( 'debug', "[Plugins] The plugin doesn't allow rerunning" );
						
						$this->plugins_performed[ $plugin[ 'ordering' ] ] = $plugin;
						
					}
					
					$plugin_model_name = $plugin[ 'name' ] . '_plugin';
					
					log_message( 'debug', "[Plugins] Loading plugin model: " . $plugin_model_name );
					
					$this->load->model( 'plugins/' . $plugin_model_name );
					
					log_message( 'debug', "[Plugins] Running plugin" );
					
					$this->{ $plugin_model_name }->run( $data, $params );
					
					log_message( 'debug', "[Plugins] Plugin performed! " );
					
				}
				
			}
			else if ( ( isset( $data[ 'plugin_type' ] ) AND $data[ 'plugin_type' ] == $plugin[ 'type' ] ) AND ! ( isset( $data[ 'plugin_name' ] ) ) ){
				
				log_message( 'debug', "[Plugins] Plugin passed by type" );
				
				if ( ! isset( $this->plugins_performed[ $plugin[ 'ordering' ] ] ) ){
					
					if ( $plugin[ 'prevent_rerunning' ] ){
						
						log_message( 'debug', "[Plugins] The plugin doesn't allow rerunning" );
						
						$this->plugins_performed[ $plugin[ 'ordering' ] ] = $plugin;
						
					}
					
					$plugin_model_name = $plugin[ 'name' ] . '_plugin';
					
					log_message( 'debug', "[Plugins] Loading plugin model: " . $plugin_model_name );
					
					$this->load->model( 'plugins/' . $plugin_model_name );
					
					log_message( 'debug', "[Plugins] Running plugin" );
					
					$this->{ $plugin_model_name }->run( $data, $params );
					
					log_message( 'debug', "[Plugins] Plugin performed! " );
					
				}
				
			}
			else if ( ! ( isset( $data[ 'plugin_type' ] ) ) AND ( isset( $data[ 'plugin_name' ] ) AND $data[ 'plugin_name' ] == $plugin[ 'name' ] ) ){
				
				log_message( 'debug', "[Plugins] Plugin passed by name" );
				
				if ( ! isset( $this->plugins_performed[ $plugin[ 'ordering' ] ] ) ){
					
					if ( $plugin[ 'prevent_rerunning' ] ){
						
						log_message( 'debug', "[Plugins] The plugin doesn't allow rerunning" );
						
						$this->plugins_performed[ $plugin[ 'ordering' ] ] = $plugin;
						
					}
					
					$plugin_model_name = $plugin[ 'name' ] . '_plugin';
					
					log_message( 'debug', "[Plugins] Loading plugin model: " . $plugin_model_name );
					
					$this->load->model( 'plugins/' . $plugin_model_name );
					
					log_message( 'debug', "[Plugins] Running plugin" );
					
					$this->{ $plugin_model_name }->run( $data, $params );
					
					log_message( 'debug', "[Plugins] Plugin performed! " );
					
				}
				
			}
			else if ( ! isset( $data[ 'plugin_type' ] ) AND  ! isset( $data[ 'plugin_name' ] ) ){
				
				log_message( 'debug', "[Plugins] Plugin passed by normal request" );
				
				if ( ! isset( $this->plugins_performed[ $plugin[ 'ordering' ] ] ) ){
					
					if ( $plugin[ 'prevent_rerunning' ] ){
						
						log_message( 'debug', "[Plugins] The plugin doesn't allow rerunning" );
						
						$this->plugins_performed[ $plugin[ 'ordering' ] ] = $plugin;
						
					}
					
					$plugin_model_name = $plugin[ 'name' ] . '_plugin';
					
					log_message( 'debug', "[Plugins] Loading plugin model: " . $plugin_model_name );
					
					$this->load->model( 'plugins/' . $plugin_model_name );
					
					log_message( 'debug', "[Plugins] Running plugin" );
					
					$this->{ $plugin_model_name }->run( $data, $params );
					
					log_message( 'debug', "[Plugins] Plugin performed! " );
					
				}
				
			}
			else{
				
				log_message( 'debug', "[Plugins] " . $plugin[ 'name' ] . " plugin does not match any condition to run, skiping ..." );
				
			}
			
		}
		
		ksort( $this->plugins_performed );
		
	}
	
	// --------------------------------------------------------------------
	
}
