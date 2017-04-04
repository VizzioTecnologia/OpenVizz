<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script type="text/javascript">
	
	/*************************************************/
	/****************** Inline edit ******************/
	
	if ( ! window.inlineEditEvent ){
		
		window.inlineEditEvent = true;
		
		$( document ).on( 'dblclick', '.ile', function(){
			
			console.log( 'Inline Edit called' );
			
			if ( $( this ).data( 'ile-el-id' ) != null ) {
				
				var $ile_el = $( '#' + $( this ).data( 'ile-el-id' ) );
				
			}
			else {
			
				var $ile_el = $( this );
				
			}
			
			var $ile_cfg_el = $( this );
			var origText = $ile_el.text();
			var updateEvent = 'blur change unfocus';
			var type = $ile_el.data( 'ile-type' );
			var $cv = $ile_cfg_el.data( 'ile-cv' ) != null ? $ile_cfg_el.data( 'ile-cv' ) : null;
			
			if ( ! $ile_el.hasClass( 'ile-editing' ) ){
				
				$ile_el.addClass( 'ile-editing' );
				
				// edit function
				if ( $ile_cfg_el.data( 'ile-ef' ) ){
					
					console.log( 'calling edit function \'' + $ile_cfg_el.data( 'ile-ef' ) + '\'' );
					
					if ( $ile_cfg_el.data( 'ile-ef-arg1' ) != null ) {
						
						$arg1 = $ile_cfg_el.data( 'ile-ef-arg1' );
						
						window[ $ile_cfg_el.data( 'ile-ef' ) ]( $cv, $arg1 );
						
					}
					else {
						
						window[ $ile_cfg_el.data( 'ile-ef' ) ]( $cv );
						
					}
					
				}
				else {
					
					$ile_el.empty();
					$ile_el.prepend( '<span class="ile-original-content">' + origText + '</span>' );
					var origTextWrapper = $ile_el.find( '.ile-original-content' );
					
					<?php
						
						$attrs = array(
							
							'class' => 'ile-input', // required class for live update plugin
							
						);
						
					?>
					
					$input_el_wrapper = $ile_el.append( '<span class="ile-input-wrapper"></span>' );
					var $input_el_wrapper = $ile_el.find( '.ile-input-wrapper' );
					
					$input_el_wrapper.append( '<?= form_input( $attrs ); ?>' );
					var $input_el = $input_el_wrapper.find( '.ile-input' );
					
					// specific for jQuery UI datepicker
					if ( type == 'date' ){
						
						if ( $ile_el.data( 'max-date' ) ){
							
							$input_el.data( 'max-date', $ile_el.data( 'max-date' ) );
							
						}
						
						if ( $ile_el.data( 'min-date' ) ){
							
							$input_el.data( 'min-date', $ile_el.data( 'min-date' ) );
							
						}
						
						$input_el.addClass( 'date' );
						$input_el.focus()
						
					}
					else {
						
						$input_el.focus()
						
					}
					/*
					$input_el.on( 'keydown', function( e ){
						
						origTextWrapper.text( $input_el.val() );
						
					});
					*/
					$input_el.val( origText );
					
				}
				
			}

		});
		
		ile_apply = function( el, e ) {
			
			console.log( 'update event was called: ' + e.type );
			
			var $input_el = el;
			var $nv = $input_el.val();
			var $ile_el = $input_el.parent().parent();
			var $ile_cfg_el = $input_el.data( 'ile-cfg-el-id' ) != null ? $( '#' + $input_el.data( 'ile-cfg-el-id' ) ) : $input_el.parent().parent();
			var triggerEventAllowed = false;
			var type = $ile_cfg_el.data( 'ile-type' );
			
			if ( type == 'date' ){
				
				if ( e.type == 'change' ){
					
					triggerEventAllowed = true;
					
				}
				
			}
			else if ( type == 'text' || type == 'select' || type == 'textarea' ){
				
				triggerEventAllowed = true;
				
			}
			
					console.log( $input_el.data( 'ile-cfg-el-id' ) );
					
			if ( triggerEventAllowed ){
				
				console.log( 'updating inline edit content' );
				
				// callback function
				if ( $ile_cfg_el.data( 'ile-cf' ) ){
					
					console.log( 'calling callback function \'' + $ile_cfg_el.data( 'ile-cf' ) + '\'' );
					
					if ( $ile_cfg_el.data( 'ile-cf-arg1' ) != null ) {
						
						$arg1 = $ile_cfg_el.data( 'ile-cf-arg1' );
						
						window[ $ile_cfg_el.data( 'ile-cf' ) ]( $nv, $arg1 );
						
					}
					else {
						
						window[ $ile_cfg_el.data( 'ile-cf' ) ]( $nv );
						
					}
					
				}
				
				// write new value function
				if ( $ile_cfg_el.data( 'ile-wnvf' ) ){
					
					console.log( 'calling Write New Value function \'' + $ile_cfg_el.data( 'ile-wnvf' ) + '\'' );
					
					if ( $ile_cfg_el.data( 'ile-wnvf-arg1' ) != null ) {
						
						$arg1 = $ile_cfg_el.data( 'ile-wnvf-arg1' );
						
						window[ $ile_cfg_el.data( 'ile-wnvf' ) ]( $nv, $arg1 );
						
					}
					else {
						
						window[ $ile_cfg_el.data( 'ile-wnvf' ) ]( $nv );
						
					}
					
				}
				else {
					
					$ile_el.text( $nv );
					
				}
				
				$ile_el.removeClass( 'ile-editing' );
				
			}
			
		}
		
		$( document ).on( 'blur', '.ile-input', function( e ){
			
			ile_apply( $( this ), e );
			
		});
		
		$( document ).on( 'keypress', '.ile-input', function( e ){
			
			// prevenindo que o formulário seja submetido
			if ( e.keyCode == 13 ) {
				
				 if( e.shiftKey){
					
					event.stopPropagation();
					
				}
				else {
					
					ile_apply( $( this ), e );
					
					e.preventDefault();
					
				}
				
			}
			
		});
			
	}
	
	/****************** Inline edit ******************/
	/*************************************************/
	
</script>
