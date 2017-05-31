<?php if ( $_field ) {
	
	?><div class="
		
		ud-data-prop
		ud-data-prop-alias-<?= url_title( $_alias ); ?>
		ud-data-prop-ud-status
		col-<?= $_alias; ?>
		<?= isset( $_field[ 'value' ] ) ? ' ud-data-prop-value-' . $_alias . '-' . word_limiter( url_title( base64_encode( $_field[ 'value' ] ), '-', TRUE ) ) : ''; ?>
		
	">
		
		<span class="ud-data-value-wrapper">
			
			<span class="title"><?= lang( $_field[ 'label' ] ); ?>: </span>
			<span class="value">
				
				<?= $_field[ 'value' ]; ?>
				
			</span>
			
		</span>
		
	</div><?php
	
} ?>
