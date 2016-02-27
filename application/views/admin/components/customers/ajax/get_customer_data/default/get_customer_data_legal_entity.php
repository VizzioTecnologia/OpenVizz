		
		<div id="customer-info-wrapper" class="info-wrapper">
			
			<?php if ( isset($customer['logo_thumb']) AND $customer['logo_thumb'] ){ ?>
			
			<div class="customer-thumb-wrapper thumb-wrapper">
				
				<?= img( array( 'src' => $customer['logo_thumb'], 'height' => 120 ) ); ?>
				
			</div>
			
			<?php } ?>
			
			<div class="customer-info-items info-items">
				
				<div class="customer-info-items-company">
					
					<div class="info-title">
						
						<?= $customer['trading_name']; ?>
						
					</div>
					
					<?php if ( isset($customer['company_name']) AND $customer['company_name'] ) { ?>
					
					<div class="customer-info-item info-item">
						
						<span class="info-item-title">
							
							<?= lang('company_name'); ?>:
							
						</span>
						
						<?= $customer['company_name']; ?>
						
					</div>
					
					<?php } ?>
					
					<?php if ( isset($customer['state_registration']) AND $customer['state_registration'] ) { ?>
					
					<div class="customer-info-item info-item">
						
						<span class="info-item-title">
							
							<?= lang('state_registration'); ?>:
							
						</span>
						
						<?= $customer['state_registration']; ?>
						
					</div>
					
					<?php } ?>
					
					<?php if ( isset($customer['corporate_tax_register']) AND $customer['corporate_tax_register'] ) { ?>
					
					<div class="customer-info-item info-item">
						
						<span class="info-item-title">
							
							<?= lang('corporate_tax_register'); ?>:
							
						</span>
						
						<?= $customer['corporate_tax_register']; ?>
						
					</div>
					
					<?php } ?>
					
					<?php if ( isset($customer['sic']) AND $customer['sic'] ) { ?>
					
					<div class="customer-info-item info-item">
						
						<span class="info-item-title">
							
							<?= lang('sic'); ?>:
							
						</span>
						
						<?= $customer['sic']; ?>
						
					</div>
					
					<?php } ?>
					
				</div>
				
				<?php if ( isset($customer['company_phones']) AND is_array($customer['company_phones']) AND $customer['company_phones'] ) { ?>
				
				<div class="customer-phones info-item">
				
					<span class="info-item-title">
						
						<?= lang('phones'); ?>
						
					</span>
					
					<?php foreach ($customer['company_phones'] as $key => $phone) { ?>
					
					<div class="phone">
						
						<?= (isset($phone['area_code']) AND $phone['area_code']) ? '('.$phone['area_code'].')' : ''; ?>
						<?= (isset($phone['number']) AND $phone['number']) ? $phone['number'] : ''; ?>
						<?= (isset($phone['extension_number']) AND $phone['extension_number']) ? $phone['extension_number'] : ''; ?>
						<?= (isset($phone['title']) AND $phone['title']) ? '- '.$phone['title'] : lang('phone').' '.$key; ?> 
						
					</div>
					
					<?php } ?>
					
				</div>
				
				<?php } ?>
				
				<?php if ( isset($customer['company_emails']) AND is_array($customer['company_emails']) AND $customer['company_emails'] ) { ?>
				
				<div class="customer-emails info-item">
				
					<span class="info-item-title">
						
						<?= lang('emails'); ?>
						
					</span>
					
					<?php foreach ($customer['company_emails'] as $key => $email) { ?>
					
					<div class="email">
						
						<?= (isset($email['email']) AND $email['email']) ? mailto(strip_tags($customer['name'].' %3C'.$email['email'].'%3E'),$email['email'],'class="email" title="'.lang('tip_email_click_to_open_your_mail_app').'"') : ''; ?>
						<?= (isset($email['title']) AND $email['title']) ? '- '.$email['title'] : lang('email').' '.$key; ?> 
						
					</div>
					
					<?php } ?>
					
				</div>
				
				<?php } ?>
				
				<?php if ( isset($customer['contacts']) AND is_array($customer['contacts']) AND $customer['contacts'] ) { ?>
				
				<div class="customer-contacts info-item">
				
					<span class="info-item-title">
						
						<?= lang('contacts'); ?>
						
					</span>
					
					<?php foreach ($customer['contacts'] as $key => $contact) { ?>
					
					<div class="contact">
						<!--
						<a href="<?= get_url('admin/contacts/contacts_management/edit_contact/'.$contact['id']); ?>" class="list-info-wrapper" data-contact-id="<?= $contact['id']; ?>" target="_blank" >
						</a>-->
						
							
						<?php
							
							$options = array(
								
								'wrapper-class' => 'radiobox-sub-item',
								'name' => 'contact_id',
								'text' => '',
								'value' => $contact[ 'id' ],
								
							);
							
							$options[ 'text' ] .= '<a rel="customer-contacts-' . $customer[ 'id' ] . '" href="' . get_url( 'admin/contacts/contacts_management/edit_contact/' . $contact[ 'id' ] ) . '" data-mc-last-modal-group="' . ( $this->input->get( 'last-modal-group' ) ? $this->input->get( 'last-modal-group' ) : 'customer-contacts-' . $customer[ 'id' ] ). '" data-mc-action="get" data-contact-id="' . $contact[ 'id' ] . '" class="modal-contact list-info-wrapper" target="_blank" title="' . strip_tags( $contact[ 'name' ] ) . '">';
							$options[ 'text' ] .= '<span class="list-info-thumb-wrapper">';
							
							if ( $contact['thumb_local'] ){
								
								$options[ 'text' ] .= img( array( 'src' => $contact['thumb_local'], 'width' => 24 ) );
								
							}
							
							$options[ 'text' ] .= '</span>';
							$options[ 'text' ] .= $contact[ 'name' ];
							$options[ 'text' ] .= '</a>';
							
							echo vui_el_radiobox( $options );
							
						?>
						
					</div>
					
					<?php } ?>
					
				</div>
				
				<?php } ?>
				
				<?php if ( isset($customer['company_addresses']) AND is_array($customer['company_addresses']) AND $customer['company_addresses'] ) { ?>
				
				<div class="customer-addresses info-item">
				
					<span class="info-item-title">
						
						<?= lang('addresses'); ?>
						
					</span>
					
					<?php foreach ($customer['company_addresses'] as $key => $address) { ?>
					
					<div class="address">
						
						<?= (isset($address['title']) AND $address['title']) ? $address['title'] : lang('address').' '.$key; ?><br/>
						<?= (isset($address['public_area_title']) AND $address['public_area_title']) ? $address['public_area_title'] : ''; ?> 
						<?= (isset($address['complement']) AND $address['complement']) ? '- '.$address['complement'] : ''; ?> 
						<?= (isset($address['number']) AND $address['number']) ? '- '.$address['number'] : ''; ?><br/>
						<?= (isset($address['neighborhood_title']) AND $address['neighborhood_title']) ? $address['neighborhood_title'] : ''; ?> 
						<?= (isset($address['city_title']) AND $address['city_title']) ? '- '.$address['city_title'] : ''; ?> 
						<?= (isset($address['state_acronym']) AND $address['state_acronym']) ? '- '.$address['state_acronym'] : ''; ?> 
						<?= (isset($address['country_title']) AND $address['country_title']) ? '- '.$address['country_title'] : ''; ?> <br/>
						<?= (isset($address['postal_code']) AND $address['postal_code']) ? $address['postal_code'] : ''; ?> 
						
					</div>
					
					<?php } ?>
					
				</div>
				
				<?php } ?>
				
				<div class="customer-info-item info-item">
					<?= anchor('admin/'.$component_name.'/customers_management/edit_customer/'.$customer['id'],lang('action_edit_customer'),'class="" title="'.lang('tip_action_edit_customer').'"'); ?>
				</div>
				
			</div>
			
			<script type="text/javascript" >
				
				$(document).ready(function(){
					
					modalContacts();
					
				});
				
			</script>
			
		</div>
		