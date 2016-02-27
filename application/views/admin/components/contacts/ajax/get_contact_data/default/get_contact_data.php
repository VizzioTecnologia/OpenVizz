		
		<div id="contact-info-wrapper" class="info-wrapper has-image">
			
			
			<?php if ( isset($contact['thumb_local']) AND $contact['thumb_local'] ){ ?>
			
			<div class="contact-thumb-wrapper thumb-wrapper">
				
				<div class="thumb-image-wrapper">
					
					<?= img( array( 'src' => $contact['thumb_local'], 'width' => 96 ) ); ?>
					
				</div>
				
			</div>
			
			<?php } else { ?>
			
			<div class="contact-thumb-wrapper thumb-wrapper no-image">
				
				<div class="thumb-image-wrapper">
					
				</div>
				
			</div>
			
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
						
						<?php if ( count( $contact[ 'phones' ] ) > 1 ){ ?>
							
							<?= lang( 'phones' ); ?>
							
						<?php } else { ?>
						
							<?= lang( 'phone' ); ?>
							
						<?php } ?>
						
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
						
						<?php if ( count( $contact[ 'emails' ] ) > 1 ){ ?>
							
							<?= lang( 'emails' ); ?>
							
						<?php } else { ?>
						
							<?= lang( 'email' ); ?>
							
						<?php } ?>
						
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
						
						<?php if ( count( $contact[ 'addresses' ] ) > 1 ){ ?>
							
							<?= lang( 'addresses' ); ?>
							
						<?php } else { ?>
						
							<?= lang( 'address' ); ?>
							
						<?php } ?>
						
					</span>
					
					<?php foreach ( $contact['addresses'] as $key => $address ) { ?>
					
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
						
						<?php if ( count( $contact[ 'websites' ] ) > 1 ){ ?>
							
							<?= lang( 'websites' ); ?>
							
						<?php } else { ?>
						
							<?= lang( 'website' ); ?>
							
						<?php } ?>
						
					</span>
					
					<?php foreach ($contact['websites'] as $key => $website) { ?>
					
					<div class="website">
						
						<?= anchor( prep_url($website['url']) , $website['title'],'class="list-info-wrapper-website list-info-wrapper-website-' . url_title($website['title'], '-', TRUE) . '" target="_blank" title="' . $website['title'] . '"'); ?>
						
					</div>
					
					<?php } ?>
					
				</div>
				
				<?php } ?>
				
				<div class="edit-button contact-info-item info-item">
					
					<?= anchor( 'admin/'.$component_name.'/contacts_management/edit_contact/'.$contact['id'], lang('action_edit_contact'),' data-mc-title="' . $contact['name'] . '" data-mc-last-modal-group="' . ( $this->input->get( 'last-modal-group' ) ? $this->input->get( 'last-modal-group' ) : '' ) . '" data-mc-action="edit" data-contact-id="' . $contact[ 'id' ] . '" class="modal-contact edit-contact-button button" title="'.lang('tip_action_edit_contact').'"'); ?>
					
				</div>
				
			</div>
			
		</div>
		
		<script type="text/javascript">
			modalContacts()
		</script>
		