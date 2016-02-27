<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' ); ?>

<section class="module-wrapper instagram_snapwidget-module instagram_snapwidget-module-wrapper <?= $module_data[ 'params' ][ 'module_class' ]; ?>">
	
	<?php if ( check_var( $module_data[ 'params' ][ 'show_title' ] ) ) { ?>
	<header class="module-title">
		
		<h3>
			
			<?= $module_data[ 'title' ]; ?>
			
		</h3>
		
	</header><?php
	
	}
	
	?><div class="module-content instagram_snapwidget-module-content layout-<?= url_title( $module_data[ 'params' ][ 'instagram_snapwidget_layout' ] ); ?>">
		
		<div class="instagram_snapwidget-wrapper">
			
			<?= $module_data[ 'params' ][ 'instagram_snapwidget_content' ]; ?>
			
		</div>
		
		<div class="clear">&nbsp;</div>
		
	</div>

</section>
