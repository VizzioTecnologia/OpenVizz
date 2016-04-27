<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

//echo print_r( $fields, true ) . '<br/><br/><br/>-----------<br/><br/><br/>';

$_default_path = VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . 'submit_forms' . DS . 'index' . DS . 'users_submits' . DS . 'default' . DS;

$_path = SITE_THEMES_PATH . site_theme() . DS . VIEWS_DIR_NAME . DS . COMPONENTS_DIR_NAME . DS . 'submit_forms' . DS . 'index' . DS . 'users_submits' . DS . 'cursos_externos_2' . DS;

$filter_fields_input_name = 'users_submits_search[dinamic_filter_fields]';

$pre_text_pos = 'before_search_fields';

$unique_hash = md5( uniqid( rand(), true ) );

if ( check_var( $submit_form[ 'params' ][ 'us_pre_text_position' ] ) ) {
	
	$pre_text_pos = $submit_form[ 'params' ][ 'us_pre_text_position' ];
	
}

?>

<section id="submit-form-users-submits-<?= $unique_hash; ?>" class="submit-form users-submits <?= @$params['page_class']; ?> cursos-externos">
	
	<?php if ( @$params['show_page_content_title'] ) { ?>
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
			
			if ( file_exists( $_default_path . 'search_box.php' ) ) {
				
				require( $_default_path . 'search_box.php' );
				
			}
			
		}
		
		/* ---------------------------------------------------------------------------
		* ---------------------------------------------------------------------------
		* Users submits
		* ---------------------------------------------------------------------------
		*/
		
		?>
		
		<?php if ( $pagination ) { ?>
		<?= $pagination; ?>
		<?php } ?>
		
		<?php
			
			if ( file_exists( $_path . 'users_submits_results.php' ) ) {
				
				require( $_path . 'users_submits_results.php' );
				
			}
			
		?>
		
		<?php if ( $pagination ){ ?>
		<?= $pagination; ?>
		<?php } ?>
		
		<div class="clear"></div>
		
		<?php
			
			/* ---------------------------------------------------------------------------
			* ---------------------------------------------------------------------------
			* Read more
			* ---------------------------------------------------------------------------
			*/
			
			if ( file_exists( $_default_path . 'readmore.php' ) ) {
				
				require( $_default_path . 'readmore.php' );
				
			}
			
		?>
		
	</div>

</section>

<?php if ( $this->plugins->load( 'fancybox' ) ){ ?>

<script type="text/javascript" >
	
	$( document ).on( 'ready', function( e ){
		
		var fbContent;
		
		$( "#submit-form-users-submits-<?= $unique_hash; ?> .modal" ).fancybox({
			
			wrapCSS: 'cursos-externos',
			minWidth: 300,
			maxWidth: 1280,
			content: fbContent,
			beforeShow: function(){
				
				var el, id = $( this.element ).data( 'fancybox-title-id' );
				
				if ( id ) {
					
					el = $( '#' + id );
					
					if ( el.length ) {
						
						this.title = el.html();
						
					}
					
				}
				
				fbContent = $( this ).html();
				$(".fancybox-overlay").addClass("user-submit-detail users-submits-layout-cursos-externos cursos-externos");
				
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
