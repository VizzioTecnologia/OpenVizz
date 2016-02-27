		
		
		<header>
			<h1><?php echo lang('search'); ?></h1>
		</header>
		
		<?php if ( isset($public_areas) AND $public_areas ){ ?>
		<div>
			
			<?php if ( $pagination ){ ?>
			<div class="pagination">
				<?php echo $pagination; ?>
			</div>
			<?php } ?>
			
			<table>
				<tr>
					<th>
						<?php echo lang('postal_code'); ?>
					</th>
					<th>
						<?php echo lang('neighborhood'); ?>
					</th>
					<th>
						<?php echo lang('public_area'); ?>
					</th>
					<th>
						<?php echo lang('city'); ?>
					</th>
					<th>
						<?php echo lang('state'); ?>
					</th>
					<th>
						<?php echo lang('country'); ?>
					</th>
					<th class="op-column">
						<?php echo lang('operations'); ?>
					</th>
				</tr>
				
				<?php foreach($public_areas as $public_area): ?>
				<tr>
					<td class="country-id ta-center">
						<?php echo $public_area->postal_code; ?>
					</td>
					<td class="country-id ta-center">
						<?php echo $public_area->title; ?>
					</td>
					<td class="country-id ta-center">
						<?php echo $public_area->neighborhood_title; ?>
					</td>
					<td class="country-id ta-center">
						<?php echo $public_area->city_title; ?>
					</td>
					<td class="country-id ta-center">
						<?php echo $public_area->state_title; ?>
					</td>
					<td class="country-id ta-center">
						<?php echo lang($public_area->country_title); ?>
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
		<?php } else if ( ( ! isset($public_areas) OR ! $public_areas ) AND $this->input->post() ){ ?>
		
		<?php echo lang('no_results'); ?>
		
		<?php } else { ?>
		
		<?php echo lang('perform_search'); ?>
		
		<?php }  ?>
		