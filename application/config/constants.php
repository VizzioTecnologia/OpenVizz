<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
if ( ! defined( 'FILE_READ_MODE' ) ) define( 'FILE_READ_MODE', 0644 );
if ( ! defined( 'FILE_WRITE_MODE' ) ) define( 'FILE_WRITE_MODE', 0666 );
if ( ! defined( 'DIR_READ_MODE' ) ) define( 'DIR_READ_MODE', 0755 );
if ( ! defined( 'DIR_WRITE_MODE' ) ) define( 'DIR_WRITE_MODE', 0777 );

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

if ( ! defined( 'DS' ) ) define( 'DS',																			DIRECTORY_SEPARATOR );
if ( ! defined( 'APPPATH' ) ) define( 'APPPATH',																FCPATH . 'application' . DS );

$base_path = explode( DS, $_SERVER[ "SCRIPT_FILENAME" ] );
unset( $base_path[ count( $base_path ) - 1 ] );
$base_path = join( DS, $base_path ) . DS;

if ( ! defined( 'HOST' ) ) define( 'HOST',																		$_SERVER[ 'SERVER_NAME' ] );

if ( ! defined( 'SERVER_PORT' ) ) define( 'SERVER_PORT',														$_SERVER[ 'SERVER_PORT' ] == '80' ? '' : $_SERVER[ 'SERVER_PORT' ] );
if( isset( $_SERVER[ 'HTTPS' ] ) && $_SERVER[ 'HTTPS' ] == 'on' ) { 
	
	if ( ! defined( 'PROTOCOL' ) ) define( 'PROTOCOL',															'https' );
	
} else { 
    
	if ( ! defined( 'PROTOCOL' ) ) define( 'PROTOCOL',															'http' );
	
}
if ( ! defined( 'RELATIVE_BASE_URL' ) ) define( 'RELATIVE_BASE_URL',											rtrim( str_replace( 'index.php', '', $_SERVER[ 'PHP_SELF' ] ), '/' ) );

if ( ! defined( 'BASE_PATH' ) ) define( 'BASE_PATH',															$base_path );

if ( file_exists( BASE_PATH . 'application' . DS . 'config' . DS . 'host.php' ) ){
	
	//require_once BASE_PATH . 'application' . DS . 'config' . DS . 'host.php';
	
}

if ( ! defined( 'HTTP_HOST' ) ) define( 'HTTP_HOST',															PROTOCOL . '://' . HOST . ( SERVER_PORT ? ':' . SERVER_PORT : '' ) );
if ( ! defined( 'BASE_URL' ) ) define( 'BASE_URL',																HTTP_HOST . RELATIVE_BASE_URL );
//if ( ! defined( 'BASE_URL' ) ) define( 'BASE_URL',																HOST . RELATIVE_BASE_URL );

if ( ! defined( 'APPURL' ) ) define( 'APPURL',																	BASE_URL . '/application' );

if ( ! defined( 'BASE_APP_INCLUDE_PATH' ) ) define( 'BASE_APP_INCLUDE_PATH',									BASE_PATH );

if ( ! defined( 'FOPEN_READ' ) ) define( 'FOPEN_READ',															'rb');
if ( ! defined( 'FOPEN_READ_WRITE' ) ) define( 'FOPEN_READ_WRITE',												'r+b');
if ( ! defined( 'FOPEN_WRITE_CREATE_DESTRUCTIVE' ) ) define( 'FOPEN_WRITE_CREATE_DESTRUCTIVE',					'wb'); // truncates existing file data, use with care
if ( ! defined( 'FOPEN_READ_WRITE_CREATE_DESTRUCTIVE' ) ) define( 'FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',		'w+b'); // truncates existing file data, use with care
if ( ! defined( 'FOPEN_WRITE_CREATE' ) ) define( 'FOPEN_WRITE_CREATE',											'ab');
if ( ! defined( 'FOPEN_READ_WRITE_CREATE' ) ) define( 'FOPEN_READ_WRITE_CREATE',								'a+b');
if ( ! defined( 'FOPEN_WRITE_CREATE_STRICT' ) ) define( 'FOPEN_WRITE_CREATE_STRICT',							'xb');
if ( ! defined( 'FOPEN_READ_WRITE_CREATE_STRICT' ) ) define( 'FOPEN_READ_WRITE_CREATE_STRICT',					'x+b');




