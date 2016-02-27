<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Facebook_module extends CI_Model{
	
	public $module_name = 'facebook';
	
	public function run( $module_data = NULL ){
		
		$params = $module_data[ 'params' ];
		$data[ 'module_data' ] = & $module_data;
		
		if ( check_var( $params[ 'fb_page_id' ] ) ) {
			
			$layout = isset( $module_data[ 'params' ][ $this->module_name . '_layout' ] ) ? $module_data[ 'params' ][ $this->module_name . '_layout' ] : 'default';
			
			if ( file_exists( THEMES_PATH . site_theme_modules_views_path() . $this->module_name . DS . $layout . DS . $layout . '.php' ) ){
				
				return $this->load->view( site_theme_modules_views_path() . $this->module_name . DS . $layout . DS . $layout, $data, TRUE );
				
			}
			// verificando se a view existe no diretório de views de módulos padrão
			else if ( file_exists( MODULES_VIEWS_PATH . $this->module_name . DS . $layout . DS . $layout . '.php' ) ){
				
				return $this->load->view( MODULES_DIR_NAME . DS . $this->module_name . DS . $layout . DS . $layout, $data, TRUE );
				
			}
			else {
				
				return lang( 'load_view_fail' ) . ':<br />' . THEMES_PATH . site_theme_modules_views_path() . $this->module_name . DS . $layout . DS . $layout . '.php' . '<br />' . MODULES_VIEWS_PATH . $this->module_name . DS . $layout . DS . $layout . '.php';
				
			}
			
		}
		
		return lang( $this->module_name . '_plugin_not_configured' );
		
	}
	
	public function get_module_params(){
		
		$params = get_params_spec_from_xml( MODULES_PATH . $this->module_name . '/params.xml' );
		
		// carregando os layouts do tema atual
		$_options_layouts = dir_list_to_array( THEMES_PATH . site_theme_modules_views_path() . $this->module_name );
		// carregando os layouts do diretório de views padrão
		$_options_layouts = array_merge( $_options_layouts, dir_list_to_array( MODULES_VIEWS_PATH . $this->module_name ) );
		
		$_params_spec_key = $this->module_name . '_module_config';
		
		foreach ( $params[ 'params_spec' ][ $_params_spec_key ] as $key => $element ) {
			
			if ( $element[ 'name' ] == $this->module_name . '_layout' ){
				
				$spec_options = array();
				
				if ( isset( $params[ 'params_spec' ][ $_params_spec_key ][ $key ][ 'options' ] ) )
					$spec_options = $params[ 'params_spec' ][ $_params_spec_key ][ $key ][ 'options' ];
				
				$params[ 'params_spec' ][ $_params_spec_key ][ $key ][ 'options' ] = is_array( $spec_options ) ? $spec_options + $_options_layouts : $_options_layouts;
				
			}
			
		}
		
		return $params;
		
	}
	
}
