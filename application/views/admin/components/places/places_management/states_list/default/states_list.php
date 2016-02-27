		
		<div>
			
			<header class="component-head">
				
				<h1>
					
					<?= lang('states'); ?> - <?= lang($country->title); ?>
					
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
						<?= lang('acronym'); ?>
					</th>
					<th>
						<?= lang('status'); ?>
					</th>
					<th>
						<?= lang('country'); ?>
					</th>
					<th class="op-column">
						<?= lang('operations'); ?>
					</th>
				</tr>
				
				<?php foreach($states as $state): ?>
				<tr>
					<td class="state-id ta-center">
						<?= $state->id; ?>
					</td>
					<td class="state-title">
						<?= anchor('admin/'.$component_name.'/' . $component_function . '/cities_list/'.$country->id . '/' . $state->id,lang($state->title),'class="" title="'.lang('action_view').'"'); ?>
					</td>
					<td class="state-acronym ta-center">
						<?= $state->acronym; ?>
					</td>
					<td class="status">
						
						<?=
							
							vui_el_button( array(
							
								'url' => 'admin/' . $component_name . '/' . $component_function . '/' . ( $state->status == 0 ? 'fetch_publish_state' : 'fetch_unpublish_state' ) . '/' . $state->id,
								'text' => lang( $state->status == 0 ? 'unpublished' : 'published' ),
								'icon' => ( $state->status == 0 ? 'cancel unpublished' : 'ok published' ),
								'only_icon' => TRUE, )
								
							);
							
						?>
						
					</td>
					<td class="country-title ta-center">
						<?= lang($state->country_title); ?>
					</td>
					<td class="operations">
						
						<?= vui_el_button( array( 'url' => 'https://maps.google.com/maps?q=' . $state->title . '+' . lang( $state->country_title ), 'text' => lang( 'action_view_on_map' ), 'target' => '_blank', 'icon' => 'map', 'only_icon' => TRUE, 'get_url' => FALSE, ) ); ?>
						
						<?= vui_el_button( array( 'url' => 'admin/' . $component_name . '/' . $component_function . '/edit_state/' . $country->id . '/' . $state->id, 'text' => lang( 'action_edit' ), 'icon' => 'edit', 'only_icon' => TRUE, ) ); ?>
						
						<?= vui_el_button( array( 'url' => 'admin/' . $component_name . '/' . $component_function . '/remove_state/' . $country->id . '/' . $state->id, 'text' => lang( 'action_delete' ), 'icon' => 'remove', 'only_icon' => TRUE, ) ); ?>
						
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