if ( ! defined( 'TMP_PATH' ) ) define( 'TMP_PATH',																APPPATH . 'tmp' . DS );
if ( ! defined( 'COMPONENTS_ALIAS' ) ) define( 'COMPONENTS_ALIAS',												'components' );
if ( ! defined( 'COMPONENTS_DIR_NAME' ) ) define( 'COMPONENTS_DIR_NAME',										COMPONENTS_ALIAS );
if ( ! defined( 'COMPONENTS_PATH' ) ) define( 'COMPONENTS_PATH',												APPPATH . 'controllers' . DS );
if ( ! defined( 'ASSETS_DIR_NAME' ) ) define( 'ASSETS_DIR_NAME',												'assets' );
if ( ! defined( 'ASSETS_PATH' ) ) define( 'ASSETS_PATH',														FCPATH . ASSETS_DIR_NAME . DS );
if ( ! defined( 'ASSETS_URL' ) ) define( 'ASSETS_URL',															BASE_URL . '/' . ASSETS_DIR_NAME );
if ( ! defined( 'MEDIA_DIR_NAME' ) ) define( 'MEDIA_DIR_NAME',													'media' );
if ( ! defined( 'MEDIA_DIR_URL' ) ) define( 'MEDIA_DIR_URL',													BASE_URL . '/' . MEDIA_DIR_NAME );
if ( ! defined( 'MEDIA_PATH' ) ) define( 'MEDIA_PATH',															FCPATH . MEDIA_DIR_NAME . DS );

if ( ! defined( 'THUMBS_DIR_NAME' ) ) define( 'THUMBS_DIR_NAME',												'thumbs' );
if ( ! defined( 'THUMBS_DIR_URL' ) ) define( 'THUMBS_DIR_URL',													BASE_URL . '/' . THUMBS_DIR_NAME );
if ( ! defined( 'THUMBS_PATH' ) ) define( 'THUMBS_PATH',														FCPATH . THUMBS_DIR_NAME . DS );

if ( ! defined( 'LANG_DIR_NAME' ) ) define( 'LANG_DIR_NAME',													'language' );
if ( ! defined( 'LANG_DIR_URL' ) ) define( 'LANG_DIR_URL',														APPURL . '/' . LANG_DIR_NAME );
if ( ! defined( 'LANG_PATH' ) ) define( 'LANG_PATH',															APPPATH . LANG_DIR_NAME . DS );

if ( ! defined( 'LIBRARIES_DIR_NAME' ) ) define( 'LIBRARIES_DIR_NAME',											'libraries' );
if ( ! defined( 'LIBRARIES_DIR_URL' ) ) define( 'LIBRARIES_DIR_URL',											BASE_URL . '/' . LIBRARIES_DIR_NAME );
if ( ! defined( 'LIBRARIES_PATH' ) ) define( 'LIBRARIES_PATH',													APPPATH . LIBRARIES_DIR_NAME . DS );

if ( ! defined( 'MODULES_ALIAS' ) ) define( 'MODULES_ALIAS',													'modules' );
if ( ! defined( 'MODULES_DIR_NAME' ) ) define( 'MODULES_DIR_NAME',												MODULES_ALIAS );
if ( ! defined( 'MODULES_PATH' ) ) define( 'MODULES_PATH',														APPPATH . 'models' . DS . MODULES_DIR_NAME . DS );

if ( ! defined( 'PLUGINS_ALIAS' ) ) define( 'PLUGINS_ALIAS',													'plugins' );
if ( ! defined( 'PLUGINS_DIR_NAME' ) ) define( 'PLUGINS_DIR_NAME',												PLUGINS_ALIAS );
if ( ! defined( 'PLUGINS_PATH' ) ) define( 'PLUGINS_PATH',														APPPATH . PLUGINS_DIR_NAME . DS );

if ( ! defined( 'STYLES_DIR_NAME' ) ) define( 'STYLES_DIR_NAME',												'css' );
if ( ! defined( 'STYLES_DIR_URL' ) ) define( 'STYLES_DIR_URL',													ASSETS_URL . '/' . STYLES_DIR_NAME );
if ( ! defined( 'STYLES_PATH' ) ) define( 'STYLES_PATH',														ASSETS_PATH . STYLES_DIR_NAME . DS );
if ( ! defined( 'IMAGES_DIR_NAME' ) ) define( 'IMAGES_DIR_NAME',												'images' );
if ( ! defined( 'JS_DIR_NAME' ) ) define( 'JS_DIR_NAME',														'js' );
if ( ! defined( 'JS_DIR_URL' ) ) define( 'JS_DIR_URL',															ASSETS_URL . '/' . JS_DIR_NAME );
if ( ! defined( 'JS_PATH' ) ) define( 'JS_PATH',																BASE_APP_INCLUDE_PATH . ASSETS_PATH . JS_DIR_NAME . DS );

