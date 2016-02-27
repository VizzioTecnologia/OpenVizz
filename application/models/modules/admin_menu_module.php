<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_menu_module extends CI_Model{
	
	protected $module_name = 'admin_menu';
	
	public function run( $module_data = NULL ){
		
		$params = $module_data[ 'params' ];
		
		$layout = $params[ $this->module_name . '_layout' ];
		
		$theme_views_url = call_user_func( $this->mcm->environment . '_theme_modules_views_url' ) . '/' . $this->module_name . '/' . $layout;
		$theme_load_views_path = call_user_func( $this->mcm->environment . '_theme_modules_views_path' ) . $this->module_name . DS . $layout . DS;
		$theme_views_path = THEMES_PATH . $theme_load_views_path;
		
		$default_modules_views_styles_path = MODULES_VIEWS_STYLES_PATH . $this->module_name . DS . $layout . DS;
		$default_modules_views_styles_url = MODULES_VIEWS_STYLES_URL . '/' . $this->module_name . '/' . $layout;
		$default_load_views_path = MODULES_DIR_NAME . DS . $this->module_name . DS . $layout . DS;
		$default_views_path = VIEWS_PATH . $default_load_views_path;
		
		$data[ 'module_data' ] = & $module_data;
		
		$output_html = lang( 'layout_not_found' );
		
		// verificando se o tema atual possui a view
		if ( file_exists( $theme_views_path . $layout . '.php') ){
			
			$data[ 'module_data' ][ 'load_view_path' ] = $theme_load_views_path;
			$data[ 'module_data' ][ 'view_path' ] = $theme_views_path;
			
			$output_html = $this->load->view( $theme_load_views_path . $layout, $data, TRUE );
			
		}
		// verificando se a view existe no diretório de views padrão
		else if ( file_exists( $default_views_path . $layout . '.php') ){
			
			$data[ 'module_data' ][ 'load_view_path' ] = $default_load_views_path;
			$data[ 'module_data' ][ 'view_path' ] = $default_views_path;
			
			$output_html = $this->load->view( $default_load_views_path . $layout, $data, TRUE );
			
		}
		
		return $output_html;
		
	}
	
	public function get_module_params( $current_params_values = NULL ){
		
		//print_r( $current_params_values );
		
		/*
		 * -------------------------------------------------------------------------------------------------
		 */
		
		if ( ! $this->load->is_model_loaded( 'sfcm' ) ) {
			
			$this->load->model( 'common/submit_forms_common_model', 'sfcm' );
			
		}
		
		$this->load->helper(
			
			array(
				
				'html',
				
			)
			
		);
		
		/*
		 * -------------------------------------------------------------------------------------------------
		 */
		
		
		$params = array();
		$_params_spec_key = $this->module_name . '_module_config';
		
		if ( file_exists( MODULES_PATH . $this->module_name . DS . 'params.xml' ) ) {
			
			$params = get_params_spec_from_xml( MODULES_PATH . $this->module_name . DS . 'params.xml' );
			
		}
		else {
			
			$params = array(
				
				'params_spec_values' => array(),
				'params_spec' => array(
					
					$_params_spec_key => array(),
					
				),
				
			);
			
		}
		
		if ( check_var( $params[ 'params_spec_values' ] ) ) {
			
			$current_params_values = filter_params( $params[ 'params_spec_values' ], $current_params_values );
			
		}
		
		/*
		* -------------------------------------------------------------------------------------------------
		*/
		
		$directories_options = array( MEDIA_DIR_NAME => MEDIA_DIR_NAME );
		$directories_options = $directories_options + scandir_to_array( MEDIA_DIR_NAME );
		
		// carregando os layouts do tema atual
		$_options_us_layouts = dir_list_to_array( THEMES_PATH . site_theme_modules_views_path() . $this->module_name );
		// carregando os layouts do diretório de views padrão
		$_options_us_layouts = array_merge( $_options_us_layouts, dir_list_to_array( MODULES_VIEWS_PATH . $this->module_name ) );
		
		foreach ( $params[ 'params_spec' ][ $_params_spec_key ] as $key => $element ) {
			
			if ( $element[ 'name' ] == $this->module_name . '_layout' ){
				
				$spec_options = array();
				
				if ( isset( $params[ 'params_spec' ][ $_params_spec_key ][ $key ][ 'options' ] ) )
					$spec_options = $params[ 'params_spec' ][ $_params_spec_key ][ $key ][ 'options' ];
				
				$params[ 'params_spec' ][ $_params_spec_key ][ $key ][ 'options' ] = is_array( $spec_options ) ? $spec_options + $_options_us_layouts : $_options_us_layouts;
				
			}
			
		}
		
		if ( ! empty( $_options_us_layouts ) ) {
			
			/*
			* ------------------------------------
			*/
			
			$_tmp = array(
				
				'type' => 'select',
				'inline' => TRUE,
				'name' => $this->module_name . '_layout',
				'label' => $this->module_name . '_layout',
				'tip' => 'tip_' . $this->module_name . '_layout',
				'validation' => array(
					
					'rules' => 'trim|required',
					
				),
				'options' => $_options_us_layouts,
				
			);
			
			$params[ 'params_spec_values' ][ $this->module_name . '_layout' ] = 'default';
			
			$new_params[] = $_tmp;
			
			/*
			* ------------------------------------
			*/
			
			$_tmp = array(
				
				'type' => 'select',
				'inline' => TRUE,
				'name' => $this->module_name . '_direction',
				'label' => $this->module_name . '_direction',
				'tip' => 'tip_' . $this->module_name . '_direction',
				'validation' => array(
					
					'rules' => 'trim|required',
					
				),
				'options' => array(
					
					'v' => 'vertical',
					'h' => 'horizontal',
					
				),
				
			);
			
			$params[ 'params_spec_values' ][ $this->module_name . '_direction' ] = 'vertical';
			
			$new_params[] = $_tmp;
			
			/*
			* ------------------------------------
			*/
			
			array_push_pos( $params[ 'params_spec' ][ $_params_spec_key ], $new_params, 10  );
			
			if ( isset( $current_params_values[ $this->module_name . '_layout' ] ) AND $current_params_values[ $this->module_name . '_layout' ] != 'global' ) {
				
				$system_views_path = MODULES_VIEWS_PATH . $this->module_name . DS;
				$theme_views_path = THEMES_PATH . site_theme_modules_views_path() . $this->module_name . DS;
				
				if ( file_exists( $system_views_path . $current_params_values[ $this->module_name . '_layout' ] . DS . 'params.php' ) ) {
					
					include_once $system_views_path . $current_params_values[ $this->module_name . '_layout' ] . DS . 'params.php';
					
				}
				
				//echo '<pre>' . print_r( $layout_params, TRUE ) . '</pre>';
				
			}
			
		}
		else {
			
			if ( file_exists( MODULES_PATH . $this->module_name . '/params_no_layout.xml' ) ) {
				
				$params = get_params_spec_from_xml( MODULES_PATH . $this->module_name . '/params_no_layout.xml' );
				
			}
			
		}
		
		return $params;
		
	}
	
}
