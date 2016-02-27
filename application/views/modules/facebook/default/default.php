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
			
			<div class="fb-page" data-href="https://www.facebook.com/<?= $module_data[ 'params' ][ 'fb_page_id' ]; ?>" data-width="1000" data-hide-cover="false" data-show-facepile="true" data-show-posts="false"><div class="fb-xfbml-parse-ignore"></div></div>
			
		</div>
		
		<div class="clear">&nbsp;</div>
		
	</div>

</section>
