
		<header>
			<h1><?php echo lang('select_component'); ?></h1>
		</header>
		
		<div>
			
			<?php foreach($components as $row): ?>
			<?php echo anchor(
				'admin/'.$component_name.'/' . $component_function . '/add_menu_item/'.$menu_type_id . '/' . $row->id,
				lang($row->title),
				'class="add-menu-component add-menu-component-'.$row->alias.'"'
			); ?>
			<?php endforeach; ?>
			
		</div>

