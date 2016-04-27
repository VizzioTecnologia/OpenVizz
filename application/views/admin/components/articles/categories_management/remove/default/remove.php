
<header>
	
	<h1><?= lang( 'confirm_delete' ); ?></h1>
	
</header>

<div>
	
	<?= form_open( get_url( 'admin' . $this->uri->ruri_string() ) ); ?>
	
	<p>
		
		<?= lang( 'article_category_confirm_delete', NULL,  $category[ 'title' ] ); ?>
		
	</p>
	
	<?= vui_el_button( array( 'text' => lang( 'action_yes' ), 'icon' => 'ok', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit', 'id' => 'submit', ) ); ?>
	
	<?= vui_el_button( array( 'text' => lang( 'action_cancel' ), 'icon' => 'cancel', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_cancel', 'id' => 'submit-cancel', ) ); ?>
	
	<?= form_hidden( 'category_id', $category[ 'id' ] ); ?>
	
	<?= form_close(); ?>
	
</div>

