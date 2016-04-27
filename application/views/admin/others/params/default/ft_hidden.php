<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

?><input type="hidden" name="<?= $formatted_name; ?>" value="<?= $params_values[ $name ]; ?>" id="<?= 'param-' . $name; ?>" class="<?= $class; ?>" /><?php 