<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$component_name = $this->component_name;

$full_page_title_alias = $component_name . '_' . $this->component_function . '_' . $this->component_function_action;
echo vui_el_button( array( 'url' => 'admin/' . $component_name . '/um/a/ul', 'text' => lang( $component_name ) . ' - ' . lang( $full_page_title_alias ), 'icon' => $component_name, 'class' => 'bread-crumb component-name', ) );

echo vui_el_button( array( 'url' => 'admin/' . $component_name . '/um/a/a', 'text' => lang( 'new_user' ), 'icon' => 'add-user', 'only_icon' => TRUE, ) );

echo vui_el_button( array( 'url' => 'admin/' . $component_name . '/users_groups_management/users_groups_list', 'text' => lang( 'users_groups' ), 'icon' => 'categories', 'only_icon' => TRUE, ) );

echo vui_el_button( array( 'url' => 'admin/' . $component_name . '/users_groups_management/add_users_group', 'text' => lang( 'new_users_group' ), 'icon' => 'add-category', 'only_icon' => TRUE, ) );

echo vui_el_button( array( 'url' => 'admin/' . $component_name . '/component_config/edit_config', 'text' => lang( 'global_config' ), 'icon' => 'config', 'only_icon' => TRUE, ) );

?>

<?php //echo anchor('admin/'.$component_name.'/preferences/edit/'.$layout,lang('preferences'),'class="tb-btn tb-btn-preferences" title="'.lang('preferences').'"'); ?>
