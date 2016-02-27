
		<header>
			<h1><?php echo lang('confirm_delete'); ?></h1>
		</header>
		
		<div>
			
			<?php echo form_open('admin'.$this->uri->ruri_string()); ?>
			
			<p>
				<?php echo lang('product_confirm_delete'); ?>
				<b><?php echo $product->title; ?></b>?
			</p>
				
			<?php echo form_submit(array('id'=>'submit','name'=>'submit'),lang('action_yes'),'autofocus'); ?>
			<?php echo form_submit(array('id'=>'submit-cancel','name'=>'submit_cancel'),lang('action_cancel')); ?>
				
			<?php echo form_hidden('product_id',$product->id); ?>
				
			<?php echo form_close(); ?>
			
		</div>

