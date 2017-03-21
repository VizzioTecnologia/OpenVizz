<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'controllers/main.php');

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

	public function api(){
		
		$f_params = $this->uri->ruri_to_assoc();
		
		if ( isset( $f_params[ 'im' ] ) ) {
			
			$f_params[ 'im' ] = NULL;
			unset( $f_params[ 'im' ] );
			
			$this->load->language( 'admin/unid_ee' );
			
			echo lang( '_' . rand( 0, 3 ) );
			
		}
		else {
			
			echo $this->udacm->api( $f_params );
			
		}
		
	}
	
}
