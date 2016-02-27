<?php  if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' ); ?>

<div class="params-set-wrapper">
	
	<div class="params-set">
		
		<?php if ( $header ) { ?>
			
			<h4 class="params-set-title">
				
				<?= vui_el_button( array( 'text' => lang( $header ), 'icon' => url_title( $header ),  ) ); ?>
				
			</h4>
			
		<?php } ?>
		
		<?php if ( isset( $elements ) AND $elements ) {
			
			foreach ( $elements as $element ) {
				
				echo $element;
				
			}
			
			foreach ( $hidden_elements as $element ) {
				
				echo $element;
				
			}
			
		} ?>
		
	</div>
	
</div>
