<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script type="text/javascript">
	
	window.modalContactClass = '.modal-contact';
	
	// deve ser executada sempre que algum elemento modal contacts for adicionado dinamicamente via js
	window.modalContacts = function(){
		
		$( modalContactClass + ':not([data-mc-grabbed])' ).each( function( index ) {
			
			var ajaxContactsBaseUrl = '<?= base_url() . 'admin/contacts/ajax'; ?>';
			
			var jthis = $( this );
			
			var modalContactsUrl = ajaxContactsBaseUrl;
			var modaContactsAction = 'get';
			var lastModalGroup = '';
			var contactId = false;
			
			jthis.data( 'mc-grabbed', 'true' );
			jthis.attr( 'data-mc-grabbed', 'true' );
			
			if ( jthis.attr( 'data-mc-action' ) ){
				
				modaContactsAction = jthis.data( 'mc-action' );
				
			}
			
			if ( jthis.attr( 'data-mc-last-modal-group' ) ){
				
				lastModalGroup = jthis.data( 'mc-last-modal-group' );
				
			}
			
			if ( jthis.attr( 'data-contact-id' ) ){
				
				contactId = jthis.data( 'contact-id' );
				
			}
			
			if ( modaContactsAction == 'get' && contactId ){
				
				modalContactsUrl += '/get_contact_data?contact_id=' + contactId;
				
			}
			else if ( modaContactsAction == 'edit' && contactId ){
				
				modalContactsUrl += '/edit?contact_id=' + contactId;
				
			}
			
			modalContactsUrl += '&ajax=true' + ( lastModalGroup ? '&last-modal-group=' + lastModalGroup : '' );
			
			jthis.attr( 'href', modalContactsUrl );
			
		});
		
		openModalContact();
		
	}
	
	// deve ser executada sempre que algum elemento modal contacts for adicionado dinamicamente via js
	window.openModalContact = function(){
		
		/***************************************************/
		/********************* Fancybox ********************/
		
		$( modalContactClass ).fancybox( {
			
			//live: true,
			type: 'ajax',
			fitToView: true,
			autoSize: true,
			closeClick: false,
			openEffect: 'none',
			closeEffect: 'none',
			closeBtn: true,
			title: function(){
				
				var title = this.text();
				
				if ( this.attr( 'data-mc-title' ) ){
					
					title = this.data( 'mc-title' );
					
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
			afterLoad: function(){
				console.log(window.modalFancybox)
				$.fancybox.update();
				$.fancybox.reposition();
				
			},
			
		});
		
		/********************* Fancybox ********************/
		/***************************************************/
		
	}
	
	$( document ).bind( 'ready', function(){
		
		if ( typeof window.modalFancybox == 'undefined' ){
			
			window.modalFancybox = false;
			
		}
		
		modalContacts();
		
	});
	
</script>
