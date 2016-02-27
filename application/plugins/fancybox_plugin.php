<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fancybox_plugin extends Plugins_mdl{
	
	public function run( &$data, $params = NULL ){
		
		$return = FALSE;
		
		if ( parent::_performed( 'jquery' ) ) {
			
			log_message( 'debug', '[Plugins] Fancybox plugin initialized' );
			
			$this->voutput->append_head_stylesheet( 'fancybox', JS_DIR_URL . '/plugins/fancybox/fancyBox-master/source/jquery.fancybox.css' );
			$this->voutput->append_head_script( 'fancybox', JS_DIR_URL . '/plugins/fancybox/fancyBox-master/source/jquery.fancybox.pack.js' );
			
			$return = TRUE;
			
		}
		else{
			
			log_message( 'debug', '[Plugins] Fancybox plugin could not be executed! jQuery plugin not performed!' );
			
			$return = FALSE;
			
		}
		
		parent::_set_performed( 'fancybox' );
		
		return $return;
		
	}
	
}
