<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' ); ?>

<div id="view-user-submit" class="view-user-submit">

	<div class="view-user-submit-inner info-items">

		<table>
			
			<?php
				
				$_tmp_array = array();
				
				foreach ( $submit_form[ 'fields' ] as $f_key => $field ) {
					
					if ( ! in_array( $field[ 'field_type' ], array( 'html', 'button' ) ) ) {
						
						$_tmp_array[ $field[ 'alias' ] ] = $field;
						
					}
					
				}
				
			?>
			
			<?php foreach ( $user_submit[ 'data' ] as $us_key => $field ) { ?>
				
				<?php if ( isset( $_tmp_array[ $us_key ] ) AND ! in_array( $_tmp_array[ $us_key ][ 'field_type' ], array( 'html', 'button' ) ) ) { ?>
					
					<tr class="item-inner user-submit-info-item-inner">
						
						<td class="title user-submit-info-item-title info-item-title">
							
							<span class="filter-me">
								
								<?= lang( isset( $_tmp_array[ $us_key ][ 'presentation_label' ] ) ? $_tmp_array[ $us_key ][ 'presentation_label' ] : ( isset( $_tmp_array[ $us_key ][ 'label' ] ) ? $_tmp_array[ $us_key ][ 'label' ] : '<span class="error" title="' . lang( 'submit_forms_error_no_field_on_submit_form' ) . '">[' . $us_key . ']</span>' ) ); ?>
								
							</span>
							
						</td>
						
						<td class="value user-submit-info-item-value info-item-content">
							
							<span class="filter-me">
								
								<?php
								
								if ( isset( $user_submit[ $us_key ] ) ) {
									
									if ( $_tmp_array[ $us_key ][ 'field_type' ] == 'date' ){
										
										if ( $user_submit[ $us_key ] !== '0000-00-00' ) {
											
											$format = '';
											
											$format .= $_tmp_array[ $us_key ][ 'sf_date_field_use_year' ] ? 'y' : '';
											$format .= $_tmp_array[ $us_key ][ 'sf_date_field_use_month' ] ? 'm' : '';
											$format .= $_tmp_array[ $us_key ][ 'sf_date_field_use_day' ] ? 'd' : '';
											
											$format = 'sf_us_dt_ft_pt_' . $format . '_' . $_tmp_array[ $us_key ][ 'sf_date_field_presentation_format' ];
											
											echo ov_strftime( lang( $format ), strtotime( $user_submit[ $us_key ] ) );
											
										}
										
									} else {
										
										echo $user_submit[ $us_key ];
										
									}
									
								} ?>
								
							</span>
							
						</td>
						
					</tr>
					
				<?php } ?>
				
			<?php } ?>
			
		</table>

	</div>

</div>
