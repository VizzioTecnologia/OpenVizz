<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
	
	$lang = $this->mcm->filtered_system_params[ $this->mcm->environment . '_language' ];
	
?>

<script type="text/javascript">
	
	$( function() {
		
		console.log( '<?= lang( '[Plugins] jQuery Lazy plugin initialized' ); ?>' );
		
		console.log( $( '.lazy' ).length );
		
		var loadedElements = 0;
		
		$( 'img.lazy' ).each( function( index ) {
			
		});
		
		$( '.lazy' ).lazy({
			
			effect: "fadeIn",
			effectTime: 2000,
			threshold: 0,
			beforeLoad: function(element){
				console.log('image "' + stripTime(element.data('src')) + '" is about to be loaded');
			},
			afterLoad: function(element) {
				loadedElements++;
				console.log('image "' + stripTime(element.data('src')) + '" was loaded successfully');
			},
			onError: function(element) {
				loadedElements++;
				console.log('image "' + stripTime(element.data('src')) + '" could not be loaded');
			},
			onFinishedAll: function() {
				console.log('finished loading ' + loadedElements + ' elements');
				console.log('lazy instance is about to be destroyed')
			}
			
		});
		
	});
	
</script>
