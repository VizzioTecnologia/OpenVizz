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
	
?>

<span class="vui-interactive-el-wrapper vui-textarea-wrapper<?= $wrapper_class ? ' ' . $wrapper_class : ''; ?><?= $icon ? ' with-icon' : ''; ?>" <?= $_title ? $_title : ''; ?>><?php
	
	$input_params = array(
		
		'placeholder' => str_replace( array( '"', '\"' ), '\'', strip_tags( $text ) ),
		'name' => $name,
		'class' => 'el textarea input-box' . ( $class ? ' ' . $class : '' ),
		'id' => $id,
		'form' => $form,
		'autofocus' => $autofocus,
		
	);
	
	$input_params = check_var( $attr ) ? array_merge( $input_params, $attr ) : $input_params;
	
	foreach( $input_params as $k => $v ) {
		
		if ( empty( $v ) ) {
			
			unset( $input_params[ $k ] );
			
		}
		
	}
	
	echo form_textarea( $input_params, $value );
	
	echo vui_el_icon( array( 'icon' => $icon, ) );
	
?></span>
