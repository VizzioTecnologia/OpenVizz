<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
	
	$props = & $submit_form[ 'fields' ];
	
	$ud_data_list = & $users_submits;
	
	$props_to_show = & $params[ 'props_to_show_site_list' ];
	
	$_is_presentation = FALSE;
	
	$_ud_image_props =
	$_ud_title_props =
	$_ud_content_props =
	$_ud_other_info_props =
	$_ud_status_props =
	$_ud_event_datetime_props = array();
	
	if (
		
		isset( $params[ 'ud_image_prop' ] ) OR
		isset( $params[ 'ud_title_prop' ] ) OR
		isset( $params[ 'ud_content_prop' ] ) OR
		isset( $params[ 'ud_other_info_prop' ] ) OR
		isset( $params[ 'ud_status_prop' ] )
		
	) {
		
		$_is_presentation = TRUE;
		
	}
	
	$wrapper_class = array(
		
		'users-submits-wrapper',
		'results',
		'ud-data-list',
		
	);
	
	if ( check_var( $params[ 'ud_data_list_max_columns' ] ) ) {
		
		$wrapper_class[] = 'columns-' . $params[ 'ud_data_list_max_columns' ];
		
	}
	
	if ( check_var( $params[ 'ud_image_prop' ] ) ) {
		
		foreach ( $params[ 'ud_image_prop' ] as $_alias => & $_value ) {
			
			if ( check_var( $props[ $_alias ][ 'visibility' ][ 'site' ][ 'list' ] ) ) {
				
				$wrapper_class[] = 'has-ud-image-prop';
				break;
				
			}
			
		}
		
	}
	
// 	echo '<pre>' . print_r( $submit_form, TRUE ) . '</pre>';exit;
	
	if ( check_var( $params[ 'ud_title_prop' ] ) ) {
		
		foreach ( $params[ 'ud_title_prop' ] as $_alias => & $_value ) {
			
			if ( check_var( $props[ $_alias ][ 'visibility' ][ 'site' ][ 'list' ] ) ) {
				
				$wrapper_class[] = 'has-ud-title-prop';
				break;
				
			}
			
		}
		
	}
	
	if ( check_var( $params[ 'ud_content_prop' ] ) ) {
		
		foreach ( $params[ 'ud_content_prop' ] as $_alias => & $_value ) {
			
			if ( check_var( $props[ $_alias ][ 'visibility' ][ 'site' ][ 'list' ] ) ) {
				
				$wrapper_class[] = 'has-content-prop';
				break;
				
			}
			
		}
		
	}
	
	if ( check_var( $params[ 'ud_other_info_prop' ] ) ) {
		
		foreach ( $params[ 'ud_other_info_prop' ] as $_alias => & $_value ) {
			
			if ( check_var( $props[ $_alias ][ 'visibility' ][ 'site' ][ 'list' ] ) ) {
				
				$wrapper_class[] = 'has-other-info-prop';
				break;
				
			}
			
		}
		
	}
	
	if ( check_var( $params[ 'ud_status_prop' ] ) ) {
		
		foreach ( $params[ 'ud_status_prop' ] as $_alias => & $_value ) {
			
			if ( check_var( $props[ $_alias ][ 'visibility' ][ 'site' ][ 'list' ] ) ) {
				
				$wrapper_class[] = 'has-status-prop';
				break;
				
			}
			
		}
		
	}
	
	if ( check_var( $params[ 'ud_event_datetime_prop' ] ) ) {
		
		foreach ( $params[ 'ud_event_datetime_prop' ] as $_alias => & $_value ) {
			
			if ( check_var( $props[ $_alias ][ 'visibility' ][ 'site' ][ 'list' ] ) ) {
				
				$wrapper_class[] = 'has-event-datetime-prop';
				break;
				
			}
			
		}
		
	}
	
	$wrapper_class = join( ' ', $wrapper_class );
	
?>

