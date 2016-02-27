<?php if(extension_loaded('zlib')){ob_start('ob_gzhandler');} header ('Content-Type: text/css');

define('SELF', pathinfo( __FILE__, PATHINFO_BASENAME ) );

if ( ! defined( 'DS' ) ) define( 'DS', DIRECTORY_SEPARATOR );

$base_path = $template_path = explode( DS, $_SERVER[ "SCRIPT_FILENAME" ] );

for ( $i = 1; $i <= 6; $i++ ){

	unset( $base_path[ count( $base_path ) - 1 ] );

}

for ( $i = 1; $i <= 3; $i++ ){
	
	unset( $template_path[ count( $template_path ) - 1 ] );
	
}

$template = $template_path[ count( $template_path ) - 1 ];

$base_path = join( DS, $base_path ) . DS;

if ( ! defined( 'BASE_PATH' ) ) define( 'BASE_PATH', $base_path );
if ( ! defined( 'FCPATH' ) ) define( 'FCPATH', BASE_PATH );
if ( ! defined( 'BASEPATH' ) ) define( 'BASEPATH', TRUE );



//---------------------------
// Defining the base url

if ( ! defined( 'HOST' ) ) define( 'HOST',																		$_SERVER[ 'SERVER_NAME' ] );

if ( ! defined( 'SERVER_PORT' ) ) define( 'SERVER_PORT',														$_SERVER[ 'SERVER_PORT' ] == '80' ? '' : $_SERVER[ 'SERVER_PORT' ] );
if( isset( $_SERVER[ 'HTTPS' ] ) && $_SERVER[ 'HTTPS' ] == 'on' ) { 

if ( ! defined( 'PROTOCOL' ) ) define( 'PROTOCOL',															'https' );

} else { 

if ( ! defined( 'PROTOCOL' ) ) define( 'PROTOCOL',															'http' );

}


if ( ! defined( 'RELATIVE_BASE_URL' ) ) define( 'RELATIVE_BASE_URL',											rtrim( str_replace( '/themes/admin/' . $template . '/assets/css/theme.css.php', '', $_SERVER[ 'PHP_SELF' ] ), '/' ) );

if ( ! defined( 'HTTP_HOST' ) ) define( 'HTTP_HOST',															PROTOCOL . '://' . HOST . ( SERVER_PORT ? ':' . SERVER_PORT : '' ) );
if ( ! defined( 'BASE_URL' ) ) define( 'BASE_URL',																HTTP_HOST . RELATIVE_BASE_URL );

// Defining the base url
//---------------------------




require_once( BASE_PATH . 'application/config/constants.php');
require_once( BASE_PATH . 'application/config/config.php');


define('THEME_IMAGE_DIR_URL', ADMIN_THEMES_URL . '/' . $template . '/assets/images');
define('THEME_IMAGE_DIR_PATH', ADMIN_THEMES_PATH . $template . DS . 'assets' . DS . 'images' . DS );
define('THEME_FONTS_DIR_URL', ADMIN_THEMES_URL . '/' . $template . '/assets/fonts');

/*
 **************************************************************************************************
 **************************************************************************************************
 --------------------------------------------------------------------------------------------------
 VUI - Via CMS UI
 --------------------------------------------------------------------------------------------------
 */

set_include_path( get_include_path() . PATH_SEPARATOR . LIBRARIES_PATH );
require_once 'vui' . DS . 'vui.php';

$vui = new Vui(
	
	THEME_IMAGE_DIR_PATH . 'svg' . DS,
	THEME_IMAGE_DIR_PATH . 'svg' . DS . 'colors.svg'
	//THEME_IMAGE_DIR_URL . '/svg'
	
);
/*
 --------------------------------------------------------------------------------------------------
 VUI - Via CMS UI
 --------------------------------------------------------------------------------------------------
 **************************************************************************************************
 **************************************************************************************************
 */

include( 'defs.php' );
include( 'reset.css' );
include( 'font_icons.css' );
include( 'html.css' );
include( 'tooltips.css' );

if ( isset( $_GET[ 'juia' ] ) AND $_GET[ 'juia' ] ) {
	
	include( 'jquery.ui.all.css' );
	
}

include( 'menus.css' );

include( 'inputs.css' );
include( 'buttons.css' );

include( 'modal.css' );

include( 'pagination.css' );
include( 'params.css' );
include( 'modules.css' );
include( 'articles.css' );
include( 'submit-forms.css' );
include( 'contacts.css' );
include( 'jquery-scrolltop.css' );
include( 'msg.css' );
include( 'search.css' );
include( 'tabs.css' );
include( 'tinymce.css' );
include( 'main-tools.css' );
include( 'toolbar.css' );
include( 'theme.css' );
include( 'adjustments.css' );

/*
include( 'html.css.php' );
include( 'template.css.php' );

include( 'params.css.php' );
include( 'info_cards.css.php' );
include( 'tabs.css.php' );
include( 'modals.css.php' );
include( 'search.css.php' );
include( 'buttons.css.php' );
include( 'jquery_switch.css.php' );


include( 'jquery_ui.css.php' );
include( 'qtip2.css.php' );
include( 'tinymce.css.php' );


include( 'dashboard.css.php' );
include( 'vesm.css.php' );
include( 'adjustments.css.php' );
*/
?>

