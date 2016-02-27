<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php if ( $icon ) { ?>

<span class="vui-icon-wrapper icon-wrapper<?= $class ? ' ' . $class : ''; ?>"<?= $name ? ' name="' . $name . '"' : ''; ?><?= $id ? ' id="' . $id . '"' : ''; ?><?= $title ? ' title="' . $title . '"' : ''; ?>>
	
	<span class="icon icon-<?= $icon; ?>"></span>
	
</span>

<?php } ?>