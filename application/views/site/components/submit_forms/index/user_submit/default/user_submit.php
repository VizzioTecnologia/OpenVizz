<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
	
	$props = & $submit_form[ 'fields' ];
	
	$_path = VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . 'submit_forms' . DS . 'index' . DS . 'user_submit' . DS . 'default' . DS;
	
	$filter_fields_input_name = 'users_submits_search[dinamic_filter_fields]';
	
	$pre_text_pos = 'before_search_fields';
	
	$unique_hash = md5( uniqid( rand(), true ) );
	
	if ( check_var( $submit_form[ 'params' ][ 'us_pre_text_position' ] ) ) {
		
		$pre_text_pos = $submit_form[ 'params' ][ 'us_pre_text_position' ];
		
	}
	
	?>
	
	<section id="submit-form-user-submit-detail-<?= $unique_hash; ?>" class="user-submit-detail ud-data-detail <?= @$params[ 'page_class' ]; ?>">
		
		<?php if ( ! isset( $params[ 'show_page_content_title' ] ) OR check_var( $params[ 'show_page_content_title' ] ) ) { ?>
		<header class="component-heading">
			
			<h1>
				
				<?= $this->mcm->html_data[ 'content' ][ 'title' ]; ?>
				
			</h1>
			
		</header>
		<?php } ?>
		
		<div id="component-content">
			
			<?php
				
				/* ---------------------------------------------------------------------------
				* ---------------------------------------------------------------------------
				* User submit
				* ---------------------------------------------------------------------------
				*/
				
				if ( file_exists( $_path . 'sub_layouts' . DS . 'default' . DS . 'user_submit.php' ) ) {
					
					require( $_path . 'sub_layouts' . DS . 'default' . DS . 'user_submit.php' );
					
				}
				
			?>
			
		</div>
		
	</section>
	