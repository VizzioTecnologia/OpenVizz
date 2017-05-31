<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="article-loader article article-wrapper columns-<?= $params['inline_col']; ?> <?= $params[ 'wrapper_class' ]; ?>">
	
	<?php
		
		$created_date_time = ( check_var( $article[ 'created_date' ] ) ) ? strtotime( $article[ 'created_date' ] ) : gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] );
		$created_date_time = ov_strftime( ( ( check_var( $params[ 'created_date_time_format' ] ) ) ? lang( $params[ 'created_date_time_format' ] ) : lang( 'articles_created_datetime_format' ) ), $created_date_time );
		
		$modified_date_time = ( check_var( $article[ 'modified_date' ] ) ) ? strtotime( $article[ 'modified_date' ] ) : gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] );
		$modified_time = ov_strftime( '%T', $modified_date_time );
	?>
	
	<article class="s1 inner item">
		
		<?php if ( check_var( $article['params']['show_image_on_list_view'] ) AND check_var( $article[ 'image' ] ) ) { ?>
		
		<?php
			
			$thumb_params = array(
				
				'wrapper_class' => 'article-image-wrapper',
				'src' => $article[ 'image' ],
				'href' => ! check_var( $params[ 'image_as_link' ] ) ? FALSE : ( ( check_var( $params[ 'link_mode' ] ) AND $params[ 'link_mode' ] == 'image' ) ? $article[ 'image' ] : $article[ 'url' ] ),
				'rel' => ! check_var( $params[ 'image_as_link' ] ) ? FALSE : ( ( check_var( $params[ 'link_mode' ] ) AND $params[ 'link_mode' ] == 'image' ) ? 'article-thumb' : FALSE ),
				'title' => $article[ 'title' ],
				
			);
			
			echo vui_el_thumb( $thumb_params );
			
		?>
		
		<?php } ?>
		
		<div class="title article-title-wrapper">
			
			<?php if ( $params[ 'show_title' ] ) { ?>
				
			<header class="s1 inner">
				
				<h3 class="s2 article-title">
					
					<?php if ( $params[ 'title_as_link' ] ) { ?>
					
					<a class="s3 title-content article-title-content" href="<?= $article[ 'url' ]; ?>">
						
						<?= html_entity_decode( $article[ 'title' ] ); ?>
						
					</a>
					
					<?php } else { ?>
					
					<span class="s3 title-content article-title-content">
						
						<?= html_entity_decode( $article[ 'title' ] ); ?>
						
					</span>
					
					<?php } ?>
					
				</h3>
				
			</header>
			
			<?php } ?>
			
		</div>
		
		<?php if ( $params[ 'created_date' ] ) { ?>
		<div class="info article-info-wrapper">
			
			<div class="created-date-time article-info-created-date-wrapper">
				
				<div class="s1 inner">
					
					<div class="s2 inner article-info-created-date">
						
						<time class="datetime" datetime="<?= $article[ 'created_date' ]; ?>"><?= $created_date_time; ?></time>
						
					</div>
					
				</div>
				
			</div>
			
		</div>
		<?php } ?>
		
		<?php if ( ( $params[ 'category_title' ] AND $article[ 'category_title' ] ) OR ( $params[ 'created_by' ] ) ) { ?>
		<div class="info article-info-wrapper">
			
			<?php if ( $article[ 'params' ][ 'show_article_category_on_list_view' ] AND $article[ 'category_title' ] ) { ?>
			<div class="category article-category">
				<?php if ( $params[ 'category_title_as_link' ] AND $article[ 'category_url' ] ) { ?>
				<a href="<?= $article[ 'category_url' ]; ?>"><?= $article[ 'category_title' ]; ?></a>
				<?php } else { ?>
				<?= html_entity_decode( $article[ 'category_title' ] ); ?>
				<?php } ?>
			</div>
			<?php } ?>
			
			<?php if ( $params[ 'created_by' ] ) { ?>
			<div class="created-by article-info-created-by-wrapper">
				
				<div class="article-info-created-by">
					
					<?= $article[ 'created_by_name' ]; ?>
					
				</div>
				
			</div>
			
			<div class="post-created-by"></div>
			<?php } ?>
			
		</div>
		<?php } ?>
		
		<?php if ( $params[ 'show_content' ] ) { ?>
		<?php if ( $article['params']['show_introtext_on_list_view'] AND $article['introtext'] ) { ?>
		<div class="content introtext article-content-wrapper">
			
			<div class="article-content-intro-text-wrapper">
				
				<div class="article-content article-content-intro-text">
					
					<?= word_limiter( strip_tags( html_entity_decode( $article[ 'introtext' ] ) ), $params[ 'content_word_limit' ], '...' ); ?>
					
				</div>
				
			</div>
			
		</div>
		
		<?php } else if ( $article['params']['show_full_text_on_list_view'] == 'auto' OR ( $article['params']['show_full_text_on_list_view'] OR ( $article['params']['show_full_text_on_list_view'] AND ! ( $article['params']['show_introtext_on_list_view'] AND $article['introtext'] ) ) ) ) { ?>
		
		<div class="content fulltext article-content-wrapper">
			<div class="article-content-full-text-wrapper">
				<div class="article-content article-content-full-text">
					<?= word_limiter( strip_tags( html_entity_decode( $article[ 'fulltext' ] ) ), $params[ 'content_word_limit' ], '...' ); ?>
				</div>
			</div>
		</div>
		
		<?php } ?>
		<?php } ?>
		
		<?php if ( ( $article[ 'params' ][ 'show_readmore_link' ] AND check_var( $params[ 'show_articles_readmore' ] ) ) OR check_var( $params[ 'show_readmore' ] ) ) { ?>
		<div class="read-more article-read-more-link-wrapper">
			
			<div class="s1 inner">
				
				<div class="s2 inner">
					
					<a class="read-more-link article-read-more-link" href="<?= $article[ 'url' ]; ?>" title="<?= lang( $article[ 'params' ][ 'readmore_text' ] ); ?>" ><?= lang( $article[ 'params' ][ 'readmore_text' ] ); ?></a>
					
				</div>
				
			</div>
			
		</div>
		<?php } ?>
		
	</article>
	
</div>
