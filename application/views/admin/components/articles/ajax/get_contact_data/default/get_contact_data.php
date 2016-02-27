		
		<div class="info-wrapper contact-info-wrapper">
			
			
			<?php if ( isset($contact['thumb_local']) AND $contact['thumb_local'] ){ ?>
			
			<div class="contact-thumb-wrapper thumb-wrapper">
				
				<?= img( array( 'src' => base_url().COMPONENTS_IMAGES_URL.$component_name . '/' . $contact['id'] . '/' . $contact['thumb_local'], 'height' => 120 ) ); ?>
				
			</div>
			
			<?php } else { ?>
			
			<div class="contact-thumb-wrapper thumb-wrapper no-image"></div>
			
			<?php } ?>
			
			<div class="contact-info-items info-items">
				
				<div class="contact-info-item contact-name info-title">
					
					<?= $contact['name']; ?>
					
				</div>
				
				<?php if ( isset($contact['birthday_date']) AND $contact['birthday_date'] ) { ?>
				
				<div class="contact-info-item info-item">
					
					<span class="contact-info-item-title info-item-title">
						
						<?= lang('birthday_date'); ?>:
						
					</span>
					
					<?= $contact['birthday_date']; ?>
					
				</div>
				
				<?php } ?>
				
				
				<?php if ( isset($contact['phones']) AND is_array($contact['phones']) AND $contact['phones'] ) { ?>
				
				<div class="contact-info-item info-item">
				
					<span class="contact-info-item-title info-item-title">
						
						<?= lang('phones'); ?>
						
					</span>
					
					<?php foreach ($contact['phones'] as $key => $phone) { ?>
					
					<div class="phone">
						
						<?= (isset($phone['area_code']) AND $phone['area_code']) ? '('.$phone['area_code'].')' : ''; ?>
						<?= (isset($phone['number']) AND $phone['number']) ? $phone['number'] : ''; ?>
						<?= (isset($phone['extension_number']) AND $phone['extension_number']) ? $phone['extension_number'] : ''; ?>
						<?= (isset($phone['title']) AND $phone['title']) ? '- '.$phone['title'] : lang('phone').' '.$key; ?> 
						
					</div>
					
					<?php } ?>
					
				</div>
				
				<?php } ?>
				
				<?php if ( isset($contact['emails']) AND is_array($contact['emails']) AND $contact['emails'] ) { ?>
				
				<div class="contact-info-item info-item">
				
					<span class="contact-info-item-title info-item-title">
						
						<?= lang('emails'); ?>
						
					</span>
					
					<?php foreach ($contact['emails'] as $key => $email) { ?>
					
					<div class="email">
						
						<?= (isset($email['email']) AND $email['email']) ? mailto(strip_tags($contact['name'].' %3C'.$email['email'].'%3E'),$email['email'],'class="email" title="'.lang('tip_email_click_to_open_your_mail_app').'"') : ''; ?>
						<?= (isset($email['title']) AND $email['title']) ? '- '.$email['title'] : lang('email').' '.$key; ?> 
						
					</div>
					
					<?php } ?>
					
				</div>
				
				<?php } ?>
				
				<?php if ( isset($contact['addresses']) AND is_array($contact['addresses']) AND $contact['addresses'] ) { ?>
				
				<div class="contact-info-item info-item">
				
					<span class="contact-info-item-title info-item-title">
						
						<?= lang('addresses'); ?>
						
					</span>
					
					<?php foreach ($contact['addresses'] as $key => $address) { ?>
					
					<div class="address">
						
						<?php if ( count( $contact[ 'addresses' ] ) > 1 ){ ?>
							
							<? $address['title'] = ( isset( $address[ 'title' ] ) AND $address[ 'title' ] ) ? $address[ 'title' ] : NULL; ?>
							
						<?php } else { ?>
							
							<? $address['title'] = NULL; ?>
							
						<?php } ?>
						
						<?= vui_el_address_box( array( 'address' => $address, ) ); ?> 
						
					</div>
					
					<?php } ?>
					
				</div>
				
				<?php } ?>
				
				<?php if ( isset($contact['websites']) AND is_array($contact['websites']) AND $contact['websites'] ) { ?>
				
				<div class="contact-info-item info-item">
				
					<span class="contact-info-item-title info-item-title">
						
						<?= lang('websites'); ?>
						
					</span>
					
					<?php foreach ($contact['websites'] as $key => $website) { ?>
					
					<div class="website">
						
						<?= anchor( prep_url($website['url']) , $website['title'],'class="list-info-wrapper-website list-info-wrapper-website-' . url_title($website['title'], '-', TRUE) . '" target="_blank" title="' . $website['title'] . '"'); ?>
						
					</div>
					
					<?php } ?>
					
				</div>
				
				<?php } ?>
				
				<div class="contact-info-item info-item">
					<?= anchor('admin/'.$component_name.'/contacts_management/edit_contact/'.$contact['id'],lang('action_edit_contact'),'class="button" title="'.lang('tip_action_edit_contact').'"'); ?>
				</div>
				
			</div>
			
		</div>
		