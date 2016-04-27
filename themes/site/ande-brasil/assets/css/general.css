<?php if(extension_loaded('zlib')){ob_start('ob_gzhandler');} header ('Content-Type: text/css');

define('SELF', pathinfo( __FILE__, PATHINFO_BASENAME ) );

$fcpath = array_reverse( explode( DIRECTORY_SEPARATOR, trim( str_replace( SELF, '', __FILE__ ), DIRECTORY_SEPARATOR ) ) );

unset( $fcpath[ 4 ] );
unset( $fcpath[ 3 ] );
unset( $fcpath[ 2 ] );
unset( $fcpath[ 1 ] );
unset( $fcpath[ 0 ] );

$fcpath = DIRECTORY_SEPARATOR . join( DIRECTORY_SEPARATOR, array_reverse( $fcpath ) ) . DIRECTORY_SEPARATOR;

define( 'FCPATH', $fcpath );
define( 'BASEPATH', TRUE );

require_once('../../../../../application/config/host.php');
require_once('../../../../../application/config/constants.php');

define('THEME_IMAGE_DIR_URL', ADMIN_THEMES_URL . '/default/assets/images');

/*
 **************************************************************************************************
 **************************************************************************************************
 --------------------------------------------------------------------------------------------------
 VUI - Via CMS UI
 --------------------------------------------------------------------------------------------------
 */

set_include_path( get_include_path() . PATH_SEPARATOR . LIBRARIES_PATH );
require_once 'vui' . DS . 'vui.php';
require_once 'vui' . DS . 'vui_css.php';
$vui = new Vui( THEME_IMAGE_DIR_URL . '/svg/' );
$vui_css = new Vui_css();

if ( ! defined( 'VUI_SPACING' ) ) define( 'VUI_SPACING', 15 );
if ( ! defined( 'VUI_FONT_COLOR' ) ) define( 'VUI_FONT_COLOR', $vui->colors->vui_darker->hex_s );
if ( ! defined( 'VUI_BORDER' ) ) define( 'VUI_BORDER', '1px solid ' . $vui->colors->vui_extra_3->rgba_s( 30 ) );
if ( ! defined( 'VUI_BORDER_LIGHT' ) ) define( 'VUI_BORDER_LIGHT', '1px solid ' . $vui->colors->vui_lighter->rgba_s( 200 ) );
if ( ! defined( 'VUI_BORDER_RADIUS' ) ) define( 'VUI_BORDER_RADIUS', '5px' );

/*
 --------------------------------------------------------------------------------------------------
 VUI - Via CMS UI
 --------------------------------------------------------------------------------------------------
 **************************************************************************************************
 **************************************************************************************************
 */

/*
 **************************************************************************************************
 **************************************************************************************************
 --------------------------------------------------------------------------------------------------
 Colors schemes
 --------------------------------------------------------------------------------------------------
 */



$color_1_rgb = '42, 37, 37';
$color_2_rgb = '57, 48, 48';
$color_3_rgb = '93, 78, 78';
$color_4_rgb = '135, 124, 124';
$color_5_rgb = '128, 117, 117';
$color_6_rgb = '238, 236, 234';
$color_7_rgb = '247, 247, 245';

$color_8_rgb = '156, 48, 48';
$color_9_rgb = '173, 48, 48';
$color_10_rgb = '200, 55, 55';

$color_11_rgb = '0, 0, 0';
$color_12_rgb = '255, 255, 255';

$color_13_rgb = '255, 238, 170';
$color_14_rgb = '249, 218, 152';
$color_15_rgb = '237, 227, 156';
$color_16_rgb = '82, 130, 33';
$color_17_rgb = '78, 128, 29';

$color_18_rgb = '255, 149, 149';

$color_19_rgb = '221, 218, 214';













define( 'COLOR_G1_1', 'rgb(' . $color_1_rgb . ')' );
define( 'COLOR_G1_2', 'rgb(' . $color_2_rgb . ')' );
define( 'COLOR_G1_3', 'rgb(' . $color_3_rgb . ')' );
define( 'COLOR_G1_4', 'rgb(' . $color_4_rgb . ')' );
define( 'COLOR_G1_5', 'rgb(' . $color_5_rgb . ')' );
define( 'COLOR_G1_6', 'rgb(' . $color_6_rgb . ')' );
define( 'COLOR_G1_7', 'rgb(' . $color_7_rgb . ')' );
define( 'COLOR_G1_8', 'rgb(' . $color_19_rgb . ')' );

define( 'COLOR_G2_1', 'rgb(' . $color_8_rgb . ')' );
define( 'COLOR_G2_2', 'rgb(' . $color_9_rgb . ')' );
define( 'COLOR_G2_3', 'rgb(' . $color_10_rgb . ')' );

define( 'COLOR_G3_1', 'rgb(' . $color_11_rgb . ')' );
define( 'COLOR_G3_2', 'rgb(' . $color_12_rgb . ')' );
define( 'COLOR_G3_3', 'rgba(' . $color_12_rgb . ', 0.5)' );
define( 'COLOR_G3_4', 'rgba(' . $color_12_rgb . ', 0.9)' );

define( 'COLOR_G4_1', 'rgba(' . $color_1_rgb . ', 0.78)' );
define( 'COLOR_G4_2', 'rgba(' . $color_1_rgb . ', 0.638)' );
define( 'COLOR_G4_3', 'rgba(' . $color_1_rgb . ', 0.418)' );
define( 'COLOR_G4_4', 'rgba(' . $color_1_rgb . ', 0.297)' );
define( 'COLOR_G4_5', 'rgba(' . $color_1_rgb . ', 0.186)' );
define( 'COLOR_G4_6', 'rgba(' . $color_1_rgb . ', 0.087)' );
define( 'COLOR_G4_7', 'rgba(' . $color_1_rgb . ', 0.037)' );

define( 'COLOR_G5_1', 'rgba(' . $color_9_rgb . ', 0.78)' );
define( 'COLOR_G5_2', 'rgba(' . $color_9_rgb . ', 0.638)' );
define( 'COLOR_G5_3', 'rgba(' . $color_9_rgb . ', 0.418)' );
define( 'COLOR_G5_4', 'rgba(' . $color_9_rgb . ', 0.297)' );
define( 'COLOR_G5_5', 'rgba(' . $color_9_rgb . ', 0.186)' );
define( 'COLOR_G5_6', 'rgba(' . $color_9_rgb . ', 0.087)' );
define( 'COLOR_G5_7', 'rgba(' . $color_9_rgb . ', 0.037)' );

define( 'COLOR_G6_1', 'rgb(' . $color_13_rgb . ')' );
define( 'COLOR_G6_2', 'rgb(' . $color_14_rgb . ')' );
define( 'COLOR_G6_3', 'rgb(' . $color_15_rgb . ')' );
define( 'COLOR_G6_4', 'rgba(' . $color_16_rgb . ', 0.111)' );
define( 'COLOR_G6_5', 'rgb(' . $color_17_rgb . ')' );
define( 'COLOR_G6_6', 'rgba(' . $color_17_rgb . ', 0.11)' );

define( 'COLOR_G7_1', 'rgba(' . $color_18_rgb . ', 1)' );
define( 'COLOR_G7_2', 'rgba(' . $color_18_rgb . ', 0.638)' );
define( 'COLOR_G7_3', 'rgba(' . $color_18_rgb . ', 0.418)' );
define( 'COLOR_G7_4', 'rgba(' . $color_18_rgb . ', 0.297)' );
define( 'COLOR_G7_5', 'rgba(' . $color_18_rgb . ', 0.186)' );
define( 'COLOR_G7_6', 'rgba(' . $color_18_rgb . ', 0.087)' );
define( 'COLOR_G7_7', 'rgba(' . $color_18_rgb . ', 0.037)' );




define('COLOR_SCHEME_1___BACKGROUND_NORMAL', COLOR_G1_6 );
define('COLOR_SCHEME_1___BACKGROUND_ALTERNATE', COLOR_G1_7 );

define('COLOR_SCHEME_1___FOREGROUND_NORMAL', COLOR_G1_3 );

define('COLOR_SCHEME_1___FOREGROUND_LINK', COLOR_G2_1 );
define('COLOR_SCHEME_1___FOREGROUND_HOVER', COLOR_G2_2 );
define('COLOR_SCHEME_1___FOREGROUND_ACTIVE', COLOR_SCHEME_1___FOREGROUND_LINK );
define('COLOR_SCHEME_1___FOREGROUND_VISITED', COLOR_SCHEME_1___FOREGROUND_LINK );

define('COLOR_SCHEME_1___FOREGROUND_INACTIVE', COLOR_SCHEME_1___BACKGROUND_NORMAL );
define('COLOR_SCHEME_1___FOREGROUND_NEGATIVE', COLOR_SCHEME_1___BACKGROUND_NORMAL );
define('COLOR_SCHEME_1___FOREGROUND_POSITIVE', COLOR_SCHEME_1___BACKGROUND_NORMAL );

define('COLOR_SCHEME_1___FOREGROUND_NEUTRAL', COLOR_SCHEME_1___BACKGROUND_NORMAL );




define('COLOR_SCHEME_2___BACKGROUND_NORMAL', COLOR_G2_3 );
define('COLOR_SCHEME_2___BACKGROUND_ALTERNATE', COLOR_SCHEME_2___BACKGROUND_NORMAL );

define('COLOR_SCHEME_2___FOREGROUND_NORMAL', COLOR_G3_2 );

define('COLOR_SCHEME_2___FOREGROUND_LINK', COLOR_SCHEME_2___BACKGROUND_NORMAL );
define('COLOR_SCHEME_2___FOREGROUND_HOVER', COLOR_SCHEME_2___BACKGROUND_NORMAL );
define('COLOR_SCHEME_2___FOREGROUND_ACTIVE', COLOR_SCHEME_2___BACKGROUND_NORMAL );
define('COLOR_SCHEME_2___FOREGROUND_VISITED', COLOR_SCHEME_2___BACKGROUND_NORMAL );

define('COLOR_SCHEME_2___FOREGROUND_INACTIVE', COLOR_SCHEME_2___BACKGROUND_NORMAL );
define('COLOR_SCHEME_2___FOREGROUND_NEGATIVE', COLOR_SCHEME_2___BACKGROUND_NORMAL );
define('COLOR_SCHEME_2___FOREGROUND_POSITIVE', COLOR_SCHEME_2___BACKGROUND_NORMAL );

define('COLOR_SCHEME_2___FOREGROUND_NEUTRAL', COLOR_SCHEME_2___BACKGROUND_NORMAL );




define('COLOR_SCHEME_3__BACKGROUND_NORMAL', COLOR_G1_6 );
define('COLOR_SCHEME_3__BACKGROUND_ALTERNATE', COLOR_G4_7 );

define('COLOR_SCHEME_3__FOREGROUND_NORMAL', COLOR_SCHEME_1___FOREGROUND_NORMAL );

define('COLOR_SCHEME_3__FOREGROUND_LINK', COLOR_SCHEME_1___FOREGROUND_LINK );
define('COLOR_SCHEME_3__FOREGROUND_HOVER', COLOR_SCHEME_1___FOREGROUND_HOVER );
define('COLOR_SCHEME_3__FOREGROUND_ACTIVE', COLOR_SCHEME_3__FOREGROUND_LINK );
define('COLOR_SCHEME_3__FOREGROUND_VISITED', COLOR_SCHEME_3__FOREGROUND_LINK );

define('COLOR_SCHEME_3__FOREGROUND_INACTIVE', COLOR_SCHEME_3__BACKGROUND_NORMAL );
define('COLOR_SCHEME_3__FOREGROUND_NEGATIVE', COLOR_SCHEME_3__BACKGROUND_NORMAL );
define('COLOR_SCHEME_3__FOREGROUND_POSITIVE', COLOR_SCHEME_3__BACKGROUND_NORMAL );

define('COLOR_SCHEME_3__FOREGROUND_NEUTRAL', COLOR_SCHEME_3__BACKGROUND_NORMAL );




define('COLOR_SCHEME_4___BACKGROUND_NORMAL', COLOR_G2_3 );
define('COLOR_SCHEME_4___BACKGROUND_ALTERNATE', COLOR_G2_1 );

define('COLOR_SCHEME_4___FOREGROUND_NORMAL', COLOR_G3_2 );

define('COLOR_SCHEME_4___FOREGROUND_LINK', COLOR_G1_3 );
define('COLOR_SCHEME_4___FOREGROUND_HOVER', COLOR_G1_4 );
define('COLOR_SCHEME_4___FOREGROUND_ACTIVE', COLOR_G1_6 );
define('COLOR_SCHEME_4___FOREGROUND_VISITED', COLOR_G2_1 );

define('COLOR_SCHEME_4___FOREGROUND_INACTIVE', COLOR_SCHEME_4___BACKGROUND_NORMAL );
define('COLOR_SCHEME_4___FOREGROUND_NEGATIVE', COLOR_SCHEME_4___BACKGROUND_NORMAL );
define('COLOR_SCHEME_4___FOREGROUND_POSITIVE', COLOR_SCHEME_4___BACKGROUND_NORMAL );

define('COLOR_SCHEME_4___FOREGROUND_NEUTRAL', COLOR_SCHEME_4___BACKGROUND_NORMAL );




define('COLOR_SCHEME_5___BACKGROUND_NORMAL', COLOR_G2_3 );
define('COLOR_SCHEME_5___BACKGROUND_ALTERNATE', COLOR_G2_1 );

define('COLOR_SCHEME_5___FOREGROUND_NORMAL', COLOR_G1_7 );

define('COLOR_SCHEME_5___FOREGROUND_LINK', COLOR_G1_3 );
define('COLOR_SCHEME_5___FOREGROUND_HOVER', COLOR_G1_4 );
define('COLOR_SCHEME_5___FOREGROUND_ACTIVE', COLOR_G1_6 );
define('COLOR_SCHEME_5___FOREGROUND_VISITED', COLOR_G2_1 );

define('COLOR_SCHEME_5___FOREGROUND_INACTIVE', COLOR_SCHEME_5___BACKGROUND_NORMAL );
define('COLOR_SCHEME_5___FOREGROUND_NEGATIVE', COLOR_SCHEME_5___BACKGROUND_NORMAL );
define('COLOR_SCHEME_5___FOREGROUND_POSITIVE', COLOR_SCHEME_5___BACKGROUND_NORMAL );

define('COLOR_SCHEME_5___FOREGROUND_NEUTRAL', COLOR_SCHEME_5___BACKGROUND_NORMAL );








