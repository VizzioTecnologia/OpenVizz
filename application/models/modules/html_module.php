<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Html_module extends CI_Model{
	
	public function run( $module_data = NULL ){
		
		$params = $module_data[ 'params' ];
		
		if ( ( isset( $params[ 'html_module_content' ] ) ) ) {
			/*
			$title = '';
			
			if ( check_var( $module_data[ 'params' ][ 'show_title' ] ) ) {
				
				$title = '
					
					<div class="module-title">
					
					<h3>
						
						' . $module_data[ 'title' ] . '
						
					</h3>
					
				</div>';
				
			}
			
			$f_params = array(
				
				'type' => 'content',
				'return_type' => 'string',
				'content' => $params[ 'html_module_content' ],
				
			);
			
			$content_plugins_output = $this->plugins->get_output( NULL, 'content', 'string', $f_params  );
			
			return '<div class="module-wrapper html-module ' . $params[ 'module_class' ] . '">' . $title . '<div class="module-content">' . $params[ 'html_module_content' ] . '</div></div>';
			*/
			
			$f_params = array(
				
				'type' => 'content',
				'return_type' => 'string',
				'content' => $params[ 'html_module_content' ],
				
			);
			
			$return = $params[ 'html_module_content' ];
			
			$content_plugins_output = $this->plugins->get_output( NULL, 'content', 'string', $f_params  );
			
			$title = check_var( $module_data[ 'params' ][ 'show_title' ] ) ? '
			<div class="module-title">
				
				<h3>
					
					' . $module_data[ 'title' ] . '
					
				</h3>
				
			</div>' : '';
			
			
			return '<div class="module-wrapper html-module ' . $params[ 'module_class' ] . '">' . $title . '<div class="module-content">' . ( $content_plugins_output ? $content_plugins_output : $return ) . '</div></div>';
				
			//return $this->plugins->load( $f_params );
			
		}
			
		return '';
		
	}
	
	public function get_module_params(){
		
		$params = get_params_spec_from_xml( MODULES_PATH . 'html/params.xml' );
		
		return $params;
	}
	
}
