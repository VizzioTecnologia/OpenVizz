<?php

if ( ! defined( 'VUI_SCALE' ) ) define( 'VUI_SCALE', 0.9 );
if ( ! defined( 'VUI_SPACING' ) ) define( 'VUI_SPACING', 1 * VUI_SCALE );

if ( ! defined( 'VUI_DEFAULT_FONT_FAMILY' ) ) define( 'VUI_DEFAULT_FONT_FAMILY', '\'Open Sans\', \'Arial\', sans-serif' );
if ( ! defined( 'VUI_SEC_FONT_FAMILY' ) ) define( 'VUI_SEC_FONT_FAMILY', '\'Open Sans Condensed\', \'Arial\', sans-serif' );
if ( ! defined( 'VUI_MONO_FONT_FAMILY' ) ) define( 'VUI_MONO_FONT_FAMILY', '\'Droid Sans Mono\', \'Menlo\', \'Monaco\', monospace' );

if ( ! defined( 'VUI_DEFAULT_FONT_COLOR' ) ) define( 'VUI_DEFAULT_FONT_COLOR', $vui->colors->vui_site_fg->rgba_s() );
if ( ! defined( 'VUI_SEC_FONT_COLOR' ) ) define( 'VUI_SEC_FONT_COLOR', $vui->colors->vui_site_fg->rgba_s() );

if ( ! defined( 'VUI_DEFAULT_FONT_SIZE' ) ) define( 'VUI_DEFAULT_FONT_SIZE', ( 1 * VUI_SCALE ) . 'em' );
if ( ! defined( 'VUI_DEFAULT_FONT_WEIGHT' ) ) define( 'VUI_DEFAULT_FONT_WEIGHT', '300' );
if ( ! defined( 'VUI_DEFAULT_LINE_HEIGHT' ) ) define( 'VUI_DEFAULT_LINE_HEIGHT', ( 1.5 * VUI_SCALE ) . 'em' );

if ( ! defined( 'VUI_DEFAULT_BORDER' ) ) define( 'VUI_DEFAULT_BORDER', '1px solid ' . $vui->colors->vui_extra_3->rgba_s( 40 ) );
if ( ! defined( 'VUI_SEC_BORDER' ) ) define( 'VUI_SEC_BORDER', '1px solid ' . $vui->colors->vui_lighter->rgba_s( 200 ) );

if ( ! defined( 'VUI_DEFAULT_BORDER_RADIUS' ) ) define( 'VUI_DEFAULT_BORDER_RADIUS', '0.25em' );

if ( ! defined( 'VUI_D_TT' ) ) define( 'VUI_D_TT', '0.3s' ); // default transition time
if ( ! defined( 'VUI_D_TTF' ) ) define( 'VUI_D_TTF', 'ease-in-out' ); // default transition timing function
if ( ! defined( 'VUI_D_TS' ) ) define( 'VUI_D_TS', VUI_D_TT . ' ' . VUI_D_TTF ); // default transition suffix
if ( ! defined( 'VUI_DEFAULT_TRANSITION' ) ) define( 'VUI_DEFAULT_TRANSITION', 'all ' . VUI_D_TS );

if ( ! defined( 'VUI_DEFAULT_SITE_WIDTH' ) ) define( 'VUI_DEFAULT_SITE_WIDTH', '70%' );
if ( ! defined( 'VUI_SITE_WIDTH_480_L' ) ) define( 'VUI_SITE_WIDTH_480_L', '95%' );
if ( ! defined( 'VUI_SITE_WIDTH_480_640' ) ) define( 'VUI_SITE_WIDTH_480_640', '90%' );
if ( ! defined( 'VUI_SITE_WIDTH_640_960' ) ) define( 'VUI_SITE_WIDTH_640_960', '90%' );
if ( ! defined( 'VUI_SITE_WIDTH_960_1280' ) ) define( 'VUI_SITE_WIDTH_960_1280', '90%' );
if ( ! defined( 'VUI_SITE_WIDTH_1280_1400' ) ) define( 'VUI_SITE_WIDTH_1280_1400', '80%' );
if ( ! defined( 'VUI_SITE_WIDTH_1400_1920' ) ) define( 'VUI_SITE_WIDTH_1400_1920', '75%' );




//------------------------------------------------------
//------------------------------------------------------
// Inputs text

