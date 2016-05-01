<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Php_js_plugin extends Plugins_mdl{
	
	public function run( &$data, $params = NULL ){
		
		log_message( 'debug', '[Plugins] PHP js plugin initialized' );
		
		$this->voutput->append_head_script( 'php_js', JS_DIR_URL . '/plugins/php_js/php.js' );
		
		parent::_set_performed( 'php_js' );
		
		return TRUE;
		
	}
	
}
