<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

//echo print_r( $module_data, true ) . '<br/><br/><br/>-----------<br/><br/><br/>';

$_default_path = VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . 'submit_forms' . DS . 'index' . DS . 'users_submits' . DS . 'default' . DS;

$_path = VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . 'submit_forms' . DS . 'index' . DS . 'users_submits' . DS . 'testimonials' . DS;

$filter_fields_input_name = 'users_submits_search[dinamic_filter_fields]';

$params = $module_data[ 'params' ];

$unique_hash = md5( uniqid( rand(), true ) );

?>

<section id="users-submits-module-<?= $unique_hash; ?>" class="module-wrapper users-submits-module users-submits-module-wrapper <?= $params[ 'module_class' ]; ?> testimonials">
	
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
					
					if ( file_exists( $_default_path . 'readmore.php' ) ) {
						
						require( $_default_path . 'readmore.php' );
						
					}
					
				?>
			
			<?php } else { ?>
				
				<?php if ( $this->input->post( NULL, TRUE ) OR ! check_var( $users_submits ) ) { ?>
					
					<h4 class="title"><?= lang( 'no_results' ); ?></h4>
					
				<?php } else { ?>
					
					<div class="users-submits-description-no-search-results">
						
						<?= lang( 'users_submits_description_no_search_results' ); ?>
						
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
