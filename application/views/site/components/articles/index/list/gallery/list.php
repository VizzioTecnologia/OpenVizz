<?php if ( !defined( 'BASEPATH' ) ) { exit( 'No direct script access allowed' ); } ?>

<section id="component-content" class="articles-list <?= @$params[ 'page_class' ]; ?>">

	<?php if ( $params[ 'show_page_content_title' ] ) { ?>
		<header class="component-heading">
			<h1>
				<?= $this->mcm->html_data[ 'content' ][ 'title' ]; ?>
			</h1>
		</header>
	<?php } ?>

	<div class="breadcrumb">

		<?= lang( 'breadcrumb_you_are_here' ); ?> <?= $this->articles->get_category_path( $category_id, NULL, NULL, TRUE ); ?>

	</div>

	<?php

	/* Obtendo as categorias, originalmente, o componente Artigos não carregas estas categorias,
	 * logo temos que carregá-las a partir deste layout, se assim estiver permitido nas configurações
	 * do mesmo
	 */

	// Checamos se a exibição das categorias está permitida
	if ( check_var( $params[ 'show_categories_on_list_view' ] ) ){

		// Carregamos as categorias, onde, neste caso, a raíz é a categoria atual
		$categories = $this->articles->get_categories( $category_id, FALSE );
		
		// Eliminamos as categorias sem artigos
		if ( isset( $params[ 'show_category_on_no_articles' ] ) AND ! $params[ 'show_category_on_no_articles' ] ) {

			foreach ( $categories as $key => $category ) {

				if ( ! $this->articles->category_has_articles( $category[ 'id' ], 1, TRUE ) ) {

					unset( $categories[ $category[ 'id' ] ] );

				}

			}

		}

		$max_cols = FALSE;

		if ( isset( $params[ 'max_cols_per_row' ] ) ) {

			if ( ( int ) $params[ 'max_cols_per_row' ] === 0 ) {

				if ( isset( $params[ 'articles_list_columns' ] ) AND ( int ) $params[ 'articles_list_columns' ] > 0 ) {

					$max_cols = ( int ) $params[ 'articles_list_columns' ];

				}

			}
			else {

				$max_cols = ( int ) $params[ 'max_cols_per_row' ];

			}

			$num_categories = count( $categories );
			$total_rows = round( count( $categories ) / $max_cols );
			$cols_last_row = round( count( $categories ) % $max_cols );

		}

		if ( $categories AND $max_cols ) { ?>
		<div class="categories-wrapper columns-<?= $max_cols; ?>" >

			<?php

				$_random_articles = $params[ 'articles_per_category_order_by' ] == 'random' ? 1 : 0;

				$current_col = 1;
				$current_row = 1;

				foreach ( $categories as $key => $category ) {

					$cat_inline_col = $max_cols;

					if ( isset( $params[ 'auto_adjust_number_of_cols_per_row' ] ) AND $params[ 'auto_adjust_number_of_cols_per_row' ] ) {



					}
					// Se estivermos na última linha, o $max_col é ajustado
					if ( $current_row == $total_rows AND $cols_last_row > 0 ) {

						$cat_inline_col = $cols_last_row;

					}

					echo '{load_article_category id=' . $category[ 'id' ] .
						' show_image=' . $params[ 'show_categories_images' ] .
						' show_category_on_no_articles=1' .
						' menu_item_id=' . current_menu_id() .
						' random_articles=' . $_random_articles .
						' inline_col=' . $cat_inline_col .
						' show_readmore=' . $params[ 'show_category_readmore' ] .
						( isset( $params[ 'max_articles_per_category' ] ) ? ' articles_content_word_limit=7 show_articles_readmore=force max_articles=' . $params[ 'max_articles_per_category' ] : '' ) .
						' articles_per_category_load_mode=' . $params[ 'articles_per_category_load_mode' ] .
						'}';

					if ( $current_col == $cat_inline_col ) {

						$current_col = 1;
						$current_row++;

					}
					else {

						$current_col++;

					}

				}

			?>

		</div>
		<?php } ?>

	<hr />

	<?php } ?>

	<div class="articles-list-wrapper articles-wrapper columns-<?= $params[ 'articles_list_columns' ]; ?>">

		<?php

		$column_counter = 1;
		$max_featured_articles = check_var( $params[ 'num_featured_articles' ] ) ? $params[ 'num_featured_articles' ] : 0;
		$featured_counter = 0;

		if ( $params[ 'articles_list_columns' ] ) {

		}

		?>

			<?php foreach ( $articles as $article ) { ?><?php

				?><div class="article article-wrapper <?php echo ( $featured_counter < $max_featured_articles ? 'featured featured-' . $featured_counter : '' );
			$featured_counter++; ?> columns-<?= $params[ 'articles_list_columns' ]; ?> col column-<?= $column_counter; ?> <?= (!( check_var( $article[ 'params' ][ 'show_image_on_list_view' ] ) AND check_var( $article[ 'image' ] ) ) ? 'no-image' : '' ); ?> <?= (!check_var( $article[ 'params' ][ 'show_readmore_link' ] ) ? 'no-readmore' : '' ); ?> <?= (!check_var( $article[ 'params' ][ 'show_full_text_on_list_view' ] ) ? 'no-full-text' : '' ); ?> <?= (!check_var( $article[ 'params' ][ 'show_introtext_on_list_view' ] ) ? 'no-intro-text' : '' ); ?> <?= (!check_var( $article[ 'params' ][ 'show_created_date_on_list_view' ] ) ? 'no-created-date' : '' ); ?> <?= (!check_var( $article[ 'params' ][ 'show_created_by_on_list_view' ] ) ? 'no-created-by' : '' ); ?> <?= (!check_var( $article[ 'params' ][ 'show_article_category_on_list_view' ] ) ? 'no-category-title' : '' ); ?> <?= (!check_var( $article[ 'params' ][ 'show_title_on_list_view' ] ) ? 'no-title' : '' ); ?>">

				<?php

				$created_date_time = ( check_var( $article[ 'created_date' ] ) ) ? strtotime( $article[ 'created_date' ] ) : gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] );
				$created_date_time = ov_strftime( ( ( check_var( $params[ 'created_date_time_format' ] ) ) ? lang( $params[ 'created_date_time_format' ] ) : lang( 'articles_created_datetime_format' ) ), $created_date_time );

				$modified_date_time = ( check_var( $article[ 'modified_date' ] ) ) ? strtotime( $article[ 'modified_date' ] ) : gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] );
				$modified_time = ov_strftime( '%T', $modified_date_time );

				?>

				<article class="s1 inner item">

					<?php if ( check_var( $article[ 'params' ][ 'show_image_on_list_view' ] ) AND check_var( $article[ 'image' ] ) ) { ?>

						<?php

						$thumb_params = array(
							'wrapper_class' => 'article-image-wrapper',
							'src' => $article[ 'image' ],
							'href' => !check_var( $params[ 'show_image_as_link_on_list_view' ] ) ? FALSE : ( ( check_var( $params[ 'list_view_image_link_mode' ] ) AND $params[ 'list_view_image_link_mode' ] == 'link_to_image' ) ? $article[ 'image' ] : $article[ 'url' ] ),
							'rel' => !check_var( $params[ 'show_image_as_link_on_list_view' ] ) ? FALSE : ( ( check_var( $params[ 'list_view_image_link_mode' ] ) AND $params[ 'list_view_image_link_mode' ] == 'link_to_image' ) ? 'article-thumb' : FALSE ),
							'title' => $article[ 'title' ],
						);

						echo vui_el_thumb( $thumb_params );

						?>

						<?php } ?>

					<div class="title article-title-wrapper">

	<?php if ( $article[ 'params' ][ 'show_title_on_list_view' ] ) { ?>

							<header class="s1 inner">

								<h3 class="s2 article-title <?= isset( $params[ 'page_class' ] ) ? $params[ 'page_class' ] : ''; ?>">

									<span class="s3 article-title-content">

											<?php if ( $article[ 'params' ][ 'show_title_as_link_on_list_view' ] ) { ?>

											<a href="<?= $article[ 'url' ]; ?>">

											<?= html_entity_decode( $article[ 'title' ] ); ?>

											</a>

										<?php }
										else { ?>

			<?= html_entity_decode( $article[ 'title' ] ); ?>

		<?php } ?>

									</span>

								</h3>

							</header>

					<?php } ?>

					</div>

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

	<?php if ( $article[ 'params' ][ 'show_introtext_on_list_view' ] AND $article[ 'introtext' ] ) { ?>
						<div class="content introtext article-content-wrapper">

							<div class="article-content-intro-text-wrapper">

								<div class="article-content article-content-intro-text">

		<?= html_entity_decode( $article[ 'introtext' ] ); ?>

								</div>

							</div>

						</div>

								<?php if ( ( $article[ 'params' ][ 'show_article_category_on_list_view' ] AND $article[ 'category_title' ] ) OR ( $article[ 'params' ][ 'show_created_by_on_list_view' ] ) ) { ?>
							<div class="info article-info-wrapper">

									<?php if ( $article[ 'params' ][ 'show_article_category_on_list_view' ] AND $article[ 'category_title' ] ) { ?>
									<div class="category article-category">
										<?php if ( $article[ 'params' ][ 'show_article_category_as_link_on_list_view' ] AND $article[ 'category_url' ] ) { ?>
											<a href="<?= $article[ 'category_url' ]; ?>"><?= $article[ 'category_title' ]; ?></a>
									<?php }
									else { ?>
										<?= html_entity_decode( $article[ 'category_title' ] ); ?>
				<?php } ?>
									</div>
			<?php } ?>

										<?php if ( $article[ 'params' ][ 'show_created_by_on_list_view' ] ) { ?>
									<div class="created-by article-info-created-by-wrapper">

										<div class="article-info-created-by">

				<?= $article[ 'created_by_name' ]; ?>

										</div>

									</div>

									<div class="post-created-by"></div>
							<?php } ?>

							</div>
		<?php } ?>

		<?php if ( $article[ 'params' ][ 'show_readmore_link' ] ) { ?>
							<div class="read-more article-read-more-link-wrapper">

								<div class="s1 inner">

									<div class="s2 inner">

										<a class="read-more-link article-read-more-link" href="<?= $article[ 'url' ]; ?>" title="<?= lang( $article[ 'params' ][ 'readmore_text' ] ); ?>" ><?= lang( $article[ 'params' ][ 'readmore_text' ] ); ?></a>

									</div>

								</div>

							</div>
		<?php } ?>

								<?php }
								else if ( $article[ 'params' ][ 'show_full_text_on_list_view' ] == 'auto' ) { ?>

						<div class="content fulltext article-content-wrapper">
							<div class="article-content-full-text-wrapper">
								<div class="article-content article-content-full-text">
						<?= html_entity_decode( $article[ 'fulltext' ] ); ?>
								</div>
							</div>
						</div>


	<?php }
	else if ( $article[ 'params' ][ 'show_full_text_on_list_view' ] == 'auto' OR ( $article[ 'params' ][ 'show_full_text_on_list_view' ] OR ( $article[ 'params' ][ 'show_full_text_on_list_view' ] AND ! ( $article[ 'params' ][ 'show_introtext_on_list_view' ] AND $article[ 'introtext' ] ) ) ) ) { ?>

						<div class="content fulltext article-content-wrapper">
							<div class="article-content-full-text-wrapper">
								<div class="article-content article-content-full-text">
		<?= html_entity_decode( $article[ 'fulltext' ] ); ?>
								</div>
							</div>
						</div>

			<?php } ?>

				</article>

			</div><?php

		if ( $column_counter == $params[ 'articles_list_columns' ] ) {

			$column_counter = 1;

			?><div class="row-separator"></div><?php }
		else
			$column_counter++;
	};

		?>

	</div>

		<?php if ( $pagination ) { ?>
		<div class="pagination">
	<?php echo $pagination; ?>
		</div>
<?php } ?>

	<div class="after-content-plugins-wrapper">

<?php

$this->plugins->load( NULL, 'after_content' );
echo print_r( $this->plugins->get_output( NULL, 'after_content' ), TRUE );

?>

	</div>

</section>

<?php if ( check_var( $article[ 'params' ][ 'article_list_use_js_tabs' ] ) ) { ?>

	<?php if ( $this->plugins->load( 'yetii' ) ) { ?>

		<script type="text/javascript" >

			$(document).ready(function () {

				/*************************************************/
				/**************** Criando as tabs ****************/

				makeTabs($('.articles-wrapper'), '#content-block .article-wrapper', '.article-title-wrapper');

				/**************** Criando as tabs ****************/
				/*************************************************/

			});

		</script>

	<?php } ?>

<?php } ?>
