
<div class="articles-module-wrapper layout-<?= url_title( $params[ 'layout' ] ); ?>">
	
	<?php foreach( $articles as $category ){ ?>
	
	<?php if ( check_var( $module_data[ 'params' ][ 'articles_list_order' ] ) ) { ?>
	
	<div class="category-title-wrapper">
		
		<h4 class="category-title"><?= $category['title']; ?></h4>
		
	</div>
	
	<?php } ?>
	
	<?php foreach( $category[ 'articles' ] as $article ){ 
	
	
	$readmore = check_var( $article['params']['show_readmore_link'] );
	
	$content = '';
	
	if ( check_var( $article['params']['show_introtext_on_list_view'] ) AND check_var( $article['introtext'] ) ) {
		
		$content = $article[ 'introtext' ];
		$readmore = TRUE;
		
	}
	else {
		
		$content = $article[ 'fulltext' ];
		$readmore = FALSE;
		
	}
	
	
	if ( check_var( $article['params']['word_limiter'] ) AND word_limiter( strip_tags( $content ), $article['params']['word_limiter'] ) !== strip_tags( $content ) ) {
		
		$content = '<p>' . word_limiter( strip_tags( $content ), $article['params']['word_limiter'] ) . '</p>';
		$readmore = TRUE;
		
	}
	else{
		
		$readmore = FALSE;
		
	}
	
	
	?>
	
	
	<div class="article-wrapper">
		
		<?php if ( $article['params']['show_title_on_list_view'] ) { ?>
		
		<div class="article-title-wrapper">
			
			<h5 class="article-title">
				
				<span class="article-title-content">
					
					<?php if ( check_var( $params['show_articles_images'] ) && check_var( $article[ 'image' ] ) ) { ?>
					
					<?php
						
						$thumb_params = array(
							
							'src' => 'thumbs/' . $article[ 'image' ],
							'href' => get_url( $article[ 'image' ] ),
							'target' => '_blank',
							'modal' => TRUE,
							
						);
						
						echo vui_el_thumb( $thumb_params );
						
					?>
					
					<?php } ?>
					
					<?php if ( $article['params']['show_title_as_link_on_list_view'] ) { ?>
						
					<a href="<?= $article[ 'url' ]; ?>"><?= $article[ 'title' ]; ?></a>
					
					<?php } else { ?>
						
					<?= $article[ 'title' ]; ?>
					
					<?php } ?>
					
				</span>
				
			</h5>
			
		</div>
		
		<?php } ?>
		
		<?php if ( $content ) { ?>
		<div class="article-content-wrapper">
			<div class="article-content-intro-text-wrapper">
				<div class="article-content article-content-intro-text">
					
					<?= $content; ?>
					
				</div>
			</div>
		</div>
		<?php } ?>
		
		<?php if ( $readmore ) { ?>
		<div class="article-read-more-link-wrapper">
			<a class="article-read-more-link" href="<?= $article['url']; ?>"><?= lang($article['params']['readmore_text']); ?></a>
		</div>
		<?php } ?>
		
	</div>
	
	<?php } ?>
	
	<?php } ?>

</div>