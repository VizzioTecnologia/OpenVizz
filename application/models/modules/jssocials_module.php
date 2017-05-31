<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jssocials_module extends CI_Model{
	
	private $module_name = 'jssocials';
	
	public function run( $module_data = NULL ){
		
		$this->lang->load( $this->module_name . '_module' );
		
		if ( $this->plugins->load( 'jquery' ) ){
			
			$params = & $module_data[ 'params' ];
			$data[ 'module_data' ] = & $module_data;
			$data[ 'module_data' ][ 'name' ] = & $this->module_name;
			
			if ( ! defined( 'JSSOCIALS' ) ) define( 'JSSOCIALS', TRUE );
			
			if ( check_var( $params[ $this->module_name . '_layout' ] ) ) {
				
				$layout = isset( $params[ $this->module_name . '_layout' ] ) ? $params[ $this->module_name . '_layout' ] : 'default';
				
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
			
		}
		
		return lang( $this->module_name . '_plugin_not_configured' );
		
	}
	
	public function get_module_params(){
		
		$this->lang->load( $this->module_name . '_module' );
		
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
		
		// carregando os layouts do tema atual
		$layouts_options = file_list_to_array( STYLES_PATH . MODULES_DIR_NAME . DS . $this->module_name, '*.css' );
		$layouts_options = array_merge_recursive_distinct( $layouts_options, file_list_to_array( STYLES_PATH . MODULES_DIR_NAME . DS . $this->module_name, '*.css.php' ) );
		
		$layouts_options = array_merge_recursive_distinct( $layouts_options, file_list_to_array( THEMES_PATH . site_theme_path() . 'assets' . DS . 'css' . DS . 'jssocials-themes' . DS . $this->module_name, '*.css' ) );
		$layouts_options = array_merge_recursive_distinct( $layouts_options, file_list_to_array( THEMES_PATH . site_theme_path() . 'assets' . DS . 'css' . DS . 'jssocials-themes' . DS . $this->module_name, '*.css.php' ) );
		
// 						print "<pre>" . print_r( $layouts_options, true ) . "</pre>"; exit;
						
		foreach ( $params[ 'params_spec' ][ $this->module_name . '_module_config' ] as $key => $element ) {
			
			if ( $element[ 'name' ] == $this->module_name . '_theme' ){
				
				$spec_options = array();
				
				if ( isset( $params[ 'params_spec' ][ $this->module_name . '_module_config' ][ $key ][ 'options' ] ) )
					$spec_options = $params[ 'params_spec' ][ $this->module_name . '_module_config' ][ $key ][ 'options' ];
				
				$params[ 'params_spec' ][ $this->module_name . '_module_config' ][ $key ][ 'options' ] = is_array( $spec_options ) ? $spec_options + $layouts_options : $layouts_options;
				
			}
			
		}
		
		return $params;
		
	}
	
}
