
function escapeHtml(text) {
  var map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };

  return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

jQuery.fn.preventDoubleSubmit = function() {
	/* ta dando pau na geração de pdf
	jQuery(this).submit(function() {
		if (this.beenSubmitted)
			return false;
		else
			this.beenSubmitted = true;
	});
	*/
};

// Create a jGrowl
window.createGrowl = function( gContent, persistent, title, type ) {
	
	if ( $( 'body' ).hasClass( 'with-toolbar' ) ) {
		
		$( '#qtip-growl-container' ).css( 'top', $( '#toolbar' ).outerHeight() + 10 );
		
	}
	
	$('<div/>').qtip({
		content: {
			text: gContent
		},
		position: {
			target: [-400,0],
			my: 'top left',
			at: 'right center',
			container: $('#qtip-growl-container')
		},
		show: {
			event: false,
			ready: true,
			effect: function() {
				$(this).css({ opacity: 0 });
				$(this).stop(0, 1).animate({ height: 'toggle', opacity: 1 }, 400, 'swing');
			},
			delay: 0,
			persistent: persistent
		},
		hide: {
			event: false,
			effect: function(api) {
				$(this).stop(0, 1).animate({ height: 'toggle', opacity: 0 }, 400, 'swing');
			}
		},
		style: {
			width: 400,
			def: false,
			classes: 'notification vui-tooltip' + ( type ? ' ' + type : '' ),
			tip: false
		},
		events: {
			render: function(event, api) {
				if( ! api.options.show.persistent ) {
					
					var msgType = 'msg-type-normal';
					var msgTypesCount = 0;
					var msgSuccessOn,
					msgErrorOn,
					msgWarningOn,
					msgInfoOn = false;
					
					if ( ! type ){
						
						$( this ).find( '.msg-item' ).each(function(index) {
							
							if ( $( '.msg-type-success' ).length > 0 ){
								
								if ( ! msgSuccessOn ) {
									
									msgTypesCount++;
									msgSuccessOn = true;
									
								}
								
								msgType = 'msg-type-success';
								
							}
							if ( $( '.msg-type-error' ).length > 0 ){
								
								if ( ! msgErrorOn ) {
									
									msgTypesCount++;
									msgErrorOn = true;
									
								}
								
								msgType = 'msg-type-error';
								
							}
							if ( $( '.msg-type-warning' ).length > 0 ){
								
								if ( ! msgWarningOn ) {
									
									msgTypesCount++;
									msgWarningOn = true;
									
								}
								
								msgType = 'msg-type-warning';
								
							}
							if ( $( '.msg-type-info' ).length > 0 && msgTypesCount == 0 ){
								
								msgType = 'msg-type-info';
								
							}
							
						});
						
						if ( msgTypesCount > 1 ){
							
							msgType = 'msg-type-mix';
							
						}
						
						$( this ).removeClass( 'msg-type-normal msg-type-success msg-type-error msg-type-warning msg-type-info msg-type-mix' )
						
						$( this ).addClass( msgType )
						
					}
					
					$(this).bind('mouseover mouseout', function(e) {
						var lifespan = 5000;
						
						clearTimeout(api.timer);
						if (e.type !== 'mouseover') {
							api.timer = setTimeout(function() { api.hide(e); }, lifespan);
						}
					})
					.triggerHandler('mouseout');
					
				}
				api.set('content.title', title ? title : '&nbsp;' );
				api.set('content.button', true);
			}
		}
	});
};

function makeNewElementFromElement( tag, elem ) {

	var newElem = document.createElement(tag),
		i, prop,
		attr = elem.attributes,
		attrLen = attr.length;

	// Copy children 
	elem = elem.cloneNode(true);
	while (elem.firstChild) {
		newElem.appendChild(elem.firstChild);
	}

	// Copy DOM properties
	for (i in elem) {
		try {
			prop = elem[i];
			if (prop && i !== 'outerHTML' && (typeof prop === 'string' || typeof prop === 'number')) {
				newElem[i] = elem[i];
			}
		} catch(e) { /* some props throw getter errors */ }
	}

	// Copy attributes
	for (i = 0; i < attrLen; i++) {
		newElem.setAttribute(attr[i].nodeName, attr[i].nodeValue);
	}

	// Copy inline CSS
	newElem.style.cssText = elem.style.cssText;

	return newElem;
}

