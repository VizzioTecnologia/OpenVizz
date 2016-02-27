		
		<div>
			
			<header class="component-head">
				
				<h1>
					
					<?= lang( 'contacts' ); ?>
					
				</h1>
				
			</header>
			
			<?php if ( $pagination ){ ?>
			<div class="pagination">
				<?php echo $pagination; ?>
			</div>
			<?php } ?>
			
			<table>
				<tr>
					<th>
						<?php echo lang( 'photo' ); ?>
					</th>
					<th>
						<?php echo lang( 'name' ); ?>
					</th>
					<th>
						<?php echo lang( 'user_associated' ); ?>
					</th>
					<th>
						<?php echo lang( 'emails' ); ?>
					</th>
					<th>
						<?php echo lang( 'phones' ); ?>
					</th>
					<th class="op-column">
						<?php echo lang( 'operations' ); ?>
					</th>
				</tr>
				
				<?php foreach( $contacts as $contact ): ?>
				<tr>
					<td class="contact-thumb ta-center">
						<?php if ( $contact[ 'thumb_local' ] ){ ?>
						
						<div class="contact-thumb-wrapper">
							
							<?php if ( $contact[ 'photo_local' ] ){ ?>
							
							<?= anchor( $contact[ 'photo_local' ], img( array( 'src' => $contact[ 'thumb_local' ], 'width' => 50 ) ),'target="_blank" class="contact-photo-thumb" title="'.lang( 'tip_view_big_image' ).'"' ); ?>
							
							<?php } ?>
							
						</div>
						
						<?php } else if ( $contact[ 'thumb_url' ] ){ ?>
						
						<div class="contact-thumb-wrapper">
							
							<?php if ( $contact[ 'user_id_assoc' ] ){ ?>
							
							<?php echo anchor( $contact[ 'photo_url' ], img( array( 'src' => $contact[ 'thumb_url' ], 'width' => 50 ) ),'target="_blank" class="contact-photo-thumb" title="'.lang( 'tip_view_big_image' ).'"' ); ?>
							
							<?php } else { ?>
							
							<?php echo img( array( 'src' => $contact[ 'thumb_url' ], 'width' => 50 ) ); ?>
							
							<?php } ?>
							
						</div>
						
						<?php } ?>
					</td>
					<td class="contact-name">
						<?php echo anchor( 'admin/'.$component_name.'/' . $component_function . '/edit_contact/'.$contact[ 'id' ],$contact[ 'name' ],'class="" title="'.lang( 'tip_action_edit_contact' ).'"' ); ?>
					</td>
					<td class="user-associated">
						<?php if ( $contact[ 'user_id_assoc' ] ){ ?>
							
						<?php echo anchor( 'admin/users/users_management/edit_user/'.base64_encode( base64_encode( base64_encode( base64_encode( $contact[ 'user_id_assoc' ] ) ) ) ),$contact[ 'username_assoc' ],'class="" title="'.lang( 'action_view' ).'"' ); ?>
						
						<?php } ?>
					</td>
					<td class="contact-emails">
						<?php
							if ( $contact[ 'emails' ] ){
								$emails = json_decode( $contact[ 'emails' ], TRUE );
								if ( $emails )
								echo mailto( strip_tags( $contact[ 'name' ].' <'.$emails[1][ 'email' ].'>' ),$emails[1][ 'email' ],'class="email" title="'.lang( 'tip_email_click_to_open_your_mail_app' ).'"' ).( ( isset( $emails[1][ 'title' ] ) AND $emails[1][ 'title' ] != '' )?' - '.$emails[1][ 'title' ]:'' );
							}
						?>
					</td>
					<td class="contact-phones">
						<?php
							if ( $contact[ 'phones' ] ){
								$phones = json_decode( $contact[ 'phones' ], TRUE );
								if ( $phones )
								foreach ( $phones as $phone ) {
									echo ( ( isset( $phone[ 'code_area' ] ) AND $phone[ 'code_area' ] )?'( '.$phone[ 'code_area' ].' ) ':'' ).( ( isset( $phone[ 'number' ] ) AND $phone[ 'number' ] )?$phone[ 'number' ]:'' ).( ( isset( $phone[ 'title' ] ) AND $phone[ 'title' ]!='' )?' - '.$phone[ 'title' ]:'' ).'<br/>';
								}
							}
						?>
					</td>
					<td class="operations">
						
						<?= vui_el_button( array( 'url' => 'admin/' . $component_name . '/' . $component_function . '/edit_contact/' . $contact[ 'id' ], 'text' => lang( 'action_edit' ), 'icon' => 'edit', 'only_icon' => TRUE, ) ); ?>
						
						<?= vui_el_button( array( 'url' => 'admin/' . $component_name . '/' . $component_function . '/remove_contact/' . $contact[ 'id' ], 'text' => lang( 'action_delete' ), 'icon' => 'remove', 'only_icon' => TRUE, ) ); ?>
						
					</td>
				</tr>
				<?php endforeach; ?>
			</table>
			
			<?php if ( $pagination ){ ?>
			<div class="pagination">
				<?php echo $pagination; ?>
			</div>
			<?php } ?>
			
		</div>
		
		<?php if ( $this->plugins->load( 'fancybox' ) ){ ?>
		
		<script type="text/javascript" >
			
		$( document ).ready( function(){
			
			$( ".contact-photo-thumb" ).fancybox();
			
		} );
		
		</script>
		
		<?php } ?>
		
		
		
		
		
		
		
		
		
		