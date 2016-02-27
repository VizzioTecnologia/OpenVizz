<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Viacms_live_search_plugin extends Plugins_mdl{
	
	public function run( &$data, $params = NULL ){
		
		$return = FALSE;
		
		if ( parent::_performed( 'qtip2' ) ){
			
			log_message( 'debug', '[Plugins] Via CMS live search plugin initialized' );
			
			$this->voutput->append_head_stylesheet( 'viacms_live_search', STYLES_DIR_URL . '/plugins/viacms-live-search/viacms.liveSearch.css' );
			$this->voutput->append_head_script( 'viacms_live_search', JS_DIR_URL . '/plugins/viacms-live-search/viacms.liveSearch.js' );
			$this->voutput->append_head_script_declaration( 'viacms_live_search', $this->load->view( 'plugins/viacms_live_search/default/viacms_live_search', $data, TRUE ), NULL, NULL );
			
			$return = TRUE;
			
		}
		else{
			
			log_message( 'debug', '[Plugins] Via CMS live search plugin could not be executed! qTip2 plugin not performed!' );
			
			$return = FALSE;
			
		}
		
		parent::_set_performed( 'viacms_live_search' );
		
		return $return;
		
	}
	
}
