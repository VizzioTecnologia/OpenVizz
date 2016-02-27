<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jquery_ui_timepicker_plugin extends Plugins_mdl{
	
	public function run( &$data, $params = NULL ){
		
		$return = FALSE;
		
		if ( parent::_performed( 'jquery_ui' ) ){
			
			log_message( 'debug', '[Plugins] jQuery UI time picker plugin initialized' );
			
			$this->load->language('time');
			
			$this->voutput->append_head_stylesheet( 'jquery_ui_timepicker', JS_DIR_URL . '/plugins/jquery-ui-timepicker/jquery-ui-timepicker-addon.css' );
			$this->voutput->append_head_script( 'jquery_ui_timepicker', JS_DIR_URL . '/plugins/jquery-ui-timepicker/jquery-ui-timepicker-addon.js' );
			$this->voutput->append_head_script_declaration( 'jquery_ui_timepicker', $this->load->view( 'plugins/jquery_ui_timepicker/default/jquery_ui_timepicker', NULL, TRUE ), NULL, NULL );
			
			$return = TRUE;
			
		}
		else {
			
			log_message( 'debug', '[Plugins] jQuery UI time picker plugin could not be executed! jQuery plugin not performed!' );
			
			$return = FALSE;
			
		}
		
		parent::_set_performed( 'jquery_ui_timepicker' );
		
		return $return;
		
	}
	
}
