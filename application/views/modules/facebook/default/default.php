<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
	
	$this->voutput->append_head_stylesheet( 'facebook-module', STYLES_DIR_URL . '/' . MODULES_ALIAS . '/facebook/facebook.css' );
	
	$this->voutput->append_body_start_script_declaration( 'facebook', '
	
		(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v2.3&appId=289346507923979";
		fjs.parentNode.insertBefore(js, fjs);
		}(document, \'script\', \'facebook-jssdk\'));
		
	', '<div id="fb-root"></div><script type="text/javascript" >' );
	
?>

<section class="module-wrapper facebook-module facebook-module-wrapper <?= $module_data[ 'params' ][ 'module_class' ]; ?>">
	
	<?php if ( check_var( $module_data[ 'params' ][ 'show_title' ] ) ) { ?>
	<header class="module-title">
		
		<h3>
			
			<?= $module_data[ 'title' ]; ?>
			
		</h3>
		
	</header><?php
	
	}
	
	?><div class="module-content facebook-module-content layout-<?= url_title( $module_data[ 'params' ][ 'facebook_layout' ] ); ?>">
		
		<div class="facebook-wrapper">
			
			<iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2F<?= $module_data[ 'params' ][ 'fb_page_id' ]; ?>%2F&tabs&width=500&height=214&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId=289346507923979" width="100%" height="214" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
			
		</div>
		
		<div class="clear">&nbsp;</div>
		
	</div>

</section>
