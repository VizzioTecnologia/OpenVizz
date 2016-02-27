<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Yetii_plugin extends Plugins_mdl{
	
	public function run( &$data, $params = NULL ){
		
		$return = FALSE;
		
		if ( parent::_performed( 'jquery' ) ){
			
			log_message( 'debug', '[Plugins] Yetii plugin initialized' );
			
			$this->voutput->append_head_script( 'yetii', JS_DIR_URL . '/plugins/yetii/yetii-min.js' );
			$this->voutput->append_head_script_declaration( 'yetii', $this->load->view( 'admin/plugins/yetii/default/yetii', NULL, TRUE ), NULL, NULL );
			
			$return = TRUE;
			
		}
		else{
			
			log_message( 'debug', '[Plugins] Yetii plugin could not be executed! jQuery plugin not performed!' );
			
			$return = FALSE;
			
		}
		
		parent::_set_performed( 'yetii' );
		
		return $return;
		
	}
	
}
