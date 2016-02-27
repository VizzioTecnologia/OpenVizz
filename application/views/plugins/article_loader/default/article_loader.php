<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$article[ 'url' ] = get_url( $article[ 'url' ] );
$allow_html_content = $plugin_params[ 'allow_html_content' ];
$articles_word_limit_str = '...';

?>

<div class="article-loader article article-wrapper columns-<?= $plugin_params['inline_col']; ?> <?= $plugin_params[ 'wrapper_class' ]; ?>">
	
	<?php
		
		$created_date_time = ( check_var( $article[ 'created_date' ] ) ) ? strtotime( $article[ 'created_date' ] ) : gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] );
		$created_date_time = strftime( ( ( check_var( $plugin_params[ 'created_date_time_format' ] ) ) ? lang( $plugin_params[ 'created_date_time_format' ] ) : lang( 'articles_created_datetime_format' ) ), $created_date_time );
		
		$modified_date_time = ( check_var( $article[ 'modified_date' ] ) ) ? strtotime( $article[ 'modified_date' ] ) : gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] );
		$modified_time = strftime( '%T', $modified_date_time );
	?>
	
	<article class="s1 inner item">
		
		<?php if ( check_var( $plugin_params[ 'show_image' ] ) AND check_var( $article[ 'image' ] ) ) { ?>
		
		<?php
			
			$thumb_params = array(
				
				'wrapper_class' => 'article-image-wrapper',
				'src' => $article[ 'image' ],
				'href' => ! check_var( $plugin_params[ 'image_as_link' ] ) ? FALSE : ( ( check_var( $plugin_params[ 'link_mode' ] ) AND $plugin_params[ 'link_mode' ] == 'image' ) ? $article[ 'image' ] : $article[ 'url' ] ),
				'rel' => ! check_var( $plugin_params[ 'image_as_link' ] ) ? FALSE : ( ( check_var( $plugin_params[ 'link_mode' ] ) AND $plugin_params[ 'link_mode' ] == 'image' ) ? 'article-thumb' : FALSE ),
				'title' => $article[ 'title' ],
				
			);
			
			echo vui_el_thumb( $thumb_params );
			
		?>
		
		<?php } ?>
		
		<div class="title article-title-wrapper">
			
			<?php if ( $plugin_params[ 'show_title' ] ) { ?>
				
			<header class="s1 inner">
				
				<h3 class="s2 article-title">
					
					<?php if ( $plugin_params[ 'title_as_link' ] ) { ?>
					
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
		
		<?php if ( $plugin_params[ 'created_date' ] ) { ?>
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
		
		<?php if ( ( $plugin_params[ 'category_title' ] AND $article[ 'category_title' ] ) OR ( $plugin_params[ 'created_by' ] ) ) { ?>
		<div class="info article-info-wrapper">
			
			<?php if ( $article[ 'params' ][ 'show_article_category_on_list_view' ] AND $article[ 'category_title' ] ) { ?>
			<div class="category article-category">
				<?php if ( $plugin_params[ 'category_title_as_link' ] AND $article[ 'category_url' ] ) { ?>
				<a href="<?= $article[ 'category_url' ]; ?>"><?= $article[ 'category_title' ]; ?></a>
				<?php } else { ?>
				<?= html_entity_decode( $article[ 'category_title' ] ); ?>
				<?php } ?>
			</div>
			<?php } ?>
			
			<?php if ( $plugin_params[ 'created_by' ] ) { ?>
			<div class="created-by article-info-created-by-wrapper">
				
				<div class="article-info-created-by">
					
					<?= $article[ 'created_by_name' ]; ?>
					
				</div>
				
			</div>
			
			<div class="post-created-by"></div>
			<?php } ?>
			
		</div>
		<?php } ?>
		
		<?php
			
			$use_introtext = FALSE;
			$use_fulltext = FALSE;
			$use_readmore = FALSE;
			
			if ( $plugin_params[ 'content_word_limit' ] > -1 ) {
				
				if ( $article[ 'params' ][ 'show_introtext_on_list_view' ] AND $article[ 'introtext' ] ) {
					
					$use_introtext = TRUE;
					$use_readmore = TRUE;
					
					if ( $allow_html_content ) {
						
						$orig_text = html_entity_decode( $article[ 'introtext' ] );
						
					}
					else {
						
						$orig_text = strip_tags( html_entity_decode( $article[ 'introtext' ] ) );
						
					}
					
					if ( $plugin_params[ 'content_word_limit' ] > 0 ) {
						
						$final_text = word_limiter( $orig_text, $plugin_params[ 'content_word_limit' ], $articles_word_limit_str );
						
					}
					else {
						
						$final_text = $orig_text;
						
					}
					
				}
				else {
					
					$use_fulltext = TRUE;
					
					if ( $allow_html_content ) {
						
						$orig_text = html_entity_decode( $article[ 'fulltext' ] );
						
					}
					else {
						
						$orig_text = strip_tags( html_entity_decode( $article[ 'fulltext' ] ) );
						
					}
					
					if ( $plugin_params[ 'content_word_limit' ] > 0 ) {
						
						$final_text = word_limiter( $orig_text, $plugin_params[ 'content_word_limit' ], $articles_word_limit_str );
						
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
			
			if ( $plugin_params[ 'show_readmore' ] == 0 ) {
				
				$use_readmore = FALSE;
				
			}
			else if ( $plugin_params[ 'show_readmore' ] == 'force' ){
				
				$use_readmore = TRUE;
				
			}
			else if ( $plugin_params[ 'show_readmore' ] == 'from_article' ){
				
				if ( check_var( $article[ 'params' ][ 'show_full_text_on_list_view' ] ) ) {
					
					$use_readmore = TRUE;
					
				}
				else {
					
					$use_readmore = FALSE;
					
				}
				
			}
			
		?>
		
		<?php if ( $use_introtext ) { ?>
		<div class="content introtext article-content-wrapper">
			
			<div class="article-content-intro-text-wrapper">
				
				<div class="article-content article-content-intro-text">
					
					<?php if ( check_var( $params[ 'content_as_link' ] ) ) { ?>
					
					<a class="article-content" href="<?= $article[ 'url' ]; ?>">
						
						<?= $final_text; ?>
						
					</a>
					
					<?php } else { ?>
						
						<?= $final_text; ?>
						
					<?php } ?>
					
				</div>
				
			</div>
			
		</div>
		
		<!-- } else if ( $article['params']['show_full_text_on_list_view'] == 'auto' OR ( $article['params']['show_full_text_on_list_view'] OR ( $article['params']['show_full_text_on_list_view'] AND ! ( $article['params']['show_introtext_on_list_view'] AND $article['introtext'] ) ) ) ) {  -->
		
		<?php } else if ( $use_fulltext ) { ?>
		
		<div class="content fulltext article-content-wrapper">
			
			<div class="article-content-full-text-wrapper">
				
				<div class="article-content article-content-full-text">

					<?= $final_text; ?>

				</div>

			</div>

		</div>

		<?php } ?>
		
		<?php if ( ( $article[ 'params' ][ 'show_readmore_link' ] AND check_var( $plugin_params[ 'show_articles_readmore' ] ) ) OR check_var( $plugin_params[ 'show_readmore' ] ) ) { ?>
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
