		
		<?php require( dirname(__FILE__) . DS . 'toolbar_neighborhoods.php' ); ?><?php
		
		echo vui_el_button( array( 'url' => 'admin/' . $component_name . '/places_management/public_areas_list/' . $country->id . '/' . $state->id . '/' . $city->id . '/' . $neighborhood->id, 'text' => lang( 'public_areas' ), 'icon' => 'places', ) );
		
		echo vui_el_button( array( 'url' => 'admin/' . $component_name . '/places_management/add_public_area/' . $country->id . '/' . $state->id . '/' . $city->id . '/' . $neighborhood->id, 'text' => lang( 'add_public_area' ), 'icon' => 'add', ) );
		
		?>