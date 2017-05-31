<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class System_parser_plugin extends Plugins_mdl{
	
	public function run( &$data, $params = NULL ){
		
		log_message( 'debug', '[Plugins] System parser plugin initialized' );
		
		$regex = '/\{sp_get:(\s*.*?)\}/i';
		
		$content = $this->voutput->get_content();
		
		preg_match_all( $regex, $content, $matches );
		
		$find = $replace = array();
		
		if ( count( $matches[ 1 ] ) ){
			
			foreach ( $matches[ 1 ] as $key => $match ) {
				
				$find[] = $matches[ 0 ][ $key ];
				$replace[] = $this->input->get( $match );
				
			}
			
			$content = str_replace( $find, $replace, $content );
			$this->voutput->set_content( $content );
			
			parent::load( 'system_parser' );
			
		}
		
		return TRUE;
		
	}
	
}
