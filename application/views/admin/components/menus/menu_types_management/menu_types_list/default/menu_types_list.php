		
		<div>
			
			<header class="component-head">
				
				<h1>
					
					<?= lang( 'menu_types' ); ?>
					
				</h1>
				
			</header>
			
			<table class="data-list responsive multi-selection-table"">
				<tr>
					<th class="col-menu-type-id col-id">
						<?php echo lang('id'); ?>
					</th>
					<th class="col-menu-type-title col-title">
						<?php echo lang('title'); ?>
					</th>
					<th class="col-alias">
						<?php echo lang('alias'); ?>
					</th>
					<th class="col-operations">
						<?php echo lang('operations'); ?>
					</th>
				</tr>
				
				<?php foreach( $menu_types as $menu_type ): ?>
				<tr>
					<td class="col-menu-type-id col-id">
						
						<?= $menu_type[ 'id' ]; ?>
						
					</td>
					<td class="col-menu-type-title col-title">
						
						<?= anchor( $this->menus->get_mi_url( 'list', array( 'menu_type_id' => $menu_type[ 'id' ], ) ), $menu_type[ 'title' ], 'class="" title="' . lang( 'action_view' ) . '"' ); ?>
						
					</td>
					<td class="col-alias">
						
						<?php echo $menu_type[ 'alias' ]; ?>
						
					</td>
					<td class="col-operations">
						
						<?= vui_el_button( array( 'url' => 'admin/' . $component_name . '/' . $component_function . '/edit_menu_type/' . $menu_type[ 'id' ], 'text' => lang( 'action_edit' ), 'icon' => 'edit', 'only_icon' => TRUE, ) ); ?>
						
						<?= vui_el_button( array( 'url' => 'admin/' . $component_name . '/' . $component_function . '/remove_menu_type/' . $menu_type[ 'id' ], 'text' => lang( 'action_delete' ), 'icon' => 'remove', 'only_icon' => TRUE, ) ); ?>
						
					</td>
				</tr>
				<?php endforeach; ?>
			</table>
			
		</div>
