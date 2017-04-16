<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

	<div class="items-list">
		
		<?php if( ! empty ( $submit_forms ) ){ ?>
			
			<?php if ( $pagination ){ ?>
			<div class="pagination">
				<?= $pagination; ?>
			</div>
			<?php } ?>

			<table class="data-list ud-ds-list-layout-default responsive">

				<tr>

					<?php $current_column = 'id'; ?>

					<th class="col-<?= $current_column; ?>  order-by <?= ( $order_by == $current_column ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">

						<?= anchor( get_url( 'admin' . '/' . $component_name.'/' . $component_function . '/a/cob/ob/' . $current_column) , lang( $current_column ), 'class="" title="'. ( ( $order_by == $current_column ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) :  ( ( $order_by == $current_column ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) : lang('click_to_order_by_this_column') )  ) .'"' ); ?>

					</th>

					<?php $current_column = 'title'; ?>

					<th class="col-<?= $current_column; ?>  order-by <?= ( $order_by == $current_column ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">

						<?= anchor( get_url( 'admin' . '/' . $component_name.'/' . $component_function . '/a/cob/ob/' . $current_column) , lang( $current_column ), 'class="" title="'. ( ( $order_by == $current_column ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) :  ( ( $order_by == $current_column ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) : lang('click_to_order_by_this_column') )  ) .'"' ); ?>

					</th>

					<?php $current_column = 'alias'; ?>

					<th class="col-<?= $current_column; ?>  order-by <?= ( $order_by == $current_column ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">

						<?= anchor( get_url( 'admin' . '/' . $component_name.'/' . $component_function . '/a/cob/ob/' . $current_column) , lang( $current_column ), 'class="" title="'. ( ( $order_by == $current_column ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) :  ( ( $order_by == $current_column ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) : lang('click_to_order_by_this_column') )  ) .'"' ); ?>

					</th>

					<?php $current_column = 'create_datetime'; ?>

					<th class="col-<?= $current_column; ?>  order-by <?= ( $order_by == $current_column ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">

						<?= anchor( get_url( 'admin' . '/' . $component_name.'/' . $component_function . '/a/cob/ob/' . $current_column) , lang( $current_column ), 'class="" title="'. ( ( $order_by == $current_column ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) :  ( ( $order_by == $current_column ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) : lang('click_to_order_by_this_column') )  ) .'"' ); ?>

					</th>

					<?php $current_column = 'mod_datetime'; ?>

					<th class="col-<?= $current_column; ?>  order-by <?= ( $order_by == $current_column ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">

						<?= anchor( get_url( 'admin' . '/' . $component_name.'/' . $component_function . '/a/cob/ob/' . $current_column) , lang( $current_column ), 'class="" title="'. ( ( $order_by == $current_column ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) :  ( ( $order_by == $current_column ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) : lang('click_to_order_by_this_column') )  ) .'"' ); ?>

					</th>

					<?php $current_column = 'operations'; ?>

					<th class="op-column">

						<?= lang( $current_column ); ?>

					</th>

				</tr>

				<?php foreach( $submit_forms as $submit_form ): ?>
				<tr>

					<?php $current_column = 'id'; ?>

					<td class="col-<?= $current_column; ?>">

						<?= $submit_form[ $current_column ]; ?>

					</td>

					<?php $current_column = 'title'; ?>

					<td class="col-<?= $current_column; ?>">

						<?= anchor( $submit_form[ 'edit_link' ] , $submit_form[ $current_column ], 'class="" title="' . lang( 'click_to_edit_this_submit_form' ) . '"' ); ?>

					</td>

					<?php $current_column = 'alias'; ?>

					<td class="col-<?= $current_column; ?>">

						<?= $submit_form[ $current_column ]; ?>

					</td>

					<?php $current_column = 'create_datetime'; ?>

					<td class="col-<?= $current_column; ?>">

						<?= $submit_form[ $current_column ]; ?>

					</td>

					<?php $current_column = 'mod_datetime'; ?>

					<td class="col-<?= $current_column; ?>">

						<?= $submit_form[ $current_column ]; ?>

					</td>

					<?php $current_column = 'operations'; ?>

					<td class="col-<?= $current_column; ?>">
						
						<?php
						
						if ( $submit_form[ 'users_submit_count' ] ){
							
							echo vui_el_button( array( 'url' => $submit_form[ 'users_submits_link' ], 'text' => ( $submit_form[ 'users_submit_count' ] > 1 ? lang( 'ud_dsl_data_count', NULL, $submit_form[ 'users_submit_count' ] ) : lang( 'ud_dsl_data_count_one' ) ), 'icon' => 'users_submits', 'only_icon' => TRUE, ) );
							
						} else {
							
							echo vui_el_button( array( 'text' => lang( 'ud_dsl_data_count_zero' ), 'icon' => 'users_submits disabled', 'only_icon' => TRUE, ) );
							
						}
						
						echo vui_el_button( array( 'url' => $submit_form[ 'users_submits_add_link' ], 'text' => lang( 'add_user_submit' ), 'icon' => 'add', 'only_icon' => TRUE, ) );
						
						echo vui_el_button( array( 'url' => $submit_form[ 'edit_link' ], 'text' => lang( 'action_edit' ), 'icon' => 'edit', 'only_icon' => TRUE, ) );
						
						echo form_open( get_url( 'admin/submit_forms/sfm/a/c/sfid/' . $submit_form[ 'id' ] ), array( 'id' => 'copy-ds-' . $submit_form[ 'id' ], ) );
						
						echo vui_el_button( array( 'text' => lang( 'copy' ), 'icon' => 'copy', 'class' => 'multi-selection-action-input', 'button_type' => 'button', 'type' => 'submit', 'value' => 'copy', 'name' => 'submit_copy', 'id' => 'submit-copy', 'form' => 'copy-ds-' . $submit_form[ 'id' ], 'only_icon' => TRUE, ) );
						
						echo form_close();
						
						echo vui_el_button( array( 'url' => $submit_form[ 'remove_link' ], 'text' => lang( 'action_delete' ), 'icon' => 'remove', 'only_icon' => TRUE, ) );
						
						?>

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
			
			<p class="info">
				
				<?= lang( 'no_submit_forms' ); ?>
				
			</p>
			
			<?= vui_el_button( array( 'url' => $c_urls[ 'sf_add_link' ], 'text' => lang( 'add_submit_form' ), 'icon' => 'add', 'only_icon' => FALSE, ) ); ?>

		<?php } ?>

	</div>
