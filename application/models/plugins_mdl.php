<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Plugins_mdl extends CI_Model{
	
	static private $_plugins = NULL;
	static private $_validated_plugins = NULL;
	static private $_types_performed = NULL;
	
	// --------------------------------------------------------------------
	
	public function __construct(){
		
		$this->_check_plugins();
		
	}
	
	// --------------------------------------------------------------------
	
	// --------------------------------------------------------------------
	
	/**
	 * Load specific or all plugins
	 * 
	 * This is a alias to _load function
	 * 
	 * @access	public
	 * @param mixed
	 * @param string
	 * @param string
	 * @return mixed
	 */
	public function load( $var1 = NULL, $var2 = NULL, $var3 = NULL, $var4 = NULL ){
		
		return $this->_load( $var1, $var2, $var3, $var4 );
		
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Load specific or all plugins
	 * 
	 * NULL return means that the required plug-in (or a few) could not be loaded, or the plugin not set properly his return. remember, return COULD NOT BE NULL if all is ok on called plugin
	 * 
	 * @access		public
	 * @param		mixed		plugin(s) to load, can be plugin name (string), plugins list (array indexed by plugin name), or a multidimensional array with two sub arrays indexed with 'names' and/or 'types', where each array must be arrays indexed with their search string (names and types)
	 * @param		string		plugin(s) type(s) to load, can be plugin name (string), plugins types list (array indexed by plugin name)
	 * @param		string		return type, can be: "bool" (default) or "string". Bool return type, just check if the apropriate plugin model was loaded (return false if some model wasn't loaded, but continue loading other plugins of same type), and the string return type, returns the plugin(s) output (concatenated if more then one plugin).
	 * @return		mixed		see return type param
	 */
	private function _load( $var1 = NULL, $var2 = NULL, $var3 = NULL, $var4 = NULL ){
		
		// check if we dont't have available plugins
		if ( ! $this->_check_plugins() ){
			
			return FALSE;
			
		}
		
		$return = NULL;
		
		// -------------------------------------------------
		// Parsing vars ------------------------------------
		
		if ( ( check_var( $var1 ) AND is_array( $var1 ) ) OR ( check_var( $var2 ) AND is_array( $var2 ) ) ) {
			
			// 1° check if is array
			if ( check_var( $var1 ) AND is_array( $var1 ) ) {
				
				$f_params = $var1;
				$params = check_var( $f_params[ 'params' ] ) ? $f_params[ 'params' ] : NULL;
				
				$return_type = ( check_var( $f_params[ 'return_type' ] ) AND is_string( $f_params[ 'return_type' ] ) ) ? $f_params[ 'return_type' ] : 'bool';
				
				// batch mode
				if (
					
					// treat each item as plugin name
					! isset( $f_params[ 'names' ] ) AND
					! isset( $f_params[ 'types' ] ) AND
					! isset( $f_params[ 'name' ] ) AND
					! isset( $f_params[ 'type' ] ) AND
					! isset( $f_params[ 'params' ] ) AND
					! isset( $f_params[ 'return_type' ] ) 
					
				) {
					
					foreach ( $f_params as $key => $name ) {
						
						$return = $this->_load( $name );
						
						if ( ! isset( $return ) ) {
							
							$return = NULL;
							break;
							
						}
						
					}
					
					return $return;
					
				}
				else if ( isset( $f_params[ 'names' ] ) OR isset( $f_params[ 'types' ] ) ) {
					
					$success = TRUE;
					
					if ( isset( $f_params[ 'names' ] ) ) {
						
						if ( is_array( $f_params[ 'names' ] ) ) {
							
							foreach ( $f_params[ 'names' ] as $key => $name ) {
								
								$lp = array(
									
									'name' => $name,
									'params' => $params,
									'return_type' => $return_type,
									
								);
								
								$return = $this->_load( $lp );
								
								if ( ! $return ) {
									
									$success = FALSE;
									
								}
								
							}
							
						}
						else if ( is_string( $f_params[ 'names' ] ) ) {
							
							$lp = array(
								
								'name' => $f_params[ 'names' ],
								'params' => $params,
								'return_type' => $return_type,
								
							);
							
							$return = $this->_load( $lp );
							
						}
						
					}
					
					if ( isset( $f_params[ 'types' ] ) ) {
						
						if ( is_array( $f_params[ 'types' ] ) ) {
							
							foreach ( $f_params[ 'types' ] as $key => $type ) {
								
								$lp = array(
									
									'type' => $type,
									'params' => $params,
									'return_type' => $return_type,
									
								);
								
								$return = $this->_load( $lp );
								
								if ( ! $return ) {
									
									$success = FALSE;
									
								}
								
							}
							
							return $return;
							
						}
						else if ( is_string( $f_params[ 'types' ] ) ) {
							
							$lp = array(
								
								'type' => $f_params[ 'types' ],
								'params' => $params,
								'return_type' => $return_type,
								
							);
							
							$return = $this->_load( $lp );
							
						}
						
					}
					
					return $success;
					
				}
				
				$name =										( check_var( $f_params[ 'name' ] ) AND is_string( $f_params[ 'name' ] ) ) ? $f_params[ 'name' ] : NULL;
				$type =										( check_var( $f_params[ 'type' ] ) AND is_string( $f_params[ 'type' ] ) ) ? $f_params[ 'type' ] : NULL;
				$return_type =								( check_var( $f_params[ 'return_type' ] ) AND is_string( $f_params[ 'return_type' ] ) ) ? $f_params[ 'return_type' ] : NULL;
				$params =									check_var( $f_params[ 'params' ] ) ? $f_params[ 'params' ] : NULL;
				
			}
			
			//echo '[_load]params for ' . $name . $type . ': <pre>' . print_r( $params, TRUE ) . "</pre><br/>\n";
			// types
			if ( check_var( $var2 ) AND is_array( $var2 ) ) {
				
				$f_params = $var2;
				
				foreach ( $f_params as $key => $type ) {
					
					$return = $this->_load( NULL, $type, $return_type, $params );
					
					if ( ! isset( $return ) ) {
						
						$return = NULL;
						break;
						
					}
					
				}
				
				return $return;
				
			}
			
		}
		else {
		
			$name =										( check_var( $var1 ) AND is_string( $var1 ) ) ? $var1 : NULL;
			$type =										( check_var( $var2 ) AND is_string( $var2 ) ) ? $var2 : NULL;
			$return_type =								( check_var( $var3 ) AND is_string( $var3 ) ) ? $var3 : 'bool';
			$params =									( check_var( $var4 ) AND is_array( $var4 ) ) ? $var4 : NULL;
			
		}
		
		// Parsing vars ------------------------------------
		// -------
		
		if (
			
			( isset( $type ) AND isset( self::$_plugins[ 'by_type' ][ $type ] ) ) OR
			( ! isset( $name ) AND ! isset( $type ) AND isset( self::$_plugins[ 'by_name' ] ) )
			
		) {
			
			
			if ( ! isset( $name ) AND isset( $type ) AND isset( self::$_plugins[ 'by_type' ][ $type ] ) ) $plugins_source = self::$_plugins[ 'by_type' ][ $type ];
			else $plugins_source = self::$_plugins[ 'by_name' ];
			
			$success = FALSE;
			$bool_return = TRUE;
			$string_return = '';
			
			foreach ( $plugins_source as $key => $plugin ) {
				
				if ( $return_type === 'bool' ) {
					
					$success = $this->_load( $plugin[ 'name' ], NULL, $return_type, $params );
					
					if ( ! $success ) {
						
						$bool_return = FALSE;
						continue;
						
					}
					
				}
				else if ( $return_type === 'string' ) {
					
					$string_return .= ( string ) $this->get_output( $plugin[ 'name' ], NULL, $return_type, $params );
					
				}
				
			}
			
			if ( $return_type === 'bool' ) {
				
				$return = $bool_return;
				
			}
			else if ( $return_type === 'string' ) {
				
				$return = $string_return;
				
			}
			
		}
		// heart's function
		else if ( isset( $name ) AND ! isset( $type ) AND isset( self::$_plugins[ 'by_name' ][ $name ] ) ) {
			
			if ( ! $this->_performed( $name ) AND $this->_check_dependencies( $name ) ) {
				
				$this->_load_depends( $name );
				
				$plugin_model_name = $name . '_plugin';
				
				// loading model
				if ( file_exists( PLUGINS_PATH . $plugin_model_name . '.php' ) ) {
					
					if ( ! $this->_is_loaded( $name ) ) {
						
						$this->load->model( '../plugins/' . $plugin_model_name, $name );
						
					}
					
					// loadgin language
					if ( file_exists( LANG_PATH . $this->mcm->filtered_system_params[ 'language' ] . DS . 'plugins' . DS . $name . '_lang.php' ) ) {
						
						$this->load->language( 'plugins/' . $name );
						
					}
					
					$this->_run_plugin( $name, $return_type, $params );
					
				}
				else {
					
					log_message( 'debug', "[Plugins] The plugin " . $name . " could not be initialized, file " . $plugin_model_name . ".php not found!" );
					
				}
				
			}
			
			if ( $return_type === 'bool' ) {
				
				$return = $this->_is_loaded( $name );
				
			}
			else if ( $return_type === 'string' ) {
				
				$return = ( string ) $this->get_output( $name, NULL, $return_type, $params );
				
			}
			
		}
		
		//echo ( isset( $name ) ? $name . ' ' : '' ) . 'return: ' . "\n";
		//var_dump( $return );
		//echo '-------' . "\n";
		
		return $return;
		
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Check if a specif plugin was loaded
	 * 
	 * @access		public
	 * @param		string		plugin name
	 * @return		bool
	 */
	
	private function _is_loaded( $name ) {
		
		return $this->load->is_model_loaded( $name );
		
	}
	
	// ? why exists a private? I dont remember
	
	public function is_loaded( $name ) {
		
		return $this->_is_loaded( $name );
		
	}
	
	// --------------------------------------------------------------------
	
	public function _check_dependencies( $name = NULL, $type = NULL ){
		
		$success = FALSE;
		
		if ( $name ){
			
			if ( check_var( self::$_validated_plugins[ 'names' ][ $name ] ) ) {
				
				$success = TRUE;
				
			}
			
			if ( ! $success ) {
				
				if ( check_var( self::$_plugins[ 'by_name' ][ $name ] ) ) {
					
					if ( check_var( self::$_plugins[ 'by_name' ][ $name ][ 'depends' ] ) ) {
						
						//echo 'checando dependencias de ' . $name . ': <br />' . ( json_encode( self::$_plugins[ 'by_name' ][ $name ][ 'depends' ], TRUE ) ) . '<br />';
						
						// iterate plugin dependencies
						foreach ( self::$_plugins[ 'by_name' ][ $name ][ 'depends' ] as $key => $depend ) {
							
							if ( isset( self::$_validated_plugins[ 'names' ][ $depend[ 'name' ] ] ) ) {
								
								$success = TRUE;
								
							}
							else {
								
								$success = $this->_check_dependencies( $depend[ 'name' ] );
								
							}
							
						}
						
					}
					else {
						
						$success = TRUE;
						
					}
					
				}
				else {
					
					//echo 'Falha ao validar ' . $name . ': Não encontrado<br />';
					return FALSE;
					
				}
				
			}
			
			if ( $success ) {
				
				//echo $name . ' validado<br />';
				self::$_validated_plugins[ 'names' ][ $name ] = TRUE;
				return TRUE;
				
			}
			else {
				
				//echo 'falha ao validar ' . $name . '<br />';
				return FALSE;
				
			}
			
		}
		else if ( $type ) {
			
			if ( check_var( self::$_validated_plugins[ 'types' ][ $type ] ) ) {
				
				$success = TRUE;
				
			}
			
			if ( ! $success ) {
				
				if ( check_var( self::$_plugins[ 'by_type' ][ $type ] ) ) {
					
					foreach ( self::$_plugins[ 'by_type' ][ $type ] as $key => $plugin ) {
						
						if ( $this->_check_dependencies( $plugin[ 'name' ] ) ) {
							
							$success = TRUE;
							
						}
						else {
							
							$success = FALSE;
							break;
							
						};
						
					}
					
				}
				else {
				
					//echo 'Falha ao validar o tipo ' . $type . ': Não encontrado. Verifique se existem plugins deste tipo instalados e ativos<br />';
					return FALSE;
					
				}
				
			}
			
			if ( $success ) {
				
				//echo $type . ' validado<br />';
				self::$_validated_plugins[ 'types' ][ $type ] = TRUE;
				return TRUE;
				
			}
			else {
				
				//echo 'falha ao validar ' . $type . '<br />';
				return FALSE;
				
			}
			
		}
		
		return $success;
		
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Load all plugin depends
	 * 
	 * @access protected
	 * @param string
	 * @return bool
	 */
	protected function _load_depends( $name = NULL ){
		
		// check if we dont't have available plugins
		if ( ! $this->_check_plugins() ){
			
			return FALSE;
			
		}
		
		$return = FALSE;
		
		if ( check_var( $name ) AND is_string( $name ) ) {
			
			if ( check_var( self::$_plugins[ 'by_name' ][ $name ][ 'depends' ] ) ) {
				
				//echo $name . ' possui as seguintes dependencias:<br /><ul>';
				
				foreach ( self::$_plugins[ 'by_name' ][ $name ][ 'depends' ] as $key => $depend ) {
					
					//echo '<li>' . ( isset( $depend[ 'name' ] ) ? 'nome: ' . $depend[ 'name' ] : ( ( isset( $depend[ 'type' ] ) ? 'tipo: ' . $depend[ 'type' ] : '' ) ) ) . '</li>';
					
					if ( isset( $depend[ 'name' ] ) ) {
						
						$return = $this->_load( $depend[ 'name' ] );
						
					}
					
					if ( isset( $depend[ 'type' ] ) ) {
						
						$return = $this->_load( NULL, $depend[ 'type' ] );
						
					}
					
					//echo 'retorno obtido de ' . ( isset( $depend[ 'name' ] ) ? $depend[ 'name' ] : ( ( isset( $depend[ 'type' ] ) ? $depend[ 'type' ] : '' ) ) ) . ': <b>'; var_dump( $return ); echo '</b><br />';
					
					if ( ! isset( $return ) ) {
						
						//echo 'retorno obtido de ' . ( isset( $depend[ 'name' ] ) ? $depend[ 'name' ] : ( ( isset( $depend[ 'type' ] ) ? $depend[ 'type' ] : '' ) ) ) . ': <b> e nulo</b><br />';
						
						$output = NULL;
						break;
						
					}
					
				}
				
				//echo '</ul><br />';
				
			}
			else {
				
				//echo $name . ' nao tem dependencias<br />';
				
			}
			
		}
		
		//echo (int)$return;
		
		return $return;
		
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Set plugin performed status
	 * 
	 * @access protected
	 * @param string
	 * @param bool
	 * @return bool
	 */
	protected function _set_performed( $name = NULL, $value = TRUE ){
		
		if ( check_var( $name ) AND is_string( $name ) ) {
			
			//echo 'setando o plugin ' . $name . ' como ' . (int) $value . '<br />';
			
			self::$_plugins[ 'by_name' ][ $name ][ 'performed' ] = ( bool ) $value;
			self::$_types_performed[ self::$_plugins[ 'by_name' ][ $name ][ 'type' ] ] = ( bool ) $value;
			
			//echo 'estado do plugin ' . $name . ': ' . ( $this->_performed( $name ) ? 'marcado como executado' : 'marcado como nao executado' ) . '<br />';
			
			return TRUE;
			
		}
		
		return FALSE;
		
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Get plugin performed status. If an array is given, the return will be false if any items have not been marked as performed
	 * 
	 * @access public
	 * @param string
	 * @param mixed
	 * @return bool
	 */
	public function performed( $var1 = NULL, $var2 = NULL ){
		
		return $this->_performed( $var1, $var2 );
		
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * @access protected
	 * @param string
	 * @param bool
	 * @return bool
	 */
	protected function _performed( $var1 = NULL, $var2 = NULL ){
		
		// check if we dont't have available plugins
		if ( ! $this->_check_plugins() ){
			
			return FALSE;
			
		}
		
		// -------------------------------------------------
		// Parsing vars ------------------------------------
		
		$return = FALSE;
		
		if ( check_var( $var1 ) AND is_array( $var1 ) ) {
			
			$f_params = $var1;
			
			// batch mode
			if ( ! isset( $f_params[ 'names' ] ) AND ! isset( $f_params[ 'types' ] ) ) { // treat each item as plugin name
				
				foreach ( $f_params as $key => $name ) {
					
					$return = $this->_performed( $name );
					
					if ( ! $return ) {
						
						$return = FALSE;
						break;
						
					}
					
				}
				
				return $return;
				
			}
			else if ( isset( $f_params[ 'names' ] ) OR isset( $f_params[ 'types' ] ) ) {
				
				if ( isset( $f_params[ 'names' ] ) ) {
					
					if ( is_array( $f_params[ 'names' ] ) ) {
						
						foreach ( $f_params[ 'names' ] as $key => $name ) {
							
							$return = $this->_performed( NULL, $type );
							
							if ( ! $return ) {
								
								$return = FALSE;
								break;
								
							}
							
						}
						
						return $return;
						
					}
					else if ( is_string( $f_params[ 'names' ] ) ) {
						
						return $this->_performed( $f_params[ 'names' ] );
						
					}
					
				}
				
				if ( isset( $f_params[ 'types' ] ) ) {
					
					if ( is_array( $f_params[ 'types' ] ) ) {
						
						foreach ( $f_params[ 'types' ] as $key => $type ) {
							
							$return = $this->_performed( NULL, $type );
							
							if ( ! isset( $return ) ) {
								
								$return = NULL;
								break;
								
							}
							
						}
						
						return $return;
						
					}
					else if ( is_string( $f_params[ 'types' ] ) ) {
						
						return $this->_performed( NULL, $f_params[ 'types' ] );
						
					}
					
				}
				
			}
			
			$name =										( check_var( $f_params[ 'name' ] ) AND is_string( $f_params[ 'name' ] ) ) ? $f_params[ 'name' ] : NULL;
			$type =										( check_var( $f_params[ 'type' ] ) AND is_string( $f_params[ 'type' ] ) ) ? $f_params[ 'type' ] : NULL;
			
		}
		else {
			
			$name =										( check_var( $var1 ) AND is_string( $var1 ) ) ? $var1 : NULL;
			$type =										( check_var( $var2 ) AND is_string( $var2 ) ) ? $var2 : NULL;
			
		}
		
		// Parsing vars ------------------------------------
		// -------------------------------------------------
		
		if ( check_var( $name ) AND is_string( $name ) ) {
			
			if ( isset( self::$_plugins[ 'by_name' ][ $name ] ) ) {
				
				return ( bool ) self::$_plugins[ 'by_name' ][ $name ][ 'performed' ];
				
			}
			
		}
		else if ( check_var( $type ) AND is_string( $type ) ) {
			
			if ( isset( self::$_types_performed[ $type ] ) ) {
				
				return ( bool ) self::$_types_performed[ $type ];
				
			}
			
		}
		
		return FALSE;
		
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * set plugin output
	 * 
	 * @access protected
	 * @param string
	 * @param mixed
	 * @return bool
	 */
	protected function _set_plugin_output( $name = NULL, $output = NULL ){
		
		// check if we dont't have available plugins
		if ( ! $this->_check_plugins() ){
			
			return FALSE;
			
		}
		
		if ( check_var( $name ) AND is_string( $name ) ) {
			
			self::$_plugins[ 'by_name' ][ $name ][ 'output' ] = $output;
			return TRUE;
			
		}
		
		return FALSE;
		
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * get plugin output
	 * 
	 * Return FALSE if no plugins outputs found
	 * 
	 * @access public
	 * @param string
	 * @param string
	 * @param mixed | Return type, can be: "string", or any other value to return the output original value
	 * @return mixed
	 */
	public function get_output( $name = NULL, $type = NULL, $return_type = 'string', $params = NULL ){
		//echo '[get_output]params for ' . $name . $type . ': <pre>' . print_r( $params, TRUE ) . "</pre><br/>\n";
		// check if we dont't have available plugins
		if ( ! $this->_check_plugins() ){
			
			return FALSE;
			
		}
		
		if ( isset( $name ) AND is_string( $name ) AND ! isset( $type ) AND isset( self::$_plugins[ 'by_name' ][ $name ] ) ) {
			
			$is_loaded = $this->_is_loaded( $name );
			//echo '[get_output] ' . $name . ' is loaded? ' . (int)$is_loaded . "<br/>\n";
			if ( ! $is_loaded ) {
				
				if ( ! ( $is_loaded = $this->_load( $name, NULL, $return_type, $params ) ) );
				
			}
			
			return ( $return_type === 'string' ) ? ( string ) self::$_plugins[ 'by_name' ][ $name ][ 'output' ] : self::$_plugins[ 'by_name' ][ $name ][ 'output' ];
			
		}
		else if ( isset( $type ) AND is_string( $type ) AND isset( self::$_plugins[ 'by_type' ][ $type ] )) {
			
			$output = NULL;
			
			foreach ( self::$_plugins[ 'by_type' ][ $type ] as $key => $plugin ) {
				//echo '[get_output]$plugin ' . $plugin[ 'name' ] . ': <pre>' . print_r( $params, TRUE ) . "</pre><br/>\n";
				if ( isset( self::$_plugins[ 'by_name' ][ $plugin[ 'name' ] ] ) ) {
					
					$is_loaded = $this->_is_loaded( $plugin[ 'name' ] );
					//echo '[get_output] ' . $name . ' is loaded? ' . (int)$is_loaded . "<br/>\n";
					if ( ! $is_loaded ) {
						
						if ( ! ( $is_loaded = $this->_load( $plugin[ 'name' ], NULL, 'bool', $params ) ) ) break;
						
					}
					
					if ( $is_loaded AND isset( self::$_plugins[ 'by_name' ][ $plugin[ 'name' ] ][ 'output' ] )) {
						
						$output[ $plugin[ 'name' ] ] = self::$_plugins[ 'by_name' ][ $plugin[ 'name' ] ][ 'output' ];
						
						if ( $return_type === 'string' ) {
							
							$out_str = '';
							
							foreach ( $output as $key => $out ) {
								
								$out_str .= ( string ) $out;
								
							}
							
							$output = $out_str;
							
						}
						
					}
					
				}
				
			}
			
			return $output;
			
		}
		
		return FALSE;
		
	}
	
	// --------------------------------------------------------------------
	
	public function get_params_spec( $name = NULL, $type = NULL ){
		
		if ( isset( $name ) AND is_string( $name ) ){
			
			if ( $this->_load( $name ) ) {
				
				$get_params_spec_function_name = FALSE;
				
				if ( method_exists( $this->{ $name }, 'get_params_spec' ) ) {
					
					$get_params_spec_function_name = 'get_params_spec';
					
				}
				
				if ( $get_params_spec_function_name )
					
					return ( array ) $this->{ $name }->{ $get_params_spec_function_name }();
					
				else return array();
				
			}
			
		}
		else if ( isset( $type ) AND is_string( $type ) ){
			
			$out = array();
			
			$this->_load( NULL, $type );
			
			if ( is_array( $this->get_plugins( $type ) ) ) {
				
				foreach ( $this->get_plugins( $type ) as $key => $plugin ) {
					
					$out = array_merge_recursive( $out, $this->get_params_spec( $plugin[ 'name' ] ) );
					
				}
				
			}
			
			return $out;
			
		}
		
		return array();
		
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Return a plugin array
	 * 
	 * @access public
	 * @param string
	 * @return mixed
	 */
	public function get_plugin( $name = NULL ){
		
		// check if we dont't have available plugins
		if ( ! $this->_check_plugins() ){
			
			return FALSE;
			
		}
		
		if ( check_var( $name ) AND is_string( $name ) ) {
			
			if ( check_var( self::$_plugins[ 'by_name' ][ $name ] ) ) {
				
				return self::$_plugins[ 'by_name' ][ $name ];
				
			}
			
		}
		
		return FALSE;
		
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Return a plugins array list from, or not from a specific type
	 * 
	 * @access public
	 * @param string
	 * @return mixed
	 */
	public function get_plugins( $type = NULL ){
		
		// check if we dont't have available plugins
		if ( ! $this->_check_plugins() ){
			
			return FALSE;
			
		}
		
		if ( check_var( $type ) AND is_string( $type ) ) {
			
			if ( check_var( self::$_plugins[ 'by_type' ][ $type ] ) ) {
				
				return self::$_plugins[ 'by_type' ][ $type ];
				
			}
			
		}
		else if ( check_var( self::$_plugins[ 'by_name' ] ) ) {
			
			return self::$_plugins[ 'by_name' ];
			
		}
		
		return FALSE;
		
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Call the run function of plugin
	 * 
	 * @access private
	 * @param string
	 * @param mixed
	 * @return bool
	 */
	private function _run_plugin( $name = NULL, $return_type = 'bool', $data = NULL ){
		
		// check if we dont't have available plugins
		if ( ! $this->_check_plugins() ){
			
			return FALSE;
			
		}
		
		$data[ 'params' ] = $data;
		
		if ( ! isset( $return_type ) ) {
			
			if ( ! isset( $data[ 'return_type' ] ) ) {
				
				$data[ 'return_type' ] = 'bool';
				
			}
			
		}
		else{
			
			$data[ 'return_type' ] = $return_type;
			
		}
		
		//echo 'running ' . $name . '<br />';
		
		if ( check_var( $name ) AND is_string( $name ) ) {
			
			if ( method_exists( $this->{ $name }, 'run' ) ) {
				
				//echo "<br/>\nrunning " . $name . '...' . "<br/>\n";
				return $this->{ $name }->run( $data );
				
			}
			
		}
		
		return FALSE;
		
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Check if plugins was loaded
	 * @return bool
	 */
	private function _check_plugins(){
		
		// NULL type means no plugins loaded yet
		if ( self::$_plugins === NULL ) {
			
			$this->db->select( '*' );
			$this->db->from( 'tb_plugins' );
			$this->db->where( 'status', '1' );
			$this->db->order_by( 'ordering ASC, id ASC, name ASC' );
			
			self::$_plugins = $this->db->get();
			
			// if we have some result
			if ( self::$_plugins->num_rows() > 0 ) {
				
				log_message( 'debug', "[Plugins] Available plugins were sought" );
				
				self::$_plugins = self::$_plugins->result_array();
				
				if ( check_var( self::$_plugins ) ) {
					
					$_tmp_array = self::$_plugins;
					
					self::$_plugins = array();
					
					foreach ( $_tmp_array as $key => $plugin ) {
						
						self::$_plugins[ 'by_name' ][ $plugin[ 'name' ] ] = $plugin;
						self::$_plugins[ 'by_name' ][ $plugin[ 'name' ] ][ 'performed' ] = FALSE;
						self::$_plugins[ 'by_name' ][ $plugin[ 'name' ] ][ 'output' ] = NULL;
						self::$_plugins[ 'by_name' ][ $plugin[ 'name' ] ][ 'depends' ] = json_decode( $plugin[ 'depends' ], TRUE );
						
						self::$_plugins[ 'by_type' ][ $plugin[ 'type' ] ][ $plugin[ 'name' ] ] = & self::$_plugins[ 'by_name' ][ $plugin[ 'name' ] ];
						
					}
					
					//echo '<pre>' . print_r( self::$_plugins, TRUE ) . '</pre>';
					
					return TRUE;
					
				}
				
			}
			
			// if no results, set to FALSE
			log_message( 'debug', "[Plugins] Plugins fetch done, but no active plugins found" );
			self::$_plugins = FALSE;
			return FALSE;
			
		}
		// FALSE type means plugins was fetched from DB, but no available plugins found
		else if ( self::$_plugins === FALSE ) {
			
			return FALSE;
			
		}
		// array type means that the plugins was loaded
		else if ( is_array( self::$_plugins ) ) {
			
			return TRUE;
			
		}
		
	}
	
	// --------------------------------------------------------------------
	
}
