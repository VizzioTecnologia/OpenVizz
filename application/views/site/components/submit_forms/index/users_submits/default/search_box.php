<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
	
	$_show_terms_field = ( check_var( $params[ 'ud_data_availability_site_search' ] ) AND in_array( '__terms', $params[ 'ud_data_availability_site_search' ] ) ) ? TRUE : FALSE;
	
?>

<div id="users-submits-search-wrapper" class="ud-data-list-search-box ud-search-box users-submits-search-wrapper">
	
	<?php
		
		if ( check_var( $params[ 'us_search_pre_text' ] ) AND $pre_text_pos == 'before_search_fields' ) {
			
			if ( check_var( $search_mode ) AND check_var( $params[ 'us_show_search_pre_text_on_search' ] ) ) {
				
				echo $params[ 'us_search_pre_text' ];
				
			}
			else if ( ! check_var( $search_mode ) AND check_var( $params[ 'us_show_search_pre_text_on_normal' ] ) ) {
				
				echo $params[ 'us_search_pre_text' ];
				
			}
			
		}
		
	?>
	
	<?= form_open_multipart( get_url( $this->uri->ruri_string() ) . '#ud-d-search-results-wrapper', array( 'id' => 'us-search-form', ) );
		
		$combo_box_fields_to_search = array();
		
		?>
		
		<?php if ( $_show_terms_field ) { ?>
			
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
			
		}
		
		$props_options = array(
			
			'id' => lang( 'id' ),
			'submit_datetime' => lang( 'submit_datetime' ),
			'mod_datetime' => lang( 'mod_datetime' ),
			
		);
		
		foreach ( $props as $key_2 => $field ) {
			
			$field_name = url_title( $field[ 'alias' ], '-', TRUE );
			$formatted_field_name = 'form[' . $field_name . ']';
			$field_value = ( isset( $post[ 'users_submits_search' ][ 'dinamic_filter_fields' ][ $field_name ] ) ) ? $post[ 'users_submits_search' ][ 'dinamic_filter_fields' ][ $field_name ] : '';
			
			if ( ! in_array( $field[ 'field_type' ], array( 'button', 'html' ) ) ) {
				
				$props_options[ $field[ 'alias' ] ] = $field[ 'label' ];
				
			}
			
			//print_r( $params[ 'ud_data_availability_site_search' ] ); exit;
			
// 			echo '<pre>' . print_r( $params[ 'ud_data_availability_site_search' ], TRUE ) . '</pre>';
			
			if ( $field[ 'field_type' ] == 'combo_box' AND check_var( $params[ 'ud_data_availability_site_search' ][ $field[ 'alias' ] ] ) ) {
				
				$options = array(
					
					'' => lang( 'combobox_select' ),
					
				);
				
				$combo_box_fields_to_search[ $field[ 'alias' ] ] = $field;
				
				if ( check_var( $field[ 'options_from_users_submits' ] ) AND ( check_var( $field[ 'options_title_field' ] ) OR check_var( $field[ 'options_title_field_custom' ] ) ) ) {
					
					$filters = NULL;
					
					if ( check_var( $field[ 'options_filter_site' ] ) ) {
						
						$filters = $field[ 'options_filter_site' ];
						
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
					$this->search->reset_config();
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
		
		?><div class="submit-form-field-wrapper submit-form-field-wrapper-order-by">
			
			<?= form_label( lang( 'order_by' ) ); ?>
			
			<div class="submit-form-field-control">
				
				<?= vui_el_dropdown(
					
					array(
						
						'id' => 'submit-form-' . $field[ 'alias' ],
						'title' => isset( $field[ 'description' ] ) ? lang( $field[ 'description' ] ) : NULL,
						'value' => $order_by,
						'name' => 'users_submits_search[order_by]',
						'options' => $props_options,
						'class' => 'form-element submit-form submit-form-' . $field[ 'alias' ],
						
					)
					
				); ?>
				
			</div>
			
		</div><?php
		
		?><div class="submit-form-field-wrapper submit-form-field-wrapper-order-by-direction">
			
			<?= form_label( lang( 'order_by_direction' ) ); ?>
			
			<div class="submit-form-field-control">
				
				<?= vui_el_dropdown(
					
					array(
						
						'id' => 'submit-form-' . $field[ 'alias' ],
						'title' => isset( $field[ 'description' ] ) ? lang( $field[ 'description' ] ) : NULL,
						'value' => $order_by_direction,
						'name' => 'users_submits_search[order_by_direction]',
						'options' => array(
							
							'asc' => lang( 'ordering_asc' ),
							'desc' => lang( 'ordering_desc' ),
							'random' => lang( 'ordering_random' ),
							
						),
						'class' => 'form-element submit-form submit-form-' . $field[ 'alias' ],
						
					)
					
				); ?>
				
			</div>
			
		</div><?php
		
		?><div class="submit-form-field-wrapper ud-search-box-search-button-wrapper submit-form-field-wrapper-submit_search submit-form-field-wrapper-button">
			
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
						'wrapper_class' => 'action ud-search-box-search-button',
						
					)
					
				); ?>
				
			</div>
			
		</div>
		
	<?= form_close(); ?>
	
	<?php
		
		if ( check_var( $params[ 'us_search_pre_text' ] ) AND $pre_text_pos == 'after_search_fields' ) {
			
			if ( check_var( $search_mode ) AND check_var( $params[ 'us_show_search_pre_text_on_search' ] ) ) {
				
				echo $params[ 'us_search_pre_text' ];
				
			}
			else if ( ! check_var( $search_mode ) AND check_var( $params[ 'us_show_search_pre_text_on_normal' ] ) ) {
				
				echo $params[ 'us_search_pre_text' ];
				
			}
			
		}
		
		$combo_box_fields_to_search = array();
		
	?>
	
</div>
