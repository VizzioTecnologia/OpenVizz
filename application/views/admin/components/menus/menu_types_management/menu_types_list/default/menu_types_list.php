		
		<div>
			
			<header class="component-head">
				
				<h1>
					
					<?= lang( 'menu_types' ); ?>
					
				</h1>
				
			</header>
			
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
				
				<?php foreach( $menu_types as $menu_type ): ?>
				<tr>
					<td class="menu-type-id">
						
						<?= $menu_type[ 'id' ]; ?>
						
					</td>
					<td class="menu-type-title">
						
						<?= anchor( $this->menus->get_mi_url( 'list', array( 'menu_type_id' => $menu_type[ 'id' ], ) ), $menu_type[ 'title' ], 'class="" title="' . lang( 'action_view' ) . '"' ); ?>
						
					</td>
					<td class="menu-link">
						
						<?php echo $menu_type[ 'alias' ]; ?>
						
					</td>
					<td class="operations">
						
						<?= vui_el_button( array( 'url' => 'admin/' . $component_name . '/' . $component_function . '/edit_menu_type/' . $menu_type[ 'id' ], 'text' => lang( 'action_edit' ), 'icon' => 'edit', 'only_icon' => TRUE, ) ); ?>
						
						<?= vui_el_button( array( 'url' => 'admin/' . $component_name . '/' . $component_function . '/remove_menu_type/' . $menu_type[ 'id' ], 'text' => lang( 'action_delete' ), 'icon' => 'remove', 'only_icon' => TRUE, ) ); ?>
						
					</td>
				</tr>
				<?php endforeach; ?>
			</table>
			
		</div>