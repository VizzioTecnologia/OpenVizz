<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script type="text/javascript">
	
	function liveSearch( search_url, funtionOnShow ){
		
		if ( ! search_url ){
			
			console.warn( 'ViaCMS Live Search: url não especificada!' )
			
		}
		
		$( 'input.live-search' ).each( function() {
			
			var elem = $(this);
			
			elem.attr('autocomplete', 'off');
			
			if ( ! search_url ){
				
				var search_url = elem.data( 'live-search-url' );
				
			}
			
			var search_filters_selector = elem.data( 'live-search-filters-selector' ) != undefined ? elem.data( 'live-search-filters-selector' ) : undefined;
			var search_min_length = elem.data( 'live-search-min-length' ) != undefined ? elem.data( 'live-search-min-length' ) : 3;
			
			if ( search_url ){
				
				elem.qtip({
					prerender: false,
					overwrite: true,
					content: {
						
						text: function(event, api) {
							
						}
						
					},
					show: {
						event: 'keyup click focus',
						effect: function(offset) {
							
							$(this).fadeIn( 300 ); // "this" refers to the tooltip
							
						},
						delay: 700
					},
					events: {
						show : function( event, api ){
							
							var leng = api.elements.target.val();
							leng = leng.length;
							
							console.log( search_min_length )
							
							if ( leng >= search_min_length ) {
								
								api.set( 'content.text', '<?= lang('msg_loading'); ?>' );
								
								api.elements.content.closest( '.qtip.live-search' ).addClass( 'loading' );
								
								//console.log(search_url)
								
								var _data = {
									
									q: api.elements.target.val(),
									contact_id: api.elements.target.data('contact-id'),
									ajax: true
									
								}
								
								if ( search_filters_selector ){
									
									$( search_filters_selector ).each( function( index ){
										
										var _jthis = $( this );
										var _name = _jthis.attr( 'name' );
										var _value = _jthis.val();
										
										if ( _value != undefined && _name != undefined ) {
											
											_data[ _name ] = _value;
											
										}
										
									});
									
								}
								
								$.ajax({
									type: "GET",
									data: _data,
									url: search_url,
									success: function(data){
										
										// Setup the map container and append it to the tooltip
										var $container = $( '<div class="s1"></div>' ).appendTo( api.elements.content.empty() );
										
										$container.html( data );
										
										$search_results = $container.find( '.live-search-results' );
										$search_results.prepend( '<li class="plugins-names-wrapper">' );
										$search_results.find( '.plugin-name' ).prependTo( $search_results.find( '.plugins-names-wrapper' ) );
										
										
										api.set('content.text', $container);
										
										if ( typeof( funtionOnShow ) == 'function' ){
											
											funtionOnShow();
											
										}
										
										api.elements.content.closest( '.qtip.live-search.loading' ).removeClass( 'loading' );
										
									},
									error: function(xhr, textStatus, errorThrown){
										
										console.log('content.text', status + ': ' + error);
										
										for( i in xhr ){
											
											if(i!="channel")
											console.log(i + '>> ' + xhr[i]);
											
										};
										
									}
								});
								
							}
							else {
								
								return false;
								
							}
							
						},
						hide : function(event, api){
							
						}
					},
					position: {
						target: elem,
						viewport: $(document),
						effect: false
					},
					hide: {
						event: 'keydown unfocus',
						effect: function(offset) {
							$(this).fadeOut(100); // "this" refers to the tooltip
						}
					},
					style: {
						def: false,
						classes: 'qtip-viacms-live-search live-search qtip-viacms'
					}
				});
				
			}
			
		});
		
	}
	
	$( document ).on( 'ready', function(){
		
		liveSearch();
		
	});
	
</script>