define('SCHEME_2_COLOR_1', '#2b74c7' );
define('SCHEME_2_COLOR_1_COMPLEMENTARY', '#fff' );
define('SCHEME_2_COLOR_2', '#5c9ceb' );
define('SCHEME_2_COLOR_2_COMPLEMENTARY', '#fff' );
define('SCHEME_2_COLOR_3', '#d8e9ff' );
define('SCHEME_2_COLOR_4', '#fff' );
define('SCHEME_2_COLOR_6', 'rgba( 255, 255, 255, .478 )' );

/*
 --------------------------------------------------------------------------------------------------
 Colors schemes
 --------------------------------------------------------------------------------------------------
 **************************************************************************************************
 **************************************************************************************************
 */

define( 'DEFAULT_SPACING', 20 );

define( 'SITE_WIDTH_VALUE', 70 );
define( 'SITE_WIDTH_UNIT', '%' );
define( 'SITE_WIDTH', SITE_WIDTH_VALUE . SITE_WIDTH_UNIT );

define('DEFAULT_TRANSITION', 'all 0.2s ease-in-out');

define('FONT_FAMILY_DEFAULT', '\'Roboto Condensed\', \'Arial\', sans-serif');
define('FONT_FAMILY_SEC', '\'Junge\', \'Arial\', sans-serif');
define('FONT_FAMILY_DEFAULT_MONO', '\'Menlo\', \'Monaco\', monospace');
define('DEFAULT_FONT_COLOR', '#1f2d3f');
define('DEFAULT_FONT_SIZE', '.99em');
define('DEFAULT_LINE_HEIGHT', '1.7em');
define('DEFAULT_FONT_BOLD_COLOR', '#445a73');

define('LINK_COLOR', '#006cd3');
define('LINK_HOVER_COLOR', '#008ee8');

