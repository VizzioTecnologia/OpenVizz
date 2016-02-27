<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jquery_lazy_plugin extends Plugins_mdl{
	
	public function run( & $params = NULL ){
		
		$return = FALSE;
		
		if ( parent::_performed( 'jquery' ) ){
			
			log_message( 'debug', '[Plugins] jQuery Lazy plugin initialized' );
			
			$this->load->language('calendar');
			
			$this->voutput->append_head_script( 'jquery_lazy', JS_DIR_URL . '/plugins/jquery_lazy/jquery.lazy.js' );
			$this->voutput->append_head_script_declaration( 'jquery_lazy', $this->load->view( 'plugins/jquery_lazy/default/jquery_lazy', NULL, TRUE ), NULL, NULL );
			
			parent::_set_performed( 'jquery_lazy' );
			
			$return = TRUE;
			
		}
		else {
			
			log_message( 'debug', '[Plugins] jQuery Lazy plugin could not be executed! jQuery plugin not performed!' );
			
			$return = FALSE;
			
		}
		
		parent::_set_performed( 'jquery_lazy' );
		
		return $return;
		
	}
	
}
