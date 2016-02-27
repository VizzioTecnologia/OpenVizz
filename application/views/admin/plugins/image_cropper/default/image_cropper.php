<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script type="text/javascript">
	
	$.imageCrop = {
		
		minSize: 600,
		margin: 20,
		opened: false,
		
	};
	
	$.imageCrop.open = function( $options ){
		
		$( '.vui-modal' ).addClass( 'image-cropper-on' );
		
		$options = {
			
			imgSrc: $options.imgSrc ? $options.imgSrc : null,
			callback: $options.callback ? $options.callback : null
			
		}
		
		var modalControls,
			modalContent;
		
		$.imageCrop.submitOk = null;
		$.imageCrop.submitCancel = null;
		
		<?php
			
			$standalone_view_params = array(
				
			);
			
		?>
		
		if ( $( '.image-cropper-main' ).length === 0 ){
			
			$( 'body' ).append( '<?= minify_html( $this->load->view( 'admin/plugins/image_cropper/default/standalone_modal', $standalone_view_params, TRUE ) ); ?>' );
			
		}
		
		$.imageCrop.imageCropperMainWrapper = $( '.image-cropper-main' );
		$.imageCrop.imageCropperMainWrapper.addClass( 'loading' );
		$.imageCrop.imageCropperControlsWrapper = $( '.image-cropper-controls' );
		$.imageCrop.imageCropperWrapper = $( '.image-cropper-main' ).find( '.image-cropper-wrapper' );
		$.imageCrop.img = '<img class="image-cropper" src="' + $options.imgSrc + '" />';
		$.imageCrop.imageCropperWrapper.append( $.imageCrop.img );
		$.imageCrop.img = $.imageCrop.imageCropperWrapper.find( '.image-cropper' );
		
		var image = new Image();
		image.src = $.imageCrop.img.attr( "src" );
		
		$.ajax({
			
			type: "GET",
			url: $.imageCrop.img.attr( "src" ) + '?' + Math.floor( ( Math.random() * 100 ) + 1 ),
			success: function( data ){
				
				$.imageCrop.opened = true;
				
				$.imageCrop.originalWidth = $.imageCrop.img.outerWidth();
				$.imageCrop.originalHeight = $.imageCrop.img.outerHeight();
				
				$.imageCrop.img.attr( 'data-original-width', $.imageCrop.img.outerWidth() );
				$.imageCrop.img.attr( 'data-original-height', $.imageCrop.img.outerHeight() );
				
				$.imageCrop.updateImageCropper();
				
				/*******/
				
				$.imageCrop.submitOk = $.imageCrop.imageCropperMainWrapper.find( '.submit-ok' );
				$.imageCrop.submitCancel = $.imageCrop.imageCropperMainWrapper.find( '.submit-cancel' );
				var imgCropper = $.imageCrop.imageCropperMainWrapper.find( '.image-cropper' );
				var imgSrc = imgCropper.attr( 'src' );
				
				imgCropper.cropper({
					
					aspectRatio: 1,
					done: function( data ) {
						
						
						
					},
					modal: true,
					preview: ".image-cropper-preview"
					
				});
				
				$.imageCrop.submitOk.click( function( e2 ) {
					
					e2.preventDefault();
					
					var postData = {
						
						ajax: true,
						image_source: imgSrc,
						crop: {
							
							image_data: imgCropper.cropper( "getData" )
							
						},
						resize: {
							
							image_data: {
								
								width: 96,
								height: 96
								
							}
							
						}
						
					}
					
					$.ajax({
						
						type: "POST",
						data: postData,
						url: '<?= get_url( 'admin/main/plg/pn/image_cropper' ); ?>',
						success: function(data){
							
							$( '.vui-modal' ).removeClass( 'image-cropper-on' );
							
							console.log( data );
							
							if ( $options.callback ){
								
								$options.callback();
								$.imageCrop.close();
								
							}
							
						},
						error: function( xhr, textStatus, errorThrown ){
							
							
							
						}
						
					});
					
					console.log( JSON.stringify( imgCropper.cropper( "getData" ) ) )
					
				});
				
				$.imageCrop.submitCancel.on( 'click', function( e2 ) {
					
					e2.preventDefault();
					
					$.imageCrop.close();
					
				});
				
				$.imageCrop.watcher = setInterval( function(){ $.imageCrop.watcher_f() }, 500 );
				
				$.imageCrop.watcher_f = function() {
					
					$.imageCrop.updateImageCropper();
					
				}
				
				$.imageCrop.stopWatcher = function() {
					
					clearInterval( $.imageCrop.watcher );
					
				}
				
			},
			error: function( xhr, textStatus, errorThrown ){
				
				
				
			}
			
		});
		
	}
	
	$.imageCrop.close = function(){
		
		if ( $.imageCrop.watcher ){
			
			$.imageCrop.stopWatcher();
			
		}
		
		$.imageCrop.opened = false;
		
		$.imageCrop.imageCropperMainWrapper.remove();
		
	}
	
	$.imageCrop.updateImageCropper = function(){
		
		if ( $.imageCrop.opened ){
			
			var windowWidth = $( window ).outerWidth() - $.imageCrop.imageCropperControlsWrapper.outerWidth();
			var windowHeight = $( window ).outerHeight();
			
			$.imageCrop.originalWidth = $.imageCrop.img.data( 'original-width' );
			$.imageCrop.originalHeight = $.imageCrop.img.data( 'original-height' );
			
			newWrapperWidth = $.imageCrop.originalWidth;
			newWrapperHeight = $.imageCrop.originalHeight;
			
			
			
			$( document ).css( 'overflow', 'hidden' )
			
			if ( ( $.imageCrop.originalHeight < $.imageCrop.minSize ) && ( $.imageCrop.originalHeight > $.imageCrop.originalWidth ) ){
				
				newWrapperHeight = $.imageCrop.minSize;
				newWrapperWidth = newWrapperHeight * $.imageCrop.originalWidth / $.imageCrop.originalHeight;
				
				$.imageCrop.img.attr( 'data-original-width', newWrapperHeight );
				$.imageCrop.img.attr( 'data-original-height', newWrapperWidth );
				
				$.imageCrop.originalWidth = $.imageCrop.img.data( 'original-width' );
				$.imageCrop.originalHeight = $.imageCrop.img.data( 'original-height' );
				
				console.log( '1' )
				
			}
			else if ( ( $.imageCrop.originalWidth < $.imageCrop.minSize ) && ( $.imageCrop.originalHeight < $.imageCrop.originalWidth ) ){
				
				newWrapperWidth = $.imageCrop.minSize;
				newWrapperHeight = $.imageCrop.originalHeight / $.imageCrop.originalWidth * newWrapperWidth;
				
				$.imageCrop.img.attr( 'data-original-width', newWrapperHeight );
				$.imageCrop.img.attr( 'data-original-height', newWrapperWidth );
				
				$.imageCrop.originalWidth = $.imageCrop.img.data( 'original-width' );
				$.imageCrop.originalHeight = $.imageCrop.img.data( 'original-height' );
				
				console.log( '2' )
				
			}
			else if ( ( $.imageCrop.originalHeight == $.imageCrop.originalWidth ) ){
				
				ratio = $.imageCrop.minSize / $.imageCrop.originalHeight;
				
				newWrapperHeight = $.imageCrop.minSize;
				newWrapperWidth = $.imageCrop.originalWidth * ratio;
				
				$.imageCrop.originalHeight = $.imageCrop.originalHeight * ratio;
				$.imageCrop.originalWidth = $.imageCrop.originalWidth * ratio;
				
				console.log( '3' )
				
			}
			
			
			if ( ( $.imageCrop.originalWidth > $.imageCrop.originalHeight ) ){
				
				// checking height
				if ( ( $.imageCrop.originalHeight > windowHeight ) ){
					
					ratio = windowHeight / $.imageCrop.originalHeight;
					
					newWrapperHeight = windowHeight;
					newWrapperWidth = $.imageCrop.originalWidth * ratio;
					
					$.imageCrop.originalHeight = $.imageCrop.originalHeight * ratio;
					$.imageCrop.originalWidth = $.imageCrop.originalWidth * ratio;
					
					console.log( '4' )
					
				}
				
				// checking width
				if ( ( $.imageCrop.originalWidth > windowWidth ) ){
					
					ratio = windowWidth / $.imageCrop.originalWidth;
					
					newWrapperWidth = windowWidth;
					newWrapperHeight = $.imageCrop.originalHeight * ratio;
					
					$.imageCrop.originalWidth = $.imageCrop.originalWidth * ratio;
					$.imageCrop.originalHeight = $.imageCrop.originalHeight * ratio;
					
					console.log( '5' )
					
				}
				
			}
			else if ( ( $.imageCrop.originalWidth < $.imageCrop.originalHeight ) ){
				
				// checking width
				if ( ( $.imageCrop.originalWidth > windowWidth ) ){
					
					ratio = windowWidth / $.imageCrop.originalWidth;
					
					newWrapperWidth = windowWidth;
					newWrapperHeight = $.imageCrop.originalHeight * ratio;
					
					$.imageCrop.originalWidth = $.imageCrop.originalWidth * ratio;
					$.imageCrop.originalHeight = $.imageCrop.originalHeight * ratio;
					
					console.log( '6' )
					
				}
				
				// checking height
				if ( ( $.imageCrop.originalHeight > windowHeight ) ){
					
					ratio = windowHeight / $.imageCrop.originalHeight;
					
					newWrapperHeight = windowHeight;
					newWrapperWidth = $.imageCrop.originalWidth * ratio;
					
					$.imageCrop.originalHeight = $.imageCrop.originalHeight * ratio;
					$.imageCrop.originalWidth = $.imageCrop.originalWidth * ratio;
					
					console.log( '7' )
					
				}
				
			}
			
			$.imageCrop.imageCropperWrapper.css({
				
				'width': ( newWrapperWidth - ( $.imageCrop.margin * 2 ) ) + 'px',
				'height': ( newWrapperHeight - ( $.imageCrop.margin * 2 ) ) + 'px'
				
			});
			
			$.imageCrop.imageCropperWrapper.css( 'left', ( ( windowWidth - $.imageCrop.imageCropperWrapper.outerWidth() ) / 2 ) );
			$.imageCrop.imageCropperWrapper.css( 'top', ( ( windowHeight - $.imageCrop.imageCropperWrapper.outerHeight() ) / 2 ) );
			
			$.imageCrop.imageCropperMainWrapper.removeClass( 'loading' );
			
		}
		
	}
	
	$( window ).on( 'resize', function( e ){
		
		$.imageCrop.updateImageCropper();
		
	});
	
</script>
