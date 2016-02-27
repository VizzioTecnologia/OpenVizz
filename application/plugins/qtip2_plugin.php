<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Qtip2_plugin extends Plugins_mdl{
	
	public function run( &$data, $params = NULL ){
		
		$return = FALSE;
		
		if ( parent::_performed( 'jquery' ) ){
			
			log_message( 'debug', '[Plugins] qTip2 plugin initialized' );
			
			$this->load->language('calendar');
			
			$this->voutput->append_head_stylesheet( 'qtip2', JS_DIR_URL . '/plugins/qtip2/jquery.qtip.min.css' );
			$this->voutput->append_head_script( 'qtip2', JS_DIR_URL . '/plugins/qtip2/jquery.qtip.min.js' );
			$this->voutput->append_head_script_declaration( 'qtip2', $this->load->view( 'admin/plugins/qtip2/default/qtip2', NULL, TRUE ), NULL, NULL );
			
			parent::_set_performed( 'qtip2' );
			
			$return = TRUE;
			
		}
		else {
			
			log_message( 'debug', '[Plugins] qTip2 plugin could not be executed! jQuery plugin not performed!' );
			
			$return = FALSE;
			
		}
		
		parent::_set_performed( 'jquery_scrolltop' );
		
		return $return;
		
	}
	
}
