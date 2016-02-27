<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function admin_theme(){
	
	$CI =& get_instance();
	
	return $CI->config->item('admin_theme');
	
}
function admin_theme_path(){
	
	return ADMIN_THEMES_PATH . admin_theme() . DS;
	
}
function admin_theme_url(){
	
	return ADMIN_THEMES_URL . '/' . admin_theme();
	
}

function site_theme(){
	
	$CI =& get_instance();
	
	return $CI->config->item('site_theme');
	
}
function site_theme_path(){
	
	return SITE_THEMES_PATH . site_theme() . DS;
	
}
function site_theme_url(){
	
	return SITE_THEMES_URL . '/' . site_theme();
	
}



function current_theme( $environment = NULL ){
	
	$CI =& get_instance();
	
	return $CI->config->item( ( $environment ? $environment : $CI->mcm->environment ) . '_theme' );
	
}
function theme_helpers_views_path( $environment = NULL ){
	
	$CI =& get_instance();
	
	return ( $environment ? $environment : $CI->mcm->environment ) . DS . current_theme( $environment ) . DS . VIEWS_DIR_NAME . DS . HELPERS_DIR_NAME . DS;
	
}
function theme_components_views_path( $environment = NULL ){
	
	$CI =& get_instance();
	
	return ( $environment ? $environment : $CI->mcm->environment ) . DS . current_theme( $environment ) . DS . VIEWS_DIR_NAME . DS . COMPONENTS_DIR_NAME . DS;
	
}

function admin_theme_components_views_path(){
	
	$CI =& get_instance();
	
	return ADMIN_DIR_NAME . DS . site_theme() . DS . VIEWS_DIR_NAME . DS . COMPONENTS_DIR_NAME . DS;
	
}
function admin_theme_views_url(){
	
	$CI =& get_instance();
	
	return admin_theme_url() . '/' . VIEWS_DIR_NAME;
	
}
function admin_theme_components_views_url(){
	
	$CI =& get_instance();
	
	return admin_theme_views_url() . '/' . COMPONENTS_DIR_NAME;
	
}

function site_theme_components_views_path(){
	
	$CI =& get_instance();
	
	return SITE_DIR_NAME . DS . site_theme() . DS . VIEWS_DIR_NAME . DS . COMPONENTS_DIR_NAME . DS;
	
}
function site_theme_views_url(){
	
	$CI =& get_instance();
	
	return site_theme_url() . '/' . VIEWS_DIR_NAME;
	
}
function site_theme_components_views_url(){
	
	$CI =& get_instance();
	
	return site_theme_views_url() . '/' . COMPONENTS_DIR_NAME;
	
}



function admin_theme_modules_views_path(){
	
	$CI =& get_instance();
	
	return ADMIN_DIR_NAME . DS . admin_theme() . DS . VIEWS_DIR_NAME . DS . MODULES_DIR_NAME . DS;
	
}
function admin_theme_modules_views_url(){
	
	$CI =& get_instance();
	
	return admin_theme_views_url() . '/' . MODULES_DIR_NAME;
	
}
function site_theme_modules_views_path(){
	
	$CI =& get_instance();
	
	return SITE_DIR_NAME . DS . site_theme() . DS . VIEWS_DIR_NAME . DS . MODULES_DIR_NAME . DS;
	
}
function site_theme_modules_views_url(){
	
	$CI =& get_instance();
	
	return site_theme_views_url() . '/' . MODULES_DIR_NAME;
	
}

function admin_theme_plugins_views_path(){
	
	$CI =& get_instance();
	
	return ADMIN_DIR_NAME . DS . admin_theme() . DS . VIEWS_DIR_NAME . DS . PLUGINS_DIR_NAME . DS;
	
}
function admin_theme_plugins_views_url(){
	
	$CI =& get_instance();
	
	return admin_theme_views_url() . '/' . PLUGINS_DIR_NAME;
	
}
function site_theme_plugins_views_path(){
	
	$CI =& get_instance();
	
	return SITE_DIR_NAME . DS . site_theme() . DS . VIEWS_DIR_NAME . DS . PLUGINS_DIR_NAME . DS;
	
}
function site_theme_plugins_views_url(){
	
	$CI =& get_instance();
	
	return site_theme_views_url() . '/' . PLUGINS_DIR_NAME;
	
}




/* End of file themes_helper.php */
/* Location: ./application/helpers/themes_helper.php */