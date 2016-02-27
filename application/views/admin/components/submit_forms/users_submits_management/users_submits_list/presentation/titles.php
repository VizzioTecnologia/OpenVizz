
<?php if ( $_title_field AND isset( $fields[ $key_2 ] ) ) { ?>
	
	<div class="user-submit-title-wrapper user-submit-alias-<?= url_title( $key_2 ); ?> user-submit-title-<?= url_title( $_title_field[ 'label' ] ); ?>">
		
		<h4 class="title"><?= $_title_field[ 'value' ]; ?></h4>
		
	</div>
	
<?php } ?>
