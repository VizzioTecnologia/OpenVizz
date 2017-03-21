<?php if ( $_field ) {
	
	$__class = array();
	
	if ( isset( $props[ $_alias ][ 'advanced_options' ][ 'prop_is_ud_url' ] ) ) {
		
		$__class[] = 'ud-data-prop-type-url';
		
	}
	if ( isset( $props[ $_alias ][ 'advanced_options' ][ 'prop_is_ud_email' ] ) ) {
		
		$__class[] = 'ud-data-prop-type-email';
		
	}
	
	$__class = join( $__class, ' ' );
	
	?><tr class="ud-data-prop <?= $__class; ?> user-submit-field-wrapper user-submit-alias-<?= url_title( $_alias ); ?> user-submit-content-<?= url_title( $_field[ 'label' ] ); ?> user-submit-<?= url_title( $_alias ); ?>-<?= url_title( $_field[ 'value' ] ); ?>">
		
		<td class="title"><?= lang( $_field[ 'label' ] ); ?>: </td><?php
		?><td class="value">
			
			<?= $_field[ 'value' ]; ?>
			
		</td>
	
	</tr><?php
	
} ?>