<?php

	$out = array();

	foreach ( $submit_forms as $sf_key => $submit_form ) {

		$sf_out = array();

		$sf_out[ 'id' ] = $submit_form[ 'id' ];
		$sf_out[ 'alias' ] = $submit_form[ 'alias' ];
		$sf_out[ 'title' ] = $submit_form[ 'title' ];
		$sf_out[ 'users_submits_count_results' ] = count( $submit_form[ 'users_submits' ] );
		$sf_out[ 'output_columns' ] = array();
		$sf_out[ 'users_submits' ] = array();
		
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
		
		foreach ( $submit_form[ 'users_submits' ] as $us_key => $user_submit ) {
			
			$sf_out[ 'users_submits' ][ $user_submit[ 'id' ] ][ 'id' ] = $user_submit[ 'id' ];
			$sf_out[ 'users_submits' ][ $user_submit[ 'id' ] ][ 'submit_datetime' ] = $user_submit[ 'submit_datetime' ];
			
			foreach ( $user_submit[ 'data' ] as $f_key => $field ) {
				
				$sf_out[ 'users_submits' ][ $user_submit[ 'id' ] ][ $f_key ] = array(
					
					'field_key' => isset( $_tmp_array[ $f_key ][ 'key' ] ) ? $_tmp_array[ $f_key ][ 'key' ] : 'null',
					'field_alias' => $f_key,
					'field_title' => isset( $_tmp_array[ $f_key ][ 'presentation_label' ] ) ? $_tmp_array[ $f_key ][ 'presentation_label' ] : $_tmp_array[ $f_key ][ 'label' ],
					
				);
				
				$value = isset( $user_submit[ $f_key ] ) ? $user_submit[ $f_key ] : ( isset( $field[ $f_key ] ) ? $field[ $f_key ] : '' );
				
				if ( $value ) {
					
					if ( $_tmp_array[ $f_key ][ 'field_type' ] == 'date' ){
						
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
		
		$out[] = $sf_out;
		
	}echo json_encode( $out );
	
	//print_r( $out );

















