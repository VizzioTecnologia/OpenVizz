
<div id="company-form-wrapper" class="form-wrapper tabs-wrapper">
	
	<div class="form-wrapper-sub tabs-children">
		
		<?= form_open_multipart( get_url( 'admin'.$this->uri->ruri_string() ), array( 'id' => 'company-form', ) ); ?>
			
			<div class="form-actions to-toolbar">
				
				<?= vui_el_button( array( 'text' => lang( 'action_save' ), 'icon' => 'save', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit', 'id' => 'submit', 'only_icon' => TRUE, 'form' => 'company-form', ) ); ?>
				
				<?= vui_el_button( array( 'text' => lang( 'action_apply' ), 'icon' => 'apply', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_apply', 'id' => 'submit-apply', 'only_icon' => TRUE, 'form' => 'company-form', ) ); ?>
				
				<?= vui_el_button( array( 'text' => lang( 'action_cancel' ), 'icon' => 'cancel', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_cancel', 'id' => 'submit-cancel', 'only_icon' => TRUE, 'form' => 'company-form', ) ); ?>
				
			</div>
			
			<header class="form-header tabs-header">
				
				<?php if ( $f_action == 'add_company' ) { ?>
					
				<h1><?= lang('add_company'); ?></h1>
				
				<?php } else if ( $f_action == 'edit_company' ) { ?>
					
				<h1><?= lang('edit_company'); ?></h1>
				
				<?php } ?>
				
			</header>
			
			<div class="form-items tabs-items">
				
				<div class="form-item">
					
					<fieldset>
						
						<legend>
							
							<?= vui_el_button( array( 'text' => lang( 'basic_details' ), 'icon' => 'basic-details',  ) ); ?>
							
						</legend>
						
						<?php if ( $component_function_action == 'add_company' ) { ?>
							
							<?= form_hidden( 'company_image_path', $company_image_path ); ?>
							
						<?php } ?>
						
						<div id="company-thumb-wrapper" class="vui-field-wrapper-inline">
							
							<?= form_hidden( 'logo_thumb', ( isset( $company[ 'logo_thumb' ] ) ? $company[ 'logo_thumb' ] : '' ) ); ?>
							
						</div>
						
						<div id="company-logo-wrapper" class="vui-field-wrapper-inline">
							
							<?php
								
								$field_name = 'logo';
								$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
								$field_attr = 'id = "company-' . $field_name . '" name="' . $field_name . '" class="' . $field_name . ' ' . ( $field_error ? 'field-error' : '' ) . '"' . ( $field_error ? element_title( $field_error ) : '' );
								
							?>
							
							<div class="company-logo-wrapper edit-page">
								
								<?= anchor( $company['logo_thumb'], img( array( 'class' => 'company-image-thumb', 'src' => $company[ $field_name ], 'height' => 120 ) ),'target="_blank" class="company-image-thumb-link" title="'.lang('action_view').'"'); ?>
							
							</div>
							
							<?= form_label( lang( $field_name ) ); ?>
							<?= form_input( $field_name, ( isset( $company[ $field_name ] ) ? $company[ $field_name ] : '' ), $field_attr ); ?>
							
							<?php
								
								unset( $field_name );
								unset( $field_error );
								unset( $field_attr );
								
							?>
							
						</div>
						
						<?= form_error('trading_name', '<div class="msg-inline-error">', '</div>'); ?>
						<?= form_label(lang('trading_name')); ?>
						<?= form_input(array('id'=>'trading-name','name'=>'trading_name', ),isset($company['trading_name'])?$company['trading_name']:'','autofocus'); ?>
						
						<?= form_error('company_name', '<div class="msg-inline-error">', '</div>'); ?>
						<?= form_label(lang('company_name')); ?>
						<?= form_input(array('id'=>'company-name','name'=>'company_name', 'title'=>lang('company_name')),isset($company['company_name'])?$company['company_name']:''); ?>
						
						<?= form_error('state_registration', '<div class="msg-inline-error">', '</div>'); ?>
						<?= form_label(lang('state_registration')); ?>
						<?= form_input(array('id'=>'state-registration','name'=>'state_registration'),isset($company['state_registration'])?$company['state_registration']:''); ?>
						
						<?= form_error('sic', '<div class="msg-inline-error">', '</div>'); ?>
						<?= form_label(lang('sic')); ?>
						<?= form_input(array('id'=>'sic','name'=>'sic'),isset($company['sic'])?$company['sic']:''); ?>
						
						<?= form_error('corporate_tax_register', '<div class="msg-inline-error">', '</div>'); ?>
						<?= form_label(lang('corporate_tax_register')); ?>
						<?= form_input(array('id'=>'corporate-tax-register','name'=>'corporate_tax_register'),isset($company['corporate_tax_register'])?$company['corporate_tax_register']:''); ?>
						
						<?= form_error('foundation_date', '<div class="msg-inline-error">', '</div>'); ?>
						<?= form_label(lang('foundation_date')); ?>
						<?= form_input(array('id'=>'foundation-date','name'=>'foundation_date', 'class' => 'date'),isset($company['foundation_date'])?$company['foundation_date']:''); ?>
						
					</fieldset>
					
				</div>
				
				<div class="form-item">
					
					<fieldset>
						
						<legend>
							
							<?= vui_el_button( array( 'text' => lang( 'contacts' ), 'icon' => 'contacts',  ) ); ?>
							
						</legend>
						
						<?php if ( isset( $company[ 'contacts' ] ) AND is_array( $company[ 'contacts' ] ) ) { ?>
						
						<div id="contacts-wrapper">
							
							<?php foreach ( $company[ 'contacts' ] as $key => $contact ) { ?>
							
							<div class="contact-item-wrapper" <?= ( @$contact[ 'id' ] ? 'data-contact-id="' . $contact[ 'id' ] . '"' : '' ); ?> data-contactkey="<?= $key; ?>">
								
								<?= ( @$contact[ 'id' ] ? '<a rel="companies-contacts" href="' . ( get_url( 'admin/contacts/contacts_management/edit_contact/' . $contact[ 'id' ] ) ) . '"' : '<span' ); ?> class="modal-contact contact-item" <?= ( @$contact[ 'id' ] ? 'data-mc-action="get" data-contact-id="' . $contact[ 'id' ] . '"' : '' ); ?> target="_blank" <?= ( @$contact[ 'name' ] ? 'title="' . strip_tags( $contact[ 'name' ] ) . '"' : '' ); ?>>
									
									<span class="contact-thumb-wrapper">
										
										<span class="contact-thumb-content">
											
											<?php if ( @$contact[ 'thumb_local' ] AND @$contact[ 'id' ] ){ ?>
											
											<?= img( array( 'src' => $contact[ 'thumb_local' ], 'width' => 24 ) ); ?>
											
											<?php } ?>
											
										</span>
										
									</span>
									
									<span class="contact-name-wrapper">
										
										<span class="contact-name-content">
											
											<?= ( @$contact[ 'name' ] ? $contact[ 'name' ] : lang( 'select_contact_and_save_to_see_the_changes' ) ); ?>
											
										</span>
										
									</span>
									
								<?= ( @$contact[ 'id' ] ? '</a>' : '</span>' ); ?>
								
								<div class="contact-company-title-field-wrapper">
									
									<?= form_error( 'contacts[' . $key . '][title]', '<div class="msg-inline-error">', '</div>' ); ?>
									<?= form_input( array( 'id' => 'contact-title-' . $key , 'name' => 'contacts[' . $key . '][title]', 'class' => 'contact-title-field', 'title' => lang( 'tip_company_contact_title' ) ), isset( $contact[ 'title' ] ) ? $contact[ 'title' ] : lang( 'contact' ) . ' ' . $key ); ?>
									
								</div>
								
								<div class="contact-company-contact-field-wrapper">
									
									<?= form_error( 'contacts[' . $key . '][contact]', '<div class="msg-inline-error">', '</div>' ); ?>
									
									<?php if ( $contacts ){ ?>
									
									<?php
										
										$options[ '' ] = lang( 'select' );
										
										foreach( $contacts as $row ):
											
											$options[ $row[ 'id' ] ] = $row[ 'name' ];
											
										endforeach;
										
									?>
									<?= form_dropdown( 'contacts[' . $key . '][id]', $options, @$contact[ 'id' ] ? $contact[ 'id' ] : 0, 'id="' . 'contact-id-' . $key . '" class="contact-id contact-company-contact-field"' ); ?>
									
									<?php } ?>
									
								</div>
								
								<div class="contact-delete-button-wrapper">
									
									<?php if ( $key > 1 OR $f_action == 'edit_company' ) { ?>
									
									<?= vui_el_button( array( 'text' => lang( 'remove_contact' ), 'icon' => 'cancel', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_remove_contact[' . $key . ']', 'id' => 'submit-remove-contact-' . $key, 'class' => 'contact-delete-button', 'only_icon' => TRUE, 'form' => 'company-form', ) ); ?>
									
									<?php } ?>
									
								</div>
								
								<?= form_hidden( 'contacts[' . $key . '][key]', $contact[ 'key' ] ); ?>
								
							</div>
							
							<?php } ?>
							
						</div>
						
						<?php } ?>
						
						<div class="clear"></div>
						
						<div class="add-contact-controls-wrapper">
							
							<?= form_label(lang('add_contact')); ?>
							<?= form_label(lang('enter_amount_contacts')); ?>
							
							<?= form_input(array('id'=>'contact-fields-to-add','name'=>'contact_fields_to_add','class'=>'add-num-contact', 'title'=>lang('tip_enter_amount_contacts')),1); ?>
							<?= form_submit(array('id'=>'submit-add-contact', 'class'=>'btn btn-add','name'=>'submit_add_contact'),lang('add')); ?>
							
						</div>
						
						<?= form_label( lang( 'add_contact' ) ); ?>
						<?= form_input( array( 'id' => 'live-search-contact', 'placeholder' => 'Digite um nome para começar', 'name' => 'live-search-contact', 'class' => 'live-search live-search-contact' ) ); ?>
						
					</fieldset>
					
				</div>
				
				<div class="form-item">
					
					<fieldset>
						
						<legend>
							
							<?= vui_el_button( array( 'text' => lang( 'emails' ), 'icon' => 'emails',  ) ); ?>
							
						</legend>
						
						<?php if ( isset($company['emails']) AND is_array($company['emails']) ) { ?>
						<?php foreach ($company['emails'] as $key => $email) { ?>
						
						<div class="emails-fields-wrapper">
							
							<?= form_hidden('emails['.$key.'][key]',$email['key']); ?>
							
							<?php echo form_error('email_'.$key, '<div class="msg-inline-error">', '</div>'); ?>
							<div class="vui-field-wrapper-inline">
								<?php echo form_label(lang('email_type')); ?>
								<?php echo form_input(array('id'=>'email-title-'.$key, 'name'=>'emails['.$key.'][title]', 'class'=>'email-title', 'title'=>lang('tip_email_title')), isset($email['title']) ? $email['title'] : lang('email').' '.$key ); ?>
							</div>
							
							<div class="vui-field-wrapper-inline">
								<?php echo form_label(lang('email')); ?>
								<?php echo form_input(array('id'=>'email-'.$key, 'name'=>'emails['.$key.'][email]', 'class'=>'email'), isset($email['email']) ? $email['email'] : '' ); ?>
								
								<?php if ( $key > 1 OR $component_function_action == 'edit_company' ) { ?>
								
								<?php echo form_submit(array('id'=>'submit-add-email-'.$key,'name'=>'submit_remove_email['.$key.']', 'class'=>'btn btn-delete', 'title'=>lang('tip_remove_email')),lang('remove_email')); ?>
								
								<?php } ?>
								
							</div>
							
						</div>
						
						<?php } ?>
						<?php } ?>
						
						<?php echo form_label(lang('add_email')); ?>
						<?php echo form_label(lang('enter_amount_emails')); ?>
						<?php echo form_input(array('id'=>'email-fields-to-add','name'=>'email_fields_to_add','class'=>'add-num-email', 'title'=>lang('tip_enter_amount_emails')),1); ?>
						<?php echo form_submit(array('id'=>'submit-add-email', 'class'=>'btn btn-add','name'=>'submit_add_email'),lang('add')); ?>
						
					</fieldset>
					
				</div>
				
				<div class="form-item">
					
					<fieldset>
						
						<legend>
							
							<?= vui_el_button( array( 'text' => lang( 'phones' ), 'icon' => 'phones',  ) ); ?>
							
						</legend>
						
						<?php if ( isset($company['phones']) AND is_array($company['phones']) ) { ?>
						<?php foreach ($company['phones'] as $key => $phone) { ?>
						
						<div class="phones-fields-wrapper">
							
							<?= form_hidden('phones['.$key.'][key]',$phone['key']); ?>
							
							<div class="vui-field-wrapper-inline">
								
								<?= form_error('phones['.$key.'][title]', '<div class="msg-inline-error">', '</div>'); ?>
								<?= form_label(lang('title')); ?>
								<?= form_input(array('id'=>'phone-title-'.$key,'name'=>'phones['.$key.'][title]','class'=>'phone-title', 'title'=>lang('tip_phone_type')), isset($phone['title']) ? $phone['title'] : lang('phone').' '.$key ); ?>
								
							</div>
							
							<div class="vui-field-wrapper-inline">
								
								<?= form_error('phones['.$key.'][area_code]', '<div class="msg-inline-error">', '</div>'); ?>
								<?= form_label(lang('phone_area_code')); ?>
								<?= form_input(array('id'=>'phone-area-code-'.$key,'name'=>'phones['.$key.'][area_code]','class'=>'phone-area-code'), isset($phone['area_code']) ? $phone['area_code'] : '' ); ?>
								
							</div>
							
							<div class="vui-field-wrapper-inline">
								
								<?= form_error('phones['.$key.'][number]', '<div class="msg-inline-error">', '</div>'); ?>
								<?= form_label(lang('phone_number')); ?>
								<?= form_input(array('id'=>'phone-number-'.$key,'name'=>'phones['.$key.'][number]','class'=>'phone-number'), isset($phone['number']) ? $phone['number'] : '' ); ?>
								
							</div>
							
							<div class="vui-field-wrapper-inline">
								
								<?= form_error('phones['.$key.'][extension_number]', '<div class="msg-inline-error">', '</div>'); ?>
								<?= form_label(lang('extension_number')); ?>
								<?= form_input(array('id'=>'phone-extension-number-'.$key,'name'=>'phones['.$key.'][extension_number]','class'=>'phone-extension-number'), isset($phone['extension_number']) ? $phone['extension_number'] : '' ); ?>
								
								<?php if ( $key > 1 OR $f_action == 'edit_company' ) { ?>
								
								<?= form_submit(array('id'=>'submit-remove-phone-'.$key,'name'=>'submit_remove_phone['.$key.']', 'class'=>'btn btn-delete', 'title'=>lang('tip_remove_phone')),lang('remove_phone')); ?>
								
								<?php } ?>
								
							</div>
							
						</div>
						
						<?php } ?>
						<?php } ?>
						
						<?= form_label(lang('add_phone')); ?>
						<?= form_label(lang('enter_amount_phones')); ?>
						<?= form_input(array('id'=>'phone-fields-to-add','name'=>'phone_fields_to_add','class'=>'add-num-phone', 'title'=>lang('tip_enter_amount_phones')),1); ?>
						<?= form_submit(array('id'=>'submit-add-phone', 'class'=>'btn btn-add','name'=>'submit_add_phone'),lang('add')); ?>
						
					</fieldset>
					
				</div>
				
				<div class="form-item">
					
					<fieldset>
						
						<legend>
							
							<?= vui_el_button( array( 'text' => lang( 'websites' ), 'icon' => 'websites',  ) ); ?>
							
						</legend>
						
						<?php if ( isset($company['websites']) AND is_array($company['websites']) ) { ?>
						<?php foreach ($company['websites'] as $key => $website) { ?>
						
						<div class="websites-fields-wrapper">
							
							<?= form_hidden('websites['.$key.'][key]',$website['key']); ?>
							
							<div class="vui-field-wrapper-inline">
								
								<?= form_error('websites['.$key.'][title]', '<div class="msg-inline-error">', '</div>'); ?>
								<?= form_label(lang('title')); ?>
								<?= form_input(array('id'=>'website-title-'.$key,'name'=>'websites['.$key.'][title]','class'=>'website-title', 'title'=>lang('tip_website_title')), isset($website['title']) ? $website['title'] : lang('website').' '.$key ); ?>
								
							</div>
							
							<div class="vui-field-wrapper-inline">
								
								<?= form_error('websites['.$key.'][url]', '<div class="msg-inline-error">', '</div>'); ?>
								<?= form_label(lang('url')); ?>
								<?= form_input(array('id'=>'website-url-'.$key,'name'=>'websites['.$key.'][url]','class'=>'website-url'), isset($website['url']) ? $website['url'] : '' ); ?>
								
								<?php if ( $key > 1 OR $f_action == 'edit_company' ) { ?>
								
								<?= form_submit(array('id'=>'submit-remove-website-'.$key,'name'=>'submit_remove_website['.$key.']', 'class'=>'btn btn-delete', 'title'=>lang('tip_remove_website')),lang('remove_website')); ?>
								
								<?php } ?>
								
							</div>
							
						</div>
						
						<?php } ?>
						<?php } ?>
						
						<?= form_label(lang('add_website')); ?>
						<?= form_label(lang('enter_amount_websites')); ?>
						<?= form_input(array('id'=>'website-fields-to-add','name'=>'website_fields_to_add','class'=>'add-num-website', 'title'=>lang('tip_enter_amount_websites')),1); ?>
						<?= form_submit(array('id'=>'submit-add-website', 'class'=>'btn btn-add','name'=>'submit_add_website'),lang('add')); ?>
						
					</fieldset>
					
				</div>
				
				<div class="form-item">
					
					<fieldset>
						
						<legend>
							
							<?= vui_el_button( array( 'text' => lang( 'addresses' ), 'icon' => 'addresses',  ) ); ?>
							
						</legend>
						
						<?php if ( isset($company['addresses']) AND is_array($company['addresses']) ) { ?>
						<?php foreach ($company['addresses'] as $key => $address) { ?>
						
						<?= form_hidden('addresses['.$key.'][key]',$address['key']); ?>
						
						<div class="addresses-fields-wrapper dinamic-fields-wrapper">
							
							<div class="vui-field-wrapper-inline">
								<?= form_label(lang('title')); ?>
								<?= form_input(array('id'=>'address-title-'.$key,'name'=>'addresses['.$key.'][title]','class'=>'address-title', 'title'=>lang('tip_address_type')), isset($address['title']) ? $address['title'] : lang('address').' '.$key ); ?>
							</div>
							
							<br/>
							
							<div class="vui-field-wrapper-inline">
								<?= form_label(lang('country')); ?>
								<?= form_input(array('id'=>'address-country-title-'.$key,'name'=>'addresses['.$key.'][country_title]','class'=>'address-country-title', 'title'=>lang('tip_address_country_title')), isset($address['country_title']) ? lang($address['country_title']) : '' ); ?>
							</div>
							
							<div class="vui-field-wrapper-inline">
								<?= form_label(lang('state')); ?>
								<?= form_input(array('id'=>'address-state-acronym-'.$key,'name'=>'addresses['.$key.'][state_acronym]','class'=>'address-state-acronym', 'title'=>lang('tip_address_state_acronym')), isset($address['state_acronym']) ? lang($address['state_acronym']) : '' ); ?>
							</div>
							
							<div class="vui-field-wrapper-inline">
								<?= form_label(lang('city')); ?>
								<?= form_input(array('id'=>'address-city-title-'.$key,'name'=>'addresses['.$key.'][city_title]','class'=>'address-city-title', 'title'=>lang('tip_address_city_title')), isset($address['city_title']) ? lang($address['city_title']) : '' ); ?>
							</div>
							
							<div class="vui-field-wrapper-inline">
								<?= form_label(lang('neighborhood')); ?>
								<?= form_input(array('id'=>'address-neighborhood-title-'.$key,'name'=>'addresses['.$key.'][neighborhood_title]','class'=>'address-neighborhood-title', 'title'=>lang('tip_address_neighborhood_title')), isset($address['neighborhood_title']) ? lang($address['neighborhood_title']) : '' ); ?>
							</div>
							
							<br/>
							
							<div class="vui-field-wrapper-inline">
								<?= form_label(lang('public_area')); ?>
								<?= form_input(array('id'=>'address-public-area-title-'.$key,'name'=>'addresses['.$key.'][public_area_title]','class'=>'address-public-area-title', 'title'=>lang('tip_address_public_area_title')), isset($address['public_area_title']) ? lang($address['public_area_title']) : '' ); ?>
							</div>
							
							<div class="vui-field-wrapper-inline">
								<?= form_label(lang('postal_code')); ?>
								<?= form_input(array('id'=>'address-postal-code-'.$key,'name'=>'addresses['.$key.'][postal_code]','class'=>'address-postal-code', 'title'=>lang('tip_address_postal_code')), isset($address['postal_code']) ? lang($address['postal_code']) : '' ); ?>
							</div>
							
							<div class="vui-field-wrapper-inline">
								<?= form_label(lang('number')); ?>
								<?= form_input(array('id'=>'address-number-'.$key,'name'=>'addresses['.$key.'][number]','class'=>'address-number', 'title'=>lang('tip_address_number')), isset($address['number']) ? lang($address['number']) : '' ); ?>
							</div>
							
							<br/>
							
							<div class="vui-field-wrapper-inline">
								<?= form_label(lang('complement')); ?>
								<?= form_input(array('id'=>'address-complement-'.$key,'name'=>'addresses['.$key.'][complement]','class'=>'address-complement', 'title'=>lang('tip_address_complement')), isset($address['complement']) ? lang($address['complement']) : '' ); ?>
							</div>
							
							<br/>
							
							<div class="field-wrapper">
								<?= form_submit(array('id'=>'submit-remove-address-'.$key,'name'=>'submit_remove_address['.$key.']', 'class'=>'button', 'title'=>lang('tip_remove_address')),lang('remove_address')); ?>
							</div>
							
						</div>
						
						<?php } ?>
						<?php } ?>
						
						<div class="field-wrapper ta-right">
							<?= form_submit(array('id'=>'submit-add-address', 'class'=>'button','name'=>'submit_add_address'),lang('add_address')); ?>
						</div>
						
					</fieldset>
					
				</div>
				
			</div>
			
			<?php if ( isset( $company[ 'id' ] ) ){ ?>
			<?= form_hidden( 'company_id', $company[ 'id' ] ); ?>
			<?php } ?>
			
		<?= form_close(); ?>
		
	</div>
	
</div>

<script type="text/javascript">
	
	<?php if ( $this->plugins->load( 'jquery' ) ){ ?>
	
	function escapeRegExp(string) {
		return string.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1");
	}
	function replaceAll( string, find, replace ) {
		return string.replace(new RegExp(escapeRegExp(find), 'g'), replace);
	}
	
	function contactFields( contactId, contactThumb, contactName, key ){
		
		/*
		
		<div class="contacts-fields-wrapper" data-contact-id="69">
		
		<input type="hidden" name="contacts[5][id]" value="69">	
		<input type="hidden" name="contacts[5][key]" value="5">
		<input type="hidden" name="contacts[5][title]" value="Contato 5">
		
		</div>
		
		 */
		
		var out = '<div class="contact-item-wrapper" data-contact-id="{{contactId}}" data-contactkey="{{key}}">';
		out += '<a rel="companies-contacts" href="<?= base_url() . 'admin/contacts/ajax/get_contact_data' . '?ajax=true&contact_id='; ?>{{contactId}}" class="modal-contact contact-item" data-mc-action="get" data-contact-id="{{contactId}}" target="_blank" title="">';
		out += '<span class="contact-thumb-wrapper">';
		out += '<span class="contact-thumb-content">';
		out += '<img src="{{contactThumb}}" width="24" alt="">';
		out += '</span>';
		out += '</span>';
		out += '<span class="contact-name-wrapper">';
		out += '<span class="contact-name-content">';
		out += '{{contactName}}';
		out += '</span>';
		out += '</span>';
		out += '</a>';
		out += '<div class="contact-company-title-field-wrapper">';
		out += '<input type="text" name="contacts[{{key}}][title]" value="<?= lang( 'contact' ); ?> {{key}}" id="contact-title-{{key}}" class="contact-title-field" title="">';
		out += '</div>';
		out += '<div class="contact-delete-button-wrapper">';
		out += '<?= vui_el_button( array( 'text' => lang( 'remove_contact' ), 'icon' => 'cancel', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_remove_contact[{{key}}]', 'id' => 'submit-remove-contact-{{key}}', 'class' => 'contact-delete-button', 'only_icon' => TRUE, 'form' => 'company-form', ) ); ?>';
		out += '</div>';
		out += '<input type="hidden" name="contacts[{{key}}][id]" value="{{contactId}}">';
		out += '<input type="hidden" name="contacts[{{key}}][key]" value="{{key}}">';
		out += '</div>\n';
		
		out = replaceAll( out, '{{contactId}}', contactId );
		out = replaceAll( out, '{{contactThumb}}', contactThumb );
		out = replaceAll( out, '{{contactName}}', contactName );
		out = replaceAll( out, '{{key}}', key );
		
		return out;
		
	}
	
	function addContact( contactId, contactThumb, contactName, callBackFunction ){
		
		var nextKey = $( '.contact-item-wrapper' ).length + 1;
		
		$( '#contacts-wrapper' ).append( contactFields( contactId, contactThumb, contactName, nextKey ) );
		
		callBackFunction();
		
	}
	
	function bindDeleteContact(){
		
		$( '.contact-delete-button' ).bind( 'click', function(e){
			
			e.preventDefault();
			
			$( this ).parent().parent().remove();
			
		});
		
	}
	
	<?php } ?>
	
	$( document ).bind( 'ready', function(){
		
		<?php if ( $this->plugins->load( 'yetii' ) ){ ?>
		
		/*************************************************/
		/**************** Criando as tabs ****************/
		
		makeTabs( $( '.tabs-wrapper' ), '.form-item', 'legend' );
		
		/**************** Criando as tabs ****************/
		/*************************************************/
		
		<?php } ?>
		
		<?php if ( $this->plugins->load( 'via_cms_live_search' ) ){ ?>
		
		$( '.contact-company-contact-field, .add-contact-controls-wrapper' ).addClass( 'hidden' );
		
		f = function(){
			
			$( '.contact-item' ).bind( 'click', function(e){
				
				e.preventDefault();
				
				var contactId = $( this ).data( 'contact-id' );
				var contactThumb = $( this ).find( '.thumb-wrapper-content img' ).attr( 'src' );
				var contactName = $( this ).find( '.contact-name-content' ).text();
				
				if ( ! contactThumb ) contactThumb = '';
				
				console.log( $( '#company-form-fields-wrapper .contact-item[data-contact-id="' + contactId + '"]' ).length )
				
				if ( $( '#company-form-fields-wrapper .contact-item[data-contact-id="' + contactId + '"]' ).length == 0 ) {
					
					cf = function(){
						
						bindDeleteContact();
						
					}
					
					addContact( contactId, contactThumb, contactName, cf );
					
				}
				else {
					
					$( '#company-form-fields-wrapper .contact-item[data-contact-id=' + contactId + ']' ).each(function(){
						
						$( this ).stop().animate({
							
							opacity: 0
							
						}, 200, function(){
							
							$( this ).stop().animate({
								
								opacity: 1
								
							}, 200 );
							
						});
						
					});
					
				}
				
			});
			
		}
		
		bindDeleteContact();
		
		liveSearch( '<?= $this->contacts_model->get_component_url_admin() . '/ajax/live_search?q='; ?>', f );
		
		<?php } ?>
		
		$(".address-postal-code").mask( "99.999-999" );
		
	});
	
</script>

<?php $this->plugins->load( 'modal_contacts' ); ?>

<script type="text/javascript">
	
	<?php if ( $this->plugins->load( 'modal_rf_file_picker' ) ) { ?>
	
	window.updateImage = function(){
		
		var url = $( '#company-logo' ).val(),
			thumb_image = $( '.company-thumb-wrapper .company-image-thumb' );
			
		var image_src = url + '?' + Math.floor( ( Math.random() * 100 ) + 1 );
		var thumb_image_src = 'thumbs/' + image_src;
		
		$( '[name=logo_thumb]' ).val( thumb_image_src );
		thumb_image.src = $( '#company-logo_thumb' ).val();
		
		$( '.company-logo-wrapper' ).empty();
		
		if ( url != '' ){
			
			$( '.company-logo-wrapper' ).append( '<a class="company-image-logo-link" href="' + url + '" target="_blank"><img class="company-image-logo" src="' + image_src + '" /></a>' );
			
			var image = $( '.company-image-logo' );
			
			image.attr( 'src', thumb_image_src );
			
		}
		
		$.fancybox.close();
		
	}
	window.onFileChooseFunction = function(){
		
		var url = $( '#company-logo' ).val();
		
		if ( url != '' ){
			
			<?php if ( $this->plugins->load( 'image_cropper' ) ) { ?>
			
			$.imageCrop.open({
				
				imgSrc: url,
				callback: updateImage
				
			})
			
			<?php } ?>
			
		}
		
	}
	
	$( document ).on( 'ready', function(){
		
		window.updateImage();
		
		$( '#company-logo' ).after( '<?= vui_el_button( array( 'attr' => 'data-rfdir="' . trim( $company_image_path, DS ) . '" data-rffieldid="company-logo" data-rftype="image"', 'url' => '#', 'text' => lang( 'select_image' ), 'get_url' => FALSE, 'id' => 'image-picker', 'icon' => 'more', 'only_icon' => TRUE, 'class' => 'modal-file-picker', ) ); ?>' );
		
		$('.company-image-logo-link').fancybox();
		
	});
	
	<?php } ?>
	
</script>

