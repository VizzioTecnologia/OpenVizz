<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script type="text/javascript">
	
	/*************************************************/
	/****************** Inline edit ******************/
	
	if ( ! window.inlineEditEvent ){
		
		window.inlineEditEvent = true;
		
		$( document ).on( 'click', '.inline-edit', function(){
			
			var jthis = $( this );
			var origText = jthis.text();
			var updateEvent = 'blur change unfocus';
			var type = jthis.data( 'inline-edit-type' );
			
			if ( ! jthis.hasClass( 'inline-edit-editing' ) ){
				
				jthis.addClass( 'inline-edit-editing' );
				
				console.log( 'Editing content: ' + origText )
				
				jthis.empty();
				jthis.prepend( '<span class="inline-edit-original-content">' + origText + '</span>' );
				var origTextWrapper = jthis.find( '.inline-edit-original-content' );
				
				<?php
				
				$attrs = array(
					
					'class' => 'inline-edit-input', // required class for live update plugin
					
				);
				
				?>
				
				inputElWrapper = jthis.append( '<span class="inline-edit-input-wrapper"></span>' );
				var inputElWrapper = jthis.find( '.inline-edit-input-wrapper' );
				
				inputElWrapper.append( '<?= form_input( $attrs ); ?>' );
				var inputEl = inputElWrapper.find( '.inline-edit-input' );
				
				
				// specific for jQuery UI datepicker
				if ( type == 'date' ){
					
					if ( jthis.data( 'max-date' ) ){
						
						inputEl.data( 'max-date', jthis.data( 'max-date' ) );
						
					}
					
					if ( jthis.data( 'min-date' ) ){
						
						inputEl.data( 'min-date', jthis.data( 'min-date' ) );
						
					}
					
					inputEl.addClass( 'date' );
					inputEl.focus()
					
				}
				else {
					
					inputEl.focus()
					
				}
				
				
				inputEl.on( 'keydown', function( e ){
					
					origTextWrapper.text( inputEl.val() );
					
				});
				
				inputEl.val( origText );
			}
			
			$( document ).on( 'change blur focusout', '.inline-edit-input', function( e ){
				
				console.log( 'update event was called: ' + e.type );
				
				var inputEl = $( this );
				var inlineEditEl = inputEl.parent().parent();
				var triggerEventAllowed = false;
				var type = inlineEditEl.data( 'inline-edit-type' );
				
				if ( type == 'date' ){
					
					if ( e.type == 'change' ){
						
						triggerEventAllowed = true;
						
					}
					
				}
				else if ( type == 'text' ){
					
					triggerEventAllowed = true;
					
				}
				
				if ( triggerEventAllowed ){
					
					console.log( 'updating inline edit content' );
					
					if ( inlineEditEl.data( 'inline-edit-field-id' ) ){
						
						$( '#' + inlineEditEl.data( 'inline-edit-field-id' ) ).val( inputEl.val() );
						$( '#' + inlineEditEl.data( 'inline-edit-field-id' ) ).change();
						
					}
					
					if ( inlineEditEl.data( 'inline-edit-callback-function' ) ){
						
						window[ inlineEditEl.data( 'inline-edit-callback-function' ) ]();
						console.log( 'calling callback function ' + inlineEditEl.data( 'inline-edit-callback-function' ) );
					}
					
					inlineEditEl.removeClass( 'inline-edit-editing' );
					inlineEditEl.text( inputEl.val() );
					
				}
				
			});
			
			$( document ).on( 'keyup', '.inline-edit-input', function( e ){
				
				// prevenindo que o formulário seja submetido
				if ( e.keyCode == 13 ) {
					
					e.preventDefault();
					
					$( this ).blur();
					$( this ).focusout();
					$( this ).change();
					
				}
				
			});
			
		});
		
	}
	
	/****************** Inline edit ******************/
	/*************************************************/
	
</script>
