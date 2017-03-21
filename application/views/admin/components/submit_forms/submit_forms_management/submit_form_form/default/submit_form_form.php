<?php
	
	$this->plugins->load( NULL, 'js_text_editor' );
	$this->plugins->load( NULL, 'js_time_picker' );

	$created_date_time = ( @$article->created_date ) ? strtotime( $article->created_date ) : gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] );

	$created_date = $this->input->post( 'created_date' ) ? $this->input->post( 'created_date' ) : date( 'Y-m-d', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) );

	$created_time = $this->input->post( 'created_time' ) ? $this->input->post( 'created_time' ) : date( 'H:i:s', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) );
	
	if ( check_var( $this->current_component[ 'params' ][ 'users_fields' ] ) ) {
		
		$users_fields = $this->current_component[ 'params' ][ 'users_fields' ];
		
	}
	
	
	
?>


<div id="contact-form-wrapper" class="form-wrapper tabs-wrapper">

	<div class="form-wrapper-sub tabs-children">

		<?= form_open( get_url( 'admin' . $this->uri->ruri_string() ), array( 'id' => 'submit-form-form', 'class' => $component_function_action != 'asf' ? 'ajax' : '', ) ); ?>

			<div class="form-actions to-toolbar to-main-toolbar">

				<?= vui_el_button( array( 'text' => lang( 'action_save' ), 'icon' => 'save', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit', 'id' => 'submit', 'only_icon' => TRUE, 'form' => 'submit-form-form', ) ); ?>

				<?= vui_el_button( array( 'text' => lang( 'action_apply' ), 'icon' => 'apply', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_apply', 'id' => 'submit-apply', 'only_icon' => TRUE, 'form' => 'submit-form-form', ) ); ?>

				<?= vui_el_button( array( 'text' => lang( 'action_cancel' ), 'icon' => 'cancel', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_cancel', 'id' => 'submit-cancel', 'only_icon' => TRUE, 'form' => 'submit-form-form', ) ); ?>

			</div>
			
			<header class="form-header tabs-header">
				
				<h1>
					
					<?= isset( $submit_form[ 'title' ] ) ? $submit_form[ 'title' ] : ''; ?>
					
				</h1>
				
			</header>
			
			<div class="form-items tabs-items">

				<div class="form-item">

					<fieldset id="submit-form-basic-details" class="for-tab" >

						<legend>

							<?= vui_el_button( array( 'text' => lang( 'basic_details' ), 'icon' => 'basic-details',  ) ); ?>

						</legend>
						
						<div id="title-container" class="vui-field-wrapper-inline">
							
							<?= form_error( 'title', '<div class="msg-inline-error">', '</div>' ); ?>
							<?= form_label( lang( 'title' ) ); ?>
							
							<?= vui_el_input_text(
								
								array(
									
									'text' => lang( 'submit_form_title' ),
									'title' => lang( 'tip_submit_form_title' ),
									'name' => 'title',
									'value' => isset( $submit_form[ 'title' ] ) ? $submit_form[ 'title' ] : '',
									'class' => 'title',
									'id' => 'title',
									'autofocus' => TRUE,
									
								)
								
							); ?>
							
						</div>
						
						<div id="alias-container" class="vui-field-wrapper-inline">
							
							<?= form_error( 'alias', '<div class="msg-inline-error">', '</div>' ); ?>
							<?= form_label( lang( 'alias' ) ); ?>
							
							<?= vui_el_input_text(
								
								array(
									
									'text' => lang( 'submit_form_alias' ),
									'title' => lang( 'tip_submit_form_alias' ),
									'name' => 'alias',
									'value' => isset( $submit_form[ 'alias' ] ) ? $submit_form[ 'alias' ] : '',
									'class' => 'alias',
									'id' => 'alias',
									
								)
								
							); ?>
							
						</div>
						
						<div class="divisor-h"></div>
						
					</fieldset>
					
				</div><?php
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				/* ---------------------------------------------------------------------------
				 * ---------------------------------------------------------------------------
				 * Fields
				 * ---------------------------------------------------------------------------
				 */
				
				?><div class="form-item">
					
					<fieldset id="submit-form-fields" class="for-tab" >
						
						<legend>
							
							<?= vui_el_button( array( 'text' => lang( 'fields' ), 'icon' => 'submit-forms',  ) ); ?>
							
						</legend>
						
						<?php
							
							$field_type_options = array(
								
								'input_text' => lang( 'input_text' ),
								'textarea' => lang( 'textarea' ),
								'combo_box' => lang( 'combo_box' ),
								'radiobox' => lang( 'radiobox' ),
								'checkbox' => lang( 'checkbox' ),
								'button' => lang( 'button' ),
								'html' => lang( 'html' ),
								'articles' => lang( 'articles' ),
								'date' => lang( 'date' ),
								
							);
							
							$field_type_default = 'input_text';
							
						?>
						
						<?php if ( isset( $submit_form[ 'fields' ] ) AND is_array( $submit_form[ 'fields' ] ) AND count( $submit_form[ 'fields' ] ) > 0 ) { ?>
						
						<?php
							
							$conditional_fields_targets = array();
							
							foreach( $submit_form[ 'fields' ] as $target_field_key => $target_field ) {
								
								// se o tipo de campo não for HTML ou button...
								// Objetivo: não queremos aplicar condições para os campos do tipo HTML nem button
								if ( ! in_array( $target_field[ 'field_type' ], array( 'html', 'button' ) ) ){
									
									$conditional_fields_targets[ $target_field_key ] = $target_field;
									
								}
								
							}
							
						?>
						
						<div id="fields-layout-preference-wrapper" class="vui-field-wrapper-auto"><?php
							
							$current_field = 'fields_layout_preference';
							$options = array(
								
								'id' => 'field-' . $current_field . '-default',
								'name' => 'params[' . $current_field . ']',
								'class' => 'sf-field-' . $current_field,
								'value' => 'default',
								'icon' => 'layout_default',
								'icon_as_check' => TRUE,
								'text' => lang( $current_field . '_default' ),
								'title' => lang( 'tip_' . $current_field . '_default' ),
								
							);
							
							$checked = ( isset( $submit_form[ 'params' ][ $current_field ] ) AND $submit_form[ 'params' ][ $current_field ] == 'default' ) ? ( bool ) $submit_form[ 'params' ][ $current_field ] : 'default';
							
							$options[ 'checked' ] = $checked;
							
							echo vui_el_radiobox( $options );
							
							$options = array(
								
								'id' => 'field-' . $current_field . '-default',
								'name' => 'params[' . $current_field . ']',
								'class' => 'sf-field-' . $current_field,
								'value' => 'mini',
								'icon' => 'layout_mini',
								'icon_as_check' => TRUE,
								'text' => lang( $current_field . '_mini' ),
								'title' => lang( 'tip_' . $current_field . '_mini' ),
								
							);
							
							$checked = ( isset( $submit_form[ 'params' ][ $current_field ] ) AND $submit_form[ 'params' ][ $current_field ] == 'mini' ) ? ( bool ) $submit_form[ 'params' ][ $current_field ] : FALSE;
							
							$options[ 'checked' ] = $checked;
							
							echo vui_el_radiobox( $options );
							
							echo vui_el_button(
								
								array(
									
									'text' => lang( 'submit_check_all_fields_available_on_admin' ),
									'title' => lang( 'tip_submit_check_all_fields_available_on_admin' ),
									'icon' => 'view',
									'button_type' => 'button',
									'name' => 'submit_check_all_fields_available_on_admin',
									'id' => 'submit-check-all-fields-available-on-admin',
									'only_icon' => TRUE,
									
								)
								
							);
							
							echo vui_el_button(
								
								array(
									
									'text' => lang( 'submit_check_all_fields_available_on_site' ),
									'title' => lang( 'tip_submit_check_all_fields_available_on_site' ),
									'icon' => 'view',
									'button_type' => 'button',
									'name' => 'submit_check_all_fields_available_on_site',
									'id' => 'submit-check-all-fields-available-on-site',
									'only_icon' => TRUE,
									
								)
								
							);
							
							echo vui_el_button(
								
								array(
									
									'text' => lang( 'submit_check_all_fields_visible_on_site_list' ),
									'title' => lang( 'tip_submit_check_all_fields_visible_on_site_list' ),
									'icon' => 'list',
									'button_type' => 'button',
									'name' => 'submit_check_all_fields_visible_on_site_list',
									'id' => 'submit-check-all-fields-visible-on-site-list',
									'only_icon' => TRUE,
									
								)
								
							);
							
							echo vui_el_button(
								
								array(
									
									'text' => lang( 'submit_check_all_fields_visible_on_site_detail' ),
									'title' => lang( 'tip_submit_check_all_fields_visible_on_site_detail' ),
									'icon' => 'detail',
									'button_type' => 'button',
									'name' => 'submit_check_all_fields_visible_on_site_detail',
									'id' => 'submit-check-all-fields-visible-on-site-detail',
									'only_icon' => TRUE,
									
								)
								
							);
							
						?></div>
						
						<div id="fields-wrapper" class="fields-layout-<?= ( isset( $submit_form[ 'params' ][ 'fields_layout_preference' ] ) ) ? $submit_form[ 'params' ][ 'fields_layout_preference' ] : 'default'; ?>"><?php
						
						foreach( $submit_form[ 'fields' ] as $key => $field ) {
							
							$field[ 'alias' ] = isset( $field[ 'alias' ] ) ? $field[ 'alias' ] : ( isset( $field[ 'label' ] ) ? $field[ 'label' ] : ( isset( $field[ 'presentation_label' ] ) ? $field[ 'presentation_label' ] : lang( 'field' ) . ' ' . $key ) );
							
							$_expanded = check_var( $field[ 'field_expanded' ] ) ? TRUE : FALSE;
							
							?><div id="field-<?= $key; ?>-wrapper" class="field-wrapper field-type-<?= $field[ 'field_type' ]; ?><?= $_expanded ? ' sortable-expanded' : ' sortable-retracted'; ?>"><?php
								
								?><div class="content <?= ! $_expanded ? 'hidden' : ''; ?>">
									
									<?php $current_field = 'expanded'; ?>
									<div class="vui-field-wrapper-inline hidden">
										
										<input class="expanded-flag sf-field-<?= $current_field; ?>" type="hidden" name="<?= 'fields[' . $key . '][field_' . $current_field . ']'; ?>" value="<?= ! $_expanded ? '' : '1'; ?>" />
										
									</div><?php
									
									$current_field = 'availability';
									?><div class="vui-field-wrapper-inline">
										
										<?php
											
											if ( isset( $post[ 'submit_check_all_fields_available_on_admin' ] ) ) {
												
												$checked = TRUE;
												
											}
											else {
												
												$checked = ( check_var( $field[ $current_field ][ 'admin' ] ) ) ? ( bool ) $field[ $current_field ][ 'admin' ] : FALSE;
												
											}
											
											$options = array(
												
												'id' => 'field-' . $current_field . '_admin-' . $key,
												'name' => 'fields[' . $key . '][' . $current_field . '][admin]',
												'class' => 'sf-field-' . $current_field . '_admin',
												'icon' => 'view',
												'icon_as_check' => TRUE,
												'value' => 'admin',
												'text' => lang( 'ud_available_on_admin' ),
												'title' => lang( 'tip_ud_available_on_admin' ),
												'checked' => $checked,
												
											);
											
											echo vui_el_checkbox( $options );
											
										?>
										
									</div><?php
									
									?><div class="vui-field-wrapper-inline">
										
										<?php
											
											if ( isset( $post[ 'submit_check_all_fields_available_on_site' ] ) ) {
												
												$checked = TRUE;
												
											}
											else {
												
												$checked = ( isset( $field[ $current_field ] ) AND in_array( 'site', $field[ $current_field ] ) ) ? ( bool ) $field[ $current_field ] : FALSE;
												
											}
											
											$options = array(
												
												'id' => 'field-' . $current_field . '_site-' . $key,
												'name' => 'fields[' . $key . '][' . $current_field . '][site]',
												'class' => 'sf-field-' . $current_field . '_site',
												'icon' => 'view',
												'icon_as_check' => TRUE,
												'value' => 'site',
												'text' => lang( 'ud_available_on_site' ),
												'title' => lang( 'tip_ud_available_on_site' ),
												'checked' => $checked,
												
											);
											
											echo vui_el_checkbox( $options );
											
										?>
										
									</div><?php
									
									if ( ! in_array( $field[ 'field_type' ], array( 'button' ) ) ) {
										
										$current_field = 'visibility';
										?><div class="vui-field-wrapper-inline">
											
											<?php
												
												if ( isset( $post[ 'submit_check_all_fields_visible_on_site_list' ] ) ) {
													
													$checked = TRUE;
													
												}
												else {
													
													$checked = ( isset( $field[ $current_field ][ 'site' ][ 'list' ] ) ) ? ( bool ) $field[ $current_field ][ 'site' ][ 'list' ] : FALSE;
													
												}
												
												$options = array(
													
													'id' => 'field-' . $current_field . '_site-' . $key,
													'name' => 'fields[' . $key . '][' . $current_field . '][site][list]',
													'class' => 'sf-field-' . $current_field . '_site_list',
													'icon' => 'list',
													'icon_as_check' => TRUE,
													'value' => 1,
													'text' => lang( 'ud_visible_on_site_list' ),
													'title' => lang( 'tip_ud_visible_on_site_list' ),
													'checked' => $checked,
													
												);
												
												echo vui_el_checkbox( $options );
												
											?>
											
										</div><?php
										
										?><div class="vui-field-wrapper-inline">
											
											<?php
												
												if ( isset( $post[ 'submit_check_all_fields_visible_on_site_detail' ] ) ) {
													
													$checked = TRUE;
													
												}
												else {
													
													$checked = ( isset( $field[ $current_field ][ 'site' ][ 'detail' ] ) ) ? ( bool ) $field[ $current_field ][ 'site' ][ 'detail' ] : FALSE;
													
												}
												
												$options = array(
													
													'id' => 'field-' . $current_field . '_site-' . $key,
													'name' => 'fields[' . $key . '][' . $current_field . '][site][detail]',
													'class' => 'sf-field-' . $current_field . '_site_detail',
													'icon' => 'detail',
													'icon_as_check' => TRUE,
													'value' => 1,
													'text' => lang( 'ud_visible_on_site_detail' ),
													'title' => lang( 'tip_ud_visible_on_site_detail' ),
													'checked' => $checked,
													
												);
												
												echo vui_el_checkbox( $options );
												
											?>
											
										</div><?php
											
										if ( ! in_array( $field[ 'field_type' ], array( 'html' ) ) ) {
											
											if ( check_var( $users_fields ) ) {
												
												$current_field = 'is_user_field';
												
												echo '<div class="vui-field-wrapper-inline">';
												
												echo '<div class="vui-field-wrapper">';
												
												echo vui_el_checkbox(
													
													array(
														
														'text' => lang( 'ud_is_user_field' ),
														'title' => lang( 'tip_ud_is_user_field' ),
														'name' => 'fields[' . $key . '][' . $current_field . ']',
														'checked' => isset( $field[ $current_field ] ) ? TRUE : FALSE,
														'class' => 'ud-field-' . $current_field,
														'icon' => 'user',
														'icon_as_check' => TRUE,
														'value' => 1,
														'id' => 'field-' . $current_field . '-' . $key,
														
													)
													
												);
												
												echo '</div>';
												
												// ------------------------
												// Users fields options
												
												$current_field = 'user_field';
												
												$users_fields_options = array(
													
													'' => lang( 'combobox_select' ),
													
												);
												
												foreach( $users_fields as $uf_alias => $user_field ) {
													
													$users_fields_options[ $uf_alias ] = $user_field[ 'title' ];
													
												}
												
												echo '<div class="vui-field-wrapper">';
												
												echo form_label( lang( 'ud_select_user_field' ) );
												
												echo vui_el_dropdown(
													
													array(
														
														'name' => 'fields[' . $key . '][' . $current_field . ']',
														'options' => $users_fields_options,
														'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : $field_type_default,
														'class' => 'ud-field-' . $current_field,
														'id' => 'field-' . $current_field . '-' . $key,
														
													)
													
												);
												
												// Users fields options
												// ------------------------
												
												echo '</div>';
												
												echo '</div>';
												
											}
											
											$current_field = 'is_unique';
											?><!--<div class="vui-field-wrapper-inline">
												
												<?php
													
													echo vui_el_checkbox(
														
														array(
															
															'text' => lang( $current_field ),
															'title' => lang( 'tip_' . $current_field ),
															'name' => 'fields[' . $key . '][' . $current_field . ']',
															'checked' => isset( $field[ $current_field ] ) ? TRUE : FALSE,
															'class' => 'ud-field-' . $current_field,
															'value' => 1,
															'id' => 'field-' . $current_field . '-' . $key,
															
														)
														
													);
													
												?>
												
											</div>--><?php
											
										}
										
									}
									
									echo '<hr />';
									
									$current_field = 'type';
									?><div class="vui-field-wrapper-inline">
										
										<?= form_label( lang( 'field_type' ) ); ?>
										
										<?= vui_el_dropdown(
												
											array(
												
												'name' => 'fields[' . $key . '][field_' . $current_field . ']',
												'options' => $field_type_options,
												'value' => isset( $field[ 'field_' . $current_field ] ) ? $field[ 'field_' . $current_field ] : $field_type_default,
												'class' => 'sf-field-' . $current_field,
												'id' => 'field-' . $current_field . '-' . $key,
												
											)
											
										); ?>
										
									</div><?php
									
									$current_field = 'key';
									echo form_error( $current_field . '_' . $key, '<div class="msg-inline-error">', '</div>' );
									?><div class="vui-field-wrapper-inline field-key-wrapper">
										
										<?= form_label( lang( 'field_key' ) ); ?>
										
										<?= vui_el_input_number(
												
											array(
												
												'id' => 'field-' . $current_field . '-' . $key,
												'name' => 'fields[' . $key . '][' . $current_field . ']',
												'class' => 'sf-field-' . $current_field,
												'title' => lang( 'tip_field_' . $current_field ),
												'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : $key,
												
											)
											
										); ?>
										
									</div><?php
									
									$current_field = 'alias';
									echo form_error( $current_field . '_' . $key, '<div class="msg-inline-error">', '</div>' );
									?><div class="vui-field-wrapper-inline">
										
										<?= form_label( lang( $current_field ) ); ?>
										
										<?= vui_el_input_text(
												
											array(
												
												'text' => lang( $current_field ),
												'title' => lang( 'tip_field_' . $current_field ),
												'name' => 'fields[' . $key . '][' . $current_field . ']',
												'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : $this->sfcm->make_field_name( ( isset( $field[ 'label' ] ) ? $field[ 'label' ] : ( isset( $field[ 'presentation_label' ] ) ? $field[ 'presentation_label' ] : lang( 'field' ) . ' ' . $key ) ) ),
												'class' => 'sf-field-' . $current_field,
												'id' => 'field-' . $current_field . '-' . $key,
												
											)
											
										); ?>
										
									</div><?php
									
									$current_field = 'label';
									echo form_error( $current_field . '_' . $key, '<div class="msg-inline-error">', '</div>' );
									?><div class="vui-field-wrapper-inline">
										
										<?= form_label( lang( $current_field ) ); ?>
										
										<?= vui_el_input_text(
												
											array(
												
												'text' => lang( $current_field ),
												'title' => lang( 'tip_field_' . $current_field ),
												'name' => 'fields[' . $key . '][' . $current_field . ']',
												'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : lang( 'field' ) . ' ' . $key,
												'class' => 'sf-field-' . $current_field,
												'id' => 'field-' . $current_field . '-' . $key,
												
											)
											
										); ?>
										
									</div><?php
									
									$current_field = 'presentation_label';
									echo form_error( $current_field . '_' . $key, '<div class="msg-inline-error">', '</div>' );
									?><div class="vui-field-wrapper-inline">
										
										<?= form_label( lang( $current_field ) ); ?>
										
										<?= vui_el_input_text(
												
											array(
												
												'text' => lang( $current_field ),
												'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : ( isset( $field[ 'label' ] ) ? $field[ 'label' ] : lang( 'field' ) . ' ' . $key ),
												'id' => 'field-' . $current_field . '-' . $key,
												'name' => 'fields[' . $key . '][' . $current_field . ']',
												'class' => 'sf-field-' . $current_field,
												'title' => lang( 'tip_field_' . $current_field ),
												
											)
											
										); ?>
										
									</div><?php
									
									if ( $field[ 'field_type' ] == 'textarea' ) {
										
										$current_field = 'sf_field_type_textarea_allow_html';
										
										?><div class="vui-field-wrapper-inline">
											
											<?php
												
												echo vui_el_checkbox(
														
													array(
														
														'text' => lang( $current_field ),
														'title' => 'tip_' . lang( $current_field ),
														'name' => 'fields[' . $key . '][' . $current_field . ']',
														'checked' => isset( $field[ $current_field ] ) ? TRUE : FALSE,
														'class' => 'sf-field-' . $current_field,
														'value' => 1,
														'id' => 'field-' . $current_field . '-' . $key,
														
													)
													
												);
												
												$current_field = 'sf_field_type_textarea_us_use_editor';
												
												echo vui_el_checkbox(
														
													array(
														
														'text' => lang( $current_field . '_admin' ),
														'title' => 'tip_' . lang( $current_field . '_admin' ),
														'name' => 'fields[' . $key . '][' . $current_field . '][admin]',
														'checked' => isset( $field[ $current_field ][ 'admin' ] ) ? TRUE : FALSE,
														'class' => 'sf-field-' . $current_field . '_admin',
														'value' => 1,
														'id' => 'field-' . $current_field . '_admin' . '-' . $key,
														
													)
													
												);
												
												echo vui_el_checkbox(
														
													array(
														
														'text' => lang( $current_field . '_site' ),
														'title' => 'tip_' . lang( $current_field . '_site' ),
														'name' => 'fields[' . $key . '][' . $current_field . '][site]',
														'checked' => isset( $field[ $current_field ][ 'site' ] ) ? TRUE : FALSE,
														'class' => 'sf-field-' . $current_field . '_site',
														'value' => 1,
														'id' => 'field-' . $current_field . '_site' . '-' . $key,
														
													)
													
												);
												
											?>
											
										</div><?php
										
										$current_field = 'default_value';
										echo form_error( $current_field . '_' . $key, '<div class="msg-inline-error">', '</div>' );
										?><div class="vui-field-wrapper"><?php
											
											echo form_label( lang( $current_field ) );
											
											echo vui_el_checkbox(
												
												array(
													
													'text' => lang( $current_field . '_use_editor' ),
													'title' => 'tip_' . lang( $current_field . '_use_editor' ),
													'name' => 'fields[' . $key . '][' . $current_field . '_use_editor]',
													'checked' => check_var( $field[ $current_field . '_use_editor' ] ) ? TRUE : FALSE,
													'class' => 'sf-field-' . $current_field . '_use_editor',
													'value' => 1,
													'id' => 'field-' . $current_field . '_use_editor' . '-' . $key,
													
												)
												
											);
											
											echo vui_el_textarea(
													
												array(
													
													'text' => lang( $current_field ),
													'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : '',
													'id' => 'field-' . $current_field . '-' . $key,
													'name' => 'fields[' . $key . '][' . $current_field . ']',
													'class' => 'sf-field-' . $current_field . ( isset( $field[ $current_field . '_use_editor' ] ) ? ' js-editor' : '' ),
													'title' => lang( 'tip_field_' . $current_field )
													
												)
												
											); ?>
											
										</div><?php
										
									}
									else {
										
										$current_field = 'default_value';
										echo form_error( $current_field . '_' . $key, '<div class="msg-inline-error">', '</div>' );
										?><div class="vui-field-wrapper-inline">
											
											<?= form_label( lang( $current_field ) ); ?>
											
											<?= vui_el_input_text(
													
												array(
													
													'text' => lang( $current_field ),
													'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : '',
													'id' => 'field-' . $current_field . '-' . $key,
													'name' => 'fields[' . $key . '][' . $current_field . ']',
													'class' => 'sf-field-' . $current_field,
													'title' => lang( 'tip_field_' . $current_field ),
													
												)
												
											); ?>
											
										</div><?php
										
									}
									
									$current_field = 'form_css_class';
									echo form_error( $current_field . '_' . $key, '<div class="msg-inline-error">', '</div>' );
									?><div class="vui-field-wrapper-inline">
										
										<?= form_label( lang( $current_field ) ); ?>
										
										<?= vui_el_input_text(
												
											array(
												
												'text' => lang( $current_field ),
												'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : '',
												'id' => 'field-' . $current_field . '-' . $key,
												'name' => 'fields[' . $key . '][' . $current_field . ']',
												'class' => 'sf-field-' . $current_field,
												'title' => lang( 'tip_field_' . $current_field ),
												
											)
											
										); ?>
										
									</div><?php
									
									$current_field = 'view_css_class';
									echo form_error( $current_field . '_' . $key, '<div class="msg-inline-error">', '</div>' );
									?><div class="vui-field-wrapper-inline">
										
										<?= form_label( lang( $current_field ) ); ?>
										
										<?= vui_el_input_text(
												
											array(
												
												'text' => lang( $current_field ),
												'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : '',
												'id' => 'field-' . $current_field . '-' . $key,
												'name' => 'fields[' . $key . '][' . $current_field . ']',
												'class' => 'sf-field-' . $current_field,
												'title' => lang( 'tip_field_' . $current_field )
												
											)
											
										);
										
									?></div><?php
									
									?><hr /><?php
									
									if ( $field[ 'field_type' ] === 'articles' ){
										
										$current_field = 'articles_category_id';
										$options = array(
											
											0 => lang( 'uncategorized' ),
											-1 => lang( 'all_articles' ),
											
										);
										
										if ( check_var( $articles_categories ) ){
											
											foreach( $articles_categories as $category ){
												
												$options[ $category[ 'id' ] ] = $category[ 'indented_title' ];
												
											};
											
										}
										
										?><div class="vui-field-wrapper-inline">
											
											<?= form_label( lang( 'select_a_articles_category' ) ); ?>
											<?= form_error( $current_field . '_' . $key, '<div class="msg-inline-error">', '</div>' ); ?>
											
											<?= vui_el_dropdown(
													
												array(
													
													'name' => 'fields[' . $key . '][' . $current_field . ']',
													'options' => $options,
													'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : 0,
													'class' => 'sf-field-' . $current_field,
													'id' => 'field-' . $current_field . '-' . $key,
													
												)
												
											); ?>
											
										</div><?php
										
										?><hr />
										
									<?php } ?>
									
									<?php if ( $field[ 'field_type' ] === 'date' ){ ?>
										
										<?php $current_field = 'sf_date_field_use_day'; ?>
										
										<div class="vui-field-wrapper-inline">
											
											<?= form_label( lang( 'sf_date_field_use_day' ) ); ?>
											<?php
												
												$options = array(
													
													'1' => lang( 'yes' ),
													'0' => lang( 'no' ),
													
												);
												
											?>
											
											<?= vui_el_dropdown(
													
												array(
													
													'name' => 'fields[' . $key . '][' . $current_field . ']',
													'options' => $options,
													'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : 1,
													'class' => 'sf-field-' . $current_field,
													'id' => 'field-' . $current_field . '-' . $key,
													
												)
												
											); ?>
											
										</div><?php
										
										$current_field = 'sf_date_field_day_is_req';
										
										?><div class="vui-field-wrapper-inline">
											
											<?= form_label( lang( $current_field) ); ?>
											<?php
												
												$options = array(
													
													'1' => lang( 'yes' ),
													'0' => lang( 'no' ),
													
												);
												
											?>
											
											<?= vui_el_dropdown(
													
												array(
													
													'name' => 'fields[' . $key . '][' . $current_field . ']',
													'options' => $options,
													'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : 0,
													'class' => 'sf-field-' . $current_field,
													'id' => 'field-' . $current_field . '-' . $key,
													
												)
												
											); ?>
											
										</div><?php
										
										$current_field = 'sf_date_field_day_min_value';
										
										?><div class="vui-field-wrapper-inline">
											
											<?= form_label( lang( $current_field ) ); ?>
											<?= form_error( $current_field . '_' . $key, '<div class="msg-inline-error">', '</div>' ); ?>
											
											<?= vui_el_input_number(
													
												array(
													
													'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : 1,
													'id' => 'field-' . $current_field . '-' . $key,
													'name' => 'fields[' . $key . '][' . $current_field . ']',
													'min' => 1,
													'max' => 31,
													'class' => 'sf-field-date sf-field-date-day-min field-' . $current_field,
													'title' => lang( 'tip_field_' . $current_field )
													
												)
												
											); ?>
											
										</div><?php
										
										$current_field = 'sf_date_field_day_max_value';
										
										?><div class="vui-field-wrapper-inline">
											
											<?= form_label( lang( $current_field ) ); ?>
											<?= form_error( $current_field . '_' . $key, '<div class="msg-inline-error">', '</div>' ); ?>
											
											<?= vui_el_input_number(
													
												array(
													
													'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : 31,
													'id' => 'field-' . $current_field . '-' . $key,
													'name' => 'fields[' . $key . '][' . $current_field . ']',
													'min' => 1,
													'max' => 31,
													'class' => 'sf-field-date sf-field-date-day-max field-' . $current_field,
													'title' => lang( 'tip_field_' . $current_field ),
													
												)
												
											); ?>
											
										</div><?php
										
										$current_field = 'sf_date_field_day_def_value';
										
										?><div class="vui-field-wrapper-inline">
											
											<?= form_label( lang( $current_field ) ); ?>
											<?= form_error( $current_field . '_' . $key, '<div class="msg-inline-error">', '</div>' ); ?>
											
											<?= vui_el_input_number(
													
												array(
													
													'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : '',
													'id' => 'field-' . $current_field . '-' . $key,
													'name' => 'fields[' . $key . '][' . $current_field . ']',
													'min' => 1,
													'max' => 31,
													'class' => 'sf-field-date sf-field-date-day-def field-' . $current_field,
													'title' => lang( 'tip_field_' . $current_field ),
													
												)
												
											); ?>
											
										</div><?php
										
										// --------------------------
										
										 $current_field = 'sf_date_field_use_month';
										
										?><div class="vui-field-wrapper-inline">
											
											<?= form_label( lang( $current_field ) ); ?>
											<?php
												
												$options = array(
													
													'1' => lang( 'yes' ),
													'0' => lang( 'no' ),
													
												);
												
											?>
											
											<?= vui_el_dropdown(
													
												array(
													
													'name' => 'fields[' . $key . '][' . $current_field . ']',
													'options' => $options,
													'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : 1,
													'class' => 'sf-field-' . $current_field,
													'id' => 'field-' . $current_field . '-' . $key,
													
												)
												
											); ?>
											
										</div><?php
										
										$current_field = 'sf_date_field_month_is_req';
										
										?><div class="vui-field-wrapper-inline">
											
											<?= form_label( lang( $current_field ) ); ?>
											<?php
												
												$options = array(
													
													'1' => lang( 'yes' ),
													'0' => lang( 'no' ),
													
												);
												
											?>
											
											<?= vui_el_dropdown(
													
												array(
													
													'name' => 'fields[' . $key . '][' . $current_field . ']',
													'options' => $options,
													'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : 0,
													'class' => 'sf-field-' . $current_field,
													'id' => 'field-' . $current_field . '-' . $key,
													
												)
												
											); ?>
											
										</div><?php
										
										$current_field = 'sf_date_field_month_min_value';
										
										?><div class="vui-field-wrapper-inline">
											
											<?= form_label( lang( $current_field ) ); ?>
											<?= form_error( $current_field . '_' . $key, '<div class="msg-inline-error">', '</div>' ); ?>
											
											<?= vui_el_input_number(
													
												array(
													
													'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : 1,
													'id' => 'field-' . $current_field . '-' . $key,
													'name' => 'fields[' . $key . '][' . $current_field . ']',
													'min' => 1,
													'max' => 12,
													'class' => 'sf-field-date sf-field-date-month-min field-' . $current_field,
													'title' => lang( 'tip_' . $current_field ),
													
												)
												
											); ?>
											
										</div><?php
										
										$current_field = 'sf_date_field_month_max_value';
										
										?><div class="vui-field-wrapper-inline">
											
											<?= form_label( lang( 'sf_date_field_month_max_value' ) ); ?>
											<?= form_error( $current_field . '_' . $key, '<div class="msg-inline-error">', '</div>' ); ?>
											
											<?= vui_el_input_number(
													
												array(
													
													'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : 12,
													'id' => 'field-' . $current_field . '-' . $key,
													'name' => 'fields[' . $key . '][' . $current_field . ']',
													'min' => 1,
													'max' => 12,
													'class' => 'sf-field-date sf-field-date-month-max field-' . $current_field,
													'title' => lang( 'tip_field_' . $current_field ),
													
												)
												
											); ?>
											
										</div><?php
										
										$current_field = 'sf_date_field_month_def_value';
										
										?><div class="vui-field-wrapper-inline">
											
											<?= form_label( lang( 'sf_date_field_month_def_value' ) ); ?>
											<?= form_error( $current_field . '_' . $key, '<div class="msg-inline-error">', '</div>' ); ?>
											
											<?= vui_el_input_number(
													
												array(
													
													'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : '',
													'id' => 'field-' . $current_field . '-' . $key,
													'name' => 'fields[' . $key . '][' . $current_field . ']',
													'min' => 1,
													'max' => 12,
													'class' => 'sf-field-date sf-field-date-month-def field-' . $current_field,
													'title' => lang( 'tip_field_' . $current_field ),
													
												)
												
											); ?>
											
										</div><?php
										
										// --------------------------
										
										$current_field = 'sf_date_field_use_year';
										
										?><div class="vui-field-wrapper-inline">
											
											<?= form_label( lang( 'sf_date_field_use_year' ) ); ?>
											<?php
												
												$options = array(
													
													'1' => lang( 'yes' ),
													'0' => lang( 'no' ),
													
												);
												
											?>
											
											<?= vui_el_dropdown(
													
												array(
													
													'name' => 'fields[' . $key . '][' . $current_field . ']',
													'options' => $options,
													'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : 1,
													'class' => 'sf-field-' . $current_field,
													'id' => 'field-' . $current_field . '-' . $key,
													
												)
												
											); ?>
											
										</div><?php
										
										$current_field = 'sf_date_field_year_is_req';
										
										?><div class="vui-field-wrapper-inline">
											
											<?= form_label( lang( 'sf_date_field_year_is_req' ) ); ?>
											<?php
												
												$options = array(
													
													'1' => lang( 'yes' ),
													'0' => lang( 'no' ),
													
												);
												
											?>
											
											<?= vui_el_dropdown(
													
												array(
													
													'name' => 'fields[' . $key . '][' . $current_field . ']',
													'options' => $options,
													'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : 0,
													'class' => 'sf-field-' . $current_field,
													'id' => 'field-' . $current_field . '-' . $key,
													
												)
												
											); ?>
											
										</div><?php
										
										$current_field = 'sf_date_field_year_min_value';
										
										?><div class="vui-field-wrapper-inline">
											
											<?= form_label( lang( 'sf_date_field_year_min_value' ) ); ?>
											<?= form_error( $current_field . '_' . $key, '<div class="msg-inline-error">', '</div>' ); ?>
											
											<?= vui_el_input_number(
													
												array(
													
													'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : 1920,
													'id' => 'field-' . $current_field . '-' . $key,
													'name' => 'fields[' . $key . '][' . $current_field . ']',
													'min' => 1920,
													'max' => date( 'Y', PHP_INT_MAX ),
													'class' => 'sf-field-date sf-field-date-year-min field-' . $current_field,
													'title' => lang( 'tip_field_' . $current_field ),
													
												)
												
											); ?>
											
										</div><?php
										
										$current_field = 'sf_date_field_year_max_value';
										
										?><div class="vui-field-wrapper-inline">
											
											<?= form_label( lang( 'sf_date_field_year_max_value' ) ); ?>
											<?= form_error( $current_field . '_' . $key, '<div class="msg-inline-error">', '</div>' ); ?>
											
											<?= vui_el_input_number(
													
												array(
													
													'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : date( 'Y' ) + 25,
													'id' => 'field-' . $current_field . '-' . $key,
													'name' => 'fields[' . $key . '][' . $current_field . ']',
													'min' => 1920,
													'max' => date( 'Y', PHP_INT_MAX ),
													'class' => 'sf-field-date sf-field-date-year-max field-' . $current_field,
													'title' => lang( 'tip_field_' . $current_field ),
													
												)
												
											); ?>
											
										</div><?php
										
										$current_field = 'sf_date_field_year_def_value';
										
										?><div class="vui-field-wrapper-inline">
											
											<?= form_label( lang( 'sf_date_field_year_def_value' ) ); ?>
											<?= form_error( $current_field . '_' . $key, '<div class="msg-inline-error">', '</div>' ); ?>
											
											<?= vui_el_input_number(
													
												array(
													
													'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : '',
												'id' => 'field-' . $current_field . '-' . $key,
												'name' => 'fields[' . $key . '][' . $current_field . ']',
												'min' => 1920,
												'max' => date( 'Y', PHP_INT_MAX ),
												'class' => 'sf-field-date sf-field-date-year-def field-' . $current_field,
												'title' => lang( 'tip_field_' . $current_field ),
													
												)
												
											); ?>
											
										</div><?php
										
										// --------------------------
										
										$current_field = 'sf_date_field_hide_current_day';
										
										?><div class="vui-field-wrapper-inline">
											
											<?= form_label( lang( $current_field ) ); ?>
											<?php
												
												$options = array(
													
													'1' => lang( 'yes' ),
													'0' => lang( 'no' ),
													
												);
												
											?>
											
											<?= vui_el_dropdown(
													
												array(
													
													'name' => 'fields[' . $key . '][' . $current_field . ']',
													'options' => $options,
													'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : 1,
													'class' => 'sf-field-' . $current_field,
													'id' => 'field-' . $current_field . '-' . $key,
													
												)
												
											); ?>
											
										</div><?php
										
										$current_field = 'sf_date_field_hide_current_month';
										
										?><div class="vui-field-wrapper-inline">
											
											<?= form_label( lang( $current_field ) ); ?>
											<?php
												
												$options = array(
													
													'1' => lang( 'yes' ),
													'0' => lang( 'no' ),
													
												);
												
											?>
											
											<?= vui_el_dropdown(
													
												array(
													
													'name' => 'fields[' . $key . '][' . $current_field . ']',
													'options' => $options,
													'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : 1,
													'class' => 'sf-field-' . $current_field,
													'id' => 'field-' . $current_field . '-' . $key,
													
												)
												
											); ?>
											
										</div><?php
										
										$current_field = 'sf_date_field_hide_current_year';
										
										?><div class="vui-field-wrapper-inline">
											
											<?= form_label( lang( $current_field ) ); ?>
											<?php
												
												$options = array(
													
													'1' => lang( 'yes' ),
													'0' => lang( 'no' ),
													
												);
												
											?>
											
											<?= vui_el_dropdown(
													
												array(
													
													'name' => 'fields[' . $key . '][' . $current_field . ']',
													'options' => $options,
													'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : 1,
													'class' => 'sf-field-' . $current_field,
													'id' => 'field-' . $current_field . '-' . $key,
													
												)
												
											); ?>
											
										</div><?php
										
										$current_field = 'sf_date_field_relative_datetime';
										
										?><div class="vui-field-wrapper-inline">
											
											<?= form_label( lang( $current_field ) ); ?>
											<?php
												
												$options = array(
													
													'1' => lang( 'yes' ),
													'0' => lang( 'no' ),
													
												);
												
											?>
											
											<?= vui_el_dropdown(
													
												array(
													
													'name' => 'fields[' . $key . '][' . $current_field . ']',
													'options' => $options,
													'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : 1,
													'class' => 'sf-field-' . $current_field,
													'id' => 'field-' . $current_field . '-' . $key,
													
												)
												
											); ?>
											
										</div><?php
										
										$current_field = 'sf_date_field_presentation_format';
										
										?><div class="vui-field-wrapper-inline">
											
											<?= form_label( lang( $current_field ) ); ?>
											
											<?php
												
												$options = array();
												
												for ( $opts = 1; $opts <= 2; $opts++ ) {
													
													$options[ $opts ] = strftime( lang( 'sf_us_dt_ft_op_pt_' . $opts ) );
													
												}
												
											?>
											
											<?= vui_el_dropdown(
													
												array(
													
													'name' => 'fields[' . $key . '][' . $current_field . ']',
													'options' => $options,
													'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : 1,
													'class' => 'sf-field-' . $current_field,
													'id' => 'field-' . $current_field . '-' . $key,
													
												)
												
											); ?>
											
										</div><?php
										
									?>
									
									<?php }
									
									if ( count ( $submit_form[ 'fields' ] ) > 1 ) {
										
										echo '<details>';
										echo '<summary>';
										echo lang( 'ud_field_conditions' );
										echo '</summary>';
										
										$current_field = 'conditional_field';
										?><div class="vui-field-wrapper-inline">
											
											<?= form_label( lang( 'conditional_field' ) ); ?>
											<?php

												$options = array(

													'1' => lang( 'yes' ),
													'0' => lang( 'no' ),

												);

											?>
											
											<?= vui_el_dropdown(
													
												array(
													
													'name' => 'fields[' . $key . '][' . $current_field . ']',
													'options' => $options,
													'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : 0,
													'class' => 'sf-field-' . $current_field,
													'id' => 'field-' . $current_field . '-' . $key,
													
												)
												
											); ?>
											
										</div><?php
										
										$current_field = 'conditional_field_function';
										?><div class="vui-field-wrapper-inline">
											
											<?= form_label( lang( 'conditional_field_function' ) ); ?>
											<?php
												
												$options = array(
													
													'show' => lang( 'show' ),
													
												);
												
											?>
											
											<?= vui_el_dropdown(
													
												array(
													
													'name' => 'fields[' . $key . '][' . $current_field . ']',
													'options' => $options,
													'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : 0,
													'class' => 'sf-field-' . $current_field,
													'id' => 'field-' . $current_field . '-' . $key,
													
												)
												
											); ?>
											
										</div><?php
										
										$current_field = 'conditional_target_field';
										?><div class="vui-field-wrapper-inline">
											
											<?= form_label( lang( 'conditional_target_field' ) ); ?>
											<?php
												
												//print_r( $field );
												
												$options = array();
												
												foreach( $conditional_fields_targets as $key_2 => $field_cond ) {
													
													// se o campo no loop principal for diferente do campo neste loop
													// Objetivo: não queremos o próprio na lista de campos que influenciam as condições
													// E também não queremos aplicar condições para os campos do tipo HTML nem button
													if ( $field_cond[ 'key' ] !== $field[ 'key' ] ){
														
														$field_cond_label = isset( $field_cond[ 'presentation_label' ] ) ? $field_cond[ 'presentation_label' ] : lang( 'field' ) . ' ' . $key_2;
														$field_cond_alias = isset( $field_cond[ 'alias' ] ) ? $field_cond[ 'alias' ] : $this->sfcm->make_field_name( $field_cond_label );
														
														$options[ $field_cond_alias ] = $field_cond_label;
														
													}
													
												}
												
											?>
											
											<?= vui_el_dropdown(
													
												array(
													
													'name' => 'fields[' . $key . '][' . $current_field . ']',
													'options' => $options,
													'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : 0,
													'class' => 'sf-field-' . $current_field,
													'id' => 'field-' . $current_field . '-' . $key,
													
												)
												
											); ?>
											
										</div><?php
										
										$current_field = 'conditional_field_cond';
										?><div class="vui-field-wrapper-inline">
											
											<?= form_label( lang( 'conditional_field_cond' ) ); ?>
											<?php
												
												$options = array(
													
													'equal' => lang( 'equal' ),
													'different' => lang( 'different_of' ),
													
												);
												
											?>
											
											<?= vui_el_dropdown(
													
												array(
													
													'name' => 'fields[' . $key . '][' . $current_field . ']',
													'options' => $options,
													'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : 'different',
													'class' => 'sf-field-' . $current_field,
													'id' => 'field-' . $current_field . '-' . $key,
													
												)
												
											); ?>
											
										</div><?php
										
										$current_field = 'conditional_field_values';
										?><div class="vui-field-wrapper-inline">
											
											<?= form_label( lang( $current_field ) ); ?>
											
											<?= vui_el_input_text(
													
												array(
													
													'text' => lang( $current_field ),
													'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : '',
													'id' => 'field-' . $current_field . '-' . $key,
													'name' => 'fields[' . $key . '][' . $current_field . ']',
													'class' => 'sf-field-' . $current_field,
													'title' => lang( 'tip_field_' . $current_field )
													
												)
												
											); ?>
											
										</div><?php
										
										echo '</details>';
										
									}
									
									if ( ! in_array( $field[ 'field_type' ], array( 'button', 'html' ) ) ){
										
										echo '<details class="validation-rules-fields-wrapper">';
										echo '<summary>';
										echo lang( 'validation_rule' );
										echo '</summary>';
										
										echo '<div class="vui-field-wrapper-inline">';
										
										$current_field = 'field_is_required';
										
										echo vui_el_checkbox(
											
											array(
												
												'text' => lang( $current_field ),
												'title' => lang( 'tip_ud_' . $current_field ),
												'name' => 'fields[' . $key . '][' . $current_field . ']',
												'checked' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : FALSE,
												'class' => 'sf-field-' . $current_field,
												'value' => $current_field,
												'id' => 'field-' . $current_field . '-' . $key,
												
											)
											
										);
										
										echo '</div>';
										
										$current_field = 'validation_rule';
										
										$options = array(
											
											'matches' => lang( 'submit_forms_validation_rule_matches' ),
											'valid_domain' => lang( 'submit_forms_validation_rule_valid_domain' ),
											'valid_domain_dns' => lang( 'submit_forms_validation_rule_valid_domain_dns' ),
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
											'alpha_space' => lang( 'ud_validation_rule_alpha_space' ),
											'alpha_dash_space' => lang( 'submit_forms_validation_rule_alpha_dash_space' ),
											'numeric' => lang( 'submit_forms_validation_rule_numeric' ),
											'integer' => lang( 'submit_forms_validation_rule_integer' ),
											'decimal' => lang( 'submit_forms_validation_rule_decimal' ),
											'is_natural' => lang( 'submit_forms_validation_rule_is_natural' ),
											'is_natural_no_zero' => lang( 'submit_forms_validation_rule_is_natural_no_zero' ),
											'valid_ip' => lang( 'submit_forms_validation_rule_valid_ip' ),
											'valid_base64' => lang( 'submit_forms_validation_rule_valid_base64' ),
											'normalizar_nome_ptbr' => lang( 'submit_forms_validation_rule_normalizar_nome_ptbr' ),
											'uppercase' => lang( 'submit_forms_validation_rule_uppercase' ),
											'lowercase' => lang( 'submit_forms_validation_rule_lowercase' ),
											'mask' => lang( 'ud_validation_rule_mask' ),
											'check_cpf' => lang( 'ud_validation_cpf_ptbr' ),
											
										);
										
										foreach( $options as $k => $v ) {
											
											?><div class="vui-field-wrapper-inline"><?php
											
											$__checkbox_settings = array(
												
												'text' => $v,
												'name' => 'fields[' . $key . '][' . $current_field . '][]',
												'checked' => isset( $field[ $current_field ][ $k ] ) ? TRUE : ( ( isset( $field[ $current_field ] ) AND in_array( $k, $field[ $current_field ] ) ) ? TRUE : FALSE ),
												'class' => 'sf-field-' . $current_field,
												'value' => $k,
												'id' => 'field-' . $current_field . '-' . $key,
												
											);
											
											if ( in_array( $k, array( 'matches' ) ) ) {
												
												$__checkbox_settings[ 'title' ] = lang( 'tip_ud_validation_rule_' . $k );
												
											}
											
											echo vui_el_checkbox( $__checkbox_settings );
											
											$validation_rule = & $k;
											
											if ( in_array( $validation_rule, array( 'min_length', 'max_length', 'exact_length', 'greater_than', 'less_than', ) ) ){
												
												$__current_field = 'validation_rule_parameter_' . $validation_rule;
												echo form_error( $__current_field . '_' . $key, '<div class="msg-inline-error">', '</div>' );
												?><div class="vui-field-wrapper">
													
													<?= vui_el_input_number(
														
														array(
															
															'text' => lang( $__current_field ),
															'value' => isset( $field[ $__current_field ] ) ? $field[ $__current_field ] : '',
															'id' => 'field-' . $__current_field . '-' . $key,
															'name' => 'fields[' . $key . '][' . $__current_field . ']',
															'class' => 'ud-' . $__current_field . ' sf-field-' . $__current_field,
															'title' => lang( 'tip_field_' . $__current_field )
															
														)
														
													); ?>
													
												</div><?php
												
											} else if ( in_array( $validation_rule, array( 'matches', ) ) ){
												
												$__current_field = 'validation_rule_parameter_' . $validation_rule;
												echo form_error( $__current_field . '_' . $key, '<div class="msg-inline-error">', '</div>' );
												?><div class="vui-field-wrapper"><?php
														
														$options = array();
														
														foreach( $conditional_fields_targets as $key_2 => $field_cond ) {
															
															// se o campo no loop principal for diferente do campo neste loop
															// Objetivo: não queremos o próprio na lista de campos que influenciam as condições
															// E também não queremos aplicar condições para os campos do tipo HTML nem button
															if ( $field_cond[ 'key' ] !== $field[ 'key' ] ){
																
																$field_cond_label = isset( $field_cond[ 'label' ] ) ? $field_cond[ 'label' ] : lang( 'field' ) . ' ' . $key_2;
																$field_cond_alias = isset( $field_cond[ 'alias' ] ) ? $field_cond[ 'alias' ] : $this->sfcm->make_field_name( $field_cond_label );
																
																$options[ $field_cond_alias ] = $field_cond_label;
																
															}
															
														}
														
														if ( $options ) {
															
															echo form_label( lang( $__current_field ) );
															
															echo vui_el_dropdown(
																
																array(
																	
																	'name' => 'fields[' . $key . '][field_' . $__current_field . ']',
																	'options' => $options,
																	'value' => isset( $field[ $__current_field ] ) ? $field[ $__current_field ] : $field_type_default,
																	'class' => 'sf-field-' . $__current_field,
																	'id' => 'field-' . $__current_field . '-' . $key,
																	'title' => lang( 'tip_field_' . $__current_field ),
																	
																)
																
															);
															
														}
														else {
															
															echo lang( 'validation_rule_matches_no_fields' );
															
														}
														
												?></div><?php
												
											} else if ( $validation_rule == 'mask' ){
												
												$__current_field = 'ud_validation_rule_parameter_mask_type';
												
												echo form_error( $__current_field . '_' . $key, '<div class="msg-inline-error">', '</div>' );
												echo '<div class="vui-field-wrapper ud_validation_rule_parameter_mask_elements_wrapper">';
												
												// ------------------------------------
												// Mask type selector
												
												$options = array(
													
													'custom_mask' => lang( 'ud_validation_rule_parameter_mask_type_custom_mask' ),
													'zip_brazil' => lang( 'ud_validation_rule_parameter_mask_type_zip_brazil' ),
													'phone_brazil' => lang( 'ud_validation_rule_parameter_mask_type_phone_brazil' ),
													
												);
												
												echo vui_el_dropdown(
													
													array(
														
														'name' => 'fields[' . $key . '][' . $__current_field . ']',
														'options' => $options,
														'value' => isset( $field[ 'field_' . $__current_field ] ) ? $field[ $__current_field ] : 'custom_mask',
														'class' => 'ud-field-' . $__current_field,
														'id' => $__current_field . '-' . $key,
														'title' => lang( 'tip_' . $__current_field ),
														
													)
													
												);
												
												$__current_field = 'ud_validation_rule_parameter_mask_custom_mask';
												
												echo form_label( lang( $__current_field ) );
												
												// ------------------------------------
												// Custom mask
												
												echo vui_el_input_text(
													
													array(
														
														'name' => 'fields[' . $key . '][' . $__current_field . ']',
														'value' => isset( $field[ 'field_' . $__current_field ] ) ? $field[ 'field_' . $__current_field ] : '(99) 9999-9999',
														'class' => 'ud-field-' . $__current_field,
														'id' => $__current_field . '-' . $key,
														'title' => lang( 'tip_field_' . $__current_field ),
														
													)
													
												);
												
												$__current_field = 'ud_validation_rule_parameter_mask_test';
												
												echo form_label( lang( $__current_field ) );
												
												echo vui_el_input_text(
													
													array(
														
														'name' => 'fields[' . $key . '][' . $__current_field . ']',
														'class' => 'ud-field-' . $__current_field,
														'id' => $__current_field . '-' . $key,
														'title' => lang( 'tip_field_' . $__current_field ),
														'attr' => array( 'data-input-mask-el-id' => $__current_field . '-' . $key, )
														
													)
													
												);
												
												$this->plugins->load( 'jquery_maskedinput' );
												
												?>
												
												<script type="text/javascript">
													
													$( document ).on( 'keypress', '#ud_validation_rule_parameter_mask_test-<?= $key; ?>', function(){
														
														if ( $( this ).attr( 'data-masked' ) != '1' ) {
															
															$( this ).attr( 'data-masked', '1' );
															
															$mask = $( '#ud_validation_rule_parameter_mask_custom_mask-<?= $key; ?>' ).val();
															
															$( this ).mask( '(99) 9999-9999x9' );
															
														}
														
													});
													
													$( document ).on( 'blur', '#ud_validation_rule_parameter_mask_test-<?= $key; ?>', function(){
														
														if ( $( this ).attr( 'data-masked' ) == '1' ) {
															
															$( this ).attr( 'data-masked', null );
															
															if($(this).val().length == 16){ // Celular com 9 dígitos + 2 dígitos DDD e 4 da máscara
																$(this).mask('(99) 99999-9999');
															} else {
																$(this).mask('(99) 9999-9999');
															}
															
														}
														
													});
													
												</script>
												
												<?php
												
												echo '</div>';
												
											} else {
												
											}
											
											?></div><?php
											
										}
										
										echo '</details>';
										
									}
									
									if ( in_array( $field[ 'field_type' ], array( 'input_text', 'textarea', 'checkbox', 'combo_box', 'radiobox', 'date', 'articles', ) ) ) {
										
										echo '<details>';
										echo '<summary>';
										echo lang( 'advanced_options' );
										echo '</summary>';
										
										$current_field = 'advanced_options';
										
										echo form_label( lang( 'ud_prop_type' ) );
										
										$options =  array(
											
											'prop_is_ud_image' => lang( 'ud_advanced_options_prop_is_ud_image' ),
											'prop_is_ud_file' => lang( 'ud_advanced_options_prop_is_ud_file' ),
											'prop_is_ud_title' => lang( 'ud_advanced_options_prop_is_ud_title' ),
											'prop_is_ud_content' => lang( 'ud_advanced_options_prop_is_ud_content' ),
											'prop_is_ud_other_info' => lang( 'ud_advanced_options_prop_is_ud_other_info' ),
											'prop_is_ud_email' => lang( 'ud_advanced_options_prop_is_ud_email' ),
											'prop_is_ud_url' => lang( 'ud_advanced_options_prop_is_ud_url' ),
											'prop_is_ud_status' => lang( 'ud_advanced_options_prop_is_ud_status' ),
											
										);
										
										if ( in_array( $field[ 'field_type' ], array( 'date', ) ) ) {
											
											unset( $options[ 'prop_is_ud_image' ] );
											unset( $options[ 'prop_is_ud_file' ] );
											unset( $options[ 'prop_is_ud_email' ] );
											unset( $options[ 'prop_is_ud_url' ] );
											unset( $options[ 'prop_is_ud_status' ] );
											
											$options[ 'prop_is_ud_event_datetime' ] = lang( 'ud_advanced_options_prop_is_ud_event_datetime' );
											
										}
										
										if ( ! in_array( $field[ 'field_type' ], array( 'input_text', ) ) ) {
											
											unset( $options[ 'prop_is_ud_file' ] );
											
										}
										
										if ( check_var( $options ) ) {
											
											foreach( $options as $k => $v ) {
												
												echo '<div class="vui-field-wrapper-inline">';
												
												echo vui_el_checkbox(
													
													array(
														
														'text' => $v,
														'name' => 'fields[' . $key . '][' . $current_field . '][' . $k . ']',
														'checked' => isset( $field[ $current_field ][ $k ] ) ? TRUE : ( ( isset( $field[ $current_field ] ) AND in_array( $k, $field[ $current_field ] ) ) ? TRUE : FALSE ),
														'class' => 'sf-field-' . $current_field . '_' . $k,
														'value' => $k,
														'id' => 'field-' . $current_field . '_' . $k . '-' . $key,
														
													)
													
												);
												
												//------------------------------------------------------
												// prop_is_ud_image
												
												if ( $k == 'prop_is_ud_image' ) {
// 												if ( $k == 'prop_is_ud_image' AND isset( $field[ $current_field ][ $k ] ) ) {
													
													$this->plugins->load(
														
														array(
															
															'names' => array(
																
																'image_cropper',
																'fancybox',
																'modal_rf_file_picker',
																
															),
															
														)
														
													);
													
													//------------------------------------------------------
													// prop_is_ud_image_thumb_prevent_cache_admin
													
													$current_field_sub = 'prop_is_ud_image_thumb_prevent_cache_admin';
													
													echo vui_el_checkbox(
														
														array(
															
															'text' => lang( 'ud_' . $current_field . '_' . $current_field_sub ),
															'title' => lang( 'tip_ud_' . $current_field . '_' . $current_field_sub ),
															'name' => 'fields[' . $key . '][' . $current_field . '][' . $current_field_sub . ']',
															'checked' => isset( $field[ $current_field ][ $current_field_sub ] ) ? TRUE : FALSE,
															'class' => 'sf-field-' . $current_field . '_' . $current_field_sub,
															'id' => 'field-' . $current_field . '_' . $current_field_sub . '-' . $key,
															
														)
														
													);
													
													//------------------------------------------------------
													// prop_is_ud_image_thumb_prevent_cache_site
													
													$current_field_sub = 'prop_is_ud_image_thumb_prevent_cache_site';
													
													echo vui_el_checkbox(
														
														array(
															
															'text' => lang( 'ud_' . $current_field . '_' . $current_field_sub ),
															'title' => lang( 'tip_ud_' . $current_field . '_' . $current_field_sub ),
															'name' => 'fields[' . $key . '][' . $current_field . '][' . $current_field_sub . ']',
															'checked' => isset( $field[ $current_field ][ $current_field_sub ] ) ? TRUE : FALSE,
															'class' => 'sf-field-' . $current_field . '_' . $current_field_sub,
															'id' => 'field-' . $current_field . '_' . $current_field_sub . '-' . $key,
															
														)
														
													);
													
												}
												
												//------------------------------------------------------
												// prop_is_ud_file
												
												if ( $k == 'prop_is_ud_file' ) {
													
													$this->plugins->load(
														
														array(
															
															'names' => array(
																
																'image_cropper',
																'fancybox',
																'modal_rf_file_picker',
																
															),
															
														)
														
													);
													
													//------------------------------------------------------
													// prop_is_ud_file_upload_dir
													
													$current_field_sub = 'prop_is_ud_file_upload_dir';
													
													echo form_label( lang( 'ud_' . $current_field . '_' . $current_field_sub ) );
													
													if ( ! isset( $directories_options ) ) {
														
														$_directories_options = $this->mcm->dir_tree( array( 'dir' => MEDIA_PATH ) );
														
														if ( ! empty( $_directories_options ) ) {
															
															foreach ( $_directories_options as $_k => $_item ){
																
																$rpath = str_replace( BASE_PATH, '', $_k );
																
																$directories_options[ $rpath ] = $_item;
																
															}
															
														}
														
													}
													
													if ( ! empty( $_directories_options ) ) {
														
														echo vui_el_dropdown(
															
															array(
																
																'name' => 'fields[' . $key . '][' . $current_field . '][' . $current_field_sub . ']',
																'options' => $directories_options,
																'value' => isset( $field[ $current_field ][ $current_field_sub ] ) ? $field[ $current_field ][ $current_field_sub ] : '',
																'class' => 'sf-field-' . $current_field . '_' . $current_field_sub,
																'id' => 'field-' . $current_field . '_' . $current_field_sub . '-' . $key,
																'text' => lang( 'ud_' . $current_field . '_' . $current_field_sub ),
																'title' => lang( 'tip_sf_' . $current_field . '_' . $current_field_sub ),
																
															)
															
														);
														
													}
													else {
														
														echo vui_el_button(
															
															array(
																
																'button_type' => 'anchor',
																'text' => lang( 'ud_no_directories' ),
																
															)
															
														);
														
													}
													
													//------------------------------------------------------
													// prop_is_ud_file_overwrite
													
													$current_field_sub = 'prop_is_ud_file_overwrite';
													
													echo vui_el_checkbox(
														
														array(
															
															'text' => lang( 'ud_' . $current_field . '_' . $current_field_sub ),
															'title' => lang( 'tip_ud_' . $current_field . '_' . $current_field_sub ),
															'name' => 'fields[' . $key . '][' . $current_field . '][' . $current_field_sub . ']',
															'checked' => isset( $field[ $current_field ][ $current_field_sub ] ) ? TRUE : FALSE,
															'class' => 'sf-field-' . $current_field . '_' . $current_field_sub,
															'id' => 'field-' . $current_field . '_' . $current_field_sub . '-' . $key,
															
														)
														
													);
													
													//------------------------------------------------------
													// prop_is_ud_file_email_send_attachment
													
													$current_field_sub = 'prop_is_ud_file_email_send_attachment';
													
													echo vui_el_checkbox(
														
														array(
															
															'text' => lang( 'ud_' . $current_field . '_' . $current_field_sub ),
															'title' => lang( 'tip_ud_' . $current_field . '_' . $current_field_sub ),
															'name' => 'fields[' . $key . '][' . $current_field . '][' . $current_field_sub . ']',
															'checked' => isset( $field[ $current_field ][ $current_field_sub ] ) ? TRUE : FALSE,
															'class' => 'sf-field-' . $current_field . '_' . $current_field_sub,
															'id' => 'field-' . $current_field . '_' . $current_field_sub . '-' . $key,
															
														)
														
													);
													
													//------------------------------------------------------
													// file size limit
													
													$current_field_sub = 'prop_is_ud_file_max_size';
													
													echo form_label( lang( 'ud_' . $current_field . '_' . $current_field_sub ) );
													
													echo vui_el_input_text(
														
														array(
															
															'text' => lang( 'ud_' . $current_field . '_' . $current_field_sub ),
															'title' => lang( 'tip_ud_' . $current_field . '_' . $current_field_sub ),
															'name' => 'fields[' . $key . '][' . $current_field . '][' . $current_field_sub . ']',
															'value' => isset( $field[ $current_field ][ $current_field_sub ] ) ? $field[ $current_field ][ $current_field_sub ] : 2048,
															'class' => 'sf-field-' . $current_field . '_' . $current_field_sub,
															'id' => 'field-' . $current_field . '_' . $current_field_sub . '-' . $key,
															
														)
														
													);
													
													//------------------------------------------------------
													// allowed types
													
													$current_field_sub = 'prop_is_ud_file_allowed_types';
													
													echo form_label( lang( 'ud_' . $current_field . '_' . $current_field_sub ) );
													
													echo vui_el_input_text(
														
														array(
															
															'text' => lang( 'ud_' . $current_field . '_' . $current_field_sub ),
															'title' => lang( 'tip_ud_' . $current_field . '_' . $current_field_sub ),
															'name' => 'fields[' . $key . '][' . $current_field . '][' . $current_field_sub . ']',
															'value' => isset( $field[ $current_field ][ $current_field_sub ] ) ? $field[ $current_field ][ $current_field_sub ] : '',
															'class' => 'sf-field-' . $current_field . '_' . $current_field_sub,
															'id' => 'field-' . $current_field . '_' . $current_field_sub . '-' . $key,
															
														)
														
													);
													
												}
												
												//------------------------------------------------------
												// prop_is_ud_status
												
												if ( $k == 'prop_is_ud_status' AND isset( $field[ $current_field ][ $k ] ) ) {
													
													if ( check_var( $field[ 'options_from_users_submits' ] ) AND ( check_var( $field[ 'options_title_field' ] ) OR check_var( $field[ 'options_title_field_custom' ] ) ) ) {
														
														$filters = NULL;
														
														if ( check_var( $field[ 'options_filter_admin' ] ) ) {
															
															$filters = $field[ 'options_filter_admin' ];
															
														}
														else if ( check_var( $field[ 'options_filter' ] ) ) {
															
															$filters = $field[ 'options_filter' ];
															
														}
														
														$search_config = array(
															
															'plugins' => 'sf_us_search',
															'allow_empty_terms' => TRUE,
															'ipp' => 0,
															'cp' => NULL,
															'plugins_params' => array(
																
																'sf_us_search' => array(
																	
																	'sf_id' => $field[ 'options_from_users_submits' ],
																	'filters' => json_decode( $filters, TRUE ),
																	'order_by' => ( isset( $field[ 'options_filter_order_by' ] ) ? $field[ 'options_filter_order_by' ] : $field[ 'options_title_field' ] ),
																	'order_by_direction' => ( isset( $field[ 'options_filter_order_by_direction' ] ) ? $field[ 'options_filter_order_by_direction' ] : 'ASC' ),
																	
																),
																
															),
															
														);
														
														$CI =& get_instance();
														$CI->load->library( 'search' );
														$CI->search->config( $search_config );
														
														$_us = $CI->search->get_full_results( 'sf_us_search', TRUE );
														
														$_us_options = NULL;
														
														if ( check_var( $_us ) ) {
															
															$_us_options = array( '' => lang( 'combobox_select' ), );
															
															foreach( $_us as & $_user_submit ) {
																
																$_user_submit[ 'data' ] = get_params( $_user_submit[ 'data' ] );
																
																if ( $field[ 'options_title_field' ] ) {
																	
																	foreach( $_user_submit[ 'data' ] as $_dk => $_data ) {
																		
																		if ( $_dk == $field[ 'options_title_field' ] )
																		
																		$_us_options[ $_user_submit[ 'id' ] ] = $_data;
																		
																	};
																	
																}
																
															};
															
														}
														else {
															
															$_us_options = array( '' => lang( 'no_us' ), );
															
														}
														
														$_current_field_sub_array = array(
															
															'prop_is_ud_status_active',
															'prop_is_ud_status_inactive',
															'prop_is_ud_status_enabled',
															'prop_is_ud_status_disabled',
															'prop_is_ud_status_canceled',
															'prop_is_ud_status_postponed',
															'prop_is_ud_status_archived',
															'prop_is_ud_status_published',
															'prop_is_ud_status_unpublished',
															'prop_is_ud_status_scheduled',
															
														);
														
														foreach( $_current_field_sub_array as $_item ) {
															
															$current_field_sub = $_item;
															
															if ( $field[ 'field_type' ] == 'combo_box' ) {
																
																echo form_label( lang( 'ud_' . $current_field . '_' . $current_field_sub ) );
																
																echo vui_el_dropdown(
																		
																	array(
																		
																		'name' => 'fields[' . $key . '][' . $current_field . '][' . $current_field_sub . ']',
																		'options' => $_us_options,
																		'value' => isset( $field[ $current_field ][ $current_field_sub ] ) ? $field[ $current_field ][ $current_field_sub ] : '',
																		'class' => 'sf-field-' . $current_field . '_' . $current_field_sub,
																		'id' => 'field-' . $current_field . '_' . $current_field_sub . '-' . $key,
																		'text' => lang( 'sf_' . $current_field . '_' . $current_field_sub ),
																		'title' => lang( 'tip_sf_' . $current_field . '_' . $current_field_sub ),
																		
																	)
																	
																);
																
															}
															
														};
														
													}
													
												}
												
												echo '</div>';
												
											}
											
											unset( $_us_options );
											unset( $_current_field_sub_array );
											unset( $options );
											
										}
										
										echo '</details>';
										
									}
									
									if ( ! in_array( $field[ 'field_type' ], array( 'html' ) ) ){
										
										echo '<hr/>';
										
										$current_field = 'description';
										echo form_error( $current_field . '_' . $key, '<div class="msg-inline-error">', '</div>' );
										?><div class="vui-field-wrapper">
											
											<?= form_label( lang( $current_field ) ); ?>
											
											<?= vui_el_textarea(
													
												array(
													
													'text' => lang( $current_field ),
													'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : '',
													'id' => 'field-' . $current_field . '-' . $key,
													'name' => 'fields[' . $key . '][' . $current_field . ']',
													'class' => 'sf-field-' . $current_field,
													'title' => lang( 'tip_field_' . $current_field )
													
												)
												
											); ?>
											
										</div><?php
										
									}
									
									if ( $field[ 'field_type' ] == 'html' ){
										
										echo '<hr/>';
										
										$current_field = 'sf_html_field_use_editor';
										
										?><div class="vui-field-wrapper-inline">
											
											<?= form_label( lang( 'sf_html_field_use_editor' ) ); ?>
											<?php
												
												$options = array(
													
													'1' => lang( 'yes' ),
													'0' => lang( 'no' ),
													
												);
												
											?>
											
											<?= vui_el_dropdown(
													
												array(
													
													'name' => 'fields[' . $key . '][' . $current_field . ']',
													'options' => $options,
													'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : 0,
													'class' => $current_field,
													'id' => 'field-' . $current_field . '-' . $key,
													
												)
												
											); ?>
											
										</div><?php
										
										$current_field = 'html';
										echo form_error( $current_field . '_' . $key, '<div class="msg-inline-error">', '</div>' );
										?><div class="vui-field-wrapper">
											
											<?= form_label( lang( $current_field ) ); ?>
											
											<?= vui_el_textarea(
													
												array(
													
													'text' => lang( $current_field ),
													'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : '',
													'id' => 'field-' . $current_field . '-' . $key,
													'name' => 'fields[' . $key . '][' . $current_field . ']',
													'class' => 'sf-field-' . $current_field,
													'title' => lang( 'tip_field_' . $current_field )
													
												)
												
											); ?>
											
										</div><?php
										
									} else if ( $field[ 'field_type' ] == 'input_text' ){
									
									
									
									} else if ( in_array( $field[ 'field_type' ], array( 'combo_box', 'radiobox', 'checkbox' ) ) ){
									
									echo '<hr/>';
									
									$current_field = 'options';
									echo form_error( $current_field . '_' . $key, '<div class="msg-inline-error">', '</div>' );
									?><div class="vui-field-wrapper-inline">
										
										<?= form_label( lang( $current_field ) ); ?>
										
										<?= vui_el_textarea(
												
											array(
												
												'text' => lang( $current_field ),
												'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : '',
												'id' => 'field-' . $current_field . '-' . $key,
												'name' => 'fields[' . $key . '][' . $current_field . ']',
												'class' => 'sf-field-' . $current_field,
												'title' => lang( 'tip_field_' . $current_field )
												
											)
											
										); ?>
										
									</div><?php
									
									$current_field = 'options_from_users_submits';
									echo form_error( $current_field . '_' . $key, '<div class="msg-inline-error">', '</div>' );
									?><div class="vui-field-wrapper-inline">
										
										<?= form_label( lang( $current_field ) ); ?>
										<?php
											
											if ( ! isset( $options_ds ) ) {
												
												$options_ds = array(
													
													'' => lang( 'combobox_select' ),
													
												);
												
												foreach( $data_schemes as $ds ) {
													
													$options_ds[ $ds[ 'id' ] ] = $ds[ 'title' ];
													
												}
												
											}
											
										?>
										
										<?= vui_el_dropdown(
											
											array(
												
												'name' => 'fields[' . $key . '][' . $current_field . ']',
												'options' => $options_ds,
												'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : 0,
												'class' => 'sf-field-' . $current_field,
												'id' => 'field-' . $current_field . '-' . $key,
												'title' => lang( 'tip_field_' . $current_field )
												
											)
											
										); ?>
										
									</div><?php
									
									$current_field = 'options_title_field';
									echo form_error( $current_field . '_' . $key, '<div class="msg-inline-error">', '</div>' );
									?><div class="vui-field-wrapper-inline">
										
										<?= form_label( lang( $current_field ) ); ?>
										<?php
											
											$options = array(
												
												'id' => lang( 'id' ),
												'submit_datetime' => lang( 'submit_datetime' ),
												'mod_datetime' => lang( 'mod_datetime' ),
												
											);
											
											if ( isset( $field[ 'options_from_users_submits' ] ) ) {
												
												if ( ! isset( $users_submits_options_cache[ $field[ 'options_from_users_submits' ] ] ) ) {
													
													foreach( $data_schemes as $ds ) {
														
														if ( check_var( $field[ 'options_from_users_submits' ] ) AND $field[ 'options_from_users_submits' ] == $ds[ 'id' ] ) {
															
															foreach( $ds[ 'fields' ] as $_field ) {
																
																if ( ! in_array( $_field[ 'field_type' ], array( 'html', 'button', ) ) ) {
																	
																	$field_alias = isset( $_field[ 'alias' ] ) ? $_field[ 'alias' ] : FALSE;
																	
																	if ( $field_alias ) {
																		
																		$options[ $field_alias ] = $_field[ 'label' ];
																		
																	}
																	
																}
																
															}
															
															$users_submits_options_cache[ $ds[ 'id' ] ] = $options;
															
															break;
															
														}
														
													}
													
												} else {
													
													$options = $users_submits_options_cache[ $field[ 'options_from_users_submits' ] ];
													
												}
												
											}
											
											echo vui_el_dropdown(
												
												array(
													
													'name' => 'fields[' . $key . '][' . $current_field . ']',
													'options' => $options,
													'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : 'id',
													'class' => 'sf-field-' . $current_field,
													'id' => 'field-' . $current_field . '-' . $key,
													'title' => lang( 'tip_field_' . $current_field )
													
												)
												
											);
											
									?></div><?php
									
									$current_field = 'options_filter_order_by';
									echo form_error( $current_field . '_' . $key, '<div class="msg-inline-error">', '</div>' );
									?><div class="vui-field-wrapper-inline">
										
										<?php
										
										echo form_label( lang( $current_field ) );
										
										echo vui_el_dropdown(
												
											array(
												
												'name' => 'fields[' . $key . '][' . $current_field . ']',
												'options' => $options,
												'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : '',
												'class' => 'sf-field-' . $current_field,
												'id' => 'field-' . $current_field . '-' . $key,
												'title' => lang( 'tip_field_' . $current_field )
												
											)
											
										);
										
										unset( $options );
										
										?>
										
									</div><?php
									
									$current_field = 'options_filter_order_by_direction';
									echo form_error( $current_field . '_' . $key, '<div class="msg-inline-error">', '</div>' );
									?><div class="vui-field-wrapper-inline">
										
										<?= form_label( lang( $current_field ) ); ?>
										<?php
											
											$options = array(
												
												'ASC' => lang( 'ascendant' ),
												'DESC' => lang( 'decrescent' ),
												
											);
											
										?>
										
										<?= vui_el_dropdown(
												
											array(
												
												'name' => 'fields[' . $key . '][' . $current_field . ']',
												'options' => $options,
												'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : 'ASC',
												'class' => 'sf-field-' . $current_field,
												'id' => 'field-' . $current_field . '-' . $key,
												'title' => lang( 'tip_field_' . $current_field )
												
											)
											
										); ?>
										
									</div><?php
									
									$current_field = 'options_filter';
									echo form_error( $current_field . '_' . $key, '<div class="msg-inline-error">', '</div>' );
									?><div class="vui-field-wrapper-inline">
										
										<?= form_label( lang( $current_field ) ); ?>
										
										<?= vui_el_textarea(
												
											array(
												
												'text' => lang( $current_field ),
												'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : '',
												'id' => 'field-' . $current_field . '-' . $key,
												'name' => 'fields[' . $key . '][' . $current_field . ']',
												'class' => 'sf-field-' . $current_field,
												'title' => lang( 'tip_field_' . $current_field )
												
											)
											
										); ?>
										
									</div><?php
									
									$current_field = 'options_filter_admin';
									?><div class="vui-field-wrapper-inline"><?php
										
										echo form_label( lang( $current_field ) );
										
										echo vui_el_textarea(
												
											array(
												
												'text' => lang( $current_field ),
												'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : '',
												'id' => 'field-' . $current_field . '-' . $key,
												'name' => 'fields[' . $key . '][' . $current_field . ']',
												'class' => 'sf-field-' . $current_field,
												'title' => lang( 'tip_field_' . $current_field )
												
											)
											
										);
										
									?></div><?php
									
									$current_field = 'options_filter_site';
									?><div class="vui-field-wrapper-inline"><?php
										
										echo form_label( lang( $current_field ) );
										
										echo vui_el_textarea(
												
											array(
												
												'text' => lang( $current_field ),
												'value' => isset( $field[ $current_field ] ) ? $field[ $current_field ] : '',
												'id' => 'field-' . $current_field . '-' . $key,
												'name' => 'fields[' . $key . '][' . $current_field . ']',
												'class' => 'sf-field-' . $current_field,
												'title' => lang( 'tip_field_' . $current_field )
												
											)
											
										);
										
									?></div><?php
									
									} ?>
									
									<div class="vui-field-wrapper-inline submit-remove-field-wrapper">
										
										<?= form_label( '&nbsp;' ); ?>
										
										<?= vui_el_button( array( 'id' => 'submit-remove-field-' . $key, 'button_type' => 'button', 'name' => 'submit_remove_field[' . $key . ']', 'class' => 'btn btn-delete submit-remove-field', 'title' => lang( 'tip_remove_field' ), 'text' => lang( 'remove_field' ), 'icon' => 'remove',  ) ); ?>
										
									</div><?php
									
								?></div><?php
								
							?></div><?php
							
						}
						
						if ( isset( $users_submits_options_cache ) ) {
							
							unset( $users_submits_options_cache );
							
						}
						
						?>
						
						</div>
						
						<hr/>
						
						<?php } ?>
						
						<h5><?= lang('add_field'); ?></h5>
						
						<div class="vui-field-wrapper-auto">
							
							<?= form_label( lang( 'field_type' ) ); ?>
							
							<?= vui_el_dropdown(
								
								array(
									
									'name' => 'field_type_to_add',
									'options' => $field_type_options,
									'value' => isset( $submit_form[ 'field_type_to_add' ] ) ? $submit_form[ 'field_type_to_add' ] : $field_type_default,
									'class' => 'sf-field-field-type-to-add',
									'id' => 'field-type-to-add',
									'title' => lang( 'tip_field_field_type_to_add' ),
									
								)
								
							); ?>
							
						</div>
						
						<div class="vui-field-wrapper-auto">
							
							<?= form_label( lang( 'enter_amount_fields' ) ); ?>
							
							<?= vui_el_input_number(
									
								array(
									
									'text' => lang( 'enter_amount_fields' ),
									'value' => 1,
									'min' => 1,
									'id' => 'field-fields-to-add',
									'name' => 'field_fields_to_add',
									'class' => 'add-num-field',
									'title' => lang( 'tip_enter_amount_fields' ),
									
								)
								
							); ?>
							
						</div>
						
						<div class="vui-field-wrapper-auto">
							
							<?= form_label( '&nbsp;' ); ?>
							
							<?= vui_el_button(
								
								array(
									
									'text' => lang( 'add' ),
									'icon' => 'add',
									'button_type' => 'button',
									'name' => 'submit_add_field',
									'id' => 'submit-add-field',
									'only_icon' => TRUE,
									
								)
								
							); ?>
							
						</div>
						
					</fieldset>
					
				</div><?php
				
				
				
				
				/* ---------------------------------------------------------------------------
				 * ---------------------------------------------------------------------------
				 * Fields
				 * ---------------------------------------------------------------------------
				 */
				
				
				// -------------------------------------------------
				// Default data filters
				
				
				?><div class="form-item">
				
					<fieldset id="ud-data-filters-params" class="for-tab">
						
						<legend>
							
							<?= vui_el_button( array( 'text' => lang( 'ud_data_filters' ), 'icon' => 'filter',  ) ); ?>
							
						</legend>
						
						<?php
						
						$current_field = 'ud_data_filters';
						?><div class="vui-field-wrapper"><?php
							
							echo form_label( lang( $current_field ) );
							
							echo vui_el_textarea(
								
								array(
									
									'text' => lang( $current_field ),
									'title' => lang( 'tip_' . $current_field ),
									'name' => $current_field,
									'value' => isset( $submit_form[ $current_field ] ) ? $submit_form[ $current_field ] : '',
									'class' => $current_field,
									'id' => $current_field,
									
								)
								
							);
							
						?></div>
						
					</fieldset>
					
					<script type="text/javascript" >
						
						$( document ).on( 'change click blur keydown', '#ud_data_filters', function( event ) {
							
							var json = $( this ).val();
							
							//the json is ok
							if ( json != '' && (/^[\],:{}\s]*$/.test( json.replace(/\\["\\\/bfnrtu]/g, '@').
							replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').
							replace(/(?:^|:|,)(?:\s*\[)+/g, '')))) {
								
								var json_ob = $.parseJSON( json );
								
								console.log( json_ob );
								
								$.each( json_ob, function( index, value ) {
									
									$.each( value, function( index, value ) {
										
										console.log( index + ': ' + value );
										
									});
									
								});
								
							}
							//the json is not ok
							else {
								
								console.log( 'erro' );
								
							}
							
							
							
						});
						
					</script>
				</div><?php
				
				
				// Default data filters
				// -------------------------------------------------
				
				
				
				// -------------------------------------------------
				// Params
				
				?><div class="form-item">
					
					<fieldset id="submit-form-params">
						
						<?php $current_field = 'type'; ?>
						
						<?php
						
						/*
						 * gerando o html dos parâmetros, ele deve ser chamado na view, não no controller,
						 * pois os erros de validação dos elementos dos parâmetros devem ser expostos
						 * após a chamada da função $this->form_validation->run()
						 */
						
						echo params_to_html( $params_spec, $final_params_values );
						
						?>
						
					</fieldset>
					
				</div><?php
				
				// Params
				// -------------------------------------------------
				
			?></div>
			
			<?= form_hidden( 'submit_form_id', @$submit_form[ 'id' ] ); ?>
			
		<?= form_close(); ?>
		
	</div>
	
</div>

<script type="text/javascript" >
	
	// show / hide mce editors
	function checkFieldsEditors( w ){
		
		if ( w != undefined ) {
			
			if ( w.find( '.sortable-expander' ).hasClass( 'sortable-expanded' ) ) {
				
				if ( w.find( '.sf_html_field_use_editor' ).val() == 1 ) {
					
					w.find( '.sf-field-html' ).each( function() {
						
						<?php if ( $this->mcm->filtered_system_params[ 'js_text_editor' ] == 'tinymce' ) { ?>
							
							tinymce.execCommand( 'mceAddEditor', true, $( this ).attr( 'id' ) );
							
						<?php } ?>
						
					});
					
				}
				else {
					
					w.find( '.sf-field-html' ).each( function() {
						
						<?php if ( $this->mcm->filtered_system_params[ 'js_text_editor' ] == 'tinymce' ) { ?>
							
							tinymce.execCommand( 'mceRemoveEditor', false, $( this ).attr( 'id' ) );
							
						<?php } ?>
						
					});
					
				}
				
				if ( w.find( '.sf-field-default_value_use_editor' ).is(':checked') ) {
					
					w.find( '.sf-field-default_value' ).each( function() {
						
						<?php if ( $this->mcm->filtered_system_params[ 'js_text_editor' ] == 'tinymce' ) { ?>
							
							tinymce.execCommand( 'mceAddEditor', true, $( this ).attr( 'id' ) );
							
						<?php } ?>
						
					});
					
				}
				else {
					
					w.find( '.sf-field-default_value' ).each( function() {
						
						<?php if ( $this->mcm->filtered_system_params[ 'js_text_editor' ] == 'tinymce' ) { ?>
							
							tinymce.execCommand( 'mceRemoveEditor', false, $( this ).attr( 'id' ) );
							
						<?php } ?>
						
					});
					
				}
				
			}
			else {
				
				w.find( '.sf-field-html, .sf-field-default_value' ).each( function() {
					
					<?php if ( $this->mcm->filtered_system_params[ 'js_text_editor' ] == 'tinymce' ) { ?>
						
						tinymce.execCommand( 'mceRemoveEditor', false, $( this ).attr( 'id' ) );
						
					<?php } ?>
					
				});
				
			}
			
		}
		else {
			
			$( '.sortable .sortable-item' ).each( function( index ) {
				
				jThis = $( this );
				
				if ( jThis.find( '.sortable-expander' ).hasClass( 'sortable-expanded' ) ) {
					
					if ( jThis.find( '.sf_html_field_use_editor' ).val() == 1 ) {
						
						jThis.find( '.sf-field-html' ).each( function() {
							
							<?php if ( $this->mcm->filtered_system_params[ 'js_text_editor' ] == 'tinymce' ) { ?>
								
								tinymce.execCommand( 'mceAddEditor', true, $( this ).attr( 'id' ) );
								
							<?php } ?>
							
						});
						
					}
					else {
						
						//// console.log( 'removendo editor 1' )
						jThis.find( '.sf-field-html' ).each( function() {
							
							<?php if ( $this->mcm->filtered_system_params[ 'js_text_editor' ] == 'tinymce' ) { ?>
								
								tinymce.execCommand( 'mceRemoveEditor', false, $( this ).attr( 'id' ) );
								
							<?php } ?>
							
						});
						
					}
					
					if ( jThis.find( '.sf-field-default_value_use_editor' ).is(':checked') ) {
						
						jThis.find( '.sf-field-default_value' ).each( function() {
							
							<?php if ( $this->mcm->filtered_system_params[ 'js_text_editor' ] == 'tinymce' ) { ?>
								
								tinymce.execCommand( 'mceAddEditor', true, $( this ).attr( 'id' ) );
								
							<?php } ?>
							
							//// console.log( 'adicionando editor' )
						});
						
					}
					else {
						
						//// console.log( 'removendo editor 1' )
						jThis.find( '.sf-field-default_value' ).each( function() {
							
							<?php if ( $this->mcm->filtered_system_params[ 'js_text_editor' ] == 'tinymce' ) { ?>
								
								tinymce.execCommand( 'mceRemoveEditor', false, $( this ).attr( 'id' ) );
								
							<?php } ?>
							
						});
						
					}
					
				}
				else {
					
					jThis.find( '.sf-field-default_value' ).each( function() {
						
						<?php if ( $this->mcm->filtered_system_params[ 'js_text_editor' ] == 'tinymce' ) { ?>
							
							tinymce.execCommand( 'mceRemoveEditor', false, $( this ).attr( 'id' ) );
							
						<?php } ?>
						
						//// console.log( 'removendo editor 2' )
					});
					
				}
				
			});
			
		}
		
	}
	
	function updateKeys( start_pos ){
		
		$( '#fields-wrapper' ).addClass( 'loading' );
		
		updateFields( start_pos );
		
		$( '#fields-wrapper' ).removeClass( 'loading' );
		
	}
	
	function _get_summary( $field_wrapper ){
		
		return $field_wrapper.find( '.summary' );
		
	}
	
	function _get_content( $field_wrapper ){
		
		return $field_wrapper.find( '.content' );
		
	}
	
	function _get_key( $field_wrapper ){
		
		return $field_wrapper.index() + 1;
		
	}
	
	function _get_field_type( $field_wrapper ){
		
		return $field_wrapper.find( '.content .sf-field-type option:selected' ).text();
		
	}
	
	function _get_field_type_value( $field_wrapper ){
		
		return $field_wrapper.find( '.content .sf-field-type option:selected' ).val();
		
	}
	
	function _get_availability_admin( $field_wrapper ){
		
		return $field_wrapper.find( '.content .sf-field-availability_admin' ).is(':checked') ? true : false;
		
	}
	
	function _get_availability_site( $field_wrapper ){
		
		return $field_wrapper.find( '.content .sf-field-availability_site' ).is(':checked') ? true : false;
		
	}
	
	function _get_visibility_site_list( $field_wrapper ){
		
		return $field_wrapper.find( '.content .sf-field-visibility_site_list' ).is(':checked') ? true : false;
		
	}
	
	function _get_visibility_site_detail( $field_wrapper ){
		
		return $field_wrapper.find( '.content .sf-field-visibility_site_detail' ).is(':checked') ? true : false;
		
	}
	
	function _check_global_availability(){
		
		if ( $( '#fields-wrapper .sf-field-availability_site:checked' ).length < $( '#fields-wrapper .sf-field-availability_site' ).length ) {
			
			$( '#submit-check-all-fields-available-on-site' )[ 0 ].checked = false;
			
		}
		else {
			
			$( '#submit-check-all-fields-available-on-site' )[ 0 ].checked = true;
			
		}
		
		if ( $( '#fields-wrapper .sf-field-availability_admin:checked' ).length < $( '#fields-wrapper .sf-field-availability_admin' ).length ) {
			
			$( '#submit-check-all-fields-available-on-admin' )[ 0 ].checked = false;
			
		}
		else {
			
			$( '#submit-check-all-fields-available-on-admin' )[ 0 ].checked = true;
			
		}
		
	}
	
	function _check_visibility_site_list(){
		
		if ( $( '#fields-wrapper .sf-field-visibility_site_list:checked' ).length < $( '#fields-wrapper .sf-field-visibility_site_list' ).length ) {
			
			$( '#submit-check-all-fields-visible-on-site-list' )[ 0 ].checked = false;
			
		}
		else {
			
			$( '#submit-check-all-fields-visible-on-site-list' )[ 0 ].checked = true;
			
		}
		
	}
	
	function _check_visibility_site_detail(){
		
		if ( $( '#fields-wrapper .sf-field-visibility_site_detail:checked' ).length < $( '#fields-wrapper .sf-field-visibility_site_detail' ).length ) {
			
			$( '#submit-check-all-fields-visible-on-site-detail' )[ 0 ].checked = false;
			
		}
		else {
			
			$( '#submit-check-all-fields-visible-on-site-detail' )[ 0 ].checked = true;
			
		}
		
	}
	
	function update_field_expanded( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-expanded' ).attr( 'name', 'fields[' + fieldKey + '][field_expanded]' );
		$content.find( '.sf-field-expanded' ).attr( 'id', 'field-expanded-' + fieldKey );
		
	}
	
	function update_field_key( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-key' ).attr( 'name', 'fields[' + fieldKey + '][key]' );
		$content.find( '.sf-field-key' ).attr( 'id', 'field-key-' + fieldKey );
		$content.find( '.sf-field-key' ).attr( 'value', fieldKey );
		$content.find( '.sf-field-key' ).val( fieldKey );
		
		$summary.find( '.field-key' ).html( fieldKey );
		
	}
	
	function update_field_type( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		var fieldType = _get_field_type( $field_wrapper );
		var fieldTypeValue = _get_field_type_value( $field_wrapper );
		
		$content.find( '.sf-field-type' ).attr( 'name', 'fields[' + fieldKey + '][field_type]' );
		$content.find( '.sf-field-type' ).attr( 'id', 'field-type-' + fieldKey );
		$summary.find( '.field-type' ).html( fieldType );
		$field_wrapper.addClass( 'field-type-' + fieldTypeValue );
		
	}
	
	function update_alias( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-alias' ).attr( 'name', 'fields[' + fieldKey + '][alias]' );
		$content.find( '.sf-field-alias' ).attr( 'id', 'field-alias-' + fieldKey );
		
	}
	
	function update_label( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		var fieldLabel = $content.find( '.sf-field-label' ).val();
		
		$content.find( '.sf-field-label' ).attr( 'name', 'fields[' + fieldKey + '][label]' );
		$content.find( '.sf-field-label' ).attr( 'id', 'field-label-' + fieldKey );
		$summary.find( '.field-label' ).html( fieldLabel );
		
	}
	
	function update_presentation_label( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		var fieldPresLabel = $content.find( '.sf-field-presentation_label' ).val();
		
		$content.find( '.sf-field-presentation_label' ).attr( 'name', 'fields[' + fieldKey + '][presentation_label]' );
		$content.find( '.sf-field-presentation_label' ).attr( 'id', 'field-presentation_label-' + fieldKey );
		$summary.find( '.field-presentation-label' ).html( fieldPresLabel );
		
	}
	
	function update_default_value( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-default_value' ).attr( 'name', 'fields[' + fieldKey + '][default_value]' );
		$content.find( '.sf-field-default_value' ).attr( 'id', 'field-default_value-' + fieldKey );
		
	}
	
	function update_textarea_fields( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-default_value_use_editor' ).attr( 'name', 'fields[' + fieldKey + '][default_value_use_editor]' );
		$content.find( '.sf-field-default_value_use_editor' ).attr( 'id', 'field-default_value_use_editor-' + fieldKey );
		
		$content.find( '.sf-field-sf_field_type_textarea_allow_html' ).attr( 'name', 'fields[' + fieldKey + '][sf_field_type_textarea_allow_html]' );
		$content.find( '.sf-field-sf_field_type_textarea_allow_html' ).attr( 'id', 'field-sf_field_type_textarea_allow_html-' + fieldKey );
		
		$content.find( '.sf-field-sf_field_type_textarea_us_use_editor_admin' ).attr( 'name', 'fields[' + fieldKey + '][sf_field_type_textarea_us_use_editor][admin]' );
		$content.find( '.sf-field-sf_field_type_textarea_us_use_editor_admin' ).attr( 'id', 'field-sf_field_type_textarea_us_use_editor_admin-' + fieldKey );
		
		$content.find( '.sf-field-sf_field_type_textarea_us_use_editor_site' ).attr( 'name', 'fields[' + fieldKey + '][sf_field_type_textarea_us_use_editor][site]' );
		$content.find( '.sf-field-sf_field_type_textarea_us_use_editor_site' ).attr( 'id', 'field-sf_field_type_textarea_us_use_editor_site-' + fieldKey );
		
		if ( ! $content.find( '.sf-field-sf_field_type_textarea_allow_html' ).is( ':checked' ) ) {
			
			$content.find( '.sf-field-sf_field_type_textarea_us_use_editor_admin, .sf-field-sf_field_type_textarea_us_use_editor_site, .sf-field-default_value_use_editor' )
				.closest( '.vui-interactive-el-wrapper' )
				.addClass( 'hidden' );
				
			$content.find( '.sf-field-sf_field_type_textarea_us_use_editor_admin, .sf-field-sf_field_type_textarea_us_use_editor_site, .sf-field-default_value_use_editor' )
				.prop( 'checked', false )
				.trigger( 'change' );
			
		}
		
		if ( $content.find( '.sf-field-sf_field_type_textarea_allow_html' ).is( ':checked' ) ) {
			
			$content.find( '.sf-field-sf_field_type_textarea_us_use_editor_admin, .sf-field-sf_field_type_textarea_us_use_editor_site, .sf-field-default_value_use_editor' )
				.closest( '.vui-interactive-el-wrapper' )
				.removeClass( 'hidden' );
			
		}
		
	}
	
	$( document ).on( 'click change', '.sf-field-sf_field_type_textarea_allow_html', function( event ){
		
		update_textarea_fields( $( this ).closest( '.field-wrapper' ) );
		
	});
	
	function update_form_css_class( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-form_css_class' ).attr( 'name', 'fields[' + fieldKey + '][form_css_class]' );
		$content.find( '.sf-field-form_css_class' ).attr( 'id', 'field-form_css_class-' + fieldKey );
		
	}
	
	function update_view_css_class( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-view_css_class' ).attr( 'name', 'fields[' + fieldKey + '][view_css_class]' );
		$content.find( '.sf-field-view_css_class' ).attr( 'id', 'field-view_css_class-' + fieldKey );
		
	}
	
	function update_availability_admin( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$summary.find( '.short-actions .action-availability-admin' )[0].checked = $content.find( '.sf-field-availability_admin' ).is( ':checked' ) ? true : false;
		
		$content.find( '.sf-field-availability_admin' ).attr( 'name', 'fields[' + fieldKey + '][availability][admin]' );
		$content.find( '.sf-field-availability_admin' ).attr( 'id', 'field-availability_admin-' + fieldKey );
		
		_check_global_availability();
		
	}
	
	function update_availability_site( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$summary.find( '.short-actions .action-availability-site' )[0].checked = $content.find( '.sf-field-availability_site' ).is( ':checked' ) ? true : false;
		
		$content.find( '.sf-field-availability_site' ).attr( 'name', 'fields[' + fieldKey + '][availability][site]' );
		$content.find( '.sf-field-availability_site' ).attr( 'id', 'field-availability_site-' + fieldKey );
		
		_check_global_availability();
		
	}
	
	function update_visibility_site_list( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-visibility_site_list' ).attr( 'name', 'fields[' + fieldKey + '][visibility][site][list]' );
		$content.find( '.sf-field-visibility_site_list' ).attr( 'id', 'field-visibility_site_list-' + fieldKey );
		
		_check_visibility_site_list();
		
	}
	
	function update_visibility_site_detail( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-visibility_site_detail' ).attr( 'name', 'fields[' + fieldKey + '][visibility][site][detail]' );
		$content.find( '.sf-field-visibility_site_detail' ).attr( 'id', 'field-visibility_site_detail-' + fieldKey );
		
		_check_visibility_site_detail();
		
	}
	
	function update_is_user_field( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.ud-field-is_user_field' ).attr( 'name', 'fields[' + fieldKey + '][is_user_field]' );
		$content.find( '.ud-field-is_user_field' ).attr( 'id', 'field-is_user_field-' + fieldKey );
		
	}
	
	function update_user_field( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.ud-field-user_field' ).attr( 'name', 'fields[' + fieldKey + '][user_field]' );
		$content.find( '.ud-field-user_field' ).attr( 'id', 'field-user_field-' + fieldKey );
		
	}
	
	function update_is_unique( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.ud-field-is_unique' ).attr( 'name', 'fields[' + fieldKey + '][is_unique]' );
		$content.find( '.ud-field-is_unique' ).attr( 'id', 'field-is_unique-' + fieldKey );
		
	}
	
	function update_field_is_required( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		var fieldTypeValue = _get_field_type_value( $field_wrapper );
		
		if ( fieldTypeValue == 'date' ) {
			
			if (
				
				$content.find( '.sf-field-sf_date_field_day_is_req' ).val() == '1'
				&& $content.find( '.sf-field-sf_date_field_month_is_req' ).val() == '1'
				&& $content.find( '.sf-field-sf_date_field_year_is_req' ).val() == '1'
				
			) {
				
				$summary.find( '.short-actions .action-field-is-required' )[0].checked = true;
				
			}
			else {
				
				$summary.find( '.short-actions .action-field-is-required' )[0].checked = false;
				
			}
			
		}
		else if ( fieldTypeValue == 'html' || fieldTypeValue == 'button' ) {
				
				$summary.find( '.short-actions .action-field-is-required' ).closest( '.vui-interactive-el-wrapper' ).remove();
				
		}
		else {
			
			//// console.log( content.find( '.sf-field-field_is_required' ).val() )
			
			$summary.find( '.short-actions .action-field-is-required' )[0].checked = $content.find( '.sf-field-field_is_required' ).is( ':checked' ) ? true : false;
			
		}
		
		$content.find( '.sf-field-field_is_required' ).attr( 'name', 'fields[' + fieldKey + '][field_is_required]' );
		$content.find( '.sf-field-field_is_required' ).attr( 'id', 'field-field_is_required-' + fieldKey );
		
		$content.find( '.sf-field-sf_date_field_day_is_req' ).attr( 'name', 'fields[' + fieldKey + '][sf_date_field_day_is_req]' );
		$content.find( '.sf-field-sf_date_field_day_is_req' ).attr( 'id', 'field-sf_date_field_day_is_req-' + fieldKey );
		
		$content.find( '.sf-field-sf_date_field_month_is_req' ).attr( 'name', 'fields[' + fieldKey + '][sf_date_field_month_is_req]' );
		$content.find( '.sf-field-sf_date_field_month_is_req' ).attr( 'id', 'field-sf_date_field_month_is_req-' + fieldKey );
		
		$content.find( '.sf-field-sf_date_field_year_is_req' ).attr( 'name', 'fields[' + fieldKey + '][sf_date_field_year_is_req]' );
		$content.find( '.sf-field-sf_date_field_year_is_req' ).attr( 'id', 'field-sf_date_field_year_is_req-' + fieldKey );
		
	}
	
	function update_field_options( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-options' ).attr( 'name', 'fields[' + fieldKey + '][options]' );
		$content.find( '.sf-field-options' ).attr( 'id', 'field-options-' + fieldKey );
		
		$content.find( '.sf-field-options_from_users_submits' ).attr( 'name', 'fields[' + fieldKey + '][options_from_users_submits]' );
		$content.find( '.sf-field-options_from_users_submits' ).attr( 'id', 'field-options_from_users_submits-' + fieldKey );
		
		$content.find( '.sf-field-options_title_field' ).attr( 'name', 'fields[' + fieldKey + '][options_title_field]' );
		$content.find( '.sf-field-options_title_field' ).attr( 'id', 'field-options_title_field-' + fieldKey );
		
		$content.find( '.sf-field-options_filter' ).attr( 'name', 'fields[' + fieldKey + '][options_filter]' );
		$content.find( '.sf-field-options_filter' ).attr( 'id', 'field-options_filter-' + fieldKey );
		
		$content.find( '.sf-field-options_filter_admin' ).attr( 'name', 'fields[' + fieldKey + '][options_filter_admin]' );
		$content.find( '.sf-field-options_filter_admin' ).attr( 'id', 'field-options_filter_admin-' + fieldKey );
		
		$content.find( '.sf-field-options_filter_site' ).attr( 'name', 'fields[' + fieldKey + '][options_filter_site]' );
		$content.find( '.sf-field-options_filter_site' ).attr( 'id', 'field-options_filter_site-' + fieldKey );
		
		$content.find( '.sf-field-options_filter_order_by' ).attr( 'name', 'fields[' + fieldKey + '][options_filter_order_by]' );
		$content.find( '.sf-field-options_filter_order_by' ).attr( 'id', 'field-options_filter_order_by-' + fieldKey );
		
		$content.find( '.sf-field-options_filter_order_by_direction' ).attr( 'name', 'fields[' + fieldKey + '][options_filter_order_by_direction]' );
		$content.find( '.sf-field-options_filter_order_by_direction' ).attr( 'id', 'field-options_filter_order_by_direction-' + fieldKey );
		
	}
	
	function update_conditional_field( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-conditional_field' ).attr( 'name', 'fields[' + fieldKey + '][conditional_field]' );
		$content.find( '.sf-field-conditional_field' ).attr( 'id', 'field-conditional_field-' + fieldKey );
		
	}
	
	// --------------
	
	function update_validation_rule( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-validation_rule' ).attr( 'name', 'fields[' + fieldKey + '][validation_rule][]' );
		$content.find( '.sf-field-validation_rule' ).attr( 'id', 'field-validation_rule-' + fieldKey );
		
	}
	
	function update_validation_rule( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-validation_rule' ).attr( 'name', 'fields[' + fieldKey + '][validation_rule][]' );
		$content.find( '.sf-field-validation_rule' ).attr( 'id', 'field-validation_rule-' + fieldKey );
		
	}
	
	function update_validation_rule_parameter_matches( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-validation_rule_parameter_matches' ).attr( 'name', 'fields[' + fieldKey + '][validation_rule_parameter_matches]' );
		$content.find( '.sf-field-validation_rule_parameter_matches' ).attr( 'id', 'field-validation_rule_parameter_matches-' + fieldKey );
		
	}
	
	function update_validation_rule_parameter_normalizar_nome_ptbr( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-validation_rule_parameter_normalizar_nome_ptbr' ).attr( 'name', 'fields[' + fieldKey + '][validation_rule_parameter_normalizar_nome_ptbr]' );
		$content.find( '.sf-field-validation_rule_parameter_normalizar_nome_ptbr' ).attr( 'id', 'field-validation_rule_parameter_normalizar_nome_ptbr-' + fieldKey );
		
	}
	
	function update_validation_rule_parameter_uppercase( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-validation_rule_parameter_uppercase' ).attr( 'name', 'fields[' + fieldKey + '][validation_rule_parameter_uppercase]' );
		$content.find( '.sf-field-validation_rule_parameter_uppercase' ).attr( 'id', 'field-validation_rule_parameter_uppercase-' + fieldKey );
		
	}
	
	function update_validation_rule_parameter_lowercase( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-validation_rule_parameter_lowercase' )
			.attr( 'name', 'fields[' + fieldKey + '][validation_rule_parameter_lowercase]' )
			.attr( 'id', 'field-validation_rule_parameter_lowercase-' + fieldKey );
		
	}
	
	function update_validation_rule_parameter_mask( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.ud-field-ud_validation_rule_parameter_mask_type' )
			.attr( 'name', 'fields[' + fieldKey + '][ud_validation_rule_parameter_mask_type]' )
			.attr( 'id', 'ud_validation_rule_parameter_mask_type-' + fieldKey );
		
		$content.find( '.ud-field-ud_validation_rule_parameter_mask_custom_mask' )
			.attr( 'name', 'fields[' + fieldKey + '][ud_validation_rule_parameter_mask_custom_mask]' )
			.attr( 'id', 'ud_validation_rule_parameter_mask_custom_mask-' + fieldKey );
		
		$content.find( '.ud-field-ud_validation_rule_parameter_mask_test' )
			.attr( 'name', 'fields[' + fieldKey + '][ud_validation_rule_parameter_mask_test]' )
			.attr( 'id', 'ud_validation_rule_parameter_mask_test-' + fieldKey );
		
		check_validation_rule_mask_type( $field_wrapper );
		
	}
	
	$( '.ud_validation_rule_parameter_mask_elements_wrapper' ).on( 'change', '.ud-field-ud_validation_rule_parameter_mask_type', function(){
		
		check_validation_rule_mask_type( $( this ).closest( '.field-wrapper' ) );
		
	});
	
	function check_validation_rule_mask_type( $field_wrapper ){
		/*
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		var $mask_type_el = $field_wrapper.find( '.ud-field-ud_validation_rule_parameter_mask_type' );
		var $mask_test_el = $field_wrapper.find( '.ud-field-ud_validation_rule_parameter_mask_test' );
		
		console.log( $mask_type_el.val() );
		
		switch( $mask_type_el.val() ) {
			
			case 'custom_mask':
				
				$mask_test_el.attr( 'data-input-mask-el-id', 'ud_validation_rule_parameter_mask_custom_mask-' + fieldKey );
				break;
				
			case 'zip_brazil':
				
				$zip_brazil = true;
				$mask = '99999-999';
				break;
				
			default:
				
				
				
				;
				
		}
		*/
	}
	
	function update_validation_rule_parameter_min_length( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-validation_rule_parameter_min_length' )
			.attr( 'name', 'fields[' + fieldKey + '][validation_rule_parameter_min_length]' )
			.attr( 'id', 'field-validation_rule_parameter_min_length-' + fieldKey );
		
	}
	
	function update_validation_rule_parameter_max_length( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-validation_rule_parameter_max_length' ).attr( 'name', 'fields[' + fieldKey + '][validation_rule_parameter_max_length]' );
		$content.find( '.sf-field-validation_rule_parameter_max_length' ).attr( 'id', 'field-validation_rule_parameter_max_length-' + fieldKey );
		
	}
	
	function update_validation_rule_parameter_exact_length( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-validation_rule_parameter_exact_length' ).attr( 'name', 'fields[' + fieldKey + '][validation_rule_parameter_exact_length]' );
		$content.find( '.sf-field-validation_rule_parameter_exact_length' ).attr( 'id', 'field-validation_rule_parameter_exact_length-' + fieldKey );
		
	}
	
	function update_validation_rule_parameter_greater_than( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-validation_rule_parameter_greater_than' ).attr( 'name', 'fields[' + fieldKey + '][validation_rule_parameter_greater_than]' );
		$content.find( '.sf-field-validation_rule_parameter_greater_than' ).attr( 'id', 'field-validation_rule_parameter_greater_than-' + fieldKey );
		
	}
	
	function update_validation_rule_parameter_less_than( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-validation_rule_parameter_less_than' ).attr( 'name', 'fields[' + fieldKey + '][validation_rule_parameter_less_than]' );
		$content.find( '.sf-field-validation_rule_parameter_less_than' ).attr( 'id', 'field-validation_rule_parameter_less_than-' + fieldKey );
		
	}
	
	// --------------
	
	function update_advanced_options_prop_is_ud_image( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-advanced_options_prop_is_ud_image' ).attr( 'name', 'fields[' + fieldKey + '][advanced_options][prop_is_ud_image]' );
		$content.find( '.sf-field-advanced_options_prop_is_ud_image' ).attr( 'id', 'field-advanced_options_prop_is_ud_image-' + fieldKey );
		
		$content.find( '.sf-field-advanced_options_prop_is_ud_image_thumb_prevent_cache_admin' ).attr( 'name', 'fields[' + fieldKey + '][advanced_options][prop_is_ud_image_thumb_prevent_cache_admin]' );
		$content.find( '.sf-field-advanced_options_prop_is_ud_image_thumb_prevent_cache_admin' ).attr( 'id', 'field-advanced_options_prop_is_ud_image_thumb_prevent_cache_admin-' + fieldKey );
		
		$content.find( '.sf-field-advanced_options_prop_is_ud_image_thumb_prevent_cache_site' ).attr( 'name', 'fields[' + fieldKey + '][advanced_options][prop_is_ud_image_thumb_prevent_cache_site]' );
		$content.find( '.sf-field-advanced_options_prop_is_ud_image_thumb_prevent_cache_site' ).attr( 'id', 'field-advanced_options_prop_is_ud_image_thumb_prevent_cache_site-' + fieldKey );
		
	}
	
	function update_advanced_options_prop_is_ud_file( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-advanced_options_prop_is_ud_file' ).attr( 'name', 'fields[' + fieldKey + '][advanced_options][prop_is_ud_file]' );
		$content.find( '.sf-field-advanced_options_prop_is_ud_file' ).attr( 'id', 'field-advanced_options_prop_is_ud_file-' + fieldKey );
		
		$content.find( '.sf-field-advanced_options_prop_is_ud_file_upload_dir' ).attr( 'name', 'fields[' + fieldKey + '][advanced_options][prop_is_ud_file_upload_dir]' );
		$content.find( '.sf-field-advanced_options_prop_is_ud_file_upload_dir' ).attr( 'id', 'field-advanced_options_prop_is_ud_file_upload_dir-' + fieldKey );
		
		$content.find( '.sf-field-advanced_options_prop_is_ud_file_overwrite' ).attr( 'name', 'fields[' + fieldKey + '][advanced_options][prop_is_ud_file_overwrite]' );
		$content.find( '.sf-field-advanced_options_prop_is_ud_file_overwrite' ).attr( 'id', 'field-advanced_options_prop_is_ud_file_overwrite-' + fieldKey );
		
		$content.find( '.sf-field-advanced_options_prop_is_ud_file_email_send_attachment' ).attr( 'name', 'fields[' + fieldKey + '][advanced_options][prop_is_ud_file_email_send_attachment]' );
		$content.find( '.sf-field-advanced_options_prop_is_ud_file_email_send_attachment' ).attr( 'id', 'field-advanced_options_prop_is_ud_file_email_send_attachment-' + fieldKey );
		
		$content.find( '.sf-field-advanced_options_prop_is_ud_file_max_size' ).attr( 'name', 'fields[' + fieldKey + '][advanced_options][prop_is_ud_file_max_size]' );
		$content.find( '.sf-field-advanced_options_prop_is_ud_file_max_size' ).attr( 'id', 'field-advanced_options_prop_is_ud_file_max_size-' + fieldKey );
		
		$content.find( '.sf-field-advanced_options_prop_is_ud_file_allowed_types' ).attr( 'name', 'fields[' + fieldKey + '][advanced_options][prop_is_ud_file_allowed_types]' );
		$content.find( '.sf-field-advanced_options_prop_is_ud_file_allowed_types' ).attr( 'id', 'field-advanced_options_prop_is_ud_file_allowed_types-' + fieldKey );
		
	}
	
	function update_advanced_options_prop_is_ud_title( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-advanced_options_prop_is_ud_title' ).attr( 'name', 'fields[' + fieldKey + '][advanced_options][prop_is_ud_title]' );
		$content.find( '.sf-field-advanced_options_prop_is_ud_title' ).attr( 'id', 'field-advanced_options_prop_is_ud_title-' + fieldKey );
		
	}
	
	function update_advanced_options_prop_is_ud_content( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-advanced_options_prop_is_ud_content' ).attr( 'name', 'fields[' + fieldKey + '][advanced_options][prop_is_ud_content]' );
		$content.find( '.sf-field-advanced_options_prop_is_ud_content' ).attr( 'id', 'field-advanced_options_prop_is_ud_content-' + fieldKey );
		
	}
	
	function update_advanced_options_prop_is_ud_other_info( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-advanced_options_prop_is_ud_other_info' ).attr( 'name', 'fields[' + fieldKey + '][advanced_options][prop_is_ud_other_info]' );
		$content.find( '.sf-field-advanced_options_prop_is_ud_other_info' ).attr( 'id', 'field-advanced_options_prop_is_ud_other_info-' + fieldKey );
		
	}
	
	function update_advanced_options_prop_is_ud_email( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-advanced_options_prop_is_ud_email' ).attr( 'name', 'fields[' + fieldKey + '][advanced_options][prop_is_ud_email]' );
		$content.find( '.sf-field-advanced_options_prop_is_ud_email' ).attr( 'id', 'field-advanced_options_prop_is_ud_email-' + fieldKey );
		
	}
	
	function update_advanced_options_prop_is_ud_url( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-advanced_options_prop_is_ud_url' ).attr( 'name', 'fields[' + fieldKey + '][advanced_options][prop_is_ud_url]' );
		$content.find( '.sf-field-advanced_options_prop_is_ud_url' ).attr( 'id', 'field-advanced_options_prop_is_ud_url-' + fieldKey );
		
	}
	
	function update_advanced_options_prop_is_ud_event_datetime( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-advanced_options_prop_is_ud_event_datetime' ).attr( 'name', 'fields[' + fieldKey + '][advanced_options][prop_is_ud_event_datetime]' );
		$content.find( '.sf-field-advanced_options_prop_is_ud_event_datetime' ).attr( 'id', 'field-advanced_options_prop_is_ud_event_datetime-' + fieldKey );
		
	}
	
	function update_advanced_options_prop_is_ud_status( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-advanced_options_prop_is_ud_status' ).attr( 'name', 'fields[' + fieldKey + '][advanced_options][prop_is_ud_status]' );
		$content.find( '.sf-field-advanced_options_prop_is_ud_status' ).attr( 'id', 'field-advanced_options_prop_is_ud_status-' + fieldKey );
		
		$content.find( '.sf-field-advanced_options_prop_is_ud_status_active' ).attr( 'name', 'fields[' + fieldKey + '][advanced_options][prop_is_ud_status_active]' );
		$content.find( '.sf-field-advanced_options_prop_is_ud_status_active' ).attr( 'id', 'field-advanced_options_prop_is_ud_status_active-' + fieldKey );
		
		$content.find( '.sf-field-advanced_options_prop_is_ud_status_inactive' ).attr( 'name', 'fields[' + fieldKey + '][advanced_options][prop_is_ud_status_inactive]' );
		$content.find( '.sf-field-advanced_options_prop_is_ud_status_inactive' ).attr( 'id', 'field-advanced_options_prop_is_ud_status_inactive-' + fieldKey );
		
		$content.find( '.sf-field-advanced_options_prop_is_ud_status_enabled' ).attr( 'name', 'fields[' + fieldKey + '][advanced_options][prop_is_ud_status_enabled]' );
		$content.find( '.sf-field-advanced_options_prop_is_ud_status_enabled' ).attr( 'id', 'field-advanced_options_prop_is_ud_status_enabled-' + fieldKey );
		
		$content.find( '.sf-field-advanced_options_prop_is_ud_status_disabled' ).attr( 'name', 'fields[' + fieldKey + '][advanced_options][prop_is_ud_status_disabled]' );
		$content.find( '.sf-field-advanced_options_prop_is_ud_status_disabled' ).attr( 'id', 'field-advanced_options_prop_is_ud_status_disabled-' + fieldKey );
		
		$content.find( '.sf-field-advanced_options_prop_is_ud_status_canceled' ).attr( 'name', 'fields[' + fieldKey + '][advanced_options][prop_is_ud_status_canceled]' );
		$content.find( '.sf-field-advanced_options_prop_is_ud_status_canceled' ).attr( 'id', 'field-advanced_options_prop_is_ud_status_canceled-' + fieldKey );
		
		$content.find( '.sf-field-advanced_options_prop_is_ud_status_postponed' ).attr( 'name', 'fields[' + fieldKey + '][advanced_options][prop_is_ud_status_postponed]' );
		$content.find( '.sf-field-advanced_options_prop_is_ud_status_postponed' ).attr( 'id', 'field-advanced_options_prop_is_ud_status_postponed-' + fieldKey );
		
		$content.find( '.sf-field-advanced_options_prop_is_ud_status_archived' ).attr( 'name', 'fields[' + fieldKey + '][advanced_options][prop_is_ud_status_archived]' );
		$content.find( '.sf-field-advanced_options_prop_is_ud_status_archived' ).attr( 'id', 'field-advanced_options_prop_is_ud_status_archived-' + fieldKey );
		
		$content.find( '.sf-field-advanced_options_prop_is_ud_status_published' ).attr( 'name', 'fields[' + fieldKey + '][advanced_options][prop_is_ud_status_published]' );
		$content.find( '.sf-field-advanced_options_prop_is_ud_status_published' ).attr( 'id', 'field-advanced_options_prop_is_ud_status_published-' + fieldKey );
		
		$content.find( '.sf-field-advanced_options_prop_is_ud_status_unpublished' ).attr( 'name', 'fields[' + fieldKey + '][advanced_options][prop_is_ud_status_unpublished]' );
		$content.find( '.sf-field-advanced_options_prop_is_ud_status_unpublished' ).attr( 'id', 'field-advanced_options_prop_is_ud_status_unpublished-' + fieldKey );
		
		$content.find( '.sf-field-advanced_options_prop_is_ud_status_scheduled' ).attr( 'name', 'fields[' + fieldKey + '][advanced_options][prop_is_ud_status_scheduled]' );
		$content.find( '.sf-field-advanced_options_prop_is_ud_status_scheduled' ).attr( 'id', 'field-advanced_options_prop_is_ud_status_scheduled-' + fieldKey );
		
	}
	
	// --------------
	
	function update_conditional_field_function( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-conditional_field_function' ).attr( 'name', 'fields[' + fieldKey + '][conditional_field_function]' );
		$content.find( '.sf-field-conditional_field_function' ).attr( 'id', 'field-conditional_field_function-' + fieldKey );
		
	}
	
	function update_conditional_target_field( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-conditional_target_field' ).attr( 'name', 'fields[' + fieldKey + '][conditional_target_field]' );
		$content.find( '.sf-field-conditional_target_field' ).attr( 'id', 'field-conditional_target_field-' + fieldKey );
		
	}
	
	function update_conditional_field_cond( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-conditional_field_cond' ).attr( 'name', 'fields[' + fieldKey + '][conditional_field_cond]' );
		$content.find( '.sf-field-conditional_field_cond' ).attr( 'id', 'field-conditional_field_cond-' + fieldKey );
		
	}
	
	function update_conditional_field_values( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-conditional_field_values' ).attr( 'name', 'fields[' + fieldKey + '][conditional_field_values]' );
		$content.find( '.sf-field-conditional_field_values' ).attr( 'id', 'field-conditional_field_values-' + fieldKey );
		
	}
	
	function update_description( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-description' ).attr( 'name', 'fields[' + fieldKey + '][description]' );
		$content.find( '.sf-field-description' ).attr( 'id', 'field-description-' + fieldKey );
		
	}
	
	function update_html_fields( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf_html_field_use_editor' ).attr( 'name', 'fields[' + fieldKey + '][sf_html_field_use_editor]' );
		$content.find( '.sf_html_field_use_editor' ).attr( 'id', 'field-sf_html_field_use_editor-' + fieldKey );
		
		$content.find( '.sf-field-html' ).attr( 'name', 'fields[' + fieldKey + '][html]' );
		$content.find( '.sf-field-html' ).attr( 'id', 'field-html-' + fieldKey );
		
	}
	
	function update_articles_category_id( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-articles_category_id' ).attr( 'name', 'fields[' + fieldKey + '][articles_category_id]' );
		$content.find( '.sf-field-articles_category_id' ).attr( 'id', 'field-articles_category_id-' + fieldKey );
		
	}
	
	function update_date_fields( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.sf-field-sf_date_field_use_day' ).attr( 'name', 'fields[' + fieldKey + '][sf_date_field_use_day]' );
		$content.find( '.sf-field-sf_date_field_use_day' ).attr( 'id', 'field-sf_date_field_use_day-' + fieldKey );
		
		$content.find( '.sf-field-sf_date_field_day_is_req' ).attr( 'name', 'fields[' + fieldKey + '][sf_date_field_day_is_req]' );
		$content.find( '.sf-field-sf_date_field_day_is_req' ).attr( 'id', 'field-sf_date_field_day_is_req-' + fieldKey );
		
		$content.find( '.sf-field-date-day-min' ).attr( 'name', 'fields[' + fieldKey + '][sf_date_field_day_min_value]' );
		$content.find( '.sf-field-date-day-min' ).attr( 'id', 'field-sf_date_field_day_min_value-' + fieldKey );
		
		$content.find( '.sf-field-date-day-max' ).attr( 'name', 'fields[' + fieldKey + '][sf_date_field_day_max_value]' );
		$content.find( '.sf-field-date-day-max' ).attr( 'id', 'field-sf_date_field_day_max_value-' + fieldKey );
		
		$content.find( '.sf-field-date-day-def' ).attr( 'name', 'fields[' + fieldKey + '][sf_date_field_day_def_value]' );
		$content.find( '.sf-field-date-day-def' ).attr( 'id', 'field-sf_date_field_day_def_value-' + fieldKey );
		
		$content.find( '.sf-field-sf_date_field_use_month' ).attr( 'name', 'fields[' + fieldKey + '][sf_date_field_use_month]' );
		$content.find( '.sf-field-sf_date_field_use_month' ).attr( 'id', 'field-sf_date_field_use_month-' + fieldKey );
		
		$content.find( '.sf-field-sf_date_field_month_is_req' ).attr( 'name', 'fields[' + fieldKey + '][sf_date_field_month_is_req]' );
		$content.find( '.sf-field-sf_date_field_month_is_req' ).attr( 'id', 'field-sf_date_field_month_is_req-' + fieldKey );
		
		$content.find( '.sf-field-date-month-min' ).attr( 'name', 'fields[' + fieldKey + '][sf_date_field_month_min_value]' );
		$content.find( '.sf-field-date-month-min' ).attr( 'id', 'field-sf_date_field_month_min_value-' + fieldKey );
		
		$content.find( '.sf-field-date-month-max' ).attr( 'name', 'fields[' + fieldKey + '][sf_date_field_month_max_value]' );
		$content.find( '.sf-field-date-month-max' ).attr( 'id', 'field-sf_date_field_month_max_value-' + fieldKey );
		
		$content.find( '.sf-field-date-month-def' ).attr( 'name', 'fields[' + fieldKey + '][sf_date_field_month_def_value]' );
		$content.find( '.sf-field-date-month-def' ).attr( 'id', 'field-sf_date_field_month_def_value-' + fieldKey );
		
		$content.find( '.sf-field-sf_date_field_use_year' ).attr( 'name', 'fields[' + fieldKey + '][sf_date_field_use_year]' );
		$content.find( '.sf-field-sf_date_field_use_year' ).attr( 'id', 'field-sf_date_field_use_year-' + fieldKey );
		
		$content.find( '.sf-field-sf_date_field_year_is_req' ).attr( 'name', 'fields[' + fieldKey + '][sf_date_field_year_is_req]' );
		$content.find( '.sf-field-sf_date_field_year_is_req' ).attr( 'id', 'field-sf_date_field_year_is_req-' + fieldKey );
		
		$content.find( '.sf-field-date-year-min' ).attr( 'name', 'fields[' + fieldKey + '][sf_date_field_year_min_value]' );
		$content.find( '.sf-field-date-year-min' ).attr( 'id', 'field-sf_date_field_year_min_value-' + fieldKey );
		
		$content.find( '.sf-field-date-year-max' ).attr( 'name', 'fields[' + fieldKey + '][sf_date_field_year_max_value]' );
		$content.find( '.sf-field-date-year-max' ).attr( 'id', 'field-sf_date_field_year_max_value-' + fieldKey );
		
		$content.find( '.sf-field-date-year-def' ).attr( 'name', 'fields[' + fieldKey + '][sf_date_field_year_def_value]' );
		$content.find( '.sf-field-date-year-def' ).attr( 'id', 'field-sf_date_field_year_def_value-' + fieldKey );
		
		$content.find( '.sf-field-sf_date_field_hide_current_day' ).attr( 'name', 'fields[' + fieldKey + '][sf_date_field_hide_current_day]' );
		$content.find( '.sf-field-sf_date_field_hide_current_day' ).attr( 'id', 'field-sf_date_field_hide_current_day-' + fieldKey );
		
		$content.find( '.sf-field-sf_date_field_hide_current_month' ).attr( 'name', 'fields[' + fieldKey + '][sf_date_field_hide_current_month]' );
		$content.find( '.sf-field-sf_date_field_hide_current_month' ).attr( 'id', 'field-sf_date_field_hide_current_month-' + fieldKey );
		
		$content.find( '.sf-field-sf_date_field_hide_current_year' ).attr( 'name', 'fields[' + fieldKey + '][sf_date_field_hide_current_year]' );
		$content.find( '.sf-field-sf_date_field_hide_current_year' ).attr( 'id', 'field-sf_date_field_hide_current_year-' + fieldKey );
		
		$content.find( '.sf-field-sf_date_field_relative_datetime' ).attr( 'name', 'fields[' + fieldKey + '][sf_date_field_relative_datetime]' );
		$content.find( '.sf-field-sf_date_field_relative_datetime' ).attr( 'id', 'field-sf_date_field_relative_datetime-' + fieldKey );
		
		$content.find( '.sf-field-sf_date_field_presentation_format' ).attr( 'name', 'fields[' + fieldKey + '][sf_date_field_presentation_format]' );
		$content.find( '.sf-field-sf_date_field_presentation_format' ).attr( 'id', 'field-sf_date_field_presentation_format-' + fieldKey );
		
	}
	
	function update_remove_field_button( $field_wrapper ){
		
		var $summary = _get_summary( $field_wrapper );
		var $content = _get_content( $field_wrapper );
		var fieldKey = _get_key( $field_wrapper );
		
		$content.find( '.submit-remove-field' ).attr( 'name', 'submit_remove_field[' + fieldKey + ']' );
		$content.find( '.submit-remove-field' ).attr( 'id', 'submit-remove-field-' + fieldKey );
		
	}
	
	// função de atualização dos atributos e valores dos campos
	// ela deve atualizar os atributos (id, name, etc.) automaticamente
	// baseado em suas respectivas keys (posições, com o index +1)
	function updateFields( start_pos ){
		
		start_pos = start_pos == undefined ? 1 : start_pos;
		
		$( '#fields-wrapper' ).addClass( 'loading' );
		
		$( '#fields-wrapper .field-wrapper' ).each( function( index ) {
			
			if ( index + 1 >= start_pos ) {
				
				var $field_wrapper = $( this );
				var newFieldKey = index + 1;
				var summary = $( this ).find( '.summary' );
				var content = $( this ).find( '.content' );
				var fieldTypeValue = content.find( '.sf-field-type option:selected' ).val();
				var fieldKey = index + 1;
				
				$( this ).removeClass (function (index, css) {
					return (css.match (/(^|\s)field-type-\S+/g) || []).join(' ');
				});
				
				// field expanded
				update_field_expanded( $field_wrapper );
				
				// availability admin
				update_availability_admin( $field_wrapper );
				
				// availability site
				update_availability_site( $field_wrapper );
				
				// visibility site list
				update_visibility_site_list( $field_wrapper );
				
				// visibility site detail
				update_visibility_site_detail( $field_wrapper );
				
				// is user field
				update_is_user_field( $field_wrapper );
				
				// user field
				update_user_field( $field_wrapper );
				
				// is unique
				update_is_unique( $field_wrapper );
				
				// key
				update_field_key( $field_wrapper );
				
				// alias
				update_alias( $field_wrapper );
				
				// label
				update_label( $field_wrapper );
				
				// presentation label
				update_presentation_label( $field_wrapper );
				
				// default value
				update_default_value( $field_wrapper );
				
				// textarea fields
				update_textarea_fields( $field_wrapper );
				
				// form css class
				update_form_css_class( $field_wrapper );
				
				// view css class
				update_view_css_class( $field_wrapper );
				
				// type
				update_field_type( $field_wrapper );
				
				// conditional_field
				update_conditional_field( $field_wrapper );
				
				// options
				update_field_options( $field_wrapper );
				
				// --------------
				
				// field_is_required
				update_field_is_required( $field_wrapper );
				
				// validation_rule
				update_validation_rule( $field_wrapper );
				
				// validation_rule_parameter_matches
				update_validation_rule_parameter_matches( $field_wrapper );
				
				// update_validation_rule_parameter_normalizar_nome_ptbr
				update_validation_rule_parameter_normalizar_nome_ptbr( $field_wrapper );
				
				// update_validation_rule_parameter_uppercase
				update_validation_rule_parameter_uppercase( $field_wrapper );
				
				// update_validation_rule_parameter_lowercase
				update_validation_rule_parameter_lowercase( $field_wrapper );
				
				// update_validation_rule_parameter_mask
				update_validation_rule_parameter_mask( $field_wrapper );
				
				// validation_rule_parameter_min_length
				update_validation_rule_parameter_min_length( $field_wrapper );
				
				// validation_rule_parameter_max_length
				update_validation_rule_parameter_max_length( $field_wrapper );
				
				// validation_rule_parameter_exact_length
				update_validation_rule_parameter_exact_length( $field_wrapper );
				
				// validation_rule_parameter_greater_than
				update_validation_rule_parameter_greater_than( $field_wrapper );
				
				// validation_rule_parameter_less_than
				update_validation_rule_parameter_less_than( $field_wrapper );
				
				// validation_rule_parameter_less_than
				update_validation_rule_parameter_less_than( $field_wrapper );
				
				// --------------
				
				update_advanced_options_prop_is_ud_image( $field_wrapper );
				
				update_advanced_options_prop_is_ud_file( $field_wrapper );
				
				update_advanced_options_prop_is_ud_title( $field_wrapper );
				
				update_advanced_options_prop_is_ud_content( $field_wrapper );
				
				update_advanced_options_prop_is_ud_other_info( $field_wrapper );
				
				update_advanced_options_prop_is_ud_email( $field_wrapper );
				
				update_advanced_options_prop_is_ud_url( $field_wrapper );
				
				update_advanced_options_prop_is_ud_event_datetime( $field_wrapper );
				
				update_advanced_options_prop_is_ud_status( $field_wrapper );
				
				// --------------
				
				// conditional_field_function
				update_conditional_field_function( $field_wrapper );
				
				// conditional_target_field
				update_conditional_target_field( $field_wrapper );
				
				// conditional_field_cond
				update_conditional_field_cond( $field_wrapper );
				
				// conditional_field_values
				update_conditional_field_values( $field_wrapper );
				
				// description
				update_description( $field_wrapper );
				
				// html
				update_html_fields( $field_wrapper );
				
				// articles_category_id
				update_articles_category_id( $field_wrapper );
				
				// date
				update_date_fields( $field_wrapper );
				
				// remove field button
				update_remove_field_button( $field_wrapper );
				
			}
			
		});
		
		$( '#fields-wrapper' ).removeClass( 'loading' );
		
	}
	
	function parseFields() {
		
		// console.log( 'parseFields() called' );
		
		$( '#fields-wrapper .field-wrapper' ).each( function( index ) {
			
			parseField( $( this ) );
			
		});
		
	}
	
	function parseField( $field_wrapper ) {
		
		var handleElTitle = "<?= str_replace( '"', '\"', element_title( lang( 'sf_tip_sortable_handle' ) ) ); ?>";
		var fieldTypeTitle = "<?= str_replace( '"', '\"', element_title( lang( 'sf_tip_sortable_handle_field_type' ) ) ); ?>";
		
		if ( $field_wrapper.children( '.content' ).children( '.summary' ).lenght > 0 ) {
			
			// console.log( 'field already parsed' );
			
			var $summary = $field_wrapper.children( '.content' ).children( '.summary' );
			var $title = $summary.children( '.title' );
			var $expander = $title.children( '.field-expand' ); // expander
			var $shortActionsWrapper = $summary.children( '.short-actions' ); // short actions wrapper
			
		}
		else {
			
			// console.log( 'field not parsed' );
			
			// console.log( 'parsing field ' + ( $field_wrapper.index() + 1 ) );
			
			$field_wrapper.children( '.content' ).before( '<div class="summary"><div class="title"></div></div>' );
			var $summary = $field_wrapper.children( '.summary' );
			var $title = $summary.children( '.title' );
			
			$title.append( '<div class="item sortable-handle handle" ' + handleElTitle + '/>' ); // handle
			$title.append( '<div class="item field-key"/>' ); // field key
			$title.append( '<div class="item field-type" ' + fieldTypeTitle + '/>' ); // field type
			$title.append( '<div class="item field-expand sortable-expander"/>' ); // expander
			var $expander = $title.children( '.field-expand' );
			$title.append( '<div class="item field-remove sortable-remove"/>' ); // remove button
			$summary.append( '<div class="item field-icon"/>' ); // type icon
			$summary.append( '<div class="item field-label"/>' ); // label
			$summary.append( '<div class="item field-presentation-label"/>' ); // presentation label
			$summary.append( '<div class="item short-actions"/>' ); // short actions wrapper
			var $shortActionsWrapper = $summary.children( '.short-actions' );
			
			$shortActionsWrapper.append('<?php // availability admin
				
				$options = array(
					
					'class' => 'action-availability-admin short-action',
					'value' => 'admin',
					'text' => lang( 'available_on_admin' ),
					'title' => lang( 'tip_availability_admin' ),
					'icon' => 'view',
					'icon_as_check' => TRUE,
					'only_icon' => TRUE,
					
				);
				
				echo str_replace( "'", "\'", vui_el_checkbox( $options ) );
				
			?>');
			$shortActionsWrapper.append('<?php // availability site
				
				$options = array(
					
					'class' => 'action-availability-site short-action',
					'value' => 'site',
					'text' => lang( 'available_on_site' ),
					'title' => lang( 'tip_availability_site' ),
					'icon' => 'view',
					'icon_as_check' => TRUE,
					'only_icon' => TRUE,
					
				);
				
				echo str_replace( "'", "\'", vui_el_checkbox( $options ) );
				
			?>');
			$shortActionsWrapper.append('<?php // field is required
				
				$options = array(
					
					'class' => 'action-field-is-required short-action',
					'value' => 'site',
					'text' => lang( 'field_is_required' ),
					'title' => lang( 'tip_field_is_required' ),
					'icon' => 'required',
					'icon_as_check' => TRUE,
					'only_icon' => TRUE,
					
				);
				
				echo str_replace( "'", "\'", vui_el_checkbox( $options ) );
				
			?>');
		}
		
		// --------------------------------
		// Expander
		
		if ( $field_wrapper.find( '.content .expanded-flag' ).val() == 1 ) {
			
			$expander.addClass( 'sortable-expanded' );
			
		}
		else {
			
			$expander.addClass( 'sortable-retracted' );
			
		}
		
		checkFieldsEditors( $field_wrapper );
		
		// --------------------------------
		// Sortable stuff
		
		$field_wrapper.addClass( 'sortable-item' );
		
		// --------------------------------
		// hiding elements
		
		$field_wrapper.find( '.content .field-key-wrapper' ).addClass( 'hidden' );
		$field_wrapper.find( '.content .submit-remove-field-wrapper' ).addClass( 'hidden' );
		
	}
	
	function changeFieldType( callbackSuccess ){
		
		var jthis = $(this);
		
		var form = $( '#submit-form-form' );
		
		var formData = form.serializeArray();
		formData.push( { name: 'submit_apply', value: 'button' } );
		
		$.ajax({
			
			type: "POST",
			url: form.attr( 'action' ) + '?ajax=submit_apply',
			data: formData,
			success: function( data ) {
				
				var object = $('<div/>').html(data).contents();
				
				data = object.html();
				
				// console.log( '<?= lang( 'msg_field_type_changed' ); ?>' );
				createGrowl( '<?= lang( 'msg_field_type_changed' ); ?>', null, null, 'msg-type-success' );
				
				if ( callbackSuccess() != undefined ) {
					
					callbackSuccess();
					
				}
				
				return true;
				
			},
			error: function( request, status, error ){
				
				//// console.log( 'request.responseText', request.responseText );
				
				msg = '<div class="msg-item msg-type-error">';
				msg += '<div class="error">Error trying save form: <strong>' + request.status + ' ' + request.statusText + '</strong></div>';
				msg += '</div>';
				
				// console.log( msg );
				createGrowl( msg, null, null, 'msg-type-error' );
				
				return false;
				
			}
			
		});
		
	}
	
	function addField( callbackSuccess ){
		
		var jthis = $(this);
		
		var form = $( '#submit-form-form' );
		
		var formData = form.serializeArray();
		formData.push( { name: 'submit_add_field', value: 'button' } );
		formData.push( { name: 'submit_apply', value: 'button' } );
		
		$.ajax({
			
			type: "POST",
			url: form.attr( 'action' ) + '?ajax=submit_apply',
			data: formData,
			success: function( data ) {
				
				var object = $('<div/>').html(data).contents();
				
				data = object.html();
				
				// console.log( '<?= lang( 'notification_success_add_field' ); ?>' );
				createGrowl( data, null, null, 'msg-type-success' );
				
				if ( callbackSuccess() != undefined ) {
					
					callbackSuccess();
					
				}
				
				return true;
				
			},
			error: function( request, status, error ){
				
				//// console.log( 'request.responseText', request.responseText );
				
				msg = '<div class="msg-item msg-type-error">';
				msg += '<div class="error">Error trying save form: <strong>' + request.status + ' ' + request.statusText + '</strong></div>';
				msg += '</div>';
				
				// console.log( msg );
				createGrowl( msg, null, null, 'msg-type-error' );
				
				return false;
				
			}
			
		});
		
	}
	
	// --------------------------------
	// Fancybox dialog
	
	function fancyAlert(msg) {
		jQuery.fancybox({
			'modal' : true,
			'content' : "<div style=\"margin:1px;width:240px;\">"+msg+"<div style=\"text-align:right;margin-top:10px;\"><input style=\"margin:3px;padding:0px;\" type=\"button\" onclick=\"jQuery.fancybox.close();\" value=\"Ok\"></div></div>"
		});
	}
	
	function fancyConfirm( msg, callback ) {
		
		var ret;
		
		jQuery.fancybox({
			
			modal : true,
			wrapCSS: 'vui-modal',
			openEffect: 'none',
			closeEffect: 'none',
			minHeight: 'auto',
			content : '<?php // field is required
				
				$unique_hash_id = md5( rand( 100, 1000 ) ) . uniqid();
				
				$_html = '<div id="modal-content-' . $unique_hash_id . '" class="modal-content">';
				
				$_html .=  '{"} + msg + {"}';
				
				$_html .= '</div>';
				$_html .= '<div id="modal-controls-' . $unique_hash_id . '" class="modal-controls modal-controls-bottom controls total-2">';
				$_html .= vui_el_button(
					
					array(
						
						'id' => 'fancyConfirm_cancel',
						'text' => lang( 'action_cancel' ),
						'icon' => 'cancel',
						'wrapper_class' => 'item',
						'only_icon' => TRUE,
						
					)
					
				);
				$_html .= vui_el_button(
					
					array(
						
						'id' => 'fancyConfirm_ok',
						'text' => lang( 'action_ok' ),
						'icon' => 'ok',
						'wrapper_class' => 'item',
						'only_icon' => TRUE,
						
					)
					
				);
				
				$_html .= '</div>';
				
				echo str_replace( array( "'", '{"}' ), array( "\'", "'" ), $_html );
				
			?>',
				
			afterLoad : function() {
				
				$( document ).on( 'click', "#fancyConfirm_cancel", function() {
					
					ret = false;
					
					// console.log( 'ret is ' + ret );
					
					// console.log( 'checking the callback function' );
					
					if ( typeof callback == 'function' ){
						
						// console.log( 'callback function called' )
						
						callback.call( this, ret );
						
					}
					
					jQuery.fancybox.close();
					
				});
				$( document ).on( 'click', "#fancyConfirm_ok", function() {
					
					ret = true;
					
					// console.log( 'ret is ' + ret );
					
					// console.log( 'checking the callback function' );
					
					if ( typeof callback == 'function' ){
						
						// console.log( 'callback function called' )
						
						callback.call( this, ret );
						
					}
					
					jQuery.fancybox.close();
					
				});
				
			},
			afterShow: function(){
				
				$( '.fancybox-overlay .fancybox-inner > .modal-content' ).each( function( index ) {
					
					var jthis = $( this );
					
					var lengModalControls = jthis.parent().find( '.modal-controls:not(.modal-controls-bottom)' ).length;
					var lengModalControlsBottom = jthis.parent().find( '.modal-controls-bottom' ).length;
					
					if ( lengModalControls > 0 ) {
						
						// console.log( 'top' )
						jthis.css( 'margin-top', jthis.parent().find( '.modal-controls' ).not( '.modal-controls-bottom' ).outerHeight() );
						jthis.css( 'max-height', 'calc( 100% - ' + jthis.parent().find( '.modal-controls' ).not( '.modal-controls-bottom' ).outerHeight() + 'px )' );
						
					}
					if ( lengModalControlsBottom > 0 ) {
						
						// console.log( 'bottom' )
						jthis.css( 'margin-bottom', jthis.parent().find( '.modal-controls-bottom' ).outerHeight() );
						jthis.css( 'max-height', 'calc( 100% - ' + jthis.parent().find( '.modal-controls-bottom' ).outerHeight() + 'px )' );
						
					}
					
				});
				
				$.fancybox.update();
				$.fancybox.reposition();
				
			},
			afterClose : function() {
				
				// console.log( 'closed' )
				
			}
			
		});
		
	}
	
$( document ).on( 'ready', function(){
	
	<?php if ( $this->plugins->load( 'yetii' ) ){ ?>
	
	// --------------------------------
	// Tabs
	
	makeTabs( $( '.tabs-wrapper' ), '.for-tab, .params-set-wrapper', 'legend, .params-set-title' );
	
	<?php } ?>
	
	parseFields();
	
	// --------------------------------
	// Drag and drop sortable
	
	$( '#fields-wrapper' ).addClass( 'sortable' );
	
	$( ".sortable" ).sortable({
		
		//containment: "parent", // descomente para limitar o movimento ao container pai
		items: '.sortable-item',
		placeholder: 'field-wrapper sortable-item sortable-placeholder',
		start: function(event, ui) {
			
			$( this ).css( 'height', $( this ).outerHeight() );
			
			$( this ).find( '.sortable-placeholder' ).css( 'height', ui.item.outerHeight() );
			
			<?php if ( $this->mcm->filtered_system_params[ 'js_text_editor' ] == 'tinymce' ) { ?>
				
				ui.item.find('.sf-field-html').each( function() {
					
					tinymce.execCommand('mceRemoveEditor', false, $(this).attr('id'));
					
				});
				
			<?php } ?>
			
			ui.item.addClass( 'sorting' );
			
			sortable_old_position = ui.item.index() + 1;
			
			console.log( "Old position: " + ( ui.item.index() + 1 ) )
			
		},
		stop: function(event, ui) {
			
			<?php if ( $this->mcm->filtered_system_params[ 'js_text_editor' ] == 'tinymce' ) { ?>
				
				ui.item.find('.sf-field-html').each(function () {
					
					tinymce.execCommand('mceAddEditor', true, $(this).attr('id'));
					
				});
				
			<?php } ?>
			
			ui.item.removeClass( 'sorting' );
			
			ui.item.css( 'position', '' );
			
			// console.log( "New position: " + ( ui.item.index() + 1 ) )
			
		},
		handle: ".summary > .title"
		
	});
	
	$( document ).on( "sortout", ".sortable", function( event, ui ){
		
		$( this ).css( 'height', '' );
		
		var start_pos = 1;
		var sortable_new_position = ui.item.index() + 1;
		
		console.log( "New position: " + sortable_new_position )
		
		if ( sortable_new_position < sortable_old_position ) {
			
			updateKeys( sortable_new_position );
			
		}
		else if ( sortable_new_position > sortable_old_position ) {
			
			updateKeys( sortable_old_position );
			
		}
		
	});
	
	
	
	
	$( document ).on( 'change', '#fields-layout-preference-wrapper .sf-field-fields_layout_preference', function( event ){
		
		$( '#fields-wrapper' ).removeClass( 'fields-layout-default fields-layout-mini' );
		$( '#fields-wrapper' ).addClass( 'fields-layout-' + $( this ).val() );
		
	});
	
	$( document ).on( 'change', '.sf_html_field_use_editor, .sf-field-default_value_use_editor', function( event ){
		
		checkFieldsEditors( $( this ).closest( '.field-wrapper' ) );
		
	});
	
	$( document ).on( "click", ".sortable .field-expand", function( event ){
		
		// console.log( 'expander clicked' );
		
		var field_wrapper = $( this ).closest( '.field-wrapper' );
		var content_wrapper = field_wrapper.find( '.content' );
		
		if ( ! $( this ).hasClass( 'sortable-expanded' ) && ! $( this ).hasClass( 'sortable-retracted' ) ) {
			
			if ( content_wrapper.hasClass( 'hidden' ) ) {
				
				$( this ).addClass( 'sortable-retracted' );
				
				content_wrapper.find( '.expanded-flag' ).val( '' );
				
			}
			else {
				
				$( this ).addClass( 'sortable-expanded' );
				
				content_wrapper.find( '.expanded-flag' ).val( '1' );
				
			}
			
		}
		
		// retract
		if ( $( this ).hasClass( 'sortable-expanded' ) ) {
			
			field_wrapper.removeClass( 'sortable-expanded' );
			field_wrapper.addClass( 'sortable-retracted' );
			
			$( this ).removeClass( 'sortable-expanded' );
			$( this ).addClass( 'sortable-retracted' );
			content_wrapper.addClass( 'hidden' );
			
			content_wrapper.find( '.expanded-flag' ).val( '' );
			
		}
		// expand
		else {
			
			field_wrapper.addClass( 'sortable-expanded' );
			field_wrapper.removeClass( 'sortable-retracted' );
			
			$( this ).addClass( 'sortable-expanded' );
			$( this ).removeClass( 'sortable-retracted' );
			content_wrapper.removeClass( 'hidden' );
			
			content_wrapper.find( '.expanded-flag' ).val( '1' );
			
		}
		
		checkFieldsEditors( field_wrapper );
		
	});
	
	
	
	
	
	
	
	
	$( document ).on( "click", ".summary .field-remove", function( event ){
		
		var jthis = $( this );
		var $field_wrapper = jthis.closest( '.field-wrapper' );
		var field_wrapper_index = jthis.closest( '.field-wrapper' ).index();
		
		fancyConfirm( "<?= str_replace( '"', '\"', lang( 'question_delete_field' ) ); ?>", function( action ) {
			
			if ( action ) {
				
				// console.log( jthis.attr( 'class' ) )
				
				console.log( 'field_wrapper_index is ' + field_wrapper_index )
				
				$field_wrapper.remove();
				
				updateKeys( field_wrapper_index );
				
			}
			
		});
		
	});
	
	$( document ).on( "keydown keyup change", '#fields-wrapper .field-wrapper .content .sf-field-label', function( event ){
		
		// labels
		$( this ).closest( '.field-wrapper' ).find( '.summary .field-label' ).html( $( this ).val() );
		
	});
	$( document ).on( "keydown keyup change", '#fields-wrapper .field-wrapper .content .sf-field-presentation_label', function( event ){
		
		// presentation labels
		$( this ).closest( '.field-wrapper' ).find( '.summary .field-presentation-label' ).html( $( this ).val() );
		
	});
	
	$( document ).on( "focus", '#fields-wrapper .field-wrapper .content .sf-field-alias', function( event ){
		
		var fieldAlias = $( this ).val();
		
		$( this ).on( 'change', function( event ){
			
			$( '.sf-field-conditional_target_field option[value="' + fieldAlias + '"]' ).val( $( this ).val() );
			
		});
		
	});
	
	$( document ).on( "focus", '#fields-wrapper .field-wrapper .content .sf-field-alias', function( event ){
		
		var fieldAlias = $( this ).val();
		
		$( this ).on( 'change', function( event ){
			
			$( '.sf-field-conditional_target_field option[value="' + fieldAlias + '"]' ).val( $( this ).val() );
			
		});
		
	});
	
	<?php /*
	
	Quando o botão "marcar todos os campos disponíveis no site", por exemplo, são acionados,
	a página atualizada recebrá em seu POST o valor deste botão, o qual todos os campos "[availability][site]"
	de todos os submit_form_fields serão marcados. Isso somente quando o js não está disponível. Quando o navegador
	possui suporte a js, substituímos esses campos por checkboxes, os quais irão marcar todos os campos via js,
	sem a necessidade de atualizar a página, economizando banda.
	
	*/ ?>
	
	<?php /*
	
	O código a seguir adicionar a classe "__to_remove" para os botões que serão substituídos pelas checkboxes
	
	*/ ?>
	
	$( '#submit-check-all-fields-available-on-admin, #submit-check-all-fields-available-on-site, #submit-check-all-fields-visible-on-site-list, #submit-check-all-fields-visible-on-site-detail' ).closest( '.vui-interactive-el-wrapper' ).addClass( '__to_remove' );
	
	<?php /*
	
	O código a seguir substitui os botões pelas checkboxes
	
	*/ ?>
	
	$( '#submit-check-all-fields-available-on-admin' ).closest( '.vui-interactive-el-wrapper' ).before('<?=
		
		str_replace( "'", "\'",
			
			vui_el_checkbox(
				
				array(
					
					'text' => lang( 'submit_check_all_fields_available_on_admin' ),
					'title' => lang( 'tip_submit_check_all_fields_available_on_admin' ),
					//'name' => 'submit_check_all_fields_available_on_admin',
					'checked' => FALSE,
					'value' => 1,
					'id' => 'submit-check-all-fields-available-on-admin',
					'only_icon' => TRUE,
					'icon' => 'view',
					'icon_as_check' => TRUE,
					
				)
				
			)
			
		);
		
	?>');
	
	$( '#submit-check-all-fields-available-on-site' ).closest( '.vui-interactive-el-wrapper' ).before('<?=
		
		str_replace( "'", "\'",
			
			vui_el_checkbox(
					
				array(
					
					'text' => lang( 'submit_check_all_fields_available_on_site' ),
					'title' => lang( 'tip_submit_check_all_fields_available_on_site' ),
					//'name' => 'submit_check_all_fields_available_on_site',
					'checked' => FALSE,
					'value' => 1,
					'id' => 'submit-check-all-fields-available-on-site',
					'only_icon' => TRUE,
					'icon' => 'view',
					'icon_as_check' => TRUE,
					
				)
				
			)
			
		);
		
	?>');
	
	$( '#submit-check-all-fields-visible-on-site-list' ).closest( '.vui-interactive-el-wrapper' ).before('<?=
		
		str_replace( "'", "\'",
			
			vui_el_checkbox(
					
				array(
					
					'text' => lang( 'submit_check_all_fields_visible_on_site_list' ),
					'title' => lang( 'tip_submit_check_all_fields_visible_on_site_list' ),
					//'name' => 'submit_check_all_fields_available_on_site',
					'checked' => FALSE,
					'value' => 1,
					'id' => 'submit-check-all-fields-visible-on-site-list',
					'only_icon' => TRUE,
					'icon' => 'list',
					'icon_as_check' => TRUE,
					
				)
				
			)
			
		);
		
	?>');
	
	$( '#submit-check-all-fields-visible-on-site-detail' ).closest( '.vui-interactive-el-wrapper' ).before('<?=
		
		str_replace( "'", "\'",
			
			vui_el_checkbox(
					
				array(
					
					'text' => lang( 'submit_check_all_fields_visible_on_site_detail' ),
					'title' => lang( 'tip_submit_check_all_fields_visible_on_site_detail' ),
					//'name' => 'submit_check_all_fields_available_on_site',
					'checked' => FALSE,
					'value' => 1,
					'id' => 'submit-check-all-fields-visible-on-site-detail',
					'only_icon' => TRUE,
					'icon' => 'detail',
					'icon_as_check' => TRUE,
					
				)
				
			)
			
		);
		
	?>');
	
	<?php /*
	
	O código a seguir aplica os eventos
	
	*/ ?>
	
	$( document ).on( 'change', '#submit-check-all-fields-available-on-admin', function( event ){
		
		var jthis = $( this );
		
		var start = new Date().getTime();
		
		if ( jthis.is( ':checked' ) ) {
			
			$( '#fields-wrapper .sf-field-availability_admin:not(:checked)' ).each( function( index ) {
				
				$( this )[ 0 ].checked = true;
				$( this ).trigger( 'change' );
				
			});
			
		}
		else {
			
			$( '#fields-wrapper .sf-field-availability_admin:checked' ).each( function( index ) {
				
				$( this )[ 0 ].checked = false;
				$( this ).trigger( 'change' );
				
			});
			
		}
		
		var end = new Date().getTime();
		
		createGrowl( '<?= lang( 'notification_done' ); ?>', null, null, 'msg-type-success' );
		
		// console.log( 'finished in ' + ( end - start ) + 'ms' )
		
	});
	
	$( document ).on( 'change', '#submit-check-all-fields-available-on-site', function( event ){
		
		var jthis = $( this );
		
		var start = new Date().getTime();
		
		if ( jthis.is( ':checked' ) ) {
			
			$( '#fields-wrapper .sf-field-availability_site:not(:checked)' ).each( function( index ) {
				
				$( this )[ 0 ].checked = true;
				$( this ).trigger( 'change' );
				
			});
			
		}
		else {
			
			$( '#fields-wrapper .sf-field-availability_site:checked' ).each( function( index ) {
				
				$( this )[ 0 ].checked = false;
				$( this ).trigger( 'change' );
				
			});
			
		}
		
		var end = new Date().getTime();
		
		createGrowl( '<?= lang( 'notification_done' ); ?>', null, null, 'msg-type-success' );
		
		// console.log( 'finished in ' + ( end - start ) + 'ms' )
		
	});
	
	$( document ).on( 'change', '#submit-check-all-fields-visible-on-site-list', function( event ){
		
		var jthis = $( this );
		
		var start = new Date().getTime();
		
		if ( jthis.is( ':checked' ) ) {
			
			$( '#fields-wrapper .sf-field-visibility_site_list:not(:checked)' ).each( function( index ) {
				
				$( this )[ 0 ].checked = true;
				$( this ).trigger( 'change' );
				
			});
			
		}
		else {
			
			$( '#fields-wrapper .sf-field-visibility_site_list:checked' ).each( function( index ) {
				
				$( this )[ 0 ].checked = false;
				$( this ).trigger( 'change' );
				
			});
			
		}
		
		var end = new Date().getTime();
		
		createGrowl( '<?= lang( 'notification_done' ); ?>', null, null, 'msg-type-success' );
		
		// console.log( 'finished in ' + ( end - start ) + 'ms' )
		
	});
	
	$( document ).on( 'change', '#submit-check-all-fields-visible-on-site-detail', function( event ){
		
		var jthis = $( this );
		
		var start = new Date().getTime();
		
		if ( jthis.is( ':checked' ) ) {
			
			$( '#fields-wrapper .sf-field-visibility_site_detail:not(:checked)' ).each( function( index ) {
				
				$( this )[ 0 ].checked = true;
				$( this ).trigger( 'change' );
				
			});
			
		}
		else {
			
			$( '#fields-wrapper .sf-field-visibility_site_detail:checked' ).each( function( index ) {
				
				$( this )[ 0 ].checked = false;
				$( this ).trigger( 'change' );
				
			});
			
		}
		
		var end = new Date().getTime();
		
		createGrowl( '<?= lang( 'notification_done' ); ?>', null, null, 'msg-type-success' );
		
		// console.log( 'finished in ' + ( end - start ) + 'ms' )
		
	});
	
	<?php /*
	
	O código a seguir remove os elementos que foram substituídos pelas checkboxes
	
	*/ ?>
	
	$( '.__to_remove' ).remove();
	
	<?php /*
	
	-----------------------------------------
	
	*/ ?>
	
	$( document ).on( 'change', '#fields-wrapper .field-wrapper .content .sf-field-availability_admin', function( event ){
		
		update_availability_admin( $( this ).closest( '.field-wrapper' ) );
		
	});
	$( document ).on( 'change', '#fields-wrapper .field-wrapper .summary .short-actions .action-availability-admin', function( event ){
		
		var summary = $( this ).closest( '.field-wrapper' ).find( '.summary' );
		var content = $( this ).closest( '.field-wrapper' ).find( '.content' );
		
		content.find( '.sf-field-availability_admin' )[0].checked = $( this ).is( ':checked' ) ? true : false;
		
		content.find( '.sf-field-availability_admin' ).trigger( 'change' );
		
	});
	
	$( document ).on( 'change', '#fields-wrapper .field-wrapper .content .sf-field-availability_site', function( event ){
		
		update_availability_site( $( this ).closest( '.field-wrapper' ) );
		
	});
	$( document ).on( 'change', '#fields-wrapper .field-wrapper .summary .short-actions .action-availability-site', function( event ){
		
		var summary = $( this ).closest( '.field-wrapper' ).find( '.summary' );
		var content = $( this ).closest( '.field-wrapper' ).find( '.content' );
		
		content.find( '.sf-field-availability_site' )[0].checked = $( this ).is( ':checked' ) ? true : false;
		
		content.find( '.sf-field-availability_site' ).trigger( 'change' );
		
	});
	
	$( document ).on( 'change', '#fields-wrapper .field-wrapper .content .sf-field-visibility_site_list', function( event ){
		
		update_visibility_site_list( $( this ).closest( '.field-wrapper' ) );
		
	});
	
	_check_global_availability();
	_check_visibility_site_list();
	_check_visibility_site_detail();
	
	
	
	
	
	
	
	
	
	
	
	<?php if ( $component_function_action != 'asf' ) { ?>
	
	$( document ).on( 'change', '#fields-wrapper .field-wrapper .content .sf-field-type', function( event ){
		
		var field_wrapper = $( this ).closest( '.field-wrapper' );
		
		createGrowl( '<?= lang( 'notification_saving_submit_form' ); ?>', null, null, 'msg-type-info' );
		
		changeFieldType( function(){
			
			field_wrapper.load( '<?= get_url( 'admin' . $this->uri->ruri_string() ); ?> #' + field_wrapper.attr( 'id' ) + ' > *', function() {
				
				parseField( field_wrapper );
				
				updateFields();
				
			});
			
		});
		
	});
	
	<?php } ?>
	
	<?php if ( $component_function_action != 'asf' ) { ?>
	
	$( document ).on( 'click', '#submit-add-field', function( event ){
		
		event.preventDefault();
		
		$( '#fields-wrapper' ).addClass( 'loading' );
		
		var fieldsWrapper = $( '#fields-wrapper' );
		
		createGrowl( '<?= lang( 'notification_saving_submit_form' ); ?>', null, null, 'msg-type-info' );
		
		addField( function(){
			
			createGrowl( '<?= lang( 'notification_loading_fields' ); ?>', null, null, 'msg-type-info' );
			
			fieldsWrapper.load( '<?= get_url( 'admin' . $this->uri->ruri_string() ); ?> #' + fieldsWrapper.attr( 'id' ) + ' > *', function() {
				
				parseFields();
				
				updateKeys();
				
				$( '#fields-wrapper' ).removeClass( 'loading' );
				
			});
			
		});
		
		
		
	});
	
	<?php } ?>
	
	$( document ).on( 'change', '#fields-wrapper .field-wrapper .content .sf-field-field_is_required, #fields-wrapper .field-wrapper .content .sf-field-sf_date_field_day_is_req, #fields-wrapper .field-wrapper .content .sf-field-sf_date_field_month_is_req, #fields-wrapper .field-wrapper .content .sf-field-sf_date_field_year_is_req', function( event ){
		
		update_field_is_required( $( this ).closest( '.field-wrapper' ) );
		
	});
	$( document ).on( 'change', '#fields-wrapper .field-wrapper .summary .short-actions .action-field-is-required', function( event ){
		
		var summary = $( this ).closest( '.field-wrapper' ).find( '.summary' );
		var content = $( this ).closest( '.field-wrapper' ).find( '.content' );
		
		content.find( '.sf-field-field_is_required option[selected]' ).removeAttr( 'selected' );
		if ( $( this ).is( ':checked' ) ) {
			
			content.find( '.sf-field-field_is_required' )[0].checked = true;
			content.find( '.sf-field-field_is_required option[value=1]' ).attr( 'selected', 'selected' );
			
		}
		else {
			
			content.find( '.sf-field-field_is_required' )[0].checked = false;
			content.find( '.sf-field-field_is_required option[value=0]' ).attr( 'selected', 'selected' );
			
		}
		
		content.find( '.sf-field-sf_date_field_day_is_req option[selected]' ).removeAttr( 'selected' );
		if ( $( this ).is( ':checked' ) ) {
			
			content.find( '.sf-field-sf_date_field_day_is_req' ).val( 1 );
			content.find( '.sf-field-sf_date_field_day_is_req option[value=1]' ).attr( 'selected', 'selected' );
			
		}
		else {
			
			content.find( '.sf-field-sf_date_field_day_is_req' ).val( 0 );
			content.find( '.sf-field-sf_date_field_day_is_req option[value=0]' ).attr( 'selected', 'selected' );
			
		}
		
		content.find( '.sf-field-sf_date_field_month_is_req option[selected]' ).removeAttr( 'selected' );
		if ( $( this ).is( ':checked' ) ) {
			
			content.find( '.sf-field-sf_date_field_month_is_req' ).val( 1 );
			content.find( '.sf-field-sf_date_field_month_is_req option[value=1]' ).attr( 'selected', 'selected' );
			
		}
		else {
			
			content.find( '.sf-field-sf_date_field_month_is_req' ).val( 0 );
			content.find( '.sf-field-sf_date_field_month_is_req option[value=0]' ).attr( 'selected', 'selected' );
			
		}
		
		content.find( '.sf-field-sf_date_field_year_is_req option[selected]' ).removeAttr( 'selected' );
		if ( $( this ).is( ':checked' ) ) {
			
			content.find( '.sf-field-sf_date_field_year_is_req' ).val( 1 );
			content.find( '.sf-field-sf_date_field_year_is_req option[value=1]' ).attr( 'selected', 'selected' );
			
		}
		else {
			
			content.find( '.sf-field-sf_date_field_year_is_req' ).val( 0 );
			content.find( '.sf-field-sf_date_field_year_is_req option[value=0]' ).attr( 'selected', 'selected' );
			
		}
		
	});
	
	$( '#fields-wrapper .field-wrapper' ).find( '.content .sf-field-presentation_label' ).bind( "focus", function( event ){
		
		var fieldLabel = $( this ).val();
		
		$( this ).change( function( event ){
			
			$( '.sf-field-conditional_target_field option:contains("' + fieldLabel + '")' ).text( $( this ).val() ).trigger( 'change' );
			
		});
		
	});
	
	$( '#fields-wrapper .field-wrapper' ).find( '.content .sf-field-alias' ).bind( "focus", function( event ){
		
		var fieldValue = $( this ).val();
		
		$( this ).change( function( event ){
			
			$( '.sf-field-conditional_target_field option[value=' + fieldValue + ']:first' ).attr( 'value', $( this ).val() ).trigger( 'change' );
			
		});
		
	});
	
	/************** Drag and drop code *************/
	/*************************************************/
	
	updateKeys();
		
	$( document ).on( 'dblclick', '#fields-wrapper .field-wrapper .summary > .title > .sortable-handle', function() {
		
		$( this ).parent().find( '.field-expand' ).click();
		
	});
	
});

$( window ).on( 'load', function(){
	
})
</script>
