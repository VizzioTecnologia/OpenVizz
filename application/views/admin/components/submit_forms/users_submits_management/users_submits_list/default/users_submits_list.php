<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
	
	$this->plugins->load( 'jquery_checkboxes' );
	$this->plugins->load( 'fancybox' );
	$this->plugins->load( 'modal_users_submits' );
	$this->load->helper( 'text' );
	
	$fields_to_show = NULL;
	
	foreach( $columns as $k => $column ) {
		
		if ( $column[ 'visible' ] ) {
			
			$fields_to_show[] = $column[ 'alias' ];
			
		}
		
	}
	
	$filter_fields_input_name = 'users_submits_search[dinamic_filter_fields]';
	
	$users_submits = $this->sfcm->parse_users_submits_list( $users_submits, $fields_to_show, $submit_form );
	
?>

<?php if ( ! $this->input->post( 'ajax' ) ) { ?>
<div class="form-actions to-toolbar to-main-toolbar">
	
	<?= form_open( get_url( 'admin' . $this->uri->ruri_string() . assoc_array_to_qs() ), array( 'id' => 'change-ipp-form', ) ); ?>
	
		<div class="filter-fields-wrapper fields-wrapper-inline">
			
			<?php
				
				echo vui_el_input_number(
					
					array(
						
						'text' => lang( 'items_per_page' ),
						'title' => lang( 'tip_change_items_per_page' ),
						'icon' => 'ipp',
						'name' => 'ipp',
						'id' => 'input-ipp',
						'min' => -1,
						'max' => isset( $submit_form[ 'params' ][ 'sf_us_list_max_ipp' ] ) ? $submit_form[ 'params' ][ 'sf_us_list_max_ipp' ] : 100,
						'form' => 'change-ipp-form',
						'value' => $ipp,
						
					)
					
				);
				
				echo vui_el_button(
					
					array(
						
						'text' => lang( 'action_change_ipp' ),
						'icon' => 'apply',
						'button_type' => 'button',
						'type' => 'submit',
						'name' => 'submit_change_ipp',
						'id' => 'submit-change-ipp',
						'only_icon' => TRUE,
						'form' => 'change-ipp-form',
						
					)
					
				);
				
			?>
			
		</div>
		
	<?= form_close(); ?>
	
</div>
<?php } ?>

<?php if ( ! $this->input->post( 'ajax' ) ) { ?>
<div id="users-submits-search-wrapper" class="users-submits-search-wrapper to-toolbar">
	
	<?= form_open_multipart( 'admin' . $this->uri->ruri_string() . assoc_array_to_qs(), array( 'id' => 'us-search-form', ) );
		
		$combo_box_fields_to_search = array();
		
		?>
		
		<?= vui_el_button(
			
			array(
				
				'text' => lang( 'filters' ),
				'icon' => 'search',
				'only_icon' => FALSE,
				'wrapper_class' => 'title',
				
			)
			
		); ?>
		
		<div class="submit-form-field-wrapper submit-form-field-wrapper-terms submit-form-field-wrapper-terms ">
			
			<?php
				
				if ( check_var( $params[ 'search_terms_string' ] ) ) {
					
					echo form_label( lang( $params[ 'search_terms_string' ] ) );
					
				}
				else {
					
					echo form_label( lang( 'search_terms' ) );
					
				}
				
			?>
			
			<div class="submit-form-field-control"><?php
				
				echo vui_el_input_text(
					
					array(
						
						'id' => 'submit-form-terms',
						'name' => 'users_submits_search[terms]',
						'value' => $terms,
						'text' => lang( 'search_terms' ),
						'title' => lang( 'search_terms' ),
						'icon' => 'search',
						'form' => 'us-search-form',
						
					)
					
				);
				
			?></div>
			
		</div><?php
		
		//echo '<pre>' . print_r( $fields, TRUE ) . '</pre>'; exit;
		
		foreach ( $fields as $key_2 => $field ) {
			
			$formatted_field_name = 'form[' . $field[ 'alias' ] . ']';
			$field_value = ( isset( $post[ 'users_submits_search' ][ 'dinamic_filter_fields' ][ $field[ 'alias' ] ] ) ) ? $post[ 'users_submits_search' ][ 'dinamic_filter_fields' ][ $field[ 'alias' ] ] : '';
			
			//print_r( $params[ 'users_submit_search_fields' ] ); exit;
			
			if ( $field[ 'field_type' ] == 'combo_box' ) {
				
				$options = array(
					
					'' => lang( 'combobox_select' ),
					
				);
				
				$combo_box_fields_to_search[ $field[ 'alias' ] ] = $field;
				
				if ( check_var( $field[ 'options_from_users_submits' ] ) AND ( check_var( $field[ 'options_title_field' ] ) OR check_var( $field[ 'options_title_field_custom' ] ) ) ) {
					
					$filters = NULL;
					
					if ( check_var( $field[ 'options_filter_admin' ] ) ) {
						
						$filters = $field[ 'options_filter_admin' ];
						
					}
					else if ( check_var( $field[ 'options_filter' ] ) ) {
						
						$filters = $field[ 'options_filter' ];
						
					}
					
					$search_config = array(
						
						'plugins' => 'sf_us_search',
						'allow_empty_terms' => TRUE,
						'ipp' => 0,
						'cp' => NULL,
						'plugins_params' => array(
							
							'sf_us_search' => array(
								
								'sf_id' => $field[ 'options_from_users_submits' ],
								'filters' => json_decode( $filters, TRUE ),
								'order_by' => ( isset( $field[ 'options_filter_order_by' ] ) ? $field[ 'options_filter_order_by' ] : $field[ 'options_title_field' ] ),
								'order_by_direction' => ( isset( $field[ 'options_filter_order_by_direction' ] ) ? $field[ 'options_filter_order_by_direction' ] : 'ASC' ),
								
							),
							
						),
						
					);
					
					$this->load->library( 'search' );
					$this->search->config( $search_config );
					
					$_users_submits = $this->search->get_full_results( 'sf_us_search', TRUE );
					
					foreach( $_users_submits as & $_user_submit ) {
						
						$_user_submit[ 'data' ] = get_params( $_user_submit[ 'data' ] );
						
						if ( $field[ 'options_title_field' ] )
						
						foreach( $_user_submit[ 'data' ] as $_dk => $_data ) {
							
							if ( $_dk == $field[ 'options_title_field' ] )
							
							$options[ $_user_submit[ 'id' ] ] = $_data;
							
						};
						
					};
					
				}
				else {
					
					$options_temp = explode( "\n" , $field[ 'options' ] );
					
					foreach( $options_temp as $option ) {
						
						$options[ $option ] = $option;
						
					};
					
				}
				
			?><div class="submit-form-field-wrapper submit-form-field-wrapper-<?= $field[ 'alias' ]; ?> submit-form-field-wrapper-<?= $field[ 'field_type' ]; ?> ">
				
				<?= form_label( lang( isset( $field[ 'presentation_label' ] ) ? $field[ 'presentation_label' ] : $field[ 'label' ] ), NULL, 
					
					array(
						
						'title' => lang( $field[ 'presentation_label' ] ),
						
					)
					
				); ?>
				
				<div class="submit-form-field-control">
					
					<?= vui_el_dropdown(
						
						array(
							
							'id' => 'submit-form-' . $field[ 'alias' ],
							'title' => isset( $field[ 'description' ] ) ? lang( $field[ 'description' ] ) : NULL,
							'value' => $field_value,
							'name' => $filter_fields_input_name . '[' . $field[ 'alias' ] . ']',
							'options' => $options,
							'class' => 'form-element submit-form submit-form-' . $field[ 'alias' ],
							
						)
						
					); ?>
					
				</div>
				
			</div><?php
			
			}
			
		} 
		
		?><div class="submit-form-field-wrapper submit-form-field-wrapper-submit_search submit-form-field-wrapper-button">
			
			<div class="submit-form-field-control">
				
				<?= vui_el_button(
					
					array(
						
						'id' => 'submit-form-submit_search',
						'button_type' => 'button',
						'text' => lang( 'submit_search' ),
						'icon' => 'search',
						'only_icon' => FALSE,
						'name' => 'users_submits_search[submit_search]',
						'class' => 'form-element submit-form submit-form-submit_search',
						'wrapper_class' => 'action',
						
					)
					
				); ?>
				
			</div>
			
		</div>
		
	<?= form_close(); ?>
	
	<?php
		
		$combo_box_fields_to_search = array();
		
	?>
	
</div>
<?php } ?>

