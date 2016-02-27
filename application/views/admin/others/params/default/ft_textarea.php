<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

?><div class="field-param-wrapper field-param-wrapper-<?= $type; ?> <?= $inline ? 'field-param-inline' : ''; ?> <?= $editor ? 'with-js-editor' : ''; ?> <?= $class; ?>">
	
	<?= form_error( $formatted_name, '<div class="msg-inline-error">', '</div>' ); ?>
	
	<label title="<?= $tip; ?>" class="<?= $class; ?>" data-ext-tip="<?= $ext_tip; ?>" for="param-<?= $name; ?>">
		
		<?= $label; ?>
		
	</label>
	
	<?php
		
		if ( $editor ){
			
			$CI->plugins->load( NULL, 'js_text_editor' );
			
		}
		
		echo vui_el_textarea(
				
			array(
				
				'text' => $label,
				'value' => $params_values[ $name ],
				'id' => $name,
				'name' => $formatted_name,
				'class' => $class . ( $editor ? ' js-editor' : '' ),
				'title' => rawurldecode( $ext_tip ),
				'icon' => $icon,
				'maxlength' => $maxlength,
				
			)
			
		);
		
	?>
	
</div><?php 