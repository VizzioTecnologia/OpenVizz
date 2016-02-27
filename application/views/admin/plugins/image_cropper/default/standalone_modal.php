<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="image-cropper-main">
	
	<div class="image-cropper-main-inner">
		
		<div class="image-cropper-overlay"></div>
		
		<div class="image-cropper-wrapper">
			
		</div>
		
		<div class="image-cropper-controls">
			
			<div class="image-cropper-preview"></div>
			
			<?php
				
				$options = array(
					
					'url' => 'admin/main/plg/pn/image_cropper',
					'text' => lang( 'action_ok' ),
					'icon' => 'apply',
					'only_icon' => FALSE,
					'class' => 'submit-ok',
					
				);
				
				$ok_button = vui_el_button( $options );
				
			?>
			
			<?= $ok_button; ?>
			
			<?php
				
				$options = array(
					
					'url' => '#',
					'text' => lang( 'action_cancel' ),
					'icon' => 'cancel',
					'only_icon' => FALSE,
					'class' => 'submit-cancel',
					
				);
				
				$cancel_button = vui_el_button( $options );
				
			?>
			
			<?= $cancel_button; ?>
			
		</div>
		
	</div>
	
</div>
