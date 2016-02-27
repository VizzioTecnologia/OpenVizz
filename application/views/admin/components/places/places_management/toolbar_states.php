		
		<?php require( dirname(__FILE__) . DS . 'toolbar_countries.php' ); ?><?php
		
		echo vui_el_button( array( 'url' => 'admin/' . $component_name . '/places_management/states_list/' . $country->id, 'text' => lang( 'states' ), 'icon' => 'places', ) );
		
		echo vui_el_button( array( 'url' => 'admin/' . $component_name . '/places_management/add_state/' . $country->id, 'text' => lang( 'add_state' ), 'icon' => 'add', ) );
		
		?>