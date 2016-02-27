<?php  if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

?><div class="field-param-wrapper field-param-wrapper-<?= $type; ?> <?= $inline ? 'field-param-inline' : ''; ?> <?= $class; ?>">
	
	<?php if ( $label ) { ?>
		
		<h<?= $level ? $level : 5; ?> title="<?= $tip; ?>" class="<?= $class; ?>" data-ext-tip="<?= $ext_tip; ?>" ><?= $label; ?></h<?= $level ? $level : 5; ?>>
		
	<?php } ?>
	
</div><?php 