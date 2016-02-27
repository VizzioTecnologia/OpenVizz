
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
						
						<div id="title-container" class="vui-field-wrapper-inline">
							
							<?= form_error( 'title', '<div class="msg-inline-error">', '</div>' ); ?>
							<?= form_label( lang( 'title' ) ); ?>
							<?= form_input( array( 'id'=>'title', 'name'=>'title' ), set_value( 'title', @$module[ 'title' ] ),'autofocus' ); ?>
							
						</div>
						
						<div id="alias-container" class="vui-field-wrapper-inline">
							
							<?= form_error( 'alias', '<div class="msg-inline-error">', '</div>' ); ?>
							<?= form_label( lang( 'alias' ) ); ?>
							<?= form_input( array( 'id'=>'alias', 'name'=>'alias' ), set_value('alias', @$module[ 'alias' ] ) ); ?>
							
						</div>
						
						<div id="environment-container" class="vui-field-wrapper-inline">
							
							<?= form_error( 'environment', '<div class="msg-inline-error">', '</div>' ); ?>
							<?= form_label( lang( 'environment' ) ); ?>
							<?php
								$options = array(
									
									'site' => lang( 'site' ),
									'admin' => lang( 'admin' ),
									
								);
							?>
							<?= form_dropdown( 'environment', $options, set_value( 'environment', @$module[ 'environment' ] ),'id="environment"' ); ?>
							
						</div>
						
						<div id="status-container" class="vui-field-wrapper-inline">
							
							<?= form_error( 'status', '<div class="msg-inline-error">', '</div>' ); ?>
							<?= form_label( lang( 'status' ) ); ?>
							<?php
								$options = array(
									
									0 => lang( 'unpublished' ),
									1 => lang( 'published' ),
									
								);
							?>
							<?= form_dropdown( 'status', $options, set_value( 'status', @$module[ 'status' ] ),'id="status"' ); ?>
							
						</div>
						
						<div id="ordering-container" class="vui-field-wrapper-inline">
							
							<?= form_error( 'ordering', '<div class="msg-inline-error">', '</div>' ); ?>
							<?= form_label( lang( 'ordering' ) ); ?>
							<?= form_input( array( 'id'=>'ordering', 'name'=>'ordering' ), set_value('ordering', @$module[ 'ordering' ] ) ); ?>
							
						</div>
						
						<div id="position-container" class="vui-field-wrapper-inline">
							
							<?= form_error( 'position', '<div class="msg-inline-error">', '</div>' ); ?>
							<?= form_label( lang( 'position' ) ); ?>
							<?= form_input( array( 'id'=>'position', 'name'=>'position' ), set_value('position', @$module[ 'position' ] ) ); ?>
							
						</div>
						
						<div id="mi-cond-container" class="vui-field-wrapper-inline">
							
							<?= form_error( 'mi_cond', '<div class="msg-inline-error">', '</div>' ); ?>
							<?= form_label( lang( 'mi_cond_label' ) ); ?>
							<?php
								$options = array(
									
									'all' => lang( 'option_menus_items_modules_all' ),
									//'none' => lang( 'option_menus_items_modules_none' ),
									'specific' => lang( 'option_menus_items_modules_specific' ),
									'all_except' => lang( 'option_menus_items_modules_all_except' ),
									'none_except' => lang( 'option_menus_items_modules_none_except' ),
									
								);
							?>
							<?= form_dropdown( 'mi_cond', $options, set_value( 'mi_cond', @$module[ 'mi_cond' ] ),'id="mi-cond"' ); ?>
							
						</div>
						
						<div id="menus-items-container" class="vui-field-wrapper-inline">
							
							<?= form_error( 'menus_items[]', '<div class="msg-inline-error">', '</div>' ); ?>
							<?= form_label( lang( 'menus_items' ) ); ?>
							<?= form_multiselect('menus_items[]', $menus_items_options, set_value('menus_items', @$module[ 'menus_items' ]), 'id="menu-items"' . ' size="' . $menu_items_count . '"' ); ?>
							
						</div>
						
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
				
				<div class="form-item">
					
					<fieldset>
						
						<legend>
							
							<?= vui_el_button( array( 'text' => lang( 'parameters' ) . ' - ' . lang( $module[ 'type' ] ), 'icon' => $module[ 'type' ],  ) ); ?>
							
						</legend>
						
						<?php //echo parse_params($params, get_params($menu_item[ 'params)); ?>
						
						<?php
						
						/* gerando o html dos parâmetros, ele deve ser chamado na view, não no controller,
						 * pois os erros de validação dos elementos dos parâmetros devem ser expostos
						 * após a chamada da função $this->form_validation->run()
						 */
						
						echo params_to_html( $module_type_params_spec, $module_type_final_params_values );
						
						?>
						
					</fieldset>
					
				</div>
				
				<div class="form-item">
					
					<fieldset>
						
						<legend>
							
							<?= vui_el_button( array( 'text' => lang( 'parameters' ) . ' - ' . lang( 'module' ), 'icon' => 'modules',  ) ); ?>
							
						</legend>
						
						<?php //echo parse_params($menu_item_params, get_params($menu_item[ 'params)); ?>
						
						<?php
						
						/* gerando o html dos parâmetros, ele deve ser chamado na view, não no controller,
						 * pois os erros de validação dos elementos dos parâmetros devem ser expostos
						 * após a chamada da função $this->form_validation->run()
						 */
						
						echo params_to_html( $module_params_spec, $module_final_params_values );
						
						?>
						
					</fieldset>
					
				</div>
				
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
	
	makeTabs( $( '.tabs-wrapper' ), '.form-item', 'legend' );
	
	/**************** Criando as tabs ****************/
	/*************************************************/
	
});

</script>

<?php } ?>
