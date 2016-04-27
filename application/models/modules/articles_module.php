<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Articles_module extends CI_Model{
	
	public function run( $module_data = NULL ){
		
		
		/*
		 * -------------------------------------------------------------------------------------------------
		 * Loading models and helpers
		 */
		
		if ( ! $this->load->is_model_loaded( 'articles' ) ) {
			
			$this->load->model( 'articles_mdl', 'articles' );
			
		}
		
		$this->load->helper(
			
			array(
				
				'html',
				
			)
			
		);
		
		/*
		 * -------------------------------------------------------------------------------------------------
		 */
		
		$params = & $module_data[ 'params' ];
		$component_params = $this->mcm->components[ 'articles' ][ 'params' ];
		$data[ 'parsed_params' ] = filter_params( $component_params, $params );
		$parsed_params = & $data[ 'parsed_params' ];
		
		/*
		 * -------------------------------------------------------------------------------------------------
		 * Articles ordering
		 */
		
		// definimos a ordenação
		$order_by = check_var( $parsed_params[ 'articles_list_order' ] ) ? $parsed_params[ 'articles_list_order' ] : NULL;
		$random = NULL;
		if ( $order_by ){
			
			if ( $order_by == 'global' AND $this->mcm->components[ 'articles' ][ 'params' ][ 'articles_list_order' ] != 'random' ){
				
				$order_by = $this->mcm->components[ 'articles' ][ 'params' ][ 'articles_list_order' ];
				
			}
			else if ( $order_by == 'random' OR ( $order_by == 'global' AND $this->mcm->components[ 'articles' ][ 'params' ][ 'articles_list_order' ] == 'random' ) ){
				
				$order_by = NULL;
				$random = TRUE;
				
			}
			
		}
		
		/*
		 * -------------------------------------------------------------------------------------------------
		 */
		
		/*
		 * -------------------------------------------------------------------------------------------------
		 * Articles list readmore
		 */
		
		if ( check_var( $parsed_params[ 'use_articles_list_readmore_link' ] ) ) {
			
			if ( ! check_var( $parsed_params[ 'articles_list_readmore_link_custom_url' ] ) ){
				
				$parsed_params[ 'articles_list_readmore_link_url' ] = get_url( $this->articles->get_link_articles_list( NULL, $parsed_params[ 'root_category_id' ] ) );
				
			}
			else {
				
				$parsed_params[ 'articles_list_readmore_link_url' ] = get_url( $parsed_params[ 'articles_list_readmore_link_custom_url' ] );
				
			}
			
		}
		
		/*
		 * -------------------------------------------------------------------------------------------------
		 */
		
		if ( isset( $parsed_params[ 'word_limiter' ] ) ){
			
			if ( $parsed_params[ 'word_limiter' ] == 0 AND check_var( $this->mcm->components[ 'articles' ][ 'params' ][ 'word_limiter' ] ) ){
				
				$parsed_params[ 'word_limiter' ] = $this->mcm->components[ 'articles' ][ 'params' ][ 'word_limiter' ];
				
			}
			else if ( $order_by == 'random' OR ( $order_by == 'global' AND $this->mcm->components[ 'articles' ][ 'params' ][ 'articles_list_order' ] == 'random' ) ){
				
				$order_by = NULL;
				$random = TRUE;
				
			}
			
		}
		
		$data[ 'module' ] = $module_data;
		
		$search_config = array(
			
			'plugins' => 'articles_search',
			'ipp' => $parsed_params[ 'quantity_of_articles_to_show' ],
			'cp' => 1,
			'allow_empty_terms' => TRUE,
			'plugins_params' => array(
				
				'articles_search' => array(
					
					'order_by' => $order_by,
					'random' => $random,
					'order_by_direction' => $parsed_params[ 'articles_list_order_mode' ],
					'category_id' => $parsed_params[ 'root_category_id' ],
					'recursive_categories' => $parsed_params[ 'articles_load_mode' ],
					
				),
				
			),
			
		);
		
		if ( check_var( $parsed_params[ 'article_has_image_first' ] ) ){
			
			$search_config[ 'order_by' ][ 'articles_search' ] = 'image_first';
			
		}
		if ( check_var( $parsed_params[ 'articles_load_mode' ] ) ){
			
			$recursive = $parsed_params[ 'articles_load_mode' ];
			
			$search_config[ 'plugins_params' ][ 'articles_search' ][ 'recursive_categories' ] = $recursive === 'recursive' ? TRUE : FALSE;
			
		}
		
		$this->load->library( 'search' );
		$this->search->config( $search_config );
		
		$articles = $this->search->get_full_results( 'articles_search', TRUE );
		
		$grouped_articles = array();
		
		foreach ( $articles as $key => & $article ) {
			
			$article[ 'params' ] = filter_params( $parsed_params, $article[ 'params' ] );
			
			if ( check_var( $parsed_params[ 'group_articles_by_category' ] ) ) {
				
				$grouped_articles[ $article[ 'category_alias' ] ][ 'title' ] = $article[ 'category_title' ];
				$grouped_articles[ $article[ 'category_alias' ] ][ 'articles' ][] = $article;
				
			}
			
		}
		
		$data[ 'articles' ] = & $articles;
		$data[ 'grouped_articles' ] = & $grouped_articles;
		
		// carregando as views
		// verificando se o tema do site possui a view
		if ( file_exists( THEMES_PATH . site_theme_modules_views_path() . 'articles' . DS . $params[ 'layout' ] . DS . $params[ 'layout' ] . '.php' ) ){
			
			return $this->load->view( site_theme_modules_views_path() . 'articles' . DS . $params[ 'layout' ] . DS . $params[ 'layout' ], $data, TRUE );
			
		}
		// verificando se a view existe no diretório de views de módulos padrão
		else if ( file_exists( VIEWS_PATH . SITE_MODULES_VIEWS_PATH . 'articles' . DS . $params[ 'layout' ] . DS . $params[ 'layout' ] . '.php' ) ){
			
			return $this->load->view( SITE_MODULES_VIEWS_PATH . 'articles' . DS . $params[ 'layout' ] . DS . $params[ 'layout' ], $data, TRUE );
			
		}
		else {
			
			return lang( 'load_view_fail' ) . ':<br />' . THEMES_PATH . site_theme_modules_views_path() . 'articles' . DS . $params[ 'layout' ] . DS . $params[ 'layout' ] . '.php' . '<br />' . VIEWS_PATH . SITE_MODULES_VIEWS_PATH . 'menu' . DS . $params[ 'layout' ] . DS . $params[ 'layout' ] . '.php';
			
		}
		
		/*
		 * -------------------------------------------------------------------------------------------------
		 */
		
		
		
	}

	public function get_module_params(){
		
		$params = get_params_spec_from_xml( MODULES_PATH . 'articles/params.xml' );
		
		$this->load->model( 'admin/articles_model' );
		$categories = $this->articles_model->get_categories_tree(0,0,'list');
		
		$categories_options = array(
			
			-1 => lang( 'all_articles' ),
			0 => lang( 'uncategorized' ),
			
		);
		
		if ( $categories ){
			
			foreach ( $categories as $category ) {
				
				$categories_options[ $category[ 'id' ] ] = $category[ 'indented_title' ];
				
			}
			
		}
		
		// carregando os layouts do tema atual
		$articles_module_layouts = dir_list_to_array( THEMES_PATH . site_theme_modules_views_path() . 'articles' );
		// carregando os layouts do diretório de views padrão
		$articles_module_layouts = array_merge( $articles_module_layouts, dir_list_to_array( VIEWS_PATH . SITE_MODULES_VIEWS_PATH . 'articles' ) );

		foreach ( $params['params_spec']['articles_module_config'] as $key => $element ) {

			if ( $element['name'] == 'layout' ){

				$spec_options = array();

				if ( isset($params['params_spec']['articles_module_config'][$key]['options']) )
					$spec_options = $params['params_spec']['articles_module_config'][$key]['options'];

				$params['params_spec']['articles_module_config'][$key]['options'] = is_array( $spec_options ) ? $spec_options + $articles_module_layouts : $articles_module_layouts;

			}

			if ( $element['name'] == 'root_category_id' ){

				$spec_options = array();

				if ( isset($params['params_spec']['articles_module_config'][$key]['options']) )
					$spec_options = $params['params_spec']['articles_module_config'][$key]['options'];

				$params['params_spec']['articles_module_config'][$key]['options'] = is_array( $spec_options ) ? $spec_options + $categories_options : $categories_options;

			}

		}

		return $params;
	}

}
