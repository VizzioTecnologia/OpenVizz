<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
	
	$is_current_user = FALSE;
	
	if ( $f_action == 'e' ) {
		
		$is_current_user = $user[ 'id' ] == $this->users->user_data[ 'id' ];
		
	}
	
?>

<div id="user-form-wrapper" class="form-wrapper tabs-wrapper">
	
	<div class="form-wrapper-sub tabs-children">
		
		<?= form_open( get_url( 'admin' . $this->uri->ruri_string() ), array( 'id' => 'user-form', ) ); ?>
			
			<div class="form-actions to-toolbar to-main-toolbar">
				
				<?= vui_el_button( array( 'text' => lang( 'action_save' ), 'icon' => 'save', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit', 'id' => 'submit', 'only_icon' => TRUE, 'form' => 'user-form', ) ); ?>
				
				<?= vui_el_button( array( 'text' => lang( 'action_apply' ), 'icon' => 'apply', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_apply', 'id' => 'submit-apply', 'only_icon' => TRUE, 'form' => 'user-form', ) ); ?>
				
				<?= vui_el_button( array( 'text' => lang( 'action_cancel' ), 'icon' => 'cancel', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_cancel', 'id' => 'submit-cancel', 'only_icon' => TRUE, 'form' => 'user-form', ) ); ?>
				
			</div>
			
			<header class="form-header tabs-header">
				
				<?php if ( $f_action == 'a' ) { ?>
				
				<h1>
					
					<?= lang( 'add_user' ); ?>
					
				</h1>
				
				<?php } else if ( $f_action == 'e' ) { ?>
				
				<h1>
					
					<?php
						
						if ( $is_current_user ) {
							
							echo form_label( lang( 'my_account' ) );
							
						}
						else {
							
							echo form_label( lang( 'edit_user' ) );
							
						}
						
					?>
					
				</h1>
				
				<?php } ?>
				
			</header>
			
			<div class="form-items tabs-items">
				
				<div class="form-item">
					
					<fieldset id="user-details">
						
						<legend>
							
							<?= vui_el_button( array( 'text' => lang( 'basic_details' ), 'icon' => 'basic-details',  ) ); ?>
							
						</legend>
						
						<?php if ( $f_action == 'e' ) { ?>
						
						<div id="id" class="vui-field-wrapper-inline">
							
							<?php
								
								$field_name = 'id';
								$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
								
								if ( $is_current_user ) {
									
									echo form_label( lang( 'set_user_own_' . $field_name ) );
									
								}
								else {
									
									echo form_label( lang( 'set_user_' . $field_name ) );
									
								}
								
								$vui_el_params = array(
									
									'text' => $field_error ? element_title( $field_error ) : ( $is_current_user ? lang( 'tip_set_user_own_' . $field_name ) : lang( 'tip_set_user_' . $field_name ) ),
									'title' => $field_error ? element_title( $field_error ) : ( $is_current_user ? lang( 'tip_set_user_own_' . $field_name ) : lang( 'tip_set_user_' . $field_name ) ),
									'name' => $field_name,
									'value' => set_value( $field_name, @$user[ $field_name ] ),
									'class' => $field_name . ' ' . ( $field_error ? 'field-error' : '' ),
									'id' => 'user-' . $field_name . 'el',
									
								);
								
								if ( $f_action == 'e' AND
									( ( $user[ 'id' ] != $this->users->user_data[ 'id' ] AND ! $this->users->check_privileges( 'users_management_can_change_id_others' ) )
									OR ( $user[ 'id' ] == $this->users->user_data[ 'id' ] AND ! $this->users->check_privileges( 'users_management_can_change_own_id' ) ) )
									) {
									
									$vui_el_params[ 'attr' ][ 'disabled' ] = 'disabled';
									
								};
								
								echo vui_el_input_text( $vui_el_params );
								
								unset( $field_error );
								
							?>
							
						</div>
						
						<?php } ?>
						
						<div id="username" class="vui-field-wrapper-inline">
							
							<?php
								
								$field_name = 'username';
								$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
								
								if ( $is_current_user ) {
									
									echo form_label( lang( 'set_user_own_' . $field_name ) );
									
								}
								else {
									
									echo form_label( lang( 'set_user_' . $field_name ) );
									
								}
								
								$vui_el_params = array(
									
									'text' => $field_error ? element_title( $field_error ) : ( $is_current_user ? lang( 'tip_set_user_own_' . $field_name ) : lang( 'tip_set_user_' . $field_name ) ),
									'title' => $field_error ? element_title( $field_error ) : ( $is_current_user ? lang( 'tip_set_user_own_' . $field_name ) : lang( 'tip_set_user_' . $field_name ) ),
									'name' => $field_name,
									'value' => set_value( $field_name, @$user[ $field_name ] ),
									'class' => $field_name . ' ' . ( $field_error ? 'field-error' : '' ),
									'id' => 'user-' . $field_name . 'el',
									'autofocus' => TRUE,
									
								);
								
								if ( $f_action == 'e' AND
									( ( $user[ 'id' ] != $this->users->user_data[ 'id' ] AND ! $this->users->check_privileges( 'users_management_can_change_username_others' ) )
									OR ( $user[ 'id' ] == $this->users->user_data[ 'id' ] AND ! $this->users->check_privileges( 'users_management_can_change_own_username' ) ) )
									) {
									
									$vui_el_params[ 'attr' ][ 'disabled' ] = 'disabled';
									
								};
								
								echo vui_el_input_text( $vui_el_params );
								
								unset( $field_error );
								
							?>
							
						</div>
						
						<div id="name" class="vui-field-wrapper-inline">
							
							<?php
								
								$field_name = 'name';
								$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
								
								if ( $is_current_user ) {
									
									echo form_label( lang( 'set_user_own_' . $field_name ) );
									
								}
								else {
									
									echo form_label( lang( 'set_user_' . $field_name ) );
									
								}
								
								$vui_el_params = array(
									
									'text' => $field_error ? element_title( $field_error ) : ( $is_current_user ? lang( 'tip_set_user_own_' . $field_name ) : lang( 'tip_set_user_' . $field_name ) ),
									'title' => $field_error ? element_title( $field_error ) : ( $is_current_user ? lang( 'tip_set_user_own_' . $field_name ) : lang( 'tip_set_user_' . $field_name ) ),
									'name' => $field_name,
									'value' => set_value( $field_name, @$user[ $field_name ] ),
									'class' => $field_name . ' ' . ( $field_error ? 'field-error' : '' ),
									'id' => 'user-' . $field_name . 'el',
									
								);
								
								if ( $f_action == 'e' AND
									( ( $user[ 'id' ] != $this->users->user_data[ 'id' ] AND ! $this->users->check_privileges( 'users_management_can_change_name_others' ) )
									OR ( $user[ 'id' ] == $this->users->user_data[ 'id' ] AND ! $this->users->check_privileges( 'users_management_can_change_own_name' ) ) )
									) {
									
									$vui_el_params[ 'attr' ][ 'disabled' ] = 'disabled';
									
								};
								
								echo vui_el_input_text( $vui_el_params );
								
								unset( $field_error );
								
							?>
							
						</div>
						
						<div id="email" class="vui-field-wrapper-inline">
							
							<?php
								
								$field_name = 'email';
								$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
								
								if ( $is_current_user ) {
									
									echo form_label( lang( 'set_user_own_' . $field_name ) );
									
								}
								else {
									
									echo form_label( lang( 'set_user_' . $field_name ) );
									
								}
								
								$vui_el_params = array(
									
									'text' => $field_error ? element_title( $field_error ) : ( $is_current_user ? lang( 'tip_set_user_own_' . $field_name ) : lang( 'tip_set_user_' . $field_name ) ),
									'title' => $field_error ? element_title( $field_error ) : ( $is_current_user ? lang( 'tip_set_user_own_' . $field_name ) : lang( 'tip_set_user_' . $field_name ) ),
									'name' => $field_name,
									'value' => set_value( $field_name, @$user[ $field_name ] ),
									'class' => $field_name . ' ' . ( $field_error ? 'field-error' : '' ),
									'id' => 'user-' . $field_name . 'el',
									
								);
								
								if ( $f_action == 'e' AND
									( ( $user[ 'id' ] != $this->users->user_data[ 'id' ] AND ! $this->users->check_privileges( 'users_management_can_change_email_others' ) )
									OR ( $user[ 'id' ] == $this->users->user_data[ 'id' ] AND ! $this->users->check_privileges( 'users_management_can_change_own_email' ) ) )
									) {
									
									$vui_el_params[ 'attr' ][ 'disabled' ] = 'disabled';
									
								};
								
								echo vui_el_input_text( $vui_el_params );
								
								unset( $field_error );
								
							?>
							
						</div>
						
						<?php if ( check_var( $users_groups ) ) { ?>
						
						<div id="group-id" class="vui-field-wrapper-inline">
							
							<?php
								
								$field_name = 'group_id';
								$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
								
								echo form_label( lang( 'set_user_' . $field_name ) );
								
								$vui_el_params = array(
									
									'text' => $field_error ? element_title( $field_error ) : lang( 'tip_set_user_' . $field_name ),
									'title' => $field_error ? element_title( $field_error ) : lang( 'tip_set_user_' . $field_name ),
									'name' => $field_name,
									'value' => set_value( $field_name, @$user[ $field_name ] ),
									'class' => $field_name . ' ' . ( $field_error ? 'field-error' : '' ),
									'id' => 'user-' . $field_name . 'el',
									
								);
								
								$vui_el_params[ 'options' ][ '' ] = lang( 'combobox_select' );
								
								foreach( $users_groups as $user_group ) {
									
									$vui_el_params[ 'options' ][ $user_group[ 'id' ] ] = $user_group[ 'indented_title' ];
									
								};
								
								echo vui_el_dropdown( $vui_el_params );
								
								unset( $field_error );
								
							?>
							
						</div>
						
						<?php } ?>
						
						<div id="password" class="vui-field-wrapper-inline">
							
							<?php
								
								$field_name = 'password';
								$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
								
								if ( $is_current_user ) {
									
									echo form_label( lang( 'set_user_own_' . $field_name ) );
									
								}
								else {
									
									echo form_label( lang( 'set_user_' . $field_name ) );
									
								}
								
								$vui_el_params = array(
									
									'text' => $field_error ? element_title( $field_error ) : ( $is_current_user ? lang( 'tip_set_user_own_' . $field_name ) : lang( 'tip_set_user_' . $field_name ) ),
									'title' => $field_error ? element_title( $field_error ) : ( $is_current_user ? lang( 'tip_set_user_own_' . $field_name ) : lang( 'tip_set_user_' . $field_name ) ),
									'name' => $field_name,
									'value' => set_value( $field_name ),
									'class' => $field_name . ' ' . ( $field_error ? 'field-error' : '' ),
									'id' => 'user-' . $field_name . 'el',
									
								);
								
								if ( $f_action == 'e' AND
									( ( $user[ 'id' ] != $this->users->user_data[ 'id' ] AND ! $this->users->check_privileges( 'users_management_can_change_password_others' ) )
									OR ( $user[ 'id' ] == $this->users->user_data[ 'id' ] AND ! $this->users->check_privileges( 'users_management_can_change_own_password' ) ) )
									) {
									
									$vui_el_params[ 'attr' ][ 'disabled' ] = 'disabled';
									
								};
								
								echo vui_el_input_password( $vui_el_params );
								
								unset( $field_error );
								
							?>
							
						</div>
						
						<div id="confirm-password" class="vui-field-wrapper-inline">
							
							<?php
								
								$field_name = 'confirm_password';
								$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
								
								if ( $is_current_user ) {
									
									echo form_label( lang( 'set_user_own_' . $field_name ) );
									
								}
								else {
									
									echo form_label( lang( 'set_user_' . $field_name ) );
									
								}
								
								$vui_el_params = array(
									
									'text' => $field_error ? element_title( $field_error ) : ( $is_current_user ? lang( 'tip_set_user_own_' . $field_name ) : lang( 'tip_set_user_' . $field_name ) ),
									'title' => $field_error ? element_title( $field_error ) : ( $is_current_user ? lang( 'tip_set_user_own_' . $field_name ) : lang( 'tip_set_user_' . $field_name ) ),
									'name' => $field_name,
									'value' => set_value( $field_name, @$user[ $field_name ] ),
									'class' => $field_name . ' ' . ( $field_error ? 'field-error' : '' ),
									'id' => 'user-' . $field_name . 'el',
									
								);
								
								if ( $f_action == 'e' AND
									( ( $user[ 'id' ] != $this->users->user_data[ 'id' ] AND ! $this->users->check_privileges( 'users_management_can_change_password_others' ) )
									OR ( $user[ 'id' ] == $this->users->user_data[ 'id' ] AND ! $this->users->check_privileges( 'users_management_can_change_own_password' ) ) )
									) {
									
									$vui_el_params[ 'attr' ][ 'disabled' ] = 'disabled';
									
								};
								
								echo vui_el_input_password( $vui_el_params );
								
								unset( $field_error );
								
							?>
							
						</div>
						
					</fieldset>
					
					<fieldset id="user-params">
						
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
			
		<?= form_close(); ?>
		
	</div>
	
</div>

<?php if ( $this->plugins->load( 'yetii' ) ){ ?>

<script type="text/javascript" >
	
$( document ).ready(function(){
	
	/*************************************************/
	/**************** Criando as tabs ****************/
	
	makeTabs( $( '.tabs-wrapper' ), '#user-details, .params-set-wrapper', 'legend, .params-set-title' );
	
	/**************** Criando as tabs ****************/
	/*************************************************/
	
});

</script>

<?php } ?>
