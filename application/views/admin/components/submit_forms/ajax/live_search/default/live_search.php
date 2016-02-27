
	<?php if ( $results ){ ?>

	<ul class="search-results live-search-results">

			<li class="plugin-wrapper">

				<span class="plugin-name">

					<?= vui_el_button( array( 'text' => lang( $plugin_name . '_search_result_title' ), 'icon' => $plugin_name, ) ); ?>

				</span>

				<ul class="plugin-search-results">

					<?php foreach ( $results as $key_2 => $result ) { ?>

						<?php if ( isset( $result[ 'id' ] ) AND $result[ 'id' ] ){ ?>

						<li class="search-result-wrapper"><a href="<?= get_url( $this->articles->{ 'get_' . ( $plugin_name === 'articles_search' ? 'a' : 'c' ) . '_url' }( 'edit', $result[ 'id' ] ) ); ?>" class="search-result" data-resultid="<?= $result[ 'id' ]; ?>" >

							<span class="s1" >

								<?php if ( $result[ 'image' ] ){ ?>

								<?php

									$thumb_params = array(

										'wrapper_class' => '',
										'wrappers_el_type' => 'span',
										'src' => $result[ 'image' ],
										'title' => $result[ 'title' ],

									);

									echo vui_el_thumb( $thumb_params );

								?>

								<?php } ?>

								<?php if ( $result[ 'title' ] ){ ?>

								<span class="title search-result-title">

									<?= ( isset( $result[ 'highlight_title' ] ) ? $result[ 'highlight_title' ] : $result[ 'title' ] ); ?>

								</span>

								<?php } ?>

								<?php if ( $result[ 'content' ] ){ ?>

								<span class="content search-result-content">

									<?= ( isset( $result[ 'highlight_content' ] ) ? $result[ 'highlight_content' ] : $result[ 'content' ] ); ?>

								</span>

								<?php } ?>

							</span>

						</a></li>

						<?php } ?>

					<?php } ?>

				</ul>

			</li>

	</ul>

	<script type="text/javascript">

		if ( typeof window.onArticleChooseFunction == 'function' ) {

			$( '.result-list-live-search .result-item' ).bind( 'click', function( event ) {

				event.preventDefault();

				window.selectedArticle = {

					id: $( this ).data( 'resultid' ),
					thumb:  $( this ).find( '.thumb-wrapper-content img' ).attr( 'src' ),
					title: $( this ).find( '.result-title-content' ).text()

				};

				window.onArticleChooseFunction();

			});

		}

	</script>

	<?php } else { ?>

	<div class="live-search-no-results">

		<?= vui_el_button( array( 'text' => lang( 'live_search_no_results' ), 'icon' => 'error', ) ); ?>

	</div>

	<?php } ?>
