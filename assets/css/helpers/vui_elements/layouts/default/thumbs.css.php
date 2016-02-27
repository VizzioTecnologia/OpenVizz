<?php if(extension_loaded('zlib')){ob_start('ob_gzhandler');} header ('Content-Type: text/css');

	define('SELF', pathinfo( __FILE__, PATHINFO_BASENAME ) );

	if ( ! defined( 'DS' ) ) define( 'DS', DIRECTORY_SEPARATOR );

	$base_path = $layout_path = explode( DS, $_SERVER[ "SCRIPT_FILENAME" ] );

	for ( $i = 6; $i >= 0; $i-- ){
		
		array_pop( $base_path );
		
	}

	$layout = $layout_path[ count( $layout_path ) - 2 ];

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


	if ( ! defined( 'RELATIVE_BASE_URL' ) ) define( 'RELATIVE_BASE_URL',											rtrim( str_replace( '/css/helpers/vui_elements/layouts/' . $layout . '/thumbs.css.php', '', $_SERVER[ 'PHP_SELF' ] ), '/' ) );

	if ( ! defined( 'HTTP_HOST' ) ) define( 'HTTP_HOST',															PROTOCOL . '://' . HOST . ( SERVER_PORT ? ':' . SERVER_PORT : '' ) );
	if ( ! defined( 'BASE_URL' ) ) define( 'BASE_URL',																HTTP_HOST . RELATIVE_BASE_URL );

	// Defining the base url
	//---------------------------




	require_once( BASE_PATH . 'application/config/constants.php');
	require_once( BASE_PATH . 'application/config/config.php');


	define('IMAGE_DIR_URL', BASE_URL . '/assets/images/helpers/vui_elements/layouts/' . $layout );
	define('IMAGE_DIR_PATH', BASE_PATH . DS . 'assets' . DS . 'images' . DS . 'helpers' . DS . 'vui_elements' . DS . 'layouts' . DS . $layout . DS );

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
		
		IMAGE_DIR_PATH . 'svg' . DS,
		IMAGE_DIR_PATH . 'svg' . DS . 'colors.svg'
		//IMAGE_DIR_URL . '/svg'
		
	);
	
	/*
	--------------------------------------------------------------------------------------------------
	VUI - Via CMS UI
	--------------------------------------------------------------------------------------------------
	**************************************************************************************************
	**************************************************************************************************
	*/
	
	$vui_spacing = 0.9;
	
	
?>


/* ---------------------------------------------------- */
/* Thumbs */

