
		<header>
			<h1><?php echo lang('confirm_delete'); ?></h1>
		</header>
		
		<div>
			
			<?php echo form_open('admin'.$this->uri->ruri_string()); ?>
			
			<p>
				<?php echo lang('company_confirm_delete'); ?>
				<b><?php echo $company->trading_name; ?></b>?
			</p>
				
			<?php echo form_submit(array('id'=>'submit','name'=>'submit'),lang('action_yes'),'autofocus'); ?>
			<?php echo form_submit(array('id'=>'submit-cancel','name'=>'submit_cancel'),lang('action_cancel')); ?>
				
			<?php echo form_hidden('company_id',$company->id); ?>
				
			<?php echo form_close(); ?>
			
		</div>

