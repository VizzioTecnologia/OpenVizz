<?php

	$out = array();

	foreach ( $submit_forms as $sf_key => $submit_form ) {
		
		if ( check_var( $submit_form[ 'users_submits' ] ) ){
			
			$columns = & $out[];
			
			// ----------------------
			// Columns
			
			$props = $submit_form[ 'fields' ];
			
			$columns = array(
				
				'submit_form' => lang( 'submit_form' ),
				'id' => lang( 'ud_data_id' ),
				'submit_datetime' => lang( 'ud_data_submit_datetime' ),
				'mod_datetime' => lang( 'ud_data_mod_datetime' ),
				
			);
			
			foreach ( $props as $prop ) {
				
				if ( ! in_array( $prop[ 'field_type' ], array( 'html', 'button' ) ) ) {
					
					$columns[ $prop[ 'alias' ] ] = lang( $prop[ 'presentation_label' ] );
					
				}
				
			}
			
			// Columns
			// ----------------------
			
			// ----------------------
			// Rows
			
			foreach ( $submit_form[ 'users_submits' ] as $key => $ud_data ) {
				
				$ud_data_out = & $out[];
				
				foreach ( $columns as $alias => $column ) {
					
					if ( $alias == 'submit_form' ) {
						
						$ud_data_out[] = $submit_form[ 'title' ];
						
					}
					else if ( isset( $ud_data[ 'parsed_data' ][ 'full' ][ $alias ] ) ) {
						
						$pd = $ud_data[ 'parsed_data' ][ 'full' ][ $alias ];
						
						if ( $alias == 'submit_datetime' OR $alias == 'mod_datetime' ) {
							
							$pd[ 'value' ] = strtotime( $pd[ 'value' ] );
							$pd[ 'value' ] = strftime( lang( 'ud_data_datetime' ), $pd[ 'value' ] );
							
						}
						
						$ud_data_out[] = htmlspecialchars_decode( str_replace( array( '“', "”" ), array( '"', '"', ), strip_tags( $pd[ 'value' ] ) ) );
						
					}
					else {
						
						$ud_data_out[] = '';
						
					}
					
				}
				
			}
			
			// Rows
			// ----------------------
			
			$out[] = array();
			
		}
		
	}
	
	$delimiter = isset( $submit_form[ 'params' ][ 'submit_form_export_csv_delimiter' ] ) ? $submit_form[ 'params' ][ 'submit_form_export_csv_delimiter' ] : NULL;
	$enclosure = isset( $submit_form[ 'params' ][ 'submit_form_export_csv_enclosure' ] ) ? $submit_form[ 'params' ][ 'submit_form_export_csv_enclosure' ] : NULL;
	
	$this->load->helper( 'csv' );

	if ( $download ){
		
		array_to_csv( array_values( $out ), $dl_filename, $delimiter, $enclosure, TRUE, TRUE );
		
	}
	else {
		
		echo array_to_csv( $out, NULL, $delimiter, $enclosure, TRUE );
		
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

















