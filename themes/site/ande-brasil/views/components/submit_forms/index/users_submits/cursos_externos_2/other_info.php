<?php if ( $_other_info ) {
	
	?><div class="user-submit-field-wrapper user-submit-alias-<?= url_title( $key_2 ); ?> user-submit-content-<?= url_title( $_other_info[ 'label' ] ); ?> user-submit-<?= url_title( $key_2 ); ?>-<?= url_title( $_other_info[ 'value' ] ); ?>">
		
		<span class="title"><?= lang( $_other_info[ 'label' ] ); ?>: </span>
		<span class="value">
			
			<?php if ( $key_2 == 'site' ) { ?>
				
				<a target="_blank" href="<?= 'http://' . $_other_info[ 'value' ]; ?>"><?= $_other_info[ 'value' ]; ?></a>
				
			<?php } else if ( $key_2 == 'e-mail' ) { ?>
				
				<a href="<?= 'mailto:' . $_other_info[ 'value' ]; ?>"><?= $_other_info[ 'value' ]; ?></a>
				
			<?php } else { ?>
				
				<?= $_other_info[ 'value' ]; ?>
				
			<?php } ?>
			
		</span>
	
	</div><?php
	
} ?>