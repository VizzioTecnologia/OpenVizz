		<!--
		<div class="">
			
			<?php if ( $auth_link ) { ?>
					
					<?= vui_el_button( array( 'class' => 'to-toolbar', 'get_url' => FALSE, 'url' => $auth_link, 'icon' => 'google', 'text' => lang( 'connect_to_google' ) ) ); ?>
					
			<?php } else { ?>
					
					<?= vui_el_button( array( 'class' => 'to-toolbar', 'get_url' => FALSE, 'url' => get_url( 'admin' . $this->uri->ruri_string() ) . '?logout', 'icon' => 'google', 'text' => lang( 'disconnect_from_google' ) ) ); ?>
					
			<?php } ?>
			
		</div>
		-->
		
		<?php if ( check_var( $google_contacts ) ){ ?>
			
		<div>
			
			<header class="component-head">
				
				<h1>
					
					<?= lang( 'google_contacts' ); ?>
					
				</h1>
				
			</header>
			
			<?php if ( check_var( $pagination ) ){ ?>
			<div class="pagination">
				<?php echo $pagination; ?>
			</div>
			<?php } ?>
			
			<?= form_open( get_url( 'admin'.$this->uri->ruri_string() ), array( 'id' => 'google_contacts-form' ) ); ?>
			
			<div class="form-actions">
			
			<?= vui_el_button( array( 'class' => 'to-toolbar', 'button_type' => 'button', 'icon' => 'save', 'only_icon' => TRUE, 'text' => lang( 'import_selected_contacts' ) ) ); ?>
			
			</div>
			
			<table>
				<tr>
					<th>
						<?php echo lang('photo'); ?>
					</th>
					<th>
						<?php echo lang('name'); ?>
					</th>
					<th>
						<?php echo lang('emails'); ?>
					</th>
					<th>
						<?php echo lang('phones'); ?>
					</th>
					<th class="op-column">
						<?php echo lang('operations'); ?>
					</th>
				</tr>
				
				<?php foreach( $google_contacts as $contact ): ?>
				<tr>
					<td class="contact-thumb ta-center">
						
						<div class="contact-thumb-wrapper">
							
						<?php if ( check_var( $contact[ 'thumb_local' ] ) ){ ?>
						
						<img src="<?= $contact['thumb_local']; ?>" />
						
						<?php } ?>
						
						</div>
						
					</td>
					<td class="contact-name">
						<?= $contact[ 'name' ]; ?>
					</td>
					
					<td class="contact-emails">
						
						<?php if ( check_var( $contact[ 'emails' ] ) ) { ?>
						
						<?php foreach ( $contact[ 'emails' ] as $key => $email ) { ?>
						
						<div class="email">
							
							<?= (isset($email['email']) AND $email['email']) ? mailto(strip_tags($contact['name'].' %3C'.$email['email'].'%3E'),$email['email'],'class="email" title="'.lang('tip_email_click_to_open_your_mail_app').'"') : ''; ?>
							<?= (isset($email['title']) AND $email['title']) ? '- '.$email['title'] : lang('email').' '.$key; ?> 
							
						</div>
						
						<?php } ?>
						
						<?php } ?>
						
					</td>
					<td class="contact-phones">
						
						<?php if ( check_var( $contact[ 'phones' ] ) ) { ?>
						
						<?php foreach ( $contact[ 'phones' ] as $key => $phone ) { ?>
						
						<div class="phone">
							
							<?= (isset($phone['area_code']) AND $phone['area_code']) ? '('.$phone['area_code'].')' : ''; ?>
							<?= (isset($phone['number']) AND $phone['number']) ? $phone['number'] : ''; ?>
							<?= (isset($phone['extension_number']) AND $phone['extension_number']) ? $phone['extension_number'] : ''; ?>
							<?= (isset($phone['title']) AND $phone['title']) ? '- '.$phone['title'] : lang('phone').' '.$key; ?> 
							
						</div>
						
						<?php } ?>
						
						<?php } ?>
						
					</td>
					<td class="operations">
						
						<input type="checkbox" name="contact_action" value="<?= $contact[ 'gc_id' ]; ?>" />
						
					</td>
				</tr>
				<?php endforeach; ?>
			</table>
			
			<?= form_close(); ?>
			
			<?php if ( check_var( $pagination ) ){ ?>
			<div class="pagination">
				<?php echo $pagination; ?>
			</div>
			<?php } ?>
			
		</div>
		
		<?php } else { ?>
			
			
			
		<?php } ?>
