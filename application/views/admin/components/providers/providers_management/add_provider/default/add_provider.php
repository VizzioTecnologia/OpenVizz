
		<header>
			<h1><?php echo lang('add_provider'); ?></h1>
		</header>
		
		<div>
			
			<?= form_open( get_url( 'admin'.$this->uri->ruri_string() ), array( 'id' => 'provider-form', ) ); ?>
				
				<div class="form-actions to-toolbar">
					
					<?= vui_el_button( array( 'text' => lang( 'action_save' ), 'icon' => 'save', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit', 'id' => 'submit', 'only_icon' => TRUE, 'form' => 'provider-form', ) ); ?>
					
					<?= vui_el_button( array( 'text' => lang( 'action_apply' ), 'icon' => 'apply', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_apply', 'id' => 'submit-apply', 'only_icon' => TRUE, 'form' => 'provider-form', ) ); ?>
					
					<?= vui_el_button( array( 'text' => lang( 'action_cancel' ), 'icon' => 'cancel', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_cancel', 'id' => 'submit-cancel', 'only_icon' => TRUE, 'form' => 'provider-form', ) ); ?>
					
				</div>
				
				<fieldset class="fl">
					
					<legend><?php echo lang('details'); ?></legend>
					
					<?php echo form_error('company_id', '<div class="msg-inline-error">', '</div>'); ?>
					<?php echo form_label(lang('company')); ?>
					<?php
						foreach($companies as $company):
							$options[$company['id']] = $company['trading_name'];
						endforeach;
					?>
					<?php echo form_dropdown('company_id', $options, set_value('company_id'),'id="company-id"'); ?>
					<?php $options = array(); ?>
					
					<?php echo form_error('title', '<div class="msg-inline-error">', '</div>'); ?>
					<?php echo form_label(lang('title')); ?>
					<?php echo form_input(array('id'=>'title','name'=>'title','title'=>lang('tip_provider_title')),set_value('title'),'autofocus'); ?>
					
					<?php echo form_error('default_provider_tax', '<div class="msg-inline-error">', '</div>'); ?>
					<?php echo form_label(lang('default_provider_tax')); ?>
					<?php echo form_input(array('id'=>'default-provider-tax','name'=>'default_provider_tax','title'=>lang('tip_default_provider_tax')),set_value('default_provider_tax',0)); ?>
					
				</fieldset>
				
				<div class="clear"></div>
				
			<?php echo form_close(); ?>
			
		</div>

