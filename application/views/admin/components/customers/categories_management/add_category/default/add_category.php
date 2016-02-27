
		<header>
			<h1><?php echo lang('new_category'); ?></h1>
		</header>
		
		<div>
			
			<?= form_open( get_url( 'admin'.$this->uri->ruri_string() ), array( 'id' => 'customer-category-form', ) ); ?>
				
				<div class="form-actions to-toolbar">
					
					<?= vui_el_button( array( 'text' => lang( 'action_save' ), 'icon' => 'save', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit', 'id' => 'submit', 'only_icon' => TRUE, 'form' => 'customer-category-form', ) ); ?>
					
					<?= vui_el_button( array( 'text' => lang( 'action_apply' ), 'icon' => 'apply', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_apply', 'id' => 'submit-apply', 'only_icon' => TRUE, 'form' => 'customer-category-form', ) ); ?>
					
					<?= vui_el_button( array( 'text' => lang( 'action_cancel' ), 'icon' => 'cancel', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_cancel', 'id' => 'submit-cancel', 'only_icon' => TRUE, 'form' => 'customer-category-form', ) ); ?>
					
				</div>
				
				<fieldset>
					
					<legend><?php echo lang('details'); ?></legend>
					
					<?php echo form_error('status', '<div class="msg-inline-error">', '</div>'); ?>
					<?php echo form_label(lang('status')); ?>
					<?php
						$options = array(
							0=>lang('unpublished'),
							1=>lang('published'),
						);
					?>
					<?php echo form_dropdown('status', $options, set_value('status', 1),'id="status"'); ?>
					
					<?php echo form_error('title', '<div class="msg-inline-error">', '</div>'); ?>
					<?php echo form_label(lang('title')); ?>
					<?php echo form_input(array('id'=>'title','name'=>'title'),set_value('title'),'autofocus'); ?>
					
					<?php echo form_error('alias', '<div class="msg-inline-error">', '</div>'); ?>
					<?php echo form_label(lang('alias')); ?>
					<?php echo form_input(array('id'=>'alias','name'=>'alias'),set_value('alias')); ?>
					
					<?php echo form_error('parent', '<div class="msg-inline-error">', '</div>'); ?>
					<?php echo form_label(lang('parent_category')); ?>
					<?php
						$options = array(
							0=>lang('root'),
						);
						foreach($categories as $row):
							$options[$row['id']] = $row['indented_title'];
						endforeach;
					?>
					<?php echo form_dropdown('parent', $options, set_value('parent', 0),'id="parent-menu-item"'); ?>
					
					<?php echo form_error('ordering', '<div class="msg-inline-error">', '</div>'); ?>
					<?php echo form_label(lang('ordering')); ?>
					<?php echo form_input(array('id'=>'ordering','name'=>'ordering'),set_value('ordering')); ?>
					
					<?php echo form_error('description', '<div class="msg-inline-error">', '</div>'); ?>
					<?php echo form_label(lang('description')); ?>
					<?php echo form_textarea(array('id'=>'description','name'=>'description'),set_value('description')); ?>
					
				</fieldset>
				
			<?php echo form_close(); ?>
			
		</div>

