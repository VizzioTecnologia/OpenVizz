<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
	
	$lang = $this->mcm->filtered_system_params[ $this->mcm->environment . '_language' ];
	
?>

<script type="text/javascript">
	
	$( document ).on( 'mousedown', '.fancybox-close', function( event ) {
		
		$( this ).qtip( 'destroy' );
		
	});
	
	$( document ).on('mouseover', '[title!=""]:not(.mce-tinymce):not( .mce-edit-area iframe )', function(event) {
		
		if ( $( this ).data( "qtip" ) ){
			
			return;
			
		}
		
		// Bind the qTip within the event handler
		$(this).qtip({
			target: 'mouse',
			adjust: {
				
				mouse: false
				
			},
			solo: true,
			prerender: false,
			overwrite: true,
			content: {
				//attr: 'title'
			},
			show: {
				event: event.type, // Use the same show event as the one that triggered the event handler
				ready: true, // Show the tooltip as soon as it's bound, vital so it shows up the first time you hover!
				delay: 1000,
				effect: function(offset) {
					$(this).fadeIn(0); // "this" refers to the tooltip
				}
			},
			hide: {
				//inactive: 1600,
				delay: 0,
				effect: function(offset) {
					$(this).fadeOut(0); // "this" refers to the tooltip
				},
				distance: 30
			},
			position: {
				my: 'bottom center',
				at: 'top center',
				target: $(this),
				viewport: $(document),
				adjust: {
					scroll: true
				},
				effect: false
			},
			style: {
				def: false,
				classes: 'vui-tooltip'
			},
			events: {
				show : function(event, api){
					
					if ( api.elements.target.data('ext-tip') ){
						
						api.set('content.text', rawurldecode(api.elements.target.data('ext-tip')) );
						
					}
					else{
						
						api.set('content.text', api.elements.target.attr('title').replace(/\n/g, '<br/>'));
						
					}
					
				},
				//this hide event will remove the qtip element from body and all assiciated events, leaving no dirt behind.
				hide: function( event, api ) {
					
					api.destroy( true ); // Destroy it immediately
					
				}
			}
			
		}, event); // Pass through our original event to qTip
	});
	
</script>
