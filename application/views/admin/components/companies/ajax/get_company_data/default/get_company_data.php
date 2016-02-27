		
		<div id="company-info-wrapper" class="info-wrapper has-image">
			
			
			<?php if ( isset($company['logo_thumb']) AND $company['logo_thumb'] ){ ?>
			
			<div class="company-thumb-wrapper thumb-wrapper">
				
				<div class="thumb-image-wrapper">
					
					<?= img( array( 'src' => $company['logo_thumb'], 'width' => 96 ) ); ?>
					
				</div>
				
			</div>
			
			<?php } else { ?>
			
			<div class="company-thumb-wrapper thumb-wrapper no-image">
				
				<div class="thumb-image-wrapper">
					
				</div>
				
			</div>
			
			<?php } ?>
			
			<div class="company-info-items info-items">
				
				<div class="company-info-items">
					
					<div class="company-info-item company-trading-name info-title">
						
						<span class="info-item-title-inline">
							
							<?= lang('trading_name'); ?>:
							
						</span>
						
						<span class="info-item-content-inline">
							
							<?= $company['trading_name']; ?>
							
						</span>
						
					</div>
					
					<?php if ( isset($company['company_name']) AND $company['company_name'] ) { ?>
					
					<div class="company-info-item info-item">
						
						<span class="info-item-title-inline">
							
							<?= lang('company_name'); ?>:
							
						</span>
						
						<span class="info-item-content-inline">
							
							<?= $company['company_name']; ?>
							
						</span>
						
					</div>
					
					<?php } ?>
					
					<?php if ( isset($company['corporate_tax_register']) AND $company['corporate_tax_register'] ) { ?>
					
					<div class="company-info-item info-item">
						
						<span class="info-item-title-inline">
							
							<?= lang('corporate_tax_register'); ?>:
							
						</span>
						
						<span class="info-item-content-inline">
							
							<?= $company['corporate_tax_register']; ?>
							
						</span>
						
					</div>
					
					<?php } ?>
					
					<?php if ( isset($company['state_registration']) AND $company['state_registration'] ) { ?>
					
					<div class="company-info-item info-item">
						
						<span class="info-item-title-inline">
							
							<?= lang('state_registration'); ?>:
							
						</span>
						
						<span class="info-item-content-inline">
							
							<?= $company['state_registration']; ?>
							
						</span>
						
					</div>
					
					<?php } ?>
					
					<?php if ( isset($company['sic']) AND $company['sic'] ) { ?>
					
					<div class="company-info-item info-item">
						
						<span class="info-item-title-inline">
							
							<?= lang('sic'); ?>:
							
						</span>
						
						<span class="info-item-content-inline">
							
							<?= $company['sic']; ?>
							
						</span>
						
					</div>
					
					<?php } ?>
					
				</div>
				
				<?php if ( isset($company['phones']) AND is_array($company['phones']) AND $company['phones'] ) { ?>
				
				<div class="company-phones info-item">
					
					<span class="info-item-title">
						
						<?php if ( count( $company[ 'phones' ] ) > 1 ){ ?>
							
							<?= lang( 'phones' ); ?>
							
						<?php } else { ?>
						
							<?= lang( 'phone' ); ?>
							
						<?php } ?>
						
					</span>
					
					<?php foreach ($company['phones'] as $key => $phone) { ?>
					
					<span class="info-item-content">
						
						<?= (isset($phone['area_code']) AND $phone['area_code']) ? '('.$phone['area_code'].')' : ''; ?>
						<?= (isset($phone['number']) AND $phone['number']) ? $phone['number'] : ''; ?>
						<?= (isset($phone['extension_number']) AND $phone['extension_number']) ? $phone['extension_number'] : ''; ?>
						
						<?php if ( count( $company[ 'phones' ] ) > 1 ){ ?>
							
							<?= (isset($phone['title']) AND $phone['title']) ? '- '.$phone['title'] : ''; ?>
							
						<?php } ?>
						
					</span>
					
					<?php } ?>
					
				</div>
				
				<?php } ?>
				
				<?php if ( isset($company['emails']) AND is_array($company['emails']) AND $company['emails'] ) { ?>
				
				<div class="company-emails info-item">
					
					<span class="info-item-title">
						
						<?php if ( count( $company[ 'emails' ] ) > 1 ){ ?>
							
							<?= lang( 'emails' ); ?>
							
						<?php } else { ?>
						
							<?= lang( 'email' ); ?>
							
						<?php } ?>
						
					</span>
					
					<?php foreach ($company['emails'] as $key => $email) { ?>
					
					<span class="info-item-content">
						
						<?= (isset($email['email']) AND $email['email']) ? mailto(strip_tags($company['trading_name'].' %3C'.$email['email'].'%3E'),$email['email'],'class="email" title="'.lang('tip_email_click_to_open_your_mail_app').'"') : ''; ?>
						
						<?php if ( count( $company[ 'emails' ] ) > 1 ){ ?>
							
							<?= (isset($email['title']) AND $email['title']) ? '- '.$email['title'] : ''; ?>
							
						<?php } ?>
						
					</span>
					
					<?php } ?>
					
				</div>
				
				<?php } ?>
				
				<?php if ( isset($company['addresses']) AND is_array( $company[ 'addresses' ] ) AND $company[ 'addresses' ] ) { ?>
				
				<div class="company-addresses info-item">
					
					<span class="info-item-title">
						
						<?php if ( count( $company[ 'addresses' ] ) > 1 ){ ?>
							
							<?= lang( 'addresses' ); ?>
							
						<?php } else { ?>
						
							<?= lang( 'address' ); ?>
							
						<?php } ?>
						
					</span>
					
					<?php foreach ($company['addresses'] as $key => $address) { ?>
					
					<span class="info-item-content">
						
						<?php if ( count( $company[ 'addresses' ] ) > 1 ){ ?>
							
							<? $address['title'] = ( isset( $address[ 'title' ] ) AND $address[ 'title' ] ) ? $address[ 'title' ] : NULL; ?>
							
						<?php } else { ?>
							
							<? $address['title'] = NULL; ?>
							
						<?php } ?>
						
						<?= vui_el_address_box( array( 'address' => $address, ) ); ?> 
						
					</span>
					
					<?php } ?>
					
				</div>
				
				<?php } ?>
				
				<?php if ( isset($company['websites']) AND is_array($company['websites']) AND $company['websites'] ) { ?>
				
				<div class="company-info-item info-item">
					
					<span class="info-item-title">
						
						<?php if ( count( $company[ 'websites' ] ) > 1 ){ ?>
							
							<?= lang( 'websites' ); ?>
							
						<?php } else { ?>
						
							<?= lang( 'website' ); ?>
							
						<?php } ?>
						
					</span>
					
					<?php foreach ($company['websites'] as $key => $website) { ?>
					
					<div class="">
						
						<?php if ( count( $company[ 'websites' ] ) > 1 ){ ?>
							
							<?= (isset($website['title']) AND $website['title']) ? $website['title'] : ''; ?>: 
							
						<?php } ?>
						
						<?php
							
							$url = parse_url( $website[ 'url' ] );
							$url = ( isset( $url[ 'path' ] ) ? $url[ 'path' ] : ( isset( $url[ 'host' ] ) ? $url[ 'host' ] : $website[ 'url' ] ) );
							
						?>
						
						<?= anchor( prep_url( $website[ 'url' ] ) , $url, 'class="list-info-wrapper-website list-info-wrapper-website-' . url_title( $website[ 'title' ], '-', TRUE ) . '" target="_blank" title="' . $website['title'] . '"'); ?>
						
					</div>
					
					<?php } ?>
					
				</div>
				
				<?php } ?>
				
				<?php if ( isset($company['contacts']) AND is_array($company['contacts']) AND $company['contacts'] ) { ?>
				
				<div class="company-contacts info-item">
				
					<span class="info-item-title">
						
						<?= lang('contacts'); ?>
						
					</span>
					
					<?php foreach ($company['contacts'] as $key => $contact) { ?>
					
					<span class="info-item-content">
						
						<a href="<?= get_url('admin/contacts/contacts_management/edit_contact/'.$contact['id']); ?>" class="modal-contact list-info-wrapper" data-contact-id="<?= $contact['id']; ?>" target="_blank" >
							
							<span class="list-info-thumb-wrapper">
								<?php if ( $contact['thumb_local'] ){ ?>
								
								<?php echo img( array( 'src' => $contact['thumb_local'], 'width' => 24 ) ); ?>
								
								<?php } ?>
							</span>
							
							<?= $contact['name']; ?>
							
						</a>
						
					</span>
					
					<?php } ?>
					
				</div>
				
				<?php } ?>
				
				<div class="company-info-item info-item">
					<?= anchor('admin/'.$component_name.'/companies_management/edit_company/'.$company['id'],lang('action_edit_company'),'class="button" title="'.lang('tip_action_edit_company').'"'); ?>
				</div>
				
			</div>
			
			<script type="text/javascript" >
				
				$(document).ready(function(){
					
					modalContacts();
					
				});
				
			</script>
			
		</div>
		