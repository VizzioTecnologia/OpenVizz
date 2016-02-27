		
		<div>
			
			<header class="component-head">
				
				<h1>
					
					<?= lang( 'countries' ); ?>
					
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
						<?php echo lang('alias'); ?>
					</th>
					<th>
						<?php echo lang('status'); ?>
					</th>
					<th class="op-column">
						<?php echo lang('operations'); ?>
					</th>
				</tr>
				
				<?php foreach($countries as $country): ?>
				<tr>
					<td class="country-id ta-center">
						<?php echo $country->id; ?>
					</td>
					<td class="country-title">
						<?php echo anchor('admin/'.$component_name.'/' . $component_function . '/states_list/'.$country->id,lang($country->title),'class="" title="'.lang('action_view').'"'); ?>
					</td>
					<td class="country-alias">
						<?php echo $country->alias; ?>
					</td>
					<td class="status">
						
						<?=
							
							vui_el_button( array(
							
								'url' => 'admin/' . $component_name . '/' . $component_function . '/' . ( $country->status == 0 ? 'fetch_publish' : 'fetch_unpublish' ) . '/' . $country->id,
								'text' => lang( $country->status == 0 ? 'unpublished' : 'published' ),
								'icon' => ( $country->status == 0 ? 'cancel unpublished' : 'ok published' ),
								'only_icon' => TRUE, )
								
							);
							
						?>
						
					</td>
					<td class="operations">
						
						<?= vui_el_button( array( 'url' => 'admin/' . $component_name . '/' . $component_function . '/edit_country/' . $country->id, 'text' => lang( 'action_edit' ), 'icon' => 'edit', 'only_icon' => TRUE, ) ); ?>
						
						<?= vui_el_button( array( 'url' => 'admin/' . $component_name . '/' . $component_function . '/remove_country/' . $country->id, 'text' => lang( 'action_delete' ), 'icon' => 'remove', 'only_icon' => TRUE, ) ); ?>
						
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