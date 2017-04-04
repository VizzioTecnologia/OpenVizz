
<div id="global-config-form-wrapper" class="form-wrapper tabs-wrapper">
	
	<div class="form-wrapper-sub tabs-children">
		
		<?= form_open( get_url( 'admin'.$this->uri->ruri_string() ), array( 'id' => 'menu-item-form', 'class' => ( ( $component_function_action == 'emi' ) ? 'ajax' : '' ) ) ); ?>
			
			<div class="form-actions to-toolbar to-main-toolbar">
				
				<?= vui_el_button( array( 'text' => lang( 'action_save' ), 'icon' => 'save', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit', 'id' => 'submit', 'only_icon' => TRUE, 'form' => 'menu-item-form', ) ); ?>
				
				<?= vui_el_button( array( 'text' => lang( 'action_apply' ), 'icon' => 'apply', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_apply', 'id' => 'submit-apply', 'only_icon' => TRUE, 'form' => 'menu-item-form', ) ); ?>
				
				<?= vui_el_button( array( 'text' => lang( 'action_cancel' ), 'icon' => 'cancel', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_cancel', 'id' => 'submit-cancel', 'only_icon' => TRUE, 'form' => 'menu-item-form', ) ); ?>
				
			</div>
			
			<header class="form-header tabs-header">
				
				<h1>
					
					<?php if ( $component_function_action == 'ami' ) { ?>
						
					<?= lang('new_menu_item'); ?>
					
					<?php } else if ( $component_function_action == 'emi' ) { ?>
						
					<?= lang('edit_menu_item'); ?>
					
					<?php } ?> - <?= lang( $type ); ?>
					
				</h1>
				
			</header>
			
			<div class="form-items tabs-items">
				
				<div class="form-item">
					
					<fieldset>
						
						<legend>
							
							<?= vui_el_button( array( 'text' => lang( 'basic_details' ), 'icon' => 'basic-details',  ) ); ?>
							
						</legend>
						
						<div id="title" class="vui-field-wrapper-inline">
							
							<?= form_error('title', '<div class="msg-inline-error">', '</div>'); ?>
							<?= form_label(lang('title')); ?>
							<?= form_input( array( 'id'=>'title', 'name'=>'title' ), set_value( 'title', @$menu_item[ 'title' ] ),'autofocus' ); ?>
							
						</div>
						
						<div id="alias" class="vui-field-wrapper-inline">
							
							<?= form_error('alias', '<div class="msg-inline-error">', '</div>'); ?>
							<?= form_label(lang('alias')); ?>
							<?= form_input( array( 'id'=>'alias', 'name'=>'alias' ), set_value('alias', @$menu_item[ 'alias' ] ) ); ?>
							
						</div>
						
						<div id="status" class="vui-field-wrapper-inline">
							
							<?= form_error('status', '<div class="msg-inline-error">', '</div>'); ?>
							<?= form_label(lang('status')); ?>
							<?php
								$options = array(
									
									0 => lang('unpublished'),
									1 => lang('published'),
									
								);
							?>
							<?= form_dropdown('status', $options, set_value('status', @$menu_item[ 'status' ] ),'id="status"'); ?>
							
						</div>
						
						<div id="parent" class="vui-field-wrapper-inline">
							
							<?= form_error('parent', '<div class="msg-inline-error">', '</div>'); ?>
							<?= form_label(lang('parent_menu_item')); ?>
							<?php
								$options = array(
									0=>lang('root'),
								);
								
								if ( $menu_items ) {
									
									foreach( $menu_items as $row ) {
										
										if ( $row['id'] != @$menu_item[ 'id' ] ){
											
											$options[$row['id']] = $row['indented_title'];
											
										}
									
									}
									
								}
								
							?>
							<?= form_dropdown('parent', $options, set_value('parent', @$menu_item[ 'parent' ]),'id="parent-menu-item"'); ?>
							
						</div>
						
						<div id="link" class="vui-field-wrapper-inline">
							
							<?= form_error('link', '<div class="msg-inline-error">', '</div>'); ?>
							<?= form_label( lang( 'link' ) ); ?>
							
							<?php if ( $menu_item_link_disabled ) { ?>
							<?= form_input( array( 'id'=>'link', 'name'=>'link', 'disabled'=>'disabled' ), set_value( 'link', @$menu_item[ 'link' ] ) ); ?>
							<?php } else { ?>
							<?= form_input( array( 'id'=>'link', 'name'=>'link' ), set_value( 'link', @$menu_item[ 'link' ] ) ); ?>
							<?php } ?>
							
						</div>
						
						<div class="divisor-h"></div>
						
						<div id="description" class="field-wrapper">
							
							<?php if ( @$target_component ) echo form_hidden( 'component_id', $target_component[ 'id' ] ); ?>
							<?= form_hidden( 'menu_type_id', $menu_type_id ); ?>
							
							<?= form_error( 'description', '<div class="msg-inline-error">', '</div>' ); ?>
							<?= form_label(lang('description')); ?>
							<?= form_textarea(array('id'=>'description', 'name'=>'description', 'class'=>'js-editor'),set_value('description', @$menu_item[ 'description' ])); ?>
							
						</div>
							
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
									
									if ( $component_function_action == 'emi' ) {
										
										$options[ 'checked' ] = ( $this->input->post( 'access_type' ) == 'public' ) ? TRUE : ( ( ! $this->input->post( 'access_type' ) AND $menu_item[ 'access_type' ] == 'public' ) ? TRUE : FALSE );
										
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
										
										if ( $component_function_action == 'emi' ) {
											
											$options[ 'checked' ] = ( $this->input->post('access_type') == 'users' ) ? TRUE : ( ( ! $this->input->post( 'access_type' ) AND $menu_item[ 'access_type' ] == 'users' ) ? TRUE : FALSE );
											
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
									
									if ( $component_function_action == 'emi' ) {
										
										$options[ 'checked' ] = ( $this->input->post( 'access_type' ) === 'users' AND $post_access_user_id AND in_array( html_escape( '>' . $user['id'] . '<' ), $post_access_user_id ) ) ? TRUE : ( ( ! $this->input->post('access_type') AND $menu_item[ 'access_type' ] == 'users' AND in_array( '>'.$user['id'].'<', $menu_item[ 'access_user_id' ] ) ) ? TRUE : FALSE );
										
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
										
										if ( $component_function_action == 'emi' ) {
											
											$options[ 'checked' ] = ( $this->input->post( 'access_type' ) == 'users_groups' ) ? TRUE: ( ( ! $this->input->post( 'access_type' ) AND $menu_item[ 'access_type' ] == 'users_groups' ) ? TRUE : FALSE );
											
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
										
										if ( $component_function_action == 'emi' ) {
											
											$options[ 'checked' ] = ( $this->input->post( 'access_type' ) === 'users_groups' AND $post_access_user_group_id AND in_array( html_escape( '>' . $user_group['id'] . '<' ), $post_access_user_group_id ) ) ? TRUE : ( ( ! $this->input->post('access_type') AND $menu_item[ 'access_type' ] == 'users_groups' AND in_array( '>'.$user_group['id'].'<', $menu_item[ 'access_user_group_id' ] ) ) ? TRUE : FALSE );
											
										}
										
										echo vui_el_checkbox( $options );
										
									};
									
									?>
									
								</label>
								
						</div>
						
					</fieldset>
					
				</div>
				
				<?php if ( $type == 'html_content' ) { ?>
				
				<div class="form-item">
					
					<fieldset>
						
						<legend>
							
							<?= vui_el_button( array( 'text' => lang( 'html_content' ), 'icon' => 'html-content',  ) ); ?>
							
						</legend>
						
						<div id="description" class="field-wrapper">
							
							<?= form_error( 'html_content', '<div class="msg-inline-error">', '</div>' ); ?>
							<?= form_label( lang( 'html_content' ) ); ?>
							<?= form_textarea( array( 'id' => 'html_content', 'name' => 'html_content', 'class' => 'js-editor' ), set_value( 'html_content', @$menu_item[ 'html_content' ] ) ); ?>
							
						</div>
							
					</fieldset>
					
				</div>
				
				<?php }
				
					/* gerando o html dos parâmetros, ele deve ser chamado na view, não no controller,
					* pois os erros de validação dos elementos dos parâmetros devem ser expostos
					* após a chamada da função $this->form_validation->run()
					*/
					
					echo params_to_html( $params_spec, $params_values );
					
				?>
				
			</div>
			
			<?= form_hidden( 'menu_item_id', @$menu_item[ 'id' ] ); ?>
			
		<?= form_close(); ?>
		
	</div>
	
</div>

<?php
	
	if ( $this->plugins->load( 'yetii' ) ){ ?>

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

