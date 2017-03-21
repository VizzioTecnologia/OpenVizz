
<div id="global-config-form-wrapper" class="form-wrapper tabs-wrapper">
	
	<div class="form-wrapper-sub tabs-children">
			
		<?= form_open_multipart( get_url( 'admin'.$this->uri->ruri_string() ), array( 'id' => 'module-form', ) ); ?>
			
			<div class="form-actions to-toolbar to-main-toolbar">
				
				<?= vui_el_button( array( 'text' => lang( 'action_save' ), 'icon' => 'save', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit', 'id' => 'submit', 'only_icon' => TRUE, 'form' => 'module-form', ) ); ?>
				
				<?= vui_el_button( array( 'text' => lang( 'action_apply' ), 'icon' => 'apply', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_apply', 'id' => 'submit-apply', 'only_icon' => TRUE, 'form' => 'module-form', ) ); ?>
				
				<?= vui_el_button( array( 'text' => lang( 'action_cancel' ), 'icon' => 'cancel', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_cancel', 'id' => 'submit-cancel', 'only_icon' => TRUE, 'form' => 'module-form', ) ); ?>
				
			</div>
			
			<header class="form-header tabs-header">
				
				<h1>
					
					<?php if ( $component_function_action == 'am' ) { ?>
						
					<?= lang( 'new_module' ); ?>
					
					<?php } else if ( $component_function_action == 'em' ) { ?>
						
					<?= lang( 'edit_module' ); ?>
					
					<?php } ?> - <?= lang( $module[ 'type' ] ); ?>
					
				</h1>
				
			</header>
			
			<div class="form-items tabs-items">
				
				<div class="form-item">
					
					<fieldset>
						
						<legend>
							
							<?= vui_el_button( array( 'text' => lang( 'basic_details' ), 'icon' => 'basic-details',  ) ); ?>
							
						</legend>
						
						<?php
							
							// --------------------------
							
							$field_name = 'title';
							$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
							
							echo '<div id="' . $field_name . '-container" class="vui-field-wrapper-inline' . ( $field_error ? ' error' : '' ) . '">';
							
							echo form_label( lang( 'set_module_' . $field_name ) );
							
							echo vui_el_input_text(
								
								array(
									
									'text' => $field_error ? $field_error : lang( 'set_module_' . $field_name ),
									'title' => $field_error ? element_title( $field_error ) : lang( 'tip_set_module_' . $field_name ),
									'name' => $field_name,
									'value' => set_value( $field_name, check_var( $module[ $field_name ] ) ? $module[ $field_name ] : '' ),
									'class' => $field_name . ' ' . ( $field_error ? 'field-error' : '' ),
									'id' => 'module-' . $field_name,
									'autofocus' => TRUE,
									
								)
								
							);
							
							echo '</div>';
							
							// --------------------------
							
							$field_name = 'alias';
							$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
							
							echo '<div id="' . $field_name . '-container" class="vui-field-wrapper-inline' . ( $field_error ? ' error' : '' ) . '">';
							
							echo form_label( lang( 'set_module_' . $field_name ) );
							
							echo vui_el_input_text(
								
								array(
									
									'text' => $field_error ? $field_error : lang( 'set_module_' . $field_name ),
									'title' => $field_error ? element_title( $field_error ) : lang( 'tip_set_module_' . $field_name ),
									'name' => $field_name,
									'value' => set_value( $field_name, check_var( $module[ $field_name ] ) ? $module[ $field_name ] : '' ),
									'class' => $field_name . ' ' . ( $field_error ? 'field-error' : '' ),
									'id' => 'module-' . $field_name,
									
								)
								
							);
							
							echo '</div>';
							
							// --------------------------
							
							$field_name = 'environment';
							$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
							
							echo '<div id="' . $field_name . '-container" class="vui-field-wrapper-inline' . ( $field_error ? ' error' : '' ) . '">';
							
							$field_options = array(
								
								'site' => lang( 'site' ),
								'admin' => lang( 'admin' ),
								
							);
							
							echo form_label( lang( 'set_module_' . $field_name ) );
							
							echo vui_el_dropdown(
								
								array(
									
									'text' => $field_error ? $field_error : lang( 'set_module_' . $field_name ),
									'title' => $field_error ? element_title( $field_error ) : lang( 'tip_set_module_' . $field_name ),
									'name' => $field_name,
									'value' => set_value( $field_name, check_var( $module[ $field_name ] ) ? $module[ $field_name ] : 'site' ),
									'class' => $field_name . ' ' . ( $field_error ? 'field-error' : '' ),
									'id' => 'module-' . $field_name,
									'options' => $field_options,
									
								)
								
							);
							
							echo '</div>';
							
							// --------------------------
							
							$field_name = 'status';
							$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
							
							echo '<div id="' . $field_name . '-container" class="vui-field-wrapper-inline' . ( $field_error ? ' error' : '' ) . '">';
							
							$field_options = array(
								
								0 => lang( 'unpublished' ),
								1 => lang( 'published' ),
								
							);
							
							echo form_label( lang( 'set_module_' . $field_name ) );
							
							echo vui_el_dropdown(
								
								array(
									
									'text' => $field_error ? $field_error : lang( 'set_module_' . $field_name ),
									'title' => $field_error ? element_title( $field_error ) : lang( 'tip_set_module_' . $field_name ),
									'name' => $field_name,
									'value' => set_value( $field_name, isset( $module[ $field_name ] ) ? $module[ $field_name ] : 0 ),
									'class' => $field_name . ' ' . ( $field_error ? 'field-error' : '' ),
									'id' => 'module-' . $field_name,
									'options' => $field_options,
									
								)
								
							);
							
							echo '</div>';
							
							// --------------------------
							
							$field_name = 'ordering';
							$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
							
							echo '<div id="' . $field_name . '-container" class="vui-field-wrapper-inline' . ( $field_error ? ' error' : '' ) . '">';
							
							$field_options = array(
								
								'site' => lang( 'site' ),
								'admin' => lang( 'admin' ),
								
							);
							
							echo form_label( lang( 'set_module_' . $field_name ) );
							
							echo vui_el_input_number(
								
								array(
									
									'text' => $field_error ? $field_error : lang( 'set_module_' . $field_name ),
									'title' => $field_error ? element_title( $field_error ) : lang( 'tip_set_module_' . $field_name ),
									'name' => $field_name,
									'value' => set_value( $field_name, check_var( $module[ $field_name ] ) ? $module[ $field_name ] : 1 ),
									'class' => $field_name . ' ' . ( $field_error ? 'field-error' : '' ),
									'id' => 'module-' . $field_name,
									'min' => 1,
									
								)
								
							);
							
							echo '</div>';
							
							// --------------------------
							
							$field_name = 'position';
							$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
							
							echo '<div id="' . $field_name . '-container" class="vui-field-wrapper-inline' . ( $field_error ? ' error' : '' ) . '">';
							
							echo form_label( lang( 'set_module_' . $field_name ) );
							
							echo vui_el_input_text(
								
								array(
									
									'text' => $field_error ? $field_error : lang( 'set_module_' . $field_name ),
									'title' => $field_error ? element_title( $field_error ) : lang( 'tip_set_module_' . $field_name ),
									'name' => $field_name,
									'value' => set_value( $field_name, check_var( $module[ $field_name ] ) ? $module[ $field_name ] : '' ),
									'class' => $field_name . ' ' . ( $field_error ? 'field-error' : '' ),
									'id' => 'module-' . $field_name,
									
								)
								
							);
							
							echo '</div>';
							
							// --------------------------
							
							$field_name = 'mi_cond';
							$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
							
							echo '<div id="' . $field_name . '-container" class="vui-field-wrapper-inline' . ( $field_error ? ' error' : '' ) . '">';
							
							$field_options = array(
								
								'all' => lang( 'option_menus_items_modules_all' ),
								//'none' => lang( 'option_menus_items_modules_none' ),
								'specific' => lang( 'option_menus_items_modules_specific' ),
								'all_except' => lang( 'option_menus_items_modules_all_except' ),
// 									'none_except' => lang( 'option_menus_items_modules_none_except' ),
								
							);
							
							echo form_label( lang( 'set_module_' . $field_name ) );
							
							echo vui_el_dropdown(
								
								array(
									
									'text' => $field_error ? $field_error : lang( 'set_module_' . $field_name ),
									'title' => $field_error ? element_title( $field_error ) : lang( 'tip_set_module_' . $field_name ),
									'name' => $field_name,
									'value' => set_value( $field_name, isset( $module[ $field_name ] ) ? $module[ $field_name ] : 0 ),
									'class' => $field_name . ' ' . ( $field_error ? 'field-error' : '' ),
									'id' => 'module-' . $field_name,
									'options' => $field_options,
									
								)
								
							);
							
							echo '</div>';
							
							// --------------------------
							
							$field_name = 'menus_items';
							$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
							
							echo '<div id="' . $field_name . '-container" class="vui-field-wrapper-inline' . ( $field_error ? ' error' : '' ) . '">';
							
							echo form_label( lang( 'set_module_' . $field_name ) );
							
							echo vui_el_dropdown(
								
								array(
									
									'text' => $field_error ? $field_error : lang( 'set_module_' . $field_name ),
									'title' => $field_error ? element_title( $field_error ) : lang( 'tip_set_module_' . $field_name ),
									'name' => $field_name . '[]',
									'value' => set_value( $field_name, isset( $module[ $field_name ] ) ? $module[ $field_name ] : 0 ),
									'class' => $field_name . ' ' . ( $field_error ? 'field-error' : '' ),
									'id' => 'module-' . $field_name,
									'options' => $menus_items_options,
									'multiselect' => TRUE,
									
								)
								
							);
							
							echo '</div>';
							
						?>
						
						<div class="divisor-h"></div>
						
					</fieldset>
					
				</div>
				
				<div class="form-item">
					
					<fieldset>
						
						<legend>
							
							<?= vui_el_button( array( 'text' => lang( 'access' ), 'icon' => 'security','title' => lang( 'tip_articles_management_access_level' ),  ) ); ?>
							
						</legend>
						
						<div class="field-wrapper">
							
							<label>
								
								<?php
									
									$options = array(
										
										'name' => 'access_type',
										'value' => 'public',
										'checked' => ( $this->input->post('access_type') == 'public') ? TRUE: ( ( ! $this->input->post( 'access_type' ) ) ? TRUE : FALSE ),
										'text' => lang( 'public' ),
										
									);
									
									if ( $component_function_action == 'em' ) {
										
										$options[ 'checked' ] = ( $this->input->post( 'access_type' ) == 'public' ) ? TRUE : ( ( ! $this->input->post( 'access_type' ) AND $module[ 'access_type' ] == 'public' ) ? TRUE : FALSE );
										
									}
									
									echo vui_el_radiobox( $options );
									
								?>
								
							</label>
							
						</div>
						
						<div class="field-wrapper">
							
							<?php
								
								$access_type_users_field_name = 'access_user_id[]';
								$access_type_users_field_error = form_error( $access_type_users_field_name, '<div class="msg-inline-error">', '</div>' );
								$access_type_users_label_attr = 'class="' . ( $access_type_users_field_error ? 'field-error' : '' ) . '"' . ( $access_type_users_field_error ? element_title( $access_type_users_field_error ) : '' );
								
							?>
							
							<label <?= $access_type_users_label_attr; ?>>
								
								<span class="fake-label">
									
									<?php
										
										$options = array(
											
											'name' => 'access_type',
											'value' => 'users',
											'checked' => ( $this->input->post('access_type') == 'users') ? TRUE: FALSE,
											'text' => lang( 'specific_users' ),
											
										);
										
										if ( $component_function_action == 'em' ) {
											
											$options[ 'checked' ] = ( $this->input->post('access_type') == 'users' ) ? TRUE : ( ( ! $this->input->post( 'access_type' ) AND $module[ 'access_type' ] == 'users' ) ? TRUE : FALSE );
											
										}
										
										echo vui_el_radiobox( $options );
										
									?>
									
								</span>
								
								<?php
								
								if ( $this->input->post( 'access_user_id' ) ) {
									
									$post_access_user_id = $this->input->post('access_user_id');
									
								}
								else {
									
									$post_access_user_id = FALSE;
									
								}
								
								?>
								
								<?php foreach( $users as $user ){
									
									$options = array(
										
										'wrapper-class' => 'checkbox-sub-item',
										'name' => $access_type_users_field_name,
										'id' => $user[ 'id' ],
										'value' => '>' . $user[ 'id' ] . '<',
										'checked' => ( $this->input->post( 'access_type' ) === 'users' AND $post_access_user_id AND in_array( html_escape( '>' . $user['id'] . '<' ), $post_access_user_id ) ) ? 'checked' : '',
										'text' => $user[ 'name' ] . ' (' . $user[ 'username' ] . ')',
										
									);
									
									if ( $component_function_action == 'em' ) {
										
										$options[ 'checked' ] = ( $this->input->post( 'access_type' ) === 'users' AND $post_access_user_id AND in_array( html_escape( '>' . $user['id'] . '<' ), $post_access_user_id ) ) ? TRUE : ( ( ! $this->input->post('access_type') AND $module[ 'access_type' ] == 'users' AND in_array( '>'.$user['id'].'<', $module[ 'access_user_id' ] ) ) ? TRUE : FALSE );
										
									}
									
									echo vui_el_checkbox( $options );
									
								};
								
								?>
								
							</label>
							
						</div>
						
						<div class="field-wrapper">
							
							<label>
								
								<?php
									
									$access_type_users_groups_field_name = 'access_user_group_id[]';
									$access_type_users_groups_field_error = form_error( $access_type_users_groups_field_name, '<div class="msg-inline-error">', '</div>' );
									$access_type_users_groups_label_attr = 'class="' . ( $access_type_users_groups_field_error ? 'field-error' : '' ) . '"' . ( $access_type_users_groups_field_error ? element_title( $access_type_users_groups_field_error ) : '' );
									
								?>
								
								<label <?= $access_type_users_groups_label_attr; ?>>
									
									<?php
										
										$options = array(
											
											'name' => 'access_type',
											'value' => 'users_groups',
											'checked' => ( $this->input->post( 'access_type' ) == 'users_groups') ? TRUE: FALSE,
											'text' => lang( 'specific_users_groups' ),
											
										);
										
										if ( $component_function_action == 'em' ) {
											
											$options[ 'checked' ] = ( $this->input->post( 'access_type' ) == 'users_groups' ) ? TRUE: ( ( ! $this->input->post( 'access_type' ) AND $module[ 'access_type' ] == 'users_groups' ) ? TRUE : FALSE );
											
										}
										
										echo vui_el_radiobox( $options );
										
									?>
									
									<?php
									
									if ( $this->input->post( 'access_user_group_id' ) ) {
										
										$post_access_user_group_id = $this->input->post('access_user_group_id');
										
									}
									else {
										
										$post_access_user_group_id = FALSE;
										
									}
									
									?>
									
									<?php foreach( $users_groups as $user_group ){
										
										$options = array(
											
											'wrapper-class' => 'checkbox-sub-item',
											'name' => $access_type_users_groups_field_name,
											'id' => 'user-group-' . $user_group[ 'id' ],
											'value' => '>' . $user_group[ 'id' ] . '<',
											'checked' => ( $this->input->post( 'access_type' ) === 'users_groups' AND $post_access_user_group_id AND in_array( html_escape( '>' . $user_group['id'] . '<' ), $post_access_user_group_id ) ) ? TRUE : FALSE,
											'text' => $user_group[ 'indented_title' ],
											
										);
										
										if ( $component_function_action == 'em' ) {
											
											$options[ 'checked' ] = ( $this->input->post( 'access_type' ) === 'users_groups' AND $post_access_user_group_id AND in_array( html_escape( '>' . $user_group['id'] . '<' ), $post_access_user_group_id ) ) ? TRUE : ( ( ! $this->input->post('access_type') AND $module[ 'access_type' ] == 'users_groups' AND in_array( '>'.$user_group['id'].'<', $module[ 'access_user_group_id' ] ) ) ? TRUE : FALSE );
											
										}
										
										echo vui_el_checkbox( $options );
										
									};
									
									?>
									
								</label>
								
						</div>
						
					</fieldset>
					
				</div>
				
				<?php
				
				/* gerando o html dos parâmetros, ele deve ser chamado na view, não no controller,
					* pois os erros de validação dos elementos dos parâmetros devem ser expostos
					* após a chamada da função $this->form_validation->run()
					*/
				
				echo params_to_html( $module_params_spec, $module_final_params_values );
				
				?>
				
				<?php
				
				/* gerando o html dos parâmetros, ele deve ser chamado na view, não no controller,
					* pois os erros de validação dos elementos dos parâmetros devem ser expostos
					* após a chamada da função $this->form_validation->run()
					*/
				
				echo params_to_html( $module_type_params_spec, $module_type_final_params_values );
				
				?>
				
			</div>
			
			<?= form_hidden('type', @$module[ 'type' ]); ?>
			
			<?= form_hidden('module_id', @$module[ 'id' ]); ?>
			
		<?= form_close(); ?>
		
	</div>
	
</div>

<?php if ( $this->plugins->load( 'yetii' ) ){ ?>

<script type="text/javascript" >
	
$( document ).ready(function(){
	
	/*************************************************/
	/**************** Criando as tabs ****************/
	
	makeTabs( $( '.tabs-wrapper' ), '.form-item, .params-set-wrapper', 'legend, .params-set-title' );
	
	/**************** Criando as tabs ****************/
	/*************************************************/
	
});

</script>

<?php } ?>
