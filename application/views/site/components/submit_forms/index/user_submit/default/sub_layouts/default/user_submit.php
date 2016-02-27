<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' ); ?>

<div id="users-submits-search-results-wrapper" class="user-submit-wrapper results ud-data-wrapper">
	
	<?php if ( check_var( $user_submit ) ) { ?>
		
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
		
		echo '<div class="items ud-data-items">';
		
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
				
				if ( file_exists( $_path . 'props_presentation' . DS . $_prop[ 'field_type' ] . '.php' ) ) {
					
					require( $_path . 'props_presentation' . DS . $_prop[ 'field_type' ] . '.php' );
					
				}
				
			}
			
			if ( ! empty( $prop_value ) AND check_var( $_prop[ 'advanced_options' ][ 'prop_is_ud_image' ] ) ) {
				
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
					
					if ( check_var( $user_submit[ 't_data' ][ $_alias ] ) ) {
						
						$_us_wrapper_class[] = 'has-ud-image';
						
						$_ud_image_props[ $_alias ][ 'label' ] = lang( $props[ $_alias ][ 'presentation_label' ] );
						$_ud_image_props[ $_alias ][ 'value' ] = $user_submit[ 't_data' ][ $_alias ];
						
					}
					
				}
				
			}
			
			// title
			
			if ( check_var( $submit_form[ 'ud_title_prop' ] ) ) {
				
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
				
			}
			
			// event datetime
			
			if ( check_var( $submit_form[ 'ud_event_datetime_prop' ] ) ) {
				
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
					
					if ( isset( $user_submit[ 't_data' ][ $_alias ] ) ) {
						
						$_us_wrapper_class[] = 'has-ud-event-datetime';
						
						$_ud_event_datetime_props[ $_alias ][ 'label' ] = lang( $props[ $_alias ][ 'presentation_label' ] );
						$_ud_event_datetime_props[ $_alias ][ 'value' ] = $user_submit[ 't_data' ][ $_alias ];
						
					}
					
				}
				
			}
			
			// content
			
			if ( check_var( $submit_form[ 'ud_content_prop' ] ) ) {
				
				$_us_wrapper_class[] = 'has-ud-content';
				
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
				
				if ( check_var( $submit_form[ 'ud_content_prop' ] ) ) {
					
					foreach ( $submit_form[ 'ud_content_prop' ] as $_alias => $_field ) {
						
						if ( isset( $user_submit[ 't_data' ][ $_alias ] ) ) {
							
							$_ud_content_props[ $_alias ][ 'label' ] = lang( $props[ $_alias ][ 'presentation_label' ] );
							$_ud_content_props[ $_alias ][ 'value' ] = $user_submit[ 't_data' ][ $_alias ];
							
						}
						
					}
					
				}
				
			}
			
			// other info
			
			if ( check_var( $submit_form[ 'ud_other_info_prop' ] ) ) {
				
				$_us_wrapper_class[] = 'has-ud-other_info';
				
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
						
						if ( isset( $user_submit[ 't_data' ][ $_alias ] ) ) {
							
							$_ud_other_info_props[ $_alias ][ 'label' ] = isset( $props[ $_alias ][ 'presentation_label' ] ) ? lang( $props[ $_alias ][ 'presentation_label' ] ) : '';
							$_ud_other_info_props[ $_alias ][ 'value' ] = $user_submit[ 't_data' ][ $_alias ];
							
						}
						
					}
					
				}
				
			}
			
			// status
			
			if ( check_var( $submit_form[ 'ud_status_prop' ] ) ) {
				
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
				
				if ( check_var( $submit_form[ 'ud_status_prop' ] ) ) {
					
					foreach ( $submit_form[ 'ud_status_prop' ] as $_alias => $_field ) {
						
						if ( isset( $user_submit[ 't_data' ][ $_alias ] ) ) {
							
							$_ud_status_props[ $_alias ][ 'label' ] = lang( $props[ $_alias ][ 'presentation_label' ] );
							$_ud_status_props[ $_alias ][ 'value' ] = $user_submit[ 't_data' ][ $_alias ];
							
						}
						
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
			
			echo '<div class="item ud-event-datetimes-wrapper">';
			
			foreach ( $_ud_event_datetime_props as $_alias => $_field ) {
				
				if ( check_var( $user_submit[ 't_data' ][ $_alias ] ) ) {
					
					if ( $_field AND isset( $props[ $_alias ] ) ) {
						
						require( 'event_datetimes.php' );
						
					}
					
				}
				
			}
			
			echo '</div>';
			
			echo '<div class="item ud-images-wrapper">';
			
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
			
			echo '</div>';
			
			echo '<div class="item ud-titles-wrapper">';
			
			foreach ( $_ud_title_props as $_alias => $_field ) {
				
				if ( check_var( $user_submit[ 't_data' ][ $_alias ] ) ) {
					
					if ( $_field AND isset( $props[ $_alias ] ) ) {
						
						require( 'titles.php' );
						
					}
					
				}
				
			}
			
			echo '</div>';
			
			echo '<div class="item ud-contents-wrapper">';
			
			foreach ( $_ud_content_props as $_alias => $_field ) {
				
				if ( check_var( $user_submit[ 't_data' ][ $_alias ] ) ) {
					
					if ( $_field AND isset( $props[ $_alias ] ) ) {
						
						require( 'contents.php' );
						
					}
					
				}
				
			}
			
			echo '</div>';
			
			echo '<div class="item ud-other-infos-wrapper">';
			
			echo '<table class="ud-other-infos-table">';
			
			foreach ( $_ud_other_info_props as $_alias => $_field ) {
				
				if ( check_var( $user_submit[ 't_data' ][ $_alias ] ) ) {
					
					if ( $_field AND isset( $props[ $_alias ] ) ) {
						
						require( 'other_info.php' );
						
					}
					
				}
				
			}
			
			echo '</table>';
			
			echo '</div>';
			
			echo '<div class="item ud-status-wrapper">';
			
			foreach ( $_ud_status_props as $_alias => $_field ) {
				
				if ( check_var( $user_submit[ 't_data' ][ $_alias ] ) ) {
					
					if ( $_field AND isset( $props[ $_alias ] ) ) {
						
						require( 'status.php' );
						
					}
					
				}
				
			}
			
			echo '</div>';
			
			echo '</div>';
			
			// ---------------------------
			
		}
		
	} ?>
	
</div>
	