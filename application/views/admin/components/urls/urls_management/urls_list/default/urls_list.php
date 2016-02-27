		
		<?php if( ! empty ( $urls ) ){ ?>
		
		<div>
			
			<header class="component-head">
				
				<h1>
					
					<?= lang( 'urls' ); ?>
					
				</h1>
				
			</header>
			
			<?php if ( $pagination ){ ?>
			<div class="pagination">
				<?= $pagination; ?>
			</div>
			<?php } ?>
			
			<table class="data-list responsive">
				
				<tr>
					
					<?php $current_column = 'id'; ?>
					
					<th class="url-<?= $current_column; ?>  order-by <?= ( $order_by == 'id' ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">
						
						<?= anchor(get_url( 'admin' . '/' . $component_name.'/' . $component_function . '/a/cob/ob/' . $current_column) , lang( $current_column ), 'class="" title="'. ( ( $order_by == $current_column ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) :  ( ( $order_by == $current_column ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) : lang('click_to_order_by_this_column') )  ) .'"'); ?>
						
					</th>
					
					<?php $current_column = 'sef_url'; ?>
					
					<th class="module-<?= $current_column; ?>  order-by <?= ( $order_by == 'sef_url' ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">
						
						<?= anchor(get_url( 'admin' . '/' . $component_name.'/' . $component_function . '/a/cob/ob/' . $current_column) , lang( $current_column ), 'class="" title="'. ( ( $order_by == $current_column ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) :  ( ( $order_by == $current_column ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) : lang('click_to_order_by_this_column') )  ) .'"'); ?>
						
					</th>
					
					<?php $current_column = 'target'; ?>
					
					<th class="module-<?= $current_column; ?>  order-by <?= ( $order_by == 'type' ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">
						
						<?= anchor(get_url( 'admin' . '/' . $component_name.'/' . $component_function . '/a/cob/ob/' . $current_column) , lang( $current_column ), 'class="" title="'. ( ( $order_by == $current_column ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) :  ( ( $order_by == $current_column ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) : lang('click_to_order_by_this_column') )  ) .'"'); ?>
						
					</th>
					
					<th class="op-column">
						<?= lang( 'operations' ); ?>
					</th>
					
				</tr>
				
				<?php foreach( $urls as $url ): ?>
				<tr>
					
					<td class="module-id">
						
						<?= $url[ 'id' ]; ?>
						
					</td>
					
					<td class="module-sef_url">
						
						<?= anchor( $url[ 'edit_link' ] , $url[ 'sef_url' ], 'class="" title="' . lang( 'click_to_edit_this_url' ) . '"' ); ?>
						
					</td>
					
					<td class="module-target">
						
						<?= $url[ 'target' ]; ?>
						
					</td>
					
					<td class="operations">
						
						<?= vui_el_button( array( 'url' => $url[ 'edit_link' ], 'text' => lang( 'action_edit' ), 'icon' => 'edit', 'only_icon' => TRUE, ) ); ?>
						
						<?php if ( $url[ 'sef_url' ] != 'default_controller' ){ ?>
						
						<?= vui_el_button( array( 'url' => $url[ 'remove_link' ], 'text' => lang( 'action_delete' ), 'icon' => 'remove', 'only_icon' => TRUE, ) ); ?>
						
						<?php } else { ?>
						
						<?= vui_el_button( array( 'text' => lang( 'you_cant_delete_home_page' ), 'icon' => 'remove disabled', 'only_icon' => TRUE, ) ); ?>
						
						<?php } ?>
						
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
		
		<?php } else { ?>
		
		<div>
			
			<header class="component-head">
				
				<h1>
					
					<?= lang( 'urls' ); ?>
					
				</h1>
				
			</header>
			
			<?= lang( 'no_urls' ); ?>
			
		</div>
			
		<?php } ?>
		
		
		