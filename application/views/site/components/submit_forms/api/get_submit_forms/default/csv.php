<?php
	
	$out = array();
	
	foreach ( $submit_forms as $sf_key => $submit_form ) {
		
		if ( check_var( $submit_form[ 'error' ] ) ){
			
			$us_out = & $out[];
			
			$us_out[] = lang( 'error' ) . ': ' . lang( $submit_form[ 'error' ] );
			$us_out[] = lang( 'submit_form_id' ) . ': ' . $submit_form[ 'id' ];
			
		}
		else if ( check_var( $submit_form[ 'users_submits' ] ) AND count( $submit_form[ 'users_submits' ] ) > 0 ){
			
			$sf_out = & $out[];
			
			$sf_out[] = lang( 'submit_form_id' );
			$sf_out[] = lang( 'submit_form_title' );
			$sf_out[] = lang( 'user_submit_id' );
			$sf_out[] = lang( 'submit_datetime' );
			
			foreach ( $submit_form[ 'fields' ] as $f_key => $field ) {
				
				if ( ! in_array( $field[ 'field_type' ], array( 'html', 'button' ) ) ) {
					
					$sf_out[] = isset( $field[ 'presentation_label' ] ) ? $field[ 'presentation_label' ] : $field[ 'label' ];
					
				}
				
			}
			
			foreach ( $submit_form[ 'users_submits' ] as $us_key => $user_submit ) {
				
				$us_out = & $out[];
				
				$us_out[] = $user_submit[ 'submit_form_id' ];
				$us_out[] = $user_submit[ 'submit_form_title' ];
				$us_out[] = $user_submit[ 'id' ];
				$us_out[] = $user_submit[ 'submit_datetime' ];
				
				foreach ( $submit_form[ 'fields' ] as $f_key => $field ) {
					
					if ( ! in_array( $field[ 'field_type' ], array( 'html', 'button' ) ) ) {
						
						$value_name = $field[ 'alias' ];
						$formatted_field_name = 'form[' . $value_name . ']';
						$value_value = isset( $user_submit[ $value_name ] ) ? $user_submit[ $value_name ] : ( isset( $user_submit[ 'data' ][ $value_name ] ) ? $user_submit[ 'data' ][ $value_name ] : '' );
						
						if ( $field[ 'field_type' ] == 'date' ){
							
							$format = '';
							
							$format .= $field[ 'sf_date_field_use_year' ] ? 'y' : '';
							$format .= $field[ 'sf_date_field_use_month' ] ? 'm' : '';
							$format .= $field[ 'sf_date_field_use_day' ] ? 'd' : '';
							
							$format = 'sf_us_dt_ft_pt_' . $format . '_' . $field[ 'sf_date_field_presentation_format' ];
							
							$us_out[] = strftime( lang( $format ), strtotime( $value_value ) );
							
						} else {
							
							$us_out[] = $value_value;
							
						}
						
					}
					
				}
				
			}
			
			//$out[] = array();
			
		}
		
	}
	
	$this->load->helper( 'csv' );
	
	if ( $config[ 'download' ] ){
		
		array_to_csv( array_values( $out ), $config[ 'filename' ] . '.csv', $config[ 'csvd' ], $config[ 'csve' ], $config[ 'csvfas' ], $config[ 'download' ] );
		
	}
	else {
		
		array_to_csv( $out, $config[ 'filename' ] . '.csv', $config[ 'csvd' ], $config[ 'csve' ], $config[ 'csvfas' ], $config[ 'download' ] );
		
	}

	/*
	foreach ( $out as $l_key => $line ) {

		if ( gettype( $line ) === 'array' ){

			echo join( ';', $line ) . "\n";

		}
		else {

			echo $line;

		}

	}
	*/
	//print_r( $out );

