.vui .thumb{
	
	position: relative;
	<?= $vui->css->display_inline_block(); ?>
	
	margin: <?= $vui_spacing / 2; ?>em;
	
}
.vui .thumb,
.vui .thumb *,
.vui .thumb a {
	
	color: <?= $vui->colors->vui_thumb_fg->rgba_s(); ?>;
	
	<?= $vui->css->transition( '
		
		color .1s linear,
		background-color .1s linear
		
	' ); ?>
	
}
.vui .thumb:hover,
.vui .thumb:hover *,
.vui .thumb:hover a {
	
	color: <?= $vui->colors->vui_thumb_active_fg->rgba_s(); ?>;
	
}
.vui .thumb .s1{
	
	position: relative;
	<?= $vui->css->display_inline_block(); ?>
	
	max-height: 5em;
	max-width: 5em;
	min-width: 5em;
	
	overflow: hidden;
	
	color: <?= $vui->colors->vui_thumb_fg->rgba_s(); ?>;
	
	background: <?= $vui->colors->vui_thumb_bg->rgba_s(); ?>;
	
	border-top: thin solid <?= $vui->colors->vui_thumb_border_top->rgba_s(); ?>;
	border-right: thin solid <?= $vui->colors->vui_thumb_border_right->rgba_s(); ?>;
	border-bottom: thin solid <?= $vui->colors->vui_thumb_border_bottom->rgba_s(); ?>;
	border-left: thin solid <?= $vui->colors->vui_thumb_border_left->rgba_s(); ?>;
	
	<?= $vui->css->border_radius( '2.5px' ); ?>
	
	<?= $vui->css->box_shadow( '0 2px 6px ' . $vui->colors->vui_thumb_shadow_bg->rgba_s() ); ?>
	
	<?= $vui->css->transition( '
		
		box-shadow .1s linear,
		-webkit-box-shadow .1s linear,
		-o-box-shadow .1s linear,
		-ms-box-shadow .1s linear,
		-moz-box-shadow .1s linear,
		opacity .1s linear,
		color .1s linear,
		background-color .1s linear
		
	' ); ?>
	
	<?= $vui->css->box_sizing( 'content-box' ); ?>
	
}
.vui .thumb.loading .s1 * {
	
	color: <?= $vui->colors->vui_thumb_fg->rgba_s(); ?>;
	
}
.vui .thumb.loading > .s1 {
	
	<?= $vui->css->display_inline_block(); ?>
	
	height: 5em;
	width: 5em;
	
}
.vui .thumb:hover .s1{
	
	color: <?= $vui->colors->vui_thumb_active_fg->rgba_s(); ?>;
	
	background: <?= $vui->colors->vui_thumb_active_bg->rgba_s(); ?>;
	
	border-top: thin solid <?= $vui->colors->vui_thumb_border_active_top->rgba_s(); ?>;
	border-right: thin solid <?= $vui->colors->vui_thumb_border_active_right->rgba_s(); ?>;
	border-bottom: thin solid <?= $vui->colors->vui_thumb_border_active_bottom->rgba_s(); ?>;
	border-left: thin solid <?= $vui->colors->vui_thumb_border_active_left->rgba_s(); ?>;
	
	<?= $vui->css->box_shadow( '0 10px 30px ' . $vui->colors->vui_thumb_shadow_active_bg->rgba_s() ); ?>
	
}
.vui .thumb:hover .s1 * {
	
	color: <?= $vui->colors->vui_thumb_active_fg->rgba_s(); ?>;
	
}


.vui .thumb.loading:before,
.vui .thumb.loading:after {
	
	display: none ;
	
}
.vui .data-list td.field-is-image{
	
	padding: 0;
	width: 1px;
	text-align: center;
	
}
.vui .thumb .s1:before{
	/*
	content: "";
	display: block;
	padding-top: 100%;
	*/
}
.vui .thumb .s2 {
	
	position: relative;
	display: block;
	
	<?= $vui->css->backface_visibility() ?>
	<?= $vui->css->transform_style(); ?>
	
}
.vui .thumb .s2 a{
	
	position: relative;
	display: block;
	
	overflow: hidden;
	
	<?= $vui->css->backface_visibility() ?>
	<?= $vui->css->transform_style(); ?>
	
}

.vui .thumb .s2 img {
	
	/*
	width: 170%;
	<?= $vui->css->transition( VUI_DEFAULT_TRANSITION ); ?>
	<?= $vui->css->transform( 'translateX( -50% ) scale( 1 )' ); ?>
	<?= $vui->css->transform_style(); ?>
	margin-left: 50%;
	*/
	
	font-size: 80%;
	font-style: italic;
	
	position: relative;
	display: block;
	
	max-width: 100%;
	
	border: <?= $vui_spacing / 2; ?>em solid transparent;
	
	-moz-animation: added 0.01s linear;
	-moz-animation-iteration-count: 1;
	-ms-animation: added 0.01s linear;
	-ms-animation-iteration-count: 1;
	-o-animation: added 0.01s linear;
	-o-animation-iteration-count: 1;
	-webkit-animation: added 0.01s linear;
	-webkit-animation-iteration-count: 1;
	animation: added 0.01s linear;
	animation-iteration-count: 1;
	
	<?= $vui->css->backface_visibility() ?>
	
}
.vui .thumb.loading .s2 img {
	
	line-height: 0;
	
	font-size: 0.001px;
	text-indent: -100000px;
	overflow: hidden;
	
}
.vui .thumb.loaded .s2 img {
	
}

<?php
	
	$added = '
		
		0% {
			
			color: #fff;
			
		}
		
	';
	
	echo $vui->css->keyframes( 'added', $added, FALSE );
	
?>

/* Thumbs */
/* ---------------------------------------------------- */
