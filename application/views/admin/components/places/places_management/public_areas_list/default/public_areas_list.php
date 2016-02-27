		
		<div>
			
			<header class="component-head">
				
				<h1>
					
					<?= lang('public_areas'); ?> - <?= lang($neighborhood->title); ?> - <?= lang($city->title); ?> - <?= lang($state->title); ?> - <?= lang($country->title); ?>
					
				</h1>
				
			</header>
			
			<?php if ( $pagination ){ ?>
			<div class="pagination">
				<?= $pagination; ?>
			</div>
			<?php } ?>
			
			<table>
				<tr>
					<th>
						<?= lang('id'); ?>
					</th>
					<th>
						<?= lang('title'); ?>
					</th>
					<th>
						<?= lang('postal_code'); ?>
					</th>
					<th>
						<?= lang('neighborhood'); ?>
					</th>
					<th>
						<?= lang('city'); ?>
					</th>
					<th>
						<?= lang('state'); ?>
					</th>
					<th>
						<?= lang('country'); ?>
					</th>
					<th class="op-column">
						<?= lang('operations'); ?>
					</th>
				</tr>
				
				<?php foreach($public_areas as $public_area): ?>
				<tr>
					<td class="public_area-id ta-center">
						<?= $public_area->id; ?>
					</td>
					<td class="public_area-title">
						<?= anchor('admin/'.$component_name.'/' . $component_function . '/edit_public_area/'.$country->id . '/' . $state->id . '/' . $city->id . '/' . $neighborhood->id . '/' . $public_area->id,$public_area->title,'class="" title="'.lang('action_view').'"'); ?>
					</td>
					<td class="postal-code ta-center">
						<?= $public_area->postal_code; ?>
					</td>
					<td class="neighborhood-title ta-center">
						<?= $public_area->neighborhood_title; ?>
					</td>
					<td class="city-title ta-center">
						<?= $public_area->city_title; ?>
					</td>
					<td class="state-title ta-center">
						<?= $public_area->state_title; ?>
					</td>
					<td class="country-title ta-center">
						<?= lang($public_area->country_title); ?>
					</td>
					<td class="operations">
						
						<?php
							
							if ( $public_area->map_url ){
								
								$map_url = $public_area->map_url;
								
							}
							else if ( $public_area->coordinates ) {
								
								$map_url = 'https://maps.google.com/maps?q=' . $public_area->coordinates;
								
							}
							else {
								
								$map_url = 'https://maps.google.com/maps?q=' . $public_area->postal_code.'+' . $public_area->title . '+' . $public_area->neighborhood_title . '+' . $public_area->city_title . '+' . $public_area->state_title . '+' . lang( $public_area->country_title );
								
							}
							
						?>
						
						<?= vui_el_button( array( 'url' => $map_url, 'text' => lang( 'action_view_on_map' ), 'target' => '_blank', 'icon' => 'map', 'only_icon' => TRUE, 'get_url' => FALSE, ) ); ?>
						
						<?= vui_el_button( array( 'url' => 'admin/' . $component_name . '/' . $component_function . '/edit_public_area/' . $country->id . '/' . $state->id . '/' . $city->id . '/' . $neighborhood->id . '/' . $public_area->id, 'text' => lang( 'action_edit' ), 'icon' => 'edit', 'only_icon' => TRUE, ) ); ?>
						
						<?= vui_el_button( array( 'url' => 'admin/' . $component_name . '/' . $component_function . '/remove_public_area/' . $country->id . '/' . $state->id . '/' . $city->id . '/' . $neighborhood->id . '/' . $public_area->id, 'text' => lang( 'action_delete' ), 'icon' => 'remove', 'only_icon' => TRUE, ) ); ?>
						
					</td>
				</tr>
				<?php endforeach; ?>
			</table>
			
			<?php if ( $pagination ){ ?>
			<div class="pagination">
				<?= $pagination; ?>
			</div>
			<?php } ?>
			
		</div>