// implementando a função trim
if (!String.prototype.trim) {
	String.prototype.trim=function(){return this.replace(/^\s+|\s+$/g, '');};
	
	String.prototype.ltrim=function(){return this.replace(/^\s+/,'');};
	
	String.prototype.rtrim=function(){return this.replace(/\s+$/,'');};
	
	String.prototype.fulltrim=function(){return this.replace(/(?:(?:^|\n)\s+|\s+(?:$|\n))/g,'').replace(/\s+/g,' ');};
}

function rand() {
	
	return Math.random().toString(36).substr(2); // remove `0.`
	
};

function token() {
	
	return rand() + rand(); // to make it longer
	
};

/*************************************************/
/******** Verificação de float em inputs *********/

function checkInputFloats( field ) {
	
	if ( field == undefined ){
		field = $('.input-float-filter');
	}
	
	field.each(function(index){
		
		var jthis = $(this);
		var num = jthis.val();
		
		if( ! (parseFloat(num) >= 0) ){
			jthis.addClass('field-error');
		}
		else{
			jthis.removeClass('field-error');
		}
		
	});
	
}

/******** Verificação de float em inputs *********/
/*************************************************/

/*************************************************/
/******************** Toolbar ********************/

$(document).on( 'ready', function(){
	
	adjust_site_block();
	
	
});

$('table.arrow-nav').on( 'keydown', function(e){
	
	var $table = $(this);
	var $active = $('input:focus,select:focus',$table);
	var $next = null;
	var focusableQuery = 'input:visible,select:visible,textarea:visible';
	var position = parseInt( $active.closest('td').index()) + 1;
	console.log('position :',position);
	switch(e.keyCode){
		case 37: // <Left>
			$next = $active.parent('td').prev().find(focusableQuery);   
			break;
		case 38: // <Up>                    
			$next = $active
				.closest('tr')
				.prev()                
				.find('td:nth-child(' + position + ')')
				.find(focusableQuery)
			;
			
			break;
		case 39: // <Right>
			$next = $active.closest('td').next().find(focusableQuery);            
			break;
		case 40: // <Down>
			$next = $active
				.closest('tr')
				.next()                
				.find('td:nth-child(' + position + ')')
				.find(focusableQuery)
			;
			break;
	}       
	if($next && $next.length)
	{        
		$next.focus();
	}
	
});

$( window ).on( 'resize', function(){
	
	reset_toolbar();
	
});
$( window ).on( 'load', function(){
	
	adjust_site_block();
	
});

function adjust_site_block(){
	
	console.log( 'adjust_site_block()' );
	
	var height = $( '#toolbar' ).data( 'current-height' ) != undefined ? $( '#toolbar' ).data( 'current-height' ) : $( '#toolbar' ).outerHeight()
	
	$( '#site-block' ).css( 'padding-top', height );
	$( '.tabs-container' ).css( 'padding-top', height );
	
};

