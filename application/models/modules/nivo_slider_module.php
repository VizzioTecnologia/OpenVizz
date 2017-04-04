<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Nivo_slider_module extends CI_Model{
	
	public function run( $module_data = NULL ){
		
		if ( $this->plugins->load( 'jquery' ) ){
			
			$this->lang->load( 'nivo_slider_module' );
			
			if ( ! defined( 'NIVO_SLIDER' ) ) define( 'NIVO_SLIDER', TRUE );
			
			$articles_component_params = $this->mcm->components[ 'articles' ][ 'params' ];
			$params = filter_params( $articles_component_params, $module_data[ 'params' ] );
			
			if ( $module_data[ 'params' ][ 'source_images' ] == 'articles' AND isset( $module_data[ 'params' ][ 'articles_category' ] ) ) {
				
				$search_config = array(
					
					'plugins' => 'articles_search',
					'ipp' => 10,
					'cp' => 1,
					'allow_empty_terms' => TRUE,
					'plugins_params' => array(
						
						'articles_search' => array(
							
							'order_by' => $params[ 'articles_list_order' ],
							'order_by_direction' => 'ASC',
							'category_id' => $module_data[ 'params' ][ 'articles_category' ],
							'recursive_categories' => TRUE,
							
						),
						
					),
					
				);
				
				$this->load->library( 'search' );
				$this->search->config( $search_config );
				
				$articles = $this->search->get_full_results( 'articles_search', TRUE );
				
				if ( ! $articles ) {
					
					return lang( 'no_articles' );
					
				}
				
				$data[ 'articles' ] = & $articles;
				
			}
			
			$layout = $module_data[ 'params' ][ 'layout' ];
			
			$theme_views_url = call_user_func( $this->mcm->environment . '_theme_modules_views_url' ) . '/' . 'nivo-slider' . '/' . $layout;
			$theme_load_views_path = call_user_func( $this->mcm->environment . '_theme_modules_views_path' ) . 'nivo-slider' . DS . $layout . DS;
			$theme_views_path = THEMES_PATH . $theme_load_views_path;
			
			$default_modules_views_styles_path = MODULES_VIEWS_STYLES_PATH . 'nivo-slider' . DS . $layout . DS;
			$default_modules_views_styles_url = MODULES_VIEWS_STYLES_URL . '/' . 'nivo-slider' . '/' . $layout;
			$default_load_views_path = MODULES_DIR_NAME . DS . 'nivo-slider' . DS . $layout . DS;
			$default_views_path = VIEWS_PATH . $default_load_views_path;
			
			$data[ 'module_data' ] = & $module_data;
			
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
		
		return FALSE;
		
	}
	
	public function get_module_params( $current_params_values = NULL ){
		
		$this->lang->load( 'nivo_slider_module' );
		
		$params = get_params_spec_from_xml( MODULES_PATH . 'nivo_slider/params.xml' );
		
		// carregando os layouts do tema atual
		$layouts_options = dir_list_to_array( STYLES_PATH . MODULES_DIR_NAME . DS . 'nivo-slider' . DS . 'themes' );
		
		// carregando os layouts do tema atual
		$layouts_options = dir_list_to_array( THEMES_PATH . site_theme_modules_views_path() . 'nivo-slider' );
		// carregando os layouts do diretório de views padrão
		$layouts_options = array_merge_recursive_distinct( $layouts_options, dir_list_to_array( MODULES_VIEWS_PATH . 'nivo-slider' ) );
		
		foreach ( $params[ 'params_spec' ][ 'nivo_slider_module_config' ] as $key => $element ) {
			
			if ( $element[ 'name' ] == 'layout' ){
				
				$spec_options = array();
				
				if ( isset( $params[ 'params_spec' ][ 'nivo_slider_module_config' ][ $key ][ 'options' ] ) )
					$spec_options = $params[ 'params_spec' ][ 'nivo_slider_module_config' ][ $key ][ 'options' ];
				
				$params[ 'params_spec' ][ 'nivo_slider_module_config' ][ $key ][ 'options' ] = is_array( $spec_options ) ? $spec_options + $layouts_options : $layouts_options;
				
			}
			
		}
		
		/*********** Articles type **********/
		
		if ( isset( $current_params_values[ 'source_images' ] ) AND $current_params_values[ 'source_images' ] == 'articles' ) {
			
			// loading articles main model
			if ( ! $this->load->is_model_loaded( 'articles' ) ) {
				
				$this->load->model( 'articles_mdl', 'articles' );
				
			}
			
			$articles_categories = $this->articles->get_categories_tree( array( 'array' => $this->articles->get_categories( array( 'status' => '1' ) ) ) );
			$categories_options = array(
				
				0 => lang( 'uncategorized' ),
				-1 => lang( 'all_articles' ),
				
			);
			
			if ( $articles_categories ){
				
				foreach ( $articles_categories as $category ) {
					
					$categories_options[ $category[ 'id' ] ] = $category[ 'indented_title' ];
					
				}
				
			}
			
			$new_params = array(
				
				'spacer' => array(
					
					'type' => 'spacer',
					
				),
				'articles_category' => array(
					
					'type' => 'select',
					'inline' => TRUE,
					'name' => 'articles_category',
					'label' => 'articles_category',
					'tip' => 'tip_articles_category',
					'validation' => array(
						
						'rules' => 'trim|required',
						
					),
					'options' => $categories_options,
					
				),
				'show_title_on_list_view' => array(
					
					'type' => 'select',
					'inline' => TRUE,
					'name' => 'show_title_on_list_view',
					'label' => 'show_articles_titles',
					'tip' => 'tip_show_articles_titles',
					'validation' => array(
						
						'rules' => 'trim|required',
						
					),
					'options' => array(
						
						'global' => 'global',
						'1' => 'yes',
						'0' => 'no',
						
					),
					
				),
				'show_title_as_link_on_list_view' => array(
					
					'type' => 'select',
					'inline' => TRUE,
					'name' => 'show_title_as_link_on_list_view',
					'label' => 'articles_titles_as_link',
					'tip' => 'tip_articles_titles_as_link',
					'validation' => array(
						
						'rules' => 'trim|required',
						
					),
					'options' => array(
						
						'global' => 'global',
						'1' => 'yes',
						'0' => 'no',
						
					),
					
				),
				'show_introtext_on_list_view' => array(
					
					'type' => 'select',
					'inline' => TRUE,
					'name' => 'show_introtext_on_list_view',
					'label' => 'show_articles_introtext',
					'tip' => 'tip_show_articles_introtext',
					'validation' => array(
						
						'rules' => 'trim|required',
						
					),
					'options' => array(
						
						'global' => 'global',
						'1' => 'yes',
						'0' => 'no',
						
					),
					
				),
				'show_full_text_on_list_view' => array(
					
					'type' => 'select',
					'inline' => TRUE,
					'name' => 'show_full_text_on_list_view',
					'label' => 'show_articles_full_text',
					'tip' => 'tip_show_articles_full_text',
					'validation' => array(
						
						'rules' => 'trim|required',
						
					),
					'options' => array(
						
						'global' => 'global',
						'1' => 'yes',
						'0' => 'no',
						
					),
					
				),
				'articles_content_as_link' => array(
					
					'type' => 'select',
					'inline' => TRUE,
					'name' => 'articles_content_as_link',
					'label' => 'articles_content_as_link',
					'tip' => 'tip_articles_content_as_link',
					'validation' => array(
						
						'rules' => 'trim|required',
						
					),
					'options' => array(
						
						'1' => 'yes',
						'0' => 'no',
						
					),
					
				),
				'articles_list_order' => array(
					
					'type' => 'select',
					'name' => 'articles_list_order',
					'label' => 'articles_order_by',
					'tip' => 'tip_articles_order_by',
					'validation' => array(
						
						'rules' => 'trim|required',
						
					),
					'options' => array(
						
						'global' => 'global',
						'random' => 'random',
						'id' => 'id',
						'title' => 'article_title',
						'ordering' => 'article_ordering',
						'category_title' => 'category',
						'category_ordering' => 'category_ordering',
						'created_by_id' => 'author',
						'created_date' => 'created_date',
						'modified_date' => 'modified_date',
						
					),
					
				),
				'show_readmore_link' => array(
					
					'type' => 'select',
					'name' => 'show_readmore_link',
					'label' => 'show_articles_readmore',
					'tip' => 'tip_show_articles_readmore',
					'validation' => array(
						
						'rules' => 'trim|required',
						
					),
					'options' => array(
						
						'global' => 'global',
						1 => 'yes',
						0 => 'no',
						
					),
					
				),
				'readmore_text' => array(
					
					'type' => 'text',
					'name' => 'readmore_text',
					'label' => 'readmore_text',
					'tip' => 'tip_readmore_text',
					'validation' => array(
						
						'rules' => 'trim|required',
						
					),
					'options' => array(
						
						'global' => 'global',
						1 => 'yes',
						0 => 'no',
						
					),
					
				),
				'articles_word_limit' => array(
					
					'type' => 'number',
					'name' => 'articles_word_limit',
					'label' => 'articles_word_limit',
					'tip' => 'tip_articles_word_limit',
					'min' => -1,
					'validation' => array(
						
						'rules' => 'trim|required|integer',
						
					),
					
				),
				
			);
			
			// setting up the default values
			$params[ 'params_spec_values' ][ 'articles_category' ] = '-1';
			$params[ 'params_spec_values' ][ 'show_title_on_list_view' ] = 'global';
			$params[ 'params_spec_values' ][ 'show_title_as_link_on_list_view' ] = 'global';
			$params[ 'params_spec_values' ][ 'show_introtext_on_list_view' ] = 'global';
			$params[ 'params_spec_values' ][ 'show_full_text_on_list_view' ] = 'global';
			$params[ 'params_spec_values' ][ 'articles_content_as_link' ] = '0';
			$params[ 'params_spec_values' ][ 'articles_list_order' ] = 'global';
			$params[ 'params_spec_values' ][ 'show_readmore_link' ] = 'global';
			$params[ 'params_spec_values' ][ 'readmore_text' ] = 'global';
			$params[ 'params_spec_values' ][ 'articles_word_limit' ] = '20';
			
		}
		else {
			
			$directories_options = array();
			$_directories_options = $this->mcm->dir_tree( array( 'dir' => MEDIA_PATH ) );
			
			foreach( $_directories_options as $k => $item ){
				
				$rpath = str_replace( BASE_PATH, '', $k );
				
				$directories_options[ $rpath ] = $item;
				
			}
			
			
			$new_params = array(
				
				'images_dir' => array(
					
					'type' => 'select',
					'inline' => TRUE,
					'name' => 'images_dir',
					'label' => 'images_dir',
					'tip' => 'tip_images_dir',
					'validation' => array(
						
						'rules' => 'trim|required',
						
					),
					'options' => $directories_options,
					
				),
				
				/*
				'teste[1]' => array(
					
					'type' => 'text',
					'name' => 'teste[1]',
					'label' => 'teste[1]',
					'tip' => 'tip_images_dir',
					'validation' => array(
						
						'rules' => 'trim|required',
						
					),
					
				),
				'teste[2]' => array(
					
					'type' => 'text',
					'name' => 'teste[2]',
					'label' => 'teste[2]',
					'tip' => 'tip_images_dir',
					'validation' => array(
						
						'rules' => 'trim|required',
						
					),
					
				),
				'teste[3][1]' => array(
					
					'type' => 'text',
					'name' => 'teste[3][1]',
					'label' => 'teste[3][1]',
					'tip' => 'tip_images_dir',
					'validation' => array(
						
						'rules' => 'trim|required',
						
					),
					
				),
				'teste[3][2]' => array(
					
					'type' => 'text',
					'name' => 'teste[3][2]',
					'label' => 'teste[3][2]',
					'tip' => 'tip_images_dir',
					'validation' => array(
						
						'rules' => 'trim|required',
						
					),
					
				),
				
				'avoid_prefix' => array(
					
					'type' => 'text',
					'name' => 'avoid_prefix',
					'label' => 'avoid_prefix',
					'tip' => 'tip_avoid_prefix',
					
				),
				*/
				
			);
			
			reset( $directories_options );
			$first_dir_option = key( $directories_options );
			// setting up the default values
			$params[ 'params_spec_values' ][ 'images_dir' ] = $first_dir_option;
			
			
			/*
			$params[ 'params_spec_values' ][ 'teste[1]' ] = 'teste1';
			$params[ 'params_spec_values' ][ 'teste[2]' ] = 'teste2';
			$params[ 'params_spec_values' ][ 'teste[3][1]' ] = 'teste3';
			$params[ 'params_spec_values' ][ 'teste[3][2]' ] = 'teste4';
			*/
			
			if ( isset( $current_params_values[ 'images_dir' ] ) AND file_exists( $current_params_values[ 'images_dir' ] ) ) {
				
				$dir = $current_params_values[ 'images_dir' ];
				
				$allowed_exts = array(
					
					'jpg',
					'JPG',
					'jpeg',
					'JPEG',
					'png',
					'PNG',
					'gif',
					'GIF',
					'bmp',
					'BMP',
					
				);
				
				$items = preg_grep( '~\.(' . join( '|', $allowed_exts ) . ')$~', array_diff( scandir( BASE_PATH . $dir ), array( ".", ".." ) ) );
				
				$items = ( is_array( $items ) AND count( $items ) > 0 ) ? $items : array();
				
				$images = array();
				$images_params = array();
				
				$i = 0;
				
				foreach( $items as $item ){
					
					if ( in_array( strtolower( pathinfo( $item, PATHINFO_EXTENSION ) ), $allowed_exts ) ) {
						
						$_image_id = md5( $dir . DS . pathinfo( $item, PATHINFO_BASENAME ) );
						
						$img_thumb_url = 'thumbs/' . $dir . '/' . pathinfo( $item, PATHINFO_BASENAME );
						
						if ( ! file_exists( $img_thumb_url ) ) {
							
							$img_thumb_url = $dir . '/' . pathinfo( $item, PATHINFO_BASENAME );
							
						}
						
						$images_params[] = array(
							
							'type' => 'spacer',
							'label' => sprintf( lang( 'image_%s' ), $i + 1 ),
							
						);
					
						$images_params[] = array(
							
							'type' => 'html',
							'inline' => TRUE,
							'name' => $_image_id,
							'label' => pathinfo( $item, PATHINFO_BASENAME ),
							'content' => '<img src="' . $img_thumb_url . '" width="100" />',
							
						);
						
						$images_params[] = array(
							
							'type' => 'hidden',
							'name' => 'images[' . $i . '][filename]',
							'label' => 'ns_image_filename',
							'tip' => 'tip_ns_image_filename',
							'validation' => array(
								
								'rules' => 'trim',
								
							),
							'value' => pathinfo( $item, PATHINFO_BASENAME ),
							
						);
						
						$images_params[] = array(
							
							'type' => 'select',
							'inline' => TRUE,
							'name' => 'images[' . $i . '][enabled]',
							'label' => 'ns_image_enabled',
							'tip' => 'tip_ns_image_enabled',
							'validation' => array(
								
								'rules' => 'trim',
								
							),
							'options' => array(
								
								1 => 'enabled',
								0 => 'disabled',
								
							),
							
						);
						$params[ 'params_spec_values' ][ 'images[' . $i . '][enabled]' ] = 1;
						
						$images_params[] = array(
							
							'type' => 'text',
							'inline' => TRUE,
							'name' => 'images[' . $i . '][caption_title]',
							'label' => 'ns_image_caption_title',
							'tip' => 'tip_ns_image_caption_title',
							'validation' => array(
								
								'rules' => 'trim',
								
							),
							
						);
						$images_params[] = array(
							
							'type' => 'select',
							'inline' => TRUE,
							'name' => 'images[' . $i . '][caption_title_as_link]',
							'label' => 'ns_image_caption_title_as_link',
							'tip' => 'tip_ns_image_caption_title_as_link',
							'validation' => array(
								
								'rules' => 'trim',
								
							),
							'options' => array(
								
								1 => 'yes',
								0 => 'no',
								
							),
							
						);
						$params[ 'params_spec_values' ][ 'images[' . $i . '][caption_title_as_link]' ] = 1;
						
						$images_params[] = array(
							
							'type' => 'text',
							'inline' => TRUE,
							'name' => 'images[' . $i . '][caption_content]',
							'label' => 'ns_image_caption_content',
							'tip' => 'tip_ns_image_caption_content',
							'validation' => array(
								
								'rules' => 'trim',
								
							),
							
						);
						$images_params[] = array(
							
							'type' => 'select',
							'inline' => TRUE,
							'name' => 'images[' . $i . '][caption_content_as_link]',
							'label' => 'ns_image_caption_content_as_link',
							'tip' => 'tip_ns_image_caption_content_as_link',
							'validation' => array(
								
								'rules' => 'trim',
								
							),
							'options' => array(
								
								1 => 'yes',
								0 => 'no',
								
							),
							
						);
						$params[ 'params_spec_values' ][ 'images[' . $i . '][caption_content_as_link]' ] = 1;
						
						$images_params[] = array(
							
							'type' => 'text',
							'inline' => TRUE,
							'name' => 'images[' . $i . '][url]',
							'label' => 'ns_image_url',
							'tip' => 'tip_ns_image_url',
							'validation' => array(
								
								'rules' => 'trim',
								
							),
							
						);
						$images_params[] = array(
							
							'type' => 'select',
							'inline' => TRUE,
							'name' => 'images[' . $i . '][link_target]',
							'label' => 'ns_image_link_target',
							'tip' => 'tip_ns_image_link_target',
							'validation' => array(
								
								'rules' => 'trim',
								
							),
							'options' => array(
								
								'_self' => '_self',
								'_blank' => '_blank',
								
							),
							
						);
						$images_params[] = array(
							
							'type' => 'select',
							'inline' => TRUE,
							'name' => 'images[' . $i . '][use_readmore]',
							'label' => 'show_readmore_link',
							'tip' => 'tip_show_readmore_link',
							'validation' => array(
								
								'rules' => 'trim',
								
							),
							'options' => array(
								
								1 => 'yes',
								0 => 'no',
								
							),
							
						);
						$params[ 'params_spec_values' ][ 'images[' . $i . '][use_readmore]' ] = 0;
						$images_params[] = array(
							
							'type' => 'text',
							'inline' => TRUE,
							'name' => 'images[' . $i . '][readmore_text]',
							'label' => 'readmore_text',
							'tip' => 'tip_readmore_text',
							'validation' => array(
								
								'rules' => 'trim',
								
							),
							
						);
						$params[ 'params_spec_values' ][ 'images[' . $i . '][readmore_text]' ] = lang( 'readmore' );
						
						$i++;
						
					}
					
				}
				
				if ( ! $images_params ) {
					
					$images_params[] = array(
						
						'type' => 'html',
						'name' => 'no_images_alert',
						'label' => '',
						'content' => '<p>' . lang( 'nivo_slider_no_images_on_selected_dir' ) . '</p>',
						
					);
					
				}
				
				$params[ 'params_spec' ][ 'nivo_slider_module_config_images' ] = $images_params;
				
			}
			
		}
		
		array_push_pos( $params[ 'params_spec' ][ 'nivo_slider_module_config' ], $new_params, 3  );
		
		return $params;
	}
	
}
