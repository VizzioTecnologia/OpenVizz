
		<header>
			<h1><?php echo lang('add_customer'); ?></h1>
		</header>
		
		<div>
			
			<?= form_open( get_url( 'admin'.$this->uri->ruri_string() ), array( 'id' => 'customer-form', ) ); ?>
				
				<div class="form-actions to-toolbar">
					
					<?= vui_el_button( array( 'text' => lang( 'action_save' ), 'icon' => 'save', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit', 'id' => 'submit', 'only_icon' => TRUE, 'form' => 'customer-form', ) ); ?>
					
					<?= vui_el_button( array( 'text' => lang( 'action_apply' ), 'icon' => 'apply', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_apply', 'id' => 'submit-apply', 'only_icon' => TRUE, 'form' => 'customer-form', ) ); ?>
					
					<?= vui_el_button( array( 'text' => lang( 'action_cancel' ), 'icon' => 'cancel', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_cancel', 'id' => 'submit-cancel', 'only_icon' => TRUE, 'form' => 'customer-form', ) ); ?>
					
				</div>
				
				<fieldset class="fl">
					
					<legend><?php echo lang('details'); ?></legend>
					
					<?php echo form_error('title', '<div class="msg-inline-error">', '</div>'); ?>
					<?php echo form_label(lang('title')); ?>
					<?php echo form_input(array('id'=>'title','name'=>'title','title'=>lang('tip_provider_title')),set_value('title'),'autofocus'); ?>
					
					<?php echo form_error('company_id', '<div class="msg-inline-error">', '</div>'); ?>
					<?php echo form_label(lang('company')); ?>
					<?php
						$options[''] = lang('combobox_select_company');
						foreach($companies as $company):
							$options[$company['id']] = $company['trading_name'];
						endforeach;
					?>
					<?php echo form_dropdown('company_id', $options, set_value('company_id'),'id="company-id"'); ?>
					<?php $options = array(); ?>
					
					<?php echo form_error('contact_id', '<div class="msg-inline-error">', '</div>'); ?>
					<?php echo form_label(lang('contact')); ?>
					<?php
						$options[''] = lang('combobox_select_contact');
						foreach($contacts as $contact):
							$options[$contact['id']] = $contact['name'];
						endforeach;
					?>
					<?php echo form_dropdown('contact_id', $options, set_value('contact_id'),'id="contact-id"'); ?>
					<?php $options = array(); ?>
					
					<?php echo form_error('category_id', '<div class="msg-inline-error">', '</div>'); ?>
					<?php echo form_label(lang('category')); ?>
					<?php
						$options = array(
							0=>lang('uncategorized'),
						);
						foreach($categories as $category):
							$options[$category['id']] = $category['indented_title'];
						endforeach;
					?>
					<?php echo form_dropdown('category_id', $options, set_value('category_id', 0),'id="category-id"'); ?>
					<?php $options = array(); ?>
					
				</fieldset>
				
				<div class="clear"></div>
				
			<?php echo form_close(); ?>
			
		</div>

