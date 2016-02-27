
<?php if ( $_field AND isset( $fields[ $_alias ] ) ) { ?>
	
	<div class="ud-data-prop user-submit-title-wrapper user-submit-alias-<?= url_title( $_alias ); ?> user-submit-title-<?= url_title( $_field[ 'label' ] ); ?>">
		
		<h4 class="value"><?= $_field[ 'value' ]; ?></h4>
		
	</div>
	
<?php } ?>
