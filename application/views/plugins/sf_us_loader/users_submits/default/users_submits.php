<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

$unique_hash = md5( uniqid( rand(), true ) );
$users_submits = $submit_form[ 'users_submits' ];
$users_submits_total_results = count( $users_submits );

$props = & $submit_form[ 'fields' ];

$_path = VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . 'submit_forms' . DS . 'index' . DS . 'users_submits' . DS . 'default' . DS;

// echo '<pre>' . print_r( $params, TRUE ) . '</pre>';

?>

<section id="users-submits-<?= $unique_hash; ?>" class="ud-data-schema ud-data-schema-item users-submits <?= $params['wc']; ?> item item-<?= $__index; ?>">
	
	<?php if ( $params['st'] ) { ?>
	<header class="heading">
		
		<h3>
			
			<?php if ( check_var( $submit_form[ 'data_list_site_link' ] ) ) { ?>
				
				<a href="<?= $submit_form[ 'data_list_site_link' ]; ?>">
				
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
			
			<?php if ( check_var( $users_submits ) ) { ?>
			
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