<div id="ud-d-search-results-wrapper" class="<?= check_var( $wrapper_class ) ? $wrapper_class : ''; ?>">
	
	<?php if ( check_var( $ud_data_list ) ) { ?>
		
		<?php if ( check_var( $params[ 'show_results_count' ] ) ) { ?>
		
		<div class="users-submits-search-results-title-wrapper ud-data-list-count">
			
			<h3 class="users-submits-search-results-title">
				
				<?php
					
					if ( $ud_data_list_total_results > 1 ) {
						
						if ( check_var( $params[ 'users_submits_search_results_string' ] ) ) {
							
							echo sprintf( $params[ 'users_submits_search_results_string' ], '<span class="users-submits-search-count">' . $ud_data_list_total_results . '</span>' );
							
						}
						else {
							
							echo sprintf( lang( 'users_submits_search_results_string' ), '<span class="users-submits-search-count">' . $ud_data_list_total_results . '</span>' );
							
						}
						
					}
					else {
						
						if ( check_var( $params[ 'users_submits_search_single_result_string' ] ) ) {
							
							echo sprintf( $params[ 'users_submits_search_single_result_string' ], '<span class="users-submits-search-count">' . $ud_data_list_total_results . '</span>' );
							
						}
						else {
							
							echo sprintf( lang( 'users_submits_search_single_result_string' ), '<span class="users-submits-search-count">' . $ud_data_list_total_results . '</span>' );
							
						}
						
					}
					
				?>
				
			</h3>
			
		</div>
		
		<?php } ?>
		
		<?php
		
// 		echo '<pre>' . print_r( $ud_data_list, TRUE ) . '</pre>';
		
		echo '<div class="items ud-data-items">';
		
		foreach ( $ud_data_list as $key => $ud_data ) {
			
			$this->ud_api->parse_ud_data( $ud_data, $props_to_show );
			
			$_us_wrapper_class = array();
			
			// ----------------------------
			// translating values
			
			foreach ( $ud_data[ 'parsed_data' ][ 'full' ] as $_pk => & $parsed_array ) {
				
				$prop_value = & $parsed_array[ 'value' ];
				
				$_prop = NULL;
				
				if ( isset( $props[ $_pk ] ) ) {
					
					$_prop = $props[ $_pk ];
					
				}
				
				if ( check_var( $_prop[ 'field_type' ] ) ){
					
					// date
					if ( $_prop[ 'field_type' ] == 'date' ){
						
						if ( check_var( $_prop[ 'sf_date_field_relative_datetime' ] ) AND date_is_valid( $prop_value ) ) {
							
							$date = new DateTime( $prop_value );
							$now = new DateTime( date( 'Y-m-d', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) ) );
							$yesterday = new DateTime( date( 'Y-m-d', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) ) );
							$yesterday->sub( date_interval_create_from_date_string( '1 day' ) );
							$tomorrow = new DateTime( date( 'Y-m-d', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) ) );
							$tomorrow->add( date_interval_create_from_date_string( '1 day' ) );
							
							if ( $date == $now ) {
								
								$_us_wrapper_class[] = 'has-ud-event-datetime-is-today';
								
								$_us_wrapper_class[] = 'is-today';
								
							}
							else if ( $date == $yesterday ) {
								
								$_us_wrapper_class[] = 'has-ud-event-datetime-is-yesterday';
								
								$_us_wrapper_class[] = 'is-yesterday';
								
							}
							else if ( $date == $tomorrow ) {
								
								$_us_wrapper_class[] = 'has-ud-event-datetime-is-tomorrow';
								
								$_us_wrapper_class[] = 'is-tomorrow';
								
							}
							
						}
						
					}
					
					if ( check_var( $prop_value ) AND check_var( $_prop[ 'advanced_options' ][ 'prop_is_ud_content' ] ) ) {
						
						$content_word_limit = check_var( $params[ 'users_submit_content_word_limit' ] ) ? $params[ 'users_submit_content_word_limit' ] : 120;
						$word_limit_str = '...';
						
						if ( $content_word_limit > 0 AND strlen( $prop_value ) > $content_word_limit ) {
							
							$prop_value = word_limiter( htmlspecialchars_decode( $prop_value ), $content_word_limit, $word_limit_str );
							
						}
						else {
							
							$prop_value = word_limiter( htmlspecialchars_decode( $prop_value ) );
							
						}
						
					}
					
				}
				
				$_us_wrapper_class[] = 'ud-data-item-value-' . url_title( $prop_value, '-', TRUE );
				
			}
			
			//echo '---------------------<pre>' . print_r( $ud_data, TRUE ) . '</pre>'; exit;
			
			// translating values
			// ----------------------------
			
			if ( $_is_presentation ) {
				
				$property_metadata_types = array(
					
					'ud_image_prop',
					'ud_title_prop',
					'ud_event_datetime_prop',
					'ud_content_prop',
					'ud_other_info_prop',
					'ud_status_prop',
					'ud_image_prop',
					
				);
				
				foreach ( $property_metadata_types as $property_metadata_type ) {
					
					${ '_' . $property_metadata_type . 's' } = array();
					
					if ( check_var( $params[ $property_metadata_type ] ) ) {
						
						$__class = FALSE;
						
						if ( check_var( $params[ $property_metadata_type ] ) ) {
							
							if ( check_var( $params[ $property_metadata_type ] ) AND in_array( 'id', $params[ $property_metadata_type ] ) ) {
								
								${ '_' . $property_metadata_type . 's' }[ 'id' ][ 'label' ] = $ud_data[ 'parsed_data' ][ 'full' ][ 'id' ][ 'label' ];
								${ '_' . $property_metadata_type . 's' }[ 'id' ][ 'value' ] = $ud_data[ 'parsed_data' ][ 'full' ][ 'id' ][ 'value' ];
								
							}
							
							if ( check_var( $params[ $property_metadata_type ] ) AND in_array( 'submit_datetime', $params[ $property_metadata_type ] ) ) {
								
								${ '_' . $property_metadata_type . 's' }[ 'submit_datetime' ][ 'label' ] = $ud_data[ 'parsed_data' ][ 'full' ][ 'id' ][ 'submit_datetime' ][ 'label' ];
								${ '_' . $property_metadata_type . 's' }[ 'submit_datetime' ][ 'value' ] = $ud_data[ 'parsed_data' ][ 'full' ][ 'id' ][ 'submit_datetime' ][ 'value' ];
								
							}
							
							if ( check_var( $params[ $property_metadata_type ] ) AND in_array( 'mod_datetime', $params[ $property_metadata_type ] ) ) {
								
								${ '_' . $property_metadata_type . 's' }[ 'mod_datetime' ][ 'label' ] = $ud_data[ 'parsed_data' ][ 'full' ][ 'id' ][ 'mod_datetime' ][ 'label' ];
								${ '_' . $property_metadata_type . 's' }[ 'mod_datetime' ][ 'value' ] = $ud_data[ 'parsed_data' ][ 'full' ][ 'id' ][ 'mod_datetime' ][ 'value' ];
								
							}
							
						}
						
						foreach ( $params[ $property_metadata_type ] as $_alias => $_field ) {
							
							if ( check_var( $props[ $_alias ][ 'visibility' ][ 'site' ][ 'list' ] ) AND check_var( $ud_data[ 'parsed_data' ][ 'full' ][ $_alias ][ 'value' ], TRUE ) ) {
								
								if ( ! $__class ) $__class = 'has-ud-image';
								
								${ '_' . $property_metadata_type . 's' }[ $_alias ][ 'label' ] = $ud_data[ 'parsed_data' ][ 'full' ][ $_alias ][ 'label' ];
								${ '_' . $property_metadata_type . 's' }[ $_alias ][ 'value' ] = $ud_data[ 'parsed_data' ][ 'full' ][ $_alias ][ 'value' ];
								
							}
							
						}
						
						if ( $__class ) $_us_wrapper_class[] = $__class;
						
					}
					
				}
				
				$_us_status = array();
				$_us_status_classes = '';
				$_us_has_status = FALSE;
				
				if ( check_var( $params[ 'ud_status_prop' ] ) ) {
					
					foreach( $submit_form[ 'fields' ] as $status_field ) {
						
						if ( isset( $params[ 'ud_status_prop' ][ $status_field[ 'alias' ] ] ) ) {
							
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
									
									if ( check_var( $status_field[ 'advanced_options' ][ $_item ] ) AND check_var( $ud_data[ 'parsed_data' ][ 'full' ][ $status_field[ 'alias' ] ] ) AND $ud_data[ 'parsed_data' ][ 'full' ][ $status_field[ 'alias' ] ] == $status_field[ 'advanced_options' ][ $_item ] ) {
										
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
						
						if ( check_var( $props[ $_alias ][ 'visibility' ][ 'site' ][ 'list' ] ) AND isset( $ud_data[ 'parsed_data' ][ 'full' ][ $_alias ] ) AND ! empty( $ud_data[ 'parsed_data' ][ 'full' ][ $_alias ] ) ) {
							
							//echo '<pre>' . print_r( $ud_data, TRUE ) . '</pre>';
							
							if ( ! $__main_image ) {
								
								$__main_image = get_url( $ud_data[ 'parsed_data' ][ 'full' ][ $_alias . '_thumb_default' ] );
								
							}
							
							$thumb_params = array( 
								
								'wrapper_class' => 'us-image-wrapper',
								'src' => get_url( $ud_data[ 'parsed_data' ][ 'full' ][ $_alias . '_thumb_default' ] ),
								'href' => get_url( $ud_data[ 'parsed_data' ][ 'full' ][ $_alias ] ),
								'rel' => 'us-thumb',
								'title' => $ud_data[ 'parsed_data' ][ 'full' ][ $_alias ],
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
						
						if ( check_var( $props[ $_alias ][ 'visibility' ][ 'site' ][ 'list' ] ) AND isset( $ud_data[ 'parsed_data' ][ 'full' ][ $_alias ] ) AND ! empty( $ud_data[ 'parsed_data' ][ 'full' ][ $_alias ] ) ) {
							
							if ( $_field AND isset( $props[ $_alias ] ) ) {
								
								require( 'event_datetimes.php' );
								
							}
							
						}
						
					}
					
					echo '</div>';
					
				}
				
				if ( ! empty( $_ud_title_props ) ) {
					
					echo '<div class="item ud-titles-wrapper">';
					
					foreach ( $_ud_title_props as $_alias => $_field ) {
						
						if ( check_var( $props[ $_alias ][ 'visibility' ][ 'site' ][ 'list' ] ) AND isset( $ud_data[ 'parsed_data' ][ 'full' ][ $_alias ] ) AND ! empty( $ud_data[ 'parsed_data' ][ 'full' ][ $_alias ] ) ) {
							
							if ( $_field AND isset( $props[ $_alias ] ) ) {
								
								require( 'titles.php' );
								
							}
							
						}
						
					}
					
					echo '</div>';
					
				}
				
				if ( ! empty( $_ud_content_props ) ) {
					
					echo '<div class="item ud-contents-wrapper">';
					
					foreach ( $_ud_content_props as $_alias => $_field ) {
						
						if ( check_var( $props[ $_alias ][ 'visibility' ][ 'site' ][ 'list' ] ) AND isset( $ud_data[ 'parsed_data' ][ 'full' ][ $_alias ] ) AND ! empty( $ud_data[ 'parsed_data' ][ 'full' ][ $_alias ] ) ) {
							
							if ( $_field AND isset( $props[ $_alias ] ) ) {
								
								require( 'contents.php' );
								
							}
							
						}
						
					}
					
					echo '</div>';
					
				}
				
// 				echo '<pre>' . print_r( $_ud_other_info_props, TRUE ) . '</pre>';exit;
				
				if ( ! empty( $_ud_other_info_props ) ) {
					
					echo '<div class="item ud-other-infos-wrapper">';
					
					echo '<table class="">';
					
					foreach ( $_ud_other_info_props as $_alias => $_field ) {
						
						if ( check_var( $props[ $_alias ][ 'visibility' ][ 'site' ][ 'list' ] ) AND isset( $ud_data[ 'parsed_data' ][ 'full' ][ $_alias ] ) AND ! empty( $ud_data[ 'parsed_data' ][ 'full' ][ $_alias ] ) ) {
							
							if ( $_field AND isset( $props[ $_alias ] ) ) {
								
								require( 'other_info.php' );
								
							}
							
						}
						
					}
					
					echo '</table>';
					
					echo '</div>';
					
				}
				
				if ( ! empty( $_ud_status_props ) ) {
					
					echo '<div class="item ud-status-wrapper">';
					
					foreach ( $_ud_status_props as $_alias => $_field ) {
						
						if ( check_var( $props[ $_alias ][ 'visibility' ][ 'site' ][ 'list' ] ) AND isset( $ud_data[ 'parsed_data' ][ 'full' ][ $_alias ] ) AND ! empty( $ud_data[ 'parsed_data' ][ 'full' ][ $_alias ] ) ) {
							
							if ( $_field AND isset( $props[ $_alias ] ) ) {
								
								require( 'status.php' );
								
							}
							
						}
						
					}
					
					echo '<div class="clear"></div>';
					
					echo '</div>';
					
				}
				
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
	
