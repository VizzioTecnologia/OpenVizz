
/*************************************************/
/** Adiciona classes ao body baseado na largura **/

function responsive_width() {
	
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
	
}

/** Adiciona classes ao body baseado na largura **/
/*************************************************/

$( window ).on( 'resize', function(){
	
	responsive_width();
	
});

$( document ).on( 'ready', function(){
	
	responsive_width();
	
	$( '#top-menu' ).addClass( 'menus-wrapper' ).prepend( '<div class="menu-switch">' );
	
	$( '.menu-switch' ).on( 'click', function(){
		
		$( this ).closest( '.menus-wrapper' ).toggleClass( 'menu-open' );
		$( 'body' ).toggleClass( 'menu-open' );
		
	});
	
});