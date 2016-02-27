<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
	
	$disqus = check_var( $this->mcm->filtered_system_params[ 'show_disqus_comments' ] );
	
	if( $current_component[ 'unique_name' ] === 'articles' ){
		
		if ( NULL !== $this->component_function ){
			
			if ( $this->component_function === 'index' ){
				
				if ( $this->component_function_action === 'articles_list' ){
					
					$disqus = check_var( $this->mcm->filtered_system_params[ 'show_disqus_comments_on_articles_list' ] );
					
				}
				else if ( $this->component_function_action === 'article_detail' ){
					
					$disqus = check_var( $this->mcm->filtered_system_params[ 'show_disqus_comments_on_article_detail' ] );
					
				}
				
			}
			
		}
		
	}
	
	if ( $disqus ){
	
?>
	
	<div id="disqus_thread"></div>
	<script type="text/javascript">
		/* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
		var disqus_shortname = '<?= $this->mcm->filtered_system_params[ 'disqus_shortname' ]; ?>'; // required: replace example with your forum shortname
		var disqus_config = function () { 
			this.language = "<?= str_replace( '-', '_', $this->mcm->filtered_system_params[ 'site_language' ] ); ?>";
		};
		/* * * DON'T EDIT BELOW THIS LINE * * */
		(function() {
			var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
			dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
			(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
		})();
	</script>
	<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
	<?= lang( 'comments_powered_by_disqus' ); ?>
	

<?php } ?>
