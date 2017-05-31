<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
	
	if ( $this->plugins->load( 'jquery' ) ){
		
		$shares = check_var( $module_data[ 'params' ][ 'jssocials_shares' ] ) ? ( '"' . join( '","', $module_data[ 'params' ][ 'jssocials_shares' ] ) . '"' ) : NULL;
		
// 		print "<pre>" . print_r( $shares, true ) . "</pre>"; exit;
		
		if ( $shares ) {
			
			$this->voutput->append_head_stylesheet( 'font-awesome', STYLES_DIR_URL . '/' . MODULES_ALIAS . '/jssocials/font-awesome/font-awesome.min.css' );
			$this->voutput->append_head_stylesheet( 'jssocials-module', STYLES_DIR_URL . '/' . MODULES_ALIAS . '/jssocials/jssocials.css' );
			$this->voutput->append_head_stylesheet( 'jssocials-module', STYLES_DIR_URL . '/' . MODULES_ALIAS . '/jssocials/' . ( check_var( $module_data[ 'params' ][ 'jssocials_theme' ] ) ? $module_data[ 'params' ][ 'jssocials_theme' ] : 'jssocials-theme-flat.css' ) );
			
			$this->voutput->append_head_script( 'jssocials', JS_DIR_URL . '/modules/jssocials/jssocials.min.js' );
			
			$this->voutput->append_head_script_declaration( 'jssocials', '
				
				$( document ).ready( function(){
					
					$(".jssocials-wrapper").jsSocials({
						showLabel: ' . ( check_var( $module_data[ 'params' ][ 'jssocials_show_label' ] ) ? 'true' : 'false' ) . ',
						showCount: ' . ( check_var( $module_data[ 'params' ][ 'jssocials_show_count' ] ) ? 'true' : 'false' ) . ',
						shares: [' . $shares . ']
					});
					
				});
				
			' );
			
		}
		
	}
	
?>

<section class="module-wrapper jssocials-module jssocials-module-wrapper <?= $module_data[ 'params' ][ 'module_class' ]; ?>">
	
	<?php if ( check_var( $module_data[ 'params' ][ 'show_title' ] ) ) { ?>
	<header class="module-title">
		
		<h3>
			
			<?= $module_data[ 'title' ]; ?>
			
		</h3>
		
	</header><?php
	
	}
	
	?><div id="shareRoundIcons" class="module-content jssocials-module-content layout-<?= url_title( $module_data[ 'params' ][ $module_data[ 'name' ] . '_layout' ] ); ?>">
		
		<div class="jssocials-wrapper">
			
		</div>
		
		<div class="clear">&nbsp;</div>
		
	</div>

</section>
