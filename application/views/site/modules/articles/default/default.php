<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	
	
?>

<div class="module-wrapper articles-module articles-module-wrapper <?= $parsed_params[ 'module_class' ]; ?>">
	
	<?php if ( check_var( $parsed_params[ 'show_title' ] ) ) { ?>
	
	<div class="module-title">
		
		<h3>
			
			<?= $module[ 'title' ]; ?>
			
		</h3>
		
	</div>
	
	<?php } ?>
	
	<div class="module-content articles-module-content layout-<?= url_title( $parsed_params[ 'layout' ] ); ?>"><?php
		
		if ( count( $grouped_articles ) ) {
			
			foreach( $grouped_articles as $category ){
				
				if ( check_var( $parsed_params[ 'show_category_titles' ] ) ) {
					
					?><div class="category-title-wrapper">
						
						<h4 class="category-title"><?= $category['title']; ?></h4>
						
					</div><?php
					
				}
				
				foreach( $category[ 'articles' ] as $article ){
					
					require( 'articles_list.php' );
					
				}
				
			}
			
		}
		else {
			
			foreach( $articles as $article ){
				
				require( 'articles_list.php' );
				
			}
			
		}
		
	?></div>
	
	<?php
	
	if ( check_var( $parsed_params[ 'use_articles_list_readmore_link' ] ) AND check_var( $parsed_params[ 'articles_list_readmore_link_url' ] ) ) { ?>
	<div class="module-read-more read-more category-read-more-link-wrapper">
		
		<div class="s1 inner">
			
			<div class="s2 inner">
				
				<a class="read-more-link category-read-more-link" href="<?= $parsed_params[ 'articles_list_readmore_link_url' ]; ?>" <?= element_title( check_var( $parsed_params[ 'articles_list_readmore_text' ] ) ? $parsed_params[ 'articles_list_readmore_text' ] : lang( 'readmore' ) ); ?> ><?= check_var( $parsed_params[ 'articles_list_readmore_text' ] ) ? $parsed_params[ 'articles_list_readmore_text' ] : lang( 'readmore' ); ?></a>
				
			</div>
			
		</div>
		
	</div>
	<?php } ?>
	
</div>