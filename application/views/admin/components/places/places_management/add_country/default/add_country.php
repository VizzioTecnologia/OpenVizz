
		<header>
			<h1><?php echo lang('add_country'); ?></h1>
		</header>
		
		<div>
			
			<?php echo form_open(get_url('admin'.$this->uri->ruri_string())); ?>
				
				<div class="form-actions">
					
					<?php echo form_submit(array('id'=>'submit','name'=>'submit','class'=>'button button-save'),lang('action_save')); ?>
					<?php echo form_submit(array('id'=>'submit-apply','class'=>'button button-apply','name'=>'submit_apply'),lang('action_apply')); ?>
					<?php echo form_submit(array('id'=>'submit-cancel','class'=>'button button-cancel','name'=>'submit_cancel'),lang('action_cancel')); ?>
					
				</div>
				
				<fieldset class="fl">
					
					<legend><?php echo lang('details'); ?></legend>
					
					<?php echo form_error('status', '<div class="msg-inline-error">', '</div>'); ?>
					<?php echo form_label(lang('status')); ?>
					<?php
						$options = array(
							1=>lang('unpublished'),
							2=>lang('published'),
						);
					?>
					<?php echo form_dropdown('status', $options, set_value('status', 2),'id="status"'); ?>
					<?php $options = array(); ?>
					
					<?php echo form_error('title', '<div class="msg-inline-error">', '</div>'); ?>
					<?php echo form_label(lang('title')); ?>
					<?php echo form_input(array('id'=>'title','name'=>'title'),set_value('title'),'autofocus'); ?>
					
					<?php echo form_error('alias', '<div class="msg-inline-error">', '</div>'); ?>
					<?php echo form_label(lang('alias')); ?>
					<?php echo form_input(array('id'=>'alias','name'=>'alias'),set_value('alias')); ?>
					
				</fieldset>
				
				<div class="clear"></div>
				
				<div class="form-actions">
					
					<?php echo form_submit(array('id'=>'submit','name'=>'submit','class'=>'button button-save'),lang('action_save')); ?>
					<?php echo form_submit(array('id'=>'submit-apply','class'=>'button button-apply','name'=>'submit_apply'),lang('action_apply')); ?>
					<?php echo form_submit(array('id'=>'submit-cancel','class'=>'button button-cancel','name'=>'submit_cancel'),lang('action_cancel')); ?>
					
				</div>
				
			<?php echo form_close(); ?>
			
		</div>

