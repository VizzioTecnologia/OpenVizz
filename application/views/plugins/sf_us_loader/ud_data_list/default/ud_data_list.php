<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

$_path = VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . 'submit_forms' . DS . 'index' . DS . 'users_submits' . DS . 'default' . DS;

$unique_hash = md5( uniqid( rand(), true ) );

$ud_data_array_total_results = count( $ud_data_array );

?>

<section id="users-submits-<?= $unique_hash; ?>" class="unid ud-d-list-layout-<?= $params[ 'ud_d_list_layout_site' ]; ?> ud-d-list-wrapper ud-data-schema ud-data-schema-item users-submits <?= $params[ 'wc' ]; ?> item item-<?= $__index; ?>">
	
	<?php if ( $params['st'] ) { ?>
	<header class="heading">
		
		<h3>
			
			<?php if ( check_var( $submit_form[ 'data_list_site_link' ] ) ) { ?>
				
				<a href="<?= get_url( $submit_form[ 'data_list_site_link' ] ); ?>">
				
			<?php } ?>
			
			<?php if ( check_var( $params[ 'csft' ] ) AND trim( $params[ 'csft' ] ) != '' ) { ?>
				
				<?= $params[ 'csft' ]; ?>
				
			<?php } else { ?>
				
				<?= $submit_form[ 'title' ]; ?>
				
			<?php } ?>
			
			<?php if ( check_var( $submit_form[ 'data_list_site_link' ] ) ) { ?>
				
				</a>
				
			<?php } ?>
			
		</h3>
		
	</header>
	<?php } ?>
	
	<div class="users-submits-wrapper results">
		
		<div class="s1">
			
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
			
			<?php if ( check_var( $ud_data_array ) ) { ?>
			
				<div class="clear"></div>
				
				<?php
					
					/* ---------------------------------------------------------------------------
					* ---------------------------------------------------------------------------
					* Read more
					* ---------------------------------------------------------------------------
					*/
					
					if ( check_var( $params[ 'ud_data_list_ds_readmore_link' ] ) AND file_exists( $_path . 'readmore.php' ) ) {
						
						require( $_path . 'readmore.php' );
						
					}
					
				?>
			
			<?php } ?>
			
		</div>
		
		<div class="clear"></div>
		
	</div>
	
</section>
