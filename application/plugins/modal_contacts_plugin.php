<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modal_contacts_plugin extends Plugins_mdl{
	
	public function run( &$data, $params = NULL ){
		
		$return = FALSE;
		
		if ( parent::_performed( array( 'fancybox', 'jquery_inline_edit', 'modal_rf_file_picker', 'image_cropper', 'jquery_ui', ) ) ){
			
			log_message( 'debug', '[Plugins] Modal Contacts plugin initialized' );
			
			$this->voutput->append_head_script_declaration( 'modal_contacts', $this->load->view( 'admin/plugins/modal_contacts/default/modal_contacts', NULL, TRUE ), NULL, NULL );
			
			$return = TRUE;
			
		}
		else{
			
			log_message( 'debug', '[Plugins] Modal Contacts plugin could not be executed! Some required plugins not performed!' );
			
			$return = FALSE;
			
		}
		
		parent::_set_performed( 'modal_contacts' );
		
		return $return;
		
	}
	
}
