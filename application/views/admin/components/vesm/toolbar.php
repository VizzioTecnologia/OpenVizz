		
		<h1 class="component-name"><?= vui_el_button( array( 'url' => 'admin/' . $component_name . '/tenders_management/tenders_list', 'text' => lang( $component_name ), 'icon' => $component_name, ) ); ?></h1><?php
		
		echo vui_el_button( array( 'url' =>  'admin/' . $component_name . '/tenders_management/add_tender', 'text' => lang( 'new_tender' ), 'icon' => 'newdocument', 'only_icon' => TRUE, ) );
		
		echo vui_el_button( array( 'url' =>  'admin/' . $component_name . '/products_management/products_list', 'text' => lang( 'products' ), 'icon' => 'products', 'only_icon' => TRUE, ) );
		
		echo vui_el_button( array( 'url' =>  'admin/' . $component_name . '/products_management/add_product', 'text' => lang( 'add_product' ), 'icon' => 'add-product', 'only_icon' => TRUE, ) );
		
		echo vui_el_button( array( 'url' =>  'admin/' . $component_name . '/component_config/edit_tender_config', 'text' => lang( 'globals_configurations' ), 'icon' => 'config', 'only_icon' => TRUE, ) );
		
		?>
		