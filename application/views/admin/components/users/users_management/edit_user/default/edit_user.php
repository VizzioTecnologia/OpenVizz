
<div id="global-config-form-wrapper" class="form-wrapper tabs-wrapper">
	
	<div class="form-wrapper-sub tabs-children">
		
		<?= form_open( get_url( 'admin'.$this->uri->ruri_string() ), array( 'id' => 'user-form', ) ); ?>
			
			<div class="form-actions to-toolbar">
				
				<?= vui_el_button( array( 'text' => lang( 'action_save' ), 'icon' => 'save', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit', 'id' => 'submit', 'only_icon' => TRUE, 'form' => 'user-form', ) ); ?>
				
				<?= vui_el_button( array( 'text' => lang( 'action_apply' ), 'icon' => 'apply', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_apply', 'id' => 'submit-apply', 'only_icon' => TRUE, 'form' => 'user-form', ) ); ?>
				
				<?= vui_el_button( array( 'text' => lang( 'action_cancel' ), 'icon' => 'cancel', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_cancel', 'id' => 'submit-cancel', 'only_icon' => TRUE, 'form' => 'user-form', ) ); ?>
				
			</div>
			
			<header class="form-header tabs-header">
				
				<h1>
					
					<?= lang( 'edit_user' ); ?>
					
				</h1>
				
			</header>
			
			<div class="form-items tabs-items">
				
				<div class="form-item">
					
					<fieldset id="user-details">
						
						<legend>
							
							<?= vui_el_button( array( 'text' => lang( 'basic_details' ), 'icon' => 'basic-details',  ) ); ?>
							
						</legend>
						
						<?php echo form_error('username', '<div class="msg-inline-error">', '</div>'); ?>
						<?php echo form_label(lang('username')); ?>
						<?php echo form_input(array('id'=>'username','name'=>'username','disabled'=>'disabled'),set_value('username', $user->username)); ?>
						
						<?php echo form_error('name', '<div class="msg-inline-error">', '</div>'); ?>
						<?php echo form_label(lang('name')); ?>
						<?php echo form_input(array('id'=>'name','name'=>'name'),set_value('name', $user->name),'autofocus'); ?>
						
						<?php echo form_error('email', '<div class="msg-inline-error">', '</div>'); ?>
						<?php echo form_label(lang('email')); ?>
						<?php echo form_input(array('id'=>'email','name'=>'email','disabled'=>'disabled'),set_value('email', $user->email)); ?>
						
						<?php if ($users_groups){ ?>
						<?php echo form_error('group_id', '<div class="msg-inline-error">', '</div>'); ?>
						<?php echo form_label(lang('user_group')); ?>
						<?php
							foreach($users_groups as $row):
								$options[$row['id']] = $row['indented_title'];
							endforeach;
						?>
						<?php echo form_dropdown('group_id', $options, set_value('group_id', $user->group_id),'id="group-id"'); ?>
						<?php } else { ?>
						
						<?php echo form_label(lang('user_group')); ?>
						<?php echo form_input(array('id'=>'user-group','disabled'=>'disabled'),set_value('user_group', $user->user_group)); ?>
						
						<?php } ?>
						
						<?php echo form_error('password', '<div class="msg-inline-error">', '</div>'); ?>
						<?php echo form_label(lang('password')); ?>
						<?php echo form_password(array('id'=>'password','name'=>'password')); ?>
						
						<?php echo form_error('confirm_password', '<div class="msg-inline-error">', '</div>'); ?>
						<?php echo form_label(lang('confirm_password')); ?>
						<?php echo form_password(array('id'=>'confirm_password','name'=>'confirm_password')); ?>
						
						<?php echo form_hidden('user_id',base64_encode(base64_encode(base64_encode(base64_encode($user->id))))); ?>
						
					</fieldset>
					
					<fieldset id="user-params">
						
						<?php
						
						/* gerando o html dos parâmetros, ele deve ser chamado na view, não no controller,
						 * pois os erros de validação dos elementos dos parâmetros devem ser expostos
						 * após a chamada da função $this->form_validation->run()
						 */
						
						echo params_to_html( $params_spec, $final_params_values );
						
						?>
						
					</fieldset>
					
				</div>
				
			</div>
			
		<?= form_close(); ?>
		
	</div>
	
</div>

<?php if ( $this->plugins->load( 'yetii' ) ){ ?>

<script type="text/javascript" >
	
$( document ).ready(function(){
	
	/*************************************************/
	/**************** Criando as tabs ****************/
	
	makeTabs( $( '.tabs-wrapper' ), '#user-details, .params-set-wrapper', 'legend, .params-set-title' );
	
	/**************** Criando as tabs ****************/
	/*************************************************/
	
});

</script>

<?php } ?>