if ( ! defined( 'VUI_INPUTS_FONT_FAMILY' ) ) define( 'VUI_INPUTS_FONT_FAMILY', VUI_DEFAULT_FONT_FAMILY );
if ( ! defined( 'VUI_INPUTS_FONT_SIZE' ) ) define( 'VUI_INPUTS_FONT_SIZE', '100%' );
if ( ! defined( 'VUI_INPUTS_LINE_HEIGHT' ) ) define( 'VUI_INPUTS_LINE_HEIGHT', 'normal' );
if ( ! defined( 'VUI_INPUTS_TEXT_TRANSFORM' ) ) define( 'VUI_INPUTS_TEXT_TRANSFORM', 'none' );
if ( ! defined( 'VUI_INPUTS_TEXT_DECORATION' ) ) define( 'VUI_INPUTS_TEXT_DECORATION', 'none' );
if ( ! defined( 'VUI_INPUTS_FONT_WEIGHT' ) ) define( 'VUI_INPUTS_FONT_WEIGHT', 'normal' );
if ( ! defined( 'VUI_INPUTS_PADDING_T' ) ) define( 'VUI_INPUTS_PADDING_T', 'calc( 0.35em + 0.5px )' );
if ( ! defined( 'VUI_INPUTS_PADDING_R' ) ) define( 'VUI_INPUTS_PADDING_R', '1.1em' );
if ( ! defined( 'VUI_INPUTS_PADDING_B' ) ) define( 'VUI_INPUTS_PADDING_B', VUI_INPUTS_PADDING_T );
if ( ! defined( 'VUI_INPUTS_PADDING_L' ) ) define( 'VUI_INPUTS_PADDING_L', VUI_INPUTS_PADDING_R );
if ( ! defined( 'VUI_INPUTS_PADDING' ) ) define( 'VUI_INPUTS_PADDING', VUI_INPUTS_PADDING_T . ' ' . VUI_INPUTS_PADDING_R . ' ' . VUI_INPUTS_PADDING_B . ' ' . VUI_INPUTS_PADDING_L );

