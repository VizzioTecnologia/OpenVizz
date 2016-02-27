<?php  if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
	
	if ( count( $options ) > 0 AND is_array( $options ) ){
		
		foreach( $options as $key => $option ) {
			
			$options[ html_escape( $key ) ] = lang( $option );
			
		};
		
	}
	
?><div class="field-param-wrapper field-param-wrapper-<?= $type; ?> <?= $inline ? 'field-param-inline' : ''; ?> <?= $class; ?>">
	
	<?= form_error( $formatted_name, '<div class="msg-inline-error">', '</div>' ); ?>
	
	<label title="<?= $tip; ?>" class="<?= $class; ?>" data-ext-tip="<?= $ext_tip; ?>" for="param-<?= $name; ?>">
		
		<?= $label; ?>
		
	</label>
	
	<?php
		
		// input params
		$ip = array();
		if ( ( int )$maxlength > 0 ) $ip[ 'maxlength' ] = $maxlength;
		
		echo vui_el_dropdown(
			
			array(
				
				'attr' => $ip,
				'id' => 'param-' . $name,
				'text' => $label,
				'class' => $class,
				'name' => $formatted_name,
				'value' => $element_value,
				'icon' => $icon,
				'options' => $options,
				
			)
			
		);
		
	?>
	
</div><?php 