define('DEFAULT_BORDER_RADIUS_VALUE', '4');
define('DEFAULT_BORDER_RADIUS', '
	
	border-top-left-radius: ' . DEFAULT_BORDER_RADIUS_VALUE . 'px;
	border-top-right-radius: ' . DEFAULT_BORDER_RADIUS_VALUE . 'px;
	border-bottom-left-radius: ' . DEFAULT_BORDER_RADIUS_VALUE . 'px;
	border-bottom-right-radius: ' . DEFAULT_BORDER_RADIUS_VALUE . 'px;
	
');

define('DEFAULT_BOX_SHADOW', '0px 2px 5px rgba( 0, 0, 0, .2 )');
define('DEFAULT_BOX_SHADOW_HOVER', '0px 10px 15px rgba( 0, 0, 0, .3 )');

define('DEFAULT_TEXT_SHADOW', 'none');
define('DEFAULT_TEXT_SHADOW_HOVER', 'none');

define('DARK_TEXT_SHADOW', '0 2px 3px rgba(15, 64, 102, 0.5)');
define('DARK_TEXT_SHADOW_HOVER', '0 2px 3px rgba(15, 64, 102, 0.7)');

define('SELECTION_FOREGROUND_COLOR', '#ffffff');
define('SELECTION_BACKGROUND_COLOR', '#348fe5');
define('SELECTION_SEC_FOREGROUND_COLOR', DEFAULT_FONT_COLOR);
define('SELECTION_SEC_BACKGROUND_COLOR', '#dde6f2');

define('HIGHLIGHT_BOX_SHADOW', '0 0 20px #ff6a07');
define('ERROR_BOX_SHADOW', '0 0 20px #e14646');

define('HIGHLIGHT_COLOR', '#65a029');

define('DEFAULT_TABLE_BORDER', '1px solid rgba(0, 97, 190, .05)');
define('DEFAULT_TABLE_FONT_SIZE', '90%');

define('DEFAULT_TABLE_TR_FOREGROUND_COLOR', DEFAULT_FONT_COLOR);
define('DEFAULT_TABLE_TR_BACKGROUND_COLOR', 'none');
define('DEFAULT_TABLE_TR_FOREGROUND_COLOR_SEC', SELECTION_SEC_FOREGROUND_COLOR);
define('DEFAULT_TABLE_TR_BACKGROUND_COLOR_SEC', SELECTION_SEC_BACKGROUND_COLOR);

define('DEFAULT_TABLE_TH_FONT_SIZE', DEFAULT_TABLE_FONT_SIZE);
define('DEFAULT_TABLE_TH_FOREGROUND_COLOR', DEFAULT_FONT_COLOR);
define('DEFAULT_TABLE_TH_BACKGROUND_COLOR', SELECTION_SEC_BACKGROUND_COLOR);

define('DEFAULT_TABLE_TD_FONT_SIZE', DEFAULT_TABLE_FONT_SIZE);
define('DEFAULT_TABLE_TD_FOREGROUND_COLOR', DEFAULT_FONT_COLOR);
define('DEFAULT_TABLE_TD_BACKGROUND_COLOR', '#ffffff');
define('DEFAULT_TABLE_TD_FOREGROUND_COLOR_SEC', DEFAULT_FONT_COLOR);
define('DEFAULT_TABLE_TD_BACKGROUND_COLOR_SEC', '#f3f6fa');

define('DEFAULT_TABLE_TH_OB_FOREGROUND_COLOR', '#fff');
define('DEFAULT_TABLE_TH_OB_BACKGROUND', '
	
	/* CSS */
	background-image: -webkit-gradient(linear, center top, center bottom, color-stop(0%, rgba(21, 139, 255, 0.554)), color-stop(100%, rgba(14, 95, 190, 0.647)));
	background-image: -webkit-linear-gradient(top, rgba(21, 139, 255, 0.554) 0%, rgba(14, 95, 190, 0.647) 100%);
	background-image: -moz-linear-gradient(top, rgba(21, 139, 255, 0.554) 0%, rgba(14, 95, 190, 0.647) 100%);
	background-image: -ms-linear-gradient(top, rgba(21, 139, 255, 0.554) 0%, rgba(14, 95, 190, 0.647) 100%);
	background-image: -o-linear-gradient(top, rgba(21, 139, 255, 0.554) 0%, rgba(14, 95, 190, 0.647) 100%);
	background-image: linear-gradient(to bottom, rgba(21, 139, 255, 0.554) 0%, rgba(14, 95, 190, 0.647) 100%);

');












/****************************************************/
/********************* Buttons **********************/

/* ------ Normal status ------ */

define('BUTTONS_FONT_FAMILY', FONT_FAMILY_DEFAULT);
define('BUTTONS_FONT_SIZE', '90%');

define( 'BUTTONS_FOREGROUND_COLOR', COLOR_G3_2 );
define( 'BUTTONS_COLOR', '
	
	color: ' . BUTTONS_FOREGROUND_COLOR . ';
	
');

define( 'BUTTONS_BACKGROUND_COLOR', COLOR_G2_3 );
define( 'BUTTONS_BACKGROUND', '
	
	background: ' . BUTTONS_BACKGROUND_COLOR . ';
	
');

define( 'BUTTONS_BORDER_TOP_WIDTH', 0 );
define( 'BUTTONS_BORDER_TOP_STYLE', 'solid' );
define( 'BUTTONS_BORDER_TOP_COLOR', 'transparent' );
define( 'BUTTONS_BORDER_TOP', BUTTONS_BORDER_TOP_WIDTH . 'px ' . BUTTONS_BORDER_TOP_STYLE . ' ' . BUTTONS_BORDER_TOP_COLOR );
define( 'BUTTONS_BORDER_RIGHT_WIDTH', 0 );
define( 'BUTTONS_BORDER_RIGHT_STYLE', 'solid' );
define( 'BUTTONS_BORDER_RIGHT_COLOR', 'transparent' );
define( 'BUTTONS_BORDER_RIGHT', BUTTONS_BORDER_RIGHT_WIDTH . 'px ' . BUTTONS_BORDER_RIGHT_STYLE . ' ' . BUTTONS_BORDER_RIGHT_COLOR );
define( 'BUTTONS_BORDER_BOTTOM_WIDTH', 3 );
define( 'BUTTONS_BORDER_BOTTOM_STYLE', 'solid' );
define( 'BUTTONS_BORDER_BOTTOM_COLOR', COLOR_G2_2 );
define( 'BUTTONS_BORDER_BOTTOM', BUTTONS_BORDER_BOTTOM_WIDTH . 'px ' . BUTTONS_BORDER_BOTTOM_STYLE . ' ' . BUTTONS_BORDER_BOTTOM_COLOR );
define( 'BUTTONS_BORDER_LEFT_WIDTH', 0 );
define( 'BUTTONS_BORDER_LEFT_STYLE', 'solid' );
define( 'BUTTONS_BORDER_LEFT_COLOR', 'transparent' );
define( 'BUTTONS_BORDER_LEFT', BUTTONS_BORDER_LEFT_WIDTH . 'px ' . BUTTONS_BORDER_LEFT_STYLE . ' ' . BUTTONS_BORDER_LEFT_COLOR );
define( 'BUTTONS_BORDER', '
	
	border-top: ' . BUTTONS_BORDER_TOP . ';
	border-right: ' . BUTTONS_BORDER_RIGHT . ';
	border-bottom: ' . BUTTONS_BORDER_BOTTOM . ';
	border-left: ' . BUTTONS_BORDER_LEFT . ';

');

define( 'BUTTONS_PADDING_TOP_WIDTH', DEFAULT_SPACING - 5 - ( DEFAULT_SPACING * 0.2 ) );
define( 'BUTTONS_PADDING_TOP', BUTTONS_PADDING_TOP_WIDTH . 'px ' );
define( 'BUTTONS_PADDING_RIGHT_WIDTH', ( DEFAULT_SPACING - 5 ) * 2 );
define( 'BUTTONS_PADDING_RIGHT', BUTTONS_PADDING_RIGHT_WIDTH . 'px '  );
define( 'BUTTONS_PADDING_BOTTOM_WIDTH', DEFAULT_SPACING - 5 - ( DEFAULT_SPACING * 0.2 ) );
define( 'BUTTONS_PADDING_BOTTOM', BUTTONS_PADDING_BOTTOM_WIDTH . 'px ' );
define( 'BUTTONS_PADDING_LEFT_WIDTH', ( DEFAULT_SPACING - 5 ) * 2 );
define( 'BUTTONS_PADDING_LEFT', BUTTONS_PADDING_LEFT_WIDTH . 'px ' );
define( 'BUTTONS_PADDING', '
	
	padding-top: ' . BUTTONS_PADDING_TOP . ';
	padding-right: ' . BUTTONS_PADDING_RIGHT . ';
	padding-bottom: ' . BUTTONS_PADDING_BOTTOM . ';
	padding-left: ' . BUTTONS_PADDING_LEFT . ';

');


/* ------ Hover status ------ */

define('BUTTONS_FONT_FAMILY_HOVER', BUTTONS_FONT_FAMILY);
define('BUTTONS_FONT_SIZE_HOVER', BUTTONS_FONT_SIZE);

define( 'BUTTONS_FOREGROUND_COLOR_HOVER', COLOR_G3_2 );
define( 'BUTTONS_COLOR_HOVER', '
	
	color: ' . BUTTONS_FOREGROUND_COLOR_HOVER . ';
	
');

define( 'BUTTONS_BACKGROUND_COLOR_HOVER', COLOR_G2_2 );
define( 'BUTTONS_BACKGROUND_HOVER', '
	
	background: ' . BUTTONS_BACKGROUND_COLOR_HOVER . ';
	
');

define( 'BUTTONS_BORDER_TOP_WIDTH_HOVER', BUTTONS_BORDER_TOP_WIDTH );
define( 'BUTTONS_BORDER_TOP_STYLE_HOVER', BUTTONS_BORDER_TOP_STYLE );
define( 'BUTTONS_BORDER_TOP_COLOR_HOVER', BUTTONS_BORDER_TOP_COLOR );
define( 'BUTTONS_BORDER_TOP_HOVER', BUTTONS_BORDER_TOP_WIDTH_HOVER . 'px ' . BUTTONS_BORDER_TOP_STYLE_HOVER . ' ' . BUTTONS_BORDER_TOP_COLOR_HOVER );
define( 'BUTTONS_BORDER_RIGHT_WIDTH_HOVER', BUTTONS_BORDER_RIGHT_WIDTH );
define( 'BUTTONS_BORDER_RIGHT_STYLE_HOVER', BUTTONS_BORDER_RIGHT_STYLE );
define( 'BUTTONS_BORDER_RIGHT_COLOR_HOVER', BUTTONS_BORDER_RIGHT_COLOR );
define( 'BUTTONS_BORDER_RIGHT_HOVER', BUTTONS_BORDER_RIGHT_WIDTH_HOVER . 'px ' . BUTTONS_BORDER_RIGHT_STYLE_HOVER . ' ' . BUTTONS_BORDER_RIGHT_COLOR_HOVER );
define( 'BUTTONS_BORDER_BOTTOM_WIDTH_HOVER', BUTTONS_BORDER_BOTTOM_WIDTH );
define( 'BUTTONS_BORDER_BOTTOM_STYLE_HOVER', BUTTONS_BORDER_BOTTOM_STYLE );
define( 'BUTTONS_BORDER_BOTTOM_COLOR_HOVER', COLOR_G2_1 );
define( 'BUTTONS_BORDER_BOTTOM_HOVER', BUTTONS_BORDER_BOTTOM_WIDTH_HOVER . 'px ' . BUTTONS_BORDER_BOTTOM_STYLE_HOVER . ' ' . BUTTONS_BORDER_BOTTOM_COLOR_HOVER );
define( 'BUTTONS_BORDER_LEFT_WIDTH_HOVER', BUTTONS_BORDER_LEFT_WIDTH );
define( 'BUTTONS_BORDER_LEFT_STYLE_HOVER', BUTTONS_BORDER_LEFT_STYLE );
define( 'BUTTONS_BORDER_LEFT_COLOR_HOVER', BUTTONS_BORDER_LEFT_COLOR );
define( 'BUTTONS_BORDER_LEFT_HOVER', BUTTONS_BORDER_LEFT_WIDTH_HOVER . 'px ' . BUTTONS_BORDER_LEFT_STYLE_HOVER . ' ' . BUTTONS_BORDER_LEFT_COLOR_HOVER );
define( 'BUTTONS_BORDER_HOVER', '
	
	border-top: ' . BUTTONS_BORDER_TOP_HOVER . ';
	border-right: ' . BUTTONS_BORDER_RIGHT_HOVER . ';
	border-bottom: ' . BUTTONS_BORDER_BOTTOM_HOVER . ';
	border-left: ' . BUTTONS_BORDER_LEFT_HOVER . ';

');

define( 'BUTTONS_PADDING_TOP_WIDTH', BUTTONS_PADDING_TOP_WIDTH );
define( 'BUTTONS_PADDING_TOP_HOVER', BUTTONS_PADDING_TOP . 'px ' );
define( 'BUTTONS_PADDING_RIGHT_WIDTH_HOVER', BUTTONS_PADDING_RIGHT_WIDTH );
define( 'BUTTONS_PADDING_RIGHT_HOVER', BUTTONS_PADDING_RIGHT . 'px '  );
define( 'BUTTONS_PADDING_BOTTOM_WIDTH_HOVER', BUTTONS_PADDING_BOTTOM_WIDTH );
define( 'BUTTONS_PADDING_BOTTOM_HOVER', BUTTONS_PADDING_BOTTOM_WIDTH . 'px ' );
define( 'BUTTONS_PADDING_LEFT_WIDTH_HOVER', BUTTONS_PADDING_LEFT_WIDTH );
define( 'BUTTONS_PADDING_LEFT_HOVER', BUTTONS_PADDING_LEFT_WIDTH . 'px ' );
define( 'BUTTONS_PADDING_HOVER', '
	
	padding-top: ' . BUTTONS_PADDING_TOP_HOVER . ';
	padding-right: ' . BUTTONS_PADDING_RIGHT_HOVER . ';
	padding-bottom: ' . BUTTONS_PADDING_BOTTOM_HOVER . ';
	padding-left: ' . BUTTONS_PADDING_LEFT_HOVER . ';

');


/********************* Buttons **********************/
/****************************************************/



/****************************************************/
/********************* Selects **********************/

/* ------ Normal status ------ */

define('SELECTS_FONT_FAMILY', BUTTONS_FONT_FAMILY );
define('SELECTS_FONT_SIZE', BUTTONS_FONT_SIZE );

define( 'SELECTS_FOREGROUND_COLOR', BUTTONS_FOREGROUND_COLOR );
define( 'SELECTS_COLOR', '
	
	color: ' . SELECTS_FOREGROUND_COLOR . ';
	
');

define( 'SELECTS_BACKGROUND_COLOR', BUTTONS_BACKGROUND_COLOR );
define( 'SELECTS_BACKGROUND', '
	
	background: ' . SELECTS_BACKGROUND_COLOR . ';
	
');

define( 'SELECTS_BORDER_TOP_WIDTH', BUTTONS_BORDER_TOP_WIDTH );
define( 'SELECTS_BORDER_TOP_STYLE', BUTTONS_BORDER_TOP_STYLE );
define( 'SELECTS_BORDER_TOP_COLOR', BUTTONS_BORDER_TOP_COLOR );
define( 'SELECTS_BORDER_TOP', SELECTS_BORDER_TOP_WIDTH . 'px ' . SELECTS_BORDER_TOP_STYLE . ' ' . SELECTS_BORDER_TOP_COLOR );
define( 'SELECTS_BORDER_RIGHT_WIDTH', BUTTONS_BORDER_RIGHT_WIDTH );
define( 'SELECTS_BORDER_RIGHT_STYLE', BUTTONS_BORDER_RIGHT_STYLE );
define( 'SELECTS_BORDER_RIGHT_COLOR', BUTTONS_BORDER_RIGHT_COLOR );
define( 'SELECTS_BORDER_RIGHT', SELECTS_BORDER_RIGHT_WIDTH . 'px ' . SELECTS_BORDER_RIGHT_STYLE . ' ' . SELECTS_BORDER_RIGHT_COLOR );
define( 'SELECTS_BORDER_BOTTOM_WIDTH', BUTTONS_BORDER_BOTTOM_WIDTH );
define( 'SELECTS_BORDER_BOTTOM_STYLE', BUTTONS_BORDER_BOTTOM_STYLE );
define( 'SELECTS_BORDER_BOTTOM_COLOR', BUTTONS_BORDER_BOTTOM_COLOR );
define( 'SELECTS_BORDER_BOTTOM', SELECTS_BORDER_BOTTOM_WIDTH . 'px ' . SELECTS_BORDER_BOTTOM_STYLE . ' ' . SELECTS_BORDER_BOTTOM_COLOR );
define( 'SELECTS_BORDER_LEFT_WIDTH', BUTTONS_BORDER_LEFT_WIDTH );
define( 'SELECTS_BORDER_LEFT_STYLE', BUTTONS_BORDER_LEFT_STYLE );
define( 'SELECTS_BORDER_LEFT_COLOR', BUTTONS_BORDER_LEFT_COLOR );
define( 'SELECTS_BORDER_LEFT', SELECTS_BORDER_LEFT_WIDTH . 'px ' . SELECTS_BORDER_LEFT_STYLE . ' ' . SELECTS_BORDER_LEFT_COLOR );
define( 'SELECTS_BORDER', '
	
	border-top: ' . SELECTS_BORDER_TOP . ';
	border-right: ' . SELECTS_BORDER_RIGHT . ';
	border-bottom: ' . SELECTS_BORDER_BOTTOM . ';
	border-left: ' . SELECTS_BORDER_LEFT . ';

');

define( 'SELECTS_PADDING_TOP_WIDTH', BUTTONS_PADDING_TOP_WIDTH + 2 );
define( 'SELECTS_PADDING_TOP', SELECTS_PADDING_TOP_WIDTH . 'px ' );
define( 'SELECTS_PADDING_RIGHT_WIDTH', BUTTONS_PADDING_RIGHT_WIDTH );
define( 'SELECTS_PADDING_RIGHT', SELECTS_PADDING_RIGHT_WIDTH . 'px '  );
define( 'SELECTS_PADDING_BOTTOM_WIDTH', BUTTONS_PADDING_BOTTOM_WIDTH + 2 );
define( 'SELECTS_PADDING_BOTTOM', SELECTS_PADDING_BOTTOM_WIDTH . 'px ' );
define( 'SELECTS_PADDING_LEFT_WIDTH', BUTTONS_PADDING_LEFT_WIDTH );
define( 'SELECTS_PADDING_LEFT', SELECTS_PADDING_LEFT_WIDTH . 'px ' );
define( 'SELECTS_PADDING', '
	
	padding-top: ' . SELECTS_PADDING_TOP . ';
	padding-right: ' . SELECTS_PADDING_RIGHT . ';
	padding-bottom: ' . SELECTS_PADDING_BOTTOM . ';
	padding-left: ' . SELECTS_PADDING_LEFT . ';

');

/* ------ Hover status ------ */

define('SELECTS_FONT_FAMILY_HOVER', BUTTONS_FONT_FAMILY_HOVER );
define('SELECTS_FONT_SIZE_HOVER', BUTTONS_FONT_SIZE_HOVER );

define( 'SELECTS_FOREGROUND_COLOR_HOVER', BUTTONS_FOREGROUND_COLOR_HOVER );
define( 'SELECTS_COLOR_HOVER', '
	
	color: ' . SELECTS_FOREGROUND_COLOR_HOVER . ';
	
');

define( 'SELECTS_BACKGROUND_COLOR_HOVER', BUTTONS_BACKGROUND_COLOR_HOVER );
define( 'SELECTS_BACKGROUND_HOVER', '
	
	background: ' . SELECTS_BACKGROUND_COLOR_HOVER . ';
	
');

define( 'SELECTS_BORDER_TOP_WIDTH_HOVER', BUTTONS_BORDER_TOP_WIDTH_HOVER );
define( 'SELECTS_BORDER_TOP_STYLE_HOVER', BUTTONS_BORDER_TOP_STYLE_HOVER );
define( 'SELECTS_BORDER_TOP_COLOR_HOVER', BUTTONS_BORDER_TOP_COLOR_HOVER );
define( 'SELECTS_BORDER_TOP_HOVER', SELECTS_BORDER_TOP_WIDTH_HOVER . 'px ' . SELECTS_BORDER_TOP_STYLE_HOVER . ' ' . SELECTS_BORDER_TOP_COLOR_HOVER );
define( 'SELECTS_BORDER_RIGHT_WIDTH_HOVER', BUTTONS_BORDER_RIGHT_WIDTH_HOVER );
define( 'SELECTS_BORDER_RIGHT_STYLE_HOVER', BUTTONS_BORDER_RIGHT_STYLE_HOVER );
define( 'SELECTS_BORDER_RIGHT_COLOR_HOVER', BUTTONS_BORDER_RIGHT_COLOR_HOVER );
define( 'SELECTS_BORDER_RIGHT_HOVER', SELECTS_BORDER_RIGHT_WIDTH_HOVER . 'px ' . SELECTS_BORDER_RIGHT_STYLE_HOVER . ' ' . SELECTS_BORDER_RIGHT_COLOR_HOVER );
define( 'SELECTS_BORDER_BOTTOM_WIDTH_HOVER', BUTTONS_BORDER_BOTTOM_WIDTH_HOVER );
define( 'SELECTS_BORDER_BOTTOM_STYLE_HOVER', BUTTONS_BORDER_BOTTOM_STYLE_HOVER );
define( 'SELECTS_BORDER_BOTTOM_COLOR_HOVER', BUTTONS_BORDER_BOTTOM_COLOR_HOVER );
define( 'SELECTS_BORDER_BOTTOM_HOVER', SELECTS_BORDER_BOTTOM_WIDTH_HOVER . 'px ' . SELECTS_BORDER_BOTTOM_STYLE_HOVER . ' ' . SELECTS_BORDER_BOTTOM_COLOR_HOVER );
define( 'SELECTS_BORDER_LEFT_WIDTH_HOVER', BUTTONS_BORDER_LEFT_WIDTH_HOVER );
define( 'SELECTS_BORDER_LEFT_STYLE_HOVER', BUTTONS_BORDER_LEFT_STYLE_HOVER );
define( 'SELECTS_BORDER_LEFT_COLOR_HOVER', BUTTONS_BORDER_LEFT_COLOR_HOVER );
define( 'SELECTS_BORDER_LEFT_HOVER', SELECTS_BORDER_LEFT_WIDTH_HOVER . 'px ' . SELECTS_BORDER_LEFT_STYLE_HOVER . ' ' . SELECTS_BORDER_LEFT_COLOR_HOVER );
define( 'SELECTS_BORDER_HOVER', '
	
	border-top: ' . SELECTS_BORDER_TOP_HOVER . ';
	border-right: ' . SELECTS_BORDER_RIGHT_HOVER . ';
	border-bottom: ' . SELECTS_BORDER_BOTTOM_HOVER . ';
	border-left: ' . SELECTS_BORDER_LEFT_HOVER . ';

');

define( 'SELECTS_PADDING_TOP_WIDTH_HOVER', SELECTS_PADDING_TOP_WIDTH );
define( 'SELECTS_PADDING_TOP_HOVER', SELECTS_PADDING_TOP_WIDTH . 'px ' );
define( 'SELECTS_PADDING_RIGHT_WIDTH_HOVER', SELECTS_PADDING_RIGHT_WIDTH );
define( 'SELECTS_PADDING_RIGHT_HOVER', SELECTS_PADDING_RIGHT_WIDTH . 'px '  );
define( 'SELECTS_PADDING_BOTTOM_WIDTH_HOVER', SELECTS_PADDING_BOTTOM_WIDTH );
define( 'SELECTS_PADDING_BOTTOM_HOVER', SELECTS_PADDING_BOTTOM_WIDTH . 'px ' );
define( 'SELECTS_PADDING_LEFT_WIDTH_HOVER', SELECTS_PADDING_LEFT_WIDTH );
define( 'SELECTS_PADDING_LEFT_HOVER', SELECTS_PADDING_LEFT_WIDTH . 'px ' );
define( 'SELECTS_PADDING_HOVER', '
	
	padding-top: ' . SELECTS_PADDING_TOP_HOVER . ';
	padding-right: ' . SELECTS_PADDING_RIGHT_HOVER . ';
	padding-bottom: ' . SELECTS_PADDING_BOTTOM_HOVER . ';
	padding-left: ' . SELECTS_PADDING_LEFT_HOVER . ';

');

/********************* Selects **********************/
/****************************************************/



/****************************************************/
/******************* Inputs text ********************/

/* ------ Normal status ------ */

define('INPUTS_TEXT_FONT_FAMILY', BUTTONS_FONT_FAMILY );
define('INPUTS_TEXT_FONT_SIZE', BUTTONS_FONT_SIZE );

define( 'INPUTS_TEXT_FOREGROUND_COLOR', COLOR_G1_1 );
define( 'INPUTS_TEXT_COLOR', '
	
	color: ' . INPUTS_TEXT_FOREGROUND_COLOR . ';
	
');

define( 'INPUTS_TEXT_BACKGROUND_COLOR', COLOR_G3_2 );
define( 'INPUTS_TEXT_BACKGROUND', '
	
	background: ' . INPUTS_TEXT_BACKGROUND_COLOR . ';
	
');

define( 'INPUTS_TEXT_BORDER_TOP_WIDTH', 1 );
define( 'INPUTS_TEXT_BORDER_TOP_STYLE', 'solid' );
define( 'INPUTS_TEXT_BORDER_TOP_COLOR', COLOR_G4_5 );
define( 'INPUTS_TEXT_BORDER_TOP', INPUTS_TEXT_BORDER_TOP_WIDTH . 'px ' . INPUTS_TEXT_BORDER_TOP_STYLE . ' ' . INPUTS_TEXT_BORDER_TOP_COLOR );
define( 'INPUTS_TEXT_BORDER_RIGHT_WIDTH', INPUTS_TEXT_BORDER_TOP_WIDTH );
define( 'INPUTS_TEXT_BORDER_RIGHT_STYLE', INPUTS_TEXT_BORDER_TOP_STYLE );
define( 'INPUTS_TEXT_BORDER_RIGHT_COLOR', INPUTS_TEXT_BORDER_TOP_COLOR );
define( 'INPUTS_TEXT_BORDER_RIGHT', INPUTS_TEXT_BORDER_RIGHT_WIDTH . 'px ' . INPUTS_TEXT_BORDER_RIGHT_STYLE . ' ' . INPUTS_TEXT_BORDER_RIGHT_COLOR );
define( 'INPUTS_TEXT_BORDER_BOTTOM_WIDTH', INPUTS_TEXT_BORDER_TOP_WIDTH );
define( 'INPUTS_TEXT_BORDER_BOTTOM_STYLE', INPUTS_TEXT_BORDER_TOP_STYLE );
define( 'INPUTS_TEXT_BORDER_BOTTOM_COLOR', INPUTS_TEXT_BORDER_TOP_COLOR );
define( 'INPUTS_TEXT_BORDER_BOTTOM', INPUTS_TEXT_BORDER_BOTTOM_WIDTH . 'px ' . INPUTS_TEXT_BORDER_BOTTOM_STYLE . ' ' . INPUTS_TEXT_BORDER_BOTTOM_COLOR );
define( 'INPUTS_TEXT_BORDER_LEFT_WIDTH', INPUTS_TEXT_BORDER_TOP_WIDTH );
define( 'INPUTS_TEXT_BORDER_LEFT_STYLE', INPUTS_TEXT_BORDER_TOP_STYLE );
define( 'INPUTS_TEXT_BORDER_LEFT_COLOR', INPUTS_TEXT_BORDER_TOP_COLOR );
define( 'INPUTS_TEXT_BORDER_LEFT', INPUTS_TEXT_BORDER_LEFT_WIDTH . 'px ' . INPUTS_TEXT_BORDER_LEFT_STYLE . ' ' . INPUTS_TEXT_BORDER_LEFT_COLOR );
define( 'INPUTS_TEXT_BORDER', '
	
	border-top: ' . INPUTS_TEXT_BORDER_TOP . ';
	border-right: ' . INPUTS_TEXT_BORDER_RIGHT . ';
	border-bottom: ' . INPUTS_TEXT_BORDER_BOTTOM . ';
	border-left: ' . INPUTS_TEXT_BORDER_LEFT . ';

');

define( 'INPUTS_TEXT_PADDING_TOP_WIDTH', BUTTONS_PADDING_TOP_WIDTH - 1 );
define( 'INPUTS_TEXT_PADDING_TOP', INPUTS_TEXT_PADDING_TOP_WIDTH . 'px ' );
define( 'INPUTS_TEXT_PADDING_RIGHT_WIDTH', BUTTONS_PADDING_TOP_WIDTH );
define( 'INPUTS_TEXT_PADDING_RIGHT', INPUTS_TEXT_PADDING_RIGHT_WIDTH . 'px '  );
define( 'INPUTS_TEXT_PADDING_BOTTOM_WIDTH', BUTTONS_PADDING_BOTTOM_WIDTH + 2 );
define( 'INPUTS_TEXT_PADDING_BOTTOM', INPUTS_TEXT_PADDING_BOTTOM_WIDTH . 'px ' );
define( 'INPUTS_TEXT_PADDING_LEFT_WIDTH', BUTTONS_PADDING_TOP_WIDTH );
define( 'INPUTS_TEXT_PADDING_LEFT', INPUTS_TEXT_PADDING_LEFT_WIDTH . 'px ' );
define( 'INPUTS_TEXT_PADDING', '
	
	padding-top: ' . INPUTS_TEXT_PADDING_TOP . ';
	padding-right: ' . INPUTS_TEXT_PADDING_RIGHT . ';
	padding-bottom: ' . INPUTS_TEXT_PADDING_BOTTOM . ';
	padding-left: ' . INPUTS_TEXT_PADDING_LEFT . ';

');

/* ------ Hover status ------ */

define('INPUTS_TEXT_FONT_FAMILY_HOVER', INPUTS_TEXT_FONT_FAMILY );
define('INPUTS_TEXT_FONT_SIZE_HOVER', INPUTS_TEXT_FONT_SIZE );

define( 'INPUTS_TEXT_FOREGROUND_COLOR_HOVER', INPUTS_TEXT_FOREGROUND_COLOR );
define( 'INPUTS_TEXT_COLOR_HOVER', '
	
	color: ' . INPUTS_TEXT_FOREGROUND_COLOR_HOVER . ';
	
');

define( 'INPUTS_TEXT_BACKGROUND_COLOR_HOVER', INPUTS_TEXT_BACKGROUND_COLOR );
define( 'INPUTS_TEXT_BACKGROUND_HOVER', '
	
	background: ' . INPUTS_TEXT_BACKGROUND_COLOR . ';
	
');

define( 'INPUTS_TEXT_BORDER_TOP_WIDTH_HOVER', INPUTS_TEXT_BORDER_TOP_WIDTH );
define( 'INPUTS_TEXT_BORDER_TOP_STYLE_HOVER', INPUTS_TEXT_BORDER_TOP_STYLE );
define( 'INPUTS_TEXT_BORDER_TOP_COLOR_HOVER', COLOR_G2_3 );
define( 'INPUTS_TEXT_BORDER_TOP_HOVER', INPUTS_TEXT_BORDER_TOP_WIDTH_HOVER . 'px ' . INPUTS_TEXT_BORDER_TOP_STYLE_HOVER . ' ' . INPUTS_TEXT_BORDER_TOP_COLOR_HOVER );
define( 'INPUTS_TEXT_BORDER_RIGHT_WIDTH_HOVER', INPUTS_TEXT_BORDER_RIGHT_WIDTH );
define( 'INPUTS_TEXT_BORDER_RIGHT_STYLE_HOVER', INPUTS_TEXT_BORDER_RIGHT_STYLE );
define( 'INPUTS_TEXT_BORDER_RIGHT_COLOR_HOVER', INPUTS_TEXT_BORDER_TOP_COLOR_HOVER );
define( 'INPUTS_TEXT_BORDER_RIGHT_HOVER', INPUTS_TEXT_BORDER_RIGHT_WIDTH_HOVER . 'px ' . INPUTS_TEXT_BORDER_RIGHT_STYLE_HOVER . ' ' . INPUTS_TEXT_BORDER_RIGHT_COLOR_HOVER );
define( 'INPUTS_TEXT_BORDER_BOTTOM_WIDTH_HOVER', INPUTS_TEXT_BORDER_BOTTOM_WIDTH );
define( 'INPUTS_TEXT_BORDER_BOTTOM_STYLE_HOVER', INPUTS_TEXT_BORDER_BOTTOM_STYLE );
define( 'INPUTS_TEXT_BORDER_BOTTOM_COLOR_HOVER', INPUTS_TEXT_BORDER_TOP_COLOR_HOVER );
define( 'INPUTS_TEXT_BORDER_BOTTOM_HOVER', INPUTS_TEXT_BORDER_BOTTOM_WIDTH_HOVER . 'px ' . INPUTS_TEXT_BORDER_BOTTOM_STYLE_HOVER . ' ' . INPUTS_TEXT_BORDER_BOTTOM_COLOR_HOVER );
define( 'INPUTS_TEXT_BORDER_LEFT_WIDTH_HOVER', INPUTS_TEXT_BORDER_LEFT_WIDTH );
define( 'INPUTS_TEXT_BORDER_LEFT_STYLE_HOVER', INPUTS_TEXT_BORDER_LEFT_STYLE );
define( 'INPUTS_TEXT_BORDER_LEFT_COLOR_HOVER', INPUTS_TEXT_BORDER_TOP_COLOR_HOVER );
define( 'INPUTS_TEXT_BORDER_LEFT_HOVER', INPUTS_TEXT_BORDER_LEFT_WIDTH_HOVER . 'px ' . INPUTS_TEXT_BORDER_LEFT_STYLE_HOVER . ' ' . INPUTS_TEXT_BORDER_LEFT_COLOR_HOVER );
define( 'INPUTS_TEXT_BORDER_HOVER', '
	
	border-top: ' . INPUTS_TEXT_BORDER_TOP_HOVER . ';
	border-right: ' . INPUTS_TEXT_BORDER_RIGHT_HOVER . ';
	border-bottom: ' . INPUTS_TEXT_BORDER_BOTTOM_HOVER . ';
	border-left: ' . INPUTS_TEXT_BORDER_LEFT_HOVER . ';

');

define( 'INPUTS_TEXT_PADDING_TOP_WIDTH_HOVER', INPUTS_TEXT_PADDING_TOP_WIDTH );
define( 'INPUTS_TEXT_PADDING_TOP_HOVER', INPUTS_TEXT_PADDING_TOP_WIDTH_HOVER . 'px ' );
define( 'INPUTS_TEXT_PADDING_RIGHT_WIDTH_HOVER', INPUTS_TEXT_PADDING_RIGHT_WIDTH );
define( 'INPUTS_TEXT_PADDING_RIGHT_HOVER', INPUTS_TEXT_PADDING_RIGHT_WIDTH_HOVER . 'px '  );
define( 'INPUTS_TEXT_PADDING_BOTTOM_WIDTH_HOVER', INPUTS_TEXT_PADDING_BOTTOM_WIDTH );
define( 'INPUTS_TEXT_PADDING_BOTTOM_HOVER', INPUTS_TEXT_PADDING_BOTTOM_WIDTH_HOVER . 'px ' );
define( 'INPUTS_TEXT_PADDING_LEFT_WIDTH_HOVER', INPUTS_TEXT_PADDING_LEFT_WIDTH );
define( 'INPUTS_TEXT_PADDING_LEFT_HOVER', INPUTS_TEXT_PADDING_LEFT_WIDTH_HOVER . 'px ' );
define( 'INPUTS_TEXT_PADDING_HOVER', '
	
	padding-top: ' . INPUTS_TEXT_PADDING_TOP_HOVER . ';
	padding-right: ' . INPUTS_TEXT_PADDING_RIGHT_HOVER . ';
	padding-bottom: ' . INPUTS_TEXT_PADDING_BOTTOM_HOVER . ';
	padding-left: ' . INPUTS_TEXT_PADDING_LEFT_HOVER . ';

');

/******************* Inputs text ********************/
/****************************************************/



/****************************************************/
/******************* Link Buttons *******************/

define('LINK_BUTTONS_PADDING', '4px 8px');

/******************* Link Buttons *******************/
/****************************************************/


/****************************************************/
/******************** Inputs text *******************/

define('INPUT_PADDING', '3px 5px');
define('INPUT_FONT_FAMILY', FONT_FAMILY_DEFAULT);
define('INPUT_FONT_SIZE', '90%');
define('INPUT_FOREGROUND_COLOR', DEFAULT_FONT_COLOR);
define('INPUT_BACKGROUND_COLOR', '#e0e9f2');
define('INPUT_BACKGROUND', '
	
	/* CSS */
	background-image: -webkit-gradient(linear, center top, center bottom, color-stop(0%, #d9e6f1), color-stop(4px, #ffffff), color-stop(100%, #e7eff9));
	background-image: -webkit-linear-gradient(top, #d9e6f1 0%, #ffffff 4px, #e7eff9 100%);
	background-image: -moz-linear-gradient(top, #d9e6f1 0%, #ffffff 4px, #e7eff9 100%);
	background-image: -ms-linear-gradient(top, #d9e6f1 0%, #ffffff 4px, #e7eff9 100%);
	background-image: -o-linear-gradient(top, #d9e6f1 0%, #ffffff 4px, #e7eff9 100%);
	background-image: linear-gradient(to bottom, #d9e6f1 0%, #ffffff 4px, #e7eff9 100%);
	
');
define('INPUT_BORDER', '
	
	border-top: thin solid rgba(49, 109, 166, 0.3);
	border-right: thin solid rgba(49, 109, 166, 0.1);
	border-bottom: thin solid #fff;
	border-left: thin solid rgba(49, 109, 166, 0.1);

');

define('INPUT_FOREGROUND_COLOR_SEC', '#0d131a');
define('INPUT_BACKGROUND_COLOR_SEC', '#cbdff2');
define('INPUT_BACKGROUND_SEC', '
	
	/* CSS */
	background-image: -webkit-gradient(linear, center top, center bottom, color-stop(0%, #e5eef6), color-stop(4px, #ffffff), color-stop(100%, #f1f6fc));
	background-image: -webkit-linear-gradient(top, #e5eef6 0%, #ffffff 4px, #f1f6fc 100%);
	background-image: -moz-linear-gradient(top, #e5eef6 0%, #ffffff 4px, #f1f6fc 100%);
	background-image: -ms-linear-gradient(top, #e5eef6 0%, #ffffff 4px, #f1f6fc 100%);
	background-image: -o-linear-gradient(top, #e5eef6 0%, #ffffff 4px, #f1f6fc 100%);
	background-image: linear-gradient(to bottom, #e5eef6 0%, #ffffff 4px, #f1f6fc 100%);
	
');
define('INPUT_BORDER_SEC', '
	
	border-top: thin solid #fff;
	border-right: thin solid #fff;
	border-bottom: thin solid #fff;
	border-left: thin solid #fff;

');
define('INPUT_BORDER_SEC', '1px solid #000');

/******************** Inputs text *******************/
/****************************************************/

/****************************************************/
/********************** Checkbox ********************/

define('CHECKBOX_PADDING', '0.25em');

define('CHECKBOX_BORDER_RADIUS', 0);

define('CHECKBOX_FOREGROUND_COLOR', BUTTONS_FOREGROUND_COLOR);
define('CHECKBOX_BACKGROUND', INPUT_BACKGROUND);
define('CHECKBOX_BORDER', INPUT_BORDER);

define('CHECKBOX_FOREGROUND_COLOR_SEC', BUTTONS_FOREGROUND_COLOR_SEC);
define('CHECKBOX_BACKGROUND_SEC', BUTTONS_BACKGROUND_SEC);
define('CHECKBOX_BORDER_SEC', BUTTONS_BORDER_SEC);

/********************** Checkbox ********************/
/****************************************************/

/****************************************************/
/********************** Radiobox ********************/

define('RADIO_PADDING', CHECKBOX_PADDING);

define('RADIO_BORDER_RADIUS', 100);

define('RADIO_FOREGROUND_COLOR', CHECKBOX_FOREGROUND_COLOR);
define('RADIO_BACKGROUND', CHECKBOX_BACKGROUND);
define('RADIO_BORDER', CHECKBOX_BORDER);

define('RADIO_FOREGROUND_COLOR_SEC', CHECKBOX_FOREGROUND_COLOR_SEC);
define('RADIO_BACKGROUND_SEC', CHECKBOX_BACKGROUND_SEC);
define('RADIO_BORDER_SEC', CHECKBOX_BORDER_SEC);

/********************** Radiobox ********************/
/****************************************************/

/****************************************************/
/*********************** Tabs ***********************/

define('TAB_ITEM_FOREGROUND_COLOR', INPUT_FOREGROUND_COLOR);
define('TAB_ITEM_BACKGROUND', INPUT_BACKGROUND);
define('TAB_ITEM_BORDER', INPUT_BORDER);

define('TAB_ITEM_FOREGROUND_COLOR_SEC', BUTTONS_FOREGROUND_COLOR_SEC);
define('TAB_ITEM_BACKGROUND_SEC', BUTTONS_BACKGROUND_SEC);
define('TAB_ITEM_BORDER_SEC', 'thin solid rgba(0,0,0,0)');

/*********************** Tabs ***********************/
/****************************************************/




define('LINK_COLOR_ALT', LINK_HOVER_COLOR);
define('LINK_BACKGROUND_ALT', BUTTONS_BACKGROUND);






/*
 **************************************************************************************************
 **************************************************************************************************
 --------------------------------------------------------------------------------------------------
 Styles sets definitions
 --------------------------------------------------------------------------------------------------
 */


/* Default buttons */
define( 'BUTTONS_STYLESHEET', '
	
	' . css_box_sizing() . '
	
	position: relative;
	' . css_display_inline_block() . '
	
	' . BUTTONS_BACKGROUND . '
	' . BUTTONS_COLOR . '
	
	' . BUTTONS_BORDER . '
	
	' . BUTTONS_PADDING . ';
	
	' . DEFAULT_BORDER_RADIUS . '
	
	font-family:  ' . FONT_FAMILY_DEFAULT . ';
	font-size: ' . DEFAULT_FONT_SIZE . ';
	margin: ' . BUTTONS_MARGIN . ';
	margin-bottom: ' . DEFAULT_SPACING . 'px;
	
	line-height:  ' . DEFAULT_LINE_HEIGHT . ';
	
	transition: ' . DEFAULT_TRANSITION . ';
	
	cursor: pointer;
	
	width: auto;
	max-width: 100%;
	
');
define( 'BUTTONS_STYLESHEET_HOVER', '
	
	' . BUTTONS_BACKGROUND_HOVER . '
	' . BUTTONS_COLOR_HOVER . '
	
	' . BUTTONS_BORDER_HOVER . '
	
');

/* Inputs buttons */
define( 'INPUT_BUTTONS_STYLESHEET', BUTTONS_STYLESHEET );
define( 'INPUT_BUTTONS_STYLESHEET_HOVER', BUTTONS_STYLESHEET_HOVER );

/* Selects */
define( 'SELECTS_STYLESHEET', '
	
	' . css_box_sizing() . '
	
	position: relative;
	' . css_display_inline_block() . '
	
	' . SELECTS_BACKGROUND . '
	' . SELECTS_COLOR . '
	
	' . SELECTS_BORDER . '
	
	' . SELECTS_PADDING . ';
	
	font-family:  ' . FONT_FAMILY_DEFAULT . ';
	font-size: ' . DEFAULT_FONT_SIZE . ';
	margin-bottom: ' . DEFAULT_SPACING . 'px;
	
	line-height:  ' . DEFAULT_LINE_HEIGHT . ';

	transition: ' . DEFAULT_TRANSITION . ';
	
	width: auto;
	max-width: 100%;
	
');
define( 'SELECTS_STYLESHEET_HOVER', '
	
	position: relative;
	' . css_display_inline_block() . '
	
	' . SELECTS_BACKGROUND_HOVER . '
	' . SELECTS_COLOR_HOVER . '
	
	' . SELECTS_BORDER_HOVER . '
	
');

/* Inputs text */
define( 'INPUTS_TEXT_STYLESHEET', '
	
	' . css_box_sizing() . '
	
	position: relative;
	' . css_display_inline_block() . '
	
	' . INPUTS_TEXT_BACKGROUND . '
	' . INPUTS_TEXT_COLOR . '
	
	' . INPUTS_TEXT_BORDER . '
	
	' . INPUTS_TEXT_PADDING . ';
	
	font-family:  ' . FONT_FAMILY_DEFAULT . ';
	font-size: ' . DEFAULT_FONT_SIZE . ';
	margin: ' . INPUTS_TEXT_MARGIN . ';
	margin-bottom: ' . DEFAULT_SPACING . 'px;
	
	line-height:  ' . DEFAULT_LINE_HEIGHT . ';
	
	transition: ' . DEFAULT_TRANSITION . ';
	
	cursor:text;
	
	width: auto;
	max-width: 100%;
	
');
define( 'INPUTS_TEXT_STYLESHEET_HOVER', '
	
	' . INPUTS_TEXT_BACKGROUND_HOVER . '
	' . INPUTS_TEXT_COLOR_HOVER . '
	
	' . INPUTS_TEXT_BORDER_HOVER . '
	
');

/* Inputs date */
define( 'INPUTS_DATE_STYLESHEET', '
	
	padding-top: ' . ( INPUTS_TEXT_PADDING_TOP_WIDTH - 1 ) . 'px;
	padding-bottom: ' . ( INPUTS_TEXT_PADDING_BOTTOM_WIDTH - 1 ) . 'px;
	
');

/* Inputs time */
define( 'INPUTS_TIME_STYLESHEET', INPUTS_DATE_STYLESHEET );

/* Inputs datetime */
define( 'INPUTS_DATETIME_STYLESHEET', '' );

/* Inputs datetime-local */
define( 'INPUTS_DATETIMELOCAL_STYLESHEET', INPUTS_DATE_STYLESHEET );

/* Inputs file */
define( 'INPUTS_FILE_STYLESHEET', '
	
	padding-top: ' . ( BUTTONS_PADDING_TOP_WIDTH - 2 ) . 'px;
	padding-bottom: ' . ( BUTTONS_PADDING_BOTTOM_WIDTH + 1 ) . 'px;
	
');

/* Textareas */
define( 'TEXTAREAS_STYLESHEET', '
	
	
	
');

/*
 --------------------------------------------------------------------------------------------------
 Styles sets definitions
 --------------------------------------------------------------------------------------------------
 **************************************************************************************************
 **************************************************************************************************
 */







function css_transition( $at = DEFAULT_TRANSITION ){
	
	return "
	
	-webkit-transition: $at;
	-moz-transition: $at;
	-khtml-transition: $at;
	-ms-transition: $at;
	-o-transition: $at;
	-icab-transition: $at;
	transition: $at;
	
	";
	
}
function css_boxshadow( $bs = DEFAULT_BOX_SHADOW ){
	
	return "
	
	-webkit-box-shadow: $bs; 
	-moz-box-shadow: $bs;
	-khtml-box-shadow: $bs;
	-ms-box-shadow: $bs;
	-o-box-shadow: $bs;
	-icab-box-shadow: $bs;
	box-shadow: $bs;
	
	";
	
}
function css_textshadow( $ts='1px 1px 1px #000' ){
	
	return "
	
	text-shadow: $ts;
	
	";
	
}
function css_display_inline_block(){
	
	return "
	
	display: -moz-inline-stack;
	display: inline-block;
	zoom: 1;
	*display: inline;
	
	";
	
}

function css_box_sizing( $bs = 'border-box' ){
	
	return "
	
	-webkit-box-sizing: $bs;
	-moz-box-sizing: $bs;
	box-sizing: $bs;
	
	";
	
}


function css_border_radius( $tf=null, $tr=null, $br=null, $bl=null ){
	if ($tf){
		$tr=$tr!=null?$tr:$tf;
		$br=$br!=null?$br:$tf;
		$bl=$bl!=null?$bl:$tf;
		
		return "
-webkit-border-radius: $tf $tr $br $bl;
-moz-border-radius: $tf $tr $br $bl;
-khtml-border-radius: $tf $tr $br $bl;
-o-border-radius: $tf $tr $br $bl;
-icab-border-radius: $tf $tr $br $bl;
border-radius: $tf $tr $br $bl;
		";
		
	}
}



?>
@charset "UTF-8";

*{outline-style:none;outline-width:medium;}html,body,div,span,applet,object,iframe,h1,h2,h3,h4,h5,h6,blockquote,pre,a,abbr,acronym,address,big,cite,code,del,dfn,em,font,img,ins,kbd,q,s,samp,small,strike,strong,sub,sup,tt,var,dl,dt,dd,ol,ul,li,fieldset,form,label,legend,table,caption,tbody,tfoot,thead,tr,th,td{margin:0;padding:0;border:0;outline:0;font-style:inherit;font-size:inherit;vertical-align:top;}:focus{outline:0;}body{line-height:1;color:black;background:white;font-size:100.01%;}ol,ul{list-style:none;}table{border-collapse:separate;border-spacing:0;}caption,th,td{text-align:left;font-weight:normal;}blockquote:before,blockquote:after,q:before,q:after{content:\"\";}blockquote,q{quotes: \"\" \"\";}strong{font-weight: bold;}body,input,select,textarea{font-size: inherit;}a *{cursor:pointer}


@import url(http://fonts.googleapis.com/css?family=Junge|Roboto+Condensed:300italic,400italic,700italic,400,300,700);

html,
body{
	
	position:relative;
	display: block;
	background: <?= COLOR_SCHEME_1___BACKGROUND_NORMAL; ?>;
	color: <?= COLOR_SCHEME_1___FOREGROUND_NORMAL; ?>;
	margin: 0;
	font-family: <?= FONT_FAMILY_DEFAULT; ?>;
	font-size: <?= DEFAULT_FONT_SIZE; ?>;
	line-height: <?= DEFAULT_LINE_HEIGHT; ?>;
	
	font-weight: normal;
	
	text-shadow: <?= DEFAULT_TEXT_SHADOW; ?>;
	height: 100%
}

a{
	
	color: <?= COLOR_SCHEME_1___FOREGROUND_LINK; ?>;
	text-decoration: none;
	
}
a:visited{
	
	color: <?= COLOR_SCHEME_1___FOREGROUND_VISITED; ?>;
	
}
a:hover{
	
	color: <?= COLOR_SCHEME_1___FOREGROUND_HOVER; ?>;
	
}
a:active{
	
	color: <?= COLOR_SCHEME_1___FOREGROUND_ACTIVE; ?>;
	
}

p{
	
	position:relative;
	display: block;
	margin: 0 0 <?= DEFAULT_SPACING; ?>px;
	
}

hr{
	
	position:relative;
	display: block;
	clear: both;
	height: 0;
	border: none;
	border-bottom: 1px solid <?= COLOR_G4_5; ?>;
	margin-bottom: <?= DEFAULT_SPACING; ?>px;
	
}




code,
pre,
samp{
	
	position: relative;
	<?= css_display_inline_block(); ?>
	font-family: <?= FONT_FAMILY_DEFAULT_MONO; ?>;
	font-weight: normal;
	padding-left: <?= DEFAULT_SPACING / 2; ?>px;
	padding-right: <?= DEFAULT_SPACING / 2; ?>px;
	background-color: <?= COLOR_G4_7; ?>;
	
}
pre{
	
	position: relative;
	display: block;
	padding: <?= DEFAULT_SPACING; ?>px;
	margin-bottom: <?= DEFAULT_SPACING; ?>px;
	
}
pre code,
pre samp{
	
	font-family: inherit;
	font-weight: inherit;
	padding: 0;
	background: none;
	
}



blockquote,
q{
	
	font-style: italic;
	
}
blockquote:before,
q:before{
	
	content: '';
	
}



h1,h2,h3,h4,h5,h6{
	
	font-family: <?= FONT_FAMILY_SEC; ?>;
	font-size: 245%;
	font-weight: 100;
	line-height: 120%;
	color: <?= COLOR_SCHEME_1___FOREGROUND_NORMAL; ?>;
	margin-bottom: <?= DEFAULT_SPACING; ?>px;
	
}
h2{
	
	font-size: 225%;
	
}
h3{
	
	font-size: 218%;
	
}
h3{
	
	font-size: 198%;
	
}
h4{
	
	font-size: 178%;
	
}
h5{
	
	font-size: 158%;
	
}
h6{
	
	font-size: 138%;
	
}

/*
 **************************************************************************************************
 **************************************************************************************************
 --------------------------------------------------------------------------------------------------
 Figures
 --------------------------------------------------------------------------------------------------
 */

figure{
	
	position: relative;
	display: block;
	float: left;
	
	<?= css_box_sizing(); ?>
	
	font-size: 90%;
	
	margin: 0;
	margin-bottom: <?= DEFAULT_SPACING; ?>px;
	padding: <?= DEFAULT_SPACING; ?>px;
	
}
figcaption{
	
	position: relative;
	display: block;
	float: left;
	
	<?= css_box_sizing(); ?>
	
	font-size: 90%;
	
	margin: 0;
	padding: <?= DEFAULT_SPACING / 2; ?>px;
	padding-left: 0;
	
}

/*
 --------------------------------------------------------------------------------------------------
 Figures
 --------------------------------------------------------------------------------------------------
 **************************************************************************************************
 **************************************************************************************************
 */

/*
 **************************************************************************************************
 **************************************************************************************************
 --------------------------------------------------------------------------------------------------
 Tables
 --------------------------------------------------------------------------------------------------
 */

table{
	
	border-collapse: collapse;
	border: none;
	text-align: center;
	width: 100%;
	font-size: <?= DEFAULT_TABLE_FONT_SIZE; ?>;
	color: <?= COLOR_SCHEME_3__FOREGROUND_NORMAL; ?>;
	margin-bottom: <?= DEFAULT_SPACING; ?>px;
	
}
tr{
	
	border: none;
	
	<?= DEFAULT_TABLE_TR_BACKGROUND; ?>
	
	z-index: 1;
	
}
td,
th,
table caption{
	border: 1px solid <?= COLOR_G4_6; ?>;
	padding: <?= DEFAULT_SPACING/2; ?>px <?= DEFAULT_SPACING; ?>px;
	vertical-align:middle;
	z-index: 1;
}
tr:hover,
td:hover,
th:hover{
	z-index: 2;
}
td:first-child,
th:first-child{
	
}
th,
table caption{
	
	border: none;
	border-bottom: 1px solid <?= COLOR_G4_6; ?>;
	font-weight: 100;
	font-size: 130%;
	text-align: center;
	
	background: <?= COLOR_SCHEME_3__BACKGROUND_NORMAL; ?>;
	
	text-shadow: <?= TEXT_SHADOW_DARK; ?>;
	
}
td:last-child{
	
	border-right: none;
	
}
td:first-child{
	
	border-left: none;
	
}
tr:last-child td{
	
	border-bottom: none;
	
}
tbody:first-child tr:first-child td{
	
	border-top: none;
	
}
td{
	
	position: relative;
	transition: <?= DEFAULT_TRANSITION; ?>;
	
}
th a{
	
	color: <?= COLOR_SCHEME_3__FOREGROUND_LINK; ?>;
	
}
th a:visited{
	
	color: <?= COLOR_SCHEME_3__FOREGROUND_VISITED; ?>;
	
}
th a:hover{
	
	color: <?= COLOR_SCHEME_3__FOREGROUND_HOVER; ?>;
	
}
th a:active{
	
	color: <?= COLOR_SCHEME_3__FOREGROUND_ACTIVE; ?>;
	
}

tr:nth-child(odd){
	
}
tr:nth-child(odd) td{
	
	color: <?= COLOR_SCHEME_3__FOREGROUND_NORMAL; ?>;
	
}
tr:nth-child(even) td{
	
	
	
}
tr.odd{
	color: <?= DEFAULT_TABLE_TD_FOREGROUND_COLOR; ?>;
	
	<?= DEFAULT_TABLE_TR_BACKGROUND_SEC; ?>
	
}
tr.even{
	color: <?= DEFAULT_TABLE_TD_FOREGROUND_COLOR_SEC; ?>;
	
	<?= DEFAULT_TABLE_TR_BACKGROUND; ?>
	
}
tr:hover{
	
	z-index: 2;
	
	border-top-color: <?= COLOR_SCHEME_4___BACKGROUND_NORMAL; ?>;
	border-bottom-color: <?= COLOR_SCHEME_4___BACKGROUND_NORMAL; ?>;
	
}
tr:hover td,
tr:nth-child(even):hover td.order-by-column,
tr:nth-child(odd):hover td.order-by-column{
	
	background-color: <?= COLOR_G5_6; ?>;
	
}
table.no-bg tr td,
table.no-bg tr:nth-child(odd) td,
table.no-bg tr:nth-child(even) td,
table.no-bg tr.odd td,
table.no-bg tr.even td{
	
	background: none;
	
}

/*
 --------------------------------------------------------------------------------------------------
 Tables
 --------------------------------------------------------------------------------------------------
 **************************************************************************************************
 **************************************************************************************************
 */





/*
 **************************************************************************************************
 **************************************************************************************************
 --------------------------------------------------------------------------------------------------
 Inputs de Textos e botões
 --------------------------------------------------------------------------------------------------
 */

input,
input[type=text],
input[type=password],
input[type=file],
input[type=date],
input[type=datetime],
input[type=datetime-local],
input[type=time],
input[type=number],
textarea,
.inputbox,
.cleditorMain,
input[type=submit],
input[type=button],
button,
select,
.button{
	
	<?= BUTTONS_STYLESHEET; ?>;
	
}
input:hover,
input[type=text]:hover,
input[type=password]:hover,
input[type=file]:hover,
input[type=date]:hover,
input[type=datetime]:hover,
input[type=datetime-local]:hover,
input[type=time]:hover,
input[type=number]:hover,
textarea:hover,
.inputbox:hover,
.cleditorMain:hover,
input[type=submit]:hover,
input[type=button]:hover,
button:hover,
select:hover,
.button:hover,

input:focus,
input[type=text]:focus,
input[type=password]:focus,
input[type=file]:focus,
input[type=date]:focus,
input[type=datetime]:focus,
input[type=datetime-local]:focus,
input[type=time]:focus,
input[type=number]:focus,
textarea:focus,
.inputbox:focus,
.cleditorMain:focus,
input[type=submit]:focus,
input[type=button]:focus,
button:focus,
select:focus,
.button:focus{
	
	<?= BUTTONS_STYLESHEET_HOVER; ?>;
	
}

input[type=submit],
input[type=button],
input[type=file],
button,
select,
.button{
	
	<?= INPUT_BUTTONS_STYLESHEET; ?>;
	
}
input[type=submit]:hover,
input[type=button]:hover,
input[type=file]:hover,
button:hover,
select:hover,
.button:hover,

input[type=submit]:focus,
input[type=button]:focus,
input[type=file]:focus,
button:focus,
select:focus,
.button:focus{
	
	<?= INPUT_BUTTONS_STYLESHEET_HOVER; ?>;
	
}

select{
	
	<?= SELECTS_STYLESHEET; ?>;
	
}
select:hover,
select:focus{
	
	<?= SELECTS_STYLESHEET_HOVER; ?>;
	
}
select option{
	
	background: <?= COLOR_SCHEME_1___BACKGROUND_NORMAL; ?>;
	color: <?= COLOR_SCHEME_1___FOREGROUND_NORMAL; ?>;
	border: none;
	
}

input[type=text],
input[type=password],
input[type=file],
input[type=date],
input[type=datetime],
input[type=datetime-local],
input[type=time],
input[type=number],
textarea,
.inputbox,
.cleditorMain{
	
	<?= INPUTS_TEXT_STYLESHEET; ?>;
	
}
input[type=text]:hover,
input[type=password]:hover,
input[type=file]:hover,
input[type=date]:hover,
input[type=datetime]:hover,
input[type=datetime-local]:hover,
input[type=time]:hover,
input[type=number]:hover,
textarea:hover,
.inputbox:hover,
.cleditorMain:hover,

input[type=text]:focus,
input[type=password]:focus,
input[type=file]:focus,
input[type=date]:focus,
input[type=datetime]:focus,
input[type=datetime-local]:focus,
input[type=time]:focus,
input[type=number]:focus,
textarea:focus,
.inputbox:focus,
.cleditorMain:focus{
	
	<?= INPUTS_TEXT_STYLESHEET_HOVER; ?>;
	
}

input[type=date]{
	
	<?= INPUTS_DATE_STYLESHEET; ?>;
	
}

input[type=time]{
	
	<?= INPUTS_TIME_STYLESHEET; ?>;
	
}

input[type=datetime-local]{
	
	<?= INPUTS_DATETIMELOCAL_STYLESHEET; ?>;
	
}

input[type=datetime]{
	
	<?= INPUTS_DATETIME_STYLESHEET; ?>;
	
}

input[type=file]{
	
	<?= INPUTS_FILE_STYLESHEET; ?>;
	
}

textarea{
	
	<?= TEXTAREAS_STYLESHEET; ?>;
	
}

/*
 --------------------------------------------------------------------------------------------------
 Inputs de Textos e botões
 --------------------------------------------------------------------------------------------------
 **************************************************************************************************
 **************************************************************************************************
 */



/*
 **************************************************************************************************
 **************************************************************************************************
 --------------------------------------------------------------------------------------------------
 List items
 --------------------------------------------------------------------------------------------------
 */

ul,
ol{
	
	position:relative;
	display: block;
	margin-bottom: <?= DEFAULT_SPACING; ?>px;
	
}
ul ol,
ul ul,
ol ol,
ol ul{
	
	margin-top: <?= DEFAULT_SPACING / 2; ?>px;
	margin-bottom: 0;
	
}
ol{
	
	counter-reset: my-badass-counter;
	
}
	li{
		
		position: relative;
		display: list-item;
		list-style-position: outside;
		margin-bottom: <?= DEFAULT_SPACING / 2; ?>px;
		margin-left: <?= DEFAULT_SPACING * 2; ?>px;
		padding-left: <?= DEFAULT_SPACING / 2; ?>px;
		list-style-type: disc;
		
	}
		li li{
			
			font-size: 100%;
			
		}
	
	ol li{
		
		display: block;
		margin-left: <?= DEFAULT_SPACING + ( DEFAULT_SPACING * 2 ) - ( DEFAULT_SPACING / 2 ); ?>px;
		padding-left: 0;
		
	}
	ol li:before{
		
		<?= css_box_sizing(); ?>
		
		content: counter( my-badass-counter, decimal );
		counter-increment: my-badass-counter;
		
		position: absolute;
		display: block;
		margin-left: -<?= DEFAULT_SPACING * 1.3; ?>px;
		padding-right: <?= DEFAULT_SPACING + ( DEFAULT_SPACING / 2 ); ?>px;
		text-align: center;
		width: <?= DEFAULT_SPACING * 2; ?>px;
		
		font-family: <?= FONT_FAMILY_SEC; ?>;
		
	}
/*
 --------------------------------------------------------------------------------------------------
 List items
 --------------------------------------------------------------------------------------------------
 **************************************************************************************************
 **************************************************************************************************
 */


#site-block{
	
	position:relative;
	display:block;
	text-align: center;
	
}



<?php

$logo_width = 194;
$logo_height = 110;

?>

#top-block,
#after-banner-block{
	
	position: relative;
	display: block;
	text-align: center;
	background: <?= COLOR_SCHEME_4___BACKGROUND_NORMAL; ?>;
	color: <?= COLOR_SCHEME_3__FOREGROUND_NORMAL; ?>;
	z-index: 3;
	
}
#after-banner-block{
	
	background: <?= COLOR_SCHEME_4___BACKGROUND_ALTERNATE; ?>;
	color: <?= COLOR_SCHEME_4___FOREGROUND_NORMAL; ?>;
	
}
	#top-block > .s1,
	#after-banner-block > .s1{
		
		position: relative;
		display: block;
		text-align: left;
		margin: 0 auto;
		width: <?= SITE_WIDTH; ?>;
		
	}
	.width-500-960 #top-block > .s1,
	.width-500-lower #top-block > .s1,
	.width-500-960 #after-banner-block > .s1,
	.width-500-lower #after-banner-block > .s1{
		
		width:100%;
		
	}
		
		#top-logo{
			
			position: relative;
			display: block;
			width: <?= $logo_width; ?>px;
			height: <?= $logo_height + DEFAULT_SPACING; ?>px;
			
		}
			#logo{
				
				position: relative;
				display: block;
				width: 100%;
				height: 100%;
				background: url('<?= THEME_IMAGE_DIR_URL; ?>logo.png') no-repeat center center;
				background-size: <?= $logo_width; ?>px <?= $logo_height; ?>px;
				
			}
		
		.with-top-banner #top-logo{
			
			position: absolute;
			left: <?= DEFAULT_SPACING * 2; ?>px;
			display: block;
			width: 30%;
			height: <?= 539 - ( DEFAULT_SPACING * 2 ); ?>px;
			
		}
			.with-top-banner #top-logo > .s1{
				
				<?= css_box_sizing(); ?>
				
				position: relative;
				display: block;
				width: 100%;
				overflow: hidden;
				background: <?= COLOR_G3_4; ?>;
				
			}
				.with-top-banner #top-logo > .s1:before{
					
					content: "";
					display: block;
					padding-top: 120%; 	/* initial ratio of 1:1*/
					
				}
			.with-top-banner #logo{
				
				position: absolute;
				top: 0;
				display: block;
				width: 100%;
				height: 100%;
				background: url('<?= THEME_IMAGE_DIR_URL; ?>logo-with-top-banner.png') no-repeat center 40%;
				background-size: 50% auto;
				
			}
		/*
		 **************************************************************************************************
		 **************************************************************************************************
		 --------------------------------------------------------------------------------------------------
		 Top menu
		 --------------------------------------------------------------------------------------------------
		 */
		
		#top-menu,
		#after-banner-menu{
			
			position:relative;
			display: block;
			text-align: center;
			
		}
		#top-menu ul,
		#top-menu li,
		#after-banner-menu ul,
		#after-banner-menu li{
			
			position:relative;
			<?= css_display_inline_block() ?>
			padding:0;
			margin:0;
			z-index:1;
			
		}
		#top-menu ul,
		#after-banner-menu ul{
			
			padding: 0;
			
		}
		#top-menu > ul.menu,
		#after-banner-menu > ul.menu{
			
			<?= css_display_inline_block() ?>
			padding: 0;
			
		}
		#top-menu li:hover,
		#after-banner-menu li:hover{
			z-index:1000;
		}
		#top-menu ul ul,
		#after-banner-menu ul ul{
			position:absolute;
			display:none;
			top:100%;
			left:0;
			
			text-align: left;
			
			background: <?= COLOR_SCHEME_1___BACKGROUND_ALTERNATE; ?>;
			color: <?= COLOR_SCHEME_4___FOREGROUND_NORMAL; ?>;
			
			padding: 0;
			z-index: 1;
			
			<?= css_boxshadow(); ?>
			
		}
		#top-menu li li ul,
		#after-banner-menu li li ul{
			
			position: absolute;
			display: none;
			top: 0px;
			left: 100%;
			
		}
		#top-menu li a,
		#after-banner-menu li a{
			
			position:relative;
			<?= css_display_inline_block() ?>
			font-size:100%;
			color: <?= COLOR_SCHEME_1___BACKGROUND_NORMAL; ?>;
			text-transform: uppercase;
			text-decoration: none;
			text-shadow:none;
			white-space:nowrap;
			padding: <?= DEFAULT_SPACING; ?>px;
			z-index: 2;
			
		}
		#top-menu li li,
		#top-menu li li a,
		#after-banner-menu li li,
		#after-banner-menu li li a{
			
			display:block;
			color: <?= COLOR_SCHEME_4___BACKGROUND_NORMAL; ?>;
			
		}
		#top-menu li li a,
		#after-banner-menu li li a{
			
			padding: <?= DEFAULT_SPACING / 2; ?>px <?= DEFAULT_SPACING * 1.5; ?>px;
			
		}
		#top-menu li.current > a,
		#top-menu li.current:hover > a,
		#top-menu li:hover > a,
		#top-menu li a:hover,
		#top-menu > li:hover,
		#after-banner-menu li.current > a,
		#after-banner-menu li.current:hover > a,
		#after-banner-menu li:hover > a,
		#after-banner-menu li a:hover,
		#after-banner-menu > li:hover{
			
			background: <?= COLOR_SCHEME_1___BACKGROUND_ALTERNATE; ?>;
			color: <?= COLOR_SCHEME_5___BACKGROUND_NORMAL; ?>;
			
		}
		#top-menu li.current > a,
		#after-banner-menu li.current > a{
			
			background: <?= COLOR_SCHEME_1___BACKGROUND_NORMAL; ?>;
			
		}
		#top-menu li.current li.current > a,
		#top-menu li li a:hover,
		#top-menu li li:hover > a,
		#after-banner-menu li.current li.current > a,
		#after-banner-menu li li a:hover,
		#after-banner-menu li li:hover > a{
			
			background: <?= COLOR_SCHEME_4___BACKGROUND_NORMAL; ?>;
			color: <?= COLOR_SCHEME_1___BACKGROUND_ALTERNATE; ?>;
			
		}
		#top-menu li:hover > ul,
		#after-banner-menu li:hover > ul{
			
			display: block;
			
		}
		
		
		/*
		 --------------------------------------------------------------------------------------------------
		 Top menu
		 --------------------------------------------------------------------------------------------------
		 **************************************************************************************************
		 **************************************************************************************************
		 */
		
		

#top-banner-block{
	
	position: relative;
	display: block;
	text-align: center;
	
	background: <?= COLOR_SCHEME_2___BACKGROUND_NORMAL; ?>;
	color: <?= COLOR_SCHEME_2___FOREGROUND_NORMAL; ?>;
	
	z-index: 1;
	
}
	#top-banner-block > .s1{
		
		position: relative;
		<?= css_display_inline_block() ?>
		text-align: left;
		margin: 0 auto;
		width: <?= SITE_WIDTH; ?>;
		
	}
	.width-500-960 #top-banner-block > .s1,
	.width-500-lower #top-banner-block > .s1{
		
		width:100%;
		
	}






	
	
	
#content-block{
	
	position:relative;
	display: block;
	margin: 0 auto;
	text-align: center;
	
}
	#content-block ul{
		
	}
		#content-block li{
			
		}
			#content-block li li{
				
			}
	#content-block > .s1{
		
		position:relative;
		<?= css_display_inline_block() ?>
		margin:0 auto;
		text-align: left;
		width: <?= SITE_WIDTH; ?>;
		
		background: <?= COLOR_SCHEME_1___BACKGROUND_NORMAL; ?>;
		
	}
	.width-500-960 #content-block > .s1,
	.width-500-lower #content-block > .s1{
		
		width:100%;
		
	}
		#content-block > .s1 > .s2{
			
			position:relative;
			display: block;
			
		}
		
		.component-heading{
			
			position:relative;
			display: block;
			padding: <?= DEFAULT_SPACING; ?>px;
			border-bottom: solid 3px <?= COLOR_SCHEME_2___BACKGROUND_NORMAL; ?>;
			
		}
		.component-heading h1{
			
			position:relative;
			display: block;
			margin: 0;
			
		}
		#component-content{
			
			position:relative;
			display: block;
			padding: <?= DEFAULT_SPACING * 2; ?>px;
			
		}
		#component-content .content-info{
			
			position:relative;
			display: block;
			text-align: right;
			margin-bottom: <?= DEFAULT_SPACING; ?>px;
			
		}
		
		#component-content .content-info .content-info-item{
			
			position:relative;
			<?= css_display_inline_block() ?>
			font-size:90%;
			opacity: .5;
			padding: 0 <?= DEFAULT_SPACING; ?>px 0 0;
			
		}
		
		
		

/*
 **************************************************************************************************
 **************************************************************************************************
 --------------------------------------------------------------------------------------------------
 Bottom content block
 --------------------------------------------------------------------------------------------------
 */

#bottom-content-block{
	
	position: relative;
	display: block;
	text-align: center;
	
	margin-bottom:<?= DEFAULT_SPACING * 2; ?>px;
	margin-top:<?= DEFAULT_SPACING * 2; ?>px;
	
	z-index: 1;
	
	background: <?= COLOR_G1_8; ?>;
	color: <?= COLOR_G7_1; ?>;
	
}
	#bottom-content-block > .s1{
		
		position: relative;
		<?= css_display_inline_block() ?>
		text-align: left;
		margin: 0 auto;
		width: <?= SITE_WIDTH; ?>;
		
	}
	
		#bottom-content-block > .s1 > .s2{
			
			position:relative;
			display: block;
			
		}
		
/*
 --------------------------------------------------------------------------------------------------
 Bottom content block
 --------------------------------------------------------------------------------------------------
 **************************************************************************************************
 **************************************************************************************************
 */




#footer-block{
	
	position: relative;
	display: block;
	text-align: center;
	
	background: <?= COLOR_SCHEME_2___BACKGROUND_NORMAL; ?>;
	color: <?= COLOR_G7_1; ?>;
	
	z-index: 1;
	
}
	#footer-block h1,
	#footer-block h2,
	#footer-block h3,
	#footer-block h4,
	#footer-block h5,
	#footer-block h6{
		
		color: <?= COLOR_G7_1; ?>;
		
	}
	#footer-block a{
		
		color: <?= COLOR_G7_1; ?>;
		
	}
	#footer-block a:hover,
	#footer-block a:focus{
		
		color: <?= COLOR_G7_1; ?>;
		text-decoration: underline;
		
	}
	#footer-block > .s1{
		
		position: relative;
		<?= css_display_inline_block() ?>
		margin: 0 auto;
		text-align: left;
		width: <?= SITE_WIDTH; ?>;
		
	}
	.width-500-960 #footer-block > .s1,
	.width-500-lower #footer-block > .s1{
		
		width:100%;
		
	}
		#footer-block .footer-col{
			
			
			<?= css_box_sizing(); ?>
			
			
			position:relative;
			<?= css_display_inline_block() ?>
			margin:0 auto;
			padding: 15px;
			text-align: left;
			width: 100%;
			
		}
		#footer-block.footer-2-cols .footer-col{
			
			width: 50%;
			
		}

		#footer-block.footer-3-cols .footer-col{
			
			width: 33.333333%;
			
		}

#footer-block .list-info-wrapper-website-wwwfacebookcom,
#footer-block .list-info-wrapper-website-facebookcom{
	
	background: url('<?= THEME_IMAGE_DIR_URL; ?>icon-website-facebook.png') no-repeat center center;
	
}
#footer-block .list-info-wrapper-website-wwwplusgooglecom
#footer-block .list-info-wrapper-website-plusgooglecom{
	
	background: url('<?= THEME_IMAGE_DIR_URL; ?>icon-website-plusgoogle.png') no-repeat center center;
	
}
#footer-block .list-info-wrapper-website-twittercom,
#footer-block .list-info-wrapper-website-wwwtwittercom{
	
	background: url('<?= THEME_IMAGE_DIR_URL; ?>icon-website-twitter.png') no-repeat center center;
	
}
#footer-block .list-info-wrapper-website-youtubecom,
#footer-block .list-info-wrapper-website-wwwyoutubecom{
	
	background: url('<?= THEME_IMAGE_DIR_URL; ?>icon-website-youtube.png') no-repeat center center;
	
}
#footer-block .list-info-wrapper-website-facebookcom,
#footer-block .list-info-wrapper-website-wwwfacebookcom,
#footer-block .list-info-wrapper-website-plusgooglecom,
#footer-block .list-info-wrapper-website-twittercom,
#footer-block .list-info-wrapper-website-wwwtwittercom,
#footer-block .list-info-wrapper-website-youtubecom,
#footer-block .list-info-wrapper-website-wwwyoutubecom{
	
	background-size: 48px;
	
}







