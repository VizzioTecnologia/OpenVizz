<?php
	
	$this->plugins->load( NULL, 'js_text_editor' );
	$this->plugins->load( NULL, 'js_time_picker' );
	
	$created_date_time = ( @$article->created_date ) ? strtotime( $article->created_date ) : gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] );
	
	$created_date = $this->input->post( 'created_date' ) ? $this->input->post( 'created_date' ) : date( 'Y-m-d', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) );
	
	$created_time = $this->input->post( 'created_time' ) ? $this->input->post( 'created_time' ) : date( 'H:i:s', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) );
	
?>


<div id="global-config-form-wrapper" class="form-wrapper tabs-wrapper">
	
	<div class="form-wrapper-sub tabs-children">
		
		<?= form_open_multipart( get_url( 'admin' . $this->uri->ruri_string() ), array( 'id' => 'submit-form-form', ) ); ?>
			
			<div class="form-actions to-toolbar">
				
				<?= vui_el_button( array( 'text' => lang( 'action_save' ), 'icon' => 'save', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit', 'id' => 'submit', 'only_icon' => TRUE, 'form' => 'submit-form-form', ) ); ?>
				
				<?= vui_el_button( array( 'text' => lang( 'action_apply' ), 'icon' => 'apply', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_apply', 'id' => 'submit-apply', 'only_icon' => TRUE, 'form' => 'submit-form-form', ) ); ?>
				
				<?= vui_el_button( array( 'text' => lang( 'action_cancel' ), 'icon' => 'cancel', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_cancel', 'id' => 'submit-cancel', 'only_icon' => TRUE, 'form' => 'submit-form-form', ) ); ?>
				
			</div>
			
			<header class="form-header tabs-header">
				
				<h1>
					
					<?php if ( $component_function_action == 'asf' ) { ?>
						
					<?= lang( 'new_submit_form' ); ?>
					
					<?php } else if ( $component_function_action == 'esf' ) { ?>
						
					<?= lang( 'edit_submit_form' ); ?>
					
					<?php } ?>
					
				</h1>
				
			</header>
			
			<div class="form-items tabs-items">
					
				<div class="form-item">
					
					<fieldset id="submit-form-basic-details">
						
						<legend><?= lang( 'basic_form_details' ); ?></legend>
						
						<div id="title-container" class="vui-field-wrapper-inline">
							
							<?= form_error( 'title', '<div class="msg-inline-error">', '</div>' ); ?>
							<?= form_label( lang( 'title' ) ); ?>
							<?= form_input( array( 'id'=>'title', 'name'=>'title' ), isset( $submit_form[ 'title' ] ) ? $submit_form[ 'title' ] : '','autofocus' ); ?>
							
						</div>
						
						<div id="alias-container" class="vui-field-wrapper-inline">
							
							<?= form_error( 'alias', '<div class="msg-inline-error">', '</div>' ); ?>
							<?= form_label( lang( 'alias' ) ); ?>
							<?= form_input( array( 'id'=>'alias', 'name'=>'alias' ), isset( $submit_form[ 'alias' ] ) ? $submit_form[ 'alias' ] : '' ); ?>
							
						</div>
						
						<div class="divisor-h"></div>
						
					</fieldset>
					
				</div>
				
				<div class="form-item">
					
					<fieldset id="submit-form-fields">
						
						<legend><?= lang( 'fields' ); ?></legend>
						
						<?php
							
							$field_type_options = array(
								
								'input_text' => lang( 'input_text' ),
								'textarea' => lang( 'textarea' ),
								'combo_box' => lang( 'combo_box' ),
								'button' => lang( 'button' ),
								'html' => lang( 'html' ),
								
							);
							
							$field_type_default = 'input_text';
							
						?>
						
						<?php ;if ( isset( $submit_form[ 'fields' ] ) AND is_array( $submit_form[ 'fields' ] ) ) { ?>
						<?php  foreach( $submit_form[ 'fields' ] as $key => $field ) { ?>
						
						<div class="fields-fields-wrapper">
							
							<?php $current_field = 'type'; ?>
							<div class="vui-field-wrapper-inline">
								
								<?= form_label( lang( 'field_type' ) ); ?>
								<?= form_dropdown( 'fields[' . $key . '][field_' . $current_field . ']', $field_type_options, isset( $field[ 'field_' . $current_field ] ) ? $field[ 'field_' . $current_field ] : $field_type_default, 'id="field-' . $current_field . '-' . $key . '"'); ?>
								
							</div>
							
							<?php $current_field = 'key'; ?>
							<?= form_error( $current_field . '_' . $key, '<div class="msg-inline-error">', '</div>' ); ?>
							<div class="vui-field-wrapper-inline">
								
								<?= form_label( lang( $current_field ) ); ?>
								<?= form_input_number( array( 'id' => 'field-' . $current_field . '-' . $key, 'name' => 'fields[' . $key . '][' . $current_field . ']', 'class' => 'field-' . $current_field, 'title' => lang( 'tip_field_' . $current_field ) ), isset( $field[ $current_field ] ) ? $field[ $current_field ] : $key ); ?>
								
							</div>
							
							<?php if ( ! in_array( $field[ 'field_type' ], array( 'html' ) ) ){ ?>
								
								<?php $current_field = 'label'; ?>
								<?= form_error( $current_field . '_' . $key, '<div class="msg-inline-error">', '</div>' ); ?>
								<div class="vui-field-wrapper-inline">
									
									<?= form_label( lang( $current_field ) ); ?>
									<?= form_input( array( 'id' => 'field-' . $current_field . '-' . $key, 'name' => 'fields[' . $key . '][' . $current_field . ']', 'class' => 'field-' . $current_field, 'title' => lang( 'tip_field_' . $current_field ) ), isset( $field[ $current_field ] ) ? $field[ $current_field ] : lang( 'field' ) . ' ' . $key ); ?>
									
								</div>
								
							<?php } ?>
							
							<?php if ( in_array( $field[ 'field_type' ], array( 'html' ) ) ){ ?>
								
								<?php $current_field = 'label'; ?>
								<?= form_hidden( 'fields[' . $key . '][' . $current_field . ']', 'html-' . $key ); ?>
								
							<?php } ?>
							
							<?php if ( ! in_array( $field[ 'field_type' ], array( 'button', 'html' ) ) ){ ?>
								
								<?php $current_field = 'field_is_required'; ?>
								<div class="vui-field-wrapper-inline">
									
									<?= form_label( lang( 'field_is_required' ) ); ?>
									<?php
										
										$options = array(
											
											'1' => lang( 'yes' ),
											'0' => lang( 'no' ),
											
										);
										
									?>
									<?= form_dropdown( 'fields[' . $key . '][' . $current_field . ']', $options, isset( $field[ $current_field ] ) ? $field[ $current_field ] : 0, 'id="field-' . $current_field . '-' . $key . '"' . ' class="field-' . $current_field . '"' ); ?>
									
								</div>
								
								<?php $current_field = 'validation_rule'; ?>
								<div class="vui-field-wrapper-inline">
									
									<?= form_label( lang( 'validation_rule' ) ); ?>
									<?php
										
										$options = array(
											
											'matches' => lang( 'submit_forms_validation_rule_matches' ),
											'valid_email' => lang( 'submit_forms_validation_rule_valid_email' ),
											'valid_email_dns' => lang( 'submit_forms_validation_rule_valid_email_dns' ),
											'valid_emails' => lang( 'submit_forms_validation_rule_valid_emails' ),
											'min_length' => lang( 'submit_forms_validation_rule_min_length' ),
											'max_length' => lang( 'submit_forms_validation_rule_max_length' ),
											'exact_length' => lang( 'submit_forms_validation_rule_exact_length' ),
											'greater_than' => lang( 'submit_forms_validation_rule_greater_than' ),
											'less_than' => lang( 'submit_forms_validation_rule_less_than' ),
											'alpha' => lang( 'submit_forms_validation_rule_alpha' ),
											'alpha_numeric' => lang( 'submit_forms_validation_rule_alpha_numeric' ),
											'alpha_dash' => lang( 'submit_forms_validation_rule_alpha_dash' ),
											'numeric' => lang( 'submit_forms_validation_rule_numeric' ),
											'integer' => lang( 'submit_forms_validation_rule_integer' ),
											'decimal' => lang( 'submit_forms_validation_rule_decimal' ),
											'is_natural' => lang( 'submit_forms_validation_rule_is_natural' ),
											'is_natural_no_zero' => lang( 'submit_forms_validation_rule_is_natural_no_zero' ),
											'valid_ip' => lang( 'submit_forms_validation_rule_valid_ip' ),
											'valid_base64' => lang( 'submit_forms_validation_rule_valid_base64' ),
											
										);
										
									?>
									<?= form_multiselect( 'fields[' . $key . '][' . $current_field . '][]', $options, isset( $field[ $current_field ] ) ? $field[ $current_field ] : '', 'id="field-' . $current_field . '-' . $key . '"' . ' class="field-' . $current_field . '"' . ' size="' . count( $options ) . '"' ); ?>
									
								</div>
								
							<?php } ?>
							
							<?php if ( isset( $field[ 'validation_rule' ] ) ){ ?>
								
								<?php foreach ( $field[ 'validation_rule' ] as $validation_key => $validation_rule ) { ?>
									
									<?php if ( in_array( $validation_rule, array( 'matches', 'min_length', 'max_length', 'exact_length', 'greater_than', 'less_than', ) ) ){ ?>
										
										<?php $current_field = 'validation_rule_parameter_' . $validation_rule; ?>
										<?= form_error( $current_field . '_' . $key, '<div class="msg-inline-error">', '</div>' ); ?>
										<div class="vui-field-wrapper-inline">
											
											<?= form_label( lang( $current_field ) ); ?>
											<?= form_input( array( 'id' => 'field-' . $current_field . '-' . $key, 'name' => 'fields[' . $key . '][' . $current_field . ']', 'class' => 'field-' . $current_field, 'title' => lang( 'tip_field_' . $current_field ) ), isset( $field[ $current_field ] ) ? $field[ $current_field ] : '' ); ?>
											
										</div>
										
									<?php } ?>
									
								<?php } ?>
									
							<?php } ?>
							
							<?php // print_r( $field ); ?>
							
							<?php if ( ! in_array( $field[ 'field_type' ], array( 'html' ) ) ){ ?>
								
								<?php $current_field = 'description'; ?>
								<?= form_error( $current_field . '_' . $key, '<div class="msg-inline-error">', '</div>' ); ?>
								<div class="vui-field-wrapper-inline">
									
									<?= form_label( lang( $current_field ) ); ?>
									<?= form_input( array( 'id' => 'field-' . $current_field . '-' . $key, 'name' => 'fields[' . $key . '][' . $current_field . ']', 'class' => 'field-' . $current_field, 'title' => lang( 'tip_field_' . $current_field ) ), isset( $field[ $current_field ] ) ? $field[ $current_field ] : '' ); ?>
									
								</div>
								
							<?php } ?>
							
							<?php if ( $field[ 'field_type' ] == 'html' ){ ?>
								
								<?php $current_field = 'html'; ?>
								<?= form_error( $current_field . '_' . $key, '<div class="msg-inline-error">', '</div>' ); ?>
								<div class="vui-field-wrapper-inline">
									
									<?= form_label( lang( $current_field ) ); ?>
									<?= form_textarea( array( 'id' => 'field-' . $current_field . '-' . $key, 'name' => 'fields[' . $key . '][' . $current_field . ']', 'class' => 'js-editor field-' . $current_field, 'title' => lang( 'tip_field_' . $current_field ) ), isset( $field[ $current_field ] ) ? $field[ $current_field ] : '' ); ?>
									
								</div>
								
							<?php } else if ( $field[ 'field_type' ] == 'input_text' ){ ?>
							
							
							
							<?php } else if ( $field[ 'field_type' ] == 'combo_box' ){ ?>
							
							<?php $current_field = 'options'; ?>
							<?= form_error( $current_field . '_' . $key, '<div class="msg-inline-error">', '</div>' ); ?>
							<div class="vui-field-wrapper-inline">
								
								<?= form_label( lang( $current_field ) ); ?>
								<?= form_textarea( array( 'id' => 'field-' . $current_field . '-' . $key, 'name' => 'fields[' . $key . '][' . $current_field . ']', 'class' => 'field-' . $current_field, 'title' => lang( 'tip_field_' . $current_field ) ), isset( $field[ $current_field ] ) ? $field[ $current_field ] : '' ); ?>
								
							</div>
							
							<?php } ?>
							
							<div class="vui-field-wrapper-inline">
								
								<?= form_label( '&nbsp;' ); ?>
								<?= form_submit( array( 'id' => 'submit-add-field-' . $key, 'name' => 'submit_remove_field[' . $key . ']', 'class' => 'btn btn-delete', 'title' => lang( 'tip_remove_field' ) ), lang( 'remove_field' ) ); ?>
								
							</div>
							
						</div>
						
						<?php } ?>
						<?php } ?>
						
						<?= form_label(lang('add_field')); ?>
						<?= form_label(lang('enter_amount_fields')); ?>
						
						<?= form_input_number(array( 'id' => 'field-fields-to-add', 'name' => 'field_fields_to_add', 'class' => 'add-num-field', 'title' => lang( 'tip_enter_amount_fields' ) ), 1 ); ?>
						
						<?= form_label( lang( 'field_type' ) ); ?>
						<?= form_dropdown( 'field_type_to_add', $field_type_options, isset( $submit_form[ 'field_type_to_add' ] ) ? $submit_form[ 'field_type_to_add' ] : $field_type_default, 'id="field-type-to-add"'); ?>
						
						<?= form_submit(array('id'=>'submit-add-field', 'class'=>'btn btn-add','name'=>'submit_add_field'),lang('add')); ?>
						
					</fieldset>
					
				</div>
				
				<div class="form-item">
					
					<fieldset id="submit-form-params">
						
						<?php $current_field = 'type'; ?>
						
						<?php
						
						/* gerando o html dos parâmetros, ele deve ser chamado na view, não no controller,
						 * pois os erros de validação dos elementos dos parâmetros devem ser expostos
						 * após a chamada da função $this->form_validation->run()
						 */
						
						echo params_to_html( $params_spec, $final_params_values );
						
						?>
						
					</fieldset>
					
				</div>
				
			</div>
			
			<?= form_hidden( 'submit_form_id', @$submit_form[ 'id' ] ); ?>
			
		<?= form_close(); ?>
		
	</div>
	
</div>

<?php if ( $this->plugins->load( 'yetii' ) ){ ?>

<script type="text/javascript" >
	
$( document ).ready(function(){
	
	/*************************************************/
	/**************** Criando as tabs ****************/
	
	makeTabs( $( '.tabs-wrapper' ), '#submit-form-basic-details, #submit-form-fields, .params-set-wrapper', 'legend, .params-set-title' );
	
	/**************** Criando as tabs ****************/
	/*************************************************/
	
});

</script>

<?php } ?>
