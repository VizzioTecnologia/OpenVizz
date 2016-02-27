		
		<div>
			
			<header class="component-head">
				
				<h1>
					
					<?= lang( 'customers_categories' ); ?>
					
				</h1>
				
			</header>
			
			<?php if($categories) { ?>
			<table>
				<tr>
					<th>
						<?php echo lang('id'); ?>
					</th>
					<th>
						<?php echo lang('title'); ?>
					</th>
					<th class="op-column">
						<?php echo lang('operations'); ?>
					</th>
				</tr>
				
				<?php foreach($categories as $category) { ?>
				<tr class="category-level-<?php echo $category['level']; ?> tree-level-<?php echo $category['level']; ?>">
					<td class="menu-id ta-center">
						<?php echo $category['id']; ?>
					</td>
					<td class="menu-title tree-title">
						<?php if ($category['level'] > 0){ ?>
						<span class="btn btn-sub-item"></span>
						<?php } ?>
						<?php echo anchor('admin/'.$component_name.'/' . $component_function . '/edit_category/'.$category['id'],$category['title'],'class="" title="'.$category['title'].'"'); ?>
					</td>
					<td class="operations">
						
						<?= vui_el_button( array( 'url' => 'admin/' . $component_name . '/' . $component_function . '/edit_category/' . $category['id'], 'text' => lang( 'action_edit' ), 'icon' => 'edit', 'only_icon' => TRUE, ) ); ?>
						
						<?= vui_el_button( array( 'url' => 'admin/' . $component_name . '/' . $component_function . '/remove_category/' . $category['id'], 'text' => lang( 'action_delete' ), 'icon' => 'remove', 'only_icon' => TRUE, ) ); ?>
						
					</td>
				</tr>
				<?php }; ?>
			</table>
			<?php } else { ?>
			
			<p>
				<?php echo lang('categories_no_items_message'); ?>
				<?php echo anchor('admin/'.$component_name.'/' . $component_function . '/add/'.$layout,lang('new_category'),'class="" title="'.lang('action_add').'"'); ?>
			</p>
			
			<?php } ?>
			
		</div>