.form-actions,
.pagination{
	position:relative;
	display:block;
	margin-bottom:<?= DEFAULT_SPACING; ?>px;
	text-align:center;
	padding:<?= DEFAULT_SPACING; ?>px;
	background: #f9f9f9;
	<?= DEFAULT_BORDER_RADIUS; ?>
}
.form-actions .button,
.pagination .button{
	text-align:center;
	margin-bottom:0;
}
.pagination .current,
.pagination .inactive{
	opacity:.4;
}






label,
.fake-label{
	
	position:relative;
	display:block;
	
	font-size:87%;
	
	margin-bottom:<?= DEFAULT_SPACING/2; ?>px;
	
}
.label-content{
	position:relative;
	display:block;
	
	margin-bottom:<?= DEFAULT_SPACING/2; ?>px;
}
label.switch .label-content{
	<?= css_display_inline_block() ?>
	margin-bottom: 0;
	line-height: 2em;
}
label.checkbox-sub-item{
	margin-left: <?= DEFAULT_SPACING*2; ?>px;
}










tr.status-3{
	color:rgba(0,0,0,.5);
}
tr.status-3 td,
tr.status-3.priority-0 td.priority,
tr.status-3.priority-1 td.priority,
tr.status-3.priority-2 td.priority{
	background:rgba(101,160,41,0.2);
}

