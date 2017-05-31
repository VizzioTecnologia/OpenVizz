<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

// 	echo '<pre>' . print_r( $params, TRUE ) . '</pre>';

if ( $_field ) {
	
	?><div class="
		
		ud-data-prop
		ud-data-prop-alias-<?= url_title( $_alias ); ?>
		ud-data-prop-ud-title
		col-<?= $_alias; ?>
		<?= isset( $_field[ 'value' ] ) ? ' ud-data-prop-value-' . $_alias . '-' . word_limiter( url_title( base64_encode( $_field[ 'value' ] ), '-', TRUE ) ) : ''; ?>
		
	">
		
		<h4 class="ud-data-value-wrapper">
			
			<span class="value">
				
				<?= $_field[ 'value' ]; ?>
				
			</span>
			
		</h4>
		
	</div>
	
<?php } ?>
