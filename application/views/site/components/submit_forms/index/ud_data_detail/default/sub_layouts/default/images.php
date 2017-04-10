<?php if ( $_field ) {
	
	?><div class="ud-data-prop user-submit-field-wrapper user-submit-alias-<?= url_title( $_alias ); ?> user-submit-image user-submit-image-<?= url_title( $_field[ 'label' ] ); ?> user-submit-<?= url_title( $_alias ); ?>-<?= url_title( $_field[ 'value' ] ); ?>">
		
		<?php
			
			$thumb_params = array(
				
				'wrapper_class' => 'us-image-wrapper',
				'src' => get_url( $user_submit[ 't_data' ][ $_alias . '_thumb_default' ] ),
				'href' => get_url( $user_submit[ 't_data' ][ $_alias ] ),
				'rel' => 'us-thumb',
				'title' => $user_submit[ 't_data' ][ $_alias ],
				'modal' => TRUE,
				'prevent_cache' => check_var( $advanced_options[ 'prop_is_ud_image_thumb_prevent_cache_' . environment() ] ) ? TRUE : FALSE,
				
			);
			
			echo vui_el_thumb( $thumb_params );
			
		?>
		
	</div><?php 
	
} ?>