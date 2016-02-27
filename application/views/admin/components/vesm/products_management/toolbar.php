		
		<div class="search-toolbar-wrapper fr">
			
			<?php echo form_open(get_url('admin/vesm/' . $component_function . '/search')); ?>
			
			<div class="vui-field-wrapper-inline">
				<?php echo form_input(array('id'=>'terms','name'=>'terms','class'=>'search-terms', 'title'=>(lang('products_search')."\n".lang('tip_terms'))), isset($search['terms']) ? $search['terms'] : ''); ?>
			</div>
			<div class="vui-field-wrapper-inline">
				<?php echo form_submit(array('id'=>'submit-search', 'class'=>'btn btn-find','name'=>'submit_search', 'title'=>lang('search')),lang('search')); ?>
			</div>
			<?php if ( $f_action == 'search' ){ ?>
			<div class="vui-field-wrapper-inline">
				<?php echo form_submit(array('id'=>'submit-cancel-search', 'class'=>'btn btn-goback','name'=>'submit_cancel_search', 'title'=>lang('cancel_search')),lang('cancel_search')); ?>
			</div>
			<?php } ?>
			
			<?php echo form_close(); ?>
			
		</div>
			