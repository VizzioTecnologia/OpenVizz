<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' ); ?>


<?php if ( check_var( $params[ 'show_readmore_link' ] ) AND check_var( $params[ 'readmore_text' ] ) ) { ?>
	
	<div class="read-more user-submit-readmore-link-wrapper user-submit-alias-<?= url_title( $key_2 ); ?> user-submit-readmore-link-<?= url_title( $key_2 ); ?>">
		
		<div class="s1 inner">
			
			<div class="s2 inner">
				
				<a class="read-more-link user-submit-read-more-link" <?= ( check_var( $params[ 'readmore_link_target' ] ) ? 'target="' . $params[ 'readmore_link_target' ] . '"' : '' ); ?> href="<?= get_url( ( check_var( $params[ 'readmore_url' ] ) ? $params[ 'readmore_url' ] : '' ) ); ?>" title="<?= lang( $params[ 'readmore_text' ] ); ?>" ><?= lang( $params[ 'readmore_text' ] ); ?></a>
				
			</div>
			
		</div>
		
	</div>
	
<?php } ?>
