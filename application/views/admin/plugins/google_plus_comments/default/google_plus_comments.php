<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
	
	$google_plus_comments = check_var( $this->mcm->filtered_system_params[ 'show_google_plus_comments' ] );
	
	if( $current_component[ 'unique_name' ] === 'articles' ){
		
		if ( NULL !== $this->component_function ){
			
			if ( $this->component_function === 'index' ){
				
				if ( $this->component_function_action === 'articles_list' ){
					
					$google_plus_comments = check_var( $this->mcm->filtered_system_params[ 'show_google_plus_comments_on_articles_list' ] );
					
				}
				else if ( $this->component_function_action === 'article_detail' ){
					
					$google_plus_comments = check_var( $this->mcm->filtered_system_params[ 'show_google_plus_comments_on_article_detail' ] );
					
				}
				
			}
			
		}
		
	}
	
	if ( $google_plus_comments ){
	
?>


<script src="https://apis.google.com/js/plusone.js"></script>
<div id="gplus-comments"></div>
<script>
gapi.comments.render('gplus-comments', {
	href: window.location,
	width: '624',
	first_party_property: 'BLOGGER',
	view_type: 'FILTERED_POSTMOD'
});
</script>

<?php } ?>
