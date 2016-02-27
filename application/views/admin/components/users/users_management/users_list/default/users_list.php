		
		<div>
			
			<header class="component-head">
				
				<h1>
					
					<?= lang( 'users' ); ?>
					
				</h1>
				
			</header>
			
			<table>
				<tr>
					<th>
						<?php echo lang('name'); ?>
					</th>
					<th>
						<?php echo lang('username'); ?>
					</th>
					<th>
						<?php echo lang('user_group'); ?>
					</th>
					<th>
						<?php echo lang('email'); ?>
					</th>
					<th class="op-column">
						<?php echo lang('operations'); ?>
					</th>
				</tr>
				
				<?php foreach($users as $user): ?>
				<tr>
					<td class="user-name">
						<?php echo anchor('admin/'.$component_name.'/' . $component_function . '/edit_user/'.base64_encode(base64_encode(base64_encode(base64_encode($user->id)))),$user->name,'class="" title="'.lang('action_view').'"'); ?>
					</td>
					<td class="user-username">
						<?php echo $user->username; ?>
					</td>
					<td class="user-usergroup">
						<?php if ($user->group_id > 0){ ?>
						<?php
							$user_group_path_array = $this->users->get_user_group_path($user->group_id);
							$last_element = end($user_group_path_array);
							foreach ($user_group_path_array as $key => $user_group) {
								echo anchor('admin/'.$component_name.'/users_groups_management/edit_users_group/'.$user_group['id'],$user_group['title'],'class="" title="'.$user_group['title'].'"').( $user_group != $last_element?' &#187; ':'' );
							}
						?>
						<?php } ?>
					</td>
					<td class="user-email">
						<?php echo $user->email; ?>
					</td>
					<td class="operations">
						
						<?= vui_el_button( array( 'url' => 'admin/' . $component_name . '/' . $component_function . '/edit_user/' . base64_encode( base64_encode( base64_encode( base64_encode( $user->id ) ) ) ), 'text' => lang( 'action_edit' ), 'icon' => 'edit', 'only_icon' => TRUE, ) ); ?>
						
						<?= vui_el_button( array( 'url' => 'admin/' . $component_name . '/' . $component_function . '/remove_user/' . base64_encode( base64_encode( base64_encode( base64_encode( $user->id ) ) ) ), 'text' => lang( 'action_delete' ), 'icon' => 'remove', 'only_icon' => TRUE, ) ); ?>
						
					</td>
				</tr>
				<?php endforeach; ?>
			</table>
			
		</div>