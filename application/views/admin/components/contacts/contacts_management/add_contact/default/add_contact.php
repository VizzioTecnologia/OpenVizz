
		<header>
			<h1><?php echo lang('new_contact'); ?></h1>
		</header>
		
		<div>
			
			<?php echo form_open(get_url('admin'.$this->uri->ruri_string())); ?>
				
				<div class="form-actions">
					
					<?php echo form_submit(array('id'=>'submit','name'=>'submit','class'=>'button button-save'),lang('action_save')); ?>
					<?php echo form_submit(array('id'=>'submit-apply','class'=>'button button-apply','name'=>'submit_apply'),lang('action_apply')); ?>
					<?php echo form_submit(array('id'=>'submit-cancel','class'=>'button button-cancel','name'=>'submit_cancel'),lang('action_cancel')); ?>
					
				</div>
				
				<fieldset class="fl">
					
					<legend><?php echo lang('photo'); ?></legend>
					
					<?php echo form_label(lang('image_thumb')); ?>
					<div class="input-file-wrapper">
						<input type="file" name="image_thumb" size="20" />
						<?php echo lang('choose_file'); ?>
					</div>
					
					<?php echo form_label(lang('image_big')); ?>
					<div class="input-file-wrapper">
						<input type="file" name="image_big" size="20" />
						<?php echo lang('choose_file'); ?>
					</div>
					
				</fieldset>
				
				<fieldset class="fl">
					
					<legend><?php echo lang('details'); ?></legend>
					
					<?php echo form_error('name', '<div class="msg-inline-error">', '</div>'); ?>
					<?php echo form_label(lang('name')); ?>
					<?php echo form_input(array('id'=>'name','name'=>'name'),isset($fields_array['field_name'])?$fields_array['field_name']['value']:'','autofocus'); ?>
					
					<?php echo form_error('birthday_date', '<div class="msg-inline-error">', '</div>'); ?>
					<?php echo form_label(lang('birthday_date')); ?>
					<?php echo form_input(array('id'=>'birthday-date','name'=>'birthday_date', 'title'=>lang('tip_birthday_date')),isset($fields_array['field_birthday_date'])?$fields_array['field_birthday_date']['value']:''); ?>
					
				</fieldset>
				
				<fieldset class="fl">
					
					<legend><?php echo lang('emails'); ?></legend>
					
					<?php foreach ($emails_fields_array as $email_field) { ?>
					
					<div class="emails-fields-wrapper">
						
						<?php echo form_error('email_'.$email_field['id'], '<div class="msg-inline-error">', '</div>'); ?>
						<?php echo form_label(lang('email').' '.$email_field['id']); ?>
						<div class="vui-field-wrapper-inline">
							<?php echo form_label(lang('email_type')); ?>
							<?php echo form_input(array('id'=>'email-type-'.$email_field['id'],'name'=>'email_type_'.$email_field['id'],'class'=>'email-type', 'title'=>lang('tip_email_type')),$email_field['type']); ?>
						</div>
						
						<div class="vui-field-wrapper-inline">
							<?php echo form_label(lang('email')); ?>
							<?php echo form_input(array('id'=>'email-'.$email_field['id'],'name'=>'email_'.$email_field['id'],'class'=>'dynamic-email'),$email_field['value']); ?>
							<?php echo form_submit(array('id'=>'submit-add-email-'.$email_field['id'],'name'=>'submit_remove_email_'.$email_field['id'], 'title'=>lang('tip_remove_email')),lang('remove_email')); ?>
						</div>
						
					</div>
					
					<?php } ?>
					
					<?php echo form_label(lang('add_email')); ?>
					<?php echo form_label(lang('enter_amount_emails')); ?>
					<?php echo form_input(array('id'=>'email-fields-to-add','name'=>'email_fields_to_add','class'=>'add-num-email', 'title'=>lang('tip_enter_amount_emails')),1); ?>
					<?php echo form_submit(array('id'=>'submit-add-email','name'=>'submit_add_email'),lang('add')); ?>
					
				</fieldset>
				
				<fieldset class="fl">
					
					<legend><?php echo lang('phones'); ?></legend>
					
					<?php foreach ($phone_fields_array as $phone_field) { ?>
					
					<div class="phones-fields-wrapper">
						
						<?php echo form_error('phone_'.$phone_field['id'], '<div class="msg-inline-error">', '</div>'); ?>
						<?php echo form_label(lang('phone').' '.$phone_field['id']); ?>
						<div class="vui-field-wrapper-inline">
							<?php echo form_label(lang('phone_type')); ?>
							<?php echo form_input(array('id'=>'phone-type-'.$phone_field['id'],'name'=>'phone_type_'.$phone_field['id'],'class'=>'phone-type', 'title'=>lang('tip_phone_type')),$phone_field['type']); ?>
						</div>
						
						<div class="vui-field-wrapper-inline">
							<?php echo form_label(lang('phone_area_code')); ?>
							<?php echo form_input(array('id'=>'phone-ddd-'.$phone_field['id'],'name'=>'phone_ddd_'.$phone_field['id'],'class'=>'phone-area-code', 'maxlength'=>'3', 'size'=>'3'),$phone_field['ddd']); ?>
						</div>
						
						<div class="vui-field-wrapper-inline">
							<?php echo form_label(lang('phone_number')); ?>
							<?php echo form_input(array('id'=>'phone-'.$phone_field['id'],'name'=>'phone_'.$phone_field['id'],'class'=>'phone-number'),$phone_field['value']); ?>
							<?php echo form_submit(array('id'=>'submit-add-phone-'.$phone_field['id'],'name'=>'submit_remove_phone_'.$phone_field['id'], 'title'=>lang('tip_remove_phone')),lang('remove_phone')); ?>
							
						</div>
						
					</div>
					
					<?php } ?>
					
					<?php echo form_label(lang('add_phone')); ?>
					<?php echo form_label(lang('enter_amount_phones')); ?>
					<?php echo form_input(array('id'=>'phone-fields-to-add','name'=>'phone_fields_to_add','class'=>'add-num-phone', 'title'=>lang('tip_enter_amount_phones')),1); ?>
					<?php echo form_submit(array('id'=>'submit-add-phone','name'=>'submit_add_phone'),lang('add')); ?>
					
				</fieldset>
				
				<div class="clear"></div>
				
				<div class="form-actions">
					
					<?php echo form_submit(array('id'=>'submit','name'=>'submit','class'=>'button button-save'),lang('action_save')); ?>
					<?php echo form_submit(array('id'=>'submit-apply','class'=>'button button-apply','name'=>'submit_apply'),lang('action_apply')); ?>
					<?php echo form_submit(array('id'=>'submit-cancel','class'=>'button button-cancel','name'=>'submit_cancel'),lang('action_cancel')); ?>
					
				</div>
				
			<?php echo form_close(); ?>
			
		</div>

