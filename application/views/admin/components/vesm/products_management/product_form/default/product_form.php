
		<header>
			<?php if ( $f_action == 'add_product' ) { ?>
			<h1><?php echo lang('add_product'); ?></h1>
			<?php } else if ( $f_action == 'edit_product' ) { ?>
			<h1><?php echo lang('edit_product'); ?></h1>
			<?php } ?>
		</header>
		
		<div>
			
			<?php echo form_open_multipart(get_url('admin'.$this->uri->ruri_string())); ?>
				
				<div class="form-actions">
					
					<?php echo form_submit(array('id'=>'submit','name'=>'submit','class'=>'button button-save'),lang('action_save')); ?>
					<?php echo form_submit(array('id'=>'submit-apply','class'=>'button button-apply','name'=>'submit_apply'),lang('action_apply')); ?>
					<?php echo form_submit(array('id'=>'submit-cancel','class'=>'button button-cancel','name'=>'submit_cancel'),lang('action_cancel')); ?>
					
				</div>
				
				<fieldset class="fl">
					
					<legend><?php echo lang('product_details'); ?></legend>
					
					<div class="vui-field-wrapper-inline">
						<?php echo form_label(lang('code_on_provider')); ?>
						<?php echo form_input(array('id'=>'code-on-provider', 'class'=>'code-on-provider', 'name'=>'code_on_provider'),isset($fields_array['code_on_provider'])?$fields_array['code_on_provider']:'', 'autofocus'); ?>
					</div>
					
					<div class="vui-field-wrapper-inline">
						<?php echo form_label(lang('product_code')); ?>
						<?php echo form_input(array('id'=>'code', 'class'=>'code', 'name'=>'code'),isset($fields_array['code'])?$fields_array['code']:''); ?>
					</div>
					
					<div class="vui-field-wrapper-inline">
						<?php echo form_label(lang('product_mcn')); ?>
						<?php echo form_input(array('id'=>'mcn', 'class'=>'mcn', 'name'=>'mcn'),isset($fields_array['mcn'])?$fields_array['mcn']:''); ?>
					</div>
					
					<div class="vui-field-wrapper-inline">
						<?php echo form_label(lang('product_title')); ?>
						<?php echo form_input(array('id'=>'title', 'class'=>'title', 'name'=>'title'),isset($fields_array['title'])?$fields_array['title']:''); ?>
					</div>
					
					<div class="vui-field-wrapper-inline">
						<?php echo form_label(lang('product_unit')); ?>
						<?php echo form_input(array('id'=>'unit', 'class'=>'unit', 'name'=>'unit'),isset($fields_array['unit'])?$fields_array['unit']:''); ?>
					</div>
					
					<div class="vui-field-wrapper-inline">
						<?php echo form_label(lang('product_provider_tax')); ?>
						<?php echo form_input(array('id'=>'product-provider-tax', 'class'=>'product-provider-tax', 'title' => lang('tip_product_provider_tax'), 'name'=>'product_provider_tax'), isset($fields_array['product_provider_tax']) ? $fields_array['product_provider_tax'] : '' ); ?>
						<span class="input-near-complement">%</span>
					</div>
					
					<div class="vui-field-wrapper-inline">
						<?php echo form_label(lang('delivery_time')); ?>
						<?php echo form_input(array('id'=>'delivery-time', 'class'=>'delivery-time', 'title' => lang('tip_delivery_time'), 'name'=>'delivery_time'), isset($fields_array['delivery_time']) ? $fields_array['delivery_time'] : '' ); ?>
					</div>
					
					<div class="clear"></div>
					
					<div class="vui-field-wrapper-inline">
						<?php echo form_error('provider_id', '<div class="msg-inline-error">', '</div>'); ?>
						<?php echo form_label(lang('provider')); ?>
						<?php
							$options = array();
							if ( $providers ) {
								foreach($providers as $key => $row):
									
									$options[$row['id']] = $row['title'];
									
								endforeach;
							}
							natsort($options);
						?>
						<?php echo form_dropdown('provider_id', $options, isset($fields_array['provider_id']) ? $fields_array['provider_id'] : '','id="provider" class="provider"'); ?>
						<?php $options = array(); ?>
					</div>
					
					<div class="vui-field-wrapper-inline">
						<?php echo form_error('warranty', '<div class="msg-inline-error">', '</div>'); ?>
						<?php echo form_label(lang('product_warranty')); ?>
						<?php
							
							if ( $warranties ) {
								foreach($warranties as $key => $row):
									
									$options[$row['title']] = $row['title'];
									
								endforeach;
							}
							natsort($options);
							$options = array_merge_recursive(array(0=>lang('custom')), $options);
						?>
						<?php echo form_dropdown('warranty', $options, isset($fields_array['warranty']) ? $fields_array['warranty'] : '','id="warranty" class="warranty"'); ?>
						<?php $options = array(); ?>
					</div>
					
					<div class="vui-field-wrapper-inline">
						<?php echo form_label(lang('product_custom_warranty')); ?>
						<?php echo form_input(array('id'=>'custom-warranty', 'class'=>'custom-warranty', 'name'=>'custom_warranty'),isset($fields_array['custom_warranty'])?$fields_array['custom_warranty']:''); ?>
					</div>
					
					<div class="clear"></div>
					
					<div class="vui-field-wrapper-inline">
						<?php echo form_label(lang('product_cost_price')); ?>
						<span class="input-near-complement">R$</span><?php echo form_input(array('id'=>'cost-price', 'class'=>'cost-price', 'name'=>'cost_price'),isset($fields_array['cost_price'])?number_format($fields_array['cost_price'], 2, ',', '.'):''); ?>
					</div>
					
					<div class="vui-field-wrapper-inline">
						<?php echo form_label(lang('product_external_url')); ?>
						<?php echo form_input(array('id'=>'external-url', 'class'=>'external-url', 'name'=>'external_url'),isset($fields_array['external_url'])?$fields_array['external_url']:''); ?>
					</div>
					
					<div class="clear"></div>
					
					<div class="vui-field-wrapper-inline">
						<?php echo form_label(lang('product_description')); ?>
						<textarea id="description"  cols="40" rows="10" class="description" name="description"><?php echo isset($fields_array['description'])?$fields_array['description']:''; ?></textarea>
					</div>
					
				</fieldset>
				
				<?php if ( $product ) { ?>
				<?php echo form_hidden('product_id',$fields_array['id']); ?>
				<?php } ?>
				
				<div class="clear"></div>
				
				<div class="form-actions">
					
					<?php echo form_submit(array('id'=>'submit','name'=>'submit','class'=>'button button-save'),lang('action_save')); ?>
					<?php echo form_submit(array('id'=>'submit-apply','class'=>'button button-apply','name'=>'submit_apply'),lang('action_apply')); ?>
					<?php echo form_submit(array('id'=>'submit-cancel','class'=>'button button-cancel','name'=>'submit_cancel'),lang('action_cancel')); ?>
					
				</div>
				
			<?php echo form_close(); ?>
			
			<script type="text/javascript" >
			
			$(document).ready(function(){
				
				$('#cost-price').maskMoney({
					thousands: '.',
					decimal: ',',
					symbolStay: true
				});
				
			});
			</script>
			
		</div>

