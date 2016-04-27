<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script type="text/javascript">
	
	if ( typeof window.onCategoryChooseFunction === 'undefined' ) {
		
		// quando se clica em uma categoria, essa função é procurada
		window.onCategoryChooseFunction = function(){
			
			updateArticlesList();
			
		}
		
	}
	
	if ( typeof window.selectedCategory === 'undefined' ) {
		
		window.selectedCategory = {
			
			'id': -1
			
		};
		
	}
	
	updateArticlesList = function(){
		
		$.ajax({
			type: "GET",
			data: {
				q: window.articlesLiveSearchTerms,
				c: window.selectedCategory.id,
				ajax: true
			},
			url: '<?= get_url( $this->articles->get_ajax_url( 'articles_search' ) ); ?>',
			success: function(data){
				
				$( '#modal-articles-list' ).html( data );
				
			},
			error: function(xhr, textStatus, errorThrown){
				
				console.log('content.text', status + ': ' + error);
				
				for(i in xhr){
					if(i!="channel")
					console.log(i + '>> ' + xhr[i]);
				};
				
			}
		});
		
	}
	
	updateModalArticlesPickerContentTop = function(){
		
		$( '.modal-content' ).each( function( index ) {
			
			var jthis = $( this );
			
			jthis.css( 'top', jthis.parent().find( '.modal-controls' ).outerHeight() );
			
		});
		
	}
	
	applyModalArticlesPicker = function(){
		
		$( ".modal-articles-picker" ).fancybox({
			
			fitToView	: true,
			autoSize	: false,
			closeClick	: false,
			openEffect	: 'elastic',
			closeEffect	: 'none',
			openMethod: 'zoomIn',
			openEasing: 'swing',
			type: 'ajax',
			href: '<?= get_url( $this->articles->get_ajax_url( 'articles_search' ) ); ?>',
			wrapCSS: 'vui-modal modal-articles-picker',
			helpers:  {
				
				overlay : {
					
					showEarly  : false,
					
				},
				title: null
				
			},
			
			afterLoad: function(){
				
				/**************************************************************/
				/************ montando a estrutura do diálogo modal ***********/
				
				this.inner.append( '<div class="modal-controls controls"><div class="modal-controls-inner controls-inner"></div></div>' );
				this.content = '<div class="modal-content"><div class="modal-articles-categories" id="modal-articles-categories"></div><div class="modal-articles-list" id="modal-articles-list">' + this.content + '</div></div>';
				
				var modalControls = $( '.modal-controls-inner' );
				
				/************ montando a estrutura do diálogo modal ***********/
				/**************************************************************/
				
				
				
				/**************************************************************/
				/****************** carregando as categorias ******************/
				
				$.ajax({
					
					type: "GET",
					data: {
						ajax: true,
						ct: true
					},
					url: '<?= get_url( $this->articles->get_ajax_url( 'categories_search' ) ); ?>',
					success: function(data){
						
						$( '#modal-articles-categories' ).html( data );
						
						$( '#modal-articles-categories .plugin-name' ).after( '<a href="#" class="selected search-result" data-resultid="-1" ><span class="title"><?= lang( 'all_articles' ); ?></span></a>' );
						$( '#modal-articles-categories .plugin-name' ).after( '<a href="#" class="search-result" data-resultid="0" ><span class="title"><?= lang( 'uncategorized' ); ?></span></a>' );
						
						updateModalArticlesPickerContentTop(); // layout adjustment
						
						// on category choose
						$( document ).on( 'click', '#modal-articles-categories .search-result', function( e ){
							
							$( '.selected' ).removeClass( 'selected' );
							$( this ).addClass( 'selected' );
							
							e.preventDefault();
							
							window.selectedCategory = {
								
								id: $( this ).data( 'resultid' ),
								title: $( this ).find( '.title' ).text()
								
							};
							
							if ( typeof window.onCategoryChooseFunction == 'function' ) {
								
								window.onCategoryChooseFunction();
								
							}
							
						});
						
						// on article choose
						$( document ).on( 'click', '#modal-articles-list .search-result', function( e ){
							
							$( '.selected' ).removeClass( 'selected' );
							$( this ).addClass( 'selected' );
							
							e.preventDefault();
							
							window.selectedArticle = {
								
								id: $( this ).data( 'resultid' ),
								title: $( this ).find( '.title' ).text()
								
							};
							
							if ( typeof window.onArticleChooseFunction == 'function' ) {
								
								window.onArticleChooseFunction();
								
							}
							
						});
						
					},
					error: function(xhr, textStatus, errorThrown){
						
						console.log('content.text', status + ': ' + error);
						
						for(i in xhr){
							if(i!="channel")
							console.log(i + '>> ' + xhr[i]);
						};
						
					}
					
				});
				
				/****************** carregando as categorias ******************/
				/**************************************************************/
				
				modalControls.append( '<input placeholder="<?= lang( 'search_articles' ); ?>" type="text" name="modal_article_id_live_search_terms" value="" id="modal_article_id_live_search_terms" class="live-search">' );
				//this.content = '<h1>2. My custom title</h1>' + this.content;
				
				var termsControl = $( '#modal_article_id_live_search_terms' );
				
				var delay = (function(){
					var timer = 0;
					return function(callback, ms){
						clearTimeout (timer);
						timer = setTimeout(callback, ms);
					};
				})();
				
				$( '#modal_article_id_live_search_terms' ).keyup(function() {
					
					var termsControl = $( this );
					window.articlesLiveSearchTerms = termsControl.val();
					
					delay(function(){
						
						updateArticlesList();
						
					}, 1 );
					
				});
				
			}
			
		});
		
	}
	
	$( document ).bind( 'ready', function(){
		
		applyModalArticlesPicker();
		
	});
	
</script>
