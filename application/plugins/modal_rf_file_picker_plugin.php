<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modal_rf_file_picker_plugin extends Plugins_mdl{
	
	public function run( &$data, $params = NULL ){
		
		$return = FALSE;
		
		if ( parent::_performed( 'fancybox' ) ) {
			
			log_message( 'debug', '[Plugins] Modal RF File Picker plugin initialized' );
			
			$this->voutput->append_body_end_script_declaration( 'modal_rf_file_picker', $this->load->view( 'admin/plugins/modal_rf_file_picker/default/modal_rf_file_picker', NULL, TRUE ), NULL, NULL );
			
			$return = TRUE;
			
		}
		else{
			
			log_message( 'debug', '[Plugins] Modal RF File Picker plugin could not be executed! Fancybox plugin not performed!' );
			
			$return = FALSE;
			
		}
		
		parent::_set_performed( 'modal_rf_file_picker' );
		
		return $return;
		
	}
	
}
