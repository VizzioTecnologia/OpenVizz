<?php


define( 'PDF_DEFAULT_SPACING', 0.9 );


$pmt = 10;
$pmr = 10;
$pmb = 10;
$pml = 10;

$fl = $pml;
$fr = $pmr;
$fb = $pmb;
$fh = 12; // footer height

$pmb = $pmb + $fh;

// adicionando as unidades
$pmt .= 'mm';
$pmr .= 'mm';
$pmb .= 'mm';
$pml .= 'mm';

$fl .= 'mm';
$fr .= 'mm';
$fb .= 'mm';
$fh .= 'mm';

?>
<html lang="<?= $this->mcm->filtered_system_params[ $this->mcm->environment . '_language' ]; ?>">
	<head>
		
		<style>
		
		@page {
			size: auto;
			margin: <?= $pmt; ?> <?= $pmr; ?> <?= $pmb; ?> <?= $pml; ?>;
			margin-header: 35px;
			margin-footer: 40px;
			header: html_header;
			footer: html_footer;
		}
		
		div.pagebreak{
			
			page-break-before: left;
			
		}
		
		body{
			
			font-family: opensanslight;
			font-size: 12pt;
			color:#000;
			
		}
		h3{
			
			font-size: 12pt;
			
		}
		table.ud_data {
			
			font-size: 10pt;
			
			width: 100%;
			border-collapse: collapse;
			
			page-break-inside: avoid;
			
		}
		tr{
			
			border-collapse:collapse;
			
		}
		td{
			
			border-collapse:collapse;
			
		}
		table.ud_data tr td {
			
			font-size: 9pt;
			
			padding: 3pt 15pt;
			
			border-collapse: collapse;
			
			border: none;
			
		}
		table.ud_data tr td.title {
			
			width: 35%;
			
		}
		table.ud_data tr td.value {
			
			border-left: thin solid #b1b9be;
			
		}
		table.ud_data tr.odd td {
			
			background: #eceeef;
			
		}
		table.ud_data th.ud-data-header {
			
			font-weight: normal;
			text-align: left;
			
			font-size: 10pt;
			
			padding: 3pt 15pt;
			
			background: #dce0e2;
			
			border-bottom: 2pt solid #fff;
			
		}
		table.ud_data tr.id td,
		table.ud_data tr.submit_datetime td {
			
			font-family: opensanssemibold;
			
		}
		table.ud_data tr.id td {
			
		}
		
		strong {
			
			font-family: opensanssemibold;
			font-weight: normal;
			
		}
		
		
		p{
			
			position:relative;
			display:block;
			margin: 12pt;
			
		}
		
		
		#footer {
			
			width: 100%;
			
		}
		#footer th,
		#footer td{
			
			font-size: 7pt;
			padding: 0;
			width: 20%;
			border: none;
			
		}
		
		#footer-pagination{
			
			text-align: right;
			
		}
		
		</style>
	</head>
	
	<body>
		
		<?php foreach ( $submit_forms as $key => $submit_form ) {
			
			if ( check_var( $submit_form[ 'users_submits' ] ) ){
				
				$index = 0;
				
			?>
				
				<?php foreach ( $submit_form[ 'users_submits' ] as $key => $ud_data ) {
					
					if ( $index > 0 ) {
						
						echo '<div class="pagebreak"></div>';
						
					}
					
					$index++;
					
				?>
					
					<table class="ud_data">
						
						<tr>
							
							<th colspan="2" class="ud-data-header">
								
								<?= lang( 'ud_data_export_pdf_data_footer', NULL, $submit_form[ 'id' ], $submit_form[ 'title' ] ); ?>
								
							</th>
							
						</tr>
						
						<?php
						
						$existing_fields = FALSE;
						
						$fields = $submit_form[ 'fields' ];
						
						foreach ( $fields as $field_key => $field ) {
							
							if ( ! in_array( $field[ 'field_type' ], array( 'html', 'button' ) ) ) {
								
								$existing_fields[ $field[ 'alias' ] ] = $field;
								
							}
							
						}
						
						$total_rows = 0;
						
						$current_row_index = 1;
						
						$odd_class = 'odd';
						
						foreach( $ud_data[ 'parsed_data' ][ 'full' ] as $alias => $pd ) {
							
							if ( $alias != 'mod_datetime' AND ( in_array( $alias, array( 'id', 'submit_datetime' ) ) OR ( ( $pd[ 'value' ] == '' AND check_var( $submit_form[ 'params' ][ 'ud_data_export_pdf_show_empty_props' ] ) ) OR $pd[ 'value' ] != '' ) ) ) {
								
								$total_rows++;
								
							}
							
						}
						
						foreach( $ud_data[ 'parsed_data' ][ 'full' ] as $alias => $pd ) {
							
							if ( $alias != 'mod_datetime' AND ( in_array( $alias, array( 'id', 'submit_datetime' ) ) OR ( ( $pd[ 'value' ] == '' AND check_var( $submit_form[ 'params' ][ 'ud_data_export_pdf_show_empty_props' ] ) ) OR $pd[ 'value' ] != '' ) ) ) {
								
								$current_row_index++;
								
								if ( ! isset( $fields[ $alias ][ 'field_type' ] ) OR ! in_array( $fields[ $alias ][ 'field_type' ], array( 'button' ) ) ) {
									
									if ( in_array( $alias, array( 'id', 'submit_datetime', 'mod_datetime' ) ) ) {
										
										if ( ! ( $alias == 'mod_datetime' AND ( isset( $ud_data[ 'parsed_data' ][ 'full' ][ 'submit_datetime' ][ 'value' ] ) AND $ud_data[ 'parsed_data' ][ 'full' ][ 'submit_datetime' ][ 'value' ] == $pd[ 'value' ] ) ) ) {
											
											echo '<tr class="' . ( $odd_class ) . ' ' . $alias . '">';
											
											echo '<td class="title">';
											
											echo '<span>';
											
											echo lang( $alias );
											
											echo '</span>';
											
											echo '</td>';
											
											echo '<td class="value">';
											
											echo '<span>';
											
											echo $pd[ 'value' ];
											
											echo '</span>';
											
											echo '</td>';
											
											echo '</tr>';
											
											$odd_class = $odd_class == 'even' ? 'odd' : 'even';
											
										}
										
										continue;
										
									}
									
									echo '<tr class="' . ( $odd_class ) . '">';
									
									echo '<td class="title">';
									
									echo '<span>';
									
									echo lang( isset( $existing_fields[ $alias ][ 'presentation_label' ] ) ? $existing_fields[ $alias ][ 'presentation_label' ] : ( isset( $existing_fields[ $alias ][ 'label' ] ) ? $existing_fields[ $alias ][ 'label' ] : '<span class="error" title="' . lang( 'submit_forms_error_no_field_on_submit_form' ) . '">[' . $alias . ']</span>' ) );
									
									echo '</span>';
									
									echo '</td>';
									
									echo '<td class="value">';
									
									echo '<span>';
									
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
									
									$odd_class = $odd_class == 'even' ? 'odd' : 'even';
									
								}
								
							}
							
						} ?>
						
					</table>
					
				<?php } ?>
				
				
			<?php } ?>

		<?php } ?>

		<htmlpagefooter name="footer" style="display:none">

			<table id="footer">

				<tr>

					<td id="footer-content">

					</td>

					<td id="footer-pagination">

						{PAGENO}/{nbpg}

					</td>

				</tr>

			</table>

		</htmlpagefooter>
		
	</body>
	
</html>
