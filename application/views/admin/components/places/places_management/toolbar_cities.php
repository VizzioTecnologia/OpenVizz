		
		<?php require( dirname(__FILE__) . DS . 'toolbar_states.php' ); ?><?php
		
		echo vui_el_button( array( 'url' => 'admin/' . $component_name . '/places_management/cities_list/' . $country->id . '/' . $state->id, 'text' => lang( 'cities' ), 'icon' => 'places', ) );
		
		echo vui_el_button( array( 'url' => 'admin/' . $component_name . '/places_management/add_city/' . $country->id . '/' . $state->id, 'text' => lang( 'add_city' ), 'icon' => 'add', ) );
		
		?>