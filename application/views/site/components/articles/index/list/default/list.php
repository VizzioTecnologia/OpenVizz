<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


	// Setting vars

	/*
	 * ------------------------------------
	 * Relativo a lista de artigos
	 */

	$page_class = check_var( $params[ 'page_class' ] ) ? $params[ 'page_class' ] : '';
	$show_page_content_title = check_var( $params[ 'show_page_content_title' ] );

	// Quantidade de artigos por linha. Depende de CSS
	$articles_list_columns = check_var( $params[ 'articles_list_columns' ], TRUE ) ? ( int ) $params[ 'articles_list_columns' ] : 1;

	/*
	 * Relativo a lista de artigos
	 * ------------------------------------
	 */

	/*
	 * ------------------------------------
	 * Relativo as categorias
	 */

	// Exibir as categorias?
	$show_categories_on_list_view = check_var( $params[ 'show_categories_on_list_view' ] );

	// Se TRUE, todas a subcategorias serão carregadas. NÃO CONFUNDA COM $load_articles_recursively
	$load_categories_recursively = check_var( $params[ 'load_categories_recursively' ] );

	// Define se devemos mostrar a categoria se está não possuir artigos
	$show_category_on_no_articles = check_var( $params[ 'show_category_on_no_articles' ] );

	// Define se devemos mostrar a imagem de cada categoria
	$show_categories_images = check_var( $params[ 'show_categories_images' ] );

	// Isto é o mesmo que "Número de categorias por linha", se for 0, usaremos o valor definido
	// em $params[ 'articles_list_columns' ]. Depende de CSS
	$max_cols_per_row = check_var( $params[ 'max_cols_per_row' ], TRUE ) ? ( int ) $params[ 'max_cols_per_row' ] : 0;

	if ( $max_cols_per_row === 0 ) {

		if ( $articles_list_columns > 0 ) {

			$max_cols_per_row = $articles_list_columns;

		}

	}

	// Define se devemos ajustar a quantidade de categorias por linha, se, por exemplo, em uma
	// linha,o número de categorias for menor que a quantidade máxima permitida de categorias
	// por linha. Depende de CSS
	$auto_adjust_number_of_cols_per_row = check_var( $params[ 'auto_adjust_number_of_cols_per_row' ] );

	// Usar o Leia mais?
	$show_category_readmore = check_var( $params[ 'show_category_readmore' ] );

	// Texto para o link de Leia mais de cada categoria
	$categories_readmore_text = check_var( $params[ 'categories_readmore_text' ] ) ? $params[ 'categories_readmore_text' ] : NULL;


	// Quantidade de artigos por categoria
	$max_articles_per_category = check_var( $params[ 'max_articles_per_category' ] ) ? ( int ) $params[ 'max_articles_per_category' ] : 0;

	// Se TRUE, todas os artigos das subcategorias serão carregados. NÃO CONFUNDA COM $load_categories_recursively
	$load_articles_categories_recursively = check_var( $params[ 'load_articles_categories_recursively' ] );

	// Forma de ordenação dos artigos
	$articles_per_category_order_by = check_var( $params[ 'articles_per_category_order_by' ] ) ? $params[ 'articles_per_category_order_by' ] : NULL;

	// Forma de ordenação dos artigos
	$articles_per_category_order_by_direction = check_var( $params[ 'articles_per_category_order_by_direction' ] ) ? $params[ 'articles_per_category_order_by_direction' ] : NULL;

	// Define se os artigos das categorias devem ser dispostos de forma aleatória
	$random_articles_per_category = $articles_per_category_order_by === 'random' ? TRUE : FALSE;

	// Número máximo de palavras por artigo
	$articles_categories_content_word_limit = check_var( $params[ 'articles_categories_content_word_limit' ], TRUE ) ? ( int ) $params[ 'articles_categories_content_word_limit' ] : 10;

	// Exibir as imagens de capa dos artigos?
	$show_articles_categories_images = check_var( $params[ 'show_articles_categories_images' ] ) ? $params[ 'show_articles_categories_images' ] : 1;

	// Exibir o Leia mais de cada artigo?
	$show_articles_categories_readmore = check_var( $params[ 'show_articles_categories_readmore' ] ) ? $params[ 'show_articles_categories_readmore' ] : 'force';

	// Quantidade de artigos por linha, para cada categoria. Depende de CSS
	$max_category_articles_columns = check_var( $params[ 'max_category_articles_columns' ] ) ? ( int ) $params[ 'max_category_articles_columns' ] : 1;

	/*
	 * Relativo as categorias
	 * ------------------------------------
	 */


?>

