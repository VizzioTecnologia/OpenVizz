		
		<div>
			
			<header class="component-head">
				
				<h1>
					
					<?= lang( 'cities' ); ?> - <?= lang( $state->title ); ?> - <?= lang( $country->title ); ?>
					
				</h1>
				
			</header>
			
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
						<?php echo lang('state'); ?>
					</th>
					<th>
						<?php echo lang('country'); ?>
					</th>
					<th class="op-column">
						<?php echo lang('operations'); ?>
					</th>
				</tr>
				
				<?php foreach($cities as $city): ?>
				<tr>
					<td class="state-id ta-center">
						<?php echo $city->id; ?>
					</td>
					<td class="state-title">
						<?php echo anchor('admin/'.$component_name.'/' . $component_function . '/neighborhoods_list/'.$country->id . '/' . $state->id . '/' . $city->id,lang($city->title),'class="" title="'.lang('action_view').'"'); ?>
					</td>
					<td class="country-title ta-center">
						<?php echo lang($city->state_title); ?>
					</td>
					<td class="country-title ta-center">
						<?php echo lang($city->country_title); ?>
					</td>
					<td class="operations">
						
						<?= vui_el_button( array( 'url' => 'https://maps.google.com/maps?q=' . $city->title . '+' . $city->state_title . '+' . lang( $city->country_title ), 'text' => lang( 'action_view_on_map' ), 'target' => '_blank', 'icon' => 'map', 'only_icon' => TRUE, 'get_url' => FALSE, ) ); ?>
						
						<?= vui_el_button( array( 'url' => 'admin/' . $component_name . '/' . $component_function . '/edit_state/' . $country->id . '/' . $state->id . '/' . $city->id, 'text' => lang( 'action_edit' ), 'icon' => 'edit', 'only_icon' => TRUE, ) ); ?>
						
						<?= vui_el_button( array( 'url' => 'admin/' . $component_name . '/' . $component_function . '/remove_state/' . $country->id . '/' . $state->id . '/' . $city->id, 'text' => lang( 'action_delete' ), 'icon' => 'remove', 'only_icon' => TRUE, ) ); ?>
						
					</td>
				</tr>
				<?php endforeach; ?>
			</table>
			
			<?php if ( $pagination ){ ?>
			<div class="pagination">
				<?php echo $pagination; ?>
			</div>
			<?php } ?>
			
		</div>