td.priority,
td.status,
td.operations{
	text-align:center;
	white-space: nowrap;
	vertical-align:middle;
}

tr.category-level-1 td.category-title{
	padding-left:0;
}
tr.category-level-2 td.category-title{
	padding-left:30px;
}
.btn.btn-sub-category{
	min-height:.9em;
	height:.9em;
	padding-top:0;
	padding-bottom:0;
	background: url('../../../../images/themes/site/default/icon-24-sub-category.png') no-repeat center center;
}



.btn{
	position:relative;
	<?= css_display_inline_block() ?>
	padding:5px;
	border-radius: 3px;
	text-indent: -10000px;
	min-width:24px;
	min-height:24px;
	border:none;
	text-decoration:none;
	outline: none;
}
a.btn-status-0{
	background: url('../../../../images/themes/site/default/icon-24-in-progress.png') no-repeat center center;
}
a.btn-status-3{
	background: url('../../../../images/themes/site/default/icon-24-done.png') no-repeat center center;
}

a.btn-priority-0{
	background: url('../../../../images/themes/site/default/icon-24-low.png') no-repeat center center;
}
a.btn-priority-1{
	background: url('../../../../images/themes/site/default/icon-24-normal.png') no-repeat center center;
}
a.btn-priority-2{
	background: url('../../../../images/themes/site/default/icon-24-important.png') no-repeat center center;
}
tr.status-3 a.priority,
tr.status-3 a.btn{
	opacity:0.5;
}