function reset_toolbar(){
	
	retract_toolbar();
	
	$( '#toolbar' ).data( 'expanded-height', null );
	$( '#toolbar' ).data( 'orig-height', null );
	$( '#toolbar' ).data( 'current-height', null );
	$( '#toolbar' ).css( 'height', '' );
	$( '#toolbar' ).css( 'overflow', '' );
	$( '#toolbar-expander' ).css( 'height', '' );
	
	$( '#toolbar' ).removeClass( 'toolbar-expanded' );
	
	adjust_site_block();
	
};
function retract_toolbar(){
	
	console.log( 'Redução' );
	$( '#toolbar' ).data( 'current-height', $( '#toolbar' ).data( 'orig-height' ) );
	console.log( $( '#toolbar' ).data( 'current-height' ) );
	
	// Redução
	
	$( '#toolbar' ).css( 'height', $( '#toolbar' ).data( 'orig-height' ) );
	
};
function expand_toolbar(){
	
	// Expansão
	
	if ( $( '#toolbar' ).data( 'expanded-height' ) == null ) {
		
		$( '#toolbar' ).css( 'height', 'auto' );
		$( '#toolbar' ).data( 'expanded-height', $( '#toolbar' ).outerHeight() );
		
	}
	if ( $( '#toolbar' ).outerHeight() >= $( window ).outerHeight() ) {
		
		$( '#toolbar' ).css({
			
			'overflow': 'auto',
			'height': $( window ).outerHeight()
			
		});
		
	}
	
	console.log( 'Expansão' );
	$( '#toolbar' ).data( 'current-height', $( '#toolbar' ).data( 'expanded-height' ) );
	console.log( $( '#toolbar' ).data( 'current-height' ) );
	
	$( '#toolbar' ).css( 'height', $( '#toolbar' ).data( 'expanded-height' ) );
	
};
function move_elements_to_toolbar() {
	
	/*
	 * 
	 * Se existir algum elemento com a classe ".to-toolbar"
	 * (que indica que o elemento deve ser movido para a barra de ferramentas)
	 * e possuir a classe ".toolbar-left" (o qual indica que o elemento deve ser movido para
	 * uma posição imediatamente após o container da barra de ferramentas principal (#toolbar-main)),
	 * e se já não existir o container desses elementos, criamos o mesmo agora
	 * 
	 */
	
	if ( $( '.to-toolbar.toolbar-left' ).length > 0 && ! $( '#toolbar > #toolbar-moved-elements-before' ).length > 0 ){
		
		/*
		 * 
		 * Se existir o container da barra de ferramentas principal (#toolbar-main),
		 * apenas anexamos o elemento imediatamente após à este
		 * 
		 */
		
		if ( $( '#toolbar > #toolbar-main' ).length == 1 ){
			
			$( '#toolbar > #toolbar-main' ).after( '<div id="toolbar-moved-elements-before" class="toolbar-child" />' );
			
		}
		
		/*
		 * 
		 * Caso contrário, anexamos ao final
		 * 
		 */
		
		else{
			
			$( '#toolbar' ).append( '<div id="toolbar-moved-elements-before" class="toolbar-child" />' );
			
		}
		
	}
	if ( $( '.to-toolbar.to-main-toolbar' ).length > 0 && ! $( '#toolbar > #toolbar-moved-elements-before' ).length > 0 ){
		
		if ( $( '#toolbar > #toolbar-main' ).length == 0 ){
			
			$( '#toolbar' ).after( '<div id="toolbar-main" class="toolbar-child" />' );
			
		}
		
		if ( $( '#toolbar > #toolbar-main .toolbar-moved-elements' ).length == 0 ){
			
			$( '#toolbar > #toolbar-main' ).append( '<div class="toolbar-moved-elements" />' );
			
		}
		
	}
	
	
	
	
	if ( $( '.to-toolbar' ).not( '.toolbar-left, .to-main-toolbar' ).length > 0 && ! $( '#toolbar > #toolbar-moved-elements-after' ).length > 0 ){
		
		$( '#toolbar' ).append( '<div id="toolbar-moved-elements-after" class="toolbar-child" />' );
		
	}
	
	if ( $( '.to-toolbar' ).not( '#toolbar .to-toolbar' ).length > 0 ){
		
		$('.to-toolbar').each(function(){
			
			var jthis = $(this);
			var tagName, val;
			var __append = false;
			
			tagName = jthis.get(0).tagName;
			
			// se o elemento for uma div
			if ( tagName == 'DIV' ){
				
				__append = true;
				
				console.log( '[Template notification] Div moved to toolbar' );
				
			}
			// se o elemento for um botão
			else if ( tagName == 'BUTTON' ){
				
				var form = false;
				
				__append = true;
				
				if ( form = jthis.closest( 'form' ) ){
					
					if ( ! form.attr( 'id' ) ){
						
						var form_id = token();
						
						form.attr( 'id', form_id );
						
					}
					
					jthis.attr( 'form', form.attr( 'id' ) );
					
					
				}
				
				console.log( '[Template notification] Button moved to toolbar' );
				
			}
			// se o elemento for um link
			else if ( tagName == 'A' ){
				
				__append = true;
				
				console.log( '[Template notification] Link moved to toolbar' );
				
			}
			
			if ( __append ){
			
				if ( jthis.hasClass( 'to-main-toolbar' ) ){
				
					jthis.appendTo("#toolbar-main .toolbar-moved-elements");
					
				}
				else if ( jthis.hasClass( 'toolbar-left' ) ){
					
					$( '#toolbar' ).addClass( 'expand' );
					
					jthis.appendTo("#toolbar-moved-elements-before");
					
				}
				else{
					
					$( '#toolbar' ).addClass( 'expand' );
					
					jthis.appendTo("#toolbar-moved-elements-after");
					
				}
				
			}
			
		});
		
		if ( ! $( '#toolbar #clear-toolbar-end' ).length == 1 || ! $( '#toolbar .clear' ).length == 1 ){
			
			$( '#toolbar #toolbar-moved-elements-after' ).after( '<div class="clear" id="clear-toolbar-end" />' );
			
		}
		
	}
	
	/*
	 * 
	 * Código do expansor
	 * 
	 * Se for encontrado qualquer elemento ignorando o container da barra de ferramentas principal (#toolbar-main),
	 * acrescentamos o botão responsável por expandir a barra de ferramentas
	 * 
	 */
	
	if ( $( '#toolbar > *' ).not( '#toolbar-main, .clear' ).length > 0 && ! $( '#toolbar > #toolbar-expander' ).length > 0 ){
		
		$( '#toolbar' ).append( '<a href="#" id="toolbar-expander" class="toolbar-child" />' );
		
	}
	
	$( '#toolbar-expander' ).on( 'click', function( e ) {
		
		var jthis = $( this );
		
		if ( $( '#toolbar' ).data( 'orig-height' ) == null && ! $( '#toolbar' ).hasClass( 'toolbar-expanded' ) ) {
			
			$( '#toolbar' ).css( 'height', 'auto' );
			$( '#toolbar' ).data( 'orig-height', $( '#toolbar' ).outerHeight() );
			$( '#toolbar' ).data( 'current-height', $( '#toolbar' ).data( 'orig-height' ) );
			jthis.css( 'height', $( '#toolbar' ).data( 'current-height' ) );
			console.log( $( '#toolbar' ).data( 'current-height' ) );
			
		}
		
		$( '#toolbar' ).toggleClass( 'toolbar-expanded' );
		
		if ( ! $( '#toolbar' ).hasClass( 'toolbar-expanded' ) ) {
			
			retract_toolbar();
			
		}
		else {
			
			expand_toolbar();
			
		}
		
		adjust_site_block();
		
		return false;
		
	})
	
}

