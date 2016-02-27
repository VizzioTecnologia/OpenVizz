
		<div>

			<header class="component-head">

				<h1>

					<?= lang('neighborhoods'); ?> - <?= lang($city->title); ?> - <?= lang($state->title); ?> - <?= lang($country->title); ?>

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

				<?php foreach($neighborhoods as $neighborhood): ?>
				<tr>
					<td class="neighborhood-id ta-center">
						<?= $neighborhood->id; ?>
					</td>
					<td class="neighborhood-title">
						<?= anchor('admin/'.$component_name.'/' . $component_function . '/public_areas_list/'.$country->id . '/' . $state->id . '/' . $city->id . '/' . $neighborhood->id,$neighborhood->title,'class="" title="'.lang('action_view').'"'); ?>
					</td>
					<td class="city-title ta-center">
						<?= lang($neighborhood->city_title); ?>
					</td>
					<td class="state-title ta-center">
						<?= lang($neighborhood->state_title); ?>
					</td>
					<td class="country-title ta-center">
						<?= lang($neighborhood->country_title); ?>
					</td>
					<td class="operations">

						<?= vui_el_button( array( 'url' => 'https://maps.google.com/maps?q=' . $neighborhood->title . '+' . $neighborhood->city_title . '+' . $neighborhood->state_title . '+' . lang( $neighborhood->country_title ), 'text' => lang( 'action_view_on_map' ), 'target' => '_blank', 'icon' => 'map', 'only_icon' => TRUE, 'get_url' => FALSE, ) ); ?>

						<?= vui_el_button( array( 'url' => 'admin/' . $component_name . '/' . $component_function . '/edit_neighborhood/' . $country->id . '/' . $state->id . '/' . $city->id . '/' . $neighborhood->id, 'text' => lang( 'action_edit' ), 'icon' => 'edit', 'only_icon' => TRUE, ) ); ?>

						<?= vui_el_button( array( 'url' => 'admin/' . $component_name . '/' . $component_function . '/remove_neighborhood/' . $country->id . '/' . $state->id . '/' . $city->id . '/' . $neighborhood->id, 'text' => lang( 'action_delete' ), 'icon' => 'remove', 'only_icon' => TRUE, ) ); ?>

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