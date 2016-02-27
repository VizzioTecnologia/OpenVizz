
		<header>
			<h1><?php echo lang('add_company'); ?></h1>
		</header>
		
		<div>
			
			<?php echo form_open_multipart(get_url('admin'.$this->uri->ruri_string())); ?>
				
				<div class="form-actions">
					
					<?php echo form_submit(array('id'=>'submit','name'=>'submit','class'=>'button button-save'),lang('action_save')); ?>
					<?php echo form_submit(array('id'=>'submit-apply','class'=>'button button-apply','name'=>'submit_apply'),lang('action_apply')); ?>
					<?php echo form_submit(array('id'=>'submit-cancel','class'=>'button button-cancel','name'=>'submit_cancel'),lang('action_cancel')); ?>
					
				</div>
				
				<fieldset class="fl">
					
					<legend><?php echo lang('logo'); ?></legend>
					
					<?php echo form_label(lang('logo_thumb')); ?>
					<div class="input-file-wrapper">
						<input type="file" name="logo_thumb" size="20" />
						<?php echo lang('choose_file'); ?>
					</div>
					
					<?php echo form_label(lang('logo')); ?>
					<div class="input-file-wrapper">
						<input type="file" name="logo" size="20" />
						<?php echo lang('choose_file'); ?>
					</div>
					
				</fieldset>
				
				<fieldset class="fl">
					
					<legend><?php echo lang('details'); ?></legend>
					
					<?php echo form_error('trading_name', '<div class="msg-inline-error">', '</div>'); ?>
					<?php echo form_label(lang('trading_name')); ?>
					<?php echo form_input(array('id'=>'trading-name','name'=>'trading_name'),isset($fields_array['trading_name'])?$fields_array['trading_name']:'','autofocus'); ?>
					
					<?php echo form_error('company_name', '<div class="msg-inline-error">', '</div>'); ?>
					<?php echo form_label(lang('company_name')); ?>
					<?php echo form_input(array('id'=>'company-name','name'=>'company_name', 'title'=>lang('company_name')),isset($fields_array['company_name'])?$fields_array['company_name']:''); ?>
					
					<?php echo form_error('state_registration', '<div class="msg-inline-error">', '</div>'); ?>
					<?php echo form_label(lang('state_registration')); ?>
					<?php echo form_input(array('id'=>'state-registration','name'=>'state_registration'),isset($fields_array['state_registration'])?$fields_array['state_registration']:''); ?>
					
					<?php echo form_error('sic', '<div class="msg-inline-error">', '</div>'); ?>
					<?php echo form_label(lang('sic')); ?>
					<?php echo form_input(array('id'=>'sic','name'=>'sic'),isset($fields_array['sic'])?$fields_array['sic']:''); ?>
					
					<?php echo form_error('corporate_tax_register', '<div class="msg-inline-error">', '</div>'); ?>
					<?php echo form_label(lang('corporate_tax_register')); ?>
					<?php echo form_input(array('id'=>'corporate-tax-register','name'=>'corporate_tax_register'),isset($fields_array['corporate_tax_register'])?$fields_array['corporate_tax_register']:''); ?>
					
					<?php echo form_error('foundation_date', '<div class="msg-inline-error">', '</div>'); ?>
					<?php echo form_label(lang('foundation_date')); ?>
					<?php echo form_input(array('id'=>'foundation-date','name'=>'foundation_date'),isset($fields_array['foundation_date'])?$fields_array['foundation_date']:''); ?>
					
				</fieldset>
				
				<fieldset class="fl">
					
					<legend><?php echo lang('contacts'); ?></legend>
					
					<?php foreach ($contacts_fields_array as $contact_field) { ?>
					
					<div class="contacts-fields-wrapper">
						
						<?php echo form_error('contact_'.$contact_field['id'], '<div class="msg-inline-error">', '</div>'); ?>
						<div class="vui-field-wrapper-inline">
							<?php echo form_label(lang('contact_type')); ?>
							<?php echo form_input(array('id'=>'contact-type-'.$contact_field['id'],'name'=>'contact_type_'.$contact_field['id'],'class'=>'contact-type', 'title'=>lang('tip_contact_type')),$contact_field['type'] != ''?$contact_field['type']:lang('contact').' '.$contact_field['id']); ?>
						</div>
						
						<div class="vui-field-wrapper-inline">
							
							<?php echo form_label(lang('contact')); ?>
							
							<?php if ($contacts){ ?>
							
							<?php
								$options[0] = lang('select');
								foreach($contacts as $row):
									$options[$row['id']] = $row['name'];
								endforeach;
							?>
							<?php echo form_dropdown('contact_'.$contact_field['id'], $options, $contact_field['value'],'id="'.'contact-'.$contact_field['id'].'"'); ?>
							
							<?php } ?>
							<?php echo form_submit(array('id'=>'submit-remove-contact-'.$contact_field['id'],'name'=>'submit_remove_contact_'.$contact_field['id'], 'class'=>'btn btn-delete', 'title'=>lang('tip_remove_contact')),lang('remove_contact')); ?>
							
						</div>
						
					</div>
					
					<?php } ?>
					
					<?php echo form_label(lang('add_contact')); ?>
					<?php echo form_label(lang('enter_amount_contacts')); ?>
					<?php echo form_input(array('id'=>'contact-fields-to-add','name'=>'contact_fields_to_add','class'=>'add-num-contact', 'title'=>lang('tip_enter_amount_contacts')),1); ?>
					<?php echo form_submit(array('id'=>'submit-add-contact', 'class'=>'btn btn-add','name'=>'submit_add_contact'),lang('add')); ?>
					
				</fieldset>
				
				<fieldset class="fl">
					
					<legend><?php echo lang('phones'); ?></legend>
					
					<?php foreach ($phone_fields_array as $phone_field) { ?>
					
					<div class="phones-fields-wrapper">
						
						<?php echo form_error('phone_'.$phone_field['id'], '<div class="msg-inline-error">', '</div>'); ?>
						<div class="vui-field-wrapper-inline">
							<?php echo form_label(lang('phone_type')); ?>
							<?php echo form_input(array('id'=>'phone-type-'.$phone_field['id'],'name'=>'phone_type_'.$phone_field['id'],'class'=>'phone-type', 'title'=>lang('tip_phone_type')),$phone_field['type'] != ''?$phone_field['type']:lang('phone').' '.$phone_field['id']); ?>
						</div>
						
						<div class="vui-field-wrapper-inline">
							<?php echo form_label(lang('phone_area_code')); ?>
							<?php echo form_input(array('id'=>'phone-ddd-'.$phone_field['id'],'name'=>'phone_ddd_'.$phone_field['id'],'class'=>'phone-area-code', 'maxlength'=>'3', 'size'=>'3'),$phone_field['ddd']); ?>
						</div>
						
						<div class="vui-field-wrapper-inline">
							<?php echo form_label(lang('phone_number')); ?>
							<?php echo form_input(array('id'=>'phone-'.$phone_field['id'],'name'=>'phone_'.$phone_field['id'],'class'=>'phone-number'),$phone_field['value']); ?>
							<?php echo form_submit(array('id'=>'submit-add-phone-'.$phone_field['id'],'name'=>'submit_remove_phone_'.$phone_field['id'], 'title'=>lang('tip_remove_phone'), 'class'=>'btn btn-delete'),lang('remove_phone')); ?>
							
						</div>
						
					</div>
					
					<?php } ?>
					
					<?php echo form_label(lang('add_phone')); ?>
					<?php echo form_label(lang('enter_amount_phones')); ?>
					<?php echo form_input(array('id'=>'phone-fields-to-add','name'=>'phone_fields_to_add','class'=>'add-num-phone', 'title'=>lang('tip_enter_amount_phones')),1); ?>
					<?php echo form_submit(array('id'=>'submit-add-phone', 'class'=>'btn btn-add','name'=>'submit_add_phone'),lang('add')); ?>
					
				</fieldset>
				
				<fieldset class="fl">
					
					<legend><?php echo lang('addresses'); ?></legend>
					
					<?php foreach ($address_fields_array as $address_field) { ?>
					
					<div class="addresses-fields-wrapper dinamic-fields-wrapper">
						
						<?php echo form_error('address_'.$address_field['id'], '<div class="msg-inline-error">', '</div>'); ?>
						<?php echo form_hidden('address_'.$address_field['id'],$address_field['id']); ?>
							
						<div class="vui-field-wrapper-inline">
							<?php echo form_label(lang('title')); ?>
							<?php echo form_input(array('id'=>'address-type-'.$address_field['id'],'name'=>'address_type_'.$address_field['id'],'class'=>'address-type', 'title'=>lang('tip_address_type')),$address_field['type'] != ''?$address_field['type']:lang('address').' '.$address_field['id']); ?>
						</div>
						
						<br/>
						
						<div class="vui-field-wrapper-inline">
							<?php echo form_label(lang('country')); ?>
							<?php echo form_hidden('address_country_id_'.$address_field['id'],$address_field['country_id']); ?>
							<?php echo form_hidden('address_country_alias_'.$address_field['id'],$address_field['country_alias']); ?>
							<?php echo form_input(array('id'=>'address-country-title-'.$address_field['id'],'name'=>'address_country_title_'.$address_field['id'],'class'=>'address-country-title', 'title'=>lang('tip_address_country_title')),lang($address_field['country_title'])); ?>
						</div>
						
						<div class="vui-field-wrapper-inline">
							<?php echo form_label(lang('state')); ?>
							<?php echo form_hidden('address_state_id_'.$address_field['id'],$address_field['state_id']); ?>
							<?php echo form_input(array('id'=>'address-state-acronym-'.$address_field['id'],'name'=>'address_state_acronym_'.$address_field['id'],'class'=>'address-state-acronym', 'title'=>lang('tip_address_state_acronym')),$address_field['state_acronym']); ?>
						</div>
						
						<div class="vui-field-wrapper-inline">
							<?php echo form_label(lang('city')); ?>
							<?php echo form_hidden('address_city_id_'.$address_field['id'],$address_field['city_id']); ?>
							<?php echo form_input(array('id'=>'address-city-title-'.$address_field['id'],'name'=>'address_city_title_'.$address_field['id'],'class'=>'address-city-title', 'title'=>lang('tip_address_city_title')),$address_field['city_title']); ?>
						</div>
						
						<div class="vui-field-wrapper-inline">
							<?php echo form_label(lang('neighborhood')); ?>
							<?php echo form_hidden('address_neighborhood_id_'.$address_field['id'],$address_field['neighborhood_id']); ?>
							<?php echo form_input(array('id'=>'address-neighborhood-title-'.$address_field['id'],'name'=>'address_neighborhood_title_'.$address_field['id'],'class'=>'address-neighborhood-title', 'title'=>lang('tip_address_neighborhood_title')),$address_field['neighborhood_title']); ?>
						</div>
						
						<br/>
						
						<div class="vui-field-wrapper-inline">
							<?php echo form_label(lang('public_area')); ?>
							<?php echo form_hidden('address_public_area_id_'.$address_field['id'],$address_field['public_area_id']); ?>
							<?php echo form_input(array('id'=>'address-public-area-title-'.$address_field['id'],'name'=>'address_public_area_title_'.$address_field['id'],'class'=>'address-public-area-title', 'title'=>lang('tip_address_public_area_title')),$address_field['public_area_title']); ?>
						</div>
						
						<div class="vui-field-wrapper-inline">
							<?php echo form_label(lang('postal_code')); ?>
							<?php echo form_input(array('id'=>'address-postal-code-'.$address_field['id'],'name'=>'address_postal_code_'.$address_field['id'],'class'=>'address-postal-code', 'title'=>lang('tip_address_postal_code')),$address_field['postal_code']); ?>
						</div>
						
						<div class="vui-field-wrapper-inline">
							<?php echo form_label(lang('number')); ?>
							<?php echo form_input(array('id'=>'address-number-'.$address_field['id'],'name'=>'address_number_'.$address_field['id'],'class'=>'address-number', 'title'=>lang('tip_address_number')),$address_field['number']); ?>
						</div>
						
						<br/>
						
						<div class="vui-field-wrapper-inline">
							<?php echo form_label(lang('complement')); ?>
							<?php echo form_input(array('id'=>'address-complement-'.$address_field['id'],'name'=>'address_complement_'.$address_field['id'],'class'=>'address-complement', 'title'=>lang('tip_address_complement')),$address_field['complement']); ?>
						</div>
						
						<br/>
						
						<div class="field-wrapper">
							<?php echo form_submit(array('id'=>'submit-remove-address-'.$address_field['id'],'name'=>'submit_remove_address_'.$address_field['id'], 'class'=>'button', 'title'=>lang('tip_remove_address')),lang('remove_address')); ?>
						</div>
						
					</div>
					
					<?php } ?>
					
					<div class="field-wrapper ta-right">
						<?php echo form_submit(array('id'=>'submit-add-address', 'class'=>'button','name'=>'submit_add_address'),lang('add_address')); ?>
					</div>
					
				</fieldset>
				
				<div class="clear"></div>
				
				<div class="form-actions">
					
					<?php echo form_submit(array('id'=>'submit','name'=>'submit','class'=>'button button-save'),lang('action_save')); ?>
					<?php echo form_submit(array('id'=>'submit-apply','class'=>'button button-apply','name'=>'submit_apply'),lang('action_apply')); ?>
					<?php echo form_submit(array('id'=>'submit-cancel','class'=>'button button-cancel','name'=>'submit_cancel'),lang('action_cancel')); ?>
					
				</div>
				
			<?php echo form_close(); ?>
			
			<script type="text/javascript" >
			
			$(document).ready(function(){
				
				$(".address-postal-code").mask("99.999-999");
				
			});
			</script>
			
		</div>
		
		