if ( ! defined( 'VIEWS_DIR_NAME' ) ) define( 'VIEWS_DIR_NAME',													'views' );
if ( ! defined( 'OTHERS_VIEWS_DIR_NAME' ) ) define( 'OTHERS_VIEWS_DIR_NAME',									'others' );
if ( ! defined( 'VIEWS_PATH' ) ) define( 'VIEWS_PATH',															APPPATH . VIEWS_DIR_NAME . DS );
if ( ! defined( 'VIEWS_STYLES_PATH' ) ) define( 'VIEWS_STYLES_PATH',											STYLES_PATH . DS . VIEWS_DIR_NAME . DS );
if ( ! defined( 'VIEWS_STYLES_URL' ) ) define( 'VIEWS_STYLES_URL',												STYLES_DIR_URL . '/' . VIEWS_DIR_NAME );


if ( ! defined( 'MODULES_VIEWS_PATH' ) ) define( 'MODULES_VIEWS_PATH',											VIEWS_PATH . MODULES_DIR_NAME . DS );
if ( ! defined( 'MODULES_VIEWS_STYLES_PATH' ) ) define( 'MODULES_VIEWS_STYLES_PATH',							STYLES_PATH . DS . MODULES_DIR_NAME . DS );
if ( ! defined( 'MODULES_VIEWS_STYLES_URL' ) ) define( 'MODULES_VIEWS_STYLES_URL',								STYLES_DIR_URL . '/' . VIEWS_DIR_NAME . '/' . MODULES_DIR_NAME );


if ( ! defined( 'HELPERS_DIR_NAME' ) ) define( 'HELPERS_DIR_NAME',												'helpers' );
if ( ! defined( 'HELPERS_STYLES_PATH' ) ) define( 'HELPERS_STYLES_PATH',										STYLES_PATH . DS . HELPERS_DIR_NAME . DS );
if ( ! defined( 'HELPERS_STYLES_URL' ) ) define( 'HELPERS_STYLES_URL',											STYLES_DIR_URL . '/' . HELPERS_DIR_NAME );

if ( ! defined( 'THEMES_DIR_NAME' ) ) define( 'THEMES_DIR_NAME',												'themes');
if ( ! defined( 'THEMES_PATH' ) ) define( 'THEMES_PATH',														FCPATH . THEMES_DIR_NAME . DS );
if ( ! defined( 'THEMES_URL' ) ) define( 'THEMES_URL',															BASE_URL . '/' . THEMES_DIR_NAME );

if ( ! defined( 'COMPONENTS_IMAGES_URL' ) ) define( 'COMPONENTS_IMAGES_URL',									ASSETS_DIR_NAME . '/' . IMAGES_DIR_NAME . '/' . COMPONENTS_DIR_NAME );

if ( ! defined( 'PARAM_PREFIX' ) ) define( 'PARAM_PREFIX',														'params' );

if ( ! defined( 'DOCUMENTS_ALIAS' ) ) define( 'DOCUMENTS_ALIAS',												'documents' );
if ( ! defined( 'DOCUMENTS_DIR_NAME' ) ) define( 'DOCUMENTS_DIR_NAME',											DOCUMENTS_ALIAS );
if ( ! defined( 'DOCUMENTS_PATH' ) ) define( 'DOCUMENTS_PATH',													FCPATH . DOCUMENTS_DIR_NAME . DS );
if ( ! defined( 'COMPONENTS_DOCUMENTS_PATH' ) ) define( 'COMPONENTS_DOCUMENTS_PATH',							DOCUMENTS_PATH . COMPONENTS_DIR_NAME . DS );
if ( ! defined( 'DOCUMENTS_URL' ) ) define( 'DOCUMENTS_URL',													BASE_URL . '/' . DOCUMENTS_DIR_NAME );
if ( ! defined( 'COMPONENTS_DOCUMENTS_URL' ) ) define( 'COMPONENTS_DOCUMENTS_URL',								DOCUMENTS_URL . '/' . COMPONENTS_DIR_NAME );


/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

