<?php if ( $_field ) {
	
	?><div class="ud-data-prop user-submit-field-wrapper user-submit-alias-<?= url_title( $_alias ); ?> user-submit-content user-submit-content-<?= url_title( $_field[ 'label' ] ); ?> user-submit-<?= url_title( $_alias ); ?>-<?= url_title( $_field[ 'value' ] ); ?>">
		
		<span class="value">
			
			<?= $_field[ 'value' ]; ?>
			
		</span>
		
	</div><?php 
	
} ?>