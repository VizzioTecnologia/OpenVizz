<?php if ( $_content_field ) {
	
	?><div class="user-submit-field-wrapper user-submit-alias-<?= url_title( $key_2 ); ?> user-submit-content user-submit-content-<?= url_title( $_content_field[ 'label' ] ); ?> user-submit-<?= url_title( $key_2 ); ?>-<?= url_title( $_content_field[ 'value' ] ); ?>">
		
		<?php
			
			$content_word_limit = check_var( $params[ 'users_submit_content_word_limit' ] ) ? $params[ 'users_submit_content_word_limit' ] : 120;
			$word_limit_str = '...';
			
			if ( $content_word_limit > 0 AND strlen( $_content_field[ 'value' ] ) > $content_word_limit ) {
				
				$_content_field[ 'value' ] = word_limiter( $_content_field[ 'value' ], $content_word_limit, $word_limit_str );
				
			}
			
		?>
		
		<span class="value">
			
			<?php if ( $key_2 == 'site' ) { ?>
				
				<a target="_blank" href="<?= 'http://' . $_content_field[ 'value' ]; ?>"><?= $_content_field[ 'value' ]; ?></a>
				
			<?php } else if ( $key_2 == 'e-mail' ) { ?>
				
				<a href="<?= 'mailto:' . $_content_field[ 'value' ]; ?>"><?= $_content_field[ 'value' ]; ?></a>
				
			<?php } else { ?>
				
				<?= $_content_field[ 'value' ]; ?>
				
			<?php } ?>
			
		</span>
		
	</div><?php 
	
} ?>