<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' ); ?>

<div id="users-submits-search-wrapper" class="users-submits-search-wrapper">
	
	<?php
		
		if ( check_var( $submit_form[ 'params' ][ 'us_search_pre_text' ] ) AND $pre_text_pos == 'before_search_fields' ) {
			
			if ( check_var( $search_mode ) AND check_var( $submit_form[ 'params' ][ 'us_show_search_pre_text_on_search' ] ) ) {
				
				echo $submit_form[ 'params' ][ 'us_search_pre_text' ];
				
			}
			else if ( ! check_var( $search_mode ) AND check_var( $submit_form[ 'params' ][ 'us_show_search_pre_text_on_normal' ] ) ) {
				
				echo $submit_form[ 'params' ][ 'us_search_pre_text' ];
				
			}
			
		}
		
	?>
	
	<?= form_open_multipart( get_url( $this->uri->ruri_string() ) . '#ud-d-search-results-wrapper', array( 'id' => 'contact-form', ) );
		
		$combo_box_fields_to_search = array();
		
		?>
		
		<div class="submit-form-field-wrapper submit-form-field-wrapper-terms submit-form-field-wrapper-terms ">
			
			<?php
				
				if ( check_var( $params[ 'search_terms_string' ] ) ) {
					
					echo form_label( lang( $params[ 'search_terms_string' ] ) );
					
				}
				else {
					
					echo form_label( lang( 'search_terms' ) );
					
				}
				
			?>
			
			<div class="submit-form-field-control">
				
				<?= form_input( array( 'id' => 'submit-form-terms', 'name' => 'users_submits_search[terms]', 'class' => 'form-element submit-form submit-form-terms' ), $terms ); ?>
				
			</div>
			
		</div><?php
			
		foreach ( $fields as $key_2 => $field ) {
			
			$field_name = url_title( $field[ 'label' ], '-', TRUE );
			$formatted_field_name = 'form[' . $field_name . ']';
			$field_value = ( isset( $post[ 'users_submits_search' ][ 'dinamic_filter_fields' ][ $field_name ] ) ) ? $post[ 'users_submits_search' ][ 'dinamic_filter_fields' ][ $field_name ] : '';
			
			if ( $field[ 'field_type' ] == 'combo_box' AND check_var( $params[ 'users_submit_search_fields' ] ) AND in_array( $field[ 'alias' ], $params[ 'users_submit_search_fields' ] ) ) {
				
				$options = array(
					
					'' => lang( 'combobox_select' ),
					
				);
				
				$combo_box_fields_to_search[ $field[ 'alias' ] ] = $field;
				
				if ( check_var( $field[ 'options_from_users_submits' ] ) AND ( check_var( $field[ 'options_title_field' ] ) OR check_var( $field[ 'options_title_field_custom' ] ) ) ) {
					
					$search_config = array(
						
						'plugins' => 'sf_us_search',
						'allow_empty_terms' => TRUE,
						'ipp' => 0,
						'cp' => NULL,
						'plugins_params' => array(
							
							'sf_us_search' => array(
								
								'sf_id' => $field[ 'options_from_users_submits' ],
								'order_by' => 'id',
								'filters' => NULL,
								
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
				
				<?= form_label( lang( $field[ 'label' ] ) ); ?>
				
				<div class="submit-form-field-control">
					
					<?= form_dropdown( $filter_fields_input_name . '[' . $field[ 'alias' ] . ']', $options, $field_value, 'id="submit-form-' . $field[ 'alias' ] . '"' . ' class="form-element submit-form submit-form-' . $field[ 'alias' ] . '"' ); ?>
					
				</div>
				
			</div><?php
			
			}
			
		} ?>
		
		<div class="submit-form-field-wrapper submit-form-field-wrapper-submit_search submit-form-field-wrapper-button">
			
			<div class="submit-form-field-control">
				
				<?= form_submit( array( 'id' => 'submit-form-submit_search', 'class' => 'button form-element submit-form submit-form-submit_search', 'name' => 'users_submits_search[submit_search]' ), lang( 'submit_search' ) ); ?>
				
			</div>
			
		</div>
		
	<?= form_close(); ?>
	
	<?php
		
		if ( check_var( $submit_form[ 'params' ][ 'us_search_pre_text' ] ) AND $pre_text_pos == 'after_search_fields' ) {
			
			if ( check_var( $search_mode ) AND check_var( $submit_form[ 'params' ][ 'us_show_search_pre_text_on_search' ] ) ) {
				
				echo $submit_form[ 'params' ][ 'us_search_pre_text' ];
				
			}
			else if ( ! check_var( $search_mode ) AND check_var( $submit_form[ 'params' ][ 'us_show_search_pre_text_on_normal' ] ) ) {
				
				echo $submit_form[ 'params' ][ 'us_search_pre_text' ];
				
			}
			
		}
		
		$combo_box_fields_to_search = array();
		
	?>
	
</div>