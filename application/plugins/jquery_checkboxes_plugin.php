<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jquery_checkboxes_plugin extends Plugins_mdl{
	
	public function run( &$data, $params = NULL ){
		
		$return = FALSE;
		
		if ( parent::_performed( 'jquery' ) ) {
			
			log_message( 'debug', '[Plugins] jQuery checkboxes plugin initialized' );
			
			$this->voutput->append_head_script( 'jquery_checkboxes', JS_DIR_URL . '/plugins/jquery_checkboxes/jquery_checkboxes.js' );
			$this->voutput->append_head_script_declaration( 'jquery_checkboxes', $this->load->view( 'admin/plugins/jquery_checkboxes/default/jquery_checkboxes', NULL, TRUE ), NULL, NULL );
			
			$return = TRUE;
			
		}
		else{
			
			log_message( 'debug', '[Plugins] jQuery checkboxes plugin could not be executed! jQuery plugin not performed!' );
			
			$return = FALSE;
			
		}
		
		parent::_set_performed( 'jquery_checkboxes' );
		
		return $return;
		
	}
	
}
