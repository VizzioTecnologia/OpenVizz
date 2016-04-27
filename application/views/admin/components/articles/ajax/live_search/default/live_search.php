<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
	
?>

<?php if ( $results ){ ?>

<ul class="search-results live-search-results">
	
	<?php if ( $this->component_function_action == 's' ) { ?>
		
		<?php foreach ( $results as $key_1 => $plugin_results ) {
			
			$plugin_name = $key_1;
			
		?>
			
			<li class="plugin-wrapper">
				
				<span class="plugin-name">
					
					<?= vui_el_button( array( 'text' => lang( $plugin_name . '_search_result_title' ), 'icon' => $plugin_name, ) ); ?>
					
				</span>
				
				<ul class="plugin-search-results">
					
					<?php foreach ( $plugin_results as $key_2 => $result ) { ?>
					
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
			
		<?php } ?>
		
	<?php } else if ( $this->component_function_action == 'as' OR $this->component_function_action == 'cs' ) { ?>
		
		<?php if ( $this->component_function_action == 'cs' ) { ?>
			
			<li class="search-result-wrapper">
				
				<a href="#" class="search-result" data-resultid="0" >
					
					<span class="s1" >
						
						<span class="title search-result-title">
							
							<?= lang( 'articles_without_category' ); ?>
							
						</span>
						
					</span>
					
				</a>
				
			</li>
			
			<li class="search-result-wrapper">
				
				<a href="#" class="search-result selected" data-resultid="-1" >
					
					<span class="s1" >
						
						<span class="title search-result-title">
							
							<?= lang( 'all_articles' ); ?>
							
						</span>
						
					</span>
					
				</a>
				
			</li>
			
		<?php } ?>
		
		<?php foreach ( $results as $key_2 => $result ) { ?>
			
			<?php if ( isset( $result[ 'id' ] ) AND $result[ 'id' ] ){ ?>
				
				<li class="search-result-wrapper">
					
					<a href="<?= get_url( $this->articles->{ 'get_' . ( $this->component_function_action == 'as' ? 'a' : 'c' ) . '_url' }( 'edit', $result[ 'id' ] ) ); ?>" class="search-result" data-resultid="<?= $result[ 'id' ]; ?>" >
						
						<span class="s1" >
							
							<?php
								
								if ( $result[ 'image' ] ){
									
									$thumb_params = array(
										
										'wrapper_class' => '',
										'wrappers_el_type' => 'span',
										'src' => $result[ 'image' ],
										'title' => $result[ 'title' ],
										
									);
									
									echo vui_el_thumb( $thumb_params );
									
								}
								
							?>
							
							<?php
								
								$title = FALSE;
								
								if ( $this->component_function_action == 'cs' ) {
									
									if ( isset( $ct ) AND isset( $result[ 'highlight_indented_title' ] ) ) {
										
										$title = $result[ 'highlight_indented_title' ];
										
									}
									else if ( isset( $ct ) AND isset( $result[ 'indented_title' ] ) ) {
										
										$title = $result[ 'indented_title' ];
										
									}
									else if ( isset( $result[ 'highlight_title' ] ) ) {
										
										$title = $result[ 'highlight_title' ];
										
									}
									else if ( isset( $result[ 'title' ] ) ) {
										
										$title = $result[ 'title' ];
										
									}
									
								}
								else if ( isset( $result[ 'highlight_title' ] ) ) {
									
									$title = $result[ 'highlight_title' ];
									
								}
								else if ( isset( $result[ 'title' ] ) ) {
									
									$title = $result[ 'title' ];
									
								}
								
							?>
							
							<?php if ( $title ){ ?>
								
								<span class="title search-result-title">
									
									<?= $title; ?>
									
								</span>
								
							<?php } ?>
							
							<?php if ( $result[ 'content' ] ){ ?>
								
								<span class="content search-result-content">
									
									<?= ( isset( $result[ 'highlight_content' ] ) ? $result[ 'highlight_content' ] : $result[ 'content' ] ); ?>
									
								</span>
								
							<?php } ?>
							
						</span>
						
					</a>
					
				</li>
				
			<?php } ?>
			
		<?php } ?>
		
	<?php } ?>
	
</ul>

<script type="text/javascript">
	
	if ( typeof window.onArticleChooseFunction == 'function' ) {
		
		$( document ).on( 'click', '.result-list-live-search .result-item', function( event ) {
			
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
	
	<?= lang( 'live_search_no_results' ); ?>
	
</div>

<?php } ?>
