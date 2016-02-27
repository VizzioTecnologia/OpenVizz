<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$full_page_title_alias = $component_name . '_' . $this->component_function . '_' . $this->component_function_action;
echo vui_el_button( array( 'url' => 'admin/' . $component_name . '/mm/a/ml', 'text' => lang( $component_name ) . ' - ' . lang( $full_page_title_alias ), 'icon' => $component_name, 'class' => 'bread-crumb component-name', ) );

echo vui_el_button( array( 'url' => $add_link, 'text' => lang( 'add_module' ), 'icon' => 'add', 'only_icon' => TRUE, ) );

echo vui_el_button( array( 'url' => 'admin/' . $component_name . '/component_config/edit_config', 'text' => lang( 'globals_configurations' ), 'icon' => 'config', 'only_icon' => TRUE, ) );

?>
	