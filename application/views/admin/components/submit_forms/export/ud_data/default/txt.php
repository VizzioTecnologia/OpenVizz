<?php foreach ( $submit_forms as $key => $submit_form ) {
?>------------------------------------------------------------------<?= "\n"; ?>
------------------------------------------------------------------<?= "\n"; ?>
<?= sprintf( lang( 'submit_form_title_sprintf' ), $submit_form[ 'title' ] ); ?><?= "\n"; ?>
<?= sprintf( lang( 'submit_form_id_sprintf' ), $submit_form[ 'id' ] ); ?><?= "\n"; ?>
------------------------------------------------------------------<?= "\n"; ?>
	<?php foreach ( $submit_form[ 'users_submits' ] as $key => $user_submit ) { ?><?= "\n"; ?>
	---------------------------<?= "\n"; ?>
	<?= sprintf( lang( 'submit_form_user_submit_id_sprintf' ), $user_submit[ 'id' ] ); ?><?= "\n"; ?>
	<?= sprintf( lang( 'submit_form_user_submit_datetime_sprintf' ), $user_submit[ 'submit_datetime' ] ); ?><?= "\n"; ?>
	---------------------------
		<?php foreach ( $submit_form[ 'fields' ] as $key => $field ) {
			
			$value_name = $field[ 'alias' ];
			$formatted_field_name = 'form[' . $value_name . ']';
			$value_value = isset( $user_submit[ $value_name ] ) ? $user_submit[ $value_name ] : ( isset( $user_submit[ 'data' ][ $value_name ] ) ? $user_submit[ 'data' ][ $value_name ] : '' );
			
			if ( ! in_array( $field[ 'field_type' ], array( 'html', 'button' ) ) ) {
				
				echo "\n\t\t";
				echo isset( $field[ 'presentation_label' ] ) ? $field[ 'presentation_label' ] : $field[ 'label' ] . ': ';
				
				if ( $value_value ) {
					
					if ( $field[ 'field_type' ] == 'date' ){
						
						$format = '';
						
						$format .= $field[ 'sf_date_field_use_year' ] ? 'y' : '';
						$format .= $field[ 'sf_date_field_use_month' ] ? 'm' : '';
						$format .= $field[ 'sf_date_field_use_day' ] ? 'd' : '';
						
						$format = 'sf_us_dt_ft_pt_' . $format . '_' . $field[ 'sf_date_field_presentation_format' ];
						
						echo strftime( lang( $format ), strtotime( $value_value ) );
						
					} else {
						
						echo $value_value;
						
					}
					
				}
				
			}
			
		}
		
		echo "\n\t\t";
		
	}
} ?><?= "\n\n"; ?>