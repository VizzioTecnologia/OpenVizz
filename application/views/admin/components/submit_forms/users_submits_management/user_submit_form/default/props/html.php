<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

?><div id="<?= $unique_hash; ?>-ud-prop-field-<?= $field_name; ?>" class="ud-prop-field-wrapper ud-prop-type-<?= $field[ 'field_type' ]; ?> vui-field-wrapper <?= ( check_var( $field[ 'form_css_class' ] ) ) ? $field[ 'form_css_class' ] : ''; ?> submit-form-field-wrapper submit-form-field-wrapper-<?= $field_name; ?>-wrapper submit-form-field-wrapper-<?= $field[ 'field_type' ]; ?> <?= ( $error ) ? 'form-error error' : ''; ?>">
	
	<div class="submit-form-field-control">
		
		<?= $field[ 'html' ]; ?>
		
	</div>
	
</div>