<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require( APPPATH . 'controllers/admin/main.php' );

class Unid extends Main {

	var $c_urls;

	public function __construct() {
		
		parent::__construct();
		
		if ( ! $this->load->is_model_loaded( 'udacm' ) ) {
			
			$this->load->model( 'common/unid_api_common_model', 'udacm' );
			
		}
		
		$this->load->language( array( 'admin/submit_forms', 'admin/unid' ) );
		
		set_current_component( 'submit_forms' );
		
	}

	public function api() {
		
		$f_params = $this->uri->ruri_to_assoc();
		
		echo $this->udacm->api( $f_params, TRUE );
		
	}
	
}
