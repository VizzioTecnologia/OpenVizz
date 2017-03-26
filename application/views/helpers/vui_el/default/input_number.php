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

<span class="vui-interactive-el-wrapper vui-input-number-wrapper<?= $wrapper_class ? ' ' . $wrapper_class : ''; ?><?= $icon ? ' with-icon' : ''; ?>" <?= $_title ? $_title : ''; ?>><?php
	
	if ( $label ) {
		
		echo form_label( $label );
		
	}
	
	$input_params = array(
		
		'placeholder' => $text,
		'name' => $name,
		'min' => $min,
		'max' => $max,
		'pattern' => $pattern,
		'step' => $step,
		'class' => 'el input-number input-box' . ( $class ? ' ' . $class : '' ),
		'id' => $id,
		'form' => $form,
		'autofocus' => $autofocus,
		
	);
	
	$input_params = check_var( $attr ) ? array_merge( $input_params, $attr ) : $input_params;
	
	foreach( $input_params as $k => $v ) {
		
		if ( $v === 0 AND ! in_array( $k, array( 'min', 'max', 'value', ) ) ) {
			
			unset( $input_params[ $k ] );
			
		}
		else if ( empty( $v ) AND ! ( $v === 0 AND in_array( $k, array( 'min', 'max', 'value', ) ) ) ) {
			
			unset( $input_params[ $k ] );
			
		}
		
		
	}
	
	echo form_input_number( $input_params, $value );
	
	echo vui_el_icon( array( 'icon' => $icon, ) );
	
?></span>
