<?php
	
	header( 'Content-Type: application/json' );
	
	if ( $config[ 'download' ] ) {
		
		header( 'Content-Description: File Transfer' );
		header( 'Content-Type: application/octet-stream' );
		
		header( 'Content-type: text/csv; charset=UTF-8' );
		header( 'Content-Disposition: attachement; filename="' . $config[ 'filename' ] . '.json' . '"' );
		
		header( 'Content-Transfer-Encoding: binary' );
		header( 'Expires: 0' );
		header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header( 'Pragma: public' );
		
	}
	else {
		
		header( 'Content-type: text/plain; charset=UTF-8' );
		header( 'Content-Disposition: inline; filename="' . $config[ 'filename' ] . '.json' . '"' );
		
	}
	
	$out = '';
	
	if ( $config[ 'gm' ] == 'compact' ){
		
		foreach ( $submit_forms as $sf_key => $submit_form ) {
			
			if ( check_var( $submit_form[ 'error' ] ) ) {
				
				$out[] = $submit_form;
				continue;
				
			}
			
			$sf_out = array();
			
			$sf_out[ 'id' ] = $submit_form[ 'id' ];
			$sf_out[ 'alias' ] = $submit_form[ 'alias' ];
			$sf_out[ 'title' ] = $submit_form[ 'title' ];
			$sf_out[ 'output_columns' ] = array();
			
			$_tmp_array = array();
			
			foreach ( $submit_form[ 'fields' ] as $f_key => $field ) {
				
				if ( ! in_array( $field[ 'field_type' ], array( 'html', 'button' ) ) ) {
					
					$_tmp_array[ $field[ 'alias' ] ] = $field;
					
					$sf_out[ 'output_columns' ][ $field[ 'alias' ] ] = array(
						
						'key' => $field[ 'key' ],
						'alias' => $field[ 'alias' ],
						'title' => isset( $field[ 'presentation_label' ] ) ? $field[ 'presentation_label' ] : $field[ 'label' ],
						'type' => $field[ 'field_type' ],
						
					);
					
				}
				
			}
			
			if ( check_var( $submit_form[ 'users_submits' ] ) ) {
				
				$sf_out[ 'users_submits_count_results' ] = count( $submit_form[ 'users_submits' ] );
				$sf_out[ 'users_submits' ] = array();
				
				foreach ( $submit_form[ 'users_submits' ] as $us_key => $user_submit ) {
					
					$sf_out[ 'users_submits' ][ $user_submit[ 'id' ] ][ 'id' ] = $user_submit[ 'id' ];
					$sf_out[ 'users_submits' ][ $user_submit[ 'id' ] ][ 'submit_datetime' ] = $user_submit[ 'submit_datetime' ];
					
					foreach ( $user_submit[ 'data' ] as $f_key => $field ) {
						
						$sf_out[ 'users_submits' ][ $user_submit[ 'id' ] ][ $f_key ] = array(
							
							'field_key' => isset( $_tmp_array[ $f_key ][ 'key' ] ) ? $_tmp_array[ $f_key ][ 'key' ] : 'null',
							'field_alias' => $f_key,
							'field_title' => isset( $_tmp_array[ $f_key ][ 'presentation_label' ] ) ? $_tmp_array[ $f_key ][ 'presentation_label' ] : ( isset( $_tmp_array[ $f_key ][ 'label' ] ) ? $_tmp_array[ $f_key ][ 'label' ] : $f_key ),
							
						);
						
						$value = isset( $user_submit[ $f_key ] ) ? $user_submit[ $f_key ] : ( isset( $field[ $f_key ] ) ? $field[ $f_key ] : '' );
						
						if ( $value ) {
							
							if ( isset( $_tmp_array[ $f_key ][ 'field_type' ] ) AND $_tmp_array[ $f_key ][ 'field_type' ] == 'date' ){
								
								$format = '';
								
								$format .= $_tmp_array[ $f_key ][ 'sf_date_field_use_year' ] ? 'y' : '';
								$format .= $_tmp_array[ $f_key ][ 'sf_date_field_use_month' ] ? 'm' : '';
								$format .= $_tmp_array[ $f_key ][ 'sf_date_field_use_day' ] ? 'd' : '';
								
								$format = 'sf_us_dt_ft_pt_' . $format . '_' . $_tmp_array[ $f_key ][ 'sf_date_field_presentation_format' ];
								
								$sf_out[ 'users_submits' ][ $user_submit[ 'id' ] ][ $f_key ][ 'value' ] = strftime( lang( $format ), strtotime( $value ) );
								
							} else {
								
								$sf_out[ 'users_submits' ][ $user_submit[ 'id' ] ][ $f_key ][ 'value' ] =  $value;
								
							}
							
						}
						
					}
					
				}
				
			}
			
			$out[] = $sf_out;
			
		}
		
	}
	else {
		
		$out = $submit_forms;
		
	}
	
	$out = @json_encode ( $out );
	//$out = mb_convert_encoding( $out,'UTF-8','UTF-8' );
	//echo '<pre>' . print_r( $out, TRUE ) . '</pre>'; exit;
	echo $out;
	
?>


