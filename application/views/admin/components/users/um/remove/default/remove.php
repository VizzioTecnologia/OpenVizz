
<header>
	
	<h4><?= lang( 'confirm_delete' ); ?></h4>
	
</header>

<div class="confirm-remove-wrapper">
	
	<?= form_open( get_url( 'admin' . $this->uri->ruri_string() ) ); ?>
	
	<p>
		
		<?= lang( 'user_confirm_delete', NULL, $user[ 'name' ], $user[ 'username' ] ); ?>
		
	</p>
	
	<?= vui_el_button( array( 'text' => lang( 'action_yes' ), 'icon' => 'ok', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit', 'id' => 'submit', ) ); ?>
	
	<?= vui_el_button( array( 'text' => lang( 'action_cancel' ), 'icon' => 'cancel', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_cancel', 'id' => 'submit-cancel', ) ); ?>
	
	<?= form_close(); ?>
	
</div>
