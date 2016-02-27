<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>

<div class="sf-us-loader-plugin-wrapper <?= $wrapper_params[ 'wc' ]; ?> ud-data-schema-list items-list total-items-list-<?= count( $us_output_html ); ?>">
	
	<?php
		
		foreach( $us_output_html as $key => $html ) {
			
			echo $html;
			
		}
		
	?>
	
</div>
