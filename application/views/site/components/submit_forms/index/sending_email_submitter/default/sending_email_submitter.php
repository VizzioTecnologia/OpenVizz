<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' ); ?>

<?php if ( check_var( $params[ 'sfsmr_message_prefix_custom' ] ) ) { ?>

	<?= $params[ 'sfsmr_message_prefix_custom' ]; ?>

<?php } ?>

<table>

<?php foreach ( $submit_form[ 'fields' ] as $key => $field ) { ?>

	<?php
		
		$field_name = $field[ 'alias' ];
		$formatted_field_name = 'form[' . $field_name . ']';
		$field_value = ( isset( $post[ 'form' ][ $field_name ] ) ) ? $post[ 'form' ][ $field_name ] : '';
		
		if ( $field[ 'field_type' ] == 'date' ){
			
			$format = '';
			
			$format .= $field[ 'sf_date_field_use_year' ] ? 'y' : '';
			$format .= $field[ 'sf_date_field_use_month' ] ? 'm' : '';
			$format .= $field[ 'sf_date_field_use_day' ] ? 'd' : '';
			
			$format = 'sf_us_dt_ft_pt_' . $format . '_' . $field[ 'sf_date_field_presentation_format' ];
			
			$field_value =  strftime( lang( $format ), strtotime( $field_value ) );
			
		}
		else if ( in_array( $field[ 'field_type' ], array( 'checkbox', 'combo_box', ) ) ){
			
			if ( get_json_array( $field_value ) ) {
				
				$field_value = json_decode( $field_value, TRUE );
				
			}
			
			$_field_value = array();
			
			if ( is_array( $field_value ) ) {
				
				foreach ( $field_value as $k => $value ) {
					
					if ( is_string( $value ) ) {
						
						if ( check_var( $field[ 'options_from_users_submits' ] ) AND ( check_var( $field[ 'options_title_field' ] ) OR check_var( $field[ 'options_title_field_custom' ] ) ) AND is_numeric( $value ) AND $_user_submit = $this->sfcm->get_user_submit( $value ) ) {
							
							$value = isset( $_user_submit[ 'data' ][ $field[ 'options_title_field' ] ] ) ? $_user_submit[ 'data' ][ $field[ 'options_title_field' ] ] : $_user_submit[ 'id' ];
							
							$_field_value[] = $value;
							
						}
						else {
							
							$_field_value[] = $value;
							
						}
						
					}
					
				}
				
				$field_value = join( ', ', $_field_value );
				
			}
			else {
				
				if ( check_var( $field[ 'options_from_users_submits' ] ) AND ( check_var( $field[ 'options_title_field' ] ) OR check_var( $field[ 'options_title_field_custom' ] ) ) AND is_numeric( $field_value ) AND $_user_submit = $this->sfcm->get_user_submit( $field_value ) ) {
					
					$field_value = isset( $_user_submit[ 'data' ][ $field[ 'options_title_field' ] ] ) ? $_user_submit[ 'data' ][ $field[ 'options_title_field' ] ] : $_user_submit[ 'id' ];
					
				}
				
			}
			
		}
		
	?>

	<?php if ( ! in_array( $field[ 'field_type' ], array( 'button', 'html' ) ) ) { ?>

		<?php if ( trim( $field_value ) !== '' OR trim( $field_value ) === '' AND check_var( $submit_form[ 'params' ][ 'submit_form_sending_email_show_empty_fields' ] ) ) { ?>

			<tr>

				<td>

				<?= $field[ 'label' ]; ?>

				</td>

				<td>

				<?= $field_value; ?>

				</td>

			</tr>

		<?php } ?>

	<?php } ?>

<?php } ?>

</table>

<?php if ( check_var( $params[ 'sfsmr_message_suffix_custom' ] ) ) { ?>

	<?= $params[ 'sfsmr_message_suffix_custom' ]; ?>

<?php } ?>

<hr />
