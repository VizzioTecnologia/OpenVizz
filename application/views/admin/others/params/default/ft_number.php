<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

?><div class="field-param-wrapper field-param-wrapper-<?= $type; ?> <?= $inline ? 'field-param-inline' : ''; ?> <?= $class; ?>">
	
	<?= form_error( $formatted_name, '<div class="msg-inline-error">', '</div>' ); ?>
	
	<label title="<?= $tip; ?>" class="<?= $class; ?>" data-ext-tip="<?= $ext_tip; ?>" for="param-<?= $name; ?>">
		
		<?= $label; ?>
		
	</label>
	
	<?php
		
		// input params
		$ip = array();
		if ( ( int )$maxlength > 0 ) $ip[ 'maxlength' ] = $maxlength;
		
		echo vui_el_input_number(
			
			array(
				
				'attr' => $ip,
				'min' => $min,
				'max' => $max,
				'pattern' => $pattern,
				'step' => $step,
				'id' => 'param-' . $name,
				'title' => rawurldecode( $ext_tip ),
				'icon' => $icon,
				'class' => $class,
				'name' => $formatted_name,
				'value' => $params_values[ $name ],
				'icon' => $icon,
				'options' => $options,
				
				
			)
			
		);
		
	?>
	
</div><?php 