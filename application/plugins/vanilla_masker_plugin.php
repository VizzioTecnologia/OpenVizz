<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vanilla_masker_plugin extends Plugins_mdl{
	
	public function run( &$data, $params = NULL ){
		
		$return = FALSE;
		
		log_message( 'debug', '[Plugins] Vanilla Masker plugin initialized' );
		
		$this->voutput->append_body_end_script( 'jquery', JS_DIR_URL . '/plugins/vanilla_masker/vanilla-masker.min.js' );
		$this->voutput->append_body_end_script_declaration( 'vanilla_masker', $this->load->view( 'plugins/vanilla_masker/default/vanilla_masker', NULL, TRUE ), NULL, NULL );
		
		$return = TRUE;
		
		parent::_set_performed( 'vanilla_masker' );
		
		return $return;
		
	}
	
}
