
		<header>
			<h1><?php echo lang('select_component_item'); ?></h1>
		</header>
		
		<div>
			
			<?php foreach($component_items as $row): ?>
			<?php echo anchor(
				'admin/'.$component_name.'/' . $component_function . '/add_menu_item/'.$menu_type_id . '/' . $selected_component_id . '/' . $row->id,
				lang($row->title),
				'class="add-menu-component-item add-menu-component-item-'.$row->alias.'"'
			); ?>
			<?php endforeach; ?>
			
		</div>

