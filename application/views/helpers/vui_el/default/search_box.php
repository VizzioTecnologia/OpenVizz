


<div class="vui-search-box <?= $wrapper_class; ?>"><?php
	
	echo form_open( ( check_var( $get_url ) ? get_url( $url ) : $url ) );
	
	echo vui_el_input_text(
		
		array(
			
			'text' => $text,
			'name' => $name,
			'value' => isset( $terms ) ? $terms : '',
			'class' => 'search-terms' . $class,
			'icon' => 'search',
			'attr' => ( array ) $attr + array(
				
				'data-live-search-url' => $live_search_url,
				'data-live-search-min-length' => $live_search_min_length,
				
			),
			
		)
		
	);
	
	echo vui_el_button(
		
		array( 
			
			'text' => lang( 'search' ),
			'icon' => 'search',
			'only_icon' => TRUE,
			'button_type' => 'button',
			'type' => 'submit',
			'name' => 'submit_search',
			'class' => 'submit-search',
			
		)
		
	);
	
	if ( check_var( $terms ) ){
	
		echo vui_el_button(
			
			array(
				
				'url' => $cancel_url,
				'text' => lang( 'cancel_search' ),
				'icon' => 'cancel',
				'only_icon' => TRUE,
				'type' => 'submit',
				'class' => 'submit-cancel-search',
				
			)
			
		);
		
	}
	
	echo form_close();
	
 ?></div>


<script type="text/javascript">
	
	<?php if ( $this->plugins->load( 'viacms_live_search' ) ) { ?>
	
	$( document ).bind( 'ready', function(){
		
	});
	
	<?php } ?>
	
</script>
