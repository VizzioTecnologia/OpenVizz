<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu_module extends CI_Model{
	
	public function run( $module_data = NULL ){
		
		
		
		/* 
		 * -------------------------------------------------------------------------------------------------
		 * Loading models and helpers
		 */
		
		$this->load->model(
		
			array(
				
				'common/menus_common_model',
				
			)
			
		);
		$this->load->helper(
			
			array(
				
				'html',
				
			)
			
		);
		
		/* 
		 * -------------------------------------------------------------------------------------------------
		 */
		
		
		
		/* 
		 * -------------------------------------------------------------------------------------------------
		 * Views
		 */
		
		// get menu items params
		$gmi_params = array(
			
			'menu_type_id' => $module_data[ 'params' ][ 'menu_type_id' ],
			'order_by' => 't1.ordering',
			
		);
		
		// definindo os dados a serem enviados às views
		$data[ 'params' ] = $module_data[ 'params' ];
		$data[ 'menu_items' ] = $this->menus_common_model->get_menu_items_respecting_privileges( $gmi_params )->result_array();
		
		// carregando as views
		// verificando se o tema do site possui a view
		if ( file_exists( THEMES_PATH . site_theme_modules_views_path() . 'menu' . DS . $module_data[ 'params' ][ 'layout' ] . DS . $module_data[ 'params' ][ 'layout' ] . '.php' ) ){
			
			return $this->load->view( site_theme_modules_views_path() . 'menu' . DS . $module_data[ 'params' ][ 'layout' ] . DS . $module_data[ 'params' ][ 'layout' ], $data, TRUE );
			
		}
		// verificando se a view existe no diretório de views de módulos padrão
		else if ( file_exists( VIEWS_PATH . SITE_MODULES_VIEWS_PATH . 'menu' . DS . $module_data[ 'params' ][ 'layout' ] . DS . $module_data[ 'params' ][ 'layout' ] . '.php' ) ){
			
			return $this->load->view( SITE_MODULES_VIEWS_PATH . 'menu' . DS . $module_data[ 'params' ][ 'layout' ] . DS . $module_data[ 'params' ][ 'layout' ], $data, TRUE );
			
		}
		else {
			
			return lang( 'load_view_fail' ) . ':<br />' . THEMES_PATH . site_theme_modules_views_path() . 'menu' . DS . $module_data[ 'params' ][ 'layout' ] . DS . $module_data[ 'params' ][ 'layout' ] . '.php' . '<br />' . VIEWS_PATH . SITE_MODULES_VIEWS_PATH . 'menu' . DS . $module_data[ 'params' ][ 'layout' ] . DS . $module_data[ 'params' ][ 'layout' ] . '.php';
			
		}
		
		/* 
		 * -------------------------------------------------------------------------------------------------
		 */
		
		
		
	}
	
	public function get_module_params(){
		
		$params = get_params_spec_from_xml( MODULES_PATH . 'menu/params.xml' );
		
		$this->load->model( 'common/menus_common_model' );
		$menus_types = $this->menus_common_model->get_menus_types()->result_array();
		
		$menus_types_options = array();
		
		foreach ( $menus_types as $key => $menu_type ) {
			
			$menus_types_options[ $menu_type[ 'id' ] ] = $menu_type[ 'title' ];
			
		}
		
		// carregando os layouts do tema atual
		$menus_layouts = dir_list_to_array( THEMES_PATH . site_theme_modules_views_path() . 'menu' );
		// carregando os layouts do diretório de views padrão
		$menus_layouts = array_merge( $menus_layouts, dir_list_to_array( VIEWS_PATH . SITE_MODULES_VIEWS_PATH . 'menu' ) );
		
		foreach ( $params['params_spec']['menu_module_config'] as $key => $element ) {
			
			if ( $element['name'] == 'layout' ){
				
				$spec_options = array();
				
				if ( isset($params['params_spec']['menu_module_config'][$key]['options']) )
					$spec_options = $params['params_spec']['menu_module_config'][$key]['options'];
				
				$params['params_spec']['menu_module_config'][$key]['options'] = is_array( $spec_options ) ? $spec_options + $menus_layouts : $menus_layouts;
				
			}
			
			if ( $element['name'] == 'menu_type_id' ){
				
				$spec_options = array();
				
				if ( isset($params['params_spec']['menu_module_config'][$key]['options']) )
					$spec_options = $params['params_spec']['menu_module_config'][$key]['options'];
				
				$params['params_spec']['menu_module_config'][$key]['options'] = is_array( $spec_options ) ? $spec_options + $menus_types_options : $menus_types_options;
				
			}
			
		}
		
		return $params;
	}
	
}
