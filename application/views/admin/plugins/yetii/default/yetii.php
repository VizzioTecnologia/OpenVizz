<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script type="text/javascript">
	
	$.fn.changeElementType = function(newType) {
		var newElements = [];
	
		$(this).each(function() {
			var attrs = {};
	
			$.each(this.attributes, function(idx, attr) {
				attrs[attr.nodeName] = attr.nodeValue;
			});
	
			var newElement = $("<" + newType + "/>", attrs).append($(this).contents());
	
			$(this).replaceWith(newElement);
	
			newElements.push(newElement);
		});
	
		return $(newElements);
	};
	
	function makeTabs( container, tabItemsSelector, titleSelector, tabContainerId, callbackFunction ) {
		
		$( 'body' ).addClass( 'tabs-on' );
		
		if ( container == null || container == '' ){
			
			container = $( '.tabs-wrapper' );
			
		}
		
		if ( tabItemsSelector == null || tabItemsSelector == '' ){
			
			tabItemsSelector = 'fieldset';
			
		}
		
		if ( titleSelector == null || titleSelector == '' ){
			
			titleSelector = 'legend';
			
		}
		
		if ( tabContainerId == null || tabContainerId == '' ){
			
			tabContainerId = escape( encodeURIComponent( window.location.pathname.replace(/[^a-zA-Z0-9]/g, '') ) );
			
		}
		
		container.addClass( 'tabs-on' );
		container.attr( 'id', tabContainerId );
		container.prepend( '<ul id="' + tabContainerId + '-nav" class="tabs-container"></ul>' );
		
		tbi = container.find( '.tabs-info' );
		if ( tbi.length > 0 ){
			
			container.find( '.tabs-container' ).prepend( '<li class="tabs-info tab-item-wrapper"><div class="tab-item">' + tbi.html() + '</div></li>' );
			tbi.remove();
			
		}
		
		tbh = container.find( '.tabs-header' );
		if ( tbh.length > 0 ){
			
			container.find( '.tabs-container' ).prepend( '<li class="tabs-header tab-item-wrapper"><div class="tab-item">' + tbh.text() + '</div></li>' );
			tbh.remove();
			
		}
		
		itemSelector = $( tabItemsSelector );
		
		itemSelector.each( function( index ) {
			
			var attrs = { };
			
			$( this ).find( titleSelector ).find( "*:not(br, span, bdo, map, object, img, tt, i, b, big, small , ins, del, script, input, select, textarea, label, button, em, strong, dfn, code, q, samp, kbd, var, cite, abbr, acronym, sub, sup )" ).changeElementType( 'span' );
			
			$( '#' + tabContainerId + '-nav').append('<li class="tab-item-wrapper"><a class="tab-item" href="#tab' + index + '">' + $( this ).find( titleSelector ).html() + '</a></li>');
			$(this).find( titleSelector ).remove();
			$(this).attr( 'id', 'tab' + index ).addClass( 'tab' );
		});
		
		var tabber1 = new Yetii({
			
			id: tabContainerId,
			persist: true
			
		});
		
		if ( typeof callbackFunction !== 'undefined' && typeof callbackFunction === 'function' ){
			
			callbackFunction();
			
		}
		
	}
	
</script>
