<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require( APPPATH . 'controllers/admin/main.php' );

class Unid extends Main {

	var $c_urls;

	public function __construct() {
		
		parent::__construct();
		
		if ( ! $this->load->is_model_loaded( 'ud_api' ) ) {
			
			$this->load->model( 'unid_api_mdl', 'ud_api' );
			
		}
		
		$this->load->language( array( 'admin/submit_forms', 'admin/unid' ) );
		
		set_current_component( 'submit_forms' );
		
	}

	public function api() {
		
		$f_params = $this->uri->ruri_to_assoc();
		
		echo $this->ud_api->api( $f_params );
		
	}
	
}
