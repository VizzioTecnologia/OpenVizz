<?php

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
			odd-header-name: html_myHeader1;
			even-header-name: html_myHeader1;
			odd-footer-name: html_myFooter1;
			even-footer-name: html_myFooter1;
		}
		@page chapter2 {
			odd-header-name: html_myHeader2;
			even-header-name: html_myHeader2;
			odd-footer-name: html_myFooter2;
			even-footer-name: html_myFooter2;
		}


		div.chapter2 {
			page-break-before: right;
			page: chapter2;
		}
		div.noheader {
			page-break-before: right;
			page: noheader;
		}

		body{

			font-family: gudea, "Arial", serif;
			font-size: 12pt;
			color:#000;

		}
		h3{

			font-size: 12pt;

		}
		table{

			autosize: 1;

			font-size: 10pt;

			width: 100%;
			border-collapse: collapse;

		}
		th{

			text-align: left;
			background: rgba( 0, 0, 0, .05 );

		}
		tr{

			border-collapse:collapse;

		}
		td{

			border-collapse:collapse;

		}
		th,
		td{

			font-size: 9pt;

			padding: 3pt 15pt;

			border-collapse: collapse;
			border-bottom: thin solid #000000;

		}


		p{

			position:relative;
			display:block;
			margin: 12pt;

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

	<?php foreach ( $submit_forms as $key => $submit_form ) { ?>

		<?php if ( check_var( $submit_form[ 'users_submits' ] ) ){ ?>


			<h3 >

				<small>#<?= $submit_form[ 'id' ]; ?></small> - <?= sprintf( lang( 'submit_form_title_sprintf' ), $submit_form[ 'title' ] ); ?>

			</h3>
			
			<?php foreach ( $submit_form[ 'users_submits' ] as $key => $user_submit ) { ?>
				
				<table >
					
					<tr>
						
						<th>
							
							<?= lang( 'user_submit_id' ); ?>

						</th>

						<th>

							<?= $user_submit[ 'id' ]; ?>

						</th>

					</tr>

					<tr>

						<th>

							<?= lang( 'submit_datetime' ); ?>

						</th>

						<th>

							<?= $user_submit[ 'submit_datetime' ]; ?>

						</th>

					</tr>

					<tr>

					<?php foreach ( $submit_form[ 'fields' ] as $key => $field ) { ?>

						<?php
							
							$value_name = $field[ 'alias' ];
							$formatted_field_name = 'form[' . $value_name . ']';
							$value_value = isset( $user_submit[ $value_name ] ) ? $user_submit[ $value_name ] : ( isset( $user_submit[ 'data' ][ $value_name ] ) ? $user_submit[ 'data' ][ $value_name ] : '' );
							
						?>
						
						<?php if ( ! in_array( $field[ 'field_type' ], array( 'html', 'button' ) ) ) { ?>
						
					<tr>
						
						<th>
							
							<?= isset( $field[ 'presentation_label' ] ) ? $field[ 'presentation_label' ] : $field[ 'label' ]; ?>
							
						</th>
						
						<td>
							
							<?php
							
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
								
							} ?>
							
						</td>
						
					</tr>
						
						<?php } ?>
						
					<?php } ?>
					
				</table>

				<br /><br />

			<?php } ?>
			<div style="page-break-before:right"></div>
		<?php } ?>

	<?php } ?>

	<htmlpagefooter name="myFooter1" style="display:none">

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