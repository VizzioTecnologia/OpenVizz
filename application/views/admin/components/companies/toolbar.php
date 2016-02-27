<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$full_page_title_alias = $component_name . '_' . $this->component_function . '_' . $this->component_function_action;
echo vui_el_button( array( 'url' => 'admin/' . $component_name . '/companies_management/companies_list', 'text' => lang( $component_name ) . ' - ' . lang( $full_page_title_alias ), 'icon' => $component_name, 'class' => 'bread-crumb component-name', ) );

echo vui_el_button( array( 'url' => 'admin/' . $component_name . '/companies_management/add_company', 'text' => lang( 'add_company' ), 'icon' => 'add-company', 'only_icon' => TRUE, ) );

?>
	