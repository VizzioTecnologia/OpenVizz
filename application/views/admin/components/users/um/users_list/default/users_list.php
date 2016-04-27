
<div id="users-list-wrapper" class="items-list">
	
	<?php if ( check_var( $users ) ){ ?>
		
		<?= form_open( get_url( 'admin/users/um/a/b' ), array( 'id' => 'users-list-form', ) ); ?>
		
		<table class="data-list responsive multi-selection-table">
			
			<tr>
				
				<?php $current_column = 'id'; ?>
				
				<th class="col-<?= $current_column; ?>">
					
					<?= lang( $current_column ); ?>
					
				</th>
				
				<?php $current_column = 'name'; ?>
				
				<th class="col-<?= $current_column; ?>">
					
					<?= lang( $current_column ); ?>
					
				</th>
				
				<?php $current_column = 'username'; ?>
				
				<th class="col-<?= $current_column; ?>">
					
					<?= lang( $current_column ); ?>
					
				</th>
				
				<?php $current_column = 'email'; ?>
				
				<th class="col-<?= $current_column; ?>">
					
					<?= lang( $current_column ); ?>
					
				</th>
				
				<?php $current_column = 'user_group'; ?>
				
				<th class="col-<?= $current_column; ?>">
					
					<?= lang( $current_column ); ?>
					
				</th>
				
				<?php $current_column = 'status'; ?>
				
				<th class="col-<?= $current_column; ?>">
					
					<?= lang( $current_column ); ?>
					
				</th>
				
				<?php $current_column = 'operations'; ?>
				
				<th class="col-<?= $current_column; ?> op-column">
					
					<?= lang( $current_column ); ?>
					
				</th>
				
			</tr>
			
			<?php foreach( $users as $user ) {
				
				$edit_link = $this->users->admin_get_link_edit( $user[ 'id' ] );
				$remove_link = $this->users->admin_get_link_remove( $user[ 'id' ] );
				$enable_link = $this->users->admin_get_link_enable( $user[ 'id' ] );
				$disable_link = $this->users->admin_get_link_disable( $user[ 'id' ] );
				
			?>
				
				<tr class="<?= ( check_var( $user[ 'status' ] ) ) ? 'active enabled' : 'inactive disabled'; ?>">
					
					<?php $current_column = 'id'; ?>
					
					<td class="col-<?= $current_column; ?>">
						
						<?= $user[ $current_column ]; ?>
						
					</td>
					
					<?php $current_column = 'name'; ?>
					
					<td class="col-<?= $current_column; ?>">
						
						<?= anchor( $edit_link, $user[ $current_column ], 'class="" title="' . lang( 'action_view' ) . '"' ); ?>
						
					</td>
					
					<?php $current_column = 'username'; ?>
					
					<td class="col-<?= $current_column; ?>">
						
						<?= $user[ $current_column ]; ?>
						
					</td>
					
					<?php $current_column = 'email'; ?>
					
					<td class="col-<?= $current_column; ?>">
						
						<?= $user[ $current_column ]; ?>
						
					</td>
					
					<?php $current_column = 'user_group'; ?>
					
					<td class="col-<?= $current_column; ?>">
						
						<?= $user[ $current_column ]; ?>
						
					</td>
					
					<?php $current_column = 'status'; ?>
					
					<td class="col-<?= $current_column; ?>">
						
						<?php if ( $user[ 'id' ] != $this->users->user_data[ 'id' ] ) {
							
							echo vui_el_button( array(
								
								'url' => ( $user[ 'status' ] == 1 ) ? $disable_link : $enable_link,
								'text' => lang( ( $user[ 'status' ] == 1 ) ? 'account_enabled' : 'account_disabled' ),
								'title' => lang( ( $user[ 'status' ] == 1 ) ? 'action_account_enabled_click_to_disable' : 'action_account_disabled_click_to_enable' ),
								'icon' => ( $user[ 'status' ] == 1 ) ? 'enabled' : 'disabled',
								'only_icon' => TRUE, )
								
							);
							
						} ?>
						
					</td>
					
					<?php $current_column = 'operations'; ?>
					
					<td class="col-<?= $current_column; ?>">
						
						<?= vui_el_button( array( 'url' => $edit_link, 'text' => lang( 'action_edit' ), 'icon' => 'edit', 'only_icon' => TRUE, ) ); ?>
						
						<?= vui_el_button( array( 'url' => $remove_link, 'text' => lang( 'action_delete' ), 'icon' => 'remove', 'only_icon' => TRUE, ) ); ?>
					
					</td>
					
				</tr>
				
			<?php } ?>
			
		</table>
		
		<?= form_close(); ?>
		
	<?php } ?>
	