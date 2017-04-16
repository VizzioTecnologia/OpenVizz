<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' ); ?>

<?php if ( check_var( $params[ 'ud_data_list_ds_readmore_link' ] ) ) { ?>
	
	<div class="item read-more readmore ud-ds-readmore-link-wrapper ud-d-list-ds-readmore-link-wrapper">
		
		<div class="s1 inner">
			
			<div class="s2 inner">
				
				<a class="read-more-link readmore-link us-d-list-ds-readmore-link" <?= ( check_var( $params[ 'ud_data_list_ds_readmore_link_target' ] ) ? 'target="' . $params[ 'ud_data_list_ds_readmore_link_target' ] . '"' : '' ); ?> href="<?= get_url( ( check_var( $params[ 'ud_data_list_ds_readmore_link_url' ] ) ? $params[ 'ud_data_list_ds_readmore_link_url' ] : ( check_var( $data_scheme[ 'data_list_site_link' ] ) ? $data_scheme[ 'data_list_site_link' ] : '' ) ) ); ?>" title="<?= check_var( $params[ 'ud_data_list_ds_readmore_link_custom_str' ] ) ? lang( $params[ 'ud_data_list_ds_readmore_link_custom_str' ] ) : lang( 'readmore' ); ?>" ><?= check_var( $params[ 'ud_data_list_ds_readmore_link_custom_str' ] ) ? lang( $params[ 'ud_data_list_ds_readmore_link_custom_str' ] ) : lang( 'readmore' ); ?></a>
				
			</div>
			
		</div>
		
	</div>
	
<?php } ?>
