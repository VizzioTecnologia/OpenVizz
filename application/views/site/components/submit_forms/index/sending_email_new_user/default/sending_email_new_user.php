<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' ); ?>

<p>
	
	<h4>
		
		<?= lang( 'submit_forms_email_to_new_user_welcome', NULL, $user_data[ 'name' ] );?>
		
	</h4>
	
</p>

<p>
	
	<?= lang( 'submit_forms_email_to_new_user_data' ); ?><br>
	
</p>

<p>
	
	<strong><?=lang( 'user_name' ); ?>:</strong> <?= $user_data[ 'name' ]; ?><br>
	<strong><?=lang( 'user_username' ); ?>:</strong> <?= $user_data[ 'username' ]; ?><br>
	<strong><?=lang( 'user_email' ); ?>:</strong> <?= $user_data[ 'email' ]; ?><br>
	<strong><?=lang( 'user_password' ); ?>:</strong> <?= $user_data[ 'password' ]; ?>
	
</p>

<p>
	
	<?= lang( 'submit_forms_email_to_new_user_access' ); ?>
	
</p>
