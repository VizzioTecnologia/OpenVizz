		
		<div class="search-toolbar-wrapper fr">
			
			<?= form_open(get_url('admin/' . $component_name . '/'. $component_function .'/search')); ?>
			
			<div class="vui-field-wrapper-inline">
				
				<?= form_input(array( 'id'=>'terms','placeholder'=>lang('search'), 'name'=>'terms', 'class'=>'search-terms', 'title'=>lang('tip_terms') ), isset($search['terms']) ? $search['terms'] : ''); ?>
				
			</div>
			<div class="vui-field-wrapper-inline">
				
				<?= vui_el_button( array( 'text' => lang( 'search' ), 'icon' => 'search', 'only_icon' => TRUE, 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_search', 'id' => 'submit-search', ) ); ?>
				
				<?php if ( $component_function_action == 'search' ){ ?>
				
				<?= vui_el_button( array( 'text' => lang( 'cancel_search' ), 'icon' => 'cancel', 'only_icon' => TRUE, 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_cancel_search', 'id' => 'submit-cancel-search', ) ); ?>
				
				<?php } ?>
				
			</div>
			
			<?= form_close(); ?>
			
			<div class="clear"></div>
			
		</div>
		