<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script type="text/javascript">
	
	console.log( '<?= lang( 'modal_rf_file_picker_plugin_started' ); ?>' );
	
	function responsive_filemanager_callback( fieldID ){
		
		var url = $( '#' + fieldID ).val();
		url = url.replace( new RegExp( '<?= site_url(); ?>', 'g' ), '' );
		$( '#' + fieldID ).val( url.replace(/^\/|\/$/g, '') );
		
		if ( typeof window.onFileChooseFunction === 'function' ) {
			
			window.onFileChooseFunction();
			
		}
		
	}
	
	window.modal_rf_file_picker_callback = function( func ){
		
		if ( typeof func === 'function' ) {
			
			func();
			
		}
		
	}
	
	if ( typeof window.onFileChooseFunction === 'undefined' ) {
		
		window.onFileChooseFunction = function(){
			
		}
		
	}
	
	if ( typeof window.runRFFilePicker === 'undefined' ) {
		
		window.runRFFilePicker = function(){
			
			$('.modal-file-picker').each( function( index ) {
				
				var jthis = $( this );
				var dir = '';
				var relUrl = '1';
				
				var fieldId = jthis.data( 'rffieldid' ) ? jthis.data( 'rffieldid' ) : ( jthis.attr( 'id' ) ? jthis.attr( 'id' ) : false );
				var fldr = jthis.data( 'rf-start-dir' ) ? jthis.data( 'rf-start-dir' ) : '' ;
				var type = jthis.data( 'rftype' );
				
				if ( type === 'image' ){
					
					type = 1;
					dir = '<?= MEDIA_DIR_NAME; ?>'
					
				}
				else if ( type === 'all' ){
					
					type = 2;
					dir = '<?= MEDIA_DIR_NAME; ?>'
					
				}
				else if ( type === 'video' ){
					
					type = 3;
					dir = '<?= MEDIA_DIR_NAME; ?>'
					
				}
				else{
					
					type = 0;
					dir = '<?= MEDIA_DIR_NAME; ?>'
					
				}
				
				if ( typeof jthis.data( 'rfdir' ) != 'undefined' && jthis.data( 'rfdir' ) != '' ){
					
					dir = jthis.data( 'rfdir' );
					
				}
				
				if ( typeof jthis.data( 'rf-relative-url' ) != 'undefined' && jthis.data( 'rf-relative-url' ) != '0' ){
					
					relUrl = '1';
					
				}
				
				var akey = '<?= md5( $this->config->item( 'encryption_key' ) ); ?>';
				var lang = '<?= str_replace( '_', '-', $this->mcm->filtered_system_params[ $this->mcm->environment . '_language' ] ); ?>';
				
				var rfUrl = '<?= JS_DIR_URL . '/responsivefilemanager/filemanager/dialog.php'; ?>';
				var uGet = '?lang=' + lang + '&dir=' + dir + '&type=' + type + '&akey=' + akey + '&field_id=' + fieldId + '&relative_url=' + relUrl + '&fldr=' + fldr;
				
				jthis.attr( 'href', rfUrl + uGet );
				
				jthis.click( function( e ){
					
					e.preventDefault();
					
					if ( typeof jthis.data( 'rf-callback-function-on-click' ) != 'undefined' ){
						
						window[ jthis.data( 'rf-callback-function-on-click' ) ]();
						
					}
					
					if ( typeof jthis.data( 'rf-callback-function-on-choose' ) != 'undefined' ){
						
						window.responsive_filemanager_callback = function( fieldID ){
							
							var url = $( '#' + fieldID ).val();
							
							if ( url != '' ){
								
								url = url.replace( new RegExp( '<?= site_url(); ?>', 'g' ), '' );
								$( '#' + fieldID ).val( url.replace(/^\/|\/$/g, '') );
								
							}
							
							window.modal_rf_file_picker_callback( window[ jthis.data( 'rf-callback-function-on-choose' ) ] );
							
						}
						
					}
					
					if ( typeof jthis.data( 'rf-container' ) != 'undefined' ){
						
						$( jthis.data( 'rf-container' ) ).html( '<iframe style="min-width:850px;min-height:300px;" src="' + jthis.attr( 'href' ) + '" ></iframe>' )
						$.fancybox.update();
						
					}
					else{
						
						$.fancybox.open({
							
							href : jthis.attr( 'href' ),
							title : '<?= lang( 'choose_file' ); ?>',
							type: "iframe",
							isDom: false,
							fitToView: true,
							minWidth: 860,
							minHeight: 300,
							autoScale : true
							
						});
						
					}
					$.fancybox.reposition();
					
				});
				
			})
			
		}
		
	}
	
	$( document ).bind( 'ready', function(){
		
		runRFFilePicker();
		
	});
	
</script>