/******************** Toolbar ********************/
/*************************************************/

/*************************************************/
/** Adiciona classes ao body baseado na largura **/

function responsive_width() {
	
	if ( $( window ).outerWidth() < 500 ){
		
		$( 'body' ).removeClass( 'width-500-900-less' );
		$( 'body' ).addClass( 'width-0-500-less' );
		
	}
	else if ( $( window ).outerWidth() >= 500 && $( window ).outerWidth() < 900 ){
		
		$( 'body' ).removeClass( 'width-0-500-less' );
		$( 'body' ).addClass( 'width-500-900-less' );
		
	}
	
}

/** Adiciona classes ao body baseado na largura **/
/*************************************************/

$( document ).on( 'keydown', function( e ){
	/*
	shifted = e.shiftKey;
	
	key = e.which;
	
	pressed_key = key;
	pressed_key += e.ctrlKey ? ' Ctrl' : '';
	pressed_key += e.shiftKey ? ' Shift' : '';
	pressed_key += e.altKey ? ' Alt' : '';
	pressed_key.trim();
	
	window.pressedKey = pressed_key;
	console.debug('key code is: ' + pressed_key);
	*/
});

$( window ).on( 'resize', function(){
	
	responsive_width();
	
});

$(document).bind('ready', function(){
	/*
	window.pressedKey = null;
	*/
	// checa se tinyMCE foi carregado
	is_tinyMCE_active = false;
	if (typeof(tinyMCE) != "undefined") {
		if (tinyMCE.activeEditor == null || tinyMCE.activeEditor.isHidden() != false) {
			is_tinyMCE_active = true;
		}
	}
	
	move_elements_to_toolbar();
	
	responsive_width();
	
	/*
	$( '.js-editor, .mce-textbox' ).on( 'keydown keypress', function( e ) {
		
		var keyCode = ( e.keyCode ? e.keyCode : e.which );
		
		console.debug( 'Textarea key pressed: ' + keyCode );
		
		if ( keyCode == 9 ){
			
			e.preventDefault();
            e.stopPropagation();
			
			var start = this.selectionStart;
			var end = this.selectionEnd;
	
			// set textarea value to: text before caret + tab + text after caret
			$(this).val($(this).val().substring(0, start)
				+ "\t"
				+ $(this).val().substring(end));
	
			// put caret at right position again
			this.selectionStart =
			this.selectionEnd = start + 1;
			
		}
		
	});
*/
	
	msgContent = '';
	$('body').append('<div id="qtip-growl-container">');
	
	$('.msg').each(function(index) {
		
		var msgType = 'msg-type-normal';
		var msgTypesCount = 0;
		
		if ( $( '.msg-type-success' ).length > 0 ){
			
			msgTypesCount++;
			msgType = 'msg-type-success';
			
		}
		if ( $( '.msg-type-error' ).length > 0 ){
			
			msgTypesCount++;
			msgType = 'msg-type-error';
			
		}
		if ( $( '.msg-type-warning' ).length > 0 ){
			
			msgTypesCount++;
			msgType = 'msg-type-warning';
			
		}
		if ( $( '.msg-type-info' ).length > 0 && msgTypesCount == 0 ){
			
			msgType = 'msg-type-info';
			
		}
		
		if ( msgTypesCount > 1 ){
			
			msgType = 'msg-type-mix';
			
		}
		
		createGrowl( $( this ).html(), null, null, msgType );
		$(this).remove();
		
	});
	
	
	$(".input-float-filter").on( 'keydown', function( event ) {
		
		// Allow: backspace, delete, tab, escape, and enter
		if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || 
		// Allow: Ctrl+A
		(event.keyCode == 65 && event.ctrlKey === true) || 
		// Allow: home, end, left, right
		(event.keyCode >= 35 && event.keyCode <= 39) ||
		// dot
		event.keyCode == 110) {
		// let it happen, don't do anything
			 return;
		}
		else {
		// Ensure that it is a number and stop the keypress
			if ( event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 ) ) {
				event.preventDefault(); 
			}
		}
		
	});
	
	$(".input-float-filter").on('keyup', function(event) {
		
		var jthis = $(this);
		var str = jthis.val();
		var pos = jthis.caret();
		
		str = str.replace(new RegExp(',', 'g'), '.');
		
		jthis.val(str);
		jthis[0].selectionStart = jthis[0].selectionEnd = pos;
		
	});
	
	$(".input-float-filter").on('blur', function(event) {
		
		var jthis = $(this);
		var str = jthis.val();
		
		checkInputFloats(jthis);
		
		str = str.replace(new RegExp(',', 'g'), '.');
		
		jthis.val(str);
	});
	
	$('#submit-apply').bind('click', function(event) {
		
		var jthis = $(this);
		
		if ( is_tinyMCE_active ){
			
			tinyMCE.triggerSave();
			
		}
		
		var form = null;
		
		if ( $(this).closest('form').length > 0 ) {
			
			form = $(this).closest('form');
			
		}
		else if ( jthis.attr( 'form' ) ) {
			
			form = $( '#' + jthis.attr( 'form' ) );
			
		}
		
		if ( form.hasClass('ajax') && ( ! form.attr('enctype') || ! form.attr('enctype') == 'multipart/form-data' ) ){
			
			var formData = form.serializeArray();
			formData.push({ name: this.name, value: this.value });
			
			createGrowl( 'Aguarde...', null, null, 'msg-type-info' );
			
			$.ajax( {
				type: "POST",
				url: form.attr( 'action' ) + '?ajax=submit_apply',
				data: formData,
				success: function( data ) {
					console.debug( data );
					
					var object = $('<div/>').html(data).contents();
					
					data = object.html();
					createGrowl( data, null, null, 'msg-type-success' );
				},
				error: function( request, status, error ){
					
					console.debug(request);
					//console.log( 'request.responseText', request.responseText );
					
					msg = '<div class="msg-item msg-type-error">';
					msg += '<div class="error">Error trying save form: <strong>' + request.status + ' ' + request.statusText + '</strong></div>';
					msg += '</div>';
					
					createGrowl( msg, null, null, 'msg-type-error' );
					
				}
			} );
			event.preventDefault();
			
		}
		
	});
	
	/*
	$( "textarea.js-editor" ).on('focus', function(e){
		
		var options = {
			
			width:			'100%',
			height:			500
			
		};
		
		var jseditor = $( this ).ckeditor();
	});
	*/
	
	
	//$('select.switch').switchify(/*{ on: '1', off: '0' }*/);
	
	
	
	/*************************************************/
	/*********** Live filter dos produtos ************/
	
	$( document ).on("keyup change", ".live-filter", function() {
		
		var value = $(this).val();
		
		var target_row = $(this).data('live-filter-for');
		
		$(target_row).each(function(index, r) {
			
			var $row = $(r);
			
			$row.data('founded', false);
			$row.find(".live-founded, .live-hidden, .live-visible").removeClass('live-founded live-hidden live-visible');
			$row.removeClass('live-founded live-hidden live-visible');
			
			$row.find(".filter-me").each(function(index, e) {
				
				var e = $(e);
				
				var text = '';
				
				var tagName;
				tagName = e.get(0).tagName;
				
				if ( tagName == 'TEXTAREA' || tagName == 'INPUT' ){
					var text = e.val();
				}
				else {
					var text = e.text();
				}
				
				if ( value != '' ){
					
					if ( text.toLowerCase().indexOf( value.toLowerCase() ) >= 0 ){
						
						$row.data('founded', true);
						
						e.closest('td').addClass('live-founded');
						
						e.closest('tr').removeClass('live-hidden');
						e.closest('tr').addClass('live-visible');
						
					}
					else if ( text.toLowerCase().indexOf( value.toLowerCase() ) === -1 && $row.data('founded') == false ){
						
						$row.removeClass('live-visible');
						$row.addClass('live-hidden');
						
					}
					
				}
				
			});
			
		});
		
	});
	
	/*********** Live filter dos produtos ************/
	/*************************************************/
	
	
	
	// previne a dupla submissão de formulários, quando submetidos via jquery
	// jQuery('form').preventDoubleSubmit();
	
	var iconWrapperSelector = '.vui-interactive-el-wrapper > .icon-wrapper';
	var iconWrapper = $( iconWrapperSelector );
	
	function simulateInteractiveElEvent( iconEl, event ) {
		
		var jthis = iconEl;
		var el = jthis.prev( '.el' );
		var elTagName = el.prop( "tagName" );
		
		if ( event === 'click' ) {
			
			if ( ( elTagName === 'INPUT' || elTagName === 'SELECT' ) && el.attr( 'type' ) != 'submit' && el.attr( 'type' ) != 'reset' && el.attr( 'type' ) != 'radio' && el.attr( 'type' ) != 'check' ) {
				
				el.trigger( 'focus' );
				
			}
			
		}
		else {
			
			el.trigger( event );
			
		}
		
	}
	
	$( document ).on( 'click', iconWrapperSelector, function () {
		
		simulateInteractiveElEvent( $( this ), 'click' );
		
	});
	$( document ).on( 'mousedown', iconWrapperSelector, function () {
		
		simulateInteractiveElEvent( $( this ), 'mousedown' );
		
	});
	$( document ).on( 'mouseup', iconWrapperSelector, function () {
		
		simulateInteractiveElEvent( $( this ), 'mouseup' );
		
	});
	
});
