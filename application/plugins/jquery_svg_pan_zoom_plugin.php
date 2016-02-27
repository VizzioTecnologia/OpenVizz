<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jquery_svg_pan_zoom_plugin extends Plugins_mdl{
	
	public function run( &$data, $params = NULL ){
		
		$return = FALSE;
		
		if ( parent::_performed( 'jquery' ) ) {
			
			log_message( 'debug', '[Plugins] jQuery svg pan zoom plugin initialized' );
			
			$this->voutput->append_head_script( 'jquery_svg_pan_zoom', JS_DIR_URL . '/plugins/jquery_svg_pan_zoom/1.03/compiled/jquery.svg.pan.zoom.js' );
			$this->voutput->append_head_script_declaration( 'jquery_svg_pan_zoom', $this->load->view( 'plugins/jquery_svg_pan_zoom/default/jquery_svg_pan_zoom', NULL, TRUE ), NULL, NULL );
			
			$return = TRUE;
			
		}
		else {
			
			log_message( 'debug', '[Plugins] jQuery svg pan zoom plugin plugin could not be executed! jQuery plugin not performed!' );
			
			$return = FALSE;
			
		}
		
		parent::_set_performed( 'jquery_svg_pan_zoom' );
		
		return $return;
		
	}
	
}
