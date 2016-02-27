<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$full_page_title_alias = $component_name . '_' . $this->component_function . '_' . $this->component_function_action;
echo vui_el_button( array( 'url' => $this->articles->get_a_url( 'list' ), 'text' => lang( $component_name ) . ' - ' . lang( $full_page_title_alias ), 'icon' => $component_name, 'class' => 'bread-crumb component-name', ) );

echo vui_el_button( array( 'url' => $this->articles->get_a_url( 'add' ), 'text' => lang( 'new_article' ), 'icon' => 'add-article', 'only_icon' => TRUE, ) );

echo vui_el_button( array( 'url' => $this->articles->get_c_url( 'list' ), 'text' => lang( 'categories' ), 'icon' => 'categories', 'only_icon' => TRUE, ) );

echo vui_el_button( array( 'url' => $this->articles->get_c_url( 'add' ), 'text' => lang( 'new_category' ), 'icon' => 'add-category', 'only_icon' => TRUE, ) );

echo vui_el_button( array( 'url' => 'admin/' . $component_name . '/component_config/edit_config', 'text' => lang( 'globals_configurations' ), 'icon' => 'config', 'only_icon' => TRUE, ) );

echo vui_el_button( array( 'url' => $this->articles->get_ag_url( 'add' ), 'text' => lang( 'new_gallery' ), 'icon' => 'gallery', 'only_icon' => TRUE, ) );

?>
