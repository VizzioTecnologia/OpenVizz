<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

?><div class="field-param-wrapper field-param-wrapper-<?= $type; ?> <?= $inline ? 'field-param-inline' : ''; ?> <?= $class; ?>">
	
	<?= form_error( $formatted_name, '<div class="msg-inline-error">', '</div>' ); ?>
	
	<label title="<?= $tip; ?>" class="<?= $class; ?>" data-ext-tip="<?= $ext_tip; ?>" for="param-<?= $name; ?>">
		
		<?= $label; ?>
		
	</label>
	
	<?php
		
		// input params
		$ip = array(
			
			'id' => $name,
			'name' => $formatted_name,
			'class' => $class,
			
		);
		if ( ( int )$maxlength > 0 ) $ip[ 'maxlength' ] = $maxlength;
		
		echo form_password( $ip, $params_values[ $name ] );
		
	?>
	
</div><?php 