<?php if ( ! $this->input->post( 'ajax' ) ) { ?>
<div id="users-submits-list-wrapper" class="items-list">
<?php } ?>
	
	<div class="ud-dl-filters-profiles">
		
		<?php
			
			if ( $this->input->post( 'ud_dl_filters_profiles' ) ) {
				
				$new_profile = $this->input->post( 'ud_dl_filters_profiles' );
				
				$new_profile[ 'name' ] = ( isset( $new_profile[ 'name' ] ) AND check_var( $new_profile[ 'name' ] ) ) ? $new_profile[ 'name' ] : lang( 'default' );
				
				$new_profile[ 'id' ] = ( isset( $new_profile[ 'name' ] ) AND check_var( $new_profile[ 'name' ] ) ) ? url_title( $new_profile[ 'name' ], '-', TRUE ) : 'default';
				
				$submit_form[ 'params' ][ 'admin_ud_dl_filters_profiles' ][ $new_profile[ 'id' ] ] = $new_profile;
				
				$update_data = array();
				
				$update_data[ 'params' ] = json_encode( $submit_form[ 'params' ] );
				
				if ( $this->sfcm->update( $update_data, array( 'id' => $submit_form[ 'id' ] ) ) ) {
					
				} else {
					
				}
				
			}
			
			$admin_ud_dl_filters_profiles = isset( $submit_form[ 'params' ][ 'admin_ud_dl_filters_profiles' ] ) ? $submit_form[ 'params' ][ 'admin_ud_dl_filters_profiles' ]: array();
			
			if ( ! empty( $admin_ud_dl_filters_profiles ) ) {
				
				foreach( $admin_ud_dl_filters_profiles as $profile_id => $profile ) {
					
					echo vui_el_button(
						
						array(
							
							'text' => $profile[ 'name' ],
							'icon' => 'ok',
							'button_type' => 'anchor',
							'type' => 'submit',
							'url' => get_url( $profile[ 'url' ] ),
// 							'url' => get_url( $c_urls[ 'us_list_link' ] . '/sfid/' . $submit_form[ 'id' ] . '/sfsp/' . $search_filters_url . '/f/' . $filters_url . '/cp/1/ipp/' . $ipp ),
							
						)
						
					);
					
				}
				
			}
			
		?>
		
	</div>
	
	<details>
		
		<summary>Gerenciar perfis de filtros</summary>
		
		<div>
			
			<?php
				
				if ( check_var( $submit_form[ 'id' ] ) ) {
					
					echo form_open( current_url(), array( 'id' => 'ud-dl-filters-profiles-form', ) );
						
						echo '<div class="ud-dl-filters-profiles-add-profile-wrapper vui-field-wrapper-inline">';
						
						echo form_label( lang( 'lbl_ud_dl_filter_profile_name' ) );
						
						echo '<div class="vui-form-field-control vui-field-control">';
						
						echo vui_el_input_text(
							
							array(
								
								'id' => 'submit-form-terms',
								'name' => 'ud_dl_filters_profiles[name]',
								'text' => lang( 'lbl_ud_dl_filter_profile_name' ),
								'title' => lang( 'tip_lbl_ud_dl_filter_profile_name' ),
								'form' => 'ud-dl-filters-profiles-form',
								
							)
							
						);
						
						echo '</div>';
						
						echo '</div>';
						
						// -------------
						
						echo '<div class="ud-dl-filters-profiles-add-profile-wrapper vui-field-wrapper-inline">';
						
						echo form_label( lang( 'ud_dl_filter_profile_title' ) );
						
						echo '<div class="vui-form-field-control vui-field-control">';
						
						echo vui_el_input_text(
							
							array(
								
								'id' => 'submit-form-terms',
								'name' => 'ud_dl_filters_profiles[url]',
								'value' => $c_urls[ 'us_list_link' ] . '/sfid/' . $submit_form[ 'id' ] . '/sfsp/' . $search_filters_url . '/f/' . $filters_url . '/cp/1/ipp/' . $ipp,
								'text' => lang( 'lbl_ud_dl_filter_profile_url' ),
								'title' => lang( 'tip_lbl_ud_dl_filter_profile_url' ),
								'icon' => 'web',
								'form' => 'ud-dl-filters-profiles-form',
								
							)
							
						);
						
						echo '</div>';
						
						echo '</div>';
						
						// -------------
						
						echo '<div class="ud-dl-filters-profiles-add-profile-wrapper vui-field-wrapper-auto">';
						
						echo '<div class="vui-form-field-control vui-field-control">';
						
						echo vui_el_button(
							
							array(
								
								'text' => lang( 'lbl_ud_dl_add_filter_profile' ),
								'name' => 'ud_dl_filters_profiles[submit_add]',
								'icon' => 'add',
								'button_type' => 'button',
								'value' => 1,
								'form' => 'ud-dl-filters-profiles-form',
								
							)
							
						);
						
						echo '</div>';
						
						echo '</div>';
						
					echo form_close();
					
				}
				
			?>
			
		</div>
		
	</details>
	
	<?php if ( $users_submits ){ ?>
		
		<?php if ( isset( $users_submits_total_results ) ) { ?>
		
		<div class="users-submits-search-results-title-wrapper">
			
			<h3 class="users-submits-search-results-title">
				
				<?php
					
					if ( $users_submits_total_results > 1 ) {
						
						echo sprintf( lang( 'users_submits_search_results_string' ), '<span class="users-submits-search-count">' . $users_submits_total_results . '</span>' );
						
					}
					else {
						
						echo sprintf( lang( 'users_submits_search_single_result_string' ), '<span class="users-submits-search-count">' . $users_submits_total_results . '</span>' );
						
					}
					
				?>
				
			</h3>
			
		</div>
		
		<?php } ?>
		
		<?php if ( $pagination ){ ?>
		<div class="pagination">
			<?= $pagination; ?>
		</div>
		<?php } ?>
		
		
		<?= form_open( get_url( $c_urls[ 'us_batch_link' ] ), array( 'id' => 'users-submits-form', ) ); ?>
		
		<?php if ( ! $this->input->post( 'ajax' ) ) { ?>
		<div class="form-actions to-toolbar to-main-toolbar">
			
			<ul class="controls-menu menu multi-selection-action-input">
				
				<li>
					
					<?= vui_el_button( array( 'text' => lang( 'remove' ), 'icon' => 'remove', 'class' => '', 'button_type' => 'button', 'type' => 'submit', 'value' => 'remove', 'name' => 'submit_remove', 'id' => 'submit-remove', 'form' => 'users-submits-form', ) ); ?>
					
				</li>
					
				<li class="parent">
					
					<?= vui_el_button( array( 'text' => lang( 'download' ), 'icon' => 'download', 'only_icon' => FALSE, ) ); ?>
					
					<ul>
						
						<li>
							
							<?= vui_el_button( array( 'text' => lang( 'download_json' ), 'icon' => 'json', 'button_type' => 'button', 'type' => 'submit', 'value' => 'json', 'name' => 'submit_export', 'id' => 'submit-export-json', 'form' => 'users-submits-form', ) ); ?>
							
						</li>
						
						<li>
							
							<?= vui_el_button( array( 'text' => lang( 'download_csv' ), 'icon' => 'csv', 'button_type' => 'button', 'type' => 'submit', 'value' => 'csv', 'name' => 'submit_export', 'id' => 'submit-export-csv', 'form' => 'users-submits-form', ) ); ?>
							
						</li>
						
						<li>
							
							<?= vui_el_button( array( 'text' => lang( 'download_xls' ), 'icon' => 'xls', 'button_type' => 'button', 'type' => 'submit', 'value' => 'xls', 'name' => 'submit_export', 'id' => 'submit-export-xls', 'form' => 'users-submits-form', ) ); ?>
							
						</li>
						
						<li>
							
							<?= vui_el_button( array( 'text' => lang( 'download_txt' ), 'icon' => 'txt', 'button_type' => 'button', 'type' => 'submit', 'value' => 'txt', 'name' => 'submit_export', 'id' => 'submit-export-txt', 'form' => 'users-submits-form', ) ); ?>
							
						</li>
						
						<li>
							
							<?= vui_el_button( array( 'text' => lang( 'download_html' ), 'icon' => 'html', 'button_type' => 'button', 'type' => 'submit', 'value' => 'html', 'name' => 'submit_export', 'id' => 'submit-export-html', 'form' => 'users-submits-form', ) ); ?>
							
						</li>
						
						<li>
							
							<?= vui_el_button( array( 'text' => lang( 'download_pdf' ), 'icon' => 'pdf', 'button_type' => 'button', 'type' => 'submit', 'value' => 'pdf', 'name' => 'submit_export', 'id' => 'submit-export-pdf', 'form' => 'users-submits-form', ) ); ?>
							
						</li>
						
					</ul>
					
				</li>
				
			</ul>
			
			<?php if ( check_var( $submit_form[ 'id' ] ) ) { ?>
				
				<?= form_hidden( array( 'name' => 'submit_form_id', 'value' => $submit_form[ 'id' ], 'form' => 'users-submits-form', ) ); ?>
				
			<?php } ?>
			
		</div>
		<?php } ?>
		
		<table class="data-list responsive multi-selection-table">
			
			<tr>
				
				<th class="col-checkbox">
					
					<?= vui_el_checkbox( array( 'title' => lang( 'select_all' ), 'value' => 'select_all', 'name' => 'select_all_items', 'id' => 'select-all-items', ) ); ?>
					
				</th>
				
				<?php if ( ! check_var( $submit_form_id ) AND ! check_var( $columns ) ) { ?>
					
					<?php $current_column = 'id'; ?>
					
					<th class="col-<?= $current_column; ?>  order-by <?= ( $order_by == $current_column ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">
						
						<?= anchor( get_url( 'admin' . '/' . $component_name.'/' . $component_function . ( check_var( $submit_form[ 'id' ] ) ? '/sfid/' . $submit_form[ 'id' ] : '' ) . '/a/cob/ob/' . $current_column) , lang( $current_column ), 'class="" title="'. ( ( $order_by == $current_column ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) :  ( ( $order_by == $current_column ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) : lang('click_to_order_by_this_column') )  ) .'"' ); ?>
						
					</th>
					
					<?php $current_column = 'submit_datetime'; ?>
					
					<th class="col-<?= $current_column; ?>  order-by <?= ( $order_by == $current_column ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">
						
						<?= anchor( get_url( 'admin' . '/' . $component_name.'/' . $component_function . ( check_var( $submit_form[ 'id' ] ) ? '/sfid/' . $submit_form[ 'id' ] : '' ) . '/a/cob/ob/' . $current_column) , lang( $current_column ), 'class="" title="'. ( ( $order_by == $current_column ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) :  ( ( $order_by == $current_column ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) : lang('click_to_order_by_this_column') )  ) .'"' ); ?>
						
					</th>
					
					<?php $current_column = 'submit_form_title'; ?>
					
					<th class="col-<?= $current_column; ?>  order-by <?= ( $order_by == $current_column ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">
						
						<?= anchor( get_url( 'admin' . '/' . $component_name.'/' . $component_function . ( check_var( $submit_form[ 'id' ] ) ? '/sfid/' . $submit_form[ 'id' ] : '' ) .  '/a/cob/ob/' . $current_column) , lang( $current_column ), 'class="" title="'. ( ( $order_by == $current_column ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) :  ( ( $order_by == $current_column ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) : lang('click_to_order_by_this_column') )  ) .'"' ); ?>
						
					</th>
					
				<?php } ?>
				
				<?php if ( ! check_var( $submit_form_id ) AND ! check_var( $columns ) ) { ?>
					
					<?php $current_column = 'output'; ?>
					
					<th class="col-<?= $current_column; ?>  order-by <?= ( $order_by == $current_column ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">
						
						<?= anchor( get_url( 'admin' . '/' . $component_name.'/' . $component_function . ( check_var( $submit_form[ 'id' ] ) ? '/sfid/' . $submit_form[ 'id' ] : '' ) . '/a/cob/ob/' . $current_column) , lang( $current_column ), 'class="" title="'. ( ( $order_by == $current_column ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) :  ( ( $order_by == $current_column ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) : lang('click_to_order_by_this_column') )  ) .'"' ); ?>
						
					</th>
					
				<?php } else { ?>
					
					<?php foreach ( $columns as $key => $column ) { ?>
						
						<?php $current_column = $column[ 'alias' ]; ?>
						
						<th class="col-<?= $current_column; ?>  order-by <?= ( $order_by == $current_column ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?> col-<?= $column[ 'visible' ] ? 'visible' : 'hidden'; ?>">
							
							<?= anchor( get_url( 'admin' . '/' . $component_name.'/' . $component_function . ( check_var( $submit_form[ 'id' ] ) ? '/sfid/' . $submit_form[ 'id' ] : '' ) . '/a/cob/ob/' . $current_column) , lang( $column[ 'title' ] ), 'class="" title="'. ( ( $order_by == $current_column ) ? lang( 'ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) :  ( ( $order_by == $current_column ) ? lang('ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) : lang('click_to_order_by_this_column') )  ) .'"' ); ?>
							
						</th>
						
					<?php } ?>
					
				<?php } ?>
				
				<?php $current_column = 'operations'; ?>
				
				<th class="col-<?= $current_column; ?> op-column">
					
					<?= lang( $current_column ); ?>
					
				</th>
				
			</tr>
			
			<?php foreach( $users_submits as $user_submit ) {
				
				$_us_status = array();
				$_us_status_classes = '';
				$_us_has_status = FALSE;
				
				if ( check_var( $submit_form[ 'ud_status_props' ] ) ) {
					
					foreach( $submit_form[ 'fields' ] as $status_field ) {
						
						if ( isset( $submit_form[ 'ud_status_props' ][ $status_field[ 'alias' ] ] ) ) {
							
							if ( check_var( $status_field[ 'options_from_users_submits' ] )
							AND ( check_var( $status_field[ 'options_title_field' ] )
							OR check_var( $status_field[ 'options_title_field_custom' ] ) ) ) {
								
								$_current_field_array = array(
									
									'prop_is_ud_status_active',
									'prop_is_ud_status_inactive',
									'prop_is_ud_status_enabled',
									'prop_is_ud_status_disabled',
									'prop_is_ud_status_canceled',
									'prop_is_ud_status_postponed',
									'prop_is_ud_status_archived',
									'prop_is_ud_status_published',
									'prop_is_ud_status_unpublished',
									'prop_is_ud_status_scheduled',
									
								);
								
								foreach( $_current_field_array as $_item ) {
									
									if ( check_var( $status_field[ 'advanced_options' ][ $_item ] ) AND $user_submit[ 'data' ][ $status_field[ 'alias' ] ] == $status_field[ 'advanced_options' ][ $_item ] ) {
										
										if ( $_item == 'prop_is_ud_status_active' ) {
											
											$_us_status[ 'active' ] = 'status-active';
											
										}
										else if ( $_item == 'prop_is_ud_status_inactive' ) {
											
											$_us_status[ 'inactive' ] = 'status-inactive';
											
										}
										else if ( $_item == 'prop_is_ud_status_enabled' ) {
											
											$_us_status[ 'enabled' ] = 'status-enabled';
											
										}
										else if ( $_item == 'prop_is_ud_status_disabled' ) {
											
											$_us_status[ 'disabled' ] = 'status-disabled';
											
										}
										else if ( $_item == 'prop_is_ud_status_canceled' ) {
											
											$_us_status[ 'disabled' ] = 'status-disabled';
											
										}
										else if ( $_item == 'prop_is_ud_status_postponed' ) {
											
											$_us_status[ 'postponed' ] = 'status-postponed';
											
										}
										else if ( $_item == 'prop_is_ud_status_archived' ) {
											
											$_us_status[ 'archived' ] = 'status-archived';
											
										}
										else if ( $_item == 'prop_is_ud_status_published' ) {
											
											$_us_status[ 'published' ] = 'status-published';
											
										}
										else if ( $_item == 'prop_is_ud_status_unpublished' ) {
											
											$_us_status[ 'unpublished' ] = 'status-unpublished';
											
										}
										else if ( $_item == 'prop_is_ud_status_scheduled' ) {
											
											$_us_status[ 'scheduled' ] = 'status-scheduled';
											
										}
										
										$_us_has_status = TRUE;
										
									}
									
								};
								
							}
							
						}
						
					}
					
				}
				
				if ( $_us_has_status ) {
					
					$_us_status_classes = join( $_us_status, ' ' );
					
				}
				
			?>
				
				<tr class="<?= $_us_has_status ? $_us_status_classes : ''; ?>">
					
					<td class="col-checkbox">
						
						<?= vui_el_checkbox( array( 'value' => $user_submit[ 'id' ], 'name' => 'selected_users_submits_ids[]', 'form' => 'users-submits-form', 'class' => 'multi-selection-action', ) ); ?>
						
					</td>
					
					<?php foreach ( $columns as $key => $column ) {
						
						$_prop_is_ud_status = FALSE;
						
						$advanced_options = check_var( $fields[ $column[ 'alias' ] ][ 'advanced_options' ] ) ? $fields[ $column[ 'alias' ] ][ 'advanced_options' ] : FALSE;
						
						?>
						
						<td
							
							class="
								
								col-<?= $column[ 'alias' ]; ?>
								col-<?= $column[ 'visible' ] ? 'visible' : 'hidden'; ?>
								<?= check_var( $advanced_options[ 'prop_is_ud_image' ] ) ? ' field-is-image' : ''; ?>
								<?= check_var( $advanced_options[ 'prop_is_ud_title' ] ) ? ' field-is-presentation-title' : ''; ?>
								<?= check_var( $advanced_options[ 'prop_is_ud_content' ] ) ? ' field-is-presentation-content' : ''; ?>
								<?= check_var( $advanced_options[ 'prop_is_ud_other_info' ] ) ? ' field-is-presentation-other-info' : ''; ?>
								<?= check_var( $advanced_options[ 'prop_is_ud_email' ] ) ? ' field-is-email' : ''; ?>
								<?= check_var( $advanced_options[ 'prop_is_ud_url' ] ) ? ' field-is-url' : ''; ?>
								<?= check_var( $advanced_options[ 'prop_is_ud_status' ] ) ? ' field-is-status' : ''; ?>
								sf-field-type-<?= check_var( $fields[ $column[ 'alias' ] ][ 'field_type' ] ) ? $fields[ $column[ 'alias' ] ][ 'field_type' ] : 'default'; ?>-wrapper
								
							"
							
						>
							
							<?php foreach( $user_submit[ 'parsed_data' ][ 'full' ] as $alias => $pd ) { ?>
								
								<?php if ( $column[ 'alias' ] == $alias ) { ?>
									
									<?php
										
										if ( check_var( $advanced_options[ 'prop_is_ud_image' ] ) AND check_var( $pd[ 'value' ] ) ) {
											
											$thumb_params = array(
												
												'wrapper_class' => 'us-image-wrapper',
												'src' => get_url( 'thumbs/' . $pd[ 'value' ] ),
												'href' => get_url( $pd[ 'value' ] ),
												'rel' => 'us-thumb',
												'title' => $pd[ 'value' ],
												'modal' => TRUE,
												'prevent_cache' => check_var( $advanced_options[ 'prop_is_ud_image_thumb_prevent_cache_admin' ] ) ? TRUE : FALSE,
												
											);
											
											echo vui_el_thumb( $thumb_params );
											
										}
										else if ( check_var( $advanced_options[ 'prop_is_ud_url' ] ) AND check_var( $pd[ 'value' ] ) ) {
											
											echo '<a target="_blank" href="' . get_url( $pd[ 'value' ] ) . '">' . $pd[ 'value' ] . '</a>';
											
										}
										else if ( check_var( $advanced_options[ 'prop_is_ud_title' ] ) AND check_var( $pd[ 'value' ] ) ) {
											
											echo '<a href="' . $user_submit[ 'edit_link' ] . '">' . $pd[ 'value' ] . '</a>';
											
										}
										else if ( check_var( $advanced_options[ 'prop_is_ud_email' ] ) AND check_var( $pd[ 'value' ] ) ) {
											
											echo '<a href="mailto:' . $pd[ 'value' ] . '">' . $pd[ 'value' ] . '</a>';
											
										}
										else if ( isset( $pd[ 'value' ] ) ) {
											
											if ( $fields[ $alias ][ 'field_type' ] == 'textarea' AND ! is_array( $user_submit[ 'data' ][ $alias ] ) ) {
												
												echo word_limiter( htmlspecialchars_decode( $pd[ 'value' ] ) );
												
											}
											else {
												
												echo word_limiter( $pd[ 'value' ] );
												
											}
											
										}
										
									?>
									
								<?php } ?>
								
							<?php } ?>
							
						</td>
						
					<?php } ?>
					
					<?php $current_column = 'operations'; ?>
					
					<td class="col-<?= $current_column; ?>">
						
						<?=
							
							vui_el_button(
								
								array(
									
									'url' => $user_submit[ 'view_link' ],
									'text' => lang( 'action_view' ),
									'icon' => 'view',
									'only_icon' => TRUE,
									'class' => 'modal-users-submits',
									'attr' => array(
										
										'rel' => 'submit-form-users-submits-' . $user_submit[ 'submit_form_id' ],
										'data-mus-last-modal-group' => ( $this->input->get( 'last-modal-group' ) ? $this->input->get( 'last-modal-group' ) : 'users-submits-' . $user_submit[ 'submit_form_id' ] ),
										'data-mus-action' => 'gus',
										'data-user-submit-id' => $user_submit[ 'id' ],
										'data-submit-form-id' => $user_submit[ 'submit_form_id' ],
										
									),
									
								)
								
							);
							
						?>
						
						<?= vui_el_button( array( 'url' => $user_submit[ 'edit_link' ], 'text' => lang( 'action_edit' ), 'icon' => 'edit', 'only_icon' => TRUE, ) ); ?>
						
						<!--<?= vui_el_button( array( 'url' => $user_submit[ 'remove_link' ], 'text' => lang( 'action_delete' ), 'icon' => 'remove', 'only_icon' => TRUE, ) ); ?>-->
						
					</td>
					
				</tr>
				
			<?php } ?>
			
		</table>
		
		<?= form_close(); ?>
		
		<?php if ( ! $this->input->post( 'ajax' ) ) { ?>
		<div class="form-actions to-toolbar">
			
			<?= form_open( current_url(), array( 'id' => 'columns-to-show-form', ) ); ?>
			<ul class="controls-menu menu" id="columns-to-show">
				
				<li class="parent columns-to-show">
					
					<?= vui_el_button(
						
						array(
							
							'text' => lang( 'columns_to_show' ),
							'icon' => 'columns',
							'only_icon' => FALSE,
							'wrapper_class' => 'columns-to-show title',
							
						)
						
					); ?>
					
					<ul>
						
						<?php foreach ( $columns as $key => $column ) {
							
							?><li>
								
								<?php
									
									echo vui_el_checkbox(
										
										array(
											
											'text' => $column[ 'title' ],
											'title' => $column[ 'title' ],
											'value' => $column[ 'alias' ],
											'name' => 'columns_to_show[]',
											'form' => 'columns-to-show-form',
											'checked' => $column[ 'visible' ],
											
										)
										
									);
									
								?>
								
							</li><?php
							
						} ?>
						
						<li class="save-as-default-wrapper columns-to-show-apply-button">
							
							<?= vui_el_button(
								
								array(
									
									'text' => lang( 'save_as_default' ),
									'icon' => 'save',
									'wrapper_class' => 'action',
									'button_type' => 'button',
									'type' => 'submit',
									'value' => 'save_as_default',
									'name' => 'update_columns_to_show',
									'id' => 'update-columns-to-show',
									'form' => 'columns-to-show-form',
									
								)
								
							); ?>
							
						</li>
						
					</ul>
					
					<script type="text/javascript">
						
						$( document ).on( 'ready', function(){
							
							$( '#columns-to-show input[type=checkbox]' ).on( 'change', function( e ){
								
								var c = this.checked ? true : false;
								var v = $( this ).val();
								
								if ( c ){
									
									$( '.col-' + v ).removeClass( 'col-hidden' );
									$( '.col-' + v ).addClass( 'col-visible' );
									
								}
								else {
									
									$( '.col-' + v ).removeClass( 'col-visible' );
									$( '.col-' + v ).addClass( 'col-hidden' );
									
								}
								
							});
							
						});
						
					</script>
					
				</li>
				
			</ul>
			
			<?= form_close(); ?>
			
		</div>
		<?php } ?>
		
		<?php if ( $pagination ){ ?>
		<div class="pagination">
			<?= $pagination; ?>
		</div>
		<?php } ?>

	<?php } else { ?>
		
		<?= vui_el_button( array( 'text' => lang( 'no_users_submits' ), 'icon' => 'error', ) ); ?>
		
	<?php } ?>
	
