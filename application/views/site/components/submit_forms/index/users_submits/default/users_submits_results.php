<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
	
	$props = & $fields;
	
?>

<div id="users-submits-search-results-wrapper" class="users-submits-wrapper results ud-data-list">
	
	<?php if ( check_var( $users_submits ) ) { ?>
		
		<?php if ( check_var( $params[ 'show_results_count' ] ) ) { ?>
		
		<div class="users-submits-search-results-title-wrapper ud-data-list-count">
			
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
		$_ud_event_datetime_props =
			
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
		
// 		echo '<pre>' . print_r( $users_submits, TRUE ) . '</pre>';
		
		echo '<div class="items ud-data-items">';
		
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
						
						$prop_y = date( 'Y', strtotime( $prop_value ) );
						$prop_m = date( 'm', strtotime( $prop_value ) );
						$prop_d = date( 'd', strtotime( $prop_value ) );
						
						$current_date[ 'y' ] = date( 'Y', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) );
						$current_date[ 'm' ] = date( 'm', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) );
						$current_date[ 'd' ] = date( 'd', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) );
						
						/*
						echo '<br/>---------------------';
						echo '<br/>$prop_value- ' . $prop_value;
						echo '<br/>y-' . ( $prop_y - $current_date[ 'y' ] );
						echo '<br/>m-' . ( $prop_m - $current_date[ 'm' ] );
						echo '<br/>d-' . ( $prop_d - $current_date[ 'd' ] );
						echo '<br/>strtotime- ' . strtotime( $prop_value );
						echo '<br/>gmt_to_local- ' . gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] );
						
						$date = new DateTime( $prop_value );
						$now = new DateTime( date( 'Y-m-d', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) ) );
						
						$difference = $date->diff( $now, FALSE );
						
						echo '<br/>' . $date->diff( $now )->format("%y years, %b months, %d days, %h hours and %i minuts");;
						echo '<br/>' . ( strtotime( $prop_value ) - strtotime( date( 'Y-m-d', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) ) ) );
						
						echo '<br/>' . $difference->y;
						echo '<br/>' . $difference->m;
						echo '<br/>' . $difference->d;
						
						echo '<br/>' . s_datediff( 'y', date( 'Y-m-d', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) ), $prop_value, FALSE );
						echo '<br/>' . s_datediff( 'm', date( 'Y-m-d', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) ), $prop_value, FALSE );
						echo '<br/>' . s_datediff( 'd', date( 'Y-m-d', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) ), $prop_value, FALSE );
						*/
						
						$format = '';
						
						$format .= ( check_var( $_prop[ 'sf_date_field_use_year' ] ) AND ! ( $prop_y == $current_date[ 'y' ] AND check_var( $_prop[ 'sf_date_field_hide_current_year' ] ) ) ) ? 'y' : '';
						$format .= ( check_var( $_prop[ 'sf_date_field_use_month' ] ) AND ! ( $prop_m == $current_date[ 'm' ] AND $prop_y == $current_date[ 'y' ] AND check_var( $_prop[ 'sf_date_field_hide_current_month' ] ) ) ) ? 'm' : '';
						$format .= ( check_var( $_prop[ 'sf_date_field_use_day' ] ) AND ! ( $prop_d == $current_date[ 'd' ] AND $prop_m == $current_date[ 'm' ] AND $prop_y == $current_date[ 'y' ] AND check_var( $_prop[ 'sf_date_field_hide_current_day' ] ) ) ) ? 'd' : '';
						
						if ( $format != '' ) {
							
							$format = 'sf_us_dt_ft_pt_' . $format . '_' . ( check_var( $_prop[ 'sf_date_field_presentation_format' ] ) ? $_prop[ 'sf_date_field_presentation_format' ] : 'ymd' );
							
						}
						
						$relative_datetime = '';
						
						$is_today = FALSE;
						$is_yesterday = FALSE;
						$is_tomorrow = FALSE;
						
						if ( check_var( $_prop[ 'sf_date_field_relative_datetime' ] ) AND date_is_valid( $prop_value ) ) {
							
							$date = new DateTime( $prop_value );
							$now = new DateTime( date( 'Y-m-d', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) ) );
							$yesterday = new DateTime( date( 'Y-m-d', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) ) );
							$yesterday->sub( date_interval_create_from_date_string( '1 day' ) );
							$tomorrow = new DateTime( date( 'Y-m-d', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) ) );
							$tomorrow->add( date_interval_create_from_date_string( '1 day' ) );
							
							$difference = $date->diff( $now );
							
							$_y = $difference->y;
							$_m = $difference->m;
							$_d = $difference->d;
							$_h = $difference->h;
							$_i = $difference->i;
							$_s = $difference->s;
							
							$relative_datetime_str_prefix_fw = 'sf_us_dt_relative_fw_';
							$relative_datetime_str_prefix_bw = 'sf_us_dt_relative_bw_';
							
							if ( $date == $now ) {
								
								$is_today = TRUE;
								
								$_us_wrapper_class[] = 'has-ud-event-datetime-is-today';
								
								$_us_wrapper_class[] = 'is-today';
								
								$relative_datetime = lang( 'sf_us_dt_relative_today' );
								
							}
							else if ( $date == $yesterday ) {
								
								$is_yesterday = TRUE;
								
								$_us_wrapper_class[] = 'has-ud-event-datetime-is-yesterday';
								
								$_us_wrapper_class[] = 'is-yesterday';
								
								$relative_datetime = lang( 'sf_us_dt_relative_yesterday' );
								
							}
							else if ( $date == $tomorrow ) {
								
								$is_tomorrow = TRUE;
								
								$_us_wrapper_class[] = 'has-ud-event-datetime-is-tomorrow';
								
								$_us_wrapper_class[] = 'is-tomorrow';
								
								$relative_datetime = lang( 'sf_us_dt_relative_tomorrow' );
								
							}
							else if ( $_y != 0 ) {
								
								if ( $_y == 1 AND $date > $now ) {
									
									$relative_datetime = sprintf( lang( $relative_datetime_str_prefix_fw . 'y' ), $difference->y );
									
								}
								else if ( $_y == 1 AND $date < $now ) {
									
									$relative_datetime = sprintf( lang( $relative_datetime_str_prefix_bw . 'y' ), $difference->y );
									
								}
								else if ( $date < $now ) {
									
									$relative_datetime = sprintf( lang( $relative_datetime_str_prefix_bw . 'plural_' . 'y' ), $difference->y );
									
								}
								else if ( $date > $now ) {
									
									$relative_datetime = sprintf( lang( $relative_datetime_str_prefix_fw . 'plural_' . 'y' ), $difference->y );
									
								}
								
							}
							else if ( $_m != 0 ) {
								
								if ( $_m == 1 AND $date > $now ) {
									
									$relative_datetime = sprintf( lang( $relative_datetime_str_prefix_fw . 'm' ), $difference->m );
									
								}
								else if ( $_m == 1 AND $date < $now ) {
									
									$relative_datetime = sprintf( lang( $relative_datetime_str_prefix_bw . 'm' ), $difference->m );
									
								}
								else if ( $date < $now ) {
									
									$relative_datetime = sprintf( lang( $relative_datetime_str_prefix_bw . 'plural_' . 'm' ), $difference->m );
									
								}
								else if ( $date > $now ) {
									
									$relative_datetime = sprintf( lang( $relative_datetime_str_prefix_fw . 'plural_' . 'm' ), $difference->m );
									
								}
								
							}
							else if ( $_d != 0 ) {
								
								if ( $date < $now ) {
									
									$relative_datetime = sprintf( lang( $relative_datetime_str_prefix_bw . 'plural_' . 'd' ), $difference->d );
									
								}
								else if ( $date > $now ) {
									
									$relative_datetime = sprintf( lang( $relative_datetime_str_prefix_fw . 'plural_' . 'd' ), $difference->d );
									
								}
								
							}
							
						}
						
						$_prop_value = $prop_value;
						
						$prop_value = '';
							
						if ( $format != '' ) {
							
							$prop_value .= '<span class="normal-datetime">' . strftime( lang( $format ), strtotime( $_prop_value ) ) . '</span>';
							
						}
						
						$prop_value .=
							
							( $relative_datetime ? '<span class="relative-datetime' . ( ( $is_today ) ? ' is-today' : ( ( $is_yesterday ) ? ' is-yesterday' : ( ( $is_tomorrow ) ? ' is-tomorrow' : '' ) ) ) . '">'
							. $relative_datetime
							. '</span> ' : '' );
							
					}
					
					// checkbox, combo_box, radiobox
					
					else if ( in_array( $_prop[ 'field_type' ], array( 'checkbox', 'combo_box', 'radiobox', ) ) ){
						
						if ( ! is_array( $prop_value ) AND get_json_array( $prop_value ) ) {
							
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
				
				if ( check_var( $prop_value ) AND check_var( $_prop[ 'advanced_options' ][ 'prop_is_ud_content' ] ) ) {
					
					$content_word_limit = check_var( $params[ 'users_submit_content_word_limit' ] ) ? $params[ 'users_submit_content_word_limit' ] : 120;
					$word_limit_str = '...';
					
					if ( $content_word_limit > 0 AND strlen( $user_submit[ 't_data' ][ $_pk ] ) > $content_word_limit ) {
						
						$prop_value = word_limiter( htmlspecialchars_decode( $user_submit[ 't_data' ][ $_pk ] ), $content_word_limit, $word_limit_str );
						
					}
					else {
						
						$prop_value = word_limiter( htmlspecialchars_decode( $user_submit[ 't_data' ][ $_pk ] ) );
						
					}
					
				}
				
				if ( check_var( $prop_value ) AND check_var( $_prop[ 'advanced_options' ][ 'prop_is_ud_url' ] ) ) {
					
					$_tmp = preg_split( "/(;| |,)/", $prop_value );
					$_tmp_2 = array();
					
					if ( is_array( $_tmp ) ) {
						
						foreach( $_tmp as $k => $v ) {
							
							if ( trim( $v ) != '' ) {
								
								$v = get_url( $v );
								$_tmp_2[] = '<a href="' . $v . '" target="_blank">' . $v . '</a>';
								
							}
							
						}
						
						$prop_value = join( '<br/>', $_tmp_2 );
						
					}
					else {
						
						$___url = get_url( $prop_value );
						$prop_value = '<a href="' . $___url . '" target="_blank"' . '>' . $___url . '</a>';
						
					}
					
				}
				
				if ( check_var( $prop_value ) AND check_var( $_prop[ 'advanced_options' ][ 'prop_is_ud_email' ] ) ) {
					
					$_tmp = preg_split( "/(;| |,|\/)/", $prop_value );
					$_tmp_2 = array();
					
					if ( is_array( $_tmp ) ) {
						
						foreach( $_tmp as $k => $v ) {
							
							if ( trim( $v ) != '' ) {
								
								$_tmp_2[] = '<a href="mailto:' . $v . '">' . $v . '</a>';
								
							}
							
						}
						
						$prop_value = join( '<br/>', $_tmp_2 );
						
					}
					else {
						
						$prop_value = '<a href="mailto:' . $prop_value . '">' . $prop_value . '</a>';
						
					}
					
				}
				
				$_us_wrapper_class[] = 'ud-data-item-value-' . url_title( $prop_value, '-', TRUE );
				
			}
			
			//echo '---------------------<pre>' . print_r( $user_submit, TRUE ) . '</pre>'; exit;
			
			// translating values
			// ----------------------------
			
			if ( $_is_presentation ) {
				
				// image
				
				if ( check_var( $submit_form[ 'ud_image_prop' ] ) ) {
					
					$__class = FALSE;
					
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
					
					foreach ( $submit_form[ 'ud_image_prop' ] as $_alias => $_field ) {
						
						if ( check_var( $props[ $_alias ][ 'visibility' ][ 'site' ][ 'list' ] ) AND isset( $user_submit[ 't_data' ][ $_alias ] ) AND ! empty( $user_submit[ 't_data' ][ $_alias ] ) ) {
							
							if ( ! $__class ) $__class = 'has-ud-image';
							
							$_ud_image_props[ $_alias ][ 'label' ] = lang( $props[ $_alias ][ 'presentation_label' ] );
							$_ud_image_props[ $_alias ][ 'value' ] = $user_submit[ 't_data' ][ $_alias ];
							
						}
						
					}
					
					if ( $__class ) $_us_wrapper_class[] = $__class;
					
				}
				
				// title
				
				if ( check_var( $submit_form[ 'ud_title_prop' ] ) ) {
					
					$__class = FALSE;
					
					if ( check_var( $params[ 'ud_title_prop' ] ) ) {
						
						if ( check_var( $params[ 'ud_title_prop' ] ) AND in_array( 'id', $params[ 'ud_title_prop' ] ) ) {
							
							$_ud_title_props[ 'id' ][ 'label' ] = lang( 'id' );
							$_ud_title_props[ 'id' ][ 'value' ] = $user_submit[ 'id' ];
							
						}
						
						if ( check_var( $params[ 'ud_title_prop' ] ) AND in_array( 'submit_datetime', $params[ 'ud_title_prop' ] ) ) {
							
							$_ud_title_props[ 'submit_datetime' ][ 'label' ] = lang( 'submit_datetime' );
							$_ud_title_props[ 'submit_datetime' ][ 'value' ] = strftime( lang( 'sf_us_dt_ft_pt_ymd_1' ), strtotime( $user_submit[ 'submit_datetime' ] ) );
							
						}
						
						if ( check_var( $params[ 'ud_title_prop' ] ) AND in_array( 'mod_datetime', $params[ 'ud_title_prop' ] ) ) {
							
							$_ud_title_props[ 'mod_datetime' ][ 'label' ] = lang( 'mod_datetime' );
							$_ud_title_props[ 'mod_datetime' ][ 'value' ] = strftime( lang( 'sf_us_dt_ft_pt_ymd_1' ), strtotime( $user_submit[ 'mod_datetime' ] ) );
							
						}
						
					}
					
					foreach ( $submit_form[ 'ud_title_prop' ] as $_alias => $_field ) {
						
						if ( check_var( $props[ $_alias ][ 'visibility' ][ 'site' ][ 'list' ] ) AND isset( $user_submit[ 't_data' ][ $_alias ] ) AND ! empty( $user_submit[ 't_data' ][ $_alias ] ) ) {
							
							if ( ! $__class ) $__class = 'has-ud-title';
							
							$_ud_title_props[ $_alias ][ 'label' ] = lang( $props[ $_alias ][ 'presentation_label' ] );
							$_ud_title_props[ $_alias ][ 'value' ] = $user_submit[ 't_data' ][ $_alias ];
							
						}
						
					}
					
					if ( $__class ) $_us_wrapper_class[] = $__class;
					
				}
				
				// event datetime
				
				if ( check_var( $submit_form[ 'ud_event_datetime_prop' ] ) ) {
					
					$__class = FALSE;
					
					if ( check_var( $params[ 'ud_event_datetime_prop' ] ) ) {
						
						if ( check_var( $params[ 'ud_event_datetime_prop' ] ) AND in_array( 'id', $params[ 'ud_event_datetime_prop' ] ) ) {
							
							$_ud_event_datetime_props[ 'id' ][ 'label' ] = lang( 'id' );
							$_ud_event_datetime_props[ 'id' ][ 'value' ] = $user_submit[ 'id' ];
							
						}
						
						if ( check_var( $params[ 'ud_event_datetime_prop' ] ) AND in_array( 'submit_datetime', $params[ 'ud_event_datetime_prop' ] ) ) {
							
							$_ud_event_datetime_props[ 'submit_datetime' ][ 'label' ] = lang( 'submit_datetime' );
							$_ud_event_datetime_props[ 'submit_datetime' ][ 'value' ] = strftime( lang( 'sf_us_dt_ft_pt_ymd_1' ), strtotime( $user_submit[ 'submit_datetime' ] ) );
							
						}
						
						if ( check_var( $params[ 'ud_event_datetime_prop' ] ) AND in_array( 'mod_datetime', $params[ 'ud_event_datetime_prop' ] ) ) {
							
							$_ud_event_datetime_props[ 'mod_datetime' ][ 'label' ] = lang( 'mod_datetime' );
							$_ud_event_datetime_props[ 'mod_datetime' ][ 'value' ] = strftime( lang( 'sf_us_dt_ft_pt_ymd_1' ), strtotime( $user_submit[ 'mod_datetime' ] ) );
							
						}
						
					}
					
					foreach ( $submit_form[ 'ud_event_datetime_prop' ] as $_alias => $_field ) {
						
						if ( check_var( $props[ $_alias ][ 'visibility' ][ 'site' ][ 'list' ] ) AND isset( $user_submit[ 't_data' ][ $_alias ] ) AND ! empty( $user_submit[ 't_data' ][ $_alias ] ) ) {
							
							if ( ! $__class ) $__class = 'has-ud-event-datetime';
							
							$_ud_event_datetime_props[ $_alias ][ 'label' ] = lang( $props[ $_alias ][ 'presentation_label' ] );
							$_ud_event_datetime_props[ $_alias ][ 'value' ] = $user_submit[ 't_data' ][ $_alias ];
							
						}
						
					}
					
					if ( $__class ) $_us_wrapper_class[] = $__class;
					
				}
				
				// content
				
				if ( check_var( $submit_form[ 'ud_content_prop' ] ) AND ! empty( $submit_form[ 'ud_content_prop' ] ) ) {
					
					$__class = FALSE;
					
					if ( check_var( $params[ 'ud_content_prop' ] ) ) {
						
						if ( check_var( $params[ 'ud_content_prop' ] ) AND in_array( 'id', $params[ 'ud_content_prop' ] ) ) {
							
							$_ud_content_props[ 'id' ][ 'label' ] = lang( 'id' );
							$_ud_content_props[ 'id' ][ 'value' ] = $user_submit[ 'id' ];
							
						}
						
						if ( check_var( $params[ 'ud_content_prop' ] ) AND in_array( 'submit_datetime', $params[ 'ud_content_prop' ] ) ) {
							
							$_ud_content_props[ 'submit_datetime' ][ 'label' ] = lang( 'submit_datetime' );
							$_ud_content_props[ 'submit_datetime' ][ 'value' ] = strftime( lang( 'sf_us_dt_ft_pt_ymd_1' ), strtotime( $user_submit[ 'submit_datetime' ] ) );
							
						}
						
						if ( check_var( $params[ 'ud_content_prop' ] ) AND in_array( 'mod_datetime', $params[ 'ud_content_prop' ] ) ) {
							
							$_ud_content_props[ 'mod_datetime' ][ 'label' ] = lang( 'mod_datetime' );
							$_ud_content_props[ 'mod_datetime' ][ 'value' ] = strftime( lang( 'sf_us_dt_ft_pt_ymd_1' ), strtotime( $user_submit[ 'mod_datetime' ] ) );
							
						}
						
					}
					
					foreach ( $submit_form[ 'ud_content_prop' ] as $_alias => $_field ) {
						
						if ( check_var( $props[ $_alias ][ 'visibility' ][ 'site' ][ 'list' ] ) AND isset( $user_submit[ 't_data' ][ $_alias ] ) AND ! empty( $user_submit[ 't_data' ][ $_alias ] ) ) {
							
							if ( ! $__class ) $__class = 'has-ud-content';
							
							$_ud_content_props[ $_alias ][ 'label' ] = lang( $props[ $_alias ][ 'presentation_label' ] );
							$_ud_content_props[ $_alias ][ 'value' ] = $user_submit[ 't_data' ][ $_alias ];
							
						}
						
					}
					
					if ( $__class ) $_us_wrapper_class[] = $__class;
					
				}
				
				// other info
				
				if ( check_var( $submit_form[ 'ud_other_info_prop' ] ) AND ! empty( $submit_form[ 'ud_other_info_prop' ] ) ) {
					
					$__class = FALSE;
					
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
					
					foreach ( $submit_form[ 'ud_other_info_prop' ] as $_alias => $_field ) {
						
						if ( check_var( $props[ $_alias ][ 'visibility' ][ 'site' ][ 'list' ] ) AND isset( $user_submit[ 't_data' ][ $_alias ] ) AND ! empty( $user_submit[ 't_data' ][ $_alias ] ) ) {
							
							if ( ! $__class ) $__class = 'has-ud-other_info';
							
							$_ud_other_info_props[ $_alias ][ 'label' ] = lang( $props[ $_alias ][ 'presentation_label' ] );
							$_ud_other_info_props[ $_alias ][ 'value' ] = $user_submit[ 't_data' ][ $_alias ];
							
						}
						
					}
					
					if ( $__class ) $_us_wrapper_class[] = $__class;
					
				}
				
				// status
				
				if ( check_var( $submit_form[ 'ud_status_prop' ] ) AND ! empty( $submit_form[ 'ud_status_prop' ] ) ) {
					
					$_us_wrapper_class[] = 'has-ud-status';
					
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
					
					foreach ( $submit_form[ 'ud_status_prop' ] as $_alias => $_field ) {
						
						if ( check_var( $props[ $_alias ][ 'visibility' ][ 'site' ][ 'list' ] ) AND isset( $user_submit[ 't_data' ][ $_alias ] ) AND ! empty( $user_submit[ 't_data' ][ $_alias ] ) ) {
							
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
				
				$_us_wrapper_class = join( ' ', $_us_wrapper_class );
				
				echo '<div class="item ud-data ' . $_us_wrapper_class . ' ' . $_us_status_classes . '">';
				
				$__main_image = FALSE;
				
				if ( ! empty( $_ud_image_props ) ) {
					
					echo '<div class="item ud-images-wrapper">';
					
					foreach ( $_ud_image_props as $_alias => $_field ) {
						
						if ( check_var( $props[ $_alias ][ 'visibility' ][ 'site' ][ 'list' ] ) AND isset( $user_submit[ 't_data' ][ $_alias ] ) AND ! empty( $user_submit[ 't_data' ][ $_alias ] ) ) {
							
							//echo '<pre>' . print_r( $user_submit, TRUE ) . '</pre>';
							
							if ( ! $__main_image ) {
								
								$__main_image = get_url( $user_submit[ 't_data' ][ $_alias . '_thumb_default' ] );
								
							}
							
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
					
					echo '</div>';
					
				}
				
				if ( ! empty( $_ud_event_datetime_props ) ) {
					
					echo '<div class="item ud-event-datetimes-wrapper"' . ( $__main_image ? ' style="background-image:url(\'' . $__main_image . '\');"' : '' ) . '>';
					
					foreach ( $_ud_event_datetime_props as $_alias => $_field ) {
						
						if ( check_var( $props[ $_alias ][ 'visibility' ][ 'site' ][ 'list' ] ) AND isset( $user_submit[ 't_data' ][ $_alias ] ) AND ! empty( $user_submit[ 't_data' ][ $_alias ] ) ) {
							
							if ( $_field AND isset( $props[ $_alias ] ) ) {
								
								require( 'event_datetimes.php' );
								
							}
							
						}
						
					}
					
					echo '</div>';
					
				}
				
				echo '<div class="item ud-titles-wrapper">';
				
				foreach ( $_ud_title_props as $_alias => $_field ) {
					
					if ( check_var( $props[ $_alias ][ 'visibility' ][ 'site' ][ 'list' ] ) AND isset( $user_submit[ 't_data' ][ $_alias ] ) AND ! empty( $user_submit[ 't_data' ][ $_alias ] ) ) {
						
						if ( $_field AND isset( $props[ $_alias ] ) ) {
							
							require( 'titles.php' );
							
						}
						
					}
					
				}
				
				echo '</div>';
				
				echo '<div class="item ud-contents-wrapper">';
				
				foreach ( $_ud_content_props as $_alias => $_field ) {
					
					if ( check_var( $props[ $_alias ][ 'visibility' ][ 'site' ][ 'list' ] ) AND isset( $user_submit[ 't_data' ][ $_alias ] ) AND ! empty( $user_submit[ 't_data' ][ $_alias ] ) ) {
						
						if ( $_field AND isset( $props[ $_alias ] ) ) {
							
							require( 'contents.php' );
							
						}
						
					}
					
				}
				
				echo '</div>';
				
				echo '<div class="item ud-other-infos-wrapper">';
				
				echo '<table class="">';
				
				foreach ( $_ud_other_info_props as $_alias => $_field ) {
					
					if ( check_var( $props[ $_alias ][ 'visibility' ][ 'site' ][ 'list' ] ) AND isset( $user_submit[ 't_data' ][ $_alias ] ) AND ! empty( $user_submit[ 't_data' ][ $_alias ] ) ) {
						
						if ( $_field AND isset( $props[ $_alias ] ) ) {
							
							require( 'other_info.php' );
							
						}
						
					}
					
				}
				
				echo '</table>';
				
				echo '</div>';
				
				echo '<div class="item ud-status-wrapper">';
				
				foreach ( $_ud_status_props as $_alias => $_field ) {
					
					if ( check_var( $props[ $_alias ][ 'visibility' ][ 'site' ][ 'list' ] ) AND isset( $user_submit[ 't_data' ][ $_alias ] ) AND ! empty( $user_submit[ 't_data' ][ $_alias ] ) ) {
						
						if ( $_field AND isset( $props[ $_alias ] ) ) {
							
							require( 'status.php' );
							
						}
						
					}
					
				}
				
				echo '<div class="clear"></div>';
				
				echo '</div>';
				
				/* ---------------------------------------------------------------------------
				* ---------------------------------------------------------------------------
				* Read more
				* ---------------------------------------------------------------------------
				*/
				
				if ( file_exists( $_path . 'readmore.php' ) ) {
					
					require( $_path . 'readmore.php' );
					
				}
				
				echo '</div>';
				
				// ---------------------------
				
			}
			
		}
		
		echo '</div>';
		
	} else {
		
		$_result_string = '';
		
		if ( $this->input->post( 'users_submits_search', TRUE ) ) {
			
			if ( check_var( $params[ 'ud_data_no_search_result_str' ] ) ) {
				
				$_result_string = lang( $params[ 'ud_data_no_search_result_str' ] );
				
			} else {
				
				$_result_string = lang( 'ud_data_no_search_result_str_value' );
				
			}
			
		} else if ( check_var( $params[ 'show_default_results' ] ) ) {
			
			if ( check_var( $params[ 'ud_data_no_result_str' ] ) ) {
				
				$_result_string = lang( $params[ 'ud_data_no_result_str' ] );
				
			} else {
				
				$_result_string = lang( 'ud_data_no_result_str_value' );
				
			}
			
		} else if ( check_var( $params[ 'use_search' ] ) ) {
			
			$_result_string = lang( 'ud_data_list_init_search_str' );
			
		} ?>
		
		<h4 class="title">
		
			<div class="users-submits-description-no-search-results">
				
				<?= $_result_string; ?>
				
			</div>
			
		</h4>
		
	<?php } ?>
	
</div>
	