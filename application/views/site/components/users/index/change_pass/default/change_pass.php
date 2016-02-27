<?php if ( ! defined( 'BASEPATH' ) ) exit('No direct script access allowed' );
	
	$_url = get_url( $this->uri->ruri_string() );
	
?>

<section class="c-users change-pass-form-wrapper form-wrapper <?= @$params['page_class']; ?>">
	
	<div class="form-wrapper-sub">
		
		<?= form_open( get_url( $_url ), array( 'id' => 'user-form', ) ); ?>
			
			<header class="form-header">
				
				<h1>
					
					<?= lang( 'c_users_change_pass_page_title' ); ?>
					
				</h1>
				
			</header>
			
			<div class="vui-field-wrapper vui-field-wrapper-inline">
				
				<?php
					
					echo form_label( lang( 'c_users_new_password_field_label' ) );
					echo form_error( 'password', '<div class="msg-inline-error">', '</div>' );
					echo vui_el_input_password(
						
						array(
							
							'text' => lang( 'c_users_new_password_field_label' ),
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
							
							'text' => lang( 'c_users_submit_submit_change_pass_field_label' ),
							'button_type' => 'button',
							'name' => 'submit_change-pass',
							'id' => 'submit-change-pass',
							'class' => 'submit-change-pass',
							
						)
						
					);
					
				?>
				
			</div>
			
		<?= form_close(); ?>
		
	</div>
	
</div>