if ( ! defined( 'ADMIN_ALIAS' ) ) define( 'ADMIN_ALIAS',														'admin');
if ( ! defined( 'ADMIN_DIR_NAME' ) ) define( 'ADMIN_DIR_NAME',													ADMIN_ALIAS);
if ( ! defined( 'ADMIN_COMPONENTS_PATH' ) ) define( 'ADMIN_COMPONENTS_PATH',									COMPONENTS_PATH . ADMIN_DIR_NAME . DS );
if ( ! defined( 'ADMIN_COMPONENTS_LOAD_VIEWS_PATH' ) ) define( 'ADMIN_COMPONENTS_LOAD_VIEWS_PATH',				ADMIN_DIR_NAME . DS . COMPONENTS_DIR_NAME . DS );
if ( ! defined( 'ADMIN_COMPONENTS_VIEWS_PATH' ) ) define( 'ADMIN_COMPONENTS_VIEWS_PATH',						VIEWS_PATH . ADMIN_COMPONENTS_LOAD_VIEWS_PATH );
if ( ! defined( 'ADMIN_MODULES_VIEWS_PATH' ) ) define( 'ADMIN_MODULES_VIEWS_PATH',								ADMIN_DIR_NAME . DS . MODULES_DIR_NAME . DS );
if ( ! defined( 'ADMIN_MODULES_VIEWS_STYLES_PATH' ) ) define( 'ADMIN_MODULES_VIEWS_STYLES_PATH',				STYLES_PATH . VIEWS_DIR_NAME . DS . ADMIN_DIR_NAME . DS . ADMIN_MODULES_VIEWS_PATH . DS );
if ( ! defined( 'ADMIN_MODULES_VIEWS_STYLES_URL' ) ) define( 'ADMIN_MODULES_VIEWS_STYLES_URL',					STYLES_DIR_URL . '/' . VIEWS_DIR_NAME . '/' . ADMIN_DIR_NAME . '/' . MODULES_DIR_NAME );

if ( ! defined( 'ADMIN_THEMES_PATH' ) ) define( 'ADMIN_THEMES_PATH',											THEMES_PATH . ADMIN_DIR_NAME . DS );
if ( ! defined( 'ADMIN_THEMES_URL' ) ) define( 'ADMIN_THEMES_URL',												THEMES_URL . '/' . ADMIN_DIR_NAME );

/*
|--------------------------------------------------------------------------
| Site
|--------------------------------------------------------------------------
*/

if ( ! defined( 'SITE_ALIAS' ) ) define( 'SITE_ALIAS',															'site');
if ( ! defined( 'SITE_DIR_NAME' ) ) define( 'SITE_DIR_NAME',													SITE_ALIAS);
if ( ! defined( 'SITE_COMPONENTS_PATH' ) ) define( 'SITE_COMPONENTS_PATH',										COMPONENTS_PATH );
if ( ! defined( 'SITE_COMPONENTS_VIEWS_PATH' ) ) define( 'SITE_COMPONENTS_VIEWS_PATH',							SITE_DIR_NAME . DS . COMPONENTS_DIR_NAME . DS );
if ( ! defined( 'SITE_COMPONENTS_VIEWS_STYLES_PATH' ) ) define( 'SITE_COMPONENTS_VIEWS_STYLES_PATH',			STYLES_PATH . VIEWS_DIR_NAME . DS . SITE_DIR_NAME . DS . COMPONENTS_DIR_NAME . DS );
if ( ! defined( 'SITE_COMPONENTS_VIEWS_STYLES_URL' ) ) define( 'SITE_COMPONENTS_VIEWS_STYLES_URL',				STYLES_DIR_URL . '/' . VIEWS_DIR_NAME . '/' . SITE_DIR_NAME . '/' . COMPONENTS_DIR_NAME );
if ( ! defined( 'SITE_MODULES_VIEWS_PATH' ) ) define( 'SITE_MODULES_VIEWS_PATH',								SITE_DIR_NAME . DS . MODULES_DIR_NAME . DS );
if ( ! defined( 'SITE_MODULES_VIEWS_STYLES_PATH' ) ) define( 'SITE_MODULES_VIEWS_STYLES_PATH',					STYLES_PATH . VIEWS_DIR_NAME . DS . SITE_DIR_NAME . DS . SITE_MODULES_VIEWS_PATH . DS );
if ( ! defined( 'SITE_MODULES_VIEWS_STYLES_URL' ) ) define( 'SITE_MODULES_VIEWS_STYLES_URL',					STYLES_DIR_URL . '/' . VIEWS_DIR_NAME . '/' . SITE_DIR_NAME . '/' . MODULES_DIR_NAME );

if ( ! defined( 'SITE_THEMES_PATH' ) ) define( 'SITE_THEMES_PATH',												THEMES_PATH . SITE_DIR_NAME . DS );
if ( ! defined( 'SITE_THEMES_URL' ) ) define( 'SITE_THEMES_URL',												THEMES_URL . '/' . SITE_DIR_NAME );


/* End of file constants.php */
/* Location: ./application/config/constants.php */