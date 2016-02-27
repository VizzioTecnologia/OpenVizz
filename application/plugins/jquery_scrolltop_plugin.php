<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jquery_scrolltop_plugin extends Plugins_mdl{
	
	public function run( &$data, $params = NULL ){
		
		$return = FALSE;
		
		if ( parent::_performed( 'jquery' ) ) {
			
			log_message( 'debug', '[Plugins] jQuery scrolltop plugin initialized' );
			
			$this->voutput->append_head_stylesheet( 'jquery_scrolltop', JS_DIR_URL . '/plugins/jquery_scrolltop/jquery-scrolltop-default.css' );
			$this->voutput->append_head_script_declaration( 'jquery_scrolltop', $this->load->view( 'admin/plugins/jquery_scrolltop/default/jquery_scrolltop', NULL, TRUE ), NULL, NULL );
			
			$return = TRUE;
			
		}
		else{
			
			log_message( 'debug', '[Plugins] jQuery scrolltop plugin could not be executed! jQuery plugin not performed!' );
			
			$return = FALSE;
			
		}
		
		parent::_set_performed( 'jquery_scrolltop' );
		
		return $return;
		
	}
	
}
