
		<header>
			<h1><?php echo lang('confirm_delete'); ?></h1>
		</header>
		
		<div>
			
			<?php echo form_open(get_url('admin'.$this->uri->ruri_string())); ?>
			
			<p>
				<?php echo lang('menu_type_delete_confirm_delete'); ?>
			</p>
			
			<p>
				<?php echo lang('title'); ?>: <b><?php echo $menu_type->title; ?></b>
			</p>
			
			<p>
				<?php echo lang('alias'); ?>: <b><?php echo $menu_type->alias; ?></b>
			</p>
			
			<p>
				<?php echo lang('description'); ?>:
			</p>
			
			<div>
				<?php echo $menu_type->description; ?>
			</div>
				
			<?php echo form_submit(array('id'=>'submit','name'=>'submit'),lang('action_yes'),'autofocus'); ?>
			<?php echo form_submit(array('id'=>'submit-cancel','name'=>'submit_cancel'),lang('action_cancel')); ?>
				
			<?php echo form_hidden('menu_type_id',$menu_type->id); ?>
				
			<?php echo form_close(); ?>
			
		</div>

