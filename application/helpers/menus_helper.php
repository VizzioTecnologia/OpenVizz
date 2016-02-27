<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
 * Obtém o id do item de menu atual da string da url não roteada.
 * Retorna 0 ( zero ) se não for um inteiro ou se não for encontrado
 */
function current_menu_id(){
	
	$CI =& get_instance();
	
	$u_array = $CI->uri->ruri_to_assoc();
	
	if ( array_key_exists( 'miid', $u_array ) ){
		
		return $u_array[ 'miid' ];
		
	}
	else if ( $CI->mcm->environment == 'site' ){
		
		$segment = $CI->uri->rsegment( 4 );
		
		if ( ctype_digit( $segment ) AND $segment != 0 ){
			
			return $segment;
			
		}
		else if ( $segment === "0" ){
			
			return 0;
			
		}
		
	}
	
	return 0;
	
}

/*
 * Retorna um array com os dados do item de menu atual
 * Retorna FALSE se o item de menu não existir
 */
function get_current_menu_item(){
	
	$CI =& get_instance();
	
	$current_menu_item_id = current_menu_id();
	
	if ( ! $CI->load->is_model_loaded( 'menus' ) ) {
		
		$CI->load->model( 'menus_mdl', 'menus' );
		
	}
	
	if ( ( $current_menu_item_id AND $current_menu_item_id !== 0 ) AND ( $menu_item = $CI->menus->get_menu_item( $current_menu_item_id ) ) ){
		
		return $menu_item;
		
	}
	else if ( $current_menu_item_id === 0 ){
		
		return 0;
		
	}
	
	return FALSE;
	
}

/**
 * 
 *
 * Converte um array uni-dimensional em um array multidimensional para items menus
 *
 */

function array_menu_to_array_tree( $array, $id_str = 'id', $parent_str = 'parent_id' ) {
	
	$CI =& get_instance();
	
	// First, convert the array so that the keys match the ids
	$reKeyed = array();
	
	foreach ( $array as $item ) {
		
		$reKeyed[ $item[ $id_str ] ] = $item;
		
	}
	
	_check_current_menu_item( $reKeyed, $id_str, $parent_str );
	
	
	// Next, use references to associate children with parents
	foreach ( $reKeyed as $id => $item ) {
		
		if ( 
			( isset( $item[ $parent_str ] ) AND $item[ $parent_str ] )
			AND ( isset( $reKeyed[ $item[ $parent_str ] ] ) AND $reKeyed[ $item[ $parent_str ] ] )
		 ){
			
			$reKeyed[ $item[ $parent_str ] ][ 'children' ][] = & $reKeyed[ $id ];
			
		}
		
	}
	
	// Finally, go through and remove children from the outer level
	foreach ( $reKeyed as $id => $item ) {
		
		if ( isset( $item[ $parent_str ] ) AND $item[ $parent_str ] ) {
			unset( $reKeyed[ $id ] );
		}
		
	}
	
	return $reKeyed;
	
}

function _check_current_menu_item( &$reKeyed, $id_str, $parent_str ){
	
	$CI =& get_instance();
	
	if ( isset( $CI->mcm->current_menu_item ) ) {
		
		foreach ( $reKeyed as &$item ) {
			
			if ( $CI->mcm->current_menu_item[ 'id' ] == $item[ $id_str ] ){
				
				_set_current_menu_item( $reKeyed, $item, $id_str, $parent_str );
				
			}
			
		}
		
	}
	
}

function _set_current_menu_item( &$reKeyed, &$item, $id_str, $parent_str ){
	
	$item[ 'current' ] = 1;
	
	if (
		( isset( $item[ $parent_str ] ) AND $item[ $parent_str ] )
		AND ( isset( $reKeyed[ ( int ) $item[ $parent_str ] ] ) AND $reKeyed[ ( int ) $item[ $parent_str ] ] )
	){
		
		_set_current_menu_item( $reKeyed, $reKeyed[ ( int ) $item[ $parent_str ] ], $id_str, $parent_str );
		
	 }
	
}


/* End of file menus_helper.php */
/* Location: ./application/helpers/menus_helper.php */