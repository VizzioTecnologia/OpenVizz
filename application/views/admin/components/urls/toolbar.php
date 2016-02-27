<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="search-toolbar-wrapper fr">
	
	<?= form_open( get_url( $urls_search_link ) ); ?>
	
	<div class="vui-field-wrapper-inline">
		
		<?= form_input(array( 'id'=>'terms','placeholder'=>lang('search'), 'name'=>'terms', 'class'=>'search-terms', 'title'=>lang('tip_terms') ), isset($search['terms']) ? $search['terms'] : ''); ?>
		
	</div>
	<div class="vui-field-wrapper-inline">
		
		<?= vui_el_button( array( 'text' => lang( 'search' ), 'icon' => 'search', 'only_icon' => TRUE, 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_search', 'id' => 'submit-search', ) ); ?>
		
		<?php if ( $component_function_action == 's' ){ ?>
		
		<?= vui_el_button( array( 'text' => lang( 'cancel_search' ), 'icon' => 'cancel', 'only_icon' => TRUE, 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_cancel_search', 'id' => 'submit-cancel-search', ) ); ?>
		
		<?php } ?>
		
	</div>
	
	<?= form_close(); ?>
	
	<div class="clear"></div>
	
</div>

<?php

$full_page_title_alias = $component_name . '_' . $this->component_function . '_' . $this->component_function_action;
echo vui_el_button( array( 'url' => $urls_list_link, 'text' => lang( $component_name ) . ' - ' . lang( $full_page_title_alias ), 'icon' => $component_name, 'class' => 'bread-crumb component-name', ) );

echo vui_el_button( array( 'url' => $add_link, 'text' => lang( 'add_url' ), 'icon' => 'add', 'only_icon' => TRUE, ) );

echo vui_el_button( array( 'url' => 'admin/' . $component_name . '/component_config/edit_config', 'text' => lang( 'globals_configurations' ), 'icon' => 'config', 'only_icon' => TRUE, ) );
echo vui_el_button( array( 'url' => $delete_all_urls_link, 'text' => lang( 'remove_all_urls' ), 'icon' => 'remove', 'only_icon' => TRUE, ) );

?>
	