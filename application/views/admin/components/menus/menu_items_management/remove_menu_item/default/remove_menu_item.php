
		<header>
			<h1><?php echo lang('confirm_delete'); ?></h1>
		</header>
		
		<div>
			
			<?php echo form_open(get_url('admin'.$this->uri->ruri_string())); ?>
			
			<p>
				<?php echo lang('Are you sure you want to delete the menu item'); ?>
				<b><?php echo $menu_item[ 'title' ]; ?></b>
				<?php echo lang('from'); ?>
				<b><?php echo $menu_item[ 'menu_type_title' ]; ?></b>?
			</p>
				
			<?php echo form_submit(array('id'=>'submit','name'=>'submit'),lang('action_yes'),'autofocus'); ?>
			<?php echo form_submit(array('id'=>'submit-cancel','name'=>'submit_cancel'),lang('action_cancel')); ?>
				
			<?php echo form_hidden('menu_item_id',$menu_item[ 'id' ]); ?>
				
			<?php echo form_close(); ?>
			
		</div>