a.btn-edit{
	background: url('../../../../images/themes/site/default/icon-24-edit.png') no-repeat center center;
}
a.btn-edit:hover{
	background: url('../../../../images/themes/site/default/icon-24-edit-hover.png') no-repeat center center;
}
a.btn-delete{
	background: url('../../../../images/themes/site/default/icon-24-delete.png') no-repeat center center;
}
a.btn-delete:hover{
	background: url('../../../../images/themes/site/default/icon-24-delete-hover.png') no-repeat center center;
}
a.btn-view{
	background: url('../../../../images/themes/site/default/icon-24-view.png') no-repeat center center;
}
a.btn-view:hover{
	background: url('../../../../images/themes/site/default/icon-24-view-hover.png') no-repeat center center;
}
a.btn:hover,
tr.status-3 a.btn:hover{
	background-color:#fff;
	opacity:1;
	
	-webkit-box-shadow: 0px 2px 5px 0px rgba(0, 0, 0, 0.2);
	box-shadow: 0px 2px 5px 0px rgba(0, 0, 0, 0.2);
}







/*
 **************************************************************************************************
 **************************************************************************************************
 --------------------------------------------------------------------------------------------------
 Tabs
 --------------------------------------------------------------------------------------------------
 */

.tabs-on .tabs-container{
	
	position: relative;
	display: block;
	text-align: center;
	margin: 0;
	
	z-index: 1;
	
}
.tabs-on .tabs-container .tab-item-wrapper{
	
	position: relative;
	<?= css_display_inline_block() ?>
	text-align: center;
	margin: 0;
	padding: 0;
	
}
.tabs-on .tabs-container .tab-item-wrapper .tab-item{
	
	position: relative;
	display: block;
	text-align: center;
	padding: <?= DEFAULT_SPACING; ?>px;
	margin: 0;
	
}
.tabs-on .tabs-container .tab-item-wrapper .tab-item:hover,
.tabs-on .tabs-container .tab-item-wrapper .active{
	
	background: <?= COLOR_SCHEME_1___BACKGROUND_ALTERNATE; ?>;
	
}

