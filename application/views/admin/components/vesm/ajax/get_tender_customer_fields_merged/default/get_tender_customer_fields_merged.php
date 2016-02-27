		
		<?php if ( isset($customer['phones']) AND $customer['phones'] ){ ?>
		
		<div id="phones-wrapper" class="info-items">
			
			<div class="info-title">
				
				<?= lang('phones'); ?>
				
			</div>
			
			<?php if ( isset($customer['phones']['company_phones']) AND $customer['phones']['company_phones'] ){ ?>
			
			<div class="customer-info-item info-item">
				
				<span class="info-item-title">
					
					<?= lang('company_phones'); ?>
					
				</span>
				
				<?php foreach ($customer['phones']['company_phones'] as $key => $phone) { ?>
					
					<?php
						
						$options = array(
							
							'wrapper-class' => 'radiobox-sub-item',
							'name' => 'phone_key',
							'text' => '',
							'value' => 'customer_' . $customer[ 'id' ] . '_phone_' . $phone[ 'key' ],
							
						);
						
						$options[ 'text' ] .= (isset($phone['area_code']) AND $phone['area_code']) ? '('.$phone['area_code'].') ' : '';
						$options[ 'text' ] .= (isset($phone['number']) AND $phone['number']) ? $phone['number'] : '';
						$options[ 'text' ] .= (isset($phone['extension_number']) AND $phone['extension_number']) ? $phone['extension_number'] : '';
						$options[ 'text' ] .= (isset($phone['title']) AND $phone['title']) ? ' - '.$phone['title'] : lang('phone').' '.$key;
						
						echo vui_el_radiobox( $options );
						
					?>
					
				<?php } ?>
				
			</div>
			
			<?php } ?>
			
			<?php if ( isset($customer['phones']['contact_phones']) AND $customer['phones']['contact_phones'] ){ ?>
			
			<div class="customer-info-item info-item">
				
				<span class="info-item-title">
					
					<?= lang('contact_phones'); ?>
					
				</span>
				
				<?php foreach ($customer['phones']['contact_phones'] as $key => $phone) { ?>
					
					<?php
						
						$options = array(
							
							'wrapper-class' => 'radiobox-sub-item',
							'name' => 'phone_key',
							'text' => '',
							'value' => 'contact_' . $customer[ 'contact' ][ 'id' ] . '_phone_' . $phone[ 'key' ],
							
						);
						
						$options[ 'text' ] .= (isset($phone['area_code']) AND $phone['area_code']) ? '('.$phone['area_code'].') ' : '';
						$options[ 'text' ] .= (isset($phone['number']) AND $phone['number']) ? $phone['number'] : '';
						$options[ 'text' ] .= (isset($phone['extension_number']) AND $phone['extension_number']) ? $phone['extension_number'] : '';
						$options[ 'text' ] .= (isset($phone['title']) AND $phone['title']) ? '- '.$phone['title'] : lang('phone').' '.$key;
						
						echo vui_el_radiobox( $options );
						
					?>
					
				<?php } ?>
				
			</div>
			
			<?php } ?>
			
		</div>
		
		<?php } ?>
		
		
		
		<?php if ( isset($customer['emails']) AND $customer['emails'] ){ ?>
		
		<div id="emails-wrapper" class="info-items">
			
			<div class="info-title">
				
				<?= lang('emails'); ?>
				
			</div>
			
			<?php if ( isset($customer['emails']['company_emails']) AND $customer['emails']['company_emails'] ){ ?>
			
			<div class="customer-info-item info-item">
				
				<span class="info-item-title">
					
					<?= lang('company_emails'); ?>
					
				</span>
				
				<?php foreach ($customer['emails']['company_emails'] as $key => $email) { ?>
					
					<?php
						
						$options = array(
							
							'wrapper-class' => 'radiobox-sub-item',
							'name' => 'email_key',
							'text' => '',
							'value' => 'customer_' . $customer[ 'id' ] . '_email_' . $email[ 'key' ],
							
						);
						
						$options[ 'text' ] .= (isset($email['email']) AND $email['email']) ? $email['email'] : '';
						$options[ 'text' ] .= (isset($email['title']) AND $email['title']) ? '- '.$email['title'] : lang('email').' '.$key;
						
						echo vui_el_radiobox( $options );
						
					?>
					
				<?php } ?>
				
			</div>
			
			<?php } ?>
			
			<?php if ( isset($customer['emails']['contact_emails']) AND $customer['emails']['contact_emails'] ){ ?>
			
			<div class="customer-info-item info-item">
				
				<span class="info-item-title">
					
					<?= lang('contact_emails'); ?>
					
				</span>
				
				<?php foreach ($customer['emails']['contact_emails'] as $key => $email) { ?>
					
					<?php
						
						$options = array(
							
							'wrapper-class' => 'radiobox-sub-item',
							'name' => 'email_key',
							'text' => '',
							'value' => 'contact_' . $customer[ 'contact' ][ 'id' ] . '_email_' . $email[ 'key' ],
							
						);
						
						$options[ 'text' ] .= (isset($email['email']) AND $email['email']) ? $email['email'] : '';
						$options[ 'text' ] .= (isset($email['title']) AND $email['title']) ? '- '.$email['title'] : lang('email').' '.$key;
						
						echo vui_el_radiobox( $options );
						
					?>
					
				<?php } ?>
				
			</div>
			
			<?php } ?>
			
		</div>
		
		<?php } ?>
		
		
		
		<?php if ( check_var( $customer[ 'addresses' ] ) ){ ?>
		
		<div id="addresses-wrapper" class="info-items">
			
			<div class="info-title">
				
				<?= lang('addresses'); ?>
				
			</div>
			
			<?php if ( check_var( $customer['addresses']['company_addresses'] ) ){ ?>
			
			<div class="customer-info-item info-item">
				
				<span class="info-item-title">
					
					<?= lang('company'); ?>
					
				</span>
				
				<?php foreach ( $customer[ 'addresses' ][ 'company_addresses' ] as $key => $address ) { ?>
					
					<?php
						
						if ( count( $customer[ 'addresses' ][ 'company_addresses' ] ) > 1 ){
							
							$address['title'] = ( isset( $address[ 'title' ] ) AND $address[ 'title' ] ) ? $address[ 'title' ] : NULL;
							
						} else {
							
							$address['title'] = NULL;
							
						}
						
						$options = array(
							
							'wrapper-class' => 'radiobox-sub-item',
							'name' => 'address_key',
							'text' => vui_el_address_box( array( 'address' => $address, ) ),
							'value' => 'customer_' . $customer[ 'id' ] . '_address_' . $address[ 'key' ],
							
						);
						
						echo vui_el_radiobox( $options );
						
					?>
					
				<?php } ?>
				
			</div>
			
			<?php } ?>
			
			<?php if ( isset($customer['addresses']['contact_addresses']) AND $customer['addresses']['contact_addresses'] ){ ?>
			
			<div class="customer-info-item info-item">
				
				<span class="info-item-title">
					
					<?= lang('contact'); ?>
					
				</span>
				
				<?php foreach ( $customer['addresses']['contact_addresses'] as $key => $address ) { ?>
					
					<?php
						
						if ( count( $customer['addresses']['contact_addresses'] ) > 1 ){
							
							$address['title'] = ( isset( $address[ 'title' ] ) AND $address[ 'title' ] ) ? $address[ 'title' ] : NULL;
							
						} else {
							
							$address['title'] = NULL;
							
						}
						
						$options = array(
							
							'wrapper-class' => 'radiobox-sub-item',
							'name' => 'address_key',
							'text' => vui_el_address_box( array( 'address' => $address, ) ),
							'value' => 'contact_' . $customer[ 'contact' ][ 'id' ] . '_address_' . $address[ 'key' ],
							
						);
						
						echo vui_el_radiobox( $options );
						
					?>
					
				<?php } ?>
				
			</div>
			
			<?php } ?>
			
		</div>
		
		<?php } ?>
		