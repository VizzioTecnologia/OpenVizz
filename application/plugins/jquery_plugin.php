<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Jquery_plugin extends Plugins_mdl{
		
		public function run( &$data, $params = NULL ){
			
			log_message( 'debug', '[Plugins] jQuery plugin initialized' );
			
			$this->voutput->append_head_script( 'jquery', JS_DIR_URL . '/plugins/jquery/jquery-1.11.1.min.js' );
			
			parent::_set_performed( 'jquery' );
			
			return TRUE;
			
		}
		
	}