/*
 --------------------------------------------------------------------------------------------------
 Tabs
 --------------------------------------------------------------------------------------------------
 **************************************************************************************************
 **************************************************************************************************
 */







/*
 **************************************************************************************************
 **************************************************************************************************
 --------------------------------------------------------------------------------------------------
 Messages
 --------------------------------------------------------------------------------------------------
 */

#msg-block{
	
	position: relative;
	display: block;
	text-align: center;
	
	z-index: 1;
	
}
	#msg-block > .s1{
		
		position: relative;
		<?= css_display_inline_block() ?>
		text-align: left;
		margin: 0 auto;
		width: <?= SITE_WIDTH; ?>;
		
	}
.msg{
	
	background: <?= COLOR_G6_1; ?>;
	padding-bottom: <?= DEFAULT_SPACING; ?>px;
	
}
.msg-item{
	
	
}
.msg-type-title{
	
	padding: <?= DEFAULT_SPACING; ?>px;
	font-family: <?= FONT_FAMILY_SEC; ?>;
	font-size: 150%;
	font-weight: 100;
	
}
.msg-type-error,
.msg-type-success{
	
	padding: <?= DEFAULT_SPACING; ?>px;
	background: <?= COLOR_G5_5; ?>;
	color: <?= COLOR_G2_2; ?>;
	
}
.msg-type-success{
	
	padding: <?= DEFAULT_SPACING; ?>px;
	background: <?= COLOR_G6_6; ?>;
	color: <?= COLOR_G6_5; ?>;
	
}

/*
 --------------------------------------------------------------------------------------------------
 Messages
 --------------------------------------------------------------------------------------------------
 **************************************************************************************************
 **************************************************************************************************
 */




/*
 **************************************************************************************************
 **************************************************************************************************
 --------------------------------------------------------------------------------------------------
 Articles module
 --------------------------------------------------------------------------------------------------
 */

.articles-module-wrapper{
	
	<?= css_box_sizing(); ?>
	
	position: relative;
	display: block;
	
	text-align: left;
	
}
.articles-module-wrapper .article-wrapper{
	
	<?= css_box_sizing(); ?>
	
	position: relative;
	display: table;
	
	overflow: hidden;
	
	min-height: 2em;
	
	width: 100%;
	
}
.articles-module-wrapper:before{
	
	content: '';
	
	position: absolute;
	
	left: 0;
	top: -<?= DEFAULT_SPACING * 2; ?>px;
	height: <?= DEFAULT_SPACING * 2; ?>px;
	width: 20%;
	background: <?= COLOR_G1_8; ?>;
	
}
.articles-module-wrapper:after{
	
	content: '';
	
	position: absolute;
	
	left: 0;
	bottom: -<?= DEFAULT_SPACING * 2; ?>px;
	height: <?= DEFAULT_SPACING * 2; ?>px;
	width: 20%;
	background: <?= COLOR_G1_8; ?>;
	
}
.articles-module-wrapper .article-title-wrapper{
	
	<?= css_box_sizing(); ?>
	
	position: relative;
	display: table-cell;
	
	vertical-align: middle;
	
	padding: <?= DEFAULT_SPACING; ?>px <?= DEFAULT_SPACING * 2; ?>px;
	
	text-align: center;
	
	background: <?= COLOR_SCHEME_2___BACKGROUND_NORMAL; ?>;
	color: <?= COLOR_G3_2; ?>;
	
	width: 20%;
	height: 100%;
	
}
.articles-module-wrapper .article-title-wrapper a{
	
	color: <?= COLOR_G3_2; ?>;
	
}
.articles-module-wrapper .article-title-wrapper h5{
	
	font-size: 130%;
	margin: 0;
	
}
.articles-module-wrapper .article-content-wrapper{
	
	<?= css_box_sizing(); ?>
	
	position: relative;
	display: table-cell;
	
	padding: <?= DEFAULT_SPACING; ?>px;
	margin: 0;
	height: 100%;
	
	vertical-align: middle;
	
	color: <?= COLOR_SCHEME_1___FOREGROUND_NORMAL; ?>;
	
}
.articles-module-wrapper .article-content-wrapper .article-content p:last-child{
	
	margin: 0;
	
}

.articles-module-wrapper .article-read-more-link-wrapper{
	
	<?= css_box_sizing(); ?>
	
	position: relative;
	display: table-cell;
	
	overflow: hidden;
	
	margin: 0;
	height: 100%;
	width: 10%;
	
	color: <?= COLOR_SCHEME_1___FOREGROUND_NORMAL; ?>;
	
}

