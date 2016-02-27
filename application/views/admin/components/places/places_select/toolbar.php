		
		<h1 class="component-name">
			
			<?= lang( $component_name ); ?>
			
		</h1>
		
		<?= form_open(get_url('admin/places/' . $component_function . '/search')); ?>
		<h4>
			<?php echo lang('search'); ?>
		</h4>
		<div class="vui-field-wrapper-inline">
			<?php echo form_label(lang('postal_code')); ?>
			<?php echo form_input(array('id'=>'postal_code','name'=>'postal_code','class'=>'address-postal-code', 'title'=>lang('tip_search_postal_code')), $search_fields['postal_code']); ?>
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