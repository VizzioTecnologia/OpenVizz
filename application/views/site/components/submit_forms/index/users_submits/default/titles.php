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
			
			<?php if ( check_var( $params[ 'ud_data_list_d_titles_as_link' ] ) AND isset( $ud_data[ 'site_link' ] ) ) { ?>
			
			<a class="value" href="<?= $ud_data[ 'site_link' ]; ?>">
				
				<?= $_field[ 'value' ]; ?>
				
			</a>
			
			<?php } else { ?>
			
			<span class="value">
				
				<?= $_field[ 'value' ]; ?>
				
			</span>
			
			<?php } ?>
			
		</h4>
		
	</div>
	
<?php } ?>
