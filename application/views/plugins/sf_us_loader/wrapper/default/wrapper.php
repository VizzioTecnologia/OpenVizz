<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( check_var( $us_output_html ) ) {

?><div class="sf-us-loader-plugin-wrapper <?= $wrapper_params[ 'wc' ]; ?> ud-data-schema-list items-list total-items-list-<?= count( $us_output_html ); ?>">
	
	<?php
		
		foreach( $us_output_html as $key => $html ) {
			
			echo $html;
			
		}
		
		if ( check_var( $wrapper_params[ 'ud_show_global_readmore' ] ) AND check_var( $wrapper_params[ 'ud_global_readmore_link' ] ) ) {
			
			echo '<div class="item read-more readmore ud-ds-readmore-link-wrapper ud-d-list-ds-readmore-link-wrapper"><div class="s1"><div class="s2">';
			
			echo vui_el_button(array(
				
				'class' => 'read-more-link',
				'url' => $wrapper_params[ 'ud_global_readmore_link' ],
				'text' => check_var( $wrapper_params[ 'ud_global_readmore_label' ] ) ? lang( $wrapper_params[ 'ud_global_readmore_label' ] ) : lang( 'readmore' ),
				
			));
			
			echo '</div></div></div>';
			
		}
		
	?>
	
</div><?php

}

?>
