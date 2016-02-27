
		<header>
			<h1><?php echo lang('edit_menu_type'); ?></h1>
		</header>
		
		<div>
			
			<?= form_open( get_url( 'admin'.$this->uri->ruri_string() ), array( 'id' => 'menu-type-form', ) ); ?>
				
				<div class="form-actions to-toolbar">
					
					<?= vui_el_button( array( 'text' => lang( 'action_save' ), 'icon' => 'save', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit', 'id' => 'submit', 'only_icon' => TRUE, 'form' => 'menu-type-form', ) ); ?>
					
					<?= vui_el_button( array( 'text' => lang( 'action_apply' ), 'icon' => 'apply', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_apply', 'id' => 'submit-apply', 'only_icon' => TRUE, 'form' => 'menu-type-form', ) ); ?>
					
					<?= vui_el_button( array( 'text' => lang( 'action_cancel' ), 'icon' => 'cancel', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_cancel', 'id' => 'submit-cancel', 'only_icon' => TRUE, 'form' => 'menu-type-form', ) ); ?>
					
				</div>
				
				<fieldset>
					
					<legend><?php echo lang('configuration'); ?></legend>
					
					<?php echo form_error('title', '<div class="msg-inline-error">', '</div>'); ?>
					<?php echo form_label(lang('title')); ?>
					<?php echo form_input(array('id'=>'title','name'=>'title'),set_value('title', $menu_type->title),'autofocus'); ?>
					
					<?php echo form_error('alias', '<div class="msg-inline-error">', '</div>'); ?>
					<?php echo form_label(lang('alias')); ?>
					<?php echo form_input(array('id'=>'alias','name'=>'alias'),set_value('alias', $menu_type->alias)); ?>
					
					<?php echo form_error('description', '<div class="msg-inline-error">', '</div>'); ?>
					<?php echo form_label(lang('description')); ?>
					<?php echo form_textarea(array('id'=>'description','name'=>'description'),set_value('description', $menu_type->description)); ?>
					
					<?php echo form_hidden('menu_type_id',$menu_type_id); ?>
					
				</fieldset>
				
			<?php echo form_close(); ?>
			
		</div>

