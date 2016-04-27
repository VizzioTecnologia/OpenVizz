<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
	
	$image_logo = check_var( $params[ 'ud_ds_submitters_layout_logo_image_file' ] ) ? get_url( $params[ 'ud_ds_submitters_layout_logo_image_file' ] ) : NULL;
	
	$bg_color = check_var( $params[ 'ud_ds_submitters_layout_bg_color' ] ) ? $params[ 'ud_ds_submitters_layout_bg_color' ] : '#ececec';
	$content_bg_color = check_var( $params[ 'ud_ds_submitters_layout_content_bg_color' ] ) ? $params[ 'ud_ds_submitters_layout_content_bg_color' ] : '#ffffff';
	$content_fg_color = check_var( $params[ 'ud_ds_submitters_layout_content_fg_color' ] ) ? $params[ 'ud_ds_submitters_layout_content_fg_color' ] : '#4e555d';
	$content_link_color = check_var( $params[ 'ud_ds_submitters_layout_content_link_color' ] ) ? $params[ 'ud_ds_submitters_layout_content_link_color' ] : '#0060d7';
	$content_border_color = check_var( $params[ 'ud_ds_submitters_layout_content_border_color' ] ) ? $params[ 'ud_ds_submitters_layout_content_border_color' ] : '#e1e1e1';
	
	$show_title = ! check_var( $params[ 'ud_ds_submitters_layout_show_title' ] ) ? TRUE : ( bool ) $params[ 'ud_ds_submitters_layout_show_title' ];
	$title = check_var( $params[ 'ud_ds_submitters_layout_custom_title' ] ) ? $params[ 'ud_ds_submitters_layout_custom_title' ] : lang( 'ud_ds_submitters_layout_default_title' );
	
	$ud_data_submit_datetime = strtotime( $ud_data[ 'submit_datetime' ] );
	$ud_data_submit_datetime = strftime( lang( 'ud_data_datetime' ), $ud_data_submit_datetime );
	
	$show_pre_text = ! check_var( $params[ 'ud_ds_submitters_layout_show_pre_text' ] ) ? TRUE : ( bool ) $params[ 'ud_ds_submitters_layout_show_pre_text' ];
	$pre_text = check_var( $params[ 'ud_ds_submitters_layout_custom_pre_text' ] ) ? $params[ 'ud_ds_submitters_layout_custom_pre_text' ] : lang( 'ud_ds_submitters_layout_default_pre_text', NULL, $submit_form[ 'url' ], $submit_form[ 'title' ], $ud_data_submit_datetime );
	
	$show_footer_text = ! check_var( $params[ 'ud_ds_submitters_layout_show_footer_text' ] ) ? TRUE : ( bool ) $params[ 'ud_ds_submitters_layout_show_footer_text' ];
	$footer_text = check_var( $params[ 'ud_ds_submitters_layout_custom_footer_text' ] ) ? $params[ 'ud_ds_submitters_layout_custom_footer_text' ] : lang( 'ud_ds_submitters_layout_default_footer_text', NULL, get_url(), get_url(), $ud_data_submit_datetime );
	
	// css specifics settings
	define( 'SUBMITTERS_DEFAULT_SPACING', 0.9 );
	
	$vars_loaded = TRUE;
	
?>