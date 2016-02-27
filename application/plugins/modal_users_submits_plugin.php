<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modal_users_submits_plugin extends Plugins_mdl{
	
	public function run( & $data, $params = NULL ){
		
		$return = FALSE;
		
		if ( parent::_performed( array( 'names' => 'jquery', 'types' => 'js_time_picker' ) ) ) {
			
			log_message( 'debug', '[Plugins] Modal Users submits plugin initialized' );
			
			$this->voutput->append_head_script_declaration( 'modal_users_submits', $this->load->view( 'admin/plugins/modal_users_submits/default/modal_users_submits', $data, TRUE ), NULL, NULL );
			
			$return = TRUE;
			
		}
		else{
			
			log_message( 'debug', '[Plugins] Modal Users submits plugin could not be executed! Some required plugins not performed!' );
			
			$return = FALSE;
			
		}
		
		parent::_set_performed( 'modal_users_submits' );
		
		return $return;
		
	}
	
}
