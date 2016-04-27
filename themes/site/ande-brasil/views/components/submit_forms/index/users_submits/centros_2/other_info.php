<?php if ( $_other_info ) {
	
	?><div class="user-submit-field-wrapper user-submit-alias-<?= url_title( $key_2 ); ?> user-submit-content-<?= url_title( $_other_info[ 'label' ] ); ?> user-submit-<?= url_title( $key_2 ); ?>-<?= url_title( $_other_info[ 'value' ] ); ?>">
		
		<span class="title"><?= lang( $_other_info[ 'label' ] ); ?>: </span>
		<span class="value">
			
			<?= $_other_info[ 'value' ]; ?>
			
		</span>
	
	</div><?php
	
} ?>