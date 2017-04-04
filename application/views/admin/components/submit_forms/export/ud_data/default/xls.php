<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
	
	header( 'Content-Type: application/vnd.ms-excel; charset=utf-8' );
	header( 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' );
	header( 'Content-Disposition: attachment; filename=' . $dl_filename . '.xlsx' );
	header( 'Cache-Control: max-age=0' );
	
	$headers_bg_color = check_var( $params[ 'ud_data_export_xls_headers_bg_color' ] ) ? $params[ 'ud_data_export_xls_headers_bg_color' ] : '#dce0e2';
	$row_bg_color = check_var( $params[ 'ud_data_export_xls_row_bg_color' ] ) ? $params[ 'ud_data_export_xls_row_bg_color' ] : 'transparent';
	$row_alt_bg_color = check_var( $params[ 'ud_data_export_xls_row_alt_bg_color' ] ) ? $params[ 'ud_data_export_xls_row_alt_bg_color' ] : '#eceeef';
	$general_font_size = check_var( $params[ 'ud_data_export_xls_general_font_size' ] ) ? $params[ 'ud_data_export_xls_general_font_size' ] : '12pt';
	$general_border_size = check_var( $params[ 'ud_data_export_xls_general_border_size' ] ) ? $params[ 'ud_data_export_xls_general_border_size' ] : '0';
	$general_border_color = check_var( $params[ 'ud_data_export_xls_general_border_color' ] ) ? $params[ 'ud_data_export_xls_general_border_color' ] : '#000000';
	
	$cell_general_style = '';
	
	if ( $general_border_size == '0' ) {
		
		$cell_general_style = 'border: none; border-left: thin solid ' . $general_border_color . ';';
		
	}
	else {
		
		$cell_general_style = 'border: ' . $general_border_size . ' solid ' . $general_border_color . ';';
		
	}
	
	$cell_general_style .= 'border-collapse: collapse;';
	$cell_general_style .= 'vertical-align: middle;';
	
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
	
	<head>
	
		<meta http-equiv="content-type" content="application/xls; charset=UTF-8" />
		<meta charset="utf-8" />
		
	</head>
	
	<body>
		
		<?php
			
			$existing_fields = FALSE;
			
// 			echo '<pre>' . print_r( $submit_forms, TRUE ) . '</pre>'; exit;
			
			foreach ( $submit_forms as $key => $data_scheme ) {
				
				if ( check_var( $data_scheme[ 'users_submits' ] ) ){
					
					// ----------------------
					// Columns
					
					$props = $data_scheme[ 'fields' ];
					
					$columns = array(
						
						'id',
						'submit_datetime',
						'mod_datetime',
						
					);
					
					foreach ( $props as $prop ) {
						
						if ( ! in_array( $prop[ 'field_type' ], array( 'html', 'button' ) ) ) {
							
							$columns[] = $prop[ 'alias' ];
							
						}
						
					}
					
					echo '<table style="border: ' . $general_border_size . ' solid ' . $general_border_color . '; border-collapse: collapse;" cellpadding="0" cellspacing="0">';
					
					echo '<tr style="background-color: ' . $headers_bg_color . ';">';
					
					echo '<td style="' . $cell_general_style . '" colspan="' . ( count( $columns ) ) . '">';
					
					echo $data_scheme[ 'title' ];
					
					echo '</td>';
					
					echo '</tr>';
					
					echo '<tr style="background-color: ' . $headers_bg_color . ';">';
					
					echo '<th style="' . $cell_general_style . '" >';
					
					echo lang( ( isset( $data_scheme[ 'params' ][ 'ud_ds_default_data_id_pres_title' ] ) ? lang( $data_scheme[ 'params' ][ 'ud_ds_default_data_id_pres_title' ] ) : 'id' ) );
					
					echo '</th>';
					
					echo '<th style="' . $cell_general_style . '" >';
					
					echo lang( ( isset( $data_scheme[ 'params' ][ 'ud_ds_default_data_sdt_pres_title' ] ) ? lang( $data_scheme[ 'params' ][ 'ud_ds_default_data_sdt_pres_title' ] ) : 'submit_datetime' ) );
					
					echo '</th>';
					
					echo '<th style="' . $cell_general_style . '" >';
					
					echo lang( ( isset( $data_scheme[ 'params' ][ 'ud_ds_default_data_mdt_pres_title' ] ) ? lang( $data_scheme[ 'params' ][ 'ud_ds_default_data_mdt_pres_title' ] ) : 'mod_datetime' ) );
					
					echo '</th>';
					
					foreach ( $props as $prop ) {
						
						if ( ! in_array( $prop[ 'field_type' ], array( 'html', 'button' ) ) ) {
							
							echo '<th style="' . $cell_general_style . '" >';
							
							echo lang( $prop[ 'presentation_label' ] );
							
							echo '</th>';
							
						}
						
					}
					
					echo '</tr>';
					
					// Columns
					// ----------------------
					
					// ----------------------
					// Rows
					
					$row_alt = FALSE;
					
					foreach ( $data_scheme[ 'users_submits' ] as $key => $ud_data ) {
						
						echo '<tr style="background-color: ' . ( $row_alt ? $row_alt_bg_color : $row_bg_color ) . ';">';
						
						foreach ( $columns as $column ) {
							
							echo '<td style="' . $cell_general_style . '" >';
							
							if ( isset( $ud_data[ 'parsed_data' ][ 'full' ][ $column ] ) ) {
								
								$pd = $ud_data[ 'parsed_data' ][ 'full' ][ $column ];
								
								echo $pd[ 'value' ];
								
							}
							
							echo '</td>';
							
						}
						
						echo '</tr>';
						
						$row_alt = $row_alt ? FALSE : TRUE;
						
					}
					
					// Rows
					// ----------------------
					/*
					foreach ( $data_scheme[ 'users_submits' ] as $key => $ud_data ) {
						
						foreach( $ud_data[ 'parsed_data' ][ 'full' ] as $alias => $pd ) {
							
							if ( ! isset( $props[ $alias ][ 'field_type' ] ) OR ! in_array( $props[ $alias ][ 'field_type' ], array( 'button' ) ) ) {
								
								if ( in_array( $alias, array( 'id', 'submit_datetime', 'mod_datetime' ) ) ) {
									
									if ( ! ( $alias == 'mod_datetime' AND ( isset( $ud_data[ 'parsed_data' ][ 'full' ][ 'submit_datetime' ][ 'value' ] ) AND $ud_data[ 'parsed_data' ][ 'full' ][ 'submit_datetime' ][ 'value' ] == $pd[ 'value' ] ) ) ) {
										
										
										
										echo '<td style="padding: ' . ( number_format( DEFAULT_SPACING / 2, 2, '.', '' ) ) . 'em ' . ( number_format( DEFAULT_SPACING, 2, '.', '' ) ) . 'em; width: 20%; text-align: right; border-bottom: thin solid ' . $content_border_color . '; border-right: thin solid ' . $content_border_color . '; border-left: ' . ( number_format( DEFAULT_SPACING * 2, 2, '.', '' ) ) . 'em solid ' . $content_bg_color . ';">';
										
										echo '<span>';
										
										echo lang( $alias );
										
										echo '</span>';
										
										echo '</td>';
										
										echo '<td style="padding: ' . ( number_format( DEFAULT_SPACING / 2, 2, '.', '' ) ) . 'em ' . ( number_format( DEFAULT_SPACING, 2, '.', '' ) ) . 'em; border-bottom: thin solid ' . $content_border_color . '; border-right: ' . ( number_format( DEFAULT_SPACING * 2, 2, '.', '' ) ) . 'em solid ' . $content_bg_color . ';">';
										
										echo '<span>';
										
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
								
								echo '<tr>';
								
								echo '<td style="padding: ' . ( number_format( DEFAULT_SPACING / 2, 2, '.', '' ) ) . 'em ' . ( number_format( DEFAULT_SPACING, 2, '.', '' ) ) . 'em; width: 20%; text-align: right; ' . ( ( $current_row_index <= $total_rows ) ? 'border-bottom: thin solid ' . $content_border_color . '; ' : '' ) . 'border-right: thin solid ' . $content_border_color . '; border-left: ' . ( number_format( DEFAULT_SPACING * 2, 2, '.', '' ) ) . 'em solid ' . $content_bg_color . ';">';
								
								echo '<span>';
								
								echo lang( isset( $existing_fields[ $alias ][ 'presentation_label' ] ) ? $existing_fields[ $alias ][ 'presentation_label' ] : ( isset( $existing_fields[ $alias ][ 'label' ] ) ? $existing_fields[ $alias ][ 'label' ] : '<span class="error" title="' . lang( 'submit_forms_error_no_field_on_submit_form' ) . '">[' . $alias . ']</span>' ) );
								
								echo '</span>';
								
								echo '</td>';
								
								echo '<td style="padding: ' . ( number_format( DEFAULT_SPACING / 2, 2, '.', '' ) ) . 'em ' . ( number_format( DEFAULT_SPACING, 2, '.', '' ) ) . 'em; ' . ( ( $current_row_index <= $total_rows ) ? 'border-bottom: thin solid ' . $content_border_color . '; ' : '' ) . 'border-right: ' . ( number_format( DEFAULT_SPACING * 2, 2, '.', '' ) ) . 'em solid ' . $content_bg_color . ';">';
								
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
							
						}
						
					}
					*/
					echo '</table>';
					
				}
				
			}
			
		?>
		
	</body>

</html>

<?php
	
	$html = ob_get_contents();
	libxml_use_internal_errors(true);
	
	ob_end_clean();
	
	$tmpfile = tempnam( sys_get_temp_dir(), 'html' );
	file_put_contents( $tmpfile, $html );
	
	$this->load->library( 'excel' );
	
	$objPHPExcel     = new PHPExcel();
	$excelHTMLReader = PHPExcel_IOFactory::createReader( 'HTML' );
	$excelHTMLReader->loadIntoExisting( $tmpfile, $objPHPExcel );
	unlink( $tmpfile );
	
	$objPHPExcel->getActiveSheet()->setTitle( lang( 'ud_data_export_xls_worksheet_default_label' ) );
	
	$sheet = $objPHPExcel->getActiveSheet();
	$cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
	$cellIterator->setIterateOnlyExistingCells( true );
	/** @var PHPExcel_Cell $cell */
	foreach( $cellIterator as $cell ) {
		$sheet->getColumnDimension( $cell->getColumn() )->setAutoSize( true );
	}
	
	$writer = PHPExcel_IOFactory::createWriter( $objPHPExcel, 'Excel2007' );
	
	$writer->save( 'php://output' );
	
?>
