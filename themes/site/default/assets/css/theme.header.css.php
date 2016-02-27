<?php

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
echo $template;
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


if ( ! defined( 'RELATIVE_BASE_URL' ) ) define( 'RELATIVE_BASE_URL',											rtrim( str_replace( '/themes/site/' . $template . '/assets/css/theme.css.php', '', $_SERVER[ 'PHP_SELF' ] ), '/' ) );

if ( ! defined( 'HTTP_HOST' ) ) define( 'HTTP_HOST',															PROTOCOL . '://' . HOST . ( SERVER_PORT ? ':' . SERVER_PORT : '' ) );
if ( ! defined( 'BASE_URL' ) ) define( 'BASE_URL',																HTTP_HOST . RELATIVE_BASE_URL );

// Defining the base url
//---------------------------
