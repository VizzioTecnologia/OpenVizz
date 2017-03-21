<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' ); ?>

<?php if ( check_var( $params[ 'ud_data_list_d_readmore_link' ] ) ) { ?>
	
	<div class="item readmore user-submit-readmore-link-wrapper ud-data-list-d-readmore-link-wrapper">
		
		<div class="s1 inner">
			
			<div class="s2 inner">
				
				<a class="readmore-link us-data-list-d-readmore-link" <?= ( check_var( $params[ 'ud_data_list_d_readmore_link_target' ] ) ? 'target="' . $params[ 'ud_data_list_d_readmore_link_target' ] . '"' : '' ); ?> href="<?= get_url( ( check_var( $params[ 'ud_data_list_d_readmore_link_url' ] ) ? $params[ 'ud_data_list_d_readmore_link_url' ] : ( check_var( $user_submit[ 'site_link' ] ) ? $user_submit[ 'site_link' ] : '' ) ) ); ?>" title="<?= check_var( $params[ 'ud_data_list_d_readmore_link_custom_str' ] ) ? lang( $params[ 'ud_data_list_d_readmore_link_custom_str' ] ) : lang( 'readmore' ); ?>" ><?= check_var( $params[ 'ud_data_list_d_readmore_link_custom_str' ] ) ? lang( $params[ 'ud_data_list_d_readmore_link_custom_str' ] ) : lang( 'readmore' ); ?></a>
				
			</div>
			
		</div>
		
	</div>
	
<?php } ?>
