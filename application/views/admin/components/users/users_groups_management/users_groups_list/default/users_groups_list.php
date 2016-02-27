		
		<div>
			
			<header class="component-head">
				
				<h1>
					
					<?= lang( 'users_groups' ); ?>
					
				</h1>
				
			</header>
			
			<?php if($users_groups) { ?>
			<table>
				<tr>
					<th>
						<?php echo lang('id'); ?>
					</th>
					<th>
						<?php echo lang('title'); ?>
					</th>
					<th>
						<?php echo lang('alias'); ?>
					</th>
					<th class="op-column">
						<?php echo lang('operations'); ?>
					</th>
				</tr>
				
				<?php foreach($users_groups as $user_group) { ?>
				<tr class="user-group-level-<?php echo $user_group['level']; ?> tree-level-<?php echo $user_group['level']; ?>">
					<td class="user-group-id ta-center">
						<?php echo $user_group['id']; ?>
					</td>
					<td class="user-group-title tree-title">
						<?php if ($user_group['level'] > 0){ ?>
						<span class="btn btn-sub-item"></span>
						<?php } ?>
						<?php echo anchor('admin/'.$component_name.'/' . $component_function . '/edit_users_group/'.$user_group['id'],$user_group['title'],'class="" title="'.$user_group['title'].'"'); ?>
					</td>
					<td class="user-group-alias ta-center">
						<?php echo $user_group['alias']; ?>
					</td>
					<td class="operations">
						
						<?= vui_el_button( array( 'url' => 'admin/' . $component_name . '/' . $component_function . '/edit_users_group/' . $user_group['id'], 'text' => lang( 'action_edit' ), 'icon' => 'edit', 'only_icon' => TRUE, ) ); ?>
						
						<?= vui_el_button( array( 'url' => 'admin/' . $component_name . '/' . $component_function . '/remove_users_group/' . $user_group['id'], 'text' => lang( 'action_delete' ), 'icon' => 'remove', 'only_icon' => TRUE, ) ); ?>
						
					</td>
				</tr>
				<?php }; ?>
			</table>
			<?php } else { ?>
			
			<p>
				<?php echo lang('users_groups_no_items_message'); ?>
				<?php echo anchor('admin/'.$component_name.'/' . $component_function . '/add_users_group',lang('new_category'),'class="" title="'.lang('action_add').'"'); ?>
			</p>
			
			<?php } ?>
			
		</div>