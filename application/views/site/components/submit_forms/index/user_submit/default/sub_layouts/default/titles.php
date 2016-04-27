
<?php if ( $_field ) { ?>
	
	<div class="ud-data-prop user-submit-title-wrapper user-submit-alias-<?= url_title( $_alias ); ?> user-submit-title-<?= url_title( $_field[ 'label' ] ); ?>">
		
		<h2 class="value"><?= $_field[ 'value' ]; ?></h2>
		
	</div>
	
<?php } ?>
