		
		<header>
			
			<h1>
				
				<?php if ( $component_function_action == 'au' ) { ?>
				<?= lang('new_url'); ?>
				<?php } else if ( $component_function_action == 'eu' ) { ?>
				<?= lang('edit_url'); ?>
				<?php } ?>
				
			</h1>
			
		</header>
		
		<div>
			
			<?= form_open_multipart( get_url( 'admin'.$this->uri->ruri_string() ), array( 'id' => 'url-form', ) ); ?>
				
				<div class="form-actions to-toolbar">
					
					<?= vui_el_button( array( 'text' => lang( 'action_save' ), 'icon' => 'save', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit', 'id' => 'submit', 'only_icon' => TRUE, 'form' => 'url-form', ) ); ?>
					
					<?= vui_el_button( array( 'text' => lang( 'action_apply' ), 'icon' => 'apply', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_apply', 'id' => 'submit-apply', 'only_icon' => TRUE, 'form' => 'url-form', ) ); ?>
					
					<?= vui_el_button( array( 'text' => lang( 'action_cancel' ), 'icon' => 'cancel', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_cancel', 'id' => 'submit-cancel', 'only_icon' => TRUE, 'form' => 'url-form', ) ); ?>
					
				</div>
				
				<table class="table-form">
					
					<tr class="table-form-row">
						
						<td class="table-form-content">
							
							<fieldset>
								
								<legend><?= lang('configuration'); ?></legend>
								
								<div id="title-container" class="vui-field-wrapper-inline">
									
									<?= form_error( 'sef_url', '<div class="msg-inline-error">', '</div>' ); ?>
									<?= form_label( lang( 'sef_url' ) ); ?>
									<?= form_input( array( 'id'=>'sef-url', 'name'=>'sef_url' ), set_value( 'sef_url', @$url[ 'sef_url' ] ),'autofocus' ); ?>
									
								</div>
								
								<div id="alias-container" class="vui-field-wrapper-inline">
									
									<?= form_error( 'target', '<div class="msg-inline-error">', '</div>' ); ?>
									<?= form_label( lang( 'target' ) ); ?>
									<?= form_input( array( 'id'=>'target', 'name'=>'target' ), set_value('target', @$url[ 'target' ] ) ); ?>
									
								</div>
								
								<div class="divisor-h"></div>
								
							</fieldset>
							
						</td>
						
					</tr>
					
				</table>
				
				<?= form_hidden('url_id', @$url[ 'id' ]); ?>
				
			<?= form_close(); ?>
			
		</div>

