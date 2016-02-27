<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Cache_mdl extends CI_Model{
	
	static private $_cache = NULL;
	
	// --------------------------------------------------------------------
	
	public function __construct(){
		
	}
	
	// --------------------------------------------------------------------
	
	// --------------------------------------------------------------------
	
	/**
	 * Cache setter / getter
	 * 
	 * @access	public
	 * @param mixed
	 * @param mixed
	 * @return mixed
	 */
	public function cache( $var1 = NULL, $var2 = NULL ){
		
		// batch cache setter, associative array must be given
		if ( is_array( $var1 ) AND ! isset( $var2 ) ){
			
			foreach ( $var1 as $c_key => $c_value ) {
				
				$this->cache( $c_key, $c_value );
				
			}
			
		}
		// getter
		else if ( $var1 AND is_string( $var1 ) AND ! isset( $var2 ) ){
			
			$var1 = md5( $var1 );
			
			return isset( self::$_cache[ $var1 ] ) ? self::$_cache[ $var1 ] : NULL;
			
		}
		// setter
		else if ( $var1 AND is_string( $var1 ) AND isset( $var2 ) ) {
			
			self::$_cache[ md5( $var1 ) ] = $var2;
			
			return TRUE;
			
		}
		// return all configs
		else {
			
			return self::$_cache;
			
		}
		
	}
	
	// --------------------------------------------------------------------
	
}
