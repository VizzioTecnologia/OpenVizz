<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

//echo print_r( $props, true ) . '<br/><br/><br/>-----------<br/><br/><br/>';

$_path = VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . 'submit_forms' . DS . 'index' . DS . 'users_submits' . DS . 'table' . DS;

$data_scheme = & $submit_form;
$props = & $data_scheme[ 'fields' ];

$props_to_show = & $params[ 'props_to_show_site_list' ];



$filter_fields_input_name = 'users_submits_search[dinamic_filter_fields]';

$pre_text_pos = 'before_search_fields';

$unique_hash = md5( uniqid( rand(), true ) );

if ( check_var( $params[ 'us_pre_text_position' ] ) ) {
	
	$pre_text_pos = $params[ 'us_pre_text_position' ];
	
}

// print_r( $params );

?>

<section id="ud-d-list-wrapper-<?= $unique_hash; ?>" class="unid ud-d-list-layout-<?= $params[ 'ud_d_list_layout_site' ]; ?> submit-form ud-d-list-wrapper <?= @$params['page_class']; ?>">
	
	<?php if ( check_var( $params['show_page_content_title'] ) ) { ?>
	<header class="component-heading">
		
		<h1>
			
			<?= $this->mcm->html_data['content']['title']; ?>
			
		</h1>
		
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
		
		<?php if ( $pagination ) { ?>
		<?= $pagination; ?>
		<?php } ?>
		
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
		
		<?php if ( $pagination ){ ?>
		<?= $pagination; ?>
		<?php } ?>
		
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
