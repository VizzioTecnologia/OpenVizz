<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<section class="c-users login-wrapper">
	
	<header>
		
		<h3>
			
			<?php echo lang( 'login' ); ?>
			
		</h3>
		
	</header>
	
	<?php
		
		$_url = get_url( 'admin' . $this->uri->ruri_string() );
		
	?>
	
	<?php echo form_open( $_url ); ?>
		
		<div class="username-wrapper vui-field-wrapper">
			
			<?= form_error( 'username', '<div class="msg-inline-error">', '</div>' ); ?>
			<?= form_label( lang( 'username' ) ); ?>
			
			<?= vui_el_input_text(
				
				array(
					
					'text' => lang( 'username' ),
					'name' => 'username',
					'value' => set_value( 'username' ),
					'class' => 'field-username',
					'id' => 'username',
					'autofocus' => TRUE,
					'icon' => 'user',
					
				)
				
			); ?>
			
		</div><?php
		
		?><div class="password-wrapper vui-field-wrapper">
			
			<?= form_error( 'password', '<div class="msg-inline-error">', '</div>' ); ?>
			<?= form_label( lang( 'password' ) ); ?>
			
			<?= vui_el_input_password(
				
				array(
					
					'text' => lang( 'password' ),
					'name' => 'password',
					'value' => set_value( 'password' ),
					'class' => 'field-password',
					'id' => 'password',
					'autofocus' => TRUE,
					'icon' => 'key',
					
				)
				
			); ?>
			
		</div><?php
		
		?><div class="button-login-wrapper vui-field-wrapper">
			
			<?php 
			
				echo vui_el_button(
					
					array(
						
						'text' => lang( 'action_login' ),
						'icon' => 'ok',
						'button_type' => 'button',
						'name' => 'submit',
						'id' => 'submit',
						
					)
					
				);
				
				?>
				
		</div><?php
		
		?><div class="keep-me-logged-in-wrapper vui-field-wrapper">
			
			<?php
				
				$options = array(
					
					'wrapper-class' => 'checkbox-sub-item',
					'name' => 'keep_me_logged_in',
					'id' => 'keep-me-logged-in',
					'checked' => ( ! $this->input->post() ? TRUE : ( $this->input->post( 'keep_me_logged_in' ) ? TRUE : FALSE ) ),
					'text' => lang( 'keep_me_logged_in' ),
					'class' => 'keep-me-logged-in',
					
				);
				
				echo vui_el_checkbox( $options );
				
			?>
			
		</div>
		
		<!-- Not good yet
		<div class="button-login-wrapper vui-field-wrapper vui-field-wrapper-inline">
			
			<?= vui_el_button( array( 'url' => 'admin/main/index/google_login', 'text' => lang( 'google_login' ), 'icon' => 'google', 'only_icon' => TRUE, ) ); ?>
			
			<?= vui_el_button( array( 'url' => 'admin/main/index/facebook_login', 'text' => lang( 'facebook_login' ), 'icon' => 'facebook', 'only_icon' => TRUE, ) ); ?>
			
			
		</div>
		-->
		
	<?php echo form_close(); ?>
	
</section>
