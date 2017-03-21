<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
	
	$props = & $fields;
	
?>

<div id="users-submits-search-results-wrapper" class="users-submits-wrapper results">
	
	<?php if ( check_var( $users_submits ) ) { ?>
		
		<?php if ( check_var( $params[ 'show_results_count' ] ) ) { ?>
		
		<div class="users-submits-search-results-title-wrapper">
			
			<h3 class="users-submits-search-results-title">
				
				<?php
					
					if ( $users_submits_total_results > 1 ) {
						
						if ( check_var( $params[ 'users_submits_search_results_string' ] ) ) {
							
							echo sprintf( $params[ 'users_submits_search_results_string' ], '<span class="users-submits-search-count">' . $users_submits_total_results . '</span>' );
							
						}
						else {
							
							echo sprintf( lang( 'users_submits_search_results_string' ), '<span class="users-submits-search-count">' . $users_submits_total_results . '</span>' );
							
						}
						
					}
					else {
						
						if ( check_var( $params[ 'users_submits_search_single_result_string' ] ) ) {
							
							echo sprintf( $params[ 'users_submits_search_single_result_string' ], '<span class="users-submits-search-count">' . $users_submits_total_results . '</span>' );
							
						}
						else {
							
							echo sprintf( lang( 'users_submits_search_single_result_string' ), '<span class="users-submits-search-count">' . $users_submits_total_results . '</span>' );
							
						}
						
					}
					
				?>
				
			</h3>
			
		</div>
		
		<?php } ?>
		
		<?php
		
		$_is_presentation = FALSE;
		
		$_ud_image_props =
		$_ud_title_props =
		$_ud_content_props =
		$_ud_other_info_props =
		$_ud_status_props =
			
			array();
			
		if (
			
			isset( $submit_form[ 'ud_image_prop' ] ) OR
			isset( $submit_form[ 'ud_title_prop' ] ) OR
			isset( $submit_form[ 'ud_content_prop' ] ) OR
			isset( $submit_form[ 'ud_other_info_prop' ] ) OR
			isset( $submit_form[ 'ud_status_prop' ] )
			
		) {
			
			$_is_presentation = TRUE;
			
		}
		
		foreach ( $users_submits as $key => $user_submit ) {
			
			$_us_wrapper_class = array();
			
			// ----------------------------
			// translating values
			
			$user_submit[ 't_data' ] = $user_submit[ 'data' ];
			
			foreach ( $user_submit[ 't_data' ] as $_pk => & $prop_value ) {
				
				$_prop = NULL;
				
				if ( isset( $props[ $_pk ] ) ) {
					
					$_prop = $props[ $_pk ];
					
				}
				
				if ( check_var( $_prop[ 'field_type' ] ) ){
					
					// date
					if ( $_prop[ 'field_type' ] == 'date' ){
					
					// checkbox, combo_box, radiobox
					
						
						$format = '';
						
						$format .= $_prop[ 'sf_date_field_use_year' ] ? 'y' : '';
						$format .= $_prop[ 'sf_date_field_use_month' ] ? 'm' : '';
						$format .= $_prop[ 'sf_date_field_use_day' ] ? 'd' : '';
						
						$format = 'sf_us_dt_ft_pt_' . $format . '_' . $_prop[ 'sf_date_field_presentation_format' ];
						
						$prop_value =  strftime( lang( $format ), strtotime( $prop_value ) );
						
					}
					
					else if ( in_array( $_prop[ 'field_type' ], array( 'checkbox', 'combo_box', 'radiobox', ) ) ){
						
						if ( get_json_array( $prop_value ) ) {
							
							$prop_value = json_decode( $prop_value, TRUE );
							
						}
						
						$_field_value = array();
						
						if ( is_array( $prop_value ) ) {
							
							foreach ( $prop_value as $k => $value ) {
								
								if ( is_string( $value ) ) {
									
									if ( check_var( $_prop[ 'options_from_users_submits' ] ) AND ( check_var( $_prop[ 'options_title_field' ] ) OR check_var( $_prop[ 'options_title_field_custom' ] ) ) AND is_numeric( $value ) AND $_user_submit = $this->sfcm->get_user_submit( $value ) ) {
										
										$value = isset( $_user_submit[ 'data' ][ $_prop[ 'options_title_field' ] ] ) ? $_user_submit[ 'data' ][ $_prop[ 'options_title_field' ] ] : $_user_submit[ 'id' ];
										
										$_field_value[] = $value;
										
									}
									else {
										
										$_field_value[] = $value;
										
									}
									
								}
								
							}
							
							$prop_value = join( ', ', $_field_value );
							
						}
						else {
							
							if ( check_var( $_prop[ 'options_from_users_submits' ] ) AND ( check_var( $_prop[ 'options_title_field' ] ) OR check_var( $_prop[ 'options_title_field_custom' ] ) ) AND is_numeric( $prop_value ) AND $_user_submit = $this->sfcm->get_user_submit( $prop_value ) ) {
								
								$prop_value = isset( $_user_submit[ 'data' ][ $_prop[ 'options_title_field' ] ] ) ? $_user_submit[ 'data' ][ $_prop[ 'options_title_field' ] ] : $_user_submit[ 'id' ];
								
							}
							
						}
						
					}
					
				}
				
				if ( check_var( $prop_value ) AND check_var( $_prop[ 'advanced_options' ][ 'prop_is_ud_image' ] ) ) {
					
					$user_submit[ 't_data' ][ $_pk . '_thumb_default' ] = get_url( $prop_value );
					
					if ( ! url_is_absolute( $prop_value ) ) {
						
						$user_submit[ 't_data' ][ $_pk . '_thumb_default' ] = get_url( THUMBS_DIR_NAME . '/' . $prop_value );
						
					}
					
					$prop_value = get_url( $prop_value );
					
				}
				
				if ( check_var( $prop_value ) AND check_var( $_prop[ 'advanced_options' ][ 'prop_is_ud_url' ] ) ) {
					
					$prop_value = get_url( $prop_value );
					
				}
				
			}
			
			//echo '---------------------<pre>' . print_r( $user_submit, TRUE ) . '</pre>'; exit;
			
			// translating values
			// ----------------------------
			
			$_us_wrapper_class = join( ' ', $_us_wrapper_class );
			
			if ( $_is_presentation ) {
				
				// image
				
				if ( check_var( $submit_form[ 'ud_image_prop' ] ) ) {
					
					if ( check_var( $params[ 'ud_image_prop' ] ) ) {
						
						if ( check_var( $params[ 'ud_image_prop' ] ) AND in_array( 'id', $params[ 'ud_image_prop' ] ) ) {
							
							$_ud_image_props[ 'id' ][ 'label' ] = lang( 'id' );
							$_ud_image_props[ 'id' ][ 'value' ] = $user_submit[ 'id' ];
							
						}
						
						if ( check_var( $params[ 'ud_image_prop' ] ) AND in_array( 'submit_datetime', $params[ 'ud_image_prop' ] ) ) {
							
							$_ud_image_props[ 'submit_datetime' ][ 'label' ] = lang( 'submit_datetime' );
							$_ud_image_props[ 'submit_datetime' ][ 'value' ] = strftime( lang( 'sf_us_dt_ft_pt_ymd_1' ), strtotime( $user_submit[ 'submit_datetime' ] ) );
							
						}
						
						if ( check_var( $params[ 'ud_image_prop' ] ) AND in_array( 'mod_datetime', $params[ 'ud_image_prop' ] ) ) {
							
							$_ud_image_props[ 'mod_datetime' ][ 'label' ] = lang( 'mod_datetime' );
							$_ud_image_props[ 'mod_datetime' ][ 'value' ] = strftime( lang( 'sf_us_dt_ft_pt_ymd_1' ), strtotime( $user_submit[ 'mod_datetime' ] ) );
							
						}
						
					}
					
					if ( check_var( $submit_form[ 'ud_image_prop' ] ) ) {
						
						foreach ( $submit_form[ 'ud_image_prop' ] as $_alias => $_field ) {
							
							$_ud_image_props[ $_alias ][ 'label' ] = lang( $props[ $_alias ][ 'presentation_label' ] );
							$_ud_image_props[ $_alias ][ 'value' ] = $user_submit[ 't_data' ][ $_alias ];
							
						}
						
					}
					
				}
				
				// title
				
				if ( check_var( $submit_form[ 'ud_title_prop' ] ) ) {
					
					if ( check_var( $params[ 'ud_title_prop' ] ) ) {
						
						if ( check_var( $params[ 'ud_title_prop' ] ) AND in_array( 'id', $params[ 'ud_title_prop' ] ) ) {
							
							$_titles_fields[ 'id' ][ 'label' ] = lang( 'id' );
							$_titles_fields[ 'id' ][ 'value' ] = $user_submit[ 'id' ];
							
						}
						
						if ( check_var( $params[ 'ud_title_prop' ] ) AND in_array( 'submit_datetime', $params[ 'ud_title_prop' ] ) ) {
							
							$_titles_fields[ 'submit_datetime' ][ 'label' ] = lang( 'submit_datetime' );
							$_titles_fields[ 'submit_datetime' ][ 'value' ] = strftime( lang( 'sf_us_dt_ft_pt_ymd_1' ), strtotime( $user_submit[ 'submit_datetime' ] ) );
							
						}
						
						if ( check_var( $params[ 'ud_title_prop' ] ) AND in_array( 'mod_datetime', $params[ 'ud_title_prop' ] ) ) {
							
							$_titles_fields[ 'mod_datetime' ][ 'label' ] = lang( 'mod_datetime' );
							$_titles_fields[ 'mod_datetime' ][ 'value' ] = strftime( lang( 'sf_us_dt_ft_pt_ymd_1' ), strtotime( $user_submit[ 'mod_datetime' ] ) );
							
						}
						
					}
					
					if ( check_var( $submit_form[ 'ud_title_prop' ] ) ) {
						
						foreach ( $submit_form[ 'ud_title_prop' ] as $_alias => $_field ) {
							
							$_titles_fields[ $_alias ][ 'label' ] = lang( $props[ $_alias ][ 'presentation_label' ] );
							$_titles_fields[ $_alias ][ 'value' ] = $user_submit[ 't_data' ][ $_alias ];
							
						}
						
					}
					
				}
				
				// content
				
				if ( check_var( $submit_form[ 'ud_content_prop' ] ) ) {
					
					if ( check_var( $params[ 'ud_content_prop' ] ) ) {
						
						if ( check_var( $params[ 'ud_content_prop' ] ) AND in_array( 'id', $params[ 'ud_content_prop' ] ) ) {
							
							$_contents_fields[ 'id' ][ 'label' ] = lang( 'id' );
							$_contents_fields[ 'id' ][ 'value' ] = $user_submit[ 'id' ];
							
						}
						
						if ( check_var( $params[ 'ud_content_prop' ] ) AND in_array( 'submit_datetime', $params[ 'ud_content_prop' ] ) ) {
							
							$_contents_fields[ 'submit_datetime' ][ 'label' ] = lang( 'submit_datetime' );
							$_contents_fields[ 'submit_datetime' ][ 'value' ] = strftime( lang( 'sf_us_dt_ft_pt_ymd_1' ), strtotime( $user_submit[ 'submit_datetime' ] ) );
							
						}
						
						if ( check_var( $params[ 'ud_content_prop' ] ) AND in_array( 'mod_datetime', $params[ 'ud_content_prop' ] ) ) {
							
							$_contents_fields[ 'mod_datetime' ][ 'label' ] = lang( 'mod_datetime' );
							$_contents_fields[ 'mod_datetime' ][ 'value' ] = strftime( lang( 'sf_us_dt_ft_pt_ymd_1' ), strtotime( $user_submit[ 'mod_datetime' ] ) );
							
						}
						
					}
					
					if ( check_var( $submit_form[ 'ud_content_prop' ] ) ) {
						
						foreach ( $submit_form[ 'ud_content_prop' ] as $_alias => $_field ) {
							
							$_contents_fields[ $_alias ][ 'label' ] = lang( $props[ $_alias ][ 'presentation_label' ] );
							$_contents_fields[ $_alias ][ 'value' ] = $user_submit[ 't_data' ][ $_alias ];
							
						}
						
					}
					
				}
				
				// other info
				
				if ( check_var( $submit_form[ 'ud_other_info_prop' ] ) ) {
					
					if ( check_var( $params[ 'ud_other_info_prop' ] ) ) {
						
						if ( check_var( $params[ 'ud_other_info_prop' ] ) AND in_array( 'id', $params[ 'ud_other_info_prop' ] ) ) {
							
							$_ud_other_info_props[ 'id' ][ 'label' ] = lang( 'id' );
							$_ud_other_info_props[ 'id' ][ 'value' ] = $user_submit[ 'id' ];
							
						}
						
						if ( check_var( $params[ 'ud_other_info_prop' ] ) AND in_array( 'submit_datetime', $params[ 'ud_other_info_prop' ] ) ) {
							
							$_ud_other_info_props[ 'submit_datetime' ][ 'label' ] = lang( 'submit_datetime' );
							$_ud_other_info_props[ 'submit_datetime' ][ 'value' ] = strftime( lang( 'sf_us_dt_ft_pt_ymd_1' ), strtotime( $user_submit[ 'submit_datetime' ] ) );
							
						}
						
						if ( check_var( $params[ 'ud_other_info_prop' ] ) AND in_array( 'mod_datetime', $params[ 'ud_other_info_prop' ] ) ) {
							
							$_ud_other_info_props[ 'mod_datetime' ][ 'label' ] = lang( 'mod_datetime' );
							$_ud_other_info_props[ 'mod_datetime' ][ 'value' ] = strftime( lang( 'sf_us_dt_ft_pt_ymd_1' ), strtotime( $user_submit[ 'mod_datetime' ] ) );
							
						}
						
					}
					
					if ( check_var( $submit_form[ 'ud_other_info_prop' ] ) ) {
						
						foreach ( $submit_form[ 'ud_other_info_prop' ] as $_alias => $_field ) {
							
							$_ud_other_info_props[ $_alias ][ 'label' ] = lang( $props[ $_alias ][ 'presentation_label' ] );
							$_ud_other_info_props[ $_alias ][ 'value' ] = $user_submit[ 't_data' ][ $_alias ];
							
						}
						
					}
					
				}
				
				// status
				
				if ( check_var( $submit_form[ 'ud_status_prop' ] ) ) {
					
					if ( check_var( $params[ 'ud_status_prop' ] ) ) {
						
						if ( check_var( $params[ 'ud_status_prop' ] ) AND in_array( 'id', $params[ 'ud_status_prop' ] ) ) {
							
							$_ud_status_props[ 'id' ][ 'label' ] = lang( 'id' );
							$_ud_status_props[ 'id' ][ 'value' ] = $user_submit[ 'id' ];
							
						}
						
						if ( check_var( $params[ 'ud_status_prop' ] ) AND in_array( 'submit_datetime', $params[ 'ud_status_prop' ] ) ) {
							
							$_ud_status_props[ 'submit_datetime' ][ 'label' ] = lang( 'submit_datetime' );
							$_ud_status_props[ 'submit_datetime' ][ 'value' ] = strftime( lang( 'sf_us_dt_ft_pt_ymd_1' ), strtotime( $user_submit[ 'submit_datetime' ] ) );
							
						}
						
						if ( check_var( $params[ 'ud_status_prop' ] ) AND in_array( 'mod_datetime', $params[ 'ud_status_prop' ] ) ) {
							
							$_ud_status_props[ 'mod_datetime' ][ 'label' ] = lang( 'mod_datetime' );
							$_ud_status_props[ 'mod_datetime' ][ 'value' ] = strftime( lang( 'sf_us_dt_ft_pt_ymd_1' ), strtotime( $user_submit[ 'mod_datetime' ] ) );
							
						}
						
					}
					
					if ( check_var( $submit_form[ 'ud_status_prop' ] ) ) {
						
						foreach ( $submit_form[ 'ud_status_prop' ] as $_alias => $_field ) {
							
							$_ud_status_props[ $_alias ][ 'label' ] = lang( $props[ $_alias ][ 'presentation_label' ] );
							$_ud_status_props[ $_alias ][ 'value' ] = $user_submit[ 't_data' ][ $_alias ];
							
						}
						
					}
					
				}
				
				$_us_status = array();
				$_us_status_classes = '';
				$_us_has_status = FALSE;
				
				if ( check_var( $submit_form[ 'ud_status_prop' ] ) ) {
					
					foreach( $submit_form[ 'fields' ] as $status_field ) {
						
						if ( isset( $submit_form[ 'ud_status_prop' ][ $status_field[ 'alias' ] ] ) ) {
							
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
									
									if ( check_var( $status_field[ 'advanced_options' ][ $_item ] ) AND $user_submit[ 't_data' ][ $status_field[ 'alias' ] ] == $status_field[ 'advanced_options' ][ $_item ] ) {
										
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
				
				foreach ( $_ud_image_props as $_alias => $_field ) {
					
					if ( check_var( $user_submit[ 't_data' ][ $_alias ] ) ) {
						
						//echo '<pre>' . print_r( $user_submit, TRUE ) . '</pre>';
						
						$thumb_params = array(
							
							'wrapper_class' => 'us-image-wrapper',
							'src' => get_url( $user_submit[ 't_data' ][ $_alias . '_thumb_default' ] ),
							'href' => get_url( $user_submit[ 't_data' ][ $_alias ] ),
							'rel' => 'us-thumb',
							'title' => $user_submit[ 't_data' ][ $_alias ],
							'modal' => TRUE,
							'prevent_cache' => check_var( $advanced_options[ 'prop_is_ud_image_thumb_prevent_cache_' . environment() ] ) ? TRUE : FALSE,
							
						);
						
						echo vui_el_thumb( $thumb_params );
						
					}
					
				}
				
				foreach ( $_titles_fields as $_alias => $_field ) {
					
					if ( check_var( $user_submit[ 't_data' ][ $_alias ] ) ) {
						
						if ( $_field AND isset( $props[ $_alias ] ) ) {
							
							require( 'titles.php' );
							
						}
						
					}
					
				}
				
				foreach ( $_contents_fields as $_alias => $_field ) {
					
					if ( check_var( $user_submit[ 't_data' ][ $_alias ] ) ) {
						
						if ( $_field AND isset( $props[ $_alias ] ) ) {
							
							require( 'contents.php' );
							
						}
						
					}
					
				}
				
				foreach ( $_ud_other_info_props as $_alias => $_field ) {
					
					if ( check_var( $user_submit[ 't_data' ][ $_alias ] ) ) {
						
						if ( $_field AND isset( $props[ $_alias ] ) ) {
							
							require( 'other_info.php' );
							
						}
						
					}
					
				}
				
				foreach ( $_ud_status_props as $_alias => $_field ) {
					
					if ( check_var( $user_submit[ 't_data' ][ $_alias ] ) ) {
						
						if ( $_field AND isset( $props[ $_alias ] ) ) {
							
							require( 'status.php' );
							
						}
						
					}
					
				}
				
				// ---------------------------
				
			}
			
		}
		
	} ?>
	
</div>
	