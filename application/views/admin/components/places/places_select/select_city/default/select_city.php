		
		
		<header>
			<h1><?php echo lang('select_city'); ?> - <?php echo lang($state->title); ?> - <?php echo lang($country->title); ?></h1>
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
					<th class="op-column">
						<?php echo lang('operations'); ?>
					</th>
				</tr>
				
				<?php foreach($cities as $city): ?>
				<tr>
					<td class="city-id ta-center">
						<?php echo $city->id; ?>
					</td>
					<td class="city-title">
						<?php echo lang($city->title); ?>
					</td>
					<td class="operations">
						<?php echo form_open(get_url('admin'.$this->uri->ruri_string())); ?>
						<?php echo form_hidden('city_id',$city->id); ?>
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