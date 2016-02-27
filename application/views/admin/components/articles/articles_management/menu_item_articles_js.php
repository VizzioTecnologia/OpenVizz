<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php if ( $this->plugins->load( 'modal_articles_picker' ) ) { ?>

<script type="text/javascript">
	
	$( document ).bind( 'ready', function(){
		
		var selectedArticleFieldId = $( '#param-article_id' ).find( ':selected' ).attr( 'id' );
		var selectedArticleId = $( '#param-article_id' ).find( ':selected' ).val();
		var selectedArticleText = $( '#param-article_id' ).find( ':selected' ).text();
		
		$( '#param-article_id' ).addClass( 'hidden' );
		
		$( '#param-article_id' ).after( '<input readonly type="text" name="article_id_live_search" value="' + selectedArticleText + '" id="article_id_live_search" class="live-search">' );
		
		$( '#article_id_live_search' ).after( '<?= vui_el_button( array( 'url' => '#', 'text' => lang( 'select_article' ), 'id' => 'article-picker', 'icon' => 'more', 'only_icon' => TRUE, 'class' => 'modal-articles-picker', ) ); ?>' );
		
		$( '#article_id_live_search' ).bind( 'blur', function(){
			
			var selectedArticleTitle = $( '#param-article_id' ).find( ':selected' ).text();
			$( '#article_id_live_search' ).val( selectedArticleTitle );
			
		});
		
		
		// quando se clica em um artigo, essa função é procurada
		window.onArticleChooseFunction = function(){
			
			var articleId = window.selectedArticle.id;
			var articleTitle = window.selectedArticle.title;
			
			$( '#article_id_live_search' ).val( articleTitle.trim() );
			$( '#param-article_id' ).val( articleId );
			
			$.fancybox.close();
			
		}
		
	});
	
</script>

<?php } ?>