<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
	
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
	
	require( 'vars.php' );
	
?>

<html xmlns="http://www.w3.org/1999/xhtml">
	
	<head>
		
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		
	</head>
	
	<body style="margin: 0; padding: <?= number_format( DEFAULT_SPACING * 2, 2, '.', '' ); ?>em 0 0; background: <?= $bg_color; ?>; font-family: 'Verdana', 'Arial';">
		
		<table width="100%" style="border-collapse: collapse; table-layout: fixed;" cellpadding="0" cellspacing="0">
			
			<tr>
				
				<td style="text-align: center;" cellpadding="0" cellspacing="0">
					
					<table width="80%" style="background: <?= $content_bg_color; ?>; color: <?= $content_fg_color; ?>; margin: 0 auto; text-align: left; border-collapse: collapse; border: none; border-bottom: thin solid <?= $content_border_color; ?>; width:80%" cellpadding="0" cellspacing="0">
						
						<tr>
							
							<?php if ( $image_logo ) { ?>
								
								<td style="padding: <?= number_format( DEFAULT_SPACING, 2, '.', '' ); ?>em <?= number_format( DEFAULT_SPACING * 2, 2, '.', '' ); ?>em; width:125px;">
									
									<img style="height: 100px; min-height: 100px; max-height: 100px;" src="<?= $image_logo; ?>" alt="logo" /> 
									
								</td>
								
							<?php } ?>
							
							<td style="padding: <?= number_format( DEFAULT_SPACING, 2, '.', '' ); ?>em <?= number_format( DEFAULT_SPACING * 2, 2, '.', '' ); ?>em; width:100%">
								
								<?php if ( $show_title ) { ?>
									
									<h3>
										
										<?= $title; ?>
										
									</h3>
									
								<?php } ?>
								
								<?php if ( $show_pre_text ) { ?>
									
									<?= $pre_text; ?>
									
								<?php } ?>
								
							</td>
							
						</tr>
						
					</table>
					
					<?php require( 'data.php' ); ?>
					
					<?php if ( $show_footer_text ) { ?>
					
					<table width="80%" style="background: <?= $content_bg_color; ?>; color: <?= $content_fg_color; ?>; margin: 0 auto; text-align: left; border-collapse: collapse; border: none; border-top: thin solid <?= $content_border_color; ?>; width:80%" cellpadding="0" cellspacing="0">
						
						<tr>
							
							<td style="padding: <?= number_format( DEFAULT_SPACING, 2, '.', '' ); ?>em <?= number_format( DEFAULT_SPACING * 2, 2, '.', '' ); ?>em; width:100%">
								
								<?= $footer_text; ?>
								
							</td>
							
						</tr>
						
					</table>
					
					<?php } ?>
					
				</td>
				
			</tr>
			
		</table>
		
	</body>
	
</html>
