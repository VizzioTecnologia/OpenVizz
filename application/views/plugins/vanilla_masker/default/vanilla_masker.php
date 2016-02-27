<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script type="text/javascript">
	
	console.log( '[Plugins] Vanilla Masker plugin initialized' );
	
	/*************************************************/
	/****************** Inline edit ******************/
	
	$( '.input-mask' ).each( function( i ){
		
		var jthis = $( this );
		var $id = jthis.attr( 'id' ) ? jthis.attr( 'id' ) : false;
		var $mask = jthis.data( 'input-mask' ) ? jthis.data( 'input-mask' ) : false;
		var $mask_el_id = jthis.data( 'input-mask-el-id' ) ? jthis.data( 'input-mask-el-id' ) : false;
		var $mask = ( $( '#' + $mask_el_id ).val() != '' ) ? $( '#' + $mask_el_id ).val() : $mask;
		var $type = jthis.data( 'input-mask-type' ) ? jthis.data( 'input-mask-type' ) : false;
		var $phone,
			$zip_brazil = false
		
		switch( $type ) {
			
			case 'phone':
				
				$phone = true;
				break;
				
			case 'zip_brazil':
				
				$zip_brazil = true;
				$mask = '99999-999';
				break;
				
			default:
				
				
				
				;
				
		}
		
		if ( $mask && $id ){
			
			console.log( 'vmasker-id: ' + $id );
			console.log( 'vmasker-mask-el-id: ' + $mask_el_id );
			console.log( 'vmasker-mask: ' + $mask );
			
			if ( $mask_el_id ){
				
				$( document ).on( 'focus', '#' + $id + ', #' + $mask_el_id, function(){
					
					$mask = $( '#' + $mask_el_id ).val();
					
					VMasker( document.getElementById( $id ) ).unMask();
					VMasker( document.getElementById( $id ) ).maskPattern( $mask );
					
				});
				
				$( document ).on( 'keyup', '#' + $mask_el_id, function(){
					
					$mask = $( '#' + $mask_el_id ).val();
					
					VMasker( document.getElementById( $id ) ).unMask();
					VMasker( document.getElementById( $id ) ).maskPattern( $mask );
					
				});
				
			}
			else {
				
				VMasker( document.getElementById( $id ) ).maskPattern( $mask );
				
			}
			
		}
		
	});
	
	/****************** Inline edit ******************/
	/*************************************************/
	
</script>
