<?php if ( $_field ) {
	
	if ( $__item_count == 1 ) {
		
		echo '<table>';
		
	}
	
	$__class = array();
	
	if ( isset( $props[ $_alias ][ 'advanced_options' ][ 'prop_is_ud_url' ] ) ) {
		
		$__class[] = 'ud-data-prop-type-url';
		
	}
	if ( isset( $props[ $_alias ][ 'advanced_options' ][ 'prop_is_ud_email' ] ) ) {
		
		$__class[] = 'ud-data-prop-type-email';
		
	}
	
	$__class[] = 'ud-data-prop';
	$__class[] = 'ud-data-prop-alias-' . url_title( $_alias );
	$__class[] = 'ud-data-prop-ud-other_info';
	$__class[] = 'col-' . $_alias;
	$__class[] = isset( $_field[ 'value' ] ) ? 'ud-data-prop-value-' . $_alias . '-' . word_limiter( url_title( base64_encode( $_field[ 'value' ] ), '-', TRUE ) ) : '';;
	
	$__class = join( $__class, ' ' );
	
	?><tr class="<?= $__class; ?>">
		
		<td class="title"><?= lang( $_field[ 'label' ] ); ?>: </td><?php
		?><td class="value">
			
			<?php
				
				if ( check_var( $ud_data[ 'parsed_data' ][ 'full' ][ $_alias ][ 'search_filter_url' ] ) ) {
					
					echo '<a href="' . get_url( $ud_data[ 'parsed_data' ][ 'full' ][ $_alias ][ 'search_filter_url' ] ) . '">';
					
				}
				
			?>
			
			<?= $_field[ 'value' ]; ?>
			
			<?php
				
				if ( check_var( $ud_data[ 'parsed_data' ][ 'full' ][ $_alias ][ 'search_filter_url' ] ) ) {
					
					echo '</a>';
					
				}
				
			?>
			
		</td>
	
	</tr><?php
	
	if ( $__item_count == count( ${ '_ud_' . $v . '_props' } ) ) {
		
		echo '</table>';
		
	}
	
} ?>
