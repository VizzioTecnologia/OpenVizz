<?php if ( $_field ) {
	
	?><div class="user-submit-field-wrapper user-submit-alias-<?= url_title( $_alias ); ?> user-submit-content user-submit-content-<?= url_title( $_field[ 'label' ] ); ?> user-submit-<?= url_title( $_alias ); ?>-<?= url_title( $_field[ 'value' ] ); ?>">
		
		<?php
			
			$content_word_limit = check_var( $params[ 'users_submit_content_word_limit' ] ) ? $params[ 'users_submit_content_word_limit' ] : 120;
			$word_limit_str = '...';
			
			if ( $content_word_limit > 0 AND strlen( $_field[ 'value' ] ) > $content_word_limit ) {
				
				$_field[ 'value' ] = word_limiter( $_field[ 'value' ], $content_word_limit, $word_limit_str );
				
			}
			
		?>
		
		<span class="value">
			
			<?= $_field[ 'value' ]; ?>
			
		</span>
		
	</div><?php 
	
} ?>