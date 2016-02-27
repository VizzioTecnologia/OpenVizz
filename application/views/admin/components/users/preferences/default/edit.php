
		<header>
			<h1><?php echo lang($component_name); ?> - <?php echo lang('global_preferences'); ?></h1>
		</header>
		
		<div>
			
			<?= form_open( get_url( 'admin'.$this->uri->ruri_string() ), array( 'id' => 'user-component-config-form', ) ); ?>
				
				<div class="form-actions to-toolbar">
					
					<?= vui_el_button( array( 'text' => lang( 'action_save' ), 'icon' => 'save', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit', 'id' => 'submit', 'only_icon' => TRUE, 'form' => 'user-component-config-form', ) ); ?>
					
					<?= vui_el_button( array( 'text' => lang( 'action_apply' ), 'icon' => 'apply', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_apply', 'id' => 'submit-apply', 'only_icon' => TRUE, 'form' => 'user-component-config-form', ) ); ?>
					
					<?= vui_el_button( array( 'text' => lang( 'action_cancel' ), 'icon' => 'cancel', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_cancel', 'id' => 'submit-cancel', 'only_icon' => TRUE, 'form' => 'user-component-config-form', ) ); ?>
					
				</div>
				
				<fieldset>
					
					<legend><?php echo lang('parameters'); ?></legend>
					
					<?php echo parse_params($params, get_params($component->params)); ?>
					
					<?php echo form_hidden('component_id',$component->id); ?>
					
				</fieldset>
				
			<?php echo form_close(); ?>
			
		</div>

