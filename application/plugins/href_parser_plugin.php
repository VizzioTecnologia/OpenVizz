<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Href_parser_plugin extends Plugins_mdl{
	
	public function run( &$data, $params = NULL ){
		
		log_message( 'debug', '[Plugins] Href parser plugin initialized' );
		
		$regex = '#(href|src)="([^:"]*)("|(?:(?:%20|\s|\+)[^"]*"))#';
		//$textarea = preg_replace('/href\s*=\s*(?<href>"[^\\"]*"|\'[^\\\']*\')/e', 'expand_links("$1")', $textarea);
		
		$content = $this->voutput->get_content();
		
		preg_match_all( $regex, $content, $matches );
		
		if ( count( $matches[ 2 ] ) ){
			
			$this->load->helper( 'url' );
			
			$content = preg_replace( $regex, '$1="' . get_url( '$2$3' ), $content );
			$this->voutput->set_content( $content );
			
			parent::load( 'href_parser' );
			
		}
		
		return TRUE;
		
	}
	
}
