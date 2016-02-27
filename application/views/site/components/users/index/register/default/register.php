<?php if ( ! defined( 'BASEPATH' ) ) exit('No direct script access allowed' ); ?>

<section class="c-users register-form-wrapper form-wrapper <?= @$params['page_class']; ?>">
	
	<div class="form-wrapper-sub">
		
		<?= form_open( get_url( $url ), array( 'id' => 'user-form', ) ); ?>
			
			<header class="form-header">
				
				<h1>
					
					<?= lang( 'c_users_register_page_title' ); ?>
					
				</h1>
				
			</header>
			
			<div class="vui-field-wrapper vui-field-wrapper-auto-block">
				
				<?php
					
					echo form_label( lang( 'c_users_complete_name_field_label' ) );
					echo form_error( 'name', '<div class="msg-inline-error">', '</div>' );
					echo vui_el_input_text(
						
						array(
							
							'text' => lang( 'c_users_complete_name_field_label' ),
							'name' => 'name',
							'value' => set_value( 'name' ),
							'class' => 'name',
							'id' => 'name',
							'autofocus' => TRUE,
							
						)
						
					);
					
				?>
				
			</div><?php
			
			?><div class="vui-field-wrapper vui-field-wrapper-auto-block">
				
				<?php
					
					echo form_label( lang( 'c_users_username_field_label' ), NULL, array( 'title' => lang( 'tip_c_users_username_field_label' ) ) );
					echo form_error( 'username', '<div class="msg-inline-error">', '</div>' );
					echo vui_el_input_text(
						
						array(
							
							'text' => lang( 'c_users_username_field_label' ),
							'title' => lang( 'tip_c_users_username_field_label' ),
							'name' => 'username',
							'value' => set_value( 'username' ),
							'class' => 'username',
							'id' => 'username',
							
						)
						
					);
					
				?>
				
			</div><?php
			
			?><div class="vui-field-wrapper vui-field-wrapper-auto-block">
				
				<?php
					
					echo form_label( lang( 'c_users_email_field_label' ) );
					echo form_error( 'email', '<div class="msg-inline-error">', '</div>' );
					echo vui_el_input_text(
						
						array(
							
							'text' => lang( 'c_users_email_field_label' ),
							'name' => 'email',
							'value' => set_value( 'email' ),
							'class' => 'email',
							'id' => 'email',
							
						)
						
					);
					
				?>
				
			</div><?php
			
			?><div class="vui-field-wrapper vui-field-wrapper-inline">
				
				<?php
					
					echo form_label( lang( 'c_users_password_field_label' ) );
					echo form_error( 'password', '<div class="msg-inline-error">', '</div>' );
					echo vui_el_input_password(
						
						array(
							
							'text' => lang( 'c_users_password_field_label' ),
							'name' => 'password',
							'value' => set_value( 'password' ),
							'class' => 'password',
							'id' => 'password',
							
						)
						
					);
					
				?>
				
			</div><?php
			
			?><div class="vui-field-wrapper vui-field-wrapper-inline">
				
				<?php
					
					echo form_label( lang( 'c_users_confirm_password_field_label' ) );
					echo form_error( 'confirm_password', '<div class="msg-inline-error">', '</div>' );
					echo vui_el_input_password(
						
						array(
							
							'text' => lang( 'c_users_confirm_password_field_label' ),
							'name' => 'confirm_password',
							'value' => set_value( 'confirm_password' ),
							'class' => 'confirm_password',
							'id' => 'confirm_password',
							
						)
						
					);
					
				?>
				
			</div><?php
			
			?><div class="vui-field-wrapper vui-field-wrapper-auto-block">
				
				<div class="captcha-wrapper">
					
					<?php
						
						$this->load->helper( 'captcha' );
						
						$vals = array(
							
							//'word'	=> 'Random word',
							
						);
						
						$cap = create_captcha($vals);
						
						$data = array(
							
							'time'	=> number_format( $cap[ 'time' ], 6, '.', '' ),
							'ip_address'	=> $this->input->ip_address(),
							'word'	=> strtolower( $cap[ 'word' ] )
							
						);
						
						$query = $this->db->insert_string( 'tb_captcha', $data );
						$this->db->query($query);
						
						echo form_label( lang( 'c_users_captcha_field_label' ) );
						echo form_error( 'captcha', '<div class="msg-inline-error">', '</div>' );
						
						echo '<div class="captcha-image-wrapper">';
						
						echo $cap[ 'image' ];
						
						echo '</div>';
						
						echo vui_el_input_text(
							
							array(
								
								'text' => lang( 'c_users_captcha_field_label' ),
								'name' => 'captcha',
								'class' => 'captcha',
								'id' => 'captcha',
								
							)
							
						);
						
					?>
					
				</div>
				
			</div><?php
				
			?><div class="vui-field-wrapper vui-field-wrapper-auto-block">
				
				<?php
					
					echo vui_el_button(
						
						array(
							
							'text' => lang( 'c_users_submit_submit_register_field_label' ),
							'button_type' => 'button',
							'name' => 'submit_register',
							'id' => 'submit-register',
							'class' => 'submit-register',
							
						)
						
					);
					
				?>
				
			</div>
			
		<?= form_close(); ?>
		
	</div>
	
</div>
