<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jquery_inline_edit_plugin extends Plugins_mdl{
	
	public function run( &$data, $params = NULL ){
		
		$return = FALSE;
		
		if ( parent::_performed( 'jquery_ui' ) ) {
			
			log_message( 'debug', '[Plugins] jQuery inline edit plugin initialized' );
			
			$this->voutput->append_head_script_declaration( 'jquery_inline_edit', $this->load->view( 'admin/plugins/jquery_inline_edit/default/jquery_inline_edit', NULL, TRUE ), NULL, NULL );
			
			$return = TRUE;
			
		}
		else{
			
			log_message( 'debug', '[Plugins] jQuery inline edit plugin could not be executed! jQuery UI plugin not performed!' );
			
			$return = FALSE;
			
		}
		
		parent::_set_performed( 'jquery_inline_edit' );
		
		return $return;
		
	}
	
}
