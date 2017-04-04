<?php  if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

?><div class="field-param-wrapper field-param-wrapper-<?= $type; ?> <?= $inline ? 'field-param-inline' : ''; ?> <?= $class; ?>">
	
	<?= form_error( $formatted_name, '<div class="msg-inline-error">', '</div>' ); ?>
	
	<?php
		
		if ( isset( $options ) AND is_array( $options ) ) {
			
			$_options = array();
			
			foreach( $options as $k => $v ) {
				
				if ( is_array( $v ) ) {
					
					$f_params = array(
						
						'name' => $v[ 'name' ],
						'class' => $class,
						'value' => $v[ 'value' ],
						'text' => $v[ 'label' ],
						'title' => $tip,
						
					);
					
					if ( isset( $params_values[ $v[ 'name' ] ] ) AND is_array( $params_values[ $v[ 'name' ] ] ) ){
						
						$checked = in_array( $v[ 'value' ], $params_values[ $v[ 'name' ] ] ) ? TRUE : FALSE;
						
					}
					else {
						
						$checked = ( isset( $params_values[ $v[ 'name' ] ] ) AND $params_values[ $v[ 'name' ] ] AND $params_values[ $v[ 'name' ] ] == $v[ 'value' ] ) ? TRUE : FALSE;
						
					}
					
					$f_params[ 'checked' ] = $checked;
					
					echo vui_el_checkbox( $f_params );
					
				}
				else {
					
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
					
					echo vui_el_checkbox( $f_params );
					
				}
				
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
				
// 				echo '<pre>' . print_r( $params_values, TRUE ) . '</pre>';
				
				$checked = ( isset( $params_values[ $name ] ) AND $params_values[ $name ] != '' ) ? TRUE : FALSE;
				
			}
			
			$f_params[ 'checked' ] = $checked;
			
			echo vui_el_checkbox( $f_params );
			
		}
		
	?>
	
</div><?php 
