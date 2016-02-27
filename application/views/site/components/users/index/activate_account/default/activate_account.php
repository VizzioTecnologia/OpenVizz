<?php if ( ! defined( 'BASEPATH' ) ) exit('No direct script access allowed' );
	
	$url = get_url( $this->uri->ruri_string() );
	
?>

<section class="c-users activate-account-form-wrapper form-wrapper <?= @$params['page_class']; ?>">
	
	<div class="form-wrapper-sub">
		
		<?= form_open( get_url( $url ), array( 'id' => 'user-form', ) ); ?>
			
			<header class="form-header">
				
				<h1>
					
					<?= lang( 'c_users_activate_account_page_title' ); ?>
					
				</h1>
				
			</header>
			
			<div class="vui-field-wrapper vui-field-wrapper-auto-block">
				
				<?php
					
					echo form_label( lang( 'c_users_acode_field_label' ) );
					echo form_error( 'acode', '<div class="msg-inline-error">', '</div>' );
					echo vui_el_input_text(
						
						array(
							
							'text' => lang( 'c_users_acode_field_label' ),
							'name' => 'acode',
							'value' => set_value( 'acode' ),
							'class' => 'acode',
							'id' => 'acode',
							'autofocus' => TRUE,
							
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
							
							'text' => lang( 'c_users_submit_acode_field_label' ),
							'button_type' => 'button',
							'name' => 'submit_acode',
							'id' => 'submit-acode',
							'class' => 'submit-acode',
							
						)
						
					);
					
				?>
				
			</div>
			
		<?= form_close(); ?>
		
	</div>
	
</div>
