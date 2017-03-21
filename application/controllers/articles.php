<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require( APPPATH . 'controllers/main.php' );

class Articles extends Main {
	
	public function __construct(){
		
		parent::__construct();
		
		if ( ! $this->load->is_model_loaded( 'articles' ) ) {
			
			$this->load->model( 'articles_mdl', 'articles' );
			
		}
		
		$this->load->language( array( 'articles' ) );
		
		set_current_component();
		
	}
	
	/******************************************************************************/
	/******************************************************************************/
	/******************************* Component index ******************************/
	
	public function index( $action = NULL, $current_menu_item_id = 0, $var1 = NULL, $var2 = NULL, $var3 = NULL, $var4 = NULL ){

		$this->component_function = __FUNCTION__;
		$this->component_function_action = $action;

		// obtendo os parâmetros globais do componente
		$component_params = $this->current_component[ 'params' ];
		
		// obtendo os parâmetros do item de menu
		if ( $this->mcm->current_menu_item ){
			
			$menu_item_params = get_params( $this->mcm->current_menu_item[ 'params' ] );
			$data[ 'params' ] = filter_params( $component_params, $menu_item_params );
			
		}
		else{
			
			$data[ 'params' ] = $component_params;
			
		}
		
		/**************************************************/
		/****************** Articles list *****************/
		
		if ( $action === 'articles_list' ){

			$this->load->helper( array( 'pagination' ) );

			// $cp = página atual
			// $ipp = itens por página

			$cp = $var3;
			$ipp = $var4 !== NULL ? $var4 : $this->mcm->filtered_system_params[ $this->mcm->environment . '_items_per_page' ];

			if ( $cp < 1 OR ! gettype( $cp ) == 'int' ) $cp = 1;
			if ( $ipp < 0 OR ! gettype( $ipp ) == 'int' ) $ipp = $this->mcm->filtered_system_params[ $this->mcm->environment . '_items_per_page' ];
			$offset = ( $cp - 1 ) * $ipp;

			$cat_id = $var1;
			$user_id = $var2;

			$url = get_url( $this->uri->ruri_string() );

			$data[ 'url' ] = $url;

			$random = FALSE;

			// -------------------------------------------------
			// Ordering ----------------------------------------

			$order_by = $data[ 'params' ][ 'articles_list_order' ];

			if ( ! ( ( $order_by_direction = $data[ 'params' ][ 'articles_list_order_mode' ] ) != FALSE ) ){

				$order_by_direction = 'ASC';

			}

			if ( $order_by === FALSE ){

				$order_by = 'id';

			}

			$data[ 'order_by' ] = $order_by;
			$data[ 'category_id' ] = $cat_id;
			$data[ 'order_by_direction' ] = $order_by_direction;

			// Ordering ----------------------------------------
			// -------------------------------------------------
			
			$search_config = array(
				
				'plugins' => 'articles_search',
				'ipp' => $ipp,
				'cp' => $cp,
				'allow_empty_terms' => ( $action === 's' ? FALSE : TRUE ),
				'plugins_params' => array(
					
					'articles_search' => array(
						
						'order_by' => $order_by,
						'order_by_direction' => $order_by_direction,
						'category_id' => $cat_id,
						'user_id' => $user_id,
						'recursive_categories' => $data[ 'params' ][ 'articles_load_mode' ],
						
					),
					
				),
				
			);
			
			if ( check_var( $this->mcm->filtered_system_params[ 'article_has_image_first' ] ) ){

				$search_config[ 'order_by' ][ 'articles_search' ] = 'image_first';

			}
			if ( check_var( $this->mcm->filtered_system_params[ 'articles_load_mode' ] ) ){

				$recursive = $this->mcm->filtered_system_params[ 'articles_load_mode' ];

				$search_config[ 'plugins_params' ][ 'articles_search' ][ 'recursive_categories' ] = $recursive === 'recursive' ? TRUE : FALSE;

			}

			$this->load->library( 'search' );
			$this->search->config( $search_config );

			$articles = $this->search->get_full_results( 'articles_search', TRUE );

			if ( $articles ) {

				foreach( $articles as & $article ){

					$article[ 'params' ] = filter_params( $data[ 'params' ], $article[ 'params' ] );

				}

			}
			else {

				$articles = array();

			}

			$data[ 'articles' ] = & $articles;

			// -------------------------------------------------
			// Pagination --------------------------------------

			// get article url params
			$gaup = array(

				'ipp' => $ipp,
				'cp' => $cp,
				'get' => $this->input->get(),
				'return' => 'template',
				'template_fields' => array(

					'ipp' => '%ipp%',
					'cp' => '%p%',

				),

			);

			$pagination_url = $this->articles->get_a_url( 'list', array(

				'menu_item_id' => current_menu_id(),
				'category_id' => $cat_id,
				'user_id' => $user_id,
				'return' => 'template',

			) );
			
			$data[ 'pagination' ] = get_pagination( $pagination_url, $cp, $ipp, $this->search->count_all_results( 'articles_search' ) );
			
			// Pagination --------------------------------------
			// -------------------------------------------------

			// obtendo o título do conteúdo da página
			// NÃO SEI PRA QUE DIABOS EU FIZ ESSA LINHA A SEGUIR
			$data[ 'params' ][ 'show_page_content_title' ] = @$data[ 'params' ][ 'show_page_content_title' ];

			if ( get_url( $this->mcm->current_menu_item[ 'link' ] ) == $url ){

				if ( @$data[ 'params' ][ 'custom_page_title' ] ){

					$this->mcm->html_data[ 'content' ][ 'title' ] = $data[ 'params' ][ 'custom_page_title' ];

				}
				else{

					$this->mcm->html_data[ 'content' ][ 'title' ] = $this->mcm->current_menu_item[ 'title' ];

				}

			}
			else if ( get_url( $this->mcm->current_menu_item[ 'link' ] ) != $url ){

				if ( $cat_id != '-1' AND $cat_id != '0' ){

					$category = $this->articles->get_category( $cat_id );
					$this->mcm->html_data[ 'content' ][ 'title' ] = $category[ 'title' ];

				}
				else{

					$this->mcm->html_data[ 'content' ][ 'title' ] = lang('articles');

				}

			}


			if ( ! $this->mcm->current_menu_item ){

				if ( $cat_id > 0 ){

					$category = $this->articles->get_category( $cat_id );
					$this->mcm->html_data[ 'content' ][ 'title' ] = $category[ 'title' ];

				}
				else{

					$this->mcm->html_data[ 'content' ][ 'title' ] = lang('articles');

				}

				$this->voutput->set_head_title( $this->mcm->html_data[ 'content' ][ 'title' ] );

			}

			set_last_url($url);

			$this->_page(

				array(

					'component_view_folder' => $this->component_view_folder,
					'function' => __FUNCTION__,
					'action' => 'list',
					'layout' => $data[ 'params' ][ 'layout_articles_list' ],
					'view' => 'list',
					'data' => $data,

				)

			);

		}

		/****************** Articles list *****************/
		/**************************************************/

		/**************************************************/
		/***************** Article detail *****************/

		else if ( $action === 'article_detail' ){

			$article_id = $var1;

			$this->articles->increment_hit( $article_id );

			// get articles params
			$gap = array(

				'art_id' => $article_id,
				'limit' => 1,

			);

			if ( $article = $this->articles->get_articles_respecting_privileges( $gap )->row_array() ){

				$url = get_url( $this->uri->ruri_string() );
				set_last_url( $url );

				// obtendo o título do conteúdo da página,
				$data[ 'params' ][ 'show_page_content_title' ] = @$data[ 'params' ][ 'show_page_content_title' ];

				if ( get_url( $this->mcm->current_menu_item[ 'link' ] ) != $url ){

					$this->mcm->html_data[ 'content' ][ 'title' ] = $article[ 'title' ];

				}
				else if ( get_url( $this->mcm->current_menu_item[ 'link' ] ) == $url ){

					if ( @$data[ 'params' ][ 'custom_page_content_title' ] ){

						$this->mcm->html_data[ 'content' ][ 'title' ] = $data[ 'params' ][ 'custom_page_content_title' ];

					}
					else{

						$this->mcm->html_data[ 'content' ][ 'title' ] = $this->mcm->current_menu_item[ 'title' ];

					}

				}
				else{

					$this->mcm->html_data[ 'content' ][ 'title' ] = lang( 'articles' );

				}



				//$this->mcm->html_data[ 'head' ][ 'title' ] = $this->mcm->html_data[ 'content' ][ 'title' ];

				$article[ 'category_url' ] = $this->articles->get_a_url( 'list', array(

					'menu_item_id' => current_menu_id(),
					'category_id' => $article[ 'category_id' ],
					
				) );
				
				$article[ 'params' ] = get_params( $article[ 'params' ] );
				
				$data[ 'params' ] = $article[ 'params' ] = filter_params( $data[ 'params' ], $article[ 'params' ] );
				
				$this->mcm->filtered_system_params = filter_params( $this->mcm->filtered_system_params, $data[ 'params' ] );

				$data[ 'article' ] = $article;
				$data[ 'article' ][ 'url' ] = $url;

				if ( ! @$data[ 'params' ][ 'custom_page_title' ] ){

					if ( @$data[ 'params' ][ 'page_title_on_detail_view' ] ){

						if ( $data[ 'params' ][ 'page_title_on_detail_view' ] == 'only_article_title' ){

							$this->voutput->set_head_title( $article[ 'title' ] );

						}
						else if ( $data[ 'params' ][ 'page_title_on_detail_view' ] == 'article_title_menu_item_title_as_prefix' ){

							$this->voutput->set_head_title( $this->mcm->current_menu_item[ 'title' ] . @$this->mcm->filtered_system_params[ 'seo_title_separator' ] . $article[ 'title' ] );

						}
						else if ( $data[ 'params' ][ 'page_title_on_detail_view' ] == 'article_title_menu_item_title_as_sufix' ){

							$this->voutput->set_head_title( $article[ 'title' ] . @$this->mcm->filtered_system_params[ 'seo_title_separator' ] . $this->mcm->current_menu_item[ 'title' ] );

						}

					}
					else{

						$this->voutput->set_head_title( $article[ 'title' ] );

					}

				}


				$head_title_org = '';
				$head_title_org .=											$this->voutput->get_head_title() ? '<meta property="og:title" content="' . $this->voutput->get_head_title() . '" >' : '';
				$head_title_org .=											$data[ 'article' ][ 'url' ] ? '<meta property="og:url" content="' . $data[ 'article' ][ 'url' ] . '" >' : '';
				$head_title_org .=											$data[ 'article' ][ 'image' ] ? '<meta property="og:image" content="' . base_url() . $data[ 'article' ][ 'image' ] . '" >' : '';

				$this->voutput->append_head_meta( 'org', $head_title_org, NULL, NULL );
				
				$this->_page(
					
					array(
						
						'component_view_folder' => $this->component_view_folder,
						'function' => __FUNCTION__,
						'action' => 'detail',
						'layout' => $data[ 'params' ][ 'layout_article_detail' ],
						'view' => 'detail',
						'data' => $data,
						
					)
					
				);
				
			}
			else{

				show_404();
			}

		}
		else{

			show_404();

		}

		/***************** Article detail *****************/
		/**************************************************/

	}

	/******************************* Component index ******************************/
	/******************************************************************************/
	/******************************************************************************/

}
