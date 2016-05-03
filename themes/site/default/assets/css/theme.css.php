<?php if ( extension_loaded( 'zlib' ) ){ ob_start( 'ob_gzhandler' ); } header ( 'Content-Type: text/css' );

require_once 'theme.header.css.php';

require_once( BASE_PATH . 'application/config/constants.php');
require_once( BASE_PATH . 'application/config/config.php');

define('THEME_IMAGE_DIR_URL', SITE_THEMES_URL . '/' . $template . '/assets/images');
define('THEME_IMAGE_DIR_PATH', SITE_THEMES_PATH . $template . DS . 'assets' . DS . 'images' . DS );
define('THEME_FONTS_DIR_URL', SITE_THEMES_URL . '/' . $template . '/assets/fonts');

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
	//THEME_IMAGE_DIR_URL . '/svg' // uncomment to cache svg images
	
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
// include( 'fonts.css' );
include( 'font_icons.css' );
include( 'html.css' );
include( 'tooltips.css' );

include( 'inputs.css' );
include( 'buttons.css' );
include( 'mimes.css' );

if ( isset( $_GET[ 'fb' ] ) AND $_GET[ 'fb' ] == 1 ) {
	
	include( 'fancybox.css' );
	
}

include( 'pagination.css' );
include( 'modules.css' );
include( 'template.css' );
include( 'articles.css' );
include( 'submit-forms.css' );
include( 'contacts.css' );
include( 'jquery-scrolltop.css' );
include( 'msg.css' );

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
include( 'articles.css.php' );
include( 'vesm.css.php' );
include( 'adjustments.css.php' );
*/
?>

