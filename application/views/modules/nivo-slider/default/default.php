<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php if ( defined( 'NIVO_SLIDER' ) ){
	
	/* 
	* -------------------------------------------------------------------------------------------------
	* Declarando as folhas de estilos
	*/
	
	$this->voutput->append_head_stylesheet( 'nivo_slider', SITE_MODULES_VIEWS_STYLES_URL . '/nivo-slider/nivo-slider.css' );
	
	/* 
	* -------------------------------------------------------------------------------------------------
	*/
	
	/* 
	* -------------------------------------------------------------------------------------------------
	* Declarando o javascript
	*/
	
	$this->voutput->append_head_script( 'nivo_slider', JS_DIR_URL . '/modules/nivo-slider/jquery.nivo.slider.pack.js' );
	
	/* 
	* -------------------------------------------------------------------------------------------------
	*/
	
	$unique_module_hash = md5( uniqid( rand(), true ) );
	
	if ( $module_data[ 'params' ][ 'source_images' ] == 'articles' AND isset( $module_data[ 'params' ][ 'articles_category' ] ) ) {
		
		$html_images = '';
		$html_captions = '';
		
		foreach ( $articles as $key => $article ) {
			
			if ( check_var( $article[ 'image' ] ) ){
				
				$articles_component_params = $this->mcm->components[ 'articles' ][ 'params' ];
				$params = filter_params( $article[ 'params' ], $module_data[ 'params' ] );
				$params = filter_params( $articles_component_params, $params );
				
				$show_articles_titles = isset( $params[ 'show_title_on_list_view' ] ) ? $params[ 'show_title_on_list_view' ] : TRUE;
				$articles_titles_as_link = isset( $params[ 'show_title_as_link_on_list_view' ] ) ? $params[ 'show_title_as_link_on_list_view' ] : TRUE;
				$articles_content_as_link = isset( $params[ 'articles_content_as_link' ] ) ? $params[ 'articles_content_as_link' ] : TRUE;
				$show_articles_readmore = isset( $params[ 'show_readmore_link' ] ) ? $params[ 'show_readmore_link' ] : 1;
				$readmore_text = isset( $params[ 'readmore_text' ] ) ? $params[ 'readmore_text' ] : lang( 'readmore' );
				$articles_content_word_limit = isset( $params[ 'articles_word_limit' ] ) ? $params[ 'articles_word_limit' ] : 20;
				$articles_word_limit_str = isset( $params[ 'articles_word_limit_str' ] ) ? $params[ 'articles_word_limit_str' ] : '...';
				
				$id = $unique_module_hash . '_' . md5( uniqid( rand(), true ) );
				
				$article_title = '';
				
				if ( $show_articles_titles ) {
					
					if ( $articles_titles_as_link ) {
						
						$article_title = '<a href="' . $article[ 'url' ] . '">' . html_entity_decode( $article[ 'title' ] ) . '</a>';
						
					}
					else {
						
						$article_title = html_entity_decode( $article[ 'title' ] );
						
					}
					
					$article_title = '<h3>' . $article_title . '</h3>';
					
				}
				
				$use_introtext = FALSE;
				$use_fulltext = FALSE;
				$final_text = FALSE;
				$use_readmore = $show_articles_readmore;
				
				if ( $articles_content_word_limit > -1 ) {
					
					if ( $params[ 'show_introtext_on_list_view' ] AND $article[ 'introtext' ] ) {
						
						$use_introtext = TRUE;
						$use_readmore = TRUE;
						$orig_text = strip_tags( html_entity_decode( $article[ 'introtext' ] ) );
						
						if ( $articles_content_word_limit > 0 ) {
							
							$final_text = word_limiter( $orig_text, $articles_content_word_limit, $articles_word_limit_str );
							
						}
						else {
							
							$final_text = $orig_text;
							
						}
						
					}
					if ( $params[ 'show_full_text_on_list_view' ] AND $article[ 'fulltext' ] ) {
						
						$use_fulltext = TRUE;
						$orig_text = strip_tags( html_entity_decode( $article[ 'fulltext' ] ) );
						
						if ( $articles_content_word_limit > 0 ) {
							
							$final_text = word_limiter( $orig_text, $articles_content_word_limit, $articles_word_limit_str );
							
						}
						else {
							
							$final_text = $orig_text;
							
						}
						
						// Se o texto original for diferente do texto final, quer dizer que o
						// texto foi cortado pela função word_limit(), logo ativamos o readmore
						if ( $orig_text !== $final_text ) {
							
							$use_readmore = TRUE;
							
						}
						
					}
					
				}
				
				if ( $show_articles_readmore == 0 ) {
					
					$use_readmore = FALSE;
					
				}
				else if ( $show_articles_readmore == 'force' ){
					
					$use_readmore = TRUE;
					
				}
				else if ( $show_articles_readmore == 'from_article' ){
					
					if ( check_var( $params[ 'show_full_text_on_list_view' ] ) ) {
						
						$use_readmore = TRUE;
						
					}
					else {
						
						$use_readmore = FALSE;
						
					}
					
				}
				
				if ( ( $use_introtext OR $use_fulltext ) AND $final_text ) {
					
					if ( $articles_content_as_link ) {
						
						$final_text = '<a href="' . $article[ 'url' ] . '">' . $final_text . '</a>';
						
					}
					else {
						
						$final_text = $final_text;
						
					}
					$final_text = '<p>' . $final_text . '</p>';
					
				}
				
				$readmore_str = '';
				if ( $show_articles_readmore OR $show_articles_readmore == 'force' ) {
					
					if ( $use_readmore ) {
						
						$readmore_str = '<div class="read-more article-read-more-link-wrapper">
						
					<div class="s1 inner">
						
						<div class="s2 inner">
							
							<a class="read-more-link article-read-more-link" href="' . $article[ 'url' ] . '" title="' . lang( $params[ 'readmore_text' ] ) . '" >' . lang( $params[ 'readmore_text' ] ) . '</a>
							
						</div>
						
					</div>
					
				</div>';
						
					}
					
				}
				
				$use_caption = FALSE;
				
				if ( $use_readmore ) {
					
					$use_caption = TRUE;
					
				}
				if ( $final_text ) {
					
					$use_caption = TRUE;
					
				}
				if ( $show_articles_titles ) {
					
					$use_caption = TRUE;
					
				}
				
				if ( $use_caption ) {
					
					$html_captions .= '<div id="' . $id . '" class="nivo-html-caption">';
					$html_captions .= $article_title;
					$html_captions .= $final_text;
					$html_captions .= $readmore_str;
					$html_captions .= '</div>';
					
				}
				
				$html_images .= '<a href="' . $article[ 'url' ] . '"><img src="' . $article[ 'image' ] . '" alt="" ' . ( $use_caption ? 'title="#' . $id . '"' : '' ) . ' /></a>';
				
			}
			
		}
		
		$html = '<section class="module-wrapper nivo-slider-module nivo-slider-module-wrapper ' . $module_data[ 'params' ][ 'module_class' ] . '">';
		
		if ( check_var( $module_data[ 'params' ][ 'show_title' ] ) ) {
			
			$html .= '<header class="module-title">';
			$html .= '<h3>';
			$html .= $module_data[ 'title' ];
			$html .= '</h3>';
			$html .= '</header>';
			
		}
		
		$html .= '<div class="module-content">';
		$html .= '<div class="slider-wrapper">';
		$html .= '<div class="ribbon"></div>';
		$html .= '<div id="ns-' . $unique_module_hash . '" class="nivoSlider">';
		$html .= $html_images;
		$html .= '</div>';
		$html .= $html_captions;
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</section>';
		
	}
	else if ( $module_data[ 'params' ][ 'source_images' ] == 'images_dir' AND isset( $module_data[ 'params' ][ 'images_dir' ] ) ) {
		
		$imagesDir = FCPATH . $module_data[ 'params' ][ 'images_dir' ] . DS;
		//$images = glob( $imagesDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE );
		$images = $module_data[ 'params' ][ 'images' ];
		
		if ( is_array( $images ) AND count( $images ) ) {
			
			$html_images = '';
			$html_captions = '';
			
			foreach ( $images as $key => & $image ) {
				
				if ( check_var( $image[ 'enabled' ] ) ) {
					
					$id = $unique_module_hash . '_' . md5( uniqid( rand(), true ) );
					
					$title = '';
					$content = '';
					$readmore = FALSE;
					$use_caption = FALSE;
					$readmore_str = '';
					$src = BASE_URL . '/' . $module_data[ 'params' ][ 'images_dir' ] . '/' . $image[ 'filename' ];
					
					if ( check_var( $image[ 'caption_title' ] ) ) {
						
						$use_caption = TRUE;
						
						if ( check_var( $image[ 'caption_title_as_link' ] ) AND check_var( $image[ 'url' ] ) ) {
							
							$title = '<a href="' . $image[ 'url' ] . '">' . html_entity_decode( $image[ 'caption_title' ] ) . '</a>';
							
						}
						else {
							
							$title = html_entity_decode( $image[ 'caption_title' ] );
							
						}
							
						$title = '<h3>' . $title . '</h3>';
						
						
					}
					if ( check_var( $image[ 'caption_content' ] ) ) {
						
						$use_caption = TRUE;
						
						if ( check_var( $image[ 'caption_content_as_link' ] ) AND check_var( $image[ 'url' ] ) ) {
							
							$content = strip_tags( html_entity_decode( $image[ 'caption_content' ] ) );
							$content = '<a href="' . $image[ 'url' ] . '">' . $content . '</a>';
							
						}
						else {
							
							$content = html_entity_decode( $image[ 'caption_content' ] );
							
						}
						$content = '<p>' . $content . '</p>';
						
					}
					if ( check_var( $image[ 'use_readmore' ] ) AND check_var( $image[ 'url' ] ) ) {
						
						$readmore = TRUE;
						$readmore_str = '
							
							<div class="read-more article-read-more-link-wrapper">
									
								<div class="s1 inner">
									
									<div class="s2 inner">
										
										<a class="read-more-link article-read-more-link" href="' . $image[ 'url' ] . '" title="' . lang( $image[ 'readmore_text' ] ) . '" >' . lang( $image[ 'readmore_text' ] ) . '</a>
										
									</div>
									
								</div>
								
							</div>
						';
						$use_caption = TRUE;
						
					}
					
					
					if ( $use_caption ) {
						
						$html_captions .= '<div id="' . $id . '" class="nivo-html-caption">';
						$html_captions .= $title;
						$html_captions .= $content;
						$html_captions .= $readmore_str;
						$html_captions .= '</div>';
						
					}
					
					
					
					if ( check_var( $image[ 'url' ] ) ) {
						
						$html_images .= '<a href="' . $image[ 'url' ] . '"><img src="' . $src . '" alt="' . strip_tags( html_entity_decode( $title ) ) . '" ' . ( $use_caption ? 'title="#' . $id . '"' : '' ) . ' /></a>';
						
					}
					else {
						
						$html_images .= '<img src="' . $src . '" alt="' . strip_tags( html_entity_decode( $title ) ) . '" ' . ( $use_caption ? 'title="#' . $id . '"' : '' ) . ' />' . "\n";
						
					}
				
					
				}
				
			}
			
		}
		
		$html = '<section class="module-wrapper nivo-slider-module nivo-slider-module-wrapper ' . $module_data[ 'params' ][ 'module_class' ] . '">';
		
		if ( check_var( $module_data[ 'params' ][ 'show_title' ] ) ) {
			
			$html .= '<header class="module-title">';
			$html .= '<h3>';
			$html .= $module_data[ 'title' ];
			$html .= '</h3>';
			$html .= '</header>';
			
		}
		
		$html .= '<div class="module-content">';
		$html .= '<div class="slider-wrapper">';
		$html .= '<div class="ribbon"></div>';
		$html .= '<div id="ns-' . $unique_module_hash . '" class="nivoSlider">';
		$html .= $html_images;
		$html .= '</div>';
		$html .= $html_captions;
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</section>';
		
	}
	
	$data[ 'module_data' ] = $module_data;
	$data[ 'unique_module_hash' ] = $unique_module_hash;
	
	$this->voutput->append_head_script_declaration( 'nivo_slider', $this->load->view( $module_data[ 'load_view_path' ] . DS . 'header-script', $data, TRUE ), NULL, NULL );
	
	echo $html;
	
?>
	
<?php } ?>