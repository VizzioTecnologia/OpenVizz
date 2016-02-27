		
		<div id="customer-info-wrapper">
			
			<?php if ( isset($customer['thumb_local']) AND $customer['thumb_local'] ){ ?>
			
			<div class="contact-thumb-wrapper">
				
				<?= img( array( 'src' => base_url().COMPONENTS_IMAGES_URL.$component_name . '/' . $customer['id'] . '/' . $customer['thumb_local'], 'height' => 120 ) ); ?>
				
			</div>
			
			<?php } ?>
			
			<div class="contact-info-items">
				
				<div class="contact-info-item contact-name">
					
					<?= $customer['name']; ?>
					
				</div>
				
				<?php if ( isset($customer['birthday_date']) AND $customer['birthday_date'] ) { ?>
				
				<div class="contact-info-item">
					
					<span class="contact-info-item-title">
						
						<?= lang('birthday_date'); ?>:
						
					</span>
					
					<?= $customer['birthday_date']; ?>
					
				</div>
				
				<?php } ?>
				
				
				<?php if ( isset($customer['phones']) AND is_array($customer['phones']) AND $customer['phones'] ) { ?>
				
				<div class="contact-info-item">
				
					<span class="contact-info-item-title">
						
						<?= lang('phones'); ?>
						
					</span>
					
					<?php foreach ($customer['phones'] as $key => $phone) { ?>
					
					<div class="">
						
						<?= (isset($phone['area_code']) AND $phone['area_code']) ? '('.$phone['area_code'].')' : ''; ?>
						<?= (isset($phone['number']) AND $phone['number']) ? $phone['number'] : ''; ?>
						<?= (isset($phone['extension_number']) AND $phone['extension_number']) ? $phone['extension_number'] : ''; ?>
						<?= (isset($phone['title']) AND $phone['title']) ? '- '.$phone['title'] : lang('phone').' '.$key; ?> 
						
					</div>
					
					<?php } ?>
					
				</div>
				
				<?php } ?>
				
				<?php if ( isset($customer['emails']) AND is_array($customer['emails']) AND $customer['emails'] ) { ?>
				
				<div class="contact-info-item">
				
					<span class="contact-info-item-title">
						
						<?= lang('emails'); ?>
						
					</span>
					
					<?php foreach ($customer['emails'] as $key => $email) { ?>
					
					<div class="">
						
						<?= (isset($email['email']) AND $email['email']) ? mailto(strip_tags($customer['name'].' %3C'.$email['email'].'%3E'),$email['email'],'class="email" title="'.lang('tip_email_click_to_open_your_mail_app').'"') : ''; ?>
						<?= (isset($email['title']) AND $email['title']) ? '- '.$email['title'] : lang('email').' '.$key; ?> 
						
					</div>
					
					<?php } ?>
					
				</div>
				
				<?php } ?>
				
				<?php if ( isset($customer['addresses']) AND is_array($customer['addresses']) AND $customer['addresses'] ) { ?>
				
				<div class="contact-info-item">
				
					<span class="contact-info-item-title">
						
						<?= lang('addresses'); ?>
						
					</span>
					
					<?php foreach ($customer['addresses'] as $key => $address) { ?>
					
					<div class="">
						
						<?php
							
							if ( count( $customer['addresses'] ) > 1 ){
								
								$address['title'] = ( isset( $address[ 'title' ] ) AND $address[ 'title' ] ) ? $address[ 'title' ] : NULL;
								
							} else {
								
								$address['title'] = NULL;
								
							}
							
							echo vui_el_address_box( array( 'address' => $address, ) );
							
						?>
						
					</div>
					
					<?php } ?>
					
				</div>
				
				<?php } ?>
				
				<div class="contact-info-item">
					<?= anchor('admin/'.$component_name.'/contacts_management/edit_contact/'.$customer['id'],lang('action_edit_contact'),'class="" title="'.lang('tip_action_edit_contact').'"'); ?>
				</div>
				
			</div>
			
		</div>
		