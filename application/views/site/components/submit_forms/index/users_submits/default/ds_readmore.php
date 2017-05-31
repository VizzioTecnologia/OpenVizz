<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

$target = ( check_var( $params[ 'ud_data_list_ds_readmore_link_target' ] ) ? 'target="' . $params[ 'ud_data_list_ds_readmore_link_target' ] . '"' : '' );

if ( check_var( $params[ 'ud_data_list_ds_readmore_link_url' ] ) ) {
	
	$url = $params[ 'ud_data_list_ds_readmore_link_url' ];
	
}
else if ( check_var( $data_scheme[ 'data_list_site_link' ] ) ) {
	
	$url = get_url( $data_scheme[ 'data_list_site_link' ] );
	
}

if ( check_var( $params[ 'ud_data_list_ds_readmore_custom_str' ] ) ) {
	
	$label = lang( $params[ 'ud_data_list_ds_readmore_custom_str' ] );
	
}
else {
	
	$label = lang( 'readmore' );
	
}

?>

<?php if ( check_var( $params[ 'ud_data_list_ds_readmore_link' ] ) ) { ?>
	
	<div class="item read-more readmore ud-ds-readmore-link-wrapper ud-d-list-ds-readmore-link-wrapper">
		
		<div class="s1 inner">
			
			<div class="s2 inner">
				
				<a class="read-more-link readmore-link us-d-list-ds-readmore-link" href="<?= $url; ?>" title="<?= $label; ?>" ><?= $label; ?></a>
				
			</div>
			
		</div>
		
	</div>
	
<?php } ?>
