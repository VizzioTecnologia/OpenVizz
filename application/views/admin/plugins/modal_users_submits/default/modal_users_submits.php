<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script type="text/javascript">
	
	// deve ser executada sempre que algum elemento modal contacts for adicionado dinamicamente via js
	window.modalUsersSubmits = function(){
		
		var modalUserSubmitClass = '.modal-users-submits';
		
		$( modalUserSubmitClass + ':not([data-mus-grabbed])' ).each( function( index ) {
			
			var ajaxUserSubmitsBaseUrl = '<?= get_url( 'admin/submit_forms/us_ajax' ); ?>';
			
			var jthis = $( this );
			
			var modalUsersSubmitsUrl = ajaxUserSubmitsBaseUrl;
			var modaUserSubmitsAction = 'gus';
			var lastModalGroup = '';
			var submitFormId = false;
			var userSubmitId = false;
			
			jthis.data( 'mus-grabbed', 'true' );
			jthis.attr( 'data-mus-grabbed', 'true' );
			
			if ( jthis.attr( 'data-mus-action' ) ){
				
				modaUserSubmitsAction = jthis.data( 'mus-action' );
				
			}
			
			if ( jthis.attr( 'data-mus-last-modal-group' ) ){
				
				lastModalGroup = jthis.data( 'mus-last-modal-group' );
				
			}
			
			if ( jthis.attr( 'data-submit-form-id' ) ){
				
				submitFormId = jthis.data( 'submit-form-id' );
				
			}
			if ( jthis.attr( 'data-user-submit-id' ) ){
				
				userSubmitId = jthis.data( 'user-submit-id' );
				
			}
			
			if ( ( modaUserSubmitsAction == 'gus' || modaUserSubmitsAction == 'vus' ) && submitFormId && userSubmitId ){
				
				modalUsersSubmitsUrl += '/a/gus?usid=' + userSubmitId + '&sfid=' + submitFormId;
				
			}
			else if ( modaUserSubmitsAction == 'edit' && userSubmitId ){
				
				modalUsersSubmitsUrl += '/edit?contact_id=' + userSubmitId;
				
			}
			
			modalUsersSubmitsUrl += '&ajax=true' + ( lastModalGroup ? '&last-modal-group=' + lastModalGroup : '' );
			
			jthis.attr( 'href', modalUsersSubmitsUrl );
			
		});
		
		
		/***************************************************/
		/********************* Fancybox ********************/
		
		$( modalUserSubmitClass ).fancybox( {
			
			//live: true,
			type: 'ajax',
			fitToView: true,
			autoSize: false,
			closeClick: false,
			openEffect: 'none',
			closeEffect: 'none',
			closeBtn: true,
			title: function(){
				
				var title = this.text();
				
				if ( this.attr( 'data-mus-title' ) ){
					
					title = this.data( 'mus-title' );
					
				}
				
				return title;
				
			},
			helpers: {
				
				overlay: {
					
					showEarly  : true,
					closeClick: false
					
				}
				
			},
			wrapCSS: 'vui-modal',
			beforeLoad: function(){
				
				if ( ! modalFancybox ){
					
					window.modalFancybox = this;
					
				}
				
			},
			beforeShow: function(){
				
				console.log( 'fancybox opened' )
				
				$( '.modal-content' ).css({
					
					'opacity': 0
					
				})
				
				$( '.fancybox-inner > .modal-content' ).each( function( index ) {
					
					var jthis = $( this );
					
					var lengModalControls = jthis.parent().find( '.modal-controls:not(.modal-controls-bottom)' ).length;
					var lengModalControlsBottom = jthis.parent().find( '.modal-controls-bottom' ).length;
					
					if ( lengModalControls > 0 ) {
						
						console.log( 'have controls at top' )
						jthis.css( 'margin-top', jthis.parent().find( '.modal-controls' ).not( '.modal-controls-bottom' ).outerHeight() );
						jthis.css( 'max-height', 'calc( 100% - ' + jthis.parent().find( '.modal-controls' ).not( '.modal-controls-bottom' ).outerHeight() + 'px )' );
						
					}
					if ( lengModalControlsBottom > 0 ) {
						
						console.log( 'have controls at bottom' )
						jthis.css( 'margin-bottom', jthis.parent().find( '.modal-controls-bottom' ).outerHeight() );
						jthis.css( 'max-height', 'calc( 100% - ' + jthis.parent().find( '.modal-controls-bottom' ).outerHeight() + 'px )' );
						
					}
					
				});
				
				$.fancybox.update();
				$.fancybox.reposition();
				
			},
			afterShow: function(){
				
				$( '.modal-content' ).css({
					
					'opacity': 1
					
				})
				
				$( '.fancybox-inner > .modal-content' ).each( function( index ) {
					
					var jthis = $( this );
					
					var lengModalControls = jthis.parent().find( '.modal-controls:not(.modal-controls-bottom)' ).length;
					var lengModalControlsBottom = jthis.parent().find( '.modal-controls-bottom' ).length;
					
					if ( lengModalControls > 0 ) {
						
						console.log( 'top' )
						jthis.css( 'margin-top', jthis.parent().find( '.modal-controls' ).not( '.modal-controls-bottom' ).outerHeight() );
						jthis.css( 'max-height', 'calc( 100% - ' + jthis.parent().find( '.modal-controls' ).not( '.modal-controls-bottom' ).outerHeight() + 'px )' );
						
					}
					if ( lengModalControlsBottom > 0 ) {
						
						console.log( 'bottom' )
						jthis.css( 'margin-bottom', jthis.parent().find( '.modal-controls-bottom' ).outerHeight() );
						jthis.css( 'max-height', 'calc( 100% - ' + jthis.parent().find( '.modal-controls-bottom' ).outerHeight() + 'px )' );
						
					}
					
				});
				
				$.fancybox.update();
				$.fancybox.reposition();
				
			},
			afterLoad: function(){
				
			}
			
		});
		
		/********************* Fancybox ********************/
		/***************************************************/
		
	}
	
	$( document ).bind( 'ready', function(){
		
		if ( typeof window.modalFancybox == 'undefined' ){
			
			window.modalFancybox = false;
			
		}
		
		modalUsersSubmits();
		
	});
	
</script>
