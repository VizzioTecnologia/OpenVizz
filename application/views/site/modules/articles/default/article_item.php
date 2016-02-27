<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	$has_image = ( check_var( $parsed_params[ 'show_image_on_list_view' ] ) AND check_var( $article[ 'image' ] ) ) ? TRUE : FALSE;
	$has_title = ( $article['params']['show_title_on_list_view'] ) ? TRUE : FALSE;
	$has_content = ( $final_text ) ? TRUE : FALSE;
	$has_readmore = ( $article[ 'params' ][ 'show_readmore_link' ] == 1 OR ( $article[ 'params' ][ 'show_readmore_link' ] == 2 AND $use_readmore  ) ) ? TRUE : FALSE;
	
	?><div class="article article-wrapper<?= $has_image ? ' with-thumb' : ''; ?>">
		
		<?php if ( $has_image ) {
			
			$thumb_params = array(
				
				'wrapper_class' => 'article-image-wrapper',
				'src' => ( ( check_var( $article[ 'params' ][ 'article_thumb_source' ] ) AND ( $article[ 'params' ][ 'article_thumb_source' ] == 'resized_picture' ) ) ? 'thumbs/' : '' ) . $article[ 'image' ],
				'href' => ! check_var( $article[ 'params' ][ 'show_image_as_link_on_list_view' ] ) ? FALSE : ( ( check_var( $article[ 'params' ][ 'list_view_image_link_mode' ] ) AND $article[ 'params' ][ 'list_view_image_link_mode' ] == 'link_to_image' ) ? $article[ 'image' ] : $article[ 'url' ] ),
				'rel' => ! check_var( $article[ 'params' ][ 'show_image_as_link_on_list_view' ] ) ? FALSE : ( ( check_var( $article[ 'params' ][ 'list_view_image_link_mode' ] ) AND $article[ 'params' ][ 'list_view_image_link_mode' ] == 'link_to_image' ) ? 'article-thumb' : FALSE ),
				'title' => $article[ 'title' ],
				
			);
			
			echo vui_el_thumb( $thumb_params );
			
		?>
		
		<?php } ?>
		
		<?php if ( $has_title ) { ?>
			
			<div class="article-title-wrapper">
				
				<h5 class="article-title">
					
					<span class="article-title-content">
						
						<?php if ( $article['params']['show_title_as_link_on_list_view'] ) { ?>
						
						<a href="<?= $article[ 'url' ]; ?>"><?= $article[ 'title' ]; ?></a>
						
						<?php } else { ?>
						
						<?= $article[ 'title' ]; ?>
						
						<?php } ?>
						
					</span>
					
				</h5>
				
			</div>
			
		<?php } ?>
		
		<?php if ( $final_text ) { ?>
			
			<div class="article-content-wrapper">
				
				<div class="article-content-intro-text-wrapper">
					
					<div class="article-content article-content-intro-text">
						
						<?= $final_text; ?>
						
					</div>
					
				</div>
				
			</div>
			
		<?php } ?>
		
		<?php if ( $has_readmore ) { ?>
			
			<div class="article-read-more-link-wrapper">
				
				<a class="article-read-more-link" href="<?= $article['url']; ?>"><?= lang( $article['params']['readmore_text']); ?></a>
				
			</div>
			
		<?php } ?>
		
		<div class="clear"></div>
		
	</div>