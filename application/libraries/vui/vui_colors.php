<?php

use \Exception;

class Vui_colors extends Vui{
	
	function __construct(){
		
		if ( ! defined ( 'DS' ) ) define( 'DS', DIRECTORY_SEPARATOR );
		
	}
	
	public function __get( $name ) {
		
		if ( isset ( $this->$name ) ) {
			
			return $this->$name;
			
		}
		
		return new Vui_color( '#000', 0, 'missing color: ' . $name );
		
	}
	
}

?>
