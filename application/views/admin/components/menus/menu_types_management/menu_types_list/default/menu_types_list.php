		
		<div>
			
			<header class="component-head">
				
				<h1>
					
					<?= lang( 'menu_types' ); ?>
					
				</h1>
				
			</header>
			
			<table>
				<tr>
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
				
				<?php foreach($menu_types as $row): ?>
				<tr>
					<td class="menu-type-title">
						
						<?php echo anchor( 'admin/' . $component_name . '/mim/mtid/' . $row->id . '/a/mil', $row->title, 'class="" title="' . lang( 'action_view' ) . '"' ); ?>
						
					</td>
					<td class="menu-link">
						
						<?php echo $row->alias; ?>
						
					</td>
					<td class="operations">
						
						<?= vui_el_button( array( 'url' => 'admin/' . $component_name . '/' . $component_function . '/edit_menu_type/' . $row->id, 'text' => lang( 'action_edit' ), 'icon' => 'edit', 'only_icon' => TRUE, ) ); ?>
						
						<?= vui_el_button( array( 'url' => 'admin/' . $component_name . '/' . $component_function . '/remove_menu_type/' . $row->id, 'text' => lang( 'action_delete' ), 'icon' => 'remove', 'only_icon' => TRUE, ) ); ?>
						
					</td>
				</tr>
				<?php endforeach; ?>
			</table>
			
		</div>