<?php if ( ! $this->input->post( 'ajax' ) ) { ?>
</div>



<script type="text/javascript" >
	
	$( document ).ready( function(){
		
		$( '#submit-form-terms' ).closest( '.vui-interactive-el-wrapper' ).prependTo( $( '#change-ipp-form' ) );
		$( '.submit-form-field-wrapper-terms' ).remove();
		
		var submit_reset_html = '<?= str_replace( array( "'", "\n", "\t", ), array( "\'", "", "", ), '
			
			<div class="submit-form-field-wrapper submit-form-field-wrapper-submit_reset submit-form-field-wrapper-button">
				
				<div class="submit-form-field-control">
					
					' . vui_el_button(
						
						array(
							
							'id' => 'submit-form-submit_reset',
							'button_type' => 'button',
							'text' => lang( 'submit_reset_filter' ),
							'icon' => 'reset',
							'only_icon' => FALSE,
							'name' => 'users_submits_search[submit_reset]',
							'class' => 'form-element submit-form submit-form-submit_reset',
							'wrapper_class' => 'action',
							
						)
						
					) . '
					
				</div>
				
			</div>
			
		' ); ?>';
		
		$( '.submit-form-field-wrapper-submit_search' ).after( submit_reset_html );
		
		$( document ).on( 'keypress keydown keyup click', '.submit-form-field-wrapper-submit_reset', function(e) {
			
			e.preventDefault(); //STOP default action
			
			var jthis = $( this );
			var form = null;
			
			if ( jthis.attr( 'form' ) ) {
				
				formIdString = '#' + jthis.attr( 'form' );
				form = $( formIdString );
				
			}
			else if ( jthis.closest('form').length > 0 ) {
				
				form = jthis.closest('form');
				formIdString = '#' + form.attr( 'id' );
				
			}
			
			form.find( '[name^="users_submits_search"]' ).each( function( index ) {
				
				$( this ).find( 'option:selected' ).removeAttr( 'selected' );
				$( this ).find( 'option:first' ).attr( 'selected', 'selected' );
				$( this ).val( '' );
				$( this ).trigger( 'change' );
				
			});
			
			$( '[form="us-search-form"]' ).each( function( index ) {
				
				$( this ).find( 'option:selected' ).removeAttr( 'selected' );
				$( this ).find( 'option:first' ).attr( 'selected', 'selected' );
				$( this ).val( '' );
				$( this ).trigger( 'change' );
				
			});
			
			e.preventDefault(); //STOP default action
			
		});
		
		$( document ).on( 'change', '[name^="users_submits_search"]', function(e) {
			
			var jthis = $( this );
			
			jthis.closest( '.submit-form-field-wrapper' ).addClass( 'changed' );
			
		});
		
		// pagination
		$( document ).on( 'keypress keydown keyup click', '.pagination .page-num > a, .pagination .prev > a, .pagination .next > a', function(e) {
			
			e.preventDefault(); //STOP default action
			
			var jthis = $( this );
			
			var postData = { ajax: 'ajax' };
			
			console.log( 'postData: ' + postData );
			
			var URL = jthis.attr( "href" );
			
			$( '#users-submits-list-wrapper' ).addClass( 'loading' );
			
			$.ajax(
			{
				url : URL,
				type: "POST",
				data : postData,
				success: function( data, textStatus, jqXHR ) 
				{
					
				},
				complete: function(e, xhr, settings){
					
					dataText = e.responseText
					
					//console.log( dataText )
					
					$( '#users-submits-list-wrapper' ).html( dataText ).removeClass( 'loading' );
					
					if(e.status === 200){
						
					}else if(e.status === 401){
						
					}else{
						
					}
					
					modalUsersSubmits();
					
				},
				error: function( jqXHR, textStatus, errorThrown ) 
				{
					//if fails      
				}
				
			});
			
			e.preventDefault(); //STOP default action
			
		});
		
		// data list order 
		
		$( '.data-list .order-by > a' ).each( function() {
			
			this.submitting = false;
			
		})
		
		$( document ).on( 'keypress keydown keyup click', '.data-list .order-by > a', function(e) {
			
			e.preventDefault(); //STOP default action
			
			$( '#users-submits-list-wrapper' ).addClass( 'loading' );
			
			reset_toolbar();
			
			console.log( 'this.submitting: ' + this.submitting );
			
			if ( ! this.submitting  ) {
				
				this.submitting = true;
				
				var self = this;
				var jthis = $( this );
				var form1 = null;
				var form1Submit = $( '#submit-form-submit_search' );
				var form2Submit = $( '#submit-change-ipp' );
				
				var postData = null;
				var postData2 = null;
				var form2IdString = null;
				var form2 = null;
				
				
				if ( form1Submit.attr( 'form' ) ) {
					
					form1IdString = '#' + form1Submit.attr( 'form' );
					form1 = $( form1IdString );
					
				}
				else if ( form1Submit.closest('form').length > 0 ) {
					
					form1 = form1Submit.closest('form');
					form1IdString = '#' + form1.attr( 'id' );
					
				}
				
				postData = form1.serializeArray();
				
				
				if ( form2Submit.attr( 'form' ) ) {
					
					form2IdString = '#' + form2Submit.attr( 'form' );
					form2 = $( '#' + form2Submit.attr( 'form' ) );
					
				}
				else if ( form2Submit.closest('form').length > 0 ) {
					
					form2 = form2Submit.closest('form');
					form2IdString = '#' + form2.attr( 'id' );
					
				}
				
				postData = $( form1IdString + ', ' + form2IdString ).serializeArray();
				
				if ( form2 != null ) {
					
					postData = $( form1IdString + ', ' + form2IdString ).serializeArray();
					
				}
				
				postData.push( { name: form1Submit.attr( 'name' ), value: form1Submit.val() } );
				postData.push( { name: form2Submit.attr( 'name' ), value: form2Submit.val() } );
				postData.push( { name: 'ajax', value: 'ajax' } );
				postData.push( { name: 'redirect_c_function', value: '<?= $this->uri->ruri_string() . assoc_array_to_qs(); ?>' } );
				
				
				console.log( 'postData: ' + postData );
				
				var URL = jthis.attr( "href" );
				
				console.log( 'URL: ' + URL );
				
				$( '#users-submits-list-wrapper' ).addClass( 'loading' );
				
				$( '#users-submits-list-wrapper *' ).remove();
				
				console.log( 'Making ajax request...' );
				
				$.ajax({
					
					url : URL,
					type: "POST",
					data : postData,
					success: function( data, textStatus, jqXHR ){
						
						self.submitting = false;
						
						console.log( 'Success!' );
						
					},
					error:  function() {
						
						self.submitting = false;
						
					},
					complete: function(e, xhr, settings){
						
						self.submitting = false;
						
						dataText = e.responseText
						
						$( '#users-submits-list-wrapper' ).html( dataText ).removeClass( 'loading' );
						
						//console.log( e.status )
						
						if( e.status === 200 ){
							
						}else if( e.status === 401 ){
							
						}else{
							
						}
						
						modalUsersSubmits();
						
						console.log( 'Request complete!' );
						
					}
					
				});
				
				console.log( 'Ajax request done!' );
				
			}
			
			e.preventDefault(); //STOP default action
			
		});
		
		//callback handler for form submit
		$( document ).on( 'keypress keydown keyup click', '#submit-form-submit_search, #submit-change-ipp', function(e) {
			
			e.preventDefault(); //STOP default action
			
			reset_toolbar();
			
			var jthis = $( this );
			var form = null;
			
			jthis.blur();
			
			if ( jthis.attr( 'form' ) ) {
				
				formIdString = '#' + jthis.attr( 'form' );
				form = $( formIdString );
				
			}
			else if ( jthis.closest('form').length > 0 ) {
				
				form = jthis.closest('form');
				formIdString = '#' + form.attr( 'id' );
				
			}
			
			$( '#users-submits-list-wrapper' ).addClass( 'loading' );
			
			if ( form != null ){
				
				var postData = form.serializeArray();
				var postData2 = null;
				var form2IdString = null;
				var form2 = null;
				var submitButtonsToPush = null;
				
				if ( jthis.attr( 'id' ) == 'submit-change-ipp' ) {
					
					form2Submit = $( '#submit-form-submit_search' );
					
					if ( form2Submit.attr( 'form' ) ) {
						
						form2IdString = '#' + form2Submit.attr( 'form' );
						form2 = $( form2String );
						
					}
					else if ( form2Submit.closest('form').length > 0 ) {
						
						form2 = form2Submit.closest('form');
						form2IdString = '#' + form2.attr( 'id' );
						
					}
					
					submitButtonsToPush = form2.find( '#submit-form-submit_search' );
					
				}
				if ( jthis.attr( 'id' ) == 'submit-form-submit_search' ) {
					
					form2Submit = $( '#submit-change-ipp' );
					
					if ( form2Submit.attr( 'form' ) ) {
						
						form2IdString = '#' + form2Submit.attr( 'form' );
						form2 = $( '#' + form2Submit.attr( 'form' ) );
						
					}
					else if ( form2Submit.closest('form').length > 0 ) {
						
						form2 = form2Submit.closest('form');
						form2IdString = '#' + form2.attr( 'id' );
						
					}
					
					submitButtonsToPush = form2.find( '#submit-change-ipp' );
					
				}
				
				if ( form2 != null ) {
					
					postData = $( formIdString + ', ' + form2IdString ).serializeArray();
					
					console.log( formIdString + ', ' + form2IdString )
					
					console.log( 'posData:' + postData )
					
				}
				
				postData.push( { name: submitButtonsToPush.attr( 'name' ), value: submitButtonsToPush.val() } );
				
				postData.push( { name: jthis.attr( 'name' ), value: jthis.val() } );
				
				postData.push( { name: 'ajax', value: 'ajax' } );
				
				console.log( postData )
				
				var formURL = form.attr("action");
				$.ajax(
				{
					url : formURL,
					type: "POST",
					data : postData,
					success: function( data, textStatus, jqXHR ) 
					{
						
						$( '#users-submits-list-wrapper' ).html( data );
						
					},
					complete: function(e, xhr, settings){
						
						dataText = e.responseText
						
						console.log( e.status )
						
						$( '#users-submits-list-wrapper' ).removeClass( 'loading' );
						
						if(e.status === 200){
							
						}else if(e.status === 401){
							
						}else{
							
						}
						
						modalUsersSubmits();
						
					},
					error: function( jqXHR, textStatus, errorThrown ) 
					{
						
						console.log( 'erro' )
						     
					}
				});
				
			}
			
		});
		
	});
	
</script>
<?php } ?>