<section id="component-content" class="articles-list <?= $page_class; ?>">

	<?php if ( $show_page_content_title ) { ?>
	<header class="component-heading">
		<h1>
			<?= $this->mcm->html_data[ 'content' ][ 'title' ]; ?>
		</h1>
	</header>
	<?php } ?>
	<!--
	<div class="breadcrumb">
		
		<?= lang( 'breadcrumb_you_are_here' ); ?> <?= $this->articles->get_category_path( $category_id, NULL, NULL, TRUE ); ?>
		
	</div>
	-->
	<?php

	/* Obtendo as categorias, originalmente, o componente Artigos não carregas estas categorias,
	 * logo temos que carregá-las a partir deste layout, se assim estiver permitido nas configurações
	 * do mesmo
	 */

	// Checamos se a exibição das categorias está permitida
	if ( $show_categories_on_list_view ){

		// Carregamos as categorias, onde, neste caso, a raíz é a categoria atual
		// O segundo argumento determina se devemos carregar as categorias recursivamente ou não
		$categories = $this->articles->get_categories( $category_id, $load_categories_recursively );

		// Eliminamos as categorias sem artigos
		if ( ! $show_category_on_no_articles ) {

			foreach ( $categories as $key => $category ) {

				if ( ! $this->articles->category_has_articles( $category[ 'id' ], 1, TRUE ) ) {

					unset( $categories[ $category[ 'id' ] ] );

				}

			}

		}

		$max_cols = $max_cols_per_row;

		$num_categories = count( $categories );

		// Determinamos o número de linhas
		$total_rows = ceil( $num_categories / $max_cols );

		// Determinamos o número de colunas contidas na última linha
		$cols_last_row = $num_categories % $max_cols;

		if ( $categories AND $max_cols ) { ?>
		<div class="categories-wrapper columns-<?= $max_cols; ?>" >

			<?php

				$current_col = 1;
				$current_row = 1;

				foreach ( $categories as $key => $category ) {

					$cat_inline_col = $max_cols;

					if ( $auto_adjust_number_of_cols_per_row ) {



					}
					// Se estivermos na última linha, o $max_col é ajustado
					if ( $current_row == $total_rows AND $cols_last_row > 0 ) {

						$cat_inline_col = $cols_last_row;

					}

					echo '{load_article_category id=' . $category[ 'id' ] .
						' show_image=' . $show_categories_images .
						' show_category_on_no_articles=1' .
						' menu_item_id=' . current_menu_id() .
						' inline_col=' . $cat_inline_col .
						' show_readmore=' . $show_category_readmore .
						' readmore_text=' . $categories_readmore_text .

						' max_articles=' . $max_articles_per_category .
						( $max_articles_per_category ?
							' show_articles_readmore=' . $show_articles_categories_readmore .
							' articles_content_word_limit=' . $articles_categories_content_word_limit .
							' articles_order_by=' . $articles_per_category_order_by .
							' articles_order_by_direction=' . $articles_per_category_order_by_direction .
							' articles_list_columns=' . $max_category_articles_columns .
							' recursive_articles=' . $load_articles_categories_recursively
							: ''
						) .
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

	<?php } ?>

	<div class="articles-list-wrapper articles-wrapper columns-<?= $params['articles_list_columns']; ?>">

		<?php

			$column_counter = 1;
			$max_featured_articles = check_var( $params[ 'num_featured_articles' ] ) ? $params[ 'num_featured_articles' ] : 0;
			$featured_counter = 0;

			if ( $params[ 'articles_list_columns' ] ){

			}

		?>

		<?php foreach( $articles as $article ){

			$article_wrapper_class = 'article article-wrapper';

			if ( $featured_counter < $max_featured_articles ) {

				$article_wrapper_class .= ' featured-articles-' . $max_featured_articles . ' featured featured-' . $featured_counter;
				$featured_counter++;

			}

			$article_wrapper_class .= ' col';

			if ( $featured_counter == $max_featured_articles ) {

				$article_wrapper_class .= ' columns-' . $params[ 'articles_list_columns' ] . ' column-' . $column_counter;

			}

			if ( ! ( check_var( $article[ 'params' ][ 'show_image_on_list_view' ] ) AND check_var( $article[ 'image' ] ) ) ) {

				$article_wrapper_class .= ' no-image';

			}

			if ( ! check_var( $article[ 'params' ][ 'show_readmore_link' ] ) ) {

				$article_wrapper_class .= ' no-readmore';

			}

			if ( ! check_var( $article[ 'params' ][ 'show_full_text_on_list_view' ] ) ) {

				$article_wrapper_class .= ' no-full-text';

			}

			if ( ! check_var( $article[ 'params' ][ 'show_introtext_on_list_view' ] ) ) {

				$article_wrapper_class .= ' no-intro-text';

			}

			if ( ! check_var( $article[ 'params' ][ 'show_created_date_on_list_view' ] ) ) {

				$article_wrapper_class .= ' no-created-date';

			}

			if ( ! check_var( $article[ 'params' ][ 'show_created_by_on_list_view' ] ) ) {

				$article_wrapper_class .= ' no-created-by';

			}

			if ( ! check_var( $article[ 'params' ][ 'show_article_category_on_list_view' ] ) ) {

				$article_wrapper_class .= ' no-category-title';

			}

			if ( ! check_var( $article[ 'params' ][ 'show_title_on_list_view' ] ) ) {

				$article_wrapper_class .= ' no-title';

			}

		?><div class="<?= $article_wrapper_class; ?>">

			<?php

				$created_date_time = ( check_var( $article[ 'created_date' ] ) ) ? strtotime( $article[ 'created_date' ] ) : gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] );
				$created_date_time = strftime( ( ( check_var( $params[ 'created_date_time_format' ] ) ) ? lang( $params[ 'created_date_time_format' ] ) : lang( 'articles_created_datetime_format' ) ), $created_date_time );
				
				$modified_date_time = ( check_var( $article[ 'modified_date' ] ) ) ? strtotime( $article[ 'modified_date' ] ) : gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] );
				$modified_time = strftime( '%T', $modified_date_time );

			?>

			<article class="s1 inner item">

				<?php if ( check_var( $article['params']['show_image_on_list_view'] ) AND check_var( $article[ 'image' ] ) ) { ?>
				
				<?php
					
					$thumb_params = array(
						
						'wrapper_class' => 'article-image-wrapper',
						'src' => $article[ 'image' ],
						'href' => ! check_var( $params[ 'show_image_as_link_on_list_view' ] ) ? FALSE : ( ( check_var( $params[ 'list_view_image_link_mode' ] ) AND $params[ 'list_view_image_link_mode' ] == 'link_to_image' ) ? $article[ 'image' ] : $article[ 'url' ] ),
						'rel' => ! check_var( $params[ 'show_image_as_link_on_list_view' ] ) ? FALSE : ( ( check_var( $params[ 'list_view_image_link_mode' ] ) AND $params[ 'list_view_image_link_mode' ] == 'link_to_image' ) ? 'article-thumb' : FALSE ),
						'title' => $article[ 'title' ],
						
					);
					
					echo vui_el_thumb( $thumb_params );
					
				?>
				
				<?php } ?>

				<?php if ( $article[ 'params' ][ 'show_title_on_list_view' ] ) { ?>
				<div class="title article-title-wrapper">

					<header class="s1 inner">

						<h3 class="s2 article-title <?= isset( $params[ 'page_class' ] ) ? $params[ 'page_class' ] : ''; ?>">

							<span class="s3 article-title-content">

								<?php if ( $article[ 'params' ][ 'show_title_as_link_on_list_view' ] ) { ?>

								<a href="<?= $article[ 'url' ]; ?>">

									<?= html_entity_decode( $article[ 'title' ] ); ?>

								</a>

								<?php } else { ?>

								<?= html_entity_decode( $article[ 'title' ] ); ?>

								<?php } ?>

							</span>

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

				<?php if ( $article[ 'params' ][ 'show_introtext_on_list_view' ] AND $article[ 'introtext' ] ) { ?>
				<div class="content introtext article-content-wrapper">

					<div class="article-content-intro-text-wrapper">

						<div class="article-content article-content-intro-text">

							<?= html_entity_decode( $article[ 'introtext' ] ); ?>

						</div>

					</div>

				</div>

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

				<?php if ( $article['params']['show_readmore_link'] ) { ?>
				<div class="read-more article-read-more-link-wrapper">

					<div class="s1 inner">

						<div class="s2 inner">

							<a class="read-more-link article-read-more-link" href="<?= $article['url']; ?>" title="<?= lang( $article[ 'params' ][ 'readmore_text' ] ); ?>" ><?= lang( $article[ 'params' ][ 'readmore_text' ] ); ?></a>

						</div>

					</div>

				</div>
				<?php } ?>

				<?php } else if ( $article['params']['show_full_text_on_list_view'] == 'auto' ) { ?>

					<div class="content fulltext article-content-wrapper">
						<div class="article-content-full-text-wrapper">
							<div class="article-content article-content-full-text">
								<?= html_entity_decode( $article[ 'fulltext' ] ); ?>
							</div>
						</div>
					</div>


				<?php } else if ( $article['params']['show_full_text_on_list_view'] == 'auto' OR ( $article['params']['show_full_text_on_list_view'] OR ( $article['params']['show_full_text_on_list_view'] AND ! ( $article['params']['show_introtext_on_list_view'] AND $article['introtext'] ) ) ) ) { ?>

					<div class="content fulltext article-content-wrapper">
						<div class="article-content-full-text-wrapper">
							<div class="article-content article-content-full-text">
								<?= html_entity_decode( $article[ 'fulltext' ] ); ?>
							</div>
						</div>
					</div>

				<?php } ?>

			</article>

		</div><?php if ( $featured_counter == $max_featured_articles AND $column_counter == $params[ 'articles_list_columns' ] ){

				$column_counter = 1;

				?><div class="row-separator"></div><?php } else $column_counter++;

		}; ?>

	</div>

	<?php if ( $pagination ){ ?>
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

<?php if ( check_var( $article['params']['article_list_use_js_tabs'] ) ) { ?>

	<?php if ( $this->plugins->load( 'yetii' ) ){ ?>

	<script type="text/javascript" >

	$( document ).ready(function(){

		/*************************************************/
		/**************** Criando as tabs ****************/

		makeTabs( $( '.articles-wrapper' ), '#content-block .article-wrapper', '.article-title-wrapper' );

		/**************** Criando as tabs ****************/
		/*************************************************/

	});

	</script>

	<?php } ?>

<?php } ?>
