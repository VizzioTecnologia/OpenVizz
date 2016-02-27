<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	$_title = FALSE;
	
	if ( $title OR $text ){
		
		if ( $title ) {
			
			$_title = element_title( $title ) . ' ';
			
		}
		else if ( $text ) {
			
			$_title = element_title( $text ) . ' ';
			
		}
		
	}
	
	$_wrapper_class = array();
	
	if ( $text ) $_wrapper_class[ 'with-text' ] = 'with-text';
	if ( $icon ) $_wrapper_class[ 'with-icon' ] = 'with-icon';
	if ( $icon AND $icon_as_check ) $_wrapper_class[ 'icon-as-check' ] = 'icon-as-check';
	if ( $has_check AND ! $icon_as_check) $_wrapper_class[ 'has-check' ] = 'has-check';
	if ( $icon AND $only_icon OR ( $icon AND ! $text ) ) $_wrapper_class[ 'only-icon' ] = 'only-icon';
	
	$_wrapper_class = join( $_wrapper_class, ' ' );
	
?>
<label class="vui-interactive-el-wrapper vui-radiobox-wrapper<?= $wrapper_class ? ' ' . $wrapper_class : ''; ?> <?= $_wrapper_class; ?>" <?= $_title ? $_title : ''; ?>><?php
	
	$input_params = array(
		
		'name' => $name,
		'checked' => $checked ? 'checked' : '',
		'class' => 'vui-radiobox' . ( $class ? ' ' . $class : '' ),
		'id' => $id,
		'form' => $form,
		'value' => $value,
		
	);
	
	$input_params = check_var( $attr ) ? array_merge( $input_params, $attr ) : $input_params;
	
	foreach( $input_params as $k => $v ) {
		
		if ( empty( $v ) ) {
			
			unset( $input_params[ $k ] );
			
		}
		
	}
	
	echo form_radio( $input_params );
	
	echo '<span class="el">';
	echo '<span class="check"></span>';
	echo '<span class="content">' . ( ( $text AND ! $only_icon ) ? lang( $text ) : '&nbsp;' ) . '</span>';
	echo '</span>';
	
	echo vui_el_icon( array( 'icon' => $icon, ) );
	
?></label>








































