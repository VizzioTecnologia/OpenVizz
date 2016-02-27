<?php if ( $_field ) {
	
	?><div class="ud-data-prop user-submit-field-wrapper user-submit-alias-<?= url_title( $_alias ); ?> user-submit-status-<?= url_title( $_field[ 'label' ] ); ?> user-submit-<?= url_title( $_alias ); ?>-<?= url_title( $_field[ 'value' ] ); ?>">
		
		<span class="title"><?= lang( $_field[ 'label' ] ); ?>: </span>
		<span class="value">
			
			<?= $_field[ 'value' ]; ?>
			
		</span>
	
	</div><?php
	
} ?>