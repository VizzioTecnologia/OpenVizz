
		<header>
			<h1><?php echo lang('add_city'); ?> - <?php echo $neighborhood->title; ?> - <?php echo $city->title; ?> - <?php echo $state->title; ?> - <?php echo lang($country->title); ?></h1>
		</header>
		
		<div>
			
			<?php echo form_open(get_url('admin'.$this->uri->ruri_string())); ?>
				
				<div class="form-actions">
					
					<?php echo form_submit(array('id'=>'submit','name'=>'submit','class'=>'button button-save'),lang('action_save')); ?>
					<?php echo form_submit(array('id'=>'submit-apply','class'=>'button button-apply','name'=>'submit_apply'),lang('action_apply')); ?>
					<?php echo form_submit(array('id'=>'submit-cancel','class'=>'button button-cancel','name'=>'submit_cancel'),lang('action_cancel')); ?>
					
				</div>
				
				<fieldset class="fl">
					
					<legend><?php echo lang('details'); ?></legend>
					
					<?php echo form_error('title', '<div class="msg-inline-error">', '</div>'); ?>
					<?php echo form_label(lang('title')); ?>
					<?php echo form_input(array('id'=>'title','name'=>'title'),set_value('title'),'autofocus'); ?>
					
					<?php echo form_error('postal_code', '<div class="msg-inline-error">', '</div>'); ?>
					<?php echo form_label(lang('postal_code')); ?>
					<?php echo form_input(array('id'=>'postal-code','name'=>'postal_code'),set_value('postal_code')); ?>
					
					<?php echo form_error('coordinates', '<div class="msg-inline-error">', '</div>'); ?>
					<?php echo form_label(lang('coordinates')); ?>
					<?php echo form_input(array('id'=>'coordinates','name'=>'coordinates'),set_value('coordinates')); ?>
					
					<?php echo form_error('map_url', '<div class="msg-inline-error">', '</div>'); ?>
					<?php echo form_label(lang('map_url')); ?>
					<?php echo form_input(array('id'=>'map-url','name'=>'map_url'),set_value('map_url')); ?>
					
					<?php echo form_hidden('neighborhood_id',$neighborhood->id); ?>
					
					<?php echo form_hidden('city_id',$city->id); ?>
					
					<?php echo form_hidden('state_id',$state->id); ?>
					
					<?php echo form_hidden('country_id',$country->id); ?>
					
				</fieldset>
				
				<div class="clear"></div>
				
				<div class="form-actions">
					
					<?php echo form_submit(array('id'=>'submit','name'=>'submit','class'=>'button button-save'),lang('action_save')); ?>
					<?php echo form_submit(array('id'=>'submit-apply','class'=>'button button-apply','name'=>'submit_apply'),lang('action_apply')); ?>
					<?php echo form_submit(array('id'=>'submit-cancel','class'=>'button button-cancel','name'=>'submit_cancel'),lang('action_cancel')); ?>
					
				</div>
				
			<?php echo form_close(); ?>
			
		</div>

