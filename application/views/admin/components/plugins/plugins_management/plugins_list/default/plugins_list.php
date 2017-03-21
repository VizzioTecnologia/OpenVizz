<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
	
	<div class="items-list">
		
		<header class="component-head">
			
			<h1>
				
				<?= lang( 'plugins' ); ?>
				
			</h1>
			
		</header>
		
		<?php if( ! empty ( $plugins ) ){ ?>
			
			<?php if ( $pagination ){ ?>
			<div class="pagination">
				<?= $pagination; ?>
			</div>
			<?php } ?>
			
			<table class="data-list responsive">
				
				<tr>
					
					<?php $current_column = 'id'; ?>
					
					<th class="col-<?= $current_column; ?>  order-by <?= ( $order_by == $current_column ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">
						
						<?= anchor( get_url( 'admin' . '/' . $component_name.'/' . $component_function . '/a/cob/ob/' . $current_column) , lang( $current_column ), 'class="" title="'. ( ( $order_by == $current_column ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) :  ( ( $order_by == $current_column ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) : lang('click_to_order_by_this_column') )  ) .'"' ); ?>
						
					</th>
					
					<?php $current_column = 'title'; ?>
					
					<th class="col-<?= $current_column; ?>  order-by <?= ( $order_by == $current_column ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">
						
						<?= anchor( get_url( 'admin' . '/' . $component_name.'/' . $component_function . '/a/cob/ob/' . $current_column) , lang( $current_column ), 'class="" title="'. ( ( $order_by == $current_column ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) :  ( ( $order_by == $current_column ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) : lang('click_to_order_by_this_column') )  ) .'"' ); ?>
						
					</th>
					
					<?php $current_column = 'name'; ?>
					
					<th class="col-<?= $current_column; ?>  order-by <?= ( $order_by == $current_column ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">
						
						<?= anchor( get_url( 'admin' . '/' . $component_name.'/' . $component_function . '/a/cob/ob/' . $current_column) , lang( $current_column ), 'class="" title="'. ( ( $order_by == $current_column ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) :  ( ( $order_by == $current_column ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) : lang('click_to_order_by_this_column') )  ) .'"' ); ?>
						
					</th>
					
					<?php $current_column = 'type'; ?>
					
					<th class="col-<?= $current_column; ?>  order-by <?= ( $order_by == $current_column ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">
						
						<?= anchor( get_url( 'admin' . '/' . $component_name.'/' . $component_function . '/a/cob/ob/' . $current_column) , lang( $current_column ), 'class="" title="'. ( ( $order_by == $current_column ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) :  ( ( $order_by == $current_column ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) : lang('click_to_order_by_this_column') )  ) .'"' ); ?>
						
					</th>
					
					<?php $current_column = 'ordering'; ?>
					<th class="url-<?= $current_column; ?>  order-by <?= ( $order_by == $current_column ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">
						
						<?= anchor(get_url( 'admin' . '/' . $component_name.'/' . $component_function . '/a/cob/ob/' . $current_column) , lang( $current_column ), 'class="" title="'. ( ( $order_by == $current_column ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) :  ( ( $order_by == $current_column ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) : lang('click_to_order_by_this_column') )  ) .'"'); ?>
						
					</th>
					
					<?php $current_column = 'status'; ?>
					<th class="url-<?= $current_column; ?>  order-by <?= ( $order_by == $current_column ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">
						
						<?= anchor(get_url( 'admin' . '/' . $component_name.'/' . $component_function . '/a/cob/ob/' . $current_column) , lang( $current_column ), 'class="" title="'. ( ( $order_by == $current_column ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) :  ( ( $order_by == $current_column ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) : lang('click_to_order_by_this_column') )  ) .'"'); ?>
						
					</th>
					
					<?php $current_column = 'operations'; ?>
					
					<th class="op-column">
						
						<?= lang( $current_column ); ?>
						
					</th>
					
				</tr>
				
				<?php foreach( $plugins as $plugin ): ?>
				<tr>
					
					<?php $current_column = 'id'; ?>
					
					<td class="col-<?= $current_column; ?>">
						
						<?= $plugin[ $current_column ]; ?>
						
					</td>
					
					<?php $current_column = 'title'; ?>
					
					<td class="col-<?= $current_column; ?>">
						
						<?= anchor( $plugin[ 'edit_link' ] , $plugin[ $current_column ], 'class="" title="' . lang( 'click_to_edit_this_plugin' ) . '"' ); ?>
						
					</td>
					
					<?php $current_column = 'name'; ?>
					
					<td class="col-<?= $current_column; ?>">
						
						<?= $plugin[ $current_column ]; ?>
						
					</td>
					
					<?php $current_column = 'type'; ?>
					
					<td class="col-<?= $current_column; ?>">
						
						<?= $plugin[ $current_column ]; ?>
						
					</td>
					
					<?php $current_column = 'ordering'; ?>
					
					<td class="col-<?= $current_column; ?>">
						
						<?php echo form_open( get_url('admin/'.$component_name.'/' . $component_function . '/a/uo'),'class="form-change-order"'); ?>
						<?php echo form_hidden('plugin_id', $plugin[ 'id' ] ); ?>
						<?php echo form_hidden( 'ordering', $plugin[ 'ordering' ] ); ?>
						
						<?= vui_el_button( array( 'text' => lang( 'down_order' ), 'icon' => 'up', 'only_icon' => TRUE, 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_down_order', 'id' => 'submit-down-order', ) ); ?>
						
						<?php echo form_close(); ?>
						
						<?php echo form_open( get_url( 'admin/' . $component_name . '/' . $component_function . '/a/co/' ), 'class="form-change-order"' ); ?>
						<?php echo form_input_number( array( 'id' => 'ordering-' . $plugin[ 'id' ], 'class' => 'inputbox-ordering', 'name' => 'ordering' ), set_value( 'ordering', $plugin[ 'ordering' ] ) ); ?>
						<?php echo form_hidden( 'plugin_id', $plugin[ 'id' ] ); ?>
						<?php echo form_close(); ?>
						
						<?php echo form_open( get_url( 'admin/' . $component_name . '/' . $component_function . '/a/do' ),'class="form-change-order"' ); ?>
						<?php echo form_hidden( 'plugin_id', $plugin[ 'id' ] ); ?>
						<?php echo form_hidden( 'ordering', $plugin[ 'ordering' ] ); ?>
						
						<?= vui_el_button( array( 'text' => lang( 'up_order' ), 'icon' => 'down', 'only_icon' => TRUE, 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_up_order', 'id' => 'submit-up-order', ) ); ?>
						
						<?php echo form_close(); ?>
						
					</td>
					
					<?php $current_column = 'status'; ?>
					
					<td class="col-<?= $current_column; ?>">
						
						<?=
							
							vui_el_button( array(
							
								'url' => $plugin[ 'status' ] == 0 ? $plugin[ 'change_status_publish_link' ] : $plugin[ 'change_status_unpublish_link' ],
								'text' => lang( $plugin[ 'status' ] == 0 ? 'unpublished' : 'published' ),
								'icon' => ( $plugin[ 'status' ] == 0 ? 'cancel unpublished' : 'ok published' ),
								'only_icon' => TRUE, )
								
							);
							
						?>
						
					</td>
					
					<?php $current_column = 'operations'; ?>
					
					<td class="col-<?= $current_column; ?>">
						
						<?= vui_el_button( array( 'url' => $plugin[ 'edit_link' ], 'text' => lang( 'action_edit' ), 'icon' => 'edit', 'only_icon' => TRUE, ) ); ?>
						
						<?= vui_el_button( array( 'url' => $plugin[ 'remove_link' ], 'text' => lang( 'action_delete' ), 'icon' => 'remove', 'only_icon' => TRUE, ) ); ?>
						
					</td>
					
				</tr>
				<?php endforeach; ?>
			</table>
			
			<?php if ( $pagination ){ ?>
			<div class="pagination">
				<?= $pagination; ?>
			</div>
			<?php } ?>
			
		<?php } else { ?>
			
			<?= vui_el_button( array( 'text' => lang( 'no_plugins' ), 'icon' => 'error', ) ); ?>
			
			<?= vui_el_button( array( 'url' => $add_link, 'text' => lang( 'add_plugin' ), 'icon' => 'add', 'only_icon' => FALSE, ) ); ?>
			
		<?php } ?>
		
	</div>
	
