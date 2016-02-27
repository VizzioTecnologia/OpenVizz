<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
	
// 	echo '<pre>' . print_r( $params, TRUE ) . '</pre>';
	
?>

<?php if ( $_field AND isset( $fields[ $_alias ] ) ) { ?>
	
	<div class="ud-data-prop user-submit-title-wrapper user-submit-alias-<?= url_title( $_alias ); ?> user-submit-title-<?= url_title( $_field[ 'label' ] ); ?>">
		
		<h4 class="value">
			
			<?php if ( check_var( $params[ 'ud_data_list_d_titles_as_link' ] ) AND isset( $user_submit[ 'site_link' ] ) ) { ?>
			
			<a href="<?= $user_submit[ 'site_link' ]; ?>">

				<?= $_field[ 'value' ]; ?>

			</a>

			<?php } else { ?>

			<?= $_field[ 'value' ]; ?>

			<?php } ?>
			
		</h4>
		
	</div>
	
<?php } ?>