.articles-module-wrapper .article-read-more-link-wrapper a{
	
	<?= css_box_sizing(); ?>
	
	position: absolute;
	display: table-cell;
	
	padding: <?= DEFAULT_SPACING; ?>px;
	margin: 0;
	height: 100%;
	width: 100%;
	
	overflow: hidden;
	
	text-indent: 1000%;
	color: <?= COLOR_G3_3; ?>;
	
	color: <?= COLOR_SCHEME_1___FOREGROUND_NORMAL; ?>;
	background: url('<?= THEME_IMAGE_DIR_URL; ?>articles-module-icon-32-readmore.png') no-repeat center center <?= COLOR_G4_7; ?>;
	background-size: 32px;
	
}
.articles-module-wrapper .article-read-more-link-wrapper a:hover{
	
	background-image: url('<?= THEME_IMAGE_DIR_URL; ?>articles-module-icon-32-readmore-dark.png');
	background-size: 32px;
	background-position: center center;
	background-repeat: no-repeat;
	background-color: <?= COLOR_SCHEME_5___BACKGROUND_NORMAL; ?>;
	
}
.articles-module-wrapper .article-read-more-link-wrapper a:after{
	
	display: none;
	
}



.articles-module-wrapper.layout-testimonials_horizontal .article-title-wrapper a:before{
	
	content: '';
	
	position: relative;
	display: block;
	
	margin-bottom: <?= DEFAULT_SPACING; ?>px;
	
	height: 37px;
	
	background: url('<?= THEME_IMAGE_DIR_URL; ?>articles-module-layout-testimonials-32-quote.png') no-repeat center center;
	background-size: auto;
	
}

.articles-module-wrapper .article-content-wrapper{
	
	<?= css_box_sizing(); ?>
	
	position: relative;
	display: table-cell;
	
	padding: <?= DEFAULT_SPACING; ?>px;
	margin: 0;
	height: 100%;
	
	vertical-align: middle;
	
	color: <?= COLOR_SCHEME_1___FOREGROUND_NORMAL; ?>;
	
}
.articles-module-wrapper.layout-testimonials_horizontal .article-content-wrapper{
	
	font-style: italic;
	
}

/*
 --------------------------------------------------------------------------------------------------
 Articles module
 --------------------------------------------------------------------------------------------------
 **************************************************************************************************
 **************************************************************************************************
 */



/*
 **************************************************************************************************
 **************************************************************************************************
 --------------------------------------------------------------------------------------------------
Articles component
 --------------------------------------------------------------------------------------------------
 */

.article-wrapper{
	
	<?= css_box_sizing(); ?>
	
	position: relative;
	<?= css_display_inline_block(); ?>
	
	overflow: hidden;
	
}
.article-detail #component-content{
	
	background: <?= COLOR_SCHEME_1___BACKGROUND_ALTERNATE; ?>;
	
}
.article-detail .component-heading{
	
}

.articles-wrapper .columns-1{
	
	width: 100%;
	
}
.article-wrapper.columns-1{
	
	display: block;
	
}
.articles-wrapper .columns-2{
	
	width: 50%;
	
}
.articles-wrapper .columns-3{
	
	width: 33%;
	
}
.articles-wrapper .columns-4{
	
	width: 25%;
	
}
.articles-wrapper .columns-5{
	
	width: 20%;
	
}
.articles-wrapper .columns-6{
	
	width: 16.6%;
	
}

.article-list{
	
	text-align: center;
	
}
.article-list .component-heading,
.article-list .article-wrapper{
	
	text-align: left;
	
}
.article-list .article-wrapper{
	
}
.article-list .article-wrapper.no-full-text.no-intro-text.no-readmore{
	
	text-align: center;
	
}


.article-list .article-wrapper .article-title-wrapper,
.article-list .article-wrapper .article-info-wrapper,
.article-list .article-wrapper .article-content-wrapper,
.article-list .article-wrapper .article-image-wrapper{
	
	<?= css_box_sizing(); ?>
	
	position: relative;
	display: block;
	
	padding: <?= DEFAULT_SPACING; ?>px <?= DEFAULT_SPACING * 2; ?>px;
	
	background: <?= COLOR_SCHEME_1___BACKGROUND_ALTERNATE; ?>;
	
}
.article-list .article-wrapper .article-image-wrapper{
	
	padding: 0 <?= DEFAULT_SPACING * 2; ?>px <?= DEFAULT_SPACING; ?>px;
	
	background: none;
	
}
.article-list .article-image-wrapper:before{
	
	content: "";
	
	position:  absolute;
	
	top: 50%;
	left: 0;
	bottom: 0;
	right: 0;
	
	background: <?= COLOR_G3_2; ?>;
	
}
.article-list .article-image-wrapper .s1{
	
	<?= css_box_sizing(); ?>
	
	<?= css_border_radius( '100%' ); ?>
	
	position: relative;
	display: block;
	width: 100%;
	overflow: hidden;
	
}
.article-list .article-image-wrapper .s1:before{
	
	content: "";
	display: block;
	padding-top: 100%; 	/* initial ratio of 1:1*/
	
}
.article-list .article-image-content{
	
	position:  absolute;
	top: 0;
	left: 0;
	bottom: 0;
	right: 0;
	
}
.article-list .article-image-content img{
	
	width: 100%;
	
}



.article-list .article-wrapper .item{
	
	<?= css_box_sizing(); ?>
	
	position: relative;
	display: block;
	
	margin: <?= DEFAULT_SPACING / 2; ?>px;
	
}
.tabs-on .article-list .article-wrapper .item{
	
	margin: 0;
	
}
.article-list .article-wrapper figure{
	
	float: none;
	
}
.article-list .article-wrapper .article-title-wrapper{
	
	border-bottom: 3px solid <?= COLOR_G4_7; ?>;
	
}
.article-list .article-wrapper.no-image .article-title-wrapper{
	
	background: <?= COLOR_G3_2; ?>;
	border-bottom: none;
	
}
.article-list .article-wrapper.no-full-text.no-intro-text.no-readmore.columns-4 .article-title-wrapper{
	
	font-size: 70%;
	
}
.article-list .article-wrapper.no-full-text.no-intro-text.no-readmore.columns-3 .article-title-wrapper{
	
}
.article-list .article-wrapper .article-title-wrapper .article-title{
	
	margin: 0;
	
}
.article-list .article-wrapper .article-content-wrapper p:last-child{
	
	margin: 0;
	
}

.article-read-more-link-wrapper{
	
	position: relative;
	display: block;
	
}
.article-read-more-link-wrapper a{
	
	position: relative;
	display: block;
	
	text-align: center;
	text-indent: -32px;
	font-size: 120%;
	text-transform: uppercase;
	
	font-weight: 100;
	
	background: <?= COLOR_SCHEME_5___BACKGROUND_NORMAL; ?>;
	color: <?= COLOR_SCHEME_5___FOREGROUND_NORMAL; ?>;
	
	transition: <?= DEFAULT_TRANSITION; ?>;
	
	padding: <?= DEFAULT_SPACING; ?>px;
	
}
.article-read-more-link-wrapper a:after{
	
	content: '';
	
	position: absolute;
	<?= css_display_inline_block() ?>
	
	top: 50%;
	margin-top: -16px;
	margin-left: <?= DEFAULT_SPACING/2; ?>px;
	
	width: 32px;
	height: 32px;
	
	background: url('<?= THEME_IMAGE_DIR_URL; ?>icon-32-readmore.png') no-repeat center center;
	background-size: 32px;
	
}
.article-read-more-link-wrapper a:hover{
	
	background: <?= COLOR_SCHEME_1___FOREGROUND_LINK; ?>;
	color: <?= COLOR_SCHEME_5___FOREGROUND_NORMAL; ?>;
	
}

/*
 --------------------------------------------------------------------------------------------------
Articles component
 --------------------------------------------------------------------------------------------------
 **************************************************************************************************
 **************************************************************************************************
 */



/*
 **************************************************************************************************
 **************************************************************************************************
 --------------------------------------------------------------------------------------------------
Forms
 --------------------------------------------------------------------------------------------------
 */

.submit-form #component-content{
	
	background: <?= COLOR_SCHEME_1___BACKGROUND_ALTERNATE; ?>;
	overflow: auto;
	
}

.submit-form .submit-form-field-wrapper{
	
	display: block;
	
	float: left;
	
	position: relative;
	<?= css_display_inline_block() ?>;
	
	width: 33.3333333%;
	
	
}
.submit-form .submit-form-field-control{
	
	display: block;
	width: 100%;
	margin-left: auto;
	margin-right: auto;
	
}
.submit-form .form-element{
	
	<?= css_display_inline_block() ?>;
	width: 90%;
	text-align: left;
	
}
.submit-form .submit-form-field-wrapper label{
	
	text-align: left;
	
}
.submit-form .submit-form-field-wrapper-html,
.submit-form .submit-form-field-wrapper-textarea,
.submit-form .submit-form-field-wrapper-button{
	
	width: 100%;
	
}
.submit-form .submit-form-field-wrapper-button,
.submit-form .submit-form-field-wrapper-textarea{
	
}
.submit-form .submit-form-field-wrapper-button .form-element{
	
	text-align: center;
	
}
.submit-form .submit-form-field-wrapper-textarea .form-element,
.submit-form .submit-form-field-wrapper-button .form-element{
	
	width: 96.7%;
	
}

/*
 --------------------------------------------------------------------------------------------------
Forms
 --------------------------------------------------------------------------------------------------
 **************************************************************************************************
 **************************************************************************************************
 */






.fb_iframe_widget span{
	
}
.fb-comments span iframe{
	
	overflow: auto;
	height: 110px;
	
}






@media screen and ( min-width: 1280px ) and ( max-width: 1400px ) {
	
	#top-block > .s1,
	#top-banner-block > .s1,
	#after_banner-block > .s1,
	#content-block > .s1{
		
		width: 80%;
		
	}
	
}

@media screen and ( min-width: 960px ) and ( max-width: 1280px ) {
	
	#top-block > .s1,
	#top-banner-block > .s1,
	#content-block > .s1{
		
		width: 90%;
		
	}
	
}

@media screen and ( max-width: 960px ) {
	
	#top-block > .s1,
	#top-banner-block > .s1,
	#after-banner-block > .s1,
	#content-block > .s1{
		
		width: 100%;
		
	}
	
	/*
	 **************************************************************************************************
	 **************************************************************************************************
	 --------------------------------------------------------------------------------------------------
	 Top menu
	 --------------------------------------------------------------------------------------------------
	 */
	
	#top-menu,
	#after-banner-menu{
		
		position: fixed;
		display: block;
		right: 10px;
		top: 10px;
		width: 40px;
		max-width: 300px;
		height: 0;
		overflow: hidden;
		padding-top: 70px;
		
		text-align: left;
		
		background: url('../images/icon-menu-mobile-top.png') no-repeat center center <?= COLOR_SCHEME_5___BACKGROUND_NORMAL; ?>;
		color: <?= COLOR_SCHEME_5___FOREGROUND_NORMAL; ?>;
		
		<?= css_transition(); ?>
		<?= css_boxshadow(); ?>
		
		border-left: 0 solid <?= COLOR_SCHEME_5___BACKGROUND_NORMAL; ?>;
		
	}
	#top-menu:hover,
	#after-banner-menu:hover{
		
		width: 80%;
		height: auto;
		bottom: 10px;
		padding: 10px 10px 10px 0;
		overflow: auto;
		
		background: <?= COLOR_SCHEME_2___BACKGROUND_NORMAL; ?>;
		color: <?= COLOR_SCHEME_2___FOREGROUND_NORMAL; ?>;
		
		border-left: 3px solid <?= COLOR_SCHEME_5___BACKGROUND_NORMAL; ?>;
		
	}
	#top-menu ul.menu,
	#top-menu ul.menu li,
	#top-menu ul.menu a,
	#top-menu ul.menu ul,
	#after-banner-menu ul.menu,
	#after-banner-menu ul.menu li,
	#after-banner-menu ul.menu a,
	#after-banner-menu ul.menu ul{
		
		position: relative;
		display: block;
		overflow: visible;
		background: none;
		<?= css_boxshadow( 'none' ); ?>
		
	}
	#top-menu > ul.menu,
	#after-banner-menu > ul.menu{
		
		background: <?= COLOR_SCHEME_2___BACKGROUND_NORMAL; ?>;
		color: <?= COLOR_SCHEME_2___FOREGROUND_NORMAL; ?>;
		display: none;
		
	}
	#top-menu:hover > ul,
	#after-banner-menu:hover > ul{
		
		display: block;
		
	}
	#top-menu ul li,
	#after-banner-menu ul li{
		
		
		
	}
	#top-menu li.hash-link > a,
	#top-menu li > a[href="#"],
	#after-banner-menu li.hash-link > a,
	#after-banner-menu li > a[href="#"]{
		
		display: none;
		
	}
	#top-menu li a,
	#after-banner-menu li a,
	#top-menu li li a,
	#after-banner-menu li li a{
		
		background: <?= COLOR_SCHEME_5___BACKGROUND_NORMAL; ?>;
		color: <?= COLOR_SCHEME_5___FOREGROUND_NORMAL; ?>;
		
	}
	#top-menu li li a,
	#after-banner-menu li li a{
		
		background: <?= COLOR_SCHEME_5___BACKGROUND_NORMAL; ?>;
		color: <?= COLOR_SCHEME_5___FOREGROUND_NORMAL; ?>;
		margin-left:  <?= DEFAULT_SPACING / 2; ?>px;
		padding: <?= DEFAULT_SPACING / 1.5; ?>px <?= DEFAULT_SPACING * 1.5; ?>px;
		
	}
	#top-menu li.current > a,
	#top-menu li:hover > a,
	#top-menu li a:hover,
	#top-menu li:hover + a,
	#after-banner-menu li.current > a,
	#after-banner-menu li:hover > a,
	#after-banner-menu li a:hover,
	#after-banner-menu li:hover + a{
		
		background: <?= COLOR_SCHEME_5___FOREGROUND_NORMAL; ?>;
		color: <?= COLOR_SCHEME_5___BACKGROUND_NORMAL; ?>;
		
	}
	#top-menu li.current li.current > a,
	#top-menu li li a:hover,
	#after-banner-menu li.current li.current > a,
	#after-banner-menu li li a:hover{
		
		background: <?= COLOR_SCHEME_5___FOREGROUND_NORMAL; ?>;
		color: <?= COLOR_SCHEME_5___BACKGROUND_NORMAL; ?>;
		
	}
	#top-menu li li ul,
	#after-banner-menu li li ul{
		
		position: relative;
		display: block;
		top: auto;
		left: auto;
		
	}
	#top-menu ul.menu a,
	#after-banner-menu ul.menu a{
		
		<?= css_transition(); ?>
		white-space: normal;
	}
	
	/*
	 --------------------------------------------------------------------------------------------------
	 Top menu
	 --------------------------------------------------------------------------------------------------
	 **************************************************************************************************
	 **************************************************************************************************
	 */
	
	
}


