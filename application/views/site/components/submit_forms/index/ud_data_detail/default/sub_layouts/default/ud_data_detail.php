<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
	
?>

<div class="<?= $wrapper_class; ?>">
	
	<?php if ( check_var( $ud_data ) ) {
		
		echo '<div class="ud-d-detail">';
		
		reset( $ud_data[ 'parsed_data' ][ 'full' ] );
		
		while ( list( $_pk, $parsed_array ) = each( $ud_data[ 'parsed_data' ][ 'full' ] ) ) {
			
			$prop_value = & $ud_data[ 'parsed_data' ][ 'full' ][ $_pk ][ 'value' ];
			
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
							
							$_ud_data_wrapper_class[] = 'has-ud-event-datetime-is-today';
							
							$_ud_data_wrapper_class[] = 'is-today';
							
						}
						else if ( $date == $yesterday ) {
							
							$_ud_data_wrapper_class[] = 'has-ud-event-datetime-is-yesterday';
							
							$_ud_data_wrapper_class[] = 'is-yesterday';
							
						}
						else if ( $date == $tomorrow ) {
							
							$_ud_data_wrapper_class[] = 'has-ud-event-datetime-is-tomorrow';
							
							$_ud_data_wrapper_class[] = 'is-tomorrow';
							
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
			
			$_ud_data_wrapper_class[] = 'ud-data-item-' . $_pk . '-value-' . word_limiter( str_replace( '_', '-', url_title( $prop_value, '-', TRUE ) ), 20, '' );
			
		}
		
		// ----------------------------
		
		if ( $_is_presentation ) {
			
			$_ud_status = array();
			$_ud_status_classes = '';
			$_ud_has_status = FALSE;
			
			if ( check_var( $params[ 'ud_status_prop' ] ) ) {
				
				foreach( $params[ 'ud_status_prop' ] as $alias => $v ) {
					
					$status_field = $props[ $alias ];
					
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
							
							if ( check_var( $status_field[ 'advanced_options' ][ $_item ] ) AND check_var( $ud_data[ 'data' ][ $alias ], TRUE ) AND $ud_data[ 'data' ][ $alias ] == $status_field[ 'advanced_options' ][ $_item ] ) {
								
								if ( $_item == 'prop_is_ud_status_active' ) {
									
									$_ud_status[ 'active' ] = 'status-active';
									
								}
								else if ( $_item == 'prop_is_ud_status_inactive' ) {
									
									$_ud_status[ 'inactive' ] = 'status-inactive';
									
								}
								else if ( $_item == 'prop_is_ud_status_enabled' ) {
									
									$_ud_status[ 'enabled' ] = 'status-enabled';
									
								}
								else if ( $_item == 'prop_is_ud_status_disabled' ) {
									
									$_ud_status[ 'disabled' ] = 'status-disabled';
									
								}
								else if ( $_item == 'prop_is_ud_status_canceled' ) {
									
									$_ud_status[ 'disabled' ] = 'status-disabled';
									
								}
								else if ( $_item == 'prop_is_ud_status_postponed' ) {
									
									$_ud_status[ 'postponed' ] = 'status-postponed';
									
								}
								else if ( $_item == 'prop_is_ud_status_archived' ) {
									
									$_ud_status[ 'archived' ] = 'status-archived';
									
								}
								else if ( $_item == 'prop_is_ud_status_published' ) {
									
									$_ud_status[ 'published' ] = 'status-published';
									
								}
								else if ( $_item == 'prop_is_ud_status_unpublished' ) {
									
									$_ud_status[ 'unpublished' ] = 'status-unpublished';
									
								}
								else if ( $_item == 'prop_is_ud_status_scheduled' ) {
									
									$_ud_status[ 'scheduled' ] = 'status-scheduled';
									
								}
								
								$_ud_has_status = TRUE;
								
							}
							
						};
						
					}
					
				}
				
			}
			
			if ( $_ud_has_status ) {
				
				$_ud_status_classes = join( $_ud_status, ' ' );
				
			}
			
			$_ud_data_wrapper_class = join( ' ', $_ud_data_wrapper_class );
			
			echo '<div class="ud-data ud-d-detail-layout-' . $params[ 'ud_d_detail_layout_site' ] . ' ' . $_ud_data_wrapper_class . ' ' . $_ud_status_classes . '">';
			
			reset( $property_presentation_types );
			
			while ( list( $k, $v ) = each( $property_presentation_types ) ) {
				
				if ( ! empty( ${ '_ud_' . $v . '_props' } ) ) {
					
					if ( $v != 'title' OR ( $v == 'title' AND ! $params[ 'ud_d_detail_page_content_title_from_metadata' ] ) ) {
						
						echo '<div class="item ud-' . str_replace( '_', '-', rtrim( url_title( $v, '-', TRUE ), 's' ) ) . 's-wrapper"' . ( $__main_image ? ' style="background-image:url(\'' . htmlspecialchars( $__main_image ) . '\');"' : '' ) . '>';
						
						if ( $__main_image AND check_var( $params[ 'ud_d_main_image_on_title' ] ) ) {
							
							$this->voutput->append_head_stylesheet_declaration( 'ud_data_' . $ud_data[ 'id' ] . $unique_hash, '
								
								#submit-form-user-submit-detail-' . $unique_hash . '.ud-image-on-title > .page-title {
									
									background-image: url(\'' . $__main_image . '\');
									
								}
								
							' );
							
						}
						
						$__item_count = 1;
						
						foreach ( ${ '_ud_' . $v . '_props' } as $_alias => $_field ) {
							
							if ( $_field AND ( isset( $props[ $_alias ] ) ) OR in_array( $_alias, array( 'id', 'submit_datetime', 'mod_datetime' ) ) ) {
								
								require( rtrim( $v, 's' ) . 's.php' );
								
							}
							
							$__item_count++;
							
						}
						
						echo '</div>';
						
					}
					
				}
				
			}
			
			echo '</div>';
			
			// ---------------------------
			
		}
		
		echo '</div>';
		
	} else { ?>
		
		<div class="ud-d-detail-ud-data-desc-wrapper no-ud-data">
		
			<span class="ud-d-detail-no-ud-data-desc">
				
				<?= lang( 'ud_data_no_ud_data' ); ?>
				
			</span>
			
		</div>
		
	<?php } ?>
	
</div>
	
