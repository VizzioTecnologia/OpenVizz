<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tinymce_plugin extends Plugins_mdl{
	
	public function run( &$data, $params = NULL ){
		
		$return = FALSE;
		
		if ( $this->mcm->filtered_system_params[ 'js_text_editor' ] == 'tinymce' ){
			
			if ( parent::_performed( 'jquery' ) ) {
				
				log_message( 'debug', '[Plugins] TinyMCE plugin initialized' );
				
				$this->voutput->append_head_script( 'tinymce', JS_DIR_URL . '/plugins/tinymce/tinymce_4.6.1/js/tinymce/tinymce.min.js' );
				$this->voutput->append_head_script_declaration( 'tinymce', $this->load->view( 'plugins/tinymce/default/tinymce', NULL, TRUE ), NULL, NULL );
				
				$return = TRUE;
				
			}
			else {
				
				log_message( 'debug', '[Plugins] TinyMCE plugin could not be executed! jQuery plugin not performed!' );
				
				$return = FALSE;
				
			}
			
		}
		
		parent::_set_performed( 'tinymce' );
		
		return $return;
		
	}
	
}
