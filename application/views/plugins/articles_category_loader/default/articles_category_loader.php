<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	$category[ 'url' ] = get_url( $category[ 'url' ] );

	// Setting vars

	$wrapper_class = $plugin_params[ 'wrapper_class' ];
	$inline_col = $plugin_params[ 'inline_col' ];
	$show_category_title = $plugin_params[ 'show_title' ];
	$category_title = $category[ 'title' ];
	$category_title_as_link = $plugin_params[ 'title_as_link' ];
	$show_category_image = $plugin_params[ 'show_image' ];
	$category_image = $category[ 'image' ];
	$category_image_as_link = $plugin_params[ 'image_as_link' ];

	$allow_html_content = $plugin_params[ 'allow_html_content' ];
	$show_articles_images = $plugin_params[ 'show_articles_images' ];
	$show_articles_titles = $plugin_params[ 'show_articles_titles' ];
	$show_articles_readmore = $plugin_params[ 'show_articles_readmore' ];
	$articles_per_row = $plugin_params[ 'articles_list_columns' ];
	$articles_content_word_limit = $plugin_params[ 'articles_content_word_limit' ];
	$articles_word_limit_str = '...';

?>

<div class="articles-category-loader columns-<?= $inline_col; ?> <?= $wrapper_class; ?>">

	<div class="category" >

		<?php if ( $show_category_image AND $category_image ) { ?>

		<?php

			$thumb_params = array(

				'wrapper_class' => 'article-image-wrapper',
				'src' => $category[ 'image' ],
				'href' => $category_image_as_link ? $category[ 'url' ] : FALSE,
				'title' => $category[ 'title' ],

			);

			echo vui_el_thumb( $thumb_params );

		?>

		<?php } ?>

		<?php if ( $show_category_title ) { ?>
		<div class="title">

			<header class="s1 inner">

				<h3 class="s2">

					<?php if ( $category_title_as_link ) { ?>

					<a class="s3 link" href="<?= $category[ 'url' ]; ?>">

						<?= html_entity_decode( $category_title ); ?>

					</a>

					<?php } else { ?>

					<span class="s3">

						<?= html_entity_decode( $category_title ); ?>

					</span>

					<?php } ?>

				</h3>

			</header>

		</div>
		<?php } ?>

	</div>

	<?php if ( $articles ) { ?>

	<div class="articles articles-wrapper columns-<?= $articles_per_row; ?>">

		<?php

			$column_counter = 1;

		?>

		<?php foreach( $articles as $article ){ ?><?php

			$article[ 'url' ] = $article[ 'url' ];

			?><div class="article article-wrapper columns-<?= $articles_per_row; ?> col column-<?= $column_counter; ?> <?= ( ! ( $show_articles_images AND check_var( $article[ 'image' ] ) ) ? 'no-image' : '' ); ?> <?= ( ! ( check_var( $article[ 'params' ][ 'show_readmore_link' ] ) AND check_var( $plugin_params[ 'show_articles_readmore_links' ] ) ) ? 'no-readmore' : '' ); ?> <?= ( ! check_var( $article['params']['show_created_date_on_list_view'] ) ? 'no-created-date' : '' ); ?> <?= ( ! check_var( $article['params']['show_created_by_on_list_view'] ) ? 'no-created-by' : '' ); ?> <?= ( ! check_var( $article['params']['show_article_category_on_list_view'] ) ? 'no-category-title' : '' ); ?> <?= ( ! check_var( $article['params']['show_title_on_list_view'] ) ? 'no-title' : '' ); ?>">

			<?php

				$created_date_time = ( check_var( $article[ 'created_date' ] ) ) ? strtotime( $article[ 'created_date' ] ) : gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] );
				$created_date_time = ov_strftime( ( ( check_var( $plugin_params[ 'created_date_time_format' ] ) ) ? lang( $plugin_params[ 'created_date_time_format' ] ) : lang( 'articles_created_datetime_format' ) ), $created_date_time );

				$modified_date_time = ( check_var( $article[ 'modified_date' ] ) ) ? strtotime( $article[ 'modified_date' ] ) : gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] );
				$modified_time = ov_strftime( '%T', $modified_date_time );
			?>

			<article class="s1 inner item">

				<?php if ( $show_articles_images AND check_var( $article[ 'image' ] ) ) { ?>

				<?php

					$thumb_params = array(

						'wrapper_class' => 'article-image-wrapper',
						'src' => $article[ 'image' ],
						'href' => ! check_var( $plugin_params[ 'articles_images_as_link' ] ) ? FALSE : ( ( check_var( $plugin_params[ 'articles_images_link_mode' ] ) AND $plugin_params[ 'articles_images_link_mode' ] == 'link_to_image' ) ? $article[ 'image' ] : $article[ 'url' ] ),
						'rel' => ! check_var( $plugin_params[ 'articles_images_as_link' ] ) ? FALSE : ( ( check_var( $plugin_params[ 'articles_images_link_mode' ] ) AND $plugin_params[ 'articles_images_link_mode' ] == 'link_to_image' ) ? 'article-thumb' : FALSE ),
						'title' => $article[ 'title' ],

					);

					echo vui_el_thumb( $thumb_params );

				?>

				<?php } ?>

				<?php if ( $show_articles_titles ) { ?>
				<div class="title article-title-wrapper">

					<header class="s1 inner">

						<h3 class="s2 article-title <?= isset( $plugin_params[ 'page_class' ] ) ? $plugin_params[ 'page_class' ] : ''; ?>">

							<?php if ( $article[ 'params' ][ 'show_title_as_link_on_list_view' ] ) { ?>

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

				</div>
				<?php } ?>

				<?php if ( $article[ 'params' ][ 'show_created_date_on_list_view' ] ) { ?>
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

				<?php if ( ( $article['params']['show_article_category_on_list_view'] AND $article['category_title'] ) OR ( $article['params']['show_created_by_on_list_view'] ) ) { ?>
				<div class="info article-info-wrapper">

					<?php if ( $article['params']['show_article_category_on_list_view'] AND $article['category_title'] ) { ?>
					<div class="category article-category">
						
						<?php if ( $article['params']['show_article_category_as_link_on_list_view'] AND $article['category_url'] ) { ?>
						<a href="<?= $article['category_url']; ?>"><?= $article['category_title']; ?></a>
						<?php } else { ?>
						<?= html_entity_decode( $article[ 'category_title' ] ); ?>
						<?php } ?>
					</div>
					<?php } ?>

					<?php if ( $article['params']['show_created_by_on_list_view'] ) { ?>
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
					
					if ( $articles_content_word_limit > -1 ) {
						
						if ( $article[ 'params' ][ 'show_introtext_on_list_view' ] AND $article[ 'introtext' ] ) {
							
							$use_introtext = TRUE;
							$use_readmore = TRUE;
							
							if ( $allow_html_content ) {
								
								$orig_text = html_entity_decode( $article[ 'introtext' ] );
								
							}
							else {
								
								$orig_text = strip_tags( html_entity_decode( $article[ 'introtext' ] ) );
								
							}
							
							if ( $articles_content_word_limit > 0 ) {
								
								$final_text = word_limiter( $orig_text, $articles_content_word_limit, $articles_word_limit_str );
								
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
				
				<!-- if ( ( $article[ 'params' ][ 'show_readmore_link' ] AND check_var( $plugin_params[ 'show_articles_readmore' ] ) ) OR check_var( $plugin_params[ 'force_articles_readmore' ] ) ) {  -->
				<?php if ( $use_readmore ) { ?>
				<div class="read-more article-read-more-link-wrapper">

					<div class="s1 inner">

						<div class="s2 inner">

							<a class="read-more-link article-read-more-link" href="<?= $article[ 'url' ]; ?>" title="<?= lang( $article[ 'params' ][ 'readmore_text' ] ); ?>" ><?= lang( $article[ 'params' ][ 'readmore_text' ] ); ?></a>

						</div>

					</div>

				</div>
				<?php } ?>

			</article>

		</div><?php if ( $column_counter == $plugin_params['articles_list_columns'] ){

				$column_counter = 1;

				?><div class="row-separator"></div><?php } else $column_counter++;

		}; ?>

	</div>

	<?php } ?>

	<?php if ( check_var( $plugin_params[ 'show_readmore' ] ) ) { ?>
	<div class="read-more category-read-more-link-wrapper">

		<div class="s1 inner">

			<div class="s2 inner">

				<a class="read-more-link category-read-more-link" href="<?= check_var( $plugin_params[ 'readmore_link' ] ) ?  get_url( $plugin_params[ 'readmore_link' ] ) : $category[ 'url' ]; ?>" <?= element_title( $plugin_params[ 'readmore_text' ] ); ?> ><?= $plugin_params[ 'readmore_text' ]; ?></a>

			</div>

		</div>

	</div>
	<?php } ?>

</div>
