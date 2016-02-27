		
		<?php echo form_open(get_url('admin/vesm/' . $component_function . '/search')); ?>
		<h4>
			<?php echo lang('search'); ?>
		</h4>
		<div class="vui-field-wrapper-inline">
			<?php echo form_label(lang('terms')); ?>
			<?php echo form_input(array('id'=>'terms','name'=>'terms','class'=>'terms', 'title'=>lang('tip_terms')), $search_fields['terms']); ?>
		</div>
		<div class="vui-field-wrapper-inline">
			<?php echo form_label('&nbsp;'); ?>
			<?php echo form_submit(array('id'=>'submit-search', 'class'=>'button','name'=>'submit_search'),lang('search')); ?>
		</div>
		<?php if ( $component_function_action == 'search' ){ ?>
		<div class="vui-field-wrapper-inline">
			<?php echo form_label('&nbsp;'); ?>
			<?php echo form_submit(array('id'=>'submit-cancel-search', 'class'=>'button button-cancel','name'=>'submit_cancel_search'),lang('cancel_search')); ?>
		</div>
		<?php } ?>
		
		<?php echo form_close(); ?>