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

<span class="vui-interactive-el-wrapper <?= ( ! $multiselect ? 'vui-btn-wrapper' : '' ); ?> vui-dropdown<?= ( $multiselect ? '-multi' : '' ); ?>-wrapper<?= $wrapper_class ? ' ' . $wrapper_class : ''; ?><?= $icon ? ' with-icon' : ''; ?>" <?= $_title ? $_title : ''; ?>><?php
	
	if ( $label ) {
		
		echo form_label( $label );
		
	}
	
	$input_params = array(
		
		'class' => 'el dropdown btn' . ( $class ? ' ' . $class : '' ),
		'id' => $id,
		'form' => $form,
		
	);
	
	$input_params = check_var( $attr ) ? array_merge( $input_params, $attr ) : $input_params;
	
	$extra = array();
	
	
	if ( $multiselect ) {
		
		$extra[] = 'multiple="multiple"';
		
	}
	
	
	foreach( $input_params as $k => $v ) {
		
		if ( ! empty( $v ) ) {
			
			$extra[] = $k . "=\"$v\"";
			
		}
		
	}
	
	$extra = join( $extra, ' ' );
	
	echo form_dropdown( $name, $options, $value, $extra );
	
	echo vui_el_icon( array( 'icon' => $icon, ) );
	
?></span>
