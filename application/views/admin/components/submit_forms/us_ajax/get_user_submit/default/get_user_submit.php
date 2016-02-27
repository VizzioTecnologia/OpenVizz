
<?php

$unique_hash_id = md5( rand( 100, 1000 ) ) . uniqid();

?>

<div id="modal-controls-<?= $unique_hash_id; ?>" class="modal-controls controls">
	
	<div class="modal-controls-inner controls-inner">
		
		<?= vui_el_input_text(
			
			array(
				
				'text' => lang( 'live_filter' ),
				'icon' => 'filter',
				'name' => 'filter',
				'id' => 'filter',
				'class' => 'live-filter',
				'attr' => array(
					
					'data-live-filter-for' => '#modal-content-' . $unique_hash_id . ' .view-user-submit-inner tr',
					
				),
				
			)
			
		); ?>
		
		<ul class="controls-menu menu horizontal">

			<li class="parent">

				<?= vui_el_button( array( 'text' => lang( 'download' ), 'icon' => 'download', 'only_icon' => FALSE, ) ); ?>

				<ul>

					<li>

						<?= vui_el_button( array( 'url' => $c_urls[ 'us_export_download_json_link' ], 'text' => lang( 'download_json' ), 'icon' => 'json', 'only_icon' => FALSE, ) ); ?>

					</li>

					<li>

						<?= vui_el_button( array( 'url' => $c_urls[ 'us_export_download_csv_link' ], 'text' => lang( 'download_csv' ), 'icon' => 'csv', 'only_icon' => FALSE, ) ); ?>

					</li>

					<li>

						<?= vui_el_button( array( 'url' => $c_urls[ 'us_export_download_xls_link' ], 'text' => lang( 'download_xls' ), 'icon' => 'xls', 'only_icon' => FALSE, ) ); ?>

					</li>

					<li>

						<?= vui_el_button( array( 'url' => $c_urls[ 'us_export_download_txt_link' ], 'text' => lang( 'download_txt' ), 'icon' => 'txt', 'only_icon' => FALSE, ) ); ?>

					</li>

					<li>

						<?= vui_el_button( array( 'url' => $c_urls[ 'us_export_download_html_link' ], 'text' => lang( 'download_html' ), 'icon' => 'html', 'only_icon' => FALSE, ) ); ?>

					</li>

					<li>

						<?= vui_el_button( array( 'url' => $c_urls[ 'us_export_download_pdf_link' ], 'text' => lang( 'download_pdf' ), 'icon' => 'pdf', 'only_icon' => FALSE, ) ); ?>

					</li>

				</ul>

			</li>

		</ul>

	</div>

</div>

<div id="modal-content-<?= $unique_hash_id; ?>" class="modal-content modal-user-submit">
	
	<div class="modal-content-inner view-user-submit-inner info-items">
		
		<table class="datalist-table">
			
			<?php
				
				$_tmp_array = array();
				
				foreach ( $submit_form[ 'fields' ] as $f_key => $field ) {
					
					if ( ! in_array( $field[ 'field_type' ], array( 'html', 'button' ) ) ) {
						
						$_tmp_array[ $field[ 'alias' ] ] = $field;
						
					}
					
				}
				
			?>
			
			<?php foreach ( $user_submit[ 'data' ] as $data_key => $field_value ) { ?>
				
				<?php if ( isset( $_tmp_array[ $data_key ] ) AND ! in_array( $_tmp_array[ $data_key ][ 'field_type' ], array( 'html', 'button' ) ) ) { ?>
					
					<tr class="item-inner user-submit-info-item-inner<?= $field_value == '' ? ' field-empty' : ''; ?>">
						
						<td class="title user-submit-info-item-title info-item-title">
							
							<span class="filter-me">
								
								<?= lang( isset( $_tmp_array[ $data_key ][ 'presentation_label' ] ) ? $_tmp_array[ $data_key ][ 'presentation_label' ] : ( isset( $_tmp_array[ $data_key ][ 'label' ] ) ? $_tmp_array[ $data_key ][ 'label' ] : '<span class="error" title="' . lang( 'submit_forms_error_no_field_on_submit_form' ) . '">[' . $data_key . ']</span>' ) ); ?>
								
							</span>
							
						</td>
						
						<td class="value user-submit-info-item-value info-item-content">
							
							<span class="filter-me">
								
								<?php
									
									if ( isset( $user_submit[ $data_key ] ) ) {
										
										if ( $_tmp_array[ $data_key ][ 'field_type' ] == 'date' ){
											
											$format = '';
											
											$format .= $_tmp_array[ $data_key ][ 'sf_date_field_use_year' ] ? 'y' : '';
											$format .= $_tmp_array[ $data_key ][ 'sf_date_field_use_month' ] ? 'm' : '';
											$format .= $_tmp_array[ $data_key ][ 'sf_date_field_use_day' ] ? 'd' : '';
											
											$format = 'sf_us_dt_ft_pt_' . $format . '_' . $_tmp_array[ $data_key ][ 'sf_date_field_presentation_format' ];
											
											echo strftime( lang( $format ), strtotime( $user_submit[ $data_key ] ) );
											
										}
										else if ( $_tmp_array[ $data_key ][ 'field_type' ] == 'checkbox' ){
											
											$user_submit[ $data_key ] = json_decode( $user_submit[ $data_key ], TRUE );
											
											$_field_value = array();
											
											foreach ( $user_submit[ $data_key ] as $k => $value ) {
												
												if ( is_string( $value ) ) {
													
													$_field_value[] = $value;
													
												}
												
											}
											
											echo join( ', ', $_field_value );
											
										}
										else {
											
											echo $user_submit[ $data_key ];
											
										}
										
									}
									
								?>
								
							</span>
							
						</td>
						
					</tr>
					
				<?php } ?>
				
			<?php } ?>
			
		</table>
		
	</div>
	
</div>
