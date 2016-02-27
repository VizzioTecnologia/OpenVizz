
		<header>
			<h1><?php echo lang($component_name); ?> - <?php echo lang('tenders_globals_configurations'); ?></h1>
		</header>
		
		<div>
			
			<?php echo form_open(get_url('admin'.$this->uri->ruri_string())); ?>
				
				<div class="form-actions">
					
					<?php echo form_submit(array('id'=>'submit','name'=>'submit','class'=>'button button-save'),lang('action_save')); ?>
					<?php echo form_submit(array('id'=>'submit-apply','class'=>'button button-apply','name'=>'submit_apply'),lang('action_apply')); ?>
					<?php echo form_submit(array('id'=>'submit-cancel','class'=>'button button-cancel','name'=>'submit_cancel'),lang('action_cancel')); ?>
					
				</div>
				
				<fieldset>
					
					<legend><?php echo lang('parameters'); ?></legend>
					
					<?php
					
					/* gerando o html dos parâmetros, ele deve ser chamado na view, não no controller,
					 * pois os erros de validação dos elementos dos parâmetros devem ser expostos
					 * após a chamada da função $this->form_validation->run()
					 */
					
					echo params_to_html( $params_spec, $final_params_values );
					
					?>
					
					<?php echo form_hidden('component_id',$component->id); ?>
					
				</fieldset>
				
				<div class="form-actions">
					
					<?php echo form_submit(array('id'=>'submit','name'=>'submit','class'=>'button button-save'),lang('action_save')); ?>
					<?php echo form_submit(array('id'=>'submit-apply','class'=>'button button-apply','name'=>'submit_apply'),lang('action_apply')); ?>
					<?php echo form_submit(array('id'=>'submit-cancel','class'=>'button button-cancel','name'=>'submit_cancel'),lang('action_cancel')); ?>
					
				</div>
				
			<?php echo form_close(); ?>
			
		</div>

