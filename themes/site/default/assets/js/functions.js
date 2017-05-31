
/*************************************************/
/** Adiciona classes ao body baseado na largura **/

function responsive_width() {
	/*
	if ( $( window ).outerWidth() < 480 ){
		
		$( 'body' ).addClass( 'width-480-lower' );
		$( 'body' ).removeClass( 'width-480-640' );
		$( 'body' ).removeClass( 'width-640-960' );
		$( 'body' ).removeClass( 'width-960-greater' );
		
	}
	else if ( $( window ).outerWidth() >= 480 && $( window ).outerWidth() < 640 ){
		
		$( 'body' ).removeClass( 'width-480-lower' );
		$( 'body' ).addClass( 'width-480-640' );
		$( 'body' ).removeClass( 'width-640-960' );
		$( 'body' ).removeClass( 'width-960-greater' );
		
	}
	else if ( $( window ).outerWidth() >= 640 && $( window ).outerWidth() < 960 ){
		
		$( 'body' ).removeClass( 'width-480-lower' );
		$( 'body' ).removeClass( 'width-480-640' );
		$( 'body' ).addClass( 'width-640-960' );
		$( 'body' ).removeClass( 'width-960-greater' );
		
	}
	else if ( $( window ).outerWidth() > 960 ){
		
		$( 'body' ).removeClass( 'width-480-lower' );
		$( 'body' ).removeClass( 'width-480-640' );
		$( 'body' ).removeClass( 'width-640-960' );
		$( 'body' ).addClass( 'width-960-greater' );
		
	}
	*/
}

/** Adiciona classes ao body baseado na largura **/
/*************************************************/

$( window ).on( 'resize', function(){
	
// 	responsive_width();
	
});

$( document ).on( 'ready', function(){
	
	var sbh = ( $( '.ud-data-list-search-box' ).length > 0 ) ? $( '.ud-data-list-search-box' ).outerHeight() : 0;
	
	$( window ).on( 'resize scroll', function(){
		
		check_top_bar_scroll( $( this ) );
		
	});
	
	var check_top_bar_scroll = function( el ){
		
		var tbh = $( '#top-bar' ).outerHeight();
		var extra_h = ( ( $( window ).outerWidth() < 500 && $( '.ud-data-list-search-box' ).length > 0 ) ? ( tbh + sbh ) : 0 );
		
		console.log( 'extra_h: ' + extra_h );
// 		console.log( $('#footer-block').offset().top - $( window ).scrollTop() );
		
		if ( el.scrollTop() > tbh + extra_h ) {
			
			if ( $( '.ud-data-list-search-box' ).length > 0 ) {
				
				if ( ( $('#footer-block').offset().top - $( window ).scrollTop() ) < $( window ).outerHeight() ) {
					
					var fbh = $( window ).outerHeight() - ( $( '#footer-block' ).offset().top - $( window ).scrollTop() );
					
				}
				else {
					
					var fbh = 0;
				}
				
				if ( $( '.fake-search-box' ).length < 1 ) {
					
					$( '#ud-d-search-results-wrapper' ).before( '<div class="fake-search-box" style="height:' + $( '.ud-data-list-search-box' ).outerHeight() + 'px"/>' );
					
				}
				
				$( '.ud-data-list-search-box' ).css({
					
					'max-height': ( $( window ).outerHeight() - tbh - fbh ),
					'top': tbh,
					'left': $( '.fake-search-box' ).offset().left
																														  
				});
				
			}
			
			$( '#site-block' ).css( 'padding-top', tbh + extra_h );
			
			//$( '#jquery-scrolltop' ).fadeIn( 300 );
			$( 'html' ).addClass( 'top-bar-fixed-on' );
			$( 'html' ).removeClass( 'top-bar-fixed-off' );
			
		} else {
			
			if ( $( '.ud-data-list-search-box' ).length > 0 ) {
				
				if ( $( '.fake-search-box' ).length > 0 ) {
					
					$( '.fake-search-box' ).remove();
					
				}
				
				$( '.ud-data-list-search-box' ).css({
					
					'max-height': '',
					'top': '',
					'left': '',
					'right': ''
					
				});
				
			}
			
			$( '#site-block' ).css( 'padding-top', '' );
			
			//$( '#jquery-scrolltop' ).fadeOut( 300 );
			$( 'html' ).addClass( 'top-bar-fixed-off' );
			$( 'html' ).removeClass( 'top-bar-fixed-on' );
			
		}
		
		
		
	};
	
// 	responsive_width();
	
	$( '#top-menu' ).addClass( 'menus-wrapper' ).prepend( '<div class="menu-switch">' );
	
	$( '.menu-switch' ).on( 'click', function(){
		
		$( this ).closest( '.menus-wrapper' ).toggleClass( 'menu-open' );
		$( 'body' ).toggleClass( 'menu-open' );
		
	});
	
});
