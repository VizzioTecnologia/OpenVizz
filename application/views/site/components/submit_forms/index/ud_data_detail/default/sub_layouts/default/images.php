<?php if ( $_field ) {
	
	?><div class="
		
		ud-data-prop
		ud-data-prop-alias-<?= url_title( $_alias ); ?>
		ud-data-prop-ud-title
		col-<?= $_alias; ?>
		<?= isset( $_field[ 'value' ] ) ? ' ud-data-prop-value-' . $_alias . '-' . word_limiter( url_title( base64_encode( $_field[ 'value' ] ), '-', TRUE ) ) : ''; ?>
		
	">
		
		<?= $_field[ 'value' ]; ?>
		
	</div><?php 
	
} ?>
