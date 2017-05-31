<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

$_path = VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . 'submit_forms' . DS . 'index' . DS . 'users_submits' . DS . 'table' . DS;

$unique_hash = md5( uniqid( rand(), true ) );

$ud_data_array_total_results = count( $ud_data_array );

$filter_fields_input_name = 'users_submits_search[dinamic_filter_fields]';

$pre_text_pos = 'before_search_fields';

if ( check_var( $params[ 'us_pre_text_position' ] ) ) {
	
	$pre_text_pos = $params[ 'us_pre_text_position' ];
	
}

// echo '<pre>' . print_r( $params, TRUE ) . '</pre>';

// print_r( $params );



?><section id="ud-d-list-wrapper-<?= $unique_hash; ?>" class="unid ud-d-list-layout-<?= $params[ 'ud_d_list_layout_site' ]; ?> ud-data-schema-item users-submits ud-d-list-wrapper <?= $params[ 'wc' ]; ?> item item-<?= $__index; ?>">
	
	<?php if ( $params[ 'st' ] ) { ?>
	<header class="heading">
		
		<h3>
			
			<?php if ( check_var( $data_scheme[ 'data_list_site_link' ] ) ) { ?>
				
				<a href="<?= get_url( $data_scheme[ 'data_list_site_link' ] ); ?>">
				
			<?php } ?>
			
			<?php if ( check_var( $params[ 'csft' ] ) AND trim( $params[ 'csft' ] ) != '' ) { ?>
				
				<?= $params[ 'csft' ]; ?>
				
			<?php } else { ?>
				
				<?= $data_scheme[ 'title' ]; ?>
				
			<?php } ?>
			
			<?php if ( check_var( $data_scheme[ 'data_list_site_link' ] ) ) { ?>
				
				</a>
				
			<?php } ?>
			
		</h3>
		
	</header>
	<?php } ?>
	
	<div class="users-submits-wrapper ud-d-list-results results">
		
		<div class="s1">
			
			<?php
				
				/* ---------------------------------------------------------------------------
				* ---------------------------------------------------------------------------
				* UniD Data
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
					
					$_default_path = VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . 'submit_forms' . DS . 'index' . DS . 'users_submits' . DS . 'default' . DS;
					
					if ( check_var( $params[ 'ud_data_list_ds_readmore_link' ] ) AND file_exists( $_default_path . 'ds_readmore.php' ) ) {
						
						require( $_default_path . 'ds_readmore.php' );
						
					}
					
				?>
			
			<?php } ?>
			
		</div>
		
		<div class="clear"></div>
		
	</div>
	
</section>
