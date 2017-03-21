<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Main_common_model extends CI_Model{

	public $components = FALSE; // Array de componentes
	public $components_stack = array(); // pilha de componentes encontrados e ativos
	public $system_params = FALSE; // parâmetros do sistema ( componente Main )
	public $user_params = FALSE; // parâmetros do usuário
	public $filtered_system_params = FALSE; // parâmetros do sistema filtrados com os parâmetros do usuário, se este estiver logado
	public $current_menu_item = FALSE; // Array com os dados do item de menu atual
	public $modules_types = FALSE; // Array dos tipos de módulos
	public $loaded_modules = FALSE; // Array dos módulos carregados
	public $html_data = FALSE; // Array dos elementos html da página enviados ao template
	
	private $_email_system_called = FALSE;
	private $_items_tree = NULL;

	public $params = array();

	public function __construct(){

	}
	
	// --------------------------------------------------------------------
	
	//TODO
	// criar análise mais profunda para determinar se a funcionalidade de email está ok
	public function load_email_system() {
		
		if ( check_var( $this->mcm->system_params[ 'email_config_enabled' ] ) ) {
			
			log_message( 'info', '[E-mail] Loading email config' );
			
			if ( isset( $this->mcm->system_params[ 'email_config_protocol' ] ) ) {
				
				$config[ 'protocol' ] = $this->mcm->system_params[ 'email_config_protocol' ];
				
			}
			
			if ( isset( $this->mcm->system_params[ 'email_config_sendmail_path' ] ) ) {
				
				$config[ 'mailpath' ] = $this->mcm->system_params[ 'email_config_sendmail_path' ];
				
			}
			
			if ( isset( $this->mcm->system_params[ 'email_config_smtp_host' ] ) ) {
				
				$config[ 'smtp_host' ] = $this->mcm->system_params[ 'email_config_smtp_host' ];
				
			}
			
			if ( isset( $this->mcm->system_params[ 'email_config_smtp_port' ] ) ) {
				
				$config[ 'smtp_port' ] = $this->mcm->system_params[ 'email_config_smtp_port' ];
				
			}
			
			if ( isset( $this->mcm->system_params[ 'email_config_smtp_user' ] ) ) {
				
				$config[ 'smtp_user' ] = $this->mcm->system_params[ 'email_config_smtp_user' ];
				
			}
			
			if ( isset( $this->mcm->system_params[ 'email_config_smtp_pass' ] ) ) {
				
				$config[ 'smtp_pass' ] = $this->mcm->system_params[ 'email_config_smtp_pass' ];
				
			}
			
			if ( isset( $this->mcm->system_params[ 'email_config_mailtype' ] ) ) {
				
				$config[ 'mailtype' ] = $this->mcm->system_params[ 'email_config_mailtype' ];
				
			}
			
			if ( isset( $this->mcm->system_params[ 'email_config_charset' ] ) ) {
				
				$config[ 'charset' ] = $this->mcm->system_params[ 'email_config_charset' ];
				
			}
			
			if ( isset( $this->mcm->system_params[ 'email_config_wordwrap' ] ) ) {
				
				$config[ 'wordwrap' ] = $this->mcm->system_params[ 'email_config_wordwrap' ];
				
			}
			
			if ( isset( $this->mcm->system_params[ 'email_config_smtp_crypto' ] ) ) {
				
				$config[ 'smtp_crypto' ] = $this->mcm->system_params[ 'email_config_smtp_crypto' ];
				
			}
			
			if ( isset( $this->mcm->system_params[ 'email_config_newline' ] ) ) {
				
				$newline_search = array(
					
					'\r',
					'\n'
					
				);
				
				$newline_replace = array(
					
					"\r",
					"\n"
					
				);
				
				$config[ 'newline' ] = str_replace( $newline_search, $newline_replace, $this->mcm->system_params[ 'email_config_newline' ] );
				
			}
			
			if ( isset( $this->mcm->system_params[ 'email_config_useragent' ] ) ) {
				
				$config[ 'useragent' ] = $this->mcm->system_params[ 'email_config_useragent' ];
				
			}
			else {
				
				$config[ 'useragent' ] = 'openvizz';
				
			}
			
			$this->load->library( 'email', $config );
			
			$this->_email_system_called = TRUE;
			
			return TRUE;
			
		}
		
		msg( ( 'email_system_disabled' ), 'error' );
		
		return FALSE;
		
	}
	
	// --------------------------------------------------------------------
	
	public function email_system_clear() {
		
		if ( $this->_email_system_called ) {
			
			if ( isset( $this->mcm->system_params[ 'email_config_useragent' ] ) ) {
				
				$config[ 'useragent' ] = $this->mcm->system_params[ 'email_config_useragent' ];
				
			}
			else {
				
				$config[ 'useragent' ] = 'openvizz';
				
			}
			
		}
		else {
			
			$this->load->library( 'email', $config );
			
		}
		
	}
	
	// --------------------------------------------------------------------
	
	private function _db_upgrade_info_cache_file_is_writable() {
		
		$urls_cache_file = APPPATH . 'cache/db_upgrade_info.php';
		
		$this->load->helper( 'file' );
		
		if ( ! file_exists( $urls_cache_file ) OR is_really_writable( $urls_cache_file ) ) {
			
			return TRUE;
			
		}
		else {
			
			msg( sprintf( lang( 'unable_to_write_db_upgrade_info_cache_file' ), ( APPPATH . 'cache/db_upgrade_info.php' ) ), 'error' );
			return FALSE;
			
		}
		
	}
	
	// --------------------------------------------------------------------
	
	public function upgrade_db(){
		
		log_message( 'info', '[DB Upgrade] DB upgrade function initialized' );
		
		$file = 'cache/db_upgrade_info.php';
		
		if ( file_exists( APPPATH . $file ) ){
			
			require_once APPPATH . $file;
			
		}
		
		// verifica se o arquivo de cache é gravável
		if ( $this->_db_upgrade_info_cache_file_is_writable() ) {
			
			$write = FALSE;
			
			// ----------------------------
			// users
			
			if ( ! check_var( $db_upgrade_cache_vars[ 'users_images_col' ] ) ) {
				
				log_message( 'info', '[DB Upgrade] Upgrading "tb_users"' );
				
				$user_images_columns = "SHOW COLUMNS FROM `tb_users` LIKE 'images'";
				$user_images_columns = $this->db->query( $user_images_columns )->row_array();
				
				if ( ! $user_images_columns ){
					
					$query = "ALTER TABLE  `tb_users` ADD  `images` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL;";
					$query = $this->db->query( $query );
					
					if ( $query ) {
						
						log_message( 'info', '[DB Upgrade] Col "images" add in "tb_users" table' );
						
						$db_upgrade_cache_vars[ 'users_images_col' ] = TRUE;;
						
					}
					else {
						
						log_message( 'error', '[DB Upgrade] Can\'t add col "images" into "tb_users" table' );
						
						msg( sprintf( lang( 'unable_to_add_users_images_col_into_tb_users_table' ), ( APPPATH . $file ) ), 'error' );
						
					}
					
				}
				else {
					
					log_message( 'info', '[DB Upgrade] "tb_users" already upgraded' );
					
					$db_upgrade_cache_vars[ 'users_images_col' ] = TRUE;;
					
				}
				
				$write = TRUE;
				
			}
			
			// users
			// ----------------------------
			
			// ----------------------------
			// tb_submit_forms_us, update submit forms users submits xml_data collumn
			
			if ( ! check_var( $db_upgrade_cache_vars[ 'sfus_xml_data_col' ] ) ) {
				
				log_message( 'info', '[DB Upgrade] Updating "xml_data" collumn in "tb_submit_forms_us" table' );
				
				$this->load->model( 'common/submit_forms_common_model', 'sfcm' );
				
				$this->db->select( 'id, submit_form_id, data' );
				$this->db->from( 'tb_submit_forms_us' );
				$users_submits = $this->db->get()->result_array();
				
				$db_data = array();
				
				foreach( $users_submits as $k => $us ) {
					
					$n_us[ 'xml_data' ] = $this->sfcm->us_json_data_to_xml( $us );
					
					$this->db->update( 'tb_submit_forms_us', $n_us, array( 'id' => $us[ 'id' ] ) );
					
				}
				
				log_message( 'info', '[DB Upgrade] Updating "xml_data" collumn in "tb_submit_forms_us" table done!' );
				
				$db_upgrade_cache_vars[ 'sfus_xml_data_col' ] = TRUE;;
				
				$write = TRUE;
				
			}
			
			// tb_submit_forms_us, update submit forms users submits xml_data collumn
			// ----------------------------
			
			log_message( 'info', '[DB Upgrade] The DB Upgrade cache file looks writable.' );
			
			if ( $write AND isset( $db_upgrade_cache_vars ) AND is_array( $db_upgrade_cache_vars ) ) {
				
				$output = array();
				$output[] = '<?php if ( ! defined( \'BASEPATH\' ) ) exit( \'No direct script access allowed\' );';
				
				foreach( $db_upgrade_cache_vars as $key => $value ) {
					
					$output[] = '$db_upgrade_cache_vars[ \'' . $key . '\' ] = ' . $value . ';';
					
				}
				
				$output = implode( "\n", $output );
				
				if ( write_file( APPPATH . $file, $output ) ) {
					
					return TRUE;
					
				}
				else{
					
					msg( sprintf( lang( 'unable_to_write_file' ), ( APPPATH . $file ) ), 'error' );
					
				}
				
			}
			
		}
		else {
			
			log_message( 'error', 'The DB Upgrade cache file is not writable!' );
			
			msg( sprintf( lang( 'unable_db_upgrade_cache_file_is_not_writable' ), ( APPPATH . $file ) ), 'error' );
			
		}
		
		return FALSE;
		
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Return a array tree (unidimensional or multidimensional array)
	 *
	 * @access public
	 * @param f_params				array				function params, where:
	 * 		parent_id | int
	 * 		level | int
	 * 		array | array | the items array
	 * 		array_type | string | array return type, can be 'unidimensional' or 'multidimensional'
	 * @return array
	 */

	public function get_array_tree( $f_params = NULL ){
		
		// -------------------------------------------------
		// Parsing vars ------------------------------------
		
		$type_options_allowed = array(
			
			'unidimensional',
			'multidimensional',
			
		);
		
		$f_params[ 'parent_id_string' ] =	( isset( $f_params[ 'parent_id_string' ] ) AND $f_params[ 'parent_id_string' ] > 0 ) ? $f_params[ 'parent_id_string' ] : 'parent_id';
		$parent_id_string = $f_params[ 'parent_id_string' ];
		$f_params[ $parent_id_string ] =	( isset( $f_params[ $parent_id_string ] ) AND is_numeric( $f_params[ $parent_id_string ] ) AND $f_params[ $parent_id_string ] >= 0 ) ? ( int ) $f_params[ $parent_id_string ] : 0;
		$f_params[ 'level' ] =				( isset( $f_params[ 'level' ] ) AND is_numeric( $f_params[ 'level' ] ) AND $f_params[ 'level' ] >= 0 ) ? ( int ) $f_params[ 'level' ] : 0;
		$f_params[ 'array' ] =				( ! isset( $f_params[ 'array' ] ) OR ! is_array( $f_params[ 'array' ] ) ) ? array() : $f_params[ 'array' ];
		$f_params[ 'array_type' ] =			( isset( $f_params[ 'type' ] ) AND is_string( $f_params[ 'array_type' ] ) AND in_array( $f_params[ 'array_type' ], $type_options_allowed ) ) ? $f_params[ 'array_type' ] : 'unidimensional';
		$f_params[ 'include_parent' ] =		isset( $f_params[ 'include_parent' ] ) ? ( bool ) $f_params[ 'include_parent' ] : TRUE;

		// Parsing vars ------------------------------------
		// -------------------------------------------------

		if ( $f_params[ 'array_type' ] == 'multidimensional' ) {

			$categories = $this->array_to_multidimensional_array( $f_params );

			if ( $f_params[ 'include_parent' ] ) {

				$this->remove_item_from_array( $categories, $f_params[ $parent_id_string ] );

			}

			return $categories;

		}
		else if ( $f_params[ 'array_type' ] == 'unidimensional' ) {

			$categories = $this->array_to_parent_ordered( $f_params );

			if ( $f_params[ 'include_parent' ] ) {

				$this->remove_item_from_array( $categories, $f_params[ $parent_id_string ] );

			}

			return $categories;

		}

		return FALSE;

	}

	// --------------------------------------------------------------------

	public function remove_item_from_array( & $array, $search_key ){
		
		if ( ! is_null( $search_key ) AND is_array( $array ) AND key_exists( $search_key, $array ) ) {
			
			unset( $array[ $search_key ] );
			
		}
		
	}

	// --------------------------------------------------------------------

	public function array_to_menu( $f_params ) {

		// -------------------------------------------------
		// Parsing vars ------------------------------------

		if ( ! is_array( $f_params ) AND ! check_var( $f_params ) ) {

			return array();

		}

		$f_params[ 'parent_id_string' ] =		( isset( $f_params[ 'parent_id_string' ] ) AND $f_params[ 'parent_id_string' ] > 0 ) ? $f_params[ 'parent_id_string' ] : 'parent_id';
		$parent_id_string = 					$f_params[ 'parent_id_string' ];
		$f_params[ 'array' ] =					( isset( $f_params[ 'array' ] ) AND is_array( $f_params[ 'array' ] ) ) ? $f_params[ 'array' ] : array();

		// Parsing vars ------------------------------------
		// -------------------------------------------------

		$array_tree = $this->array_to_multidimensional_array( $f_params );

		return ul_menu( $array_tree );

	}

	// --------------------------------------------------------------------

	public function array_to_multidimensional_array( $f_params ) {

		// -------------------------------------------------
		// Parsing vars ------------------------------------

		if ( ! is_array( $f_params ) AND ! check_var( $f_params ) ) {

			return array();

		}

		$f_params[ 'parent_id_string' ] =				( isset( $f_params[ 'parent_id_string' ] ) AND $f_params[ 'parent_id_string' ] > 0 ) ? $f_params[ 'parent_id_string' ] : 'parent_id';
		$parent_id_string = 							$f_params[ 'parent_id_string' ];
		$f_params[ $parent_id_string ] =				( isset( $f_params[ $parent_id_string ] ) AND is_numeric( $f_params[ $parent_id_string ] ) AND $f_params[ $parent_id_string ] >= 0 ) ? ( int ) $f_params[ $parent_id_string ] : 0;
		$f_params[ 'array' ] =							( isset( $f_params[ 'array' ] ) AND is_array( $f_params[ 'array' ] ) ) ? $f_params[ 'array' ] : array();
		$f_params[ 'id_field' ] =						( isset( $f_params[ 'id_field' ] ) AND is_string( $f_params[ 'id_field' ] ) ) ? $f_params[ 'id_field' ] : 'id';
		$f_params[ 'parent_field' ] =					( isset( $f_params[ 'parent_field' ] ) AND is_string( $f_params[ 'parent_field' ] ) ) ? $f_params[ 'parent_field' ] : 'parent';

		// Parsing vars ------------------------------------
		// -------------------------------------------------

		$array = $f_params[ 'array' ];

		// First, convert the array so that the keys match the ids
		$new = array();
		foreach ( $array as $item ) {

			$new[ ( int ) $item[ $f_params[ 'id_field' ] ] ] = $item;

		}

		// Next, use references to associate children with parents
		foreach ( $new as $id => $item ) {

			if (

				( isset( $item[ $f_params[ 'parent_field' ] ] ) AND $item[ $f_params[ 'parent_field' ] ] )
				AND ( isset( $new[ ( int ) $item[ $f_params[ 'parent_field' ] ] ] ) AND $new[ ( int ) $item[ $f_params[ 'parent_field' ] ] ] )

			 ){

				$new[ ( int ) $item[ $f_params[ 'parent_field' ] ] ][ 'children' ][] =& $new[ $id ];

			}

		}

		// Finally, go through and remove children from the outer level
		foreach ( $new as $id => $item ) {

			if ( isset( $item[ $f_params[ 'parent_field' ] ] ) AND $item[ $f_params[ 'parent_field' ] ] ) {
				
				unset( $new[ $id ] );
				
			}

		}

		return $new;

	}

	// --------------------------------------------------------------------
	
	function dirToArray($dir) {
		$contents = array();
		# Foreach node in $dir
		foreach (scandir($dir) as $node) {
			# Skip link to current and parent folder
			if ($node == '.')  continue;
			if ($node == '..') continue;
			# Check if it's a node or a folder
			if (is_dir($dir . DIRECTORY_SEPARATOR . $node)) {
				# Add directory recursively, be sure to pass a valid path
				# to the function, not just the folder's name
				$contents[$node] = $this->dirToArray($dir . DIRECTORY_SEPARATOR . $node);
			} else {
				# Add node, the keys will be updated automatically
				$contents[] = $node;
			}
		}
		# done
		return $contents;
	}
	
	public function dir_tree( $f_params ) {

		// -------------------------------------------------
		// Parsing vars ------------------------------------

		if ( ! is_array( $f_params ) AND ! check_var( $f_params ) ) {

			return ;

		}

		$dir =									( isset( $f_params[ 'dir' ] ) AND is_string( $f_params[ 'dir' ] ) AND $f_params[ 'dir' ] >= 0 ) ? $f_params[ 'dir' ] : '';
		$level =								( isset( $f_params[ 'level' ] ) AND is_numeric( $f_params[ 'level' ] ) AND $f_params[ 'level' ] >= 0 ) ? ( int ) $f_params[ 'level' ] : 0;
		$var_set =								( isset( $f_params[ 'var_set' ] ) ) ? ( bool ) $f_params[ 'var_set' ] : TRUE;
		$include_files =						( isset( $f_params[ 'include_files' ] ) ) ? ( bool ) $f_params[ 'include_files' ] : FALSE;
		$return_multidimensional =				( isset( $f_params[ 'return_multidimensional' ] ) ) ? ( bool ) $f_params[ 'return_multidimensional' ] : FALSE;
		$indented_symbol =						( isset( $f_params[ 'indented_symbol' ] ) AND is_string( $f_params[ 'indented_symbol' ] ) ) ? $f_params[ 'indented_symbol' ] : lang( 'indented_symbol' );
		
		// Parsing vars ------------------------------------
		// -------------------------------------------------
		
		static $tree = array();
		static $u_array = array(); // unidimensional array
		static $child = FALSE;

		// Detect the current branch to append files/directories to
		if ( $child !== FALSE AND isset( $tree[ $child ] ) ) {

			$branch =& $tree[ $child ];

		}
		else
		{
			$branch =& $tree;
		}

		// Force trailing slash on directory
		$dir = rtrim( $dir, DS ) . DS;
		$dirlen = strlen( $dir );

		// Find files/directories
		$items = glob( $dir . '*' );
		
		foreach( $items as $key => $item ) {

			// Get basename
			$base = pathinfo( $item ); //substr($item, $dirlen);
			$base_name = $base[ 'basename' ]; //substr($item, $dirlen);
			$dir_name = $base[ 'dirname' ]; //substr($item, $dirlen);
			//print_r( pathinfo( $item ) );
			// always skip dot files
			if ( $base_name[ 0 ] == '.' ) continue;

			// If is file
			if ( is_file( $item ) AND is_readable( $item ) ) {

				if ( $include_files ) {

					$level++;

					$u_array[ $item ] =  str_repeat( '&nbsp;' , $level * 4 + 4 ) . $indented_symbol . pathinfo( $item, PATHINFO_BASENAME );

					$branch[] = $base_name;

					$level--;

				}

				$child = FALSE;
				continue;

			}

			// If directory
			if ( is_dir( $item ) AND is_readable( $item ) ) {

				// Dirty hack to get around PHP's numerical index rules
				if ( ctype_digit( $base_name ) ) {

					$base_name = '~' . $base_name;

				}

				$u_array[ $dir_name ] = str_repeat( '&nbsp;' , $level * 4 + 4 ) . $indented_symbol . pathinfo( $dir_name, PATHINFO_BASENAME );

				$level++;

				$u_array[ $item ] =  str_repeat( '&nbsp;' , $level * 4 + 4 ) . $indented_symbol . pathinfo( $item, PATHINFO_BASENAME );

				$branch[ $base_name ] = array();
				$child = $base_name;


				$this->dir_tree(

					array(

						'dir' => $item,
						'level' => $level,
						'var_set' => FALSE,
						'include_files' => $include_files,
						'return_multidimensional' => $return_multidimensional,
						'indented_symbol' => $indented_symbol,

					)

				);

				$level--;

				continue;

			}

		}

		// Only return from the root call
		if ( $child === FALSE ) {

			$_tree = $tree;
			$_u_array = $u_array;

			if ( $var_set ) {

				$tree = array();
				$u_array = array();

			}

			if ( $return_multidimensional ) {

				return $_tree;

			}

			return $_u_array;

		}

	}

	// --------------------------------------------------------------------

	public function array_to_parent_ordered( $f_params ) {

		// -------------------------------------------------
		// Parsing vars ------------------------------------

		if ( ! is_array( $f_params ) AND ! check_var( $f_params ) ) {

			return array();

		}

		$f_params[ 'parent_id_string' ] =		( isset( $f_params[ 'parent_id_string' ] ) AND $f_params[ 'parent_id_string' ] > 0 ) ? $f_params[ 'parent_id_string' ] : 'parent_id';
		$parent_id_string = 					$f_params[ 'parent_id_string' ];
		$f_params[ $parent_id_string ] =		( isset( $f_params[ $parent_id_string ] ) AND is_numeric( $f_params[ $parent_id_string ] ) AND $f_params[ $parent_id_string ] >= 0 ) ? ( int ) $f_params[ $parent_id_string ] : 0;
		$f_params[ 'level' ] =					( isset( $f_params[ 'level' ] ) AND is_numeric( $f_params[ 'level' ] ) AND $f_params[ 'level' ] >= 0 ) ? ( int ) $f_params[ 'level' ] : 0;
		$f_params[ 'array' ] =					( isset( $f_params[ 'array' ] ) AND is_array( $f_params[ 'array' ] ) ) ? $f_params[ 'array' ] : array();
		$f_params[ 'id_field' ] =				( isset( $f_params[ 'id_field' ] ) AND is_string( $f_params[ 'id_field' ] ) ) ? $f_params[ 'id_field' ] : 'id';
		$f_params[ 'title_field' ] =			( isset( $f_params[ 'title_field' ] ) AND is_string( $f_params[ 'title_field' ] ) ) ? $f_params[ 'title_field' ] : 'title';
		$f_params[ 'parent_field' ] =			( isset( $f_params[ 'parent_field' ] ) AND is_string( $f_params[ 'parent_field' ] ) ) ? $f_params[ 'parent_field' ] : 'parent';
		$f_params[ 'indented_symbol' ] =		( isset( $f_params[ 'indented_symbol' ] ) AND is_string( $f_params[ 'indented_symbol' ] ) ) ? $f_params[ 'indented_symbol' ] : lang( 'indented_symbol' );

		// Parsing vars ------------------------------------
		// -------------------------------------------------

		$return = $this->_array_to_parent_ordered( $f_params );
		$this->_items_tree = NULL;
		return $return;

	}

	// --------------------------------------------------------------------

	private function _array_to_parent_ordered( $f_params ) {

		$array = $f_params[ 'array' ];
		$parent_wanted = $f_params[ 'parent_id' ];

		foreach( $array as $row ) {

			$id = $row[ $f_params[ 'id_field' ] ];
			$parent_id = $row[ $f_params[ 'parent_field' ] ];
			$title = $row[ $f_params[ 'title_field' ] ];

			if ( $id == $parent_wanted ) {

				$this->_items_tree[ $id ] = $row;
				$this->_items_tree[ $id ][ 'indented_title' ] = str_repeat( '&nbsp;' , $f_params[ 'level' ] * 4 + 4 ) . $f_params[ 'indented_symbol' ] . $row[ $f_params[ 'title_field' ] ];
				$this->_items_tree[ $id ][ 'level' ] = $f_params[ 'level' ];

			}

			if ( $parent_id == $parent_wanted ) {

				$this->_items_tree[ $id ] = $row;
				$this->_items_tree[ $id ][ 'indented_title' ] = str_repeat( '&nbsp;' , $f_params[ 'level' ] * 4 + 4 ) . $f_params[ 'indented_symbol' ] . $row[ $f_params[ 'title_field' ] ];
				$this->_items_tree[ $id ][ 'level' ] = $f_params[ 'level' ];

				$gcalp = $f_params;
				$gcalp[ 'level' ] += 1;
				$gcalp[ 'parent_id' ] = $id;

				$this->_array_to_parent_ordered( $gcalp );

			}

		}

		return $this->_items_tree;

	}

	// --------------------------------------------------------------------

	/**
	 * Obtém o número de filhos a partir de uma árvore de array
	 *
	 * @access	public
	 * @param	id_field			string			the index name for id
	 * @param	parent_id_field		string			the index name for parent_id
	 * @param	parent_id			int				the parent item id
	 * @param	array				array			the items array
	 * @return	boolean
	 */

	private function _get_num_childrens( $id_field, $parent_id_field, $parent_id, $array ){

		$return = 0;

		foreach ( $array as $key => $item ) {

			if ( $item[ $parent_id_field ] == $parent_id ) {

				$return++;

			}

		}

		return $return;

	}

	// --------------------------------------------------------------------

	/**
	 * Fix items ordering
	 *
	 * @access	public
	 * @param	f_params array
	 * @return	boolean
	 */

	public function fix_items_ordering( $f_params = NULL ){

		$table_name =							isset( $f_params[ 'table_name' ] ) ? $f_params[ 'table_name' ] : NULL;
		$id_column_name =						isset( $f_params[ 'id_column_name' ] ) ? $f_params[ 'id_column_name' ] : 'id';
		$parent_column_name =					isset( $f_params[ 'parent_column_name' ] ) ? $f_params[ 'parent_column_name' ] : NULL;
		$parent_value =							isset( $f_params[ 'parent_value' ] ) ? $f_params[ 'parent_value' ] : NULL;
		$ordering_column_name =					isset( $f_params[ 'ordering_column_name' ] ) ? $f_params[ 'ordering_column_name' ] : 'ordering';
		$where_condition =						isset( $f_params[ 'where_condition' ] ) ? $f_params[ 'where_condition' ] : NULL;
		$return =								FALSE;

		if ( ! $table_name AND ! $id_column_name AND ! $ordering_column_name ) return FALSE;

		$order_by = $ordering_column_name . ' ASC';

		if ( $parent_column_name ){

			$order_by = $parent_column_name . ' ASC, ' . $order_by;

		}

		$this->db->select( '*' );
		$this->db->from( $table_name );
		$this->db->order_by( $order_by );

		// if we have parent column name and the parent value
		// We limit the search to items with that parent
		if ( $parent_column_name AND $parent_value ){

			$this->db->where( array( $parent_column_name => $parent_value, ) );

		}

		// get items
		$items = $this->db->get();

		// if we have some result
		if ( $items->num_rows() > 0 ){

			// bring result to array
			$items = $items->result_array();

			$items_parent = array();

			foreach ( $items as $key => $item ) {

				$items_parent[ $parent_column_name ? $item[ $parent_column_name ] : 0 ][] = $item;

			}

			// updating each item
			foreach ( $items_parent as $p_key => & $parent ) {

				foreach ( $parent as $i_key => & $item ) {

					$update_data = array(

						'ordering' => $i_key + 1,

					);

					$return = $this->db->update( $table_name, $update_data, array( $id_column_name => $item[ $id_column_name ] ) );

				}

			}

		}

		return $return;

	}
	public function get_item( $f_params = NULL ){

		$table_name =							isset( $f_params[ 'table_name' ] ) ? $f_params[ 'table_name' ] : NULL;
		$id_column_name =						isset( $f_params[ 'id_column_name' ] ) ? $f_params[ 'id_column_name' ] : 'id';
		$id_value =								isset( $f_params[ 'id_value' ] ) ? $f_params[ 'id_value' ] : NULL;
		$return =								FALSE;

		if ( $table_name AND $id_column_name AND $id_value ){

			$this->db->select( '*' );
			$this->db->from( $table_name );
			$this->db->where( $id_column_name, $id_value );
			$this->db->limit( 1 );

			$return = $this->db->get();
			$return = $return->num_rows() > 0 ? $return : FALSE;

		}

		return $return;

	}
	public function update_item( $f_params = NULL ){

		$table_name =							isset( $f_params[ 'table_name' ] ) ? $f_params[ 'table_name' ] : NULL;
		$data =									isset( $f_params[ 'data' ] ) ? $f_params[ 'data' ] : NULL;
		$condition =							isset( $f_params[ 'condition' ] ) ? $f_params[ 'condition' ] : NULL;
		$return =								FALSE;

		if ( $table_name AND $data AND $condition ){

			$return = $this->db->update( $table_name, $data, $condition );
		}

		return $return;

	}
	public function search_duplicated_positions( $f_params = NULL ){

		$table_name =							isset( $f_params[ 'table_name' ] ) ? $f_params[ 'table_name' ] : NULL;
		$id_column_name =						isset( $f_params[ 'id_column_name' ] ) ? $f_params[ 'id_column_name' ] : 'id';
		$parent_column_name =					isset( $f_params[ 'parent_column_name' ] ) ? $f_params[ 'parent_column_name' ] : NULL;
		$parent_value =							isset( $f_params[ 'parent_value' ] ) ? $f_params[ 'parent_value' ] : NULL;
		$ordering_column_name =					isset( $f_params[ 'ordering_column_name' ] ) ? $f_params[ 'ordering_column_name' ] : 'ordering';
		$return =								FALSE;

		if ( ! $table_name AND ! $id_column_name AND ! $ordering_column_name ) return FALSE;

		$this->db->select( '*' );
		$this->db->from( $table_name );

		// if we have parent column name and the parent value
		// We limit the search to items with that parent
		if ( $parent_column_name AND isset( $parent_value ) ){

			$this->db->where( array( $parent_column_name => $parent_value, ) );

		}

		// get items
		$items = $this->db->get();

		// if we have some result
		if ( $items->num_rows() > 0 ){

			// bring result to array
			$items = $items->result_array();

			foreach ( $items as $key => $item ) {

				// getting the position to search
				$position_to_search = $item[ $ordering_column_name ];

				foreach ( $items as $key2 => $item2 ) {

					// if we founded a item with $position_to_search var value, we have a duplicate
					if ( $item2[ 'id' ] !== $item[ 'id' ] AND $item2[ $ordering_column_name ] == $position_to_search ){

						$return = TRUE;

						break;

					}

				}

				if ( $return ) break;

			}

		}

		return $return;

	}
	public function get_max_ordering( $f_params = NULL ){

		$table_name =							isset( $f_params[ 'table_name' ] ) ? $f_params[ 'table_name' ] : NULL; // required
		$parent_column_name =					isset( $f_params[ 'parent_column_name' ] ) ? $f_params[ 'parent_column_name' ] : NULL; // required
		$parent_value =							isset( $f_params[ 'parent_value' ] ) ? $f_params[ 'parent_value' ] : NULL; // required
		$ordering_column_name =					isset( $f_params[ 'ordering_column_name' ] ) ? $f_params[ 'ordering_column_name' ] : 'ordering'; // required
		$return =								FALSE;

		$this->db->select( '*' );
		$this->db->from( $table_name );
		$where_condition = array(

			$parent_column_name => $parent_value,

		);

		$this->db->where( $where_condition );
		$this->db->select_max( $ordering_column_name );

		$item = $this->db->get();

		if ( $item->num_rows() > 0 ){

			$item = $item->row_array();

			return ( int ) $item[ $ordering_column_name ];

		}

		return FALSE;

	}
	public function set_item_ordering( $f_params = NULL ){

		$table_name =							isset( $f_params[ 'table_name' ] ) ? $f_params[ 'table_name' ] : NULL; // required
		$id_column_name =						isset( $f_params[ 'id_column_name' ] ) ? $f_params[ 'id_column_name' ] : 'id'; // required
		$id_value =								isset( $f_params[ 'id_value' ] ) ? $f_params[ 'id_value' ] : NULL; // required
		$parent_column_name =					isset( $f_params[ 'parent_column_name' ] ) ? $f_params[ 'parent_column_name' ] : NULL; // required
		$ordering_column_name =					isset( $f_params[ 'ordering_column_name' ] ) ? $f_params[ 'ordering_column_name' ] : 'ordering'; // required
		$requested_position =					isset( $f_params[ 'requested_position' ] ) ? $f_params[ 'requested_position' ] : NULL; // required
		$return =								FALSE;

		if ( $table_name AND $id_column_name AND $id_value AND $parent_column_name AND $ordering_column_name AND $requested_position ){

			$min_position = 1;
			$requested_position = ( int ) $requested_position >= $min_position ? ( int ) $requested_position : $min_position;

			// get item params
			$gip = array(

				'table_name' => $table_name,
				'id_column_name' => $id_column_name,
				'id_value' => $id_value,

			);

			$item = $this->get_item( $gip );

			if ( $item->num_rows() > 0 ){

				$item = $item->row_array();
				$current_ordering = $item[ $ordering_column_name ];

				/* ---------------------------------------------- */
				/* ------- search duplicated items params ------- */
				$sdip = array(

					'table_name' => $table_name,
					'parent_column_name' => $parent_column_name,
					'parent_value' => $item[ $parent_column_name ],

				);

				$duplicated_items = $this->search_duplicated_positions( $sdip );

				if ( $duplicated_items ) {

					if ( $this->fix_items_ordering( $sdip ) ) {

						// set item ordering params
						$siop = array(

							'table_name' => $table_name,
							'id_column_name' => $id_column_name,
							'id_value' => $item[ $id_column_name ],
							'parent_column_name' => $parent_column_name,
							'ordering_column_name' => $ordering_column_name,
							'requested_position' => $requested_position,

						);

						return $this->set_item_ordering( $siop );

					}

				}
				/* ------- search duplicated items params ------- */
				/* ---------------------------------------------- */

				// get the max position params
				$gmpp = array(

					'table_name' => $table_name,
					'parent_column_name' => $parent_column_name,
					'parent_value' => $item[ $parent_column_name ],
					'ordering_column_name' => $ordering_column_name,

				);

				// getting the max position
				$max_position = $this->get_max_ordering( $gmpp );

				$requested_position = $requested_position > $max_position ? $max_position : $requested_position;

				// setting up the direction, up, down or null?
				if ( $requested_position > $current_ordering ) {

					$direction = 'up';

				}
				else if ( $requested_position < $current_ordering ) {

					$direction = 'down';

				}
				else {

					$direction = NULL;

				}

				if ( $direction === 'down' ) {

					// current position already is the min position? If yes, nothing to do
					if ( $current_ordering == $min_position ){

						return FALSE;

					}

					// searching for previous items
					$this->db->select( "$id_column_name, $ordering_column_name" );
					$this->db->from( $table_name );
					$this->db->where( $ordering_column_name . ' >=', $requested_position );
					$this->db->where( $ordering_column_name . ' <', $current_ordering );
					$this->db->where( $parent_column_name, $item[ $parent_column_name ] );
					$this->db->order_by( $ordering_column_name . ' ASC' );

					$near_items = $this->db->get();

					// we have previous items? if yes, increase the previous items positions
					if ( $near_items->num_rows() > 0 ) {

						$near_items = $near_items->result_array();

						foreach ( $near_items as $key => $near_item ) {

							$near_item_update_data = array(

								$ordering_column_name => ( ( int ) $near_item[ $ordering_column_name ] + 1 ),

							);

							// update item params
							$uip = array(

								'table_name' => $table_name,
								'data' => $near_item_update_data,
								'condition' => array( $id_column_name => $near_item[ $id_column_name ] ),

							);

							$return = $this->update_item( $uip );

						}

					}

				}
				else if ( $direction === 'up' ) {

					// current position already is the min position? If yes, nothing to do
					if ( $current_ordering == $max_position ){

						return FALSE;

					}

					// searching for previous items
					$this->db->select( "$id_column_name, $ordering_column_name" );
					$this->db->from( $table_name );
					$this->db->where( $ordering_column_name . ' <=', $requested_position );
					$this->db->where( $ordering_column_name . ' >', $current_ordering );
					$this->db->where( $parent_column_name, $item[ $parent_column_name ] );
					$this->db->order_by( $ordering_column_name . ' ASC' );

					$near_items = $this->db->get();

					// we have next items? if yes, increase the previous items positions
					if ( $near_items->num_rows() > 0 ) {

						$near_items = $near_items->result_array();

						foreach ( $near_items as $key => $near_item ) {

							$near_item_update_data = array(

								$ordering_column_name => $near_item[ $ordering_column_name ] - 1,

							);

							// update item params
							$uip = array(

								'table_name' => $table_name,
								'data' => $near_item_update_data,
								'condition' => array( $id_column_name => $near_item[ $id_column_name ] ),

							);

							$return = $this->update_item( $uip );

						}

					}

				}
				else {

					return FALSE;

				}

				// updating the item position
				$update_data = array(

					$ordering_column_name => $requested_position,

				);

				// update item params
				$uip = array(

					'table_name' => $table_name,
					'data' => $update_data,
					'condition' => array( $id_column_name => $item[ $id_column_name ] ),

				);

				$return = $this->update_item( $uip );

			}

		}

		return $return;

	}












	public function parse_params( $params = NULL ){
		
		if ( isset( $params[ 'admin_items_per_page' ] ) AND $params[ 'admin_items_per_page' ] == -1 ){
			
			if ( ! ( (int) $params[ 'admin_custom_items_per_page' ] > 0 ) ){
				
				$params[ 'admin_items_per_page' ] = 10;
				
			}
			else{
				
				$params[ 'admin_items_per_page' ] = $params[ 'admin_custom_items_per_page' ];
				
			}
			
		}
		
		if ( isset( $params[ 'site_items_per_page' ] ) AND $params[ 'site_items_per_page' ] == -1 ){
			
			if ( ! ( (int) $params[ 'site_custom_items_per_page' ] > 0 ) ){
				
				$params[ 'site_items_per_page' ] = 10;
				
			}
			else{
				
				$params[ 'site_items_per_page' ] = $params[ 'site_custom_items_per_page' ];
				
			}
			
		}
		
		if ( ! isset( $params[ 'friendly_urls' ] ) ){
			
			$params[ 'friendly_urls' ] = FALSE;
			
		}
		
		if ( ! isset( $params[ 'language' ] ) ){
			
			$params[ 'language' ] = 'pt-BR';
			
		}
		
		if ( ! isset( $params[ 'dst' ] ) ){
			
			$params[ 'dst' ] = TRUE;
			
		}
		
		return $params;
		
	}

	public function get_components(){

		if ( ! $this->components ){

			$this->db->select('*');
			$this->db->from('tb_components');
			$this->db->where( 'status = 1' );
			$this->db->order_by('id asc');
			$this->components = $this->db->get()->result_array();

			$tmp = array();

			foreach ( $this->components as $key => &$component ) {

				$component[ 'params' ] = get_params( $component[ 'params' ] );
				$component[ 'depends' ] = get_params( $component[ 'depends' ] );

				$tmp[ $component[ 'unique_name' ] ] = $component;

			}

			$this->components = $tmp;

			foreach ( $this->components as $key => &$component ) {

				$this->require_component( $component[ 'unique_name' ] );

			}

		}

		return FALSE;

	}

	public function get_component_by_type( $type = NULL ){

		if ( $type ){

			foreach ( $this->components as $key => $component ) {

				if ( $component[ 'type' ] === $type ){

					return $component[ 'unique_name' ];

				}

			}

		}

		return FALSE;

	}

	public function get_component( $component_name = NULL ){

		if ( $component_name ){

			$this->get_components();

			foreach ( $this->components as $key => &$component ) {

				if ( $component[ 'unique_name' ] == $component_name ){

					return $component;

				}

			}

			show_404();

		}

		return FALSE;

	}

	// função para montar páginas
	public function page( $f_params = NULL ){

	}

	/**
	 * Function _config_file_is_writable
	 *
	 * Check if codeigniter main config file application/config/config.php is writable
	 *
	 * @access	private
	 * @return	bool
	 */

	private function _config_file_is_writable(){

		$config_file = APPPATH . 'config/config.php';

		$this->load->helper( 'file' );

		if ( ! file_exists( $config_file ) OR is_really_writable( $config_file ) ){

			return TRUE;

		}
		else{

			msg( sprintf( lang( 'unable_to_write_config_file' ), ( APPPATH . 'config/config.php' ) ), 'error' );
			return FALSE;

		}
	}

	/*
	 * Function update_config_file
	 *
	 * Save main params to codeigniter main config file application/config/config.php
	 *
	 */

	public function update_config_file( $p = NULL ){

		// verifica se o arquivo de configuração é gravável
		if ( $this->_config_file_is_writable() ){

			$params = array();

			foreach ( $this->config->config as $key => $value) {

				$params[ $key ] = $value;

			}

			$params = array_merge( $params, $p );

			if ( ! empty( $params ) ) {

				if ( $params AND gettype( $params ) === 'array' ){

					$data[] = '<?php if ( ! defined( \'BASEPATH\' ) ) exit( \'No direct script access allowed\' );';
					
					$int_params = array(
						
						'log_threshold',
						'sess_expiration',
						'sess_time_to_update',
						'csrf_expire',
						'email_config_smtp_port',
						'admin_items_per_page',
						'admin_custom_items_per_page',
						'site_items_per_page',
						'site_custom_items_per_page',
						'thumbnails_width',
						'thumbnails_height',
						
					);
					
					foreach ( $params as $key => $param ) {
						
						if ( in_array( $key, $int_params ) ) {
							
							$value = ( int )$param;
							
						}
						else {
							
							if ( is_string( $param ) ){
								
								if ( $param === '1' ){
									
									$value = "TRUE";
									
								}
								else if ( $param === '0' ){
									
									$value = "FALSE";
									
								}
								else{
									$value = str_replace( "'", "\'" , $param );
									
									$value = "'" . $value . "'";
									
								}
								
							}
							else if ( is_bool( $param ) ){
								
								$value = ( int )$param > 0 ? 'TRUE' : 'FALSE';
								
							}
							
							if ( $key === 'base_url' ){
								
								$value = 'BASE_URL';
								
							}
							if ( $key === 'params' ){
								
								unset( $params[ $key ] );
								
							}
							
						}
						
						//print "<pre>" . print_r( $param, true ) . "</pre>";
						
						$data[] = '$config[\'' . $key . '\'] = ' . "$value" . ';';
						
					}
					
					$int_params = NULL;
					unset( $int_params );
					
					$output = implode( "\n", $data );

					if ( write_file( APPPATH . 'config/config.php', $output ) ){

						return TRUE;

					}
					else{

						msg( sprintf( lang( 'unable_to_write_file' ), ( APPPATH . 'config/config.php' ) ), 'error' );

					}

				}

			}

		}

		return FALSE;

	}

	public function require_component( $f_params = NULL ){

		log_message( 'debug', "[Components] Require component function triggered" );

		// verificamos se a variável passada é uma string ou array, se for string, este deve ser o unique_name do componente,
		// caso contrário, esta deve ser um array contendo as informações do componente requerido
		if ( gettype( $f_params ) === 'string' ){

			log_message( 'debug', "[Components] Require component by string" );

			$f_params = check_var( $this->components[ $f_params ] ) ? $this->components[ $f_params ] : ( check_var( $this->components_stack[ $f_params ] ) ? $this->components_stack[ $f_params ] : NULL );

			$f_params[ 'requester' ] = $f_params[ 'unique_name' ];

		}

		$requester =						isset( $f_params[ 'requester' ] ) ? $f_params[ 'requester' ] : NULL; // essa variável impedirá que a função entre em loop, pois dois componentes podem depender um do outro
		$unique_name =						isset( $f_params[ 'unique_name' ] ) ? $f_params[ 'unique_name' ] : NULL;
		$type =								isset( $f_params[ 'type' ] ) ? $f_params[ 'type' ] : NULL;
		$version =							isset( $f_params[ 'version' ] ) ? $f_params[ 'version' ] : NULL;

		// executamos isto apenas se o nome do componente não for informado, mas apenas o seu tipo.
		// obtemos então o PRIMEIRO componente encontrado do tipo informado
		if ( ! $unique_name AND $type ){

			$component = $this->get_component_by_type( $type );

			$unique_name = $component[ 'unique_name' ];

		}

		log_message( 'debug', "[Components] Component required: " . $unique_name );

		if ( $unique_name OR $type ){

			$component = check_var( $this->components_stack[ $unique_name ] ) ? $this->components_stack[ $unique_name ] : ( check_var( $this->components[ $unique_name ] ) ? $this->components[ $unique_name ] : FALSE );

			if ( $component ){

				log_message( 'debug', "[Components] Searching component on components stack ... " );

				if ( check_var( $this->components_stack[ $unique_name ] ) ){

					log_message( 'debug', "[Components] Founded on components stack" );

					return TRUE;

				}

				log_message( 'debug', "[Components] Not founded on components stack" );

				log_message( 'debug', "[Components] Searching component on active components list ... " );

				if ( check_var( $this->components[ $unique_name ] ) ){

					log_message( 'debug', "[Components] Founded on active components list" );

					log_message( 'debug', "[Components] Validating dependencies ..." );

					if ( $this->validate_component_dependencies( array( 'unique_name' => $unique_name, 'requester' => $requester ) ) === TRUE ){

						log_message( 'debug', "[Components] Validation passed! Appending component to components stack" );

						$this->components_stack[ $unique_name ] = $component;

						$this->components_stack = array_filter( $this->components_stack );

						return TRUE;

					}
					else{

						log_message( 'debug', "[Components] Validation fail for requester $requester!" );

						return FALSE;

					}

				}

			}

			log_message( 'debug', "[Components] Component not founded!" );

		}

		return FALSE;

	}

	public function validate_component_dependencies( $f_params = NULL ){

		if ( gettype( $f_params ) === 'string' ){

			$f_params = check_var( $this->components[ $f_params ] ) ? $this->components[ $f_params ] : ( check_var( $this->components_stack[ $f_params ] ) ? $this->components_stack[ $f_params ] : NULL );

			$f_params[ 'requester' ] = $f_params[ 'unique_name' ];

		}

		$requester =						isset( $f_params[ 'requester' ] ) ? $f_params[ 'requester' ] : NULL; // essa variável impedirá que a função entre em loop, pois dois componentes podem depender um do outro
		$unique_name =						isset( $f_params[ 'unique_name' ] ) ? $f_params[ 'unique_name' ] : NULL;
		$type =								isset( $f_params[ 'type' ] ) ? $f_params[ 'type' ] : NULL;
		$version =							isset( $f_params[ 'version' ] ) ? $f_params[ 'version' ] : NULL;

		if ( ! $unique_name AND $type ){

			$component = $this->get_component_by_type( $type );

			$unique_name = $component[ 'unique_name' ];

		}

		if ( $requester AND ( $unique_name OR $type OR $version ) ){

			$component = check_var( $this->components_stack[ $unique_name ] ) ? $this->components_stack[ $unique_name ] : ( check_var( $this->components[ $unique_name ] ) ? $this->components[ $unique_name ] : FALSE );

			if ( $component ){

				log_message( 'debug', "[Components] Checking dependencies for component " . $component[ 'unique_name' ] . " from requester $requester" );

				$validate = FALSE;

				if ( check_var( $component[ 'depends' ] ) ){

					foreach ( $component[ 'depends' ] as $key => $depend ) {

						if ( check_var( $depend[ 'unique_name' ] ) ){

							$dep_unique_name = $depend[ 'unique_name' ];

						}
						else if ( check_var( $depend[ 'type' ] ) ){

							$dep = $this->get_component_by_type( $depend[ 'type' ] );
							$dep_unique_name = $depend[ 'unique_name' ];

						}

						if ( $dep_unique_name === $requester ){

							log_message( 'debug', "[Components] The requester component is equal to current checking component, skipping ..." );

							$validate = TRUE;

						}
						else{

							// validate component dependecies params
							$vcdp = array(

								'requester' => $component[ 'unique_name' ],
								'unique_name' => check_var( $depend[ 'unique_name' ] ) ? $depend[ 'unique_name' ] : NULL,
								'type' => check_var( $depend[ 'type' ] ) ? $depend[ 'type' ] : NULL,
								'version' => check_var( $depend[ 'version' ] ) ? $depend[ 'version' ] : NULL,

							);

							$validate = $this->require_component( $vcdp );

						}

					}

				}
				else {

					log_message( 'debug', "[Components] There's no dependencies." );

					$validate = TRUE;

				}

			}

		}

		return $validate;
	
	}
	
	function check_session_config(){
		
		// Antes de carregar a biblioteca Sessions, verificamos se esta utilizará o banco de dados, se sim criamos a tabela apropriada, caso não exista
		if ( $this->config->item( 'sess_use_database' ) === TRUE AND $this->config->item( 'sess_table_name' ) != '' AND ! $this->db->table_exists( $this->config->item( 'sess_table_name' ) ) AND file_exists( APPPATH . DS . 'libraries' . DS . 'session' . DS . 'tb_sessions.sql' ) ){
			
			$this->load->database();
			
			$sess_table_query = file_get_contents( APPPATH . DS . 'libraries' . DS . 'session' . DS . 'tb_sessions.sql' );
			$sess_table_query = str_replace( '{sess_table_name}' , $this->db->dbprefix . $this->config->item( 'sess_table_name' ), $sess_table_query );

			$sql_clean = '';
			foreach (explode("\n", $sess_table_query) as $line){
				
				if(isset($line[0]) && $line[0] != "#"){
					$sql_clean .= $line."\n";
				}
				
			}
			
			//echo $sql_clean;
			
			foreach (explode(";\n", $sql_clean) as $sql){
				$sql = trim($sql);
				//echo  $sql.'<br/>============<br/>';
				if($sql)
				{
					$this->db->query($sql);
				}
			}
			
		}
		
	}
	
	function load_view( $env = NULL, $rel_view_path = NULL, $view, $data = NULL, $html = FALSE ){
		
		if ( ! ( isset( $env ) AND in_array( $env, array( 'admin', 'site' ) ) ) ) {
			
			$env = $this->environment;
			
		}
		
		$content = '';
		
		if ( isset( $env ) ) {
			
			$theme_load_views_path = call_user_func( $env . '_theme_views_path' ) . rtrim( $rel_view_path, DS ) . DS;
			$theme_views_path = THEMES_PATH . $theme_load_views_path;
			
			$default_load_views_path = get_constant_name( strtoupper( $env ) . '_LOAD_VIEWS_PATH' ) . rtrim( $rel_view_path, DS ) . DS;
			
			$default_views_path = VIEWS_PATH . $default_load_views_path;
			
			// verificando se o tema atual possui a view
			if ( file_exists( $theme_views_path . $view . '.php') ){
				
				$content = $this->load->view( $theme_load_views_path . $view, $data, $html );

			}
			// verificando se a view existe no diretório de views padrão
			else if ( file_exists( $default_views_path . $view . '.php') ){
				
				$content = $this->load->view( get_constant_name( $env . '_LOAD_VIEWS_PATH' ) . rtrim( $rel_view_path, DS ) . DS . $view, $data, $html );

			}
			else {
				
				$content = lang( 'load_view_fail' ) . ': <b>' . rtrim( $rel_view_path, DS ) . DS . $view . '.php</b>';
				
			}
		}
		else if ( $content = $this->load->view( $view, $data, $html ) ){
			
		}
		
		return $content;
		
	}
	
}
