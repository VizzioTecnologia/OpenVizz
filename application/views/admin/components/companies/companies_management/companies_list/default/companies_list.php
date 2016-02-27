		
			<header class="component-head">
				
				<h1>
					
					<?= lang( 'companies' ); ?>
					
				</h1>
				
			</header>
			
			<?php if ( $component_function == 'companies_select' ){ ?>
			
			<div class="form-actions to-toolbar">
				
				<?php echo form_open( get_url( 'admin' . $this->uri->ruri_string() ) ); ?>
					
					<?php echo form_submit( array( 'id' => 'submit-cancel', 'class' => 'button button-cancel', 'name' => 'submit_cancel', ),lang( 'action_cancel' ) ); ?>
					
				<?php echo form_close(); ?>
			
			</div>
			
			<?php } ?>
			
			<?php if ( isset( $companies ) AND $companies ){ ?>
			
			<script type="text/javascript">
			document.write( '<div class="filter"><input type="text" id="filter" placeholder="<?= lang( 'live_filter' ); ?>" class="live-filter" data-live-filter-for="table#companies-list tr" ></input></div>' );
			</script>
			
			<?php if ( $pagination ){ ?>
			<div class="pagination">
				<?= $pagination; ?>
			</div>
			<?php } ?>
			
			<table id="companies-list" class="data-list responsive">
				<tr>
					
					<th class="company-thumb">
						
						<?= lang( 'logo' ); ?>
						
					</th>
					
					<th class="company-trading-name order-by <?= ( $order_by == 'trading_name' ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">
						
						<?= anchor( get_url( 'admin' . '/' . $component_name.'/' . $component_function . '/'.'change_order_by'.'/trading_name' ) , lang( 'trading_name' ), 'class="" title="'. ( ( $order_by == 'trading_name' ) ? lang( 'ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) :  ( ( $order_by == 'trading_name' ) ? lang( 'ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) : lang( 'click_to_order_by_this_column' ) )  ) .'"' ); ?>
						
					</th>
					
					<th class="company-contacts order-by <?= ( $order_by == 'contacts' ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">
						
						<?= anchor( get_url( 'admin' . '/' . $component_name.'/' . $component_function . '/'.'change_order_by'.'/contacts' ) , lang( 'contacts' ), 'class="" title="'. ( ( $order_by == 'contacts' ) ? lang( 'ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) :  ( ( $order_by == 'contacts' ) ? lang( 'ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) : lang( 'click_to_order_by_this_column' ) )  ) .'"' ); ?>
						
					</th>
					
					<th class="operations">
						
						<?= lang( 'operations' ); ?>
						
					</th>
					
				</tr>
				
				<?php foreach( $companies as $company ){ ?>
				<tr>
					<td class="company-thumb ta-center">
						
						<?php if ( $company[ 'logo_thumb' ] ){ ?>
						
						<div class="company-thumb-wrapper">
							
							<?php if ( $company[ 'logo' ] ){ ?>
							
							<?= anchor( $company[ 'logo' ], img( array( 'src' => $company[ 'logo_thumb' ], 'width' => 50 ) ), 'target="_blank" class="company-logo-thumb" title="' . lang( 'tip_view_big_image' ) . '"' ); ?>
							
							<?php } ?>
							
						</div>
						
						<?php } ?>
						
					</td>
					
					<td class="company-trading-name <?= ( $order_by == 'trading_name' ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">
						
						<span class=" filter-me">
							
							<?php if ( $component_function == 'companies_select' ){ ?>
							
							<?= $company[ 'trading_name' ]; ?>
							
							<?php } else { ?>
							
							<?= anchor( 'admin/'.$component_name.'/' . $component_function . '/edit_company/'.$company[ 'id' ],$company[ 'trading_name' ],'class="list-link-cover-me" title="'.lang( 'action_edit' ).'"' ); ?>
							
							<?php } ?>
							
						</span>
						
					</td>
					
					<td class="company-contacts <?= ( $order_by == 'contacts' ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">
						
						<span class=" filter-me">
							
							<?php if ( isset( $company[ 'contacts' ] ) AND $company[ 'contacts' ] AND is_array( $company[ 'contacts' ] ) ){ ?>
							<?php foreach ( $company[ 'contacts' ] as $key => $contact ) { ?>
								
								<?php if ( $component_function == 'companies_select' ){ ?>
								
								<span class="list-info-wrapper" >
									
									<span class="list-info-thumb-wrapper">
										<?php if ( $contact[ 'thumb_local' ] ){ ?>
										
										<?= img( array( 'src' => $contact[ 'thumb_local' ], 'width' => 24 ) ); ?>
										
										<?php } ?>
									</span>
									
									<?= $contact[ 'name' ]; ?>
									
								</span>
								
								<?php } else { ?>
								
								<a rel="company-contacts-<?= $company[ 'id' ]; ?>" href="<?= get_url( 'admin/contacts/contacts_management/edit_contact/'.$contact[ 'id' ] ); ?>" data-mc-last-modal-group="<?= ( $this->input->get( 'last-modal-group' ) ? $this->input->get( 'last-modal-group' ) : 'company-contacts-' . $company[ 'id' ] ); ?>" data-mc-action="get" data-contact-id="<?= $contact[ 'id' ]; ?>" class="modal-contact list-info-wrapper" target="_blank" title="<?= strip_tags( $contact[ 'name' ] ); ?>">
									
									<span class="list-info-thumb-wrapper">
										<?php if ( $contact[ 'thumb_local' ] ){ ?>
										
										<?= img( array( 'src' => $contact[ 'thumb_local' ], 'width' => 24 ) ); ?>
										
										<?php } ?>
									</span>
									
									<?= $contact[ 'name' ]; ?>
									
								</a>
								
								<?php } ?>
								
							<?php } ?>
							
							<?php } ?>
							
						</span>
						
					</td>
					
					<td class="operations">
						
						<?php if ( $component_function == 'companies_select' ){ ?>
						
						<?= form_open( get_url( 'admin'.$this->uri->ruri_string() ) ); ?>
						<?= form_hidden( 'company_id',$company[ 'id' ] ); ?>
						<?= form_submit( array( 'id'=>'submit-select','name'=>'submit_select','class'=>'button button-select' ),lang( 'select' ) ); ?>
						<?= form_close(); ?>
						
						<?php } else { ?>
						
						<?= vui_el_button( array( 'url' => 'admin/' . $component_name . '/' . $component_function . '/edit_company/' . $company[ 'id' ], 'text' => lang( 'action_edit' ), 'icon' => 'edit', 'only_icon' => TRUE, ) ); ?>
						
						<?= vui_el_button( array( 'url' => 'admin/' . $component_name . '/' . $component_function . '/remove_company/' . $company[ 'id' ], 'text' => lang( 'action_delete' ), 'icon' => 'remove', 'only_icon' => TRUE, ) ); ?>
						
						<?php } ?>
						
					</td>
					
				</tr>
				<?php } ?>
			</table>
			
			<?php if ( $pagination ){ ?>
			<div class="pagination">
				<?= $pagination; ?>
			</div>
			<?php } ?>
			
			<script type="text/javascript" >
				
				$( document ).ready( function(){
					
					modalContacts();
					
				} );
				
			</script>
			
			<?php } else if ( $this->input->post( 'submit_search' ) AND ! isset( $companies ) AND ! $companies ) { ?>
				
				<?= lang( 'no_companies_founded' ); ?>
				
			<?php } else { ?>
				
				<?= vui_el_button( array( 'text' => lang( 'no_companies_records' ), 'icon' => 'error', ) ); ?>
				
				<?= vui_el_button( array( 'url' => 'admin/' . $component_name . '/companies_management/add_company', 'text' => lang( 'add_company' ), 'icon' => 'add-company', 'only_icon' => FALSE, ) ); ?>
				
			<?php } ?>
			
		</div>
		
		
		<?php
			
			$this->plugins->load( 'modal_contacts' );
			
		?>
		
		<?php if ( $this->plugins->load( 'fancybox' ) ){ ?>
		
		<script type="text/javascript" >
			
		$( document ).ready( function(){
			
			$( ".company-logo-thumb" ).fancybox();
			
		} );
		
		</script>
		
		<?php } ?>
		