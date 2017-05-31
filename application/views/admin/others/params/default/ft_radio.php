<?php  if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

?><div class="field-param-wrapper field-param-wrapper-<?= $type; ?> <?= $inline ? 'field-param-inline' : ''; ?> <?= $class; ?>">
	
	<?= form_error( $formatted_name, '<div class="msg-inline-error">', '</div>' ); ?>
	
	<?php
		
		if ( isset( $options ) AND is_array( $options ) ) {
			
			if ( count( $options ) > 1 ) {
				
				echo '<label title="' . $tip . '" class="' . $class . '" data-ext-tip="' . $ext_tip . '" for="param-' . $name . '">';
				
				echo $label;
				
				echo '</label>';
				
			}
			
			$_options = array();
			
			foreach( $options as $k => $v ) {
				
				$value = $k;
				$label = $v;
				
				$f_params = array(
					
					'name' => $formatted_name,
					'class' => $class,
					'value' => $value,
					'text' => $label,
					'title' => $tip,
					
				);
				
				if ( is_array( $params_values[ $name ] ) ){
					
					$checked = in_array( $value, $params_values[ $name ] ) ? TRUE : FALSE;
					
				}
				else {
					
					$checked = ( $params_values[ $name ] AND $params_values[ $name ] == $value ) ? TRUE : FALSE;
					
				}
				
				$f_params[ 'checked' ] = $checked;
				
				echo vui_el_radiobox( $f_params );
				
			}
			
		}
		else {
			
			$f_params = array(
				
				'name' => $formatted_name,
				'class' => $class,
				'value' => $value,
				'text' => $label,
				'title' => $tip,
				
			);
			
			if ( is_array( $params_values[ $name ] ) ){
				
				$checked = in_array( $value, $params_values[ $name ] ) ? TRUE : FALSE;
				
			}
			else {
				
				$checked = ( $params_values[ $name ] AND $params_values[ $name ] == $value ) ? TRUE : FALSE;
				
			}
			
			$f_params[ 'checked' ] = $checked;
			
			echo vui_el_radiobox( $f_params );
			
		}
		
	?>
	
</div><?php 
