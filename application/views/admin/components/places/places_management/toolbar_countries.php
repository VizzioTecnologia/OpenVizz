		
		<?php require( dirname(__FILE__) . DS . 'toolbar.php' ); ?><?php
		
		echo vui_el_button( array( 'url' => 'admin/' . $component_name . '/places_management/add_country', 'text' => lang( 'add_country' ), 'icon' => 'add', ) );
		
		?>