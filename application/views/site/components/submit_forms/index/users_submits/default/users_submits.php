<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

$__index = isset( $__index ) ? $__index : 1;

$_path = VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . 'submit_forms' . DS . 'index' . DS . 'users_submits' . DS . 'default' . DS;

$filter_fields_input_name = 'users_submits_search[dinamic_filter_fields]';

$pre_text_pos = 'before_search_fields';

$unique_hash = md5( uniqid( rand(), true ) );

if ( check_var( $data_scheme[ 'params' ][ 'us_pre_text_position' ] ) ) {
	
	$pre_text_pos = $data_scheme[ 'params' ][ 'us_pre_text_position' ];
	
}

if ( check_var( $params[ 'ud_d_list_site_search_box_positioning' ] ) AND in_array( $params[ 'ud_d_list_site_search_box_positioning' ], array( 'l', 'r', 't', ) ) ) {
	
	$search_box_pos = $params[ 'ud_d_list_site_search_box_positioning' ];
	
}
else {
	
	$search_box_pos = 't';
	
}

$show_page_content_title = check_var( $params['show_page_content_title'] );

if ( check_var( $params[ 'ud_d_list_show_page_content_title' ], TRUE ) ) {
	
	if ( $params[ 'ud_d_list_show_page_content_title' ] != 'global' ) {
		
		$show_page_content_title = check_var( $params[ 'ud_d_list_show_page_content_title' ] );
		
	}
	
}

?>

<section id="submit-form-users-submits-<?= $unique_hash; ?>" class="unid ud-d-list-layout-<?= $params[ 'ud_d_list_layout_site' ]; ?> ud-d-list-search-box-pos-<?= $search_box_pos; ?> <?= check_var( $params[ 'ud_d_list_page_content_title_image' ] ) ? 'ud-image-on-title' : ''; ?> ud-d-list-wrapper submit-form users-submits <?= @$params['page_class']; ?> item item-<?= $__index; ?>">
	
	<?php if ( $show_page_content_title ) { ?>
	<header class="component-heading page-title">
		
		<h1>
			
			<?= $this->mcm->html_data['content']['title']; ?>
			
		</h1>
		
	</header>
	<?php } ?>
	
	<div id="component-content">
		
		<?php
		
		/*
		* ---------------------------------------------------------------------------
		* ---------------------------------------------------------------------------
		* Search box
		* ---------------------------------------------------------------------------
		*/
		
		if ( check_var( $params[ 'use_search' ] ) ) {
			
			if ( file_exists( $_path . 'search_box.php' ) ) {
				
				require( $_path . 'search_box.php' );
				
			}
			
		}
		
		/*
		* ---------------------------------------------------------------------------
		* Search box
		* ---------------------------------------------------------------------------
		* ---------------------------------------------------------------------------
		*/
		
		/* ---------------------------------------------------------------------------
		* ---------------------------------------------------------------------------
		* Users submits
		* ---------------------------------------------------------------------------
		*/
		
		if ( file_exists( $_path . 'users_submits_results.php' ) ) {
			
			require( $_path . 'users_submits_results.php' );
			
		}
		
		?>
		
	</div>

</section>

<?php

$__index++;

?>
