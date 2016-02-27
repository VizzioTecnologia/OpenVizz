
		<header>
			<h1><?php echo lang('new_menu_item'); ?></h1>
		</header>
		
		<div>
			
			<?= form_open( get_url( 'admin'.$this->uri->ruri_string() ), array( 'id' => 'menu-item-form', ) ); ?>
				
				<div class="form-actions to-toolbar">
					
					<?= vui_el_button( array( 'text' => lang( 'action_save' ), 'icon' => 'save', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit', 'id' => 'submit', 'only_icon' => TRUE, 'form' => 'menu-item-form', ) ); ?>
					
					<?= vui_el_button( array( 'text' => lang( 'action_apply' ), 'icon' => 'apply', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_apply', 'id' => 'submit-apply', 'only_icon' => TRUE, 'form' => 'menu-item-form', ) ); ?>
					
					<?= vui_el_button( array( 'text' => lang( 'action_cancel' ), 'icon' => 'cancel', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_cancel', 'id' => 'submit-cancel', 'only_icon' => TRUE, 'form' => 'menu-item-form', ) ); ?>
					
				</div>
				
				<fieldset>
					
					<legend><?php echo lang('configuration'); ?></legend>
					
					<?php echo form_error('status', '<div class="msg-inline-error">', '</div>'); ?>
					<?php echo form_label(lang('status')); ?>
					<?php
						$options = array(
							0=>lang('disabled'),
							1=>lang('enabled'),
						);
					?>
					<?php echo form_dropdown('status', $options, set_value('status', 1),'id="status"'); ?>
					
					<?php echo form_error('title', '<div class="msg-inline-error">', '</div>'); ?>
					<?php echo form_label(lang('title')); ?>
					<?php echo form_input(array('id'=>'title','name'=>'title'),set_value('title'),'autofocus'); ?>
					
					<?php echo form_error('alias', '<div class="msg-inline-error">', '</div>'); ?>
					<?php echo form_label(lang('alias')); ?>
					<?php echo form_input(array('id'=>'alias','name'=>'alias'),set_value('alias')); ?>
					
					<?php echo form_error('parent', '<div class="msg-inline-error">', '</div>'); ?>
					<?php echo form_label(lang('parent_menu_item')); ?>
					<?php
						$options = array(
							0=>lang('root'),
						);
						foreach($menu_items as $row):
							$options[$row['id']] = $row['indented_title'];
						endforeach;
					?>
					<?php echo form_dropdown('parent', $options, set_value('parent', 0),'id="parent-menu-item"'); ?>
					
					<?php echo form_error('description', '<div class="msg-inline-error">', '</div>'); ?>
					<?php echo form_label(lang('description')); ?>
					<?php echo form_textarea(array('id'=>'description','name'=>'description'),set_value('description')); ?>
					
				</fieldset>
				
				<fieldset>
					
					<legend><?php echo lang('access'); ?></legend>
					<label>
						<input type="radio" name="access_type" value="public" class="days" <?php echo ($this->input->post('access_type') == 'public'?'checked="checked"':( ! $this->input->post('access_type')?'checked="checked"':'')); ?> />
						<?php echo lang('public'); ?>
					</label>
					
					<label>
						<span class="fake-label">
							<input type="radio" name="access_type" value="users" class="" <?php echo ($this->input->post('access_type') == 'users')?'checked="checked"':''; ?> />
							<?php echo lang('specific_users'); ?>
						</span>
					
						<?php echo form_error('access_user_id', '<div class="msg-inline-error">', '</div>'); ?>
						<?php 
						if ($this->input->post('access_user_id')){
							$post_access_user_id = $this->input->post('access_user_id');
						}
						else {
							$post_access_user_id = FALSE;
						}
						?>
						<?php foreach($users as $user){ ?>
							<label class="checkbox-sub-item" for="user-<?php echo $user['id']; ?>">
								<input id="user-<?php echo $user['id']; ?>" name="access_user_id[]" type="checkbox" value="><?php echo $user['id']; ?><" <?php echo ($this->input->post('access_type') === 'users' AND $post_access_user_id AND in_array('>'.$user['id'].'<', $post_access_user_id))?'checked="checked"':''; ?> />
								<?php echo $user['name'].' ('.$user['username'].')'; ?>
							</label>	
						<?php };
						?>
					</label>
					
					<label>
						<span class="fake-label">
							<input type="radio" name="access_type" value="users_groups" class="" <?php echo ($this->input->post('access_type') == 'users_groups')?'checked="checked"':''; ?> />
							<?php echo lang('specific_users_groups'); ?>
						</span>
					
						<?php echo form_error('access_user_group_id', '<div class="msg-inline-error">', '</div>'); ?>
						<?php 
						if ($this->input->post('access_user_group_id')){
							$post_access_user_group_id = $this->input->post('access_user_group_id');
						}
						else {
							$post_access_user_group_id = FALSE;
						}
						?>
						<?php foreach($users_groups as $user_group){ ?>
							<label class="checkbox-sub-item" for="user-group-<?php echo $user_group['id']; ?>">
								<input id="user-group-<?php echo $user_group['id']; ?>" name="access_user_group_id[]" type="checkbox" value="><?php echo $user_group['id']; ?><" <?php echo ($this->input->post('access_type') === 'users_groups' AND $post_access_user_group_id AND in_array('>'.$user_group['id'].'<', $post_access_user_group_id))?'checked="checked"':''; ?> />
								<?php echo $user_group['indented_title']; ?>
							</label>	
						<?php };
						?>
					</label>
					
				</fieldset>
				
				<fieldset>
					
					<legend><?php echo lang('parameters'); ?> - <?php echo lang('menu_item'); ?></legend>
					
					<?php //echo parse_params($menu_item_params, get_params($menu_item->params)); ?>
					
					<?php
					
					/* gerando o html dos parâmetros, ele deve ser chamado na view, não no controller,
					 * pois os erros de validação dos elementos dos parâmetros devem ser expostos
					 * após a chamada da função $this->form_validation->run()
					 */
					
					echo params_to_html( $menu_item_params_spec, $menu_item_final_params_values );
					
					?>
					
				</fieldset>
				
				<fieldset>
					
					<legend><?php echo lang('parameters'); ?> - <?php echo lang($target_component_item->title); ?></legend>
					
					<?php //echo parse_params($params, get_params($menu_item->params)); ?>
					
					<?php
					
					/* gerando o html dos parâmetros, ele deve ser chamado na view, não no controller,
					 * pois os erros de validação dos elementos dos parâmetros devem ser expostos
					 * após a chamada da função $this->form_validation->run()
					 */
					
					echo params_to_html( $component_params_spec, $component_final_params_values );
					
					?>
					
				</fieldset>
				
				<?php echo form_hidden('component_id',$target_component_item->component_id); ?>
				<?php echo form_hidden('component_item_id',$target_component_item->id); ?>
				<?php echo form_hidden('menu_type_id',$menu_type_id); ?>
				
			<?php echo form_close(); ?>
			
		</div>

