
<div id="global-config-form-wrapper" class="form-wrapper tabs-wrapper">
	
	<div class="form-wrapper-sub tabs-children">
		
		<?= form_open( get_url( 'admin'.$this->uri->ruri_string() ), array( 'id' => 'users-group-form', ) ); ?>
			
			<div class="form-actions to-toolbar to-main-toolbar">
				
				<?= vui_el_button( array( 'text' => lang( 'action_save' ), 'icon' => 'save', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit', 'id' => 'submit', 'only_icon' => TRUE, 'form' => 'users-group-form', ) ); ?>
				
				<?= vui_el_button( array( 'text' => lang( 'action_apply' ), 'icon' => 'apply', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_apply', 'id' => 'submit-apply', 'only_icon' => TRUE, 'form' => 'users-group-form', ) ); ?>
				
				<?= vui_el_button( array( 'text' => lang( 'action_cancel' ), 'icon' => 'cancel', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_cancel', 'id' => 'submit-cancel', 'only_icon' => TRUE, 'form' => 'users-group-form', ) ); ?>
				
			</div>
			
			<header class="form-header tabs-header">
				
				<h1><?= lang( 'new_user_group' ); ?></h1>
				
			</header>
			
			<div class="form-items tabs-items">
				
				<div class="form-item">
					
					<fieldset id="user-group-basic-details">
						
						<legend><?php echo lang('details'); ?></legend>
						
						<?php echo form_error('title', '<div class="msg-inline-error">', '</div>'); ?>
						<?php echo form_label(lang('title')); ?>
						<?php echo form_input(array('id'=>'title','name'=>'title'),set_value('title'),'autofocus'); ?>
						
						<?php echo form_error('alias', '<div class="msg-inline-error">', '</div>'); ?>
						<?php echo form_label(lang('alias')); ?>
						<?php echo form_input(array('id'=>'alias','name'=>'alias','autocomplete'=>'on'),set_value('alias')); ?>
						
						<?php echo form_error('parent', '<div class="msg-inline-error">', '</div>'); ?>
						<?php echo form_label(lang('parent_user_group')); ?>
						<?php
							$options = array(
								0=>lang('root'),
							);
							foreach($users_groups as $row):
								$options[$row['id']] = $row['indented_title'];
							endforeach;
						?>
						<?php echo form_dropdown('parent', $options, set_value('parent', 0),'id="parent-user-group"'); ?>
						
					</fieldset>
					
				</div>
				
				<div class="form-item">
					
					<fieldset id="user-group-privileges">
						
						<?php
						
						/* gerando o html dos parâmetros, ele deve ser chamado na view, não no controller,
						 * pois os erros de validação dos elementos dos parâmetros devem ser expostos
						 * após a chamada da função $this->form_validation->run()
						 */
						
						echo params_to_html( $privileges_params_spec, $privileges_final_params_values, 'privileges' );
						
						?>
						
					</fieldset>
					
				</div>
				
			</div>
			
		<?= form_close(); ?>
		
	</div>
	
</div>

<?php if ( $this->plugins->load( 'yetii' ) ){ ?>

<script type="text/javascript" >
	
$( document ).ready(function(){
	
	/*************************************************/
	/**************** Criando as tabs ****************/
	
	makeTabs( $( '.tabs-wrapper' ), '#user-group-basic-details, .params-set-wrapper', 'legend, .params-set-title' );
	
	/**************** Criando as tabs ****************/
	/*************************************************/
	
});

</script>

<?php } ?>
