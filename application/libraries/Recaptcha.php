<?php

require_once( 'recaptcha/src/autoload.php' );

class Recaptcha {
	
	// --------------------------------------------------------------------
	
	private $obj_classc;
	
	// --------------------------------------------------------------------
	
	public function __construct( $config = array() ) {
		
		// batch config setter, associative array must be given
		if ( is_array( $config ) ){
			
			$this->obj_classc = new \ReCaptcha\ReCaptcha( $config[ 'secret' ] );
			
		}
		
	}
	
	// --------------------------------------------------------------------
	
	public function __call( $name, $arguments ) {
		
		return call_user_func_array(array($this->obj_classc, $name), $arguments);
		
	}
	
	// --------------------------------------------------------------------
	
}
