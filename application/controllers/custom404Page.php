<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

require( APPPATH . 'controllers/main.php' );

class custom404Page extends Main {
	
	public function __construct(){
		
		parent::__construct();
		
	}
	
	public function index(){  
		
		$this->_page(
			
			array(
				
				'component_view_folder' => 'main',
				'function' => 'errors',
				'action' => '404',
				'layout' => ( ( isset( $data[ 'params' ][ '404_layout' ] ) AND $data[ 'params' ][ '404_layout' ] ) ? $data[ 'params' ][ '404_layout' ] : 'default' ),
				'view' => '404',
				'data' => @$data,
				
			 )
			
		);
		
		//echo $this->output->get_output();
		exit;
	}
	
}
