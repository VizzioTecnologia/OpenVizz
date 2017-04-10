<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

//echo print_r( $module_data, true ) . '<br/><br/><br/>-----------<br/><br/><br/>';

$_path = VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . 'submit_forms' . DS . 'index' . DS . 'users_submits' . DS . 'default' . DS;

$filter_fields_input_name = 'users_submits_search[dinamic_filter_fields]';

$params = $module_data[ 'params' ];

$unique_hash = md5( uniqid( rand(), true ) );

?>

<section id="users-submits-module-<?= $unique_hash; ?>" class="<?= ( ! count( $users_submits ) ? 'no-results ' : '' ); ?>module-wrapper users-submits-module users-submits-module-wrapper <?= $params[ 'module_class' ]; ?>">
	
	<?php if ( check_var( $params[ 'show_title' ] ) ) { ?>
	<header class="module-title">
		
		<h1>
			
			<?= $module_data[ 'title' ]; ?>
			
		</h1>
		
	</header><?php
	
	}
	
	?><div class="module-content users-submits-module-content layout-<?= url_title( $params[ 'ud_d_list_layout_site' ] ); ?>">
		
		<div class="users-submits-wrapper results">
			
			<?php if ( check_var( $users_submits ) ) { ?>
				
				<?php
					
					/* ---------------------------------------------------------------------------
					* ---------------------------------------------------------------------------
					* Users submits
					* ---------------------------------------------------------------------------
					*/
					
					if ( file_exists( $_path . 'users_submits_results.php' ) ) {
						
						require( $_path . 'users_submits_results.php' );
						
					}
					
				?>
				
				<div class="clear"></div>
				
				<?php
					
					/* ---------------------------------------------------------------------------
					* ---------------------------------------------------------------------------
					* Read more
					* ---------------------------------------------------------------------------
					*/
					
					if ( file_exists( $_path . 'readmore.php' ) ) {
						
						require( $_path . 'readmore.php' );
						
					}
					
				?>
				
			<?php } else { ?>
				
				<?php if ( check_var( $params[ 'us_module_no_results_message' ] ) ) { ?>
					
					<div class="ud-d-list-no-search-results-desc-wrapper no-results">
					
						<span class="ud-d-list-no-search-results-desc">
							
							<?= $params[ 'us_module_no_results_message' ]; ?>
							
						</span>
						
					</div>
					
				<?php } else { ?>
					
					<div class="ud-d-list-no-search-results-desc-wrapper no-results">
					
						<span class="ud-d-list-no-search-results-desc">
							
							<?= lang( 'users_submits_description_no_search_results' ); ?>
							
						</span>
						
					</div>
					
				<?php } ?>
				
			<?php } ?>
			
		</div>
		
		<div class="clear"></div>
		
	</div>

</section>

<?php if ( $this->plugins->load( 'fancybox' ) ){ ?>

<script type="text/javascript" >
	
	$( document ).on( 'ready', function( e ){
		
		var fbContent;
		
		$( "#users-submits-module-<?= $unique_hash; ?> .modal" ).fancybox({
			
			wrapCSS: 'testimonials',
			maxWidth: 1280,
			content: fbContent,
			beforeShow: function(){
				
				fbContent = $( this ).html();
				$(".fancybox-overlay").addClass("user-submit-detail users-submits-layout-testimonials testimonials");
				
			},
			afterClose: function(){
				fbContent = '';
			},
			onComplete: function(){
			}
			
		});
		
	});
	
</script>

<?php } ?>
