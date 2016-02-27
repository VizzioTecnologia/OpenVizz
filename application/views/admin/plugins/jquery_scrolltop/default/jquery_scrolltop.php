<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script type="text/javascript">

	$( window ).scroll( function(){
		
		checkScroll( $( this ) );
		
	});
	var checkScroll = function( el ){
		
		if ( el.scrollTop() > 50 ) {
			
			//$( '#jquery-scrolltop' ).fadeIn( 300 );
			$( '#jquery-scrolltop' ).addClass( 'jquery-scrolltop-on' );
			$( '#jquery-scrolltop' ).removeClass( 'jquery-scrolltop-off' );
			
		} else {
			
			//$( '#jquery-scrolltop' ).fadeOut( 300 );
			$( '#jquery-scrolltop' ).addClass( 'jquery-scrolltop-off' );
			$( '#jquery-scrolltop' ).removeClass( 'jquery-scrolltop-on' );
			
		}
		
		
		
	};

	$( document ).on( 'ready', function(){
		
		$.jqueryScrollTop = $( '<div id="jquery-scrolltop" class="jquery-scrolltop-off"><div class="inner"></div></div>"' );
		
		$.jqueryScrollTop.appendTo( 'body' );
		
		$( document ).on( 'click', '#jquery-scrolltop', function(){
			
			$("html, body").stop().animate({ scrollTop: 0 }, 500);
			return false;
			
		});
		
		checkScroll( $( window ) );
		
	});
	
</script>
