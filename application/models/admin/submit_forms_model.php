<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Submit_forms_model extends CI_Model{
	
	public function get_component_url_admin(){
		
		return RELATIVE_BASE_URL . '/' . ADMIN_ALIAS . '/submit_forms';
		
	}
	
	public function menu_item_submit_form(){
		
		if ( isset( $this->mcm->system_params[ 'email_config_enabled' ] ) AND $this->mcm->system_params[ 'email_config_enabled' ] ){
			
			$this->load->model( 'common/submit_forms_common_model', 'sfcm' );
			
			$params = get_params_spec_from_xml( APPPATH . 'controllers/admin/com_submit_forms/menu_item_submit_form.xml' );
			
			// obtendo a lista de layouts
			
			// carregando os layouts do tema atual
			$layouts_options = dir_list_to_array( THEMES_PATH . site_theme_components_views_path() . get_class_name( get_class() ) . DS . 'index' . DS . 'submit_form' );
			// carregando os layouts do diretório de views padrão
			$layouts_options = array_merge( $layouts_options, dir_list_to_array( VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . get_class_name( get_class() ) . DS . 'index' . DS . 'submit_form' ) );
			
			
			$submit_forms = $this->sfcm->get_submit_forms()->result_array();
			$submit_forms_options = array();
			
			foreach ( $submit_forms as $submit_form ){
				
				$submit_forms_options[ $submit_form[ 'id' ] ] = $submit_form[ 'title' ];
				
			}
			
			$current_section = 'submit_form';
			foreach ( $params[ 'params_spec' ][ $current_section ] as $key => $element ) {
				
				if ( $element[ 'name' ] == 'submit_form_id' ){
					
					$spec_options = array();
					
					if ( isset( $params[ 'params_spec' ][ $current_section ][ $key ][ 'options' ] ) )
						$spec_options = $params[ 'params_spec' ][ $current_section ][ $key ][ 'options' ];
					
					$params[ 'params_spec' ][ $current_section ][ $key ][ 'options' ] = is_array( $spec_options ) ? $spec_options + $submit_forms_options : $submit_forms_options;
					
				}
				else if ( $element[ 'name' ] == 'ud_ds_form_layout_site' ){
					
					$spec_options = array();
					
					if ( isset( $params[ 'params_spec' ][ $current_section ][ $key ][ 'options' ] ) )
						$spec_options = $params[ 'params_spec' ][ $current_section ][ $key ][ 'options' ];
					
					$params[ 'params_spec' ][ $current_section ][ $key ][ 'options' ] = is_array( $spec_options ) ? $spec_options + $layouts_options : $layouts_options;
					
				}
				
			}
			
			//print_r($params);
			
			return $params;
			
		}
		else {
			
			msg( ('email_config_not_enabled' ), 'title' );
			msg( validation_errors( '<div class="error">', '</div>' ), 'error' );
			
			redirect( get_last_url() );
			
		}
		
	}
	
	
	
	
	
	
	
	
	
	public function site_submit_form_base_url(){
		
		return 'submit_forms/index/a/sf';
		
	}
	
	
	
	
	
	
	
	
	public function menu_item_get_link_submit_form( $menu_item_id = NULL, $params = NULL ){
		
		return $this->site_submit_form_base_url() . '/miid/' . $menu_item_id . '/sfid/' . $params[ 'submit_form_id' ];
		
	}
	
	
	
	public function get_link_submit_form( $submit_form, $env = NULL, $menu_item_id = NULL ){
		
		if ( ! isset( $env ) ) {
			
			$env = environment();
			
		}
		
		if ( $env == SITE_ALIAS ) {
			
			return $this->site_submit_form_base_url() . '/miid/' . ( int ) $menu_item_id . '/sfid/' . $submit_form[ 'id' ];
			
		}
		else if ( $env == ADMIN_ALIAS ) {
			
			return '/admin/submit_forms/sfm/a/esf/sfid/' . $submit_form[ 'id' ];
			
		}
		
		return '';
		
	}
	
	
	
	
	
	
	
	
	// [IN PROGRESS]
	public function get_ud_users_fields_params( $current_params_values = NULL ){
		
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
						
						'rules' => 'trim',
						
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
	
	
	
	
	
	
	
	
	
	
	
	public function menu_item_users_submits( $menu_item = NULL ){
		
		if ( ! $this->load->is_model_loaded( 'sfcm' ) ) {
			
			$this->load->model( 'common/submit_forms_common_model', 'sfcm' );
			
		}
		
		if ( ! $this->load->is_model_loaded( 'ud_api' ) ) {
			
			$this->load->model( 'unid_api_mdl', 'ud_api' );
			
		}
		
		$this->load->language( 'submit_forms' );
		
		$this->load->helper(
			
			array(
				
				'html',
				
			)
			
		);
		
		/*
		 * -------------------------------------------------------------------------------------------------
		 */
		
		// If we have submit forms
		if ( ( $submit_forms = $this->sfcm->get_submit_forms()->result_array() ) ){
			
			/*
			* -------------------------------------------------------------------------------------------------
			*/
			
			$params = array();
			
			if ( file_exists( APPPATH . 'controllers/admin/com_submit_forms/menu_item_users_submits.xml' ) ) {
				
				$params = get_params_spec_from_xml( APPPATH . 'controllers/admin/com_submit_forms/menu_item_users_submits.xml' );
				
			}
			
			$post = $this->input->post( NULL, TRUE ) ? $this->input->post( NULL, TRUE ) : NULL;
			
			
			$current_params_values = isset( $menu_item[ 'params' ] ) ? $menu_item[ 'params' ] : array();
			
			// merging with post data
			$current_params_values = isset( $post[ 'params' ] ) ? array_merge( $current_params_values, $post[ 'params' ] ) : $current_params_values;
			
			if ( check_var( $params[ 'params_spec_values' ] ) ) {
				
				$current_params_values = filter_params( $params[ 'params_spec_values' ], $current_params_values );
				
			}
			
			
			
			//echo '<pre>' . print_r( $params[ 'params_spec_values' ], TRUE ) . '</pre>';
			//echo '<pre>' . print_r( $current_params_values, TRUE ) . '</pre>'; exit;
			
			// obtendo a lista de layouts
			// carregando os layouts do tema atual
			$_options_us_layouts = dir_list_to_array( THEMES_PATH . site_theme_components_views_path() . get_class_name( get_class() ) . DS . 'index' . DS . 'users_submits' );
			// carregando os layouts do diretório de views padrão
			$_options_us_layouts = array_merge( $_options_us_layouts, dir_list_to_array( VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . get_class_name( get_class() ) . DS . 'index' . DS . 'users_submits' ) );
			
			// obtendo a lista de layouts
			// carregando os layouts do tema atual
			$_options_dd_layouts = dir_list_to_array( THEMES_PATH . site_theme_components_views_path() . get_class_name( get_class() ) . DS . 'index' . DS . 'ud_data_detail' );
			// carregando os layouts do diretório de views padrão
			$_options_dd_layouts = array_merge( $_options_dd_layouts, dir_list_to_array( VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . get_class_name( get_class() ) . DS . 'index' . DS . 'ud_data_detail' ) );
			
			$new_flag = FALSE;
			
			foreach ( $submit_forms as $key => $_submit_form ) {
				
				if ( ! check_var( $current_params_values[ 'submit_form_id' ] ) ) {
					
					$current_params_values[ 'submit_form_id' ] = $_submit_form[ 'id' ];
					
					$new_flag = TRUE;
					
				}
				
				$_options_sf[ $_submit_form[ 'id' ] ] = $_submit_form[ 'title' ];
				
			}
			
			$_params_spec_key = 'ud_params_section_d_list_general_params';
			
			foreach ( $params[ 'params_spec' ][ $_params_spec_key ] as $key => $element ) {
				
				if ( $element[ 'name' ] == 'submit_form_id' ){
					
					$spec_options = array();
					
					if ( isset( $params[ 'params_spec' ][ $_params_spec_key ][ $key ][ 'options' ] ) )
						$spec_options = $params[ 'params_spec' ][ $_params_spec_key ][ $key ][ 'options' ];
					
					$params[ 'params_spec' ][ $_params_spec_key ][ $key ][ 'options' ] = is_array( $spec_options ) ? $spec_options + $_options_sf : $_options_sf;
					
				}
				
			}
			
			$_params_spec_key = 'ud_params_section_d_list_params';
			
			foreach ( $params[ 'params_spec' ][ $_params_spec_key ] as $key => $element ) {
				
				if ( $element[ 'name' ] == 'ud_d_list_layout_site' ){
					
					$spec_options = array();
					
					if ( isset( $params[ 'params_spec' ][ $_params_spec_key ][ $key ][ 'options' ] ) )
						$spec_options = $params[ 'params_spec' ][ $_params_spec_key ][ $key ][ 'options' ];
					
					$params[ 'params_spec' ][ $_params_spec_key ][ $key ][ 'options' ] = is_array( $spec_options ) ? $spec_options + $_options_us_layouts : $_options_us_layouts;
					
				}
				
			}
			
			$_params_spec_key = 'ud_params_section_d_detail_params';
			
			foreach ( $params[ 'params_spec' ][ $_params_spec_key ] as $key => $element ) {
				
				if ( $element[ 'name' ] == 'ud_d_detail_layout_site' ){
					
					$spec_options = array();
					
					if ( isset( $params[ 'params_spec' ][ $_params_spec_key ][ $key ][ 'options' ] ) )
						$spec_options = $params[ 'params_spec' ][ $_params_spec_key ][ $key ][ 'options' ];
					
					$params[ 'params_spec' ][ $_params_spec_key ][ $key ][ 'options' ] = is_array( $spec_options ) ? $spec_options + $_options_dd_layouts : $_options_dd_layouts;
					
				}
				
			}
			
			/*
			* -------------------------------------------------------------------------------------------------
			*/
			
			$data_scheme = FALSE;
			
			if ( check_var( $current_params_values[ 'submit_form_id' ] ) ) {
				
				// get data scheme params
				$gdsp = array(
					
					'where_condition' => 't1.id = ' . $current_params_values[ 'submit_form_id' ],
					'limit' => 1,
					
				);
				
				$data_scheme = $this->ud_api->get_data_schemes( $gdsp )->row_array();
				
			}
			
			if ( $data_scheme ) {
				
				$this->ud_api->parse_ds( $data_scheme );
				
				$new_params = array();
				
				//------------------------------------------------------
				
				$layout = ( isset( $current_params_values[ 'ud_d_list_layout_site' ] ) AND $current_params_values[ 'ud_d_list_layout_site' ] != 'global' ) ? $current_params_values[ 'ud_d_list_layout_site' ] : 'default';
				
				$system_views_path = VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . get_class_name( get_class() ) . DS . 'index' . DS . 'users_submits' . DS;
				$theme_views_path = THEMES_PATH . site_theme_components_views_path() . get_class_name( get_class() ) . DS . 'index' . DS . 'users_submits' . DS;
				
				if ( file_exists( $system_views_path . $layout . DS . 'params.php' ) ) {
					
					include_once $system_views_path . $layout . DS . 'params.php';
					
				}
				else if ( file_exists( $theme_views_path . $layout . DS . 'params.php' ) ) {
					
					include_once $theme_views_path . $layout . DS . 'params.php';
					
				}
				
				//------------------------------------------------------
				
				array_push_pos( $params[ 'params_spec' ][ 'ud_params_section_d_list_params' ], $new_params, 1  );
				
				//------------------------------------------------------
				
				$new_params = array();
				
				$layout = ( isset( $current_params_values[ 'ud_d_detail_layout_site' ] ) AND $current_params_values[ 'ud_d_detail_layout_site' ] != 'global' ) ? $current_params_values[ 'ud_d_detail_layout_site' ] : 'default';
				
				$system_views_path = VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . get_class_name( get_class() ) . DS . 'index' . DS . 'ud_data_detail' . DS;
				$theme_views_path = THEMES_PATH . site_theme_components_views_path() . get_class_name( get_class() ) . DS . 'index' . DS . 'ud_data_detail' . DS;
				
				if ( file_exists( $system_views_path . $layout . DS . 'params.php' ) ) {
					
					include_once $system_views_path . $layout . DS . 'params.php';
					
				}
				else if ( file_exists( $theme_views_path . $layout . DS . 'params.php' ) ) {
					
					include_once $theme_views_path . $layout . DS . 'params.php';
					
				}
				
				array_push_pos( $params[ 'params_spec' ][ 'ud_params_section_d_detail_params' ], $new_params, 1  );
				
			}
			
			$after_content_params = $this->plugins->get_params_spec( NULL, 'after_content' );
			$params = array_merge_recursive( $params, $after_content_params );
			
			/*
			* -------------------------------------------------------------------------------------------------
			*/
			
		}
		else {
			
			if ( file_exists( MODULES_PATH . 'users_submits/params_no_submit_forms.xml' ) ) {
				
				$params = get_params_spec_from_xml( MODULES_PATH . 'users_submits/params_no_submit_forms.xml' );
				
			}
			
		}
		
// 		echo '<pre>' . print_r( $params, TRUE ) . '</pre>'; exit;
		return $params;
		
	}
	
	public function menu_item_get_link_users_submits( $menu_item_id = NULL, $params = NULL ){
		
		return 'submit_forms/index' . '/miid/' . $menu_item_id . '/a/us/sfid/' . $params[ 'submit_form_id' ];
		
	}
	
	//------------------------------------------------------
	// Menu item user submit detail
	
	public function menu_item_ud_data_detail( $menu_item = NULL, $menu_params_spec = NULL ){
		
// 		echo '<pre>' . print_r( $menu_params_spec, TRUE ) . '</pre>'; exit;
		
		if ( ! $this->load->is_model_loaded( 'ud_api' ) ) {
			
			$this->load->model( 'unid_api_mdl', 'ud_api' );
			
		}
		
		//------------------------------------------------------
		// Loading submit forms common model
		
		if ( ! $this->load->is_model_loaded( 'sfcm' ) ) {
			
			$this->load->model( 'common/submit_forms_common_model', 'sfcm' );
			
		}
		
		$group_str = 'menu_item_ud_data_config';
		
		//------------------------------------------------------
		
		$_tmp = array(
			
			'type' => 'text',
			'inline' => TRUE,
			'name' => 'ud_data_id',
			'label' => 'ud_data_id',
			'tip' => 'tip_ud_data_id',
			'validation' => array(
				
				'rules' => 'trim|required',
				
			),
			
		);
		
		$params[ 'params_spec_values' ][ 'ud_data_id' ] = 0;
		
		$new_params[] = $_tmp;
		
		if ( isset( $menu_item[ 'params' ][ 'ud_data_id' ] ) ) {
			
			$ud_data = $this->ud_api->get_ud_data( $menu_item[ 'params' ][ 'ud_data_id' ] );
			
			if ( check_var( $ud_data[ 'submit_form_id' ] ) ) {
				
				// get data scheme params
				$gdsp = array(
					
					'where_condition' => 't1.id = ' . $ud_data[ 'submit_form_id' ],
					'limit' => 1,
					
				);
				
				$data_scheme = $this->ud_api->get_data_schemes( $gdsp )->row_array();
				
				if ( $data_scheme ) {
					
					$this->ud_api->parse_ds( $data_scheme );
					
					//------------------------------------------------------
					
					// obtendo a lista de layouts
					// carregando os layouts do tema atual
					$_options_dd_layouts = dir_list_to_array( THEMES_PATH . site_theme_components_views_path() . get_class_name( get_class() ) . DS . 'index' . DS . 'ud_data_detail' );
					// carregando os layouts do diretório de views padrão
					$_options_dd_layouts = array_merge( $_options_dd_layouts, dir_list_to_array( VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . get_class_name( get_class() ) . DS . 'index' . DS . 'ud_data_detail' ) );
					
					$_tmp = array(
						
						'type' => 'select',
						'inline' => TRUE,
						'icon' => 'template',
						'name' => 'ud_d_detail_layout_site',
						'label' => 'ud_d_detail_layout_site',
						'tip' => 'tip_ud_d_detail_layout_site',
						'validation' => array(
							
							'rules' => 'trim|required',
							
						),
						'options' => array(
							
							'global' => 'global',
							
						) + $_options_dd_layouts,
						
					);
					
					$params[ 'params_spec_values' ][ 'ud_data_id' ] = 0;
					
					$new_params[] = $_tmp;
					
					//------------------------------------------------------
					
					//echo '<pre>' . print_r( $params, TRUE ) . '</pre>'; exit;
					
					$ud_d_detail_layout_site = ( isset( $menu_item[ 'params' ][ 'ud_d_detail_layout_site' ] ) AND $menu_item[ 'params' ][ 'ud_d_detail_layout_site' ] != 'global' ) ? $menu_item[ 'params' ][ 'ud_d_detail_layout_site' ] : 'global';
					
					if ( $ud_d_detail_layout_site != 'global' ) {
						
						$system_views_path = VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . get_class_name( get_class() ) . DS . 'index' . DS . 'ud_data_detail' . DS;
						$theme_views_path = THEMES_PATH . site_theme_components_views_path() . get_class_name( get_class() ) . DS . 'index' . DS . 'ud_data_detail' . DS;
						
						if ( file_exists( $system_views_path . $ud_d_detail_layout_site . DS . 'params.xml' ) ) {
							
							$layout_params = get_params_spec_from_xml( $system_views_path . $ud_d_detail_layout_site . DS . 'params.xml' );
							
							if ( file_exists( $system_views_path . $ud_d_detail_layout_site . DS . 'params.php' ) ) {
								
								include_once $system_views_path . $ud_d_detail_layout_site . DS . 'params.php';
								
							}
							
						}
						else if ( file_exists( $theme_views_path . $ud_d_detail_layout_site . DS . 'params.xml' ) ) {
							
							$layout_params = get_params_spec_from_xml( $theme_views_path . $ud_d_detail_layout_site . DS . 'params.xml' );
							
							if ( file_exists( $theme_views_path . $ud_d_detail_layout_site . DS . 'params.php' ) ) {
								
								include_once $theme_views_path . $ud_d_detail_layout_site . DS . 'params.php';
								
							}
							
						}
						
						//echo '<pre>' . print_r( $layout_params, TRUE ) . '</pre>';
						
					}
					
					$after_content_params = $this->plugins->get_params_spec( NULL, 'after_content' );
					$params = array_merge_recursive( $params, $after_content_params );
					
				}
				
			}
			
		}
		
		$params[ 'params_spec' ][ $group_str ] = $new_params;
		
		return $params;
		
	}
	
	//------------------------------------------------------
	// Menu item user submit detail link
	
	public function menu_item_get_link_ud_data_detail( $menu_item_id = NULL, $params = NULL ){
		
		return $this->unid->menu_item_get_link_ud_data_detail( $menu_item_id, $params );
		
	}
	
}
