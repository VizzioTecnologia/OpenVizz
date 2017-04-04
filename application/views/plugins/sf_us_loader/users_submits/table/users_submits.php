<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

$users_submits = $submit_form[ 'users_submits' ];
$users_submits_total_results = count( $users_submits );

$_path = VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . 'submit_forms' . DS . 'index' . DS . 'users_submits' . DS . 'table' . DS;

$props = & $submit_form[ 'fields' ];

$props_to_show = & $params[ 'props_to_show_site_list' ];

$filter_fields_input_name = 'users_submits_search[dinamic_filter_fields]';

$pre_text_pos = 'before_search_fields';

$unique_hash = md5( uniqid( rand(), true ) );

if ( check_var( $params[ 'us_pre_text_position' ] ) ) {
	
	$pre_text_pos = $params[ 'us_pre_text_position' ];
	
}

// print_r( $params );

?>

<section id="ud-d-list-wrapper-<?= $unique_hash; ?>" class="ud-d-list-wrapper <?= @$params['page_class']; ?>">
	<?= get_url( $submit_form[ 'data_list_site_link' ] ); ?>
	<?php if ( $params['st'] ) { ?>
	<header class="heading">
		
		<h3>
			
			<?php if ( check_var( $submit_form[ 'data_list_site_link' ] ) ) { ?>
				
				<a href="<?= get_url( $submit_form[ 'data_list_site_link' ] ); ?>">
				
			<?php } ?>
			
			<?php if ( check_var( $params[ 'csft' ] ) AND trim( $params[ 'csft' ] ) != '' ) { ?>
				
				<?= $params[ 'csft' ]; ?>
				
			<?php } else { ?>
				
				<?= $submit_form[ 'title' ]; ?>
				
			<?php } ?>
			
			<?php if ( check_var( $submit_form[ 'data_list_site_link' ] ) ) { ?>
				
				</a>
				
			<?php } ?>
			
		</h3>
		
	</header>
	<?php } ?>
	
	<div id="component-content">
		
		<?php
		
		/* ---------------------------------------------------------------------------
		* ---------------------------------------------------------------------------
		* Search box
		* ---------------------------------------------------------------------------
		*/
		
		if ( check_var( $params[ 'use_search' ] ) ) {
			
			if ( file_exists( $_path . 'search_box.php' ) ) {
				
				require( $_path . 'search_box.php' );
				
			}
			
		}
		
		?>
		
		<?php
			
			/* ---------------------------------------------------------------------------
			* ---------------------------------------------------------------------------
			* Users submits
			* ---------------------------------------------------------------------------
			*/
			
			if ( file_exists( $_path . 'users_submits_results.php' ) ) {
				
				require( $_path . 'users_submits_results.php' );
				
			}
			
		?>
		
	</div>

</section>

<?php if ( $this->plugins->load( 'fancybox' ) ){ ?>

<script type="text/javascript" >
	
	$( document ).on( 'ready', function( e ){
		
		var fbContent;
		
		$( "#submit-form-users-submits-<?= $unique_hash; ?> .modal" ).fancybox({
			
			content: fbContent,
			beforeShow: function(){
				
				fbContent = $( this ).html();
				$(".fancybox-overlay").addClass("user-submit-detail");
				
			},
			afterClose: function(){
				fbContent = '';
			},
			onComplete: function(){
			}
			
		});
		
	});
	
</script>

<?php } ?>
