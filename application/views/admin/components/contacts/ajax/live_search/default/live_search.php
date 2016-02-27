	
	<div class="contact-list-live-search live-search-result-wrapper">
		
		<?php foreach ( $contacts as $key => $contact ) { ?>
			
			<div class="info-wrapper">
				
				<?php if ( isset( $contact['id'] ) AND $contact['id'] ){ ?>
				
				<a href="<?= get_url('admin/contacts/contacts_management/edit_contact/'.$contact['id']); ?>" class="contact-item live-search-result-item" data-contact-id="<?= $contact['id']; ?>" >
					
					<span class="thumb-wrapper">
						
						<span class="thumb-wrapper-content">
							<?php if ( $contact['thumb_local'] ){ ?>
							
							<?= img( array( 'src' => $contact['thumb_local'], 'width' => 24 ) ); ?>
							
							<?php } ?>
						</span>
						
					</span>
					
					<span class="contact-name-wrapper">
						
						<span class="contact-name-content">
							
							<?= $contact[ 'name' ]; ?>
							
						</span>
						
					</span>
					
				</a>
				
				<?php } ?>
				
			</div>
			
		<?php } ?>
	</div></div>
	</div>