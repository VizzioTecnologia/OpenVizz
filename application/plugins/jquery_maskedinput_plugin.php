<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jquery_maskedinput_plugin extends Plugins_mdl{
	
	public function run( &$data, $params = NULL ){
		
		$return = FALSE;
		
		if ( parent::_performed( 'jquery' ) ) {
			
			log_message( 'debug', '[Plugins] jQuery Masked Input plugin initialized' );
			
			$this->voutput->append_head_script( 'jquery', JS_DIR_URL . '/plugins/jquery_maskedinput/jquery.maskedinput.min.js' );
			$this->voutput->append_body_end_script_declaration( 'jquery_maskedinput', $this->load->view( 'plugins/jquery_maskedinput/default/jquery_maskedinput', NULL, TRUE ), NULL, NULL );
			
			$return = TRUE;
			
			parent::_set_performed( 'jquery_maskedinput' );
			
			return $return;
			
		}
		else{
			
			log_message( 'debug', '[Plugins] jQuery Masked Input plugin could not be executed! jQuery plugin not performed!' );
			
			$return = FALSE;
			
		}
		
	}
	
}
