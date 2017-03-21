<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$full_page_title_alias = $component_name . '_' . $this->component_function . '_' . $this->component_function_action;
echo vui_el_button( array( 'url' => 'admin/' . $component_name . '/contacts_management/contacts_list', 'text' => lang( $component_name ) . ' - ' . lang( $full_page_title_alias ), 'icon' => $component_name, 'class' => 'bread-crumb component-name', ) );

echo vui_el_button( array( 'url' => 'admin/' . $component_name . '/contacts_management/add_contact', 'text' => lang( 'new_contact' ), 'icon' => 'add', 'only_icon' => TRUE, ) );

//echo vui_el_button( array( 'url' => 'admin/' . $component_name . '/contacts_management/google_contacts', 'icon' => 'google', 'text' => lang( 'google_contacts' ), 'only_icon' => TRUE, ) );

?>

<div class="search-toolbar-wrapper fr">
	
	<?php
		
		$search_box_params = array(
			
			'url' => get_url( 'admin/' . $component_name . '/'. $component_function .'/search' ),
			'terms' => isset( $search[ 'terms' ] ) ? $search[ 'terms' ] : '',
			'wrapper_class' => 'search-toolbar-wrapper',
			'name' => 'terms',
			'cancel_url' => get_url( 'admin/' . $component_name . '/'. $component_function .'/search' ),
			'live_search_url' => $this->contacts_model->get_component_url_admin() . '/ajax/live_search?q=',
			'live_search_min_length' => 2,
			
		);
		
		echo vui_el_search( $search_box_params );
		
	?>
	
	<div class="clear"></div>
	
</div>

<?php if ( $this->plugins->load( 'viacms_live_search' ) ){ ?>

<script type="text/javascript">
	
	$( document ).bind( 'ready', function(){
		
	});
	
</script>

<?php } ?>
	