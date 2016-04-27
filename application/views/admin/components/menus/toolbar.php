<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$full_page_title_alias = $component_name . '_' . $this->component_function . '_' . $this->component_function_action;
echo vui_el_button( array( 'url' => 'admin/' . $component_name . '/menu_types_management/menu_types_list', 'text' => lang( $component_name ) . ' - ' . lang( $full_page_title_alias ), 'icon' => $component_name, 'class' => 'bread-crumb component-name', ) );

echo vui_el_button( array( 'url' => 'admin/' . $component_name . '/menu_types_management/add_menu_type', 'text' => lang( 'new_menu_type' ), 'icon' => 'add-menu', 'only_icon' => TRUE, ) );

echo vui_el_button( array( 'url' => $this->menus->get_mi_url( 'list', array( 'menu_type_id' => isset( $menu_type_id ) ? $menu_type_id : NULL ) ), 'text' => lang( 'menu_items' ), 'icon' => 'menu-items', 'only_icon' => TRUE, ) );

echo vui_el_button( array( 'url' => $this->menus->get_mi_url( 'select_menu_item_type', array( 'menu_type_id' => isset( $menu_type_id ) ? $menu_type_id : NULL ) ), 'text' => lang( 'new_menu_item' ), 'icon' => 'add-menu-item', 'only_icon' => TRUE, ) );

if ( isset( $menu_item ) ) {
	
	echo vui_el_button( array( 'url' => $this->menus->get_mi_url( 'select_menu_item_type', array( 'menu_type_id' => ( isset( $menu_type_id ) ? $menu_type_id : NULL ), 'menu_item' => $menu_item ) ), 'text' => lang( 'swap_menu_item_type' ), 'icon' => 'swap', 'only_icon' => TRUE, ) );
	
}

?>