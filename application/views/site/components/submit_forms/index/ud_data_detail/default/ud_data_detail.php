<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
	
	$_path = VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . 'submit_forms' . DS . 'index' . DS . 'ud_data_detail' . DS . 'default' . DS;
	
	$filter_fields_input_name = 'users_submits_search[dinamic_filter_fields]';
	
	$unique_hash = md5( uniqid( rand(), true ) );
	
	?>
	
	<section id="submit-form-user-submit-detail-<?= $unique_hash; ?>" class="unid ud-d-detail-layout-<?= $params[ 'ud_d_detail_layout_site' ]; ?> ud-d-detail-wrapper submit-form user-submit <?= @$params['page_class']; ?>">
	
		<?php if ( ! isset( $params[ 'show_page_content_title' ] ) OR check_var( $params[ 'show_page_content_title' ] ) ) { ?>
		<header class="page-title">
			
			<h1>
				
				<?= $this->mcm->html_data[ 'content' ][ 'title' ]; ?>
				
			</h1>
			
		</header>
		<?php } ?>
		
		<div class="component-content">
			
			<?php
				
				/* ---------------------------------------------------------------------------
				* ---------------------------------------------------------------------------
				* User submit
				* ---------------------------------------------------------------------------
				*/
				
				if ( file_exists( $_path . 'sub_layouts' . DS . 'default' . DS . 'ud_data_detail.php' ) ) {
					
					require( $_path . 'sub_layouts' . DS . 'default' . DS . 'ud_data_detail.php' );
					
				}
				
			?>
			
		</div>
		
	</section>
	
