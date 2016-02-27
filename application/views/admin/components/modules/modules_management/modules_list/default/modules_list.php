		
		<div>
			
			<header class="component-head">
				
				<h1>
					
					<?= lang( 'modules' ); ?>
					
				</h1>
				
			</header>
			
			<?php if ( $pagination ){ ?>
			<div class="pagination">
				<?= $pagination; ?>
			</div>
			<?php } ?>
			
			<table class="data-list responsive">
				
				<tr>
					
					<th class="module-id  order-by <?= ( $order_by == 'id' ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">
						
						<?= anchor(get_url( 'admin' . '/' . $component_name.'/' . $component_function . '/a/cob/ob/id') , lang('id'), 'class="" title="'. ( ( $order_by == 'id' ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) :  ( ( $order_by == 'id' ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) : lang('click_to_order_by_this_column') )  ) .'"'); ?>
						
					</th>
					
					<th class="module-title  order-by <?= ( $order_by == 'title' ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">
						
						<?= anchor(get_url( 'admin' . '/' . $component_name.'/' . $component_function . '/a/cob/ob/title') , lang('title'), 'class="" title="'. ( ( $order_by == 'title' ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) :  ( ( $order_by == 'title' ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) : lang('click_to_order_by_this_column') )  ) .'"'); ?>
						
					</th>
					
					<th class="module-type  order-by <?= ( $order_by == 'type' ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">
						
						<?= anchor(get_url( 'admin' . '/' . $component_name.'/' . $component_function . '/a/cob/ob/type') , lang('module_type'), 'class="" title="'. ( ( $order_by == 'type' ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) :  ( ( $order_by == 'type' ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) : lang('click_to_order_by_this_column') )  ) .'"'); ?>
						
					</th>
					
					<th class="module-ordering  order-by <?= ( $order_by == 'ordering' ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">
						
						<?= anchor(get_url( 'admin' . '/' . $component_name.'/' . $component_function . '/a/cob/ob/ordering') , lang('ordering'), 'class="" title="'. ( ( $order_by == 'ordering' ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) :  ( ( $order_by == 'ordering' ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) : lang('click_to_order_by_this_column') )  ) .'"'); ?>
						
					</th>
					
					<th class="module-position  order-by <?= ( $order_by == 'position' ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">
						
						<?= anchor(get_url( 'admin' . '/' . $component_name.'/' . $component_function . '/a/cob/ob/position') , lang('position_on_template'), 'class="" title="'. ( ( $order_by == 'position' ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) :  ( ( $order_by == 'position' ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) : lang('click_to_order_by_this_column') )  ) .'"'); ?>
						
					</th>
					
					<th class="module-status  order-by <?= ( $order_by == 'position' ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">
						
						<?= anchor(get_url( 'admin' . '/' . $component_name.'/' . $component_function . '/a/cob/ob/status') , lang('status'), 'class="" title="'. ( ( $order_by == 'status' ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) :  ( ( $order_by == 'status' ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) : lang('click_to_order_by_this_column') )  ) .'"'); ?>
						
					</th>
					
					<th class="op-column">
						<?= lang( 'operations' ); ?>
					</th>
					
				</tr>
				
				<?php foreach( $modules as $module ): ?>
				<tr>
					
					<td class="module-id">
						
						<?= $module[ 'id' ]; ?>
						
					</td>
					
					<td class="module-title">
						
						<?= anchor( $module[ 'edit_link' ] , $module[ 'title' ], 'class="" title="' . lang( 'click_to_edit_this_module' ) . '"' ); ?>
						
					</td>
					
					<td class="module-type">
						
						<?= lang( 'module_type_' . $module[ 'type' ] ); ?>
						
					</td>
					
					<td class="module-ordering">
						
						<?= form_open( $module[ 'down_order_link' ], 'class="form-change-order"' ); ?>
						
						<?= form_hidden( 'module_id',$module[ 'id' ] ); ?>
						<?= form_hidden( 'ordering', $module[ 'ordering' ] ); ?>
						
						<?= vui_el_button( array( 'text' => lang( 'down_order' ), 'icon' => 'up', 'only_icon' => TRUE, 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_down_order', 'id' => 'submit-down-order', ) ); ?>
						
						<?= form_close(); ?>
						
						<?= form_open( $module[ 'change_order_link' ], 'class="form-change-order"' ); ?>
						<?= form_input( array( 'id'=>'ordering-' . $module[ 'id' ], 'class'=>'inputbox-order','name'=>'ordering'), set_value( 'ordering', $module[ 'ordering' ])); ?>
						<?= form_hidden('module_id',$module[ 'id' ]); ?>
						<?= form_close(); ?>
						
						<?= form_open( $module[ 'up_order_link' ], 'class="form-change-order"' ); ?>
						
						<?= form_hidden( 'module_id', $module[ 'id' ] ); ?>
						<?= form_hidden( 'ordering', $module[ 'ordering' ] ); ?>
						
						<?= vui_el_button( array( 'text' => lang( 'up_order' ), 'icon' => 'down', 'only_icon' => TRUE, 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_up_order', 'id' => 'submit-up-order', ) ); ?>
						
						<?= form_close(); ?>
						
					</td>
					
					<td class="module-position">
						
						<?= lang( $module[ 'position' ] ); ?>
						
					</td>
					
					<td class="status">
						
						<?=
							
							vui_el_button( array(
							
								'url' => $module[ 'status' ] == 0 ? $module[ 'change_status_publish_link' ] : $module[ 'change_status_unpublish_link' ],
								'text' => lang( $module[ 'status' ] == 0 ? 'unpublished' : 'published' ),
								'icon' => ( $module[ 'status' ] == 0 ? 'cancel unpublished' : 'ok published' ),
								'only_icon' => TRUE, )
								
							);
							
						?>
						
					</td>
					
					<td class="operations">
						
						<?= vui_el_button( array( 'url' => $module[ 'edit_link' ], 'text' => lang( 'action_edit' ), 'icon' => 'edit', 'only_icon' => TRUE, ) ); ?>
						
						<?= vui_el_button( array( 'url' => $module[ 'remove_link' ], 'text' => lang( 'action_delete' ), 'icon' => 'remove', 'only_icon' => TRUE, ) ); ?>
						
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