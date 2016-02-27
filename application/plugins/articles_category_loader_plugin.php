<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Articles_category_loader_plugin extends Plugins_mdl{

	public function run( &$data, $params = NULL ){

		log_message( 'debug', '[Plugins] Article category loader plugin initialized' );

		$regex = '/{load_article_category \s*.*?}/i';

		$content = $this->voutput->get_content();

		preg_match_all( $regex, $content, $matches );

		if ( count( $matches[ 0 ] ) ){

			if ( ! $this->load->is_model_loaded( 'articles' ) ) {

				$this->load->model( 'articles_mdl', 'articles' );

			}

			foreach ( $matches[ 0 ] as $key => $match ) {

				$category_array = str_replace( '{', '', $match );
				$category_array = str_replace( '}', '', $category_array );
				$category_array = trim( $category_array );

				$category_array = explode( ' ', $category_array );

				unset( $category_array[ 0 ] );
				
				$plugin_params = array(
					
					'menu_item_id' => $this->mcm->current_menu_item[ 'id' ],
					
					/*
					 * If you want to load more than one category (using the plugin several times),
					 * and would keep them side by side, specify here the number of categories in the same line.
					 * This configuration depends on the layout and template used.
					 */
					'inline_cols' => 1,
					'show_image' => TRUE,
					'wrapper_class' => '',
					'image_as_link' => TRUE,
					'show_title' => TRUE,
					'title_as_link' => TRUE,
					'show_readmore' => TRUE,
					'readmore_text' => lang( 'articles_category_readmore' ),
					'readmore_link' => '',
					'show_description' => TRUE,
					
					'show_articles_titles' => TRUE,
					'show_articles_images' => TRUE,
					'articles_images_as_link' => TRUE,
					'articles_images_link_mode' => 'link_to_article', // link_to_article, link_to_image
					'show_articles_readmore' => 1, // '1', '0', 'from_article' and 'force': 'force' override any readmore param
					'articles_readmore_text_source' => 'orig', // 'orig' = from article, 'custom' = override with articles_readmore_text param
					'articles_readmore_text' => lang( 'articles_readmore' ),
					'articles_content_word_limit' => 0, // 0 to no limit, -1 to disable content
					'allow_html_content' => TRUE,
					'content_as_link' => FALSE,
					'recursive_articles' => TRUE,
					'max_articles' => 4, // 0 to disable, -1 to all
					'articles_list_columns' => 0,
					'status' => 2,
					'articles_order_by' => 'created_date',
					'articles_order_by_direction' => 'desc',
					'show_category_on_no_articles' => FALSE,
					
				);
				
				foreach ( $category_array as $key => $attr ) {
					
					$attr = explode( '=', $attr );
					
					$_config = $attr[ 0 ];
					$_value = $attr[ 1 ];
					
					$plugin_params[ $attr[ 0 ] ] = $attr[ 1 ];
					
					if ( in_array( $_config , array(
						
						'show_image',
						'image_as_link',
						'show_title',
						'title_as_link',
						'show_readmore',
						'show_description',
						'show_articles_titles',
						'show_articles_images',
						'articles_images_as_link',
						'recursive_articles',
						'show_category_on_no_articles',
						'allow_html_content',
						
					) ) ) {

						$plugin_params[ $_config ] = ( bool ) $_value;

					}
					else if ( $_config === 'inline_col' ) {

						if ( is_numeric( $_value ) AND $_value > 0 ) {

							$plugin_params[ $_config ] = ( int ) $_value;

						}

					}
					else if ( $_config === 'max_articles' ) {

						if ( is_numeric( $_value ) AND ( $_value > 0 OR $_value == -1 ) ) {

							$plugin_params[ $_config ] = ( int ) $_value;

						}
						else {

							$plugin_params[ $_config ] = 0;

						}

					}
					else if ( $_config === 'articles_content_word_limit' ) {
						
						if ( is_numeric( $_value ) AND ( $_value > 0 OR $_value == -1 ) ) {
							
							$plugin_params[ $_config ] = ( int ) $_value;
							
						}
						else {
							
							$plugin_params[ $_config ] = 0;
							
						}
						
					}
					else if ( $_config === 'show_articles_readmore' ) {

						if ( ! in_array( strtolower( $_value ) , array(

							'force',
							'from_article',
							'1',

						) ) ) {

							$plugin_params[ $_config ] = FALSE;

						}

					}
					else {

						$plugin_params[ $_config ] = $_value;

					}

				}
				
				if ( $plugin_params[ 'menu_item_id' ] != $this->mcm->current_menu_item[ 'id' ] ){

					$menu_item = $this->menus->get_menu_item( $plugin_params[ 'menu_item_id' ] );

				}
				else{

					$menu_item = $this->mcm->current_menu_item;

				}

				if ( ! isset( $this->mcm->article_component ) ){

					// obtendo o componente articles
					$this->mcm->article_component = $this->mcm->components[ 'articles' ];

				}

				// obtendo os parâmetros do item de menu
				if ( $menu_item ){

					$menu_item_params = get_params( $menu_item[ 'params' ] );
					$articles_component[ 'params' ] = filter_params( $this->mcm->article_component[ 'params' ], $menu_item_params );

				}

				// getting the category
				$category = $this->articles->get_category( $plugin_params[ 'id' ] );
				$category[ 'url' ] = $this->articles->get_a_url( 'list', array(

					'menu_item_id' => $plugin_params[ 'menu_item_id' ],
					'category_id' => $category[ 'id' ],

				) );

				if ( $category ) {

					$data[ 'category' ] = & $category;

				}

				if ( $category AND ( $plugin_params[ 'max_articles' ] > 0 OR $plugin_params[ 'max_articles' ] == -1 ) ) {

					// getting the articles
					$search_config = array(

						'plugins' => 'articles_search',
						'ipp' => $plugin_params[ 'max_articles' ] != -1 ? $plugin_params[ 'max_articles' ] : NULL,
						'cp' => 1,
						'allow_empty_terms' => TRUE,
						'plugins_params' => array(
							
							'articles_search' => array(
								
								'order_by' => $plugin_params[ 'articles_order_by' ],
								'order_by_direction' => $plugin_params[ 'articles_order_by_direction' ],
								'category_id' => $plugin_params[ 'id' ],
								'filter_params' => TRUE,
								'menu_item_id' => $plugin_params[ 'menu_item_id' ],
								'recursive_categories' => $plugin_params[ 'recursive_articles' ],
								
							),
							
						),
						
					);
					
					if ( $plugin_params[ 'status' ] != 2 ){

						$search_config[ 'plugins_params' ][ 'articles_search' ][ 'status' ] = $plugin_params[ 'status' ];

					}

					$this->load->library( 'search' );
					$this->search->config( $search_config );

					$articles = $this->search->get_full_results( 'articles_search', TRUE );

				}
				else {

					$articles = NULL;

				}

				if ( ! check_var( $articles ) AND ! $plugin_params[ 'show_category_on_no_articles' ] ) {

					$content = str_replace( $match, '', $content );
					$this->voutput->set_content( $content );
					parent::load( NULL, 'content' );
					return FALSE;

				}
				else if ( ! check_var( $articles ) AND $plugin_params[ 'show_category_on_no_articles' ] ) {

					$data[ 'plugin_params' ] = $plugin_params;
					$data[ 'articles' ] = FALSE;

					$view = $this->load->view( 'plugins/articles_category_loader/default/articles_category_loader', $data, TRUE );

					$content = str_replace( $match, minify_html( $view ), $content );

				}
				else {

					$data[ 'plugin_params' ] = $plugin_params;
					$data[ 'articles' ] = $articles;

					$view = $this->load->view( 'plugins/articles_category_loader/default/articles_category_loader', $data, TRUE );

					$content = str_replace( $match, minify_html( $view ), $content );

				}

			}

			$this->voutput->set_content( $content );

			/*
			 * -------------------------------------------------------------------------------------------------
			 * Executa novamente os plugins de conteúdo
			 */

			// carrega os plugins de conteúdo
			parent::load( NULL, 'content' );

			//parent::_set_performed( 'article_loader' );

			/*
			 * -------------------------------------------------------------------------------------------------
			 */

		}

		return TRUE;

	}

}
