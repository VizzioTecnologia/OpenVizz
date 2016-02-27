<?php if ( $_field ) {
	
	?><tr class="ud-data-prop user-submit-field-wrapper user-submit-alias-<?= url_title( $_alias ); ?> user-submit-content-<?= url_title( $_field[ 'label' ] ); ?> user-submit-<?= url_title( $_alias ); ?>-<?= url_title( $_field[ 'value' ] ); ?>">
		
		<td class="title"><?= lang( $_field[ 'label' ] ); ?>: </td><?php
		?><td class="value">
			
			<?= $_field[ 'value' ]; ?>
			
		</td>
	
	</tr><?php
	
} ?>