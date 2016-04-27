<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
	
	$unique_hash_id = md5( rand( 100, 1000 ) ) . uniqid();
	
	$ud_data = $this->sfcm->parse_ud_d_data( $user_submit );
	
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
			
			$existing_fields = FALSE;
			
			$fields = $submit_form[ 'fields' ];
			
			foreach ( $fields as $field_key => $field ) {
				
				if ( ! in_array( $field[ 'field_type' ], array( 'html', 'button' ) ) ) {
					
					$existing_fields[ $field[ 'alias' ] ] = $field;
					
				}
				
			}
			
			echo '<tr class="item-inner ud-data-info-item-inner ud-data-created-by-user">';
			
			echo '<td class="value info-item-content" colspan="2">';
			
			echo '<span class="filter-me">';
			
			if ( isset( $ud_data[ 'created_by_user' ] ) AND isset( $ud_data[ 'modified_by_user' ] ) AND $ud_data[ 'modified_by_user' ][ 'id' ] == $ud_data[ 'created_by_user' ][ 'id' ] ) {
				
				echo lang( 'ud_data_created_and_modified_by_user', NULL, anchor( $ud_data[ 'created_by_user' ][ 'edit_link' ], $ud_data[ 'created_by_user' ][ 'name' ] ) );
				
			}
			else if ( isset( $ud_data[ 'created_by_user' ] ) AND ! isset( $ud_data[ 'modified_by_user' ] ) ) {
				
				echo lang( 'ud_data_created_by_user', NULL, anchor( $ud_data[ 'created_by_user' ][ 'edit_link' ], $ud_data[ 'created_by_user' ][ 'name' ] ) );
				
			}
			else if ( ! isset( $ud_data[ 'created_by_user' ] ) AND isset( $ud_data[ 'modified_by_user' ] ) ) {
				
				echo lang( 'ud_data_modified_by_user', NULL, anchor( $ud_data[ 'modified_by_user' ][ 'edit_link' ], $ud_data[ 'modified_by_user' ][ 'name' ] ) );
				
			}
			else {
				
				echo lang( 'ud_data_created_by_unknow_user' );
				
			}
			
			echo '</span>';
			
			echo '</td>';
			
			echo '</tr>';
			
			foreach( $ud_data[ 'parsed_data' ][ 'full' ] as $alias => $pd ) {
				
				if ( ! isset( $fields[ $alias ][ 'field_type' ] ) OR ! in_array( $fields[ $alias ][ 'field_type' ], array( 'button' ) ) ) {
					
					if ( in_array( $alias, array( 'id', 'submit_datetime', 'mod_datetime' ) ) ) {
						
						if ( ! ( $alias == 'mod_datetime' AND ( isset( $ud_data[ 'parsed_data' ][ 'full' ][ 'submit_datetime' ][ 'value' ] ) AND $ud_data[ 'parsed_data' ][ 'full' ][ 'submit_datetime' ][ 'value' ] == $pd[ 'value' ] ) ) ) {
							
							echo '<tr class="item-inner user-submit-info-item-inner">';
							
							echo '<td class="title user-submit-info-item-title info-item-title">';
							
							echo '<span class="filter-me">';
							
							echo lang( $alias );
							
							echo '</span>';
							
							echo '</td>';
							
							echo '<td class="value user-submit-info-item-value info-item-content">';
							
							echo '<span class="filter-me">';
							
							if ( $alias == 'submit_datetime' OR $alias == 'mod_datetime' ) {
								
								$pd[ 'value' ] = strtotime( $pd[ 'value' ] );
								$pd[ 'value' ] = strftime( lang( 'ud_data_datetime' ), $pd[ 'value' ] );
								
							}
							
							echo $pd[ 'value' ];
							
							echo '</span>';
							
							echo '</td>';
							
							echo '</tr>';
							
						}
						
						continue;
						
					}
					
					echo '<tr class="item-inner user-submit-info-item-inner' . ( $pd[ 'value' ] == '' ? ' field-empty' : '' ) . '">';
					
					echo '<td class="title user-submit-info-item-title info-item-title">';
					
					echo '<span class="filter-me">';
					
					echo lang( isset( $existing_fields[ $alias ][ 'presentation_label' ] ) ? $existing_fields[ $alias ][ 'presentation_label' ] : ( isset( $existing_fields[ $alias ][ 'label' ] ) ? $existing_fields[ $alias ][ 'label' ] : '<span class="error" title="' . lang( 'submit_forms_error_no_field_on_submit_form' ) . '">[' . $alias . ']</span>' ) );
					
					echo '</span>';
					
					echo '</td>';
					
					echo '<td class="value user-submit-info-item-value info-item-content">';
					
					echo '<span class="filter-me">';
					
					$advanced_options = check_var( $existing_fields[ $alias ][ 'advanced_options' ] ) ? $existing_fields[ $alias ][ 'advanced_options' ] : FALSE;
					
					if ( check_var( $advanced_options[ 'prop_is_ud_image' ] ) AND check_var( $pd[ 'value' ] ) ) {
						
						$thumb_params = array(
							
							'wrapper_class' => 'us-image-wrapper',
							'src' => url_is_absolute( $pd[ 'value' ] ) ? $pd[ 'value' ] : get_url( 'thumbs/' . $pd[ 'value' ] ),
							'href' => get_url( $pd[ 'value' ] ),
							'rel' => 'gus-thumb',
							'title' => $pd[ 'value' ],
							'modal' => TRUE,
							'prevent_cache' => check_var( $advanced_options[ 'prop_is_ud_image_thumb_prevent_cache_admin' ] ) ? TRUE : FALSE,
							
						);
						
						echo vui_el_thumb( $thumb_params );
						
					}
					else if ( check_var( $advanced_options[ 'prop_is_ud_url' ] ) AND check_var( $pd[ 'value' ] ) ) {
						
						echo '<a target="_blank" href="' . get_url( $pd[ 'value' ] ) . '">' . $pd[ 'value' ] . '</a>';
						
					}
					else if ( check_var( $advanced_options[ 'prop_is_ud_title' ] ) AND check_var( $pd[ 'value' ] ) ) {
						
						echo $pd[ 'value' ];
						
					}
					else if ( check_var( $advanced_options[ 'prop_is_ud_email' ] ) AND check_var( $pd[ 'value' ] ) ) {
						
						echo '<a href="mailto:' . $pd[ 'value' ] . '">' . $pd[ 'value' ] . '</a>';
						
					}
					else if ( isset( $pd[ 'value' ] ) ) {
						
						echo htmlspecialchars_decode( $pd[ 'value' ] );
						
					}
					
					echo '</span>';
					
					echo '</td>';
					
					echo '</tr>';
					
				}
				
			} ?>
			
		</table>
		
	</div>
	
</div>