define( 'DEFAULT_INPUTS_STYLESHEET', '
	
	position: relative;
	
	' . $vui->css->display_inline_block() . '
	
	font-family: ' . VUI_INPUTS_FONT_FAMILY . ';
	font-size: ' . VUI_INPUTS_FONT_SIZE . ';
	text-transform: ' . VUI_INPUTS_TEXT_TRANSFORM . ';
	text-decoration: ' . VUI_INPUTS_TEXT_DECORATION . ';
	font-weight: ' . VUI_INPUTS_FONT_WEIGHT . ';
	
	margin: 0;
	margin-bottom: ' . VUI_SPACING . 'em;
	
	color: ' . $vui->colors->vui_input_fg->rgba_s() . ';
	
	border-top: thin solid ' . $vui->colors->vui_input_border_top->rgba_s() . ';
	border-right: thin solid ' . $vui->colors->vui_input_border_right->rgba_s() . ';
	border-bottom: thin solid ' . $vui->colors->vui_input_border_bottom->rgba_s() . ';
	border-left: thin solid ' . $vui->colors->vui_input_border_left->rgba_s() . ';
	
	background-color: ' . $vui->colors->vui_input_bg->rgba_s() . ';
	
	padding: ' . VUI_INPUTS_PADDING . ';
	
	line-height: ' . VUI_INPUTS_LINE_HEIGHT . ';
	
	' . $vui->css->border_radius( VUI_DEFAULT_BORDER_RADIUS ) . '
	
	' . $vui->css->transition( VUI_DEFAULT_TRANSITION ) . '
	
	' . $vui->css->box_shadow( 'none' ) . '
	
	cursor: text;
	
	vertical-align: top;
	
	z-index: 1;
	
');

// Inputs text
//------------------------------------------------------
//------------------------------------------------------

//------------------------------------------------------
//------------------------------------------------------
// Buttons

if ( ! defined( 'VUI_BUTTONS_FONT_FAMILY' ) ) define( 'VUI_BUTTONS_FONT_FAMILY', VUI_DEFAULT_FONT_FAMILY );
if ( ! defined( 'VUI_BUTTONS_FONT_SIZE' ) ) define( 'VUI_BUTTONS_FONT_SIZE', '100%' );
if ( ! defined( 'VUI_BUTTONS_LINE_HEIGHT' ) ) define( 'VUI_BUTTONS_LINE_HEIGHT', 'normal' );
if ( ! defined( 'VUI_BUTTONS_TEXT_TRANSFORM' ) ) define( 'VUI_BUTTONS_TEXT_TRANSFORM', 'none' );
if ( ! defined( 'VUI_BUTTONS_TEXT_DECORATION' ) ) define( 'VUI_BUTTONS_TEXT_DECORATION', 'none' );
if ( ! defined( 'VUI_BUTTONS_FONT_WEIGHT' ) ) define( 'VUI_BUTTONS_FONT_WEIGHT', 'normal' );
if ( ! defined( 'VUI_BUTTONS_PADDING_T' ) ) define( 'VUI_BUTTONS_PADDING_T', '.35em' );
if ( ! defined( 'VUI_BUTTONS_PADDING_R' ) ) define( 'VUI_BUTTONS_PADDING_R', '1.1em' );
if ( ! defined( 'VUI_BUTTONS_PADDING_B' ) ) define( 'VUI_BUTTONS_PADDING_B', VUI_BUTTONS_PADDING_T );
if ( ! defined( 'VUI_BUTTONS_PADDING_L' ) ) define( 'VUI_BUTTONS_PADDING_L', VUI_BUTTONS_PADDING_R );
if ( ! defined( 'VUI_BUTTONS_PADDING' ) ) define( 'VUI_BUTTONS_PADDING', VUI_BUTTONS_PADDING_T . ' ' . VUI_BUTTONS_PADDING_R . ' ' . VUI_BUTTONS_PADDING_B . ' ' . VUI_BUTTONS_PADDING_L );

define( 'DEFAULT_BUTTONS_STYLESHEET', '
	
	position: relative;
	
	' . $vui->css->display_inline_block() . '
	
	font-family: ' . VUI_BUTTONS_FONT_FAMILY . ';
	font-size: ' . VUI_BUTTONS_FONT_SIZE . ';
	text-transform: ' . VUI_BUTTONS_TEXT_TRANSFORM . ';
	text-decoration: ' . VUI_BUTTONS_TEXT_DECORATION . ';
	font-weight: ' . VUI_BUTTONS_FONT_WEIGHT . ';
	
	margin: 0;
	margin-bottom: ' . VUI_SPACING . 'em;
	
	color: ' . $vui->colors->vui_button_fg->rgba_s() . ';
	
	border-top: thin solid ' . $vui->colors->vui_button_border_top->rgba_s() . ';
	border-right: thin solid ' . $vui->colors->vui_button_border_right->rgba_s() . ';
	border-bottom: thin solid ' . $vui->colors->vui_button_border_bottom->rgba_s() . ';
	border-left: thin solid ' . $vui->colors->vui_button_border_left->rgba_s() . ';
	
	background-color: ' . $vui->colors->vui_button_bg->rgba_s() . ';
	
	padding: ' . VUI_BUTTONS_PADDING . ';
	
	line-height: ' . VUI_BUTTONS_LINE_HEIGHT . ';
	
	' . $vui->css->border_radius( VUI_DEFAULT_BORDER_RADIUS ) . '
	
	' . $vui->css->transition( VUI_DEFAULT_TRANSITION ) . '
	
	cursor: pointer;
	
	vertical-align: top;
	
	z-index: 1;
	
	' . $vui->css->unselectable() . '
	
');

// Buttons
//------------------------------------------------------
//------------------------------------------------------

//------------------------------------------------------
//------------------------------------------------------
// Flat buttons

define( 'DEFAULT_FLAT_BUTTONS_STYLESHEET', '
	
	' . DEFAULT_BUTTONS_STYLESHEET . '
	
	border-top: thin solid ' . $vui->colors->vui_flat_button_border_top->rgba_s() . ';
	border-right: thin solid ' . $vui->colors->vui_flat_button_border_right->rgba_s() . ';
	border-bottom: thin solid ' . $vui->colors->vui_flat_button_border_bottom->rgba_s() . ';
	border-left: thin solid ' . $vui->colors->vui_flat_button_border_left->rgba_s() . ';
	
	background-color: ' . $vui->colors->vui_flat_button_bg->rgba_s() . ';
	
	color: ' . $vui->colors->vui_flat_button_fg->rgba_s() . ';
	
');

// Flat buttons
//------------------------------------------------------
//------------------------------------------------------

if ( ! defined( 'DEFAULT_ICON_SIZE' ) ) define( 'DEFAULT_ICON_SIZE', '16px' );

/* Font icons */
define( 'FONT_ICONS_STYLESHEET', '
	
	position: relative;
	display: inline-block;
	font-family: "openvizz-set" !important;
	speak: none;
	font-size: ' . DEFAULT_ICON_SIZE . ';
	font-style: normal;
	font-weight: normal;
	font-variant: normal;
	text-transform: none;
	
	/* Better Font Rendering =========== */
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
	
');


define('TOOLTIP_STYLESHEET', '
	
	background-color: ' . $vui->colors->vui_tooltip_bg->rgba_s() . ';
	
	color: ' . $vui->colors->vui_tooltip_bg->rgba_s() . '; /* influencia na cor das setas */
	font-size: inherit;
	
	border: thin solid ' . $vui->colors->vui_tooltip_border->rgba_s() . ';
	
	' . $vui->css->border_radius( VUI_DEFAULT_BORDER_RADIUS ) . '
	
	' . $vui->css->box_shadow( '0 5px 30px ' . $vui->colors->vui_tooltip_bg->darken( 50, TRUE )->rgba_s( 30 ) ) . '
	
	-webkit-background-clip: padding-box;
	-moz-background-clip: padding;
	background-clip: padding-box;
	
	line-height: normal;
	
');
define('TOOLTIP_CONTENT_STYLESHEET', '
	
	padding: ' . VUI_SPACING . 'em;
	
	color: ' . $vui->colors->vui_tooltip_fg->rgba_s() . '; /* influencia na cor das setas */
	
');