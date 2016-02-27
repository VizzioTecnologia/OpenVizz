<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$full_page_title_alias = $component_name . '_' . $this->component_function . '_' . $this->component_function_action;
echo vui_el_button( array( 'url' => 'admin/' . $component_name . '/customers_management/customers_list', 'text' => lang( $component_name ) . ' - ' . lang( $full_page_title_alias ), 'icon' => $component_name, 'class' => 'bread-crumb component-name', ) );

echo vui_el_button( array( 'url' => 'admin/' . $component_name . '/customers_management/add_customer', 'text' => lang( 'add_customer' ), 'icon' => 'add-customer', 'only_icon' => TRUE, ) );

echo vui_el_button( array( 'url' => 'admin/' . $component_name . '/categories_management/categories_list', 'text' => lang( 'categories' ), 'icon' => 'categories', 'only_icon' => TRUE, ) );

echo vui_el_button( array( 'url' => 'admin/' . $component_name . '/categories_management/add_category', 'text' => lang( 'add_category' ), 'icon' => 'add-category', 'only_icon' => TRUE, ) );

?>
	