<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	$_title = FALSE;
	
	if ( $title OR ( $text AND ! $title AND $only_icon ) ){
		
		if ( $title ) {
			
			$_title = element_title( $title ) . ' ';
			
		}
		else if ( $text AND ! $title AND $only_icon ) {
			
			$_title = element_title( $text ) . ' ';
			
		}
		
	}
	
	$_wrapper_attr = array();
	
	if ( $wrapper_attr ) {
		
		foreach( $wrapper_attr as $key => $value ) {
			
			if ( isset( $value ) AND $value != '' ) {
				
				$_wrapper_attr[] = "$key=\"$value\"";
				
			}
			
		}
		
	}
	
	$_wrapper_attr = join( ' ', $_wrapper_attr );
	
?>

<span id="<?= $wrapper_id; ?>" class="vui-interactive-el-wrapper vui-btn-wrapper vui-<?= $button_type; ?>-wrapper<?= $wrapper_class ? ' ' . $wrapper_class : ''; ?><?= $icon ? ' with-icon' : ''; ?><?= ( ! in_array( $button_type, array( 'reset', 'submit' ) ) AND $icon AND $only_icon OR ( $icon AND ! $text ) ) ? ' only-icon' : ''; ?>" <?= $_title ? $_title : ''; ?> <?= $_wrapper_attr; ?>><?php
	
	$_attr = array(
		
		'name' => $name,
		'class' => 'el btn button vui-button' . ( $class ? ' ' . $class : '' ),
		'id' => $id,
		'autofocus' => $autofocus,
		
	);
	
	$attr = ( is_array( $attr ) ) ? array_merge( $_attr, $attr ) : $_attr;
	
	if ( $button_type == 'anchor' ){
		
		if ( $url ) {
				
			$_el_tag = 'a';
			
			$attr[ 'class' ] .= ( ( $check_current_url AND strpos( current_url(), $url ) !== FALSE ) ? ' active' : '' );
			$attr[ 'href' ] = ( $get_url ? get_url( $url ) : $url );
			
		}
		else {
				
			$_el_tag = 'span';
			
		}
		
		$attr[ 'target' ] = $target;
		
	}
	else if ( $button_type == 'button' ){
		
		$_el_tag = 'button';
		
		$attr[ 'value' ] = ! $value ? $button_type : $value;
		$attr[ 'form' ] = $form;
		
	}
	else if ( $button_type == 'submit' ){
		
		$_el_tag = 'input';
		
		$attr[ 'type' ] = $button_type;
		$attr[ 'value' ] = $value ? $value : ( $text ? $text : lang( 'submit' ) );
		$attr[ 'form' ] = $form;
		
	}
	
	$_attr = array();
	
	foreach( $attr as $key => $value ) {
		
		if ( isset( $value ) AND $value != '' ) {
			
			$_attr[] = "$key=\"$value\"";
			
		}
		
	}
	
	$_attr = join( ' ', $_attr );
	
	if ( in_array( $button_type, array( 'reset', 'submit' ) ) ){
		
		echo '<' . $_el_tag . ' ' . $_attr . ' />';
		echo vui_el_icon( array( 'icon' => $icon, ) );
		
	}
	else {
		
		echo '<' . $_el_tag . ' ' . $_attr . '>' . '<span class="text">' . ( ! $only_icon ? $text : '&nbsp;' ) . '</span>' . ( $icon ? vui_el_icon( array( 'icon' => $icon, ) ) : '' ) . '</' . $_el_tag . '>';
		
	}
	
?></span>
