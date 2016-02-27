		
		
		<header>
			<h1><?php echo lang('public_areas'); ?> - <?php echo lang($neighborhood->title); ?> - <?php echo lang($city->title); ?> - <?php echo lang($state->title); ?> - <?php echo lang($country->title); ?></h1>
		</header>
		
		<div>
			
			<?php if ( $pagination ){ ?>
			<div class="pagination">
				<?php echo $pagination; ?>
			</div>
			<?php } ?>
			
			<table>
				<tr>
					<th>
						<?php echo lang('id'); ?>
					</th>
					<th>
						<?php echo lang('title'); ?>
					</th>
					<th>
						<?php echo lang('postal_code'); ?>
					</th>
					<th class="op-column">
						<?php echo lang('operations'); ?>
					</th>
				</tr>
				
				<?php foreach($public_areas as $public_area): ?>
				<tr>
					<td class="public_area-id ta-center">
						<?php echo $public_area->id; ?>
					</td>
					<td class="public_area-title">
						<?php echo $public_area->title; ?>
					</td>
					<td class="postal-code ta-center">
						<?php echo $public_area->postal_code; ?>
					</td>
					<td class="operations">
						<?php echo form_open(get_url('admin'.$this->uri->ruri_string())); ?>
						<?php echo form_hidden('public_area_id',$public_area->id); ?>
						<?php echo form_submit(array('id'=>'submit-select','name'=>'submit_select','class'=>'button button-select'),lang('select')); ?>
						<?php echo form_close(); ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</table>
			
			<?php echo form_open(get_url('admin'.$this->uri->ruri_string())); ?>
			
			<div class="form-actions">
				
				<?php echo form_submit(array('id'=>'submit-ok','class'=>'button button-ok','name'=>'submit_ok'),lang('action_ok')); ?>
				<?php echo form_submit(array('id'=>'submit-cancel','class'=>'button button-cancel','name'=>'submit_cancel'),lang('action_cancel')); ?>
				
			</div>
			
			<?php echo form_close(); ?>
			
			<?php if ( $pagination ){ ?>
			<div class="pagination">
				<?php echo $pagination; ?>
			</div>
			<?php } ?>
			
		</div>