
		<header>
			<h1><?php echo lang('confirm_delete'); ?></h1>
		</header>
		
		<div>
			
			<?php echo form_open(get_url('admin'.$this->uri->ruri_string())); ?>
			
			<p>
				<?php echo lang('users_group_confirm_delete'); ?>
				<b><?php echo $users_group->title; ?></b>?
			</p>
			
			<?php echo form_submit(array('id'=>'submit','name'=>'submit'),lang('action_yes'),'autofocus'); ?>
			<?php echo form_submit(array('id'=>'submit-cancel','name'=>'submit_cancel'),lang('action_cancel')); ?>
				
			<?php echo form_hidden('users_group_id',$users_group->id); ?>
				
			<?php echo form_close(); ?>
			
		</div>

