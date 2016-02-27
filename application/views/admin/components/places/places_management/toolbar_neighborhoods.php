		
		<?php require( dirname(__FILE__) . DS . 'toolbar_cities.php' ); ?><?php
		
		echo vui_el_button( array( 'url' => 'admin/' . $component_name . '/places_management/neighborhoods_list/' . $country->id . '/' . $state->id . '/' . $city->id, 'text' => lang( 'neighborhoods' ), 'icon' => 'places', ) );
		
		echo vui_el_button( array( 'url' => 'admin/' . $component_name . '/places_management/add_neighborhood/' . $country->id . '/' . $state->id . '/' . $city->id, 'text' => lang( 'add_neighborhood' ), 'icon' => 'add', ) );
		
		?>